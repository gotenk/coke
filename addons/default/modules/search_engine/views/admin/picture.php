<div class="one_full">
	<section class="title">
		<h4><?php echo lang('carousel:carousel_picture_list') ?></h4>
	</section>

	<section class="item">
		<div class="content">
			<?php if ($pia) : ?>
				<?php  echo $this->load->view('admin/partials/filter_picture') ?>
	
				<?php echo form_open(ADMIN_URL.'/carousel/action') ?>
					<div id="filter-stage">
						<?php echo $this->load->view('admin/tables/picture_list') ?>
					</div>
				<?php echo form_close() ?>
			<?php else : ?>
				<div class="no_data"><?php echo lang('carousel:currently_no_carousel') ?></div>
			<?php endif ?>
		</div>
	</section>
</div>
