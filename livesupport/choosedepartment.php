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
 

$lastaction = date("YmdHis");
$timeof = date("YmdHis");
$startdate =  date("Ymd");

if(!(isset($UNTRUSTED['tab']))){ $UNTRUSTED['tab'] = ""; }
if(!(isset($UNTRUSTED['action']))){ $UNTRUSTED['action'] = ""; }
if(!(isset($UNTRUSTED['makenamed']))){ $UNTRUSTED['makenamed'] = ""; }
if(!(isset($UNTRUSTED['doubleframe']))){ $UNTRUSTED['doubleframe'] = ""; }
if(!(isset($UNTRUSTED['department']))){ $UNTRUSTED['department'] = ""; }
  
// get the info of this user.. 
$sqlquery = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($sqlquery);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];

// get department information...
$sqlquery = "SELECT * FROM livehelp_departments";
$data_d = $mydatabase->query($sqlquery);  
$department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC);
$department = $department_a['recno']; 
$topframeheight = $department_a['topframeheight'];  
$topbackground = $department_a['topbackground']; 
$colorscheme = $department_a['colorscheme']; 
$blank_offset = $topframeheight - 20;
?>
<html>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
 
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 onload=window.focus();>
<table cellpadding=0 cellspacing=0 border=0  background="<?php echo $topbackground; ?>">
<tr>
<td width=1% background=images/blank.gif><img src=images/blank.gif width=10 height=<?php echo $topframeheight ?> border=0><br>
</td>
<td width=99% background=images/blank.gif align=right><!--<a href=http://www.craftysyntax.com/ target=_blank><img src=images/livehelp.gif border=0></a> --></td>
</tr>
</table>
<center><br>
	<form action=livehelp.php METHOD=GET>
	<input type=hidden name=cslhVISITOR value=<?php echo $identity['SESSIONID']; ?> >	

<h2><?php echo $lang['txt108']; ?>:</h2>
<table>
<?php
// select all Departments:
$query = "SELECT * FROM livehelp_departments WHERE visible=1 ORDER by ordering";
$data_d = $mydatabase->query($query);
while($department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC)){
  $department = $department_a['recno'];
  $name = $department_a['nameof'];
  ?>
  <tr>
   <td><input type=radio name=department value=<?php echo intval($department); ?> > <a name="chatRef" href="livehelp.php?department=<?php echo intval($department); ?>&cslhVISITOR=<?php echo $identity['SESSIONID']; ?>" target=_top><font size=+1><?php echo $name; ?></font></a></td>  
  <?php
    // see if anyone in this department is online:
    $sqlquery = "SELECT isonline FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' AND livehelp_operator_departments.department=".intval($department);
    $data = $mydatabase->query($sqlquery);  
    if($data->numrows() != 0){
      ?><td><img src=images/greenlight.gif width=27 height=28 border=0><?php echo $lang['online']; ?> </td><?php
    } else {
     ?><td><img src=images/redlight.gif width=27 height=28 border=0><?php echo $lang['offline']; ?> </td><?php
    }      
  ?>
  </tr>
  <?php
}
print "</table><br>";
print "<input type=submit value=\"".$lang['SEND']."\">";
print "</form></center>";
?>