
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(<?php echo base_url('assets/images/bg-01.jpg')?>);">
					<span class="login100-form-title-1">
						Connexion - TimeTracking
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

					<div class="flex-sb-m w-full p-b-30">
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
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Se connecter
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
