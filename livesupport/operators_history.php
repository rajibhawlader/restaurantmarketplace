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

$query = "SELECT * FROM livehelp_users WHERE user_id='" . intval($UNTRUSTED['user_id']) . "'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$username = $people['username'];

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
$totalonline = 0;
$totalchat = 0;
$totalmono = 0;
$bgcolor = "";
if(empty($UNTRUSTED['clearall'])) 
  $UNTRUSTED['clearall'] = "";

if( ($isadminsetting == "Y") && ($UNTRUSTED['clearall']=="YES") ){
 $monthof = $UNTRUSTED['year'] . $UNTRUSTED['month']; 
   
 $query = "DELETE FROM livehelp_operator_history WHERE opid='".intval($UNTRUSTED['user_id'])."' AND (dateof>".intval($monthof)."00000000"." AND dateof <".intval($monthof)."99990000) OR dateof<2000";
 $mydatabase->query($query); 
 
 print "<font color=007700 size=+2>" . $lang['txt33'] . "</font>";
}

$to_month = (empty($UNTRUSTED['to_MONTH'])) ? date("m") : $UNTRUSTED['to_MONTH'];
$to_day = (empty($UNTRUSTED['to_DAY'])) ? date("d") : $UNTRUSTED['to_DAY'];
$to_year = (empty($UNTRUSTED['to_YEAR'])) ? date("Y") : $UNTRUSTED['to_YEAR'];

// Default to 7 days ago..
$lastweek = mktime(0, 0, 0, date("m"), date("d")-7,  date("Y"));
$from_month = (empty($UNTRUSTED['from_MONTH'])) ? date("m",$lastweek) : $UNTRUSTED['from_MONTH'];
$from_day = (empty($UNTRUSTED['from_DAY'])) ? date("d",$lastweek) : $UNTRUSTED['from_DAY'];
$from_year = (empty($UNTRUSTED['from_YEAR'])) ? date("Y",$lastweek) : $UNTRUSTED['from_YEAR'];
 
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" >
<body bgcolor=<?php echo $color_background;?>><center>
<h2>History for <?php echo $username; ?></h2>
<form action=operators_history.php METHOD=POST>
<input type=hidden name=user_id value="<?php echo $UNTRUSTED['user_id']; ?>">
 
<table>
<tr><td>From:</td><td>

<table border=0><tr>
<TD><SELECT NAME=from_MONTH>
<OPTION value="">- Month - 
<OPTION value=01 <?php if ($from_month == "01"){ print "SELECTED"; } ?>>Jan
<OPTION value=02 <?php if ($from_month == "02"){ print "SELECTED"; } ?>>Feb
<OPTION value=03 <?php if ($from_month == "03"){ print "SELECTED"; } ?>>Mar
<OPTION value=04 <?php if ($from_month == "04"){ print "SELECTED"; } ?>>Apr
<OPTION value=05 <?php if ($from_month == "05"){ print "SELECTED"; } ?>>May
<OPTION value=06 <?php if ($from_month == "06"){ print "SELECTED"; } ?>>Jun
<OPTION value=07 <?php if ($from_month == "07"){ print "SELECTED"; } ?>>Jul
<OPTION value=08 <?php if ($from_month == "08"){ print "SELECTED"; } ?>>Aug
<OPTION value=09 <?php if ($from_month == "09"){ print "SELECTED"; } ?>>Sep
<OPTION value=10 <?php if ($from_month == "10"){ print "SELECTED"; } ?>>Oct
<OPTION value=11 <?php if ($from_month == "11"){ print "SELECTED"; } ?>>Nov
<OPTION value=12 <?php if ($from_month == "12"){ print "SELECTED"; } ?>>Dec
</SELECT></TD>

