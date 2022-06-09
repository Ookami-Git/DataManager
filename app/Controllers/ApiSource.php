<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models;

class ApiSource extends ResourceController
{
    use ResponseTrait;

    public function show ($id = null) {
        $model = new Models\DbDataManager();
        $data = $model->where('name', $id)->first();
        if($data){
            return $this->respond($data['source']);
        }else{
            return $this->failNotFound('No source found');
        }
    }

    public function update($id = null){
        $input=$this->request->getRawInput();
        $model = new Models\DbDataManager();
        $id = $input['name'];
        $data = [
            'source'  => $input['source'],
        ];
        $model->update($id, $data);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Source updated successfully'
            ]
        ];
        return $this->respond($response);
    }
}