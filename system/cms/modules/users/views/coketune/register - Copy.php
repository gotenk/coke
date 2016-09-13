<main>
	<section id="register">
		<div id="background-img" class="fluid-img register">
			<div id="register-inner" class="container">
				<div class="row">

					<?php if (isset($session['twitter_id'])) { $display_name = explode(' ', $session['display_name']); ?>
					<div class="panel profile" id="social-register">
						<div class="userProfile-image">
							<img src="<?php echo $session['image_url_https']?>"/>
						</div> <!-- .image -->
						<div class="userProfile-info">
							<div class="name"><?php echo $display_name[0]?></div>
							<div class="detail"><span class="gender"></span></div>
							<div class="detail"><span class="username-tw">@<?php echo $session['screen_name']?></span></div>
						</div> <!-- .userProfile-info -->
					</div>
					<?php } else  if (isset($session['fb_id'])) { $display_name = explode(' ', $session['display_name']); ?>
					<div class="panel profile" id="social-register">
						<div class="userProfile-image">
							<img src="<?php echo $session['image_url']?>"/>
						</div> <!-- .image -->
						<div class="userProfile-info">
							<div class="name"><?php echo $display_name[0]?></div>
							<div class="detail"><span class="gender"><?php echo $session['gender']?></span></div>
						</div> <!-- .userProfile-info -->
					</div> <!-- .panel -->
					<?php } else { ?>

					<div class="panel" id="social-register">
						<div class="button-action-wrapper social-button">
							<a href="<?php echo site_url('fb-connect')?>" class="button rounded login-button fb">
								<i class="social-icon fb"></i>
								<span>daftar dengan facebook</span>
							</a>
							<a href="<?php echo site_url('tw-connect')?>" class="button rounded login-button tw">
								<i class="social-icon tw"></i>
								<span>daftar dengan twitter</span>
							</a>
						</div> <!-- .button-action-wrapper -->
					</div> <!-- .panel -->

					<?php } ?>


					<div id="figure" class="wide panel"> <?php echo isset($session) ? 'lengkapi data kamu' : 'or' ?></div>
					<div class="panel" id="email-register">
						<?php if (!isset($session)){?>
						<div class="title">
							<h4>daftar</h4>
						</div> <!-- .title -->
						<?php }?>

						<?php echo cmc_form_open('user-register', site_url('register'), 'id="user-register"');?>
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
								<input onkeypress="return allLetterspace(event)" id="username" type="text" style="text-transform:capitalize;" placeholder="nama lengkapmu" name="name" value="<?php echo $name_value;?>">
								<?php echo form_error('name')?>
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
								<input onkeypress="return emailValidation(event)" id="email" type="text" placeholder="alamat email mu" name="email" value="<?php echo $email_value;?>">
								<?php echo form_error('email')?>
							</div> <!-- .column -->
							<div class="column">
								<label for="password" class="sub-title">kata sandi<span>*</span></label>
								<input id="password" type="password" placeholder="kata sandi" name="password" value="<?php echo set_value('password');?>">
								<?php echo form_error('password')?>
							</div> <!-- .column -->
							<div class="column">
								<label for="re-password" class="sub-title">konfirmasi kata sandi<span>*</span></label>
								<input id="re-password" type="password" placeholder="ulangi kata sandi" name="re-password" value="<?php echo set_value('re-password');?>">
								<?php echo form_error('re-password')?>
							</div> <!-- .column -->
							<div class="column">
								<label for="phone" class="sub-title">nomer ponsel<span>*</span></label>
								<input onkeypress="return numeric(event)" id="phone" type="number" placeholder="08X-XXXXXXXXX" name="phone" value="<?php echo set_value('phone');?>">
								<?php echo form_error('phone')?>
							</div> <!-- .column -->
							<div class="column half">
								<label class="sub-title">jenis kelamin<span>*</span></label>
								<div class="custom-radio-button">
									<?php
										$gender_m = '';
										$gender_f = '';
										$gender_m_tambahan = '';
										$gender_f_tambahan = '';

										if( $this->input->post('gender') == 'm' ){
											$gender_m = 'checked';
										}else if( isset($session['gender']) && $session['gender']=='male' ){
											$gender_m = 'checked';
											$gender_f_tambahan = ' disabled';
										}

										if( $this->input->post('gender') == 'f' ){
											$gender_f = 'checked';
										}else if( isset($session['gender']) && $session['gender']=='female' ){
											$gender_f = 'checked';
											$gender_m_tambahan = ' disabled';
										}
									?>
								    <input type="radio" id="m-option" name="gender" value="m" <?php echo $gender_m.$gender_m_tambahan;?>>
								    <label for="m-option"><span></span>Laki-laki</label>
								    <div class="check"></div>
								</div> <!-- .custom-radio-button -->
								<div class="custom-radio-button">
								    <input type="radio" id="f-option" name="gender" value="f" <?php echo $gender_f.$gender_f_tambahan;?>>
								    <label for="f-option"><span></span>Perempuan</label>
								    <div class="check"></div>
								</div> <!-- .custom-radio-button -->
								<?php echo form_error('gender')?>
							</div> <!-- .column -->
							<div class="column">
								<label class="sub-title">tanggal lahir<span>*</span></label>
								<div class="devide-3">
									<div class="child">
										<input onkeypress="return numeric(event)" readonly type="text" id="day" placeholder="DD" class="center" name="dd" value="<?php echo (isset($dob_ar[2])) ? $dob_ar[2] : '' ;?>"/>
									</div> <!-- .child -->
									<div class="child">
										<input onkeypress="return numeric(event)" readonly type="text" id="month" placeholder="MM" class="center" name="mm" value="<?php echo (isset($dob_ar[1])) ? $dob_ar[1] : '' ;?>"/>
									</div> <!-- .child -->
									<div class="child">
										<input onkeypress="return numeric(event)" readonly type="text" id="year" placeholder="YYYY" class="center" name="yy" value="<?php echo (isset($dob_ar[0])) ? $dob_ar[0] : '' ;?>"/>
									</div> <!-- .child -->
								</div> <!-- .devide-3 -->
								<p class="error"><?php echo $dob_err;?></p>
							</div> <!-- .column -->

							<div class="column">
								<label class="sub-title mobile">lokasi pembelian</label>
								<ul id="tab" class="tabbb">
									<li <?php echo ($vendor == 'alfamart') ? 'class="current"' : '' ?> data-tab="tab-1" data-vendor="alfamart">
										<a href="javascript:void(0)"><span></span><img src="{{ theme:image_url file= "coke/vendor_alfamart.png" }}" alt=""/></a>
									</li>
									<li <?php echo ($vendor == 'alfamidi') ? 'class="current"' : '' ?> data-tab="tab-2" data-vendor="alfamidi">
										<a href="javascript:void(0)"><span></span><img src="{{ theme:image_url file= "coke/vendor_alfamidi.png" }}" alt=""/></a>
										<span></span>
									</li>
									<li <?php echo ($vendor == 'indomaret') ? 'class="current"' : '' ?> data-tab="tab-3" data-vendor="indomaret">
										<a href="javascript:void(0)"><span></span><img src="{{ theme:image_url file= "coke/vendor_indomaret.png" }}" alt=""/></a>
										<span></span>
									</li>									
								</ul>		
								<input type="hidden" name="vendor" value="<?php echo $vendor;?>" id="vendor-register">														
							</div> <!-- .column -->


							<div id="tab-1" class="tab-container <?php echo ($vendor == 'alfamart') ? 'current' : '' ?>">
								<div class="column">
									<label for="code-id" class="sub-title">kode unik<span>*</span></label>
									<?php 										
										if($code_temp && $code_temp['vendor'] == 'alfamart' ){
											$kode_alfamart = $code_temp['code'];
											$kode_transaksi_alfamart = $code_temp['code_transaksi'];
										}else{
											$kode_alfamart = set_value('kode_unik');
											$kode_transaksi_alfamart = set_value('kode_transaksi');
										}
									?>
									<input id="code-id" type="text" placeholder="CokeTune_0431xxxx-xxxx" name="kode_alfamart" value="<?php echo $kode_alfamart;?>" onkeypress="return textAlphanumeric(event)">
								</div> <!-- .column -->
								<div class="column">
									<label for="code-tr" class="sub-title">kode transaksi<span>*</span></label>
									<input id="code-tr" type="text" placeholder="CokeTune_0431xxxx-xxxx" name="kode_transaksi_alfamart" value="<?php echo $kode_transaksi_alfamart;?>" onkeypress="return textAlphanumeric(event)">
								</div> <!-- .column -->								
							</div> <!-- .tab-container -->

							<div id="tab-2" class="tab-container <?php echo ($vendor == 'alfamidi') ? 'current' : '' ?>">
								<div class="column">
									<label for="code-id" class="sub-title">kode unik<span>*</span></label>
									<?php 										
										if($code_temp && $code_temp['vendor'] == 'alfamidi' ){
											$kode_alfamidi = $code_temp['code'];
											$kode_transaksi_alfamidi = $code_temp['code_transaksi'];
										}else{
											$kode_alfamidi = set_value('kode_unik');
											$kode_transaksi_alfamidi = set_value('kode_transaksi');
										}
									?>
									<input id="code-id" type="text" placeholder="CokeTune_0431xxxx-xxxx" name="kode_alfamidi" value="<?php echo $kode_alfamidi;?>" onkeypress="return textAlphanumeric(event)">
								</div> <!-- .column -->
								<div class="column">
									<label for="code-tr" class="sub-title">kode transaksi<span>*</span></label>
									<input id="code-tr" type="text" placeholder="CokeTune_0431xxxx-xxxx" name="kode_transaksi_alfamidi" value="<?php echo $kode_transaksi_alfamidi;?>" onkeypress="return textAlphanumeric(event)">
								</div> <!-- .column -->								
							</div> <!-- .tab-container -->

							<div id="tab-3" class="tab-container <?php echo ($vendor == 'indomaret') ? 'current' : '' ?>">
								<div class="column">
									<label for="code-id" class="sub-title">kode unik<span>*</span></label>
									<?php 										
										if($code_temp && $code_temp['vendor'] == 'indomaret' ){
											$kode_unik_indomaret = $code_temp['code'];											
										}else{
											$kode_unik_indomaret = set_value('kode_unik_indomaret');											
										}
									?>
									<input id="code-id" type="text" placeholder="CokeTune_0431xxxx-xxxx" name="kode_unik_indomaret" value="<?php echo $kode_unik_indomaret;?>" onkeypress="return textAlphanumeric(event)">
								</div> <!-- .column -->
							</div> <!-- .tab-container -->

							



							<div class="column">
								<label for="code-id" class="sub-title">kode unik<span>*</span></label>
								<input onkeypress="return textAlphanumeric(event)" id="code-id" type="text" value="<?php echo isset($code_temp['code'])?$code_temp['code']:set_value('kode_unik')?>" placeholder="CokeTune_0431xxxx-xxxx" name="kode_unik">
								<?php echo form_error('kode_unik')?>
								<?php if($code_err):?>
									<p class="error"><?php echo $code_err;?></p>
								<?php endif;?>

							</div> <!-- .column -->
							<div class="column">
								<label for="code-tr" class="sub-title">kode transaksi<span>*</span></label>
								<input onkeypress="return textAlphanumeric(event)" id="code-tr" value="<?php echo isset($code_temp['code_transaksi'])?$code_temp['code_transaksi']:set_value('kode_transaksi')?>" type="text" placeholder="CokeTune_0431xxxx-xxxx" name="kode_transaksi">
							</div> <!-- .column -->


							

							<div class="column">
								<div id="captcha">
									<div>
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
	                                    <div id="html_element"></div>
	                                    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
	                                    <input type="hidden" name="recaptcha_response_field" value="" readonly="readonly" />
	                                </div>
	                                <?php echo form_error('recaptcha_response_field')?>
								</div>
							</div>
							<div class="column">
								<div class="custom-check-button">
								    <input type="checkbox" id="terms" name="term" <?php echo ($this->input->post('term') == 'on') ? 'checked' : '';?>>
								    <label for="terms">
								    		<span></span>
								    	</label>
											<span class="text">Saya telah memahami dan menyetujui <a href="{{ url:site uri="cara-mengikuti-kompetisi"}}#page" target="_blank">Syarat dan Ketentuan</a> Promosi</span>
								</div> <!-- .custom-radio-button -->
								<?php echo form_error('term')?>
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
