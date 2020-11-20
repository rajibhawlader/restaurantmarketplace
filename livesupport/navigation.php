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
 
$colorfile = "images/".$CSLH_Config['colorscheme']."/adminstyle.css";
?>
<html>
<head>
<link rel="stylesheet" href="<?php echo $colorfile; ?>" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" > 
<SCRIPT type="text/javascript" >
r_arrow = new Image;
h_arrow = new Image;
r_arrow.src = 'images/arrow_off.gif';
h_arrow.src = 'images/arrow_on.gif';

function openwindow(url){ 
 window.open(url, 'chat54057', 'width=572,height=320,menubar=no,scrollbars=1,resizable=1');
}
</SCRIPT>
</head>
<body>
<center>
 
<table cellpadding="0" cellspacing="0" border="0" width="100%">
 <tr><td align=center><br><b>Crafty Syntax Live Help</b><br><font color=000066> Version <b><?php echo $CSLH_Config['version'];?></b></font></td></tr>
</table>
<br/>

<DIV STYLE="border: 1px #08245B dotted;width:192px;">
	 <table cellpadding="0" cellspacing="0" border="0" width="190">
 <tr><td class="sectionheader" align="left"><b><?php echo $lang['general']; ?>:</b></td></tr> 
 <tr><td class="leftnavlink"><a href="http://www.craftysyntax.com/remote/docs/" onmouseout="document.arrows1.src=r_arrow.src"  onmouseover="document.arrows1.src=h_arrow.src" target=_blank><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows1"><?php echo $lang['documentation']; ?></a></td></tr>
<?php if ($isadminsetting=="Y" ) { ?>
  <tr><td class="leftnavlink"><a href="admin.php?page=mastersettings.php&tab=settings" onmouseout="document.arrows2.src=r_arrow.src"  onmouseover="document.arrows2.src=h_arrow.src" target=_top><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows2"><?php echo $lang['txt90']; ?></a></td></tr>
<?php } ?>
 <tr><td class="leftnavlink"><a href="admin.php?page=help.php" onmouseout="document.arrows3.src=r_arrow.src"  onmouseover="document.arrows3.src=h_arrow.src" target=_top><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows3"><?php echo $lang['txt88']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="http://security.craftysyntax.com/updates/?direct=support&v=<?php echo $CSLH_Config['version']; ?>&d=<?php echo $CSLH_Config['directoryid'] ?>&h=<?php echo $_SERVER['HTTP_HOST']; ?>" target=_blank onmouseout="document.arrows4.src=r_arrow.src"  onmouseover="document.arrows4.src=h_arrow.src" target=_top><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows4">Message Board</a></td></tr>
 <tr><td class="leftnavlink"><a href="registerit.php" target="contents" onmouseout="document.arrows5.src=r_arrow.src"  onmouseover="document.arrows5.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows5">Security Registration</a></td></tr> 	
 <tr><td class="leftnavlink"><a href="http://www.craftysyntax.com/members/?d=<?php echo $CSLH_Config['directoryid'] ?>" target="contents" onmouseout="document.arrows5a.src=r_arrow.src"  onmouseover="document.arrows5a.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows5a">Member Services</a></td></tr> 	

</table>
</DIV>
<br/>

<DIV STYLE="border: 1px #08245B dotted;width:192px;">
	 <table cellpadding="0" cellspacing="0" border="0" width="190">
 <tr><td class="sectionheader" align="left"><b><?php echo $lang['livehelp']; ?>:</b></td></tr> 
 <tr><td class="leftnavlink"><a href="live.php" onmouseout="document.arrows6.src=r_arrow.src"  onmouseover="document.arrows6.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows6"><?php echo $lang['txt91']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="edit_quick.php" onmouseout="document.arrows7.src=r_arrow.src"  onmouseover="document.arrows7.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows7"><?php echo $lang['txt32']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="edit_quick.php?typeof=IMAGE" onmouseout="document.arrows8.src=r_arrow.src"  onmouseover="document.arrows8.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows8"><?php echo $lang['txt30']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="edit_quick.php?typeof=URL" onmouseout="document.arrows9.src=r_arrow.src"  onmouseover="document.arrows9.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows9"><?php echo $lang['txt28']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="admin.php?page=edit_smile.php&tab=settings" onmouseout="document.arrows10.src=r_arrow.src"  onmouseover="document.arrows10.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows10">Emotion Icons</a></td></tr>
 <tr><td class="leftnavlink"><a href="admin.php?page=edit_layer.php&tab=settings" onmouseout="document.arrows11.src=r_arrow.src"  onmouseover="document.arrows11.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows11">Edit Layer Images</a></td></tr>
 <tr><td class="leftnavlink"><a href="http://craftysyntax.com/winapp.php" onmouseout="document.arrows12.src=r_arrow.src"  onmouseover="document.arrows12.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows12">Windows Application</a></td></tr>
 <tr><td class="leftnavlink"><a href="autoinvite.php" onmouseout="document.arrows12a.src=r_arrow.src"  onmouseover="document.arrows12a.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows12a"><?php echo $lang['txt132']; ?></a></td></tr>
 
