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
$query = "SELECT user_id,onchannel FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ORDERED);
$myid = $people[0];
$channel = $people[1];
$operator_id = $myid;
  
$jsrn = get_jsrn($identity);

if(!(isset($UNTRUSTED['offset']))){ $UNTRUSTED['offset'] = ""; }
if(!(isset($UNTRUSTED['clear']))){ $UNTRUSTED['clear'] = ""; }
if(!(isset($UNTRUSTED['cleartonow']))){ $UNTRUSTED['cleartonow'] = ""; }
if(!(isset($UNTRUSTED['channel']))){ $UNTRUSTED['channel'] = 0; }
if(!(isset($UNTRUSTED['myid']))){ 
	 $UNTRUSTED['myid'] = 0; 
} else {
	 $myid = intval($UNTRUSTED['myid']);
}

if(!(isset($UNTRUSTED['see']))){ 
	 $UNTRUSTED['see'] = 0; 
} else {
  $see = intval($UNTRUSTED['see']);	
}
if(!(isset($UNTRUSTED['starttimeof']))){ $UNTRUSTED['starttimeof'] = 0; }

$timeof = date("YmdHis");
$timeof_load = $timeof;
$prev = mktime ( date("H"), date("i")-30, date("s"), date("m"), date("d"), date("Y") );
$oldtime = date("YmdHis",$prev);
$timeof = $oldtime;

if( ($UNTRUSTED['clear'] == "now") || ($UNTRUSTED['cleartonow'] == 1) ){
  // get the timestamp of the last message sent on this channel.
  $query = "SELECT timeof FROM livehelp_messages ORDER BY timeof DESC";	
  $messages = $mydatabase->query($query);
  $message = $messages->fetchRow(DB_FETCHMODE_ORDERED);
  $timeof = $message[0] - 2;  
  $offset = $message[0] - 2; 
  $starttimeof =  $message[0] -2; 
} 
if(isset($starttimeof)){
  $timeof = $starttimeof;
}

if(!(empty($UNTRUSTED['setchattype']))){
 $query = "UPDATE livehelp_users SET chattype='refresh' WHERE sessionid='".$identity['SESSIONID']."'";	
 $mydatabase->query($query);
}

// if no one is talking end here also update security:
$query = "SELECT livehelp_operator_channels.txtcolor,livehelp_operator_channels.userid,livehelp_users.username,livehelp_users.onchannel,livehelp_users.lastaction FROM livehelp_operator_channels,livehelp_users where livehelp_operator_channels.userid=livehelp_users.user_id AND livehelp_operator_channels.user_id=".intval($myid);
$mychannels = $mydatabase->query($query);
if($mychannels->numrows() == 0){
   $query = "SELECT * FROM livehelp_users WHERE user_id=" . intval($myid);
   $person_a = $mydatabase->query($query);
   $person = $person_a->fetchRow(DB_FETCHMODE_ASSOC);
   $visits = intval($person['visits']);         
   if( ($visits % 35) == 34){
      $visits++;
      $query = "UPDATE livehelp_users SET visits=$visits WHERE user_id=".intval($myid);
      $mydatabase->query($query);
      ?><SCRIPT type="text/javascript"> setTimeout('security();',1000); 
function security(){
     // for security reasons this page is here. If a major hack is discovered it will be listed here
    url = '<?php print "http://www.craftysyntax.com/remote/updates.php"; ?>'
    window.open(url,'rdfdsf','toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes');
}  
 
var sleeping=true;
</SCRIPT>
<?php } 
 print "<SCRIPT type=\"text/javascript\"> var sleeping=true; </SCRIPT><body bgcolor=$color_background>" . $lang['noone_online2'];
 print "  <img id=\"imageformac\" name=\"imageformac\" src=\"images/blank.gif\" width=10 heith=10 border=\"0\">"; 
 exit;
} ?>
<HTML>
<head> 
<title>Admin Chat Refresh</title> 
<link title="new" rel="stylesheet" href="style.css" type="text/css">
  <meta http-equiv="Content-Type" content="text/html; charset="<?php echo $lang['charset']; ?>" />
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/xLayer.js"></SCRIPT> 
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/xBrowser.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/staticMenu.js"></SCRIPT> 
<script language="JavaScript"  type="text/javascript" src="javascript/dynapi/js/dynlayer.js"></script>
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript"> 
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;
var sleeping=false;
setTimeout('up();',1000); 
 
var ismac = navigator.platform.indexOf('Mac');	              
function up(){
  scroll(1,10000000);
  if(skipfocus == 0){
    if(window.parent.bottomof.loaded)
      window.parent.bottomof.shouldifocus();
  }
}        

myBrowser = new xBrowser();
skipfocus = 1;     
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;	
starttyping_layer_exists = false;
var whatissaid  = new Array(100);
for(i=0;i<100;i++){
  whatissaid[i] = 'nullstring';
}

