<div class='ui <?=themeClass?> grid'>
    <div class='two wide column'>
        <div class="ui <?=themeClass?> vertical pointing fluid menu">
        <?php foreach($users as $userItem):
            if ($user['username'] == $userItem) { $class = "active";} else { $class=null;}
        ?>
            <a class="item <?=$class?>" href="<?=base_url()?>/admin/user/<?=$userItem?>"><?=$userItem?></a>
        <?php endforeach?>
        </div>
    </div>
    <div class='fourteen wide column'>
        <div class="ui <?=themeClass?> segment classicmain">
            <form class="ui <?=themeClass?> form">
                <div class="four fields">
                    <div class="field">
                        <label>Utilisateur</label>
                        <input class="transparent" type="text" value="<?=$user['username']?>" readonly="">
                    </div>
                    <div class="field">
                        <label>Connexion</label>
                        <input class="transparent" type="text" value="<?=$user['connection']?>" readonly="">
                    </div>
                    <div class="eight wide field required">
                        <label>Groupes</label>
                        <?php if ($user['username'] == "admin"):?>
                            <input class="transparent" type="text" value="<?=implode(', ',$userRoles)?>" readonly="">
                        <?php else: ?>
                            <select name='[updateUser][<?=$user['username']?>][groups][]' class='ui <?=themeClass?> fluid search dropdown' multiple=''><option value=''>Groupes</option>
                                <?php foreach ($groups as $group):?>
                                <option value='<?=$group['groupname']?>' <?php if(in_array($group['groupname'],$userRoles)) {echo "selected";} ?>><?=$group['groupname']?></option>
                                <?php endforeach ?>
                            </select>
                        <?php endif ?>
                    </div>
                </div>
                <?php if ($user['connection'] == "local"):?>
                <div class="four fields">
                    <div class="field">
                        <label>Nouveau mot de passe</label>
                        <input class="nospace" name='[updateUser][<?=$user['username']?>][password]' id="newPass" type="password">
                    </div>
                    <div class="field">
                        <label>Confirmer</label>
                        <input class="nospace" id="confirmNewPass" type="password">
                    </div>
                </div>
                <?php endif ?>
            </form>
        </div>
        <br>
        <?php echo view("templates/parameters_footer");?>
    </div>
</div>