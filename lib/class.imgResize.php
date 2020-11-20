<?php
 
function Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,$i,$postid)
{
    
	 $size = 120; // the thumbnail width
	 $size1=200;
	
     $$filedir = 'product_image/'; // the directory for the original image
	 $thumbdir = 'product_image/'; // the directory for the thumbnail image
     $prefix = 'small_'.$postid.'_'; // the prefix to be added to the original name
     $maxfile = '2048000';
     $mode = '0666';
	 
	 $pos = strrpos($userfile_name, ".");
	 //echo $pos;
	 
	 $image_type=strtolower(substr($userfile_name,$pos + 1));  
     if ($userfile_name!="")
     {
         //$prod_img = strtolower($filedir.$postid.'_'.$userfile_name);
		 $prefixL = 'large_'.$postid.'_';
		 $prod_img = strtolower($thumbdir.$prefixL.$userfile_name);

       	$prod_img_thumb = strtolower($thumbdir.$prefix.$userfile_name);
		$prod_img_thumb1 = strtolower($thumbdir.'mid_'.$postid.'_'.$userfile_name);
		
		//echo 'uploaded small pic='.$prod_img_thumb;
         move_uploaded_file($userfile_tmp, $prod_img);
         chmod ($prod_img, octdec($mode));
         
         $sizes = getimagesize($prod_img);
		 $cheching_width = $sizes[0];
         $cheching_height = $sizes[1];

          if($cheching_width > $cheching_height)
		  {
		  	$aspect_ratio = $sizes[1]/$sizes[0];
		  }
		  else
		  {
		  	$aspect_ratio = $sizes[0]/$sizes[1];
		  }

         if ($sizes[0] <= $size and $sizes[1] <= $size )
         {
             $new_width = $sizes[0];
             $new_height = $sizes[1];
         }
		 else
		 {
             if($cheching_width > $cheching_height)
			 {
			 $new_width = $size;
			 $new_height = abs($new_width*$aspect_ratio);
			 }
			 else
			 {
			 $new_height = $size;
			 $new_width = abs($new_height*$aspect_ratio);
			 }
         }

         $destimg=imagecreatetruecolor($new_width,$new_height) or die('Problem In Creating image');
		 if($image_type=="jpg" || $image_type=="jpeg"){
		   	$srcimg=imagecreatefromjpeg($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="gif"){
		 	$srcimg=imagecreatefromgif($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="png"){
		 	$srcimg=imagecreatefrompng($prod_img) or die('Problem In opening Source Image');
		 }else{
		 	$srcimg="";
		 }
		 //echo $srcimg;
		 if(!$srcimg == ""){
         	ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die('Problem In resizing');
		 	if($image_type=="jpg" || $image_type=="jpeg"){
	        	 ImageJPEG($destimg,$prod_img_thumb,90) or die('Problem In saving');
		 	}elseif($image_type=="gif"){
			 	ImageGIF($destimg,$prod_img_thumb,90) or die('Problem In saving');
		 	}elseif($image_type=="png"){
				 ImagePNG($destimg,$prod_img_thumb,90) or die('Problem In saving');
		 	}
    	
	     }
		 
		 $sizes1 = getimagesize($prod_img);
		 $cheching_width1 = $sizes1[0];
         $cheching_height1 = $sizes1[1];


		// $aspect_ratio1 = $sizes1[1]/$sizes1[0];

         if ($sizes1[0] <= $size1 and $sizes1[1] <= $size1)
         {
             $new_width1 = $sizes1[0];
             $new_height1 = $sizes1[1];
         }
		 else
		 {
	       if($cheching_width1 > $cheching_height1)
			 {
			 $new_width1 = $size1;
			 $new_height1 = abs($new_width1*$aspect_ratio);
			 }
			 else
			 {
			 $new_height1 = $size1;
			 $new_width1 = abs($new_height1*$aspect_ratio);
			 }
         }

         $destimg1=ImageCreateTrueColor($new_width1,$new_height1) or die('Problem In Creating image');
		 if($image_type=="jpg" || $image_type=="jpeg"){
		   	$srcimg1=ImageCreateFromJPEG($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="gif"){
		 	$srcimg1=ImageCreateFromGIF($prod_img) or die('Problem In opening Source Image');
		 }elseif($image_type=="png"){
		 	$srcimg1=ImageCreateFromPNG($prod_img) or die('Problem In opening Source Image');
		 }else{
		 	$srcimg1="";
		 }
		 //echo $srcimg1;
		 if(!$srcimg1 == ""){
         	ImageCopyResized($destimg1,$srcimg1,0,0,0,0,$new_width1,$new_height1,ImageSX($srcimg1),ImageSY($srcimg1)) or die('Problem In resizing');
		 	if($image_type=="jpg" || $image_type=="jpeg"){
	        	 ImageJPEG($destimg1,$prod_img_thumb1,90) or die('Problem In saving');
		 	}elseif($image_type=="gif"){
			 	ImageGIF($destimg1,$prod_img_thumb1,90) or die('Problem In saving');
		 	}elseif($image_type=="png"){
				 ImagePNG($destimg1,$prod_img_thumb1,90) or die('Problem In saving');
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
				$prefix = 'large_'.$postid.'_';
				$prod_img_large=strtolower($thumbdir.$prefix.$userfile_name);
				//echo ',uploaded large pic='.$prod_img_large;
			 
				 $destimg=ImageCreateTrueColor($new_width,$new_height) or die('Problem In Creating image');
				 if($image_type=="jpg" || $image_type=="jpeg"){
					$srcimg=ImageCreateFromJPEG($prod_img) or die('Problem In opening Source Image');
				 }elseif($image_type=="gif"){
					$srcimg=ImageCreateFromGIF($prod_img) or die('Problem In opening Source Image');
				 }elseif($image_type=="png"){
					$srcimg=ImageCreateFromPNG($prod_img) or die('Problem In opening Source Image');
				 
				 }else{
					$srcimg="";
				 }
				 
				 if(!$srcimg == ""){
					ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die('Problem In resizing');
							if($image_type=="jpg" || $image_type=="jpeg"){
								 ImageJPEG($destimg,$prod_img_large,100) or die('Problem In saving');
							}elseif($image_type=="gif"){
								ImageGIF($destimg,$prod_img_large,100) or die('Problem In saving');
							}elseif($image_type=="png"){
								 ImagePNG($destimg,$prod_img_large,100) or die('Problem In saving');
							}
				
				 }
		//}
			
		
		
         unlink($prod_img);
		 imagedestroy($destimg);*/
		 
		 
     }
	
}

?>
