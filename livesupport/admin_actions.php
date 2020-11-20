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


if(empty($UNTRUSTED['whattodo'])){  $UNTRUSTED['whattodo'] = ""; }
if(empty($UNTRUSTED['whattodochat'])){  $UNTRUSTED['whattodochat'] = ""; }
if(empty($UNTRUSTED['whattodo'])){ $UNTRUSTED['whattodo'] = $UNTRUSTED['whattodochat']; }
 
// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$show_arrival = $people['show_arrival']; 
$user_alert = $people['user_alert'];
$greeting = $people['greeting'];
$photo = $people['photo'];
if( (!(empty($photo))) && (!(empty($greeting))))
    $greeting ="<table><tr><td><img src=$photo></td><td>$greeting</td></tr></table>";
$timeof = rightnowtime();

if(empty($myid)){
  // get the if of this user.. 
  $query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
  $people = $mydatabase->query($query);
  $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = intval($people['user_id']);
  $channel = intval($people['onchannel']);
}

// Stop Chat.
if($UNTRUSTED['whattodo'] == "stop"){
  reset($_POST);
  while (list($key, $val) = each($_POST)) {
  	$pair = split("__",$key);
  	if($pair[0] == "session"){    	  
      $query = "SELECT * FROM livehelp_users WHERE sessionid='".filter_sql($val)."'";	
      $rs = $mydatabase->query($query);  
      $person = $rs->fetchRow(DB_FETCHMODE_ASSOC);  
      $who = $person['user_id'];
      $onchannel = $person['onchannel'];
      $sessionid = $person['sessionid'];
  	  stopchat($sessionid);	
      $html_head = " window.parent.bottomof.location.replace(\"admin_chat_bot.php\");";
  	  print "stopped: $val<br>\n";
  	}
  }
  print "Done..";
  print "<SCRIPT type=\"text/javascript\"> window.close(); </SCRIPT>";
  exit;
}
   reset($_POST);
	 $comma = "";
	 $listedids = "";
   while (list($key, $val) = each($_POST)) {
  	$pair = split("__",$key);
  	if($pair[0] == "session"){
	    $listedids .= $comma . intval($pair[1]);
	    $comma = ",";
	  }
	 } 

// 
if($UNTRUSTED['whattodo'] == "conference"){
   // go through all the ids and make them all on the same channel:
   $pairs = split(",",$listedids);
   for($i=0;$i<count($pairs);$i++){
     if($i==0){
        $query = "SELECT onchannel FROM livehelp_users WHERE user_id=".intval($pairs[$i]);	
        $rs = $mydatabase->query($query);  
        $person = $rs->fetchRow(DB_FETCHMODE_ORDERED);  
        $onchannel = intval($person[0]); 
        $query = "SELECT channelcolor FROM livehelp_operator_channels WHERE channel=$onchannel";
        $rs = $mydatabase->query($query);  
        $person = $rs->fetchRow(DB_FETCHMODE_ORDERED);  
        $channelcolor = $person[0];        
     } else {
        $query = "UPDATE livehelp_users SET onchannel=$onchannel WHERE user_id=".intval($pairs[$i]);	
        $mydatabase->query($query);  
        $query = "UPDATE livehelp_operator_channels SET channelcolor='$channelcolor' WHERE userid=".intval($pairs[$i]);	
        $mydatabase->query($query);  
     }
   }
   print "done. ";
   print "<SCRIPT type=\"text/javascript\">";
   print "window.close();";
   print "</SCRIPT>";	
   exit;
}


// Layer Invite
if($UNTRUSTED['whattodo'] == "DHTML"){
   Header("Location: layer.php?selectedwho=$listedids");
   exit;
}

// Pop-up Invite
if($UNTRUSTED['whattodo'] == "pop"){
   Header("Location: invite.php?selectedwho=$listedids");
   exit;
}

