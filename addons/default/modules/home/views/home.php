<div class="home">
		<div class="form" style="margin:0 auto; background:rgba(0, 0, 0, .5); width:400px; padding:10px; color:#fff;">
			<div style="display:block; margin-bottom:10px; padding:0; text-align:center;">
				<a href="<?php echo site_url('fb-connect'); ?>" style="background:blue; color:#fff; text-align:center; width:100%; display:block; padding:10px 0;">Connect FB</a>
			</div>

			<div style="display:block; margin-bottom:10px; padding:0; text-align:center;">
				<a href="<?php echo site_url('tw-connect'); ?>" style="background:blue; color:#fff; text-align:center; width:100%; display:block; padding:10px 0;">Connect TW</a>
			</div>

			<hr />
			<?php
			var_dump($this->session->userdata('me'));
			?>

			<div class="input" style="">
				<label>NAME <span>*</span></label>
				<input onkeypress="return allLetter(event)" type="text" name="" value="" class="input-text-global" placeholder="Nama Anda" />
			</div>
			<div class="input" style="">
				<label>EMAIL <span>*</span></label>
				<input onkeypress="return emailValidation(event)" type="text" name="" value="" class="input-text-global" placeholder="nama@email.com" />
			</div>
			<div class="input" style="">
				<label>KATA SANDI <span>*</span></label>
				<input type="text" name="" value="" class="input-text-global" placeholder="p@ssw0rd" />
			</div>
			<div class="input" style="">
				<label>KONFIRMASI KATA SANDI <span>*</span></label>
				<input type="text" name="" value="" class="input-text-global" placeholder="p@ssw0rd" />
			</div>
			<div class="input" style="">
				<label>NOMOR PONSEL <span>*</span></label>
				<input onkeypress="return numeric(event)" type="text" name="" value="" class="input-text-global" placeholder="08xxxxxxxxx" />
			</div>
			<div class="input" style="">
				<label>JENIS KELAMIN <span>*</span></label>
				<input type="radio" name="jk" value="" class="input-text" placeholder="Nama Anda" />Laki-laki
				<input type="radio" name="jk" value="" class="input-text" placeholder="Nama Anda" />Perempuan
			</div>
			<div class="input" style="">
				<label>TANGGAL LAHIR <span>*</span></label>
				<input type="text" name="" value="" class="input-text-global" placeholder="Tgl" />
				<input type="text" name="" value="" class="input-text-global" placeholder="Bln" />
				<input type="text" name="" value="" class="input-text-global" placeholder="Tahun" />
			</div>
			<div class="input" style="">
				<label>KODE UNIK <span>*</span></label>
				<input type="text" name="" value="" class="input-text-global" placeholder="Coketune_041xxxx" />
			</div>
			<div class="input" style="">
				<label>KODE TRANSAKSI <span>*</span></label>
				<input type="text" name="" value="" class="input-text-global" placeholder="Coketune_041xxxx" />
			</div>
			<div class="input" style="">
				<label>Captcha <span>*</span></label>
			</div>
			<div class="input" style="">
				<input type="checkbox" name="" value="" class="input-text" placeholder="" />
				Saya telah memahami dan menyetujui syarat & ketentuan
			</div>
			<div class="input" style="">
				<input type="button" value="Daftar" />
			</div>

			<div class="clear"></div>
		</div>
</div>