<TD>
<SELECT NAME=from_DAY>
<OPTION value="">- Day - 
<OPTION value=01 <?php if ($from_day == "01"){ print "SELECTED"; } ?>>1
<OPTION value=02 <?php if ($from_day == "02"){ print "SELECTED"; } ?>>2
<OPTION value=03 <?php if ($from_day == "03"){ print "SELECTED"; } ?>>3
<OPTION value=04 <?php if ($from_day == "04"){ print "SELECTED"; } ?>>4
<OPTION value=05 <?php if ($from_day == "05"){ print "SELECTED"; } ?>>5
<OPTION value=06 <?php if ($from_day == "06"){ print "SELECTED"; } ?>>6
<OPTION value=07 <?php if ($from_day == "07"){ print "SELECTED"; } ?>>7
<OPTION value=08 <?php if ($from_day == "08"){ print "SELECTED"; } ?>>8
<OPTION value=09 <?php if ($from_day == "09"){ print "SELECTED"; } ?>>9
<OPTION value=10 <?php if ($from_day == "10"){ print "SELECTED"; } ?>>10
<OPTION value=11 <?php if ($from_day == "11"){ print "SELECTED"; } ?>>11
<OPTION value=12 <?php if ($from_day == "12"){ print "SELECTED"; } ?>>12
<OPTION value=13 <?php if ($from_day == "13"){ print "SELECTED"; } ?>>13
<OPTION value=14 <?php if ($from_day == "14"){ print "SELECTED"; } ?>>14
<OPTION value=15 <?php if ($from_day == "15"){ print "SELECTED"; } ?>>15
<OPTION value=16 <?php if ($from_day == "16"){ print "SELECTED"; } ?>>16
<OPTION value=17 <?php if ($from_day == "17"){ print "SELECTED"; } ?>>17
<OPTION value=18 <?php if ($from_day == "18"){ print "SELECTED"; } ?>>18
<OPTION value=19 <?php if ($from_day == "19"){ print "SELECTED"; } ?>>19
<OPTION value=20 <?php if ($from_day == "20"){ print "SELECTED"; } ?>>20
<OPTION value=21 <?php if ($from_day == "21"){ print "SELECTED"; } ?>>21
<OPTION value=22 <?php if ($from_day == "22"){ print "SELECTED"; } ?>>22
<OPTION value=23 <?php if ($from_day == "23"){ print "SELECTED"; } ?>>23
<OPTION value=24 <?php if ($from_day == "24"){ print "SELECTED"; } ?>>24
<OPTION value=25 <?php if ($from_day == 25){ print "SELECTED"; } ?>>25
<OPTION value=26 <?php if ($from_day == 26){ print "SELECTED"; } ?>>26
<OPTION value=27 <?php if ($from_day == 27){ print "SELECTED"; } ?>>27
<OPTION value=28 <?php if ($from_day == 28){ print "SELECTED"; } ?>>28
<OPTION value=29 <?php if ($from_day == 29){ print "SELECTED"; } ?>>29
<OPTION value=30 <?php if ($from_day == 30){ print "SELECTED"; } ?>>30
<OPTION value=31 <?php if ($from_day == 31){ print "SELECTED"; } ?>>31
</SELECT>
</TD>

<TD><SELECT NAME=from_YEAR>

<OPTION value="">- Year - 
<?php
for ($i=2005;$i <=date("Y");$i++){
print "<OPTION value=$i ";
  if ($from_year == $i){ print "SELECTED"; }
print " >$i \n";
}
?>
</select>
</TD></TR>
</TABLE>

</td></tr>
<tr><td>To:</td><td>

<table border=0><tr>
<TD><SELECT NAME=to_MONTH>
<OPTION value="">- Month - 
<OPTION value=01 <?php if ($to_month == "01"){ print "SELECTED"; } ?>>Jan
<OPTION value=02 <?php if ($to_month == "02"){ print "SELECTED"; } ?>>Feb
<OPTION value=03 <?php if ($to_month == "03"){ print "SELECTED"; } ?>>Mar
<OPTION value=04 <?php if ($to_month == "04"){ print "SELECTED"; } ?>>Apr
<OPTION value=05 <?php if ($to_month == "05"){ print "SELECTED"; } ?>>May
<OPTION value=06 <?php if ($to_month == "06"){ print "SELECTED"; } ?>>Jun
<OPTION value=07 <?php if ($to_month == "07"){ print "SELECTED"; } ?>>Jul
<OPTION value=08 <?php if ($to_month == "08"){ print "SELECTED"; } ?>>Aug
<OPTION value=09 <?php if ($to_month == "09"){ print "SELECTED"; } ?>>Sep
<OPTION value=10 <?php if ($to_month == "10"){ print "SELECTED"; } ?>>Oct
<OPTION value=11 <?php if ($to_month == "11"){ print "SELECTED"; } ?>>Nov
<OPTION value=12 <?php if ($to_month == "12"){ print "SELECTED"; } ?>>Dec
</SELECT></TD>

