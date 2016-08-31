<?php include 'header-fluid.php'; ?>

<main>
	
	<section id="login">
		<div id="background-img" class="fluid-img">
			<div id="login-inner" class="container">
				<div class="row">
					<div class="panel" id="social-login">
						<div class="button-action-wrapper">
							<a href="#" class="button rounded login-button fb">
								<i class="social-icon fb"></i>
								<span>login with facebook</span>
							</a>
							<a href="#" class="button rounded login-button tw">
								<i class="social-icon tw"></i>
								<span>login with twitter</span>
							</a>
						</div> <!-- .button-action-wrapper -->
					</div> <!-- .panel -->
					<div id="small-figure">OR</div>
					<div class="panel" id="email-login">
						<div class="title">
							<h4>log in with email</h4>
						</div> <!-- .title -->
						<div class="column">
							<label for="email">email</label>
							<input class="ipt" type="email" id="email" placeholder="your email address"</div>
						</div> <!-- .column -->
						<div class="column">
							<label for="password">password</label>
							<input class="ipt" type="password" id="password" placeholder="your password"</div>
						</div> <!-- .column -->
						<div class="button-action-wrapper">
							<input type="submit" class="button rounded primary" name="login" value="login">
						</div> <!-- .button-action-wrapper -->
					</div> <!-- .panel -->					
				</div> <!-- .row -->
			</div> <!-- #login-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->
	
</main>
	
<?php include 'footer.php'; ?>	