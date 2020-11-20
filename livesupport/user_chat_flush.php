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

if($serversession){
  $autostart = @ini_get('session.auto_start');
  if($autostart!=0){		
   session_write_close();
  }
}

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
 
// get a channel for this user:
$onchannel = createchannel($myid);
 
if(empty($UNTRUSTED['offset'])){ $UNTRUSTED['offset'] = ""; }
if(empty($UNTRUSTED['department'])){ $department = 0; } else { $department = intval($UNTRUSTED['department']); }
if(empty($UNTRUSTED['printit'])){ $UNTRUSTED['printit'] = ""; } 
if(empty($UNTRUSTED['timeof'])){ $UNTRUSTED['timeof'] = 0; } 
if(empty($UNTRUSTED['tab'])){ $UNTRUSTED['tab'] = "";  }
if(empty($UNTRUSTED['clear'])){ $UNTRUSTED['clear'] = "";  }

 
$starttimeof = "";
 
$message_test = date("YmdHis") -1;

// get department information...
$where="";
$query = "SELECT * FROM livehelp_departments ";
if($department!=""){ 
	 $query .= " WHERE recno=".intval($department); 
}
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
  $query = "UPDATE livehelp_users set lastaction='$mytimeof' WHERE user_id=".intval($myid);
  $mydatabase->query($query);

if($UNTRUSTED['clear'] == "now"){
  // get the timestamp of the last message sent on this channel.
  $query = "SELECT * FROM livehelp_messages WHERE saidto=".intval($myid)." ORDER BY timeof DESC";	
  $messages = $mydatabase->query($query);
  $message = $messages->fetchRow(DB_FETCHMODE_ASSOC);
  $timeof = $message['timeof'] - 2;  
  $offset = $message['timeof'] - 2; 
  $starttimeof =  $message['timeof'] -2; 
} 

if($starttimeof != ""){ 
   $timeof = $starttimeof;
   $offset = $starttimeof;      
}  

if(empty($offset)){ $offset = 2; }


//turn off max execution timeout if not refreshing..
// unset max execution time
@ini_set("max_execution_time",0);


// see if anyone is online . if not send them to the leave a message page..
$query = "SELECT * 
          FROM livehelp_users,livehelp_operator_departments 
          WHERE livehelp_users.user_id=livehelp_operator_departments.user_id
           AND livehelp_users.isonline='Y'
           AND livehelp_users.isoperator='Y' 
           AND livehelp_operator_departments.department=". intval($department);
$data = $mydatabase->query($query);  
if($data->numrows() == 0){
 ?>
    <SCRIPT type="text/javascript">
    window.parent.location.replace("livehelp.php?tab=1&doubleframe=yes&pageurl=offline.php&department=<?php echo $department; ?>");       
    </SCRIPT>
 <?php
if(!($serversession))
    $mydatabase->close_connect();
  exit;
 } 
 
 
$abort_counter = 1;
$abort_counter_end = ($CSLH_Config['maxexe']/2);

// load javascript.
if($UNTRUSTED['printit'] != "Y"){
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
<?php if(($UNTRUSTED['offset'] != "")){ ?>
skipfocus = 1;
setTimeout('skipfocus=0;',2999);
<?php } else { ?>
skipfocus = 0;
<?php } ?>
 
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
<?php if(isset($UNTRUSTED['offset'])){ ?>
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
 window.location.replace("user_chat_flush.php?offset=<?php echo $UNTRUSTED['offset']; ?>&department=<?php echo $department; ?><?php echo $querystringadd; ?>");	
}
function printit(){
   url = 'user_chat_flush.php?offset=2&printit=Y&department=<?php echo $department; ?><?php echo $querystringadd; ?>';
   window.open(url, 'chat54087', 'width=572,height=320,menubar=yes,scrollbars=1,resizable=1');	
}
</SCRIPT>

<DIV ID="UserIsTypingDiv" STYLE="position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;"></DIV>
 
<DIV id="MenuDiv" STYLE="position:absolute;left:250;top:10;width:100;"> 
<table width=200><tr><td nowrap=nowrap valign=top><a href=javascript:window.print()><img src=images/print.gif width=25 height=25 border=0  Alt="Print"></a>&nbsp;&nbsp;<a href=user_chat_flush.php?department=<?php echo $department; ?> ><img src=images/refresh.gif width=25 height=25 border=0  Alt="Refresh"></a>&nbsp;&nbsp;<a href=user_chat_flush.php?clear=now&department=<?php echo $department; ?>><img src=images/clear.gif width=25 height=25 border=0  Alt="Clear"></a>&nbsp;&nbsp;<a href=livehelp.php?action=leave&department=<?php echo $department; ?> TARGET=_top><img src=images/exit.gif width=25 height=25 border=0  Alt="EXIT"></a></td></tr></table>       
</DIV>

<SCRIPT type="text/javascript">setTimeout('start()',900);</SCRIPT>
<pre>
















</pre>
<?php
}
?>
<html>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body marginheight=0 marginwidth=0  topmargin=0 background=images/<?php echo $colorscheme; ?>/mid_bk.gif>

<?php
 
// delete auto-invited messages:
$query = "DELETE FROM livehelp_messages WHERE saidfrom=0 AND saidto=".intval($myid);
$mydatabase->query($query);

$abort_counter = 0;
sendbuffer(); 
$timeof =0;
$DHTMLtimeof = 0;
while($abort_counter != $abort_counter_end){	
	$abort_counter++; 
	$buffer_html = showmessages($myid,"",$timeof,$channel); 
  $buffer_layer = showmessages($myid,"writediv",$timeofDHTML,$channel); 
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
	$query = "SELECT * FROM livehelp_users WHERE user_id=".intval($myid)." AND status='chat'";
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
        	exit; 
         }
}
if($UNTRUSTED['printit'] == "Y"){
  print "<br><br><font color=999999>Printing Page..</font><br>";
  print "<SCRIPT type=\"text/javascript\">up(); setTimeout('window.print()',300);</SCRIPT>";
  print "<a href=javascript:window.close()>Close window</a>";
if(!($serversession))
  $mydatabase->close_connect();
  exit;	
}

 
  ?>
  <br><b>Refreshing...</b><br><br>
  <br>
  <br>  
  <SCRIPT type="text/javascript">
  up()
  refreshnow();
  </SCRIPT>

<?php 
if(!($serversession))
  $mydatabase->close_connect();
?>
</body>
</html>