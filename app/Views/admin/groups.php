<form>
    <table class="ui <?=themeClass?> table">
        <thead>
            <tr>
                <th>
                    <div class="ui <?=themeClass?> form"> 
                        <div class="field required" id="newgroupname"> <input placeholder="Nom du groupe" name="[addGroup][groupname]" type="text"></div>
                    </div>
                </th>
                <th>
                    <div class="ui <?=themeClass?> form"> 
                        <div class="field"> <input placeholder="Description" name="[addGroup][description]" type="text"></div>
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
            <th>Groupe</th>
            <th>Description</th>
            <th>Utilisateurs</th>
            <th class="collapsing"></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <?php foreach($groups as $group):?>
        <tr>
            <td><?=$group['groupname']?></td>
            <td><?=$group['description']?></td>
            <td><?=implode(", ",$group['users'])?></td>
            <td>
                <div class="ui <?=themeClass?> buttons">
                <a class="ui <?=themeClass?> blue tiny icon button" href="<?=base_url()?>/admin/group/<?=$group['groupname']?>"><i class="edit icon"></i></a>
                <?php if ($group['groupname'] != "admin" && $group['groupname'] != "users"):?>
                    <div class="ui <?=themeClass?> negative tiny icon button" onclick="datamanagerRemove($(this),'<?=$group['groupname']?>','group')"><i class="trash icon"></i></div>
                <?php endif ?>
                </div>
            </td>
        </tr>
    <?php endforeach?>
    </tbody>
</table>

<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.serializejson.js');?>"></script>