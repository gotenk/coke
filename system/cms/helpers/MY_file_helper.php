<?php defined('BASEPATH') OR exit('No direct script access allowed.');

function curl_download_image($image_url, $image_file){
    $fp = fopen ($image_file, 'w+');              // open file handle

    $ch = curl_init($image_url);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // enable if you want
    curl_setopt($ch, CURLOPT_FILE, $fp);          // output to file
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);      // some large value to allow curl to run for a long time
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    // curl_setopt($ch, CURLOPT_VERBOSE, true);   // Enable this line to see debug prints
    curl_exec($ch);

    curl_close($ch);                              // closing curl handle
    fclose($fp);                                  // closing file handle
}

function curl_fb_download_image($picture_url,$save_path)
{
     class sfFacebookPhoto {
    
            private $useragent = 'Loximi sfFacebookPhoto PHP5 (cURL)';
            private $curl = null;
            private $response_meta_info = array();
            private $header = array(
                    "Accept-Encoding: gzip,deflate",
                    "Accept-Charset: utf-8;q=0.7,*;q=0.7",
                    "Connection: close"
                );
    
            public function __construct() {
                $this->curl = curl_init();
                register_shutdown_function(array($this, 'shutdown'));
            }
    
            /**
             * Get the real URL for the picture to use after
             */
            public function getRealUrl($photoLink) {
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
                curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, false);
                curl_setopt($this->curl, CURLOPT_HEADER, false);
                curl_setopt($this->curl, CURLOPT_USERAGENT, $this->useragent);
                curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($this->curl, CURLOPT_TIMEOUT, 100);
                curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($this->curl, CURLOPT_URL, $photoLink);
    
                //This assumes your code is into a class method, and
                //uses $this->readHeader as the callback function.
                curl_setopt($this->curl, CURLOPT_HEADERFUNCTION, array(&$this, 'readHeader'));
                $response = curl_exec($this->curl);
                if (!curl_errno($this->curl)) {
                    $info = curl_getinfo($this->curl);
                    
                    if ($info["http_code"] == 302) {
                        $headers = $this->getHeaders();
                        if (isset($headers['fileUrl'])) {
                            return $headers['fileUrl'];
                        }
                    }
                }
                return false;
            }
    
    
            /**
             * Download Facebook user photo
             *
             */
            public function download($fileName,$save_path) {
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header);
                curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($this->curl, CURLOPT_HEADER, false);
                curl_setopt($this->curl, CURLOPT_USERAGENT, $this->useragent);
                curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 30);
                curl_setopt($this->curl, CURLOPT_TIMEOUT, 100);
                curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                curl_setopt($this->curl, CURLOPT_URL, $fileName);
                $response = curl_exec($this->curl);
                $return = false;
                if (!curl_errno($this->curl)) {
                    $parts = explode('.', $fileName);
                    $ext = array_pop($parts);
                    $return = getcwd().$save_path;
                   
                    file_put_contents($return, $response);
                }
                return $return;
            }
    
            /**
             * cURL callback function for reading and processing headers.
             * Override this for your needs.
             *
             * @param object $ch
             * @param string $header
             * @return integer
             */
            private function readHeader($ch, $header) {
    
                //Extracting example data: filename from header field Content-Disposition
                $filename = $this->extractCustomHeader('Location: ', '\n', $header);
                if ($filename) {
                    $this->response_meta_info['fileUrl'] = trim($filename);
                }
                return strlen($header);
            }
    
            private function extractCustomHeader($start, $end, $header) {
                $pattern = '/'. $start .'(.*?)'. $end .'/';
                if (preg_match($pattern, $header, $result)) {
                    return $result[1];
                }
                else {
                    return false;
                }
            }
    
            public function getHeaders() {
                return $this->response_meta_info;
            }
    
            /**
             * Cleanup resources
             */
            public function shutdown() {
                if($this->curl) {
                    curl_close($this->curl);
                }
            }
   }
            
     $obj_fb = new sfFacebookPhoto();
     $evaluation =0;
     $thephotoURL =  $obj_fb->getRealUrl($picture_url); 
     
     while(!$thephotoURL)
     {
         $evaluation ++;
        if($evaluation == 4)
        {
            break;
        }
        $thephotoURL =  $obj_fb->getRealUrl($picture_url);  
     }
     
     if($thephotoURL)
     {
         //remove query string if exists
        $thephotoURLName =  explode('?', basename($thephotoURL));
        $filename    = array_shift($thephotoURLName);
        $save_path = $save_path.'/'.$filename;
        $obj_fb->download($thephotoURL,$save_path);
        return $save_path;
     }
     else {
        return Asset::get_filepath_img('theme::anonim-circle.png');
     }
     
}    
        

function create_circle($img_path = '', $result_path = '')
{
    //set_include_path(APPPATH.'libraries/imagine_library' . PATH_SEPARATOR . get_include_path());
    function imagineLoader($class) {
        $path = $class;
       
        $path = APPPATH.'libraries/imagine_library/'.str_replace('\\', DIRECTORY_SEPARATOR, $path) . '.php';

        if (file_exists($path)) {
            include_once $path;
        }
    }
    spl_autoload_register('imagineLoader');
    
    
    class CircleThumbnailFilter implements Imagine\Filter\FilterInterface
    {
        private $imagine;
    
        public function __construct(Imagine\Image\ImagineInterface $imagine,
            Imagine\Image\BoxInterface $size)
        {
            $this->imagine = $imagine;
            $this->size = $size;
        }
    
        public function apply(Imagine\Image\ImageInterface $image)
        {
            // create a thumbnail
            $thumbnail = $image->thumbnail(
                $this->size,
                Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND
            );
    
            // create a new image to hold our mask
            // make the background white
            $mask = $this->imagine->create($this->size, new Imagine\Image\Color('fff'));
    
            // draw a black circle at the center of our new image
            // use $this->size to make it full width and height
            $mask->draw()
                ->ellipse(
                    new Imagine\Image\Point\Center($this->size),
                    $this->size,
                    new Imagine\Image\Color('000', 0),
                    true
                );
    
            // apply the mask to the thumbnail and return it
            return $thumbnail->applyMask($mask);
        }
      }
    
    $imagine = new Imagine\Gd\Imagine();
    $filter  = new CircleThumbnailFilter($imagine, new Imagine\Image\Box(103, 103));
    
    //$filter->apply($imagine->open(getcwd().'/normal.jpg'))->save(getcwd().'/cirle_normal_1.png');
    $filter->apply($imagine->open(getcwd().$img_path))
        ->save(getcwd().$result_path.'/cirle_photo.png');

    $ret = $result_path.'/cirle_photo.png';
    return $ret;
       
}

function get_base64_encode($source_image)
{
	$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
	$filename = getcwd().$source_image;
	$mimetype = finfo_file($finfo, $filename);
	$data_image = base64_encode(file_get_contents(getcwd().$source_image));
	$str_image="data:".$mimetype.";base64,".$data_image;
	return $str_image;
	
}