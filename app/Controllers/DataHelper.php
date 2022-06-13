<?php

namespace App\Controllers;
use App\Models;
use App\Libraries\DataManipulation;

class DataHelper extends BaseController
{
    public function dataHelper($descriptionName,$sourceName) {
        $modelUsr = new \App\Models\DbUsers;
        // THEME
        $theme = $modelUsr->where('username', session()->get("username"))->first()['theme'];

        if ($theme == "dark") {
            $themeClass = "inverted";
        } else {
            $themeClass = "";
        }
        defined('themeClass')          || define('themeClass', $themeClass); //THEME CLASS

        $data = New DataManipulation();
        $data->rawDataGenerator($descriptionName,$sourceName);
        $data->displaySourcePattern($sourceName);
    }
}