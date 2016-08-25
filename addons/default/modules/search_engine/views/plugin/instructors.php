<ul class="small_flight_instructor">
	<?php foreach($instructor as $item): ?>
		<li>
		<div class="small_frame_trainer">
			<a href="<?php echo site_url(array('instructor',$item->slug)) ?>">
				<img src="<?php echo site_url(array('files','large',$item->trainer_image)); ?>" alt="<?php echo $item->instructor_name ;?>" title="Flight Instructor"/>
			</a>
		</div>
		<span class="trainer_name">
			<?php echo  $item->instructor_name; ?>
		</span>
		<span class="trainer_title">
			<?php echo $item->position;?>
		</span>
	</li>
	<?php endforeach; ?>
	<div class="clearfix"></div>			
</ul>