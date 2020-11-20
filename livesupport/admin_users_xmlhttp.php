<?php 
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2008 Eric Gerdes   (http://www.craftysyntax.com )
// --------------------------------------------------------------------------
// $              CVS will be released with version 3.1.0                   $
// $    Please check http://www.craftysyntax.com/ or REGISTER your program for updates  $
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
// --------------------------------------------------------------------------
// FILE NOTES:
//     This file controls the list of active users on the site. 
//===========================================================================
require_once("admin_common.php");
validate_session($identity);
 
$timeof = rightnowtime();
$insitewav = "";
$soundwav = "";

//
// Start initial var setup
//
if(empty($UNTRUSTED['action'])){ $UNTRUSTED['action'] = ""; }
if(empty($UNTRUSTED['clearchannel'])){ $UNTRUSTED['clearchannel'] = "N"; }
// comma deliminated list of people to ignore chat requests for..
if(empty($UNTRUSTED['ignorelist'])){ $UNTRUSTED['ignorelist'] = "0"; }
$ignorelist = $UNTRUSTED['ignorelist'];
// comma deliminated list of operators that we are talking to.
if(empty($UNTRUSTED['operators'])){ $UNTRUSTED['operators'] = "0"; }
// setting for refresh rate 
if(empty($UNTRUSTED['autoinvite'])){ $UNTRUSTED['autoinvite'] = ""; }
if(empty($UNTRUSTED['togglerefresh'])){ $UNTRUSTED['togglerefresh'] = ""; }
if(empty($UNTRUSTED['hidechatters'])) { $UNTRUSTED['hidechatters'] = 0; $hidechatters = 0; } else { $hidechatters = $UNTRUSTED['hidechatters']; }

if(empty($UNTRUSTED['showvisitors'])){ 
	$UNTRUSTED['showvisitors']= $defaultshowvisitors;
} else {
	 $sqlquery = "UPDATE livehelp_users SET istyping='".intval($UNTRUSTED['showvisitors'])."'  WHERE sessionid='".$identity['SESSIONID']."'";	
   $mydatabase->query($sqlquery);
}
if(empty($UNTRUSTED['needaction'])){ $UNTRUSTED['needaction']=0; }
if(empty($UNTRUSTED['numchats'])){ $numchats = 0; } else { $numchats = $UNTRUSTED['numchats']; }
$stuffatbottom = "";

$DIVS = "";

if(!(empty($UNTRUSTED['togglerefresh']))){
  $CSLH_Config['admin_refresh'] = $UNTRUSTED['togglerefresh'];   	
  $sqlquery = "UPDATE livehelp_config set admin_refresh ='".$CSLH_Config['admin_refresh']."'";
  $mydatabase->query($sqlquery);
}
 
// peoplestring is a sting of the current users online it is used 
// to compair with the live people online to determine if we should 
// reload the page or not basicly a bandwith saver.
  $peoplestring = "users";
  $user = "";
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
    $user = $visitor['user_id'];
    $peoplestring .= str_replace(" ","",$user);
  } 
 
// List of people we are ignoring:
$ignorelist_array = array();
if(!(empty($UNTRUSTED['ignorelist']))){
  $ignorelist_array = split(",",str_replace(" ","",$UNTRUSTED['ignorelist']));  
  $ignorelist = $UNTRUSTED['ignorelist'];
}


// List of operators that I know I am talking to:
$operators_array = array();
if(!(empty($UNTRUSTED['operators']))){
  $operators_array = split(",",$UNTRUSTED['operators']); 
  $operators = $UNTRUSTED['operators']; 
} else {
	 $operators = "";
	 $operators_array = array();
}
 
if($UNTRUSTED['autoinvite']=="ON"){
  $sqlquery = "UPDATE livehelp_users set auto_invite='Y' WHERE sessionid='".$identity['SESSIONID']."'";
  $mydatabase->query($sqlquery);
}

if($UNTRUSTED['autoinvite']=="OFF"){
  $sqlquery = "UPDATE livehelp_users set auto_invite='N' WHERE sessionid='".$identity['SESSIONID']."'";
  $mydatabase->query($sqlquery);
}

  $sqlquery = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
  $people = $mydatabase->query($sqlquery);
  $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = $people['user_id'];
  $channel = $people['onchannel'];
  $show_arrival = $people['show_arrival']; 
  $user_alert = $people['user_alert'];
  $auto_invite = $people['auto_invite'];
  $greeting = $people['greeting'];
  $photo = $people['photo'];
  if( (!(empty($photo))) &&  (!(empty($greeting))) )
    $greeting ="<table><tr><td><img src=$photo></td><td>$greeting</td></tr></table>";


    
?>
<SCRIPT type="text/javascript">
function seepages(id){
  url = 'details.php?id=' + id;
  window.open(url, 'chat54050872', 'width=555,height=320,menubar=no,scrollbars=1,resizable=1');
}
<?php

 // see if any new operators are talking to me:  
 // select out all of the operators that are online and create an array of operators talking to me
 $sqlquery = "SELECT * FROM livehelp_users WHERE isoperator='Y' AND isonline='Y'";
 $operators_online = $mydatabase->query($sqlquery);
 $operators_talking = array();
 while($operator = $operators_online->fetchRow(DB_FETCHMODE_ASSOC)){
      $commonchannel = 0;
      $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id='".$operator['user_id']."'";
      $mychannels = $mydatabase->query($sqlquery);
      while($rowof = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){
         $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id=".intval($myid). " And channel='".$rowof['channel']."'";
         $counting = $mydatabase->query($sqlquery);        
         if($counting->numrows() != 0){ 
         	  if(!(in_array($operator['user_id'],$operators_talking)))
               array_push($operators_talking,$operator['user_id']);
         }
      }  
 } 
 
 
 // see if I do not know about one of them:
 for($k=0;$k< count($operators_talking); $k++){
     if(!(in_array($operators_talking[$k],$operators_array))){
        $stuffatbottom = "<SCRIPT type=\"text/javascript\"> window.parent.bottomof.refreshit(); </SCRIPT>\n";              
     }
 }

 // if they do not know about me
 for($k=0;$k< count($operators_array); $k++){
     if(!(in_array($operators_array[$k],$operators_talking))){
        $stuffatbottom = "<SCRIPT type=\"text/javascript\"> window.parent.bottomof.refreshit(); </SCRIPT>\n";                  
     }
 }

 // if the number of chatters changed:
  $sqlquery = "SELECT userid FROM livehelp_operator_channels WHERE user_id=" . intval($myid);
  $chatcheck = $mydatabase->query($sqlquery);
  $numchatsnow = $chatcheck->numrows();
  if(($numchats != 0) && ($numchatsnow != $numchats)){
    $stuffatbottom = "<SCRIPT type=\"text/javascript\"> window.parent.bottomof.refreshit(); </SCRIPT>\n";  
  }  

// re-buld list:
$operators ="";
$prevnum="";
 for($k=0;$k< count($operators_talking); $k++){
   if($k==0)
     $operators = $operators_talking[$k];
   else{
    if($prevnum!=$operators_talking[$k])
       $operators .= "," . $operators_talking[$k];
    $prevnum=$operators_talking[$k];
   }  
 }



 
 // re-buld list:
 $operators2 = "";
 $prevnum="";
 for($k=0;$k< count($operators_array); $k++){
   if($k==0)
     $operators2 = $operators_array[$k];
   else
    if($prevnum!=$operators_array[$k])
      $operators2 .= "," . $operators_array[$k];
   $prevnum=$operators_array[$k];      
 }
 
