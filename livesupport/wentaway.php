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
  
  // stop chat.
  stopchat($identity['SESSIONID']);

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
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
 
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 onload=window.focus();>
<table cellpadding=0 cellspacing=0 border=0  background="<?php echo $topbackground; ?>">
<tr>
<td width=1% background=images/blank.gif><img src=images/blank.gif width=10 height=<?php echo $topframeheight ?> border=0><br>
</td>
<td width=99% background=images/blank.gif align=right><a href=http://www.craftysyntax.com/ target=_blank><img src=images/livehelp.gif border=0></a></td>
</tr>
</table>
<center><br>
<?php     
  print "<br><br><br><br><center><font color=990000 size=+2>" . $lang['gone'] . "</font><br><br>";
  
  if($emailfun!="N"){
   ?>
   <form action=sendtranscript.php method=post>
   <input type=hidden name=transsessionid value=<?php echo $identity['SESSIONID']; ?> >
   <table width=300><tr><td>
     <?php echo $lang['txt128']; ?>
     <br>
     Email:<input name=sendto type=text size=40>
   </td></tr></table>
   <input type=submit value="<?php echo $lang['SEND']; ?>" >
   </FORM>
   <?php
   print "<br><br><a href=javascript:window.close()>" . $lang['txt40'] . "</a></center>"; 
  }
 
if(!($serversession))
  $mydatabase->close_connect();
?>