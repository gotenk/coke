<?php if (!empty($articles)): ?>
	<table border="0" class="table-list" cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<th width="15%" ><?php echo lang('article_manager:title');?></th>
				<th  width="25%"><?php echo lang('article_manager:content');?></th>
				<th  width="15%"><?php echo lang('article_manager:date_label');?></th>
				<th width="5%" style="text-align:center;"><?php echo lang('article_manager:sort_order'); ?></th>
				<th width="15%"><?php echo lang('article_manager:status'); ?></th>
				<th width="25%"></th>
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
			<?php foreach ($articles->result() as $article): 
			 ?>
				<tr id="<?php echo $article->id; ?>">
		
					<td ><?php echo $article->title; ?></td>
					<td><?php echo character_limit_custom($article->content) ?></td>
					<td><?php echo ($article->date_custom) ?></td>
					<td class="collapse"><span class="move-handle"></span></td>
					<td class="collapse"><?php echo $article->status == 1 ? lang('article_manager:active') : lang('article_manager:inactive')  ?></td>
					<td class="actions">
						<?php echo anchor(ADMIN_URL.'/article_manager/edit/' . $article->id, lang('article_manager:update'), array('class'=>'button','onclick'=>'editAjax(this);return false;')) ?>
						<?php //echo anchor(ADMIN_URL.'/article_manager/change_status' , lang('article_manager:update_status'), array('class'=>'button change','onclick'=>'updateStatusAjax(this);return false;')) ?>
						<?php //echo anchor(ADMIN_URL.'/article_manager/delete', lang('article_manager:delete'), array('class'=>'button delete','onclick'=>'deleteAjax(this);return false;')) ?>
						<a href="javascript:void(0);" onclick="var myForm =<?php echo htmlentities(json_encode(array('data_html'=>form_open(site_url(ADMIN_URL.'/article_manager/change_status/'),array('id'=>'updateForm')).form_hidden('id',$article->id).form_close()),JSON_HEX_APOS),ENT_QUOTES, 'UTF-8'); ?>; $('#updateForm').remove();$('body').append(myForm.data_html);$('#updateForm').trigger('submit');"  class="button change"><?php echo lang('article_manager:update_status')?></a>
						<a href="javascript:void(0);" onclick=" $return_val = confirm($(this).attr('title')); if(!$return_val){return $return_val } var myForm =<?php echo htmlentities(json_encode(array('data_html'=>form_open(site_url(ADMIN_URL.'/article_manager/delete/'),array('id'=>'deleteForm')).form_hidden('id',$article->id).form_close()),JSON_HEX_APOS),ENT_QUOTES, 'UTF-8'); ?>; $('#deleteForm').remove();$('body').append(myForm.data_html);$('#deleteForm').trigger('submit');" title="<?php echo lang('global:delete')?>" class="btn red"><?php echo lang('global:delete')?></a>
						<?php //echo anchor(ADMIN_URL.'/article_manager/up', lang('article_manager:delete'), array('class'=>'button delete','onclick'=>'deleteAjax(this);return false;')) ?>
						<?php //echo anchor(ADMIN_URL.'/article_manager/down', lang('article_manager:delete'), array('class'=>'button delete','onclick'=>'deleteAjax(this);return false;')) ?>
					</td>
				</tr>
			<?php 
			 endforeach; ?>
		</tbody>
	</table>
<?php endif ?>