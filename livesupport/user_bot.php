<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2006 Eric Gerdes   (http://www.craftysyntax.com )
// --------------------------------------------------------------------------
// $              CVS will be released with version 3.1.0                   $
// $    Please check http://www.craftysyntax.com/ or REGISTER your program for updates  $
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
// -------------------------------------------------------------------------- 
// BIG NOTE:
//     At the time of the release of this version of CSLH, Version 3.1.0 
//     which is a more modular, extendable , “skinable” version of CSLH
//     was being developed.. please visit http://www.craftysyntax.com to see if it was released! 
//===========================================================================
require_once("visitor_common.php");
  
// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);  
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isnamed = $people['isnamed'];

// get a channel for this user:
$onchannel = createchannel($myid);

// get department information...
$where="";
if(!(isset($UNTRUSTED['department']))){ $UNTRUSTED['department'] = ""; }
if(!(isset($UNTRUSTED['printit']))){ $UNTRUSTED['printit'] = ""; }
if($UNTRUSTED['department']!=""){ $where = " WHERE recno=". intval($UNTRUSTED['department']); }
$query = "SELECT * FROM livehelp_departments $where ";
$data_d = $mydatabase->query($query);  
$department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC);
$department = $department_a['recno'];
$colorscheme = $department_a['colorscheme'];
$smiles = $department_a['smiles'];
 
if(!(empty($UNTRUSTED['enditall']))){
	?>
	<SCRIPT type="text/javascript">
		 window.parent.location.replace("wentaway.php?savepage=1&department=<?php echo $UNTRUSTED['department']; ?>");
  </SCRIPT> 
  <?php
  exit;
}

if (empty($UNTRUSTED['convertsmile']))
   $UNTRUSTED['convertsmile'] = "ON";

if($smiles=="N")
  $UNTRUSTED['convertsmile'] = "OFF";

if(!(empty($UNTRUSTED['comment']))){ 
   $comment = filter_html($UNTRUSTED['comment']);

	 // convert links :
   $comment = preg_replace('#(\s(www.))([^\s]*)#', ' http://\\2\\3 ', $comment);
   $comment = preg_replace('#((http|https|ftp|news|file)://)([^\s]*)#', '<a href="\\1\\3" target=_blank>\\1\\3</a>', $comment);
  
   
   if($UNTRUSTED['convertsmile'] !="OFF")
     $comment = convert_smile($comment);

   $timeof = date("YmdHis");
   if(empty($UNTRUSTED['saidto'])) $UNTRUSTED['saidto'] = 0;
   $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('".filter_sql($comment)."',". intval($UNTRUSTED['channel']).",'$timeof',".intval($myid).",".intval($UNTRUSTED['saidto']).")";	
   $mydatabase->query($query);
   $query = "DELETE FROM livehelp_messages WHERE typeof='writediv'";
   $mydatabase->query($query);   
   $query = "UPDATE livehelp_users set lastaction='$timeof' WHERE sessionid='".$identity['SESSIONID']."'";	
   $mydatabase->query($query);
} 
?>
<html>
<SCRIPT  type="text/javascript">
var ismac = navigator.platform.indexOf('Mac');	
mymessagesofar = "";
 
 function showsmile(){
	msgWindow = open('smile.php','smileimages','width=500,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=no,scrollbars=yes','POPUP');
	if (msgWindow.opener == null){
		msgWindow.opener = self;
	}
	if (msgWindow.opener.myform == null){
        msgWindow.opener.myform = document.sendform;
	}
	msgWindow.focus();
	
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
  if (document.chatter.comment.value.length > 2){
   if( (blockmessage != 1) && (mymessagesofar != newmess)){
    var u = 'image.php?' + 
					'what=startedtyping' + 
					'&channelsplit=' + document.chatter.channel.value + 
					'&fromwho=' + document.chatter.myid.value + 
					'&sayingwhat=' + noamps(document.chatter.comment.value);
    cscontrol.src = u; 
    mymessagesofar = newmess;
   }
  }
}

function expandit() {
  window.parent.resizeTo(window.screen.availWidth - 50,      
  window.screen.availHeight - 50); 
}

function netscapeKeyPress(e) {
     if (e.which == 13)
           safeSubmit(document.chatter);
}

function microsoftKeyPress() {
  ns4 = (document.layers)? true:false;
  ie4 = (document.all)? true:false;
  if(ie4){
    if (window.event.keyCode == 13)
           safeSubmit(document.chatter);
  } 
}

function safeSubmit(f) {
	for (i=1; i<f.elements.length; i++) {
		if (f.elements[i].type == 'submit') {
			f.elements[i].disabled = true;
		}
	}
    var u = 'image.php?' + 
					'what=startedtyping' + 
					'&channelsplit=' + document.chatter.channel.value + 
					'&fromwho=' + document.chatter.myid.value + 
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

function shouldifocus(){	  
   if( (flag_imtyping == false) && (loaded==1) ){
     var newmess = document.chatter.comment.value;
     if(document.chatter.comment.value.length < 2){
       window.focus();
       setTimeout("document.chatter.comment.focus()",1000);
    }
   }
}

function pingchat() {
	 pingcsID=Math.round(Math.random()*99999);
   pingcontrol = new Image;      	 
	 var u = 'image.php?randu=' + pingcsID + '&what=pingchat'
	 pingcontrol.src = u;
	 setTimeout('delete pingcontrol', 200);
}

ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;


NS4 = (document.layers);
IE4 = (document.all)? true:false; 
if (NS4) 
   document.captureEvents(Event.KEYPRESS);
if(!(IE4))   
   document.onkeypress = netscapeKeyPress;

cscontrol= new Image;
pingcontrol= new Image;
blockmessage = 0;
setInterval('pingchat()', 5000); 
loaded=0;
setTimeout('loaded=1',4000);
var flag_imtyping = false;

</SCRIPT>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 background=images/<?php echo $colorscheme; ?>/botbg.gif onload=init()>

<table cellpadding=0 cellspacing=0 border=0 width=100%>
<tr>
<td width=1% valign=top><img src=images/<?php echo $colorscheme; ?>/qmark.gif width=74 height=21 border=0></td>
<td width=99% background=images/<?php echo $colorscheme; ?>/bot_bg.gif valign=top>
<?php
// get department information...
$where="";
if(empty($UNTRUSTED['department'])) $UNTRUSTED['department']= "";
if($UNTRUSTED['department']!=""){ $where = " WHERE recno=" . intval($UNTRUSTED['department']); }
$query = "SELECT * FROM livehelp_departments $where ";
$data_d = $mydatabase->query($query);  
$department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC);

