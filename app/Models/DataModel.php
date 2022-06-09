<?php

namespace App\Models;

class DataModel {
    protected $dataResult;
    protected $loopDataResult;
    protected $dataFormat=array();

    public function getAllData() {
        return $this->dataResult;
    }

    public function getData($dataPath) {
        helper('array');
        return dot_array_search($dataPath, $this->dataResult);
    }

    public function getFormat() {
        return json_encode($this->dataFormat);
    }

    public function setLoopSource($loopKey,$sourceParameters) {
        $this->loopDataResult=$this->dataResult;
        $this->setSource($sourceParameters);
        $this->loopDataResult[$loopKey]=$this->dataResult;
        $this->dataResult=$this->loopDataResult;
    }
}