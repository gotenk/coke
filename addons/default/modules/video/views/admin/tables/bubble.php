	<?php 
	echo '<b>TOTAL VIDEOS : '.$total_rows.'</b>';
	?>
	<table cellspacing="0">
		<thead>
			<tr>
				<th width="1%"></th>
				<th width="6%">ID</th>
				<th>Decription</th>
                <th width="10%">Photo</th>
				<th width="10%">Video</th>
				<th width="10%">VIA</th>
				<th width="7%">Status</th>
				<th width="10%">Favorite</th>
				<th width="7%">Register</th>
				<th width="10%" class="collapse">Created On</th>
				<th width="15%" style="text-align: center;"><?php echo lang('global:actions') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($data as $key=>$item) : ?>
				<tr>
					<td><input type="checkbox" name="cerita_id[]" value="<?php echo $item->id ?>" /> </td>
					<td><?php echo $item->id ?></td>
					<td><?php echo $item->description ?></td>
					<td>
						<?php if($item->photo_profile!=''){
							if ($item->via == "facebook") {
								$file_src = $item->photo_profile;
							} else {
								$file_src = base_url($item->photo_profile);
							}
							?>
							<img src="<?php echo $file_src; ?>"  height="50px" />
						<?php } ?>
					</td>
					<td><a href="<?PHP echo $item->video; ?>" target="_blank"><img src="<?php echo base_url($item->video_preview) ?>"  height="50px" /></a></td>
					<td><?php echo $item->via ?></td>
					<td><?php echo $item->status ?></td>
					<td><?php echo ucfirst($item->favorite) ?></td>
					<td>
						<?php
						if ($item->userid_match != "") {
							echo 'Register';
						} else {
							echo 'Belum';
						}
                        ?>
                    </td>
                    <td><?php echo date('d M Y', $item->created_on); ?></td>
					<td style="padding-top:10px; text-align: center;">
                    	<?PHP if ($item->via == "facebook") { ?>
                        <a href="<?php echo site_url(ADMIN_URL.'/video/create/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="btn orange"><?php echo lang('global:edit')?></a>
                        <?PHP } else { ?>
                        <a href="<?php echo site_url(ADMIN_URL.'/video/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="btn orange"><?php echo lang('global:edit')?></a>
                        <?PHP } ?>
						<a href="javascript:void(0);" onclick=" $return_val = confirm($(this).attr('title')); if(!$return_val){return $return_val } var myForm =<?php echo htmlentities(json_encode(array('data_html'=>form_open(site_url(ADMIN_URL.'/video/delete/'),array('id'=>'deleteForm')).form_hidden('id',$item->id).form_close()),JSON_HEX_APOS),ENT_QUOTES, 'UTF-8'); ?>;console.log(myForm); $('#deleteForm').remove();$('body').append(myForm.data_html);$('#deleteForm').trigger('submit');" title="<?php echo lang('global:delete')?>" class="btn red"><?php echo lang('global:delete')?></a>
					</td>
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
	</div>