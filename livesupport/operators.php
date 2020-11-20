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


function whatright($right){
  if($right=="Y") return "<font color=990000><b>Admin</b></font>";
  if($right=="N") return "<font color=009900><b>Normal</b></font>";
  if($right=="R") return "<font color=990099><B>Restricted</b></font>";
  if($right=="L") return "<font color=000099><b>Live-Help-ONLY</b></font>";    
  
   
}

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
if(!(isset($UNTRUSTED['action']))){ $UNTRUSTED['action'] = ""; }
if(!(isset($UNTRUSTED['addmenu']))){ $UNTRUSTED['addmenu'] = ""; }
if(!(isset($UNTRUSTED['editit']))){ $UNTRUSTED['editit'] = ""; }
if(!(isset($UNTRUSTED['remove']))){ $UNTRUSTED['remove'] = ""; }

if(!(isset($UNTRUSTED['isadmin']))){ $UNTRUSTED['isadmin'] = ""; }
if(!(isset($UNTRUSTED['isadmin1']))){ $UNTRUSTED['isadmin1'] = ""; }
if(!(isset($UNTRUSTED['isadmin2']))){ $UNTRUSTED['isadmin2'] = ""; }
if(!(isset($UNTRUSTED['isadmin3']))){ $UNTRUSTED['isadmin3'] = ""; }
 
// Normal and restricted access:
if($UNTRUSTED['isadmin3']=="L") $UNTRUSTED['isadmin'] = "L"; 
if($UNTRUSTED['isadmin2']=="R") $UNTRUSTED['isadmin'] = "R"; 
if($UNTRUSTED['isadmin1']=="N") $UNTRUSTED['isadmin'] = "N"; 
 
if($UNTRUSTED['action'] == "addnew"){

 // just a little error checking..
 $query = "SELECT * FROM livehelp_users WHERE username='".filter_sql($UNTRUSTED['newuser']) . "'";
 $data = $mydatabase->query($query);
 if($data->numrows() != 0){
   print "<font color=990000>" . $lang['error2'];
   exit;
 }
 if(empty($UNTRUSTED['newpass'])){
   print "<font color=990000>" . $lang['error3'];
   exit; 
 }
 
 $md5string = strtolower(MD5(date("MdHis")));
 $query = "INSERT INTO livehelp_users (username,password,isoperator,isadmin,isnamed,email,user_alert,show_arrival,identity,greeting,showtype) 
           VALUES ('".filter_sql($UNTRUSTED['newuser'])."','".filter_sql($UNTRUSTED['newpass'])."','Y','".filter_sql($UNTRUSTED['isadmin'])."','Y','".filter_sql($UNTRUSTED['email'])."','".filter_sql($UNTRUSTED['user_alert'])."','".filter_sql($UNTRUSTED['show_arrival'])."','$md5string','".filter_sql($UNTRUSTED['greeting'])."','".filter_sql($UNTRUSTED['showtyping'])."')";	
 $user_id = $mydatabase->insert($query);

 $query = "UPDATE livehelp_users set onchannel=user_id where isadmin='Y'";	
 $mydatabase->query($query);
  
 $query = "SELECT * FROM livehelp_users ORDER BY user_id DESC LIMIT 1";
 $channel_a = $mydatabase->query($query);
 $channel_a = $channel_a->fetchRow(DB_FETCHMODE_ASSOC);
 $user_id = $channel_a['user_id'];
 $found = 0;
    	
  $query = "SELECT * FROM livehelp_departments";
  $data = $mydatabase->query($query);
  while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
    $varname = "mydepartment_" . $row['recno']; 
    if(isset($UNTRUSTED[$varname])) {  
    	$found = 1;
      $query = "INSERT INTO livehelp_operator_departments (user_id,department) VALUES (".intval($user_id)."," . intval($row['recno']) . ")";
      $mydatabase->query($query);
    }	
  }	
  if($found == 0){
   print "<font color=990000>" . $lang['txt101'] . "</font>";	
  }
}
if(!(empty($UNTRUSTED['remove']))){
  $query = "DELETE FROM livehelp_users WHERE user_id=". intval($UNTRUSTED['remove']);	
  $mydatabase->query($query);
  $query = "DELETE FROM livehelp_operator_departments WHERE user_id=". intval($UNTRUSTED['remove']);	
  $mydatabase->query($query);
}