</table>
</DIV>
<br/>

<DIV STYLE="border: 1px #08245B dotted;width:192px;">
	 <table cellpadding="0" cellspacing="0" border="0" width="190">
 <tr><td class="sectionheader" align="left"><b><?php echo $lang['operators']; ?>:</b></td></tr> 
 <tr><td class="leftnavlink"><a href="operators.php?editit=<?php echo $myid ?>" onmouseout="document.arrows13.src=r_arrow.src"  onmouseover="document.arrows13.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows13"><?php echo $lang['EDIT']; ?> Your account</a></td></tr>
<?php if ($isadminsetting=="Y" ) { ?>
  <tr><td class="leftnavlink"><a href="admin.php?page=operators.php&tab=oper" onmouseout="document.arrows14.src=r_arrow.src"  onmouseover="document.arrows14.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows14"><?php echo $lang['CREATE']; ?>/<?php echo $lang['EDIT']; ?>/<?php echo $lang['DELETE']; ?></a></td></tr>
<?php } ?>
</table>
</DIV>
<br/>

<?php if( ($isadminsetting == "Y") || ($isadminsetting == "N") || ($isadminsetting == "R") ){ ?>

<?php if ($isadminsetting != "R") {  ?>
<DIV STYLE="border: 1px #08245B dotted;width:192px;">
	 <table cellpadding="0" cellspacing="0" border="0" width="190">
 <tr><td class="sectionheader" align="left"><b><?php echo $lang['dept']; ?>:</b></td></tr> 
 <tr><td class="leftnavlink"><a href="admin.php?page=departments.php&tab=dept&help=1" onmouseout="document.arrows15.src=r_arrow.src"  onmouseover="document.arrows15.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows15">HTML CODE for <?php echo $lang['dept']; ?></a></td></tr>
 <?php if ($isadminsetting=="Y" ) { ?>
  <tr><td class="leftnavlink"><a href="admin.php?page=departments.php&tab=dept" onmouseout="document.arrows16.src=r_arrow.src"  onmouseover="document.arrows16.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows16"><?php echo $lang['CREATE']; ?>/<?php echo $lang['EDIT']; ?>/<?php echo $lang['DELETE']; ?></a></td></tr> 
 <?php } ?>
</table>
</DIV>
<br/>
<?php } ?>

<DIV STYLE="border: 1px #08245B dotted;width:192px;">
	 <table cellpadding="0" cellspacing="0" border="0" width="190">
 <tr><td class="sectionheader" align="left"><b><?php echo $lang['data']; ?>:</b></td></tr> 
 <tr><td class="leftnavlink"><a href="admin.php?page=data.php&tab=data&alttab=0" onmouseout="document.arrows17.src=r_arrow.src"  onmouseover="document.arrows17.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows17"><?php echo $lang['txt100']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="admin.php?page=data.php&tab=data&alttab=1" onmouseout="document.arrows18.src=r_arrow.src"  onmouseover="document.arrows18.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows18">Messages</a></td></tr>
 <tr><td class="leftnavlink"><a href="admin.php?page=data.php&tab=data&alttab=2" onmouseout="document.arrows19.src=r_arrow.src"  onmouseover="document.arrows19.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows19"><?php echo $lang['txt98']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="admin.php?page=data.php&tab=data&alttab=3" onmouseout="document.arrows20.src=r_arrow.src"  onmouseover="document.arrows20.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows20"><?php echo $lang['txt99']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="admin.php?page=data.php&tab=data&alttab=4" onmouseout="document.arrows21.src=r_arrow.src"  onmouseover="document.arrows21.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows21">Paths</a></td></tr>
 <tr><td class="leftnavlink"><a href="admin.php?page=data.php&tab=data&alttab=5" onmouseout="document.arrows22.src=r_arrow.src"  onmouseover="document.arrows22.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows22"><?php echo $lang['keywords']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="admin.php?page=data.php&tab=data&alttab=6" onmouseout="document.arrows23.src=r_arrow.src"  onmouseover="document.arrows23.src=h_arrow.src" target="_top"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows23"><?php echo $lang['users']; ?></a></td></tr>    
</table>
</DIV>
<br/>
<?php } ?>


