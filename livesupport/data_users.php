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

Global $mydatabase,$UNTRUSTED,$bgcolor,$color_background,$lang,$CSLH_Config,$color_alt1,$color_alt2;
  

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


 

if(empty($UNTRUSTED['remove'])){  $UNTRUSTED['remove']=""; }
if(empty($UNTRUSTED['clearall'])){  $UNTRUSTED['clearall']=""; }
if(empty($UNTRUSTED['nukeit'])){  $UNTRUSTED['nukeit']=""; }	
if(empty($UNTRUSTED['clearoneclick'])){  $UNTRUSTED['clearoneclick']=""; }	
if(empty($UNTRUSTED['updateconfig'])){  $UNTRUSTED['updateconfig']=""; }	
if(empty($UNTRUSTED['limitclicks'])){  $UNTRUSTED['limitclicks']=2; }	
if(empty($UNTRUSTED['typeofview'])){ 
  if ($UNTRUSTED['show']=="referer")	
	  $UNTRUSTED['typeofview'] = "levelvisits"; 
  if ($UNTRUSTED['show']=="visit")
	  $UNTRUSTED['typeofview'] = "directvisits";	  
}
 

?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=<?php echo $color_background;?>><center>

<?php 
// Select out the current months data:
$whatYm = $UNTRUSTED['year'] . $UNTRUSTED['month'];

?>
<FORM action=data.php METHOD=POST name=dataforuser>
<input type=hidden name=tab value=<?php echo $UNTRUSTED['tab']; ?>>
<table bgcolor=DDDDDD width=600><tr><td>
<b><?php echo $lang['month']; ?></b><select name=month onchange=document.dataforuser.submit()>
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

</select> <b><?php echo $lang['year']; ?></b><select name=year onchange=document.dataforuser.submit()>
<?php
for($i=2004; $i<=date("Y"); $i++){
  print "<option value=$i ";
  if($UNTRUSTED['year'] == $i){ print " SELECTED "; } 
  print ">$i</option>";
}
?>   
</select> </td> </td><td>&nbsp;</td>
<td><b>Sort By:</b></td>
<td> <select name="typeofview" onchange="document.dataforuser.submit();">
<?php
if(empty($UNTRUSTED['typeofview']))
  $UNTRUSTED['typeofview'] = "groupusername";
  
print "<option value=groupusername";
if($UNTRUSTED['typeofview']== "groupusername") 
  print " SELECTED ";
print "> Username </option>\n";
 
print "<option value=username";
if($UNTRUSTED['typeofview']== "username") 
  print " SELECTED ";
print "> Username w/ Ip address</option>\n";
 
print "<option value=uservisits";
if($UNTRUSTED['typeofview']== "uservisits") 
  print " SELECTED ";
print "> # Visits</option>\n"; 
  
print "<option value=seconds";
if($UNTRUSTED['typeofview'] == "seconds") 
  print " SELECTED ";
print "> Time on Site </option>\n";
 
?>
</select></td></tr></table>
</FORM>
<table width=600><tr><td><?php echo $lang['txt216']; ?></td></tr></table><?php

