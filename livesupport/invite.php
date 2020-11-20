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

$timeof = date("YmdHis");

 

if(empty($UNTRUSTED['what'])){ $UNTRUSTED['what'] = ""; }

 
if(!(empty($UNTRUSTED['selectedwho']))){
	$pairs = split("__",$UNTRUSTED['selectedwho']);
	for($i=0;$i< count($pairs); $i++){
		$selected = $pairs[$i]; 
    $whatchannel = createchannel($selected);
    $query = "DELETE FROM livehelp_operator_channels WHERE user_id=".intval($myid)." AND userid=".intval($selected);	
    $mydatabase->query($query);
    $timeof = date("YmdHis");
 }
}

if($UNTRUSTED['what'] == "send"){
$pair = split(",",$UNTRUSTED['channelsplit']);
for($i=0;$i<count($pair);$i++){
	$split = $pair[$i];
  $array = split("__",$split);
  $saidto = $array[1]; 
  $channel = $array[0];  
  if($saidto == ""){ $channel = -1; }
  $query = "UPDATE livehelp_users set status='request' WHERE user_id=".intval($saidto);
  $mydatabase->query($query); 	   	    
  $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('".filter_sql($UNTRUSTED['comment'])."',".intval($channel).",'$timeof',".intval($myid).",".intval($saidto).")";
  $mydatabase->query($query);    
  if(!(empty($UNTRUSTED['askquestions']))){
  	  $whatchannel = createchannel(intval($saidto));	 
  	  $now = date("YmdHis");
      $query = "UPDATE livehelp_users set isnamed='Y',askquestions='N',chataction='$now' WHERE user_id=".intval($saidto);
      $mydatabase->query($query); 
      // add operator answer call.
   // generate random Hex..
    $txtcolor = "";
    $lowletters = array("0","2","4","6");
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $txtcolor .= $lowletters[$randomindex];
    }	 

   // generate random Hex..
    $txtcolor_alt = "";
    $lowletters = array("2","4","6","8");
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $txtcolor_alt .= $lowletters[$randomindex];
    }	       
      $query = "DELETE FROM livehelp_operator_channels WHERE user_id=".intval($myid) . " AND userid=".intval($saidto);	
      $mydatabase->query($query);  
      $channelcolor = get_next_channelcolor();  
      $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,txtcolor,txtcolor_alt,channelcolor) VALUES (".intval($myid).",".intval($whatchannel).",".intval($saidto).",'$txtcolor','$txtcolor_alt','$channelcolor')";	
      $mydatabase->query($query);	
        // add to history:
   $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,channel,totaltime) VALUES ($myid,'startchat','".date("YmdHis")."',".intval($whatchannel).",0)";
   $mydatabase->query($query);
  }
 }
 print $lang['txt71'] . "<a href=javascript:window.close()>" . $lang['txt40'] . "</a>";
 print "<SCRIPT type=\"text/javascript\">window.close();</script>";
 if(!($serversession))
  $mydatabase->close_connect();
 exit;    
}
?>
<script type="text/javascript">
<!--
function netscapeKeyPress(e) {
     if (e.which == 13)
         returnsend();
}

function microsoftKeyPress() {
  if(ie4){
    if (window.event.keyCode == 13)
         returnsend();
  }
}

if (navigator.appName == 'Netscape') {
    window.captureEvents(Event.KEYPRESS);
    window.onKeyPress = netscapeKeyPress;
}
//--></script>

<SCRIPT type="text/javascript">
ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
cscontrol= new Image;
var flag_imtyping = false;

