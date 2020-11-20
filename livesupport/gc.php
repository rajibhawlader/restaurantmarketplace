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

//global for number of referers deleted..
$deletedsofar = 0;

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~    
/**
  * Recursivly deletes old visit data stops after 10,000 have been removed
  * to save proccessing time.
  *
  * @param int $recno the recno of the referer to delete 
  * @param string  the table to delete from
  * @param array  array of element IDs that calls to delete have been already been made for  
  *
  * @global object $mydatabase mysql database object.
  * @global int Global Count of total deleted so far.
  */
function recursive_delete_pages($recno,$tablename,$graph=array()){
	global $mydatabase,$deletedsofar,$dbtype;	
	
	// if this is a text based database 
	if($dbtype=="txt-db-api")
	  return;
	  
	// if the recusive call was called without adding the starting 
	// parent id to the list of ids seen add it here:
	if(count($graph) == 0)
	   $graph[] = $recno;
	
	// if we have already removed 10,000 
	if ($deletedsofar >10000){
	 return;
	} else { 	
      // Base case: this child has no children:
      $sqlquery = "SELECT recno FROM $tablename WHERE parentrec=$recno";  
      $data = $mydatabase->query($sqlquery); 
      if($data->numrows()==0){
        // Delete this row:
		    $sqlquery = "DELETE FROM $tablename WHERE recno=$recno";
		    $mydatabase->query($sqlquery);
		    $deletedsofar++;		  
      } else {      	 
       	 // delete children:
      	 while($row = $data->fetchRow(DB_FETCHMODE_ORDERED)){             
             // ahhh .. if a delete call has already been made for this parent
             // this Tree is actually a graph ! .. Don't endlessly loop:
      	     if (in_array($row[0],$graph))
       	       return;
             else {                     
             	 // add child to list of id's seen:
             	 $graph[] = $row[0];
             	 // recursion on removing children: 
               recursive_delete_pages($row[0],$tablename,$graph);  
             } // if in graph or array of ids already seen   
         } // while more children
      }	// if there are children	
   } // if we have removed 10,0000 rows. 
} // end function.

// Random cleaning of OLD daily data:
//-------------------------------------
$randomNumber = rand(1, 501);
if($randomNumber == 7){

 if(empty($CSLH_Config['maxmonths'])) 
   $CSLH_Config['maxmonths'] = 12;
 if($CSLH_Config['maxmonths']<2)
   $CSLH_Config['maxmonths'] = 2;
 
 if(empty($CSLH_Config['maxdays'])) 
   $CSLH_Config['maxdays'] = 31;
 if($CSLH_Config['maxdays']<2)
   $CSLH_Config['maxdays'] = 2;
   
 $maxmonthskept = date("Ym",mktime ( 2, 0, 0, date("m") - $CSLH_Config['maxmonths'], date("d") -1, date("Y") ));
 $maxdayskept = date("Ymd",mktime ( 2, 0, 0, date("m"), date("d")-$CSLH_Config['maxdays'], date("Y") ));

 $hoursago= date("YmdHis",mktime ( date("H")-12, date("i"), date("s"), date("m") , date("d"), date("Y") ));
 
 $yesterday = date("YmdHis",mktime ( date("H")-1, date("i"), date("s"), date("m") , date("d")-1, date("Y") ));
 $sqlquery = "DELETE FROM livehelp_channels WHERE startdate < $yesterday";
 $mydatabase->query($sqlquery);  
 $sqlquery = "DELETE FROM livehelp_messages WHERE timeof < $yesterday";
 $mydatabase->query($sqlquery);  
 $sqlquery = "DELETE FROM livehelp_referers_daily WHERE dateof < $maxdayskept";
 $mydatabase->query($sqlquery); 
 $sqlquery = "DELETE FROM livehelp_visits_daily WHERE dateof < $maxdayskept";
 $mydatabase->query($sqlquery);   
 $sqlquery = "DELETE FROM livehelp_keywords_daily WHERE dateof < $maxdayskept";
 $mydatabase->query($sqlquery);   
 $sqlquery = "DELETE FROM livehelp_referers_monthly WHERE dateof < $maxmonthskept";
 $mydatabase->query($sqlquery); 
 $sqlquery = "DELETE FROM livehelp_paths_firsts WHERE dateof < $maxmonthskept";
 $mydatabase->query($sqlquery); 
 $sqlquery = "DELETE FROM livehelp_paths_monthly WHERE dateof < $maxmonthskept";
 $mydatabase->query($sqlquery);     
 $sqlquery = "DELETE FROM livehelp_visits_monthly WHERE dateof < $maxmonthskept";
 $mydatabase->query($sqlquery);  
 $sqlquery = "DELETE FROM livehelp_keywords_monthly WHERE dateof < $maxmonthskept";
 $mydatabase->query($sqlquery);  

 // if not txt-db-api and $CSLH_Config['tracking'] == "Y" insert visitor and referer information:
 if( ($dbtype != "txt-db-api") && ($CSLH_Config['tracking']=="Y") ){     
   $sqlquery = "SELECT sessionid FROM livehelp_visit_track WHERE whendone < $hoursago";
   $sth = $mydatabase->query($sqlquery);        
   while($old_user = $sth->fetchRow(DB_FETCHMODE_ORDERED)){
      archivefootsteps($old_user[0]);       
   }   
 }  
 $sqlquery = "DELETE FROM livehelp_visit_track WHERE whendone < $hoursago";
 $mydatabase->query($sqlquery);   
 
 if($dbtype!="txt-db-api"){
  $sqlquery = "OPTIMIZE TABLE `livehelp_referers_daily` , `livehelp_referers_monthly` , `livehelp_users` , `livehelp_visit_track` , `livehelp_visits_daily` , `livehelp_visits_monthly`,`livehelp_paths_monthly`, `livehelp_paths_firsts` ";	
  $mydatabase->query($sqlquery); 
 }
 
} // end random cleaning..

