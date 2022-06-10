<form>
    <div id="presentations" class="ui <?=themeClass?> form">
    </div>
</form>
<div class='ui <?=themeClass?> classicmain segment'>
    <button class="ui <?=themeClass?> compact blue labeled icon button btnAddPresentation" onclick="genericAdd($(this),'body','#presentations','#tpl_presentation');displayParameters($('.typeSelector:last'),'.presentation','presentationParameter');"><i class="plus icon"></i><label>Ajouter un visuel</label></button>
</div>
</br>

<script>
    readPresentation("<?=$input?>");
</script>

<template id="tpl_presentation">
    <div class="presentation">
        <div class='ui <?=themeClass?> classicmain segment'>
            <h3 class="ui <?=themeClass?> header">Visuel</h3>
            <div class="field required">
                <label>Type</label>
                <select class="ui <?=themeClass?> fluid dropdown typeSelector" name="presentation[][type]" onchange="displayParameters($(this),'.presentation','presentationParameter');">
                    <?php foreach($types as $type):?>
                    <option value="<?=$type?>"><?=$type?></option>
                    <?php endforeach?>
                </select>
            </div>
            <div>
                <parameters>
                    <div class="presentationParameter presentationParameterSeparator">
                        <h4 class="ui <?=themeClass?> header">Paramètres Séparateur</h4>
                        <div class="field">
                            <label>Label</label>
                            <input type="text" class="presLabel" name="presentation[][parameters][label]">
                        </div>
                        <div class="field">
                            <label>Icone</label>
                            <input type="text" class="presIcon" placeholder="circle, cog, cat, ..." name="presentation[][parameters][icon]">
                        </div>
                    </div>
                    <div class="presentationParameter presentationParameterItems">
                        <h4 class="ui <?=themeClass?> header">Paramètres Objets</h4>
                        <div class="field required">
                            <label>Objets par ligne</label>
                            <input type="number" min="1" value="3" class="presColumn" name="presentation[][parameters][column]">
                        </div>
                        <items>
                        </items>
                        <div class="ui <?=themeClass?> mini basic icon buttons">
                            <div class="ui <?=themeClass?> compact mini labeled icon button btnAddItem" onclick="genericAdd($(this),'.presentationParameter','items','#tpl_item');"><i class="plus icon"></i><label>Ajouter un objet</label></div>
                        </div>
                    </div>
                    <div class="presentationParameter presentationParameterPage">
                        <h4 class="ui <?=themeClass?> header">Paramètres Page</h4>
                        <div class="fields">
                            <div class="field required">
                                <label>Page</label>
                                <select class="ui <?=themeClass?> fluid dropdown pageSelector" name="presentation[][parameters][page]">
                                    <?php foreach($pages as $page):?>
                                    <option value="<?=$page?>"><?=$page?></option>
                                    <?php endforeach?>
                                </select>
                            </div>
                            <div class="field ">
                                <div class="ui <?=themeClass?> checkbox">
                                    <input type="checkbox" tabindex="0" class="hidden pageSegment" value="true" name="presentation[][parameters][segment]:boolean">
                                    <label>Placer dans un segment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </parameters>
            </div>  
            <br>
            <div class="ui <?=themeClass?> basic icon buttons">
                <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'up','.presentation');"><i class="angle up icon"></i></div>
                <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'down','.presentation');"><i class="angle down icon"></i></div>
                <div class="ui <?=themeClass?> compact labeled icon button" onclick="deletediv($(this),'.presentation','le visuel de type '+$(this).closest('.presentation').find('.typeSelector').val())"><i class="minus icon"></i><label>Supprimer le visuel</label></div>
            </div>
        </div>
        <br>
    </div>
</template>

<template id="tpl_item">
    <div class="itemSelector">
        <div class="fields">
            <div class="field">
                <label>Action</label>
                <div class="ui <?=themeClass?> mini basic icon buttons">
                    <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'up','.itemSelector');"><i class="angle up icon"></i></div>
                    <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'down','.itemSelector');"><i class="angle down icon"></i></div>
                    <div class="ui <?=themeClass?> red tiny icon button" onclick="deletediv($(this),'.itemSelector','l\'objet')"><i class="trash icon"></i></div>
                </div>
            </div>
            <div class="field required">
                <label>Objet</label>
                <select class="ui <?=themeClass?> fluid dropdown presItemSelector" name="presentation[][parameters][items][]">
                    <?php foreach($items as $item):?>
                    <option value="<?=$item?>"><?=$item?></option>
                    <?php endforeach?>
                </select>
            </div>
        </div>
    </div>
</template>