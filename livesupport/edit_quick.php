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

// id of what invite to edit:
if(empty($UNTRUSTED['what'])){ $UNTRUSTED['what'] = ""; }

if(empty($UNTRUSTED['ishtml'])){ $UNTRUSTED['ishtml'] = ""; } 
if(empty($UNTRUSTED['typeof'])){ $UNTRUSTED['typeof'] = ""; } 
if($UNTRUSTED['typeof']=="IMAGE")
   $UNTRUSTED['ishtml'] = "YES";    

$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isnamed = $people['isnamed'];
 
 
if($UNTRUSTED['what'] == $lang['SAVE']){       
     $query = "SELECT * FROM livehelp_departments ";
     $data = $mydatabase->query($query);
     $departments = "";
     $comma = "";
     
     // filter comments for newlines:
     $comment = ereg_replace("\n"        , ""          , $UNTRUSTED['comment'] ); 
	   $comment = ereg_replace("\r"        , ""          , $comment ); 	
     
     // make a comma diliminated list of the departments that this belongs to.
     while($row =  $data->fetchRow(DB_FETCHMODE_ASSOC) ){
        $lookingfor = "department_" . $row['recno'];
        if(isset($UNTRUSTED[$lookingfor])){ $departments .= $comma . $row['recno']; $comma = ","; }
        print "$departments<br>";
     }
     
   if(!(empty($UNTRUSTED['editid']))){ 
      $query = "UPDATE livehelp_quick set ishtml='".filter_sql($UNTRUSTED['ishtml'])."',user=".intval($myid).",name='".filter_sql($UNTRUSTED['notename'])."',message='".filter_sql($comment)."',department='".filter_sql($departments)."',visiblity='".filter_sql($UNTRUSTED['visiblity'])."' WHERE id=". intval($UNTRUSTED['editid']);	
      $messages = $mydatabase->query($query);	
   } else {
      $query = "INSERT INTO livehelp_quick (name,message,typeof,visiblity,department,user,ishtml) VALUES ('".filter_sql($UNTRUSTED['notename'])."','".filter_sql($comment)."','". filter_sql($UNTRUSTED['typeof'])."','".filter_sql($UNTRUSTED['visiblity'])."','".filter_sql($departments)."',".intval($myid).",'".filter_sql($UNTRUSTED['ishtml'])."')";	
      $mydatabase->query($query);
   }
   $what = "";
   $quicknote = "";
}

if($UNTRUSTED['what'] == $lang['REMOVE']){
   $query = "DELETE FROM livehelp_quick WHERE id=".intval($UNTRUSTED['editid']);
   $mydatabase->query($query);
}


?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=E0E8F0>
<table width=100%><tr><td align=left>
<?php

$what ="";
if(empty($UNTRUSTED['typeof'])){
 $UNTRUSTED['typeof'] = "";
 print "<h2>" . $lang['txt32'] . "</h2>";
 $what = " typeof!='URL' AND typeof!='IMAGE' ";	
}
if($UNTRUSTED['typeof']=="URL"){
 print "<h2>" . $lang['txt28'] . "</h2>";	
 $what = " typeof='URL' ";	
}
if($UNTRUSTED['typeof']=="IMAGE"){
 print "<h2>" . $lang['txt30'] . "</h2>";	
 $what = " typeof='IMAGE' ";	
}
?>
</td><td align=right>
<a href=javascript:window.close()><?php echo $lang['CLOSE']; ?></a>
</td></tr></table>
<table width=555>
<tr bgcolor=DDDDDD><td><b><?php echo $lang['title']; ?></b></td><td><?php echo $lang['visibility']; ?></td><td><?php echo $lang['options']; ?></td></tr>
<?php

