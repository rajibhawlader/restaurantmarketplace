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

if(empty($UNTRUSTED['top'])) $UNTRUSTED['top'] = 0;
$days_row = "";

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
 if(empty($UNTRUSTED['typeof']))
  $UNTRUSTED['typeof'] = "directvisits";
 
$typeof = $UNTRUSTED['typeof'];
 
$gtotal = 0;

if($UNTRUSTED['type'] == "refer"){
$query = "SELECT * FROM livehelp_referers_monthly where recno=".intval($UNTRUSTED['item']);
$openedacc_tmp = $mydatabase->query($query);
$openedacc_tmp = $openedacc_tmp->fetchRow(DB_FETCHMODE_ASSOC);
$query = "SELECT * FROM livehelp_referers_daily where pageurl='".filter_sql($openedacc_tmp['pageurl'])."' ORDER by dateof";
$openedacc = $mydatabase->query($query);
} else {
$query = "SELECT * FROM livehelp_visits_monthly where recno=".intval($UNTRUSTED['item']);
$openedacc_tmp = $mydatabase->query($query);
$openedacc_tmp = $openedacc_tmp->fetchRow(DB_FETCHMODE_ASSOC);
$query = "SELECT * FROM livehelp_visits_daily where pageurl='".filter_sql($openedacc_tmp['pageurl'])."' ORDER by dateof";
$openedacc = $mydatabase->query($query);
}

$max = 10;
 while($myday = $openedacc->fetchRow(DB_FETCHMODE_ASSOC)){
     $uniquevisits = $myday[$typeof];    
     if($uniquevisits>$max){ $max =$uniquevisits; }
  }
?>
<table bgcolor=<?php echo $color_background;?>>
  <tr><td bgcolor=FFFFFF colspan=31> 
  <b><?php echo $lang['txt69']; ?>:</b></td></tr>
  <tr>
  <?php
  $back = $UNTRUSTED['top'] - 30;
  $next = $UNTRUSTED['top'] + 30;
  if ($back > -1){
  $days_row .= "<td></td>";
}
 $openedacc = $mydatabase->query($query);
 while($myday = $openedacc->fetchRow(DB_FETCHMODE_ASSOC)){
     $uniquevisits = $myday[$typeof];
     $gtotal = $gtotal + $uniquevisits;
     $dayof =  $myday['dateof'];     
     $height = floor(($uniquevisits/$max) * 200);
     print "<td valign=bottom>$uniquevisits<br><img src=images/bar.gif width=10 height=$height></td>";
     $days_row .= "<td>" . substr($dayof,6,2);
     $days_row .= "</font></td>";     
  }
  ?>
  </tr><tr>
  <?php echo $days_row; ?>
  </tr>
  </table><br>
<?php  
$days_row ="";
$gtotal = 0;
if($UNTRUSTED['type'] == "refer"){
$query = "SELECT * FROM livehelp_referers_monthly where recno=".intval($UNTRUSTED['item']);
$openedacc_tmp = $mydatabase->query($query);
$openedacc_tmp = $openedacc_tmp->fetchRow(DB_FETCHMODE_ASSOC);
$query = "SELECT * FROM livehelp_referers_monthly where pageurl='".filter_sql($openedacc_tmp['pageurl'])."' ORDER by dateof";
$openedacc = $mydatabase->query($query);
} else {
$query = "SELECT * FROM livehelp_visits_monthly where recno=".intval($UNTRUSTED['item']);
$openedacc_tmp = $mydatabase->query($query);
$openedacc_tmp = $openedacc_tmp->fetchRow(DB_FETCHMODE_ASSOC);
$query = "SELECT * FROM livehelp_visits_monthly where pageurl='".filter_sql($openedacc_tmp['pageurl'])."' ORDER by dateof";
$openedacc = $mydatabase->query($query);
}

$max = 10;
 while($myday = $openedacc->fetchRow(DB_FETCHMODE_ASSOC)){
     $uniquevisits = $myday[$typeof];     
     if($uniquevisits>$max){ $max =$uniquevisits; }
  }
?>
<table bgcolor=<?php echo $color_background;?>>
  <tr><td bgcolor=FFFFFF colspan=31> 
  <b><?php echo $lang['txt70']; ?>:</b></td></tr>
  <tr>
  <?php
  $back = $UNTRUSTED['top'] - 30;
  $next = $UNTRUSTED['top'] + 30;
  if ($back > -1){
  $days_row .= "<td></td>";
}
 $openedacc = $mydatabase->query($query);
 while($myday = $openedacc->fetchRow(DB_FETCHMODE_ASSOC)){
     $uniquevisits = $myday[$typeof];
     $gtotal = $gtotal + $uniquevisits;
     $dateof =  $myday['dateof'];     
     $height = floor(($uniquevisits/$max) * 200);
     print "<td valign=bottom>$uniquevisits<br><img src=images/bar.gif width=10 height=$height></td>";
     $days_row .= "<td>" . substr($dateof,4,2);
     $days_row .= "</font></td>";     
  }
  ?>
  </tr><tr>
  <?php echo $days_row; ?>
  </tr>
  </table><br>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>