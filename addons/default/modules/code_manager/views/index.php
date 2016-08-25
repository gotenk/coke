<?php if(isset($is_ajax)) : ?>
	
	<?php if($data): foreach($data->result() as $item ): ?> 
		<li>
			<div class="picture">
				<?php echo '<img src="'.base_url($item->picture_thumb).'" />'; ?>
			</div>
			<div class="text <?php echo random_text(array('gray','red','')); ?>">
				<div class="title">
					<?php echo html_entity_decode($item->title); ?>
				</div>
				<div class="description">
					<?php echo show_teaser_custom($item->content); ?>
				</div>	
				<div class="link-wrapper">
					<a href="<?php echo site_url(array('event','detail',$item->slug)); ?>">Read more >></a>
				</div>
			</div>
			<div class="clear"></div>
		</li>
	<?php   endforeach; endif;?>

<?php  else : ?>

<div class="article">
		<h2 class="page-title">
			Indonesia berbagi keceriaan
			<br>
			bersama Coca-Cola!
		</h2>
		<div class="page-description">
			Apa aja acara serunya?<br>
			Yuk, follow <a href="https://twitter.com/cocacola_id" target="_blank">@CocaCola_ID</a> untuk dapatkan update terbaru!
		</div>
		<div class="article-list">
			<ul>
				<?php if($data): foreach($data->result() as $item ): ?> 
					<li>
					<div class="picture">
						<?php echo '<img src="'.base_url($item->picture_thumb).'" />'; ?>
					</div>
					<div class="text <?php echo random_text(array('gray','red','')); ?>">
						<div class="title">
							<?php echo html_entity_decode($item->title); ?>
						</div>
						<div class="description">
							<?php echo show_teaser_custom($item->content); ?>
						</div>	
						<div class="link-wrapper" style="font-style: italic;">
							<a href="<?php echo site_url(array('event','detail',$item->slug)); ?>">Read more >></a>
						</div>
					</div>
					<div class="clear"></div>
					</li>
		
				<?php   endforeach; endif;?>
			</ul>
			<?php if($pagination['total_pages'] > 1) : ?>
				<div class="more">
					<a href="javascript:void(0);" class="more">{{ theme:image file="more-article.png" }}</a>
				</div>
			<?php endif; ?>
		</div>
		</div>
<?php endif; ?>