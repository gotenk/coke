<section class="title">
<?php if ($aksi== 'create'): ?>
	<h4>
        <?php echo lang('video:create_title') ?>
    </h4>
<?php else: ?>
	<h4><?php echo sprintf(lang('video:edit_title'), $data_create->id) ?></h4>
<?php endif ?>
</section>

<section class="item">
<div class="content">

<?php
    //var_dump($data_create);
    //var_dump($data_create->content);
    //$gallery_section = $this->uri->segment(3);
    echo form_open_multipart() ?>
<div class="tabs">
	<!-- Content tab -->
	<div class="form_inputs" id="dago_gallery-content-tab">
		<fieldset>
        	<?PHP
			echo str_pad("Hello World", 10, "-");
			if (strpos($data_fb['description'],"BMW") == false) {
			?>
        	<p>Warning : tidak terdapat hashtag #<?PHP echo $hastag; ?> pada description video ini.</p>
            <?PHP } ?>
			<ul>			
                <li>
                    <label for="video_url">Video ID <span>*</span></label>
                    <div class="input"><?php echo form_input('video_url', set_value('video_url', $data_fb['id']), 'class="width-15"'); ?></div>
                </li>
                <li>
                    <label for="name">User Name <span>*</span></label>
                    <div class="input"><?php echo form_input('name', set_value('name', $data_fb['from']['name']), 'class="width-15"'); ?></div>
                </li>
                <li>
                    <label for="userid">User ID <span>*</span></label>
                    <div class="input"><?php echo form_input('userid', set_value('userid', $data_fb['from']['id']), 'class="width-15"'); ?></div>
                </li>
                <li>
                    <label for="title">Photo Profile <span>*</span></label>
                    <div class="input picture-sel">
                        <input type="hidden" name="photo_profile" value="<?php echo 'https://graph.facebook.com/'.$data_fb['from']['id'].'/picture?type=square'; ?>" readonly="readonly" />
                        <img class="preview-img" src="<?php echo 'https://graph.facebook.com/'.$data_fb['from']['id'].'/picture?type=square'; ?>" /><br >
                        <img class="cek-img" src="" style="display:none;" />
                    </div>
                </li>
                <li>
                    <label for="title">Video Preview <span>*</span></label>
                    <div class="input picture-sel">
                        <input type="hidden" name="video_preview" value="<?php echo $data_fb['picture']; ?>" readonly="readonly" />
                        <input type="hidden" name="video" value="<?php echo $data_fb['source']; ?>" readonly="readonly" />
                        <img class="preview-img" src="<?php echo $data_fb['picture']; ?>" height="200px" /><br >
                        <img class="cek-img" src="" style="display:none;" />
                    </div>
                </li>
                <li>
                    <label for="status">Description <span>*</span></label>
                    <div class="input">
                        <textarea style="width:590px; height:104px;" name="desc"><?php echo set_value('desc', $data_fb['description']); ?></textarea>                        
                    </div>
                </li>
                <li>
                    <label for="userid">Created Time <span>*</span></label>
                    <?PHP
					list($tanggal, $waktu) = explode("T", $data_fb['created_time']);
					list($wkt, $plus) = explode("+", $waktu);
					list($thn, $bln, $tgl) = explode("-", $tanggal);
					list($jam, $menit, $detik) = explode(":", $wkt);
					?>
                    <input type="hidden" name="created_on" value="<?php echo strtotime(date('Y-m-d H:i:s', mktime($jam, $menit, $detik, $bln, $tgl, $thn))); ?>" readonly="readonly" />
                    <div class="input"><?php echo form_input('created_on_tampil', set_value('created_on_tampil', date('d M Y', strtotime($tanggal)).' '.$wkt), 'class="width-15"'); ?></div>
                </li>
                <li>
                    <label for="status"><?php echo lang('video:status_label') ?></label>
                    <div class="input"><?php echo form_dropdown('status', array('live' => lang('video:live_label'), 'draft' => lang('video:draft_label')), "draft") ?></div>
                </li>
			</ul>
		</fieldset>
	</div>
	
</div>

<div class="buttons">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>
</div>

<?php echo form_close() ?>
<script type="text/javascript">
$(document).ready(function(){
	/*
	$('#name-photo').keyup(function(){
		var $this = $(this);
		$('#slug-photo').val($this.val().replace(/ /g, '-'));
	});
	*/

    $('.add-more').click(function(){
        var isi = '<input type="file" name="userfile[]" class="img-input" /><br ><img class="preview-img" src="" width="100px"><br /><img class="cek-img" src="" style="display:none;">';
        $('.picture-sel').append(isi)
    });

    $('.picture-sel').on('change', '.img-input', function(){
        var idx = $( ".img-input" ).index( $(this) );
        var img_preview = $(this).val();
       
        readURL(this, idx);
    });

    function readURL(input, idx) {       
        //var $prev = $('#blah'); // cached for efficiency
        var $prev = $('.preview-img').eq(idx);
        var $cek = $('.cek-img').eq(idx);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            var img = '';
            reader.onload = function (e) {
                //$prev.attr('src', e.target.result);
                $cek.attr('src', e.target.result);
                
                $cek.unbind("load");
                $cek.bind("load", function () {
                    // Get image sizes
                    //console.log(this.width, this.height);
                    var w_img = this.width;
                    var h_img = this.height;
                    
                    /*
                    //console.log(w_img, h_img)
                    if(w_img != 946 && h_img != 350 ){
                        $('.img-input').eq(idx).val('');
                        alert('Image tidak valid !! \n\n Format Valid :\n - Width = 946\n - Height = 350');
                    }else{
                        $prev.attr('src', e.target.result);
                        $prev.show();
                    }
                    */
                    $prev.attr('src', e.target.result);
                    $prev.show();
                });
            }

            reader.readAsDataURL(input.files[0]);
            
            //var width = img.clientWidth;
            //console.log(width)
            //$prev.show(); // this will show only when the input has a file
        } else {
            $prev.hide(); // this hides it when the input is cleared
        }
    }

	$('.picture-sel-thumb').on('change', '.img-input-thumb', function(){
        var idx = $( ".img-input-thumb" ).index( $(this) );
        var img_preview = $(this).val();
       
        readURL_thumb(this, idx);
    });

	function readURL_thumb(input, idx) {       
        //var $prev = $('#blah'); // cached for efficiency
        var $prev = $('.preview-img-thumb').eq(idx);
        var $cek = $('.cek-img-thumb').eq(idx);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            var img = '';
            reader.onload = function (e) {
                //$prev.attr('src', e.target.result);
                $cek.attr('src', e.target.result);
                
                $cek.unbind("load");
                $cek.bind("load", function () {
                    // Get image sizes
                    //console.log(this.width, this.height);
                    var w_img = this.width;
                    var h_img = this.height;
                    
                    /*
                    //console.log(w_img, h_img)
                    if(w_img != 946 && h_img != 350 ){
                        $('.img-input').eq(idx).val('');
                        alert('Image tidak valid !! \n\n Format Valid :\n - Width = 946\n - Height = 350');
                    }else{
                        $prev.attr('src', e.target.result);
                        $prev.show();
                    }
                    */
                    $prev.attr('src', e.target.result);
                    $prev.show();
                });
            }

            reader.readAsDataURL(input.files[0]);
            
            //var width = img.clientWidth;
            //console.log(width)
            //$prev.show(); // this will show only when the input has a file
        } else {
            $prev.hide(); // this hides it when the input is cleared
        }
    }


}); 

</script>

</div>
</section>
