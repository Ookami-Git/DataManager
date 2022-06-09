<?php

namespace App\Controllers;
use App\Models;
use App\Libraries\AuthLdap;

class LoginController extends BaseController
{
    public function index() {
        helper(['form']);
        //echo view('login');

        $this->pageHeader(false);
        echo view("login",array('title'=>$this->title));
    } 
  
    public function signin() {
        $session = session();
        $model = new Models\DbUsers();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        
        $data = $model->where('username', $username)->first();
        
        if($data){
            $pass = $data['password'];
            switch ($data['connection']) {
                case "local":
                    $pwd_verify = password_verify($password, $pass);
                    break;
                case "ldap":
                    $modelParameters = new Models\DbParameters();
                    $ldap = $modelParameters->where('name', 'ldapEnable')->first();
                    if (!$ldap['value']) {
                        $session->setFlashdata('msg', "L'authentification LDAP n'est pas activé dans les paramètres de l'application.<br>Utilisez un compte local ou contactez votre administrateur.");
                        return redirect()->to(base_url().'/login');
                    }
                    if (!extension_loaded('ldap')) {
                        $session->setFlashdata('msg', "Le module PHP LDAP n'est pas installé ou configuré sur ce serveur.<br>Utilisez un compte local ou contactez votre administrateur.");
                        return redirect()->to(base_url().'/login');
                    }
                    $authLdap = new AuthLdap();
                    if (is_object($authLdap) && method_exists($authLdap, 'authenticate')) {
                        $ldapConfig=array(
                            "baseDn"=>"dc=example,dc=com",
                            "ldapDomain"=>"ldap.forumsys.com",
                            "usrTls"=>false,
                            "tcpPort"=>389
                        );
                        $authLdap->setConfig($ldapConfig);
                        $authenticatedUserData = $authLdap->authenticate(
                                                    trim($username),
                                                    trim($password)
                                                );
                        if (!empty($authenticatedUserData))
                        {
                            $pwd_verify = true;
                        }
                        else {
                            $session->setFlashdata('msg', 'Compte ou mot de passe incorrect.');
                            return redirect()->to(base_url().'/login');
                        }
                    }
                    else {
                        $session->setFlashdata('msg', "Erreur module LDAP.<br>Utilisez un compte local ou contactez votre administrateur.");
                        return redirect()->to(base_url().'/login');
                    }
                    break;
            }

            if($pwd_verify){
                //GET USER GROUPS
                $modelrole = new Models\DbRoles();
                $roles=array_flatten_with_dots($modelrole->select('groupname')->where('username', $username)->findall());
                $ses_data = [
                    'username' => $data['username'],
                    'isSignedIn' => TRUE,
                    'roles' => $roles
                ];

                $session->set($ses_data);
                //return redirect()->to(base_url());
                return redirect()->to(session()->get('previousUrl')??"/");
            }else{
                $session->setFlashdata('msg', 'Mot de passe incorrect.');
                return redirect()->to(base_url().'/login');
            }
        }else{
            $session->setFlashdata('msg', "Nom d'utilisateur incorrect.");
            return redirect()->to(base_url().'/login');
        }
    }

    public function logout() {
        $session = session();
        $session->destroy();
        return redirect()->to(base_url().'/login'); //to redirect back to "index.php" after logging out
    }
}