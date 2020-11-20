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

// this file is only called as an include in a "tab" 
// everything else is a hack:
if (!(defined('IS_SECURE'))){
	print "Hacking attempt . Exiting..";
	exit;
}

// Need these because this is included inside a function where globals are
// out of the scope.
Global $CSLH_Config,$mydatabase,$UNTRUSTED,$dbtype,$lang,$color_alt2,$color_alt1,$identity;

if(empty($UNTRUSTED['show'])){ $UNTRUSTED['show']= "";}
if(empty($UNTRUSTED['view'])){ $UNTRUSTED['view']= "";}
if(empty($UNTRUSTED['year'])){ $UNTRUSTED['year']= date("Y"); }
if(empty($UNTRUSTED['month'])){ $UNTRUSTED['month']= date("m"); }
if(empty($UNTRUSTED['expand'])){ $UNTRUSTED['expand'] = 0; }
if(empty($UNTRUSTED['top'])){ $UNTRUSTED['top'] = 0; }
if(empty($UNTRUSTED['perpage'])){ $UNTRUSTED['perpage'] = 25; } 


$month = sprintf("%02d",intval($UNTRUSTED['month']));
$year = sprintf("%04d",intval($UNTRUSTED['year']));

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
$expand_array = split(",",$UNTRUSTED['expand']);

// Select out the current months data:
$whatYm = $UNTRUSTED['year'] . $UNTRUSTED['month'];


if($CSLH_Config['keywordtrack']!="Y"){ 
    print "&nbsp;<br>"   ."<table bgcolor=FFFFEE width=500><tr><td colspan=3 align=center><font color=990000><b>".$lang['txt217b']."</b></font><br> <a href=admin.php?page=mastersettings.php&tab=settings  target=_top>Click here to Enable in settings</a></td></tr></table>";       	
} else { 
	
// if no admin rights then user can not clear or remove data: 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$data = $mydatabase->query($query);
$row = $data->fetchRow(DB_FETCHMODE_ASSOC);
$isadminsetting = $row['isadmin'];


 
?>
<FORM action=data.php METHOD=POST name=dataformrefer>
<input type=hidden name=tab value=<?php echo $UNTRUSTED['tab']; ?>>
<table bgcolor=DDDDDD width=600><tr><td>
<b><?php echo $lang['month']; ?></b><select name=month onchange=document.dataformrefer.submit()>
<option value=01 <?php if($UNTRUSTED['month'] == "01"){ print " SELECTED "; } ?>><?php echo $lang['jan']; ?></option>
<option value=02 <?php if($UNTRUSTED['month'] == "02"){ print " SELECTED "; } ?>><?php echo $lang['feb']; ?></option>
<option value=03 <?php if($UNTRUSTED['month'] == "03"){ print " SELECTED "; } ?>><?php echo $lang['mar']; ?></option>
<option value=04 <?php if($UNTRUSTED['month'] == "04"){ print " SELECTED "; } ?>><?php echo $lang['apr']; ?></option>
<option value=05 <?php if($UNTRUSTED['month'] == "05"){ print " SELECTED "; } ?>><?php echo $lang['may']; ?></option>
<option value=06 <?php if($UNTRUSTED['month'] == "06"){ print " SELECTED "; } ?>><?php echo $lang['jun']; ?></option>
<option value=07 <?php if($UNTRUSTED['month'] == "07"){ print " SELECTED "; } ?>><?php echo $lang['jul']; ?></option>
<option value=08 <?php if($UNTRUSTED['month'] == "08"){ print " SELECTED "; } ?>><?php echo $lang['aug']; ?></option>
<option value=09 <?php if($UNTRUSTED['month'] == "09"){ print " SELECTED "; } ?>><?php echo $lang['sep']; ?></option>
<option value=10 <?php if($UNTRUSTED['month'] == "10"){ print " SELECTED "; } ?>><?php echo $lang['oct']; ?></option>
<option value=11 <?php if($UNTRUSTED['month'] == "11"){ print " SELECTED "; } ?>><?php echo $lang['nov']; ?></option>
<option value=12 <?php if($UNTRUSTED['month'] == "12"){ print " SELECTED "; } ?>><?php echo $lang['dec']; ?></option>

</select> <b><?php echo $lang['year']; ?></b><select name=year onchange=document.dataformrefer.submit()>
<?php
for($i=2004; $i<=date("Y"); $i++){
  print "<option value=$i ";
  if($UNTRUSTED['year'] == $i){ print " SELECTED "; } 
  print ">$i</option>";
}
?>   
</select> </td> </td>
<td>&nbsp;</td>
<td><b>Search:</b></td>
	<td><input type=text name=keywords size=15 value="<?php if(!(empty($UNTRUSTED['keywords']))) echo $UNTRUSTED['keywords']; ?>" ><a href=javascript:document.dataformrefer.submit()><img src=images/go.gif width=20 height=20 border=0></a></td>
</tr>
	
</table>

</FORM>
<table bgcolor=DDDDDD width=600><tr><td>
<b>Key Words:</b></td></tr></table>
  
<?php  
 
 $keywordssql = "";
 if(!(empty($UNTRUSTED['keywords']))){
    $keywordssql = " AND keywords LIKE '%". filter_sql($UNTRUSTED['keywords']) . "%' ";
 } 
 
 
 $query = "SELECT * FROM livehelp_keywords_monthly WHERE dateof=$whatYm $keywordssql order by levelvisits DESC"; 
 $refer_a = $mydatabase->query($query);
 $total_p = $refer_a->numrows();
 $perpage = intval($UNTRUSTED['perpage']);
 $top = intval($UNTRUSTED['top']);
 $show = $UNTRUSTED['show'];
   $pageUrl = "data.php";
  $varstring = "&tab=".$UNTRUSTED['tab']."&month=".$UNTRUSTED['month'] . "&year=" . $UNTRUSTED['year'];
  if(empty($UNTRUSTED['perpage'])) $UNTRUSTED['perpage'] = 25;
  if(empty($UNTRUSTED['offset'])) $UNTRUSTED['offset'] = 0;
 
  print  pagingLinks($pageUrl, $total_p, $varstring,$UNTRUSTED['offset'],$UNTRUSTED['perpage']);

?>
<table width=600>
<tr bgcolor=FFFFFF>
	 <td>&nbsp;</td>
	 <td><b>Keyword</b></td>
	 <td><b>Visits</b></td>
	 </tr>
<?php

 $query = "SELECT * FROM livehelp_keywords_monthly WHERE dateof=$whatYm $keywordssql order by levelvisits DESC  LIMIT ". intval($UNTRUSTED['offset']) ."," . intval($perpage);
$data = $mydatabase->query($query);
 
 $bgcolor="$color_alt2";
 while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){ 
  if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
   print "<tr bgcolor=$bgcolor>";
  // print "<td><a href=data.php?&tab=".$UNTRUSTED['tab']."&typeofview=levelvisits&$leveltop=".$UNTRUSTED[$leveltop]."&show=".$UNTRUSTED['show']."&expand=" . $UNTRUSTED['expand'] . "," . $row['recno'] . $parentstring . "><img src=images/plus.gif border=0></a></td>";
   print "<td>&nbsp;</td>";   
   print "<td>". $row['keywords'] ."</td>"; 
   print "<td>". $row['levelvisits'] ."</td>";       
   print "</tr>";   
 } 
 
 ?>
</table>
<?php } ?>