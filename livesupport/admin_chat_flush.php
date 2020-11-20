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

if($serversession){
  $autostart = @ini_get('session.auto_start');
  if($autostart!=0){		
   session_write_close();
  }
}
   
// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
if($people->numrows() != 0){
 $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
 $UNTRUSTED['myid'] = $people['user_id'];
 $operator_id = $UNTRUSTED['myid'];
 $channel = $people['onchannel']; 
}  
  
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

if(!(empty($UNTRUSTED['setchattype']))){
 $query = "UPDATE livehelp_users SET chattype='flush' WHERE sessionid='".$identity['SESSIONID']."'";	
 $mydatabase->query($query);
}

$timeof = date("YmdHis");
$timeof_load = $timeof;
$prev = mktime ( date("H"), date("i")-30, date("s"), date("m"), date("d"), date("Y") );
$oldtime = date("YmdHis",$prev);

if($UNTRUSTED['offset'] != ""){ $timeof = $oldtime; }

if( ($UNTRUSTED['clear'] == "now") || ($UNTRUSTED['cleartonow'] == 1) ){
  // get the timestamp of the last message sent on this channel.
  $query = "SELECT * FROM livehelp_messages ORDER BY timeof DESC";	
  $messages = $mydatabase->query($query);
  $message = $messages->fetchRow(DB_FETCHMODE_ASSOC);
  $timeof = $message['timeof'] - 2;  
  $offset = $message['timeof'] - 2; 
  $starttimeof =  $message['timeof'] -2; 
} 
if(isset($starttimeof)){
  $timeof = $starttimeof;
}
  
//turn off max execution timeout if not refreshing..
// unset max execution time
@ini_set("max_execution_time",0);


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
}

 print "  <img id=\"imageformac\" name=\"imageformac\" src=images/blank.gif border=\"0\">";
?>
<HTML>
<head> 
<title>Admin Chat Flush</title> 
<body bgcolor=<?php echo $color_background;?>>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" SRC="javascript/xLayer.js"></SCRIPT> 
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" SRC="javascript/xBrowser.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" SRC="javascript/staticMenu.js"></SCRIPT> 
<script language="JavaScript" type="text/javascript" src="javascript/dynapi/js/dynlayer.js"></script>
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript"> 
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;
var sleeping=false;

setTimeout('up();',1000); 
 
var ismac = navigator.platform.indexOf('Mac');
 ismac = 1;   	              
function up(){
  scroll(1,10000000);
  if(skipfocus == 0){
      if( (window.parent.bottomof.loaded) && (window.parent.rooms.document.mine.auto_focus.checked) )
      window.parent.bottomof.shouldifocus();
  }
}        
<?php if(isset($UNTRUSTED['skipfocus'])){ ?>
skipfocus = 1;
setTimeout('skipfocus=0;',2999);
<?php } else { ?>
skipfocus = 0;
<?php } ?>

</SCRIPT>
<SCRIPT type="text/javascript">
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
<?php if( ($CSLH_Config['use_flush'] == "no")  ){ ?>
skipfocus = 1;
setTimeout('skipfocus=0;',2999);
<?php } else { ?>
skipfocus = 0;
<?php } ?>

ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
readyone = ready = false; // ready for onmouse overs (are the layers known yet)
 
ready = true;


</SCRIPT>
 
<DIV ID="UserIsTypingDiv" STYLE="position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;"></DIV>
<br>
<?php

$abort_counter = 1;
// seconds timeout for IE.
$abort_counter_end = ($CSLH_Config['maxexe']/2);
 
if(empty($offset)){  
// load the buffer 
sendbuffer();
}
 
$abort_counter = 0; 
$timeof_new = 0;
$timeofDHTML = $timeof;
$timeof =0;
$DHTMLtimeof = 0;
if(!(empty($see))){
 print showmessages($operator_id,"",$timeof,$see);
 print showmessages($operator_id,"writediv",$timeofDHTML,$see);
 $timeofDHTML = $timeof = date("YmdHis") -1;
}
while($abort_counter != $abort_counter_end)
{
	$buffer_html = showmessages($operator_id,"",$timeof,""); 
  $buffer_layer = showmessages($operator_id,"writediv",$timeofDHTML,""); 
  sleep(1); 
  $offset = "";
	// load the buffer 	
  if( ($buffer_html != "") || ($buffer_layer!="") ){
  	 print $buffer_html;
  	 if($buffer_html != ""){
  	    ?>
  	     <SCRIPT type="text/javascript">
  	       whatissaid[<?php echo $jsrn;?>]='nullstring';
  	        
  	     </SCRIPT>
  	    <?php  	 
  	 }   
  	 print $buffer_layer;
  	 	?><SCRIPT type="text/javascript">up(); setTimeout('up()',9);</SCRIPT><?php
     sendbuffer(); 
     	?><SCRIPT type="text/javascript">up(); setTimeout('up()',9);</SCRIPT><?php
	}
	$abort_counter++;	
}
 
  ?>
  <br><b>Refreshing...</b><a href=admin_chat_flush.php>click here</a><br><br>
  <br>
  <br>  
  <SCRIPT type="text/javascript">
  skipfocus = 1;
  up()
  function reloadit(){
    window.location.replace("admin_chat_flush.php?skipfocus=1");	
  }
  setTimeout('reloadit()', 999);
  </SCRIPT>

<?php 
if(!($serversession)) 
   $mydatabase->close_connect();
exit;
?>