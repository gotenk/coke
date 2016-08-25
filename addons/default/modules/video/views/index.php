
<div class="cerita">
	<div id="wrapper-list">
		<div id="list-cerita" class="list-cerita">
			<?php 
			if($data->num_rows() > 0){ 
				foreach ($data->result() as $key => $value) {
					$is_yt = false;
					$link_img = '';

					if($value->photo!=''){ 
						if($value->author_id){
							$link_img = base_url().$value->photo;
						}else{
							$link_img = $value->photo;
							if($value->via_tw){
								$link_img = base_url().$value->photo;
							}
						}
					}

					$pp = '';
					$username = '';
					if($value->video!=''){ 
						$is_yt = true;
						$arr_video = explode('watch?v=', $value->video);
						$link_img = "https://img.youtube.com/vi/".$arr_video['1']."/0.jpg";
						
						$yt_detail = get_video_author($arr_video['1']); 
						$pp = $yt_detail['propic'];
						$username = character_limiter($yt_detail['author'], 15);
					}
					$cerita = word_limiter($value->content, 15);
					
					//var_dump($yt_detail); die();

					
					if($value->via_fb){
						$pp = $value->profile_pic; //"https://graph.facebook.com/" . $value->username . "/picture?type=square";
						$username = character_limiter($value->name, 15);
					}
					if($value->via_tw){
						$username = '@'.character_limiter($value->username, 15);
						$pp = base_url().$value->profile_pic;
					}
					if($value->via_yt){
						//$username = character_limiter($value->name, 20);
					}

					if($pp==''){
						$pp = base_url('addons/default/themes/ramadan/img/pict-sample-thumbs.jpg');
					}
					?>		
					<div class="item kotak-cerita" data-id="<?php echo $value->id; ?>" ini-yt="<?php echo $is_yt; ?>" ini-big="no">
						<div class="thumbs"><img src="<?php echo $pp; ?>" width="100%" /></div>
						<div class="username">
							<a href="javascript:void(0);"><?php echo $username==''? 'USERNAME':$username; ?></a>
							<span><?php echo date('d M Y', strtotime($value->created)); ?></span>
						</div>
						<div class="clear"></div>
						<p class="text"><?php echo $cerita ?></p>
						<div class="img-video">
							<?php if($link_img!=''): ?>
							<img src="<?php echo $link_img; ?>" width="100%" >
						<?php endif; ?>
						</div>
						<div class="mask"></div>
					</div>
					<?php		
				}
			}else{
				echo '<div style="margin:auto; text-align:center; font-size:20px; font-weight:bold; padding-top:50px;">NO DATA</div>';			
			}	
			?>
			<!--  
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				<p class="text">Lorem ipsum dolor sit amet, consectetur adipisicing edivt, sed do eiusmod
				tempor incididunt ut labore et </p>
				{{ theme:image file="pict-sample-cerita-3.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				<p class="text">Lorem ipsum dolor sit amet</p>
				{{ theme:image file="pict-sample-cerita-2.png" }}
			</div>
			
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				<p class="text">Lorem ipsum dolor sit amet, consectetur adipisicing edivt,</p>
				{{ theme:image file="pict-sample-cerita-1.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				<p class="text">Lorem ipsum dolor sit amet, consectetur adipisicing edivt, sed do eiusmod
				tempor incididunt ut labore et dolore magna adivqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut adivquip ex ea commodo</p>
				{{ theme:image file="pict-sample-cerita-4.png" }}
			</div>
			<!--
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				<p class="text">Lorem ipsum dolor sit amet, consectetur adipisicing edivt, sed do eiusmod
				tempor incididunt ut labore et dolore </p>
				{{ theme:image file="pict-sample-cerita-1.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				{{ theme:image file="pict-sample-cerita-2.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				{{ theme:image file="pict-sample-cerita-4.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				<p class="text">Lorem ipsum dolor sit amet, consectetur adipisicing edivt, sed do eiusmod
				tempor incididunt ut labore et dolore magna adivqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut adivquip ex ea commodo</p>
				{{ theme:image file="pict-sample-cerita-1.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				{{ theme:image file="pict-sample-cerita-1.png" }}
			</div>
			<div class="item">
				<div class="thumbs"> {{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				{{ theme:image file="pict-sample-cerita-3.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				{{ theme:image file="pict-sample-cerita-2.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				{{ theme:image file="pict-sample-cerita-1.png" }}
			</div>
			<div class="item kotak-cerita">
				<div class="thumbs">{{ theme:image file="pict-sample-thumbs.jpg" }}</div>
				<div class="username"><a href="">@sutalasc</a><span>suta Lascarya</span></div>
				<div class="clear"></div>
				{{ theme:image file="pict-sample-cerita-3.png" }}
			</div>
			-->
		</div>
	</div>
	<div class="social-btn">
		Urut berdasarkan : 
		<a href="javascript:void(0);" id="sorted-fb" class="social-cerita fb"></a> 
		<a href="javascript:void(0);" id="sorted-tw" class="social-cerita twitter"></a> 
		<a href="javascript:void(0);" id="sorted-youtube" class="social-cerita youtube"></a>
		<a href="javascript:void(0);" id="sorted-all" class="social-cerita all active"></a>
		<a href="javascript:void(0);" id="kirim" class="link-btn red">SAMPAIKAN SEKARANG</a>
	</div>
</div>
<div class="clear"></div>
