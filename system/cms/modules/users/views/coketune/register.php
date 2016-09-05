<?php echo validation_errors()?>
<?php 
	if($dob_err){
		echo $dob_err;
	}
?>
<main>
	<section id="register">
		<div id="background-img" class="fluid-img">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="social-register">
						<div class="button-action-wrapper social-button">
							<a href="<?php echo site_url('fb-connect')?>" class="button rounded login-button fb">
								<i class="social-icon fb"></i>
								<span>register with facebook</span>
							</a>
							<a href="<?php echo site_url('tw-connect')?>" class="button rounded login-button tw">
								<i class="social-icon tw"></i>
								<span>register with twitter</span>
							</a>
						</div> <!-- .button-action-wrapper -->
					</div> <!-- .panel -->
					<div id="figure" class="wide panel">or</div>
					<div class="panel" id="email-register">
						<div class="title">
							<h4>daftar</h4>
						</div> <!-- .title -->						
						<?php echo form_open('', 'id="user-register"');?>
							<div class="column">
								<label for="username" class="sub-title">nama lengkap<span>*</span></label>
								<?php
									$name_value = '';
									if($this->input->post('name')){
										$name_value = $this->input->post('name');
									}else{
										if(isset($session['display_name'])){
											$name_value = $session['display_name'];
										}
									}											
								?>
								<input id="username" type="text" placeholder="nama lengkapmu" name="name" value="<?php echo $name_value;?>">
							</div> <!-- .column -->
							<div class="column">
								<label for="email" class="sub-title">alamat email<span>*</span></label>
								<?php
									$email_value = '';
									if($this->input->post('email')){
										$email_value = $this->input->post('email');
									}else{
										if(isset($session['email'])){
											$email_value = $session['email'];
										}
									}											
								?>
								<input id="email" type="text" placeholder="alamat email mu" name="email" value="<?php echo $email_value;?>">
							</div> <!-- .column -->
							<div class="column">
								<label for="password" class="sub-title">kata sandi<span>*</span></label>
								<input id="password" type="password" placeholder="kata sandi baru" name="password" value="<?php echo set_value('password');?>">
							</div> <!-- .column -->
							<div class="column">
								<label for="re-password" class="sub-title">konfirmasi kata sandi<span>*</span></label>
								<input id="re-password" type="password" placeholder="ulangi kata sandi" name="re-password" value="<?php echo set_value('re-password');?>">
							</div> <!-- .column -->
							<div class="column">
								<label for="phone" class="sub-title">nomer ponsel<span>*</span></label>
								<input id="phone" type="text" placeholder="08X-XXXXXXXXX" name="phone" value="<?php echo set_value('phone');?>">
							</div> <!-- .column -->
							<div class="column half">
								<label class="sub-title">jenis kelamin<span>*</span></label>
								<div class="custom-radio-button">
								    <input type="radio" id="m-option" name="gender" value="m" <?php echo ($this->input->post('gender') == 'male') ? 'checked' : '';?>>
								    <label for="m-option"><span></span>Laki-laki</label>
								    <div class="check"></div>								    
								</div> <!-- .custom-radio-button -->
								<div class="custom-radio-button">
								    <input type="radio" id="f-option" name="gender" value="f" <?php echo ($this->input->post('gender') == 'female') ? 'checked' : '';?>>
								    <label for="f-option"><span></span>Perempuan</label>
								    <div class="check"></div>
								</div> <!-- .custom-radio-button -->
							</div> <!-- .column -->
							<div class="column">
								<label class="sub-title">tanggal lahir<span>*</span></label>
								<div class="devide-3">
									<div class="child">
										<input type="text" id="day" placeholder="DD" class="center" name="dd" value="<?php echo (isset($dob_ar[2])) ? $dob_ar[2] : '' ;?>"/>
									</div> <!-- .child -->
									<div class="child">
										<input type="text" id="month" placeholder="MM" class="center" name="mm" value="<?php echo (isset($dob_ar[1])) ? $dob_ar[1] : '' ;?>"/>
									</div> <!-- .child -->
									<div class="child">
										<input type="text" id="year" placeholder="YYYY" class="center" name="yy" value="<?php echo (isset($dob_ar[0])) ? $dob_ar[0] : '' ;?>"/>
									</div> <!-- .child -->									
								</div> <!-- .devide-3 -->
							</div> <!-- .column -->
							<div class="column">
								<label for="code-id" class="sub-title">kode unik<span>*</span></label>
								<input id="code-id" type="text" placeholder="CokeTune_0431xxxx-xxxx" name="kode_unik">
							</div> <!-- .column -->
							<div class="column">
								<label for="code-tr" class="sub-title">kode transaksi<span>*</span></label>
								<input id="code-tr" type="text" placeholder="CokeTune_0431xxxx-xxxx" name="kode_transaksi">
							</div> <!-- .column -->
							<div class="column">
								<div id="captcha">									
									<div style="margin-top: 10px;" class="test">
	                                    <script type="text/javascript">
	                                        var verifyCallback = function(response) {
	                                            //alert(response);
	                                            $('input[name="recaptcha_response_field"]').val(response);
	                                        };

	                                        var onloadCallback = function() {
	                                            grecaptcha.render('html_element', {
	                                                'sitekey' : '<?php echo Settings::get("recaptcha_public_key"); ?>',
	                                                'callback' : verifyCallback,
	                                                'theme' : 'light'
	                                            });
	                                        };
	                                    </script>
	                                    <div id="html_element" style="margin:0 auto;"></div>
	                                    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
	                                    <input type="hidden" name="recaptcha_response_field" value="" readonly="readonly" />
	                                </div>
								</div> <!-- #captcha -->
							</div> <!-- .column -->
							<div class="column">
								<div class="custom-check-button">
								    <input type="checkbox" id="terms" name="term" <?php echo ($this->input->post('term') == 'on') ? 'checked' : '';?>>
								    <label for="terms">
								    		<span></span>
								    	</label>
								    <span class="text">Saya telah memahami dan menyetujui Syarat dan Ketentuan Promosi</span>
								</div> <!-- .custom-radio-button -->
							</div> <!-- .column -->
							<div class="button-action-wrapper">
								<input type="submit" class="button rounded border primary" name="register" value="daftar">
							</div> <!-- .button-action-wrapper -->
						<?php echo form_close();?> <!-- #user-register -->
					</div> <!-- .panel -->
				</div> <!-- .row -->
			</div> <!-- #register-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #login -->
</main>	