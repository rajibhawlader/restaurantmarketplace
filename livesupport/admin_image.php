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
require_once("gc.php"); 


if(empty($UNTRUSTED['message_test'])) 
   $message_test = 0;
else {
  if (is_numeric($UNTRUSTED['message_test']))
     $message_test = $UNTRUSTED['message_test'];  
  else
     $message_test =0; 
}

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);  
  $myid = $people['user_id'];
  $channel = $people['onchannel'];
  $isnamed = $people['isnamed'];
  $status = $people['status'];
  
if(!(isset($UNTRUSTED['hide']))){ $UNTRUSTED['hide'] = ""; }
if(empty($UNTRUSTED['what'])){ $UNTRUSTED['what'] = "getstate"; }

if ( ($UNTRUSTED['what'] == "hidden") || ($UNTRUSTED['what'] == "userstat") || ($UNTRUSTED['what'] == "getstate") || ($UNTRUSTED['what'] == "talkative") ){ 
  update_session($identity);
  // check user identity and referer information:
  $query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
  $data = $mydatabase->query($query);	
  if($data->numrows() == 0){ 
    $query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
    $data = $mydatabase->query($query);	       
  }
  $userrow = $data->fetchRow(DB_FETCHMODE_ASSOC);
}       


// Check to see if I am chatting...
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "talkative"){
	
  $query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."' AND status='chat'";
  $justchecking = $mydatabase->query($query);

  if($justchecking->numrows() == 0){
    $filepath = "images/controlimage_noaction.gif"; 
    showimage($filepath,"image/gif");  
    exit;
  } else {
    $filepath = "images/controlimage_action.gif";
    showimage($filepath,"image/gif");  
    exit;   	
  }
  exit;
}

// Check to see if a reload is needed based on message timestamp..
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "messagecheck"){
	
  $sqlquery = "SELECT timeof FROM livehelp_messages WHERE typeof !='writediv' AND timeof>$message_test";
  $justchecking = $mydatabase->query($sqlquery);
  if($justchecking->numrows() == 0){
    $filepath = "images/controlimage_noaction.gif"; 
    showimage($filepath,"image/gif");   
    exit; 
  } else {
    $filepath = "images/controlimage_action.gif";
    showimage($filepath,"image/gif");   
    exit;   	
  }
  exit;
}


// ping the chat window.
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "pingchat"){
	$rightnow = date("YmdHis");
  $query = "UPDATE livehelp_users set chataction='$rightnow',lastaction='$rightnow' WHERE sessionid='".$identity['SESSIONID']."' ";
  $mydatabase->query($query);

    $filepath = "images/controlimage_noaction.gif"; 
    showimage($filepath,"image/gif");   
    exit;
}


// check to see if a reload is needed.. also update user timestamp.
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "usercheck"){

  $peoplestring = "users";
  if($UNTRUSTED['showvisitors'] == 1)
    $sqlquery = "SELECT * FROM livehelp_users ORDER by user_id DESC";
  else
    $sqlquery = "SELECT * FROM livehelp_users WHERE status='chat' ORDER by user_id DESC";      
  
  $visitors = $mydatabase->query($sqlquery);
  while( $visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
    $visitor_string = $visitor['sessionid'] . $visitor['status'];
    $user = "_" . ereg_Replace("([^a-zA-Z0-9])*", "", $visitor_string);
    $peoplestring .= str_replace(" ","",$user);
  }  
  
  $sqlquery = "SELECT * FROM livehelp_operator_channels ORDER by user_id DESC";        
  $visitors = $mydatabase->query($sqlquery);
  while( $visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
    $visitor_string = $visitor['user_id'];
    $peoplestring .= str_replace(" ","",$visitor_string);
  } 
   
  if(empty($UNTRUSTED['peoplestring_test'])){ 
  	 $UNTRUSTED['peoplestring_test'] = $peoplestring; 
  }
  if($UNTRUSTED['peoplestring_test'] == $peoplestring){
    $filepath = "images/controlimage_noaction.gif"; 
    showimage($filepath,"image/gif");  
    exit;
  } else {
    $filepath = "images/controlimage_action.gif";
    showimage($filepath,"image/gif");   
    exit;  	
  }
  exit;
}


// userstat: return the control image for this user. 
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "changestat"){
   
 
   // now..
   $rightnow = date("YmdHis");
   $query = "UPDATE livehelp_users set status='$what' WHERE sessionid='".$identity['SESSIONID']."'";
   $mydatabase->query($query);
   if(!($serversession))
    $mydatabase->close_connect();
   $filepath = "images/browse.gif";
    showimage($filepath,"image/gif");  
   exit;
 
}

