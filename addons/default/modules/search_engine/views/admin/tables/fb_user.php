	<?php 
	echo '<b>TOTAL VIDEOS : '.$total_rows.'</b>';
	?>
	<table cellspacing="0">
		<thead>
			<tr>
				<th width="1%"></th>
				<th width="6%">ID</th>
				<th>ID Facebook</th>
				<th>User Name (Alias Name)</th>
                <th width="10%">Photo Profile</th>
				<th width="20%">Jumlah Video</th>
				<th width="8%" class="collapse">Created</th>
				<th width="10%" style="text-align: center;"><?php echo lang('global:actions') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($data as $key=>$item) : ?>
				<tr>
					<td><input type="checkbox" name="cerita_id[]" value="<?php echo $item->id ?>" /> </td>
					<td><?php echo $item->id ?></td>
					<td><?php echo $item->fb_id ?></td>
					<td><?php echo $item->display_name ?></td>
					<td>
						<?php if($item->fb_id!=''){
							?>
							<img src="https://graph.facebook.com/<?PHP echo $item->fb_id; ?>/picture?type=square" height="50px" />
						<?php } ?>
					</td>
					<td><?php echo $item->jumlah_video ?></td>
                    <td><?php echo date('d M Y', strtotime($item->created)); ?></td>
					<td style="padding-top:10px; text-align: center;">
                        <a href="<?php echo site_url(ADMIN_URL.'/video/facebook_video/' . $item->fb_id) ?>" title="<?php echo lang('global:edit')?>" class="btn orange"><?php echo ucfirst(lang('video:video_title')) ?></a>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?php $this->load->view('admin/partials/pagination') ?>
	<br>