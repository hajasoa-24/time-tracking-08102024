<!DOCTYPE html>
<html lang="en">
<head>
	<title>Connexion</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css"  href="<?php echo base_url('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') ?>">
	<link rel="stylesheet" type="text/css"  href="<?php echo base_url('assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/util.css')?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/main.css')?>">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url('<?=base_url("assets/images/bg-01.jpg")?>');">
					<span class="login100-form-title-1">
                        TimeTracking
					</span>
				</div>

				<form class="login100-form validate-form" method="post" action="<?php echo site_url('Auth/verify')?>">
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username requis">
						<span class="label-input100">Username</span>
						<input class="input100" type="text" name="tt_username" placeholder="Enter username">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password requis">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" name="tt_pwd" placeholder="Enter password">
						<span class="focus-input100"></span>
					</div>

					<!-- <div class="flex-sb-m w-full p-b-30">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<div>
							<a href="#" class="txt1">
								Mot de passe oublié?
							</a>
						</div>
					</div> -->

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Se connecter
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<script src="<?= base_url('assets/js/jquery/jquery-3.2.1.min.js')?>"></script>

</body>
</html>