if($UNTRUSTED['action'] == "updateuser"){
	  if($isadminsetting == "Y") 
	   $isadminsql = " isadmin='".filter_sql($UNTRUSTED['isadmin'])."',";
	  else
  	  $isadminsql = "";

    $query = "UPDATE livehelp_users 
                SET showtype='".filter_sql($UNTRUSTED['showtyping'])."',
                  photo='".filter_sql($UNTRUSTED['photo'])."',
                  greeting='".filter_sql($UNTRUSTED['greeting'])."',
                  user_alert='".filter_sql($UNTRUSTED['user_alert'])."',
                  show_arrival='".filter_sql($UNTRUSTED['show_arrival'])."',
                  username='".filter_sql($UNTRUSTED['newuser'])."',
                  password='".filter_sql($UNTRUSTED['newpass'])."',
                  $isadminsql
                  email='".filter_sql($UNTRUSTED['email'])."' 
                WHERE user_id=". intval($UNTRUSTED['who']);
    $mydatabase->query($query);
    
  
if($isadminsetting == "Y") {
  $query = "DELETE FROM livehelp_operator_departments WHERE user_id=". intval($UNTRUSTED['who']);
  $mydatabase->query($query);
  $query = "SELECT * FROM livehelp_departments";
  $data = $mydatabase->query($query);
  while ($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
    $varname = "mydepartment_" . $row['recno']; 
    if(!(empty($UNTRUSTED[$varname]))) {  
        $query = "INSERT INTO livehelp_operator_departments (user_id,department) 
                  VALUES (".intval($UNTRUSTED['who'])."," . intval($row['recno']) . ")";	
        $mydatabase->query($query);
    }	
  }
 } 
  if(!($serversession))
    $mydatabase->close_connect();
    sleep(1);
    if($isadminsetting == "Y"){
      Header("Location: operators.php");
      exit;  
    } else {
    	print "<font color=009900 size=+2>".$lang['txt63']."</font>";
    	exit;
    }
}
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=<?php echo $color_background;?>><center>
<SCRIPT type="text/javascript" SRC="javascript/hideshow.js"></SCRIPT>
<SCRIPT type="text/javascript">
function showhelp(){
	makeVisible ('accessptions');
}
function hidehelp(){
	makeInvisible ('accessptions');
}
	function uncheck(whichone){
		if (whichone==1){
		   document.operatorform.isadmin1.checked = false;
		   document.operatorform.isadmin2.checked = false;
		   document.operatorform.isadmin3.checked = false;
	  } 
		if (whichone==2){
		   document.operatorform.isadmin.checked = false;
		   document.operatorform.isadmin2.checked = false;
		   document.operatorform.isadmin3.checked = false;		   
	  } 
		if (whichone==3){
		   document.operatorform.isadmin1.checked = false;
		   document.operatorform.isadmin.checked = false;
		   document.operatorform.isadmin3.checked = false;		   
	  } 	
		if (whichone==4){
		   document.operatorform.isadmin1.checked = false;
		   document.operatorform.isadmin.checked = false;
		   document.operatorform.isadmin2.checked = false;		   
	  } 	  		
	} 
	
</SCRIPT>	
		
