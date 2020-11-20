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
require_once("visitor_common.php");
  
// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
 
  // get a channel for this user:
   $onchannel = createchannel($myid);
 
if(empty($UNTRUSTED['clear'])){ $clear = ""; } else { $clear = $UNTRUSTED['clear']; } 
if(empty($UNTRUSTED['starttimeof'])){ $starttimeof = ""; } else { $starttimeof = $UNTRUSTED['starttimeof']; } 
if(empty($UNTRUSTED['offset'])){ $offset = ""; } else { $offset = $UNTRUSTED['offset']; } 
if(empty($UNTRUSTED['department'])){ $department = 0; } else { $department = intval($UNTRUSTED['department']); } 
if(empty($UNTRUSTED['printit'])){ $printit = ""; } else { $printit = $UNTRUSTED['printit']; } 
if(empty($UNTRUSTED['tab'])){ $tab = ""; } else { $tab = $UNTRUSTED['tab']; } 
$message_test = date("YmdHis") -1;

// get department information...
$query = "SELECT * FROM livehelp_departments ";
if($department!=0)
   $query .= " WHERE recno=".intval($department);

$data_d = $mydatabase->query($query);  
$department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC);
$department = $department_a['recno'];
 $topbackground = $department_a['topbackground']; 
 $colorscheme = $department_a['colorscheme']; 

  $query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
  $people = $mydatabase->query($query);
  $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = $people['user_id'];
  $channel = $people['onchannel'];
  $isnamed = $people['isnamed'];
  $username = $people['username'];

$jsrn = get_jsrn($identity);


  $mytimeof = date("YmdHis");
  $query = "UPDATE livehelp_users set lastaction='$mytimeof' WHERE user_id='$myid' ";
  $mydatabase->query($query);

if($clear == "now"){
  // get the timestamp of the last message sent on this channel.
  $query = "SELECT timeof FROM livehelp_messages WHERE saidto='$myid' ORDER BY timeof DESC";	
  $messages = $mydatabase->query($query);
  $message = $messages->fetchRow(DB_FETCHMODE_ASSOC);
  $timeof = $message['timeof'] - 2;  
  $offset = $message['timeof'] - 2; 
  $starttimeof =  $message['timeof'] -2; 
} 

if($starttimeof != ""){ 
   $timeof = $starttimeof;
   $offset = $starttimeof;      
} else {   
   $timeof = $offset;  
}

if(empty($offset)){ $offset = 2; }

?>
<SCRIPT type="text/javascript">
var ismac = navigator.platform.indexOf('Mac');
//-----------------------------------------------------------------
// Update the control image. This is the image that the operators 
// use to communitate with the visitor. 
function csgetimage()
{	 
	
	imageloaded = 1;
	 // set a number to identify this page .
	 csID=Math.round(Math.random()*9999);
	 randu=Math.round(Math.random()*9999);
   
   cscontrol = new Image;
      	 
	 var u = 'image.php?randu=' + randu + '&what=messagecheck' + '&message_test=<?php echo $message_test; ?>'

	 
	 if (ismac > -1){
       document.getElementById("imageformac").src= u;
       document.getElementById("imageformac").onload = lookatimage;
    } else {
       cscontrol.src = u;
       cscontrol.onload = lookatimage;
    }    
   setTimeout('lookatimage()', 2000);
      
}

function lookatimage(){
	
	if(typeof(cscontrol) == 'undefined' ){
		setTimeout('refreshit()',9000); 
    return; 
 }  
   
	if(imageloaded == 1){
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
        imageloaded = 0;      
    } 
} 
	
csTimeout = 299; 
imageloaded = 0;
cscontrol = new Image;

<?php 
  if($CSLH_Config['refreshrate']==1) { ?>
setInterval('csgetimage()', 4000); 
<?php } ?>

function refreshit(){
 window.location.replace("user_chat_refresh.php?department=<?php echo $department; ?>&tab=<?php echo $tab; ?><?php echo $querystringadd; ?>");
}	

</SCRIPT>
<?php 
  if($CSLH_Config['refreshrate']!=1) { 
    print "<META HTTP-EQUIV=\"refresh\" content=\"". $CSLH_Config['refreshrate'] .";URL=user_chat_refresh.php?department=". $department ."&tab=". $tab . $querystringadd ."\">";
  }
  

$abort_counter_end = 2;

