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
 

  // open up layer directory and get list of layer invites:
 $dir = "layer_invites".C_DIR;   
   
  $handle=opendir($dir);
  $i = 0;
  $count =101;
  if($handle){
   while (false !== ($file = readdir($handle))) {	
		if ($file!="." && $file!=".." && eregi("layer-",$file) ){
			$imageurl = $file;
			if ( (is_file("$dir".C_DIR."$file")) && (!(eregi(".txt",$file))) ){
				 $parts = explode(".",$file);
				 $file = $parts[0];			 				 				 
				 $file = str_replace("layer-","",$file);	
				 // see if we have this invite in the database if not 
				 // add it .
				 $querysql = "SELECT layerid from livehelp_layerinvites WHERE  imagename='$imageurl'";
         $rs = $mydatabase->query($querysql);
				 if($rs->numrows() == 0){
				 	  $layerid = find_puka();
				 	  $fullfile = "layer_invites/layer-". $file . ".txt";
				 	  $imagemapof = file_get_contents($fullfile);
				 	  $imagemap = addslashes($imagemapof);
				 	  $sqlquery = "INSERT INTO livehelp_layerinvites (layerid,imagename,imagemap) VALUES ($layerid,'$imageurl','$imagemap')";
				 	  $mydatabase->query($sqlquery);
				  }			 
				  $count++;
			}
		}
	}
 }
  
$timeof = rightnowtime();
if(!(isset($UNTRUSTED['whattodo']))){  $UNTRUSTED['whattodo'] = ""; }


if(empty($myid)){
  // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
  $people = $mydatabase->query($query);
  $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = $people['user_id'];
  $channel = $people['onchannel'];
}

if($UNTRUSTED['whattodo'] == $lang['SAVE']){
 	$seconds = $UNTRUSTED['seconds'] + ($UNTRUSTED['minutes'] * 60);
 if(empty($UNTRUSTED['editidnum'])){
  $query = "INSERT INTO livehelp_autoinvite (isactive,department,message,page,visits,referer,typeof,seconds) VALUES ('Y',".intval($UNTRUSTED['department']).",'".filter_sql($UNTRUSTED['comment'])."','".filter_sql($UNTRUSTED['page'])."',". intval($UNTRUSTED['visits']).",'".filter_sql($UNTRUSTED['referer'])."','".filter_sql($UNTRUSTED['typeof'])."','$seconds')";
  $mydatabase->query($query);  	
 } else {
  $query = "UPDATE livehelp_autoinvite SET seconds='$seconds',typeof='".filter_sql($UNTRUSTED['typeof'])."',department=".intval($UNTRUSTED['department']).",message='".filter_sql($UNTRUSTED['comment'])."',page='".filter_sql($UNTRUSTED['page'])."',visits=".intval($UNTRUSTED['visits']).",referer='".filter_sql($UNTRUSTED['referer'])."' WHERE idnum=". intval($UNTRUSTED['editidnum']); 
  $mydatabase->query($query);   	
 }
}

if($UNTRUSTED['whattodo'] == $lang['UPDATE']){  
  $query = "SELECT * FROM livehelp_autoinvite";
  $data = $mydatabase->query($query);
  while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
    $varname = "isactive__" . $row['idnum']; 
    if(!(empty($UNTRUSTED[$varname]))) {  
        $query = "UPDATE livehelp_autoinvite set isactive='Y' WHERE idnum='".$row['idnum']."' ";
        $mydatabase->query($query);
    } else {
        $query = "UPDATE livehelp_autoinvite set isactive='N' WHERE idnum='".$row['idnum']."' ";
        $mydatabase->query($query);    	
    }
  }  
}

if($UNTRUSTED['whattodo'] == $lang['REMOVE']){
        $query = "DELETE FROM livehelp_autoinvite WHERE idnum=".intval($UNTRUSTED['which']);
        $mydatabase->query($query);  
}

?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=E0E8F0 onload=window.focus()>

<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td>
<b><?php echo $lang['txt5']; ?></b></td></tr></table>
<?php echo $lang['txt6']; ?>

