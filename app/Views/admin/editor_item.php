<form>
    <div id="items" class="ui <?=themeClass?> form">
    </div>
</form>
<div class='ui <?=themeClass?> classicmain segment'>
    <div class="ui <?=themeClass?> compact blue labeled icon button btnAddItem" onclick="genericAdd($(this),'body','#items','#tpl_dmItem');displayParameters($('.typeSelector:last'),'.dmItem','displayParameter');"><i class="plus icon"></i><label>Ajouter un objet</label></div>
    <div class="ui <?=themeClass?> compact labeled icon button" onclick="$('.ui.modal.longer.helper').modal('show')"><i class="plus icon"></i><label>Aide saisie variable (CTRL+H)</label></div>
</div>
</br>

<script>
    readItem("<?=$input?>");
</script>

<template id="tpl_dmItem">
    <div class="dmItem">
        <div class='ui <?=themeClass?> classicmain segment'>
            <h3 class="ui <?=themeClass?> header">Objet</h3>
            <div class="ui <?=themeClass?> ribbon label red">Prévisualisation ruban : <span class="itemRibbonTitle"></span></div>
            <div class="three fields">
                <div class="field required">
                    <label>Nom</label>
                    <input class="itemName" type="text" placeholder="Titre de l'objet" name="items[][title][label]" onchange="$(this).closest('.dmItem').find('.itemRibbonTitle').text($(this).val());">
                </div>
                <div class="field required">
                    <label>Couleur</label>
                    <select class="ui <?=themeClass?> fluid dropdown itemColor" name="items[][title][color]" onchange="$(this).closest('.dmItem').find('.ribbon')[0].setAttribute('class', 'ui ribbon label <?=themeClass?> '+$(this).val());">
                        <?php foreach($colours as $color):?>
                        <option value="<?=$color?>"><?=$color?></option>
                        <?php endforeach?>
                    </select>
                </div>
                <div class="inline field">
                    <div class="ui <?=themeClass?> checkbox">
                        <input type="checkbox" tabindex="0" class="hidden itemHideRibbon" value="true" name="items[][title][hide]:boolean">
                        <label>Cacher le ruban</label>
                    </div>
                </div>
            </div>
            <div class="field required">
                <label>Type</label>
                <select class="ui <?=themeClass?> fluid dropdown typeSelector" onchange="displayParameters($(this),'.dmItem','displayParameter');displayParameters($(this),'.dmItem','displaySecondParameter');" name="items[][type]">
                    <option value="list">Liste</option>
                    <option value="table">Tableau</option>
                    <option value="text">Texte</option>
                </select>
            </div>
            <parameters>
            </parameters>
            <br>
            <div class="ui <?=themeClass?> mini basic icon buttons">
                <div class="ui <?=themeClass?> compact labeled icon button" onclick="deletediv($(this),'.dmItem','l\'objet : '+$(this).closest('.dmItem').find('.itemName').val())"><i class="minus icon"></i><label>Supprimer l'objet</label></div>
            </div>
        </div>
        <br>
    </div>
</template>

<template id="tpl_parameters_table">
    <div class="displayParameter displayParameterTable">
        <h4 class="ui <?=themeClass?> header">Paramètres Tableau</h4>
        <div class="field required">
            <label>Base de la boucle</label>
            <input type="text" class="itemLoopBase" id="tags" placeholder="Chemin de la boucle : {sourceName.path}" name="items[][parameters][loopBase]">
        </div>
        <div class="ui <?=themeClass?> styled fluid accordion">
            <div class="title">
                <i class="dropdown icon"></i>
                Filtres
            </div>
            <div class="content">
                <filters>
                </filters>
                <div class="ui <?=themeClass?> mini basic icon buttons">
                    <div class="ui <?=themeClass?> compact mini labeled icon button btnAddFilterRegex" onclick="genericAdd($(this),'.content','filters','#tpl_f_regex');"><i class="plus icon"></i><label>Filtre regex</label></div>
                    <div class="ui <?=themeClass?> compact mini labeled icon button btnAddFilterCond" onclick="genericAdd($(this),'.content','filters','#tpl_f_condition');"><i class="plus icon"></i><label>Filtre condition</label></div>
                </div>
            </div>
        </div>
        <h4 class="ui <?=themeClass?> header">Affichage</h4>
        <display>
        </display>
        <br>
        <div class="ui <?=themeClass?> compact mini basic labeled icon button btnAddDisplay" onclick="genericAdd($(this),'.dmItem','display:visible','#tpl_display_field');displayParameters($(this),'.dmItem','displaySecondParameter');"><i class="plus icon"></i><label>Ajouter un champ</label></div>
    </div>
