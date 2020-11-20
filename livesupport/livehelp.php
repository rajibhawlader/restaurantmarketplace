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
$query = "SELECT * 
          FROM livehelp_users 
          WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);  
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isnamed = $people['isnamed'];
$lastaction = date("YmdHis");
$timeof = date("YmdHis");
$startdate =  date("Ymd");

if(!(isset($UNTRUSTED['tab']))){ $UNTRUSTED['tab'] = ""; } else { $UNTRUSTED['tab'] = intval($UNTRUSTED['tab']);}
if(!(isset($UNTRUSTED['action']))){ $UNTRUSTED['action'] = ""; }
if(!(isset($UNTRUSTED['makenamed']))){ $UNTRUSTED['makenamed'] = ""; }
if(!(isset($UNTRUSTED['doubleframe']))){ 
	 $doubleframe = "no"; }
else { $doubleframe = $UNTRUSTED['doubleframe']; }	 
if(!(isset($UNTRUSTED['department']))){ $UNTRUSTED['department'] = 0; }


// get department information...
$where="";
// if no department is sent in the query string then re-direct 
// to department choice page:
if( (empty($UNTRUSTED['department'])) || ($UNTRUSTED['department'] == 0)){ 
  Header("Location: choosedepartment.php");
  exit;
} else {
	$UNTRUSTED['department'] = intval($UNTRUSTED['department']);
} 
   $query = "SELECT * 
             FROM livehelp_departments 
             WHERE recno=". intval($UNTRUSTED['department']);
   $data_d = $mydatabase->query($query);  
   if($data_d->numrows() == 0){
     print "<font color=990000>Error no department with that id</font>";
     exit;	
   }
   $department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC);
   $UNTRUSTED['department'] = $department_a['recno'];
   $topframeheight = $department_a['topframeheight'];
   if($topframeheight<30) 
      $topframeheight = 30;
   if($department_a['requirename'] == "N"){
      $alterisnamed = ", isnamed='Y',askquestions='N' ";      
      $isnamed="Y";
   } else {
      $alterisnamed = "";
   }
   // update their status to show the department:
  $query = "UPDATE livehelp_users 
            SET department=".intval($UNTRUSTED['department']) . $alterisnamed . "
            WHERE sessionid='".$identity['SESSIONID']."'";	
  $mydatabase->query($query);
 
$query = "SELECT * 
          FROM livehelp_users 
          WHERE sessionid='".$identity['SESSIONID']."'";
$person_a = $mydatabase->query($query);  
$person = $person_a->fetchRow(DB_FETCHMODE_ASSOC);

if(!(empty($UNTRUSTED['tab']))){
    $query = "SELECT * 
              FROM livehelp_modules 
              WHERE id=".intval($UNTRUSTED['tab']);	
    $data = $mydatabase->query($query);
    $row = $data->fetchRow(DB_FETCHMODE_ASSOC);
    if(empty($UNTRUSTED['pageurl']))
     $UNTRUSTED['pageurl'] = $row['path'];
    $UNTRUSTED['pageurl'] .= "?department=".$UNTRUSTED['department']."&" . $row['query_string']; 			
}

// see if anyone is online . if not send them to the leave a message page..
$query = "SELECT * 
          FROM livehelp_users,livehelp_operator_departments 
          WHERE livehelp_users.user_id=livehelp_operator_departments.user_id 
            AND livehelp_users.isonline='Y' 
            AND livehelp_users.isoperator='Y' 
            AND livehelp_operator_departments.department=".intval($UNTRUSTED['department']);
$data = $mydatabase->query($query);  

if($data->numrows() == 0){

 // else go to default tab id.. 
if(empty($UNTRUSTED['tab'])){
   $doubleframe = "yes";  
   $query = "SELECT * 
             FROM livehelp_modules_dep,livehelp_modules 
             WHERE livehelp_modules_dep.modid=livehelp_modules.id 
               AND defaultset='Y' 
               AND departmentid=".intval($UNTRUSTED['department']);	
   $data = $mydatabase->query($query);
   if( $data->numrows() == 0){
     $doubleframe = "yes";
     $UNTRUSTED['pageurl'] = "offline.php?department=".intval($UNTRUSTED['department']);
     $UNTRUSTED['tab'] = 1;
   } else {
    $row = $data->fetchRow(DB_FETCHMODE_ASSOC);
    if(empty($UNTRUSTED['pageurl']))
      $UNTRUSTED['pageurl'] = $row['path'];
    $UNTRUSTED['pageurl'] .= "?department=".$UNTRUSTED['department']."&" . $row['query_string'];
    $UNTRUSTED['tab']= $row['id'];
   }
 } 
}
if(empty($UNTRUSTED['tab'])){ $UNTRUSTED['tab'] = 1;}
if($UNTRUSTED['tab']!= 1){
  $doubleframe = "yes";
}

