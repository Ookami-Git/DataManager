<script src="<?=base_url('/assets/js/profile.js')?>"></script>
<script src="<?=base_url('/assets/js/extended.js')?>"></script>

<div class='ui <?=themeClass?> center aligned one column grid'>
    <div class="ui <?=themeClass?> segment classicmain">
        <center><div class="ui <?=themeClass?> massive circular label color<?php echo strtoupper(($user['username'][0])); ?>"><?php echo strtoupper(($user['username'][0])); ?></div></center>
        <form class="ui <?=themeClass?> form">
            <table class="ui <?=themeClass?> very basic collapsing celled table">
                <tbody>
                    <tr>
                        <th class="collapsing"><i class="user icon"></i> Utilisateur</th>
                        <td><?=$user['username']?></td>
                    </tr>
                    <tr>
                        <th><i class="users icon"></i> Groupes </th>
                        <td><?=implode(', ',$userRoles)?></td>
                    </tr>
                    <tr>
                        <th class="collapsing"><i class="lightbulb icon"></i> Theme</th>
                        <td>
                            <div class="field">
                                <label>Theme par défaut</label>
                                <select class="ui <?=themeClass?> fluid dropdown pageSelector" id="theme" name="[theme]">
                                    <option value="dark" <?php if ($user['theme'] == "dark") {echo "selected";}?>>Sombre</option>
                                    <option value="light" <?php if ($user['theme'] == "light") {echo "selected";}?>>Clair</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <?php if ($user['connection'] == "local"):?>
                    <tr>
                        <th class="collapsing"><i class="lock icon"></i> Changer mot de passe</th>
                        <td>
                            <div class="fields">
                                <div class="field">
                                    <label>Nouveau mot de passe</label>
                                    <input class="nospace" name='[password]' id="newPass" type="password">
                                </div>
                                <div class="field">
                                    <label>Confirmer</label>
                                    <input class="nospace" id="confirmNewPass" type="password">
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </form>
        <br>
        <button id="save" class="ui <?=themeClass?> compact positive labeled icon button disabled" onclick="save();">
            <i class="save icon"></i>
            Enregistrer
        </button>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.serializejson.js');?>"></script>
