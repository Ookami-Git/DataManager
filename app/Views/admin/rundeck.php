<div class='ui <?=themeClass?> classicmain segment'>
    <form class="ui <?=themeClass?> form">
        <input type="hidden" name="[rundeck]:null" value="null"/>
        <div id="divrdk" class="ui <?=themeClass?> styled accordion fluid ">
        </div>
    </form>
    <br>
    <div id="addRdk" class="ui <?=themeClass?> compact mini labeled icon button" onclick="genericAdd($(this),'.segment','#divrdk','#tpl_rundeck')">
        <i class="plus icon"></i>
        Instance rundeck
    </div>
</div>
<br>

<?php echo view("templates/parameters_footer");?>

<template id="tpl_rundeck">
    <dmrundeck> 
        <div class="title"> 
            <i class="dropdown icon"></i> 
            <span id='rdk_title'>Rundeck nouvelle instance</span>
        </div> 
        <div class="content"> 
            <div class="fields"> 
                <div class="inline required field"> 
                    <label>Nom de l'instance</label> 
                    <input class="rdkInstance nospace" type="text" placeholder="instance" name="[rundeck][instances][][instance]" onchange="$(this).closest('dmrundeck').find('#rdk_title').text('Rundeck Instance : '+$(this).val());"> 
                </div> 
                <div class="field"> 
                    <div class="ui <?=themeClass?> mini blue icon button" onclick="window.open('<?=base_url()?>/rundeckview/'+$(this).closest('dmrundeck').find('.rdkInstance').val(), '_blank');"> 
                        <i class="external alternate icon"></i> 
                    </div> 
                </div> 
                <div class="field"> 
                    <div class="ui <?=themeClass?> mini negative icon button" onclick="deletediv($(this),'dmrundeck',$(this).closest('dmrundeck').find('.title:first').text());"> <i class="trash icon"></i></div> 
                </div> 
            </div> 
            <div class="three fields"> 
                <div class="field required"> 
                    <label>URL Rundeck (pour les liens)</label> 
                    <div class="ui <?=themeClass?> input left icon"> 
                        <i class="globe icon"></i> 
                        <input class="rdkUrl nospace" type="text" placeholder="http(s)://rdk_host:rdk_port/rdk_path" name="[rundeck][instances][][url]" > 
                    </div> 
                </div> 
                <div class="field required"> 
                    <label>API Rundeck</label> 
                    <div class="ui <?=themeClass?> input left icon"> 
                        <i class="stream icon"></i> 
                        <input class="rdkApi nospace" type="text" placeholder="http(s)://rdk_host:rdk_port/rdk_path/api" name="[rundeck][instances][][api]" > 
                    </div> 
                </div> 
                <div class="field"> 
                    <div class="ui <?=themeClass?> checkbox"> 
                        <label>Check SSL API</label> 
                        <input type="checkbox" value="true" tabindex="0" class="hidden rdkApiSsl" name="[rundeck][instances][][api_verify_ssl]:boolean"> 
                    </div> 
                </div> 
            </div> 
            <div class="two fields"> 
                <div class="field required"> 
                    <label>Nom du Projet</label> 
                    <div class="ui <?=themeClass?> input left icon"> 
                        <i class="folder icon"></i> 
                        <input class="rdkProject nospace" type="text" placeholder="RDK_PROJECT" name="[rundeck][instances][][project]"> 
                    </div> 
                </div> 
                <div class="field required"> 
                    <label>Token du projet</label> 
                    <div class="ui <?=themeClass?> input left icon"> 
                        <i class="key icon"></i> 
                        <input class="rdkToken nospace" type="password" placeholder="xxxxxxxxxxxxx" name="[rundeck][instances][][token]"> 
                    </div> 
                </div> 
            </div> 
            <div class="field"> 
                <div class="ui <?=themeClass?> checkbox"> 
                    <label>Cacher les JOBS dont l'ordonnancement est configuré mais désactivé</label> 
                    <input type="checkbox" value="true" tabindex="0" class="hidden rdkJobsDisabled" name="[rundeck][instances][][hide][jobs_disabled]:boolean"> 
                </div> 
            </div>
            <div class="divhidejobs"></div> 
            <div class="ui <?=themeClass?> compact mini labeled icon button addHideJob" onclick="genericAdd($(this),'dmrundeck','.divhidejobs','#tpl_hidejob');"> 
                <i class="plus icon"></i> Job à cacher
            </div> 
        </div> 
    </dmrundeck>
</template>

<template id="tpl_hidejob">
    <dmhidejobs>
        <div class="inline fields">
            <div class="field">
                <div class="ui <?=themeClass?> mini negative icon button" onclick="deletediv($(this),'dmhidejobs','JOB ID - Cacher');"> <i class="trash icon"></i> </div>
            </div>
            <div class="inline field required six wide">
                <label>JOB ID à cacher</label>
                <input class="jobId nospace" name="[rundeck][instances][][hide][jobs][]" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" type="text">
            </div>
        </div>
    </dmhidejobs>
</template>

<script>
    document.saveAll=true;
    readRundeck();
</script>