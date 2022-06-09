<div class='column'>
    <div class='ui <?=themeClass?> classicmain segment'>
        <?php if (!$title['hide']): ?>
        <div class='ui <?=themeClass?> <?= esc($title['color']) ?> ribbon label'><?= esc($title['label']) ?></div>
        <br>
        <?php endif ?>
        <table class="ui <?=themeClass?> selectable sortable table basic tablesearch tablesort">
            <thead>
                <tr>
                <?php foreach ($data['header'] as $column): ?>
                    <?php if ($column['collapse'] ?? false) {$column['collapse']='class="collapsing"';} else {$column['collapse']=null;}?>
                    <th <?= $column['collapse'] ?>><?= $column['label'] ?></th>
                <?php endforeach;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['data'] as $line): ?>
                    <tr>
                    <?php foreach ($line as $column): ?>
                        <?php if (is_numeric($column)) {$sortableValue="data-sort-value=\"$column\"";} else {$sortableValue=null;}?>
                        <td <?= $sortableValue ?>><?= $column ?></td>
                    <?php endforeach;?>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>