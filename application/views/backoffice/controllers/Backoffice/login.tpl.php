<!DOCTYPE html>
<html lang="<?php echo $this->lang; ?>">
    <?php include $this->getPath() . 'includes' . DS . 'head.tpl.php'; ?>
    <body>
        <div id="login-page">
            <div class="container">
                <form id="login" class="form-login" action="<?php echo $this->urls->login; ?>">
                    <h2 class="form-login-heading">Administration</h2>
                    <div class="login-wrap">
                        <input type="text" id="admin-username" name="admin-username" class="form-control" placeholder="Nom" autofocus>
                        <br>
                        <input type="password" id="admin-password" name="admin-password" class="form-control" placeholder="Mot de passe">
                        <div class="checkbox">
                            <label><input type="checkbox" id="admin-cookie" name="admin-cookie">Se souvenir de moi</label>
                        </div>
                        <label class="checkbox">
                            <span class="pull-right">
                                <a data-toggle="modal" href="#forgot"> Forgot Password?</a>

                            </span>
                        </label>
                        <input type="submit" autofocus="" id="admin-login" name="admin-login" value="Connexion" placeholder="Connexion" class="btn btn-theme btn-block">
                        <img class="hide" id="login-loader" src="<?php echo $this->getUrlAsset('img'); ?>loader.gif" />
                        <input type="hidden" value="<?php echo $this->token; ?>" id="backoffice-token" name="backoffice-token">
                    </div>
                    <!-- Modal -->
                    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="forgot" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title">Forgot Password ?</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Enter your e-mail address below to reset your password.</p>
                                    <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                                </div>
                                <div class="modal-footer">
                                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                                    <button class="btn btn-theme" type="button">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- modal -->
                </form>	  	
            </div>
        </div>
        <script type="text/javascript"><?php echo $this->getJs(); ?></script>
    </body>
</html>