if( $data->numrows() != 0){
 // get the userid, channel and isnamed fields.
  $query = "SELECT * 
            FROM livehelp_users 
            WHERE sessionid='".$identity['SESSIONID']."'";
  $people = $mydatabase->query($query);
  if($people->numrows() == 0){ 
       $query = "ALTER TABLE `livehelp_users` CHANGE `user_id` `user_id` INT( 10 ) NOT NULL AUTO_INCREMENT ";
       $mydatabase->query($query);	       
  }
  $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = $people['user_id'];
  $channel = $people['onchannel'];
  $isnamed = $people['isnamed'];
  $askquestions = $people['askquestions'];
  $status = $people['status'];  
 
  // if the user was invited. change status to invited.
  if($status == "request"){
    $query = "UPDATE livehelp_users 
              SET status='invited' 
              WHERE sessionid='".$identity['SESSIONID']."'";
    $mydatabase->query($query);
   }

   /// make sure the department is right.
   $query = "UPDATE livehelp_users 
             SET department=".intval($UNTRUSTED['department'])."
             WHERE sessionid='".$identity['SESSIONID']."'";	
  $mydatabase->query($query);

  // if the user requested an exit..
  if($UNTRUSTED['action'] == "leave"){
    Header("Location: wentaway.php?savepage=1&department=".$UNTRUSTED['department']);
    exit;
  }

  // skip ask for name if that is set.
  if($department_a['requirename'] != "Y"){
    $newusername = $identity['IP_ADDR'];
    $makenamed = "Y";
  }

  // isnamed is set to yes when the user enters in their name. 
  if($UNTRUSTED['makenamed'] == "Y"){
 
  // make sure the username is a username:
  $newusername = substr($UNTRUSTED['newusername'],0,15);
  $newusername = filter_html($newusername);  
  $newusername = str_replace("'","",$newusername);
  
  // make sure the username that we have is unique:  
  $currenthandle = $identity['HANDLE'];
  $countnum = 0;
  
  // if the username submitted is different then the current handle we have
  // and the handle is not an ip address..
  
  if( ($currenthandle != $newusername) && ($currenthandle !=$identity['IP_ADDR']) )
    $count = 1;
  else
    $count = 0;
  $username_s = $newusername; 
  if($newusername == ""){ $newusername = "no name"; }
  while($count != 0){
    $query = "SELECT * 
              FROM livehelp_users 
              WHERE username='".filter_sql($newusername)."'"; 
    $count_a = $mydatabase->query($query);
    $count = $count_a->numrows();  
    if($count != 0){ $newusername = $username_s . "_" . $countnum; }
    $countnum++;
  }      
  
  // get the answers to the questions and place in users session.
	$sessiondata = "";
	$useremail = "";
	$errors = "";
	$query = "SELECT * 
	          FROM livehelp_questions 
	          WHERE department=".intval($UNTRUSTED['department']);
	$res = $mydatabase->query($query);
	while($questions = $res->fetchRow(DB_FETCHMODE_ASSOC)){
	  $fieldname = "field_" . $questions['id']; 
	  $required = $row['required'];   
    $headertext = $row['headertext'];
    
     // if required:
   		if( ($required == "Y") && empty($UNTRUSTED[$fieldname]) ) {
   		   	$errors = "<li>" . $headertext . " " . $lang['isrequired'];   		   		
   		 }
   		   
		// if this is a checkbox field the values are in an array: 
		switch($questions['fieldtype']){
		  case "username":
		    // do nothing.. this is handled above.
		    break;
		  case "email":
		    if(!(empty($UNTRUSTED[$fieldname])))
  		    $useremail = filter_html($UNTRUSTED[$fieldname]);
		    break;
		  case "checkboxes":
		    $values = "";
		    $comma = "";
		    for($k=0;$k<99;$k++){
  		    $checkfieldname = $fieldname . "__" . $k;
  		    if(!(empty($UNTRUSTED[$checkfieldname]))){	     	
		    	 $values .= $comma . urlencode(filter_html($UNTRUSTED[$checkfieldname]));
		    	 $comma = ",";
		      }  
		    }
		     $sessiondata .= $fieldname . "=" . $values . "&";		    
		    break;
		  default:
		    if(!(empty($UNTRUSTED[$fieldname])))
   		    $sessiondata .= $fieldname . "=" . urlencode(filter_html($UNTRUSTED[$fieldname])) . "&";
		    break;
	  }  
	  
  }
 
  $useremail = str_replace("\'","",$useremail);
  $useremail = str_replace("'","",$useremail);
  $query = "UPDATE livehelp_users 
            SET email='".filter_sql($useremail)."',isnamed='Y',askquestions='N',username='".filter_sql($newusername)."',sessiondata='$sessiondata' 
            WHERE sessionid='".$identity['SESSIONID']."'";
  $mydatabase->query($query);	
   
  $query = "SELECT * 
            FROM livehelp_users 
            WHERE sessionid='".$identity['SESSIONID']."'";
  $people = $mydatabase->query($query);
  $people = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = $people['user_id'];
  $channel = $people['onchannel'];  
  $isnamed = "Y"; 
}


// create the channel
if(( ($channel == -1) || ($channel == "")) && ($isnamed == "Y") ){

$channel = createchannel($myid);
 
$query = "UPDATE livehelp_users 
          SET status='chat' 
          WHERE user_id=".intval($myid);
$mydatabase->query($query);
}

// change status to chat 
if( ($isnamed == "Y") && ($askquestions =="N") ){
  $query = "UPDATE livehelp_users
            SET status='chat' 
            WHERE user_id=".intval($myid);
  $mydatabase->query($query); 
}
if(!($serversession))
  $mydatabase->close_connect();
}