//----------------------------------------------------------------
if($UNTRUSTED['what'] == "nameme"){
   $query = "UPDATE livehelp_users set username='$name',isnamed='Y' WHERE sessionid='".$identity['SESSIONID']."'";
   $mydatabase->query($query);
   if(!($serversession))
     $mydatabase->close_connect();
   $filepath = "images/browse.gif";   
    showimage($filepath,"image/gif");  
    exit;
}

//----------------------------------------------------------------
if($UNTRUSTED['what'] == "alive"){
   print $CSLH_Config['version'] . "-";
   $query = "SELECT * FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' ";
   $data = $mydatabase->query($query);  
  if($data->numrows() == 0){
    print "offline";
  } else {
    while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
      print $row['department'] . ",";      
    }
  }
  if(!($serversession))  
    $mydatabase->close_connect();
  exit; 
}

//----------------------------------------------------------------
if($UNTRUSTED['what'] == "startedtyping"){       
   $timeof = rightnowtime();
  
   if(!(empty($UNTRUSTED['channelsplit'])))
     $array = split("__",$UNTRUSTED['channelsplit']);
   else
     $array = array();
     
    if(isset($array[1])){
      $saidto = $array[1];    
      $channel = $array[0];
    } else {
      $channel = $UNTRUSTED['channelsplit'];
    }
    if(intval($channel)==0)
       $channel = intval(trim($UNTRUSTED['channelsplit']));
       
   // see if we currently have a write layer if not create it.
   $query = "SELECT * FROM livehelp_messages WHERE typeof='writediv' AND channel=". intval($channel) . " AND saidfrom=" . intval($UNTRUSTED['fromwho']);
   $checking = $mydatabase->query($query); 
   // if not create one
   if($checking->numrows() == 0){
      if (empty($saidto)){ $saidto = -1; }
      $query = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto,typeof) VALUES ('".filter_sql(convertamps($UNTRUSTED['sayingwhat']))."',".intval($channel).",'$timeof',".intval($UNTRUSTED['fromwho']).",".intval($saidto).",'writediv')";	
      $mydatabase->query($query); 
   }      

   // see if we have something to update...
   $query = "SELECT * FROM livehelp_messages WHERE message='".filter_sql(convertamps($UNTRUSTED['sayingwhat']))."' AND typeof='writediv' AND channel=".intval($channel) ." AND saidfrom=".intval($UNTRUSTED['fromwho']);
   $checking = $mydatabase->query($query); 
   // if so update it
   if($checking->numrows() == 0){
     $query = "UPDATE livehelp_messages set timeof='". date("YmdHis") . "',message='".filter_sql(convertamps($UNTRUSTED['sayingwhat']))."' WHERE typeof='writediv' AND channel=".intval($channel) ." AND saidfrom=".intval($UNTRUSTED['fromwho']);
     $mydatabase->query($query); 
   }    
   if(!($serversession))   
     $mydatabase->close_connect();
   $filepath = "images/browse.gif";   
    showimage($filepath,"image/gif");   
   exit; 
}

//----------------------------------------------------------------
if($UNTRUSTED['what']== "browse"){
	if(!($serversession))
      $mydatabase->close_connect();
      $filepath = "images/browse.gif";       
    showimage($filepath,"image/gif");  
      exit;
}

// userstat: return the control image for this user. 
//----------------------------------------------------------------
if($UNTRUSTED['what']== "userstat"){
    
   update_session($identity);
   $query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
   $data = $mydatabase->query($query);	
   $visitor = $data->fetchRow(DB_FETCHMODE_ASSOC);
   
   // now..
   $rightnow = date("YmdHis");

   // update the visitors tracks.
   // see if we already have the page they are on.
   if(empty($pageid)){ $pageid = 1; }
     
   // update their last action to now..
   $query = "UPDATE livehelp_users set lastaction='$rightnow' WHERE sessionid='".$identity['SESSIONID']."'";
   $mydatabase->query($query);	

    // see if the operator wants anything with them:
    // status = R means request Chat.. 
    if($visitor['status'] == "request"){
     if(!($serversession))
     $mydatabase->close_connect();
     $filepath = "images/requestchat.gif";      
    showimage($filepath,"image/gif");  
     exit;
    } elseif ($visitor['status'] == "DHTML") {
     $filepath = "images/requestDHTML.gif"; 
    showimage($filepath,"image/gif");   
     exit;   	
    } else {	
     if(!($serversession))
     $mydatabase->close_connect();
     $filepath = "images/browse.gif";
    showimage($filepath,"image/gif");   
     exit;   	
    }
}

 
?>