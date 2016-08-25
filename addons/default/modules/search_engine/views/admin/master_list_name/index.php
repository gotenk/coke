<div class="one_full">
	<section class="title">
		<h4>Search Engine List</h4>
	</section>

	<section class="item">
		<div class="content">
			<?php if ($data) : ?>
				<?php  echo $this->load->view('admin/master_list_name/partials/filters') ?>				
								
				<!--<?php //echo form_open_multipart(ADMIN_URL.'/bubble/import_fb_crawling', '', array('f_module' => $module_details['slug'])) ?>
				<fieldset id="filters">
					<legend>Import Crawling Facebook</legend>
						<ul>
							<li class="" style="margin-left:5px;">
								<input type="file" id="file_upload" name="userfile" size="20" />
								<?php //echo form_submit('submit', 'Upload'); ?>
							</li>		
						</ul>
				</fieldset>	
				<?php //echo form_close() ?>	-->		
				
				<?php echo form_open(ADMIN_URL.'/search_engine/master_list_name/action') ?>
					<div id="filter-stage">
						<?php echo $this->load->view('admin/master_list_name/tables/master_list_name') ?>
					</div>
				<?php echo form_close() ?>
			<?php else : ?>
				<div class="no_data">No data</div>
			<?php endif ?>
		</div>
	</section>
</div>