// visitor sessions more then five minutes old and archive them:
//-------------------------------------
$randomNumber = rand(1, 18);
if($randomNumber == 4){ 

   // operators more then 3 minutes old:
   $prev = mktime ( date("H"), date("i")-3, date("s")-30, date("m"), date("d"), date("Y") );
   $oldtime = date("YmdHis",$prev);     
   $sqlquery = "SELECT user_id,sessionid,camefrom FROM livehelp_users WHERE isoperator='Y' AND lastaction<$oldtime";
   $old_people = $mydatabase->query($sqlquery);     
   while($old_user = $old_people->fetchRow(DB_FETCHMODE_ORDERED)){ 
     $sql = "UPDATE livehelp_users SET isonline='N' WHERE user_id=".$old_user[0];
     $mydatabase->query($sql);
     $sql = "DELETE FROM livehelp_operator_channels  WHERE user_id=".$old_user[0] ." OR userid=" . $old_user[0];
     $mydatabase->query($sql);
          
   }
   
   // sessions more then 5 minutes old:
   $prev = mktime ( date("H"), date("i")-5, date("s"), date("m"), date("d"), date("Y") );
   $oldtime = date("YmdHis",$prev);  
   $sqlquery = "SELECT user_id,sessionid,camefrom FROM livehelp_users WHERE isoperator='N' AND lastaction<$oldtime";
   $old_people = $mydatabase->query($sqlquery);     
   while($old_user = $old_people->fetchRow(DB_FETCHMODE_ORDERED)){  	    	
   	 $user_id = $old_user[0]; 
   	 $sessionid = $old_user[1];   	 
     $camefrom = $old_user[2];     
     stopchat($sessionid);                
     // if not txt-db-api and $CSLH_Config['tracking'] == "Y" insert visitor and referer information:
     if($dbtype != "txt-db-api"){     
       if(!(empty($camefrom)) && ($CSLH_Config['reftracking']=="Y")){
     	   archivepage('livehelp_referers_daily',$camefrom,date("Ymd"));
     	   archivepage('livehelp_referers_monthly',$camefrom,date("Ym"));     	   
     	 }
     	 if ($CSLH_Config['tracking']=="Y")
     	   archivefootsteps($sessionid);       
     }	
    archiveuser($sessionid);    
         
    // Delete messages that are from auto-invites that are older then 50 minutes:
    $prev = mktime ( date("H"), date("i")-50, date("s"), date("m"), date("d"), date("Y") );
    $reallyoldtime = date("YmdHis",$prev);             
    $sqlquery = "DELETE FROM livehelp_messages WHERE timeof<'$reallyoldtime'";
    $mydatabase->query($sqlquery);       
  } // end of recordset of old users 
}// end rand 7 chance of archiving data. 



