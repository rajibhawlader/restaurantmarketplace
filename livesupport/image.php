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
// P3P should work but it does not WTF?!?!
//Header("P3P: CP=\"ALL DSP COR NID CURa OUR STP PUR\""); 

// backwards compatability (renamed because of modsecurity)
if(!(empty($UNTRUSTED['cmd']))){ $UNTRUSTED['what'] = $UNTRUSTED['cmd']; }

// default command:
if(empty($UNTRUSTED['what'])){ $UNTRUSTED['what'] = "getstate"; }

// if this is a credit image ghost the session:
if($UNTRUSTED['what'] == "getcredit")
  $ghost_session = true;

require_once("visitor_common.php");
if(empty($UNTRUSTED['what'])){ $UNTRUSTED['what'] = "getstate"; }

 
if(!(isset($UNTRUSTED['hide']))){ $UNTRUSTED['hide'] = ""; }

if(empty($UNTRUSTED['message_test'])) 
   $message_test = 0;
else {
  if (is_numeric($UNTRUSTED['message_test']))
     $message_test = $UNTRUSTED['message_test'];  
  else
     $message_test =0; 
}
 
require_once("gc.php"); 

// auto invite user:
autoinvite($identity,$status,$department);

  
// ping the chat window.
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "pingchat"){
	$rightnow = date("YmdHis");
  $sqlquery = "UPDATE livehelp_users set chataction='$rightnow',lastaction='$rightnow' WHERE sessionid='".$identity['SESSIONID']."' ";
  $mydatabase->query($sqlquery);

    $filepath = "images/controlimage_noaction.gif"; 
    showimage($filepath,"image/gif");
    exit; 
}
 
// TODO: all commands should be in a switch statement and the 
//       actions for each command be a separate function.. However,
//       I will be programming that in a status image class in version 3.1.0 
//       so live with it this way for right now .. ok :-)

// Get the layerinvite sent..
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "getlayerinvite"){

   $sqlquery = "SELECT user_id,status,sessiondata FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
   $data = $mydatabase->query($sqlquery);    
   if($data->numrows() !=0){
    $visitor = $data->fetchRow(DB_FETCHMODE_ORDERED);
    $user_id = $visitor[0];
    $status = $visitor[1];
    $sessiondata = $visitor[2];
    
        
     $filepath = "images/requestDHTML.gif"; 
     // get type of layer:
     $datapairs = explode("&",$sessiondata);
     $datamessage="";
     for($l=0;$l<count($datapairs);$l++){
        $dataset = explode("=",$datapairs[$l]);
        if( (!(empty($dataset[1])) && ($dataset[0] == "invite"))){
             $layerid = $dataset[1];
             $hundreds = floor($layerid/100);
             $tens = floor(($layerid - $hundreds*100)/10);
             $ones = $layerid - $hundreds*100 - $tens*10;
             if($UNTRUSTED['whatplace'] == "ones")
               $digit = $ones;
             if($UNTRUSTED['whatplace'] == "tens")
               $digit = $tens;
             if($UNTRUSTED['whatplace'] == "hundreds")
               $digit = $hundreds;    
             switch($digit){
                    case 0:
                        $filepath = "images/digit0.gif"; 
                      break;
                    case 1:
                        $filepath = "images/digit1.gif"; 
                      break;                    
                    case 2:
                        $filepath = "images/digit2.gif"; 
                      break;                    
                    case 3:
                        $filepath = "images/digit3.gif"; 
                      break;                    
                    case 4:
                        $filepath = "images/digit4.gif"; 
                      break;
                    case 5:
                        $filepath = "images/digit5.gif"; 
                      break;
                    case 6:
                        $filepath = "images/digit6.gif"; 
                      break;
                    case 7:
                        $filepath = "images/digit7.gif"; 
                      break;
                    case 8:
                        $filepath = "images/digit8.gif"; 
                      break;
                    case 9:
                        $filepath = "images/digit9.gif"; 
                      break;
                 }                                                   
                                                                        
                                                                     
                                    
       }
     }   
   }    
    showimage($filepath,"image/gif");
    exit;  
}

