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

// get department information...
   $where="";
   if(!(empty($UNTRUSTED['department']))){ 
   	  $where = " WHERE recno=".intval($UNTRUSTED['department']); 
   } else { 
   	  $where = ""; $UNTRUSTED['department'] = 0; 
   }
   $query = "SELECT offline,busymess,colorscheme,topbackground FROM livehelp_departments $where ";
   $data_d = $mydatabase->query($query);  
   $department_a = $data_d->fetchRow(DB_FETCHMODE_ASSOC);
   $topbackground = $department_a['topbackground']; 
   $colorscheme = $department_a['colorscheme']; 
   $busymess = $department_a['busymess'];
   $offline = $department_a['offline'];
   
// see if anyone is online if noone is online show offline message. 
// else show busy message:
 $query = "SELECT * 
           FROM livehelp_users,livehelp_operator_departments 
           WHERE livehelp_users.user_id=livehelp_operator_departments.user_id 
             AND livehelp_users.isonline='Y' 
             AND livehelp_users.isoperator='Y' 
             AND livehelp_operator_departments.department=". intval($UNTRUSTED['department']);
 $data = $mydatabase->query($query); 

?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" > 
<body  background=images/<?php echo $colorscheme; ?>/mid_bk.gif onload=window.parent.focus()>
<br><Br><bR>
<blockquote>
<?php 
 if($data->numrows() != 0){
   $busymess = str_replace("[department]",$department,$busymess);
   echo $busymess;
 }else  {
   echo $offline; 
  }
?>
 </blockquote>
</body>
<?php

if(!($serversession))
  $mydatabase->close_connect();
?>