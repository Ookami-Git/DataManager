<div class='column'>
    <?php if ($data['segment']): ?>
        <div class='ui <?=themeClass?> classicmain segment'>
        <?php if (!$title['hide']): ?>
        <div class='ui <?=themeClass?> <?= esc($title['color']) ?> ribbon label'><?= esc($title['label']) ?></div>
        <br>
        <?php endif ?>
    <?php endif ?>
        <?=$data['print']?>
    <?php if ($data['segment']): ?>
    </div>
    <?php endif ?>
</div>