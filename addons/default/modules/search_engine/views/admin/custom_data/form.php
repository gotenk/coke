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
			<ul>			
                <li>
                    <label for="title">Picture <span>*</span></label>
                    <div class="input picture-sel">
                        <input type="hidden" name="id_cerita" value="<?php echo isset($data_create->id) ? $data_create->id:''; ?>" readonly="readonly" />
                        <?php if($aksi=='create'):?>
                            <input type="file" name="userfile" class="img-input" /><br >
                        <?php endif; ?>
                        <?php 
                        $filename = isset($data_create->video_preview) ? $data_create->video_preview : '';
                        $img_file = '';
                        $style_prev = 'display:none;';
                        if($filename!=''){
                            $img_file = base_url($filename);    
                            $style_prev = '';                   
                        }
                        ?>
                        <img class="preview-img" src="<?php echo $img_file; ?>" style="<?php echo $style_prev; ?>" height="200px" /><br >
                        <img class="cek-img" src="" style="display:none;" />
                    </div>
                </li>
                <li>
                    <label for="status">Description</label>
                    <div class="input">
                        <textarea style="width:590px; height:104px;" name="desc"><?php echo set_value('desc', isset($data_create->description) ? htmlspecialchars(trim($data_create->description)) : ''); ?></textarea>                        
                    </div>
                </li>
                <li>
                    <label for="status"><?php echo lang('video:status_label') ?></label>
                    <div class="input"><?php echo form_dropdown('status', array('live' => lang('video:live_label'), 'draft' => lang('video:draft_label')), $data_create->status) ?></div>
                </li>
                <li>
                    <label for="status"><?php echo lang('video:favorite_label') ?></label>
                    <div class="input"><?php echo form_dropdown('favorite', array('ya' => 'Ya', 'tidak' => 'Tidak'), isset($data_create->favorite) ? $data_create->favorite:'tidak') ?></div>
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
