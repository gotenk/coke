	<?php 
	echo '<b>TOTAL PICTURE : '.$total_rows.'</b>';
	?>
	<table cellspacing="0">
		<thead>
			<tr>
				<th width="25%">Picture</th>
                <th width="35%">Info Detail</th>
                <th width="35%">Carousel Group</th>
				<th width="10%" style="text-align: center;"><?php echo lang('carousel:status_label') ?></th>
				<th width="15%" style="text-align: center;"><?php echo lang('global:actions') ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($pia as $item) : ?>
				<tr>
					<td><img src="<?php echo base_url($item->full_path); ?>" width="100px" /></td>
					<td>
						<?php 
							echo 
								'<b>IMAGE NAME</b> : <br />'.$item->filename.'<br /><br /> 
								<b>Created On</b> : <br />'.date('d M Y', $item->created_on).'<br /><br /> 
							';
						?>
					</td>
                    <td><?php echo $item->name_carousel; ?></td>
					<td style="text-align: center;"><?php echo lang('carousel:'.$item->status.'_label') ?></td>
					<td style="padding-top:10px; text-align: center;">
                        <a href="<?php echo site_url(ADMIN_URL.'/carousel/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="btn orange"><?php echo lang('global:edit')?></a>
						<a href="<?php echo site_url(ADMIN_URL.'/carousel/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="btn red"><?php echo lang('global:delete')?></a>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>

	<?php $this->load->view('admin/partials/pagination') ?>
	<!--
	<br>

	<div class="table_action_buttons">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete', 'publish'))) ?>
	</div>
	-->
