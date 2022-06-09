<?php

namespace App\Controllers;
use App\Models;
use App\Libraries;
use DateTime;
use SQLite3;
use SimpleXMLElement;

class RundeckController extends BaseController
{
    public function rundeck($instance) {
        $this->pageHeader();
        $themeClass = themeClass;

        $model = new Models\DbParameters();
        $rdkInstances = json_decode($model->where('name','rundeck')->first()['value'],true)??array();

        foreach($rdkInstances['instances'] as $rdkInstance) {
            if ($instance != $rdkInstance['instance']) {
                continue;
            }
            $rdk_parameters = $rdkInstance;
            $rdk_url= $data['rdk_url'] = $rdkInstance['url'];
            $rdk_api = $data['api'] = $rdkInstance['api'];
            $rdk_token = $data['token'] = $rdkInstance['token'];
            $rdk_project = $data['rdk_project'] = $rdkInstance['project'];
        }
        if (!isset($rdk_parameters)) {
            return redirect()->to(base_url()."/error/rundeckInstanceUnknown");
        }
        if (isset($rdk_parameters['api_verify_ssl']) && $rdk_parameters['api_verify_ssl'] == false) {
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            ); 
        } else {$arrContextOptions=array();}
    
        $rdk_health=@file_get_contents("$rdk_api/40/metrics/healthcheck?authtoken=$rdk_token", false, stream_context_create($arrContextOptions));

        if ($rdk_health === FALSE) { 
            return redirect()->to(base_url()."/error/rundeckInstanceError");
        }

        //Fuseau horaire pour calcule (TZ Rundeck API)
        date_default_timezone_set('Europe/Paris');

        //Paramètrage Date 
        if ( isset($_GET['date']) ) {
            $date_choice = date('Y-m-j',$_GET['date']/1000) ;
        } else {
            $date_choice = date('Y-m-j') ;
        }
        $select_begin = new DateTime($date_choice);
        $select_begin->modify("- 1 seconde");
        $select_end = new DateTime($date_choice);
        $select_end->modify("+ 1 day");
        $date_start_ms = $select_begin->format('U') * 1000 ;
        $date_end_ms = $select_end->format('U') * 1000 ;

