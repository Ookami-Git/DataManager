<?php
namespace App\Models;

class DataSqlite extends DataModel {
    protected $dataFormat=array(
        "query"=>array(
            "label"=>"Requête SQL",
            "type"=>"text",
            "require"=>true
        ),
        "path"=>array(
            "label"=>"Emplacement",
            "type"=>"text",
            "require"=>true
        )
    );
    public function setSource ($sourceParameters) {
        if (!file_exists($sourceParameters['path'])) {throw new Exception("Failed to load {$sourceParameters['path']}");}
        if (!isset($sourceParameters['query'])) {throw new Exception("Need query for Sqlite execution");}
        $dataResult=array();

        $db_sqlite = new \PDO("sqlite:{$sourceParameters['path']}");
        $db_sqlite->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        foreach($db_sqlite->query($sourceParameters['query']) as $row) {
            $dataResult[]=$row;
        }

        if ($dataResult === null) {throw new Exception("Failed to load {$sourceParameters['path']}");}

        $this->dataResult = $dataResult;
    }
}