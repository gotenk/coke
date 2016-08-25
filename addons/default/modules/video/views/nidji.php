<div class="nidji">
	<div class="left-side">
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
			                    $('.mejs-poster').show();
			                    player.exitFullScreen();
			                });
			            }
					});
					//console.log('kcl');
				}
			}
		</script>
		<style>
			#middle .nidji .video .mejs-mediaelement{
				overflow: hidden;
			}			
			.mejs-overlay-button{
				margin-top: -50px !important;
			}

			@media screen and (max-width:1024px){
				.mejs-duration, .mejs-overlay-button{
					display:none !important;
				}
			}
		</style>
		<div class="video">
			<!--
			<video controls="controls" preload="none">
		    	<source type="video/youtube" src="https://www.youtube.com/watch?v=y5a9R5RIEdc" /> https://img.youtube.com/vi/y5a9R5RIEdc/1.jpg
	    	</video>	
	    	http://www.youtube.com/watch?v=o4y_ObGz-M4&feature=youtu.be
	    	-->
	    	<video style="width:100%; height:100%;" controls="controls" preload="none" > 
		    	<!-- <source type="video/youtube" src="https://www.youtube.com/watch?v=y5a9R5RIEdc" /> -->
		    	<source type="video/youtube" src="https://www.youtube.com/watch?v=o4y_ObGz-M4&feature=youtu.be" />
		    	<object type="application/x-shockwave-flash" data="<?php base_url('addons/default/themes/ramadan/media/flashmediaelement.swf'); ?>">         
		    		<param name="movie" value="<?php base_url('addons/default/themes/ramadan/media/flashmediaelement.swf'); ?>" />         
		    		<!-- <param name="flashvars" value="controls=true&file=https://www.youtube.com/watch?v=y5a9R5RIEdc" />   -->
		    		<param name="flashvars" value="controls=true&file=https://www.youtube.com/watch?v=o4y_ObGz-M4&feature=youtu.be" />      
		    	</object> 
		    	<object type="application/x-shockwave-flash" data="<?php base_url('addons/default/themes/ramadan/media/flashmediaelement.swf'); ?>">
					<param name="movie" value="<?php base_url('addons/default/themes/ramadan/media/flashmediaelement.swf'); ?>" />
					<!-- <param name="flashvars" value="controls=true&amp;file=https://www.youtube.com/watch?v=y5a9R5RIEdc" /> -->
					<param name="flashvars" value="controls=true&amp;file=https://www.youtube.com/watch?v=o4y_ObGz-M4&feature=youtu.be" />
				</object>
	    	</video>	

		</div>
    	<div class="link">
			<a href="<?php echo site_url('download-lagu')?>" onclick="
		_gaq.push(['_trackEvent', 'Button', 'Download Lagu', 'Click']);">DOWNLOAD LAGU</a>  		
			<!-- <a href="<?php echo site_url(); ?>" onclick="
		_gaq.push(['_trackEvent', 'Button', 'Kirim Ceritamu - Lagu', 'Click']);" >SAMPAIKAN CERITA</a> -->
		<a href="javascript:void(0);" onclick="$('#login-form').show(); return false; " >SAMPAIKAN CERITA</a>
    	</div>	
	</div>
	<div class="right-side">
		<div class="title-1">CERITA</div>
		<div class="title-2">NIDJI</div>
		<p class="text">
			Begitu banyak cara untuk menyambut bulan Ramadan. Begitu pula dengan Nidji. Sebagai grup band dengan aliran modern rock, tahun 2013 lalu Nidji menyambut bulan Ramadan dengan meluncurkan single berjudul  Cahaya Ramadan.
			<br/><br/>
			Bersama COCA-COLA, grup musik yang beranggotakan Giring (vokal), Rama dan Ariel (gitar), Adrie (drum), Andro (bass), dan Randy (keyboard) ini kembali menyambut bulan Ramadan tahun ini dengan tembang terbarunya yang berjudul
			<strong>Judul Lagu Blom Tau Namanya</strong>.
			<br/><br/>
			Lagu yang membawa semangat untuk berkumpul kembali di bulan yang penuh maaf ini. Bukan hanya bertemu dengan keluarga, namun juga terhubung kembali dengan orang-orang tersayang.
		</p>
	</div>
	<div class="clear"></div>
</div>