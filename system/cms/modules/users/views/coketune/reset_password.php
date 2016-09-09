<main>
	<section id="register" class="date-check fullScreen">
		<div id="background-img" class="fluid-img register">
			<div id="register-inner" class="container">
				<div class="row">
					<div id="figure" class="wide panel no-transform">Lupa Kata Sandi</div>
					<div class="panel" id="email-register">
						<div class="title">
							<h4>masukan email kamu</h4>
						</div> <!-- .title -->
						<?php echo cmc_form_open('user-register', '');?>	
						<form id="user-register">
							<div class="column">
								<input id="email" type="text" placeholder="alamat email mu" name="email" value="<?php echo set_value('email');?>">
								<p><?php echo form_error('email')?></p>
							</div> <!-- .column -->
							<div class="button-action-wrapper">
								<?php echo form_submit('f_lanjut', 'LANJUT','class="button rounded border primary"')?>							
							</div> <!-- .button-action-wrapper -->
						<?php echo form_close()?> <!-- #user-register -->
					</div> <!-- .panel -->
				</div> <!-- .row -->
			</div> <!-- #register-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->
	
</main>