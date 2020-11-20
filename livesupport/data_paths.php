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

Global $mydatabase,$UNTRUSTED,$bgcolor,$color_background,$lang,$CSLH_Config,$lang,$color_alt2,$color_alt1,$identity;

if(empty($UNTRUSTED['show'])){ $UNTRUSTED['show']= "";}
if(empty($UNTRUSTED['view'])){ $UNTRUSTED['view']= "";}
if(empty($UNTRUSTED['year'])){ $UNTRUSTED['year']= date("Y"); }
if(empty($UNTRUSTED['month'])){ $UNTRUSTED['month']= date("m"); }
if(empty($UNTRUSTED['expand'])){ $UNTRUSTED['expand'] = 0; }
if(empty($UNTRUSTED['top'])){ $UNTRUSTED['top'] = 0; }
if(empty($UNTRUSTED['perpage'])){ $UNTRUSTED['perpage'] = 25; } 
if(empty($UNTRUSTED['visit_recno'])){ $UNTRUSTED['visit_recno'] = 25; }  
 
   
function lookupparent($parent){
  global $mydatabase,$dbtype;
  
  if($parent!=0){
   $sqlquery = "SELECT pageurl FROM livehelp_visits_monthly WHERE recno='$parent' LIMIT 1";
   $rs = $mydatabase->query($sqlquery);	  	  
   $row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
   $pageurl = $row[0]; 
   return "<a href=$pageurl target=_blank>$pageurl</a>";
  } else
   return " END of Session";    
}

 
// removes a page and its children.
if(!(empty($UNTRUSTED['removevisit']))){ 
	$rec = intval($UNTRUSTED['removevisit']);
	$graph = array();
	$graph[] = $rec;
	recursive_delete_pages($rec,'livehelp_visits_monthly');
	print "<font color=990000>$deletedsofar removed...</font><br>";
}

$month = sprintf("%02d",intval($UNTRUSTED['month']));
$year = sprintf("%04d",intval($UNTRUSTED['year']));

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
$expand_array = split(",",$UNTRUSTED['expand']);


if(empty($UNTRUSTED['parent']))
  $UNTRUSTED['parent'] = 0;

 
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=<?php echo $color_background;?>><center>
 
<?php

