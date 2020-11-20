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
$defaultshowtype = $people['showtype'];
$externalchats= $people['externalchats'];
$chattype = $people['chattype'];

if(!(isset($UNTRUSTED['channelsplit']))){ $UNTRUSTED['channelsplit'] = "__"; }
if(!(isset($UNTRUSTED['starttimeof']))){ $UNTRUSTED['starttimeof'] = 0; }
$mychannelcolor = $color_background;
$myusercolor = "000000";
if(!(isset($UNTRUSTED['whattodo']))){ $UNTRUSTED['whattodo'] = ""; }


$usercolor = "";
$myuser = "";

$timeof = date("YmdHis");

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
 
$array = split("__",$UNTRUSTED['channelsplit']);
if(empty($array[0])){ $array[0] = ""; }
if(empty($array[1])){ $array[1] = ""; }
$saidto = intval($array[1]); 
$channel = intval($array[0]);  
if($saidto == ""){ $channel = -1; }

// alternate whattodo for keystroke return 
if(isset($UNTRUSTED['alt_what'])){ $UNTRUSTED['whattodo'] = "send";}


if($UNTRUSTED['whattodo'] == "send"){
  
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
   
   if(empty($UNTRUSTED['allowHTML']))
     $UNTRUSTED['comment'] = filter_html($UNTRUSTED['comment']);

	 // convert links if not PUSH urls:
	 if(!(ereg("[PUSH]",$UNTRUSTED['comment']))){
     $UNTRUSTED['comment'] = preg_replace('#(\s(www.))([^\s]*)#', ' http://\\2\\3 ', $UNTRUSTED['comment']);
     $UNTRUSTED['comment'] = preg_replace('#((http|https|ftp|news|file)://)([^\s]*)#', '<a href="\\1\\3" target=_blank>\\1\\3</a>', $UNTRUSTED['comment']);
   }
      
   if(!(empty($UNTRUSTED['smilies'])))      
     $UNTRUSTED['comment'] = convert_smile($UNTRUSTED['comment']);	          
	                
   $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('".filter_sql($UNTRUSTED['comment'])."',".intval($channel).",'$timeof',".intval($myid).",".intval($saidto).")";
   $mydatabase->query($query);
   $quicknote ="";
}

?>
<html>
<head>
	<title>chat bot.</title>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >

</head>


<script type="text/javascript">

 ns4 = (document.layers)? true:false;
IE4 = (document.all)? true:false;

