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

 
$query = "SELECT * from livehelp_users WHERE user_id=". intval($UNTRUSTED['id']);
$user_info = $mydatabase->query($query);
if($user_info->numrows() == 0){
	print "<SCRIPT type=\"text/javascript\">window.close();</SCRIPT>";
	exit;
}
$user_info = $user_info->fetchRow(DB_FETCHMODE_ASSOC);

$query = "SELECT * from livehelp_departments WHERE recno=".intval($UNTRUSTED['department']);
$tmp = $mydatabase->query($query);
$nameof = $tmp->fetchRow(DB_FETCHMODE_ASSOC);
$nameof = $nameof['nameof'];

?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<BODY bgcolor=<?php echo $color_background;?> onload=window.focus()>
<table width=100%>
<tr><td colspan=2 bgcolor=DDDDDD> <?php echo $lang['txt64']; ?>&nbsp;

<script type="text/javascript"> 

//Refresh page script- By Brett Taylor (glutnix@yahoo.com.au) 
//Modified by Dynamic Drive for NS4, NS6+ 
//Visit http://www.dynamicdrive.com for this script 

//configure refresh interval (in seconds) 
var countDownInterval=30; 
//configure width of displayed text, in px (applicable only in NS4) 
var c_reloadwidth=200 

</script> 

<SCRIPT type="text/javascript"> 

var countDownTime=countDownInterval+1; 
function countDown(){ 
countDownTime--; 
if (countDownTime <=0){ 
countDownTime=countDownInterval; 
clearTimeout(counter) 
window.location.reload() 
return 
} 
if (document.all) //if IE 4+ 
document.all.countDownText.innerText = countDownTime+" "; 
else if (document.getElementById) //else if NS6+ 
document.getElementById("countDownText").innerHTML=countDownTime+" " 
else if (document.layers){ //CHANGE TEXT BELOW TO YOUR OWN 
document.c_reload.document.c_reload2.document.write('Next <a href="javascript:window.location.reload()">refresh</a> in <b id="countDownText">'+countDownTime+' </b> seconds') 
document.c_reload.document.c_reload2.document.close() 
} 
counter=setTimeout("countDown()", 1000); 
} 

function startit(){ 
if (document.all||document.getElementById) //CHANGE TEXT BELOW TO YOUR OWN 
  document.write('Next <a href="javascript:window.location.reload()">refresh</a> in <b id="countDownText">'+countDownTime+' </b> seconds'); 
  countDown(); 
} 

if (document.all||document.getElementById){ 
   startit(); 
}

</script> 


</td></tr>
<tr><td align=left><?php
print "<b>" . $lang['referer'] . ":</b> <a href=" . str_replace(" ","+",$user_info['camefrom']) . " target=_blank>" . $user_info['camefrom'] . "</a><br>";
print "<b>" . $lang['status'] . ":</b>" . $user_info['status'] . "<br>";
print "<b>" . $lang['dept'] . "</b> $nameof<br>";
print "<b>E-mail :</b>" . $user_info['email'] . "<br>";
print "<b>SessionID :</b>" . $user_info['sessionid'] . "<br>";
print "<b>identity :</b>" . $user_info['identity'] . "<br>";
if($user_info['hostname']=="host_lookup_not_enabled"){
  if(!(empty($UNTRUSTED['lookup'])))
    print "<b>HostName:</b>" . gethostbyaddr($user_info['ipaddress']) . "<br>";
  else
    print "<b>HostName:</b> <a href=details.php?lookup=1&id=" .$UNTRUSTED['id'] . "> Lookup Host </a><br>";
} else
  print "<b>HostName:</b>" . $user_info['hostname'] . "<br>"; 
  
