<section class="title">
	<h4><?php echo lang('user:list_title') ?></h4>
</section>

<section class="item">
	<div class="content">
	
		<?php template_partial('filters') ?>
	
		<?php echo cmc_form_open('frm_users_action',ADMIN_URL.'/admins/action') ?>
		
			<div id="filter-stage">
				<?php template_partial('tables/users') ?>
			</div>
		
			<div class="table_action_buttons">
				<?php #$this->load->view('admin/partials/buttons', array('buttons' => array('activate', 'delete') )) ?>
			</div>
	
		<?php echo form_close() ?>
	</div>
</section>
<!-- delete with post action -->
<?php echo cmc_form_open('frm_users_delete',ADMIN_URL.'/admins/delete','id="user_delete"'); ?>
<?php echo form_close(); ?>