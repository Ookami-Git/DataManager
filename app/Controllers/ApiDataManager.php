<?php

namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models;

class ApiDataManager extends ResourceController
{
    use ResponseTrait;

    public function index(){
        $model = new Models\DbDataManager();
        $data['datamanager'] = $model->orderBy('name', 'DESC')->findAll();
        return $this->respond($data);
    }

    public function show ($id = null, $column = null) {
        if ($column == null) {
            return $this->failNotFound('Need more parameters : GET api/name/column');
        }
        $model = new Models\DbDataManager();
        $data = $model->where('name', $id)->first();
        if($data){
            return $this->respond($data[$column]);
        }else{
            return $this->failNotFound('No source found');
        }
    }

    public function update($id = null){
        $input=$this->request->getRawInput();
        $model = new Models\DbDataManager();
        $id = $input['name'];
        $data = [
            $input['type']  => $input['value'],
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

    public function delete($id = null){
        $input=$this->request->getRawInput();
        $model = new Models\DbDataManager();
        $data = $model->where('name', $id)->delete($id);
        if($data){
            $model->delete($id);
            $modelAcl = new Models\DbAcl();
            $modelAcl->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Page successfully deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No name found');
        }
    }

    public function create($id = null){
        $model = new Models\DbDataManager();
        $data = [
            'name' => $this->request->getVar('name'),
            'source'  => $this->request->getVar('source'),
            'item'  => $this->request->getVar('item'),
            'presentation'  => $this->request->getVar('presentation'),
        ];
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
              'success' => 'Data Manager description created successfully'
          ]
      ];
      return $this->respondCreated($response);
    }
}