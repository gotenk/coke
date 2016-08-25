	<?php 
	echo '<b>TOTAL WORDS : '.$total_rows.'</b>';
	?>
	<div class="table_action_buttons">
		<!--<button class="btn blue" value="top" name="btnAction" type="submit">
			<span>Set TOP</span>
		</button>
		<button class="btn blue" value="untop" name="btnAction" type="submit">
			<span>Set Default</span>
		</button> | -->
		<button class="btn green" value="live" name="btnAction" type="submit">
			<span>Publish</span>
		</button>
		<button class="btn red" value="draft" name="btnAction" type="submit">
			<span>Un-publish</span>
		</button>
		<button class="btn red" value="black_listed" name="btnAction" type="submit">
			<span>Black Listed</span>
		</button>
	</div>
	<br>
	<table cellspacing="0">
		<thead>
			<tr>
				<th width="1%"></th>
				<th width="6%">ID</th>
				<th width="20%">Name</th>
				<th>Created</th>
              	<th>Status</th>
				<!--<th width="15%" style="text-align: center;"><?php echo lang('global:actions') ?></th> -->
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($data as $key=>$item) : ?>
				<tr>
					<td><input type="checkbox" name="id[]" value="<?php echo $item->id ?>" /> </td>
					<td><?php echo $item->id ?></td>
					<td><?php echo $item->name ?></td>
					<td><?php echo date('Y-m-d H:i:s',strtotime($item->created)) ?></td>
					<td><?php echo lang('search_engine:'.$item->status.'_label'); ?></td>
					<!-- <td style="padding-top:10px; text-align: center;">                    	
                        <a href="<?php echo site_url(ADMIN_URL.'/search_engine/master_list_name/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="btn orange"><?php echo lang('global:edit')?></a>
						<a href="javascript:void(0);" onclick=" $return_val = confirm($(this).attr('title')); if(!$return_val){return $return_val } var myForm =<?php echo htmlentities(json_encode(array('data_html'=>form_open(site_url(ADMIN_URL.'/search_engine/master_list_name/delete/'),array('id'=>'deleteForm')).form_hidden('id',$item->id).form_close()),JSON_HEX_APOS),ENT_QUOTES, 'UTF-8'); ?> $('#deleteForm').remove();$('body').append(myForm.data_html);$('#deleteForm').trigger('submit');" title="<?php echo lang('global:delete')?>" class="btn red"><?php echo lang('global:delete')?></a>
					</td> -->
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?php $this->load->view('admin/partials/pagination') ?>
	<br>

	<div class="table_action_buttons">
		<!--<button class="btn blue" value="top" name="btnAction" type="submit">
			<span>Set TOP</span>
		</button>
		<button class="btn blue" value="untop" name="btnAction" type="submit">
			<span>Set Default</span>
		</button> | -->
		<button class="btn green" value="live" name="btnAction" type="submit">
			<span>Publish</span>
		</button>
		<button class="btn red" value="draft" name="btnAction" type="submit">
			<span>Un-publish</span>
		</button>
		<button class="btn red" value="black_listed" name="btnAction" type="submit">
			<span>Black Listed</span>
		</button>
	</div>