if(empty($UNTRUSTED['top'])){ $UNTRUSTED['top'] = 0; }

  if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
 
  $ascdesc = " ASC";
  if( ($UNTRUSTED['typeofview'] == "seconds") || ($UNTRUSTED['typeofview'] == "uservisits"))
    $ascdesc = " DESC";
  
  if($UNTRUSTED['typeofview']== "groupusername"){    
   $query = "SELECT DISTINCT groupusername FROM livehelp_identity_monthly WHERE dateof=". intval($whatYm) ." ORDER BY " . $UNTRUSTED['typeofview'] . $ascdesc;
   $sth = $mydatabase->query($query);
	 $num_rows = $sth->numrows();
  } else {
   $query = "SELECT count(*) as totalrows FROM livehelp_identity_monthly WHERE dateof=". intval($whatYm) ." ORDER BY " . $UNTRUSTED['typeofview'] . $ascdesc;
   $sth = $mydatabase->query($query);
   $row = $sth->fetchRow(DB_FETCHMODE_ASSOC);
	 $num_rows = $row['totalrows'];
  }
  
	if(empty($UNTRUSTED['offset']))
	  $UNTRUSTED['offset']=0;
	if(empty($UNTRUSTED['perpage']))
	  $UNTRUSTED['perpage'] = 25;	  

  if($UNTRUSTED['typeofview']== "groupusername"){
	   $query = "SELECT DISTINCT groupusername FROM livehelp_identity_monthly WHERE dateof=". intval($whatYm) ." ORDER BY username LIMIT " . intval($UNTRUSTED['offset']) . "," . intval($UNTRUSTED['perpage']);  
  } else {
	  $query = "SELECT * FROM livehelp_identity_monthly WHERE dateof=". intval($whatYm) ." ORDER by ".$UNTRUSTED['typeofview'] ." DESC LIMIT " . intval($UNTRUSTED['offset']) . "," . intval($UNTRUSTED['perpage']);
  }
  $visits_a = $mydatabase->query($query);
  $pageUrl = "data.php";
  $varstring = "&tab=".$UNTRUSTED['tab']."&typeofview=".$UNTRUSTED['typeofview']."&show=usertrack&month=".$UNTRUSTED['month'] . "&year=" . $UNTRUSTED['year'];
  print  pagingLinks($pageUrl, $num_rows, $varstring,$UNTRUSTED['offset'],$UNTRUSTED['perpage']);
  print "<table width=600><tr bgcolor=\"#FFFFFF\"><td>&nbsp;</td><td><b>Date:</b></td><td><b>UserName</b></td><td><b>IP</b></td><td><b># Visits</b></td><td><b>Time</b></td></tr>";
  $bgcolor="F0F1E1"; 
   // for each monthly record:
   while($outerrow = $visits_a->fetchRow(DB_FETCHMODE_ASSOC)){ 
   	 // show totals:
   	   if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
  	 print "<tr bgcolor=$bgcolor>";
  	 if($UNTRUSTED['typeofview']== "groupusername"){
  	 	  $query = "SELECT * FROM livehelp_identity_monthly WHERE groupusername=".$outerrow['groupusername']." AND dateof=". intval($whatYm) ." ORDER BY id";
        $visits_b = $mydatabase->query($query);
        $outerrow = $visits_b->fetchRow(DB_FETCHMODE_ASSOC);
        
        $id = $outerrow['id'];
        $dateof = $outerrow['dateof'];
        $username = $outerrow['username'];
        $ipaddress = "*";
        $outerrow['uservisits'];
        $uservisits = $outerrow['uservisits'];
        $seconds = $outerrow['seconds'];
        // add the rest of the rows:
        while($outerrow = $visits_b->fetchRow(DB_FETCHMODE_ASSOC)){
           $uservisits += $outerrow['uservisits'];
           $seconds += $outerrow['seconds'];
        }
  	 } else {
  	    $id = $outerrow['id'];
  	    $uservisits = $outerrow['uservisits'];
  	    $dateof = $outerrow['dateof'];
  	    $username = $outerrow['username'];
  	    $ipaddress = $outerrow['ipaddress'];  	    
  	    $uservisits = $outerrow['uservisits'];
  	    $seconds = $outerrow['seconds'];
  	    $identity = $outerrow['identity'];
  	 } 	
  	    $contract = $UNTRUSTED['expand'];
        $reg1 = "," . $id . "\$";
        $contract = ereg_replace($reg1,"",$contract);
        $reg2 = "," . $id . ",";
        $contract = ereg_replace($reg2,"",$contract);
        $reg3 = "^" . $id . ",";
        $contract = ereg_replace($reg3,"",$contract);
       // plus/minus sign
       if(in_array($id,$expand_array))
         print "<td><a href=data.php?tab=".$UNTRUSTED['tab']."&month=".$UNTRUSTED['month']."&year=".$UNTRUSTED['year']."&typeofview=".$UNTRUSTED['typeofview']."&show=".$UNTRUSTED['show']."&expand=$contract&offset=".$UNTRUSTED['offset']."><img src=images/minus.gif border=0></a></td>";
       else
         print "<td><a href=data.php?tab=".$UNTRUSTED['tab']."&month=".$UNTRUSTED['month']."&year=".$UNTRUSTED['year']."&typeofview=".$UNTRUSTED['typeofview']."&show=".$UNTRUSTED['show']."&expand=".$UNTRUSTED['expand'].",".$id."&offset=".$UNTRUSTED['offset']."><img src=images/plus.gif border=0></a></td>";       
     	 print "<td>" . substr($dateof,4,2) . "-" . substr($dateof,0,4) . "</td><td>" . $username . "</a></td><td>" . $ipaddress . "</td><td>" . $uservisits . "</td><td>" . secondstoHHmmss($seconds) . "</td>";
  	 print "</tr>";     	 
   	
   	 // look for daily records:   
   	 if(in_array($id,$expand_array)){	    	  
   	  if($UNTRUSTED['typeofview']== "groupusername")
	      $query = "SELECT * FROM livehelp_identity_daily WHERE username='".$username."' AND dateof>". intval($whatYm) ."00 AND dateof<". intval($whatYm) ."32  ORDER by dateof DESC";      
      else
	      $query = "SELECT * FROM livehelp_identity_daily WHERE identity='".$identity."' AND username='".$username."' AND dateof>". intval($whatYm) ."00 AND dateof<". intval($whatYm) ."32 ORDER by dateof DESC";      
      
      $inner_sth = $mydatabase->query($query);            
      while( $innerrow = $inner_sth->fetchRow(DB_FETCHMODE_ASSOC)){
      	if($bgcolor=="F7FAFF"){$bgcolor="F0F1E1"; } else { $bgcolor="F7FAFF"; }
        print "<tr bgcolor=".$bgcolor."><td>&nbsp;</td><td>" . substr($innerrow['dateof'],4,2) . "-" . substr($innerrow['dateof'],6,2) . "-" . substr($innerrow['dateof'],0,4) . "</td><td>" . $innerrow['username'] . "</a></td><td>" . $innerrow['ipaddress'] . "</td><td>" . $innerrow['uservisits'] . "</td><td>" . secondstoHHmmss($innerrow['seconds']) . "</td></tr>";
      }  
     }
    }
  print "</table>";

?>
<table width=600><tr><td><?php echo $lang['txt216']; ?></td></tr></table>
  