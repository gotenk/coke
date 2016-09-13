<main>
	
	<section id="page">		
		<div id="background-img" class="fluid-img user-main-profile">
			
			<div id="userProfile">
				<div class="column">
					<div id="userProfile-inner" class="container">
						<div class="row">
							<div class="userProfile-image">
								<?php if($user->photo_profile && is_file($user->photo_profile)):?>
									<img src="<?php echo base_url().$user->photo_profile?>"/>
								<?php else:?>
									<img src="{{ url:site }}addons/default/themes/coketune/img/coke/demo-user-profile-picture.jpg"/>
								<?php endif;?>
							</div> <!-- .image -->
							<div class="userProfile-info">
								<div class="name"><?php echo $user->display_name;?></div>
								<div class="detail"><span class="age"><?php echo profile_get_umur($user->dob_date_format);?></span>,&nbsp;<span class="gender"><?php echo profile_gender_format($user->gender);?></span></div>
								<div class="detail text-link"><a style="text-decoration: none;color: white" href="<?=site_url()?>pass-change">Reset Password</a></div>
							</div> <!-- .userProfile-info -->
						</div> <!-- .row -->
					</div> <!-- #userProfile-inner -->
				</div> <!-- .column -->
			</div> <!-- #userProfile -->
			
			{{ code:code_page_landscape }}
			
			<div id="page-inner" class="container">
				<div class="row">
					
					<div id="user-code-data">
						<div class="title">
							<h3><span class="value"><?php echo $total;?></span>kode unik yang sudah terdaftar</h3>
						</div> <!-- .title -->
						<div class="data" id="user-data-result">
							<table>
								<tbody>
									<tr>
										<th>tanggal</th>
										<th>kode unik</th>
										<th>kode transaksi</th>
									</tr>
									<?php if($codes):
											foreach($codes as $code):
									?>
									<tr>
										<td data-th="TANGGAL"><?php echo profile_date_format($code->tanggal);?></td>
										<td data-th="KODE UNIK"><?php echo $code->kode_unik;?></td>
										<td data-th="KODE TRANSAKSI"><?php echo $code->transaction_code;?></td>
									</tr>			
									<?php 
											endforeach;
										endif;
									?>
								</tbody>
							</table>
							
							<div class="button-action-wrapper">
								<a href="<?=site_url()?>" class="button primary rounded border">kembali</a>
							</div> <!-- .button-action-wrapper -->
							
						</div> <!-- .data -->
					</div> <!-- #user-code-data -->
					
				</div> <!-- .row -->
			</div> <!-- #page-inner -->
		</div> <!-- #background-img -->
	</section> <!-- #page -->
	
</main>