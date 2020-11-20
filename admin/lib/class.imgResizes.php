<?php
function Image_resizes($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,$i,$postids)
{
     
	 $size = 120; // the thumbnail width
     $filedir = '../companylogo/'; // the directory for the original image
	 $thumbdir = '../companylogo/'; // the directory for the thumbnail image
     $prefix = 'small_'.$postids.'_'; // the prefix to be added to the original name
     $maxfile = '10000000';
     $mode = '0666';
	 
	 $pos = strrpos($userfile_name, ".");
	 //echo $pos;
	 
	 $image_type=strtolower(substr($userfile_name,$pos + 1));  
     if (!$userfile_name=="")
     {
         //$prod_imgs = strtolower($filedir.$postids.'_'.$userfile_name);
		 $prefixL = 'large_'.$postids.'_';
		 $prod_imgs = strtolower($thumbdir.$prefixL.$userfile_name);

       	$prod_img_thumbs = strtolower($thumbdir.$prefix.$userfile_name);
		//echo 'uploaded small pic='.$prod_img_thumbs;
         move_uploaded_file($userfile_tmp, $prod_imgs);
         chmod ($prod_imgs, octdec($mode));
         
         $sizes = getimagesize($prod_imgs);
		 $cheching_width = $sizes[0];
         $cheching_height = $sizes[1];

         $aspect_ratio = $sizes[1]/$sizes[0];

         if ($sizes[0] <= $size)
         {
             $new_width = $sizes[0];
             $new_height = $sizes[1];
         }
		 else
		 {
             $new_width = $size;
			 $new_height = abs($new_width*$aspect_ratio);
         }

         $destimg=ImageCreateTrueColor($new_width,$new_height) or die('Problem In Creating image');
		 if($image_type=="jpg" || $image_type=="jpeg"){
		   	$srcimg=ImageCreateFromJPEG($prod_imgs) or die('Problem In opening Source Image');
		 }elseif($image_type=="gif"){
		 	$srcimg=ImageCreateFromGIF($prod_imgs) or die('Problem In opening Source Image');
		 }elseif($image_type=="png"){
		 	$srcimg=ImageCreateFromPNG($prod_imgs) or die('Problem In opening Source Image');
		 }else{
		 	$srcimg="";
		 }
		 //echo $srcimg;
		 if(!$srcimg == ""){
         	ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die('Problem In resizing');
		 	if($image_type=="jpg" || $image_type=="jpeg"){
	        	 ImageJPEG($destimg,$prod_img_thumbs,90) or die('Problem In saving');
		 	}elseif($image_type=="gif"){
			 	ImageGIF($destimg,$prod_img_thumbs,90) or die('Problem In saving');
		 	}elseif($image_type=="png"){
				 ImagePNG($destimg,$prod_img_thumbs,90) or die('Problem In saving');
		 	}
    	
	     }
		
		//if ($sizes[0]>520 || $sizes[1]>300){
		  /*
				  if ($sizes[1]>300 && $sizes[1]>$sizes[0]){
					 $aspect_ratio = $sizes[0]/$sizes[1];
					 $new_height = 300;
					 $new_width = abs($new_height*$aspect_ratio);
				  }else{
						   if ($sizes[0]>520){
							 $new_width = 520;
							 $new_height = abs($new_width*$aspect_ratio);
						  }else{
							$new_width = $cheching_width;
							
							$new_height = $cheching_height;
							
						  }
				  }
			  
			 */ 
			  
				/*
				$prefix = 'large_'.$postids.'_';
				$prod_imgs_large=strtolower($thumbdir.$prefix.$userfile_name);
				//echo ',uploaded large pic='.$prod_imgs_large;
			 
				 $destimg=ImageCreateTrueColor($new_width,$new_height) or die('Problem In Creating image');
				 if($image_type=="jpg" || $image_type=="jpeg"){
					$srcimg=ImageCreateFromJPEG($prod_imgs) or die('Problem In opening Source Image');
				 }elseif($image_type=="gif"){
					$srcimg=ImageCreateFromGIF($prod_imgs) or die('Problem In opening Source Image');
				 }elseif($image_type=="png"){
					$srcimg=ImageCreateFromPNG($prod_imgs) or die('Problem In opening Source Image');
				 
				 }else{
					$srcimg="";
				 }
				 
				 if(!$srcimg == ""){
					ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die('Problem In resizing');
							if($image_type=="jpg" || $image_type=="jpeg"){
								 ImageJPEG($destimg,$prod_imgs_large,100) or die('Problem In saving');
							}elseif($image_type=="gif"){
								ImageGIF($destimg,$prod_imgs_large,100) or die('Problem In saving');
							}elseif($image_type=="png"){
								 ImagePNG($destimg,$prod_imgs_large,100) or die('Problem In saving');
							}
				
				 }
		//}
			
		
		
         unlink($prod_imgs);
		 imagedestroy($destimg);*/
     }
	
}

?>