</template>

<template id="tpl_parameters_list">
    <div class="displayParameter displayParameterList">
        <h4 class="ui <?=themeClass?> header">Affichage</h4>
        <display>
        </display>
        <br>
        <div class="ui <?=themeClass?> compact mini basic labeled icon button btnAddDisplay" onclick="genericAdd($(this),'.dmItem','display:visible','#tpl_display_field');displayParameters($(this),'.dmItem','displaySecondParameter');"><i class="plus icon"></i><label>Ajouter un champ</label></div>
    </div>
</template>

<template id="tpl_parameters_text">
    <div class="displayParameter displayParameterText">
        <h4 class="ui <?=themeClass?> header">Affichage</h4>
        <div class="ui <?=themeClass?> checkbox">
            <input type="checkbox" tabindex="0" class="hidden textSegment" value="true" name="items[][parameters][segment]:boolean">
            <label>Afficher dans un segment</label>
        </div>
        <div class="field">
            <label>Text</label>
            <textarea class="itemTextarea" name=items[][print]></textarea>
        </div>
        <br>
    </div>
</template>

<template id="tpl_display_field">
    <div class="ui <?=themeClass?> grid displayfield">
        <div class="two wide column">
            <div class="ui <?=themeClass?> mini basic icon buttons">
                <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'up','.displayfield');"><i class="angle up icon"></i></div>
                <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'down','.displayfield');"><i class="angle down icon"></i></div>
                <div class="ui <?=themeClass?> red tiny icon button" onclick="deletediv($(this),'.displayfield','le champ : '+$(this).closest('.displayfield').find('.fieldName').val())"><i class="trash icon"></i></div>
            </div>
        </div>
        <div class="fourteen wide column">
            <div class="ui <?=themeClass?> styled fluid accordion">
                <div class="title">
                    <i class="dropdown icon"></i>
                    Champ <span class="fieldtitle"></span>
                </div>
                <div class="content">
                    <div class="two fields">
                        <div class="field required">
                            <label>Label</label>
                            <input class="fieldName" type="text" onchange="$(this).closest('.displayfield').find('.fieldtitle').text($(this).val());" name="items[][display][][label]">
                        </div>
                        <div class="field">
                            <div class="inline field displaySecondParameter displaySecondParameterTable">
                                <div class="ui <?=themeClass?> checkbox">
                                    <input type="checkbox" tabindex="0" class="hidden displayCollapse" value="true" name="items[][display][][collapse]:boolean">
                                    <label>Collaps</label>
                                </div>
                            </div>
                            <div class="field displaySecondParameter displaySecondParameterList">
                                <label>Icone</label>
                                <input class="displayIcon" type="text" placeholder="circle, cog, cat, ..." name="items[][display][][icon]">
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Affichage par défaut</label>
                        <input data-skip-falsy="false" type="text" class="displayPrintDefault" name="items[][display][][print][default]">
                    </div>
                    <div class="ui <?=themeClass?> classicmain segment">
                        <conditionaldisplay>
                        </conditionaldisplay>
                        <div class="ui <?=themeClass?> mini basic icon buttons">
                            <div class="ui <?=themeClass?> compact mini labeled icon button btnAddCDisplay" onclick="genericAdd($(this),'.displayfield','conditionaldisplay','#tpl_conditional_display');"><i class="plus icon"></i><label>Ajouter Affichage conditionnel</label></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="tpl_conditional_display">
    <cdisplay>
        <div class="ui <?=themeClass?> compact mini basic icon buttons">
            <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'up','cdisplay');"><i class="angle up icon"></i></div>
            <div class="ui <?=themeClass?> button" onclick="alter_order($(this),'down','cdisplay');"><i class="angle down icon"></i></div>
            <div class="ui <?=themeClass?> labeled icon button" onclick="deletediv($(this),'cdisplay','l\'affichage conditionnel')"><i class="minus icon"></i><label>Supprimer affichage conditionnel</label></div>
        </div>
        <br><br>
        <div class="field">
            <label>Affichage</label>
            <input data-skip-falsy="false" type="text" class="displayPrintConditional" name="items[][display][][print][conditional][][print]">
        </div>
        <br>
        <div class="conditions">
        </div>
        <br>
        <div class="ui <?=themeClass?> mini basic icon buttons">
            <div class="ui <?=themeClass?> compact mini labeled icon button btnAddFilterRegex" onclick="genericAdd($(this),'cdisplay','.conditions','#tpl_c_regex');"><i class="plus icon"></i><label>Ajouter regex</label></div>
            <div class="ui <?=themeClass?> compact mini labeled icon button btnAddFilterCond" onclick="genericAdd($(this),'cdisplay','.conditions','#tpl_c_condition');"><i class="plus icon"></i><label>Ajouter condition</label></div>
        </div>
        <div class="ui <?=themeClass?> divider"></div>
    </cdisplay>
