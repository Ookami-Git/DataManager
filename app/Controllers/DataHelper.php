<?php

namespace App\Controllers;
use App\Models;
use App\Libraries\DataManipulation;

class DataHelper extends BaseController
{
    public function dataHelper($descriptionName,$sourceName) {
        $data = New DataManipulation();
        $data->rawDataGenerator($descriptionName);
        $data->displaySourcePattern($sourceName);
    }
}