<table class="ui <?=themeClass?> celled selectable tablesearch table">
    <thead>
        <tr>
            <th></th>
            <th>Chemin</th>
            <th>Valeur</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($source as $path => $value):?>
        <tr>
            <td class="collapsing"><div class="ui <?=themeClass?> mini icon button" onclick="navigator.clipboard.writeText('{<?=$path?>}');$('.ui.modal.longer.helper').modal('hide');"><i class="copy icon"></i></div></td>
            <td>{<?=$path?>}</td>
            <td><?=$value?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>