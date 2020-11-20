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

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];
$show_arrival = $people['show_arrival']; 
$user_alert = $people['user_alert'];  
$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
 
$colorfile = "images/".$CSLH_Config['colorscheme']."/adminstyle.css";

 


$timeof = date("YmdHis");
$timeof_old = $timeof - 100000;

if(!(isset($UNTRUSTED['alterations']))){ $UNTRUSTED['alterations'] = ""; }
if(!(isset($UNTRUSTED['auto_focus']))){ $UNTRUSTED['auto_focus'] = ""; }
if(!(isset($UNTRUSTED['status']))){ $UNTRUSTED['status'] = ""; }
if(!(isset($UNTRUSTED['show_arrival_new']))){ $UNTRUSTED['show_arrival_new'] = ""; }
if(!(isset($UNTRUSTED['user_alert_new']))){ $UNTRUSTED['user_alert_new'] = ""; }
if(!(isset($UNTRUSTED['auto_invite']))){ $UNTRUSTED['auto_invite'] = ""; }

$alterations_sql = "";
 
$prev = mktime ( date("H"), date("i")-35, date("s"), date("m"), date("d"), date("Y") );
$oldtime = date("YmdHis",$prev);
$rightnow = date("YmdHis");
 
 
if(($UNTRUSTED['alterations'] == "Y") && ($UNTRUSTED['show_arrival_new'] == "")){
	 $UNTRUSTED['show_arrival_new'] = "N"; } 
if(($UNTRUSTED['alterations'] == "Y") && ($UNTRUSTED['user_alert_new'] == "")){ 
	$UNTRUSTED['user_alert_new'] = "Y"; } 
if($UNTRUSTED['status'] == ""){ $UNTRUSTED['status'] = "Y"; } 

if($UNTRUSTED['alterations'] == "Y"){
  $alterations_sql = "auto_invite='".filter_sql($UNTRUSTED['auto_invite'])."',show_arrival='".filter_sql($UNTRUSTED['show_arrival_new'])."',user_alert='".filter_sql($UNTRUSTED['user_alert_new'])."',";
}
    
if($UNTRUSTED['status'] == "N"){
$query = "UPDATE livehelp_users set " . $alterations_sql . "isonline='N',lastaction='$rightnow',status='offline',auto_invite='N' WHERE sessionid='".$identity['SESSIONID']."'";
$mydatabase->query($query);
}
if($UNTRUSTED['status'] == "Y"){
$query = "UPDATE livehelp_users set " . $alterations_sql . "isonline='Y',lastaction='$rightnow',status='chat' WHERE sessionid='".$identity['SESSIONID']."'";
$mydatabase->query($query);
}



$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$data = $mydatabase->query($query);
$row = $data->fetchRow(DB_FETCHMODE_ASSOC);
 
$offline = "  ";
$online = "  ";
$show_arrival = "  ";
$user_alert = "  ";
$auto_invite = "  ";
$auto_focus = " ";

if($row['isonline'] == "N")
	$offline = " SELECTED ";

if($row['isonline'] == "Y")
	$online = " SELECTED ";

if($row['show_arrival'] == "Y")
	$show_arrival = " CHECKED ";

if($row['user_alert'] == "N")
	$user_alert = " CHECKED ";

if($row['auto_invite'] == "Y")
	$auto_invite = " CHECKED ";

if( ($UNTRUSTED['auto_focus']=="Y") || ($UNTRUSTED['alterations'] ==""))
  $auto_focus = " CHECKED ";

?>
<SCRIPT type="text/javascript">

function my_auto_invite(){
  url = 'autoinvite.php';
  if(document.mine.auto_invite.checked){
    window.open(url, 'chat545087', 'width=590,height=400,menubar=no,scrollbars=1,resizable=1');
  }
  for($i=0;$i<100000;$i++){
   // sleep  	
  }
   document.mine.submit();
   
}
 
</SCRIPT>
<link rel="stylesheet" href="<?php echo $colorfile; ?>" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=4>
<form action=admin_rooms.php method=post name=mine>
<table width=100% height=31 cellpadding=0 cellspacing=0 border=0>
<tr>
<td NOWRAP height=31><b><?php echo $lang['status']; ?>:</b><select name=status onchange=document.mine.submit()>
<option value=Y <?php echo $online; ?> ><?php echo $lang['online']; ?></option>
<option value=N <?php echo $offline; ?>><?php echo $lang['offline']; ?></option>
</select></td>
<td NOWRAP height=31><input type=checkbox value=Y name=auto_invite <?php echo $auto_invite; ?> onclick="javascript:my_auto_invite();"><b><font color=007777><?php echo $lang['txt134']; ?></font></b></td>
<td NOWRAP height=31><input type=checkbox value=Y name=show_arrival_new <?php echo $show_arrival; ?> onclick=document.mine.submit() ><b><?php echo $lang['txt135']; ?></b> </td>
<td NOWRAP height=31><input type=checkbox value=N name=user_alert_new  <?php echo $user_alert; ?> onclick=document.mine.submit() ><b><?php echo $lang['txt136']; ?></b></td>
<td NOWRAP height=31><input type=checkbox value=Y name=auto_focus  <?php echo $auto_focus; ?> ><b><?php echo $lang['txt169']; ?></b></td>
</tr>
</table>
<input type=hidden name=alterations value=Y>
</form>
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>