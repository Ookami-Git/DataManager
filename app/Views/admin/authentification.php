<form class="ui <?=themeClass?> form">
    <div class='ui <?=themeClass?> classicmain segment'>
        <div class="inline field">
            <div class="ui <?=themeClass?> checkbox">
                <input type="checkbox" tabindex="0" class="hidden" value="true" name="[ldapEnable]:boolean" <?php if ($ldapEnable) {echo "checked";}?>>
                <label>Activer LDAP</label>
            </div>
        </div>
        <div class="ui horizontal divider">
            Serveur et recherche
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
                <label>Filtre de recherche LDAP sur login</label>
                <input type="text" name="[ldapFilter]" placeholder="samaccountname, mail, dn ..." value="<?=$ldapFilter?>">
            </div>
        </div>
        <div class="ui horizontal divider">
            SSL/TLS
        </div>
        <div class="four fields">
            <div class="field">
                <label>SSL (LDAPS)</label>
                <div class="ui <?=themeClass?> checkbox">
                    <input type="checkbox" tabindex="0" class="hidden" value="true" name="[ldapSsl]:boolean" <?php if ($ldapSsl) {echo "checked";}?>>
                </div>
            </div> 
            <div class="field">
                <label>Vérifier le certificat</label>
                <div class="ui <?=themeClass?> checkbox">
                    <input type="checkbox" tabindex="0" class="hidden" value="true" name="[ldapCheckCert]:boolean" <?php if ($ldapCheckCert) {echo "checked";}?>>
                </div>
            </div>  
        </div> 
        <div class="ui horizontal divider">
            Authentification
        </div>
        <div class="four fields">
            <div class="field">
                <label>Anonyme</label>
                <div class="ui <?=themeClass?> checkbox">
                    <input type="checkbox" tabindex="0" class="hidden" value="true" name="[ldapAnonymous]:boolean" <?php if ($ldapAnonymous) {echo "checked";}?>>
                </div>
            </div> 
            <div class="field">
                <label>Utilisateur (DN)</label>
                <input type="text" name="[ldapUser]" value="<?=$ldapUser?>">
            </div>
            <div class="field">
                <label>Mot de passe</label>
                <input type="password" name="[ldapPassword]" value="<?=$ldapPassword?>">
            </div>
        </div>
    </div>
</form>

<?php echo view("templates/parameters_footer");?>