<div class='ui <?=themeClass?> classicmain segment menuEditorParent'>
    <form class="ui <?=themeClass?> form">
        <input type="hidden" name="[menu]:null" value="null"/>
        <div id="menuEditor" class="ui <?=themeClass?> relaxed selection divided list">
        </div>
    </form>
    <br>
    <div class="ui <?=themeClass?> basic buttons">
        <div id="addPage" class="ui <?=themeClass?> compact blue labeled icon button" onclick="genericAdd($(this),'.menuEditorParent','#menuEditor','#tpl_page',false,'menu[]');"><i class="file icon"></i><label>Ajouter Page</label></div>
        <div id="addLink" class="ui <?=themeClass?> compact blue labeled icon button" onclick="genericAdd($(this),'.menuEditorParent','#menuEditor','#tpl_link',false,'menu[]');"><i class="linkify icon"></i><label>Ajouter Lien</label></div>
        <div id="addGrp" class="ui <?=themeClass?> compact blue labeled icon button" onclick="genericAdd($(this),'.menuEditorParent','#menuEditor','#tpl_group',false,'menu[]');"><i class="folder icon"></i><label>Ajouter Groupe</label></div>
    </div>
</div>
</br>
<?php echo view("templates/parameters_footer");?>

<template id="tpl_page">
    <div class="item">
        <i class="large <?=themeClass?> file middle aligned icon"></i>
        <div class="content">
            <div class="header">
                <div class="ui <?=themeClass?> compact mini basic icon buttons">
                    <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'up','.item');"><i class="angle up icon"></i></div>
                    <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'down','.item');"><i class="angle down icon"></i></div>
                    <div class="ui <?=themeClass?> icon button" onclick="deletediv($(this),'.item','la page du menu')"><i class="trash icon"></i></div>
                </div>
                Page
            </div>
            <div class="description">
                <input name="[type]" type="hidden" value="page">
                <div class="ui <?=themeClass?> fields">
                    <div class="required field"> <input class="pageName" placeholder="Libellé" name="[name]" type="text"></div>
                    <div class="four wide required field"> 
                        <select name='[page]' class='ui <?=themeClass?> fluid search dropdown pagePage'><option value=''>Page</option>
                            <?php foreach ($pages as $page):?>
                            <option value='<?=$page['name']?>'><?=$page['name']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <h4>Paramètres GET</h4>
                <div class="parameters">
                </div>
                <div class="ui <?=themeClass?> compact labeled icon button addGet" onclick="genericAdd($(this),'.description','.parameters','#tpl_page_get',false,parentGrpPath($(this)));"><i class="file alternate outline icon"></i><label>Ajouter variable GET</label></div>
            </div>
        </div>
    </div>
</template>

<template id="tpl_page_get">
    <div class="getVar">
        <div class="ui <?=themeClass?> grid">
            <div class="one wide column">
                <div class="field">
                    <label>Supp.</label>
                    <div class="ui <?=themeClass?> basic small icon button" onclick="deletediv($(this),'.getVar','la variable GET')"><i class="trash icon"></i></div>
                </div>
            </div>
            <div class="fifteen wide column">
                <div class="three fields">
                    <div class="field required">
                        <label>Nom variable</label>
                        <input class="getVarName" type="text" placeholder="Name" name="[parameters][][name]">
                    </div>
                    <div class="field required">
                        <label>Valeur</label>
                        <input class="getVarValue" type="text" placeholder="Value" name="[parameters][][value]">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="tpl_link">
    <div class="item">
        <i class="large <?=themeClass?> linkify middle aligned icon"></i>
        <div class="content">
            <div class="header">
                <div class="ui <?=themeClass?> compact mini basic icon buttons">
                    <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'up','.item');"><i class="angle up icon"></i></div>
                    <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'down','.item');"><i class="angle down icon"></i></div>
                    <div class="ui <?=themeClass?> icon button" onclick="deletediv($(this),'.item','le lien du menu')"><i class="trash icon"></i></div>
                </div>
                Lien
            </div>
            <div class="description">
                <input name="[type]" type="hidden" value="link">
                <div class="ui <?=themeClass?> fields">
                    <div class="required field"> <input class="linkName" placeholder="Libellé" name="[name]" type="text"></div>
                    <div class="four wide required field"> <input class="linkPage" placeholder="http(s)://..." name="[page]" type="text"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="tpl_group">
    <div class="item itemGroup">
        <i class="large <?=themeClass?> folder middle aligned icon"></i>
        <div class="content">
            <div class="header">
                <div class="ui <?=themeClass?> compact mini basic icon buttons">
                    <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'up','.item');"><i class="angle up icon"></i></div>
                    <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'down','.item');"><i class="angle down icon"></i></div>
                    <div class="ui <?=themeClass?> icon button" onclick="deletediv($(this),'.item','le groupe du menu')"><i class="trash icon"></i></div>
                </div>
                Groupe
            </div>
            <div class="description">
                <input name="[type]" type="hidden" value="group">
                <div class="ui <?=themeClass?> fields">
                    <div class="required field"> <input class="groupName" placeholder="Libellé" name="[name]" type="text"></div>
                </div>
                <div class="ui <?=themeClass?> list groupContent">
                    
                </div>
            </div>
            <br>
            <div class="ui <?=themeClass?> basic buttons">
                <div class="ui <?=themeClass?> compact icon button addPage" onclick="genericAdd($(this),'.itemGroup','.groupContent:first','#tpl_page',false,parentGrpPath($(this)));"><i class="file icon"></i></div>
                <div class="ui <?=themeClass?> compact icon button addLink" onclick="genericAdd($(this),'.itemGroup','.groupContent:first','#tpl_link',false,parentGrpPath($(this)));"><i class="linkify icon"></i></div>
                <div class="ui <?=themeClass?> compact icon button addGrp" onclick="genericAdd($(this),'.itemGroup','.groupContent:first','#tpl_group',false,parentGrpPath($(this)));"><i class="folder icon"></i></div>
            </div>
        </div>
    </div>
</template>


<script>
    document.saveAll=true;
    readMenu();
</script>