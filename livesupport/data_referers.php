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
// this file is only called as an include.. everything else is a hack:
if (!(defined('IS_SECURE'))){
	print "Hacking attempt . Exiting..";
	exit;
}

Global $CSLH_Config,$mydatabase,$UNTRUSTED,$dbtype,$lang,$color_alt2,$color_alt1,$identity;
  
if(empty($UNTRUSTED['show'])){ $UNTRUSTED['show']= "";}
if(empty($UNTRUSTED['view'])){ $UNTRUSTED['view']= "";}
if(empty($UNTRUSTED['year'])){ $UNTRUSTED['year']= date("Y"); }
if(empty($UNTRUSTED['month'])){ $UNTRUSTED['month']= date("m"); }
if(empty($UNTRUSTED['expand'])){ $UNTRUSTED['expand'] = 0; }
if(empty($UNTRUSTED['top'])){ $UNTRUSTED['top'] = 0; }
if(empty($UNTRUSTED['perpage'])){ $UNTRUSTED['perpage'] = 25; } 

// removes a referer and its children.
if(!(empty($UNTRUSTED['removereferer']))){ 
	$rec = intval($UNTRUSTED['removereferer']);
	$graph = array();
	$graph[] = $rec;
	recursive_delete_pages($rec,'livehelp_referers_monthly',$graph);
	print "<font color=990000>$deletedsofar removed...</font><br>";
}
 
$month = sprintf("%02d",intval($UNTRUSTED['month']));
$year = sprintf("%04d",intval($UNTRUSTED['year']));

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
$expand_array = split(",",$UNTRUSTED['expand']);
 

// Select out the current months data:
$whatYm = $UNTRUSTED['year'] . $UNTRUSTED['month'];

if($CSLH_Config['reftracking']!="Y"){ 
    print "&nbsp;<br>"   ."<table bgcolor=FFFFEE width=500><tr><td colspan=3 align=center><font color=990000><b>".$lang['txt217a']."</b></font><br> <a href=admin.php?page=mastersettings.php&tab=settings  target=_top>Click here to Enable in settings</a></td></tr></table>";       	
} else { 
	
?>
<FORM action=data.php METHOD=POST name=dataformrefer>
<input type=hidden name=show value=referer>
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
</select> </td> </td><td>&nbsp;</td>
<td><b>Type of VIEW:</b></td>
<td> <select name="typeofview" onchange="document.dataformrefer.submit();">
<?php
if(empty($UNTRUSTED['typeofview'])) $UNTRUSTED['typeofview'] = "levelvisits";

print "<option value=directvisits";
if($UNTRUSTED['typeofview'] == "directvisits") 
  print " SELECTED ";
print "> Top Urls</option>\n";
print "<option value=levelvisits";
if($UNTRUSTED['typeofview']== "levelvisits") 
  print " SELECTED ";
print "> Domain Tree</option>\n";
?>
</select></td></tr></table>
</FORM>
<table width=600><tr><td><?php echo $lang['txt216']; ?></td></tr></table><?php

if(empty($UNTRUSTED['top'])){ $UNTRUSTED['top'] = 0; }

$bgcolor="";
$lastfive  = "<tr><td colspan=4>Last five Query Strings for this refering page:</td></tr>";
if($bgcolor==$color_alt2){$bgcolor=$color_alt1; } else { $bgcolor=$color_alt2; }
if($UNTRUSTED['typeofview']== "levelvisits"){
  print "<table width=600><tr bgcolor=#FFFFFF><td>.</td><td><b>" . $lang['url'] . "</b></td><td><b># Total </b></td><td><b># Direct</b></td><td><b>" . $lang['graph'] . "</b></td></tr>";
  showchildrenof(0,10,$whatYm,$expand_array,'livehelp_referers_monthly');
  print "</table>";
} else {
  $query = "SELECT count(*) as totalrows FROM livehelp_referers_monthly WHERE dateof=". intval($whatYm) ." AND directvisits!=0 ORDER by directvisits DESC";
  $sth = $mydatabase->query($query);
  $row = $sth->fetchRow(DB_FETCHMODE_ASSOC);
	$num_rows = $row['totalrows'];
	if(empty($UNTRUSTED['offset']))
	  $UNTRUSTED['offset']=0;
	if(empty($UNTRUSTED['perpage']))
	  $UNTRUSTED['perpage'] = 25;
	  
	$query = "SELECT * FROM livehelp_referers_monthly WHERE dateof=". intval($whatYm) ." AND directvisits!=0 ORDER by directvisits DESC LIMIT " . intval($UNTRUSTED['offset']) . "," . intval($UNTRUSTED['perpage']);
  $visits_a = $mydatabase->query($query);
  $pageUrl = "data.php";
  $varstring = "&tab=".$UNTRUSTED['tab']."&month=".$UNTRUSTED['month'] . "&show=referer&month=".$UNTRUSTED['month'] . "&year=" . $UNTRUSTED['year'] . "&typeofview=" . $UNTRUSTED['typeofview'];
  print pagingLinks($pageUrl, $num_rows, $varstring,$UNTRUSTED['offset'],$UNTRUSTED['perpage']);
  print "<table width=600><tr bgcolor=FFFFFF><td><b>" . $lang['url'] . "</b></td><td><b>" . $lang['numclicks'] . "</b></td><td><b>" . $lang['graph'] . "</b></td></tr>";
  $bgcolor="F0F1E1"; 
   while($visits = $visits_a->fetchRow(DB_FETCHMODE_ASSOC)){ 
     if($bgcolor==$color_alt2){$bgcolor=$color_alt1; } else { $bgcolor=$color_alt2; }
    print "<tr bgcolor=".$bgcolor."><td><a href=" . $visits['pageurl'] . " target=_blank>" . $visits['pageurl'] . "</a></td><td>" . $visits['directvisits'] . "</td><td> <a href=graph.php?item=" . $visits['recno'] . "&type=refer&typeof=directvisits target=_blank>" . $lang['graph'] . "</a></td></tr>";
   }
  print "</table>";
}
?>

<table width=600><tr><td><?php echo $lang['txt216']; ?></td></tr></table>
<?php } ?>