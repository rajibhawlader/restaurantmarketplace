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

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];
if($isadminsetting != "Y"){
 print $lang['txt41'];
if(!($serversession))
  $mydatabase->close_connect();
 exit;	
}

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");

if(!(isset($UNTRUSTED['whattodo']))){ $UNTRUSTED['whattodo']=""; }
 



if($UNTRUSTED['whattodo'] == "update"){
    
    //Delete old values:
    $query = "TRUNCATE TABLE `livehelp_smilies`";
    $mydatabase->query($query);
        
    // go though post vars 
    reset($_POST);
    while (list($key, $val) = each($_POST)) {
    	 $arraysl = split("_",$key);
    
    // if the code is not empty and this is a smile then insert it..
    if( ($arraysl[0]=="smile") && ($val != "")){
       $imgsrc = $arraysl[2];
    	 $index = 3;    	 
    	 while(!(empty($arraysl[$index]))){  	
    	   $imgsrc .= "_" . $arraysl[$index];
    	   $index++;
    	 }
    	 
      $imgsrc = str_replace("^",".",$imgsrc);
      $query = "INSERT INTO livehelp_smilies (code,smile_url) VALUES ('".filter_sql($val)."','".filter_sql($imgsrc)."')";
      $mydatabase->query($query);
     }     
    }
    
    print "<font color=007700 size=+2>" . $lang['txt63'] . "</font>";  
   
}
?>
<body bgcolor=<?php echo $color_background;?> ><center>
<table width=500 bgcolor=<?php echo $color_background;?>><tr><td>
<?php echo $lang['txt102']; ?>
</td></tr></table>
<form action="edit_smile.php" method=POST>
<input type=hidden name=whattodo value=update>

<?php
 $dir = "chat_smiles" . C_DIR;
 $query = "SELECT * FROM livehelp_smilies";
   $rs = $mydatabase->query($query);
   $conversions = array(); 
   while($row = $rs->fetchRow(DB_FETCHMODE_ASSOC)){
     $key = $row['smile_url'];
     $val = $row['code'];
     $conversions[$key] = $row;
   }
   	

  $i = 0;
  print "<table cellpadding=0 cellspacing=0 border=0>";
  if($handle=opendir($dir)){
   while (false !== ($file = readdir($handle))) {
			if ($file!="." && $file!=".."){
			  if (is_file("$dir".C_DIR."$file")){  
				 print "<tr><td> Image: </td>\n";
				 print "<td> <img src=chat_smiles/$file border=0></td>\n";
				 print "<td>Ascii:</td>\n";
				 if(isset($conversions[$file])){
				 	    $idof = $conversions[$file]['smilies_id'];
				 	    $value = $conversions[$file]['code']; 
				 } else { 
  				 	  $idof = "0";
				 	    $value = "";
				
				}
				 $file = str_replace(".","^",$file);
				 print "<td><input type=text name=\"smile_" . $idof . "_$file\" value=\"" . $value . "\"> </td>\n";				 
				 print "</tr>\n\n ";  
  			}
  		}
   $i++;		
  }
 } 
 print "</table>";
 print "<input type=submit value=\"" . $lang['UPDATE'] . "\"></form>";
 
 print "<font color=000077><br>To change these Images simply add/remove the images located in the directory chat_smiles</font>";	
if(!($serversession))  
  $mydatabase->close_connect();
?></td></tr></table>
<pre>


</pre>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
<a href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help</a> &copy; 2003 - 2008 by <a href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>  
</font>