// start up the is typing layer... 
//--------------------------------------------------------------          
function starttyping(){           
            if (IE4){
    	       docWidth = document.body.clientWidth;
 	    } else {
 	       docWidth = window.innerWidth;
	    }
 
	     myxvar2 = 50;

            istyping_Layer = new DynLayer('UserIsTypingDiv');                     
 	    CreateStaticMenu2("UserIsTypingDiv",  myxvar2, 5); 
            starttyping_layer_exists = true;
}

// update the istyping layer.
//---------------------------------------------------------------
function update_typing(){
  if(starttyping_layer_exists == true){     	              
    ouputtext = "";
    foundtext = 0;
    for(i=0;i<100;i++){
      if(whatissaid[i]!='nullstring'){
         ouputtext = ouputtext + whatissaid[i];
         foundtext = 1;
      }
    }
    if(foundtext == 1){
    	fulloutput = '<TABLE BORDER=0 WIDTH=400><TR BGCOLOR=#000000><TD><TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD width=1 BGCOLOR=#D4DCF2><img src=images/blank.gif width=7 height=120></TD><TD BGCOLOR=#D4DCF2 valign=top><table width=400 bgoclor=DFDFDF><tr><td width=90%><?php echo str_replace("'","\\'",$lang['istyping']); ?></td><td width=10%><a href=javascript:istyping_Layer.hide();><b>X</b></a></td></tr></table><br>' + ouputtext + '</TD></TR></TABLE></TD></TR></TABLE>';
        istyping_Layer.show();
    	istyping_Layer.write(fulloutput);
    } else {
    	istyping_Layer.hide();
    }
  }	
}
 
 setTimeout('starttyping()',2000);	        


function delay(gap){ /* gap is in millisecs */
 var then,now; then=new Date().getTime();
 now=then;
 while((now-then)<gap){  now=new Date().getTime();}
}

function up(){
  scroll(1,10000000);
}
<?php if( ($CSLH_Config['use_flush'] == "no") || (isset($offset)) ){ ?>
skipfocus = 1;
setTimeout('skipfocus=0;',2999);
<?php } else { ?>
skipfocus = 0;
<?php } ?>

ns4 = (document.layers)? 1 : 0;
ie4 = (document.all)? 1 : 0;
readyone = ready = false; // ready for onmouse overs (are the layers known yet)
 
ready = true;


</SCRIPT>
<?php
	$message_test = date("YmdHis") -3;
?>
<SCRIPT type="text/javascript">
//-----------------------------------------------------------------
// Update the control image. This is the image that the operators 
// use to communitate with the visitor. 
function csgetimage()
{	 
	 // set a number to identify this page .
	 csID=Math.round(Math.random()*9999);
	 randu=Math.round(Math.random()*9999);
   
   cscontrol = new Image;
 
	 var u = 'admin_image.php?randu=' + randu + '&what=messagecheck' + '&message_test=<?php echo $message_test; ?>'
 
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
   	
	 	if (ismac > -1)
      w = document.getElementById("imageformac").width;
    else
      w = cscontrol.width;   	 
  
   if( (w == 55) || (w == 0)){
           delete cscontrol;
           imageloaded = 0; 	
  	   refreshit();
	 } 	        	            
        delete cscontrol;                

} 
	
csTimeout = 299; 
imageloaded = 0;
cscontrol = new Image;

<?php 
if($CSLH_Config['refreshrate']==1) { ?>
setInterval('csgetimage()', 3500); 
<?php 
} 
?>


function refreshit(){
 window.location.replace("admin_chat_refresh.php");
}	

</SCRIPT>
<?php

  if($CSLH_Config['refreshrate']!=1) { 
    print "<META HTTP-EQUIV=\"refresh\" content=\"". $CSLH_Config['refreshrate'] .";URL=admin_chat_refresh.php\">";
  }

 print "  <img id=\"imageformac\" name=\"imageformac\" src=\"images/blank.gif\" width=10 heith=10 border=\"0\">"; 

?>
<body bgcolor=<?php echo $color_background;?>>
<DIV ID="UserIsTypingDiv" STYLE="position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;"></DIV>
<br>
<?php

$abort_counter = 1;
$abort_counter_end = 2;
 
 
$abort_counter = 0; 
$timeof_new = 0;
$timeofDHTML = $timeof;
if(!(empty($see))){
 $timeof = showmessages($operator_id,"",$timeof,$see);
 $timeofDHTML = showmessages($operator_id,"writediv",$timeofDHTML,$see);
 $timeofDHTML = $timeof = date("YmdHis") -1;
}

	print showmessages($operator_id,"",$timeof,"");
	$timeof = $timeof_new;  
 
  
 	
	 $offset = "";
	 ?>
	 <SCRIPT type="text/javascript">up(); setTimeout('up()',9);</SCRIPT>
	 <?php
	$abort_counter++;	


 

if(!($serversession))
  $mydatabase->close_connect();
exit;
?>