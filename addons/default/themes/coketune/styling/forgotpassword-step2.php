<?php include 'header-fluid.php'; ?>

<main>
	
	<section id="register" class="date-check fullScreen register">
		<div id="background-img" class="fluid-img">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="email-register">
						<div class="title">
							<h4>perbarui kata sandi</h4>
						</div> <!-- .title -->
						<form id="user-register">
							<div class="column">
								<label for="password" class="sub-title">kata sandi<span>*</span></label>
								<input id="password" type="password" placeholder="kata sandi baru">
							</div> <!-- .column -->
							<div class="column">
								<label for="re-password" class="sub-title">konfirmasi kata sandi<span>*</span></label>
								<input id="re-password" type="password" placeholder="ulangi kata sandi">
							</div> <!-- .column -->
							<div class="button-action-wrapper">
								<input type="submit" class="button rounded border primary" name="register" value="daftar">
							</div> <!-- .button-action-wrapper -->
						</form> <!-- #user-register -->
					</div> <!-- .panel -->
				</div> <!-- .row -->
			</div> <!-- #register-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->
	
</main>
	
<?php include 'footer.php'; ?>	