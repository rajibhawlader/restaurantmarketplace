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
include_once("class/browser_info.php");

$errors = "";
$comments = "";
$heading = "";
if(!(isset($UNTRUSTED['action']))){ $UNTRUSTED['action'] = ""; }
if(!(isset($UNTRUSTED['department']))){ $department = 0; } else { $department = intval($UNTRUSTED['department']); }

// Header Separator Differences fixes:
// To get the correct header separators we see what platform PHP is running on
	switch( strtolower(substr(PHP_OS,0,3)) ) {
	  case 'win': $sys_eol = "\r\n"; break;
		case 'mac': $sys_eol = "\r";   break;
		   default: $sys_eol = "\n";
} 

$qQry = "SELECT user_id,camefrom FROM livehelp_users WHERE sessionid='".filter_sql($identity['SESSIONID'])."'";
$qRes = $mydatabase->query($qQry);
$qRow = $qRes->fetchRow(DB_FETCHMODE_ORDERED);  // -------------- GLOBAL VARS --------------- 
$uid      = $qRow[0];                // $uid      <-- livehelp_users.user_id
$camefrom = $qRow[1];                // $camefrom <-- livehelp_users.camefrom 
 
$qQry = "UPDATE livehelp_users set status='message' WHERE user_id=".intval($uid);		
$mydatabase->query($sqlquery);          // livehelp_users.status <-- 'message'

