<?php
ini_set('memory_limit', '128M');
set_time_limit(600);

class imagesModel
{
	function dataImage($filename)
	{
		$imagedata = getimagesize($filename);
		return array('width' => $imagedata[0], 'height' => $imagedata[1], 'format' => $imagedata[2] == 1 ? 'gif' : ($imagedata[2] == 2 ? 'jpg' : ($imagedata[2] == 3 ? 'png' : false)));
	}
	
	function createImageResource($filename)
	{
		$imagedata = @imagesModel::dataImage($filename);
		if(!empty($imagedata))
		{
			$format = $imagedata['format'];
			switch($format)
			{
				case 'gif': $image = imagecreatefromgif($filename); break;
				case 'png': $image = imagecreatefrompng($filename); imagesavealpha($image, 1); break;
				case 'jpg': $image = imagecreatefromjpeg($filename); break;
			}
		}
		
		if($image)
		{
			$resource['data'] = $imagedata;
			$resource['image'] = $image;
			return $resource;
		}
		
		return false;
	}
	
	function outputImage($res_image, $format, $dest_filename = false)
	{
		imageinterlace($res_image, 1);
		
		switch($format)
		{
			case 'gif': 
						if($dest_filename)
						{
							@unlink($dest_filename);
							return imagegif($res_image, $dest_filename);
						}	
						else 
						{
							header("Content-type: image/gif");
							return imagegif($res_image);
						}
			case 'png': 
						if($dest_filename)
						{
							@unlink($dest_filename);
							return imagepng($res_image, $dest_filename);
						}	
						else 
						{
							header("Content-type: image/png");
							return imagepng($res_image);
						}			
						
			case 'jpg': 
						if($dest_filename)
						{
							@unlink($dest_filename);
							return imagejpeg($res_image, $dest_filename, 100);
						}
						else
						{
							header("Content-type: image/jpeg");
							return imagejpeg($res_image);
						}
		}
		
		return false;
	}
	
	function drawFillRectangle($input_filename, $x1, $y1, $x2, $y2, $dest_filename = false)
	{
		$resource = imagesModel::createImageResource($input_filename);
		$image = $resource['image'];
		$format = $resource['data']['format'];
		
		if(imagefilledrectangle($image, $x1, $y1, $x2, $y2, imagecolorallocatealpha($image, 0, 0, 0, 0)) && imagesModel::outputImage($image, $format, $dest_filename))
		{
			imagedestroy($image);
			return true;	
		}
		
		return false;		
	}
	
	function drawWaterMark($input_filename, $input_watermark, $opacity, $dest_filename = false)
	{
		$resource = imagesModel::createImageResource($input_filename);
		$image = $resource['image'];
		$imagedata = $resource['data'];
		
		$w_resource = imagesModel::createImageResource($input_watermark);
		$w_image = $w_resource['image'];
		$w_imagedata = $w_resource['data'];
		
		if($image && $w_image)
		{
			//if($imagedata['format'] != 'png')
			{
				if($imagedata['width'] > $w_imagedata['width'] || $imagedata['height'] > $w_imagedata['height'])
				{
					$x = $y = 0;
					while($x < $imagedata['width'] && $y < $imagedata['height'])
					{
						imagecopymerge($image, $w_image, $x, $y, 0, 0, $w_imagedata['width'], $w_imagedata['height'], $opacity);
						
						$x += $w_imagedata['width'];
						if($x >= $imagedata['width'])
						{
							$x = 0;
							$y += $w_imagedata['height'];
						}
					}
				}
				else
					imagecopymerge($image, $w_image, 0, 0, 0, 0, $w_imagedata['width'], $w_imagedata['height'], $opacity);
			}
				
			if(imagesModel::outputImage($image, $imagedata['format'], $dest_filename))
			{
				imagedestroy($image);
				imagedestroy($w_image);
				return true;
			}
		}
  		
		return false;
	}
	