?>
</SCRIPT>
<?php
// activiate chatting with the user.. 
if($UNTRUSTED['action'] == "activiate"){

  // see if anyone is chatting with this person. 
  $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE userid=" . intval($UNTRUSTED['who']);
  $counting = $mydatabase->query($sqlquery);
  // if someone is chatting with them ask if we would like to join...
  if( ($counting->numrows() != 0) && ($UNTRUSTED['needaction'] ==1)){
   print $lang['txt180'];   
  } else {
  
  // see if anyone is chatting with this person. 
  $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE userid=" . intval($UNTRUSTED['who']);
  $counting = $mydatabase->query($sqlquery);
  if($counting->numrows() == 0){       
   // get session data and post it as a message if not one
   $sqlquery = "SELECT * FROM livehelp_users WHERE user_id=" . intval($UNTRUSTED['who']);
   $userdata = $mydatabase->query($sqlquery);
   $user_row = $userdata->fetchRow(DB_FETCHMODE_ASSOC);
   $sessiondata = $user_row['sessiondata'];
   $datapairs = explode("&",$sessiondata);
   $datamessage="";
   for($l=0;$l<count($datapairs);$l++){
  	  $dataset = explode("=",$datapairs[$l]);
  	  if(!(empty($dataset[1]))){
  	  	$fieldid = str_replace("field_","",$dataset[0]);
  	  	$sqlquery = "SELECT * FROM livehelp_questions WHERE id=".intval($fieldid);
  	  	$questiondata = $mydatabase->query($sqlquery);
        $question_row = $questiondata->fetchRow(DB_FETCHMODE_ASSOC);    	  
    	  if($l!=0)
    	     $datamessage.= "<br>";
    	  $datamessage.= "<b>" . $question_row['headertext'] . "</b><br>" . urldecode($dataset[1]);
      }
   }
   if($datamessage!=""){
  	 $timeof = rightnowtime();
  	 $sqlquery = "INSERT INTO livehelp_messages (saidto,saidfrom,message,channel,timeof) VALUES (".intval($myid).",".intval($UNTRUSTED['who']).",'".filter_sql($datamessage)."',".intval($UNTRUSTED['whatchannel']).",'$timeof')";	
     $mydatabase->query($sqlquery);
   }
  }

  if( (empty($whatchannel)) || ($whatchannel == 0) ){
    $whatchannel = createchannel($UNTRUSTED['who']);
  }

   // generate random Hex..
    $txtcolor = "";
    $lowletters = array("0","2","4","6");
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $txtcolor .= $lowletters[$randomindex];
    }	 

    $txtcolor_alt = "";
    $lowletters = array("2","4","6","9");
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $txtcolor_alt .= $lowletters[$randomindex];
    }	     
           
  $sqlquery = "DELETE FROM livehelp_operator_channels WHERE user_id=".intval($myid)." AND userid=" . intval($UNTRUSTED['who']);	
  $mydatabase->query($sqlquery);  
  $channelcolor = get_next_channelcolor();  
  $sqlquery = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,txtcolor,txtcolor_alt,channelcolor) VALUES (".intval($myid).",".intval($whatchannel).",".intval($UNTRUSTED['who']).",'$txtcolor','$txtcolor_alt','$channelcolor')";	
  $mydatabase->query($sqlquery);
  // add to history:
   $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,channel,totaltime) VALUES ($myid,'startchat','".date("YmdHis")."',".intval($whatchannel).",0)";
   $mydatabase->query($query);

  $timeof = rightnowtime();    
  if (!(empty($UNTRUSTED['conferencein']))){
    $channelcolor = get_next_channelcolor();  
    $sqlquery = "INSERT INTO livehelp_operator_channels (user_id,channel,userid,txtcolor,channelcolor) VALUES (".intval($UNTRUSTED['who']).",".intval($whatchannel).",".intval($myid).",'$txtcolor','$channelcolor')";	
    $mydatabase->query($sqlquery);                
  } else {
   if(!(empty($greeting))){
    $sqlquery = "INSERT INTO livehelp_messages (saidto,saidfrom,message,channel,timeof) VALUES (".intval($UNTRUSTED['who']).",".intval($myid).",'".filter_sql($greeting)."',".intval($whatchannel).",'$timeof')";	
    $mydatabase->query($sqlquery);
   }
  }
  $channelsplit = $whatchannel . "__" . $UNTRUSTED['who'];

  $sqlquery = "SELECT * FROM livehelp_users WHERE isoperator='Y' AND isonline='Y'";
  $operators_online = $mydatabase->query($sqlquery);
  $operators_talking = array();
  while($operator = $operators_online->fetchRow(DB_FETCHMODE_ASSOC)){
      $commonchannel = 0;
      $sqlquery = "SELECT * 
                FROM livehelp_operator_channels 
                WHERE user_id=".intval($operator['user_id']);
      $mychannels = $mydatabase->query($sqlquery);
      while($rowof = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){
         $sqlquery = "SELECT * 
                   FROM livehelp_operator_channels 
                   WHERE user_id=".intval($myid)." And channel=".$rowof['channel'];
         $counting = $mydatabase->query($sqlquery);        
         if($counting->numrows() != 0){ 
         	  if(!(in_array($operator['user_id'],$operators_talking)))
               array_push($operators_talking,$operator['user_id']);
         }
      }  
   } 
  
  // re-buld list:
  $prevnum = "";
  
 for($k=0;$k< count($operators_talking); $k++){
   if($k==0)
     $operators = $operators_talking[$k];
   else {
    if($prevnum !=$operators_talking[$k])
       $operators .= "," . $operators_talking[$k];
    $prevnum=$operators_talking[$k];
   }
  }
 }
}
?>
<HEAD>
<META http-equiv="pragma" CONTENT="no-cache"> 
<?php
if($CSLH_Config['admin_refresh'] != "auto"){
	 if(empty($ignorelist))
	    $ignorelist ="";
 ?>
<META HTTP-EQUIV="REFRESH" content="<?php echo $CSLH_Config['admin_refresh']; ?>;URL=admin_users_refresh.php?hidechatters=<?php echo $hidechatters; ?>&ignorelist=<?php echo $ignorelist; ?>&numchats=<?php echo $numchatsnow ?>&operators=<?php echo $operators; ?>&showvisitors=<?php echo $UNTRUSTED['showvisitors']; ?>">
<?php } ?>
<META HTTP-EQUIV="EXPIRES" CONTENT="Sat, 01 Jan 2001 00:00:00 GMT">
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
</HEAD>
<?php


// Update operator History for when operator came online to monitor 
// traffic: //showedup 
$q = "SELECT showedup,user_id FROM livehelp_users WHERE sessionid='" . $identity['SESSIONID'] . "' LIMIT 1";
$sth = $mydatabase->query($q);
$row = $sth->fetchRow(DB_FETCHMODE_ORDERED);
$rightnow = date("YmdHis");

$showedup = $row[0];
$opid = $row[1];
if($showedup < 10){ 
   // update history for operator to show login:
   $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid) VALUES ($opid,'Started Monitoring Traffic','$rightnow','".$identity['SESSIONID']."')";
   $mydatabase->query($query);
}

$q = "UPDATE livehelp_users SET showedup='$rightnow' WHERE sessionid='" . $identity['SESSIONID'] . "'";
$mydatabase->query($q);


$html_head = "
<SCRIPT type=\"text/javascript\">

var ismac = navigator.platform.indexOf('Mac');
 

