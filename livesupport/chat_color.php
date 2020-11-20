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

$timeof = date("YmdHis");

if(!(isset($UNTRUSTED['whattodo']))){  $UNTRUSTED['whattodo'] = ""; }

if($UNTRUSTED['whattodo'] == ""){
 // get current colors...
 $query = "SELECT * FROM livehelp_operator_channels WHERE id=".intval($UNTRUSTED['id']);
 $sth = $mydatabase->query($query);
 $rs = $sth->fetchRow(DB_FETCHMODE_ASSOC);
 $channelcolor = $rs['channelcolor'];
 $txtcolor = $rs['txtcolor'];
 $txtcolor_alt = $rs['txtcolor_alt'];
 
 // get the usernames:
  $query = "SELECT username FROM livehelp_users WHERE user_id=".intval($rs['user_id']);
 $sth = $mydatabase->query($query);
 $rs2 = $sth->fetchRow(DB_FETCHMODE_ASSOC);
 $txtcolor_username = $rs2['username'];
 
 $query = "SELECT username FROM livehelp_users WHERE user_id=".intval($rs['userid']);
 $sth = $mydatabase->query($query);
 $rs2 = $sth->fetchRow(DB_FETCHMODE_ASSOC);  
 $txtcolor_alt_username = $rs2['username'];
 
  
}

if($UNTRUSTED['whattodo'] == "UPDATE"){
 $query = "UPDATE livehelp_operator_channels SET txtcolor_alt='".filter_sql($UNTRUSTED['txtcolor_alt'])."',channelcolor='".filter_sql($UNTRUSTED['channelcolor'])."',txtcolor='".filter_sql($UNTRUSTED['txtcolor'])."' WHERE id=".intval($UNTRUSTED['id']);
 $sth = $mydatabase->query($query);
 print $lang['newcolor']; 
 exit;  
}
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=E0E8F0 onload=window.focus()>
<SCRIPT type="text/javascript">
function changebg(towhat){
 document.mine.channelcolor.value = towhat;
}
function changetxt(towhat){
 document.mine.txtcolor.value = towhat;	
}
function changetxtalt(towhat){
 document.mine.txtcolor_alt.value = towhat;	
}

</SCRIPT>
<FORM ACTION=chat_color.php Method=POST name=mine>
<input type=hidden name=id value="<?php echo $UNTRUSTED['id']; ?>">
<input type=hidden name=whattodo value=UPDATE>
<font size=+2><?php echo $lang['txt1']; ?></font><br>
<b><?php echo $lang['txt2']; ?></b> #<input type=text size=7 name=channelcolor value="<?php echo $channelcolor; ?>"> <br>
<table width=500>
<tr>
<?php

// we want to create a random color chart of Light colors consisting
// of C,D,E,F in Hex..
$highletters = array("C","D","E","F");

// rows
for($i=1; $i< 5; $i++){
 print "<tr>\n";
 // cols 
 for($j=1;$j<20;$j++){
    // generate random Hex..
    $randomhex = "";
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $randomhex .= $highletters[$randomindex];
    }	 
    print "<td width=20 bgcolor=$randomhex><a href=javascript:changebg('$randomhex')><img src=images/blank.gif width=20 height=20 border=0></a></td>\n";
 }
 print "</tr>\n";
}
print "</table>\n";
?> 
<br>

<font color=<?php echo $txtcolor; ?>><b><?php echo $txtcolor_username; ?> <?php echo $lang['txt3']; ?></b></font> #<input type=text size=7 name=txtcolor  value="<?php echo $txtcolor; ?>"> <br>
<table width=500>
<tr>
<?php

// we want to create a random color chart of Light colors consisting
// of C,D,E,F in Hex..
$highletters = array("C","D","E","F");
$lowletters = array("0","2","4","6");

// rows
for($i=1; $i< 5; $i++){
 print "<tr>\n";
 // cols 
 for($j=1;$j<20;$j++){
    // generate random Hex..
    $randomhex = "";
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $randomhex .= $lowletters[$randomindex];
    }	 
    print "<td width=20 bgcolor=$randomhex><a href=javascript:changetxt('$randomhex')><img src=images/blank.gif width=20 height=20 border=0></a></td>\n";
 }
 print "</tr>\n";
}
print "</table>\n";
?> 
<br><br>

<font color=<?php echo $txtcolor_alt; ?>><b><?php echo $txtcolor_alt_username; ?> <?php echo $lang['txt3']; ?></b></font> #<input type=text size=7 name=txtcolor_alt  value="<?php echo $txtcolor_alt; ?>"> <br>
<table width=500>
<tr>
<?php

// we want to create a random color chart of Light colors consisting
// of C,D,E,F in Hex..
$highletters = array("C","D","E","F");
$lowletters = array("2","4","6","8");

// rows
for($i=1; $i< 5; $i++){
 print "<tr>\n";
 // cols 
 for($j=1;$j<20;$j++){
    // generate random Hex..
    $randomhex = "";
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $randomhex .= $lowletters[$randomindex];
    }	 
    print "<td width=20 bgcolor=$randomhex><a href=javascript:changetxtalt('$randomhex')><img src=images/blank.gif width=20 height=20 border=0></a></td>\n";
 }
 print "</tr>\n";
}
print "</table>\n";
?> 
<br>
<input type=SUBMIT value="<?php echo $lang['txt4']; ?>">
</FORM>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>