<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    public $title;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    protected function pageHeader($logged=true) {
        $model = new \App\Models\AppParameters;
        if ($logged) {
            $modelUsr = new \App\Models\DbUsers;
            // THEME
            $theme = $modelUsr->where('username', session()->get("username"))->first()['theme'];

        } else {
            $theme = $model->where('name = "name"')->first()['value'];
        }

        if ($theme == "dark") {
            $themeClass = "inverted";
        } else {
            $themeClass = "";
        }
        defined('themeClass')          || define('themeClass', $themeClass); //THEME CLASS

        
        $menu=json_decode($model->getParameter("menu"),true);
        $this->title=$model->where("name = 'name'")->first()['value'];
        $data=array(
            "title"=>$this->title,
            "theme"=>session()->get("theme")??$model->where('name = "name"')->first()['value']
        );
        echo view("templates/header",$data);
        #Menu
        if ($logged) {
            echo view("templates/menu/m_header");
            if (isset($menu) && is_array($menu)) {$this->menuItems($menu);} ;
            echo view("templates/menu/m_footer",array("admin"=>true,"user"=>session()->get("username")));
            #Content
            echo view("templates/start_content");
        }
    }

    private function menuItems($menu,$level=0) {
        foreach ($menu as $item) {
            switch($item['type']) {
                case "link":
                    echo view('templates/menu/m_item',$item);
                    break;
                case "page":
                    $item['page']="/view/{$item['page']}";
                    echo view('templates/menu/m_item',$item);
                    break;
                case "group":
                    if ($level == 0) {
                        echo view("templates/menu/m_group",$item);
                    } else {
                        echo view("templates/menu/m_subgroup",$item);
                    }
                    if (isset($item['items'])) {
                        $this->menuItems($item['items'],$level+1);
                    }
                    echo view("templates/menu/m_closegroup");
                    break;
            }
        }
    }
}