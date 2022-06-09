<?php

namespace App\Controllers;
use App\Models;

class ErrorController extends BaseController
{
    function __construct() {
        $this->pageHeader();
    }

    public function index($error) {
        $data=array("title"=>"Erreur","message"=>"Une erreur inconnue s'est produite");
        $response = service('response');
        $response->setStatusCode(500);
        switch($error) {
            case "401":
                $response->setStatusCode(401);
                $data['title']="Erreur 401";
                $data['message']="Vous ne disposez pas des droits requis";
                break;
            case "403":
                $response->setStatusCode(403);
                $data['title']="Erreur 403";
                $data['message']="Vous ne disposez pas des droits requis";
                break;
            case "rundeckInstanceError":
                $response->setStatusCode(500);
                $data['title']="Erreur Connexion Rundeck";
                $data['message']="Erreur lors de la connexion vers l'instance Rundeck";
                break;
            case "rundeckInstanceUnknown":
                $response->setStatusCode(404);
                $data['title']="Erreur Instance Rundeck";
                $data['message']="Instance Rundeck introuvable";
                break;
        }
        echo view("error",$data);
        echo view("templates/footer");
    }
}