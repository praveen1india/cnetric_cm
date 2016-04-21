<?php

class salesProImage {
    
    public $cache = 'cache/';
    public $dirs = array('cache','items','categories','default');
    public $dir = 'images/salesPro/';
    public $default_img = 'default/default.jpg';
    public $compression = '90';
    public $purge = 86400; //1 DAY CACHE
    public $image;
    public $image_type;
    public $valid_exts = array('jpg','jpeg','gif','png');
    
    /* FACTORY METHOD TO LOAD, CROP AND CACHE AN IMAGE
    // ACCEPTS A RELATIVE IMAGE FILENAME AND RETURNS
    // THE CACHED IMAGE FILENAME */
    public static function _($img = '', $width = 0, $height = 0) {
        $spr = new salesProImage;

        //VALIDATE THE REQUESTED IMAGE
        $spr->image_file = JPATH_SITE.'/'.$spr->dir.$img;

        $path = pathinfo($spr->image_file);
        $ext = (isset($path['extension'])) ? $path['extension'] : '';
        if(!file_exists($spr->image_file) || !in_array($ext,$spr->valid_exts)) {
            $spr->image_file = JPATH_SITE.'/'.$spr->dir.$spr->default_img;
            $path = pathinfo($spr->image_file);
            $ext = $path['extension'];
        }

        //CREATE THE HASHED CACHE FILENAME
        $size = filesize($spr->image_file);
        $settings = sprConfig::_load('images');
        $cropping = $settings->crop;
        $background = trim($settings->bg);
        if($background === '') $ext = 'png';
        $hash = md5($spr->image.$size.$width.$height.$cropping.$background);
        $filename = $hash.'.'.$ext;
        
        //IF FILE DOES NOT EXIST IN CACHE - CREATE IT
        $newfile = JPATH_SITE.'/'.$spr->dir.$spr->cache.$filename;
        if(!file_exists($newfile)) {
            $spr->load($spr->image_file);
            $spr->resize($width,$height,$cropping,$background);
            $spr->save($newfile);
        }
        
        //RETURN THE CACHED IMAGE SRC
        $src = JURI::root().$spr->dir.$spr->cache.$filename;
        return $src;
    }
    
    private function __construct() {
        $this->checkCacheDir();
        $this->checkDefaultImg();
        $this->purge(0);
    }
    
    private function checkCacheDir() {
        if(!is_dir(JPATH_SITE.'/'.$this->dir)) @mkdir(JPATH_SITE.'/'.$this->dir);
        foreach($this->dirs as $d) {
            if(!is_dir(JPATH_SITE.'/'.$this->dir.$d)) @mkdir(JPATH_SITE.'/'.$this->dir.$d);
        }
    }
    
    private function checkDefaultImg() {
        $default_img = JPATH_SITE.'/'.$this->dir.$this->default_img;
        if(!file_exists($default_img)) {
            $img = JPATH_SITE.'/components/com_salespro/images/default/default.jpg';
            @copy($img,$default_img);
        }
    }

    private function purge($all=1) {
        $files = glob(JPATH_SITE.'/'.$this->dir.$this->cache . '*', GLOB_MARK);
        if(is_array($files) && count($files)>0) foreach($files as $f) {
            if($all === 0) {
                $time = time() - filemtime($f);
                if($time < $this->purge) continue;
            }
            unlink($f);
        }
    }

    private function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    private function save($filename, $compression=75, $permissions=null) {
        $path = pathinfo($filename);
        $ext = $path['extension'];
        switch($ext) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($this->image,$filename,$compression);
                break;
            case 'gif':
                imagegif($this->image,$filename);
                break;
            case 'png':
            default:
                imagepng($this->image,$filename);
                break;
        }
        
        if( $permissions != null) {
            chmod($filename,$permissions);
        }
    }

    private function getWidth() {
        return imagesx($this->image);
    }
    
    private function getHeight() {
        return imagesy($this->image);
    }
    
    private function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        return $width;
    }
    
    private function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        return $height;
    }
    
    private function scale($scale) {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }
    
    private function hex2rgb($hex) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b);
        return $rgb;
    }
    
    private function resize($newwidth=0,$newheight=0,$stretch=0,$background='') {

        $stretch = (int)$stretch;

        if($stretch === 0) {
            return false;
        }

        if($newwidth == 0 && $newheight == 0) {
            $newheight = $this->getHeight();
            $newwidth = $this->getWidth();
        }
        elseif($newwidth == 0) $newwidth = $this->resizeToHeight($newheight);
        elseif($newheight == 0) $newheight = $this->resizeToWidth($newwidth);
        
        $width = $this->getWidth();
        $height = $this->getHeight();
        $x = 0;
        $y = 0;
        $tmp = imagecreatetruecolor($newwidth, $newheight);
        
        /*
        if($this->image_type == IMAGETYPE_PNG) {
            imagealphablending($tmp, false);
            imagesavealpha($tmp,true);
            $color = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
            imagefill($tmp, 0, 0, $color);
        } else {
            $bgcolor = imagecolorallocate($tmp, 255, 0, 0); 
            imagefill($tmp,0,0,$bgcolor);
        }
        */
        
        imagealphablending($tmp, false);
        imagesavealpha($tmp,true);
        $color = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
        imagefill($tmp, 0, 0, $color);
        
        if($background !== '') {
            $rgb = $this->hex2rgb($background);
            $bgcolor = imagecolorallocate($tmp, $rgb[0], $rgb[1], $rgb[2]); 
            imagefill($tmp,0,0,$bgcolor);
        }
        
        $widthProportion  = $newwidth / $width;
        $heightProportion = $newheight / $height;
        
        if($stretch === 2) {
            if($widthProportion > $heightProportion) {
                $savewidth = $widthProportion * $width;
                $saveheight = $widthProportion * $height;
                $y = ($height * ($heightProportion-$widthProportion)) /2;
            } else {
                $savewidth = $heightProportion * $width;
                $saveheight = $heightProportion * $height;
                $x = ($width * ($widthProportion - $heightProportion)) /2;
            }
        } else {
            if($widthProportion > $heightProportion) {
                $savewidth = $heightProportion * $width;
                $saveheight = $heightProportion * $height;
                $x = ($width * ($widthProportion - $heightProportion)) /2;
            } else {
                $savewidth = $widthProportion * $width;
                $saveheight = $widthProportion * $height;
                $y = ($height * ($heightProportion-$widthProportion)) /2;
            }
        }
        imagecopyresampled($tmp, $this->image, $x, $y, 0, 0, $savewidth, $saveheight, $width, $height);
        $this->image = $tmp;
    }
}