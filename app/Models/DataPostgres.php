<?php
namespace App\Models;

class DataPostgres extends DataModel {
    protected $dataFormat=array(
        "query"=>array(
            "label"=>"Requête SQL",
            "type"=>"text",
            "require"=>true
        ),
        "host"=>array(
            "label"=>"Hôte",
            "type"=>"text",
            "require"=>true,
            "default"=>"localhost"
        ),
        "port"=>array(
            "label"=>"Port",
            "type"=>"number",
            "require"=>true,
            "default"=>3306
        ),
        "dbname"=>array(
            "label"=>"Nom de la base de données",
            "type"=>"text",
            "require"=>true
        ),
        "username"=>array(
            "label"=>"Compte",
            "type"=>"text",
            "require"=>true
        ),
        "password"=>array(
            "label"=>"Mot de passe",
            "type"=>"password",
            "require"=>false
        )
    );
    public function setSource ($sourceParameters) {
        if (!isset($sourceParameters['query'])) {throw new Exception("Need query for Sqlite execution");}
        $dataResult=array();

        if (!isset($sourceParameters['password'])) $sourceParameters['password']=null;

        $db = new PDO("postgres:host={$sourceParameters['host']};port={$sourceParameters['port']};dbname={$sourceParameters['dbname']};", $sourceParameters['user'], $sourceParameters['password']);

        foreach($db->query($sourceParameters['query']) as $row) {
            $dataResult[]=$row;
        }
        
        if ($dataResult === null) {throw new Exception("Failed to load {$sourceParameters['path']}");}
        return $dataResult;
    }
}