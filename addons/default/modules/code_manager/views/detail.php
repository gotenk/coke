<div class="detail-article">
		<div class="back-link">
			<a href="{{ url:site uri="event" }}">{{ theme:image file="back.png" }} kembali ke halaman acara seru</a>
		</div>
		<div class="picture">
			<img src="<?php echo base_url($data->picture); ?>">	
		</div>
		<div class="title">
			<?php echo $data->title; ?>
		</div>
		<div class="social">
			<a href="javascript:void(0);" onclick="postFeedURL('{{fb_url}}');return false;" class="share-btn facebook">share to facebook</a>
			<a href="{{ share_twitter }}" class="share-btn share-twitter twitter">share to twitter</a>
		</div>
		<div class="detail-body">
			<?php echo sanitize_html( html_entity_decode($data->content) ); ?>
		</div>
</div>