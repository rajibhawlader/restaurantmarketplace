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
$query = "SELECT user_id,onchannel,showtype,externalchats,chattype FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ORDERED);
$myid = $people[0];
$channel = $people[1];
$defaultshowtype = $people[2];
$externalchats= $people[3];
$external = false;
$chattype = $people[4];
 
if($chattype!="xmlhttp"){
	 $chattype = "xmlhttp"; 
}

$previewsetting1 = "";
$previewsetting2 = "";
$previewsetting3 = "";
$previewsetting4 = "";
if(empty($UNTRUSTED['previewsetting'])){
  $UNTRUSTED['previewsetting'] = $defaultshowtype; 
  if($CSLH_Config['show_typing']!="Y")
  	 $UNTRUSTED['previewsetting'] = 4; 
}  
if(!(empty($UNTRUSTED['previewsetting']))){
	 if($UNTRUSTED['previewsetting'] == 1)  $previewsetting1 = " SELECTED ";
	 if($UNTRUSTED['previewsetting'] == 2)  $previewsetting2 = " SELECTED ";
	 if($UNTRUSTED['previewsetting'] == 3)  $previewsetting3 = " SELECTED ";
	 if($UNTRUSTED['previewsetting'] == 4)  $previewsetting4 = " SELECTED ";	 	 
}

$externalchats_array = explode(",",$externalchats);

if(!(isset($UNTRUSTED['channelsplit']))){ $UNTRUSTED['channelsplit'] = "__"; }
if(!(isset($UNTRUSTED['starttimeof']))){ $UNTRUSTED['starttimeof'] = 0; }
$mychannelcolor = $color_background;
$myusercolor = "000000";
if(!(isset($UNTRUSTED['whattodo']))){ $UNTRUSTED['whattodo'] = ""; }


$usercolor = "";
$myuser = "";

$timeof = rightnowtime();
 

$array = split("__",$UNTRUSTED['channelsplit']);
if(empty($array[0])){ $array[0] = ""; }
if(empty($array[1])){ $array[1] = ""; }
$saidto = intval($array[1]); 
$channel = intval($array[0]);  
if($saidto == ""){ $channel = -1; }

// alternate whattodo for keystroke return 
if(isset($UNTRUSTED['alt_what'])){ $UNTRUSTED['whattodo'] = "send";}


if($UNTRUSTED['whattodo'] == "send"){
  
  // update username:
  if(!(empty($UNTRUSTED['newusername']))){
    $query = "UPDATE livehelp_users SET username='".filter_sql($UNTRUSTED['newusername'])."' WHERE isoperator='N' AND user_id=". intval($saidto);
    $mydatabase->query($query);
  }
  
   // check to see if they are active is a chat session. 
   $query = "SELECT * FROM livehelp_users WHERE user_id=". intval($saidto);
   $check_s = $mydatabase->query($query);
   $check_s = $check_s->fetchRow(DB_FETCHMODE_ASSOC);
   if($check_s['status'] != "chat"){
    $query = "UPDATE livehelp_users set status='request' WHERE user_id=".intval($saidto);
    $mydatabase->query($query);   	   	
   }

   $query = "DELETE FROM livehelp_messages WHERE typeof='writediv'";
   $mydatabase->query($query);
   
    if(empty($UNTRUSTED['allowHTML'])){
       $UNTRUSTED['comment'] = filter_html($UNTRUSTED['comment']);
   	        	 
	   // convert links if not PUSH urls:
	   if(!(ereg("[PUSH]",$UNTRUSTED['comment']))){
       $UNTRUSTED['comment'] = preg_replace('#(\s(www.))([^\s]*)#', ' http://\\2\\3 ', $UNTRUSTED['comment']);
       $UNTRUSTED['comment'] = preg_replace('#((http|https|ftp|news|file)://)([^\s]*)#', '<a href="\\1\\3" target=_blank>\\1\\3</a>', $UNTRUSTED['comment']);
     }  
   
     if(!(empty($UNTRUSTED['smilies'])))      
      $UNTRUSTED['comment'] = convert_smile($UNTRUSTED['comment']);
    }
                 
   $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('".filter_sql($UNTRUSTED['comment'])."',".intval($channel).",'$timeof',".intval($myid).",".intval($saidto).")";
   $mydatabase->query($query);
   $quicknote ="";
}