// Remove previous months old data:
//------------------------------------------------
 $randomNumber = rand(1, 999);
 $monthago = date("Ym",mktime ( 2, 0, 0, date("m"), date("d")-33, date("Y") ));
 if( ($dbtype != "txt-db-api") && ($CSLH_Config['tracking']=="Y") && ($randomNumber == 7) ){ 
 
  $sqlquery = "SELECT COUNT(*) as totalreferers FROM livehelp_referers_monthly WHERE dateof=$monthago AND parentrec=0";  
  $rs = $mydatabase->query($sqlquery); 
  $row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
  $totalreferers = $row[0];       
  if(empty($CSLH_Config['maxreferers'])) 
    $CSLH_Config['maxreferers'] = 50;
  // if we have more referers then we should have for previous month:
  if($totalreferers >$CSLH_Config['maxreferers']){ 
   	if($CSLH_Config['maxoldhits']>0){ 
      $query = "DELETE FROM livehelp_referers_monthly WHERE levelvisits<=".$CSLH_Config['maxoldhits'];
      $mydatabase->query($query);   
    }
  	 	
   // select out referers past the limit and delete them max 10,000 at a time.
   $query = "SELECT recno FROM livehelp_referers_monthly WHERE dateof=$monthago AND parentrec=0 ORDER by levelvisits DESC LIMIT " . $CSLH_Config['maxreferers'] .",1000";
   $sth = $mydatabase->query($query);  
   while($row = $sth->fetchRow(DB_FETCHMODE_ORDERED)){
       $deletedsofar = 0;
       	$graph = array();
	      $graph[] = $row[0];
       recursive_delete_pages($row[0],'livehelp_referers_monthly',$graph);
   }
  }
  
  $sqlquery = "SELECT COUNT(*) as totalvisits FROM livehelp_visits_monthly WHERE dateof=$monthago ";
  $rs = $mydatabase->query($sqlquery); 
  $row = $rs->fetchRow(DB_FETCHMODE_ASSOC);
  $totalvisits = $row['totalvisits'];  
  if(empty($CSLH_Config['maxvisits'])) 
    $CSLH_Config['maxvisits'] = 100;
  // if we have more visits then we should have for previous month:
  if($totalvisits >$CSLH_Config['maxvisits']){ 
  	 if($CSLH_Config['maxoldhits']>0){ 
      $query = "DELETE FROM livehelp_visits_monthly WHERE levelvisits<=".$CSLH_Config['maxoldhits'];
      $mydatabase->query($query);   
     }  	 
     $query = "SELECT recno FROM livehelp_visits_monthly WHERE dateof=$monthago ORDER by levelvisits DESC LIMIT ".$CSLH_Config['maxvisits'].",1000";
     $sth = $mydatabase->query($query);  
     while($row = $sth->fetchRow(DB_FETCHMODE_ORDERED)){
       $deletedsofar = 0;
       	$graph = array();
	      $graph[] = $row[0];       
       recursive_delete_pages($row[0],'livehelp_visits_monthly',$graph);
     }
  }	  
  
  // Delete old Keywords:
  $sqlquery = "SELECT COUNT(*) as totalkeywords FROM livehelp_keywords_monthly WHERE dateof=$monthago";
  $rs = $mydatabase->query($sqlquery); 
  $row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
  $totalkeywords = $row[0];       
  // if we have more keywords then we should have for previous month:
  if($totalkeywords >$CSLH_Config['topkeywords']){ 
     $query = "SELECT keywords FROM livehelp_keywords_monthly WHERE dateof=$monthago ORDER by levelvisits DESC LIMIT ".$CSLH_Config['topkeywords'].",1000";
     $sth = $mydatabase->query($query);  
     while($row = $sth->fetchRow(DB_FETCHMODE_ORDERED)){
     	  $keywords = filter_sql($row[0]);
        $q= "DELETE FROM livehelp_keywords_monthly WHERE keywords='$keywords'";
        $mydatabase->query($q); 
        $q= "DELETE FROM livehelp_keywords_daily WHERE keywords='$keywords'";        
        $mydatabase->query($q); 
     }
 
   }        
  
  
}