// Check to see if I am chatting...
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "channelcheck"){
	
	// time out: 
	if($UNTRUSTED['dieat'] < date("YmdHis")){
		
    $filepath = "images/digit1.gif"; 
    showimage($filepath,"image/gif");  
    exit; 
	} else {	
    $sqlquery = "SELECT channel FROM livehelp_operator_channels WHERE channel=" . intval($channel) ." AND userid=".intval($myid) . " LIMIT 1";
    $justchecking = $mydatabase->query($sqlquery); 
    if($justchecking->numrows() == 0){
      $rightnow = date("YmdHis");
      $sqlquery = "UPDATE livehelp_users SET chataction=$rightnow,status='chat' WHERE sessionid='".$identity['SESSIONID']."'";
      $mydatabase->query($sqlquery);  
      $filepath = "images/controlimage_noaction.gif"; 
      showimage($filepath,"image/gif"); 
      exit; 
    } else {
      $filepath = "images/controlimage_action.gif";
      showimage($filepath,"image/gif");  
      exit; 
    }
  }
  exit;
}

// Check to see if I am still talking..
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "talkative"){
	
  $sqlquery = "SELECT sessionid FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."' AND status='chat' LIMIT 1";
  $justchecking = $mydatabase->query($sqlquery);

  if($justchecking->numrows() == 0){
    $filepath = "images/controlimage_noaction.gif"; 
    showimage($filepath,"image/gif");  
    exit; 
  } else {
    $filepath = "images/controlimage_action.gif";
    showimage($filepath,"image/gif"); 
    exit;   	
  }
  exit;
}


// Check to see if a reload is needed based on message timestamp..
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "messagecheck"){
	
  $sqlquery = "SELECT timeof FROM livehelp_messages WHERE typeof !='writediv' AND timeof>$message_test";
  $justchecking = $mydatabase->query($sqlquery);
  if($justchecking->numrows() == 0){
    $filepath = "images/controlimage_noaction.gif"; 
    showimage($filepath,"image/gif"); 
    exit; 
  } else {
    $filepath = "images/controlimage_action.gif";
    showimage($filepath,"image/gif");
    exit;   	
  }
  exit;
}


// check to see if a reload is needed.. also update user timestamp.
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "usercheck"){
  $peoplestring = "users";
  if(!(empty($UNTRUSTED['showvisitors'])))
    $sqlquery = "SELECT sessionid,status FROM livehelp_users ORDER by user_id DESC";      
  else
    $sqlquery = "SELECT sessionid,status FROM livehelp_users WHERE status='chat' ORDER by user_id DESC";       

  $visitors = $mydatabase->query($sqlquery);
  while( $visitor = $visitors->fetchRow(DB_FETCHMODE_ORDERED)){
    $visitor_string = $visitor[0] . $visitor[1];
    $user = "_" . ereg_Replace("([^a-zA-Z0-9])*", "", $visitor_string);
    $peoplestring .= str_replace(" ","",$user);
  } 

  $sqlquery = "SELECT user_id FROM livehelp_operator_channels ORDER by user_id DESC";        
  $visitors = $mydatabase->query($sqlquery);
  while($visitor = $visitors->fetchRow(DB_FETCHMODE_ORDERED)){
    $visitor_string = $visitor[0];
    $peoplestring .= str_replace(" ","",$user);
  } 
   
  if(empty($UNTRUSTED['peoplestring_test'])){  $UNTRUSTED['peoplestring_test'] = $peoplestring; }
    
  if($UNTRUSTED['peoplestring_test'] == $peoplestring){
    $filepath = "images/controlimage_noaction.gif"; 
    showimage($filepath,"image/gif"); 
    exit; 
  } else {
    $filepath = "images/controlimage_action.gif";
    showimage($filepath,"image/gif"); 
    exit;    	
  }
  exit;
}


// userstat: return the control image for this user. 
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "changestat"){
   
   $rightnow = date("YmdHis");
   $sqlquery = "UPDATE livehelp_users set status='".filter_sql($UNTRUSTED['towhat'])."' WHERE (status='' OR status='DHTML' OR status='invited' OR status='request')  AND sessionid='".$identity['SESSIONID']."'";
   $mydatabase->query($sqlquery);
if(!($serversession))
      $mydatabase->close_connect();
   $filepath = "images/browse.gif";
   showimage($filepath,"image/gif");
   exit; 
 
}

 
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "alive"){
   print $CSLH_Config['version'] . "-";
   $sqlquery = "SELECT department FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' ";
   $data = $mydatabase->query($sqlquery);  
  if($data->numrows() == 0){
    print "offline";
  } else {
    while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
      print $row[0] . ",";
    }
  }
