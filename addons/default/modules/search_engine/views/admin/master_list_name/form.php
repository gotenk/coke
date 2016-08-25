<section class="title">
<?php if ($this->method == 'create'): ?>
	<h4>
        <?php echo lang('search_engine:create_list_name_title') ?>
    </h4>
<?php else: ?>
	<h4><?php echo sprintf(lang('search_engine:edit_list_name_title'), $data->name) ?></h4>
<?php endif ?>
</section>

<section class="item">
<div class="content">

<?php

    echo form_open() ?>
<div class="tabs">
	<!-- Content tab -->
	<div class="form_inputs">
		<fieldset>
			<ul>			
                <li>
                    <label for="title"><?php echo lang('search_engine:word_label'); ?> <span>*</span></label>
                    <div class="input">
						<?php echo form_input('name',$data->name); ?>
                    </div>
                </li>
                <li>
                    <label for="status"><?php echo lang('search_engine:status_label'); ?></label>
                    <div class="input">
                        <?php echo form_dropdown('status',$master_status,$data->status); ?>                      
                    </div>
                </li>
			</ul>
		</fieldset>
	</div>
	
</div>

<div class="buttons">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>
</div>

<?php echo form_close() ?>
</div>
</section>