?>
<HTML>
	<HEAD>
		<title> chat bot </title>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
</HEAD>
<body bgcolor=<?php echo $color_alt1; ?> onload="loaded=1;shouldifocus()">  
<script type="text/javascript">

ns4 = (document.layers)? true:false;
IE4 = (document.all)? true:false;

function openwindow(url){ 
 win4 = window.open(url, 'chat54057', 'width=600,height=400,menubar=no,scrollbars=1,resizable=1');
   win4.creator=self; 
}
 
function netscapeKeyPress(e) {
     if (e.which == 13)
         safeSubmit(document.chatter);
}

function microsoftKeyPress() {

  if(IE4){
    if (window.event.keyCode == 13)
         safeSubmit(document.chatter);
  }  
}

NS4 = (document.layers);
if (NS4) 
   document.captureEvents(Event.KEYPRESS);
if(!(IE4))   
   document.onkeypress = netscapeKeyPress;

loaded=0;
setTimeout('loaded=1',2000);
cscontrol= new Image;
var flag_imtyping = false;
var mymessagesofar = "";

function setflag(){
  flag_imtyping = true; 	
}
function noamps(whatstring){
  s = new String(whatstring); 
  s = s.replace(/&/g,"*amp*");
  s = s.replace(/=/g,"*equal*");
  s = s.replace(/\+/g,"*plus*");
  s = s.replace(/\#/g,"*hash*");  
  s = s.replace(/\%/g,"*percent*");
  return s;
}

function sayingwhat(){ 
   var newmess = document.chatter.comment.value;
   if ( (blockmessage!=1) && 
        (document.chatter.comment.value.length > 2) && 
        (document.chatter.previewsetting.value<3)){
    flag_imtyping = true;	
    document.chatter.typing.value="YES";
    if(document.chatter.previewsetting.value==2){
      saidwhat = "<?php echo $lang['istyping']; ?>";
      for (i=0;i<document.chatter.comment.value.length;i=i+3)
         saidwhat = saidwhat + " .";            
    } else
    	saidwhat = noamps(document.chatter.comment.value);
    if(mymessagesofar != newmess){
    var u = 'admin_image.php?' + 
					'what=startedtyping' + 
					'&channelsplit=' + escape(document.chatter.channel.value) + 
					'&fromwho=' + escape(document.chatter.myid.value) + 
					'&sayingwhat=' + saidwhat;

    cscontrol.src = u; 
    mymessagesofar = newmess;
   }
  }
}

function shouldifocus(){	 
   if( (flag_imtyping == false) && (loaded==1) ){
     var newmess = document.chatter.comment.value;
//alert(document.chatter.comment.value.length);
     if(document.chatter.comment.value.length < 2){
      	//alert("got here");
       window.parent.focus();
       window.focus();
      <?php if($UNTRUSTED['channelsplit'] != "__"){ ?>    
        setTimeout("document.chatter.comment.focus()",500);
      <?php } ?>
    }
   }
   if(window.parent.connection.sleeping){
     	window.parent.connection.location.replace("admin_connect.php");
     	window.parent.focus();
      window.focus();
   }
}

function forcerefreshit(){
 if(loaded==1){
 	window.parent.connection.location.replace("admin_connect.php");
  document.chatter.typing.value="no";
  refreshit();
 }
}
function refreshit(){
 if(loaded==1){
  if(document.chatter.typing.value=="no"){
   if(window.parent.connection.sleeping){
     	window.parent.connection.location.replace("admin_connect.php");
   }   
   setTimeout("window.location='admin_chat_bot.php'",200);	
  }
 }
}

function expandit() {
  window.parent.resizeTo(window.screen.availWidth,      
  window.screen.availHeight); 
  if(IE4){
    // everything should be ok.. 
  } else {    
    setTimeout('refreshit()',900);
  }
}

function safeSubmit(f) {
  document.chatter.alt_what.value= "send";		
	for (i=1; i<f.elements.length; i++) {
		if (f.elements[i].type == 'submit') {
			f.elements[i].disabled = true;
		}
	}
    var u = 'admin_image.php?' + 
					'what=startedtyping' + 
					'&channelsplit=' + escape(document.chatter.channel.value) + 
					'&fromwho=' + escape(document.chatter.myid.value) + 
					'&sayingwhat=nullstring';
    cscontrol.src = u; 
    mymessagesofar = '';
    	
	f.submit();
	blockmessage = 1;
	safeSubmit = blockIt;
	return false;
}


function blockIt(f) {
	return false;
}	

function stopwindow(channelsplit){
  // open external window with chat 
  var url = 'external_frameset.php?whattodo=NOWINDOW&channelsplit=' + channelsplit;
  window.open(url,'chat'+channelsplit,"scrollbars=yes,resizable=yes,width=500,height=420");  
  setTimeout("window.parent.users.location='admin_users.php'",2000);
  setTimeout("window.parent.connection.location='admin_chat_<?php echo $chattype; ?>.php'",2000);
  setTimeout("window.location='admin_chat_bot.php'",2500);
}

function externalwindow(channelsplit){
  // open external window with chat 
  var url = 'external_frameset.php?channelsplit=' + channelsplit;
  window.open(url,'chat'+channelsplit,"scrollbars=yes,resizable=yes,width=520,height=420");  
  setTimeout("window.parent.users.location='admin_users.php'",2000);
  setTimeout("window.parent.connection.location='admin_chat_<?php echo $chattype; ?>.php'",2000);
  setTimeout("window.location='admin_chat_bot.php'",2500);
}

	blockmessage = 0;

loaded=0;
setTimeout('loaded=1',1000);
</SCRIPT>

<form action=admin_chat_bot.php name=chatter method=post>
<!-- Tabs of current Chatting users.-->

<table border="0" cellspacing="0" cellpadding="0" class="tabs">
  <tr>
   <td width="8">&nbsp;</td>
<?php 

$query = "SELECT livehelp_operator_channels.id,livehelp_operator_channels.txtcolor,livehelp_operator_channels.txtcolor_alt,livehelp_operator_channels.channelcolor,livehelp_operator_channels.userid,livehelp_users.user_id,livehelp_users.isoperator,livehelp_users.ipaddress,livehelp_users.username,livehelp_users.onchannel,livehelp_users.lastaction FROM livehelp_operator_channels,livehelp_users where livehelp_operator_channels.userid=livehelp_users.user_id AND livehelp_operator_channels.user_id=". intval($myid);
$mychannels = $mydatabase->query($query);	
$counting = $mychannels->numrows();

$botline = "<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>";
$j=0;
$wearelinking = false; // flag for if we are linking the chat tabs or not.
$alltabs = array();

while($channel_a = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){

 $prev = mktime ( date("H"), date("i")-3, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 $alltabs[$j] = $channel_a;
 $j++;
}

 // Update operator History for when operator is chatting 
 $q = "SELECT chataction,user_id FROM livehelp_users WHERE sessionid='" . $identity['SESSIONID'] . "' LIMIT 1";
 $sth = $mydatabase->query($q);
 $row = $sth->fetchRow(DB_FETCHMODE_ORDERED);
 $rightnow = rightnowtime();
 $chataction = $row[0];
 $opid = $row[1];
 
 if($j>0){
  if($chataction < 10){ 
    // update history for operator to show login:
    $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid) VALUES ($opid,'Started Chatting','$rightnow','".$identity['SESSIONID']."')";
    $mydatabase->query($query);
  }
  $q = "UPDATE livehelp_users SET chataction='$rightnow' WHERE sessionid='" . $identity['SESSIONID'] . "'";
  $mydatabase->query($q);
 } else {
  // get when they logged in and how many seconds they have been online:
        $query = "SELECT dateof FROM livehelp_operator_history WHERE opid=$opid AND action='Started Chatting' ORDER by dateof DESC LIMIT 1";
        $data3 = $mydatabase->query($query);
        $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
        $seconds = timediff($chataction,$row3['dateof']);
        
        if($chataction>10){
         // update history for operator to show login:
         $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid,totaltime) VALUES ($opid,'Stopped Chatting','$chataction','".$identity['SESSIONID']."',$seconds)";
         $mydatabase->query($query);       
         $query = "UPDATE livehelp_users set chataction='0' WHERE sessionid='" . $identity['SESSIONID'] . "'";
         $mydatabase->query($query); 	
        } 
 }

