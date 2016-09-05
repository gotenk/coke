<?php include 'header-fluid.php'; ?>

<main>
	
	<section id="register">
		<div id="background-img" class="fluid-img">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel profile" id="social-register">
						<div class="userProfile-image">
							<img src="img/coke/demo-user-profile-picture.jpg"/>
						</div> <!-- .image -->
						<div class="userProfile-info">
							<div class="name">Tatjana saphira</div>
							<div class="detail"><span class="gender">female</span></div>
							<div class="detail"><span class="username-tw">@tatjanasaphira</span></div>
						</div> <!-- .userProfile-info -->						
					</div> <!-- .panel -->
					<div id="figure" class="wide panel">or</div>
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
								<label for="code-id" class="sub-title">kode unik<span>*</span></label>
								<input id="code-id" type="text" placeholder="CokeTune_0431xxxx-xxxx">
							</div> <!-- .column -->
							<div class="column">
								<label for="code-tr" class="sub-title">kode transaksi<span>*</span></label>
								<input id="code-tr" type="text" placeholder="CokeTune_0431xxxx-xxxx">
							</div> <!-- .column -->
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