function openwindow(url){ 
 win4 = window.open(url, 'chat54057', 'width=580,height=400,menubar=no,scrollbars=1,resizable=1');
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
setTimeout('loaded=1',4000);
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
  s = s.replace(/\#/g,"*hash*");    
  s = s.replace(/\+/g,"*plus*");
  s = s.replace(/\%/g,"*percent*");
  return s;
}
function sayingwhat(){ 
   var newmess = document.chatter.comment.value;
   if ( (blockmessage!=1) && 
        (document.chatter.comment.value.length > 2) && 
        (document.chatter.previewsetting.value<3)){
    flag_imtyping = true;	
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
     if(document.chatter.comment.value.length < 2){
       window.parent.focus();
       window.focus();
      <?php if($UNTRUSTED['channelsplit'] != "__"){ ?>    
        setTimeout("document.chatter.comment.focus()",1000);
      <?php } ?>
    }
   }
}

function forcerefreshit(){
 document.chatter.typing.value="no";
 refreshit();
}
function refreshit(){
 if(document.chatter.typing.value=="no"){
   window.parent.connection.location="admin_connect.php?starttimeof=<?php echo $UNTRUSTED['starttimeof']; ?>";
   setTimeout("window.location='external_bot.php'",200);	
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


	blockmessage = 0;
	
<?php 
	if ( ($CSLH_Config['show_typing'] != "N") && ($UNTRUSTED['channelsplit'] != "__") ){ ?> 
    setInterval("sayingwhat()",5000);
<?php } ?>
 

</SCRIPT>
<body bgcolor=E0E8F0 onload=document.chatter.comment.focus(); >
<SCRIPT type="text/javascript">
loaded=0;
setTimeout('loaded=1',4000);
</SCRIPT>
<form action=external_bot.php name=chatter method=post>

<?php 

$query = "SELECT livehelp_operator_channels.id,livehelp_operator_channels.txtcolor,livehelp_operator_channels.txtcolor_alt,livehelp_operator_channels.channelcolor,livehelp_operator_channels.userid,livehelp_users.user_id,livehelp_users.isoperator,livehelp_users.username,livehelp_users.onchannel,livehelp_users.lastaction FROM livehelp_operator_channels,livehelp_users where livehelp_operator_channels.userid=livehelp_users.user_id AND livehelp_operator_channels.user_id=". intval($myid);
$mychannels = $mydatabase->query($query);	
$counting = $mychannels->numrows();

$botline = "<td bgcolor=000000><a name=top><img src=images/blank.gif width=1 height=1 border=0></a></td>";

while($channel_a = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){

 if($channel_a['isoperator'] == "Y"){
       // Operators can be on multiple channels so see if one of the channels they are on is one we are on.      
      $foundcommonchannel = 0;
      $query = "SELECT * FROM livehelp_operator_channels WHERE user_id=". intval($channel_a['user_id']);
      $mychannels2 = $mydatabase->query($query);
      while($rowof = $mychannels2->fetchRow(DB_FETCHMODE_ASSOC)){
         $query = "SELECT * FROM livehelp_operator_channels WHERE user_id=".intval($myid)." And channel=".intval($rowof['channel']);
         $counting = $mydatabase->query($query);        
         if($counting->numrows() != 0){ 
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
         $myusercolor = $usercolor;
         $mychannelcolor = $channel_a['channelcolor'];
         $txtcolor = $channel_a['channelcolor'];
    } else {          
         $txtcolor = "DDDDDD";
         $txtcolor = $channel_a['channelcolor'];         
   }
   
   $dakineuser = substr($channel_a['username'],0,15);

} 

?>
<?php if($UNTRUSTED['channelsplit'] == "__"){ 

if ($mychannels->numrows() == 0){
?>
<table bgcolor=FFFFFF width=100%><tr><td>
<?php echo $lang['noone_online']; ?>
</td></tr></table>
<?php
} else {
?>
<table bgcolor=FFFFFF width=100%><tr><td>
<b><?php echo $lang['choose']; ?></b></td></tr></table>

<?php } ?>
<form action=external_bot.php name=chatter method=post>
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
<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=<?php echo $mychannelcolor; ?>><tr><td width=90% align=center>
<table width=100% cellpadding=0 cellspacing=0 border=0 bgcolor=EFEFEF><tr><td><b><font color=<?php echo $myusercolor; ?> size=+1><?php echo $myuser; ?></font>:</b></td><td align=right><a href=javascript:showsmile()>Smilies</a> | <a href=#options>Options</a>  </tr></table>
<table cellpadding=0 cellspacing=0 border=0><tr><td>
<textarea cols=50 rows=3 name=comment ONKEYDOWN="return microsoftKeyPress()"></textarea>
</td><td valign=top>	
&nbsp;&nbsp;<b>Auto Focus:</b><input type=checkbox name=auto_focus value=1 <?php if(!(empty($UNTRUSTED['auto_focus']))) print "CHECKED"; ?>><br><br>
<input type=submit value="<?php echo $lang['send']; ?>"  onClick="return safeSubmit(this.form);">
</td></tr></table>
<b>SMILES:</b> <input type=checkbox name=smilies value=YES CHECKED> 
<b>HTML:</b> <input type=checkbox name=allowHTML value=YES> 
<b>TYPING PREVIEW:</b>
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
   print "if(num == $in){ document.chatter.comment.value= document.chatter.comment.value + '<img src=" . $row['message'] . " >'; document.chatter.editid.value='" . $row['id'] . "';  }\n";	    
   print " document.chatter.allowHTML.click(); \n";
   } else {
   print "if(num == $in){ document.chatter.comment.value= document.chatter.comment.value + '" . addslashes(stripslashes(stripslashes($row['message']))) . " '; document.chatter.editid.value='" . $row['id'] . "';  \n";	
   if($row['ishtml']) { print " document.chatter.allowHTML.click(); \n"; }
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
     print "if(num == $in){ document.chatter.comment.value=document.chatter.comment.value + '[PUSH]$row[message][/PUSH]';  }\n";	
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
<input type=hidden name=timeof value=<?php echo $timeof; ?> >
<input type=hidden name=editid value="">
</form>
<table width=100%>
<tr><td colspan=3>
<table width=100% bgcolor=EFEFEF><tr><td><b><a name=options><?php echo $lang['addtional']; ?>:</a></b></td><td align=right><a href=#top>Back to Top</a></td></tr></table>
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
<option value=-1 ><?php echo $lang['txt27']; ?>:</option>
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

</td>
</tr></table>
<?php } ?><br>
 
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>