if(!($serversession))  
  $mydatabase->close_connect();
  exit; 
}

//----------------------------------------------------------------
if($UNTRUSTED['what'] == "startedtyping"){       
   $timeof = date("YmdHis") - 1; 
  
   if(!(empty($UNTRUSTED['channelsplit'])))
     $array = split("__",$UNTRUSTED['channelsplit']);
   else
     $array = array();
     
    if(isset($array[1])){
      $saidto = $array[1];    
      $channel = $array[0];
    } else {
      $channel = $UNTRUSTED['channelsplit'];
    }
    
    if(intval($channel)==0)
       $channel = intval(trim($UNTRUSTED['channelsplit']));
   
   // see if we currently have a write layer if not create it.
   $sqlquery = "SELECT typeof FROM livehelp_messages WHERE typeof='writediv' AND channel=".intval($channel)." AND saidfrom=".intval($UNTRUSTED['fromwho']) . " LIMIT 1";
   $checking = $mydatabase->query($sqlquery); 

   // if not create one
   if($checking->numrows() == 0){
      if (empty($saidto)){ $saidto = -1; }
      $sqlquery = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto,typeof) VALUES ('".filter_sql(convertamps($UNTRUSTED['sayingwhat']))."',".intval($channel).",'$timeof',".intval($UNTRUSTED['fromwho']).",".intval($saidto).",'writediv')";	
      $mydatabase->query($sqlquery);
   }      

   // see if we have something to update...
   $sqlquery = "SELECT typeof FROM livehelp_messages WHERE message='".filter_sql(convertamps($UNTRUSTED['sayingwhat']))."' AND typeof='writediv' AND channel=".intval($channel)." AND saidfrom=".intval($UNTRUSTED['fromwho']) . " LIMIT 1";
   $checking = $mydatabase->query($sqlquery); 
   // if so update it
   if($checking->numrows() == 0){
     $sqlquery = "UPDATE livehelp_messages set timeof='". date("YmdHis") . "',message='".filter_sql(convertamps($UNTRUSTED['sayingwhat']))."' WHERE typeof='writediv' AND channel=".intval($channel)." AND saidfrom=".intval($UNTRUSTED['fromwho']);
     $mydatabase->query($sqlquery);
   }    
if(!($serversession))   
   $mydatabase->close_connect();
   $filepath = "images/browse.gif";   
   showimage($filepath,"image/gif");  
   exit; 
}

//----------------------------------------------------------------
if($UNTRUSTED['what'] == "browse"){
    if(!($serversession))
      $mydatabase->close_connect();
      $filepath = "images/browse.gif";       
      showimage($filepath,"image/gif"); 
      exit; 
}