	function createResizeImage($image, $format, $w_n, $h_n, $width, $height)
	{
		if(!($res_image = @imagecreatetruecolor($w_n, $h_n))) 
			return false;
		
		imagesavealpha($res_image, 1); // for png
		imagealphablending($res_image, 0);
		
		$white_color = imagecolorallocatealpha($res_image, 255, 255, 255, 127);
		imagefilledrectangle($res_image, 0, 0, $w_n, $h_n, $white_color);
		
	 	imagecopyresampled($res_image, $image, 0, 0, 0, 0, $w_n, $h_n, $width, $height);
	 	
	 	if($format == 'gif')
	 	{
		 	$image_color_transparent = imagecolortransparent($res_image);
			if ($image_color_transparent == -1)
			{
				if ($img_alpha_mixdown_dither = @imagecreatetruecolor($w_n, $h_n)) 
				{
					for($i=0; $i<=255; $i++)
						$dither_color[$i] = imagecolorallocate($img_alpha_mixdown_dither, $i, $i, $i);
		
					// scan through current truecolor image copy alpha channel to temp image as grayscale
					for ($x=0; $x<$w_n; $x++) 
						for ($y=0; $y<$h_n; $y++) 
						{
							$pixel_color = @imagecolorsforindex($res_image, @imagecolorat($res_image, $x, $y));
							imagesetpixel($img_alpha_mixdown_dither, $x, $y, $dither_color[($pixel_color['alpha'] * 2)]);
						}
		
					// dither alpha channel grayscale version down to 2 colors
					imagetruecolortopalette($img_alpha_mixdown_dither, true, 2);
		
					// reduce color palette to 256-1 colors (leave one palette position for transparent color)
					imagetruecolortopalette($res_image, true, 255);
		
					// allocate a new color for transparent color index
					$transparent_color = imagecolorallocate($res_image, 1, 254, 253);
					imagecolortransparent($res_image, $transparent_color);
		
					// scan through alpha channel image and note pixels with >50% transparency
					for($x=0; $x < $w_n; $x++) 
						for($y=0; $y < $h_n; $y++) 
						{
							$alpha_channel_pixel = @imagecolorsforindex($img_alpha_mixdown_dither, @imagecolorat($img_alpha_mixdown_dither, $x, $y));
							if ($alpha_channel_pixel['red'] > 127) 
								imagesetpixel($res_image, $x, $y, $transparent_color);
						}
					imagedestroy($img_alpha_mixdown_dither);
				}
			}
	 	}
	 	
		return $res_image;
	}
	
	function fitImageFromInside($input_filename, $w, $h, $dest_filename = false)
	{
		$imagedata = @imagesModel::dataImage($input_filename);
		
		if(!empty($imagedata))
		{
			$width = $imagedata['width'];
			$height = $imagedata['height'];
			$format = $imagedata['format'];
			
			switch ($format)
			{
				case 'gif': $image  = imagecreatefromgif($input_filename);  break;
				case 'png': $image  = imagecreatefrompng($input_filename);  break;
				case 'jpg': $image  = imagecreatefromjpeg($input_filename); break;
			}
			
			if(!$image) return false;
			
			if($width <= $w && $height <= $h) 
			{
				$w_n = $width;
				$h_n = $height;
			}
			else 
			{
				if($h > $w)
				{
					if($height > $width) 
					 {
						if($height/$width*$w>$h)
						{
							$w_n = $width/$height*$h;
							$h_n = $h;
						}
						else 
						{
							$h_n = $height/$width*$w;
							$w_n = $w;
						}
					 }
					else 
					{
						$h_n = $height/$width*$w;
						$w_n = $w;
					}
				}
				else 
				{
					if($height > $width)
					{
						$w_n = $width/$height*$h;
						$h_n = $h;
					}
					else
					{
						if($width/$height*$h>$w)
						{
							$h_n = $height/$width*$w;
							$w_n = $w;
						}
						else 
						{
							$w_n = $width/$height*$h;
							$h_n = $h;
						} 
					}
				}  
			}
			
			$res_image = imagesModel::createResizeImage($image, $format, $w_n, $h_n, $width, $height);
			imagedestroy($image);
			
			if($res_image)
			{
				imagesModel::outputImage($res_image, $format, $dest_filename);
				imagedestroy($res_image);
				return true;
			}	
		}
		
		return false;
	}
	
	function fitImageFromOutside($input_filename, $w, $h, $dest_filename = false)
	{
		$imagedata = @imagesModel::dataImage($input_filename);
		
		if(!empty($imagedata))
		{
			$width = $imagedata['width'];
			$height = $imagedata['height'];
			$format = $imagedata['format'];
			
			switch ($format)
			{
				case 'gif': $image  = imagecreatefromgif($input_filename);  break;
				case 'png': $image  = imagecreatefrompng($input_filename);  break;
				case 'jpg': $image  = imagecreatefromjpeg($input_filename); break;
			}
			
			if(!$image) return false;
			
			/*if($height < $width) 
		 		$width = $w/$h*$height;
			else 
			 	$height = $h/$w*$width;*/
			
			if($height < $width)
			{
			 	$width = $w/$h*$height > $width ? $width : $w/$h*$height;
			 	//$height = $h;
			}
			else 
			{
			 	$height = $h/$w*$width > $height ? $height : $h/$w*$width;
			 	//$width = $w;
			}
			
			//$res_image = imagesModel::createResizeImage($image, $format, $w_n, $h_n, $width, $height);
			$res_image = imagesModel::createResizeImage($image, $format, $w, $h, $width, $height);
			imagedestroy($image);
			
			if($res_image)
			{
				imagesModel::outputImage($res_image, $format, $dest_filename);
				imagedestroy($res_image);
				return true;
			}	
		}
		
		return false;
	}
}