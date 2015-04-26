<?php
	if (isset($_GET['url'])) {
		header('Content-Type: image/png');
		$image = new SimpleImage();
		$image->load($_GET['url']);
      if (isset($_GET['pos'])) {
         $pos = explode(',', $_GET['pos']);
         if (count($pos) == 2) {
            $x = $pos[0];
            $y = $pos[1];
         } elseif (count($pos) == 1) {
            $x = $pos[0];
            $y = 0;
         } else {
            $x = 0;
            $y = 0;
         }
         $image->position($x, $y);
      }
      if (isset($_GET['crop'])) {
         $crop = explode('x', $_GET['crop']);
         if (count($crop) == 2) {
            $cropWidth = $crop[0];
            $cropHeight = $crop[1];
         } elseif (count($crop) == 1) {
            $cropWidth = $crop[0];
            $cropHeight = $crop[0];
         } else {
            $cropWidth = $image->getWidth();
            $cropHeight = $image->getHeight();
         }
         $image->crop($cropWidth, $cropHeight);
      }
      if (isset($_GET['size'])) {
         $size = explode('x', $_GET['size']);
         if (count($size) == 2) {
            $width = $size[0];
            $height = $size[1];
         } elseif (count($size) == 1) {
            $width = $size[0];
            $height = $size[0];
         } else {
            $width = $image->getWidth();
            $height = $image->getHeight();
         }
      } else {
         $width = $image->getWidth();
         $height = $image->getHeight();
      }
      $image->resize($width, $height);
		$image->output(IMAGETYPE_PNG);
	}
 
/*
* File: SimpleImage.php
* Author: Simon Jarvis
* Copyright: 2006 Simon Jarvis
* Date: 08/11/06
* Link: http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/
 
class SimpleImage {
 
   var $image;
   var $image_type;
 
   function load($filename) {
 
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
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename,-1);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   function getWidth() {
 
      return imagesx($this->image);
   }
   function getHeight() {
 
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getHeight() * $ratio;
      $this->resize($width,$height);
   }
 
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getHeight() * $scale/100;
      $this->resize($width,$height);
   }

   function position($x,$y) {
      $this->resample($this->getWidth(),$this->getHeight(),$this->getWidth(),$this->getHeight(),$x,$y);
   }

   function crop($width,$height) {
      $this->resample($this->getWidth(),$this->getHeight(),$width,$height,0,0);
   }

   function resize($width,$height) {
      $this->resample($width,$height,$this->getWidth(),$this->getHeight(),0,0);
   }
 
   function resample($width,$height,$cropWidth,$cropHeight,$x,$y) {
      $new_image = imagecreatetruecolor($width, $height);
      if( $this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG ) {
         $current_transparent = imagecolortransparent($this->image);
         /*if($current_transparent != -1) {
            $transparent_color = imagecolorsforindex($this->image, $current_transparent);
            $current_transparent = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
            imagefill($new_image, 0, 0, $current_transparent);
            imagecolortransparent($new_image, $current_transparent);
         } elseif( $this->image_type == IMAGETYPE_PNG) {*/
            imagealphablending($new_image, false);
            $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $color);
            imagesavealpha($new_image, true);
         //}
      }
      imagecopyresampled($new_image, $this->image, 0, 0, $x, $y, $width, $height, $cropWidth, $cropHeight);
      $this->image = $new_image; 
   }
 
}
?>