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

Header('Content-Type: text/csv\n',true);
Header('Content-Disposition: attachment; filename="download.csv"');
Header('Pragma: no-cache',true);
Header('Expires: 0',true);


require_once("admin_common.php");
require_once("data_functions.php");
require_once("ctabbox.php");
require_once("gc.php");

validate_session($identity);

 
if(empty($UNTRUSTED['department'])){ $UNTRUSTED['department']= 0;}
if(empty($UNTRUSTED['data'])){ $UNTRUSTED['data']= "";}
if(empty($UNTRUSTED['whatYm'])){ $UNTRUSTED['whatYm']= "";}
 
$whatYm = $UNTRUSTED['whatYm'];

   
 
 $whatYm_b =  $whatYm . "00000000";
 $whatYm_e =  $whatYm . "99999999";
 $whichdepartmentsql = "";
 $whatdates = "";

// if no admin rights then user can not clear or remove data: 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$data = $mydatabase->query($query);
$row = $data->fetchRow(DB_FETCHMODE_ASSOC);
$isadminsetting = $row['isadmin'];
$myid = $row['user_id'];

if(!(empty($UNTRUSTED['department']))){
   $sqlquery = "SELECT user_id FROM livehelp_operator_departments WHERE user_id='$myid' AND department='".intval($UNTRUSTED['department'])."' ";
   $data_check = $mydatabase->query($sqlquery); 
   if( ($UNTRUSTED['whichdepartment']!=0) && ($data_check->numrows() == 0) ){ 
    $UNTRUSTED['whichdepartment'] = "";
   }
} 

if(empty($UNTRUSTED['department'])){  	 
  $qQry = "SELECT department FROM livehelp_operator_departments WHERE user_id='$myid' LIMIT 1";
  $qRes = $mydatabase->query($qQry);
  $qRow = $qRes->fetchRow(DB_FETCHMODE_ORDERED); 
  $UNTRUSTED['whichdepartment'] = $qRow[0];     	
 }


 $whichdepartmentsql = " AND department=". intval($UNTRUSTED['department']) . " ";
 if($UNTRUSTED['whatYm'] != "")
    $whatdates = " AND  dateof>$whatYm_b AND dateof<$whatYm_e ";
    
 $query = "SELECT * FROM livehelp_leavemessage WHERE 1 $whatdates $whichdepartmentsql order by dateof DESC"; 
 $refer_a = $mydatabase->query($query);
 $total_p = $refer_a->numrows();
 $perpage = intval($UNTRUSTED['perpage']);
 $top = intval($UNTRUSTED['top']);
 $show = $UNTRUSTED['show'];
   $pageUrl = "data.php";
  $varstring = "&tab=".$UNTRUSTED['tab']."&show=trans&month=".$UNTRUSTED['month'] . "&year=" . $UNTRUSTED['year'];
 
   echo $lang['date'];    

	  // get list of fields for this department..	
	  $q = "SELECT headertext,id FROM livehelp_questions WHERE module='leavemessage' AND department=". intval($UNTRUSTED['department']) . " order by ordering ";
	  $qRes = $mydatabase->query($q);
    $fields = array();
    while($qRow = $qRes->fetchRow(DB_FETCHMODE_ORDERED)){
      print  ",\"".$qRow[0]."\"";	
      $fields[]= "field_" . $qRow[1];
      $comma = ",";
    }

  print "\n";
  // show data : 
 $query = "SELECT * FROM livehelp_leavemessage WHERE 1  $whatdates  $whichdepartmentsql order by dateof DESC ";
 $data = $mydatabase->query($query); 
 
 $myfields = array();
 if(!($data->numrows() == 0)) {
  while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){ 
 	unset($myfields);
 	$myfields = array();
 
   print substr($row['dateof'],4,2) . "-" . substr($row['dateof'],6,2) . "-" . substr($row['dateof'],0,4) . " (" . substr($row['dateof'],8,2) . ":" . substr($row['dateof'],10,2) . ":" . substr($row['dateof'],12,2) . ")";
 
   
   // get fields:
   $pairs = explode("&",$row['deliminated']);
   for($i=0;$i<count($pairs);$i++){
   	 $myarray = explode("=",$pairs[$i]);
   	 $key = $myarray[0];
     $myfields[$key] = str_replace("\n"," ",str_replace("\r"," ",urldecode($myarray[1])));   
   }
   
   for($i=0;$i<count($fields);$i++){
   	 $key = $fields[$i];
   	 print ",\"".$myfields[$key]."\"";
   }

   print "\n";   
  } 
 }
 ?> 