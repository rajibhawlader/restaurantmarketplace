<?php
$Version="2.5";
//File version 2.5
//This file will be required for special features in LC2 such as 
//notifications and modifying online/offline status using LC2 options.
//error_reporting (E_ALL);
error_reporting (0);

require_once("admin_common.php");
validate_session($identity);


$timeof = date("YmdHis");

 $prev = mktime ( date("H"), date("i")-4, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 $sqlquery = "SELECT * FROM livehelp_users WHERE lastaction>'$oldtime'";

$visitors = $mydatabase->query($sqlquery);
$VisitorCount=0; $OnlineStatus="N"; $ChatCount=0;
$UserInfo=""; $TimeOnline=""; $CurLocation="";
while( $visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
              if ($visitor['isoperator']=="N") {                  
                        if ($visitor['status']=="chat") {$ChatCount++;}
			$TrackQuery="SELECT * FROM livehelp_visit_track WHERE sessionid='".$visitor['sessionid']."'";
			$Tracks = $mydatabase->query($TrackQuery);
			while($tracking = $Tracks->fetchRow(DB_FETCHMODE_ASSOC)){
				$CurLocation= $tracking['location'];
				$later = $tracking['whendone'];
				$TimeOnline=secondstoHHmmss(timediff($later,date("YmdHis")));

			}
			$UserInfo =$visitor['username']."::".$visitor['ipaddress']."::".$visitor['status']."::".$visitor['camefrom']."::".$TimeOnline."::".$CurLocation.",,".$UserInfo;
              }  
      if ($visitor['username']==$_GET['username']) { $OnlineStatus=$visitor['isonline']; $MyUserID=$visitor['user_id'];}

}


 // get the count of active visitors in the system right now.
 $timeof = date("YmdHis");
 $prev = mktime ( date("H"), date("i")-4, date("s"), date("m"), date("d"), date("Y") );
 $oldtime = date("YmdHis",$prev);
 $sqlquery = "SELECT * FROM livehelp_users WHERE lastaction>'$oldtime' AND isoperator!='Y' ";



  // make list of ignored visitors:
 $ipadd = split(",",$CSLH_Config['ignoreips']);
 for($i=0;$i<count($ipadd); $i++){
        if(!(empty($ipadd[$i])))
         $sqlquery .= " AND ipaddress NOT LIKE '".$ipadd[$i]."%' ";
 }

 $visitors = $mydatabase->query($sqlquery);

$onlinenow = $visitors->numrows();
 
 while($visitor = $visitors->fetchRow(DB_FETCHMODE_ASSOC)){
   $chatting = "";
    // see if we are in the same department as this user..
   $sqlquery = "SELECT * FROM livehelp_operator_departments WHERE user_id='$myid' AND department='".$visitor['department']."' ";
   $data_check = $mydatabase->query($sqlquery);
   if( ($visitor['department']!=0) && ($data_check->numrows() == 0) ){
       $hiddennow++;
       $onlinenow--;
   }
}
 
$VisitorCount=$onlinenow; 


//Working on UserInfo
//echo  "--$UserInfo--";




echo "VisitorCount=$VisitorCount; OnlineStatus=$OnlineStatus; ChatCount=$ChatCount; Visitors=$UserInfo; Version=$Version;";


//////This section will be for setting user online and offline.
///&online_status=Y&status=online
if(!(empty($_GET['online_status']))) {
		$online_status = filter_sql($UNTRUSTED['online_status']);
    $status = filter_sql($UNTRUSTED['status']);
		$query = "UPDATE livehelp_users set isonline='$online_status',lastaction='$timeof',status='$status' WHERE sessionid='".$identity['SESSIONID']."'";
	$mydatabase->query($query);
}

?>