for($k=0;$k<count($alltabs);$k++){
	
	$channel_a = $alltabs[$k];
	if(!(empty($alltabs[$k+1])))
  	$channel_b =  $alltabs[$k+1]; 
  else
    $channel_b['onchannel'] = -1;
    	
  if($channel_a['isoperator'] == "Y"){
       // Operators can be on multiple channels so see if one of the channels they are on is one we are on.      
      $foundcommonchannel = 0;
      $query = "SELECT * FROM livehelp_operator_channels WHERE user_id=". intval($channel_a['user_id']);
      $mychannels2 = $mydatabase->query($query);
      while($rowof = $mychannels2->fetchRow(DB_FETCHMODE_ASSOC)){
         $query = "SELECT * FROM livehelp_operator_channels WHERE user_id=".intval($myid)." And channel=".intval($rowof['channel']);
         $countingthem = $mydatabase->query($query);        
         if($countingthem->numrows() != 0){ 
             $foundcommonchannel = $rowof['channel'];
         }
      }
  $channel_a['onchannel'] = $foundcommonchannel;
  $channel = $foundcommonchannel;
  }
 
    $thischannel = $channel_a['onchannel'] . "__" . $channel_a['userid']; 
    $usercolor = $channel_a['txtcolor'];
    $usercolor_alt = $channel_a['txtcolor_alt'];
    if ($UNTRUSTED['channelsplit'] == $thischannel){
         $myuser = $channel_a['username'];
         $myuser_ip = $channel_a['ipaddress'];
         $myusercolor = $usercolor;
         $mychannelcolor = $channel_a['channelcolor'];
         $txtcolor = $channel_a['channelcolor'];
         if(in_array($channel_a['onchannel'],$externalchats_array))
           $external = true;
         else
           $external = false;            
    } else {          
         $txtcolor = "DDDDDD";
         $txtcolor = $channel_a['channelcolor'];         
   }   
   $dakineuser = substr($channel_a['username'],0,15);
   
 // if we are linking to next channel :
 if($channel_b['onchannel'] == $channel_a['onchannel']){
 	 if($wearelinking == false){
 	    $wearelinking = true;
 	    ?>
 	    <td bgcolor=000000 width=1><img src=images/blank.gif width=1 height=1 border=0></td>
      <td valign=top bgcolor="#<?php echo $txtcolor; ?>">
       <table border="0" cellspacing="0" cellpadding="0" width=100%>
         <tr><td colspan=3 bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td></tr>
         <tr><td bgcolor="#<?php echo $txtcolor; ?>">&nbsp;</td>
 	    <?php
 	 }
 	 ?>
 	 <td bgcolor="#<?php echo $txtcolor; ?>" align="center" nowrap="nowrap" class="tab"><a href="admin_chat_bot.php?channelsplit=<?php echo $channel_a['onchannel']; ?>__<?php echo $channel_a['userid']; ?>"><font color=<?php echo $usercolor; ?>><b><?php echo ereg_replace(" ","&nbsp;",$dakineuser); ?></font></b></a>&nbsp;&nbsp;</td>
 	 <td bgcolor="#<?php echo $txtcolor; ?>" align="center" nowrap="nowrap" class="tab"><img src=images/link.gif border=0 width=25 height=21></td>
 	 <?php
 } else {	
 	// if we were linking the tabs:
 	if($wearelinking == true){
 	 $wearelinking = false;
   ?>
    </tr>
   </table> 
  </td>  
<?php
 	} else {
   ?>
 <td bgcolor=000000 width=1><img src=images/blank.gif width=1 height=1 border=0></td>
 <?php } ?>
  <td valign=top bgcolor="#<?php echo $txtcolor; ?>">
   <table border="0" cellspacing="0" cellpadding="0" width=100%>
    <tr><td colspan=3 bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td></tr>
    <tr>
    <td bgcolor="#<?php echo $txtcolor; ?>">&nbsp;</td> 
    <td bgcolor="#<?php echo $txtcolor; ?>" align="center" nowrap="nowrap" class="tab"><a href="admin_chat_bot.php?channelsplit=<?php echo $channel_a['onchannel']; ?>__<?php echo $channel_a['userid']; ?>"><font color=<?php echo $usercolor; ?>><b><?php echo ereg_replace(" ","&nbsp;",$dakineuser); ?></font></b></a>&nbsp;&nbsp;</td>
    <td bgcolor="#<?php echo $txtcolor; ?>" align="right" nowrap="nowrap" class="tab"><?php if($counting >1){ ?><a href=admin_chat_<?php echo $chattype; ?>.php?offset=2&see=<?php echo $channel_a['onchannel']; ?> target=connection><img src=images/makvis.gif width=29 height=13 border=0></a><?php } ?> <a href=javascript:openwindow('chat_color.php?id=<?php echo $channel_a['id']; ?>')><img src=images/paint.gif width=25 height=21 border=0></a>
    <?php
     if(in_array($channel_a['onchannel'],$externalchats_array))
      print "<a href=javascript:stopwindow('". $channel_a['onchannel'] ."__". $channel_a['userid'] ."')><img src=images/closewin.gif width=25 height=21 border=0></a></td>";
     else
      print "<a href=javascript:externalwindow('". $channel_a['onchannel'] ."__". $channel_a['userid'] ."')><img src=images/newwin.gif width=25 height=21 border=0></a></td>";
    ?></tr>
   </table> 
  </td>  
 <td bgcolor=000000 width=1><img src=images/blank.gif width=1 height=1 border=0></td>
 <td width="8">&nbsp;</td>
 
<?php

 if ($UNTRUSTED['channelsplit'] == $thischannel){ 
   $botline .= "
    <td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td bgcolor=\"". $txtcolor ."\"><img src=images/blank.gif width=1 height=1 border=0></td>
<td bgcolor=\"". $txtcolor ."\"><img src=images/blank.gif width=1 height=1 border=0></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
";
} else {
	  $botline .= "
    <td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
";
 }
} 
}
?>
    <td width=90% align=right NOWRAP bgcolor=EFEFEF>&nbsp;&nbsp;<a href="javascript:clearchat()"><img src=images/clear.gif width=25 height=25 border=0></a>&nbsp;&nbsp;<a href=javascript:forcerefreshit()><img src=images/refresh.gif width=25 height=25 border=0></a>&nbsp;    
    <?php if($CSLH_Config['resetbutton']=="Y"){ ?>
    &nbsp;<a href=live.php?reset=yes target=_top><img src=images/reset.gif width=25 height=25 border=0></a>
    <?php } ?>
    <?php if($CSLH_Config['showgames']=="Y"){ ?>
    &nbsp;<a href=javascript:games()><img src=images/games.gif width=25 height=25 border=0></a>
    <?php } ?>
    <?php if($CSLH_Config['showdirectory']=="Y"){ ?>    
      &nbsp;<a href="http://www.craftysyntax.com/directory/" target="_blank"><img src=images/directory.gif width=25 height=25 border=0></a>
    <?php } ?>  
    <?php if(substr($CSLH_Config['everythingelse'],0,1)=="Y"){ ?>    
      &nbsp;<a href="http://www.craftysyntax.com/special.php" target="_blank"><img src=images/pp.gif width=25 height=25 border=0></a>
    <?php } ?> 
    <?php if(substr($CSLH_Config['everythingelse'],1,1)=="Y"){ ?>   
&nbsp;<a href="http://www.craftysyntax.com/howto.php" target="_blank"><img src=images/help.gif width=25 height=25 border=0></a>    
    <?php } ?>      
    </tr>
