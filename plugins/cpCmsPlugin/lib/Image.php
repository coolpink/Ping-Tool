<?php

/**
 * Image manipulation library
 */

class Image {

	/**
	 * Is the mime type we're dealing with an image?
	 * @param string $mime
	 * @return bool
	 */
	static function is_image($mime) {

		$mime = Image::get_mime($mime);

		$types = array('jpeg', 'gif', 'png');
		foreach ($types as $type) {
			if (strpos($mime, $type) !== false) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the image type from the given mime type
	 * @param string $mime
	 * @return string
	 */
	static function get_mime($mime) {

		$mime = strtolower(substr($mime, strrpos($mime, '/') + 1));
		if ($mime == 'pjpeg') $mime = 'jpeg';

		return $mime;

	}

	/**
	 * Resize the given file to the width and height specified and save to the new filename
	 * @param string $old_filename
	 * @param string $new_filename
	 * @param int $new_width
	 * @param int $new_height
	 * @return bool
	 */
	static function resize($old_filename, $new_filename, $new_width, $new_height) {

		$imagesize = getimagesize($old_filename);
		$width = $imagesize[0];
		$height = $imagesize[1];

		$mime = Image::get_mime($imagesize['mime']);

		if ($mime == "png"){

		//deal with transparency for png's

			$alphablending = false;
			$old_img = imagecreatefrompng($old_filename);

			imagealphablending($old_img, true);
			imagesavealpha($old_img, true);

			$new_img = imagecreatetruecolor($new_width, $new_height);

			$background = imagecolorallocate($new_img, 0, 0, 0);
			ImageColorTransparent($new_img, $background);
			imagealphablending($new_img, $alphablending);
			imagesavealpha($new_img, true);

			ImageCopyResampled(
				$new_img,
				$old_img,
				0, 0, 0, 0,
				$new_width,
				$new_height,
				imagesx($old_img),
				imagesy($old_img)
			);

			//header("Content-type: ".$mime);
			//imagepng($img_temp);

			$success = imagepng($new_img, $new_filename);
			@chmod($new_filename, 0777);

			imagedestroy($old_img);
			imagedestroy($new_img);

			return $success;


		} else {

			$input_func = "imagecreatefrom$mime";
			$output_func = "image$mime";

			$old_img = $input_func($old_filename);
			$new_img = imagecreatetruecolor($new_width, $new_height);

			imagecopyresampled($new_img, $old_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			$success = $output_func($new_img, $new_filename, 100);
			@chmod($new_filename, 0777);

			imagedestroy($old_img);
			imagedestroy($new_img);

			return $success;

		}

	}

	/**
	 * Resize the given image to the size specified. Landscape images will become $max_size wide,
	 * portrait images will become $max_size high
	 * @param string $old_filename
	 * @param string $new_filename
	 * @param int $max_size
	 * @return bool
	 */
	static function resize_max($old_filename, $new_filename, $max_size) {

		$imagesize = getimagesize($old_filename);
		$width = $imagesize[0];
		$height = $imagesize[1];

		$old_aspect = $height / $width;

		if ($width > $height) {
			$new_width = $max_size;
			$new_height = round($new_width * $old_aspect);
		} else {
			$new_height = $max_size;
			$new_width = round($new_height * (1 / $old_aspect));
		}

		return Image::resize($old_filename, $new_filename, $new_width, $new_height);

	}


	static function resize_maxsize($old_filename, $new_filename, $max_width = 100000, $max_height = 100000) {

		$imagesize = getimagesize($old_filename);
		$width = $imagesize[0];
		$height = $imagesize[1];

		$wRatio = $width / $max_width;
		$hRatio = $height / $max_height;
		$maxRatio = max($wRatio, $hRatio);

		if ($maxRatio > 1) {
			$outputWidth = $width / $maxRatio;
        	$outputHeight = $height / $maxRatio;
		} else {
			$outputWidth = $width;
        	$outputHeight = $height;
		}

		return Image::resize($old_filename, $new_filename, $outputWidth, $outputHeight);

	}

	/**
	 * Resize the given image to the width specified. The height will be fluid and the image
	 * will remain at the original aspect ratio
	 * @param string $old_filename
	 * @param string $new_filename
	 * @param int $new_width
	 * @return bool
	 */
	static function resize_w($old_filename, $new_filename, $new_width) {

		$imagesize = getimagesize($old_filename);
		$width = $imagesize[0];
		$height = $imagesize[1];

		$old_aspect = $height / $width;
		$new_height = round($new_width * $old_aspect);

		return Image::resize($old_filename, $new_filename, $new_width, $new_height);

	}

	/**
	 * Resize the given image to the height specified. The width will be fluid and the image
	 * will remain at the original aspect ratio
	 * @param string $old_filename
	 * @param string $new_filename
	 * @param int $new_height
	 * @return bool
	 */
	static function resize_h($old_filename, $new_filename, $new_height) {

		$imagesize = getimagesize($old_filename);
		$width = $imagesize[0];
		$height = $imagesize[1];

		$old_aspect = $height / $width;
		$new_width = round($new_height * (1 / $old_aspect));

		return Image::resize($old_filename, $new_filename, $new_width, $new_height);

	}

	/**
	 * Resize the given image to the width and height specified. The center of the image
	 * will remain with the excess cropped off
	 * @param string $old_filename
	 * @param string $new_filename
	 * @param int $new_width
	 * @param int $new_height
	 * @return bool
	 */
	static function resize_crop($old_filename, $new_filename, $new_width, $new_height) {

		$imagesize = getimagesize($old_filename);
		$width = $imagesize[0];
		$height = $imagesize[1];

		$mime = Image::get_mime($imagesize['mime']);

		$input_func = "imagecreatefrom$mime";
		$output_func = "image$mime";

		$old_aspect = $height / $width;
		$new_aspect = $new_height / $new_width;

		if ($height > $width) {

			if ($old_aspect < $new_aspect) {
				$scale_height = $new_height;
			} else {
				$scale_width = $new_width;
			}

		} elseif ($width > $height) {

			if ($old_aspect < $new_aspect) {
				$scale_height = $new_height;
			} else {
				$scale_width = $new_width;
			}

		} else { // image is square

            if($new_height > $new_width) {
                $scale_height = $new_height;
                $scale_width = $new_height;
            } else {
                $scale_height = $new_width;
                $scale_width = $new_width;
            }


		}

		if (isset($scale_width) && !isset($scale_height)) {
			$scale_height = round($scale_width * $old_aspect);
		} elseif (isset($scale_height) && !isset($scale_width)) {
			$scale_width = round($scale_height * (1 / $old_aspect));
		}

		$xpos = ($scale_width - $new_width) / -2;
		$ypos = ($scale_height - $new_height) / -2;
		if($mime == "png"){

			//deal with png transparencies

			$alphablending = false;
			$old_img = imagecreatefrompng($old_filename);

			imagealphablending($old_img, true);
			imagesavealpha($old_img, true);

			$new_img = imagecreatetruecolor($new_width, $new_height);

			$background = imagecolorallocate($new_img, 0, 0, 0);
			ImageColorTransparent($new_img, $background);
			imagealphablending($new_img, $alphablending);
			imagesavealpha($new_img, true);

			imagecopyresampled($new_img, $old_img, $xpos, $ypos, 0, 0, $scale_width, $scale_height, $width, $height);

			$success = $output_func($new_img, $new_filename);

		} else {

			$old_img = $input_func($old_filename);
			$new_img = imagecreatetruecolor($new_width, $new_height);

			imagecopyresampled($new_img, $old_img, $xpos, $ypos, 0, 0, $scale_width, $scale_height, $width, $height);

			$success = $output_func($new_img, $new_filename, 100);

		}

		@chmod($new_filename, 0777);

		imagedestroy($old_img);
		imagedestroy($new_img);

		return $success;

	}

	/**
	 * Resize the given image to fit the width and height specified. Background will be filled.
	 * @param string $old_filename
	 * @param string $new_filename
	 * @param int $new_width
	 * @param int $new_height
	 * @param string $background Hex code eg FFFF00
	 * @return bool
	 */
	static function resize_fit($old_filename, $new_filename, $new_width, $new_height, $background) {

		$imagesize = getimagesize($old_filename);
		$width = $imagesize[0];
		$height = $imagesize[1];

		$mime = Image::get_mime($imagesize['mime']);

		$input_func = "imagecreatefrom$mime";
		$output_func = "image$mime";

		$new_img = imagecreatetruecolor($new_width, $new_height);

		// Convert hex colour to RGB
		$background = array(base_convert(substr($background, 0, 2), 16, 10), base_convert(substr($background, 2, 2), 16, 10), base_convert(substr($background, 4, 2), 16, 10));
		$bg = imagecolorallocate($new_img, $background[0], $background[1], $background[2]);

		if (($width / $height) >= ($new_width / $new_height)) {
			// by width
			$nw = $new_width;
			$nh = $height * ($new_width / $width);
			$nx = 0;
			$ny = round(abs($new_height - $nh) / 2);
		} else {
			// by height
			$nw = $width * ($new_height / $height);
			$nh = $new_height;
			$nx = round(abs($new_width - $nw) / 2);
			$ny = 0;
		}

		if($mime == "png"){

			//deal with png transparencies

			$alphablending = false;
			$old_img = imagecreatefrompng($old_filename);

			imagealphablending($old_img, true);
			imagesavealpha($old_img, true);

			ImageColorTransparent($new_img, $bg);
			imagealphablending($new_img, $alphablending);
			imagesavealpha($new_img, true);

			imagecopyresampled($new_img, $old_img, $nx, $ny, 0, 0, $nw, $nh, $width, $height);

			$success = $output_func($new_img, $new_filename);

		} else {

			$old_img = $input_func($old_filename);

			imagefill($new_img, 0, 0, $bg);
			imagecopyresampled($new_img, $old_img, $nx, $ny, 0, 0, $nw, $nh, $width, $height);

			$success = $output_func($new_img, $new_filename, 100);

		}

		@chmod($new_filename, 0777);

		imagedestroy($old_img);
		imagedestroy($new_img);

		return $success;

	}

	/**
	 * Used to crop an image using the javascript crop tool
	 * @param string $path Path to original file
	 * @param string $old_filename Original filename
	 * @param string $new_filename Temp new filename
	 * @param int $x1
	 * @param int $y1
	 * @param int $x2
	 * @param int $y2
	 * @param int $width Width of selection
	 * @param int $height Height of selection
	 * @param int $resized_width Resized width of image
	 */
	static function crop_image($original_path, $new_path, $x1, $y1, $x2, $y2, $width, $height) {

		// original image size
		$imagesize = getimagesize($original_path);

		$orig_width = $imagesize[0];
		$orig_height = $imagesize[1];


		// dynamically get open and close functions
		$mime = Image::get_mime($imagesize['mime']);

		$input_func = "imagecreatefrom$mime";
		$output_func = "image$mime";

		$img = imagecreatetruecolor($width, $height);
		$orig = $input_func($original_path);
		imagecopy($img, $orig, 0, 0, $x1, $y1, $orig_width, $orig_height);
		$success = $output_func($img, $new_path, 90);
		imagedestroy($img);

		return $success;
	}

	/**
	 * Change the brightness of an image
	 * @param string $filename
	 * @param float $amount
	 * @return bool
	 */
	static function change_brightness($filename, $amount) {

		$upload_path = $GLOBALS['SystemSettings']['upload_files'];

		$imagesize = getimagesize("{$upload_path}{$filename}");
		$mime = Image::get_mime($imagesize['mime']);

		$input_func = "imagecreatefrom$mime";
		$output_func = "image$mime";

		$img = $input_func($upload_path . $filename);

		$param = 1.0;

		for ($h = 0; $h < $imagesize[1]; $h++) {
			for ($w = 0; $w < $imagesize[0]; $w++) {
				$colours = imagecolorsforindex($img, imagecolorat($img, $w, $h));
				$colours['red'] = max(0, min(255, $colours['red'] * $param));
				$colours['green'] = max(0, min(255, $colours['green'] * $param));
				$colours['blue'] = max(0, min(255, $colours['blue'] * $param));
				$new_colour = imagecolorallocate($img, $colours['red'], $colours['green'], $colours['blue']);
				imagesetpixel($img, $w, $h, $new_colour);
			}
		}

		$result = $output_func($img, "{$upload_path}{$filename}", 90);
		return $result;
	}

	/**
	 * Clears asset cache of a particular file
	 * @param string $filename
	 */
	static function clear_cache($filename) {

		$asset_cache_path = getcwd() . '/tmp/asset_cache/';

		$handle = @opendir($asset_cache_path);

		while ($file = readdir($handle)) {
			$tmp_file = explode('-', $file);
			unset($tmp_file[0]);
			$tmp_file = implode('-', $tmp_file);
			if ($tmp_file == $filename) {
				unlink("{$asset_cache_path}{$file}");
			}
		}

		closedir($handle);
	}

	/**
	 * Creates rounded corners on an image
	 * @param string $filename
	 */
	static function round_corners($old_filename, $new_filename) {

		$upload_path = $GLOBALS['SystemSettings']['upload_files'];

		$imagesize = getimagesize("{$old_filename}");
		$mime = Image::get_mime($imagesize['mime']);

		$input_func = "imagecreatefrom$mime";
		$output_func = "image$mime";

		$image_file = $old_filename;
		$corner_radius = 20;
		$angle = 0;

		$images_dir = getcwd() . '/upload_files/';
		$corner_source = imagecreatefrompng(getcwd() . '/webroot/images/rounded_corner.png');

		$corner_width = imagesx($corner_source);
		$corner_height = imagesy($corner_source);
		$corner_resized = ImageCreateTrueColor($corner_radius, $corner_radius);
		ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $corner_radius, $corner_radius, $corner_width, $corner_height);

		$corner_width = imagesx($corner_resized);
		$corner_height = imagesy($corner_resized);
		$image = imagecreatetruecolor($corner_width, $corner_height);
		$image = imagecreatefromjpeg(getcwd() .'/'. $old_filename); // replace filename with $_GET['src']
		$size = getimagesize(getcwd() .'/'. $old_filename); // replace filename with $_GET['src']
		$white = ImageColorAllocate($image,255,255,255);
		$black = ImageColorAllocate($image,0,0,0);

		// Top-left corner

			$dest_x = 0;
			$dest_y = 0;
			imagecolortransparent($corner_resized, $black);
			imagecopymerge($image, $corner_resized, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);


		// Bottom-left corner

			$dest_x = 0;
			$dest_y = $size[1] - $corner_height;
			//null = imagerotate($corner_resized, 90, 0);
			//imagecolortransparent(null, $black);
			imagecopymerge($new_filename, null, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);

/*
		// Bottom-right corner

			$dest_x = $size[0] - $corner_width;
			$dest_y = $size[1] - $corner_height;
			//null = imagerotate($corner_resized, 180, 0);
			imagecolortransparent(null, $black);
			imagecopymerge($old_filename, null, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);


		// Top-right corner

			$dest_x = $size[0] - $corner_width;
			$dest_y = 0;
			//null = imagerotate($corner_resized, 270, 0);
			imagecolortransparent(null, $black);
			imagecopymerge($old_filename, null, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);


		// Rotate image
		//$image = imagerotate($image, $angle, $white);
*/
		// Output final image
		imagejpeg($old_filename);

		imagedestroy($image);
		imagedestroy($corner_source);

	}

	/**
	 * Creates rounded corners
	 * @param image-resource $img
	 * @param int $radius
	 */
	static function roundcorners($filename, $output_filename, $radius){

		$upload_path = $GLOBALS['SystemSettings']['upload_files'];
		$imagesize = getimagesize("{$filename}");

		// dynamically get open and close functions
		$mime = Image::get_mime($imagesize['mime']);

		$input_func = "imagecreatefrom$mime";

		$img = $input_func("{$filename}");

	 	$width = imagesx($img);
	 	$height = imagesy($img);

		$cx = 0;
		$cy = 0;
		// offset
		$offset = $radius;


		$xoff = $offset;
		$yoff = 0;

		$colour = imagecolortransparent($img);
		if($colour <=0){
			$crazy_random_colour = imagecolorallocate($img, 255,20,147);
			$colour = imagecolortransparent($img, $crazy_random_colour);
		}


		//top left

		for ($i = 0; $i < $offset; $i++) {
			imageline($img, $xoff, $cy, $cx, $yoff, $colour);

			$xoff --;
			$yoff ++;
		}

		//top right

		$xoff = $width - $offset;
		$yoff = 0;
		$cy = 0;
		$cx = $width -1;

		for ($i = 0; $i < $offset; $i++) {
			imageline($img, $xoff, $cy, $cx, $yoff, $colour);

			$xoff ++;
			$yoff ++;
		}


		//bottom right
		$xoff = $width - $offset;
		$yoff = $height - 1;
		$cy = $height - 1;
		$cx = $width - 1;

		for ($i = 0; $i < $offset; $i++) {
			imageline($img, $xoff, $cy, $cx, $yoff, $colour);
			$xoff ++;
			$yoff --;
		}


		//bottom left
		$xoff = $offset;
		$yoff = $height - 1;
		$cx = 0;
		$cy = $height - 1;

		for ($i = 0; $i < $offset; $i++) {
			imageline($img, $xoff, $cy, $cx, $yoff, $colour);
			$xoff --;
			$yoff --;
		}

		imagepng($img, "{$output_filename}");
		return true;


	}

	static function imagebmp($image) {
		$width_orig = imagesx($image);
		// width = 16*x
		$width_floor = ((floor($width_orig/16))*16);
		$width_ceil = ((ceil($width_orig/16))*16);
		$height = imagesy($image);

		$size = ($width_ceil*$height*3)+54;

		// Bitmap file header
		$result = "\x42\x4d"; 	//'BM';			// header (2b)
		$result .= self::intToDword($size);		// size of file (4b)
		$result .= self::intToDword(0);			// reserved (4b)
		$result .= self::intToDword(54);		// byte location in the file which is first byte of Image (4b)
		// Bitmap info header
		$result .= self::intToDword(40);		// size of BITMAPINFOHEADER (4b)
		$result .= self::intToDword($width_ceil);	// width of Bitmap (4b)
		$result .= self::intToDword($height);	// height of Bitmap (4b)
		$result .= self::intToWord(1);			// biPlanes = 1 (2b)
		$result .= self::intToWord(24);			// biBitCount = {1 (mono) or 4 (16clr) or 8 (256 clr) or 24 (16 Mil)} (2b)
		$result .= self::intToDword(0);			// RLE COMPRESSION (4b)
		$result .= self::intToDword(0);			// width x height (4b)
		$result .= self::intToDword(0);			// biXPelsPerMeter (4b)
		$result .= self::intToDword(0);			// biYPelsPerMeter (4b)
		$result .= self::intToDword(0);			// Number of pallettes used (4b)
		$result .= self::intToDword(0);			// Number of important colour (4b)

		// is faster than chr()
		$arrChr = array();
		for ($i=0;$i<256;$i++) {
			$arrChr[$i] = chr($i);
		}

		// creates image data
		$bg_fill_colour = array("red" => 255, "green" => 255, "blue" => 255);

		// bottom to top, left to right
		$y=$height-1;
		for ($y2=0;$y2<$height;$y2++) {
			for ($x=0;$x<$width_floor; ) {
				$rgb = imagecolorsforindex($image, imagecolorat($image, $x++, $y));
				$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
				$rgb = imagecolorsforindex($image, imagecolorat($image, $x++, $y));
          		$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
          		$rgb = imagecolorsforindex($image, imagecolorat($image, $x++, $y));
          		$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
          		$rgb = imagecolorsforindex($image, imagecolorat($image, $x++, $y));
          		$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
          		$rgb = imagecolorsforindex($image, imagecolorat($image, $x++, $y));
          		$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
          		$rgb = imagecolorsforindex($image, imagecolorat($image, $x++, $y));
          		$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
          		$rgb = imagecolorsforindex($image, imagecolorat($image, $x++, $y));
          		$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
          		$rgb = imagecolorsforindex($image, imagecolorat($image, $x++, $y));
          		$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
			}
			for ($x=$width_floor;$x<$width_ceil;$x++) {
				$rgb = ($x<$width_orig) ? imagecolorsforindex($image, imagecolorat($image, $x, $y)) : $bg_fill_colour;
				$result .= $arrChr[$rgb["blue"]].$arrChr[$rgb["green"]].$arrChr[$rgb["red"]];
			}
			$y--;
		}

		return $result;
	}

	static function intToDword($n) {
		return chr($n & 255).chr(($n >> 8) & 255).chr(($n >> 16) & 255).chr(($n >> 24) & 255);
	}

	static function intToWord($n) {
		return chr($n & 255).chr(($n >> 8) & 255);
	}
}

?>
