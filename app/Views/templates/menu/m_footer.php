<div class='right menu'>
        <div id="divVisibility" class="item" style="display: none;">
            <button id="btnVisibility" class="ui <?=themeClass?> mini icon button" onclick="toggle_visibility();" title="Alterner l'affichage des lignes caché"><i id="iconVisibility" class="eye slash icon"></i></button>
        </div>

        <div id="divCalendar" class="ui <?=themeClass?>  calendar item" style="display: none;">
            <div class="ui <?=themeClass?> input left icon mini  transparent">
                <i class="calendar icon"></i>
                <input id="inputCalendar" type="text" placeholder="Date/Time">
            </div>
        </div>

        <div id="searchengine" class="item" style="display: none;">
            <div class="ui <?=themeClass?> mini transparent  icon input">
                <input class="searchbox" onkeyup="searchFilter()" type="text" placeholder="Filtrer tableau..."/>
                <i class="search icon"></i>
            </div>
        </div>

        <script type="text/javascript" src="<?=base_url('assets/js/table2csv.js')?>"></script>

        <div  id="btnExport" class="ui dropdown icon item <?=themeClass?>" style="display: none;">
            <i class="download icon"></i>
            <div id="menuExportTable" class="menu">
            </div>
        </div>

        <div class="ui <?=themeClass?> dropdown icon item">
            <i class="bars icon"></i>
            <div class="menu">
                <div class="header"><?= $user ?></div>
                <a class="dm item" href="<?=base_url("/account")?>"><i class="user icon"></i>Mon compte</a>
                <a class="dm item" href="<?=base_url("/logout")?>"><i class="sign out alternate icon"></i>Deconnexion</a>
                <?php if (in_array("admin",session()->get("roles")) == true || session()->get("username") == "admin"): ?>
                <div class="divider"></div>
                <div class="header">Administration</div>
                <a class="dm item" href="<?=base_url("/admin/general")?>"><i class="tools icon"></i>Paramètres</a>
                <a class="dm item" href="<?=base_url("/editor")?>"><i class="layer group icon"></i>Modifier pages</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<template id="tpl_itemMenuExport">
    <a class="item" onclick="$('.tableexport.'+$(this).find('.exportTitle').text()).table2csv({ filename: 'export_'+$(this).find('.exportTitle').text()+'.csv', separator: ';',  newline: '\n',  quoteFields: true,  excludeColumns: '.access_column',  excludeRows: ''});">Export CSV <span class="exportTitle">id<span></a>
</template>