<br>
<?php
if ( (empty($UNTRUSTED['createnew'])) && ($UNTRUSTED['whattodo'] != "EDIT") ){ 
?>
<b><?php echo $lang['txt7']; ?></b>
<form action=autoinvite.php method=post>
<table width=555>
<tr bgcolor=D4DCF2><td><b><?php echo $lang['active']; ?></b></td><td><b><?php echo $lang['invite']; ?></b></td><td><b><?php echo $lang['options']; ?></b></td></tr>
<?php
$query = "SELECT * FROM livehelp_autoinvite ";
$data = $mydatabase->query($query);
if($data->numrows() == 0){
  print "<tr><td colspan=3 bgcolor=FFFFFF align=center><font color=990000>" . $lang['txt8'] ." </font></td></tr>";  	
} else {
 $bgcolor="";
 while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
 
  if($bgcolor=="FEFEFE"){ $bgcolor="CDCDCD";  } else { $bgcolor="FEFEFE"; }
  if($row['isactive'] =="Y"){ $isactive = " CHECKED "; } else { $isactive = " "; }
  print "<tr bgcolor=$bgcolor><td><input type=checkbox name=isactive__".$row['idnum']." value=Y $isactive></td><td>";
  print "<table width=100% bgcolor=$color_background><tr><td> ";
  if($row['department']!= 0){ print "<b>" . $lang['txt9'] ."</b><font color=000077>" . whatdep($row['department']) . "</font><br>"; }
  if($row['page']!= ""){ print "<b>" . $lang['txt10'] ."</b><font color=000077>".$row['page']."</font><br>"; }
  if($row['referer']!= ""){ print "<b>" . $lang['txt11'] ."</b><font color=000077>".$row['referer']."</font><br>"; }
  if($row['visits'] > 1){ print "<b>" . $lang['txt12'] ."</b><font color=000077>".$row['visits']."</font><br>";  }
 $minutes = floor($row['seconds']/60);
 $seconds = $row['seconds'] - ($minutes * 60); 
  if($row['seconds'] > 1){ print "<b> " . $lang['timeonline'] . "</b><font color=000077> $minutes Minutes and ".$seconds." Seconds</font><br>";  }
  print "</td></tr></table>";
  if($row['typeof'] == "layer"){

  // $row['message'] should be the id of the layerinvite:
  $layerid = intval($row['message']);
  $sqlquery = "SELECT layerid,imagename,imagemap FROM livehelp_layerinvites WHERE layerid='$layerid'";
  $layers = $mydatabase->query($sqlquery);    
  $invite = $layers->fetchRow(DB_FETCHMODE_ORDERED);
  print "<img src=layer_invites/$invite[1]>"; 
  } else
    print $row['message'];
  print  "</td><td><a href=autoinvite.php?whattodo=EDIT&which=" . $row['idnum'] . ">" . $lang['edit'] ."</a> <br><Br><br> <a href=autoinvite.php?whattodo=REMOVE&which=" . $row['idnum'] . "><font color=990000>" . $lang['delete'] ."</font></td></tr>";	
 }

}
?>
</table>
<table><tr><td><input type=submit name=whattodo value="<?php echo $lang['UPDATE']; ?>"></td><td><input type=submit name=createnew value="<?php echo $lang['CREATE']; ?>"></td></tr></table>
<br>

<?php } else { ?>
<form action=autoinvite.php name=chatter method=post>
<?php
if ($UNTRUSTED['whattodo'] == "EDIT"){ 
 $query = "SELECT * FROM livehelp_autoinvite WHERE idnum=".intval($UNTRUSTED['which']);
 $row = $mydatabase->query($query);
 $item = $row->fetchRow(DB_FETCHMODE_ASSOC);
 print "<input type=hidden name=editidnum value=".intval($UNTRUSTED['which']).">\n";
} else {
 	 $item['page'] = "";
	 $item['department'] = "";
   $item['referer'] = "";
   $item['visits'] = "";
   $item['message'] = "";
   $item['typeof'] = "";
   $item['seconds'] = 0;
}
 $minutes = floor($item['seconds']/60);
 $seconds = $item['seconds'] - ($minutes * 60); 
?>
<h2><?php echo $lang['txt13']; ?></h2>
<b><?php echo $lang['txt14']; ?></b>
<?php
 if(empty($UNTRUSTED['createnew']))  $UNTRUSTED['createnew'] = "";
 if(empty($UNTRUSTED['whattodo']))  $UNTRUSTED['whattodo'] = "";    
 if(empty($UNTRUSTED['which']))  $UNTRUSTED['which'] = "";  
 if(empty($UNTRUSTED['force']))  $UNTRUSTED['force'] = "";  
?>
<SCRIPT type="text/javascript">
function refreshthis(what){
  if (what == "popup")
       	window.location.replace("autoinvite.php?createnew=<?php echo $UNTRUSTED['createnew']; ?>&whattodo=<?php echo $UNTRUSTED['whattodo']; ?>&which=<?php echo $UNTRUSTED['which']; ?>&force=popup");  
  else
       	window.location.replace("autoinvite.php?createnew=<?php echo $UNTRUSTED['createnew']; ?>&whattodo=<?php echo $UNTRUSTED['whattodo']; ?>&which=<?php echo $UNTRUSTED['which']; ?>&force=layer");  
 
 
}
</SCRIPT>
<table>
<tr><td><b>
<?php echo $lang['txt15']; ?></b></td><td>
<select name=typeof onchange="refreshthis(this.value)">
<?php
    $layerselected = " ";
    $popupselected = "  ";    
  if( ($item['typeof'] == "layer") || ($UNTRUSTED['force'] =="layer")){
    $layerselected = " SELECTED ";
    $popupselected = "  ";    	
  } 
  if( ($item['typeof'] == "popup") || ($UNTRUSTED['force'] =="popup")){  
    $layerselected = "  ";
    $popupselected = " SELECTED ";   	
  }
?>
<option value=layer <?php echo $layerselected; ?>><?php echo $lang['txt16']; ?></option>
<option value=popup <?php echo $popupselected; ?>><?php echo $lang['txt17']; ?></option>
</select>
</td></tr><tr>
<td>
<b><?php echo $lang['txt18']; ?></b></td><td>
<select name=department>
<option value=0><?php echo $lang['any']; ?></option>
<?php
 $query = "SELECT * FROM livehelp_departments ";
 $data = $mydatabase->query($query);
 while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){   
   print "<option value=" . $row['recno'];
   if($item['department'] == $row['recno']){ print " SELECTED "; }
   print ">" . $row['nameof'] . "</option>\n";
 }
