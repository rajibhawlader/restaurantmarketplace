<?php
require_once("visitor_common.php");
?>
<h2>Click on the image you want to use:</h2>
<SCRIPT type="text/javascript">
  function getit(filename){ 
  	opener.self.document.chatter.comment.value = opener.self.document.chatter.comment.value + "[SMILE]" + filename + "[/SMILE]";
        window.close();
   }
  
  function getit_ascii(code){ 
  	opener.self.document.chatter.comment.value = opener.self.document.chatter.comment.value + code;
        window.close();
   }
   
</SCRIPT>
<?php
 $dir = "chat_smiles" . C_DIR;
 $query = "SELECT * FROM livehelp_smilies";
   $rs = $mydatabase->query($query);
   $conversions = array(); 
   while($row = $rs->fetchRow(DB_FETCHMODE_ASSOC)){
     $key = $row['smile_url'];
     $val = $row['code'];
     $conversions[$key] = $val;
   }
   	
$i = 0;
print "<table width=450 cellpadding=0 cellspacing=0>";
if($handle=opendir($dir)){
	while (false !== ($file = readdir($handle))) {
			if ($file!="." && $file!=".."){
			  if (is_file("$dir".C_DIR."$file")){
         if(($i % 5) == 0){ print "</tr><tr>"; }
         print "<td valign=top>";
				 print "<table width=50 cellpadding=0 cellspacing=0 border=0><tr><td>";
				 if(isset($conversions[$file]))
  				 $url = "<a href=javascript:getit_ascii('". $conversions[$file] . "')>";  	     
  	     else
  				 $url = "<a href=javascript:getit('$file')>";
				 
				 print "$url<img src=chat_smiles/$file border=0></a></td></tr><tr><td>$url";
 
				 if(isset($conversions[$file]))
				  print $conversions[$file];
				 else
				  print "USE";
				 print "</a></td></tr></table>";
         print "</td>";
  			}
  		}
   $i++;		
  }
 } else {
 	 $query = "SELECT * FROM livehelp_smilies";
   $rs = $mydatabase->query($query);
   $conversions = array(); 
   while($row = $rs->fetchRow(DB_FETCHMODE_ASSOC)){
   	 $i++;		
     $key = $row['smile_url'];
     $val = $row['code'];
      if(($i % 5) == 0){ print "</tr><tr>"; }
         print "<td valign=top>";
				 print "<table width=50 cellpadding=0 cellspacing=0 border=0><tr><td>";
  			 $url = "<a href=javascript:getit_ascii('". $row['code'] . "')>";				 
				 print "$url<img src=chat_smiles/".$row['smile_url']." border=0></a></td></tr><tr><td>$url";
 				 print $conversions[$file]; 
				 print "</a></td></tr></table>";
         print "</td>";
   }
 } 
print "</table>";
  
if(!(empty($note))){
   print "<font color=000077><br>To change these Images simply add/remove the images located in the directory chat_smiles</font>";	
}
  ?>