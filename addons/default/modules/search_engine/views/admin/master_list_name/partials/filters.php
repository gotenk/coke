<fieldset id="filters">
	<legend><?php echo lang('global:filters') ?></legend>

	<?php echo form_open('', '', array('f_module' => $module_details['slug'])) ?>
		<ul>
			<li class="">
        		<label for="f_status">Status</label>
        		<?php echo form_dropdown('f_status', array(0 => lang('global:select-all'), 'draft'=>'Draft', 'live'=>'Live')) ?>
    		</li>
			<!--<li class="">
        		<label for="f_status">Sosmed</label>
        		<?php echo form_dropdown('f_via', array(0 => lang('global:select-all'), 'manual_add'=>lang('search_engine:manual_add'), 'twitter'=>'Twitter', 'instagram'=>'Instagram')) ?>
    		</li>
			<li class="">
        		<label for="f_status">Favorite</label>
        		<?php echo form_dropdown('f_favorite', array(0 => lang('global:select-all'), 'ya'=>'Ya', 'tidak'=>'Tidak')) ?>
    		</li>-->
			<!--<li class="">
        		<label for="f_status">TOP Data</label>
        		<?php //echo form_dropdown('f_top', array(0 => lang('global:select-all'), 'top'=>'TOP', 'no'=>'No Top')) ?>
    		</li>
			<li class="">
        		<label for="f_status">Data Source</label>
        		<?php //echo form_dropdown('f_source', array(0 => lang('global:select-all'), 'submmision'=>'Submmision', 'crawl'=>'Crawling')) ?>
    		</li>

			<br />	-->
			<li class="">
				<label for="f_category"><?php echo lang('global:keywords') ?></label>
				<?php echo form_input('f_keywords', '', 'style="width: 95%;"') ?>
			</li>		
			<!-- <li class="">
        		<label for="f_status">Start Date</label>
        		<?php echo form_input('f_date_start', '', 'style="width: 95%;"') ?>
    		</li>
			<li class="">
				<label for="f_category">End Date</label>
				<?php echo form_input('f_date_end', '', 'style="width: 95%;"') ?>
			</li>-->
			<!--
			<br />
			<li class="" style="margin-left:5px;">
				<a href="javascript:void(0);" title="Export Candidate To Excel" class="btn blue" id="export-kandidat">Export Data</a>
			</li>
			-->			
		</ul>
	<?php echo form_close() ?>
</fieldset>

<!--<fieldset id="filters">
	<legend>Export Crawling Facebook</legend>
		<ul>
			<li class="" style="margin-left:5px;">
				<a href="javascript:void(0);" title="Export To CSV" class="btn blue" id="export-kandidat">Export Data</a>
			</li>		
		</ul>
</fieldset>-->


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
	
	$('#export-kandidat').click(function(){
		window.location.href=SITE_URL+ADMIN_URL+'/bubble/export_fb_crawling';
		
	});
</script>