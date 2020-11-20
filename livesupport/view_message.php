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
require_once("data_functions.php");
require_once("ctabbox.php"); 

validate_session($identity);

?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<?php

 $query = "SELECT * FROM livehelp_leavemessage WHERE id=".intval($UNTRUSTED['view']);
 $data = $mydatabase->query($query);
 print "<table width=600 bgcolor=FFFFFF border=1><tr><td>";
 $data = $data->fetchRow(DB_FETCHMODE_ASSOC);
  
  // parse data:
  $pairs = explode("&",$data['deliminated']);
  for($i=0;$i<count($pairs);$i++){
   	 $myarray = explode("=",$pairs[$i]);
   	 $key = $myarray[0];
     $myfields[$key] = urldecode($myarray[1]);   
   }

  // get list of fields for this department..	
	$q = "SELECT headertext,id FROM livehelp_questions WHERE module='leavemessage' AND department=". intval($data['department']) . " order by ordering ";
	$qRes = $mydatabase->query($q);
  $fields = array();
  while($qRow = $qRes->fetchRow(DB_FETCHMODE_ORDERED)){
    print "<b>".$qRow[0]."</b>:";	
    $key= "field_" . $qRow[1];
    print $myfields[$key] . "<br>";
  }
 
 print "</td></tr></table></td></tr></table>";
 
print "<a href=javascript:window.close()>" . $lang['txt40'] . "</A>"; 

?>