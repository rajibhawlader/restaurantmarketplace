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
Global $mydatabase,$UNTRUSTED,$dbtype,$color_background,$lang,$color_alt2,$color_alt1,$identity,$CSLH_Config;
 

if(empty($UNTRUSTED['show'])){ $UNTRUSTED['show']= "";}
if(empty($UNTRUSTED['view'])){ $UNTRUSTED['view']= "";}
if(empty($UNTRUSTED['year'])){ $UNTRUSTED['year']= date("Y"); }
if(empty($UNTRUSTED['month'])){ $UNTRUSTED['month']= date("m"); }
if(empty($UNTRUSTED['expand'])){ $UNTRUSTED['expand'] = 0; }
if(empty($UNTRUSTED['top'])){ $UNTRUSTED['top'] = 0; }
if(empty($UNTRUSTED['perpage'])){ $UNTRUSTED['perpage'] = 25; } 

if($dbtype == "txt-db-api")
   $UNTRUSTED['show'] = "trans";
   
// if no admin rights then user can not clear or remove data: 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$data = $mydatabase->query($query);
$row = $data->fetchRow(DB_FETCHMODE_ASSOC);
$isadminsetting = $row['isadmin'];
if($isadminsetting != "Y"){ 
	  $UNTRUSTED['clearall'] = ""; 
	  $UNTRUSTED['remove']=""; 
	  $UNTRUSTED['removereferer']="";
	  $UNTRUSTED['nukeit']="";
}

 

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
 
if( ($UNTRUSTED['updateconfig']=="YES")){ 
  $query = "UPDATE livehelp_config SET " .
           "maxreferers=".intval($UNTRUSTED['maxreferers']) .
           ",maxvisits=".intval($UNTRUSTED['maxvisits']) .
           ",maxmonths=".intval($UNTRUSTED['maxmonths']) .
           ",maxoldhits=".intval($UNTRUSTED['maxoldhits']) .
           ",topkeywords=".intval($UNTRUSTED['topkeywords']) .
           ",maxrecords=".intval($UNTRUSTED['maxrecords']);
           
   $mydatabase->query($query);  
  
  $CSLH_Config['maxreferers'] = $UNTRUSTED['maxreferers']; 
  $CSLH_Config['maxvisits'] = $UNTRUSTED['maxvisits'];
  $CSLH_Config['maxmonths'] = $UNTRUSTED['maxmonths'];
  $CSLH_Config['maxoldhits'] = $UNTRUSTED['maxoldhits']; 
  $CSLH_Config['topkeywords'] = $UNTRUSTED['topkeywords'];     
}

