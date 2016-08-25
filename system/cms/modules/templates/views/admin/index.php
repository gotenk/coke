<?php if(!empty($templates)): ?>

<div class="one_full">
	<section class="title">
		<h4><?php echo lang('templates:default_title') ?></h4>
	</section>
		
	<section class="item">
		<div class="content">
	
		    <?php echo form_open(ADMIN_URL.'/templates/action') ?>
		
		    <table border="0" class="table-list" cellspacing="0">
		        <thead>
		            <tr>
		                <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
		                <th><?php echo lang('name_label') ?></th>
		                <th class="collapse"><?php echo lang('global:description') ?></th>
		                <th class="collapse"><?php echo lang('templates:language_label') ?></th>
		                <th width="220"></th>
		            </tr>
		        </thead>
		
		        <tbody>
				
		    <?php foreach ($templates as $template): ?>
				<?php if($template->is_default): ?>
		            <tr>
						<td><?php echo form_checkbox('action_to[]', $template->id);?></td>
		                <td><?php echo $template->name ?></td>
		                <td class="collapse"><?php echo $template->description ?></td>
		                <td class="collapse"><?php echo $template->lang ?></td>
		                <td class="actions">
						<div class="buttons buttons-small align-center">
							<?php echo anchor(ADMIN_URL.'/templates/preview/' . $template->id, lang('buttons:preview'), 'class="button preview modal"') ?>
		                    <?php echo anchor(ADMIN_URL.'/templates/edit/' . $template->id, lang('buttons:edit'), 'class="button edit"') ?>
							<?php echo anchor(ADMIN_URL.'/templates/create_copy/' . $template->id, lang('buttons:clone'), 'class="button clone"') ?>
						</div>
		                </td>
		            </tr>
				<?php endif ?>
		    <?php endforeach ?>
			</tbody>
			</table>
		    <?php echo form_close() ?>
		 
		 	<div class="table_action_buttons">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )) ?>
			</div>
		</div>
	</section>
</div>

<div class="one_full">
	<section class="title">
		<h4><?php echo lang('templates:user_defined_title') ?></h4>
	</section>
	
	<?php echo form_open(ADMIN_URL.'/templates/delete') ?>
	   
	<section class="item">
		<div class="content">
			<table border="0" class="table-list clear-both" cellspacing="0">
		        <thead>
		            <tr>
		                <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
		                <th><?php echo lang('name_label') ?></th>
		                <th><?php echo lang('global:description') ?></th>
		                <th><?php echo lang('templates:language_label') ?></th>
		                <th width="220"></th>
		            </tr>
		        </thead>
		
		        <tbody>
			
		    <?php foreach ($templates as $template): ?>
				<?php if(!$template->is_default): ?>
		            <tr>
						<td><?php echo form_checkbox('action_to[]', $template->id);?></td>
		                <td><?php echo $template->name ?></td>
		                <td><?php echo $template->description ?></td>
		                <td><?php echo $template->lang ?></td>
		                <td class="actions">
						<div class="buttons buttons-small align-center">
							<?php echo anchor(ADMIN_URL.'/templates/preview/' . $template->id, lang('buttons:preview'), 'class="button preview modal"') ?>
		                    <?php echo anchor(ADMIN_URL.'/templates/edit/' . $template->id, lang('buttons:edit'), 'class="button edit"') ?>
							<?php echo anchor(ADMIN_URL.'/templates/delete/' . $template->id, lang('buttons:delete'), 'class="button delete"') ?>
							<a href="javascript:void(0);" onclick=" $return_val = confirm( typeof(max.lang.dialog_message)!= 'undefined'? max.lang.dialog_message: $(this).attr('title')); if(!$return_val){ return $return_val } var myForm =<?php echo htmlentities(json_encode(array('form_val'=>form_hidden('id',$template->id)),JSON_HEX_APOS),ENT_QUOTES, 'UTF-8'); ?>;$('#frm_email_template_delete input[name=\'id\']').remove();$('#frm_email_template_delete').append(myForm.form_val);$('#frm_email_template_delete').trigger('submit');" title="<?php echo lang('global:delete')?>" class="button delete"><?php echo lang('global:delete')?></a>
						</div>
		                </td>
		            </tr>
				<?php endif ?>
		    <?php endforeach ?>
			
			
		        </tbody>
		    </table>
		
			<div class="table_action_buttons">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )) ?>
			</div>
		
		    <?php echo form_close() ?>
		</div>
	</section>
	<?php echo cmc_form_open('frm_email_template_delete',ADMIN_URL.'/templates/delete','id="frm_email_template_delete"'); ?>
</div>
	
<?php else: ?>

<div class="one_full">
	<section class="item">
		<div class="content">
	    <p><?php echo lang('templates:currently_no_templates') ?></p>
		</div>
	</section>
</div>

<?php endif ?>