<div class='column'>
    <div class='ui <?=themeClass?> classicmain segment'>
        <?php if (!$title['hide']): ?>
        <div class='ui <?=themeClass?> <?= esc($title['color']) ?> ribbon label'><?= esc($title['label']) ?></div>
        <br>
        <?php endif ?>
        <table class="ui <?=themeClass?> selectable table basic tablesearch">
            <tbody>
                <?php foreach ($data as $line): ?>
                    <tr>
                        <td class="collapsing">
                        <?php if (isset($line['icon'])): ?>
                            <i class="<?= esc($line['icon']) ?> icon"></i>
                        <?php endif ?>
                        </td>
                        <td class="collapsing"><h4><?= esc($line['label']) ?></h4></td>
                        <td><?= $line['display'] ?></td>
                    </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>