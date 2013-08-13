<?php
/**
 * Description of ImageMaker
 * class for makeing easly images and (backgrounds)
 *
 *
 * @author Pavkatar
 * @year 2010
 * @version 0.1
 */

class ImageMaker{
    public $_width; //Width of the main image
    public $_height; //Height of the main image
    public $_bgType; //Backgroun type  //Gradient Types: horizontal, vertical, ellipse, ellipse2, circle, circle2, square, rectangle, diamond
    public $_colors = array(); //Colors for the main image
    public $_imageUrl; // Image url
    public $_data; //All new images, made from users
    
    public $imagePosition; //Position

    public  $image; // The main image

    public function __construct($w,$h,$bgType,$colors,$imageUrl = False) {
        $this->_width = $w; // Set width
        $this->_height = $h; //Set height
        $this->_bgType = $bgType; //Set background type (solid, gradient {and gradient type })
        $this->_colors = $colors;//Set colors if bg is solid -> 1 color if is gradient ->2 colors in array
        $this->_imageUrl = $imageUrl; //Set image url (on our server (ouready uploaded))

        $this->image = $this->makeImage($this->image, $this->_width, $this->_height); //Make the main image
        //Make the backgound
        if($this->_bgType == 'solid'){
            $this->fillSolid($this->image, $this->_colors);
        }elseif(preg_match ('/gradient/', $this->_bgType)){
            if(preg_match('/\//',  $this->_bgType)){
                $values = explode('/', $this->_bgType);
                //Types: horizontal, vertical, ellipse, ellipse2, circle, circle2, square, rectangle, diamond
                $this->fillGradient($this->image,$values[1],$this->_colors[0],$this->_colors[1]);
            }
        }
        if($imageUrl != False){
                $this->fillImage($this->image, $imageUrl,$w,$h);
        }
    }
    //Image maker
    public function makeImage($image, $width,$height){
        if (function_exists('imagecreatetruecolor')) {
            $image = imagecreatetruecolor($width,$height);
        } elseif(function_exists('imagecreate')){
            $image = imagecreate($width,$height);
        }else{
            die('Unable to create an image');
        }
        return $image;
    }
    //Image builder from photo,illustration
    public function buildImage($picture){
        if(preg_match("/[.](jpg)$/", $picture)){
            $img = imagecreatefromjpeg($picture);
        }elseif(preg_match("/[.](png)$/", $picture)){
            $img = imagecreatefrompng($picture);
        }else if(preg_match("/[.](gif)$/", $picture)){
            $img = imagecreatefromgif($picture);
        }
        return $img;
    }
    //Put the image to the main
    public function positionImage($image,$newImage,$w,$h, $cords = array(0,0), $position = array('top','left') ,$square = false){
                $ox = imagesx($newImage);
		$oy = imagesy($newImage);
                if($square == false){
                    $size = $this->makeSizes($w, $h, $ox, $oy);
                }else{
                    $size[0] = $w;
                    $size[1] = $h;
                }

                $ps = $this->countPosition($this->_width, $this->_height, $size[0], $size[1], $cords[0], $cords[1], $position);

                return imagecopyresized($image, $newImage, $ps[1], $ps[0], 0, 0, $size[0], $size[1], $ox, $oy); // copy the old image on the new
    }
    //Get the image map (colors)
    public function imageColors($img, $numColors, $image_granularity = 5){
           $image_granularity = max(1, abs((int)$image_granularity));
           $colors = array();
      
           $size[0] = imagesx($img);
           $size[1] = imagesy($img);
           for($x = 0; $x < $size[0]; $x += $image_granularity)
           {
              for($y = 0; $y < $size[1]; $y += $image_granularity)
              {
                 $thisColor = imagecolorat($img, $x, $y);
                 $rgb = imagecolorsforindex($img, $thisColor);
                $red = round(round(($rgb['red'] / 0x33)) * 0x33);
                 $green = round(round(($rgb['green'] / 0x33)) * 0x33);
                 $blue = round(round(($rgb['blue'] / 0x33)) * 0x33);
                 $thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue);
                 if(array_key_exists($thisRGB, $colors))
                 {
                        $colors[$thisRGB]++;
                 }
                 else
                 {
                        $colors[$thisRGB] = 1;
                 }
              }
           }
           arsort($colors);
           