// Ask to transfer
if($UNTRUSTED['whattodo'] == "transfer"){
	?>
	<link title="new" rel="stylesheet" href="style.css" type="text/css">
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
  <body bgcolor=E0E8F0 onload=window.focus()>
  <h2><?php echo $lang['txt178']; ?>:</h2>
  <form action=admin_actions.php method=post>  
  <?php echo $lang['txt179']; ?>:<select name=todepartment>
  <?php
  $query = "SELECT * FROM livehelp_departments ORDER by nameof";
  $res = $mydatabase->query($query);
  while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)){
    print "<option value=" . $row['recno'];
    print "> ".$lang['txt43']." " . $row['nameof'] . "</option>\n";
  }
 ?>
  </select>
    <?php echo $lang['txt184']; ?>:<select name=tooperator>
  <?php
  $query = "SELECT * FROM livehelp_users WHERE isoperator='Y' AND isonline='Y' ORDER by username";
  $res = $mydatabase->query($query);
  ?><option value=""> -- </option><?php
  while($row = $res->fetchRow(DB_FETCHMODE_ASSOC)){
    print "<option value=" . $row['user_id'];
    print ">" .  $row['username'] . "</option>\n";
  }
 ?>
  </select>
  <input type=hidden name=redirectids value=<?php echo $listedids; ?>>
  <input type=hidden name=whattodo value=redirect>
  <input type=submit value=GO>
  </form>
<?php 
  exit;
}

// perform transfer
if($UNTRUSTED['whattodo'] == "redirect"){
  $pairs = split(",",$UNTRUSTED['redirectids']);
  for($i=0;$i<count($pairs);$i++){
    $thisid = $pairs[$i];	
    $query = "SELECT * FROM livehelp_users WHERE user_id=".intval($thisid);	
    $rs = $mydatabase->query($query);  
    $person = $rs->fetchRow(DB_FETCHMODE_ASSOC);  
    $onchannel = $person['onchannel'];
 	  $message = "[transfer]livehelp.php?department=".intval($UNTRUSTED['todepartment'])."&tab=1[/transfer]";
    $rightnow = rightnowtime();
    
    $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('$message',".intval($onchannel).",'$rightnow',".intval($myid).",'".intval($thisid)."')";  
    $mydatabase->query($query);
    
    // get channel data:    
    $query = "SELECT * FROM livehelp_operator_channels WHERE channel=".intval($person['onchannel']);	 
    $userdata = $mydatabase->query($query);
    $user_row = $userdata->fetchRow(DB_FETCHMODE_ASSOC);
    $txtcolor = $user_row['txtcolor'];
    $txtcolor_alt = $user_row['txtcolor_alt']; 
    $channelcolor = $user_row['channelcolor']; 
    
    // release channel from operator:
    $query = "DELETE FROM livehelp_operator_channels WHERE channel=".intval($person['onchannel']);	    
    $mydatabase->query($query); 
    
    // add other operator..    
    $channelcolor = get_next_channelcolor();  
    $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,txtcolor,txtcolor_alt,channelcolor) VALUES (".intval($UNTRUSTED['tooperator']).",".intval($onchannel).",".intval($thisid).",'$txtcolor','$txtcolor_alt','$channelcolor')";	
    $mydatabase->query($query);
  
   }
   print "done.";
   ?>
    <SCRIPT type="text/javascript">setTimeout('window.close()',1000);</script>
   <?php
if(!($serversession))
   $mydatabase->close_connect();
   exit;
}

