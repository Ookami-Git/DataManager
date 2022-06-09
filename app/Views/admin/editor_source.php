<form>
<div id="sources" class="ui <?=themeClass?> form">
</div>
</form>
<div class='ui <?=themeClass?> classicmain segment'>
    <button class="ui <?=themeClass?> compact blue labeled icon button" onclick="addSource();"><i class="plus icon"></i><label>Ajouter une source</label></button>
</div>
</br>

<template id="tpl_dmSource">
<div class="dmSource">
    <div class='ui <?=themeClass?> classicmain segment'>
        <h3 class="ui <?=themeClass?> header">Source</h3>
        <div class="two fields">
            <div class="required field">
                <label>Nom</label>
                <input class="sourceName" type="text" placeholder="Identifiant de la source" name="sources[][name]">
            </div>
            <div class="field">
                <label>Type</label>
                <select class="ui <?=themeClass?> fluid dropdown typeSelector" onchange="loadParameters($(this));" name="sources[][type]">
                    <?php foreach($types as $type):?>
                    <option value="<?=$type?>"><?=$type?></option>
                    <?php endforeach?>
                </select>
            </div>
        </div>
        <div class="field">
            <label>Boucle</label>
            <input class="loopBase" type="text" placeholder="Chemin de la boucle : {sourceName.path} ou vide pour ne pas activer la boucle" name="sources[][fromLoop][base]">
        </div>
        <h4 class="ui <?=themeClass?> header">Paramètres</h4>
        <parameters>
        </parameters>
        <br>
        <div class="ui <?=themeClass?> mini basic icon buttons">
            <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'up','.dmSource');"><i class="angle up icon"></i></div>
            <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'down','.dmSource');"><i class="angle down icon"></i></div>
            <div class="ui <?=themeClass?> compact labeled icon button" onclick="deletediv($(this),'.dmSource','la source : '+$(this).closest('.dmSource').find('.sourceName').val())"><i class="minus icon"></i><label>Supprimer la source</label></div>
        </div>
    </div>
    <br>
</div>
</template>

<template id="tpl_input_text">
    <div class="field">
        <label>input text</label>
        <input type="text">
    </div>
</template>

<template id="tpl_input_password">
    <div class="field">
        <label>input password</label>
        <input type="password">
    </div>
</template>

<template id="tpl_input_number">
    <div class="field">
        <label>input num</label>
        <input type="number">
    </div>
</template>

<template id="tpl_input_boolean">
    <div class="inline field">
        <div class="ui <?=themeClass?> checkbox">
            <input type="checkbox" tabindex="0" class="hidden" value="true">
            <label>Checkbox</label>
        </div>
    </div>
</template>

<script>
    readSource("<?=$input?>");
</script>