           return array_slice(array_keys($colors), 0, $numColors);

    }
    //Make the image greyscale
    public function imagegreyscale(&$img, $dither=1) {
        if (!($t = imagecolorstotal($img))) {
            $t = 256;
            imagetruecolortopalette($img, $dither, $t);
        }
        for ($c = 0; $c < $t; $c++) {
            $col = imagecolorsforindex($img, $c);
            $min = min($col['red'],$col['green'],$col['blue']);
            $max = max($col['red'],$col['green'],$col['blue']);
            $i = ($max+$min)/2;
            imagecolorset($img, $c, $i, $i, $i);
        }
    }
    //Make the image - sepia
    public function imagetosepia($img) {
      if (!($t = imagecolorstotal($img))) {
        $t = 256;
        imagetruecolortopalette($img, true, $t);
      }
      $total = imagecolorstotal( $img );
      for ( $i = 0; $i < $total; $i++ ) {
        $index = imagecolorsforindex( $img, $i );
        $red = ( $index["red"] * 0.393 + $index["green"] * 0.769 + $index["blue"] * 0.189 );
        $green = ( $index["red"] * 0.349 + $index["green"] * 0.686 + $index["blue"] * 0.168 );
        $blue = ( $index["red"] * 0.272 + $index["green"] * 0.534 + $index["blue"] * 0.131 );
        if ($red > 255) { $red = 255; }
        if ($green > 255) { $green = 255; }
        if ($blue > 255) { $blue = 255; }
        imagecolorset( $img, $i, $red, $green, $blue );
      }
    }
    //Text positioning
    public function text($text, $image, $size,$cords, $position,$color,$font = '../images/font.ttf',$angle=0){
        $co = $this->hex2rgb($color);
        $textSze = strlen($text);
        $ps = $this->countPosition($this->_width, $this->_height, $textSze*$size+$size/2, sqrt($size*$size/13), $cords[0], $cords[1], $position);
        
        $colors = imagecolorallocate($image, $co[0], $co[1], $co[2]);
        imagettftext($image, $size, $angle, $ps[1], $ps[0], $colors, $font, $text);

    }
    //Put to the image Drop shadow
    public function drawShadow($image){
        $width = imagesx($image);
        $height = imagesy($image);
        $tl = imagecreatefromgif("../images/drop/shadow_TL.gif");
        $t  = imagecreatefromgif("../images/drop/shadow_T.gif");
        $tr = imagecreatefromgif("../images/drop/shadow_TR.gif");
        $r  = imagecreatefromgif("../images/drop/shadow_R.gif");
        $br = imagecreatefromgif("../images/drop/shadow_BR.gif");
        $b  = imagecreatefromgif("../images/drop/shadow_B.gif");
        $bl = imagecreatefromgif("../images/drop/shadow_BL.gif");
        $l  = imagecreatefromgif("../images/drop/shadow_L.gif");

        $w = imagesx($l); 	//Width of the left shadow image
        $h = imagesy($l);	//Height of the left shadow image

        $canvasHeight = $height + (2*$w);
        $canvasWidth  = $width + (2*$w);

        $canvas = imagecreatetruecolor($canvasWidth, $canvasHeight);

        imagecopyresized($canvas, $t,0,0,0,0,$canvasWidth,$w,$h,$w);
        imagecopyresized($canvas, $l,0,0,0,0,$w,$canvasHeight,$w,$h);
        imagecopyresized($canvas, $b,0,$canvasHeight-$w,0,0,$canvasWidth,$w,$h, $w);
        imagecopyresized($canvas, $r,$canvasWidth-$w,0,0,0,$w,$canvasHeight,$w,$h);

        
        $w = imagesx($tl);
        $h = imagesy($tl);
        imagecopyresized($canvas, $tl,0,0,0,0,$w,$h,$w,$h);
        imagecopyresized($canvas, $bl,0,$canvasHeight-$h,0,0,$w,$h,$w,$h);
        imagecopyresized($canvas, $br,$canvasWidth-$w,$canvasHeight-$h,0,0,$w,$h,$w,$h);
        imagecopyresized($canvas, $tr,$canvasWidth-$w,0,0,0,$w,$h,$w, $h);

        $w = imagesx($l);
        imagecopyresampled($canvas, $image, $w,$w,0,0,  imagesx($image), imagesy($image), imagesx($image),imagesy($image));
        return $canvas;
    }

    //Draw border to the image
    public function drawBorder($img, $color, $thickness = 1)
    {
        $co = $this->hex2rgb($color);
        $color = ImageColorAllocate($img, $co[0], $co[1], $co[2]);
        $x1 = 0;
        $y1 = 0;
        $x2 = ImageSX($img) - 1;
        $y2 = ImageSY($img) - 1;

        for($i = 0; $i < $thickness; $i++)
        {
            ImageRectangle($img, $x1++, $y1++, $x2--, $y2--, $color);
        }
    }
    //Background solid - one color;
    public function fillSolid($image,$color){
        if(is_array($color)){
            $rgb = $this->hex2rgb($color[0]);
        }else{
            $rgb = $this->hex2rgb($color);
        }
       
        $cvqt = imagecolorallocate($image, $rgb[0],$rgb[1],$rgb[2]);
        if (function_exists("imagefill")) {
            return imagefill($image, 0, 0, $cvqt);
        }else{
            die ('System error!');
        }
    }
    //Background - gradient (2 colros)
    //Gradient Types: horizontal, vertical, ellipse, ellipse2, circle, circle2, square, rectangle, diamond
    private function fillGradient($image,$direction,$color1,$color2){

        switch($direction) {
            case 'horizontal':
                $line_numbers = imagesx($image);
                $line_width = imagesy($image);
                list($r1,$g1,$b1) = $this->hex2rgb($color1);
                list($r2,$g2,$b2) = $this->hex2rgb($color2);
                break;
            case 'vertical':
                $line_numbers = imagesy($image);
                $line_width = imagesx($image);
                list($r1,$g1,$b1) = $this->hex2rgb($color1);
                list($r2,$g2,$b2) = $this->hex2rgb($color2);
                break;
            case 'ellipse':
                $width = imagesx($image);
                $height = imagesy($image);
                $rh=$height>$width?1:$width/$height;
                $rw=$width>$height?1:$height/$width;
                $line_numbers = min($width,$height);
                $center_x = $width/2;
                $center_y = $height/2;
                list($r1,$g1,$b1) = $this->hex2rgb($color2);
                list($r2,$g2,$b2) = $this->hex2rgb($color1);
                imagefill($image, 0, 0, imagecolorallocate( $image, $r1, $g1, $b1 ));
                break;
            case 'ellipse2':
                $width = imagesx($image);
                $height = imagesy($image);
                $rh=$height>$width?1:$width/$height;
                $rw=$width>$height?1:$height/$width;
                $line_numbers = sqrt(pow($width,2)+pow($height,2));
                $center_x = $width/2;
                $center_y = $height/2;
                list($r1,$g1,$b1) = $this->hex2rgb($color2);
                list($r2,$g2,$b2) = $this->hex2rgb($color1);
                break;
            case 'circle':
                $width = imagesx($image);
                $height = imagesy($image);
                $line_numbers = sqrt(pow($width,2)+pow($height,2));
                $center_x = $width/2;
                $center_y = $height/2;
                $rh = $rw = 1;
                list($r1,$g1,$b1) = $this->hex2rgb($color2);
                list($r2,$g2,$b2) = $this->hex2rgb($color1);
                break;
            case 'circle2':
                $width = imagesx($image);
                $height = imagesy($image);
                $line_numbers = min($width,$height);
                $center_x = $width/2;
                $center_y = $height/2;
                $rh = $rw = 1;
                list($r1,$g1,$b1) = $this->hex2rgb($color2);
                list($r2,$g2,$b2) = $this->hex2rgb($color1);
                imagefill($image, 0, 0, imagecolorallocate( $image, $r1, $g1, $b1 ));
                break;
            case 'square':
            case 'rectangle':
                $width = imagesx($image);
                $height = imagesy($image);
                $line_numbers = max($width,$height)/2;
                list($r1,$g1,$b1) = $this->hex2rgb($color2);
                list($r2,$g2,$b2) = $this->hex2rgb($color1);
                break;
            case 'diamond':
                list($r1,$g1,$b1) = $this->hex2rgb($color2);
                list($r2,$g2,$b2) = $this->hex2rgb($color1);
                $width = imagesx($image);
                $height = imagesy($image);
                $rh=$height>$width?1:$width/$height;
                $rw=$width>$height?1:$height/$width;
                $line_numbers = min($width,$height);
                break;
            default:
        }

        for ( $i = 0; $i < $line_numbers; $i=$i+1+$this->step ) {

            if((isset($r))&&(isset($g))&&(isset($b))){
            $old_r=$r;
            $old_g=$g;
            $old_b=$b;
            }else{
            $old_r=0;
            $old_g=0;
            $old_b=0;
            }

            $r = ( $r2 - $r1 != 0 ) ? intval( $r1 + ( $r2 - $r1 ) * ( $i / $line_numbers ) ): $r1;
            $g = ( $g2 - $g1 != 0 ) ? intval( $g1 + ( $g2 - $g1 ) * ( $i / $line_numbers ) ): $g1;
            $b = ( $b2 - $b1 != 0 ) ? intval( $b1 + ( $b2 - $b1 ) * ( $i / $line_numbers ) ): $b1;

            if ( "$old_r,$old_g,$old_b" != "$r,$g,$b")
                $fill = imagecolorallocate( $image, $r, $g, $b );
            switch($direction) {
                case 'vertical':
                    imagefilledrectangle($image, 0, $i, $line_width, $i+$this->step, $fill);
                    break;
                case 'horizontal':
                    imagefilledrectangle( $image, $i, 0, $i+$this->step, $line_width, $fill );
                    break;
                case 'ellipse':
                case 'ellipse2':
                case 'circle':
                case 'circle2':
                    imagefilledellipse ($image,$center_x, $center_y, ($line_numbers-$i)*$rh, ($line_numbers-$i)*$rw,$fill);
                    break;
                case 'square':
                case 'rectangle':
                    imagefilledrectangle ($image,$i*$width/$height,$i*$height/$width,$width-($i*$width/$height), $height-($i*$height/$width),$fill);
                    break;
                case 'diamond':
                    imagefilledpolygon($image, array (
                        $width/2, $i*$rw-0.5*$height,
                        $i*$rh-0.5*$width, $height/2,
                        $width/2,1.5*$height-$i*$rw,
                        1.5*$width-$i*$rh, $height/2 ), 4, $fill);
                    break;
                default:
            }
        }
    }
    //Put image to the main image like bg
    public function fillImage($image, $picture,$w,$h, $cords = array(0,0), $position = array('top','left') ,$square = false){
        
                if(preg_match("/[.](jpg)$/", $picture)){
                    $img = imagecreatefromjpeg($picture);
                }elseif(preg_match("/[.](png)$/", $picture)){
                    $img = imagecreatefrompng($picture);
                }else if(preg_match("/[.](gif)$/", $picture)){
                    $img = imagecreatefromgif($picture);
                }
		$ox = imagesx($img);
		$oy = imagesy($img);
                if($square == false){
                    $size = $this->makeSizes($w, $h, $ox, $oy);
                }else{
                    $size[0] = $w;
                    $size[1] = $h;
                }
                
                $ps = $this->countPosition($this->_width, $this->_height, $size[0], $size[1], $cords[0], $cords[1], $position);

                return imagecopyresized($image, $img, $ps[1], $ps[0], 0, 0, $size[0], $size[1], $ox, $oy); // copy the old image on the new
    }
    //Count the position (top,center,bottom - left,center,right)
    public function countPosition($imageW,$imageH,$w,$h, $marginTop, $marginLeft, $position = array('top','left')){
        if($position[0] == 'top'){
            if($position[1] == 'left'){
                return array($marginTop,$marginLeft);
            }elseif($position[1] == 'right'){
                $marginLeft = $imageW-$marginLeft-$w;
                return array($marginTop,$marginLeft);
            }else{
                $marginLeft = $imageW/2-$w/2+$marginLeft;
                return array($marginTop,$marginLeft);
            }
        }elseif($position[0] == 'center'){
            if($position[1] == 'left'){
                 $marginTop = ($imageH/2)-($h/2)+$marginTop;
                 return array($marginTop,$marginLeft);
            }elseif($position[1] == 'right'){
                $marginTop = ($imageH/2)-($h/2)+$marginTop;
                $marginLeft = $imageW-$marginLeft-$w;
                return array($marginTop,$marginLeft);
            }else{
                $marginTop = ($imageH/2)-($h/2)+$marginTop;
                $marginLeft = ($imageW/2)-($w/2)+$marginLeft;
                return array($marginTop,$marginLeft);
            }
        }else{
            if($position[1] == 'left'){
                 $marginTop = $imageH-$marginTop-$h;
                 return array($marginTop,$marginLeft);
            }elseif($position[1] == 'right'){
                 $marginTop = $imageH-$marginTop-$h;
                 $marginLeft = $imageW-$marginLeft-$w;
                 return array($marginTop,$marginLeft);
            }else{
                $marginTop = $imageH-$marginTop-$h;
                $marginLeft = ($imageW/2)-($w/2)+$marginLeft;
                return array($marginTop,$marginLeft);
            }
        }
    }
    //Image rotator
    public function rotate($image,$degree){
         return imagerotate($image, $degree, 0);
    }
    //Image cropper 
    public function crop($image,$cords,$w,$h){
        $width = imagesx($image);
	$height = imagesy($image);
        //list($width,$height) = getimagesize($this->image);
        $tmp = imagecreatetruecolor($w,$h);
        imagecopyresampled($tmp, $image, 0,0, $cords[1], $cords[0], $w,$h, $w,$h);
        return $tmp;
    }
    public function brightness($im,$value){
        imagefilter($im, IMG_FILTER_BRIGHTNESS, $value);
    }
    public function contrast($im,$value){
        imagefilter($im, IMG_FILTER_CONTRAST, $value);
    }
    public function blur($image){
        imagefilter($image, IMG_FILTER_GAUSSIAN_BLUR);
    }
    //Show the image
    public function show($image) {
        if (function_exists("imagepng")) {
            header("Content-type: image/png");
            imagepng($image);
        }
        elseif (function_exists("imagegif")) {
            header("Content-type: image/gif");
            imagegif($image);
        }
        elseif (function_exists("imagejpeg")) {
            header("Content-type: image/jpeg");
            imagejpeg($image, "", 0.5);
        }
        elseif (function_exists("imagewbmp")) {
            header("Content-type: image/vnd.wap.wbmp");
            imagewbmp($image);
        } else {
            die("Install GD on the server to use this class!");
        }
        imagedestroy($image);
        return true;
    }
    //Magic methods, for making more iamges
    public function __get($name) {
        return $this->_data[$name];
    }
    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }
    //Color transformer
    public function hex2rgb($color) {
        $color = str_replace('#','',$color);
        $s = strlen($color) / 3;
        $rgb[]=hexdec(str_repeat(substr($color,0,$s),2/$s));
        $rgb[]=hexdec(str_repeat(substr($color,$s,$s),2/$s));
        $rgb[]=hexdec(str_repeat(substr($color,2*$s,$s),2/$s));
        return $rgb;
    }
        /**
	 * Image size maker.
	 * @param integer $w New width
	 * @param integer $h New height
	 * @param integer $ow, $h - old width/height
	 */
    public function makeSizes($w,$h,$ow,$oh){
            if($w>$h){
		$nw = $w;
		$nh = ($w/$ow)*$oh;
            }elseif($w<$h){
		$nh = $h;
		$nw = ($h/$oh)*$ow;
            }else{
		$nw = $w;
		$nh = $h;
            }
            return array($nw,$nh);
    }
        /**
	 * Save the image on a disk.
	 * @param string $type A type i
	 * @param string $name The path to save the file to. If NULL, then image will be outputted to browser.
	 * @param integer $quality A quality of image, for JPEG it's betwen 0-100 (quality level), for PNG 0-9 (compresion level)
	 */
	public function Saver( $type = 'png', $name = '../images/', $quality = 95 ){
		$quality = (int)$quality;
		switch( $type ){
			case 'png':
				$quality = $quality > 9 ? 9 : $quality;
				$quality = $quality < 0 ? 0 : $quality;
				if( !$name ) header( 'Content-type: image/png' );
				return imagePNG( $this->image, $name, $quality );
				break;
			case 'gif':
				if( !$name ) header( 'Content-type: image/gif' );
				return imageGIF( $this->image, $name );
				break;
			case 'jpeg':
			case 'jpg':
			default:
				$quality = $quality > 100 ? 100 : $quality;
				$quality = $quality < 0 ? 0 : $quality;
				if( !$name ) header( 'Content-type: image/jpeg' );
				return imageJpeg( $this->image, $name, $quality );
				break;
		}
	}
        public static function reflection($imgImport){

            $imgName_w = imagesx($imgImport);
            $imgName_h = imagesy($imgImport);
            $gradientHeight = 100;

            $background = imagecreatetruecolor($imgName_w, $gradientHeight);
            $gradientColor = "255 255 255"; //White
            $gradparts = explode(" ",$gradientColor); // get the parts of the  colour (RRR,GGG,BBB)
            $dividerHeight = 1;

            $gradient_y_startpoint = $dividerHeight;
            $gdGradientColor=ImageColorAllocate($background,$gradparts[0],$gradparts[1],$gradparts[2]);

            $newImage = imagecreatetruecolor($imgName_w, $imgName_h);
            for ($x = 0; $x < $imgName_w; $x++) {

                for ($y = 0; $y < $imgName_h; $y++)
                {
                imagecopy($newImage, $imgImport, $x, $imgName_h - $y - 1, $x, $y, 1, 1);
                }
            }
            imagecopymerge ($background, $newImage, 0, 0, 0, 0, $imgName_w, $imgName_h, 100);

            $gradient_line = imagecreatetruecolor($imgName_w, 1);
            imageline ($gradient_line, 0, 0, $imgName_w, 0, $gdGradientColor);
            $i = 0;
            $transparency = 30; //from 0 - 100
                while ($i < $gradientHeight) //create line by line changing as we go
                {
                    imagecopymerge ($background, $gradient_line, 0,$gradient_y_startpoint, 0, 0, $imgName_w, 1, $transparency);
                    ++$i;
                    ++$gradient_y_startpoint;
                            if ($transparency == 100) {
                                $transparency = 100;
                            }
                            else
                            {
                                $transparency = $transparency + 1;
                            }
                }

                imagesetthickness ($background, $dividerHeight);
                imageline ($background, 0, 0, $imgName_w, 0, $gdGradientColor);
                return $background;
        }
        public function drawline($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
        {
            
            $co = $this->hex2rgb($color);
            $color=ImageColorAllocate($image,$co[0],$co[1],$co[2]);
            if ($thick == 1) {
                return imageline($image, $x1, $y1, $x2, $y2, $color);
            }
            $t = $thick / 2 - 0.5;
            if ($x1 == $x2 || $y1 == $y2) {
                return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
            }
            $k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
            $a = $t / sqrt(1 + pow($k, 2));
            $points = array(
                round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
                round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
                round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
                round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
            );
            imagefilledpolygon($image, $points, 4, $color);
            return imagepolygon($image, $points, 4, $color);
        }


        public function flipVertical($img) {
         $size_x = imagesx($img);
         $size_y = imagesy($img);
         $temp = imagecreatetruecolor($size_x, $size_y);
         imagecopyresampled($temp, $img, 0, 0, 0, ($size_y-1), $size_x, $size_y, $size_x, 0-$size_y);
         return $temp;
        }
        public function flipHorizontal(&$img) {
         $size_x = imagesx($img);
         $size_y = imagesy($img);
         $temp = imagecreatetruecolor($size_x, $size_y);
         imagecopyresampled($temp, $img, 0, 0, ($size_x-1), 0, $size_x, $size_y, 0-$size_x, $size_y);
         return $temp;
        }
}

?>
