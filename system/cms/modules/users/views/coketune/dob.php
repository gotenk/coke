<main>

	<section id="register" class="date-check fullScreen register">
		<div id="background-img" class="fluid-img">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="email-register">
						<div class="title">
							<h4>masukan tanggal lahir</h4>
						</div> <!-- .title -->
						<?php echo cmc_form_open('user-dob', site_url('dob'), 'id="user-register"');?>
							<div class="column">
								<div class="devide-3">
									<div class="child custom-selectbox">
										<!-- <input type="text" id="day" placeholder="DD" class="center"/> -->
										<?php echo form_dropdown('dd', $dob_day, (isset($sekarang[2])) ? ( $sekarang[2]) : '', 'class="center" id="day"' )?>
									</div> <!-- .child -->
									<div class="child">
										<!-- <input type="text" id="month" placeholder="MM" class="center"/> -->
										<?php echo form_dropdown('mm', $dob_month, (isset($sekarang[1])) ? ( $sekarang[1]) : '')?>
									</div> <!-- .child -->
									<div class="child">
										<!-- <input type="text" id="year" placeholder="YYYY" class="center"/> -->
										<?php echo form_dropdown('yy', $dob_year, (isset($sekarang[0])) ? ( $sekarang[0]) : '')?>
									</div> <!-- .child -->
								</div> <!-- .devide-3 -->
							</div> <!-- .column -->
							<div class="button-action-wrapper">
								<?php echo form_submit('f_lanjut', 'Daftar', 'class="button rounded border primary"');?>
							</div> <!-- .button-action-wrapper -->
						<?php echo form_close();?> <!-- #user-register -->
					</div> <!-- .panel -->
				</div> <!-- .row -->
			</div> <!-- #register-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->

</main>
