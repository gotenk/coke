<?php if($status=='success'): ?>
<div class="container">	
	<div class="find-name">
		<div class="message">Yay!</div>
		<div class="page-description">
			<b>Nama kamu tersedia di botol COCA-COLA!<br>
			Ayo, langsung share ke teman-teman di media sosial.</b><br>
			Buruan dapatkan Coca-Cola dengan nama kamu di toko, selfie dengan botol spesialmu
			<br>dan share dengan hashtag #ShareACokeID Jangan lupa sertakan lokasinya ya!
			<br>Nanti Coca-Cola selfie kamu bakal <a href="{{url:site uri="sample"}}">seperti ini!</a>

		</div>
		<div class="social">
			<!-- postFeed('{{ share_facebook }}') -->
			<a href="javascript:void(0)" onclick="postFeedURL('{{ fb_url }}');return false;" class="share-btn facebook">share facebook</a>
			<a href="{{ share_twitter }}" class="share-btn share-twitter twitter">share twitter</a>
		</div>	
		<div class="link">
			<a href="{{ url:site }}">cari nama lainnya di sini <span class="arrow-icon red"></span></a>
		</div>
	</div>
</div>
<div class="coke-bottle-name">
	<ul>
		<li>
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[0] ?></span>
		</li>
		<li>
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[1] ?></span>
		</li>
		<li class="tablet">
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[2] ?></span>
		</li>
		<li class="mobile">
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[3] ?></span>
		</li>
		<li class="mobile center">
			{{ theme:image file="coke-bottle-center.png" }}
			<span class="name"><?php echo $data_cocacola[4] ?></span>
		</li>
		<li class="mobile">
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[5] ?></span>
		</li>
		<li class="tablet">
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[6] ?></span>
		</li>
		<li>
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[7] ?></span>
		</li>
		<li>
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[8] ?></span>
		</li>
	</ul>
	<div class="clear"></div>
</div>
<?php else : ?>
<div class="container">	
	<div class="find-name">
		<div class="message">Maaf!</div>
		<div class="page-description">
			<br>
			Nama kamu untuk sementara belum tersedia. Kami telah menyimpan namamu dan kamu akan segera mengetahuinya saat sudah tersedia.<br><br>

		</div>
		<div class="social">
			<a href="https://instagram.com/cocacola_id/" target="_blank" class="share-btn instagram">follow instagram</a>
			<a href="https://www.facebook.com/cocacolaindonesia" target="_blank" class="share-btn facebook">follow facebook</a>
			<a href="https://twitter.com/cocacola_id" target="_blank" class="share-btn twitter">follow twitter</a>
		</div>	
		<div class="link">
			<a href="{{ url:site }}">cari nama lainnya di sini <span class="arrow-icon red"></span></a>
		</div>
	</div>
</div>
<div class="coke-bottle-name">
	<ul>
		<li>
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[0] ?></span>
		</li>
		<li>
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[1] ?></span>
		</li>
		<li class="tablet">
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[2] ?></span>
		</li>
		<li class="mobile">
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[3] ?></span>
		</li>
		<li class="mobile center">
			{{ theme:image file="coke-bottle-center.png" }}
			<span class="name"><?php echo $data_cocacola[4] ?></span>
		</li>
		<li class="mobile">
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[5] ?></span>
		</li>
		<li class="tablet">
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[6] ?></span>
		</li>
		<li>
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[7] ?></span>
		</li>
		<li>
			{{ theme:image file="coke-bottle.png" }}
			<span class="name"><?php echo $data_cocacola[8] ?></span>
		</li>
	</ul>
	<div class="clear"></div>
</div>	
<?php endif;