<TD>
<SELECT NAME=to_DAY>
<OPTION value="">- Day - 
<OPTION value=01 <?php if ($to_day == "01"){ print "SELECTED"; } ?>>1
<OPTION value=02 <?php if ($to_day == "02"){ print "SELECTED"; } ?>>2
<OPTION value=03 <?php if ($to_day == "03"){ print "SELECTED"; } ?>>3
<OPTION value=04 <?php if ($to_day == "04"){ print "SELECTED"; } ?>>4
<OPTION value=05 <?php if ($to_day == "05"){ print "SELECTED"; } ?>>5
<OPTION value=06 <?php if ($to_day == "06"){ print "SELECTED"; } ?>>6
<OPTION value=07 <?php if ($to_day == "07"){ print "SELECTED"; } ?>>7
<OPTION value=08 <?php if ($to_day == "08"){ print "SELECTED"; } ?>>8
<OPTION value=09 <?php if ($to_day == "09"){ print "SELECTED"; } ?>>9
<OPTION value=10 <?php if ($to_day == "10"){ print "SELECTED"; } ?>>10
<OPTION value=11 <?php if ($to_day == "11"){ print "SELECTED"; } ?>>11
<OPTION value=12 <?php if ($to_day == "12"){ print "SELECTED"; } ?>>12
<OPTION value=13 <?php if ($to_day == "13"){ print "SELECTED"; } ?>>13
<OPTION value=14 <?php if ($to_day == "14"){ print "SELECTED"; } ?>>14
<OPTION value=15 <?php if ($to_day == "15"){ print "SELECTED"; } ?>>15
<OPTION value=16 <?php if ($to_day == "16"){ print "SELECTED"; } ?>>16
<OPTION value=17 <?php if ($to_day == "17"){ print "SELECTED"; } ?>>17
<OPTION value=18 <?php if ($to_day == "18"){ print "SELECTED"; } ?>>18
<OPTION value=19 <?php if ($to_day == "19"){ print "SELECTED"; } ?>>19
<OPTION value=20 <?php if ($to_day == "20"){ print "SELECTED"; } ?>>20
<OPTION value=21 <?php if ($to_day == "21"){ print "SELECTED"; } ?>>21
<OPTION value=22 <?php if ($to_day == "22"){ print "SELECTED"; } ?>>22
<OPTION value=23 <?php if ($to_day == "23"){ print "SELECTED"; } ?>>23
<OPTION value=24 <?php if ($to_day == "24"){ print "SELECTED"; } ?>>24
<OPTION value=25 <?php if ($to_day == 25){ print "SELECTED"; } ?>>25
<OPTION value=26 <?php if ($to_day == 26){ print "SELECTED"; } ?>>26
<OPTION value=27 <?php if ($to_day == 27){ print "SELECTED"; } ?>>27
<OPTION value=28 <?php if ($to_day == 28){ print "SELECTED"; } ?>>28
<OPTION value=29 <?php if ($to_day == 29){ print "SELECTED"; } ?>>29
<OPTION value=30 <?php if ($to_day == 30){ print "SELECTED"; } ?>>30
<OPTION value=31 <?php if ($to_day == 31){ print "SELECTED"; } ?>>31
</SELECT>
</TD>

<TD><SELECT NAME=to_YEAR>

<OPTION value="">- Year - 
<?php
for ($i=2005;$i <=date("Y");$i++){
print "<OPTION value=$i ";
  if ($to_year == $i){ print "SELECTED"; }
print " >$i \n";
}
?>
</select>
</TD></TR>
</TABLE>
</td></tr>
</table>
<input type=submit value=SEARCH>
<br><br>

