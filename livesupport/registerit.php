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

// if no admin rights then user can not clear or remove data: 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$data = $mydatabase->query($query);
$row = $data->fetchRow(DB_FETCHMODE_ASSOC);
$isadminsetting = $row['isadmin'];

if($isadminsetting != "Y"){
	 print "You must be logged in with Admin rights in order to change/view security settings";
exit;
}
?>
<body bgcolor=<?php echo $color_background;?> onload=currentstatus();>
<center>
<table border=0 cellpadding=0 cellspacing=0 width=590>
<?php
if(!(empty($UNTRUSTED['goforit']))){
  $query = "UPDATE livehelp_config set directoryid='".filter_sql($UNTRUSTED['directoryid'])."'";
  $mydatabase->query($query);
  $CSLH_Config['directoryid'] = $UNTRUSTED['directoryid'];
 } 
 ?>
<tr><td bgcolor=<?php echo $color_alt2; ?>><b>Crafty Syntax Security Registration Information:</b></td></tr>
<tr><td bgcolor=<?php echo $color_alt1;?>><ul>
Security is <font color=990000><b>*VERY*</b></font> important to Crafty Syntax. 
Security vulnerabilities are found in open source programs on almost a weekly 
basis and not knowing about these vulnerabilities can be catastrophic to your
website. Although great efforts have been made to try to make the code that 
runs Crafty Syntax Live Help very secure, there is still the possiblilty that
a security hole could have been overlooked. By registering your version of Crafty Syntax 
Live Help you can be e-mailed on any updates that are made to Crafty Syntax 
Code and prevent attacks from hackers. 
<SCRIPT type="text/javascript">
function currentstatus(){
   	w = document.getElementById("security").width;
  if(w == 490){
  	var mydiv = document.getElementById('currentreg');   
    mydiv.innerHTML = '<br><br><font color=007700><b>This Installation has already been Registered.</b></font>';
  }    
}
 
</SCRIPT>
<?php
   // Display any Security Warnings, Updates, Notices etc..
   // ! * Do not remove this block of code * !. 
   // There are Security holes discovered
   // every day in Open source programs and not knowing about them
   // could be fatal to your website so this is *VERY* important:
   if(empty($CSLH_Config['directoryid'])) 
     $CSLH_Config['directoryid'] = 0;
   print "<br><a href=http://security.craftysyntax.com/updates/?v=" . $CSLH_Config['version'] . "&d=" . $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . " target=_blank><img src=http://security.craftysyntax.com/?randu=".date("YmdHis")."&format=image&v=". $CSLH_Config['version'] . "&d=". $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . " name=security id=security border=0></a><br>";
?>
<span id=currentreg>
<form action=registerit.php method=post name=register>
<input type=hidden value=now name=goforit>
<b>Registration ID:</b><input type=text name=directoryid size=32 MAXSIZE=32 value="<?php echo $CSLH_Config['directoryid']; ?>"><input type=submit value=UPDATE>
</form>
</span>
</ul>
</td></tr>
</table>
<br><br>
<?php
if(!($serversession))
   $mydatabase->close_connect();
?>