<?php include 'header.php'; ?>
<?php include 'file-nav.php'; ?>

<main>
		
	<section id="method" class="wrapper">
		<div class="container-shadow top"></div>
		<div id="method-inner" class="container inner">
			<div class="title main">
				<h2>Caranya?</h2>
			</div> <!-- .title -->
			<div class="row">
				<figure>
					<div class="image"><img src="img/coke/method_step1.png"/></div>
					<figcaption>
						<h3>step 1</h3>
						<div class="text">
							Kamu cukup beli 2 botol COCA-COLA/FANTA/SPRITE ukuran 390ml di Alfamart & Indomaret terdekat.
						</div> <!-- .text -->
					</figcaption>
				</figure>
				<figure>
					<div class="image"><img src="img/coke/method_step2.png"/></div>
					<figcaption>
						<h3>step 2</h3>
						<div class="text">
							Kamu akan mendapatkan kode unik dan kode transaksi (khusus pembelian di Alfamart) yang tertera dalam struk 
							belanja kamu.
						</div> <!-- .text -->
					</figcaption>
				</figure>
				<figure>
					<div class="image"><img src="img/coke/method_step3.png"/></div>
					<figcaption>
						<h3>step 3</h3>
						<div class="text">
							Masukkan kode unik dan kode transaksi (khusus pembelian di Alfamart) yang tertera di struk belanja kamu di 
							kolom yang tersedia di www.waktunyacokebreak.coca-cola.co.id
						</div> <!-- .text -->
					</figcaption>
				</figure>
			</div> <!-- .row -->
			<div class="action-button-wrapper">
				<a href="#" class="button rounded border box-shadow primary">lihat selengkapnya</a>
			</div> <!-- .action-button-wrapper -->
		</div> <!-- #method-inner -->						
	</section> <!-- #method -->

	<section id="floating" class="wrapper sub">
		<div class="container-shadow top"></div>
		<div id="floating-inner" class="container">
			<div class="title main">
				<h2>Masukkan kode kamu di sini:</h2>
			</div> <!-- .title -->				
		</div> <!-- #floating-inner -->
	</section> <!-- #floating -->
		
	<section id="code" class="wrapper sub">					
		<div id="code-inner" class="container inner">
			<div class="row">
				<div class="vendor">
					<ul id="tab" class="tabbb">
						<li class="current" data-tab="tab-1">
							<a href="javascript:void(0)"><span></span><img src="img/coke/vendor_alfamart.png" alt=""/></a>
						</li>
						<li data-tab="tab-2">
							<a href="javascript:void(0)"><span></span><img src="img/coke/vendor_indomaret.png" alt=""/></a>
							<span></span>
						</li>
					</ul>
					<form id="input-id">
						<div id="input-inner">
							<div id="tab-1" class="tab-container current">
								<span class="panel">
									<label>kode unik</label>
									<input type="text" placeholder="ketik kode unik di sini"/>
								</span> <!-- .panel -->
								<span class="panel">
									<label>kode transaksi</label>
									<input type="text" placeholder="ketik kode transaksi di sini"/>
								</span> <!-- .panel -->								
							</div> <!-- #vendor-a -->
							<div id="tab-2" class="tab-container">
								<span class="panel">
									<label>kode unik</label>
									<input type="text" placeholder="ketik kode unik di sini"/>
								</span> <!-- .panel -->
							</div> <!-- #vendor-b -->
							<div class="psst">
								<span class="error-m">
									Ops! Masukkan kode yang masih berlaku atau kode yang belum pernah kamu gunakan sebelumnya
								</span> <!-- .error-m -->
							</div> <!-- .psst -->
						</div> <!-- #input-inner -->
						<div class="opt">
							<div id="captcha">
								<div>
									<!-- CAPTCHA GOES HERE -->
									<img src="img/coke/captcha_demo.png"/>
									<!-- END -->
								</div>
							</div> <!-- #captcha -->
							<div class="button-action-wrapper">
								<a href="#" class="button rounded border">submit</a>
							</div> <!-- .button-action-wrapper -->
						</div> <!-- .opt -->
					</form>
				</div> <!-- .vendor -->
			</div> <!-- .row -->
		</div> <!-- $code-inner -->
		<div class="container-shadow bottom"></div>
	</section> <!-- #code -->

</main>
	
<?php include 'footer.php'; ?>	