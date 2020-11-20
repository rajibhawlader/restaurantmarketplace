<body bgcolor=<?php echo $color_background;?>>
<?php
for($l=0;$l<20;$l++){
     	   // load the buffer 
 for ($i=0; $i<300; $i++) print ("  "); 
  print ("\n");
     	   ?>
     	   <!-- BUFFER -->
     	 <?php
     	      	   flush(); 
           if (function_exists('ob_flush')) {
           if(ob_get_contents()) ob_flush(); }  
     	 ?>  
     	 test<br><bR>
       	   <!-- BUFFER -->
     	   <?php
     	   // load the buffer 
for ($i=0; $i<300; $i++) print ("  "); 
print ("\n");
          ?>
           <!-- BUFFER -->
          <?php
     	   flush(); 
           if (function_exists('ob_flush')) {
           if(ob_get_contents()) ob_flush(); } 
sleep(2);
} 
?>