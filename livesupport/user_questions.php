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
  
if(!(isset($UNTRUSTED['makenamed']))){ $UNTRUSTED['makenamed'] = ""; }

// get department information...
$where="";
if(empty($UNTRUSTED['department'])){ $UNTRUSTED['department'] = 0; } else { $UNTRUSTED['department'] = intval($UNTRUSTED['department']); }
if($UNTRUSTED['department']!=0){ $where = " WHERE recno=". intval($UNTRUSTED['department']); }
$sqlquery = "SELECT * FROM livehelp_departments $where ";
$data_d = $mydatabase->query($sqlquery);  
$department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC);
$department = $department_a['recno'];
$topbackground = $department_a['topbackground']; 
$colorscheme = $department_a['colorscheme']; 

// get user information:
$sqlquery = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($sqlquery);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isnamed = $people['isnamed'];
$askquestions = $people['askquestions'];
$username = $people['username'];

// javascript registry number. 
get_jsrn($identity);

  $mytimeof = date("YmdHis");
  $sqlquery = "UPDATE livehelp_users set lastaction='$mytimeof' WHERE user_id=".intval($myid);
  $mydatabase->query($sqlquery);

// see if anyone is online . if not send them to the leave a message page..
$sqlquery = "SELECT * 
          FROM livehelp_users,livehelp_operator_departments 
          WHERE livehelp_users.user_id=livehelp_operator_departments.user_id
            AND livehelp_users.isonline='Y'
            AND livehelp_users.isoperator='Y' 
            AND livehelp_operator_departments.department=". intval($UNTRUSTED['department']);
$data = $mydatabase->query($sqlquery);  
if($data->numrows() == 0){
 ?>
    <SCRIPT type="text/javascript">
    window.parent.location.replace("livehelp.php?tab=1&doubleframe=yes&pageurl=offline.php&department=<?php echo intval($UNTRUSTED['department']); ?>");       
    </SCRIPT>
 <?php
if(!($serversession))
   $mydatabase->close_connect();
  exit;
} 

// if we are not asking questions:
if($askquestions=="N"){
 ?>
    <SCRIPT type="text/javascript">
    window.location.replace("user_connect.php?try=0&tab=1&doubleframe=yes&pageurl=offline.php&department=<?php echo $UNTRUSTED['department']; ?>");       
    </SCRIPT>
 <?php
if(!($serversession))
  $mydatabase->close_connect();
  exit;
} 	



  // isnamed is set to yes when the user enters in their name. 
  if($UNTRUSTED['makenamed'] == "Y"){

  // get the answers to the questions and place in users session.
	$sessiondata = "";
	$useremail = "";
	$errors = "";
	$newusername="";
	$query = "SELECT * 
	          FROM livehelp_questions 
	          WHERE department=".intval($UNTRUSTED['department'])." AND module='livehelp'";
	$res = $mydatabase->query($query);
	while($questions = $res->fetchRow(DB_FETCHMODE_ASSOC)){
	  $fieldname = "field_" . $questions['id']; 
	  $required = $questions['required'];   
    $headertext = $questions['headertext'];
       		   
		// if this is a checkbox field the values are in an array: 
		switch($questions['fieldtype']){
		  case "username":
		      if(!(empty($UNTRUSTED[$fieldname])))
		         $newusername= $UNTRUSTED[$fieldname];
	           
	           // make sure the username is a username:
             $newusername = substr($newusername,0,16);
             $newusername = filter_html($newusername);  
             $newusername = str_replace("'","",$newusername);
  
		       // if required:
   		     if( ($required == "Y") && (empty($UNTRUSTED[$fieldname])) ) {
   		   	      $errors .= "<li>" . $headertext . " " . $lang['isrequired'];   		   		
   		     }   
		    break;
		  case "email":
		    if(!(empty($UNTRUSTED[$fieldname])))
  		    $useremail = filter_html($UNTRUSTED[$fieldname]);
  		  // if required:
   		  if( ($required == "Y") && (empty($UNTRUSTED[$fieldname])) ) {
   		   	$errors .= "<li>" . $headertext . " " . $lang['isrequired'];   		   		
   		   }
   		   if ( ($required == "Y") && (!(good_emailaddress($useremail))))
            $errors .= "<li>".$lang['txt185'];   
	  		 if ( ($required != "Y") && (!(empty($UNTRUSTED[$fieldname]))) && (!(good_emailaddress($useremail))))
            $errors .= "<li>".$lang['txt185']; 
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
		    // if required:
   		  if( ($required == "Y") && (empty($values)) ) {
   		   	$errors .= "<li>" . $headertext . " " . $lang['isrequired'];   		   		
   		   }
		     $sessiondata .= $fieldname . "=" . $values . "&";		    
		    break;
		  default:
		    if(!(empty($UNTRUSTED[$fieldname])))
   		    $sessiondata .= $fieldname . "=" . urlencode(filter_html($UNTRUSTED[$fieldname])) . "&";
   		  if( ($required == "Y") && (empty($UNTRUSTED[$fieldname])) ) {
   		   	$errors .= "<li>" . $headertext . " " . $lang['isrequired'];   		   		
   		   } 
		    break;
	  }  
	  
  }
  // if there are no errors re-direct to chat:
  if($errors == ""){
  
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
  $isnamed = $people['isnamed'];  
  $isnamed = "Y";
 ?>
    <SCRIPT type="text/javascript">
    window.location.replace("user_connect.php?try=0&tab=1&doubleframe=yes&pageurl=offline.php&department=<?php echo $UNTRUSTED['department']; ?>");       
    </SCRIPT>
 
 <?php
 if(!($serversession))
  $mydatabase->close_connect();
  exit;  
 }
 
}
?>
<html>
<HEAD>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<SCRIPT type="text/javascript">
skipfocus = 1;     
NS4 = (document.layers) ? 1 : 0;
IE4 = (document.all) ? 1 : 0;
W3C = (document.getElementById) ? 1 : 0;	
starttyping_layer_exists = false;
var whatissaid  = new Array(100);
for(i=0;i<100;i++){
  whatissaid[i] = 'nullstring';
}

