        <div class='ui <?=themeClass?> classicmain segment'>
            <button id="save" class="ui <?=themeClass?> compact positive labeled icon button disabled" onclick="save();">
                <i class="save icon"></i>
                Enregistrer
            </button>
        </div>
    </div>
</div>

<div id="helpmodal" class="ui <?=themeClass?> modal ">
    <div id="help_header" class="header  classicmain">Header</div>
    <div id="help_content" class="scrolling content  classicmain"></div>
</div>
<script type="text/javascript" src="<?php echo base_url('/assets/js/jquery.serializejson.js');?>"></script>
<script>
$('.ui.accordion').accordion();
$('.ui.checkbox').checkbox();
$('.ui.dropdown').dropdown();
</script>