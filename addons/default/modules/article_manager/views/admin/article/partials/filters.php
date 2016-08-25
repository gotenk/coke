<fieldset id="filters">

	<legend><?php echo lang('global:filters') ?></legend>
	
	<?php echo form_open('') ?>
	<?php echo form_hidden('f_module', $module_details['slug']) ?>
		<ul>

			<li>
				<?php echo lang('youtube_manager:active_title', 'f_active') ?>
				<?php echo form_dropdown('f_active', array(-1 => lang('global:select-all'), 1 => lang('global:yes'), 0 => lang('global:no') ), array(-1)) ?>
			</li>
			
			<li style="margin-bottom:0px;padding-bottom:0px;"><?php echo '<label for="search_data" style="width:100px;margin-top:15px;">'.lang('article_manager:search_all').'</label>'; ?>
				<?php echo form_input('f_keywords','','style="float:right;width:200px;margin-top:10px;"') ?>
			</li>
			
		
		</ul>
	<?php echo form_close() ?>
</fieldset>