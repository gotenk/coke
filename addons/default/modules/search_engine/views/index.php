<?php if (isset($is_ajax)): ?>
<?php if($data) { foreach($data as $item){ ?>
	<div class="item">
		<div class="item-wrapper">
			<div class="picture">
				<?php echo '<img src="'.base_url($item->picture).'" />'; ?>
			</div>
			<div class="text <?php echo random_text(array('gray','red','')); ?>">
				<label><?php echo ($item->via == 'twitter')? $item->screen_name : $item->name; ?></label>
				<span><?php echo $item->location; ?></span>
			</div>
		</div>
	</div>					
<?php	}
}	?>
<?php else : ?>
<div class="gallery">
			<h2 class="page-title">
				Indonesia berbagi keceriaan
				<br>
				bersama Coca-Cola!
			</h2>
			<div class="page-description">
				Apakah kamu salah satunya?<br>
				Yuk, share selfie kamu di media social, sertakan hastag #shareCokeID<br>
				biar kamu bisa muncul di sini!
			</div>
			<div class="filter">
				<?php echo form_open(uri_string(),array('id'=>'search_query')); ?>
				<input type="text" name="search_field" id="autocomplete" value="{{ search_field }}"class="input-text">
					<input type="submit" class="submit">
				<?php echo form_close(); ?>
				<div class="list">
					<a href="">Wayan</a>
					<a href="">Nengah</a>
					<a href="">Putu</a>	
				</div>
				<div class="clear"></div>
			</div>
			<div class="gallery-list" id="gallery-list">
				<div class="grid-sizer"></div>
				<?php if($data) { foreach($data as $item){ ?>
					<div class="item">
						<div class="item-wrapper">
							<div class="picture">
								<?php echo '<img src="'.base_url($item->picture).'" />'; ?>
							</div>
							<div class="text <?php echo random_text(array('gray','red','')); ?>">
								<label><?php echo ($item->via == 'twitter')? $item->screen_name : $item->name; ?></label>
								<span><?php echo $item->location; ?></span>
							</div>
						</div>
					</div>					
				<?php	}
				}	?>
			</div>
			<?php if($pagination['total_pages'] > 1) :?>
			<div id="loader">
				{{ theme:image file="example_loading.gif" }}
				LOADING...
			</div>
			<?php endif; ?>
			<style type="text/css">
			#loader{
				display: block;
				text-align: center;
				line-height: 60px;
				height: 60px;
			}
			#loader img {
				vertical-align: text-bottom;
			}
			</style>
			</div>
<?php endif; ?>