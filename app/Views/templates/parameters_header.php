<script type="text/javascript" src="<?=base_url('/assets/js/extended.js')?>"></script>
<script type="text/javascript" src="<?=base_url('/assets/js/parameters.js')?>"></script>
<div class='ui <?=themeClass?> grid'>
    <div class='two wide column'>
        <div class="ui <?=themeClass?> vertical pointing fluid menu">
        <?php foreach($menu as $item):
            if (in_array($page,$item['page'])) { $class = "active";} else { $class=null;}
            if (isset($item['icon'])) { $icon = "<i class='{$item['icon']} icon'></i>";} else { $icon=null;}
        ?>
            <a class="item <?=$class?>" href="<?=$item['href']?>"><?=$icon?><?=$item['label']?></a>
        <?php endforeach?>
        </div>
    </div>
    <div class='fourteen wide column'>