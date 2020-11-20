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
if($people->numrows() != 0){
 $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
 $UNTRUSTED['myid'] = $people['user_id'];
 $operator_id = $UNTRUSTED['myid'];
 $channel = $people['onchannel'];
}  
  
get_jsrn($identity);

$channelsplit = $UNTRUSTED['channelsplit'];
$channelsp = explode("__",$UNTRUSTED['channelsplit']);
$see = $channelsp[0];
//$see = $channelsp[1];

if(!(isset($UNTRUSTED['offset']))){ $UNTRUSTED['offset'] = ""; }
if(!(isset($UNTRUSTED['clear']))){ $UNTRUSTED['clear'] = ""; }
if(!(isset($UNTRUSTED['channel']))){ $UNTRUSTED['channel'] = 0; }
if(!(isset($UNTRUSTED['myid']))){ 
	 $UNTRUSTED['myid'] = 0; 
} else {
	 $myid = intval($UNTRUSTED['myid']);
}
 
if(!(isset($UNTRUSTED['starttimeof']))){ $UNTRUSTED['starttimeof'] = 0; }


if(!(empty($UNTRUSTED['setchattype']))){
 $query = "UPDATE livehelp_users SET chattype='xmlhttp' WHERE sessionid='".$identity['SESSIONID']."'";	
 $mydatabase->query($query);
}

$timeof = date("YmdHis");
$timeof_load = $timeof;
$prev = mktime ( date("H"), date("i")-30, date("s"), date("m"), date("d"), date("Y") );
$oldtime = date("YmdHis",$prev);
 
if($UNTRUSTED['clear'] == "now"){
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
?>

<HTML>
<head> 
<title>Admin Chat xmlhttp</title> 
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<script src="javascript/xmlhttp.js" type="text/javascript"></script> 
<script type="text/javascript"> 
var HTMLtimeof = 0;
var LAYERtimeof = 0;
var whatissaid  = new Array(100);
/**
  * parse the deliminated string of messages from XMLHttpRequest.
  * This will be converted to XML in next version of CSLH.
  *
  *@param string text to parse.
  */
function ExecRes(textstring){

	var chatelement = document.getElementById('currentchat'); 
  
  var messages=new Array();

  //textstring = " messages[0] = new Array();  messages[0][0]=2004010101;   messages[0][1]=1;  messages[0][2]=\"HTML\";  messages[0][3]=\"test\"; ";
  eval(textstring);
	
	for (var i=0;i<messages.length;i++){	
    res_timeof = messages[i][0];
	  res_jsrn = messages[i][1];
	  res_typeof = messages[i][2];	  
    res_message = messages[i][3];    
  
      // is it defined:
    if (typeof chatelement!='undefined') { 
    	
  	if(res_typeof=="HTML"){  
	    if( res_timeof>HTMLtimeof ){    
         try { chatelement.innerHTML = chatelement.innerHTML + unescape(res_message); }
         catch(fucken_IE) { }         
         HTMLtimeof = res_timeof;
         whatissaid[res_jsrn] = 'nullstring';	
         update_typing();         
         up();         
       }
    }    
	  if(res_typeof=="LAYER"){  
	    if(res_timeof>LAYERtimeof){ 
       whatissaid[res_jsrn] = res_message;	
       LAYERtimeof = res_timeof;       
       update_typing();
     }
    } 
   }
  }
}

/**
  * loads a XMLHTTP response into parseresponse
  *
  *@param string url to request
  *@see parseresponse()
  */ 
function update_xmlhttp() { 
     // account for cache..
	   randu=Math.round(Math.random()*9999);
     extra = "";
 
     sPostData = 'op=yes&see=<?php echo $see; ?>&whattodo=messages&rand='+ randu + '&HTML=' + HTMLtimeof + '&LAYER=' + LAYERtimeof + extra;
     sURL = 'xmlhttp.php';
     fullurl =  'xmlhttp.php?' + sPostData;
     //PostForm(sURL, sPostData)
     GETForm(fullurl);
         
} 
setInterval('update_xmlhttp()',2100);

</SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/xLayer.js"></SCRIPT> 
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/xBrowser.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/staticMenu.js"></SCRIPT> 
<script language="JavaScript"  type="text/javascript" src="javascript/dynapi/js/dynlayer.js"></script>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" > 
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;


setTimeout('up();',1000); 
 
var ismac = navigator.platform.indexOf('Mac');
 ismac = 1;   	              
function up(){
  scroll(1,10000000);
  if(window.parent.bottomof.document.chatter.auto_focus.checked)
    window.parent.bottomof.shouldifocus();  
}        

</SCRIPT>
<SCRIPT type="text/javascript">
myBrowser = new xBrowser(); 
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
      if( (whatissaid[i]!='nullstring') && (whatissaid[i]!='nullstring ')){
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

ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
readyone = ready = false; // ready for onmouse overs (are the layers known yet)
 
ready = true;


</SCRIPT>
</HEAD>
<body bgcolor="#<?php echo $color_background;?>">
<DIV ID="UserIsTypingDiv" STYLE="position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;"></DIV>
<pre>


























</pre>
<span id="currentchat"><?php print showmessages($operator_id,"",$timeof,$see);	?></span>

<?php
if(!($serversession))
  $mydatabase->close_connect();
exit;
?>
</HTML>