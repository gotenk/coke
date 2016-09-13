<?php include 'header-fluid.php'; ?>

<main>
	
	<section id="register" class="date-check fullScreen">
		<div id="background-img" class="fluid-img register">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="email-register">
						<div class="title">
							<h4>masukan tanggal lahir</h4>
						</div> <!-- .title -->
						<form id="user-register">
							<div class="column">
								<div class="devide-3">
									<div class="child custom-selectbox">
										<select>
										  <option value="day">DD</option>
										  <option value="1">01</option>
										  <option value="2">02</option>
										  <option value="3">03</option>
										  <option value="4">04</option>
										  <option value="5">05</option>
										</select>										
									</div> <!-- .child -->
									<div class="child custom-selectbox">
										<select>
										  <option value="month">MM</option>
										  <option value="1">01</option>
										  <option value="2">02</option>
										  <option value="3">03</option>
										  <option value="4">04</option>
										  <option value="5">05</option>
										</select>										
									</div> <!-- .child -->
									<div class="child custom-selectbox">
										<select>
										  <option value="year">YYYY</option>
										  <option value="1">01</option>
										  <option value="2">02</option>
										  <option value="3">03</option>
										  <option value="4">04</option>
										  <option value="5">05</option>
										</select>										
									</div> <!-- .child -->									
								</div> <!-- .devide-3 -->
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