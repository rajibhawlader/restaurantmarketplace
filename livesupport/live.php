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

if(!(empty($UNTRUSTED['speak']))){ 
	$_COOKIE['speaklanguage'] = $UNTRUSTED['speak']; 
	print "Language changed to " . $UNTRUSTED['speak'];
 print "<SCRIPT type=\"text/javascript\"> window.location.replace(\"live.php\");</script>";
  print "<a href=live.php>click here</a>";
  exit;
} 

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
 
if(isset($UNTRUSTED['reset'])){
 $query = "SELECT user_id,sessionid,camefrom FROM livehelp_users WHERE isoperator='N' AND status!='chat'";
 $sth = $mydatabase->query($query);
 while($row = $sth->fetchRow(DB_FETCHMODE_ORDERED)){ 	
   	 $user_id = $row[0]; 
   	 $sessionid = $row[1];   	 
     $camefrom = $row[2];                     
     // if not txt-db-api and $CSLH_Config['tracking'] == "Y" insert visitor and referer information:
     if($dbtype != "txt-db-api"){     
       if(!(empty($camefrom)) && ($CSLH_Config['reftracking']=="Y")){
     	   archivepage('livehelp_referers_daily',$camefrom,date("Ymd"));
     	   archivepage('livehelp_referers_monthly',$camefrom,date("Ym"));     	   
     	 }
     	 if ($CSLH_Config['tracking']=="Y")
     	   archivefootsteps($sessionid);       
     }	
    archiveuser($sessionid);   
 }
print "Database reset...";
print "<SCRIPT type=\"text/javascript\"> window.location.replace(\"live.php\");</script>";
print "<a href=live.php>click here</a>";
exit;
}
if(!($serversession))
$mydatabase->close_connect();
?>
<title>Live help admin</title>
<frameset rows="50,*,155" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
 <frame src="admin_options.php?tab=live" name="topofit" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
 <frameset cols="*,300" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
  <frameset rows="32,*" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
   <frame src="admin_rooms.php" name="rooms" scrolling="NO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
   <frame src="admin_connect.php?rand=<?php echo date("YmdHis"); ?>" name="connection" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
  </frameset>
  <frame src="admin_users.php" name="users" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
 </frameset>
 <frame src="admin_chat_bot.php" name="bottomof" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
</frameset>