// activate a chat request..
if($UNTRUSTED['whattodo'] == "activiate"){
  // see if anyone is chatting with this person. 
  $query = "SELECT * FROM livehelp_operator_channels WHERE userid=" . intval($UNTRUSTED['who']);
  $counting = $mydatabase->query($query);
  // if someone is chatting with them ask if we would like to join...
  if($counting->numrows() != 0){
   print $lang['txt180'];
  if(!($serversession))  
    $mydatabase->close_connect();
   exit;
  } 
  if($counting->numrows() == 0){       
   // get session data and post it as a message if not one
   $query = "SELECT * FROM livehelp_users WHERE user_id=" . intval($UNTRUSTED['who']);
   $userdata = $mydatabase->query($query);
   $user_row = $userdata->fetchRow(DB_FETCHMODE_ASSOC);
   $sessiondata = $user_row['sessiondata'];
   $datapairs = explode("&",$sessiondata);
   $datamessage="";
   for($l=0;$l<count($datapairs);$l++){
  	  $dataset = explode("=",$datapairs[$l]);
  	  if(!(empty($dataset[1]))){
  	  	$fieldid = str_replace("field_","",$dataset[0]);
  	  	$query = "SELECT * FROM livehelp_questions WHERE id=" . intval($fieldid);
  	  	$questiondata = $mydatabase->query($query);
        $question_row = $questiondata->fetchRow(DB_FETCHMODE_ASSOC);    	  
    	  $datamessage.= $question_row['headertext'] . "<br><font color=000000><b>" . urldecode($dataset[1]) . "</font></b><br>";
      }
   }
   if($datamessage!=""){
  	 $timeof = rightnowtime();  
  	 $query = "INSERT INTO livehelp_messages (saidto,saidfrom,message,channel,timeof) VALUES (".intval($myid).",".intval($who).",'<br>".filter_sql($datamessage)."',".intval($whatchannel).",'$timeof')";	
     $mydatabase->query($query);
   }
  }
  if( (empty($whatchannel)) || ($whatchannel == 0) ){
   $whatchannel = createchannel(intval($UNTRUSTED['who']));	 
  }

   // generate random Hex..
    $txtcolor = "";
    $lowletters = array("0","2","4","6");
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $txtcolor .= $lowletters[$randomindex];
    }	 

   // generate random Hex..
    $txtcolor_alt = "";
    $lowletters = array("2","4","6","8");
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $txtcolor_alt .= $lowletters[$randomindex];
    }	 
           
  $query = "DELETE FROM livehelp_operator_channels WHERE user_id=".intval($myid) . " AND userid=".intval($UNTRUSTED['who']);	
  $mydatabase->query($query);  
  $channelcolor = get_next_channelcolor();  
  $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,txtcolor,txtcolor_alt,channelcolor) VALUES (".intval($myid).",".intval($whatchannel).",".intval($UNTRUSTED['who']).",'$txtcolor','$txtcolor_alt','$channelcolor')";	
  $mydatabase->query($query);

  // add to history:
   $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,channel,totaltime) VALUES ($myid,'startchat','".rightnowtime()."',".intval($whatchannel).",0)";
   $mydatabase->query($query);
   
  if (!(empty($UNTRUSTED['conferencein']))){
    $channelcolor = get_next_channelcolor();  
    $query = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,txtcolor,channelcolor) VALUES ('".intval($UNTRUSTED['who'])."','$whatchannel','$myid','$txtcolor','$txtcolor_alt','$channelcolor')";	
    $mydatabase->query($query);    
  }
  $timeof = rightnowtime();
  
  if(!(empty($greeting))){ 
   $query = "INSERT INTO livehelp_messages (saidto,saidfrom,message,channel,timeof) VALUES (".intval($UNTRUSTED['who']).",".intval($myid).",'".filter_sql($greeting)."',".intval($whatchannel).",'$timeof')";	
   $mydatabase->query($query);
  } 
  

  $channelsplit = $whatchannel . "__" . $who;

  $query = "SELECT * FROM livehelp_users WHERE isoperator='Y' AND isonline='Y'";
  $operators_online = $mydatabase->query($query);
  $operators_talking = array();
  while($operator = $operators_online->fetchRow(DB_FETCHMODE_ASSOC)){
      $commonchannel = 0;
      $query = "SELECT * FROM livehelp_operator_channels WHERE user_id=". intval($operator['user_id']);
      $mychannels = $mydatabase->query($query);
      while($rowof = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){
         $query = "SELECT * FROM livehelp_operator_channels WHERE user_id=".intval($myid) ." And channel=". intval($rowof['channel']);
         $counting = $mydatabase->query($query);        
         if($counting->numrows() != 0){ 
             array_push($operators_talking,$operator['user_id']);
         }
      }  
   } 
  
  // re-buld list:
 for($k=0;$k< count($operators_talking); $k++){
   if($k==0)
     $operators = $operators_talking[$k];
   else
    $operators .= "," . $operators_talking[$k];
 }
   print "Done..";
  print "<SCRIPT type=\"text/javascript\"> window.close(); </SCRIPT>";
  exit;
} 
 print "Done..";
  print "<SCRIPT type=\"text/javascript\"> window.close(); </SCRIPT>";
  exit;
if(!($serversession))
  $mydatabase->close_connect();

?>