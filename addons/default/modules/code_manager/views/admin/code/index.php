<section class="title">
	<h4><?php echo lang('twitter_hashtag:list_title') ?></h4>
</section>

<section class="item">
	<div class="content">
	
		<?php template_partial('filters') ?>
	
		<?php echo form_open(ADMIN_URL.'/twitter_hashtag/action') ?>
		
			<div id="filter-stage">
				<?php template_partial('tables/youtubes') ?>
			</div>
	
		<?php echo form_close() ?>
	</div>
</section>