// userstat: return the control image for this user. 
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "userstat"){    
    
   // begin stuff to do with current users:
   // now..
   $rightnow = date("YmdHis");
   // see if we already have the page they are on.
   if(empty($pageid)){ $pageid = 1; } // page id sent from javascript   
   if(empty($UNTRUSTED['page'])) $UNTRUSTED['page'] = "";
   if(empty($UNTRUSTED['pageid'])){ $UNTRUSTED['pageid'] = 1; }
   if(empty($UNTRUSTED['page'])){ $UNTRUSTED['page'] = ""; }
   if(empty($UNTRUSTED['title'])){ $UNTRUSTED['title'] = ""; }  
   if(empty($UNTRUSTED['referer'])){ $UNTRUSTED['referer'] = ""; }    
    
   $sqlquery = "SELECT user_id,status,sessiondata FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."' LIMIT 1";
   $data = $mydatabase->query($sqlquery);	
   if($data->numrows() !=0){
    $visitor = $data->fetchRow(DB_FETCHMODE_ORDERED);
    $user_id = $visitor[0];
    $status = $visitor[1];
    $sessiondata = $visitor[2];
       
    // get the last page we recorded the user was on if it is not the page that they
    // are on now then insert the new page:	
    $sqlquery = "SELECT location,page FROM livehelp_visit_track WHERE sessionid='".$identity['SESSIONID']."' ORDER by recno DESC LIMIT 1";
    $data_tmp = $mydatabase->query($sqlquery);
    $row_tmp = $data_tmp->fetchRow(DB_FETCHMODE_ORDERED);
    
    if( substr($row_tmp[0],0,250) != substr($UNTRUSTED['page'],0,250) ){  // location != page  	
      // check page ids:
      $sqlq = "SELECT page FROM livehelp_visit_track WHERE location='".filter_sql(substr($UNTRUSTED['page'],0,250))."' AND page=".intval($UNTRUSTED['pageid'])." LIMIT 1";
      $data_tmp2 = $mydatabase->query($sqlq);
      if($data_tmp2->numrows() ==0){
       $sqlquery = "INSERT INTO livehelp_visit_track (sessionid,location,page,title,whendone,referrer) VALUES 
       ('".$identity['SESSIONID']."','".filter_sql(substr($UNTRUSTED['page'],0,250))."',".intval($UNTRUSTED['pageid']).",'".filter_sql($UNTRUSTED['title'])."','$rightnow','".filter_sql($UNTRUSTED['referer'])."')";
       $mydatabase->query($sqlquery);
      }
     }
   }
     
   // update their last action to now..
   $sqlquery = "UPDATE livehelp_users set lastaction='$rightnow' WHERE sessionid='".$identity['SESSIONID']."'";
   $mydatabase->query($sqlquery);	
   if(!($serversession))
   $mydatabase->close_connect();
    
    // see if the operator wants anything with them:
    if($status == "request"){
     $filepath = "images/requestchat.gif";      
     showimage($filepath,"image/gif");
     exit; 
    } elseif ($status == "DHTML") {
     
     $filepath = "images/requestDHTML.gif"; 
     showimage($filepath,"image/gif"); 
     exit;     	
    } else {	
     $filepath = "images/browse.gif";
     showimage($filepath,"image/gif");
     exit; 
    }
}

//give credit to the programmer .. 
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "getcredit"){

if($department!=0) 	 	
   $sqlquery = "SELECT isonline FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' AND livehelp_operator_departments.department=".intval($department);
else
   $sqlquery = "SELECT isonline FROM livehelp_users,livehelp_operator_departments WHERE livehelp_users.user_id=livehelp_operator_departments.user_id AND livehelp_users.isonline='Y' AND livehelp_users.isoperator='Y' ";

   $data = $mydatabase->query($sqlquery);  
  if( $data->numrows() != 0){
    if(!($serversession))
      $mydatabase->close_connect();
      if( ($creditline == "L") || ($creditline == "")){
       $filepath = "images/livehelp.gif";
       showimage($filepath,"image/gif"); 
       exit; 
    } 
    if($creditline == "W"){
       $filepath = "images/livehelp2.gif";
       showimage($filepath,"image/gif"); 
       exit;    
    }
    if($creditline == "N"){
       $filepath = "images/livehelp3.gif";
       showimage($filepath,"image/gif");  
       exit;       
    }  
  } else {  
    if(!($serversession))  	
       $mydatabase->close_connect();  	
    if($leaveamessage == "YES"){
      if( ($creditline == "L") || ($creditline == "")){
        $filepath = "images/livehelp.gif";
        showimage($filepath,"image/gif"); 
        exit; 
      } 
      if($creditline == "W"){
       $filepath = "images/livehelp2.gif";
       showimage($filepath,"image/gif"); 
       exit; 
      }
      if($creditline == "N"){
       $filepath = "images/livehelp3.gif";
       showimage($filepath,"image/gif");  
       exit; 
      }     
   } else {	      
       $filepath = "images/livehelp3.gif";
       showimage($filepath,"image/gif"); 
       exit; 
   }
 }
}