</template>

<template id="tpl_c_regex">
    <filter>
        <div class="ui <?=themeClass?> grid">
            <div class="one wide column">
                <div class="field">
                    <label>Supp.</label>
                    <div class="ui <?=themeClass?> red small icon button" onclick="deletediv($(this),'filter','la regex')"><i class="trash icon"></i></div>
                </div>
            </div>
            <div class="fifteen wide column">
                <div class="three fields">
                    <div class="field required">
                        <label>Donnée</label>
                        <input class="filterData" type="text" placeholder="{sourceName.path}" name="items[][display][][print][conditional][][conditions][][data]">
                    </div>
                    <div class="field required">
                        <label>Regex</label>
                        <input class="filterRegex" type="text" placeholder="PHP REGEX" name="items[][display][][print][conditional][][conditions][][regex]">
                    </div>
                    <div class="field required">
                        <label>Résultat attendu</label>
                        <select class="ui <?=themeClass?> fluid dropdown filterExpected" onchange="" name="items[][display][][print][conditional][][conditions][][expected]">
                            <option value="true">Vrai</option>
                            <option value="false">Faux</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </filter>
</template>

<template id="tpl_c_condition">
    <filter>
        <div class="ui <?=themeClass?> grid">
            <div class="one wide column">
                <div class="field">
                    <label>Supp.</label>
                    <div class="ui <?=themeClass?> red small icon button" onclick="deletediv($(this),'filter','la condition')"><i class="trash icon"></i></div>
                </div>
            </div>
            <div class="fifteen wide column">
                <div class="four fields">
                    <div class="field">
                        <label>Donnée ou valeur</label>
                        <input data-skip-falsy="false" class="filterData" type="text" placeholder="{sourceName.path} or value" name="items[][display][][print][conditional][][conditions][][data]">
                    </div>
                    <div class="field required">
                        <label>Condition</label>
                        <select class="ui <?=themeClass?> fluid dropdown filterCondition" name="items[][display][][print][conditional][][conditions][][condition]">
                            <?php foreach($conditions as $condition):?>
                            <option value="<?=$condition?>"><?=$condition?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                    <div class="field">
                        <label>Donnée ou valeur</label>
                        <input data-skip-falsy="false" class="filterValue" type="text" placeholder="{sourceName.path} or value" name="items[][display][][print][conditional][][conditions][][value]">
                    </div>
                    <div class="field required">
                        <label>Résultat attendu</label>
                        <select class="ui <?=themeClass?> fluid dropdown filterExpected" onchange="" name="items[][display][][print][conditional][][conditions][][expected]">
                            <option value="true">Vrai</option>
                            <option value="false">Faux</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </filter>
</template>

<template id="tpl_f_regex">
    <filter>
        <div class="ui <?=themeClass?> grid">
            <div class="one wide column">
                <div class="field">
                    <label>Supp.</label>
                    <div class="ui <?=themeClass?> red small icon button" onclick="deletediv($(this),'filter','la regex')"><i class="trash icon"></i></div>
                </div>
            </div>
            <div class="fifteen wide column">
                <div class="three fields">
                    <div class="field required">
                        <label>Donnée</label>
                        <input class="filterData" type="text" placeholder="{sourceName.path}" name="items[][parameters][filters][][data]">
                    </div>
                    <div class="field required">
                        <label>Regex</label>
                        <input class="filterRegex" type="text" placeholder="PHP REGEX" name="items[][parameters][filters][][regex]">
                    </div>
                    <div class="field required">
                        <label>Résultat attendu</label>
                        <select class="ui <?=themeClass?> fluid dropdown filterExpected" onchange="" name="items[][parameters][filters][][expected]">
                            <option value="true">Vrai</option>
                            <option value="false">Faux</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </filter>
