<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models;
use App\Libraries\DataManipulation;

class ApiTypeFields extends ResourceController
{
    use ResponseTrait;

    public function show ($id = null) {
        $dm = new DataManipulation();
        $model = $dm->initType($id);
        $data = $model->getFormat();
        if($data){
            return $this->respond($data);
        }else{
            return $this->failNotFound('No source found');
        }
    }
}