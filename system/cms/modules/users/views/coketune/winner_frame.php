<main>
		
	{{ theme:partial name="caranya" }}
	
	<section id="winnertable" class="wrapper">
		<div class="container-shadow top"></div>
		<div id="winnertable-inner" class="container inner">
			<div class="title main">
				<h2>Daftar pemenang</h2>
			</div> <!-- .title -->
			<div class="row">
				<div id="list">
					<div class="search-panel">
						<label for="search" id="icon-search"><i class="icon only icon-search">search</i></label>
						<input id="search" type="text" placeholder="Cari nama kamu..."/>
					</div> <!-- .search-panel -->

					<div id="winner-result">
					<?php $this->load->view('coketune/winner_table');?>									
					</div> <!-- #winner-result -->
					
				</div> <!-- #list -->		
			</div> <!-- .row -->
		</div> <!-- #winnertable-inner -->						
	</section> <!-- #winnertable -->	

	{{ theme:partial name="masukkan_code" }}

</main>