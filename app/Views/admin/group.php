<div class='ui <?=themeClass?> grid'>
    <div class='two wide column'>
        <div class="ui <?=themeClass?> vertical pointing fluid menu">
        <?php foreach($groups as $groupItem):
            if ($group['groupname'] == $groupItem) { $class = "active";} else { $class=null;}
        ?>
            <a class="item <?=$class?>" href="<?=base_url()?>/admin/group/<?=$groupItem?>"><?=$groupItem?></a>
        <?php endforeach?>
        </div>
    </div>
    <div class='fourteen wide column'>
        <div class="ui <?=themeClass?> segment classicmain">
            <form class="ui <?=themeClass?> form">
                <div class="four fields">
                    <div class="field">
                        <label>Groupe</label>
                        <input class="transparent" type="text" value="<?=$group['groupname']?>" readonly="">
                    </div>
                    <div class="twelve wide field">
                        <label>Description</label>
                        <input type="text" name='[updateGroup][<?=$group['groupname']?>][description]' value="<?=$group['description']?>">
                    </div>
                </div>
                <div class="field">
                    <label>Utilisateurs</label>
                    <select name='[updateGroup][<?=$group['groupname']?>][users][]' class='ui <?=themeClass?> fluid search dropdown' multiple=''><option value=''>Groupes</option>
                        <?php foreach ($users as $user):?>
                        <option value='<?=$user['username']?>' <?php if(in_array($user['username'],$userRoles)) {echo "selected";} ?>><?=$user['username']?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </form>
        </div>
        <br>
        <?php echo view("templates/parameters_footer");?>
    </div>
</div>