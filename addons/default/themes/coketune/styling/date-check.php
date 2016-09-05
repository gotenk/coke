<?php include 'header-fluid.php'; ?>

<main>
	
	<section id="register" class="date-check fullScreen register">
		<div id="background-img" class="fluid-img">
			<div id="register-inner" class="container">
				<div class="row">
					<div class="panel" id="email-register">
						<div class="title">
							<h4>masukan tanggal lahir</h4>
						</div> <!-- .title -->
						<form id="user-register">
							<div class="column">
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