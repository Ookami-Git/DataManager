<form class="ui <?=themeClass?> form">
    <div class='ui <?=themeClass?> classicmain segment'>
        <div class="inline field">
            <div class="ui <?=themeClass?> checkbox">
                <input type="checkbox" tabindex="0" class="hidden" value="true" name="[ldapEnable]:boolean" <?php if ($ldapEnable) {echo "checked";}?>>
                <label>Activer LDAP</label>
            </div>
        </div>
        <div class="four fields">
            <div class="field">
                <label>Serveur LDAP</label>
                <input type="text" name="[ldapHost]" value="<?=$ldapHost?>">
            </div>
            <div class="field">
                <label>Port</label>
                <input type="number" name="[ldapPort]" value="<?=$ldapPort?>">
            </div>
            <div class="field">
                <label>Base DN</label>
                <input type="text" name="[ldapBaseDN]" value="<?=$ldapBaseDN?>">
            </div>
            <div class="field">
                <label>SSL</label>
                <div class="ui <?=themeClass?> checkbox">
                    <input type="checkbox" tabindex="0" class="hidden" value="true" name="[ldapSsl]:boolean" <?php if ($ldapSsl) {echo "checked";}?>>
                </div>
            </div>  
        </div>
    </div>
</form>

<?php echo view("templates/parameters_footer");?>