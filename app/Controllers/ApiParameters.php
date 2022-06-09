<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models;

class ApiParameters extends ResourceController
{
    use ResponseTrait;

    public function index(){
        $model = new Models\DbParameters();
        $data['datamanager'] = $model->orderBy('name', 'DESC')->findAll();
        return $this->respond($data);
    }

    public function show ($id = null) {
        $model = new Models\DbParameters();
        $data = $model->where('name', $id)->first();
        if($data){
            return $this->respond($data['value']);
        }else{
            return $this->failNotFound('No parameters found');
        }
    }

    public function update($id = null){
        $input=$this->request->getRawInput();
        if (isset($input['parameters'])) {
            $input=json_decode($input['parameters'],true);
            if (isset($input['acl'])) {
                $model = new Models\DbAcl();
                foreach($input['acl'] as $id => $acl) {
                    $data['page'] = $id;
                    if (isset($acl['users'])) {
                        $data['users'] = json_encode($acl['users']);
                    }
                    if (isset($acl['groups'])) {
                        $data['groups'] = json_encode($acl['groups']);
                    }
                    $model->replace($data);
                }
            } elseif (isset($input['updateUser'])) {
                // Only one user but we need get username in array (key)
                foreach($input['updateUser'] as $username => $update) {
                    if (isset($update['groups'])) {
                        $model = new Models\DbRoles();
                        $data = $model->where('username', $username)->delete();
                        foreach($update['groups'] as $group) {
                            $data=array(
                                "username"      =>$username,
                                "groupname"     =>$group,
                            );
                            $model->insert($data);
                        }
                    }
                    if (isset($update['password'])) {
                        $model = new Models\DbUsers();
                        $data['password']=password_hash($update['password'],PASSWORD_DEFAULT);
                        $model->update($username, $data);
                    }
                }
            } elseif (isset($input['updateGroup'])) {
                // Only one group but we need get groupname in array (key)
                foreach($input['updateGroup'] as $groupname => $update) {
                    if (isset($update['users'])) {
                        $model = new Models\DbRoles();
                        $data = $model->where('groupname', $groupname)->delete();
                        foreach($update['users'] as $user) {
                            $data=array(
                                "username"      =>$user,
                                "groupname"     =>$groupname,
                            );
                            $model->insert($data);
                        }
                        if ($groupname == "admin") {
                            $model->replace(array('username'=>'admin','groupname'=>'admin'));
                        }
                    }
                    if (isset($update['description'])) {
                        $model = new Models\DbGroups();
                        $data['description']=$update['description'];
                        $model->update($groupname, $data);
                    }
                }
            } else {
                $model = new Models\DbParameters();
                foreach($input as $key=>$value) {
                    if ($key == "rundeck" || $key == "menu") {$value=json_encode($value);}
                    $data = [
                        'value'  => $value,
                    ];
                    $model->update($key, $data);
                }
            }
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Parameters updated successfully'
                ]
            ];
        } else {
            $response = [
                'status'   => 500,
                'error'    => "No parameters",
                'messages' => [
                    'error' => 'Parameters does not exist'
                ]
            ];
        }
        return $this->respond($response);
    }

    public function delete($id = null, $type = null){
        switch ($type) {
            case "user":
                $model = new Models\DbRoles();
                $data = $model->where('username', $id)->delete();
                $model = new Models\DbUsers();
                $data = $model->where('username', $id)->delete($id);
                if($data){
                    $model->delete($id);
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => [
                            'success' => 'User successfully deleted'
                        ]
                    ];
                    return $this->respondDeleted($response);
                }else{
                    return $this->failNotFound('No name found');
                }
                break;
            case "group":
                $model = new Models\DbRoles();
                $data = $model->where('groupname', $id)->delete();
                $model = new Models\DbGroups();
                $data = $model->where('groupname', $id)->delete($id);
                if($data){
                    $model->delete($id);
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => [
                            'success' => 'Group successfully deleted'
                        ]
                    ];
                    return $this->respondDeleted($response);
                }else{
                    return $this->failNotFound('No name found');
                }
                break;
                break;
            default:
                return $this->failNotFound('Unkown TYPE');
        }
    }

    public function create($id = null) {
        $input=$this->request->getRawInput();
        if (isset($input['parameters'])) {
            $input=json_decode($input['parameters'],true);
            if (isset($input['addUser'])) {
                $model = new Models\DbUsers();
                $data=array(
                    "username"      =>$input['addUser']['username'],
                    "connection"    =>$input['addUser']['connection'],
                );
                if (isset($input['addUser']['password'])) {
                    $data['password']=password_hash($input['addUser']['password'],PASSWORD_DEFAULT);
                }
                $model->insert($data);
                $model = new Models\DbRoles();
                foreach($input['addUser']['groups'] as $group) {
                    $data=array(
                        "username"      =>$input['addUser']['username'],
                        "groupname"     =>$group,
                    );
                    $model->insert($data);
                }
            }
            if (isset($input['addGroup'])) {
                $model = new Models\DbGroups();
                $data=array(
                    "groupname"      =>$input['addGroup']['groupname'],
                    "description"      =>$input['addGroup']['description']??null,
                );
                $model->insert($data);
            }
        }
    }
}