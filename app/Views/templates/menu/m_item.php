<?php 
    if ($parameters ?? false) {
        $parametersChain=array();
        foreach($parameters as $parameter) {
            array_push($parametersChain,"{$parameter['name']}={$parameter['value']}");
        }
        $parametersChain="?".implode("&",$parametersChain);
        if ("?".$_SERVER['QUERY_STRING'] == $parametersChain) {$sameQuery=true;} else {$sameQuery=false;}
    } else { $parametersChain=null; }
    if ("/".uri_string(true) == $page && ($sameQuery ?? true)) { $class = "active";} else { $class=null;}
?>
<a class="item <?=$class?>" href="<?php if ($type == "page") {echo base_url();}?><?=$page.$parametersChain?>"><?php if($class=="active") {echo "<b>";} ?><?=$name?><?php if($class=="active") {echo "</b>";} ?></a>