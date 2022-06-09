<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models;

class ApiUser extends ResourceController
{
    use ResponseTrait;

    public function index(){
    }

    public function update($id = null){
        $input=$this->request->getRawInput();
        if (isset($input['user'])) {
            $update=json_decode($input['user'],true);
            $model = new Models\DbUsers();
            foreach($update as $key=>$value) {
                if ($key == "password") {
                    $value = password_hash($value,PASSWORD_DEFAULT);
                }
                $data[$key]=$value;
            }
            $model->update(session()->get("username"), $data);
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
    }

    public function create($id = null) {
    }
}