// If current database table is big:
//------------------------------------------------
 $randomNumber = rand(1, 999);
 $thismonth = date("Ym",mktime ( 2, 0, 0, date("m"), date("d"), date("Y") ));
 if( ($dbtype != "txt-db-api") && ($CSLH_Config['tracking']=="Y") && ($randomNumber == 17) ){ 
  if(empty($CSLH_Config['maxrecords'])) 
    $CSLH_Config['maxrecords'] = 50000;
  
  // livehelp_referers_daily
  $sqlquery = "SELECT COUNT(*) as totalreferers FROM livehelp_referers_daily WHERE dateof>$thismonth"."00 AND dateof<$thismonth"."31";
  $rs = $mydatabase->query($sqlquery); 
  $row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
  $totalreferers = $row[0];       
  // if we have more referers then we should have for previous month:
  if($totalreferers >$CSLH_Config['maxrecords']){ 
      $query = "DELETE FROM livehelp_referers_monthly WHERE levelvisits<=".$CSLH_Config['maxoldhits'];
      $mydatabase->query($query);   
   }
 // livehelp_referers_monthly
  $sqlquery = "SELECT COUNT(*) as totalreferers FROM livehelp_referers_monthly WHERE dateof=$thismonth";
  $rs = $mydatabase->query($sqlquery); 
  $row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
  $totalreferers = $row[0];       
  // if we have more referers then we should have for previous month:
  if($totalreferers >$CSLH_Config['maxrecords']){ 
      $query = "DELETE FROM livehelp_referers_monthly WHERE levelvisits<=".$CSLH_Config['maxoldhits'];
      $mydatabase->query($query);   
  }
 
  // livehelp_visits_total
  $sqlquery = "SELECT COUNT(*) as totalreferers FROM livehelp_visits_monthly WHERE dateof=$thismonth";
  $rs = $mydatabase->query($sqlquery); 
  $row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
  $totalreferers = $row[0];       
  // if we have more referers then we should have for previous month:
  if($totalreferers >$CSLH_Config['maxrecords']){ 
      $query = "DELETE FROM livehelp_visits_daily WHERE levelvisits<=".$CSLH_Config['maxoldhits'];
      $mydatabase->query($query);   
   }

  // livehelp_visits_daily
  $sqlquery = "SELECT COUNT(*) as totalreferers FROM livehelp_visits_daily WHERE dateof>$thismonth"."00 AND dateof<$thismonth"."31";
  $rs = $mydatabase->query($sqlquery); 
  $row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
  $totalreferers = $row[0];       
  // if we have more referers then we should have for previous month:
  if($totalreferers >$CSLH_Config['maxrecords']){ 
      $query = "DELETE FROM livehelp_visits_daily WHERE levelvisits<=".$CSLH_Config['maxoldhits'];
      $mydatabase->query($query);   
   }         
 
}

