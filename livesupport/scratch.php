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

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");


// if no admin rights then user can not clear or remove data: 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$data = $mydatabase->query($query);
$row = $data->fetchRow(DB_FETCHMODE_ASSOC);
$isadminsetting = $row['isadmin'];

// update scratch
if(isset($UNTRUSTED['new_scratch_space'])){
  $query = "UPDATE livehelp_config 
            SET scratch_space='".filter_sql(htmlspecialchars(strip_tags($UNTRUSTED['new_scratch_space'])))."'";
  $mydatabase->query($query);  
  $CSLH_Config['scratch_space'] = htmlspecialchars(strip_tags($UNTRUSTED['new_scratch_space']));
}

if(!(isset($UNTRUSTED['editbox']))){
  ?>
 
<SCRIPT type="text/javascript" SRC="javascript/hideshow.js"></SCRIPT>
<SCRIPT type="text/javascript">
function currentstatus(){
   	h = document.getElementById("security").height;
  if(h == 150){
   makeVisible('currentreg');
  }    
} 
</SCRIPT>
<body bgcolor="<?php echo $color_background;?>" onload="currentstatus();" marginheight=0 marginwidth=0 topmargin=0>
<center><br><br>
   
<?php
   // Display any Security Warnings, Updates, Notices etc..
   // ! * Do not remove this block of code * !. 
   // There are Security holes discovered
   // every day in Open source programs and not knowing about them
   // could be fatal to your website so this is *VERY* important:
   if(empty($CSLH_Config['directoryid'])) 
     $CSLH_Config['directoryid'] = 0; 
     print "<a href=http://security.craftysyntax.com/updates/?v=" . $CSLH_Config['version'] . "&d=" . $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . " target=_blank>";
   if(empty($_SERVER['HTTPS'])) $_SERVER['HTTPS'] = "no";
   if($_SERVER['HTTPS'] == "on") 
     print "<img src=https://www.craftysyntax.com/security/?p=scratch&randu=".date("YmdHis")."&format=image&v=". $CSLH_Config['version'] . "&d=". $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . "  name=security id=security  border=0></a><br>";
   else
     print "<img src=http://security.craftysyntax.com/?p=scratch&randu=".date("YmdHis")."&format=image&v=". $CSLH_Config['version'] . "&d=". $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . "  name=security id=security  border=0></a><br>";
 } else { print "<body>"; }
?>
<br>

<DIV id=currentreg STYLE="display:none;">
<form action=registerit.php method=post name=register>
<input type=hidden value=now name=goforit>
<b>Registration ID:</b><input type=text name=directoryid size=32 MAXSIZE=32 value="<?php echo $CSLH_Config['directoryid']; ?>"><input type=submit value=UPDATE><br>
<?php
     print "<a href=http://security.craftysyntax.com/updates/?v=" . $CSLH_Config['version'] . "&d=" . $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . " target=_blank>";
     print " How do I Register the program? (CLICK HERE)</a>";
?>
</form>
</DIV>

<DIV STYLE="width:90%; background:#FFFFFF; border:1px dotted;">
<?php 
if(isset($UNTRUSTED['editbox'])){
  ?>
<form action=scratch.php name=chatter method=post>
<input type=hidden name=whattodo value=update_scratch_space>
<textarea cols=65 rows=20 ID="Content" name=new_scratch_space><?php echo htmlspecialchars($CSLH_Config['scratch_space']); ?></textarea><br><br>
<input type=submit value=UPDATE>
</FORM>
  <?php
} else {
   // show edit button:
   if ( ($isadminsetting!="R") && ($isadminsetting!="L") ) 
     print "<a href=\"scratch.php?editbox=1\" style=\"float:right;\">EDIT NOTES</a><br/>";
 
   // Display Scratch space from configuration table.
   print nl2br($CSLH_Config['scratch_space']);
} ?>
</DIV>
