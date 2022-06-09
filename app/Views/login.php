<style type="text/css">
            body > .grid {
              height: 100%;
            }
            .image {
              margin-top: -100px;
            }
            .column {
              max-width: 450px;
            }
            .grid {
                height: 70%;
            }
</style>
<script>
    $(document).ready(function() {
        $('.ui.form')
            .form({
            fields: {
                email: {
                    identifier  : 'httpd_username',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Saisir votre nom d\'utilisateur'
                        }
                    ]
                },
                password: {
                    identifier  : 'httpd_password',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Saisir votre mot de passe'
                        }
                    ]
                }
            }
        });
    });
</script>
<div style="margin-top: 2em;text-align:center;" class="ui basic segment">
        <img class="logo" src="<?php echo base_url(); ?>/assets/img/logo.png" height="80"/>
    </div>
<div class="ui <?=themeClass?> middle aligned center aligned grid">
    <div class="column">
        <form class="ui <?=themeClass?> large form" method="POST" action="<?=base_url();?>/signin">
            <div class="ui <?=themeClass?> segment blurmain">
                <h2 class="ui <?=themeClass?> blue header">
                    <div class="content">
                        <?=$title?>
                    </div>
                </h2>
                <div class="field">
                    <div class="ui <?=themeClass?> left icon input">
                    <i class="user icon"></i>
                    <input type="text" name="username" placeholder="Utilisateur" value="">
                    </div>
                </div>
                <div class="field">
                    <div class="ui <?=themeClass?> left icon input">
                    <i class="lock icon"></i>
                    <input type="password" name="password" placeholder="Mot de passe" value="">
                    </div>
                </div>
                <button class="ui <?=themeClass?> fluid large blue submit button" type="submit" name="login" value="Login">Connexion</button>
            </div>
        </form>
        <?php if(session()->getFlashdata('msg')):?>
            <br>
            <div class="ui <?=themeClass?> negative message">
                <div class="header">
                    Erreur lors de l'authentification
                </div>
                <p><?= session()->getFlashdata('msg') ?></p>
            </div>
        <?php endif;?>
    </div>
</div>