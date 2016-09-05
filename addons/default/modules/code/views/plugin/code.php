<div class="vendor">
    <ul id="tab" class="tabbb">
        <li class="vendor-list current" data-tab="tab-1" data-name="alfamart">
            <a href="javascript:void(0)"><span></span><img src="{{ theme:image_url file= "coke/vendor_alfamart.png" }}" alt=""/></a>
        </li>
        <li class="vendor-list" data-tab="tab-2" data-name="indomaret">
            <a href="javascript:void(0)"><span></span><img src="{{ theme:image_url file= "coke/vendor_indomaret.png" }}" alt=""/></a>
            <span></span>
        </li>
    </ul>
    <?= form_open('code-check', array('id' => 'input-id')); ?>
        <div id="input-inner">
            <div id="tab-1" class="tab-container current">
                <span class="panel">
                    <label>kode unik</label>
                    <input type="text" placeholder="ketik kode unik di sini" id="alfamart-code" name="alfamart_code" />
                </span> <!-- .panel -->
                <span class="panel">
                    <label>kode transaksi</label>
                    <input type="text" placeholder="ketik kode transaksi di sini" id="transaction-code" name="transaction_code" />
                </span> <!-- .panel -->
            </div> <!-- #vendor-a -->
            <div id="tab-2" class="tab-container">
                <span class="panel">
                    <label>kode unik</label>
                    <input type="text" placeholder="ketik kode unik di sini" id="indomaret-code" name="indomaret_code" />
                </span> <!-- .panel -->
            </div> <!-- #vendor-b -->
            <div class="psst">
                <span class="error-m">
                </span><!-- .error-m -->
            </div> <!-- .psst -->
        </div> <!-- #input-inner -->
        <div class="opt">
            <!-- CAPTCHA GOES HERE -->
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
            <!-- END -->
            <div class="button-action-wrapper">
                <input type="hidden" name="vendor" id="vendor-name" value="" readonly="readonly">
                <a href="#" id="code-btn" class="button rounded border">submit</a>
            </div> <!-- .button-action-wrapper -->
        </div> <!-- .opt -->
    <?= form_close(); ?>
</div> <!-- .vendor -->
