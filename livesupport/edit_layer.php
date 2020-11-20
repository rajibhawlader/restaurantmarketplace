<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: livehelp@craftysyntax.com
//         Copyright (C) 2003-2008 Eric Gerdes   (http://www.craftysyntax.com )
// ----------------------------------------------------------------------------
// Please check http://www.craftysyntax.com/ or REGISTER your program for updates
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is Crafty Syntax Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
//===========================================================================
require_once("admin_common.php");
validate_session($identity);

?> 
<SCRIPT type="text/javascript">
ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
cscontrol= new Image;
var flag_imtyping = false;

 
</SCRIPT>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=E0E8F0 onload=window.focus()>
<table width=500 bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt161']; ?>
</td></tr></table>
<?php 
  // where the layer invites live:
  $dir = "layer_invites" . C_DIR;
  
  // look for deleted layers and update exisiting layers:
  $querysql = "SELECT imagename,layerid from livehelp_layerinvites";
  $rs = $mydatabase->query($querysql);
  while($row = $rs->fetchRow(DB_FETCHMODE_ASSOC)){  
  	$file = $row['imagename'];
  	$layerid = $row['layerid'];  	
  	if(!(is_file("$dir".C_DIR."$file"))){
  		print "REMOVED $dir/$file<br>";
  		$querysql = "DELETE FROM livehelp_layerinvites WHERE layerid=$layerid";
  		$mydatabase->query($querysql);
  		$querysql = "DELETE FROM livehelp_autoinvite WHERE message='$layerid'";
  		$mydatabase->query($querysql);  		
  	}
  }
 
  // open up layer directory and get list of layer invites:
  $dir = "layer_invites" . C_DIR; 
  $i = 0;
  $count =101;
  if($handle=opendir($dir)){
   while (false !== ($file = readdir($handle))) {
		if ($file!="." && $file!=".." && eregi("layer-",$file) ){
			$imageurl = $file;
			if ( (is_file("$dir".C_DIR."$file")) && (!(eregi(".txt",$file))) ){
				 $parts = explode(".",$file);
				 $file = $parts[0];			 				 				 
				 $file = str_replace("layer-","",$file);	
				 $fullfile = "layer_invites".C_DIR."layer-". $file . ".txt";
				 $imagemapof = file_get_contents($fullfile);
				 $imagemap = addslashes($imagemapof);
				 	  
				 // see if we have this invite in the database if not 
				 // add it .
				 $querysql = "SELECT layerid from livehelp_layerinvites WHERE  imagename='$imageurl'";
         $rs = $mydatabase->query($querysql);
				 if($rs->numrows() == 0){
				 	  $layerid = find_puka();
				 	  $sqlquery = "INSERT INTO livehelp_layerinvites (layerid,imagename,imagemap) VALUES ($layerid,'$imageurl','$imagemap')";
				 	  $mydatabase->query($sqlquery);
				  } else {
				  	$row = $rs->fetchRow(DB_FETCHMODE_ASSOC);
				  	$layerid = $row['layerid'];
				    $sqlquery = "UPDATE livehelp_layerinvites set imagemap='$imagemap' WHERE layerid=$layerid ";
				 	  $mydatabase->query($sqlquery);				 	   
				  }			 
				  $count++;
			  }
			}
		}
	} else {
		print "Your server Disallows this feature";
	}

$querysql = "SELECT * from livehelp_layerinvites";
$rs = $mydatabase->query($querysql);
while($row = $rs->fetchRow(DB_FETCHMODE_ASSOC)){
	   print "loaded:<img src=layer_invites/".$row['imagename'] . "><br>";
}
         
?>		
 
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>