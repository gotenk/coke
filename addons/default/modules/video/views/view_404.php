{{ theme:partial name="header_page" }}
	
		<section id="banner">
	
		<div id="banner_top_shadow"></div>
		<div id="banner_wrap">
			<img src="{{ banner_image:file }}" alt="" title=""/>
			
		{{ theme:partial name="social_network" }}
			
		</div> <!-- #banner_wrap -->
		<div id="banner_bottom_shadow"></div>
	</section> <!-- #banner -->
	<section id="main_content">
		<div id="main_content_wrap">
            <div class="main_content_title">
				<h1>NOTICE BOARD</h1>
			</div>
            <div id="content-desc">            
                <h1 style="text-align: center;">Notice Not Found</h1>
            <div class="notice-nav" style="text-align: left;">
                <a href="<?php echo site_url().'/notice/'.$this->session->userdata('pagination') ?>">< Back</a>
            </div>
            <div class="clearfix"></div>
            </div>
		</div>
	</section>
	<!-- /content -->

{{ theme:partial name="footer" }}