if($CSLH_Config['tracking']!="Y"){ 
    print "&nbsp;<br>"   ."<table bgcolor=FFFFEE width=500><tr><td colspan=3 align=center><font color=990000><b>".$lang['txt217']."</b></font><br> <a href=admin.php?page=mastersettings.php&tab=settings  target=_top>".$lang['txt218']."</a></td></tr></table>";       	
} else { 
 
// Select out the current months data:
$whatYm = $UNTRUSTED['year'] . $UNTRUSTED['month'];

?>
<FORM action=data.php METHOD=POST name=dataformvisit>
<input type=hidden name=show value=visit>
<input type=hidden name=tab value=<?php print $UNTRUSTED['tab']; ?>>
<input type=hidden name=parent value=<?php print $UNTRUSTED['parent']; ?>>
<table bgcolor=DDDDDD width=600><tr><td>
<b><?php echo $lang['month']; ?></b><select name=month onchange=document.dataformvisit.submit()>
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

</select> <b><?php echo $lang['year']; ?></b><select name=year onchange=document.dataformvisit.submit()>
<?php
for($i=2004; $i<=date("Y"); $i++){
  print "<option value=$i ";
  if($UNTRUSTED['year'] == $i){ print " SELECTED "; } 
  print ">$i</option>";
}
?>   
</select> </td><td>&nbsp;</td>
<td><b><?php echo $lang['txt219']; ?>:</b></td>
<td> <select name="typeofview" onchange="document.dataformvisit.submit();">
<?php
print "<option value=all";
if(empty($UNTRUSTED['typeofview'])) $UNTRUSTED['typeofview'] = "all";
 
if($UNTRUSTED['typeofview'] == "directvisits") 
  print " SELECTED ";
print "> All Visit Paths</option>\n";
print "<option value=first";
if($UNTRUSTED['typeofview']== "first") 
  print " SELECTED ";
print "> First Visit Paths</option>\n";
?>
</select></td></tr></table>
</FORM>
<?php if($UNTRUSTED['typeofview'] == "first"){ ?>
<table width=600 bgcolor=#FFFFFF><tr><td>
	<b>Note:</b> View of "First Visit Paths" will show the paths taken from all 
	visitors on a selected page from when they <b>FIRST</b> visit that page. Not
	included in this view is paths taken from the selected page if they re-visit
	the page again and take a different path. </td></tr></table>
<?php } ?>	
<table width=600><tr><td><?php echo $lang['txt216']; ?></td></tr></table>
<table width=600><tr>
<td bgcolor=#FFFFEE>
 <b>Paths From:</b> 
 <?php
   if ($UNTRUSTED['parent']==0)
      print " START of Session (List of urls where visitors first enter the monitoring from)";
   else 
      print lookupparent($UNTRUSTED['parent']);

  $parent = intval($UNTRUSTED['parent']);

  ?>        
</td>
<?php
  if(!(empty($UNTRUSTED['prevpath'])))
  	print "<td bgcolor=DDDDDD><a href=data.php?tab=".$UNTRUSTED['tab']."&parent=".intval($UNTRUSTED['prevpath'])."&month=".$UNTRUSTED['month'] . "&year=" . $UNTRUSTED['year'] ."> &lt; -- Back Track Path </a></td>";
 ?>
</tr></table>
<?php

if(empty($UNTRUSTED['top'])){ $UNTRUSTED['top'] = 0; }
if(empty($UNTRUSTED['offset'])){ $UNTRUSTED['offset'] = 0; }
$bgcolor="";
$lastfive  = "<tr><td colspan=4>".$lang['txt220'].":</td></tr>";
if($bgcolor==$color_background){$bgcolor="F0F1E1"; } else { $bgcolor=$color_background; }
  
 
  
  if($UNTRUSTED['typeofview']== "first"){ 
	 $query = "SELECT * FROM livehelp_paths_firsts WHERE dateof=". intval($whatYm) ." AND visit_recno='". intval($parent) . "' ORDER by visits DESC LIMIT " . intval($UNTRUSTED['offset']) . "," . intval($UNTRUSTED['perpage']);
	 $q = "SELECT SUM(visits) as numvisits FROM livehelp_paths_firsts WHERE dateof=". intval($whatYm) ." AND visit_recno='". intval($parent) . "'";  
	 $q_count = "SELECT count(*) as totalrows FROM livehelp_paths_firsts WHERE dateof=". intval($whatYm) ." AND visit_recno='". intval($parent) . "'";
  } else {
   $query = "SELECT * FROM livehelp_paths_monthly WHERE dateof=". intval($whatYm) ." AND visit_recno='". intval($parent) ."' ORDER by visits DESC LIMIT " . intval($UNTRUSTED['offset']) . "," . intval($UNTRUSTED['perpage']);
	 $q = "SELECT SUM(visits) as numvisits FROM livehelp_paths_monthly WHERE dateof=". intval($whatYm) ." AND visit_recno='". intval($parent) . "'";
   $q_count = "SELECT count(*) as totalrows FROM livehelp_paths_monthly WHERE dateof=". intval($whatYm) ." AND visit_recno='". intval($parent) ."'";
  } 
  $visits_b = $mydatabase->query($q);
  $tmp = $visits_b->fetchRow(DB_FETCHMODE_ASSOC);  
  $visittotal = $tmp['numvisits'];;
  if($visittotal<1) $visittotal = 1;
  
  $sth = $mydatabase->query($q_count);
  $row = $sth->fetchRow(DB_FETCHMODE_ASSOC);
	$num_rows = $row['totalrows'];
  
  
  $visits_a = $mydatabase->query($query);
  $pageUrl = "data.php";
  $varstring = "typeofview=" . $UNTRUSTED['typeofview'] . "&show=visit&tab=".$UNTRUSTED['tab'] . "&month=".$UNTRUSTED['month'] . "&year=" . $UNTRUSTED['year'];
  print  pagingLinks($pageUrl, $num_rows, $varstring,$UNTRUSTED['offset'],$UNTRUSTED['perpage']);
  print "<table width=600><tr bgcolor=\"#FFFFFF\"><td><b>Clicks to:</b></td><td> <b>%</b> </td><td><b>" . $lang['numclicks'] . "</b></td><td><b>" . $lang['graph'] . "</b></td></tr>";
  $bgcolor="F0F1E1"; 
  while($visits = $visits_a->fetchRow(DB_FETCHMODE_ASSOC)){ 
     if($bgcolor==$color_alt2){$bgcolor=$color_alt1; } else { $bgcolor=$color_alt2; }
     print "<tr bgcolor=".$bgcolor."><td>" . lookupparent($visits['exit_recno']) . "</td><td> <b>" . sprintf("%.2f",(($visits['visits']/$visittotal) *100)) . " %</b> </td><td>" . $visits['visits'] . "</td><td><a href=data.php?typeofview=" . $UNTRUSTED['typeofview'] . "&tab=".$UNTRUSTED['tab']."&parent=".$visits['exit_recno']."&prevpath=$parent&month=".$UNTRUSTED['month'] . "&year=" . $UNTRUSTED['year'] .">View Path</a> </td></tr>";
   }
  print "</table>";
   

?>
<table width=600><tr><td><?php echo $lang['txt216']; ?></td></tr></table> 
<br><br>
<?php } ?>