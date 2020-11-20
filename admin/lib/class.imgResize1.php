<?php
function Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,$i,$postid)
{
     
	 $size = 64; // the thumbnail height
	 $size1 = 478;
     $filedir = '../propertyimages/'; // the directory for the original image
	 $thumbdir = '../propertyimages/'; // the directory for the thumbnail image
     $prefix = 'small_'.$postid.'_'; // the prefix to be added to the original name
     $maxfile = '10000000';
     $mode = '0666';
	 
	 $pos = strrpos($userfile_name, ".");
	 //echo $pos;
	 
	 $image_type=strtolower(substr($userfile_name,$pos + 1));  
     if (isset($userfile_name))
     {
         $prod_img = strtolower($filedir.'large_'.$postid.'_'.$userfile_name);

         $prod_img_thumb = strtolower($thumbdir.$prefix.$userfile_name);
		 $prod_img_thumb1 = strtolower($thumbdir.'mid_'.$postid.'_'.$userfile_name);
         move_uploaded_file($userfile_tmp, $prod_img);
         chmod ($prod_img, octdec($mode));
         
         $sizes = getimagesize($prod_img);

         $aspect_ratio = $sizes[0]/$sizes[1];

         if ($sizes[1] <= $size)
         {
             $new_width = $sizes[0];
             $new_height = $sizes[1];
         }else{
            
             $new_width = $size;
			  $new_height = abs($new_width/$aspect_ratio);
         }

         $destimg=ImageCreateTrueColor($new_width,$new_height) or die('Problem In Creating image');
		 if($image_type=="jpg" || $image_type=="jpeg"){
		   	$srcimg=ImageCreateFromJPEG($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="gif"){
		 	$srcimg=ImageCreateFromGIF($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="png"){
		 	$srcimg=ImageCreateFromPNG($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="bmp"){
		 	$srcimg=ImageCreateFromBMP($prod_img) or die('Problem In opening Source Image');
		 }else{
		 	$srcimg="";
		 }
		 //echo $srcimg;
		 if(!$srcimg == ""){
         	ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die('Problem In resizing');
		 	if($image_type=="jpg" || $image_type=="jpeg"){
	        	 ImageJPEG($destimg,$prod_img_thumb,100) or die('Problem In saving');
		 	}elseif($image_type=="gif"){
			 	ImageGIF($destimg,$prod_img_thumb,100) or die('Problem In saving');
		 	}elseif($image_type=="png"){
				 ImagePNG($destimg,$prod_img_thumb,100) or die('Problem In saving');
		 	}elseif($image_type=="bmp"){
				 ImageBMP($destimg,$prod_img_thumb,100) or die('Problem In saving');
		 	}
    	
	     }
         imagedestroy($destimg);
		 
		 $size1s = getimagesize($prod_img);

         $aspect_ratio1 = $size1s[0]/$size1s[1];

         if ($size1s[1] <= $size1)
         {
             $new_width1 = $size1s[0];
             $new_height1 = $size1s[1];
         }else{
            
             $new_width1 = $size1;
			  $new_height1 = abs($new_width1/$aspect_ratio1);
         }

         $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die('Problem In Creating image');
		 if($image_type=="jpg" || $image_type=="jpeg"){
		   	$srcimg1=ImageCreateFromJPEG($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="gif"){
		 	$srcimg1=ImageCreateFromGIF($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="png"){
		 	$srcimg1=ImageCreateFromPNG($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="bmp"){
		 	$srcimg1=ImageCreateFromBMP($prod_img) or die('Problem In opening Source Image');
		 }else{
		 	$srcimg1="";
		 }
		 //echo $srcimg1;
		 if(!$srcimg1 == ""){
         	ImageCopyResized($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die('Problem In resizing');
		 	if($image_type=="jpg" || $image_type=="jpeg"){
	        	 ImageJPEG($destimg1,$prod_img_thumb1,100) or die('Problem In saving');
		 	}elseif($image_type=="gif"){
			 	ImageGIF($destimg1,$prod_img_thumb1,100) or die('Problem In saving');
		 	}elseif($image_type=="png"){
				 ImagePNG($destimg1,$prod_img_thumb1,100) or die('Problem In saving');
		 	}elseif($image_type=="bmp"){
				 ImageBMP($destimg1,$prod_img_thumb1,100) or die('Problem In saving');
		 	}
    	
	     }
         imagedestroy($destimg1);
     }
	
}

?>
