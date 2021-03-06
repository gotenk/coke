<?php include 'header-login.php'; ?>

<main>
	
	<section id="page">
		<div id="background-img" class="fluid-img user-main-profile">
			
			<div id="userProfile">
				<div class="column">
					<div id="userProfile-inner" class="container">
						<div class="row">
							<div class="userProfile-image">
								<img src="img/coke/demo-user-profile-picture.jpg"/>
							</div> <!-- .image -->
							<div class="userProfile-info">
								<div class="name">Tatjana saphira</div>
								<div class="detail"><span class="age">19</span>,&nbsp;<span class="gender">female</span></div>
							</div> <!-- .userProfile-info -->
						</div> <!-- .row -->
					</div> <!-- #userProfile-inner -->
				</div> <!-- .column -->
			</div> <!-- #userProfile -->
			
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
										<li class="current" data-tab="tab-1">
											<a href="javascript:void(0)">
												<span></span><img src="img/coke/vendor_alfamart.png"/>
											</a>
										</li>
										<li data-tab="tab-2">
											<a href="javascript:void(0)">
												<span></span><img src="img/coke/vendor_indomaret.png"/>
											</a>
										</li>									
									</ul> <!-- #tab -->									
								</div> <!-- .column -->
								
								<form id="input-id" class="column">
									<div id="input-inner">
										
										<div id="tab-1" class="tab-container current">
											<div class="input-panel">
												<label>kode unik</label>
												<input type="text" placeholder="ketik kode unik disini"/>
											</div> <!-- .input-panel -->
											<div class="input-panel">
												<label>kode transaksi</label>
												<input type="text" placeholder="ketik kode transaksi disini"/>
											</div> <!-- .input-panel -->
										</div> <!-- #tab-1 -->

										<div id="tab-2" class="tab-container">
											<div class="input-panel">
												<label>kode unik</label>
												<input type="text" placeholder="ketik kode unik disini"/>
											</div> <!-- .input-panel -->
											<div class="input-panel" id="figure">
												<label>kode transaksi</label>
												<input type="text" placeholder="ketik kode transaksi disini"/>
											</div> <!-- .input-panel -->											
										</div> <!-- #tab-1 -->
																				
									</div> <!-- #input-inner -->																		
								</form> <!-- #input-id -->
								
								<div class="modal">
									<span class="message">
										Ops! Masukkan kode yang masih berlaku atau kode yang belum pernah kamu gunakan sebelumnya
									</span> <!-- .message -->
								</div> <!-- .modal -->
								
								<div id="captcha">
									<div>
										<!-- CAPTCHA GOES HERE -->
										<img src="img/coke/captcha_demo.png"/>
										<!-- END -->
									</div>
								</div> <!-- #captcha -->
								
								<div class="button-action-wrapper">
									<input class="button rounded border" type="submit" name="submit" value="submit"/>
								</div> <!-- .button-action-wrapper -->
								
							</div> <!-- .panel -->
						</div> <!-- .content -->
					</div> <!-- .column -->
				</div> <!-- #codeBar -->
			</div> <!-- #codeBar -->
			
			<div id="page-inner" class="container">
				<div class="row">
					
					<div id="user-code-data">
						<div class="title">
							<h3><span class="value">12</span>kode unik yang sudah terdaftar</h3>
						</div> <!-- .title -->
						<div class="data" id="user-data-result">
							<table>
								<tbody>
									<tr>
										<th>tanggal</th>
										<th>kode unik</th>
										<th>kode transaksi</th>
									</tr>
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>																		
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>																		
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>																		
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>																		
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>																		
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>																		
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>																		
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>																		
									<tr>
										<td data-th="TANGGAL">29 nov</td>
										<td data-th="KODE UNIK">#cokeTune_0431xxxxx_333xxx</td>
										<td data-th="KODE TRANSAKSI">#cokeTune_0431xxxxx_333xxx</td>
									</tr>
								</tbody>
							</table>
							
							<div class="button-action-wrapper">
								<a href="#" class="button primary rounded border">kembali</a>
							</div> <!-- .button-action-wrapper -->
							
						</div> <!-- .data -->
					</div> <!-- #user-code-data -->
					
				</div> <!-- .row -->
			</div> <!-- #page-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #page -->
	
</main>
	
<?php include 'footer.php'; ?>	