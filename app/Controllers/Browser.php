<?php

namespace App\Controllers;
use App\Models;

class Browser extends BaseController
{
    function __construct() {
        $this->pageHeader();
    }

    public function browse() {
        $data = array(
            "url"=> $this->request->getVar('url')
        );
        echo view("browser",$data);
        echo view("templates/footer");
    }
}