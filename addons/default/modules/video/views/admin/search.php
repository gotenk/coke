<?php echo form_open('admin/instructor/action');?>

<h3><?php echo lang('instructor_list_title');?></h3>

<?php if (!empty($instructor)): ?>

	<table border="0" class="table-list" cellspacing="0">
		<thead>
			<tr>
				<th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
				<th><?php echo lang('instructor_post_label');?></th>
				<th class="width-10"><?php echo lang('instructor_category_label');?></th>
				<th class="width-10"><?php echo lang('instructor_date_label');?></th>
				<th class="width-5"><?php echo lang('instructor_status_label');?></th>
				<th class="width-10"></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
					<div class="inner filtered"><?php $this->load->view('admin/partials/pagination') ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($instructor as $post): ?>
				<tr>
					<td><?php echo form_checkbox('action_to[]', $post->id);?></td>
					<td><?php echo $post->title;?></td>
					<td><?php echo $post->category_title;?></td>
					<td><?php echo format_date($post->created_on);?></td>
					<td><?php echo lang('instructor_'.$post->status.'_label');?></td>
					<td>
						<?php echo anchor('admin/instructor/preview/' . $post->id, lang($post->status == 'live' ? 'global:view' : 'global:preview'), 'rel="modal-large" class="iframe" target="_blank"') . ' | ' ?>
						<?php echo anchor('admin/instructor/edit/' . $post->id, lang('global:edit'));?> |
						<?php echo anchor('admin/instructor/delete/' . $post->id, lang('global:delete'), array('class'=>'confirm')) ?>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<div class="buttons float-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete', 'publish'))) ?>
	</div>

<?php else: ?>
	<p><?php echo lang('instructor_no_posts');?></p>
<?php endif ?>

<?php echo form_close();?>