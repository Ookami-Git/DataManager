<?php

namespace App\Models;
use CodeIgniter\Model;

class AppParameters extends Model {
    protected $table = "parameters";

    public function getParameter ($name) {
        $this->table ;
        if ($name === false) {
            return $this->findAll();
        }
        $parameter = $this->where(['name' => $name])->first()['value'];
        return $parameter;
    }
}