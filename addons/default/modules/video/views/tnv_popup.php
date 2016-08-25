<script type="text/javascript">
	$(document).ready(function(){
		video_resize();
		$(window).resize(function(){
			video_resize();
		})
	})
	function video_resize(){
		if($(window).width() > 1024){
			$('video,audio').mediaelementplayer({
				features: ['playpause','progress','current','duration','tracks','volume','fullscreen'],
				defaultVideoWidth: 570,
				defaultVideoHeight: 310,
				enableAutosize: true,
				loop: false,
				success: function(player, node) {
	                player.addEventListener('ended', function(e){
	                    //alert('ended');
	                    //$('.mejs-unfullscreen button').click();
	                    AUTO_RELOAD = true;
	                    setTimeout(function () {
							if(AUTO_RELOAD){
								get_cerita(0, 'all'); 
					    		$('.loading').hide();
					    		$('#detail-cerita').hide();
					    		return false;
							} 
					    }, 15000);
	                    $('.mejs-poster').show();
	                    player.exitFullScreen();
	                });
	            }
			});
			//console.log('besar');
		}else{
			$('video,audio').mediaelementplayer({
				features: ['playpause','progress','current','duration','tracks','volume','fullscreen'],
				defaultVideoWidth: 290,
				defaultVideoHeight: 153,
				enableAutosize: true,
				loop: false,
				success: function(player, node) {
	                player.addEventListener('ended', function(e){
	                    //alert('ended');
	                    //$('.mejs-unfullscreen button').click();
	                    AUTO_RELOAD = true;
	                    setTimeout(function () {
							if(AUTO_RELOAD){
								get_cerita(0, 'all'); 
					    		$('.loading').hide();
					    		$('#detail-cerita').hide();
					    		return false;
							} 
					    }, 15000);
	                    $('.mejs-poster').show();
	                    player.exitFullScreen();
	                });
	            }
			});
			//console.log('kcl');
		}
	}
</script>
<div class="" id="wrapper-photo-video-2" >
				
	<div class="video">
    	<video style="width:100%; height:100%;" controls="controls" preload="none"  > 
	    	<source type="video/youtube" src="https://www.youtube.com/watch?v=Ict635rYyWY" />
	    	<object type="application/x-shockwave-flash" data="<?php base_url('addons/default/themes/ramadan/media/flashmediaelement.swf'); ?>">         
	    		<param name="movie" value="<?php base_url('addons/default/themes/ramadan/media/flashmediaelement.swf'); ?>" />         
	    		<param name="flashvars" value="controls=true&file=https://www.youtube.com/watch?v=Ict635rYyWY" />      
	    	</object> 
	    	<object type="application/x-shockwave-flash" data="<?php base_url('addons/default/themes/ramadan/media/flashmediaelement.swf'); ?>">
				<param name="movie" value="<?php base_url('addons/default/themes/ramadan/media/flashmediaelement.swf'); ?>" />
				<param name="flashvars" value="controls=true&amp;file=https://www.youtube.com/watch?v=Ict635rYyWY" />
			</object>
    	</video>	
	</div>

</div>