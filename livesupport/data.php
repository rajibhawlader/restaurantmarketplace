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
require_once("gc.php");

validate_session($identity);

if(empty($UNTRUSTED['tab'])) $UNTRUSTED['tab'] = 0;
 
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<?php

print '<table><tr><td>&nbsp;</td><td>';  
$tabBox = new CTabBox( "?a=b", "", $UNTRUSTED['tab'] );
$tabBox->add('data_transcripts', $lang['transcripts']);
$tabBox->add('data_messages', $lang['messages']);
$tabBox->add('data_referers', $lang['referers']);
$tabBox->add('data_visits', $lang['txt35']);
$tabBox->add('data_paths', "Paths");
$tabBox->add('data_keywords', 'Keywords');
$tabBox->add('data_users', $lang['users']);
if ($isadminsetting=="Y" ) { 
 $tabBox->add('data_clean', $lang['clean']);
}
$tabBox->show();

print '</td></tr></table><br><br>';  

if(!($serversession))
  $mydatabase->close_connect();

?>
<pre>





</pre>
<center>

<font size=-2>

<!-- Note if you remove this line you will be violating the license even if you have modified the program -->

<a href=http://www.craftysyntax.com target=_blank>Crafty Syntax Live Help</a> &copy; 2003 - 2008 by <a href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  

<br>

CSLH is  Software released 

under the <a href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>

</font>
</center>