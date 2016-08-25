<fieldset id="filters">
	<legend><?php echo lang('global:filters') ?></legend>

	<?php echo form_open('', '', array('f_module' => $module_details['slug'])) ?>
		<ul>
			<li class="">
        		<label for="f_status">Kategory</label>
        		<select name="f_carousel">
	    			<option value="all">-- All -- </option>
	    			<?php 
	    			foreach ($list_carousel->result() as $key => $value) {
	    				echo '<option value="'.$value->id.'">'.$value->name.'</option>';
	    			}
	    			?>
    			</select>
    		</li>
    		<li class="">
        		<label for="f_status"><?php echo lang('carousel:status_label') ?></label>
        		<?php echo form_dropdown('f_status', array(0 => lang('global:select-all'), 'draft'=>lang('carousel:draft_label'), 'live'=>lang('carousel:live_label'))) ?>
    		</li>
			<li class="">
				<label for="f_category"><?php echo lang('global:keywords') ?></label>
				<?php echo form_input('f_keywords', '', 'style="width: 95%;"') ?>
			</li>

			
		</ul>
	<?php echo form_close() ?>
</fieldset>