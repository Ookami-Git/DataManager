<h4 id="descName" class="ui <?=themeClass?> horizontal divider header"><?=$input?></h4>
<script type="text/javascript" src="<?=base_url('/assets/js/extended.js')?>"></script>
<script type="text/javascript" src="<?=base_url('/assets/js/editor.js')?>"></script>
<div class='ui <?=themeClass?> grid'>
    <div class='two wide column'>
        <div class="ui <?=themeClass?> vertical pointing fluid menu">
            <a class="item" href="<?=base_url()?>/editor"><i class="arrow circle left icon"></i></i>Editer une autre page</a>
        </div>
        <div class="ui <?=themeClass?> vertical pointing fluid menu">
            <a class="item" href="<?=base_url()?>/editor/<?=$input?>/source"><i class='sitemap icon'></i>Sources</a>
            <a class="item" href="<?=base_url()?>/editor/<?=$input?>/item"><i class='th icon'></i>Objets</a>
            <a class="item" href="<?=base_url()?>/editor/<?=$input?>/presentation"><i class='shapes icon'></i>Présentation</a>
        </div>
    </div>
    <div class='fourteen wide column'>