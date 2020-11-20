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


if(!(isset($_REQUEST['removesetup']))){ $_REQUEST['removesetup'] = 0; }
 
// option to remove setup.php program after upgrade / installation:
if($_REQUEST['removesetup']==1){
  if(unlink("setup.php")){
     print "setup removed .. <a href=index.php>CLICK HERE</a>";	
     exit;
  } else {
     print "setup remove failed .. The server does not run as user so delete it manually and then re-visit index.php file";
     print "<br><br>";
     print "<br>After removing file <a href=index.php>click here</a>";  	
     exit;
  }  
}

// this file is only called as an include.. everything else is a hack:
if (!(defined('IS_SECURE'))){
	print "Hacking attempt . Exiting..";
	exit;
}

// path separator:
  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
   define('C_DIR', "\\");
  else 
   define('C_DIR', "/");
   
// backwards compatability:
if($dbtype == "mysql_options.php"){ $dbtype = "mysql"; }
if($dbtype == "txt-db-api.php"){ $dbtype = "txt-db-api"; }

$CSLH_Config = array();
$CSLH_Config['version'] = "";
$CSLH_Config['site_title'] = "";
$CSLH_Config['use_flush'] = "";
$CSLH_Config['membernum'] = "";
$CSLH_Config['offset'] = "";
$CSLH_Config['show_typing'] = "";
$CSLH_Config['webpath'] = "";
$CSLH_Config['s_webpath'] = "";
$CSLH_Config['speaklanguage'] = "";
$CSLH_Config['s_webpath'] = "";

$CSLH_Config['smtp_host'] = "";
$CSLH_Config['smtp_username'] = "";
$CSLH_Config['smtp_password']="";
$CSLH_Config['owner_email']="";


if($installed == "true"){
// CONNECT to database:
if ($dbtype == "mysql"){
 require "class/mysql_db.php";
 $mydatabase = new MySQL_DB;
 $dsn = "mysql://$datausername:$password@$server/$database";
 $mydatabase->connect($dsn);	
 if(!($mydatabase->CONN)){ 
   // oh hell the mysql database is down.
   exit;	
 }
} 
if ($dbtype == "txt-db-api"){	
 require "txt-db-api".C_DIR."txt-db-api.php";
 $mydatabase = new Database("livehelp");
 $dsn = "txt-db-api://$datausername:$password@$server/$database";
 $mydatabase->connect($dsn); 	
}

if(!(empty($_REQUEST['repair']))){
	$query = "REPAIR TABLE livehelp_users";
  $mydatabase->query($query);
	$query = "REPAIR TABLE livehelp_sessions";
  $mydatabase->query($query);
}

// LOAD CONFIG:
 $query = "SELECT * FROM livehelp_config";
 $result = $mydatabase->query($query);


 // if this is not the setup and it exists re-direct:
    if ( (file_exists("setup.php")) && (!eregi("setup.php", $_SERVER['PHP_SELF'])) ){
      Header("Location: setup.php");
      exit;
    } 
    $row = $result->fetchRow(DB_FETCHMODE_ASSOC);
    $CSLH_Config = $row;
 
}   

//backwards compat.
if($CSLH_Config['speaklanguage'] == "eng"){ $CSLH_Config['speaklanguage'] = "English"; }  
if($CSLH_Config['speaklanguage'] == "frn"){ $CSLH_Config['speaklanguage'] = "French"; } 
if($CSLH_Config['speaklanguage'] == "ger"){ $CSLH_Config['speaklanguage'] = "German"; } 
if($CSLH_Config['speaklanguage'] == "ita"){ $CSLH_Config['speaklanguage'] = "Italian"; } 
if($CSLH_Config['speaklanguage'] == "por"){ $CSLH_Config['speaklanguage'] = "Portuguese"; } 
if($CSLH_Config['speaklanguage'] == "spn"){ $CSLH_Config['speaklanguage'] = "Spanish"; } 

// Sessions:
$autostart = @ini_get('session.auto_start');
if($autostart==0){
 require_once("class/sessionmanager.php");
 $sess = new SessionManager();
}
if(empty($CSLH_Config['smtp_host'])){ $CSLH_Config['smtp_host'] = ""; }

if($CSLH_Config['smtp_host']!=""){
 require_once("class/smtp.php");
}
 
$languagefile = "lang".C_DIR."lang-" . $CSLH_Config['speaklanguage'] . ".php";
if(!(file_exists($languagefile))){
	$languagefile = "lang".C_DIR."lang-.php";
}	
include($languagefile);

?>