// are we online or not.. 
//----------------------------------------------------------------
if($UNTRUSTED['what'] == "getstate"){

   if(empty($UNTRUSTED['referer']))
       $UNTRUSTED['referer'] = "";
   
    $sqlquery = "SELECT user_id,camefrom FROM livehelp_users WHERE sessionid='".filter_sql($identity['SESSIONID'])."'";
    $data = $mydatabase->query($sqlquery);	
    $visitor = $data->fetchRow(DB_FETCHMODE_ORDERED);
    $user_id = $visitor[0];
    $camefrom = $visitor[1];
    
    // now..
    $rightnow = date("YmdHis");
    // update the visitors tracks.
    // see if we already have the page they are on.
    if(empty($UNTRUSTED['pageid'])){ $UNTRUSTED['pageid'] = 1; }
    if(empty($UNTRUSTED['page'])){ $UNTRUSTED['page'] = ""; }
    if(empty($UNTRUSTED['title'])){ $UNTRUSTED['title'] = ""; }  
   
    if($data->numrows() !=0){
      $sqlquery = "SELECT location FROM livehelp_visit_track WHERE sessionid='".filter_sql($identity['SESSIONID'])."' ORDER by recno DESC LIMIT 1";
      $data_tmp = $mydatabase->query($sqlquery);
      $row_tmp = $data_tmp->fetchRow(DB_FETCHMODE_ORDERED);
      if( substr($row_tmp[0],0,250) != substr($UNTRUSTED['page'],0,250) ){  
       $sqlquery = "INSERT INTO livehelp_visit_track (sessionid,location,page,title,whendone,referrer) VALUES ('".filter_sql($identity['SESSIONID'])."','".filter_sql(substr($UNTRUSTED['page'],0,250))."',".intval($UNTRUSTED['pageid']).",'".filter_sql($UNTRUSTED['title'])."','$rightnow','".filter_sql($UNTRUSTED['referer'])."')";
       $mydatabase->query($sqlquery);
     }
    }
    
    if( ($camefrom =="") && (!(empty($UNTRUSTED['referer']))) ){
     $sqlquery = "UPDATE livehelp_users 
               SET camefrom='".filter_sql($UNTRUSTED['referer'])." 
               WHERE sessionid='".$identity['SESSIONID']."'";
     $mydatabase->query($sqlquery);	
    }
   
   if($department!=0) {
     $sqlquery = "SELECT lastaction 
             FROM livehelp_users,livehelp_operator_departments 
             WHERE livehelp_users.user_id=livehelp_operator_departments.user_id 
                AND livehelp_users.isonline='Y' 
                AND livehelp_users.isoperator='Y' 
                AND livehelp_operator_departments.department=".intval($department);
   } else {
      $sqlquery = "SELECT lastaction 
             FROM livehelp_users,livehelp_operator_departments 
             WHERE livehelp_users.user_id=livehelp_operator_departments.user_id 
                AND livehelp_users.isonline='Y' 
                AND livehelp_users.isoperator='Y'"; 
   }
   $sqlquery .= " ORDER by lastaction LIMIT 1";

    // see if they left their computer but did not log off.. 
    $prev = mktime ( date("H"), date("i")-25, date("s"), date("m"), date("d"), date("Y") );
    $oldtime = date("YmdHis",$prev);
    $query = "SELECT user_id FROM livehelp_users WHERE isoperator='Y' AND authenticated='Y' AND lastaction<'$oldtime'";
    $data2 = $mydatabase->query($query); 
    while($row = $data2->fetchRow(DB_FETCHMODE_ASSOC)){
    	 $opid = $row['user_id'];
        
        // get when they logged in and how many seconds they have been online:
        $query = "SELECT dateof FROM livehelp_operator_history WHERE opid=$opid AND action='login' ORDER by dateof DESC LIMIT 1";
        $data3 = $mydatabase->query($query);
        $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
        $seconds = timediff(date("YmdHis"),$row3['dateof']);
        
        // update history for operator to show login:
        $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,sessionid,totaltime) VALUES ($opid,'Logout','".date("YmdHis")."','".$identity['SESSIONID']."',$seconds)";
        $mydatabase->query($query);
        
       // log them off:
       $query = "UPDATE livehelp_users set authenticated='N',isonline='N',status='offline' WHERE user_id=$opid";
       $mydatabase->query($query); 
 	  }
   
   $data = $mydatabase->query($sqlquery);      
   if(!($serversession))           
     $mydatabase->close_connect();   
   if($UNTRUSTED['hide'] == "Y"){
      $filepath = "images/livehelp3.gif";
      showimage($filepath,"image/gif");
      exit;   	
   } else {
     if( $data->numrows() != 0){ 
       $filepath = $onlineimage;
       showimage($filepath);
       exit;         
     } else {          
       if($leaveamessage == "YES"){           
         $filepath = $offlineimage;
         showimage($filepath);         
        // end if $leaveamessage == "YES"
        } else {	
        	// blank image:
          $filepath = "images/livehelp3.gif";
          showimage($filepath,"image/gif"); 
          exit; 
        }
     }
   }
}

?>