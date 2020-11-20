<?php
require_once("security.php");
require_once("config.php");
require_once("config_cslh.php");
    
  if(empty($UNTRUSTED['scriptname'])){
    print "error: no script name provided.. is_xmlhttp.php?scriptname=[scriptname] ";
    exit;	
  }

if(empty($UNTRUSTED['department'])){ $department=0; } else { $department= intval($UNTRUSTED['department']); }

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
   $CSLH_Config['chatmode'] = "flush-xmlhttp-refresh";
if(empty($_REQUEST['try'])){ 
  $try = 0;
  $_REQUEST['try'] = 0;
} else { 
  $try = intval($_REQUEST['try']);
}
$chatmodes = explode('-',$CSLH_Config['chatmode']);
if(empty($chatmodes[$try])) 
   $chatmodes[$try] = "refresh";

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

$success = $UNTRUSTED['scriptname'] . "_xmlhttp.php";
$fail = $page;

$querystring = "";
$_REQUEST['try'] = $_REQUEST['try'] +1; 
reset($_REQUEST);
while (list($key, $val) = each($_REQUEST)) {
	 if(!(is_array($key)) && !(is_array($val)))	
      $querystring .= "&" . urlencode($key) . "=". urlencode($val);
} 	
     
?>
<html> 
<head> 
<title>Detect XMLHTTP</title> 
<SCRIPT SRC="javascript/xmlhttp.js" type="text/javascript"></script> 
<SCRIPT type="text/javascript">
function checkXMLHTTP(){
  if(XMLHTTP_supported){
    window.location.replace("<?php print $success . "?setchattype=1&" . $querystring; ?>");
  } else {   
   // window.location.replace("<?php print $fail . "?" . $querystring; ?>");    
   <?php print $fail ?> 
  }
}
setTimeout('loadXMLHTTP()', 1000);
setTimeout('checkXMLHTTP()',5500);
</SCRIPT>
</HEAD>
<body background=images/<?php echo $colorscheme; ?>/mid_bk.gif>
<?php echo $lang['txt92']; ?>     
</body> 