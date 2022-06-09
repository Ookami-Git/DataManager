<?php

namespace App\Libraries;

use App\Models;

class DataManipulation
{
    private $description;
    private $preview=false;
    #For RAW Data
    private $data=array();
    private $loop;
    private $loopKey;
    #For output Data
    private $outputItems=array();

    function __construct() {
        $this->description = new Models\DbDataManager;
    }

    private function searchAndReplace($input) {
        // function searchAndReplace --> Search all match regexPattern in string or array and remplace with value if exist
        if (is_array($input)) {
            foreach ($input as $key=>$subInput) {
                $input[$key]=$this->searchAndReplace($subInput);
            } 
        } else {
            #Check if match pattern
            while(preg_match_all(REGPATTERN, $input, $matchPattern)) {
                if (count($matchPattern[0])>0) {
                    foreach($matchPattern[0] as $searchPattern) {
                        $value = $this->search($searchPattern);
                        if (is_array($value)) { $value = implode(',',$value);}
                        $input = str_replace($searchPattern, $value, $input);
                    }
                }
            }
        }
        return $input;
    }

    private function search($pattern) {
        // function search --> get value which corresponds to pattern
        $result=$pattern;
        #Remove {} at begin and end of pattern
        $pattern=trim($pattern,'{}');
        #If :key: is set remplace it
        $pattern=preg_replace('/\:key\:/i', $this->loopKey, $pattern);
        if(strtolower($result) == "{:key:}") {return $pattern;}
        #First world in path is group (group.path1.path2)
        $splitPattern=preg_split('/\./', $pattern);
        $dataGroup=$splitPattern[0];
        unset($splitPattern[0]);
        $dataPath=implode(".",$splitPattern);

        switch(strtolower($dataGroup)) {
            #If value is :get: search value in $_GET php var
            case ":get:" :
                if (isset($_GET[$dataPath])) {
                    $result=$_GET[$dataPath];
                }
                break;
            #If value is :loop: remplace this value by $loop var who contain actual loop path
            case ":loop:":
                $pattern=preg_replace('/\:loop\:/i', $this->loop, $pattern);
                $result=$this->search($pattern);
                break;
            default:
                if (isset($this->data[$dataGroup])) {
                    if (is_null($dataPath) || empty($dataPath)) {
                        $result=$this->data[$dataGroup]->getAllData();
                    } else {
                        $result=$this->data[$dataGroup]->getData($dataPath);
                    }
                }
        }
        return $result;
    }

    private function conditionsValidator($conditions) {
        foreach ($conditions as $condition) {
            $testValue=$this->search($condition['data']);
            if (isset($condition['regex'])) {
                if (is_array($testValue)) {
                    $testResult=!empty(preg_grep("/{$condition['regex']}/",$testValue));
                } else { 
                    $testResult=boolval(preg_match("/{$condition['regex']}/",$testValue)); 
                }
            }
            elseif (isset($condition['condition'])) {
                $testResult = false;
                $conditionValue = $this->search($condition['value']);
                switch ($condition['condition']) {
                    case "!=":
                        if ($testValue != $conditionValue) $testResult=true;
                        break;
                    case "==":
                        if ($testValue == $conditionValue) $testResult=true;
                        break;
                    case ">=":
                        if ($testValue >= $conditionValue) $testResult=true;
                        break;
                    case "<=":
                        if ($testValue <= $conditionValue) $testResult=true;
                        break;
                    case ">":
                        if ($testValue > $conditionValue) $testResult=true;
                        break;
                    case "<":
                        if ($testValue < $conditionValue) $testResult=true;
                        break;
                    case "IN":
                        if (!is_array($conditionValue)) {
                            $conditionValue = explode(" ", $conditionValue);
                        }
                        if (in_array($testValue,$conditionValue)) $testResult=true;
                    case "NOT IN":
                        if (!is_array($conditionValue)) {
                            $conditionValue = explode(" ", $conditionValue);
                        }
                        if (!in_array($testValue,$conditionValue)) $testResult=true;
                }
            }
            if ($testResult != $condition['expected']) {
                return false;
            }
        }
        return true;
    }

    public function initType($type) {
        switch ($type) {
            case "json":
                $data = new Models\DataJson;
            break;
            case "xml":
                $data = new Models\DataXml;
            break;
            case "yaml":
                $data = new Models\DataYaml;
            break;
            case "postgres":
                $data = new Models\DataPostgres;
            break;
            case "sqlite":
                $data = new Models\DataSqlite;
            break;
            case "mysql":
                $data = new Models\DataMysql;
            break;
            default : return null;
        }
        return $data;
    }

    public function rawDataGenerator($getDescriptionSource) {
        $descriptionSource = json_decode($this->description->where(['name' => $getDescriptionSource])->first()['source']??null,true);
        $descriptionSource = $descriptionSource['sources']??array();
        foreach ($descriptionSource as $sourceDescription) {
            #Init data var to type object
            $this->data[$sourceDescription['name']]=$this->initType($sourceDescription['type']);
            #If loop, init $loop and $loopKey values
            if ( isset($sourceDescription['fromLoop']) ) {
                $loopBase=$sourceDescription['fromLoop']['base'];
                $loopData=$this->search($loopBase);
                foreach ($loopData as $loopKey=>$loopValue) {
                    $loopSourceDescription=$sourceDescription;
                    $this->loop="$loopBase.$loopKey";
                    $this->loopKey=$loopKey;
                    $loopSourceDescription=$this->searchAndReplace($loopSourceDescription);
                    $this->data[$loopSourceDescription['name']]->setLoopSource($loopKey,$loopSourceDescription['parameters']);
                }
            } else {
                $sourceDescription=$this->searchAndReplace($sourceDescription);
                $this->data[$sourceDescription['name']]->setSource($sourceDescription['parameters']);
            }
        }
    }

