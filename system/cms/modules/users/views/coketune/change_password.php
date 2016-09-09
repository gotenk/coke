<main>
	<section id="register" class="date-check fullScreen register">
		<div id="background-img" class="fluid-img">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="email-register">
						<div class="title">
							<h4>perbarui kata sandi</h4>
						</div> <!-- .title -->						
						<?php echo cmc_form_open('user-reset-password', '', 'id="user-register"')?>
							<div class="column">
								<label for="password" class="sub-title">kata sandi<span>*</span></label>
								<input name="password" id="password" type="password" placeholder="kata sandi baru" value="<?php echo set_value('password')?>">
								<p><?php echo form_error('password');?></p>
							</div> <!-- .column -->
							<div class="column">
								<label for="re-password" class="sub-title">konfirmasi kata sandi<span>*</span></label>
								<input name="re-password" id="re-password" type="password" placeholder="ulangi kata sandi" value="<?php echo set_value('re-password')?>">
								<p><?php echo form_error('re-password');?></p>
							</div> <!-- .column -->
							<div class="button-action-wrapper">
								<input type="submit" class="button rounded border primary" name="f_ganti" value="simpan">
							</div> <!-- .button-action-wrapper -->
						<?php echo form_close()?> <!-- #user-register -->
					</div> <!-- .panel -->
				</div> <!-- .row -->
			</div> <!-- #register-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->
</main>
