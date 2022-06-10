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

        $username = trim($this->request->getVar('username'));
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
                    $ldap = $modelParameters->where('name', 'ldapEnable')->first()['value'];
                    if (!$ldap) {
                        $session->setFlashdata('msg', "L'authentification LDAP n'est pas activé dans les paramètres de l'application.<br>Utilisez un compte local ou contactez votre administrateur.");
                        return redirect()->to(base_url().'/login');
                    }
                    if (!extension_loaded('ldap')) {
                        $session->setFlashdata('msg', "Le module PHP LDAP n'est pas installé ou configuré sur ce serveur.<br>Utilisez un compte local ou contactez votre administrateur.");
                        return redirect()->to(base_url().'/login');
                    }

                    $ldapDMInfo=$modelParameters->select('name, value')->where("name LIKE 'ldap%'")->findAll();
                    foreach($ldapDMInfo as $ldapParameter) {
                        $ldapConfig[$ldapParameter['name']]=$ldapParameter['value'];
                    }

                    if (!$ldapConfig['ldapCheckCert']) {
                        putenv('LDAPTLS_REQCERT=never'); //Ignore TLS Certificat
                    }

                    if ($ldapConfig['ldapSsl']) {
                        $ldapProtocole="ldaps";
                    } else {
                        $ldapProtocole="ldap";
                    }

                    $ldapconn = ldap_connect("{$ldapProtocole}://{$ldapConfig['ldapHost']}",$ldapConfig['ldapPort']);

                    if ($ldapconn) {
                        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
                        if (!$ldapConfig['ldapAnonymous']) { //Non anonymous auth LDAP
                            $ldapbind = @ldap_bind($ldapconn, $ldapConfig['ldapUser'], $ldapConfig['ldapPassword']);
                            // Vérification de l'authentification
                            if (!$ldapbind) {
                                $session->setFlashdata('msg', "Configuration des paramètres LDAP incorrecte. Contactez votre administrateur.");
                                return redirect()->to(base_url().'/login');
                            }
                        }

                        if (!empty($ldapConfig['ldapFilter'])) { //Filter -> Search DN USER

                            $sr = ldap_search($ldapconn, $ldapConfig['ldapBaseDN'], "{$ldapConfig['ldapFilter']}={$username}");

                            $info = ldap_get_entries($ldapconn, $sr);
                            if (!$info) {
                                $session->setFlashdata('msg', "Problème rencontré lors de la recherche LDAP.");
                                return redirect()->to(base_url().'/login');
                            }
                            if ($info['count'] == 0) {
                                $session->setFlashdata('msg', "Utilisateur introuvable sur le serveur LDAP.");
                                return redirect()->to(base_url().'/login');
                            }
                            $username = $info[0]['dn'];
                        }

                        $ldapUserBind = @ldap_bind($ldapconn, $username, $password);

                        if (!$ldapUserBind) {
                            $session->setFlashdata('msg', 'Mot de passe incorrect.');
                            return redirect()->to(base_url().'/login');
                        } else {
                            $pwd_verify = true;
                        }

                    } else {
                        $session->setFlashdata('msg', 'Erreur de communication avec le serveur LDAP.');
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