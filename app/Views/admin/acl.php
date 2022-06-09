<form>
    <table class="ui <?=themeClass?> celled selectable table">
        <thead>
            <tr>
                <th>Page</th>
                <th>Groupes</th>
                <th>Utilisateurs</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $page):?>
            <tr>
                <td class="collapsing"><?=$page['name']?></td>
                <td>
                    <input type="hidden" name="[acl][<?=$page['name']?>][groups]:array" value="[]"/>
                    <select name='[acl][<?=$page['name']?>][groups][]' class='ui <?=themeClass?> fluid search dropdown' multiple='' onchange='$(this).closest("td").find("input:first").addClass("newvalue");'><option value=''>Groupes</option>
                        <?php foreach ($groups as $group):?>
                        <option value='<?=$group['groupname']?>' <?php if (isset($acl[$page['name']]['groups']) && in_array($group['groupname'],$acl[$page['name']]['groups'])) {echo "selected";}?>><?=$group['groupname']?></option>
                        <?php endforeach ?>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="[acl][<?=$page['name']?>][users]:array" value="[]"/>
                    <select name='[acl][<?=$page['name']?>][users][]' class='ui <?=themeClass?> fluid search dropdown' multiple='' onchange='$(this).closest("td").find("input:first").addClass("newvalue");'><option value=''>Utilisateurs</option>
                        <?php foreach ($users as $user):?>
                        <option value='<?=$user['username']?>' <?php if (isset($acl[$page['name']]['users']) && in_array($user['username'],$acl[$page['name']]['users'])) {echo "selected";}?>><?=$user['username']?></option>
                        <?php endforeach ?>
                    </select>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</form>

<?php echo view("templates/parameters_footer");?>