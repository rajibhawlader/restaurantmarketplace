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
$email = $people['email'];

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
 
if($UNTRUSTED['action'] == "update"){
  if($UNTRUSTED['mypassword'] == $UNTRUSTED['mypassword2']){
    $query = "UPDATE livehelp_users 
              SET email='".filter_sql($UNTRUSTED['email']).",
                  username='".filter_sql($UNTRUSTED['myusername'])."',
                  password='".filter_sql($UNTRUSTED['mypassword'])."',
              WHERE sessionid='".$identity['SESSIONID']."'";
    $mydatabase->query($query);
    print "<font color=007700 size=+2>" . $lang['txt63'] .  "</font>";
    $username = $UNTRUSTED['myusername'];
    $pass = $UNTRUSTED['mypassword'];    
  }
}
?>
<body bgcolor=<?php echo $color_background;?>>
<b><?php echo $lang['settings']; ?>:</b>
<br><hr>
<form action=prefer.php method=post>
<input type=hidden name=action value=update>
<table>
<tr><td><b>Username:</b></td><td><input type=text name=myusername value="<?php echo $username; ?>" ></td></tr>
<tr><td><b>email:</b></td><td><input type=text size=50 name=email value="<?php echo $email; ?>" ></td></tr>
<tr><td><b>password:</b></td><td><input type=password name=mypassword value="<?php echo $pass; ?>" ></td></tr>
<tr><td><b>password (again):</b></td><td><input type=password name=mypassword2 value="<?php echo $pass; ?>" ></td></tr>
</table>
<input type=submit value="<?php echo $lang['UPDATE']; ?>">
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>