include_once("class/browser_info.php");
    if(empty($user_info['useragent']))
      $client_agent = ( !empty($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : ( ( !empty($_ENV['HTTP_USER_AGENT']) ) ? $_ENV['HTTP_USER_AGENT'] : $HTTP_USER_AGENT );
    else
      $client_agent = $user_info['useragent'];
    $b = new BrowserInfo($client_agent);
print "<b>User Agent :</b>" . $b->USER_AGENT  ."<br>"; // STRING - USER_AGENT_STRING
print "<b>OS :</b>" . $b->OS  ."<br>"; // STRING - USER_AGENT_STRING
print "<b>OS Version :</b>" . $b->OS_Version  ."<br>"; // STRING - USER_AGENT_STRING
print "<b>Browser :</b>" . $b->Browser  ."<br>"; // STRING - USER_AGENT_STRING
print "<b>Browser_Version :</b>". $b->Browser_Version   ."<br>"; // STRING - USER_AGENT_STRING 
print "<b>Type :</b>". $b->Type  ."<br>"; // STRING - USER_AGENT_STRING
	
print "<b>Ip :</b>" . $user_info['ipaddress'] . "<br>";
print "<b>Cookied :</b>" . $user_info['cookied'] . "<br>";
print "<b>New Session :</b>" . $user_info['new_session'] . "<br>";
  

  // get session data 
  $query = "SELECT * FROM livehelp_users WHERE user_id=" . intval($UNTRUSTED['id']);
  $userdata = $mydatabase->query($query);
  $user_row = $userdata->fetchRow(DB_FETCHMODE_ASSOC);
  $sessiondata = $user_row['sessiondata'];
  $datapairs = explode("&",$sessiondata);
  $datamessage="";
  for($l=0;$l<count($datapairs);$l++){
  	  $dataset = explode("=",$datapairs[$l]);
  	  if(!(empty($dataset[1]))){
  	  	$fieldid = str_replace("field_","",$dataset[0]);
  	  	$query = "SELECT * FROM livehelp_questions WHERE id=". intval($fieldid); 
  	  	$questiondata = $mydatabase->query($query);
        $question_row = $questiondata->fetchRow(DB_FETCHMODE_ASSOC);    	          
    	  print "<b> ". $question_row['headertext'] . ":</b> <br><font color=000000>" . urldecode($dataset[1]) . "</font><br>";
      }
  }
   
$now = date("YmdHis");
$thediff = $now - $user_info['lastaction'];
print "<b>" .  $lang['txt65'] . "</b> $thediff sec. <br>";

// time online:
$query = "SELECT whendone from livehelp_visit_track WHERE sessionid='".filter_sql($user_info['sessionid'])."' Order by whendone LIMIT 1";
$page_trail = $mydatabase->query($query); 
$page = $page_trail->fetchRow(DB_FETCHMODE_ASSOC);
$later = $page['whendone'];
print "<b>Time online:</b>". secondstoHHmmss(timediff($later,date("YmdHis"))) . "<br>";

print "<b>" .  $lang['txt66'] . "</b><br>";
$query = "SELECT * from livehelp_visit_track WHERE sessionid='".filter_sql($user_info['sessionid'])."' Order by whendone DESC";
$page_trail = $mydatabase->query($query);

print "<table border=1><tr bgcolor=FFFFFF><td>" .  $lang['txt67'] . "</td><td>url</td><td>" . $lang['date'] . "</td></tr>";
while($page = $page_trail->fetchRow(DB_FETCHMODE_ASSOC)){
  if(empty($CSLH_Config['offset'])){ $CSLH_Config['offset']= 0; }
  $when = mktime ( substr($page['whendone'],8,2)+$CSLH_Config['offset'], substr($page['whendone'],10,2), substr($page['whendone'],12,2), substr($page['whendone'],4,2) , substr($page['whendone'],6,2), substr($page['whendone'],0,4) );
  print "<tr><td>" . $page['title']  . "</td><td><a href=" . $page['location'] . "  target=_blank>" . $page['location'] . "</a></td><td>";
  print date("F j, Y, g:i a",$when);
  print "</td></tr>";
}
print "</table><br><center><a href=javascript:window.close()>".$lang['txt40']."</a>";
if(!($serversession))
  $mydatabase->close_connect();
?>