<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="icon" href="<?=base_url('/assets/img/favicon.ico')?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?=$title?></title>
        <script>
            document.baseUrl='<?=base_url()?>';
            document.theme='<?=$theme?>';
        </script>
        <!-- You MUST include jQuery before Fomantic -->
        <script src="<?=base_url('/assets/js/jquery-3.6.0.min.js')?>"></script>
        <link rel="stylesheet" type="text/css" href="<?=base_url('/assets/css/Fomantic-UI-CSS-2.8.8/semantic.min.css')?>">
        <script src="<?=base_url('/assets/css/Fomantic-UI-CSS-2.8.8/semantic.min.js')?>"></script>

        <script type="text/javascript" src="<?=base_url('/assets/js/tablesort.js')?>"></script>	
        <script type="text/javascript" src="<?=base_url('/assets/js/default.js')?>"></script>
<!--
        <script src="vis-timeline/vis-timeline-graph2d.min.js"></script>
        <link href="vis-timeline/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />
-->
        <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>">
    </head>
    <body class="ui <?=themeClass?> landing-image" style="margin:0;">
        <div class="ui basic segment">