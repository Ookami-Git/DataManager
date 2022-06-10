<?php
namespace App\Models;

class DataXml extends DataModel {
    protected $dataFormat=array(
        "ssl"=>array(
            "label"=>"SSL",
            "type"=>"boolean",
            "default"=>false
        ),
        "path"=>array(
            "label"=>"Emplacement / URL",
            "type"=>"text",
            "require"=>true
        )
    );
    public function setSource ($sourceParameters) {
        if (isset($sourceParameters['ssl']) && !$sourceParameters['ssl']) {
            $stream_context=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                )
            );
        } else {
            $stream_context = null;
        };

        $dataResult = $this->object_to_array(new \SimpleXMLElement(file_get_contents($sourceParameters['path'],false,stream_context_create($stream_context))));
            
        if ($dataResult === null) {throw new Exception("Failed to load {$sourceParameters['path']}");}

        $this->dataResult = $dataResult;
    }

    private function object_to_array($obj) {
        if(is_object($obj) || is_array($obj)) {
            $ret = (array) $obj;
            foreach($ret as &$item) {
                //recursively process EACH element regardless of type
                $item = object_to_array($item);
            }
            return $ret;
        } else {
            return $obj;
        }
    }
}