if( ($UNTRUSTED['nukeit']=="YES")){
 $query = "TRUNCATE livehelp_operator_channels";
 $mydatabase->query($query);  
 $query = "DELETE FROM livehelp_users WHERE isoperator='N'";
 $mydatabase->query($query);  
 $query = "TRUNCATE livehelp_messages";
 $mydatabase->query($query); 
 $query = "TRUNCATE livehelp_channels";
 $mydatabase->query($query);  

  if(!(empty($UNTRUSTED['referers']))){
   $query = "TRUNCATE livehelp_referers_daily";
   $mydatabase->query($query);  
   $query = "TRUNCATE livehelp_referers_monthly";
   $mydatabase->query($query); 
  }
  
 if(!(empty($UNTRUSTED['pagevisits']))){ 
  $query = "TRUNCATE livehelp_visits_daily";
  $mydatabase->query($query);
  $query = "TRUNCATE livehelp_visits_monthly";
  $mydatabase->query($query); 
  $query = "TRUNCATE livehelp_visit_track";
  $mydatabase->query($query); 
  $query = "TRUNCATE livehelp_paths_firsts";
  $mydatabase->query($query); 
  $query = "TRUNCATE livehelp_paths_monthly";
  $mydatabase->query($query); 
 }
 
 if(!(empty($UNTRUSTED['usertrack']))){
   $query = "TRUNCATE livehelp_identity_daily ";
   $mydatabase->query($query);
   $query = "TRUNCATE livehelp_identity_monthly";
   $mydatabase->query($query); 
  } 

 if(!(empty($UNTRUSTED['keywords']))){
   $query = "TRUNCATE livehelp_keywords_daily ";
   $mydatabase->query($query);
   $query = "TRUNCATE livehelp_keywords_monthly";
   $mydatabase->query($query); 
  } 
  
  print "<font color=007700 size=+2>" . $lang['txt33'] . "</font>";
}	
	
	
if( ($UNTRUSTED['clearall']=="YES") ){
 $monthof = $UNTRUSTED['year'] . $UNTRUSTED['month']; 
 $query = "TRUNCATE livehelp_operator_channels";
 $mydatabase->query($query);  
 $query = "DELETE FROM livehelp_users WHERE isoperator='N'";
 $mydatabase->query($query);  
 $query = "TRUNCATE livehelp_messages";
 $mydatabase->query($query); 
 $query = "TRUNCATE livehelp_channels";
 $mydatabase->query($query);   
 
 if(!(empty($UNTRUSTED['referers']))){
  $query = "DELETE FROM livehelp_referers_daily WHERE (dateof>".intval($monthof)."00"." AND dateof <".intval($monthof)."32) OR dateof<2000";
  $mydatabase->query($query);  
  $query = "DELETE FROM livehelp_referers_monthly WHERE dateof=".intval($monthof)." OR dateof<2000";
  $mydatabase->query($query); 
 }
 
 if(!(empty($UNTRUSTED['pagevisits']))){ 
  $query = "DELETE FROM livehelp_visits_daily WHERE (dateof>".intval($monthof)."00"." AND dateof <".intval($monthof)."32) OR dateof<2000";
  $mydatabase->query($query);
  $query = "DELETE FROM livehelp_visits_monthly WHERE dateof=".intval($monthof)." OR dateof<2000";
  $mydatabase->query($query); 
  $query = "DELETE FROM livehelp_paths_firsts WHERE (dateof>".intval($monthof)."00"." AND dateof <".intval($monthof)."32) OR dateof<2000";
  $mydatabase->query($query);
  $query = "DELETE FROM livehelp_paths_monthly WHERE dateof=".intval($monthof)." OR dateof<2000";
  $mydatabase->query($query); 
  $query = "DELETE FROM livehelp_visit_track";
  $mydatabase->query($query); 
}
 if(!(empty($UNTRUSTED['usertrack']))){
   $query = "DELETE FROM livehelp_identity_daily WHERE (dateof>".intval($monthof)."00"." AND dateof <".intval($monthof)."32) OR dateof<2000";
   $mydatabase->query($query);
   $query = "DELETE FROM livehelp_identity_monthly WHERE dateof=".intval($monthof)." OR dateof<2000";
   $mydatabase->query($query); 
  } 
 if(!(empty($UNTRUSTED['transcripts']))){
  $query = "DELETE FROM livehelp_transcripts  WHERE (endtime >".intval($monthof)."00000000"." AND endtime <".intval($monthof)."32000000) ";
  $mydatabase->query($query);  
 }

 if(!(empty($UNTRUSTED['keywords']))){
   $query = "DELETE FROM livehelp_keywords_daily WHERE (dateof>".intval($monthof)."00"." AND dateof <".intval($monthof)."32) OR dateof<2000";
   $mydatabase->query($query);
   $query = "DELETE FROM livehelp_keywords_monthly WHERE dateof=".intval($monthof)." OR dateof<2000";
   $mydatabase->query($query); 
  }
  
  
 print "<font color=007700 size=+2>" . $lang['txt33'] . "</font>";
}

