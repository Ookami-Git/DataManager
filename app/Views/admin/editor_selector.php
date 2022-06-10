<h4 class="ui <?=themeClass?> horizontal divider header">
    <i class="layer group icon"></i>
    Editer ou créer une page
</h4>
<script type="text/javascript" src="<?=base_url('/assets/js/extended.js')?>"></script>
<script type="text/javascript" src="<?=base_url('/assets/js/editor.js')?>"></script>
<div class="ui <?=themeClass?> container">
    <table class="ui <?=themeClass?> celled selectable tablesearch table">
        <thead>
            <tr><th>Nom de la page</th>
            <th>Action</th>
        </tr></thead>
        <tbody>
            <?php foreach($data as $entry):?>
                <tr>
                    <td class="descName"><?=$entry['name']?></td>
                    <td>
                        <a href='<?=base_url()?>/editor/<?=$entry['name']?>/source' class='ui <?=themeClass?> blue icon button'><i class='pen icon'></i></a>
                        <a href='<?=base_url()?>/view/<?=$entry['name']?>' class='ui <?=themeClass?> olive icon button'><i class='eye icon'></i></a>
                        <a class='ui <?=themeClass?> negative icon button' onclick="deletepage($(this));"><i class='trash icon'></i></a>
                    </td>
                </tr>
            <?php endforeach?>
        </tbody>
        <tfoot>
            <tr>
                <th><div class="ui <?=themeClass?> form"> <div class="field"> <input id="newpage" class="nospace" placeholder="New Name" type="text"></div></div></th>
                <th><div class="ui <?=themeClass?> positive icon button" onclick="if($(this).closest('tr').find('#newpage').val() != '') {window.location.href = '<?=base_url()?>/editor/'+$(this).closest('tr').find('#newpage').val()+'/source'};"><i class="plus icon"></i></div></th>
            </tr>
        </tfoot>
    </table>
</div>