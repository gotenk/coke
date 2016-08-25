<?php defined("BASEPATH") or exit('No Direct Script Access Allowed');

class Exams_image_manipulation
{
	
	private $image_data = array();
	private $save_path = '/uploads/default/user_files';
	private $sub_folder = '';
	private $save_path_not_login = false;
	function __construct($config=array())
	{
		$this->ci =& get_instance();
		$this->ci->load->library('bubble/random_string');
		$this->ci->load->config('bubble/exams_manager');
		$this->initialize($config);
	}
	
	function initialize($config)
	{
	
		//image data harus di sort 
		if(isset($config['image_data']))
		{
			$this->image_data = $config['image_data'];
		}
		
		if(isset($config['sub_folder']))
		{
			$this->sub_folder = '/'.$config['sub_folder'].'/';
		}
		else {
			$this->sub_folder = '/';
		}
		
		
		//check_folder
		$this->new_save_path =  $this->save_path.$this->sub_folder;
		if(!is_dir(getcwd().$this->new_save_path))
		{		
			mkdir(getcwd().$this->new_save_path,0775,true);
		}
	}
	public function get_current_path()
	{
		return $this->new_save_path;
	}
	
	function fetch_image_source($image_path,$ext = false)
	{
		if($ext)
		{
			$_ext_image = $ext;
		}
		else
		{
			$tmp = explode('.',$image_path);
		    $_ext_image= end($tmp);
			$img_resource;
		}
		$png_or_gif_back =false;
		switch(strtolower($_ext_image))
			{
				case 'jpg':
									$img_resource = imagecreatefromjpeg($image_path);
									break;
				case 'jpeg' :
									$img_resource = imagecreatefromjpeg($image_path);
									break;
				case 'png'			:
									$img_resource = imagecreatefrompng($image_path); 
									$png_or_gif_back = true;
									break;
				case 'gif'			:
									$img_resource = imagecreatefromgif($image_path);
									$png_or_gif_back = true;		
									break;
				case 'bmp'			:
									$img_resource = $this->imagecreatefrombmp($image_path);
									break;
				
			}
		
		return array('type'=>$_ext_image,'resource'=>$img_resource,'is_png_gif'=>$png_or_gif_back);
	}
	
	private function join_image(&$destination,$resource)
	{
		
		if(! $resource) return false;
		$destination_source = $destination['image_source'] ;
		$upper_layer = $resource['image_source'];
		
		$x = $resource['koordinat_x_over'] != 0 ? $resource['koordinat_x_over'] : $resource['koordinat_x'] ;
		$y = $resource['koordinat_y_over'] != 0 ? $resource['koordinat_y_over'] : $resource['koordinat_y'] ;
		$this->imagecopymerge_alpha($destination_source['resource'],$upper_layer['resource'],$x,$y , 0, 0, imagesx($upper_layer['resource']), imagesy($upper_layer['resource']),100);
	}
	
	function generate_image()
	{
		foreach($this->image_data as &$itm)
		{
			$item_source = $this->fetch_image_source($itm['image_path']);
			$itm['image_source'] = $item_source;
		}
		
		foreach($this->image_data as $index=>$item)
		{
			//$this->save_image($item);
			$this->join_image($this->image_data[( ($index==0)?$index:0)],( (isset($this->image_data[$index+1])? $this->image_data[$index+1]:false ) ));
		}
		
		//save image
		reset($this->image_data);
		
		return $this->save_image(current($this->image_data));
		
	}
	
