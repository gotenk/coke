<?php include 'header-fluid.php'; ?>

<main>
	
	<section id="register">
		<div id="background-img" class="fluid-img register">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="social-register">
						<div class="button-action-wrapper social-button">
							<a href="#" class="button rounded login-button fb">
								<i class="social-icon fb"></i>
								<span>register with facebook</span>
							</a>
							<a href="#" class="button rounded login-button tw">
								<i class="social-icon tw"></i>
								<span>register with twitter</span>
							</a>
						</div> <!-- .button-action-wrapper -->
					</div> <!-- .panel -->
					<div id="figure" class="wide panel">atau</div>
					<div class="panel" id="email-register">
						<div class="title">
							<h4>daftar</h4>
						</div> <!-- .title -->
						<form id="user-register">
							<div class="column">
								<label for="username" class="sub-title">nama lengkap<span>*</span></label>
								<input id="username" type="text" placeholder="nama lengkapmu">
							</div> <!-- .column -->
							<div class="column">
								<label for="email" class="sub-title">alamat email<span>*</span></label>
								<input id="email" type="email" placeholder="alamat email mu">
							</div> <!-- .column -->
							<div class="column">
								<label for="password" class="sub-title">kata sandi<span>*</span></label>
								<input id="password" type="password" placeholder="kata sandi baru">
							</div> <!-- .column -->
							<div class="column">
								<label for="re-password" class="sub-title">konfirmasi kata sandi<span>*</span></label>
								<input id="re-password" type="password" placeholder="ulangi kata sandi">
							</div> <!-- .column -->
							<div class="column">
								<label for="phone" class="sub-title">nomer ponsel<span>*</span></label>
								<input id="phone" type="text" placeholder="08X-XXXXXXXXX">
							</div> <!-- .column -->
							<div class="column half">
								<label class="sub-title">jenis kelamin<span>*</span></label>
								<div class="custom-radio-button">
								    <input type="radio" id="m-option" name="selector">
								    <label for="m-option"><span></span>Laki-laki</label>
								    <div class="check"></div>								    
								</div> <!-- .custom-radio-button -->
								<div class="custom-radio-button">
								    <input type="radio" id="f-option" name="selector">
								    <label for="f-option"><span></span>Perempuan</label>
								    <div class="check"></div>
								</div> <!-- .custom-radio-button -->
							</div> <!-- .column -->
							<div class="column">
								<label class="sub-title">tanggal lahir<span>*</span></label>
								<div class="devide-3">
									<div class="child">
										<input type="text" id="day" placeholder="DD" class="center"/>
									</div> <!-- .child -->
									<div class="child">
										<input type="text" id="month" placeholder="MM" class="center"/>
									</div> <!-- .child -->
									<div class="child">
										<input type="text" id="year" placeholder="YYYY" class="center"/>
									</div> <!-- .child -->									
								</div> <!-- .devide-3 -->
							</div> <!-- .column -->
							<div class="column">
								<label class="sub-title mobile">lokasi pembelian</label>
								<ul id="tab" class="tabbb">
									<li class="current" data-tab="tab-1">
										<a href="javascript:void(0)"><span></span><img src="img/coke/vendor_alfamart.png" alt=""/></a>
									</li>
									<li data-tab="tab-2">
										<a href="javascript:void(0)"><span></span><img src="img/coke/vendor_indomaret.png" alt=""/></a>
										<span></span>
									</li>
									<li data-tab="tab-3">
										<a href="javascript:void(0)"><span></span><img src="img/coke/vendor_alfamart.png" alt=""/></a>
										<span></span>
									</li>									
								</ul>								
							</div> <!-- .column -->
							
							<div id="tab-1" class="tab-container current">
								<div class="column">
									<label for="code-id" class="sub-title">kode unik<span>*</span></label>
									<input id="code-id" type="text" placeholder="CokeTune_0431xxxx-xxxx">
								</div> <!-- .column -->
								<div class="column">
									<label for="code-tr" class="sub-title">kode transaksi<span>*</span></label>
									<input id="code-tr" type="text" placeholder="CokeTune_0431xxxx-xxxx">
								</div> <!-- .column -->								
							</div> <!-- .tab-container -->

							<div id="tab-2" class="tab-container">
								<div class="column">
									<label for="code-id" class="sub-title">kode unik<span>*</span></label>
									<input id="code-id" type="text" placeholder="CokeTune_0431xxxx-xxxx">
								</div> <!-- .column -->
							</div> <!-- .tab-container -->

							<div id="tab-3" class="tab-container">
								<div class="column">
									<label for="code-id" class="sub-title">kode unik<span>*</span></label>
									<input id="code-id" type="text" placeholder="CokeTune_0431xxxx-xxxx">
								</div> <!-- .column -->
								<div class="column">
									<label for="code-tr" class="sub-title">kode transaksi<span>*</span></label>
									<input id="code-tr" type="text" placeholder="CokeTune_0431xxxx-xxxx">
								</div> <!-- .column -->								
							</div> <!-- .tab-container -->
							
							<div class="column">
								<div id="captcha">
									<div>
										<!-- CAPTCHA GOES HERE -->
										<img id="demo" src="img/coke/captcha_demo.png"/>
										<!-- END -->
									</div>
								</div> <!-- #captcha -->
							</div> <!-- .column -->
							<div class="column">
								<div class="custom-check-button">
								    <input type="checkbox" id="terms" name="selector">
								    <label for="terms">
								    		<span></span>
								    	</label>
								    <span class="text">Saya telah memahami dan menyetujui Syarat dan Ketentuan Promosi</span>
								</div> <!-- .custom-radio-button -->
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