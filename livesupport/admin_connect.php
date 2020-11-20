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


if(!(isset($UNTRUSTED['offset']))){ $UNTRUSTED['offset'] = ""; }
if(!(isset($UNTRUSTED['clear']))){ $UNTRUSTED['clear'] = ""; }
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

if(($CSLH_Config['use_flush'] == "no") || ($UNTRUSTED['offset'] != "")){ $timeof = $oldtime; }
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
 
if(empty($_REQUEST['see']))  
 $see = "";
else
 $see = $_REQUEST['see'];

// get chatmode:
if(empty($CSLH_Config['chatmode'])) 
   $CSLH_Config['chatmode'] = "flush-xmlhttp-refresh";

$chatmodes = explode('-',$CSLH_Config['chatmode']);
switch ($chatmodes[0]){
	 case "xmlhttp":
      $page = "is_xmlhttp.php";
      break;
	 case "flush":
      $page = "is_flush.php";
      break;
   default:
      $page = "admin_chat_refresh.php";
      break;
 }      
 
?>
    <SCRIPT type="text/javascript">
       window.location.replace("<?php echo $page; ?>?try=0&scriptname=admin_chat&see=<?php echo $see; ?>"); 	 
    </SCRIPT>
</body>
</html>
<?php
if(!($serversession))
  $mydatabase->close_connect();
  exit;
?>