$query = "SELECT * FROM livehelp_operator_channels WHERE channel=" . intval($onchannel) . " AND userid=".intval($myid);
$counting = $mydatabase->query($query);
if($counting->numrows() == 0){
 ?>
 <table width=100% cellpadding=0 cellspacing=0 border=0 background=images/blank.gif>
<tr>
<td background=images/blank.gif>
  <img src=images/blank.gif width=20 height=10 border=0></td>
</tr>
 <td background=images/blank.gif><font color=999999><?php echo $lang['notchat']; ?></font></td></tr></table>
 <SCRIPT  type="text/javascript">
 function init(){
   // nothing	
 }
 </SCRIPT>
 
<SCRIPT  type="text/javascript">
//-----------------------------------------------------------------
// Update the control image. This is the image that the operators 
// use to communitate with the visitor. 
function csgetimage()
{	 
	 // set a number to identify this page .
	 csID=Math.round(Math.random()*9999);
	 randu=Math.round(Math.random()*9999);
   cscontrol = new Image;
      	 
	 var u = 'image.php?randu=' + randu + '&what=talkative'
  
    if (ismac > -1){
       document.getElementById("imageformac").src= u;
       document.getElementById("imageformac").onload = lookatimage;
    } else {
       cscontrol.src = u;
       cscontrol.onload = lookatimage;
    }  
    
      
}

function lookatimage(){
	
	if(typeof(cscontrol) == 'undefined' ){
		setTimeout('refreshit()',9000); 
    return; 
 }   
 
	 var w = cscontrol.width;      	 
   if( (w == 55) || (w == 0)){
       delete cscontrol; 	
  	   refreshit();
	 } 	        	            
      delete cscontrol;                 
 
} 
	
csTimeout = 299; 
cscontrol = new Image;

setInterval('csgetimage()', 7000); 


function refreshit(){
 window.location.replace("user_bot.php");
}	
  
setTimeout("refreshit();",99920);

</SCRIPT>
 
 <br>
<?php } else { ?>
<table width=100% cellpadding=0 cellspacing=0 border=0 background=images/blank.gif>
<tr>
<td background=images/blank.gif>
  <img src=images/blank.gif width=20 height=10 border=0></td>
</tr>
<tr><td valign=top>
<FORM action=user_bot.php  name=chatter METHOD=POST name=chatter>
<table cellspacing=0 cellpadding=0 border=0><tr>
<td><b><?php echo $lang['ask']; ?></b></td>
<td><textarea cols=40 rows=2 name=comment ONKEYDOWN="return microsoftKeyPress()"></textarea></td>
<td><table cellspacing=0 cellpadding=0 border=0><tr>
 
<?php 
 if($smiles!="N"){  
   if ($UNTRUSTED['convertsmile'] == "ON"){ 
   	print "<td><a href=javascript:showsmile()><img src=images/icon_smile.gif border=0>&nbsp;".$lang['view']."</a></td></tr><tr><td><font color=009900><b>".$lang['ON']."</b></font> / <a href=user_bot.php?convertsmile=OFF&department=$department>".$lang['OFF']."</a></td></tr></table></td>";
   } else { 
   	print "<td><img src=images/icon_nosmile.gif border=0>&nbsp;<s>".$lang['view']."</s></td></tr><tr><td><a href=user_bot.php?department=$department>".$lang['ON'] ."</a> / <font color=990000><b>".$lang['OFF']."</b></font></td></tr></table></td>";  }
  } 
   
 ?>
<td>&nbsp;<input type=submit value="<?php echo $lang['SAY']; ?>"  onClick="return safeSubmit(this.form);"></td>
<td>&nbsp;
<input type=submit name=enditall value="<?php echo $lang['exit']; ?>" onClick="return confirm('Are you sure you want to end this Chat?')">
<input type=hidden name=channel value=<?php echo $channel; ?> >
<input type=hidden name=department value=<?php echo $department; ?> >
<input type=hidden name=myid value=<?php echo $myid; ?> >
<input type=hidden name=typing value="no">
<?php 
if ($UNTRUSTED['convertsmile'] == "ON")
  	print "<input type=hidden name=convertsmile value=ON>";
   else
    print "<input type=hidden name=convertsmile value=OFF>";
?>	
</td></tr></table>
</FORM>
</td></tr></table>
<SCRIPT  type="text/javascript">
<?php if ($CSLH_Config['show_typing'] != "N"){ print " setInterval(\"sayingwhat()\",5000); "; } ?>

function init(){
  document.chatter.comment.focus();	
}
</SCRIPT>
<?php } ?>
</td>
</tr>
</table>
<br><img id="imageformac" name="imageformac" src=images/blank.gif border="0">
</body>
</html>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>