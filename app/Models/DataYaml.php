<?php
namespace App\Models;

class DataYaml extends DataModel {
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

        $dataResult = yaml_parse(file_get_contents($sourceParameters['path'],false,stream_context_create($stream_context)));
            
        if ($dataResult === null) {throw new Exception("Failed to load {$sourceParameters['path']}");}

        $this->dataResult = $dataResult;
    }
}