#######################################################################################
##  HTML - Common Header ---------
####################################################################################### ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Leave a Message</title>
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>">
  <meta name="robots" content="noindex,nofollow">
  <link rel="stylesheet" href="style.css" type="text/css">
  <!-- These styles will complement/override the styles in style.css -->
  <style type="text/css">
    body {padding: 0; margin: 0; background-image: url(images/<?php echo $colorscheme; ?>/mid_bk.gif);} 
    .errTxt {font: 12px Arial,Helvetica,sans-serif; color: #990000;}
    /* Form Controls */
    .tdHeader, .tdFldHdr, .tdGtoupHdr, .tdGroupItemDesc {font: 12px Arial, Helvetica, sans-serif;}
    .tdFldHdr {font-weight: bold;}
    .tdHeader {padding-left: 28px; padding-right: 28px; padding-bottom: 18px; padding-top: 10px;}
    .tblGroup {margin-top: 5px;}
    .tdGroupHdr {font-weight: bold; padding-left: 4px; padding-bottom: 4px; padding-top: 8px;}
    .tdGroupItem {padding-left: 18px;}
    .tdGoupItemDesc {width: 100%; padding-left: 2px;}
    /* Message Sent display */
    #divMsgSent {margin: 12px; text-align: center;}
    #divMsgSent h2 p {font-family: Arial,Helvetica,sans-serif;}
    #divMsgSent h2 {font-size: 24px; font-weight: bold; color: #009900;}
    #divMsgSent p {font-size: 12px;}
    #divMsgSent hr {width: 80%;} 
    #divForm {text-align: center;}
  </style>
</head>
<body>
<?php
//-------------------------------------------------------------------------------------
//  PHP
//-------------------------------------------------------------------------------------
//  -- SEND ----------------------------------
if($UNTRUSTED['action']=="send" ) {
  $errors = "";
  $useremail = "";
  
   // select out all of the questions from the database and get their answers..
   // also check required fields. 
   $dilimated = "this=that"; 
   $sqlquery = "SELECT id,fieldtype,headertext,required FROM livehelp_questions WHERE department=$department AND module='leavemessage' ORDER by ordering";
   $result = $mydatabase->query($sqlquery);
   while($row = $result->fetchRow(DB_FETCHMODE_ORDERED)){
     $id = $row[0];
     $fieldtype = $row[1];
     $headertext = $row[2];
     $required = $row[3];      
         
     $fieldname = "field_" . $id; 
		 switch($fieldtype){		  
		  case "email":
		    if(!(empty($UNTRUSTED[$fieldname]))){
  		    $useremail = $UNTRUSTED[$fieldname];

  		    if ( ($required == "Y") && (!(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$useremail))))
            $errors .= "<li>".$lang['txt185'];            		    
	  		  
	  		  if ( ($required != "Y") && (!(empty($UNTRUSTED[$fieldname]))) && (!(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$",$useremail))))
            $errors .= "<li>".$lang['txt185']; 
                      
          $dilimated .= "&" . $fieldname . "=" . urlencode(filter_html($UNTRUSTED[$fieldname]));  

		      $fieldid = str_replace("field_","",$fieldname);
  	  	  $sqlquery = "SELECT headertext FROM livehelp_questions WHERE id=". intval($fieldid); 
  	  	  $questiondata = $mydatabase->query($sqlquery);
          $question_row = $questiondata->fetchRow(DB_FETCHMODE_ORDERED); 
   		    $comments .= $question_row[0] . ":$sys_eol" . $UNTRUSTED[$fieldname] . "$sys_eol"; 
 
  		  } else {
  		    if ($required == "Y") 
  		      $errors .= "<li>" . $headertext . " " . $lang['isrequired'] ;
  		    if($emailfun!="N")
  		       $useremail = "nobody@nowhere.com";
  		  }  
		    break;
		  case "checkboxes":   // field_1__0
		    $values = "";
		    $comma = "";
		    for($k=0;$k<99;$k++){
  		    $checkfieldname = $fieldname . "__" . $k;
  		    if(!(empty($UNTRUSTED[$checkfieldname]))){	      	
		    	 $values .= $comma . $UNTRUSTED[$checkfieldname];
		    	 $comma = ",";
		      }
		     }		    
		     $pair = split("__",$fieldname);
		     $fieldid = str_replace("field_","",$pair[0]);
  	  	 $sqlquery = "SELECT headertext FROM livehelp_questions WHERE id=". intval($fieldid) . " LIMIT 1"; 
  	  	 $questiondata = $mydatabase->query($sqlquery);
         $question_row = $questiondata->fetchRow(DB_FETCHMODE_ORDERED); 
		     $comments .= $question_row[0] . ":$sys_eol" . $values . "$sys_eol";
		     $dilimated .= "&" . $fieldname . "=" . urlencode(filter_html($values)); 
		    break;
		  case "subject_dropmenu": 
		  case "subject_textfield": 
		    if(!(empty($UNTRUSTED['subjectline']))){
		     $fieldid = str_replace("field_","",$fieldname);
  	  	 $sqlquery = "SELECT headertext FROM livehelp_questions WHERE id=". intval($fieldid); 
  	  	 $questiondata = $mydatabase->query($sqlquery);
         $question_row = $questiondata->fetchRow(DB_FETCHMODE_ORDERED); 
   		   $comments .= $question_row[0] . ":$sys_eol" . $UNTRUSTED['subjectline'] . "$sys_eol";   		   
   		   $dilimated .= "&" . $fieldname . "=" . urlencode(filter_html($UNTRUSTED['subjectline']));
   		  } else {
   		   // if required:
   		   if($required == "Y") {
   		   	$errors .= "<li>" . $headertext . " " . $lang['isrequired'];   		   		
   		   }
   		  }    		  
		    break;
		  default:
		    if(!(empty($UNTRUSTED[$fieldname]))){
		     $fieldid = str_replace("field_","",$fieldname);
  	  	 $sqlquery = "SELECT headertext FROM livehelp_questions WHERE id=". intval($fieldid); 
  	  	 $questiondata = $mydatabase->query($sqlquery);
         $question_row = $questiondata->fetchRow(DB_FETCHMODE_ORDERED); 
   		   $comments .= $question_row[0] . ":$sys_eol" . $UNTRUSTED[$fieldname] . "$sys_eol";   		   
   		   $dilimated .= "&" . $fieldname . "=" . urlencode(filter_html($UNTRUSTED[$fieldname]));
   		  } else {
   		   // if required:
   		   if($required == "Y") {
   		   	$errors .= "<li>" . $headertext . " " . $lang['isrequired'];   		   		
   		   }
   		  }    		  
		    break;
	   }       
   } // END_WHILE
	//
  if( $useremail=="" ) $errors.="<li>" . $lang['txt185'];
  if( $errors=="" ) {
  	
  	$departmentname = whatdep($department);
    $client_agent = ( !empty($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : ( ( !empty($_ENV['HTTP_USER_AGENT']) ) ? $_ENV['HTTP_USER_AGENT'] : $HTTP_USER_AGENT );
    $b = new BrowserInfo($client_agent);
  	   
    $heading .= "=======================================================" . $sys_eol; 
    $heading .= "DEPARTMENT: ".$departmentname. $sys_eol;   
    $heading .= "  HOSTNAME: ${identity['HOSTNAME']}" . $sys_eol;
    $heading .= "        IP: ${identity['IP_ADDR']}" . $sys_eol;
    $heading .= "USER AGENT: " . $b->OS . " " . $b->OS_Version . " " . $b->Browser . " " . $b->Browser_Version . " " . $sys_eol;
    $heading .= "   DETAILS: " . $b->USER_AGENT . $sys_eol; 
    $heading .= "   REFERER: $camefrom" . $sys_eol;
    $heading .= "=======================================================" . $sys_eol;      	   
    $comments = $heading . $comments;		
 
   // get subject:
   if(empty($UNTRUSTED['subjectline']))
      $subjectline = "$departmentname - Contact";
   else
      $subjectline = $UNTRUSTED['subjectline'];
         
  if($emailfun!="N"){
   if($sys_eol=="\r\n"){
     if (!(send_message("Contact", $useremail, $departmentname, $messageemail, $subjectline, $comments, "text/plain", $lang['charset'], true))) {
         send_message("Contact", $useremail, $departmentname, $messageemail, $subjectline, $comments, "text/plain", $lang['charset'], false);
     }				
   } else {
     if (!(send_message("Contact", $useremail, $departmentname, $messageemail, $subjectline, $comments, "text/plain", $lang['charset'], false))) {
         send_message("Contact", $useremail, $departmentname, $messageemail, $subjectline, $comments, "text/plain", $lang['charset'], true);
     }	   	
   }
  }
  
   // record message in messages database:      
  if($dbfun!="N"){
   $dateof = date("YmdHis");

   if(empty($CSLH_Config['offset'])){ $CSLH_Config['offset'] = 0; }
   //YYYYMMDDHHIISS
   //  01234567890123
   $when = mktime ( substr($dateof,8,2)+$CSLH_Config['offset'], substr($dateof,10,2), substr($dateof,12,2), substr($dateof,4,2) , substr($dateof,6,2), substr($dateof,0,4) );
   $dateof = date("YmdHis",$when);
 

   $sessiondata = getsessiondata($uid,true);
   
   $q = "INSERT INTO livehelp_leavemessage (email,subject,department,dateof,sessiondata,deliminated) VALUES ('".filter_sql($useremail)."','".filter_sql($subjectline)."',$department,$dateof,'".filter_sql($sessiondata)."','".filter_sql($dilimated)."') ";
   $mydatabase->query($q);
  }
   
		#####################################################################################
		##  No errors, Mail Sent, display message indicating so
	  ##################################################################################### ?>
		<div id='divMsgSent'>
		  <h2><?php echo $lang['txt73']; ?></h2>
			<hr>
			<p><?php echo $lang['txt71']; ?></p>
		</div>
<?php
  } else {
		#################################################################
		##  Errors occured while processing the 'send' POST
		##  Skip submitting it, $errors will be shown
		#################################################################
    $UNTRUSTED['action'] = "";	
  }
}
#####################################################################
## If $action NOT 'send', or errors occured while trying to send it
if( $UNTRUSTED['action']=="" ) {
##  SHOW FORM
################################################################### ?>
  <div id="divForm">
    <form name="form" method="POST" action="leavemessage.php">
      <input type="hidden" name="action" value="send">
      <input type="hidden" name="department" value="<?php echo $department; ?>">
      <table width=480 border=0 cellspacing=0 cellpadding=0>
        <tr><td colspan=2 class="tdHeader"><?php
	echo $leavetxt;
	if( $errors!='' ) echo "<span class=\"errTxt\">$errors</span>"; 
                ?></td></tr>
<?php
	##########################################################################
  ## check to make sure the email field exists.. if not and e-mail functions are on make it.
	##########################################################################
    
  $sqlquery = "SELECT id FROM livehelp_questions WHERE module='leavemessage' AND department=$department AND fieldtype='email' LIMIT 1";
  $result = $mydatabase->query($sqlquery);
  if( ($emailfun =="Y") && ($result->numrows()==0)){
    $insertit = "INSERT INTO livehelp_questions (";
    $insertit .=   "department".","."ordering".","."headertext".","."fieldtype".",".   "module"   .",required) VALUES (";
    $insertit .=   $department .",".   "0"    .",".  "'E-mail:'" .",".  "'email'"  .","."'leavemessage'".",'Y')";
		$mydatabase->query($insertit);
  }

	##########################################################################
	##  Read them all in
	##  If only one(must be eMail), add a text area and re-query
	##########################################################################
  $sqlquery = "SELECT id,headertext,options,required,fieldtype FROM livehelp_questions WHERE module='leavemessage' AND department=$department ORDER by ordering,id";
  $result = $mydatabase->query($sqlquery);
  if($emailfun=="Y") 
    $min =2; 
  else
    $min = 1;

  if( $result->numrows()<$min ) {
    $insertit = "INSERT INTO livehelp_questions (";
    $insertit .=   "department".","."ordering".",". "headertext" .","."fieldtype"  .",".   "module"     .") VALUES (";
    $insertit .=   $department .",".   "0"    .",". "'Question:'".","."'textarea'" .","."'leavemessage'".")";
    $mydatabase->query($insertit);
		// Try initial select again
  	$result = $mydatabase->query($sqlquery);
	}
	//
	############################################################################
	##  Show Results
	############################################################################
  while($row = $result->fetchRow(DB_FETCHMODE_ORDERED)){
  	 $id = $row[0]; 
  	 $headertext = $row[1]; 
  	 $options = $row[2]; 
  	 $required = $row[3]; 
  	 $fieldtype= $row[4];
  	 
  	 $fieldname = "field_" . $id; 
  	 $options_array = explode(",",$options);
  	 if($required == "Y") { $star = "<font color=990000><b>*</b></font>"; } else { $star = ""; }
  	 
  	 // text fields:   	
     if( ($fieldtype == "username") || ($fieldtype == "subject_textfield") || ($fieldtype == "textfield") || ($fieldtype == "email") ){
      	if ($fieldtype == "username") 
      	   $fieldname = "newusername"; 
      	if ($fieldtype == "subject_textfield") 
      	   $fieldname = "subjectline";       	
      	?>
        <tr><td class="tdFldHdr"><?php print $headertext . $star; ?></td>
            <td><input type=text size=30 name="<?php echo $fieldname; ?>" value="<?php echo empty($UNTRUSTED[$fieldname])? '': filter_html($UNTRUSTED[$fieldname]); ?>"></td></tr>
<?php }
      // text areas:
      if($fieldtype == "textarea"){ ?>
        <tr><td colspan=2><table class="tblGroup" width="100%" border=0 cellspacing=0 cellpadding=0>
              <tr><td colspan=2 class="tdGroupHdr"><?php print $headertext . $star; ?></td></tr>
              <tr><td><textarea cols=30 rows=5 name="<?php echo $fieldname; ?>" style="width: 90%;"><?php echo (empty($UNTRUSTED[$fieldname])? '': filter_html($UNTRUSTED[$fieldname])); ?></textarea></td></tr>
            </table></td></tr>
<?php }
      // Drop Menu
      if( ($fieldtype == "dropmenu") || ($fieldtype == "subject_dropmenu") ){ 
      	
      	 if ($fieldtype == "subject_dropmenu") 
      	   $fieldname = "subjectline";         	   
      	   
      	?>
        <tr><td class="tdFldHdr"><?php print $headertext . $star; ?></td>
            <td><select name="<?php echo $fieldname; ?>">
<?php if( !(empty($UNTRUSTED[$fieldname])) ) { ?>
                  <option value="<?php echo $UNTRUSTED[$fieldname] ?>" selected><?php echo filter_html($UNTRUSTED[$fieldnsme]) ?></option>
<?php } else { ?>
                  <option value=""> </option>
<?php }
      for( $i=0; $i<count($options_array); $i++ ) { ?>
                  <option><?php echo $options_array[$i]; ?></option>
<?php } ?>
                </select></td></tr>            	           	  
<?php }      
      // Radio Buttons
      if($fieldtype == "radio") { ?>
        <tr><td colspan=2><table class="tblGroup" border=0 cellspacing=0 cellpadding=0>
              <tr><td colspan=2 class="tdGroupHdr"><?php print $headertext . $star; ?></td></tr>
<?php   for($i=0; $i<count($options_array); $i++ ) { ?>
              <tr><td class="tdGroupItem"><input type="radio" name="<?php echo $fieldname; ?>" value="<?php echo $options_array[$i]; ?>"<?php if(!empty($UNTRUSTED[$fieldname."__$i"])) echo ' checked'; ?>></td>
                  <td class="tdGoupItemDesc"><?php echo $options_array[$i]; ?></td></tr>
<?php   } ?>
            </table></td></tr>			
<?php }
      // Checkboxes.
      if($fieldtype == "checkboxes") { ?>
        <tr><td colspan=2><table class="tblGroup" border=0 cellspacing=0 cellpadding=0>
              <tr><td colspan=2 class="tdGroupHdr"><?php print $headertext . $star; ?></td></tr>
<?php   for($i=0; $i<count($options_array); $i++ ) { ?>
              <tr><td class="tdGroupItem"><input type="checkbox" name="<?php echo $fieldname.'__'.$i; ?>" value="<?php echo $options_array[$i]; ?>"<?php if(!empty($UNTRUSTED[$fieldname."__$i"])) echo ' checked'; ?>></td>
                  <td class="tdGoupItemDesc"><?php echo $options_array[$i]; ?></td></tr>
<?php   } ?>
            </table></td></tr>			
<?php }
   } ?>
        <tr><td colspan=2 align="center" class="smRed" style="padding-top: 10px; padding-bottom: 18px;">
              <input type="submit" value="<?php echo $lang['SEND']; ?>" name="submit_button" class="butMargin">
            </td></tr>
      </table>
    </form>
  </div>
</body>
</html>
<?php } 
if(!($serversession))
  $mydatabase->close_connect();
?>