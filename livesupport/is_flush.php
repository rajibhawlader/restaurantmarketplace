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
require_once("security.php");
require_once("functions.php");
require_once("config.php");
require_once("config_cslh.php");  

  if(empty($UNTRUSTED['scriptname'])){
    print "error: no script name provided.. is_flush.php?scriptname=[scriptname] ";
    exit;	
  }

if(empty($UNTRUSTED['department'])){ $department=0; } else { $department = intval($UNTRUSTED['department']); }

// Get department information. First found if no specific department assigned
$qQry = "SELECT recno,messageemail,colorscheme,leavetxt,creditline,onlineimage,leaveamessage,offlineimage,speaklanguage FROM livehelp_departments "
      . (($department==0)? 'LIMIT 1': "WHERE recno=$department");
$qRes = $mydatabase->query($qQry);
$qRow = $qRes->fetchRow(DB_FETCHMODE_ORDERED); 
$department   = $qRow[0];         
$messageemail = $qRow[1];          
$colorscheme  = $qRow[2];           
$leavetxt     = $qRow[3];           
$creditline   = $qRow[4];            
$onlineimage  = $qRow[5];
$leaveamessage = $qRow[6];
$offlineimage = $qRow[7];
$speaklanguage = $qRow[8];

// Change Language if department Language is not the same as default language:
if(($CSLH_Config['speaklanguage'] != $speaklanguage) && !(empty($speaklanguage)) ){
 $languagefile = "lang/lang-" . $speaklanguage . ".php";
 if(!(file_exists($languagefile))){
 	$languagefile = "lang/lang-.php";
 }	
 include($languagefile);
}

// get chatmode:
if(empty($CSLH_Config['chatmode'])) 
   $CSLH_Config['chatmode'] = "xmlhttp-refresh";
   
if(empty($_REQUEST['try'])) 
  $try = 2;
else 
  $try = intval($_REQUEST['try']);
    
$chatmodes = explode('-',$CSLH_Config['chatmode']);

if(empty($chatmodes[$try])) 
   $chatmodes[$try] = "refresh";
//print $CSLH_Config['chatmode'] . " try $try $chatmodes[$try]"; 
switch ($chatmodes[$try]){
	 case "xmlhttp":
      $page = "is_xmlhttp.php";
      break;
	 case "flush":
      $page = "is_flush.php";
      break;
   default:
      $page = $UNTRUSTED['scriptname'] . "_refresh.php";
      break;
 } 
 
$success = $UNTRUSTED['scriptname'] . "_flush.php";
$fail = $page;

$_REQUEST['try'] = $_REQUEST['try'] + 1; 
reset($_REQUEST);
 
$querystring="";
while (list($key, $val) = each($_REQUEST)) {
	 if(!(is_array($key)) && !(is_array($val)))
     $querystring .= "&" . urlencode($key) . "=". urlencode($val);
} 	
     
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN"> 
<html> 
<head> 
<title>Detect FLUSH</title> 
</head>  
<SCRIPT type="text/javascript">
function flushworks(){
   window.location.replace("<?php print $success . "?setchattype=1&" . $querystring; ?>");
}

function flushdoesnotwork(){
   window.location.replace("<?php print $fail . "?" . $querystring; ?>");
}
 
</SCRIPT>
<body background=images/<?php echo $colorscheme; ?>/mid_bk.gif>
<?php echo $lang['txt92']; ?>     
 
<?php
// load the buffer 
 sendbuffer();  
 sleep(1);
print " . ";
?>
<SCRIPT type="text/javascript">
   setTimeout('flushworks()', 3000);
</SCRIPT>
<?php
// load the buffer 
 sendbuffer();  
 sleep(3);
print " . ";
?>
<SCRIPT type="text/javascript">
   setTimeout('flushdoesnotwork()', 1000);
</SCRIPT>
</body> 