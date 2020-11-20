<?php
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2006 Eric Gerdes   (http://www.craftysyntax.com )
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
// BIG NOTE:
//     At the time of the release of this version of CSLH, Version 3.1.0 
//     which is a more modular, extendable , “skinable” version of CSLH
//     was being developed.. please visit http://www.craftysyntax.com to see if it was released!  
//===========================================================================
require_once("security.php");
require_once("config.php");
require_once("config_cslh.php");
require_once("functions.php");
require_once("file_get_contents.php");

// Ghost session or not:
if(empty($ghost_session)) $ghost_session = false;

// The two sessions operator and visitor sessions:
if(empty($UNTRUSTED['cslhVISITOR'])) $UNTRUSTED['cslhVISITOR'] = "";
if(empty($UNTRUSTED['cslhOPERATOR'])) $UNTRUSTED['cslhOPERATOR'] = "";

// config settions that can be pased in the query string, Default values are set here:
if(empty($UNTRUSTED['allow_ip_host_sessions'])){ $allow_ip_host_sessions=1; } else { $allow_ip_host_sessions = intval($UNTRUSTED['allow_ip_host_sessions']); }
if(empty($UNTRUSTED['serversession'])){ $serversession=1; } else { $serversession = intval($UNTRUSTED['serversession']); }
if(empty($UNTRUSTED['cookiesession'])){ $cookiesession=1; } else { $cookiesession = intval($UNTRUSTED['cookiesession']); }
if(empty($UNTRUSTED['pingtimes'])){ $pingtimes=60; } else { $pingtimes = intval($UNTRUSTED['pingtimes']); }

  
// other variables:
 if(empty($UNTRUSTED['pageid'])){ $UNTRUSTED['pageid'] = 1; }
 if(empty($UNTRUSTED['page'])){ $UNTRUSTED['page'] = ""; }
 if(empty($UNTRUSTED['title'])){ $UNTRUSTED['title'] = ""; }  
 if(empty($UNTRUSTED['referer'])){ $UNTRUSTED['referer'] = ""; }
 if(empty($UNTRUSTED['page'])){ $UNTRUSTED['page'] = ""; }  
 $UNTRUSTED['referer'] = "http://" . str_replace("--dot--",".",$UNTRUSTED['referer']);
 $UNTRUSTED['page'] = "http://" . str_replace("--dot--",".",$UNTRUSTED['page']);
 
// setting that will not work:
if( ($cookiesession!=1) && ($allow_ip_host_sessions!=1) && ($serversession!=1) ){
   $allow_ip_host_sessions=0;
   $serversession=1;
   $cookiesession=1;
}

// department 
if(empty($UNTRUSTED['department'])){ $department=0; } else { $department = intval($UNTRUSTED['department']); }

// username:
if(empty($UNTRUSTED['username']) && !(empty($_COOKIE['username'])))
  $UNTRUSTED['username'] = $_COOKIE['username'];
	    
$identity = identity($UNTRUSTED['cslhVISITOR'],"cslhVISITOR",$allow_ip_host_sessions,$serversession,$cookiesession,$ghost_session);
update_session($identity,$ghost_session);

$querystringadd ="&cslheg=1";
if(!($allow_ip_host_sessions)){
   $querystringadd .= "&allow_ip_host_sessions=0";
}
if($serversession==1){
   $querystringadd .= "&serversession=1";
} else {
   $querystringadd .= "&serversession=0";
}
if(!(empty($relative))){
      $querystringadd .= "&relative=Y";
}
if (!(empty($username))) {
    $querystringadd .= "&username=".$username;
}

// get the info of this user.. 
$sqlquery = "SELECT user_id,onchannel,isnamed,status FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($sqlquery);
$people = $people->fetchRow(DB_FETCHMODE_ORDERED);  
$myid = $people[0];
$channel = $people[1];
$isnamed = $people[2];
$status = $people[3];  

/// make sure the department is right.
if( (intval($department)!=0) && ($status!="chat") ){
  $sqlquery = "UPDATE livehelp_users set department=".intval($department)." WHERE sessionid='".$identity['SESSIONID']."'";	
  $mydatabase->query($sqlquery);
}

// Get department information. First found if no specific department assigned
$qQry = "SELECT recno,messageemail,colorscheme,leavetxt,creditline,onlineimage,leaveamessage,offlineimage,speaklanguage,emailfun,dbfun FROM livehelp_departments "
      . (($department==0)? 'LIMIT 1': "WHERE recno=$department");
$qRes = $mydatabase->query($qQry);
$qRow = $qRes->fetchRow(DB_FETCHMODE_ORDERED);     
$messageemail = $qRow[1];          
$colorscheme  = $qRow[2];           
$leavetxt     = $qRow[3];           
$creditline   = $qRow[4];            
$onlineimage  = $qRow[5];
$leaveamessage = $qRow[6];
$offlineimage = $qRow[7];
$speaklanguage = $qRow[8];
$emailfun= $qRow[9];
$dbfun= $qRow[10];


// Change Language if department Language is not the same as default language:
if(($CSLH_Config['speaklanguage'] != $speaklanguage) && !(empty($speaklanguage)) ){
 $languagefile = "lang/lang-" . $speaklanguage . ".php";
 if(!(file_exists($languagefile))){
 	$languagefile = "lang/lang-.php";
 }	
 include($languagefile);
}

// include color file:
$colorfile = "images/$colorscheme/color.php";
if(!(file_exists($colorfile))){
  $color_background="FFFFEE";
  $color_alt1 = "D8E6F0";
  $color_alt2 = "CED9FA";    
  $color_alt3 = "E5ECF4";  
  $color_alt4 = "C4CAE4";
} else {
  include($colorfile);
}  

?>