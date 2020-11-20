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
require_once("visitor_common.php");
  
// get the info of this user.. 
$sqlquery = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($sqlquery);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];

if(!(isset($UNTRUSTED['tab']))){ $UNTRUSTED['tab'] = 0; }


// get department information...
$where="";
   if($UNTRUSTED['department']!=0){ $where = " WHERE recno=".intval($UNTRUSTED['department']); }
   $sqlquery = "SELECT * FROM livehelp_departments $where ";
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
<link title="new" rel="stylesheet" href="images/<?php echo $colorscheme; ?>/navigation.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
 
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 onload=window.focus(); background="<?php echo $topbackground; ?>">
<table cellpadding=0 cellspacing=0 border=0>
<tr>
<td width=1% background=images/blank.gif><img src=images/blank.gif width=10 height=<?php echo $blank_offset ?> border=0><br>

<table cellpadding=0 cellspacing=0 border=0 width=100%>
<tr>
 <td width=7 valign=bottom><img src="images/blank.gif" width="7" height="1" border="0"></td>
<?php
$tabshown = 0;
$sqlquery = "SELECT * 
          FROM livehelp_modules,livehelp_modules_dep 
          WHERE livehelp_modules.id=livehelp_modules_dep.modid
            AND livehelp_modules_dep.departmentid=".intval($department)." 
          ORDER by livehelp_modules_dep.ordernum";
$tabs = $mydatabase->query($sqlquery);
$k = 0;
while($row = $tabs->fetchRow(DB_FETCHMODE_ASSOC)){
 $k++;
 if(strlen($row['name'])>20){ $long = 1; } else { $long = 0;  }
 if($UNTRUSTED['tab'] == $k){ $class = "class=\"ontab\""; } else { $class = ""; } ?>
 <td id="navigation" class="navigation" nowrap width="75" STYLE="text-align:center;"><span <?php echo $class; ?>><A HREF="livehelp.php?page=<?php echo $row['path']; ?>&department=<?php echo $department; ?>&tab=<?php echo $row['id']; ?>" target=_top><?php print navspaces($lang['name']) . ereg_replace(" ","&nbsp;",$row['name']) . navspaces($lang['name']); ?></a></span></td>
 	<td><img src="images/blank.gif" width="2" height="5" border="0"></td>
<?php } 	
if(!($serversession))
  $mydatabase->close_connect();
?>
 <td width=95%>&nbsp;</td>
 <td><img src="images/blank.gif" width="10" height="5" border="0"></td> 
</tr>
</table>	
 
</td>
<td width=99% background=images/blank.gif align=right valign=top>


</td>
</tr>
</table>
</body>
</html>