?>
</select>
</td></tr>
</table>
<b>... On the website for more then</b>:
<select name=minutes>
	<option value=<?php echo intval($minutes); ?> > <?php echo intval($minutes); ?> </option>
	<option value=<?php echo intval($minutes); ?> > --- </option>
	<option value=0>0</option>
	<option value=1>1</option>
	<option value=2>2</option>
	<option value=3>3</option>
	<option value=4>4</option>
	<option value=5>5</option>
	<option value=6>6</option>
	<option value=7>7</option>
	<option value=8>8</option>
	<option value=9>9</option>
	<option value=10>10</option>
	<option value=11>11</option>										
	<option value=12>12</option>
	<option value=13>13</option>
	<option value=14>14</option>			
	<option value=15>15</option>
	<option value=16>16</option>
	<option value=17>17</option>			
	<option value=18>18</option>	
 	<option value=19>19</option>
	<option value=20>20</option>
	<option value=21>21</option>
	<option value=22>22</option>
 	<option value=23>23</option>				
	</select> Minutes
<select name=seconds>
	<option value=<?php echo intval($seconds); ?> > <?php echo intval($seconds); ?> </option>	
	<option value=<?php echo intval($seconds); ?> >--- </option>	
	<option value=0>0</option>
	<option value=15>15</option>
  <option value=30>30</option>
	<option value=45>45</option>
	</select> Seconds		
	<br><br>
<table border=1><tr bgcolor=DEDEDE><td>
	<b>Advanced Options:</b>
</td></tr>
<tr><td>
	<b><?php echo $lang['txt19']; ?></b><br>
<?php echo $lang['txt20']; ?>
<br>
<input type=text size=45 name=page value="<?php echo $item['page']; ?>" ><br>
<b><?php echo $lang['txt21']; ?></b><br>
<?php echo $lang['txt22']; ?>
<br>
<input type=text size=45 name=referer  value="<?php echo $item['referer']; ?>" ><br>

<table>
<tr><td><b><?php echo $lang['txt23']; ?></b></td><td>
<select name=visits>
<option value="<?php echo $item['visits']; ?>"><?php echo $item['visits']; ?></option>
<?php
for($i=1; $i< 30; $i++){
print "<option value=$i>$i</option>\n";
}
?>

</select> <?php echo $lang['txt24']; ?>
</td></tr>
</table></td></tr>
</table>
<br>

<br>

