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
$colorfile = "images".C_DIR. $CSLH_Config['colorscheme'] .C_DIR."color.php";
require_once($colorfile);

// The two sessions operator and visitor sessions:
if(empty($UNTRUSTED['cslhVISITOR'])) $UNTRUSTED['cslhVISITOR'] = "";
if(empty($UNTRUSTED['cslhOPERATOR'])) $UNTRUSTED['cslhOPERATOR'] = "";
if(!(empty($_COOKIE['cslhVISITOR']))) $UNTRUSTED['cslhVISITOR'] = $_COOKIE['cslhVISITOR'];
if(!(empty($_COOKIE['cslhOPERATOR']))) $UNTRUSTED['cslhOPERATOR'] = $_COOKIE['cslhOPERATOR'];
if(!(empty($_COOKIE['speaklanguage']))){ $CSLH_Config['speaklanguage']  = $_COOKIE['speaklanguage']; }
if(!(empty($UNTRUSTED['speak']))){ $CSLH_Config['speaklanguage'] = $UNTRUSTED['speak'];  setcookie("speaklanguage", $UNTRUSTED['speak'], time()+9600);  }
 
// settings for server session/host ip logins, cookies:
$allow_ip_host_sessions=false; // for the operator this should NEVER be set to true.
$cookiesession=true;           // in most cases this is needed to be True. unles trans-id
$serversession=true;

$identity = identity($UNTRUSTED['cslhOPERATOR'],"cslhOPERATOR",$allow_ip_host_sessions,$serversession,$cookiesession);

update_session($identity);

// get the info on the admin user:
$query = "SELECT user_id,onchannel,show_arrival,user_alert,externalchats,istyping,isadmin FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
if($people->numrows() != 0){
  $operator_row = $people->fetchRow(DB_FETCHMODE_ASSOC);
  $myid = $operator_row['user_id'];
  $channel = $operator_row['onchannel'];
  $show_arrival = $operator_row['show_arrival']; 
  $user_alert = $operator_row['user_alert'];
  $externalchats = $operator_row['externalchats'];
  $defaultshowvisitors = $operator_row['istyping'];
  $isadminsetting = $operator_row['isadmin'];
}
 
// Change Language if department Language is not the same as default language:
 $languagefile = "lang/lang-" . $CSLH_Config['speaklanguage'] . ".php";
 if(!(file_exists($languagefile))){
 	$languagefile = "lang/lang-.php";
 }	
 include($languagefile);



?>