<DIV STYLE="border: 1px #08245B dotted;width:192px;">
	 <table cellpadding="0" cellspacing="0" border="0" width="190">
 <tr><td class="sectionheader" align="left"><b>Added Modules :</b></td></tr> 
 <tr><td class="leftnavlink"><a href="qa.php" onmouseout="document.arrows24.src=r_arrow.src"  onmouseover="document.arrows24.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows24">Questions & Answers</a></td></tr>
</table>
</DIV>
<br/> 
 
<DIV STYLE="border: 1px #08245B dotted;width:192px;">
	 <table cellpadding="0" cellspacing="0" border="0" width="190">
 <tr><td class="sectionheader" align="left"><b>Extras :</b></td></tr> 
 <?php if($CSLH_Config['showgames']=="Y"){ ?>
 <tr><td class="leftnavlink"><a href="http://games.craftysyntax.com/" onmouseout="document.arrows25.src=r_arrow.src"  onmouseover="document.arrows25.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows25">View Games</a></td></tr>
<?php } ?>
 <?php if($CSLH_Config['showdirectory']=="Y"){ ?> 
  <tr><td class="leftnavlink"><a href="http://www.craftysyntax.com/directory/" onmouseout="document.arrows26.src=r_arrow.src"  onmouseover="document.arrows26.src=h_arrow.src" target="_blank"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows26">View Directory</a></td></tr>
<?php } ?>
</table>
</DIV>
<br/> 

<DIV STYLE="border: 1px #08245B dotted;width:192px;">
	 <table cellpadding="0" cellspacing="0" border="0" width="190">
 <tr><td class="sectionheader" align="left"><b>Additional Information :</b></td></tr> 
 <tr><td class="leftnavlink"><a href="http://www.craftysyntax.com/remote/updates.php#donate" onmouseout="document.arrows27.src=r_arrow.src"  onmouseover="document.arrows27.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows27">Donations to the project</a></td></tr>
 <tr><td class="leftnavlink"><a href="http://www.craftysyntax.com/remote/updates.php" onmouseout="document.arrows28.src=r_arrow.src"  onmouseover="document.arrows28.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows28"><?php echo $lang['txt89']; ?></a></td></tr>
 <tr><td class="leftnavlink"><a href="README_FILES/CHANGELOG.txt" onmouseout="document.arrows29.src=r_arrow.src"  onmouseover="document.arrows29.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows29">Change Log (version <?php echo $CSLH_Config['version']; ?>)</a></td></tr>
 <tr><td class="leftnavlink"><a href="http://www.craftysyntax.com/remote/bugs.php" onmouseout="document.arrows30.src=r_arrow.src"  onmouseover="document.arrows30.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows30">Bugs/Security Holes</a></td></tr>
 <tr><td class="leftnavlink"><a href="http://www.craftysyntax.com/remote/version3.php" onmouseout="document.arrows31.src=r_arrow.src"  onmouseover="document.arrows31.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows31">Version 3.x.x</a></td></tr>
 <tr><td class="leftnavlink"><a href="http://www.craftysyntax.com/projects/" onmouseout="document.arrows32.src=r_arrow.src"  onmouseover="document.arrows32.src=h_arrow.src" target="contents"><img src=images/arrow_off.gif width="20" height="12" border="0" name="arrows32">Additional Programs</a></td></tr>
 
</table>
</DIV>
<br/> <br/> 
 
<font size=-2><center>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
<a class="adminnavleft" href=http://www.craftysyntax.com/ target=_blank>Crafty Syntax Live Help</a><br> &copy; 2003 - 2008  <a class="adminnavleft" href=http://www.craftysyntax.com/EricGerdes/ target=_blank>Eric Gerdes</a>  
<br>
CSLH is  Software released 
under the <br><a class="adminnavleft" href=http://www.gnu.org/copyleft/gpl.html target=_blank>GNU/GPL license</a>
</font></center>
</font><br><br><br>
</body>
</html>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>