</template>

<template id="tpl_f_condition">
    <filter>
        <div class="ui <?=themeClass?> grid">
            <div class="one wide column">
                <div class="field">
                    <label>Supp.</label>
                    <div class="ui <?=themeClass?> red small icon button" onclick="deletediv($(this),'filter','la condition')"><i class="trash icon"></i></div>
                </div>
            </div>
            <div class="fifteen wide column">
                <div class="four fields">
                    <div class="field">
                        <label>Donnée ou valeur</label>
                        <input data-skip-falsy="false" class="filterData" type="text" placeholder="{sourceName.path} or value" name="items[][parameters][filters][][data]">
                    </div>
                    <div class="field required">
                        <label>Condition</label>
                        <select class="ui <?=themeClass?> fluid dropdown filterCondition" name="items[][parameters][filters][][condition]">
                            <?php foreach($conditions as $condition):?>
                            <option value="<?=$condition?>"><?=$condition?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                    <div class="field">
                        <label>Donnée ou valeur</label>
                        <input data-skip-falsy="false" class="filterValue" type="text" placeholder="{sourceName.path} or value" name="items[][parameters][filters][][value]">
                    </div>
                    <div class="field required">
                        <label>Résultat attendu</label>
                        <select class="ui <?=themeClass?> fluid dropdown filterExpected" onchange="" name="items[][parameters][filters][][expected]">
                            <option value="true">Vrai</option>
                            <option value="false">Faux</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </filter>
</template>

<template id="tpl_help">
    <p>Chaque variable doit être placée dans des balises <code>{}</code>. La variable est composée de la manière suivante : <code>{NOM_SOURCE.chemin}</code></p>
    <p>Voici la liste des valeurs spéciales que peut contenir une balise :</p>
    <ul>
        <li><code>{:GET:varName}</code> Récupère la variable dans l'URL (<code>https://site/path?varName=value</code>).</li>
        <li><code>{:LOOP:...}</code> Lorsqu'une boucle est active <code>:LOOP:</code> comprend la base de la boucle ainsi que la clé de la boucle <code>:KEY:</code></li>
        <li><code>{:KEY:}</code> Lorsque une boucle est active <code>:KEY:</code> contient la clé de la boucle. La variable spéciale <code>:KEY:</code> peut être placé n'importe où dans la balise.</li>
    </ul>
</template>

<div class="ui <?=themeClass?> longer modal helper">
    <div class="header">
        <form class="ui <?=themeClass?> form">
            <div class="fields">
                <div class="field">
                    <label>Aide</label>
                    <div class="ui <?=themeClass?> basic icon buttons">
                        <div id="defaultHelp" class="ui <?=themeClass?> compact icon button" onclick="genericAdd($(this),'.helper','#sourceHelper','#tpl_help',true);"><i class="info icon"></i></div>
                    </div>
                </div>
                <div class="field">
                    <label>Recherche</label>
                    <div id="searchengine" class="item">
                        <div class="ui <?=themeClass?> icon input">
                            <input class="searchbox" onkeyup="searchFilter()" type="text" placeholder="Filtrer tableau..."/>
                            <i class="search icon"></i>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>Obtenir</label>
                    <div class="ui <?=themeClass?> basic icon buttons">
                        <div class="ui <?=themeClass?> compact icon button" onclick="getHelp();"><i class="refresh icon"></i></div>
                    </div>
                </div>
                <div class="field required">
                    <label>Source</label>
                    <select id="selectSourceHelp" class="ui <?=themeClass?> fluid dropdown">
                        <?php foreach($sources as $source):?>
                        <option value="<?=$source?>"><?=$source?></option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
        </form>
    </div>
    <div class="scrolling content">
        <div id="requiredGet"></div>
        <div id="sourceHelper">
        </div>
    </div>
</div>
<script>$('#defaultHelp').click();</script>