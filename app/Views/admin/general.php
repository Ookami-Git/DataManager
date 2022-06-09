<form class="ui <?=themeClass?> form">
    <div class='ui <?=themeClass?> classicmain segment'>
        <div class="three fields">
            <div class="field">
                <label>Nom de l'application</label>
                <div class="ui <?=themeClass?> input left icon">
                    <i class="passport icon"></i>
                    <input type="text" name="[name]" placeholder="Nom du projet" value="<?=$name?>">
                </div>
            </div>
            <div class="field">
                <label>Theme par défaut</label>
                <select class="ui <?=themeClass?> fluid dropdown pageSelector" id="theme" name="[theme]">
                    <option value="dark" <?php if ($theme == "dark") {echo "selected";}?>>Sombre</option>
                    <option value="light" <?php if ($theme == "light") {echo "selected";}?>>Clair</option>
                </select>
            </div>
            <div class="field">
                <label>Page d'accueil</label>
                <select class="ui <?=themeClass?> fluid dropdown pageSelector" name="[defaultPage]">
                    <option disabled value <?php if (empty($defaultPage)) {echo "selected";}?>> Aucune </option>
                    <?php foreach($pages as $page):?>
                    <option value="<?=$page['name']?>" <?php if ($page == $defaultPage) {echo "selected";}?>><?=$page['name']?></option>
                    <?php endforeach?>
                </select>
            </div>
        </div>
    </div>
</form>

<?php echo view("templates/parameters_footer");?>