        // Création de la BDD qui stock les infos des requetes API RUNDECK
        $db = new SQLite3(':memory:');
        // Requete de création de la table
        $tb_create = 'CREATE TABLE IF NOT EXISTS jobs (
                        job_id   TEXT,
                        launch_sec_perm INT,
                        launch_sec INT,
                        job_group TEXT,
                        job_name TEXT,
                        exec_id TEXT,
                        statut TEXT,
                        job_enable TEXT,
                        end_sec INT,
                        avg_ms INT,
                        PRIMARY KEY (job_id, launch_sec_perm))';
        // Execution de la requete de création de table  
        $db->exec($tb_create);

        // Liste des jobs programmé
        $schedule_jobs = file_get_contents("$rdk_api/40/project/$rdk_project/jobs?authtoken=$rdk_token&scheduledFilter=True", false, stream_context_create($arrContextOptions));
        $schedule_jobs = new SimpleXMLElement($schedule_jobs);

        // Liste des executions en cours
        $running_jobs = file_get_contents("$rdk_api/40/project/$rdk_project/executions/running?authtoken=$rdk_token", false, stream_context_create($arrContextOptions));
        $running_jobs = new SimpleXMLElement($running_jobs);

        // Liste de toutes les exeuctions (complété plus loins)
        $all_exec_jobs[0]=$running_jobs;

        foreach($schedule_jobs as $job) {
            // Filtre sur les jobs du projet
            if( $job->project == $rdk_project) {
                $job_id = $job['id'] ;
                $job_scheduled = $job['scheduled'] ;
                $job_schedule_enable = $job['scheduleEnabled'] ;
                // Filtre sur les job programmé uniquement
                if ( $job_scheduled == "true" ) {	
                    // Détails du job
                    $export_job = file_get_contents("$rdk_api/40/job/$job_id?authtoken=$rdk_token", false, stream_context_create($arrContextOptions));
                    $export_job = new SimpleXMLElement($export_job);
                    $job_detail = $export_job->job;
                    $job_detail_id = $job_detail->id ;
                        //Chargement des metadata
                        $meta_job = file_get_contents("$rdk_api/40/job/$job_id/info?authtoken=$rdk_token", false, stream_context_create($arrContextOptions));
                        $meta_job = new SimpleXMLElement($meta_job);
                        // Règle de schedule de ce job
                        if (ctype_digit((string)$job_detail->schedule->time['minute'])) {
                            $time_minute = (int)$job_detail->schedule->time['minute'] ;
                        } else {
                            if (strpos((string)$job_detail->schedule->time['minute'], ",") === false) {
                                $time_minute = $job_detail->schedule->time['minute'];
                            } else {
                                $minutes = explode(',',(string)$job_detail->schedule->time['minute']);
                                $time_minute = NULL;
                                foreach ($minutes as $minute) {
                                    $minute = (int)$minute;
                                    $time_minute .= ",$minute";
                                }
                                $time_minute = substr($time_minute,1);
                            }
                        }

                        if (ctype_digit((string)$job_detail->schedule->time['hour'])) {
                            $time_hour = (int)$job_detail->schedule->time['hour'] ;
                        } else {
                            if (strpos((string)$job_detail->schedule->time['hour'], ",") === false) {
                                $time_hour = $job_detail->schedule->time['hour'];
                            } else {
                                $heurs = explode(',',(string)$job_detail->schedule->time['hour']);
                                $time_hour = NULL;
                                foreach ($heurs as $heur) {
                                    $heur = (int)$heur;
                                    $time_hour .= ",$heur";
                                }
                                $time_hour = substr($time_hour,1);
                            }
                        }

                        $month_month = $job_detail->schedule->month['month'] ;
                        $month_day = $job_detail->schedule->month['day'] ;
                        //$time_seconds = $job_detail->schedule->time['seconds'] ; //N'est pas utilisé
                        $week_day = $job_detail->schedule->weekday['day'] ;
                        //$year_year = $job_detail->schedule->year['year'] ; // N'est pas utilisé
                        //Si la valeur month_day n'existe pas on la remplace par "*"
                        if( $month_day == NULL ) {
                            $month_day = "*" ;
                        }
                        // Si la valeur week_day n'existe pas on la remplace par "*"
                        if( $week_day == NULL ) {
                            $week_day = "*" ;
                        } else {
                            $week_day_rework="";
                            $week_day_rework_array = str_split($week_day);
                            foreach ($week_day_rework_array as $day_rework) {
                                if (is_numeric($day_rework)) {
                                    $week_day_rework .= $day_rework - 1;
                                } else {
                                    $week_day_rework .= $day_rework;
                                }
                            }
                            $week_day = $week_day_rework;
                        }

                        $daily_exec = NULL ;
                        $cron = Libraries\CronExpression::factory("$time_minute $time_hour $month_day $month_month $week_day");

                        $run = $cron->getNextRunDate(date($select_begin->format('Y-m-d H:i:s')));

                        $job_avg = $meta_job['averageDuration'] ;

                        while ( $select_end > $run) {
                            $next_exec_ms = $run->format('U') * 1000 ;
                            $next_exec_perm = $run->format('U') / 10 ;
                            $db->exec("INSERT INTO jobs (job_id, launch_sec_perm, launch_sec, job_group, job_name, job_enable, avg_ms) VALUES (\"$job_id\", \"$next_exec_perm\", \"$next_exec_ms\", \"$job_detail->group\", \"$job_detail->name\", \"$job_schedule_enable\", \"$job_avg\")");
                            $run = $cron->getNextRunDate(date($run->format('Y-m-d H:i:s')));
                        } ;

                        // Liste des executions dans la plage de dates de UNIXTIME
                        $postdata = http_build_query(
                            array(
                                'begin' => "$date_start_ms",
                                'end' => "$date_end_ms",
                                'max' => '0',
                                'jobIdListFilter' => "$job_id"
                            )
                        );

                        $opts = array('http' =>
                            array(
                                'method'  => 'POST',
                                'header'  => 'Content-type: application/x-www-form-urlencoded',
                                'content' => $postdata
                            ),
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        );
                        $context = stream_context_create($opts); 
                        $exec_jobs = file_get_contents("$rdk_api/40/project/$rdk_project/executions?authtoken=$rdk_token", false, $context);
                        $exec_jobs = new SimpleXMLElement($exec_jobs);

                        $all_exec_jobs[1]=$exec_jobs;

                        foreach ($all_exec_jobs as $exec_lst) {
                            foreach($exec_lst as $execution) {
                                $exec_id = $execution['id'] ;
                                $exec_job_id = $execution->job['id'] ;
                                $exec_job_name = $execution->job->name ;
                                $exec_job_group = $execution->job->group ;
                                $exec_status = $execution['status'] ;
                                $exec_date = $execution->{'date-started'}['unixtime'] ;
                                $exec_end_date = $execution->{'date-ended'}['unixtime'] ;
                                $exec_date_perm = substr($exec_date,0,-4) ;
                                if( "$exec_job_id" == "$job_id" ) {
                                    $db->exec("INSERT OR REPLACE INTO jobs (job_id, launch_sec_perm, launch_sec, job_group, job_name, exec_id, statut, job_enable, end_sec, avg_ms) VALUES (\"$exec_job_id\", \"$exec_date_perm\", \"$exec_date\", \"$exec_job_group\", \"$exec_job_name\", \"$exec_id\", \"$exec_status\", \"$job_schedule_enable\", \"$exec_end_date\", \"$job_avg\")");
                                }
                            }
                        }
                    
                }
            }
        }

        $results = $db->query('SELECT * FROM jobs ORDER BY launch_sec');

        $hide=$hide[false]=$hide[true]=array();
        $hide[false]["style"]=$hide[false]["class"]=$hide[false]["icon"]=NULL;
        $hide[true]["style"]="style='display: none;'";
        $hide[true]["class"]="trhide active";
        $hide[true]["icon"]="slash";

        $data['hide']=$hide;

        $rdkData = array();

        while ($row = $results->fetchArray()) {
            $rdkDataRow = array();
            $rdkDataRow['exec_id'] = $row['exec_id'];
            $rdkDataRow['job_id'] = $row['job_id'];
            $rdkDataRow['job_group'] = $row['job_group'];
            $rdkDataRow['job_name'] = $row['job_name'];
            date_default_timezone_set('UTC');
            $avg = $rdkDataRow['avg'] = date('G\h i\m\n s\s', $row['avg_ms']/1000) ;
            date_default_timezone_set('Europe/Paris');
            // On ajoute date(Z) pour passer du timestamp (UTC) rundeck vers notre fuseau horaire (Europe/Paris)
            $human_date = (int)($row['launch_sec'] / 1000) + date('Z') ;
            $human_date = DateTime::createFromFormat('U', "$human_date");
            $human_date = $human_date->format('j/m/Y G:i') ;
            $rdkDataRow['human_date'] = $human_date;
            // Calcule de la durée du traitement
            if ( $row['end_sec'] != NULL ) {
                $duration = (int)(($row['end_sec'] - $row['launch_sec'])/ 1000) ;
                $diff = (int)($duration - $row['avg_ms']/1000);
                date_default_timezone_set('UTC');
                $duration = date('G\h i\m\n s\s', $duration) ;
                $html_diff = date($this->formattemps(abs($diff)*1000), abs($diff)) ;


            }
            if ( $row['end_sec'] != NULL ) {
                $duration = (int)(($row['end_sec'] - $row['launch_sec'])/ 1000) ;
                $diff = (int)($duration - $row['avg_ms']/1000);
                date_default_timezone_set('UTC');
                $duration = date('G\h i\m\n s\s', $duration) ;
                $html_diff = date($this->formattemps(abs($diff)*1000), abs($diff)) ;
                if ($diff == 0) { $duration = "$duration";}
                if ($diff > 0) { $duration = "$duration (<span class='ui $themeClass orange text small'>+$html_diff</span>)";}
                if ($diff < 0) { $duration = "$duration (<span class='ui $themeClass green text small'>-$html_diff</span>)";}
                $rdkDataRow['diff']=$diff;
                $rdkDataRow['html_diff']=$html_diff;
            } else {
                $duration = "<span class='ui $themeClass grey text small'>Estimée : $avg</span>" ;
                
            }
            $rdkDataRow['duration']=$duration;
            $html_log=" - <a href=\"$rdk_url/project/$rdk_project/execution/show/{$row['exec_id']}#output\" target=\"_blank\">Log</a>";
            $hidden=false;
            if (isset($rdk_parameters['hide']['jobs']) && in_array($row['job_id'],$rdk_parameters['hide']['jobs'])) {
                $hidden=true;
            }
            switch ($row['statut']) {
            case "succeeded":
                $exec_status="<i class='check icon green'></i>Ok";
                break;
            case "failed":
                $exec_status="<i class='x icon icon red'></i>Ko";
                break;
            case "running":
                $exec_status="<i class='spinner icon yellow'></i>En cours";
                break;
            case "aborted":
                $exec_status="<i class='minus icon purple'></i>Annulé";
                break;
            default:
                $html_log=NULL ;
                if ( $row['job_enable'] == "false" ) {
                    $exec_status="<i class='ban icon blue' title=\"L'exécution est désactivé sur rundeck\"></i>Désactivé";
                    $rdkDataRow['duration'] = "";
                    if (isset($rdk_parameters['hide']['jobs_disabled']) && $rdk_parameters['hide']['jobs_disabled']) {
                        $hidden=true;
                    }
                } else {
                    $exec_status=NULL ;
                }
            }

            $rdkDataRow['exec_status'] = $exec_status;
            $rdkDataRow['hidden'] = $hidden;
            $rdkDataRow['html_log'] = $html_log;

            array_push($rdkData,$rdkDataRow);
        }
        $data['rdkData']=$rdkData;

        echo view("rundeck.php",$data);
        echo view("templates/footer.php");
    }

    // Choix du format de durée adapté
    private function formattemps($ms) {
        if ($ms < 60000 ) { return 's\s' ;}
        if ($ms < 60000*60 ) { return 'i\m\n s\s' ;}
        return 'G\h i\m\n s\s';
    }

    //Convertis les ms en format lisible
    private function formatMilliseconds($milliseconds) {
        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $milliseconds = $milliseconds % 1000;
        $seconds = $seconds % 60;
        $minutes = $minutes % 60;
    
        $format = '%u:%02u:%02u.%03u';
        $time = sprintf($format, $hours, $minutes, $seconds, $milliseconds);
        return rtrim($time, '0');
    }
}