function update_typing(){
   // do nothing..	
}

function up(){
  scroll(1,10000000);
}
function openwindow(mypage,myname) {
window.open(mypage,myname,'toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes');
}
</SCRIPT>
<?php
 // check for forced messages.. 
  print "<center><table width=450><tr><td>";
  $zero=0;
  $textmessages = showmessages($myid,"",$zero,$channel);
  if(empty($textmessages))
    print $department_a['opening'];
  print $textmessages;
  
 if(!(empty($errors)))
 	 print "<ul><font color=990000>$errors</font></ul>";
 	  
 // change status to invited..
  $sqlquery = "UPDATE livehelp_users SET status='invited' WHERE user_id='$myid'";
  $mydatabase->query($sqlquery);
 ?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
</HEAD>

<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" background="images/<?php echo $colorscheme; ?>/mid_bk.gif">
<FORM action="user_questions.php" METHOD="post">
 <input type=hidden name="department" value="<?php echo $department; ?>">
 <input type=hidden name="makenamed" value="Y">
<?php
  // check to make sure the name field exists.. if not make it.  
  $department = $department_a['recno'];
  $sqlquery = "SELECT * 
            FROM livehelp_questions 
            WHERE  module='livehelp' 
              AND department=".intval($department)." 
              AND fieldtype='username' ";
  $result = $mydatabase->query($sqlquery);
  if($result->numrows()==0){
    $insertit = "INSERT INTO livehelp_questions (department,ordering,headertext,fieldtype,module) VALUES (".intval($department).",'0','Name','username','livehelp')";
    $mydatabase->query($insertit);
  }
  
  // Get questions to ask user at time of chat request:
  $sqlquery = "SELECT * FROM livehelp_questions WHERE module='livehelp' AND department=".intval($department)." ORDER by ordering";
  $result = $mydatabase->query($sqlquery);
  print "<table>"; 

 $currentusername = explode("_",$identity['HANDLE']);
 if(!( ($identity['IP_ADDR'] == $currentusername[0]) && (!(empty($username)))))
   $usernamevalue = $currentusername[0];
 else
   $usernamevalue = "";
      	   
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
  	// based on the length of the text we will indent or not..
  	$length = strlen($row['headertext']);
  	if($length<30)
  	  $indent = false;
  	else 
  	  $indent = true;
  	 
  	 $fieldname = "field_" . $row['id']; 
  	 $options = explode(",",$row['options']);
  	 if($row['required'] == "Y") { $star = "<font color=990000><b>*</b></font>"; } else { $star = ""; }
  	   	 
  	 // text fields:   	
     if( ($row['fieldtype'] == "username") || ($row['fieldtype'] == "textfield") || ($row['fieldtype'] == "email") ){	     	   
     	 
     	 // if this is the username field and we already have a username for this user:
     	 if ( ($row['fieldtype'] == "username") && ($isnamed=="Y"))
     	   $UNTRUSTED[$fieldname] = $identity['HANDLE'];
     	
        if($indent){
         	  print "<tr><td colspan=2><b>".$row['headertext']."$star </b><br><table><tr><Td><img src=images/blank.gif width=20 height=1></td><td><input type=text size=30 name=$fieldname ";
         	  if(!(empty($UNTRUSTED[$fieldname])))
         	     print " value=\"".$UNTRUSTED[$fieldname]."\"";
         	  print "></td></tr></table></td></tr>";      	  
          } else {
         	  print "<tr><td><b>".$row['headertext']."$star </b></td><td><input type=text size=30 name=$fieldname";
         	  if(!(empty($UNTRUSTED[$fieldname])))
         	     print " value=\"".filter_html($UNTRUSTED[$fieldname])."\"";         	  
         	  print "></td></tr>";
         	}  
      } 	  
      
      
      // text areas:
      if($row['fieldtype'] == "textarea"){
         print "<tr><td colspan=2><b>".$row['headertext']."$star </b><br><table><tr><Td><img src=images/blank.gif width=10 height=1></td><td><textarea cols=40 rows=3 name=$fieldname>";
         if(!(empty($UNTRUSTED[$fieldname]))) 
            print filter_html($UNTRUSTED[$fieldname]);
         print "</textarea></td></tr></table></td></tr>";      	        	
      }         
      
      // Drop Menu
      if($row['fieldtype'] == "dropmenu"){
          if($indent){
         	  print "<tr><td colspan=2><b>".$row['headertext']."$star </b><br><table><tr><Td><img src=images/blank.gif width=20 height=1></td><td><select name=$fieldname>";
            if( !(empty($UNTRUSTED[$fieldname])) ) { ?>
               <option value="<?php echo filter_html($UNTRUSTED[$fieldname]) ?>" selected><?php echo $UNTRUSTED[$fieldnsme] ?></option>
            <?php }   
         	  print "<option value=\"\"> </option>\n";
         	  for($i=0;$i<count($options);$i++){
         	   print "<option>$options[$i]</option>\n";
         	  }
         	  print "</select></td></tr></table></td></tr>";      	  
          } else {
         	  print "<tr><td><b>".$row['headertext']."$star </b></td><td>";
         	  print "<select name=$fieldname>";
            if( !(empty($UNTRUSTED[$fieldname])) ) { ?>
               <option value="<?php echo $UNTRUSTED[$fieldname]; ?>" selected><?php echo filter_html($UNTRUSTED[$fieldname]); ?></option>
            <?php } 
         	  print "<option value=\"\"> </option>\n";
 	          for($i=0;$i<count($options);$i++){
         	   print "<option>$options[$i]</option>\n";
         	  }
         	  print "</select></td></tr>\n";            	           	  
         	}  
      }      
           
      // Radio Buttons
      if($row['fieldtype'] == "radio"){
         	  print "<tr><td colspan=2><b>".$row['headertext']."$star </b><br><table><tr><Td><img src=images/blank.gif width=20 height=1></td><td>";
 	          for($i=0;$i<count($options);$i++){
         	   print "<input type=radio name=$fieldname value=\"" . $options[$i] . "\" ";
         	   if(!(empty($UNTRUSTED[$fieldname])))
         	      if($UNTRUSTED[$fieldname] == $options[$i]) echo ' checked';
         	   print " >".$options[$i]." <br>\n";
         	  }
         	  print "</td></tr></table></td></tr>\n";            	           	           	      	
      }           
      
      // Checkboxes.
      if($row['fieldtype'] == "checkboxes"){
         	  print "<tr><td colspan=2><b>".$row['headertext']."$star </b><br><table><tr><Td><img src=images/blank.gif width=20 height=1></td><td>";
 	          for($i=0;$i<count($options);$i++){ 	           
         	   print "<input type=Checkbox name=$fieldname"."__$i"." value=\"" . $options[$i] . "\" ";
         	   $checkname= $fieldname."__$i";
         	   if(!empty($UNTRUSTED[$checkname])) echo ' checked';
         	   print ">".$options[$i]."<br>\n";
         	  }
         	  print "</td></tr></table></td></tr>\n";            	           	           	      	
      }         
   }
   print "</table>";
  
?>

 <br><input type=submit value=<?php echo $lang['SEND']; ?> >
 </FORM>
</td></tr></table></center>
 <br><br>
</body>
</html>
<?php
if(!($serversession))
 $mydatabase->close_connect();
 exit;
?>