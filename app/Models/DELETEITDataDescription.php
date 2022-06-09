<?php

namespace App\Models;
use CodeIgniter\Model;

class DELETEITDataDescription extends Model {
    protected $table = "data_manager";

    public function getSource ($name) {
        $source = json_decode($this->where(['name' => $name])->first()['source'],true);
        return $source['sources']??array();
    }

    public function putSource ($name,$value) {
        $sql = "UPDATE data_manager SET source = :source: WHERE name = :name:";
        $this->query($sql, [
            'name'     => 3,
            'source' => $value
        ]);
    }

    public function getItems ($name) {
        $items = json_decode($this->where(['name' => $name])->first()['item'],true);
        return $items['items']??array();
    }

    public function putItems ($name,$value) {
    }

    public function getPresentation ($name) {
        $presentation = json_decode($this->where(['name' => $name])->first()['presentation'],true);
        return $presentation['presentation']??array();
    }

    public function putPresentation ($name,$value) {
    }

    public function createDescription($name,$source,$items,$presentation) {
        
    }
}