$bgcolor="$color_alt2";
if(empty($UNTRUSTED['action'])){
  $query = "SELECT * FROM livehelp_quick WHERE $what ORDER by name ";
  $result = $mydatabase->query($query);
  $j=0;
  while($row = $result->fetchRow(DB_FETCHMODE_ASSOC)){
   $j++;
   $in= $j + 1;
   if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
    if(note_access($row['visiblity'],$row['department'],$row['user']))
      print "<tr bgcolor=$bgcolor><td><b>".$row['name']."</b></td><td>".$row['visiblity']."</td><td><a href=edit_quick.php?typeof=".$UNTRUSTED['typeof']."&action=edit&id=".$row['id'].">" . $lang['edit'] . "</a></td></tr>\n";	
  
  } 
?>
</table><br>
<a href=edit_quick.php?action=edit&typeof=<?php echo $UNTRUSTED['typeof']; ?> ><b>+<?php echo $lang['CREATE']; ?></b></a>
<SCRIPT type="text/javascript">
window.focus();
</SCRIPT>
<?php
} else {
  if (empty($UNTRUSTED['id'])){ $UNTRUSTED['id'] = 0; }
  $query = "SELECT * FROM livehelp_quick WHERE id=".intval($UNTRUSTED['id']);
  $result = $mydatabase->query($query);
  $result = $result->fetchRow(DB_FETCHMODE_ASSOC);
?>
<table width=100% bgcolor=<?php echo $color_alt1;?>><tr><td><b><?php echo $lang['CREATE']; ?>/<?php echo $lang['EDIT']; ?> :</b></td></tr></table>
<form action=edit_quick.php name=chatter method=post>
<input type=hidden name=typing value="no">
<input type=hidden name=user_id value="<?php echo $myid; ?>">
<input type=hidden name=alt_what value="">
<input type=hidden name=typeof value="<?php echo $UNTRUSTED['typeof']; ?>">
<input type=hidden name=timeof value=<?php echo $timeof; ?> >
<input type=hidden name=editid value="<?php echo $result['id']; ?>">
<b><?php echo $lang['name']; ?>:</b><input type=text name=notename value="<?php echo $result['name']; ?>"><br>
<b><?php echo $lang['permissions']; ?>:</b><select name=visiblity>
<?php if($result['visiblity'] == "Private"){ $is_private = " SELECTED "; } else { $is_private = " "; } ?>
<?php if($result['visiblity'] == "Department"){ $is_department = " SELECTED "; } else { $is_department = " "; } ?>
<?php if($result['visiblity'] == "Everyone"){ $is_global = " SELECTED "; } else { $is_global = " "; } ?>
<option value=Private <?php echo $is_private; ?>><?php echo $lang['private']; ?></option>
<option value=Department<?php echo $is_department; ?>><?php echo $lang['dept']; ?></option>
<option value=Everyone<?php echo $is_global; ?>><?php echo $lang['everyone']; ?></option>
</select><br>
<b><?php echo $lang['dept']; ?>:</b>
<?php
$query = "SELECT * FROM livehelp_departments ";
$data = $mydatabase->query($query);
$haystack = split(",",$result['department']);
while( $row =  $data->fetchRow(DB_FETCHMODE_ASSOC) ){
   $needle = $row['recno'];	 
   if (in_array("$needle", $haystack)) { 
    	 $ischecked = " CHECKED "; 
    }  else { $ischecked = " "; }
   print "<input type=checkbox value=1 name=department_" . $row['recno'] . " $ischecked  > " . $row['nameof'] . " ";
}
?>
<br>
<?php if($UNTRUSTED['typeof'] == ""){ ?>
<?php echo $lang['message']; ?>
<textarea cols=40 rows=2 name=comment><?php echo htmlspecialchars($result['message']); ?></textarea><br>
<input type=checkbox name=ishtml value=YES <?php if($result['ishtml'] == "YES") print " CHECKED "; ?>> - <?php echo $lang['txt68']; ?> <br>
<?php } else { ?>
<b>url:</b><input type=text size=60 name=comment value="<?php echo $result['message']; ?>"><br>
<?php } ?>
<input type=submit name=what value="<?php echo $lang['SAVE']; ?>"><input type=submit name=what value="<?php echo $lang['REMOVE']; ?>">
</form>
<?php }?>
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>