// load javascript.
if($printit != "Y"){
?>
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/xLayer.js"></SCRIPT> 
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/xBrowser.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript"  type="text/javascript" SRC="javascript/staticMenu.js"></SCRIPT> 
<script language="JavaScript"  type="text/javascript" src="javascript/dynapi/js/dynlayer.js"></script>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" > 
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;


setTimeout('up();',1000); 
 
   	              
function up(){
  scroll(1,10000000);
  if(skipfocus == 0){
     if(window.parent.bottomof.loaded){
        window.parent.bottomof.shouldifocus();
     }
  }
}

skipfocus = 1;
setTimeout('skipfocus=0;',2999);

 
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
    	fulloutput = '<TABLE BORDER=0 WIDTH=400><TR BGCOLOR=#000000><TD><TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0 BORDER=0 background=images/<?php echo $colorscheme; ?>/botbg.gif><TR><TD width=7><img src=images/blank.gif width=7 height=120></TD><TD valign=top><table width=400 background=images/<?php echo $colorscheme; ?>/botbg.gif><tr><td width=90%><?php echo str_replace("'","\\'",$lang['istyping']); ?></td><td width=10%><a href=javascript:istyping_Layer.hide();><b>X</b></a></td></tr></table><br>' + ouputtext + '</TD></TR></TABLE></TD></TR></TABLE>';
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

skipfocus = 1;
setTimeout('skipfocus=0;',2999);


ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
readyone = ready = false; // ready for onmouse overs (are the layers known yet)
 
ready = true;


</SCRIPT>
<SCRIPT LANGUAGE="JavaScript"> 
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;

function start() 
{       	
	if (IE4){
	  docWidth = document.body.clientWidth;
	} else {
	  docWidth = window.innerWidth;
	}
	myxvar = docWidth - 200;
	//if (myxvar < 5){ 
	  myxvar = 250;
	//}
	CreateStaticMenu("MenuDiv",  myxvar, 1);  

} 

function moveto(myblock,x,y)
{  
	myblock.xpos = x
	myblock.ypos = y
	myblock.left = myblock.xpos
	myblock.top = myblock.ypos
}

function expandit() {
  window.parent.resizeTo(window.screen.availWidth - 50,      
  window.screen.availHeight - 50); 
  if(IE4){
    // everything should be ok.. 
  } else {    
    setTimeout('refreshnow()',900);
  }
}
function refreshnow(){
 window.location.replace("user_chat_refresh.php?offset=<?php echo $offset; ?>&starttimeof=<?php echo $starttimeof; ?>&department=<?php echo $department; ?>&tab=<?php echo $tab; ?><?php echo $querystringadd; ?>");	
}

</SCRIPT>
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 background=images/<?php echo $colorscheme; ?>/mid_bk.gif>

<DIV ID="UserIsTypingDiv" STYLE="position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;"></DIV>
 
<DIV id="MenuDiv" STYLE="position:absolute;left:250;top:10;width:100;"> 
<table width=200><tr><td valign=top><a href=javascript:window.print()><img src=images/print.gif width=25 height=25 border=0  Alt="Print"></a>&nbsp;&nbsp;<a href=user_chat_refresh.php?offset=<?php echo $offset;  ?>&starttimeof=<?php echo $starttimeof; ?>&department=<?php echo $department; ?> ><img src=images/refresh.gif width=25 height=25 border=0  Alt="Refresh"></a>&nbsp;&nbsp;<a href=user_chat_refresh.php?clear=now&department=<?php echo $department; ?>><img src=images/clear.gif width=25 height=25 border=0  Alt="Clear"></a>&nbsp;&nbsp;<a href=livehelp.php?action=leave&department=<?php echo $department; ?> TARGET=_top><img src=images/exit.gif width=25 height=25 border=0  Alt="EXIT"></a></td></tr></table>       
</DIV>

<SCRIPT type="text/javascript">setTimeout('start()',900);</SCRIPT>
<pre>
















</pre>
<?php
}

// delete auto-invited messages:
$query = "DELETE FROM livehelp_messages WHERE saidfrom='0' AND saidto='$myid'";
$mydatabase->query($query);

$abort_counter = 0;
$abort_counter_end=2;
$timeofDHTML = $timeof;
$timeof_new = $timeof;

	$abort_counter++; 
	print showmessages($myid,"",$timeof,$channel); 
  ?><SCRIPT type="text/javascript">up(); setTimeout('up()',9);</SCRIPT><?php


	$query = "SELECT * FROM livehelp_users WHERE user_id='$myid' AND status='chat'";
        $alive = $mydatabase->query($query);
        if($alive->numrows() == 0){                        	        	
        	?><b><font color=990000>Session CLOSED!!</font></b><SCRIPT type="text/javascript">up()</SCRIPT><?php        	
        	$abort_counter = 99999;
        	?>
        	<SCRIPT type="text/javascript">
        	function redirectme(){
                  window.parent.location.replace("wentaway.php?department=<?php echo $UNTRUSTED['department']; ?>");
                }
                redirectme();
        	</SCRIPT>
        	<?php
          if(!($serversession))
        	   $mydatabase->close_connect();
        	exit; }

if(!($serversession))   
   $mydatabase->close_connect();
 print "  <img id=\"imageformac\" name=\"imageformac\" src=\"images/blank.gif\" width=10 heith=10 border=\"0\">";
?>
</body>
</html>