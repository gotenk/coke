
<main>
	<section id="register">
		<div id="background-img" class="fluid-img register">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="social-register">
						<div class="button-action-wrapper social-button">
							<a href="{{ url:site uri="fb-connect"}}" class="button rounded login-button fb">
								<i class="social-icon fb"></i>
								<span>masuk dengan facebook</span>
							</a>
							<a href="{{url:site uri="tw-connect"}}" class="button rounded login-button tw">
								<i class="social-icon tw"></i>
								<span>masuk dengan twitter</span>
							</a>
						</div> <!-- .button-action-wrapper -->
					</div> <!-- .panel -->
					<div id="figure" class="wide panel no-transform">atau masuk dengan email</div>
					<div class="panel" id="email-register">
						<?php echo cmc_form_open('user-register', site_url('login'));?>	
							<div class="column">
								<label for="email" class="sub-title">alamat email<span>*</span></label>
								<input id="email" name="email" class="transparent" type="email" placeholder="alamat email mu">
							</div> <!-- .column -->
							<div class="column">
								<label for="password" class="sub-title">kata sandi<span>*</span></label>
								<input id="password" name="password" class="transparent" type="password" placeholder="kata sandi">
							</div> <!-- .column -->
							<?php echo form_error('email', '<div class="column allert"><p class="error">', '</p></div>'); ?>
							<!-- .column -->
							<div class="column">
								<div id="captcha">
									<div class="g-recaptcha" data-sitekey="6Ld1aikTAAAAAJf-jN1_IhnqipH_FjSXb0l8Gzrk"></div>
								</div> <!-- #captcha -->
							</div> <!-- .column -->
							<div class="button-action-wrapper login">
								<input type="submit" class="button rounded border primary" name="login" value="masuk">
							</div> <!-- .button-action-wrapper -->
							
							<span class="linkk center"><a href="{{ url:site uri="reset-password"}}">Lupa kata sandi?</a></span>
							
							<div class="border-figure"></div>
							
							<div class="button-action-wrapper column">
								<label class="sub-title">Pengguna baru?</label>
								<input type="submit" class="button rounded border primary register" name="register" value="daftar">								
							</div> <!-- .button-action-wrapper -->							
							
						<?php echo form_close();?> <!-- #user-register -->
					</div> <!-- .panel -->
				</div> <!-- .row -->
			</div> <!-- #register-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->
</main>