	private function save_image($data_img)
	{
		$ext = $data_img['image_source']['type'];
		$resource = $data_img['image_source']['resource'];
		$image_file = $this->ci->random_string->generate();

		switch(strtolower($ext))
				{
					case 'jpg':
										imagejpeg($resource,getcwd().$this->new_save_path.$image_file.'.'.$ext,100);
										break;
					case 'jpeg' :
										imagejpeg($resource,getcwd().$this->new_save_path.$image_file.'.'.$ext,100);
										break;
					case 'png'			:
										
										imagepng($resource,getcwd().$this->new_save_path.$image_file.'.'.$ext,9,PNG_ALL_FILTERS);
										break;
					case 'gif'			:
										imagegif($resource,getcwd().$this->new_save_path.$image_file.'.'.$ext);	
										break;
					
				}
		if ($this->ci->config->item('use_pngquant'))
		{
		 	$this->compress_png(getcwd().$this->new_save_path.$image_file.'.'.$ext,100);
		}

		return $this->new_save_path.$image_file.'.'.$ext ;		
	}
	
	
	private function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
	    if(!isset($pct)){
	        return false;
	    }
	    $pct /= 100;
	    // Get image width and height
	    $w = imagesx( $src_im );
	    $h = imagesy( $src_im );
	    // Turn alpha blending off
	    imagealphablending( $src_im, false );
	    // Find the most opaque pixel in the image (the one with the smallest alpha value)
	    $minalpha = 127;
	    for( $x = 0; $x < $w; $x++ )
	    for( $y = 0; $y < $h; $y++ ){
	        $alpha = ( imagecolorat( $src_im, $x, $y ) >> 24 ) & 0xFF;
	        if( $alpha < $minalpha ){
	            $minalpha = $alpha;
	        }
	    }
	    //loop through image pixels and modify alpha for each
	    for( $x = 0; $x < $w; $x++ ){
	        for( $y = 0; $y < $h; $y++ ){
	            //get current alpha value (represents the TANSPARENCY!)
	            $colorxy = imagecolorat( $src_im, $x, $y );
	            $alpha = ( $colorxy >> 24 ) & 0xFF;
	            //calculate new alpha
	            if( $minalpha !== 127 ){
	                $alpha = 127 + 127 * $pct * ( $alpha - 127 ) / ( 127 - $minalpha );
	            } else {
	                $alpha += 127 * $pct;
	            }
	            //get the color index with new alpha
	            $alphacolorxy = imagecolorallocatealpha( $src_im, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha );
	            //set pixel with the new color + opacity
	            if( !imagesetpixel( $src_im, $x, $y, $alphacolorxy ) ){
	                return false;
	            }
	        }
	    }
	    // The image copy
	    imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
	}

	private function compress_png($path_to_png_file, $max_quality = 90)
	{ 
	    if (!file_exists($path_to_png_file)) {
	        throw new Exception("File does not exist: $path_to_png_file");
	    }
	
	    // guarantee that quality won't be worse than that.
	    $min_quality = 60;
	
	    // '-' makes it use stdout, required to save to $compressed_png_content variable
	    // '<' makes it read from the given file path
	    // escapeshellarg() makes this safe to use with any path
	    $compressed_png_content = shell_exec("pngquant --speed 10 --quality=$min_quality-$max_quality - < ".escapeshellarg(    $path_to_png_file));
	
	  	  if (!$compressed_png_content) {
	        throw new Exception("Conversion to compressed PNG failed. Is pngquant 1.8+ installed on the server?");
	    }
		  
		file_put_contents($path_to_png_file, $compressed_png_content);
	
	   
	}
	
	public function imagecreatefrombmp($p_sFile)
	{
	    $file    =    fopen($p_sFile,"rb");
	    $read    =    fread($file,10);
	    while(!feof($file)&&($read<>""))
	        $read    .=    fread($file,1024);
	    $temp    =    unpack("H*",$read);
	    $hex    =    $temp[1];
	    $header    =    substr($hex,0,108);
	    if (substr($header,0,4)=="424d")
	    {
	        $header_parts    =    str_split($header,2);
	        $width            =    hexdec($header_parts[19].$header_parts[18]);
	        $height            =    hexdec($header_parts[23].$header_parts[22]);
	        unset($header_parts);
	    }
	    $x                =    0;
	    $y                =    1;
	    $image            =    imagecreatetruecolor($width,$height);
	    $body            =    substr($hex,108);
	    $body_size        =    (strlen($body)/2);
	    $header_size    =    ($width*$height);
	    $usePadding        =    ($body_size>($header_size*3)+4);
	    for ($i=0;$i<$body_size;$i+=3)
	    {
	        if ($x>=$width)
	        {
	            if ($usePadding)
	                $i    +=    $width%4;
	            $x    =    0;
	            $y++;
	            if ($y>$height)
	                break;
	        }
	        $i_pos    =    $i*2;
	        $r        =    hexdec($body[$i_pos+4].$body[$i_pos+5]);
	        $g        =    hexdec($body[$i_pos+2].$body[$i_pos+3]);
	        $b        =    hexdec($body[$i_pos].$body[$i_pos+1]);
	        $color    =    imagecolorallocate($image,$r,$g,$b);
	        imagesetpixel($image,$x,$height-$y,$color);
	        $x++;
	    }
	    unset($body);
	    return $image;
	}
	
	public function check_allowed_image($filename,$xhr = true)
	{
		if(!$xhr) return true;
		if(isset($_FILES[$filename]) )
		{
			$allowed_image = array('gif','jpg','png','jpeg');
			$mimes =& get_mimes();
			$collection_mimes =array();
			foreach($allowed_image as $itm_allow)
			{
				if (is_array($mimes[$itm_allow]) )
				{
				 	$collection_mimes = array_merge($collection_mimes,$mimes[$itm_allow]);
				}
				else {
					$collection_mimes[] = $mimes[$itm_allow];
				}
				
			}
			if(in_array($_FILES['file']['type'],$collection_mimes))
			{
				return true;
				
			
			}
			else {
				return false;
			}
			
		}
		else
		{
			return false;
		}
	}
	public function upload_image($new_filename='',$new_filename_original='',$new_filename_thumb='',$support_xhr='1'){
		$allowed_image = array('gif','jpg','png','jpeg');
		
		if(isset($_FILES['uploadphoto']) && $support_xhr =='1' )
		{
			
			$mimes =& get_mimes();
			$collection_mimes =array();
			foreach($allowed_image as $itm_allow)
			{
				if (is_array($mimes[$itm_allow]) )
				{
				 	$collection_mimes = array_merge($collection_mimes,$mimes[$itm_allow]);
				}
				else {
					$collection_mimes[] = $mimes[$itm_allow];
				}
				
			}
			if(in_array($_FILES['uploadphoto']['type'],$collection_mimes))
			{
				$exts = '';
				foreach($allowed_image as $itm_allow)
				{
					if(is_array($mimes[$itm_allow]))
					{
						$id = array_search($_FILES['uploadphoto']['type'], $mimes[$itm_allow]);
						if($id !== false)
						{
							$exts = $itm_allow;
						}
					}
					else if ( ($mimes[$itm_allow] == $_FILES['uploadphoto']['type']) ) {
						$exts = $itm_allow;
					}
				}
	
				$ext = $exts;
				$file_location = $_FILES['uploadphoto']['tmp_name'];
				$data = $this->fetch_image_source($file_location,$ext);
				
				$image = $data['resource'];
				
			
			}
			
			
	
		}
		else if($support_xhr =='0')
		{
			
			$image_paths ='';
			
			$image_paths = getcwd().'/uploads/upload_temporary'.$this->sub_folder;
			
			if(!isset($_POST['filename']))
			{
				if(!is_dir($image_paths))
				{
					mkdir($image_paths,0775,true);
				}
				$uploader = new FileUpload('uploadphoto');
				$uploader->sizeLimit = 1024*512;   
				$result = $uploader->handleUpload($image_paths,$allowed_image); 
				if(! $result)
				{
					return array('success' => false,'msg' => $uploader->getErrorMsg());
				}
				else {
					return array('realpath'=>base_url('/uploads/upload_temporary'.$this->sub_folder.$uploader->getFileName()),'filename'=> $uploader->getFileName(),'success' => true);
				}
				
			}
			else if(isset($_POST['filename']))
			{
			
			$image = $this->fetch_image_source($image_paths.$_POST['filename']);
			$image = $image['resource'];
			$ext = $image['type'];
			
			}
			else
			{
				return array('success' => false,'msg' => 'file not exists');
			}
			
			
		}
		
		if(!isset($_POST['x']) && !isset($_POST['y']) && ! isset($_POST['w']) && !isset($_POST['h']) )
		{
			return array('success' => false,'msg' => 'not all parameter returned');
		}
		if(!$image)
		{
			return array('success' => false,'msg' => 'file not exists');
		}
		
		$width = imagesx($image);	
		$height = imagesy($image);
		$thumb_width = $this->ci->config->item('default_photo_width');
		$thumb_height = $this->ci->config->item('default_photo_height');
		$new_image_resize = imagecreatetruecolor($thumb_width, $thumb_height );
		$new_image_resize_thumb = imagecreatetruecolor( $this->ci->config->item('default_photo_thumb_width'), $this->ci->config->item('default_photo_thumb_height') );
		if($ext=='png' || $ext =='gif')
		{
			
				$transparent = imagecolorallocate($new_image_resize,255, 255 ,255);
				imagefill($new_image_resize, 0, 0, $transparent );	
				$transparent_thumb = imagecolorallocate($new_image_resize_thumb, 255, 255, 255);
				imagefill($new_image_resize_thumb, 0, 0, $transparent_thumb );	
			    
			   imagecopyresampled($new_image_resize,
				$image,
			    0,0,
			     $_POST['x'], $_POST['y'],
			    $thumb_width,$thumb_height,
			   $_POST['w'], $_POST['h']); 
			
			  	if(!is_dir( getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/'))
				{
					//var_dump($this->new_save_path.$this->ci->config->item('default_path_save_image').'/');
					mkdir(getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/',0775,true);
				}
				
				//thumb
				imagecopyresampled($new_image_resize_thumb,
				$new_image_resize,
			    0,0,
			    0,
			    0,
			    $this->ci->config->item('default_photo_thumb_width'),
			    $this->ci->config->item('default_photo_thumb_height'),
			    $thumb_width, $thumb_height);
				
				
		}
		else {
			
			imagecopyresampled($new_image_resize,
				$image,
			    0,0,
			     $_POST['x'], $_POST['y'],
			    $thumb_width,$thumb_height,
			   $_POST['w'], $_POST['h']);
				
			
			  	if(!is_dir( getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/'))
				{
					//var_dump($this->new_save_path.$this->ci->config->item('default_path_save_image').'/');
					mkdir(getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/',0775,true);
				}
				
				//thumb
			imagecopyresampled($new_image_resize_thumb,
				$new_image_resize,
			    0,0,
			    0,
			    0,
			    $this->ci->config->item('default_photo_thumb_width'),
			    $this->ci->config->item('default_photo_thumb_height'),
			    $thumb_width, $thumb_height);
			
		}
		
		
		
		
		
		 $image_file = $this->ci->random_string->generate();
		 $image_file_original = $this->ci->random_string->generate();
	     $filename = ((!empty($new_filename))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename :  $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'.jpg');
		 $filename_thumb = ((!empty($new_filename_thumb))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename_thumb :   $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_thumb'.'.jpg');
		 $filename_original = ((!empty($new_filename_original))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename_original :  $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file_original.'.jpg');
		

		
		
			  
		 imagejpeg($new_image_resize,getcwd().$filename,100);
		 imagejpeg($image,getcwd().$filename_original,100);
		 imagejpeg($new_image_resize_thumb,getcwd().$filename_thumb, 100);
		 imagedestroy($new_image_resize);
		 imagedestroy($image);	 
		 imagedestroy($new_image_resize_thumb);	 
		if(isset($POST['filename']) && $this->ci->session->userdata('current_temporary'))
		{
		  	@unlink($this->ci->session->userdata('current_temporary'));
			$this->ci->session->unset_userdata('current_temporary');
		}
		return array('filename'=>((!empty($new_filename))? $new_filename:$image_file.'.jpg'),'file_path'=>$filename,'filename_original'=>((!empty($new_filename_original))? $new_filename_original:$image_file_original.'.jpg'),'file_path_original'=>$filename_original,'file_path_thumb'=>$filename_thumb);
		
		
	}

	/*public function upload_save_image($new_filename ='',$new_filename_thumb='', $new_filename_edited='',$new_filename_edited_thumb='',$crop_pos = null)
	{
		
	
		$frame 	= $_POST['crop']['frame'];
		$img 	= $_POST['crop']['img'];
		if(isset($_POST['filename']))
		{
			$image_paths ='';
			$image_paths = getcwd().'/uploads/upload_temporary/'.$_POST['filename'];
			$image = $this->fetch_image_source($image_paths);
			$image = $image['resource'];
			$exts = $image['type'];
		}
		else {
			$base64img = explode(',', $_POST['raw']);
			
			$current_image_ext = str_replace(array('data:',';','base64'),array('','',''),$base64img[0] );
			$allowed_image = array('gif','jpg','png','jpeg','bmp');
			$mimes =& get_mimes();
			$collection_mimes =array();
			foreach($allowed_image as $itm_allow)
			{
				if (is_array($mimes[$itm_allow]) )
				{
				 	$collection_mimes = array_merge($collection_mimes,$mimes[$itm_allow]);
				}
				else {
					$collection_mimes[] = $mimes[$itm_allow];
				}
				
			}
			$exts = 'jpg';
			if(in_array($current_image_ext,$collection_mimes))
			{
					$exts = '';
					foreach($allowed_image as $itm_allow)
					{
						if(is_array($mimes[$itm_allow]))
						{
							$id = array_search($current_image_ext, $mimes[$itm_allow]);
							if($id !== false)
							{
								$exts = $itm_allow;
							}
						}
						else if ( ($mimes[$itm_allow] == $current_image_ext) ) {
							$exts = $itm_allow;
						}
				}
			}
			
			$data = base64_decode($base64img[1]);	
			$image = imagecreatefromstring($data);
		}

		
		
	
		$thumb_width = $this->ci->config->item('default_photo_width');
		$thumb_height = $this->ci->config->item('default_photo_height');
	
		$width = imagesx($image);
		$height = imagesy($image);
		$new_image_resize = imagecreatetruecolor( $thumb_width, $thumb_height );
		$new_image_resize_thumb = imagecreatetruecolor( $this->ci->config->item('default_photo_thumb_width'), $this->ci->config->item('default_photo_thumb_height') );
		
		if($exts=='png' || $exts =='gif')
		{
			
				$transparent = imagecolorallocate($new_image_resize,255, 255 ,255);
				imagefill($new_image_resize, 0, 0, $transparent );	
				
				$transparent_thumb = imagecolorallocate($new_image_resize_thumb, 255, 255, 255);
				imagefill($new_image_resize_thumb, 0, 0, $transparent_thumb );	
			    
			    imagecopyresampled($new_image_resize,
				$image,
			    0,0,
			    (-1*intval($img['x']))/intval($img['w'])*$width,
			    (-1*intval($img['y']))/intval($img['h'])*$height,
			    intval($img['w'])/intval($frame['w'])*$thumb_width, intval($img['h'])/intval($frame['h'])*$thumb_height,
			    $width, $height);
			
			  	if(!is_dir( getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/'))
				{
					//var_dump($this->new_save_path.$this->ci->config->item('default_path_save_image').'/');
					mkdir(getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/',0775,true);
				}
			//thumb
			imagecopyresampled($new_image_resize_thumb,
				$new_image_resize,
			    0,0,
			    0,
			    0,
			    $this->ci->config->item('default_photo_thumb_width'),
			    $this->ci->config->item('default_photo_thumb_height'),
			    $thumb_width, $thumb_height);
				
				
		}
		else {
			
		
			imagecopyresampled($new_image_resize,
				$image,
			    0,0,
			    (-1*intval($img['x']))/intval($img['w'])*$width,
			    (-1*intval($img['y']))/intval($img['h'])*$height,
			    intval($img['w'])/intval($frame['w'])*$thumb_width, intval($img['h'])/intval($frame['h'])*$thumb_height,
			    $width, $height);
			
			  	if(!is_dir( getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/'))
				{
					//var_dump($this->new_save_path.$this->ci->config->item('default_path_save_image').'/');
					mkdir(getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/',0775,true);
				}
			//thumb
			imagecopyresampled($new_image_resize_thumb,
				$new_image_resize,
			    0,0,
			    0,
			    0,
			    $this->ci->config->item('default_photo_thumb_width'),
			    $this->ci->config->item('default_photo_thumb_height'),
			    $thumb_width, $thumb_height);
			
		}
			
			
			
			if($crop_pos!=null)
		{
				//crop image
			$new_image_thumb_crop =  imagecreatetruecolor( $crop_pos['w_thumb'], $crop_pos['h_thumb'] );
			
				imagecopyresampled($new_image_thumb_crop,
				$new_image_resize_thumb,
			    0,0,
			    $crop_pos['x_thumb'],
			    $crop_pos['y_thumb'],
			    $crop_pos['w_thumb'],
			    $crop_pos['h_thumb'],
			    $crop_pos['w_thumb'], $crop_pos['h_thumb']);
				
			$new_image_crop = imagecreatetruecolor( $crop_pos['w'], $crop_pos['h'] );
			
				imagecopyresampled($new_image_crop,
				$new_image_resize,
			    0,0,
			    $crop_pos['x'],
			    $crop_pos['y'],
			     $crop_pos['w'],
			     $crop_pos['h'],
			     $crop_pos['w'], $crop_pos['h']);
				 
			  $image_file = $this->ci->random_string->generate();
			  //$filename_edited = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_edited..jpg';
			  //$filename_thumb_edited = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_thumb_edited.'.'.jpg';
		        $filename_edited = ((!empty($new_filename_edited))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename_edited :  $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_edited.jpg');
			   $filename_thumb_edited = ((!empty($new_filename_edited_thumb))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename_edited_thumb :   $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_edited_thumb'.'.jpg');
		      imagejpeg($new_image_crop,getcwd().$filename_edited, 100);
			  imagejpeg($new_image_thumb_crop,getcwd().$filename_thumb_edited, 100);
			  
			  imagedestroy($new_image_thumb_crop);
			  imagedestroy($new_image_crop);
			  
			 
		}
		else {
			$image_crop_resize_edit = imagecreatetruecolor($thumb_width, $thumb_height );
			$new_image_resize_thumb_edit = imagecreatetruecolor( $this->ci->config->item('default_photo_thumb_width'), $this->ci->config->item('default_photo_thumb_height') );
			imagecopyresampled($image_crop_resize_edit,
			$new_image_resize ,
		    0,0,
		    0,0,
		    imagesx($image_crop_resize_edit),
		    imagesy($image_crop_resize_edit),
		    imagesx($image_crop_resize),
		    imagesy($image_crop_resize));
		    
			imagecopyresampled($new_image_resize_thumb_edit,
				$image_crop_resize,
			    0,0,
			    0,
			    0,
			    $this->ci->config->item('default_photo_thumb_width'),
			    $this->ci->config->item('default_photo_thumb_height'),
			    $thumb_width, $thumb_height);
				
			  $image_file = $this->ci->random_string->generate();
			 // $filename_edited = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_edited..jpg';
			  //$filename_thumb_edited = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_thumb_edited.'.'.jpg';
		     
		       $filename_edited = ((!empty($new_filename_edited))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename_edited :  $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_edited.jpg');
			   $filename_thumb_edited = ((!empty($new_filename_edited_thumb))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename_edited_thumb :   $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_edited_thumb'.'.jpg');
		     
		      imagejpeg($image_crop_resize_edit,getcwd().$filename_edited, 100);
			  imagejpeg($new_image_resize_thumb_edit,getcwd().$filename_thumb_edited, 100);
			  imagedestroy($image_crop_resize);
			  imagedestroy($new_image_resize_thumb);
				
			
		}
			
			
			
			
			
		  	  $image_file = $this->ci->random_string->generate();
			  $filename = ((!empty($new_filename))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename :  $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'.jpg');
			  $filename_thumb = ((!empty($new_filename_thumb))? $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$new_filename_thumb :   $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_thumb'.'.jpg');
		      
			  
		      imagejpeg($new_image_resize,getcwd().$filename,100);
			  imagejpeg($new_image_resize_thumb,getcwd().$filename_thumb, 100);
			  imagedestroy($new_image_resize);
			  imagedestroy($new_image_resize_thumb);
			  if(isset($POST['filename']) && $this->ci->session->userdata('current_temporary'))
			  {
			  	@unlink($this->ci->session->userdata('current_temporary'));
				$this->ci->session->unset_userdata('current_temporary');
			  }
			  return array('filename'=>((!empty($new_filename))? $new_filename:$image_file.'.jpg'),'file_path'=>$filename,'file_path_thumb'=>$filename_thumb,'file_path_edited'=>$filename_edited,'file_path_edited_thumb'=>$filename_thumb_edited);
	}*/
	
	//crop resize
	/*public function crop_resize($ext,$file_location,$crop_pos=null)
	{
		
		switch(strtolower($ext))
		{
			case 'jpg':
								$img_resource = imagecreatefromjpeg($file_location);
								break;
			case 'jpeg' :
								$img_resource = imagecreatefromjpeg($file_location);
								break;
			case 'png'			:
								$img_resource = imagecreatefrompng($file_location); 
								$png_or_gif_back = true;
								break;
			case 'gif'			:
								$img_resource = imagecreatefromgif($file_location);
								$png_or_gif_back = true;		
								break;
			case 'bmp'			:
								$img_resource = $this->imagecreatefrombmp($file_location);
								break;
			
		}
		
		$width = imagesx($img_resource);
		$height = imagesy($img_resource);
		
		$thumb_width = $this->ci->config->item('default_photo_width');
		$thumb_height = $this->ci->config->item('default_photo_height');
		
		$image_crop_resize = imagecreatetruecolor($thumb_width, $thumb_height );
		
		$square_length = ($width >= $height) ? $height : $width;
		
		if(($width >= $height))
		{
			$padding_x = ( ($width >= $height) ?  ( ( ($width-$height)>0) ? ($width-$height)/2  :0 ) : 0 );
			//$padding_y = ( ($height >= $thumb_height) ?  ( ( ($height-$thumb_height)>0) ? ($height-$thumb_height)/2  :0 ) : 0 );
			$padding_y = 0;
			
		}
		else if(($height >= $width))
		{
			//$padding_x = ( ($width >= $thumb_width) ?  ( ( ($width-$thumb_width)>0) ? ($width-$thumb_width)/2  :0 ) : 0 );
			$padding_x = 0;
			$padding_y = ( ($height >= $width) ? ( ( ($height-$width)>0) ?($height-$width)/2    :0 ) :0  );
		}
		//var_dump($width);
		//var_dump($height);
		//var_dump($padding_x);
		//var_dump($padding_y);
		//imagecopyresampled($image_crop_resize,
		//$img_resource,
	  //  0,0,
	   // (intval($padding_x))/intval($square_length)*$thumb_width,
	    //(intval($padding_y))/intval($square_length)*$thumb_height,
	  //  $padding_x,
	   // $padding_y,
	    // $width+$padding_x, $square_length+$padding_y,
	     //(($square_length>$thumb_width)? $square_length :$thumb_width ),
	     //(($square_length>$thumb_width)? $square_length :$thumb_height ),
	   // intval($square_length)/intval($width)*$thumb_width +(intval($padding_x))/intval($square_length)*$thumb_width , intval($square_length)/intval($height)*$thumb_height+( (intval($padding_y))/intval($square_length)*$thumb_height),
	    $width, $height);
	    
	    $image_crop_resize1 = imagecreatetruecolor($square_length, $square_length );
	    imagecopyresampled($image_crop_resize1,
		$img_resource,
	    0,0,
	    $padding_x,
	    $padding_y,
	    $square_length+$padding_x,  $square_length+$padding_y,
	    $square_length+$padding_x, $square_length+$padding_y);
		
		imagecopyresampled($image_crop_resize,
		$image_crop_resize1,
	    0,0,
	    0,0,
	    imagesx($image_crop_resize),
	    imagesy($image_crop_resize),
	    imagesx($image_crop_resize1),
	    imagesy($image_crop_resize1));
		
		$new_image_resize_thumb = imagecreatetruecolor( $this->ci->config->item('default_photo_thumb_width'), $this->ci->config->item('default_photo_thumb_height') );
	
		imagecopyresampled($new_image_resize_thumb,
			$image_crop_resize,
		    0,0,
		    0,
		    0,
		    $this->ci->config->item('default_photo_thumb_width'),
		    $this->ci->config->item('default_photo_thumb_height'),
		    $thumb_width, $thumb_height);
	
	  	if(!is_dir( getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/'))
		{
			//var_dump($this->new_save_path.$this->ci->config->item('default_path_save_image').'/');
			mkdir(getcwd().$this->new_save_path.$this->ci->config->item('default_path_save_image').'/',0775,true);
		}
		
		
		
		if($crop_pos!=null)
		{
				//crop image
			$new_image_thumb_crop =  imagecreatetruecolor( $crop_pos['w_thumb'], $crop_pos['h_thumb'] );
			
				imagecopyresampled($new_image_thumb_crop,
				$new_image_resize_thumb,
			    0,0,
			    $crop_pos['x_thumb'],
			    $crop_pos['y_thumb'],
			    $crop_pos['w_thumb'],
			    $crop_pos['h_thumb'],
			    $crop_pos['w_thumb'], $crop_pos['h_thumb']);
				
			$new_image_crop = imagecreatetruecolor( $crop_pos['w'], $crop_pos['h'] );
			
				imagecopyresampled($new_image_crop,
				$image_crop_resize,
			    0,0,
			    $crop_pos['x'],
			    $crop_pos['y'],
			     $crop_pos['w'],
			     $crop_pos['h'],
			     $crop_pos['w'], $crop_pos['h']);
				 
			  $image_file = $this->ci->random_string->generate();
			  $filename_edited = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_edited..jpg';
			  $filename_thumb_edited = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_thumb_edited.'.'.jpg';
		      imagejpeg($new_image_crop,getcwd().$filename_edited, 100);
			  imagejpeg($new_image_thumb_crop,getcwd().$filename_thumb_edited, 100);
			  
			  imagedestroy($new_image_thumb_crop);
			  imagedestroy($new_image_crop);
			  
			 
		}
		else {
			$image_crop_resize_edit = imagecreatetruecolor($thumb_width, $thumb_height );
			$new_image_resize_thumb_edit = imagecreatetruecolor( $this->ci->config->item('default_photo_thumb_width'), $this->ci->config->item('default_photo_thumb_height') );
			imagecopyresampled($image_crop_resize_edit,
			$image_crop_resize ,
		    0,0,
		    0,0,
		    imagesx($image_crop_resize_edit),
		    imagesy($image_crop_resize_edit),
		    imagesx($image_crop_resize),
		    imagesy($image_crop_resize));
		    
			imagecopyresampled($new_image_resize_thumb_edit,
				$image_crop_resize,
			    0,0,
			    0,
			    0,
			    $this->ci->config->item('default_photo_thumb_width'),
			    $this->ci->config->item('default_photo_thumb_height'),
			    $thumb_width, $thumb_height);
				
			  $image_file = $this->ci->random_string->generate();
			  $filename_edited = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_edited..jpg';
			  $filename_thumb_edited = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_thumb_edited.'.'.jpg';
		      imagejpeg($image_crop_resize_edit,getcwd().$filename_edited, 100);
			  imagejpeg($new_image_resize_thumb_edit,getcwd().$filename_thumb_edited, 100);
			  imagedestroy($image_crop_resize);
			  imagedestroy($new_image_resize_thumb);
				
			
		}
		
			  $image_file = $this->ci->random_string->generate();
			  $filename = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'.jpg';
			  $filename_thumb = $this->new_save_path.$this->ci->config->item('default_path_save_image').'/'.$image_file.'_thumb'.'.jpg';
		      imagejpeg($image_crop_resize,getcwd().$filename, 100);
			  imagejpeg($new_image_resize_thumb,getcwd().$filename_thumb, 100);
			  imagedestroy($image_crop_resize);
			  imagedestroy($new_image_resize_thumb);
			
		
		
		
		
		 
		  return array('filename'=>$image_file.'.jpg','file_path'=>$filename,'file_path_thumb'=>$filename_thumb,'file_path_edited'=>$filename_edited,'file_path_thumb_edited'=>$filename_thumb_edited);
		
		header("Content-type: image/".$ext);
		switch(strtolower($ext))
		{
			case 'jpg':
								imagejpeg($image_crop_resize,null,100);
								break;
			case 'jpeg' :
								imagejpeg($image_crop_resize,null,100);
								break;
			case 'png'			:
								imagepng($image_crop_resize);
								break;
			case 'gif'			:
								imagegif($image_crop_resize);	
								break;
			
		}
		
		
		
	}	 */
	
}