<input type=hidden name=typing value="no"> 
<b><?php echo $lang['message']; ?></b>
<input type=hidden name=user_id value="<?php echo $myid; ?>">
<input type=hidden name=alt_what value="">
<SCRIPT type="text/javascript">
function autofill(num){
   if(num == 0){ document.chatter.comment.value=''; }
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' AND typeof!='IMAGE' ORDER by name ";
  $result = $mydatabase->query($query);
  $j=-1;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
  	if(note_access($row['visiblity'],$row['department'],$row['user'])){
  	$j++;
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='" . addslashes(stripslashes(stripslashes($row['message']))) . "'; document.chatter.editid.value='" . $row['id'] . "';  }\n";	
   }
 } ?>
}
function autofill_url(num){
   if(num == 0){ document.chatter.comment.value=''; }
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof='URL' ORDER by name ";
  $result = $mydatabase->query($query);
  $j = -1;
  $j=-1;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
  	if(note_access($row['visiblity'],$row['department'],$row['user'])){
  	$j++;
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='[SCRIPT]openwindow(\'". $row['message'] . "\',\'window".$row['id']."\');[/SCRIPT]';  }\n";	
  }
 } ?>
}

function openwindow(url){ 
 window.open(url, 'chat540', 'width=572,height=320,menubar=no,scrollbars=0,resizable=1');
}

function autofill_image(num){
   if(num == 0){ document.chatter.comment.value=''; }
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof='IMAGE' ORDER by name ";
  $result = $mydatabase->query($query);
  $j=-1;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
   if(note_access($row['visiblity'],$row['department'],$row['user'])){ 	
  	$j++;
   $in= $j + 1;
   print "if(num == $in){ document.chatter.comment.value='<img src=".$row['message'].">';  }\n";	
   }
 } ?>
}

function updatedhtml(){

<?php
  $sqlquery = "SELECT layerid,imagename,imagemap FROM livehelp_layerinvites ";
  $layers = $mydatabase->query($sqlquery);    
  while($invite = $layers->fetchRow(DB_FETCHMODE_ORDERED)){ 
				   print "if (document.chatter.comment.value==\"".$invite[0]."\")\n";
				   print "   document.chatter.dhtmlimage.src = 'layer_invites/$invite[1]'\n";
				   $count++;
	}  
?>		

}
</SCRIPT><br>
<input type=hidden name=timeof value=<?php echo $timeof; ?> >
<input type=hidden name=editid value="">
<?php if( (($UNTRUSTED['force'] !="popup") && ($item['typeof'] == "layer")) || ($UNTRUSTED['force'] =="layer") || (empty($item['typeof']) && (empty($UNTRUSTED['force']))) ){

if(!(empty($item['message']))){
 
  // $item['message'] should be the id of the layerinvite:
  $layerid = intval($item['message']);
  $sqlquery = "SELECT layerid,imagename,imagemap FROM livehelp_layerinvites WHERE layerid='$layerid'";
  $layers = $mydatabase->query($sqlquery);    
  $invite = $layers->fetchRow(DB_FETCHMODE_ORDERED);
  print "<img src=layer_invites/$invite[1]  name=dhtmlimage>"; 
 
} else {
  print "<img src=images/blank.gif name=dhtmlimage border=0>";
}
?>
<br><br>
<?php echo $lang['txt27'] ?> <select name=comment onchange=updatedhtml()>
<option value="<?php echo $item['message']; ?>"></option>
<?php
  $sqlquery = "SELECT layerid,imagename,imagemap FROM livehelp_layerinvites ";
  $layers = $mydatabase->query($sqlquery);    
  while($invite = $layers->fetchRow(DB_FETCHMODE_ORDERED)){ 
    print "<option value=$invite[0]> $invite[1]</option>\n";  
  }
?>			 
</select><br><br>
<input type=submit name=whattodo value="<?php echo $lang['SAVE']; ?>">
</FORM>
<br>
 <br><br>
<?php
 } else { 
?>
<textarea cols=40 rows=3 name=comment ><?php echo htmlspecialchars($item['message']); ?></textarea>

 
<br>
<img src=images/blank.gif width=300 height=1><br>
</td></tr>
<tr><td colspan=3><b><?php echo $lang['txt31']; ?></b><br>
<select name=quicknote onchange=autofill(this.selectedIndex)>
<option value=-1 ><?php echo $lang['txt27']; ?></option>
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' AND typeof!='IMAGE' ORDER by name ";
  $result = $mydatabase->query($query);
  $j=-1;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
    if(note_access($row['visiblity'],$row['department'],$row['user'])){	
  	$j++;
   $in= $j + 1;
   print "<option value=".$row['id'].">".$row['name']."</option>\n";	
  }
} ?>
</select> <a href=javascript:openwindow('edit_quick.php')><?php echo $lang['txt32']; ?></a><br>
</td>
</tr></table>

<input type=submit name=whattodo value="<?php echo $lang['SAVE']; ?>">
</FORM>
<br><br>
<br><br>
<?php } ?>
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
}
?>