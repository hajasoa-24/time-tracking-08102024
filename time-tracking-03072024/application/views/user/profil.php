
<div class="container">
    <div class="row">
        <div class="col-md-12 title-page">
            <h1>Profil Utilisateur</h1>
        </div>
    </div>
    <?php if(!empty($msg) && $msg['err'] == false) : ?>
        <div class="row">
            <div class="col-md-6 mx-auto text-center my-3 alert alert-success alert-dismissible fade show">
                <span><?= (isset($msg['message'])) ? $msg['message'] : 'Operation réussie' ?></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    <?php endif; ?>
    <?php if(!empty($msg) && $msg['err'] == true) : ?>
        <div class="row">
            <div class="col-md-6 mx-auto text-center my-3 alert alert-danger alert-dismissible fade show">
                <span><?= (isset($msg['message'])) ? $msg['message'] : 'Erreur survenue' ?></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>é"-
        </div>
    <?php endif; ?>
    <form action="<?=site_url('user/updateProfil')?>" method="post">
        <div class="row content-page">
        
            <div class="col-md-6">
                <div class="form-group form-row">
                    <label for="usr_nom" class="col-md-2 col-form-label">Nom</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="usr_nom" name="usr_nom" value="<?=$user->usr_nom?>">
                    </div>
                </div>

                <div class="form-group form-row">
                    <label for="usr_prenom" class="col-md-2 col-form-label">Prénom</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="usr_prenom" name="usr_prenom" value="<?=$user->usr_prenom?>">
                    </div>
                </div>

                <div class="form-group form-row">
                    <label for="usr_email" class="col-md-2 col-form-label">Email</label>
                    <div class="col-md-10">
                        <input type="email" class="form-control" id="usr_email" name="usr_email" value="<?=$user->usr_email?>">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group form-row">
                    <label for="usr_matricule" class="col-md-2 col-form-label">Matricule</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="usr_matricule" name="usr_matricule" disabled value="<?=$user->usr_matricule?>">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label for="usr_initiale" class="col-md-2 col-form-label">Initiale</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="usr_initiale" name="usr_initiale" disabled value="<?=$user->usr_initiale?>">
                    </div>
                </div>
                <div class="form-group form-row">
                    <label for="usr_ingress" class="col-md-2 col-form-label">Ingress</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="usr_ingress" name="usr_ingress" disabled value="<?=$user->usr_ingress?>">
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button class="btn btn-sm btn-primary" name="updateProfileUser" >Modifier</button>
            </div>
        
        </div>
    </form>
    <hr/>
    <form action="<?=site_url('user/updateUserPassword')?>" method="post">
        <div class="row content-page">
        
            <div class="col-md-6">
                <div class="form-group form-row">
                    <label for="usr_oldpwd" class="col-md-4 col-form-label">Ancien mot de passe</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control" id="usr_oldpwd" name="usr_oldpwd" required>
                    </div>
                </div>

                <div class="form-group form-row">
                    <label for="usr_newpwd" class="col-md-4 col-form-label">Mot de passe</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control" id="usr_newpwd" name="usr_newpwd" required>
                    </div>
                </div>
                <div class="form-group form-row">
                    <label for="usr_pwdconfirm" class="col-md-4 col-form-label">Confirmation</label>
                    <div class="col-md-8">
                        <input type="password" class="form-control" id="usr_pwdconfirm" name="usr_pwdconfirm" required>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button class="btn btn-sm btn-primary" name="updatePwdUser" >Mettre à jour mon mot de passe</button>
                <!--<button class="btn btn-sm btn-warning" name="clearpwd" id="clearpwd">Effacer</button>-->
            </div>   
        </div>
    </form>
    
</div> 

<script type="text/javascript">
    var password = document.getElementById("usr_newpwd")
    , confirm_password = document.getElementById("usr_pwdconfirm");

    function validatePassword(){
        if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Les mots de passe ne correspondent pas");
        } else {
            confirm_password.setCustomValidity('');
        }
    }

    password.onchange = validatePassword;
    confirm_password.onkeyup = validatePassword;
</script>