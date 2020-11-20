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
$bgcolor = "";
 
if($isadminsetting != "Y"){
 print $lang['txt41'];
 if(!($serversession))
  $mydatabase->close_connect();
 exit;	
}

?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
 
<body bgcolor=<?php echo $color_background;?>><center>

<table bgcolor=DDDDDD width=600><tr><td>
<b>Tabs:</b> 
</b></td></tr></table>
<table width=600>
<tr bgcolor=FFFFFF><td><b><?php echo $lang['name']; ?></b></td><td><b>url</b></td><td><b><?php echo $lang['options']; ?></b></td></tr>
<?php
if(isset($UNTRUSTED['updatemod'])){
  $query = "UPDATE livehelp_modules 
            SET name='".filter_sql($UNTRUSTED['name'])."',
                path='".filter_sql($UNTRUSTED['path'])."',
                adminpath='".filter_sql($UNTRUSTED['adminpath'])."',
                `query_string`='".filter_sql($UNTRUSTED['query_string'])."'
            WHERE id=".intval($UNTRUSTED['updatemod']);       
  $mydatabase->query($query);  
}
if(isset($UNTRUSTED['newmodinsert'])){
  $query = "INSERT INTO livehelp_modules
             (name,path,adminpath,query_string) 
            VALUES ('".filter_sql($UNTRUSTED['name'])."','".filter_sql($UNTRUSTED['path'])."','".filter_sql($UNTRUSTED['adminpath'])."','".filter_sql($UNTRUSTED['query_string'])."')";
  $mydatabase->query($query); 	
}

if(isset($UNTRUSTED['delmod'])){
  $query = "DELETE FROM livehelp_modules WHERE id=".intval($UNTRUSTED['delmod']);
  $mydatabase->query($query); 	
  $query = "DELETE FROM livehelp_modules_dep WHERE modid=".intval($UNTRUSTED['delmod']);
  $mydatabase->query($query); 
}

$query = "SELECT * FROM livehelp_modules ";
$bgcolor="$color_alt2";
$data = $mydatabase->query($query);
while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
 if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
 print "<tr bgcolor=$bgcolor><td>" . $row['name'] . "</b></td><td>" . $row['path'] . "</td><td><b><a href=modules.php?edmod=" . $row['id'] . ">".$lang['edit']."</a>";
 if($row['id'] != 1){ print " <a href=modules.php?delmod=" . $row['id'] . " onClick=\"return confirm('".$lang['areyousure']."')\"><font color=990000>".$lang['DELETE']."</font></a>"; }
 if($row['adminpath'] != ""){  print "  <a href=" . $row['adminpath'] . " ><font color=009999>ADMIN</font></a>"; } 
 print "</b></td></tr>\n";
}
 ?>
</table>
<?php if (!(isset($UNTRUSTED['newmod']))){ ?> <br><br>
<a href=modules.php?newmod=1><font size=+2><?php echo $lang['CREATE']; ?></font></a>
<?php } 
if (isset($UNTRUSTED['edmod'])){
	
$query = "SELECT * FROM livehelp_modules WHERE id=". intval($UNTRUSTED['edmod']);
$data = $mydatabase->query($query);
$modulerow = $data->fetchRow(DB_FETCHMODE_ASSOC);

}
if(isset($UNTRUSTED['newmod'])){
  $modulerow['name'] = "";
  $modulerow['adminpath'] = "";
  $modulerow['query_string']  = "";
  $modulerow['path'] = "";
}

if ( (isset($UNTRUSTED['newmod'])) || (isset($UNTRUSTED['edmod']))){
 ?>
<table width=600><tr><td>
<h2><?php echo $lang['CREATE']; ?>/<?php echo $lang['edit']; ?> Tab:</h2>
  <form action="modules.php" method="post"> 
<?php if (isset($UNTRUSTED['edmod'])){ ?>
  <input type="hidden" name="updatemod" value="<?php echo $modulerow['id']; ?>">
<?php } else { ?>
  <input type="hidden" name="newmodinsert" value="yes">
<?php } ?>
<table width=100% bgcolor=D4DCF2><tr><td>
<b><?php echo $lang['name']; ?>:</b>
</td></tr></table>
<?php echo $lang['txt81']; ?>
<br>
<b><?php echo $lang['name']; ?>:</b> <input type="text" name="name"  MAXLENGTH=20 size=20 value="<?php echo $modulerow['name']; ?>"><br>
<table width=100% bgcolor=D4DCF2><tr><td>
<?php echo $lang['txt82']; ?> 
</td></tr></table>
<?php echo $lang['txt83']; ?> 
<br>
<b> URL:</b><input type=text name=path size=45 value="<?php echo $modulerow['path']; ?>" ><br>
<table width=100% bgcolor=D4DCF2><tr><td>
<?php echo $lang['txt84']; ?>
</td></tr></table>
<?php echo $lang['txt85']; ?> <br>
<b>URL :</b><input type=text name=adminpath size=45 value="<?php echo $modulerow['adminpath']; ?>" ><br>
<table width=100% bgcolor=D4DCF2><tr><td>
<?php echo $lang['txt86']; ?>
</td></tr></table>
<?php echo $lang['txt86']; ?>
<b>query string:</b><input type=text name="query_string" size=45 value="<?php echo $modulerow['query_string']; ?>" ><br>
<br>
<input type=submit name="<?php echo $lang['CREATE']; ?>">
  </form>  
</td></tr></table>
<?php } ?>
<br><br>
<pre>


</pre>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
<a href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help</a> &copy; 2003 - 2008 by <a href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>  
</font>