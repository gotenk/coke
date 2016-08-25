<div class="top-text top-cerita">
	<span class="coke-text"></span>
	<span class="sampaikan">SAMPAIKAN</span>
	<span class="dengan">DENGAN</span>
	<div class="clear"></div>
</div>
<div class="form-cerita" style="margin-bottom:65px;">
<?php echo form_open_multipart('bubble/kirim_cerita'); ?>
<!--<form enctype="multipart/form-data" accept-charset="utf-8" method="post" action="<?php echo site_url('bubble/kirim_cerita'); ?>">-->

	<div class="input">
		<i class="form-cerita-icon email"></i> <strong>SAMPAIKAN CERITAMU DISINI</strong>
		<span class="max-char"><span id="chars">0</span> / 200 Maksimal Karakter</span>
		<div class="clear"></div>
		<textarea class="input-textarea" placeholder="katakan ceritamu disini..." name="cerita" id="cerita"></textarea>
		<input type="hidden" value="" name="photo_cerita" id="photo-cerita" readonly="readonly" />
		<input type="hidden" value="" name="thumb_photo_cerita" id="thumb-photo-cerita" readonly="readonly" />
	</div>
	<div class="input">
		<input type="file" class="input-file">
		<i class="form-cerita-icon picture"></i> <strong>UPLOAD FOTOMU / FOTO</strong> saat bersamanya* 
		<a href="javascript:void(0);" class="red-link" id="upload-file">UPLOAD FOTO</a> (Optional)
		<br />
		<div class="max-file">*Ukuran maksimal file 500kb</div><br />
		<span style="color:red; display:none;" id="info-foto">Foto terpilih</span>
		<div id="div-preview" style="display:none; margin-left:26px;"><img id="photo-preview" src="" height="150px" /></div>
	</div>
	<div class="input">
		<i class="form-cerita-icon video"></i> <strong>REKAM PESANMU</strong> untuknya dalam video,  upload di Youtube dan salin link.
		<input type="text" class="input-text" name="link_youtube" id="link-youtube" > (Optional)
		<br /><br />
		<div id="div-captha">
			{{ bubble:recaptcha }}
			<div class="clear"></div>
		</div>
		<!-- <input type="text" name="captha" value="" id="captha" class="input-text" /> -->
		<br />
	</div>
	<div class="spacer"></div>
	<div class="input syarat">
		<input type="checkbox" class="input-check" name="agreement" id="agreement" value="1"> Saya telah menyetujui <a href="<?php echo site_url('persyaratan-penggunaan'); ?>" target="_blank">Syarat dan Ketentuan</a> yang berlaku.
	</div>
	<div class="input">
		<input type="button" class="link-btn red posting" value="KIRIM CERITAMU">		
	</div>
<?php echo form_close(); ?>
<!--</form>-->
</div>

<script type="text/javascript">
	//IS_LOGGED_IN = true;

	(function($) {
		$.fn.extend( {
			limiter: function(limit, elem) {
				$(this).on("keyup focus", function() {
					setCount(this, elem);
				});
				function setCount(src, elem) {
					var chars = src.value.length;
					if (chars > limit) {
						src.value = src.value.substr(0, limit);
						chars = limit;
					}
					elem.html( limit - chars );
				}
				setCount($(this)[0], elem);
			}
		});
	})(jQuery);

	$(document).ready( function() {
		var elem = $("#chars");
		$("#cerita").limiter(200, elem);
	});
</script>
<style type="text/css">
	iframe{
		display: none !important;
	}
</style>

<div class="popup global" style="display:none">
	<div class="global-wrapper crop-popup">
		<div class="popup-wrapper">
			<div class="logo"></div>
			<a href="javascript:void(0)" onclick="$('.crop-popup').parent().hide(); " class="close-btn"></a>
			<img class="cropimage" cropwidth="300" cropheight="320" src="">		
			<div class="link"><a href="javascript:void(0);" class="crop-submit kirim link-btn red">Selesai</a></div>
		</div>
	</div>
</div>
