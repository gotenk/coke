<?php defined('BASEPATH') OR exit('No Direct Script Access Allowed');

class Generate_image
{
	
	 protected $image_library =array('font_path'=>'',
	 								'image_path'=>'',
									'font_size' =>36,
									'minimum_margin_left_right'	=>100,
									'offset_y' => 100,
									'box_height' =>300,
									'box_width'=>700,
									'angle' => 0,
									'text_color'=>array(255,255,255),
									'opacity'=>1.0,
									'quality'=>70
									);
	protected $text  ='';
	
	 function __construct($_config = array())
	 {
	 	$this->initialize($_config);
	 }
	 
	 function fetch_image_source($image_path,$ext = false)
	 {
		if($ext)
		{
			$_ext_image = strtolower($ext);
		}
		else
		{
			$tmp = explode('.',$image_path);
		    $_ext_image= strtolower(end($tmp));
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
	 
	 function initialize($_config = array())
	 {
	 	if (count($_config))
		{
	 		$this->image_library =  array_replace_recursive($this->image_library,$_config);
		}
		foreach($this->image_library as $_index => $_value)
		{
			$this->image_library[] = $_value;
		}
	 }
	 
	 public function setTextColor($colour=array(255, 255, 255)){
		$this->image_library['text_color'] = $colour;		
		
	}
	
	 function set_text($char ='')
	{
		
		$this->text = $char ;
	}
	 function generate($file_name = '',$set_as_output = false)
	{
		if(!is_file($this->image_library['image_path']))
		{
			echo 'File image not exists';
			return;
		}
		
		if(!is_file($this->image_library['font_path']))
		{
			echo 'File font not exists';
			return;
		}
		
		if(empty($this->text))
		{
			echo 'text cannot empty';
			return;
		}
		
		
		$data_image = $this->fetch_image_source($this->image_library['image_path']);
		
		$res = $data_image['resource'];
		$width = imagesx($res);
		$height = imagesy($res);
		$angle = $this->image_library['angle'];
		$fontSize = $this->image_library['font_size'];
		$boxHeight = $this->image_library['box_height'];
		$boxWidth = $this->image_library['box_width'];
		$fontColor = $this->image_library['text_color'];
		$fontFile = $this->image_library['font_path'];
		$opacity = $this->image_library['opacity'];
		$text = $this->text;
		if($boxHeight > $height || $boxWidth > $width)
		{
			echo 'image master smaller than textbox';
			return;
		}
		$x = ($width - $boxWidth)/2	;
		$y = $this->image_library['offset_y'];
		// Get Y offset as it 0 Y is the lower-left corner of the character
		$testbox = imagettfbbox($fontSize, $angle, $fontFile, $text);
		$offsety = abs($testbox[7]);
		$offsetx = 0;
		$actualWidth = abs($testbox[6] - $testbox[4]);
		$actualHeight = abs($testbox[1] - $testbox[7]);
		
		while ($actualWidth > $boxWidth || $actualHeight > $boxHeight  )
		{
			$testbox = imagettfbbox($fontSize--, $angle, $fontFile, $text);
			$offsety = abs($testbox[7]);
			$offsetx = 0;
			$actualWidth = abs($testbox[6] - $testbox[4]);
			$actualHeight = abs($testbox[1] - $testbox[7]);
		}
		
		//set center position
		$offsetx += (($boxWidth - $actualWidth) / 2);
		$offsety += (($boxHeight - $actualHeight) / 2);
				// Draw text
		imagettftext($res, $fontSize, $angle, $x + $offsetx, $y + $offsety, imagecolorallocatealpha($res, $fontColor[0], $fontColor[1], $fontColor[2], (1 - $opacity) * 127), $fontFile, $text);
		
		if($set_as_output)
		{
			header('Expires: Wed, 1 Jan 1997 00:00:00 GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: post-check=0, pre-check=0', false);
			header('Pragma: no-cache');
			header('Content-type: image/png');
			switch($data_image['type']){
				case IMAGETYPE_GIF:
					imagegif($res, null);
					break;
				case IMAGETYPE_PNG:
					imagepng($res, null,$this->image_library['quality']);
					break;
				default:
					imagejpeg($res, null, $this->image_library['quality']);
					break;
				}
		}
		else {
			switch($data_image['type']){
				case IMAGETYPE_GIF:
					imagegif($res, $file_name);
					break;
				case IMAGETYPE_PNG:
					imagepng($res,  $file_name, $this->image_library['quality']);
					break;
				default:
					imagejpeg($res, $file_name,  $this->image_library['quality']);
					break;
				}
				
			return true;
		}
		
	}
}
