<?php echo validation_errors();?>
<main>
	<section id="login">
		<div id="background-img" class="fluid-img">
			<div id="login-inner" class="container">
				<div class="row">
					<div class="panel" id="social-login">
						<div class="button-action-wrapper social-button">
							<a href="{{ url:site uri="fb-connect"}}" class="button rounded login-button fb">
								<i class="social-icon fb"></i>
								<span>login with facebook</span>
							</a>
							<a href="{{ url:site uri="tw-connect"}}" class="button rounded login-button tw">
								<i class="social-icon tw"></i>
								<span>login with twitter</span>
							</a>
						</div> <!-- .button-action-wrapper -->
					</div> <!-- .panel -->
					<div id="figure" class="wide panel">or</div>
					
					<div class="panel" id="email-login">
						<?php echo cmc_form_open('user-login', site_url('login'));?>						
						<div class="title">
							<h4>log in with email</h4>
						</div> <!-- .title -->
						<div class="column">
							<label for="email">email</label>
							<input class="ipt" type="text" id="email" placeholder="your email address" name="email">
							<?php #echo form_error('email');?>
						</div> <!-- .column -->
						<div class="column">
							<label for="password">password</label>
							<input class="ipt" type="password" id="password" placeholder="your password" name="password">
							<?php #echo form_error('password');?>
						</div> <!-- .column -->
						<div class="button-action-wrapper">
							<input type="submit" class="button rounded primary" name="login" value="login">
						</div> <!-- .button-action-wrapper -->
						<?php echo form_close();?>
					</div> <!-- .panel -->						
				</div> <!-- .row -->
			</div> <!-- #login-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->
	
</main>