<tr>
<?php 
  echo $botline;
?>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1 border=0></td>
</tr>    
</table>
<SCRIPT type="text/javascript">
function games(){ 
  window.open('http://games.craftysyntax.com/', 'gameswindow', 'width=620,height=420,menubar=no,scrollbars=1,resizable=1');	
}
function clearchat(){
	window.parent.connection.location='admin_chat_<?php echo $chattype; ?>.php?cleartonow=1';	
}

function directory(){
  window.open('http://www.craftysyntax.com/directory/', 'directorywindow', 'width=620,height=420,menubar=no,scrollbars=1,resizable=1');	
}
</SCRIPT>
<?php if( ($UNTRUSTED['channelsplit'] == "__") || empty($myuser)){ 

if ($mychannels->numrows() == 0){
?>
<table bgcolor=FFFFFF width=450><tr><td>
<?php echo $lang['noone_online']; ?>
</td></tr></table>
<SCRIPT type="text/javascript">
	 if(!(window.parent.connection.sleeping)){
     	window.parent.connection.location.replace("admin_connect.php");
   }
	</SCRIPT>
<?php
} else {
?>
<table bgcolor=FFFFFF width=450><tr><td>
<b><?php echo $lang['choose']; ?></b></td></tr></table>

<?php } ?>
<form action=admin_chat_bot.php name=chatter method=post>
<input type=hidden name=typing value="no">
<input type=hidden name=user_id value="<?php echo $myid; ?>">
<input type=hidden name=myid value="<?php echo $myid; ?>">
<input type=hidden name=comment value=1 size=1>
</form>
<?php
  
 } else { ?>
 <SCRIPT type="text/javascript">
 function editsmile(){
 	window.open('edit_smile.php','smileimagesedit','width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=yes,scrollbars=yes','POPUP');
 }
 function showsmile(){
	newmsgWindow = open('smile.php?note=1','smileimages','width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=no,scrollbars=yes','POPUP');
	if (newmsgWindow.opener == null){
		newmsgWindow.opener = self;
	}
	if (newmsgWindow.opener.chatter == null){
        newmsgWindow.opener.chatter = document.sendform;
	}
	newmsgWindow.focus();
	
}
 </SCRIPT>
<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=<?php echo $mychannelcolor; ?>><tr><td width=50%>
<?php 
  if($external){
   ?>
   <form action=admin_chat_bot.php name=chatter method=post>
   <input type=hidden name=typing value="no">
   <input type=hidden name=user_id value="<?php echo $myid; ?>">
   <input type=hidden name=myid value="<?php echo $myid; ?>">
   <center><h2><?php echo $lang['txt181']; ?></h2><br>
   <a href="javascript:externalwindow('<?php echo $UNTRUSTED['channelsplit'];?>')"><?php echo $lang['txt182']; ?></a></center>
   </form>
   <?php
 } else { 
 	
 	
// if the username is a ip address give an option to rename it:
$haystack = split("_",$myuser);
 if(!(empty($haystack[2])))
    $myuser2 = $haystack[1];
 else
   $myuser2 = $haystack[0];
             
if(($myuser2 == $myuser_ip) || (!(empty($UNTRUSTED['renameuser']))) ){ 	
	$myuser2 = "<font size=-2>Rename:</font><input type=text size=12 name=newusername value=\"$myuser2\">"; 
  $rename = "";  
} else {
	$rename = "&nbsp;<font size=-2><a href=admin_chat_bot.php?renameuser=1&channelsplit=".$UNTRUSTED['channelsplit'].">Rename</a></font>";
}
	           
	            	
?>      	
<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=EFEFEF><tr><td><b><?php echo $lang['message_to']; ?> <font color=<?php echo $myusercolor; ?> size=+1><?php echo $myuser2; ?></font>:</b></td><td align=right><?php echo $rename; ?>&nbsp;&nbsp;<a href=javascript:showsmile()><img src=chat_smiles/icon_smile.gif border=0>smilies</a> :: <a href=javascript:editsmile()><?php echo $lang['edit']; ?></a></tr></table>
<textarea cols=50 rows=3 name=comment ONKEYDOWN="return microsoftKeyPress()"></textarea><input type=submit value="<?php echo $lang['send']; ?>"  onClick="return safeSubmit(this.form);"><br>
<b>SMILES:</b> <input type=checkbox name=smilies value=YES CHECKED>&nbsp;&nbsp;
<b>HTML:</b> <input type=checkbox name=allowHTML value=YES>&nbsp;&nbsp; 
<b>PREVIEW:</b>
 
<select name=previewsetting>
	<option value=1 <?php echo $previewsetting1 ?> ><?php echo $lang['txt221']; ?></option>
	<option value=2 <?php echo $previewsetting2 ?> ><?php echo $lang['txt222']; ?></option>
	<option value=3 <?php echo $previewsetting3 ?> ><?php echo $lang['txt223']; ?></option>
	<option value=4 <?php echo $previewsetting4 ?> ><?php echo $lang['txt224']; ?></option>
</select>
<input type=hidden name=whattodo value=send>
<SCRIPT type="text/javascript">
function autofill(num){
   if(num == 0){ document.chatter.comment.value=''; }
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' ORDER by typeof,name ";
  $result = $mydatabase->query($query);
  $j =-1;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
    if(note_access($row['visiblity'],$row['department'],$row['user'])){
      $j++;
      $in= $j + 1;
   if($row['typeof'] == "IMAGE"){
   print "if(num == $in){ document.chatter.comment.value= document.chatter.comment.value + '<img src=" . $row['message'] . " >'; document.chatter.editid.value='" . $row['id'] . "';   \n";	    
   print " document.chatter.allowHTML.checked = true; } \n";
   } else {
   print "if(num == $in){ document.chatter.comment.value= document.chatter.comment.value + '" . addslashes(stripslashes(stripslashes($row['message']))) . " '; document.chatter.editid.value='" . $row['id'] . "';  \n";	
   if($row['ishtml']=="YES") { print " document.chatter.allowHTML.checked = true ; \n"; } else {  print " document.chatter.allowHTML.checked = false ; \n"; }
   print " } ";
   } 
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
     print "if(num == $in){ document.chatter.comment.value= document.chatter.comment.value + '[PUSH]$row[message][/PUSH]';  }\n";	
  }
 } ?>
}
</SCRIPT>
<input type=hidden name=typing value="no">
<input type=hidden name=channel value=<?php echo $channel; ?> >
<input type=hidden name=channelsplit value="<?php echo $UNTRUSTED['channelsplit']; ?>" > 
<input type=hidden name=user_id value="<?php echo $myid; ?>">
<input type=hidden name=myid value="<?php echo $myid; ?>">
<input type=hidden name=alt_what value="">
<input type=hidden name=editid value="">
</form>
</td>
<td width=50% valign=top>
<table width=100%>
<tr><td colspan=3>
<table width=100% bgcolor=EFEFEF><tr><td><b><?php echo $lang['addtional']; ?>:</b></td></tr></table>
<img src=images/blank.gif width=300 height=1><br>
</td></tr>
<tr><td><b><?php echo $lang['txt26']; ?></b>:</td><td>
<select name=url  onchange=autofill_url(this.selectedIndex)><option value=-1 ><?php echo $lang['pick']; ?>:</option>
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof='URL' ORDER by name ";
  $result = $mydatabase->query($query);
  $j=0;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
   if(note_access($row['visiblity'],$row['department'],$row['user'])){
      $j++;
      $in= $j + 1;
      print "<option value=".$row['id'].">".$row['name']."</option>\n";	
   }   
} ?>
</select>
</td><td><a href="javascript:openwindow('edit_quick.php?typeof=URL');" Onclick="setflag()"><?php echo $lang['txt28']; ?></a></td></tr>
<tr><td colspan=3><b><?php echo $lang['quicknote']; ?></b><br>
<select name=quicknote onchange=autofill(this.selectedIndex)>
<option value=-1 ><?php echo $lang['txt27']; ?></option>
<?php 
  $query = "SELECT * FROM livehelp_quick Where typeof!='URL' ORDER by  typeof,name ";
  $result = $mydatabase->query($query);
  $j=0;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
   if(note_access($row['visiblity'],$row['department'],$row['user'])){ 
     $j++;
     $in= $j + 1;
     print "<option value=".$row['id'].">".$row['name']."</option>\n";	
  }
} ?>
</select><?php echo $lang['edit']; ?> <a href="javascript:openwindow('edit_quick.php');"  Onclick="setflag()"> <?php echo $lang['notes']; ?></a> <a href="javascript:openwindow('edit_quick.php?typeof=IMAGE');" Onclick="setflag()"><?php echo $lang['images']; ?></a><br>
<?php } ?>
</td>
</tr></table>
<?php } ?><br>
 
 <SCRIPT type="text/javascript">
 <?php 
	if ( ($CSLH_Config['show_typing'] != "N") && (!($external)) && ($UNTRUSTED['channelsplit'] != "__") && (!(empty($myuser))) ){ ?> 
    setInterval("sayingwhat()",5000);
    setTimeout('document.chatter.comment.focus()',500);    
    channelset = true;
<?php } else { ?>
channelset = false;
<?php } ?>
 </SCRIPT>
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>