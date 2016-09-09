<?php $code_temp = ($this->session->userdata('code_temp')) ? $this->session->userdata('code_temp') : array(); ?>

<div id="codeBar">
	<div class="column">
		<div id="codeBar-inner" class="container">
			<div class="title">
				<h3>Tambah kode</h3>
			</div> <!-- .title -->
			<div class="content">
				<div class="panel">
					<div class="column">
						<label>lokasi pembelian</label>
						<ul id="tab" class="tabbb">
							<li class="vendor-list current" data-tab="tab-1" data-name="alfamart">
								<a href="javascript:void(0)"><span></span><img src="{{ theme:image_url file= "coke/vendor_alfamart.png" }}" alt=""/></a>
							</li>
							<li class="vendor-list" data-tab="tab-1" data-name="alfamidi">
								<a href="javascript:void(0)"><span></span><img src="{{ theme:image_url file= "coke/vendor_alfamidi.png" }}" alt=""/></a>
							</li>
							<li class="vendor-list" data-tab="tab-2" data-name="indomaret">
							  <a href="javascript:void(0)"><span></span><img src="{{ theme:image_url file= "coke/vendor_indomaret.png" }}" alt=""/></a>
  							</li>
						</ul> <!-- #tab -->
					</div> <!-- .column -->

					<?= form_open('code-check', array('id' => 'input-id')); ?>
						<div id="input-inner">

							<div id="tab-1" class="tab-container current">
								<div class="input-panel">
									<label>kode unik</label>
									<input onkeypress="return textAlphanumeric(event)" type="text" placeholder="ketik kode unik di sini" id="alfamart-code" name="alfamart_code" value="<?=isset($code_temp['code'])?$code_temp['code']:''?>" maxlength="10" />
								</div> <!-- .input-panel -->
								<div class="input-panel">
									<label>kode transaksi</label>
									<input onkeypress="return textAlphanumeric(event)" type="text" placeholder="ketik kode transaksi di sini" id="transaction-code" name="transaction_code" value="<?=isset($code_temp['code_transaksi'])?$code_temp['code_transaksi']:''?>" maxlength="25" />
								</div> <!-- .input-panel -->
							</div> <!-- #tab-1 -->

							<div id="tab-2" class="tab-container">
								<div class="input-panel">
									<label>kode unik</label>
									<input onkeypress="return textAlphanumeric(event)" type="text" placeholder="ketik kode unik di sini" id="indomaret-code" name="indomaret_code" value="<?=isset($code_temp['code'])?$code_temp['code']:''?>" maxlength="10" />
								</div> <!-- .input-panel -->
							</div> <!-- #tab-1 -->

						</div> <!-- #input-inner -->
					<?= form_close(); ?>

					<div class="psst">
		                <span class="error-m message">
		                </span><!-- .error-m -->
		            </div> <!-- .psst -->

				  	<div id="captcha">
		                <div style="margin-top: 10px;" class="test">
		                    <script type="text/javascript">
		                        var verifyCallback = function(response) {
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
		                    <input type="hidden" id="recaptcha-response-field" name="recaptcha_response_field" value="" readonly="readonly" />
		                </div>
		            </div> <!-- #captcha -->

					<div class="button-action-wrapper">
		                <input type="hidden" name="vendor" id="vendor-name" value="" readonly="readonly">
		                <a href="#" id="code-btn" class="button rounded border">submit</a>
		            </div> <!-- .button-action-wrapper -->

				</div> <!-- .panel -->
			</div> <!-- .content -->
		</div> <!-- .column -->
	</div> <!-- #codeBar -->
</div> <!-- #codeBar -->