//-----------------------------------------------------------------
// Update the control image. This is the image that the operators 
// use to communitate with the visitor. 
function csgetimage()
{	 
 
	 // set a number to identify this page .
	 csID=Math.round(Math.random()*9999);
	 randu=Math.round(Math.random()*9999);
   cscontrol = new Image;
      	 
	 var u = 'admin_image.php?randu=' + randu + '&showvisitors=".$UNTRUSTED['showvisitors']."&what=usercheck' + '&peoplestring_test=' + '$peoplestring'
	 cscontrol.src = u;
	 
	 	if (ismac > -1){
       document.getElementById(\"imageformac\").src= u;
       document.getElementById(\"imageformac\").onload = lookatimage;
    }   
    else {
       cscontrol.src= u; 
       cscontrol.onload = lookatimage;
    }   
     
}

function lookatimage(){
   
	if(typeof(cscontrol) == 'undefined' ){
		setTimeout('refreshit()',9000); 
    return; 
 }   
	 if (ismac > -1)
      w = document.getElementById(\"imageformac\").width;
   else
      w = cscontrol.width; 
 
   if(w == 55){
        delete cscontrol;	
  	    refreshit();
	 } 	        	            
        delete cscontrol;                  
} 
	
csTimeout = 299; 
imageloaded = 0;
cscontrol = new Image;

";

if($CSLH_Config['admin_refresh'] == "auto"){  
$html_head .= " setInterval('csgetimage()', 4000); \n";
$stuffatbottom .= "
<SCRIPT type=\"text/javascript\">
if (ismac > -1) {
	randu=Math.round(Math.random()*9999);
  document.write('<img id=\"imageformac\" name=\"imageformac\" src=\"'  +
  'admin_image.php?randu=' + randu + 
  '&showvisitors=".$UNTRUSTED['showvisitors']."&what=usercheck' + 
  '&peoplestring_test=' +  
  '$peoplestring' +
  '\" border=\"0\">');
 }
</SCRIPT>
";
}

$html_head .= "

function updatepeople(refreshtime){
	document.admin_actions.submit();
	if(refreshtime ==1){
    setTimeout(\"refreshit();\",7920);
    setTimeout(\"window.parent.bottomof.location.replace('admin_chat_bot.php');\",2500);  
  } else
    setTimeout(\"refreshit();\",2920);  
}

function refreshit(){
 window.location.replace(\"admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=".$UNTRUSTED['ignorelist']."&numchats=". $numchatsnow ."&operators=".$operators."&showvisitors=".$UNTRUSTED['showvisitors']."\");
}	
  
  setTimeout(\"refreshit();\",99920);

 
";

if($UNTRUSTED['action'] == "stop"){
  $sqlquery = "SELECT * FROM livehelp_users WHERE user_id=".intval($UNTRUSTED['who']);	
  $rs = $mydatabase->query($sqlquery);  
  $person = $rs->fetchRow(DB_FETCHMODE_ASSOC);
  $onchannel = $person['onchannel'];
  $sessionid = $person['sessionid'];
  stopchat($sessionid);
  $UNTRUSTED['action'] = "leave";
}

// leave the channel or user..
if($UNTRUSTED['action'] == "leave"){

  $sqlquery = "select count(*) as numpeople FROM livehelp_operator_channels WHERE userid=".intval($UNTRUSTED['who']);	
  $rs = $mydatabase->query($sqlquery); 
  $persons = $rs->fetchRow(DB_FETCHMODE_ASSOC);
  if ($persons['numpeople'] < 2){ $UNTRUSTED['clearchannel']="Y"; }
  
  if($UNTRUSTED['clearchannel']=="Y"){
  $sqlquery = "DELETE FROM livehelp_operator_channels WHERE channel=". intval($UNTRUSTED['whatchannel']);	
  $mydatabase->query($sqlquery);  
  }
  $sqlquery = "DELETE FROM livehelp_operator_channels WHERE user_id=".intval($myid)." AND channel=".intval($UNTRUSTED['whatchannel'])." AND userid=".intval($UNTRUSTED['who']);	
  $mydatabase->query($sqlquery); 
     
  $html_head .= " window.parent.bottomof.location.replace(\"admin_chat_bot.php\");";
}

if($UNTRUSTED['action'] == "activiate"){
	if(empty($channelsplit)) $channelsplit = "";
  $html_head .= " window.parent.bottomof.location.replace(\"admin_chat_bot.php?channelsplit=$channelsplit\");";
}

$html_head .= "

function tellme(force){	
 if( (window.parent.bottomof.loaded) || (force==1) ){
   window.parent.bottomof.shouldifocus();
 } else {
 	setTimeout(\"tellme(1);\",3000);
 }
";

if($user_alert == "Y"){ 
$html_head .= "  alert(\"".$lang['txt139']."\"); ";
} 

$html_head .= " }

function doorbell(){   
";
if($show_arrival != "N"){
  $html_head .= " if(window.parent.bottomof.loaded) { window.parent.bottomof.shouldifocus(); }";
  if($user_alert == "Y"){ 
     $html_head .= "  alert(\"".$lang['txt140']."\"); ";	
  }
}
$html_head .= "
}

function seepages(id){
  url = 'details.php?id=' + id;
  window.open(url, 'chat54050872', 'width=590,height=350,menubar=no,scrollbars=1,resizable=1');
}
</SCRIPT>
";
?>
<script type="text/javascript">
function showit ( name ) {
  if (W3C) {
    if(document.getElementById(name))
      document.getElementById(name).style.visibility = "visible";
  } else if (NS4) {
    if(document.layers[name])
      document.layers[name].visibility = "show";
  } else {
    if(document.all[name])
      document.all[name].style.visibility = "visible";
  }
}

function hide ( name ) {
  if (W3C) {
    if(document.getElementById(name))
      document.getElementById(name).style.visibility = "hidden";
  } else if (NS4) {
    if(document.layers[name])
      document.layers[name].visibility = "hide";
  } else {
    if(document.all[name])
      document.all[name].style.visibility = "hidden";
  }
}
</SCRIPT>
<?php
print $html_head;
?>
<script type="text/javascript">
 
ns4 = (document.layers)? true:false
ie4 = (document.all)? true:false

bluecount = 0
redcount = 0

 
ns4 = (document.layers)? true:false;
ie4 = (document.all)? true:false;
 
 
ready = true;

NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;	
// W3C stands for the W3C standard, implemented in Mozilla (and Netscape 6) and IE5

// Function show(evt, name)
//	evt is a pointer to the Event object passed when the event occurs
//	name is the ID attribute of the element to show
function show ( evt, name ) {
  if (IE4) {
   evt = window.event;  //is it necessary?
  }

  var currentX,		//mouse position on X axis
      currentY,		//mouse position on X axis
      x,		//layer target position on X axis
      y,		//layer target position on Y axis
      docWidth,		//width of current frame
      docHeight,	//height of current frame
      layerWidth,	//width of popup layer
      layerHeight,	//height of popup layer
      ele;		//points to the popup element
  layerWidth = 200;
    layerHeight = 100;
  // First let's initialize our variables
  if ( W3C ) {
    ele = document.getElementById(name);
    try { currentX = evt.clientX; } catch (error6){ currentX = 40; }
    try { currentY = evt.clientY; } catch (error6){ currentY = 250; }
    try { docWidth = document.width; } catch (error6){  docWidth = 200;}
    try { docHeight = document.height; } catch (error6){ docHeight = 400;}
    try {  layerWidth = ele.style.width; } catch (error6){ layerWidth = 200;}
    try {  layerHeight = ele.style.height; } catch (error6){ layerHeight = 100;}     
  } else if ( NS4 ) {
    ele = document.layers[name];
    try { currentX = evt.pageX;  } catch (error6){ currentX = 40; }  
    try { currentY = evt.pageY; } catch (error6){ currentY = 250;  }  
    try { docWidth = document.width; } catch (error6){ docWidth = 200;}  
    try { docHeight = document.height; } catch (error6){  docHeight = 400;}  
    try { layerWidth = ele.clip.width; } catch (error6){ layerWidth = 200; }  
    try { layerHeight = ele.clip.height; } catch (error6){ layerHeight = 100;}       
  } else {	// meant for IE4
    ele = document.all[name];
    try {  currentX = evt.clientX; } catch (error6){ currentX = 40; } 
    try { currentY = evt.clientY; } catch (error6){ currentY = 250; } 
    try { docWidth = document.body.offsetWidth; } catch (error6){ docWidth = 200; } 
    try { docHeight = document.body.offsetHeight; } catch (error6){ docHeight = 400; }     
    layerWidth = 200; 
    try { layerHeight = ele.offsetHeight;  } catch (error6){ layerHeight = 100; } 
  }

  // Then we calculate the popup element's new position
  if ( ( currentX + layerWidth ) > docWidth ) {
    x = ( currentX - layerWidth );
  }
  else {
    x = currentX;
  }
  if ( ( currentY + layerHeight ) >= docHeight ) {
     y = ( currentY - layerHeight - 20 );
  }
  else {
    y = currentY + 20;
  }
  if ( IE4 ) {
    x += document.body.scrollLeft;
    y += document.body.scrollTop;
  } else if ( NS4)  {
  } else {
    x += window.pageXOffset;
    y += window.pageYOffset;
  }
// (for debugging purpose) alert("docWidth " + docWidth + ", docHeight " + docHeight + "\nlayerWidth " + layerWidth + ", layerHeight " + layerHeight + "\ncurrentX " + currentX + ", currentY " + currentY + "\nx " + x + ", y " + y);
  x = 5;
  // Finally, we set its position and visibility
   if ( NS4 ) {
   	try {
    ele.left = parseInt ( x );
    ele.top = parseInt ( y );
    ele.visibility = "show";
    } catch(error6) { } 
  } else {  // IE4 & W3C
  	try {
    ele.style.left = parseInt ( x );
    ele.style.top = parseInt ( y );
    ele.style.visibility = "visible";
    } catch(error6) { } 
  }
}

function unhide ( name ) {
  if (W3C) {
    if(document.getElementById(name))
      document.getElementById(name).style.visibility = "visible";
  } else if (NS4) {
    if(document.layers[name])
      document.layers[name].visibility = "show";
  } else {
    if(document.all[name])
      document.all[name].style.visibility = "visible";
  }
}
 

</SCRIPT>
</HEAD>

<body bgcolor=<?php echo $color_background;?>>
<center>
<table bgcolor=<?php echo $color_background;?> cellpadding=0 cellspacing=0 border=0 width=280>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>

<table bgcolor=EEEEEE width=100%><tr><td  bgcolor=EEEEEE align=center>
<?php
$sqlquery = "SELECT * FROM livehelp_config";
$data = $mydatabase->query($sqlquery);
$data = $data->fetchRow(DB_FETCHMODE_ASSOC);
$offset = $data['offset'];
if($offset == ""){ $offset = 0; }
$when = mktime ( date("H")+$offset, date("i"), date("s"), date("m") , date("d"), date("Y") );
?>
<?php echo date("F j, Y, g:i a",$when); ?>
</td></tr></table>
</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td>
<FORM method=post action=admin_actions.php Target=_Blank name=admin_actions>
<?php
$html = gethtml();
print $html;	
?>
</FORM>
</td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
</table>
<SCRIPT type="text/javascript">
function open_url(url){
  window.open(url, 'chat545087', 'width=590,height=400,menubar=no,scrollbars=1,resizable=1'); 
}
</SCRIPT><br>
<!-- auto invite and refresh rate: -->
<table bgcolor=<?php echo $color_background;?> cellpadding=0 cellspacing=0 border=0 width=280>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=FEFEFE Align=center>
<?php
if($auto_invite =="Y"){
  print $lang['txt132'] . "<a href=\"javascript:showhelpwindow(2)\">(?)</a> : <font color=007700><b>".$lang['ON']."</b></font> (<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=$operators&autoinvite=OFF&showvisitors=".$UNTRUSTED['showvisitors'];
    if ($isadminsetting!="L") {  print " onclick=\"javascript:open_url('autoinvite.php');\""; }
    	print ">".$lang['MakeOFF']."</a>)";
} else {
  print $lang['txt132'] . "<a href=\"javascript:showhelpwindow(2)\">(?)</a> : <font color=000077><b>".$lang['OFF']."</b></font> (<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=$operators&autoinvite=ON&showvisitors=".$UNTRUSTED['showvisitors'];
    if ($isadminsetting!="L") {  print " onclick=\"javascript:open_url('autoinvite.php');\""; }
    	print ">".$lang['MakeON']."</a>)";
}
  if ($isadminsetting!="L") {  print "<b>|</b> <a href=autoinvite.php target=_blank>".$lang['EDIT']."</a>)"; }
?>
<br><br>
	<FORM ACTION=admin_users_refresh.php METHOD=POST NAME="adminrefresh">
		
		<input type=hidden name="hidechatters" value="<?php echo $hidechatters; ?>">
		<input type=hidden name="ignorelist"   value="<?php echo $ignorelist; ?>">
    <input type=hidden name="numchats"     value="<?php echo $numchatsnow ?>">
    <input type=hidden name="operators"    value="<?php echo $operators; ?>">
    <input type=hidden name="showvisitors" value="<?php echo $UNTRUSTED['showvisitors']; ?>">		
		<select name=togglerefresh> 
			     <option value="AJAX" <?php if($CSLH_Config['admin_refresh'] == "AJAX"){ echo " SELECTED "; } ?> > AJAX </option>
			     <option value="auto" <?php if($CSLH_Config['admin_refresh'] == "auto"){ echo " SELECTED "; } ?> > <?php echo $lang['Automatic']; ?></option>			     
			     <option value="10" <?php if($CSLH_Config['admin_refresh'] == "10"){ echo " SELECTED "; } ?> > 10 <?php echo $lang['Seconds']; ?></option>
			     <option value="15" <?php if($CSLH_Config['admin_refresh'] == "15"){ echo " SELECTED "; } ?> > 15 <?php echo $lang['Seconds']; ?></option>
			     <option value="20" <?php if($CSLH_Config['admin_refresh'] == "20"){ echo " SELECTED "; } ?> > 20 <?php echo $lang['Seconds']; ?></option>
			     <option value="25" <?php if($CSLH_Config['admin_refresh'] == "25"){ echo " SELECTED "; } ?> > 25 <?php echo $lang['Seconds']; ?></option>
			     <option value="30" <?php if($CSLH_Config['admin_refresh'] == "30"){ echo " SELECTED "; } ?> > 30 <?php echo $lang['Seconds']; ?></option>			     
</select><a href="javascript:showhelpwindow(1)">(?)</a> <a href="javascript:adminrefresh.submit();"><img src=images/go.gif border=0 width=20 height=20></a></FORM> 

	<FORM ACTION=live.php METHOD=POST TARGET=_top NAME="adminlang">
		<select name="speak">
			<?php
// get list of installed langages..
if(!(empty($_COOKIE['speaklanguage']))){ $CSLH_Config['speaklanguage'] = $_COOKIE['speaklanguage']; }
$dir = "lang" . C_DIR;
if($handle=opendir($dir)){
	$i=0;
	while ( ($i<100) && (false !== ($file = readdir($handle)))) {
	 $i++;
	 if (ereg("lang-",$file))
		{
		if ( (is_file("$dir".C_DIR."$file") && ($file!="lang-.php")))
			{
			$language = str_replace("lang-","",$file);
			$language = str_replace(".php","",$language);
			?><option value=<?php echo $language; ?> <?php if ($CSLH_Config['speaklanguage'] == $language){ print " SELECTED "; } ?> ><?php echo $language; ?> </option><?php
			}
		}
	}
// no list guess...
} else {
      $languages = array("Dutch","English","English_uk","French","German","Italian","Portuguese_Brazilian","Spanish","Swedish");
			for($i=0;$i<count($languages); $i++){
  			$language = $languages[$i];
  			?><option value=<?php echo $language ?> <?php if ($CSLH_Config['speaklanguage'] == $language){ print " SELECTED "; } ?> ><?php echo $language ?> </option><?php		
  		}	
}		
?>
			</select><a href="javascript:adminlang.submit();"><img src=images/go.gif border=0 width=20 height=20></a></FORM> 
 
 </td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
<tr>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=278 height=1></td>
<td bgcolor=000000><img src=images/blank.gif width=1 height=1></td>
</tr>
</table>

</center>

<SCRIPT type="text/javascript">
 <?php echo $onload; ?>

function showhelpwindow(num){	
	  window.open('helpwindow.php?info=' + num, 'helpwindow', 'width=585,height=390,menubar=no,scrollbars=1,resizable=1');
}

</SCRIPT>
 <?php
  echo $stuffatbottom; 
  echo $insitewav;
  echo $soundwav;

function gethtml(){
  global $insitewav,$soundwav,$hidechatters,$color_alt2,$color_alt4,$color_background,$numchatsnow,$UNTRUSTED,$CSLH_Config,$operators,$operators_array,$ignorelist_array,$ignorelist,$lang,$show_arrival,$mydatabase,$identity,$onload,$myid,$stuffatbottom,$user_alert;
  $onload = "";

      $DIVS = " "; 

  $lasthtml = "";
  $timeof = rightnowtime();
    
  // update operators timestamp .
  $sql = "UPDATE livehelp_users set lastaction='$timeof' WHERE sessionid='".$identity['SESSIONID']."' ";
  $mydatabase->query($sql);
  
   /// Current Chat Requests: -----------------------------------------------------------
   $html = "<table width=100% bgcolor=".$color_alt2."><tr>";
   $html .= "<td width=20>&nbsp;</td>";  
   $html .= "<td><b>".$lang['txt230']."</b></td></tr></table>";
   
   $prev = mktime ( date("H"), date("i")-10, date("s"), date("m"), date("d"), date("Y") );
   $oldtime = date("YmdHis",$prev);

   $sqlquery = "SELECT * FROM livehelp_users WHERE status='chat' AND isoperator='N' ORDER by showedup";
   $chatcheck = $mydatabase->query($sqlquery);
   $numchatnow = $chatcheck->numrows();
 
   $html .= "<table width=100%>";
   $chatrequests = 0;

   while($visitor = $chatcheck->fetchRow(DB_FETCHMODE_ASSOC)){
    $actionlink = "";
    $chatting ="";
    // see if we are in the same department as this user..
    $sqlquery = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='" . $visitor['department'] . "' ";
    $data_check = $mydatabase->query($sqlquery);          
    if($data_check->numrows() == 0){ 
       // we are not in the department that this user is chatting in.. 
    } else {
       // see if anyone is chatting with this person. 
       $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE channel='" . $visitor['onchannel'] . "'";
       $counting = $mydatabase->query($sqlquery);
       $showedup = substr($visitor['showedup'],8,2) . ":" . substr($visitor['showedup'],10,2);
       if($counting->numrows() == 0){
            $updated_ignore = $ignorelist . "," . $visitor['user_id'];
            $chatting = "<img src=images/needaction.gif width=21 height=20 border=0>";  
            $actionlink = "[<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=$operators&action=activiate&who=" . $visitor['user_id'] . "&whatchannel=" . $visitor['onchannel'] . "&showvisitors=".$UNTRUSTED['showvisitors']."&needaction=1>".$lang['Activate']."</a>]\n";            
            if (!(in_array($visitor['user_id'],$ignorelist_array))){
             $actionlink .= "[<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$updated_ignore&numchats=$numchatsnow&operators=$operators&showvisitors=".$UNTRUSTED['showvisitors'].">".$lang['Ignore']."</a>]\n";
             $onload = " setTimeout(\"tellme(0);\",500); ";
             $chatrequests++;
             if( ($user_alert == "N") || ($user_alert == "")){ 
              $soundwav = "<SCRIPT  type=\"text/javascript\">\n";
              $soundwav .= "var MSIE=navigator.userAgent.indexOf(\"MSIE\");\n";
              $soundwav .= "var NETS=navigator.userAgent.indexOf(\"Netscape\");\n";
              $soundwav .= "var OPER=navigator.userAgent.indexOf(\"Opera\");\n";
              $soundwav .= "if((MSIE>-1) || (OPER>-1)) {\n";
              $soundwav .= "document.write(\"<BGSOUND SRC=sound.wav LOOP=1>\");\n";
              $soundwav .= "} else {\n";
              $soundwav .= "document.write(\"<EMBED SRC=sound.wav AUTOSTART=TRUE HIDDEN=true VOLUME=100 LOOP=FALSE><noembed><BGSOUND SRC=sound.wav LOOP=1></noembed>\");\n";
              $soundwav .= "}\n";
              $soundwav .= "</SCRIPT>\n";              
              $stuffatbottom .= "\n<SCRIPT type=\"text/javascript\">top.status=\"[activate]\";top.status=\"\"; setTimeout(\"tellme(0);\",1500); </SCRIPT>\n";
             }      
           }  
      if (!(in_array($visitor['user_id'],$ignorelist_array))){
      	       $html .= "<tr><td width=10> $showedup </td><td nowrap=nowrap><table><tr><td>$chatting</td><td><a href=javascript:seepages(" . $visitor['user_id'] . ") onMouseOver=\"try { hide('visitorsdiv');hide('chattersdiv');show(event, 'info-" . $visitor['username'] . "'); } catch(dam){} return true;\" onMouseOut=\"try { showit('chattersdiv');showit('visitorsdiv');hide('info-" . $visitor['username'] . "'); } catch(dam){} return true;\">" . $visitor['username'] . "</a>  $actionlink </td></tr></table></td></tr>\n";
 	    } else {
 	    	       $html .= "<tr bgcolor=#DDDDDD><td width=10> $showedup </td><td nowrap=nowrap><table><tr><td>$chatting</td><td><a href=javascript:seepages(" . $visitor['user_id'] . ") onMouseOver=\"try { hide('visitorsdiv');hide('chattersdiv');show(event, 'info-" . $visitor['username'] . "');} catch(dam){} return true;\" onMouseOut=\"try { showit('chattersdiv');showit('visitorsdiv');hide('info-" . $visitor['username'] . "'); } catch(dam){} return true;\">" . $visitor['username'] . "</a>  $actionlink </td></tr></table></td></tr>\n";
 	    }

       $chatrequests++;
      $sqlquery = "SELECT * from livehelp_users WHERE sessionid='" . $visitor['sessionid'] . "'";

      $user_info = $mydatabase->query($sqlquery);
      $user_info = $user_info->fetchRow(DB_FETCHMODE_ASSOC);

      $sqlquery = "SELECT * from livehelp_visit_track WHERE sessionid='".$visitor['sessionid']."' Order by whendone DESC LIMIT 1";
      $page_trail = $mydatabase->query($sqlquery);
      $page = $page_trail->fetchRow(DB_FETCHMODE_ASSOC);
  
      $sqlquery = "SELECT * from livehelp_departments WHERE recno='".$visitor['department']."'";
      $tmp = $mydatabase->query($sqlquery);
      $nameof = $tmp->fetchRow(DB_FETCHMODE_ASSOC);
       $nameof = $nameof['nameof'];
  
       $DIVS .= "<DIV ID=\"info-" . $visitor['username'] . "\" STYLE=\"position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;\">
      <TABLE BORDER=\"0\" WIDTH=\"250\"><TR BGCOLOR=\"#000000\"><TD> 
      <TABLE BORDER=\"0\" WIDTH=\"248\" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD width=1 BGCOLOR=#".$color_alt2."><img src=images/blank.gif width=7 height=120></TD><TD BGCOLOR=\"#".$color_alt2."\" valign=top>
      <FONT COLOR=\"#000000\">
      <b>".$lang['referer'].":</b><br>" . $user_info['camefrom'] . "<br>
      <b>".$lang['txt43'].":</b><br>$nameof<br>
      <b>Url:</b><br><a href=" . $page['location'] . "  target=_blank>" . $page['location'] . "</a><br>";
      $now = date("YmdHis");
      if(($user_info['lastaction'] =="") || ($user_info['lastaction'] < 20002020202020) )
         $user_info['lastaction'] = $now;
      $thediff = $now - $user_info['lastaction'];
       $DIVS .= "<b>".$lang['txt65'].":</b><br>$thediff Seconds ago<br> 
       </FONT></TD></TR></TABLE></TD></TR></TABLE></DIV>"; 
      }
     }
    }
  
    if($chatrequests == 0){
     	$html .= "<tr><td>" . $lang['txt231'] . "</td></tr>";
    }
   $html .= "</table>";
 
 
 //------------------------------------------------------------------------
  $html .= "<table width=100% bgcolor=".$color_alt2."><tr>";
  $sqlquery = "SELECT * FROM livehelp_users WHERE status='chat' AND isoperator='N'";
  $chatcheck = $mydatabase->query($sqlquery);
  $numchatnow = $chatcheck->numrows();
  while($visitor = $chatcheck->fetchRow(DB_FETCHMODE_ASSOC)){
   $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE channel='" . $visitor['onchannel'] . "'";
   $counting = $mydatabase->query($sqlquery);
   if( ($counting->numrows() == 0) && (!(in_array($visitor['user_id'],$ignorelist_array))) )
     $UNTRUSTED['hidechatters'] = 0;    	
   }
  
  if($UNTRUSTED['hidechatters'] == 1) { 
     $html .= "<td width=20><a href=admin_users_refresh.php?hidechatters=0&ignorelist=$ignorelist&numchats=$numchatsnow&operators=".$UNTRUSTED['operators']."&hidechatters=0><img src=images/plus.gif border=0></a></td>";  
  } else { 
     $html .= "<td width=20><a href=admin_users_refresh.php?hidechatters=1&ignorelist=$ignorelist&numchats=$numchatsnow&operators=".$UNTRUSTED['operators']."&hidechatters=1><img src=images/minus.gif border=0></a></td>";
  } 
  
  $html .= "<td><b> " . $lang['chat_text'] . " </b></td></tr></table>";
  

  $prev = mktime ( date("H"), date("i")-4, date("s"), date("m"), date("d"), date("Y") );
  $oldtime = date("YmdHis",$prev);

  // select out all of the operators that are online chatting:  
  $sqlquery = "SELECT * FROM livehelp_users WHERE status='chat' ORDER by showedup";
  $chatcheck = $mydatabase->query($sqlquery);
  $numchatnow = $chatcheck->numrows();
  $chatboxes =0;
  
   $html .= "<table width=100% cellpadding=0 cellspacing=0 border=0>";
   
  if($UNTRUSTED['hidechatters'] != 1) {    
   if($chatcheck->numrows() == 0){
   	$html .= "<tr><td>".$lang['no_chat']."</td></tr>";
   }
   
  $sqlquery = "SELECT * FROM livehelp_users WHERE isonline='Y' and isoperator='Y' ORDER by username";
  $chatcheck = $mydatabase->query($sqlquery);
   while($operatorsarray = $chatcheck->fetchRow(DB_FETCHMODE_ASSOC)){
    $actionlink = "";
    $chatting ="";
    // Operators can be on multiple channels so see if one of the channels they are on is one we are on.      
    $foundcommonchannel = 0;
    $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id='".$operatorsarray['user_id']."'";
    $mychannels = $mydatabase->query($sqlquery);
    while($rowof = $mychannels->fetchRow(DB_FETCHMODE_ASSOC)){
       $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='".$rowof['channel']."'";
       $counting = $mydatabase->query($sqlquery);        
       if($counting->numrows() != 0){ 
           $foundcommonchannel = $rowof['channel'];
       }
    }
    if($foundcommonchannel == 0){
       $chatting = "<img src=images/noton.gif width=19 height=18 border=0>";
     if($myid != $operatorsarray['user_id'])
        $actionlink = "[<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=$operators&action=activiate&who=" . $operatorsarray['user_id'] . "&whatchannel=" . $operatorsarray['onchannel'] . "&conferencein=yes&showvisitors=".$UNTRUSTED['showvisitors']."><font color=007700>". $lang['begin'] . "</font></a>]\n";
      else {
         $actionlink = ""; 
         $chatting = "<img src=images/active.gif width=19 height=18 border=0>\n";
      }            
    } else {
        $chatting = "<table cellpadding=0 cellspacing=0 border=0 bgcolor=".$rowof['txtcolor_alt']."><tr><td><img src=images/active.gif width=19 height=18 border=0></td></tr></table>\n";
        if($operatorsarray['user_id'] != $myid)
           $actionlink = "[<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=$operators&action=leave&who=" . $operatorsarray['user_id'] . "&whatchannel=$foundcommonchannel&clearchannel=N&showvisitors=".$UNTRUSTED['showvisitors'].">".$lang['stop_chat']."</a>]\n"; 
        }
    
    if($myid != $operatorsarray['user_id'])   
      $html .= "<tr><td width=21><img src=images/operator.gif width=21 height=20 border=0></td><td colspan=3><table><tr><td>$chatting</td><td width=90% align=left>  <b>" . $operatorsarray['username'] . "</b>  $actionlink </td></tr></table></td></tr>\n";
    else 
      $lasthtml = "<tr><td width=21><img src=images/operator.gif width=21 height=20 border=0></td><td colspan=3><table><tr><td>$chatting</td><td width=90% align=left>  <b>" . $operatorsarray['username'] . "</b>  $actionlink </td></tr></table></td></tr>\n";
      
    
    // show all the operators chats:
    $q = "SELECT livehelp_users.* FROM livehelp_users,livehelp_operator_channels WHERE livehelp_users.user_id=livehelp_operator_channels.userid AND livehelp_users.status='chat' AND livehelp_operator_channels.user_id=" . $operatorsarray['user_id'] . " ORDER by showedup";
    $sth = $mydatabase->query($q);
    while($chatters = $sth->fetchRow(DB_FETCHMODE_ASSOC)){
      
       
      // see if we are in the same department as this user..
      $sqlquery = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='" . $chatters['department'] . "' ";
      $data_check = $mydatabase->query($sqlquery);          
      if($data_check->numrows() == 0){ 
        // we are not in the department that this user is chatting in.. 
       } else {
 
            // see if we are chatting with them
           $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE user_id='$myid' And channel='" . $chatters['onchannel'] . "'";
           $counting = $mydatabase->query($sqlquery);
           $checkbox = "";
           $actionlinkouter ="";
           if($counting->numrows() == 0){
             $chatting = "<img src=images/noton.gif width=19 height=18 border=0>";
             $actionlink = "[<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=$operators&action=activiate&who=" . $chatters['user_id'] . "&whatchannel=" . $chatters['onchannel'] . "&showvisitors=".$UNTRUSTED['showvisitors']."&conferencein=yes><font color=007700>Join Chat</font></a>]\n";
              $actionlinkouter = $actionlink;
           } else {
            $chatboxes++;
            $rowof = $counting->fetchRow(DB_FETCHMODE_ASSOC);
            $chatting = "<table cellpadding=0 cellspacing=0 border=0 bgcolor=".$rowof['channelcolor']."><tr><td><img src=images/active.gif width=19 height=18 border=0></td></tr></table>\n";
            $actionlink = "";
            $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE channel='" . $chatters['onchannel'] . "'";
            $counting = $mydatabase->query($sqlquery);
            if($counting->numrows() >1){
              $actionlink = "[<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=$operators&action=leave&who=" . $chatters['user_id'] . "&whatchannel=" . $chatters['onchannel'] . "&showvisitors=".$UNTRUSTED['showvisitors'].">Un-Conference</a>]\n ";
            }
            $checkbox = "<input type=checkbox name=session__".$chatters['user_id']." value=".$chatters['sessionid']." border=0>";
            $actionlink .= "[<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=$operators&action=stop&who=" . $chatters['user_id'] . "&whatchannel=" . $chatters['onchannel'] . "&showvisitors=".$UNTRUSTED['showvisitors']."><font color=990000>" . $lang['stop'] . "</font></a>]\n"; 
           }
          
        // see if anyone is chatting with this person. 
       $sqlquery = "SELECT * FROM livehelp_operator_channels WHERE channel='" . $chatters['onchannel'] . "'";
       $counting = $mydatabase->query($sqlquery);
       if( ($counting->numrows() == 0) && (!(in_array($chatters['user_id'],$ignorelist_array))) ){
        } else {
        	if($myid != $operatorsarray['user_id'])  
            $html .= "<tr bgcolor=#EFEFEF><td>&nbsp;</td><td><img src=images/blank.gif width=5 height=5 border=0></td><td nowrap=nowrap>$chatting</td><td width=90% align=left><a href=javascript:seepages(" . $chatters['user_id'] . ") onMouseOver=\" try { hide('visitorsdiv');hide('chattersdiv');show(event, 'info-" . $chatters['username'] . "'); } catch(dam){} return true;\" onMouseOut=\"try { showit('chattersdiv');showit('visitorsdiv');hide('info-" . $chatters['username'] . "');} catch(dam){} return true;\">" . $chatters['username'] . "</a>  $actionlinkouter </td></tr>\n";
          else
            $lasthtml .= "<tr bgcolor=#EFEFEF><td>&nbsp;</td><td>$checkbox</td><td nowrap=nowrap>$chatting</td><td width=90% align=left><a href=javascript:seepages(" . $chatters['user_id'] . ") onMouseOver=\"try { hide('visitorsdiv');hide('chattersdiv');show(event, 'info-" . $chatters['username'] . "'); } catch(dam){} return true;\" onMouseOut=\"try { showit('chattersdiv');showit('visitorsdiv');hide('info-" . $chatters['username'] . "'); } catch(dam){} return true;\">" . $chatters['username'] . "</a>  $actionlink </td></tr>\n";
        }
      }    
     
$sqlquery = "SELECT * from livehelp_users WHERE sessionid='" . $chatters['sessionid'] . "'";

$user_info = $mydatabase->query($sqlquery);
$user_info = $user_info->fetchRow(DB_FETCHMODE_ASSOC);

$sqlquery = "SELECT * from livehelp_visit_track WHERE sessionid='".$chatters['sessionid']."' Order by whendone DESC LIMIT 1";
$page_trail = $mydatabase->query($sqlquery);
$page = $page_trail->fetchRow(DB_FETCHMODE_ASSOC);
  
$sqlquery = "SELECT * from livehelp_departments WHERE recno='".$chatters['department']."'";
$tmp = $mydatabase->query($sqlquery);
$nameof = $tmp->fetchRow(DB_FETCHMODE_ASSOC);
$nameof = $nameof['nameof'];
  
 $DIVS .= "<DIV ID=\"info-" . $chatters['username'] . "\" STYLE=\"position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;\">
<TABLE BORDER=\"0\" WIDTH=\"250\"><TR BGCOLOR=\"#000000\"><TD> 
<TABLE BORDER=\"0\" WIDTH=\"248\" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD width=1 BGCOLOR=#".$color_alt2."><img src=images/blank.gif width=7 height=120></TD><TD BGCOLOR=\"#".$color_alt2."\" valign=top>
<FONT COLOR=\"#000000\">
<b>".$lang['referer'].":</b><br>" . $user_info['camefrom'] . "<br>
<b>".$lang['txt43'].":</b><br>$nameof<br>
<b>Url:</b><br><a href=" . $page['location'] . "  target=_blank>" . $page['location'] . "</a><br>";
$now = date("YmdHis");
if(($user_info['lastaction'] =="") || ($user_info['lastaction'] < 20002020202020) )
   $user_info['lastaction'] = $now;
$thediff = $now - $user_info['lastaction'];
 $DIVS .= "<b>".$lang['txt65'].":</b><br>$thediff ".$lang['Seconds']."<br>
 </FONT></TD></TR></TABLE></TD></TR></TABLE></DIV>"; 
      
    }        
    }
  } 
 
 
 
 
    $html .= $lasthtml . "</table>";  
 
 $html .= "<DIV ID=\"chattersdiv\" STYLE=\"z-index: 2;\">"; 	
 if ( ($UNTRUSTED['hidechatters'] != 1) && ($chatboxes > 0) ){
  $html .= "<table cellpadding=0 cellspacing=0 border=0>";
  $html .= "<tr><td><img src=images/blank.gif width=18 height=22 border=0></td><td align=left><img src=images/arrow_ltr.gif width=38 height=22><select name=whattodochat onchange=updatepeople(1)>";
  $html .= "<option value=\"\">".$lang['txt207'].":</option>";
  $html .= "<option value=stop>".$lang['stop_chat']."</option>";
  $html .= "<option value=transfer>".$lang['transfer_chat']."</option>"; 
  $html .= "<option value=conference>".$lang['conference_chats']."</option>";
  $html .= "</select> <a href=javascript:updatepeople(1)><img src=images/go.gif border=0 width=20 height=20></a></td></tr>";
  $html .= "</table>";
 }
  $html .= "</DIV>";
 
 
 ///--------------------------------------------------------------------------------------------------
 // get the count of active visitors in the system right now.
 $timeof = rightnowtime();
 $prev = mktime ( date("H"), date("i")-4, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 $sqlquery = "SELECT * FROM livehelp_users WHERE lastaction>'$oldtime' AND status!='chat' AND isoperator!='Y' ";

  // make list of ignored visitors:
 $ipadd = split(",",$CSLH_Config['ignoreips']); 
 for($i=0;$i<count($ipadd); $i++){
 	if(!(empty($ipadd[$i])))
 	 $sqlquery .= " AND ipaddress NOT LIKE '".$ipadd[$i]."%' ";
 } 
 $sqlquery .= " ORDER by lastaction DESC";

 $visitors = $mydatabase->query($sqlquery);
 $onlinenow = $visitors->numrows();
 $hiddennow = 0;
 $html .= "<table width=100% cellpadding=0 cellspacing=0 bgcolor=".$color_alt2."><tr><td>";
 
  if($UNTRUSTED['showvisitors'] == 1) { 
     $html .= "<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=".$UNTRUSTED['operators']."&showvisitors=2><img src=images/minus.gif border=0></a>";
  } else { 
     $html .= "<a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=".$UNTRUSTED['operators']."&showvisitors=1><img src=images/plus.gif border=0></a>";  
  } 
 
if($UNTRUSTED['showvisitors'] == 1) {
 $html .= "</td><td><b>" .  $lang['visit'] . " <font color=007700>[onlinenow] ".$lang['online']."</font> <font color=777777>[hiddennow] ".$lang['Hidden']."</font> </td></tr></table>"; 
 
 
 $html .= "<table width=100%><tr bgcolor=$color_background><td>&nbsp;</td><td><b>ID</b></td><td><b>".$lang['status'].":</b></td><td><b>#</b></td></tr>";

while($visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
   $chatting = "";
    // see if we are in the same department as this user..
   $sqlquery = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='".$visitor['department']."' ";
   $data_check = $mydatabase->query($sqlquery); 
   if( ($visitor['department']!=0) && ($data_check->numrows() == 0) ){ 
       $hiddennow++;
       $onlinenow--;
   } else {
  if(empty($visitor['user_alert'])) $visitor['user_alert'] = 0; 
  if( ($visitor['isoperator'] != "Y") && ($visitor['user_alert'] != 1)){
      $onload = " setTimeout(\"doorbell();\",700); ";
      if( ($user_alert == "N") || ($user_alert == "") ){ 
        if ($show_arrival != "N"){
              $insitewav = "<SCRIPT  type=\"text/javascript\">\n";
              $insitewav .= "var MSIE=navigator.userAgent.indexOf(\"MSIE\");\n";
              $insitewav .= "var NETS=navigator.userAgent.indexOf(\"Netscape\");\n";
              $insitewav .= "var OPER=navigator.userAgent.indexOf(\"Opera\");\n";
              $insitewav .= "if((MSIE>-1) || (OPER>-1)) {\n";
              $insitewav .= "document.write(\"<BGSOUND SRC=insite.wav LOOP=1>\");\n";
              $insitewav .= "} else {\n";
              $insitewav .= "document.write(\"<EMBED SRC=insite.wav AUTOSTART=TRUE HIDDEN=true VOLUME=100 LOOP=FALSE><noembed><BGSOUND SRC=insite.wav LOOP=1></noembed>\");\n";
              $insitewav .= "}\n";
              $insitewav .= "</SCRIPT>\n";     
        }      
      } 
      $sql = "UPDATE livehelp_users SET user_alert='1' WHERE user_id='" . $visitor['user_id'] . "' ";
      $mydatabase->query($sql);
  }
  $sqlquery = "SELECT * from livehelp_visit_track WHERE sessionid='" . $visitor['sessionid'] . "'";
  $my_count = $mydatabase->query($sqlquery);
  $my_count = $my_count->numrows();
 
  $html .= "<tr><td width=10><input type=checkbox name=session__".$visitor['user_id']." value=".$visitor['sessionid']." border=0></td><td>";    
  $html .= "<a href=javascript:seepages(" . $visitor['user_id'] . ") onMouseOver=\" try { hide('visitorsdiv');hide('chattersdiv');show(event, 'info-" . $visitor['username'] . "'); } catch(dam){} return true;\" onMouseOut=\"try { showit('chattersdiv');showit('visitorsdiv');hide('info-" . $visitor['username'] . "'); } catch(dam){} return true;\">" . $visitor['username'] . "</a>";
 
  $sqlquery = "SELECT * from livehelp_users WHERE user_id='" . $visitor['user_id'] . "'";
  $user_info = $mydatabase->query($sqlquery);
  $user_info = $user_info->fetchRow(DB_FETCHMODE_ASSOC); 

  $sqlquery = "SELECT * from livehelp_visit_track WHERE sessionid='" . $visitor['sessionid'] . "' Order by whendone DESC";
  $page_trail = $mydatabase->query($sqlquery);
  $page = $page_trail->fetchRow(DB_FETCHMODE_ASSOC);

  $sqlquery = "SELECT * from livehelp_departments WHERE recno='" . $visitor['department'] . "'";
  $tmp = $mydatabase->query($sqlquery);
  $nameof = $tmp->fetchRow(DB_FETCHMODE_ASSOC);
  $nameof = $nameof['nameof'];
  
  $query = "SELECT whendone from livehelp_visit_track WHERE sessionid='".filter_sql($visitor['sessionid'])."' Order by whendone LIMIT 1";
  $page_tmprow = $mydatabase->query($query); 
  $tmprow = $page_tmprow->fetchRow(DB_FETCHMODE_ASSOC);
  $later = $tmprow['whendone'];
  
  $DIVS .= "<DIV ID=\"info-" . $visitor['username'] . "\" STYLE=\"position: absolute; z-index: 20; visibility: hidden; top: 0px; left: 0px;\">
<TABLE BORDER=\"0\" WIDTH=\"300\"><TR BGCOLOR=\"#000000\"><TD> 
<TABLE BORDER=\"0\" WIDTH=\"100%\" CELLPADDING=0 CELLSPACING=0 BORDER=0><TR><TD width=1 BGCOLOR=#".$color_alt2."><img src=images/blank.gif width=7 height=120></TD><TD BGCOLOR=\"#".$color_alt2."\" valign=top>
<FONT COLOR=\"#000000\">
<b>Referer:</b><br>" . $user_info['camefrom'] . "<br>";
if(empty($page['location'])) 
  $page['location'] ="";
 $DIVS .= "<b>".$lang['timeonline'].":</b>". secondstoHHmmss(timediff($later,date("YmdHis"))) . "<br> 
<b>".$lang['txt43'].":</b><br>$nameof<br>
<b>url:</b><br>" . $page['location'] . "<br>";
$now = date("YmdHis");
$thediff = $now - $user_info['lastaction'];
 $DIVS .= "<b>".$lang['lastact'].":</b><br>$thediff ".$lang['Seconds']."<br>
 </FONT></TD></TR></TABLE></TD></TR></TABLE></DIV>";  
 $html .= "</td>";
  switch($visitor['status']){
    case("DHTML"):
       $html .= "<td>L:<img src=images/invited.gif> ";
       break;
    case("request"):
       $html .= "<td>P:<img src=images/invited.gif> ";
       break;
    case("invited"):
       $html .= "<td><img src=images/invited2.gif>  ";
       break;
     case("qna"):
       $html .= "<td><img src=images/qna.gif>";
       break; 
    case("stopped"):
       $html .= "<td><img src=images/stopped.gif> ";
       break;    
    case("message"):
       $html .= "<td><img src=images/message.gif> ";
       break;            
    default:
      $html .= "<td><a href=layer.php?selectedwho=" . $visitor['user_id'] . " target=_blank>".$lang['invite']."</a> ";
      break;
    }
  $html .= "</td>";
  $html .= "<td>$my_count</td></tr>";
  }  
 }
 $html = str_replace("[onlinenow]",$onlinenow,$html);
 $html = str_replace("[hiddennow]",$hiddennow,$html); 
 
 $html .= "</table>";
 $html .= "<DIV ID=\"visitorsdiv\" STYLE=\"z-index: 2;\">";
 if($onlinenow > 0){
  $html .= "<table>";
  $html .= "<tr><td><img src=images/arrow_ltr.gif width=38 height=22><select name=whattodo onchange=updatepeople(2)>";
  $html .= "<option value=\"\">".$lang['txt207'].":</option>";
  $html .= "<option value=DHTML>".$lang['Invite'].":".$lang['layer']."</option>";
  $html .= "<option value=pop>".$lang['Invite'].":".$lang['popup']."</option>";
  $html .= "</select> <a href=javascript:updatepeople(2)><img src=images/go.gif border=0 width=20 height=20></a></td></tr>";
  $html .= "</table><center><a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=".$UNTRUSTED['operators']."&showvisitors=2>".$lang['txt208']."</a><br></center>";
 }
  $html .= "</DIV>"; 

 } else {

while($visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
   $chatting = "";
    // see if we are in the same department as this user..
   $sqlquery = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='".$visitor['department']."' ";
   $data_check = $mydatabase->query($sqlquery); 
   if( ($visitor['department']!=0) && ($data_check->numrows() == 0) ){ 
       $hiddennow++;
       $onlinenow--;
   } 
}

 $html .= "</td><td><b>" .  $lang['visit'] . " <font color=007700>$onlinenow ".$lang['online']."</font> <font color=777777>$hiddennow ".$lang['Hidden']."</font></b></td></tr></table>";
  
 $html .= "<table width=100%><tr bgcolor=$color_background><td colspan=4 align=center><font color=#990000><i>".$lang['Hidden']."</i></a></font><br><a href=admin_users_refresh.php?hidechatters=". $hidechatters . "&ignorelist=$ignorelist&numchats=$numchatsnow&operators=".$UNTRUSTED['operators']."&showvisitors=1>".$lang['txt209']."</a></td></tr></table>";
 }
$html = $html . $DIVS;
return $html;
}
if(!($serversession))
  $mydatabase->close_connect();
?>