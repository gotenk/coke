<fieldset id="filters">

	<legend><?php echo lang('global:filters') ?></legend>
	
	<?php echo cmc_form_open('index_users', '') ?>
	<?php echo form_hidden('f_module', $module_details['slug']) ?>
		<ul>
			<?php /*for coke tune only
			<li>
				<?php echo '<b>Tanggal Awal</b><br />'; ?>
				<?php echo form_input('f_date_start') ?>
			</li>
			<li>
				<?php echo '<b>Tanggal Akhir</b><br />'; ?>
				<?php echo form_input('f_date_end') ?>
			</li>
			<br />*/?>

			<li>
				<?php echo lang('user:active', 'f_active') ?>
				<?php echo form_dropdown('f_active', array(0 => lang('global:select-all'), 1 => lang('global:yes'), 2 => lang('global:no') ), array(0)) ?>
			</li>
			<?php /*for coke tune only
			<li>
				<?php echo lang('user:group_label', 'f_group') ?>
				<?php echo form_dropdown('f_group', array(0 => lang('global:select-all')) + $groups_select) ?>
			</li>

			<li style="top:-10px;width: 300px;">
				<?php echo '<b>Search</b><br />'; ?>
				<?php echo form_input('f_keywords', '', 'style="width: 67%;"') ?>
				<?php echo anchor(current_url(), lang('buttons:cancel'), 'class="cancel"') ?>
			</li>
			
			<li class="" style="top:24px;float:right;">
				<a href="javascript:void(0);" title="Export Data" class="btn blue" id="export-data">Export Data CSV</a>
			</li>
			*/?>
		</ul>
	<?php echo form_close() ?>
</fieldset>

<script type="text/javascript">
	$( "input[name='f_date_start']" ).datepicker({
    	dateFormat: "dd-mm-yy",
    	onSelect : function(selected){
    		$("input[name='f_date_start']").trigger('datepicking')
    		$("input[name='f_date_end']").datepicker("option","minDate", selected)
    	}
    });
    $( "input[name='f_date_end']" ).datepicker({
    	dateFormat: "dd-mm-yy",
    	onSelect : function(selected){
    		$("input[name='f_date_end']").trigger('datepicking')
    		$("input[name='f_date_start']").datepicker("option","maxDate", selected)
    	}
    });
	
	$('#export-data').click(function(){
		var status = $( "select[name='f_active']" ).val();
		var group = $( "select[name='f_group']" ).val();
		var keywords = $( "input[name='f_keywords']" ).val();
			if(keywords==''){ keywords=0; }
		var date_from = $( "input[name='f_date_start']" ).val();
			if(date_from==''){ date_from=0; }
		var date_end = $( "input[name='f_date_end']" ).val();
			if(date_end==''){ date_end=0; }
		
		var admin_url = '<?php echo ADMIN_URL; ?>';

		window.location.href=SITE_URL+admin_url+'/users/export_data/'+date_from+'/'+date_end+'/'+keywords+'/'+status+'/'+group;
	});
</script>