<?php if ( ($UNTRUSTED['addmenu'] != "Y" ) && ($UNTRUSTED['editit'] == "") ){ ?>
<table border=0>
<tr bgcolor=FFFFFF><td><b><?php echo $lang['txt184']; ?></b></td><td><b><?php echo $lang['dept']; ?></b></td><td><b>Rights</b></td><td><b>e-mail</b></td><td><b><?php echo $lang['options']; ?></b></td></tr>
<?php 
$query = "SELECT * FROM livehelp_users WHERE isoperator='Y' ";
$data = $mydatabase->query($query);
while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
   if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
 print "<tr bgcolor=$bgcolor><td>" . $row['username'] . "</td><td>";
 $query = "SELECT * 
           FROM livehelp_users,livehelp_operator_departments 
           WHERE livehelp_users.user_id=livehelp_operator_departments.user_id 
             AND livehelp_users.user_id=" . intval($row['user_id']);
 $data2 = $mydatabase->query($query);
 while($dept = $data2->fetchRow(DB_FETCHMODE_ASSOC)){	
  $query = "SELECT * 
            FROM livehelp_departments 
            WHERE recno=" . intval($dept['department']);
  $data3 = $mydatabase->query($query);
  $data3 =  $data3->fetchRow(DB_FETCHMODE_ASSOC); 
  print $data3['nameof'] . "<br>";
 }
 print "</td><td>" . whatright($row['isadmin']) . "</td><td>" . $row['email'] . "</td><td>";
 if( ($isadminsetting == "Y") || ($myid == $row['user_id']) ) { print "<a href=operators.php?editit=" . $row['user_id'] . ">".$lang['EDIT']."</a> "; }
 if( ($isadminsetting == "Y") && ($row['user_id'] != $myid) ){ print "<a href=operators.php?remove=" . $row['user_id'] . " onClick=\"return confirm('".str_replace("'","\\'",$lang['areyousure'])."')\"><font color=990000>".$lang['REMOVE']."</font> "; }
 print "</a>  ";
 if( ($isadminsetting == "Y") || ($myid == $row['user_id']) ) { print "  <a href=operators_history.php?user_id=" . $row['user_id'] . ">".$lang['HISTORY']."</a> "; }

 print "</td></tr>\n"; 	
}
?>
</table>
<?php 
if($isadminsetting == "Y"){	
  print "<br><a href=operators.php?addmenu=Y><font size=+1>" . $lang['CREATE'] . "  " . $lang['operators'] . "</font></a>";
 }
}
if( ( ($isadminsetting == "Y") || ($UNTRUSTED['editit']==$myid) ) && ( ($UNTRUSTED['addmenu'] == "Y" ) || (!(empty($UNTRUSTED['editit']))) ) ){ 
?>
<br><table width=600><tr><td>
<form action=operators.php name=operatorform method=post>
<?php 
if(!(empty($UNTRUSTED['editit']))){
 $query = "SELECT * FROM livehelp_users where user_id=". intval($UNTRUSTED['editit']);
 $data = $mydatabase->query($query);
 $userinfo = $data->fetchRow(DB_FETCHMODE_ASSOC);	
?>
<input type=hidden name=action value=updateuser>
<input type=hidden name=who value=<?php echo $UNTRUSTED['editit']; ?> >
<table bgcolor=<?php echo $color_alt1;?>>
<tr><td bgcolor=FFFFFF colspan=2><?php echo $lang['UPDATE']; ?> <?php echo $lang['operators']; ?>  </td></tr>
<?php } else { ?>
<input type=hidden name=action value=addnew>
<table bgcolor=<?php echo $color_alt1;?>>
<tr><td bgcolor=FFFFFF colspan=2><?php echo $lang['CREATE']; ?> <?php echo $lang['operators']; ?></td></tr>
<?php 
$userinfo['show_arrival'] = "N";
$userinfo['user_alert'] = "Y";
$userinfo['isadmin'] = "N";
$userinfo['username'] = "";
$userinfo['password'] = "";
$userinfo['email'] = "";
$userinfo['user_id'] = 0;
$userinfo['greeting'] = "How May I help You?";
$userinfo['photo'] = "";
$userinfo['showtype'] = "";


} ?>
<tr><td>username:</td><td><input type=text name=newuser size=30 value="<?php echo $userinfo['username']; ?>"></td></tr>
<tr><td>password:</td><td><input type=password name=newpass size=30 value="<?php echo $userinfo['password']; ?>"></td></tr>
<tr><td>email:</td><td><input type=text name=email size=30 value="<?php echo $userinfo['email']; ?>"></td></tr>
<?php
if ($userinfo['show_arrival'] == "N"){ $show_arrival_n = " CHECKED "; $show_arrival_y=""; } else { $show_arrival_n=""; $show_arrival_y = " CHECKED "; }
if ($userinfo['user_alert'] == "Y"){ $user_alert_y = " CHECKED "; $user_alert_n ="";  }
if ($userinfo['user_alert'] == "N"){ $user_alert_n = " CHECKED "; $user_alert_y =""; }
if ($userinfo['user_alert'] == "E"){ $user_alert_e = " CHECKED "; $user_alert_e = ""; }
  
  $isadmin_y = "";  
  $isadmin_n = ""; 
  $isadmin_r = "";   
  $isadmin_l = ""; 
   
if ($userinfo['isadmin'] == "Y"){ $isadmin_y = " CHECKED "; }
if ($userinfo['isadmin'] == "N"){ $isadmin_n = " CHECKED "; }
if ($userinfo['isadmin'] == "R"){ $isadmin_r = " CHECKED "; }
if ($userinfo['isadmin'] == "L"){ $isadmin_l = " CHECKED "; }

$previewsetting1 = "";
$previewsetting2 = "";
$previewsetting3 = "";
$previewsetting4 = "";
if(!(empty($userinfo['showtype']))){
	 if($userinfo['showtype'] == 1)  $previewsetting1 = " SELECTED ";
	 if($userinfo['showtype'] == 2)  $previewsetting2 = " SELECTED ";
	 if($userinfo['showtype'] == 3)  $previewsetting3 = " SELECTED ";
	 if($userinfo['showtype'] == 4)  $previewsetting4 = " SELECTED ";	 	 
}

?>
<tr><td><?php echo $lang['txt135']?>:</td><td><input type=radio name=show_arrival value=Y <?php echo $show_arrival_y; ?> ><?php echo $lang['YES'];?> <input type=radio name=show_arrival value=N <?php echo $show_arrival_n; ?>><?php echo $lang['NO'];?></td></tr>
<tr><td><?php echo $lang['txt136']?>:</td><td><input type=radio name=user_alert value=Y <?php echo $user_alert_y; ?> > JS alert <input type=radio name=user_alert value=N <?php echo $user_alert_n; ?> > EMBED wav sound</td></tr>
<tr><td><?php echo $lang['txt156']?>:</td><td>

<select name=showtyping>
	<option value=1 <?php echo $previewsetting1 ?> ><?php echo $lang['txt221']; ?></option>
	<option value=2 <?php echo $previewsetting2 ?> ><?php echo $lang['txt222']; ?></option>
	<option value=3 <?php echo $previewsetting3 ?> ><?php echo $lang['txt223']; ?></option>
	<option value=4 <?php echo $previewsetting4 ?> ><?php echo $lang['txt224']; ?></option>
</select><br>
 
  </td></tr>

<?php if($isadminsetting == "Y"){ ?>
<tr>	
	<td align=center colspan=2>
 <br><br>
		<table><tr>
	  <td><b><font size=+1>Access Rights:</font></b><br>
	  	<a href=javascript:showhelp()><font size=-1>Help on Access Rights</font></a></td>
		<td><font color=990000><b>Admin</b><br></font>: <input type=checkbox name=isadmin value=Y <?php echo $isadmin_y; ?> onclick="uncheck(1)" ></td>
	  <td>&nbsp;&nbsp;</td>
		<td><font color=009900><b>Normal</b><br></font>: <input type=checkbox name=isadmin1 value=N <?php echo $isadmin_n; ?> onclick="uncheck(2)" ></td>
	  <td>&nbsp;&nbsp;</td>
		<td><font color=990099><b>Restricted</b><br></font>: <input type=checkbox name=isadmin2 value=R <?php echo $isadmin_r; ?> onclick="uncheck(3)" ></td>
	  <td>&nbsp;&nbsp;</td>
		<td><font color=000099><b>Live-Help-ONLY</b><br></font>: <input type=checkbox name=isadmin3 value=L <?php echo $isadmin_l; ?> onclick="uncheck(4)" ></td>
    </tr></table>			
			
			<DIV ID="accessptions" STYLE="display:none">
<table border=1 STYLE="border-style: dashed">
<tr><td> 
	<center> ~~ <a href="javascript:hidehelp()">Hide Help on Access Rights</a> ~~ </center> <br>
	<font color=990000><b>Admin</b><br></font>: Users with this Right have access to everything.<br><br>
	<font color=009900><b>Normal</b><br></font>: Users with this Right can NOT add/edit or delete Operators, Departments or Modules and can not access the Settings tab. They also can not access the "clean up" option in the data tab.<br><br>
	<font color=990099><b>Restricted</b><br></font>: Users with this Right can NOT access anything other then Live Help and the data tab. They also can not access the "clean up" option in the data tab.<br><br>
	<font color=000099><b>Live-Help-ONLY</b><br></font>: Users with this Right can NOT access anything except the Live Help tab and are re-directed to that tab upon login. <br><br>
	<center> ~~ <a href="javascript:hidehelp()">Hide Help on Access Rights</a> ~~ </center> <br>
	</td></tr>
</table>
</DIV>

		</td></tr>

<tr><td colspan=2><br><font size=+1><b><?php echo $lang['dept']; ?></font></b><ul>
<?php
$query = "SELECT * FROM livehelp_departments";
$data = $mydatabase->query($query);
while($dept = $data->fetchRow(DB_FETCHMODE_ASSOC)){
  $query = "SELECT * 
            FROM livehelp_operator_departments 
            WHERE user_id=" . intval($userinfo['user_id']) . " AND department=" . intval($dept['recno']);
  $check = $mydatabase->query($query);
  if( $check->numrows() == 0){ $checked =""; } else { $checked= " CHECKED "; }
  if($isadminsetting == "Y"){
    print "<input type=checkbox name=mydepartment_" . $dept['recno'] . " value=" . $dept['recno'] . " $checked><b> " . $dept['nameof'] . " </b><br>";
  } else {
    if($checked== " CHECKED ")
     print "<input type=checkbox name=mydepartment_" . $dept['recno'] . " value=" . $dept['recno'] . " $checked><b> " . $dept['nameof'] . " </b><br>";     
    else
    print "[N/A] <b> " . $dept['nameof'] . " </b><br>";    
  }   
 }
 print "</td></tr>";
}

?><tr><td colspan=2><br><br>
<table><tr><td>
<b>Photo:</b><br>
<?php 
  if (empty($userinfo['photo'])) 
    print "No Photo<br>";
  else
    print "<img src=". $userinfo['photo'] .">";
?>
</td><td>
<b>Greeting:</B><br>
<textarea cols=25 rows=6 name=greeting><?php echo htmlspecialchars($userinfo['greeting']); ?></textarea><br>
<input type=checkbox name=cleargreeting onclick="javascript:document.operatorform.greeting.value=''">Clear Greeting (No Greeting message)<br>
</td></tr>
<tr><td colspan=2>
<br>Image URL:<input type=text size=50 name=photo value=<?php echo $userinfo['photo']; ?> ><br>
</td></tr>
</table>

<input type=submit value="<?php echo $lang['SAVE']; ?>"><br>

</td></tr>
</table> 

</td></tr></table><br>
</form>
<?php
}
if(!($serversession))
$mydatabase->close_connect();
?>
<pre>


</pre>
<font size=-2>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
<a href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help</a> &copy; 2003 - 2008 by <a href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>
</font>