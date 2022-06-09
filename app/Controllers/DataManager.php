<?php

namespace App\Controllers;
use App\Models;
use App\Libraries\DataManipulation;

class DataManager extends BaseController
{
    public function viewPage($descriptionName=false)
    {
        $this->pageHeader();
        if (!$descriptionName) {
            $model = new Models\dbParameters();
            $descriptionName = $model->where("name", "defaultPage")->first()['value'];
            $model = new Models\dbDataManager();
            if (empty($descriptionName) || empty($model->where("name", $descriptionName)->first()['name'])) {
                echo view("default.php");
            }
        }
        if ($descriptionName) {
            $data = New DataManipulation();
            $data->rawDataGenerator($descriptionName);
            $data->outputDataGenerator($descriptionName);
            $data->displayView($descriptionName);
        }
        echo view("templates/footer.php");
    }

    public function previewItem($descriptionName,$itemName) {

    }

    public function previewPage($descriptionName = false) {
        $data = New DataManipulation();
        $data->previewEnable();
        $data->rawDataGenerator($descriptionName);
        $data->outputDataGenerator($descriptionName);
        $data->displayView($descriptionName);
    }
}