    public function outputDataGenerator($getItemsDescription) {
        $descriptionItems = json_decode($this->description->where(['name' => $getItemsDescription])->first()['item']??null,true);
        $descriptionItems = $descriptionItems['items']??array();
        foreach ($descriptionItems as $ItemDescription) {
            $output=array();
            switch ($ItemDescription['type']) {
                case "table":
                    $output['header']=array();
                    $output['data']=array();
                    #Set column header label
                    foreach ($ItemDescription['display'] as $display) {
                        array_push($output['header'],array("label"=>$display['label'],"collapse"=>$display['collapse']));
                    }
                    #Init loop parameters
                    $loopBase=$ItemDescription['parameters']['loopBase'];
                    $loopData=$this->search($loopBase);
                    #Start loop
                    foreach ($loopData as $loopKey=>$loopValue) {
                        $this->loop="$loopBase.$loopKey";
                        $this->loopKey=$loopKey;
                        #Check filters
                        if (!$this->conditionsValidator($ItemDescription['parameters']['filters']??array())) {
                            continue;
                        }
                        #Set data for display
                        $tableDisplayLine=array();
                        foreach($ItemDescription['display'] as $display) {
                            $tableDisplayCell=$display['print']['default'];
                            if (isset($display['print']['conditional'])) {
                                foreach($display['print']['conditional'] as $conditionalDisplay) {
                                    if ($this->conditionsValidator($conditionalDisplay['conditions'])) {
                                        $tableDisplayCell=$conditionalDisplay['print'];
                                        break;
                                    }
                                }
                            }
                            $tableDisplayCell = $this->searchAndReplace($tableDisplayCell);#Check if match pattern
                            array_push($tableDisplayLine,$tableDisplayCell); #push 1 cell in 1 line
                        }
                        array_push($output['data'],$tableDisplayLine); #push 1 line in table
                    }
                    break;
                case "list":
                    foreach($ItemDescription['display'] as $display) {
                        $listeLine=array();
                        #Label
                        $listeLine['label']=$display['label'];
                        #Icon
                        if (isset($display['icon'])) {$listeLine['icon']=$display['icon'];}
                        #Display
                        $listeLine['display']=$display['print']['default'];
                        if (isset($display['print']['conditional'])) {
                            foreach($display['print']['conditional'] as $conditionalDisplay) {
                                if ($this->conditionsValidator($conditionalDisplay['conditions'])) {
                                    $listeLine['display']=$conditionalDisplay['print'];
                                    break;
                                }
                            }
                        }
                        $listeLine = $this->searchAndReplace($listeLine); #Check if match pattern
                        array_push($output,$listeLine);
                    }
                    break;
                case "text":
                    $output['print'] = $this->searchAndReplace($ItemDescription['print']);
                    $output['segment'] = $ItemDescription['parameters']['segment'];
            }
            $this->outputItems[$ItemDescription['title']['label']]=array();
            $this->outputItems[$ItemDescription['title']['label']]['title']=$ItemDescription['title'];
            $this->outputItems[$ItemDescription['title']['label']]['type']=$ItemDescription['type'];
            $this->outputItems[$ItemDescription['title']['label']]['data']=$output;
        }
    }

    public function displayView($getPresentationDescription) {
        helper('number');
        $presentations = json_decode($this->description->where(['name' => $getPresentationDescription])->first()['presentation']??null,true);
        $presentations = $presentations['presentation']??array();
        foreach ($presentations as $key=>$presentationDescription) {
            switch ($presentationDescription['type']) {
                case "items":
                    echo view("templates/items/i_header.php",array("column"=>strtolower(number_to_word($presentationDescription['parameters']['column']))));
                    foreach($presentationDescription['parameters']['items'] as $item) {
                        echo view("templates/items/i_".$this->outputItems[$item]['type'],$this->outputItems[$item]);
                    }
                    echo view("templates/items/i_footer.php");
                    break;
                case "separator": 
                    echo view("templates/".$presentationDescription['type'],$presentationDescription['parameters']);
                    break;
                case "page":
                    if ($presentationDescription['parameters']['segment'] ?? false) { echo view("templates/segment_open.php");}
                    echo view("pages/".$presentationDescription['parameters']['page'],$presentationDescription['parameters']);
                    if ($presentationDescription['parameters']['segment'] ?? false) { echo view("templates/segment_close.php");}
                    break;
            }
            if ($key !== array_key_last($presentations)) {
                echo "<br>";
            }
        }
    }

    public function displaySourcePattern($sourceName) {
        $flattened = array_flatten_with_dots($this->data[$sourceName]->getAllData(), "{$sourceName}.");
        $data=array("source"=>$flattened);
        echo view("admin/editor_help",$data);
    }

    public function previewEnable($preview=true) {
        $this->preview=$preview;
        if ($preview) {
            $this->description = new Models\DbDataManagerPreview;
        } else {
            $this->description = new Models\DbDataManager;
        }
    }
}