<section class="title">
	<?php if ($this->method == 'create'): ?>
	<h4><?php echo lang('article_manager:create_title') ?></h4>
<?php else: ?>
	<h4><?php  echo sprintf(lang('article_manager:edit_title'), $article->title) ?></h4>
<?php endif ?>
</section>

<section class="item">
	
	<div class="content">
	<?php echo form_open_multipart(uri_string().(( $this->input->get('ref') ? '?ref='.$this->input->get('ref') : '')),'class="crud'.((isset($mode)) ? ' '.$mode : '').'" id="youtube_form"'); ?>
		
		<div class="form_inputs">
	
				<ul>
					
					<li>
					<label for="title"><?php echo lang('article_manager:title') ?> <span>*</span></label>
					<div class="input"><?php echo form_input('title', htmlspecialchars_decode($article->title), 'maxlength="255" class="text width-20" style="min-width:350px !important;" id="title"') ?></div>
					</li>					
					
					<li>
						<label for="status"><?php echo lang('article_manager:image_upload_label') ?></label>
						 <div class="input picture-sel">
                        <input type="hidden" name="id_cerita" value="<?php echo isset($data_create->id) ? $data_create->id:''; ?>" readonly="readonly" />
                        <?php //if($this->method=='create'):?>
                            <input type="file" name="userfile" class="img-input" style="margin-bottom:30px;"/> <br ><br >
                        <?php //endif; ?>
                        <?php 
                        $filename = isset($article->picture) ? $article->picture : '';
                        $img_file = '';
                        $style_prev = 'display:none;';
                        if($filename!=''){
                            $img_file = base_url($filename);    
                            $style_prev = '';                   
                        }
                        ?>
						<?php if($img_file) : ?>
	                        <img class="preview-img" src="<?php echo $img_file;?>" style="<?php echo $style_prev; ?>" width="400px" height="auto" /><br > 
						<?php endif; ?>
                    </div>
					</li>
					
					<li>
						<label for="status"><?php echo lang('article_manager:description') ?></label>
						<div class="input"><?php echo form_textarea(array('id' => 'description', 'name' => 'content', 'value' => html_entity_decode($article->content), 'rows' => 10, 'class' =>'wysiwyg-simple-custom')) ?></div>
					</li>
					<li>
						<label for="status"><?php echo lang('article_manager:date_label') ?></label>
						<div class="input datetime_input">
						 <div style="width:35%;float:left"> <?php echo form_input('created_on', date('Y-m-d', $article->created_on), 'maxlength="10" id="datepicker" class="text width-20" style="width:100% !important;"') ?></div> &nbsp; &nbsp;
						<?php echo form_dropdown('created_on_hour', $hours, date('H', $article->created_on)) ?> <span style="vertical-align:top;line-height:3">:</span>
						<?php echo form_dropdown('created_on_minute', $minutes, date('i', ltrim($article->created_on, '0'))) ?>
						</div>
					</li>
					<li>
						<label for="status"><?php echo lang('article_manager:status') ?></label>
						<div class="input"><?php echo form_dropdown('status', array('1' => lang('article_manager:active'), '0' => lang('article_manager:inactive')), $article->status) ?></div>
					</li>
					<?php echo  form_hidden('id', $article->id) ?>					
				</ul>
				
		</div>
	
		<div class="buttons align-right padding-top">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'=>array('url'=>(($data_ref = $this->input->get('ref'))? site_url(rawurldecode($data_ref)) :site_url(ADMIN_URL.'/youtube_manager'.(isset($slug_back)? '#'.$slug_back: ''))))))) ?>
		</div>
	
		<?php echo form_close() ?>
		<script type="text/javascript">
$(document).ready(function(){

    $('.picture-sel').on('change', '.img-input', function(){
        var idx = $( ".img-input" ).index( $(this) );
        var img_preview = $(this).val();
       
        readURL(this, idx);
    });
	
	$('.img-input').trigger('change');

    function readURL(input, idx) {       
        //var $prev = $('#blah'); // cached for efficiency
        var $prev = $('<img>',{'class':'preview-img','width':'400px','height':'auto'});
 	
		
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            
            reader.onload = function (e) {
				
                //$prev.attr('src', e.target.result);
				if($('.preview-img').length)
				{
					$('.preview-img').attr('src', e.target.result);
				}
				else {
					$prev.attr('src', e.target.result);
					$( ".img-input" ).parent().append($($prev));
				}
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