function returnsend(){
  document.chatter.alt_what.value= "send";
  document.chatter.submit();	
}
</SCRIPT>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=E0E8F0 onload=window.focus()>
<?php 
$expires = date("YmdHis");
$expires = $expires - 20000;
$pairs = split(",",$UNTRUSTED['selectedwho']);
$comma = "";
$myusers ="";
$channelsplit = "";
 for($i=0;$i< count($pairs); $i++){
	 $selected = $pairs[$i];
   $query = "SELECT * FROM livehelp_users where user_id=".intval($selected);
   $mychannels = $mydatabase->query($query);	
   $channel_a = $mychannels->fetchRow(DB_FETCHMODE_ASSOC);
   $thischannel = $channel_a['onchannel'] . "__" . $selected; 
   $myusers .= $comma . $channel_a['username'];
   $bgcolor = "FFFFFF";
   $channelsplit .= $comma . $thischannel;
   $comma = ",";
 }  
?>
<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td><b><?php echo $lang['invite']; ?> <font color=000000 size=+1><?php echo $myusers; ?></font>:</b></td></tr></table>
<form action=invite.php name=chatter method=post>
<input type=hidden name=typing value="no">
<input type=hidden name=channelsplit value="<?php echo $channelsplit; ?>" > 
<b><?php echo $lang['message']; ?>:</b>
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
    print "if(num == $in){ document.chatter.comment.value='" . addslashes(stripslashes(stripslashes($row['message']))) . "'; document.chatter.editid.value='".$row['id']."';  }\n";	
   }
 } ?>
}
function autofill_url(num){
   if(num == 0){ document.chatter.comment.value=''; }
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof='URL' ORDER by name ";
  $result = $mydatabase->query($query);
  $j=-1;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
   if(note_access($row['visiblity'],$row['department'],$row['user'])){
    $j++;
    $in= $j + 1;
    print "if(num == $in){ document.chatter.comment.value='[SCRIPT]openwindow(\'".$row['message']."\',\'window".$row['id']."\');[/SCRIPT]';  }\n";	
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

</SCRIPT><br>
<input type=hidden name=timeof value=<?php echo $timeof; ?> >
<input type=hidden name=editid value="">
<textarea cols=40 rows=3 name=comment ></textarea><input type=submit name=what value=send>
<br>
<input type=checkbox name=askquestions value=Y>
<?php echo $lang['txt72']; ?>
</form>
<br>
<table width=100%>
<tr><td colspan=3>
<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td><b><?php echo $lang['addtional']; ?></b></td></tr></table>
<img src=images/blank.gif width=300 height=1><br>
</td></tr>
<tr><td><b><?php echo $lang['txt26']; ?></b>:</td><td>
<select name=url  onchange=autofill_url(this.selectedIndex)><option value=-1 ><?php echo $lang['txt27']; ?></option>
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof='URL' ORDER by name ";
  $result = $mydatabase->query($query);
  $j=-1;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
   if(note_access($row['visiblity'],$row['department'],$row['user'])){
    $j++;
    $in= $j + 1;
    print "<option value=".$row['id'].">".$row['name']."</option>\n";	
   }
} ?>
</select>
</td><td><a href=javascript:openwindow('edit_quick.php?typeof=URL')><?php echo $lang['txt28']; ?></a></td></tr>
<tr><td><b><?php echo $lang['txt29']; ?>:</b></td><td>
<select name=image onchange=autofill_image(this.selectedIndex)><option value=-1 ><?php echo $lang['txt27']; ?>:</option>
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof='IMAGE' ORDER by name ";
  $result = $mydatabase->query($query);
  $j=-1;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
  if(note_access($row['visiblity'],$row['department'],$row['user'])){
   $j++;
   $in= $j + 1;
   print "<option value=".$row['id'].">".$row['name']."</option>\n";	
   }
} ?>
</select>
</td><td><a href=javascript:openwindow('edit_quick.php?typeof=IMAGE')><?php echo $lang['txt30']; ?></a></td></tr>
<tr><td colspan=3><b><?php echo $lang['txt31']; ?>:</b><br>
<select name=quicknote onchange=autofill(this.selectedIndex)>
<option value=-1 ><?php echo $lang['txt27']; ?>:</option>
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' AND typeof!='IMAGE' ORDER by name ";
  $result = $mydatabase->query($query);
  $j=0;
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
<br>
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>