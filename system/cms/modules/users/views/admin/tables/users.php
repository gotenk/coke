<?php if (!empty($users)): ?>
	<table border="0" class="table-list" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th with="30" class="align-center"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
				<th><?php echo lang('user:name_label');?></th>
				<th class="collapse">Email</th>
				<?php /*<th><?php echo lang('user:group_label');?></th>*/?>
				<th class="collapse"><?php echo lang('user:active') ?></th>
				<th class="collapse"><?php echo lang('user:joined_label');?></th>
				<th class="collapse"><?php echo lang('user:last_visit_label');?></th>
				<th width="200"></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<div class="inner"><?php $this->load->view('admin/partials/pagination') ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php $link_profiles = Settings::get('enable_profiles') ?>
			<?php foreach ($users as $member): ?>
				<tr>
					<td class="align-center"><?php echo form_checkbox('action_to[]', $member->id) ?></td>
					<td>
					<?php if ($link_profiles) : ?>
						<?php echo anchor(ADMIN_URL.'/admins/preview/' . $member->id, $member->display_name, 'target="_blank" class="modal-large"') ?>
					<?php else: ?>
						<?php echo $member->display_name ?>
					<?php endif ?>
					</td>
					<td class="collapse"><?php echo mailto($member->email) ?></td>
					<?php /*<td><?php echo $member->group_name ?></td>*/?>
					<td class="collapse"><?php echo $member->active ? lang('global:yes') : lang('global:no')  ?></td>
					<td class="collapse"><?php echo format_date($member->created_on) ?></td>
					<td class="collapse"><?php echo ($member->last_login > 0 ? format_date($member->last_login) : lang('user:never_label')) ?></td>
					<td class="actions">
						<?php echo anchor(ADMIN_URL.'/admins/edit/' . $member->id, lang('global:edit'), array('class'=>'button edit')) ?>
						<a href="javascript:void(0);" onclick=" $return_val = confirm( typeof(max.lang.dialog_message)!= 'undefined'? max.lang.dialog_message: $(this).attr('title')); if(!$return_val){ return $return_val } var myForm =<?php echo htmlentities(json_encode(array('form_val'=>form_hidden('id',$member->id)),JSON_HEX_APOS),ENT_QUOTES, 'UTF-8'); ?>;console.log($('#user_delete'));$('#user_delete input[name=\'id\']').remove();$('#user_delete').append(myForm.form_val);$('#user_delete').trigger('submit');" title="<?php echo lang('global:delete')?>" class="button delete"><?php echo lang('global:delete')?></a>
						<?php /*echo anchor(ADMIN_URL.'/admins/delete/' . $member->id, lang('global:delete'), array('class'=>'confirm button delete')) */ ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>