// if an operator left their computer but did not log off.. 
//-------------------------------------
$randomNumber = rand(1, 5);
if($randomNumber == 3){     

    // make chataction off  
    $prev = mktime ( date("H"), date("i")-5, date("s"), date("m"), date("d"), date("Y") );
    $oldtime = date("YmdHis",$prev);
    $query = "SELECT user_id,chataction,sessionid FROM livehelp_users WHERE isoperator='Y' AND chataction<'$oldtime' AND chataction>10 ";
    $data2 = $mydatabase->query($query); 
    while($row = $data2->fetchRow(DB_FETCHMODE_ASSOC)){
    	 $opid = $row['user_id'];
       $chataction = $row['chataction'];
       $sessionid = $row['sessionid'];
        // get when they logged in and how many seconds they have been online:
        $query = "SELECT dateof FROM livehelp_operator_history WHERE opid=$opid AND action='Started Chatting' ORDER by dateof DESC LIMIT 1";
        $data3 = $mydatabase->query($query);
        $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
        $seconds = timediff($chataction,$row3['dateof']);
        
        // update history for operator to show login:
        $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid,totaltime) VALUES ($opid,'Stopped Chatting','$chataction','$sessionid',$seconds)";
        $mydatabase->query($query);
        
       $query = "UPDATE livehelp_users set chataction='0' WHERE user_id=$opid";
       $mydatabase->query($query); 
 	  }
 	  
    // make operator not online if not in monitor traffic area for 4 minutes...
    $prev = mktime ( date("H"), date("i")-4, date("s"), date("m"), date("d"), date("Y") );
    $oldtime = date("YmdHis",$prev);
    $query = "SELECT user_id,showedup,sessionid FROM livehelp_users WHERE isoperator='Y' AND showedup<'$oldtime' AND showedup>10 ";
    $data2 = $mydatabase->query($query); 
    while($row = $data2->fetchRow(DB_FETCHMODE_ASSOC)){
    	 $opid = $row['user_id'];
       $showedup = $row['showedup'];
       $sessionid = $row['sessionid'];
        // get when they logged in and how many seconds they have been online:
        $query = "SELECT dateof FROM livehelp_operator_history WHERE opid=$opid AND action='Started Monitoring Traffic' ORDER by dateof DESC LIMIT 1";
        $data3 = $mydatabase->query($query);
        $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
        $seconds = timediff($showedup,$row3['dateof']);
        
        // update history for operator to show login:
        $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid,totaltime) VALUES ($opid,'Stopped Monitoring Traffic','$showedup','$sessionid',$seconds)";
        $mydatabase->query($query);
        
       // log them off:
       $query = "UPDATE livehelp_users set isonline='N',status='offline',showedup=0 WHERE user_id=$opid";
       $mydatabase->query($query); 
 	  }
 	  
	
    $prev = mktime ( date("H"), date("i")-20, date("s"), date("m"), date("d"), date("Y") );
    $oldtime = date("YmdHis",$prev);
    $query = "SELECT user_id,lastaction,sessionid FROM livehelp_users WHERE isoperator='Y' AND authenticated='Y' AND lastaction<'$oldtime'";
    $data2 = $mydatabase->query($query); 
    while($row = $data2->fetchRow(DB_FETCHMODE_ASSOC)){
    	 $opid = $row['user_id'];
       $lastaction = $row['lastaction'];
       $sessionid = $row['sessionid'];
        // get when they logged in and how many seconds they have been online:
        $query = "SELECT dateof FROM livehelp_operator_history WHERE opid=$opid AND action='login' ORDER by dateof DESC LIMIT 1";
        $data3 = $mydatabase->query($query);
        $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
        $seconds = timediff($lastaction,$row3['dateof']);
        
        // update history for operator to show login:
        $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid,totaltime) VALUES ($opid,'Logout','$lastaction','$sessionid',$seconds)";
        $mydatabase->query($query);
        
       // log them off:
       $query = "UPDATE livehelp_users set authenticated='N',isonline='N',status='offline' WHERE user_id=$opid";
       $mydatabase->query($query); 
 	  }
}

    
// chatters who closed their chat window timeout after a minute of inactivity..:
 $aminute = mktime ( date("H"), date("i")-1, date("s")-30, date("m"), date("d"), date("Y") );
 $oldchat = date("YmdHis",$aminute);
 $sqlquery = "SELECT sessionid,user_id FROM livehelp_users WHERE status='chat' AND isoperator='N' AND chataction<$oldchat";
 $old_people = $mydatabase->query($sqlquery);   
 while($old_chat = $old_people->fetchRow(DB_FETCHMODE_ORDERED)){
  	  $sessionid = $old_chat[0]; 
  	  $user_id = $old_chat[1];
      stopchat($sessionid);    
 } // end of old chats where user closed chat window.
   

?>