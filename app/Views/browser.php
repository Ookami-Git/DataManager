<div class="ui <?=themeClass?> pointing below left aligned floating tiny label icon"><i class="hdd icon"></i><?=$url?></div>

<div class="ui <?=themeClass?> embed" id='browser'>
</div>

<script>
    $('.ui.embed').embed({
        url:"<?=$url?>"
    });
    var MaxHeight = Math.round($(window).height() * 0.80) + 'px';
    document.getElementById('browser').style.paddingBottom = MaxHeight ;
</script>