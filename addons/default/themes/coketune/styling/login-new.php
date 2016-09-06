<?php include 'header-fluid.php'; ?>

<main>
	<section id="register">
		<div id="background-img" class="fluid-img register">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="social-register">
						<div class="button-action-wrapper social-button">
							<a href="#" class="button rounded login-button fb">
								<i class="social-icon fb"></i>
								<span>register with facebook</span>
							</a>
							<a href="#" class="button rounded login-button tw">
								<i class="social-icon tw"></i>
								<span>register with twitter</span>
							</a>
						</div> <!-- .button-action-wrapper -->
					</div> <!-- .panel -->
					<div id="figure" class="wide panel">atau masuk dengan email</div>
					<div class="panel" id="email-register">
						<form id="user-register">
							<div class="column">
								<label for="email" class="sub-title">alamat email<span>*</span></label>
								<input id="email" class="transparent" type="email" placeholder="alamat email mu">
							</div> <!-- .column -->
							<div class="column">
								<label for="password" class="sub-title">kata sandi<span>*</span></label>
								<input id="password" class="transparent" type="password" placeholder="kata sandi baru">
							</div> <!-- .column -->
							<div class="column allert">
								<p class="error">kata sandi / email salah</p>
							</div> <!-- .column -->
							<div class="column">
								<div id="captcha">
									<div>
										<!-- CAPTCHA GOES HERE -->
										<img id="demo" src="img/coke/captcha_demo.png"/>
										<!-- END -->
									</div>
								</div> <!-- #captcha -->
							</div> <!-- .column -->
							<div class="button-action-wrapper login">
								<input type="submit" class="button rounded border primary" name="login" value="masuk">
							</div> <!-- .button-action-wrapper -->
							
							<span class="linkk center"><a href="#">Lupa kata sandi?</a></span>
							
							<div class="border-figure"></div>
							
							<div class="button-action-wrapper column">
								<label class="sub-title">Pengguna baru?</label>
								<input type="submit" class="button rounded border primary register" name="register" value="daftar">
							</div> <!-- .button-action-wrapper -->							
							
						</form> <!-- #user-register -->
					</div> <!-- .panel -->
				</div> <!-- .row -->
			</div> <!-- #register-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->
	
</main>
	
<?php include 'footer.php'; ?>	