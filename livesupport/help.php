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
?>
<body bgcolor=<?php echo $color_background;?> marginheight=0 marginwidth=0 topmargin=0 leftmargin=0 bottommargin=0>
<center>
<SCRIPT type="text/javascript">
// onmouseovers
r_about = new Image;
h_about = new Image;
r_about.src = 'images/admin_arr.gif';
h_about.src = 'images/blank.gif';

function q_a(topic){
 url = 'http://www.craftysyntax.com/lh/cslh.php?m=knowlegebase&a=index'
 window.open(url, 'chat540872', 'width=540,height=390,menubar=no,scrollbars=0,resizable=1');
}

</SCRIPT>
<table border=0 cellpadding=0 cellspacing=0 width=580 bgcolor=<?php echo $color_background;?>><tr><td>
Your Are Here: <b><a href=scratch.php>Overview Page</a> :: Help Page</b><br>
</td></tr></table>
<table border=0 cellpadding=0 cellspacing=0 width=580>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
<tr><td height=1 bgcolor=<?php echo $color_alt2; ?>> <b>CSLH! Help Page:</b></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>

<tr><td bgcolor=<?php echo $color_alt1;?> > <img src=images/blank.gif width=22 height=21 name=one3><a href=http://www.craftysyntax.com/docs/ > Documentation Pages</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt1;?> > <img src=images/blank.gif width=22 height=21 name=one5><a href=http://www.craftysyntax.com/howto.php>Getting Started.(How to setup and use CSLH)</a></td></tr>
<tr><td bgcolor=<?php echo $color_alt1;?> ><img src=images/blank.gif width=22 height=21 name=one1><a href=http://www.craftysyntax.com/help/ target=_blank>Crafty Syntax Support Message Board</a></td></tr>		
<tr><td bgcolor=<?php echo $color_alt3; ?>><img src=images/blank.gif width=22 height=21 name=i2a><a href=http://www.craftysyntax.com/remote/updates.php?direct=support&v=<?php echo $CSLH_Config['version']; ?>&d=<?php echo $CSLH_Config['directoryid'] ?>&h=<?php echo $_SERVER['HTTP_HOST']; ?> target=_blank>News and Updates</a></td></tr>	
<tr><td bgcolor=<?php echo $color_alt1;?>><br></td></tr>
<tr><td height=1 bgcolor=000000><img src=images/blank.gif width=10 height=1 border=0></td></tr>
</table>
<br><br>
<font size=-2>
<a href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help</a> &copy; 2003 - 2008 by <a href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  
CSLH is  Software released 
under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a> 
</font><br><br><br><br><br><br>
</body>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>