if($doubleframe == "yes"){
?>
<SCRIPT type="text/javascript">
function exitchat(){
 <?php if ($isnamed == "Y"){ ?>
  window.open('wentaway.php?action=leave&autoclose=Y&visiting=Y&department=<?php echo $UNTRUSTED['department']; ?>', 'ch54050872', 'width=40,height=90,menubar=no,scrollbars=0,resizable=1');
 <?php } ?>
}
</SCRIPT>
<TITLE><?php echo $CSLH_Config['site_title']; ?></TITLE>
<SCRIPT type="text/javascript">
if (window.parent != window.self){
   window.parent.location='livehelp.php?department=<?php echo $UNTRUSTED['department']; ?>&tab=<?php echo $UNTRUSTED['tab']; ?>';
}
</SCRIPT>
<frameset rows="<?php echo $topframeheight; ?>,*" border="0" frameborder="0" framespacing="0" spacing="0" NORESIZE=NORESIZE>
<frame src="user_top.php?department=<?php echo $UNTRUSTED['department']; ?>&tab=<?php echo $UNTRUSTED['tab']; ?><?php echo $querystringadd; ?>" name="1a" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
<?php
$pos = strpos($UNTRUSTED['pageurl'], "?");
if ($pos === false) {$UNTRUSTED['pageurl'] = $UNTRUSTED['pageurl'] . "?"; } 
$url = $UNTRUSTED['pageurl'] . $querystringadd;
$url = str_replace("&&","&",$url);
?>
<frame src="<?php echo $url; ?>" name="1b" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
</frameset><noframes></noframes>
<?php
} else {
?><SCRIPT type="text/javascript">
function exitchat(){
 <?php if ($isnamed == "Y"){ ?>
  window.open('livehelp.php?action=leave&autoclose=Y&department=<?php echo $UNTRUSTED['department']; ?>', 'ch54050872', 'width=540,height=190,menubar=no,scrollbars=0,resizable=1');
 <?php } ?>
}
</SCRIPT>
<TITLE><?php echo $CSLH_Config['site_title']; ?></TITLE>
<SCRIPT type="text/javascript">
if (window.parent != window.self){
   window.parent.location='livehelp.php?department=<?php echo $UNTRUSTED['department']; ?>&tab=<?php echo $UNTRUSTED['tab']; ?>';
}
</SCRIPT>
<frameset rows="<?php echo $topframeheight; ?>,*,75" border="0" frameborder="0" framespacing="0" spacing="0">
<frame src="user_top.php?department=<?php echo $UNTRUSTED['department']; ?>&channel=<?php echo $channel; ?>&t=<?php echo $lastaction; ?>&myid=<?php echo $myid; ?>&tab=<?php echo $UNTRUSTED['tab']; ?><?php echo $querystringadd; ?>" name="topnavigation" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
<?php
  // if the user is named then connect to the chat. otherwize ask questions:
  if(empty($askquestions)) 
    $askquestions = "Y";
    
  if ($askquestions == "Y"){
    $page = "user_questions.php";
  } else {
    $page = "user_connect.php";
  }  
?>
<frame src="<?php echo $page; ?>?department=<?php echo $UNTRUSTED['department']; ?>&channel=<?php echo $channel; ?>&t=<?php echo $lastaction; ?><?php echo $querystringadd; ?>" name="chatwindow" scrolling="AUTO" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
<frame src="user_bot.php?department=<?php echo $UNTRUSTED['department']; ?>&channel=<?php echo $channel; ?>&t=<?php echo $lastaction; ?>&myid=<?php echo $myid; ?><?php echo $querystringadd; ?>" name="bottomof" scrolling="no" border="0" marginheight="0" marginwidth="0" NORESIZE=NORESIZE>
</frameset>
<?php } ?>