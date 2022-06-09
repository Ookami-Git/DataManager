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
        <div id="btnExport" class="item" style="display: none;">
            <button class="ui <?=themeClass?> primary mini button" onclick="$('.tableexport').table2csv({ filename: 'export.csv', separator: ';',  newline: '\n',  quoteFields: true,  excludeColumns: '.access_column',  excludeRows: ''});"><i class="download icon"></i> Export</button>
        </div>

        <div class="ui <?=themeClass?>  dropdown icon item">
            <i class="bars icon"></i>
            <div class="menu">
                <div class="header"><?= $user ?></div>
                <a class="item" href="<?=base_url("/account")?>"><i class="user icon"></i>Mon compte</a>
                <a class="item" href="<?=base_url("/logout")?>"><i class="sign out alternate icon"></i>Deconnexion</a>
                <?php if (in_array("admin",session()->get("roles")) == true || session()->get("username") == "admin"): ?>
                <div class="divider"></div>
                <div class="header">Administration</div>
                <a class="item" href="<?=base_url("/admin/general")?>"><i class="tools icon"></i>Paramètres</a>
                <a class="item" href="<?=base_url("/editor")?>"><i class="layer group icon"></i>Modifier pages</a>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>