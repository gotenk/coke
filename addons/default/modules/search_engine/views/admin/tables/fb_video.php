	<?php 
	echo '<b>TOTAL VIDEOS : '.$total_rows.'</b>';
	?>
	<table cellspacing="0">
		<thead>
			<tr>
				<th width="1%"></th>
				<th width="5%">ID</th>
				<th width="10%">Facebook User</th>
                <th width="8%">Photo</th>
				<th width="8%">Video</th>
				<th>Description</th>
				<th width="6%">VIA</th>
				<th width="7%">Status</th>
				<th width="7%">Favorite</th>
				<th width="7%">Register</th>
				<th width="10%" class="collapse">Created On</th>
				<th width="12%" style="text-align: center;"><?php echo lang('global:actions') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($data as $key=>$item) : ?>
				<tr>
					<td><input type="checkbox" name="cerita_id[]" value="<?php echo $item->id ?>" /></td>
					<td><?php echo $item->id ?></td>
					<td><?php echo $item->name ?></td>
					<td>
						<?php if($item->photo_profile!=''){
							if ($item->via == "twitter") {
								$drafted = '_'.$item->id.'_drafted';
								$arr_photo = explode('.', $item->photo_profile);
								$arr_src = explode($drafted, $arr_photo['0']);
								$src = $arr_src['0'].'.'.$arr_photo['1'];
								
								//$file_src = base_url($item->photo);
								$file_src = base_url($src);
								
								if($item->author_id==0){
									//$file_src = $item->photo;
									$file_src = $src;
								}
							} else {
								$file_src = $item->photo_profile;
							}
							?>
							<img src="<?php echo $file_src; ?>"  height="50px" />
						<?php } ?>
					</td>
					<td><a href="<?PHP echo $item->video; ?>" target="_blank"><img src="<?php echo $item->video_preview ?>"  height="50px" /></a></td>
					<td><?php echo $item->description ?></td>
					<td><?php echo $item->via ?></td>
					<td><?php echo $item->status ?></td>
					<td><?php echo $item->favorite ?></td>
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
                        <a href="<?php echo site_url(ADMIN_URL.'/video/fb_create/'.$item->userid.'/'.$item->id) ?>" title="<?php echo lang('global:edit')?>" class="btn orange"><?php echo lang('global:edit')?></a>
						<a href="<?php echo site_url(ADMIN_URL.'/video/fb_delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="btn red"><?php echo lang('global:delete')?></a>
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
        <input type="hidden" name="fb_id" value="<?php echo $fb_id; ?>" readonly="readonly" />
		<button class="btn green" value="live" name="btnAction" type="submit">
			<span>Publish</span>
		</button>
		<button class="btn red" value="draft" name="btnAction" type="submit">
			<span>Un-publish</span>
		</button>
	</div>