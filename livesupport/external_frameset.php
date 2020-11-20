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

if(empty($UNTRUSTED['channelsplit'])){
	 print "error no id given...";
	 exit;
}
$channelsplit = $UNTRUSTED['channelsplit'];
$channelsp = explode("__",$UNTRUSTED['channelsplit']);
$channel = $channelsp[0];
$user_id = $channelsp[1];

// given the channelsplit sent make sure we have that id in our diliminated 
// list of external chats:
$externalchats_array = explode(",",$externalchats);

if(empty($chattype)) 
  $chattype = "xmlhttp";
if(empty($UNTRUSTED['whattodo']))
  $UNTRUSTED['whattodo'] = "";
   
if($UNTRUSTED['whattodo'] == "NOWINDOW"){
   $externalchats=""; 
   for($i=0;$i<count($externalchats_array); $i++){
    	if($channel!=$externalchats_array[$i]){
    	   $externalchats = $externalchats .",$externalchats_array[$i]";
    	}   
   }
   $sqlquery = "UPDATE livehelp_users SET externalchats='".filter_sql($externalchats)."' WHERE sessionid='".$identity['SESSIONID']."'";	
   $mydatabase->query($sqlquery); 	   
   print "<SCRIPT type=\"text/javascript\">window.close();</SCRIPT>";
   exit;
  } else {
     if(!(in_array($channel,$externalchats_array))){
        array_push($externalchats_array,$channel);
        $externalchats = $externalchats .",$channel";
     }	
     $sqlquery = "UPDATE livehelp_users SET externalchats='".filter_sql($externalchats)."' WHERE sessionid='".$identity['SESSIONID']."'";	
   $mydatabase->query($sqlquery); 	 
  } 

// who is this?  
$sqlq = "SELECT username FROM livehelp_users WHERE user_id=". intval($user_id);
$rs = $mydatabase->query($sqlq); 
$row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
$thisusername = $row[0];  
  
if(!($serversession))
  $mydatabase->close_connect();
?>
<html>
	<head>
<title><?php echo $thisusername; ?> chat</title>
</head>
<frameset rows="*,120" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
 <frame src="external_chat_<?php echo $chattype; ?>.php?channelsplit=<?php echo $channelsplit;?>" name="topofit" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
 <frame src="external_bot.php?channelsplit=<?php echo $channelsplit;?>" name="bottomof" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
</frameset>
</html>