?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
  <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=<?php echo $color_background;?>><center>

 <?php if($isadminsetting == "Y"){ ?>
<br>
<table bgcolor=DDDDDD width=600><tr><td>
<b><?php echo $lang['txt38']; ?></b></td></tr></table> 
<?php 
  if($dbtype == "txt-db-api")
    print "<tr><td colspan=3><font color=990000><b>This Feature Requires a MySQL database.. Disabled in txt-db-api</b></font></td></tr>";       	
 else {
 	?>
<FORM action=data.php METHOD=POST name=updateform>
<input type=hidden name=updateconfig value=YES>
<input type=hidden name=tab value=<?php echo $UNTRUSTED['tab']; ?> >
<input type=hidden name=month value=<?php echo $month; ?> >
<input type=hidden name=year value=<?php echo $year; ?>>
<table bgcolor=FEFEFE width=400>
<tr><td >
<font color=000077><b>
Data Referer and Page view Archive Settings:
</b></font></td></tr>
<tr><td >
Record Top <select name=maxreferers>
<?php 
  for($i=10;$i<300;$i=$i+5){
     print "<option value=$i ";
     if($CSLH_Config['maxreferers'] == $i)
        print " SELECTED ";
     print ">$i</option>\n";
  } 
  for($i=300;$i<1000;$i=$i+100){
     print "<option value=$i ";
     if($CSLH_Config['maxreferers'] == $i)
        print " SELECTED ";
     print ">$i</option>\n";
  }    
?>  
</select> Referers for data over one month old. (domain tree count) <br>
Record Top <select name=maxvisits>
<?php 
  for($i=10;$i<300;$i=$i+5){
     print "<option value=$i ";
     if($CSLH_Config['maxvisits'] == $i)
        print " SELECTED ";
     print ">$i</option>\n";
  } 
  for($i=300;$i<1000;$i=$i+100){
     print "<option value=$i ";
     if($CSLH_Config['maxvisits'] == $i)
        print " SELECTED ";
     print ">$i</option>\n";
  }    
?>  
</select> Page Views for data over one month old. (domain tree count) <bR>
Record Top <select name=topkeywords>
<?php 
  for($i=5;$i<100;$i=$i+5){
     print "<option value=$i ";
     if($CSLH_Config['topkeywords'] == $i)
        print " SELECTED ";
     print ">$i</option>\n";
  }    
?> 
</select> Keywords .<br>

Record Past <select name=maxmonths>
<?php 
  for($i=1;$i<36;$i++){
     print "<option value=$i ";
     if($CSLH_Config['maxmonths'] == $i)
        print " SELECTED ";
     print ">$i</option>\n";
  }    
?> 
</select> Months of Data.<br>
Delete records over one month old that contain less than or equal to
<select name=maxoldhits>
<option value=0>0 - do not delete.</option>
<option value=1 <?php  if($CSLH_Config['maxoldhits'] == 1) print " SELECTED "; ?>>1 Visit</option>
<?php for($i=2;$i<20;$i++){  
print "<option value=$i";
     if($CSLH_Config['maxoldhits'] == $i)
        print " SELECTED ";
print ">$i Visits</option>\n";
} ?> 
</select><br>
Flood control: If the number of records for the 
current month excedes <select name=maxrecords>
<option value=50000 <?php if($CSLH_Config['maxrecords'] == 50000) print " SELECTED ";?>>50,000</option>
<option value=75000 <?php if($CSLH_Config['maxrecords'] == 75000) print " SELECTED ";?>>75,000</option>
<option value=100000 <?php if($CSLH_Config['maxrecords'] == 100000) print " SELECTED ";?>>100,000</option>
<option value=125000 <?php if($CSLH_Config['maxrecords'] == 125000) print " SELECTED ";?>>125,000</option>
<option value=150000 <?php if($CSLH_Config['maxrecords'] == 150000) print " SELECTED ";?>>150,000</option>
<option value=175000 <?php if($CSLH_Config['maxrecords'] == 175000) print " SELECTED ";?>>175,000</option>
<option value=200000 <?php if($CSLH_Config['maxrecords'] == 200000) print " SELECTED ";?>>200,000</option>
<option value=250000 <?php if($CSLH_Config['maxrecords'] == 250000) print " SELECTED ";?>>250,000</option>
<option value=300000 <?php if($CSLH_Config['maxrecords'] == 300000) print " SELECTED ";?>>300,000</option>
<option value=400000 <?php if($CSLH_Config['maxrecords'] == 400000) print " SELECTED ";?>>400,000</option>
<option value=500000 <?php if($CSLH_Config['maxrecords'] == 500000) print " SELECTED ";?>>500,000</option>
<option value=1000000 <?php if($CSLH_Config['maxrecords'] == 1000000) print " SELECTED ";?>>1,000,000</option>
</select> Delete records with less than or equal to 1 Visit
to save resources.
<br><br>
<input type=submit value="UPDATE SETTINGS">  
</td></tr></table>
</FORM>


 <br><br>

<FORM action=data.php METHOD=POST name=deleteform>
	<input type=hidden name=tab value=<?php echo $UNTRUSTED['tab']; ?> >
<input type=hidden name=clearall value=YES>
<table bgcolor=FEFEFE width=400>
<tr><td >
<font color=990000><b>
Clear Referer and/or Visit and/or user sessions and/or transcripts data for the month of:
</b></font><br>
This will reset the values for the selected month to zero</td></tr>
<tr><td>
<b><?php echo $lang['month']; ?></b><select name=month>
<option value=01><?php echo $lang['jan']; ?></option>
<option value=02><?php echo $lang['feb']; ?></option>
<option value=03><?php echo $lang['mar']; ?></option>
<option value=04><?php echo $lang['apr']; ?></option>
<option value=05><?php echo $lang['may']; ?></option>
<option value=06><?php echo $lang['jun']; ?></option>
<option value=07><?php echo $lang['jul']; ?></option>
<option value=08><?php echo $lang['aug']; ?></option>
<option value=09><?php echo $lang['sep']; ?></option>
<option value=10><?php echo $lang['oct']; ?></option>
<option value=11><?php echo $lang['nov']; ?></option>
<option value=12><?php echo $lang['dec']; ?></option>

</select> <b><?php echo $lang['year']; ?></b><select name=year>
<?php
for($i=2004; $i<=date("Y"); $i++){
  print "<option value=$i >$i</option>";
}
?>   
</select> <br>
<b><?php echo $lang['DELETE']; ?></b>: <br>
<table>
	<tr>
		<td><?php echo $lang['referers']; ?><input type=checkbox name=referers value=1> </td>
	  <td><?php echo $lang['txt35']; ?><input type=checkbox name=pagevisits value=1> </td>
	  </tr><tr>
		<td>User Sessions <input type=checkbox name=usertrack value=1> </td>
		<td>Keywords <input type=checkbox name=keywords value=1> </td>
		</tr>
		<tr>
			<td><?php echo $lang['transcripts']; ?><input type=checkbox name=transcripts value=1></td>
	</tr></table>
			<br>
     
     <input type=SUBMIT value="<?php echo $lang['DELETE']; ?>" ></td></tr></table>
</FORM>

<br><br>

<FORM action=data.php METHOD=POST name=deleteform OnSubmit="return confirm('Are you sure you want to remove this ?!?')")>
<input type=hidden name=nukeit value=YES>
<input type=hidden name=tab value=<?php echo $UNTRUSTED['tab']; ?> >
<input type=hidden name=month value=<?php echo $month; ?> >
<input type=hidden name=year value=<?php echo $year; ?>>
<table bgcolor=FEFEFE width=400>
<tr><td >
<font color=990000><b>
RESET ALL REFERER AND/OR PAGE VISIT AND/OR USER SESSION DATA TO ZERO
</b></font></td></tr>
<tr><td >
This will reset page visits and or referer data to zero.<br>
<b><?php echo $lang['DELETE']; ?></b>: <br>
<?php echo $lang['referers']; ?><input type=checkbox name=referers value=1> 
<?php echo $lang['txt35']; ?><input type=checkbox name=pagevisits value=1> 
Keywords <input type=checkbox name=keywords value=1><br>
User Sessions <input type=checkbox name=usertrack value=1><br>
<input type=submit value="RESET TO ZERO/ DELETE ALL">  
</td></tr></table>
</FORM>
<?php
  }
 }
 
 
?>