<table>
  <tr bgcolor=DFDFDF><td><b>Date:</b></td><td><b>Action:</b></td><td><b>Notes:</b></td></tr>  

<?php
 $b_dateof = $to_year . $to_month . $to_day . "999999";
 $a_dateof = $from_year . $from_month . $from_day . "000000";
 $query = "SELECT * 
           FROM livehelp_operator_history
           WHERE dateof >= '".$a_dateof."'
             AND dateof <= '".$b_dateof."'
             AND opid=" . intval($UNTRUSTED['user_id']) . "
           ORDER by dateof";
 $bgcolor = "#FFFFFF";
 $data2 = $mydatabase->query($query); 
 while($row = $data2->fetchRow(DB_FETCHMODE_ASSOC)){	
 	  if($bgcolor=="#FFFFFF")
 	    $bgcolor= "#FFFFFF";
 	  else
 	    $bgcolor= "#DEDEDE";
if($row['action']!="startchat"){
 	  print "<tr bgcolor=$bgcolor>";
 	  print "<td>". substr($row['dateof'],4,2) . "-". substr($row['dateof'],6,2)."-". substr($row['dateof'],0,4)." (". substr($row['dateof'],8,2) .":" . substr($row['dateof'],10,2) .")</td>";
 	  if($row['action']=="Stopchat"){ 
 	    print "<td><b>Chatting:</b>";
 	    if($row['transcriptid'] != 0){
 	  	 $q = "SELECT * FROM livehelp_transcripts WHERE recno=" . $row['transcriptid'] . " LIMIT 1";
 	  	 $data3 = $mydatabase->query($q); 
       $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
       print "<a href=view_transcript.php?view=" . $row['transcriptid'] . " target=_blank >" . $row3['who'] . "</a>";
 	     $row['totaltime'] = $row3['duration'];
 	    }
 	  
 	  } else {
 	    print "<td><b>".$row['action']."</b></td>";
 	  }  
    if($row['action']=="Logout")
      $totalonline += $row['totaltime'];
    if($row['action']=="Stopped Monitoring Traffic")
      $totalmono += $row['totaltime'];      
    if($row['action']=="Stopped Chatting")
      $totalchat += $row['totaltime'];
 	  print "<td> ";
 	  if($row['totaltime'] != 0)
 	  	print "Duration: ". secondstoHHmmss($row['totaltime']);
 	 
 	  print "</td>";
 	  print "</tr>";
 	}
 }
?>   
</table>
<hr>
<table>
<tr bgcolor=<?php echo $color_background;?>><td><b>Total Time Online:</b></td><td><?php echo secondstoHHmmss($totalonline);?></td></tr>
<tr bgcolor=<?php echo $color_background;?>><td><b>Total Time Monitoring Traffic:</b></td><td><?php echo secondstoHHmmss($totalmono);?></td></tr>  
<tr bgcolor=<?php echo $color_background;?>><td><b>Total Time Chatting:</b></td><td><?php echo secondstoHHmmss($totalchat);?></td></tr>    
</table>
<hr>
<table width=450><tr><td>
Note: Total Time Online/Monitoring Traffic and Chattings is calculated when the operator logs out so does not include current active session.   
</td></tr></table>
</FORM>

<?php if($isadminsetting == "Y"){ ?>
<br><br><br><br>

<table bgcolor=DDDDDD width=600><tr><td>
<b><?php echo $lang['txt173']; ?></b></td></tr></table>
 
<FORM action=operators_history.php METHOD=POST name=deleteform>
<input type=hidden name=clearall value=YES>
<input type=hidden name=user_id value="<?php echo $UNTRUSTED['user_id']; ?>">
<table bgcolor=FEFEFE width=400>
<tr><td >
<font color=990000><b>
<?php echo $lang['txt173']; ?>

</b></font></td></tr>
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
for($i=2005; $i<=date("Y"); $i++){
  print "<option value=$i >$i</option>";
}
?>   
</select> <input type=SUBMIT value="<?php echo $lang['DELETE']; ?>" ></td></tr></table>
</FORM>
<?php
}
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