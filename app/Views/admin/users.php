<form>
    <table class="ui <?=themeClass?> table">
        <thead>
            <tr>
                <th>
                    <div class="ui <?=themeClass?> form"> 
                        <div class="field required" id="newusername"> <input placeholder="Nom d'utilisateur" name="[addUser][username]" type="text"></div>
                    </div>
                </th>
                <th>
                    <div class="ui <?=themeClass?> form"> 
                        <div class="field required" id="newuserpass"> <input placeholder="Mot de passe" name="[addUser][password]" type="password"></div>
                    </div>
                </th>
                <th><div class="ui <?=themeClass?> form">
                    <div class="field required"> 
                        <select name='[addUser][groups][]' class='ui <?=themeClass?> fluid search dropdown' multiple=''><option value=''>Groupes</option>
                            <?php foreach ($groups as $group):?>
                            <option value='<?=$group['groupname']?>' <?php if($group['groupname'] == "users") {echo "selected";} ?>><?=$group['groupname']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div></th>
                <th>
                    <div class="field required">
                        <select class="ui <?=themeClass?> fluid dropdown pageSelector" name="[addUser][connection]" id="connectionType">
                            <?php if(isset($ldap) && $ldap):?>
                            <option value="ldap">LDAP</option>
                            <?php endif?>
                            <option value="local">Local</option>
                        </select>
                    </div>
                </th>
                <th class="collapsing"><div class="ui <?=themeClass?> positive labeled icon button" onclick="datamanagerAdd();"><i class="plus icon"></i>Ajouter</div></th>
            </tr>
        </thead>
    </table>
</form>

<table class="ui <?=themeClass?> celled selectable tablesearch table">
    <thead>
        <tr>
            <th>Utilisateur</th>
            <th>Groupes</th>
            <th>Connexion</th>
            <th class="collapsing"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($users as $user):?>
        <tr>
            <td><?=$user['username']?></td>
            <td><?=implode(", ",$user['groups'])?></td>
            <td><?=$user['connection']?></td>
            <td>
                <div class="ui <?=themeClass?> buttons">
                <a class="ui <?=themeClass?> blue tiny icon button" href="<?=base_url()?>/admin/user/<?=$user['username']?>"><i class="edit icon"></i></a>
                <?php if ($user['username'] != "admin"):?>
                    <div class="ui <?=themeClass?> negative tiny icon button" onclick="datamanagerRemove($(this),'<?=$user['username']?>','user')"><i class="trash icon"></i></div>
                <?php endif ?>
                </div>
            </td>
        </tr>
    <?php endforeach?>
    </tbody>
</table>

<script>passwordProperties();</script>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.serializejson.js');?>"></script>
