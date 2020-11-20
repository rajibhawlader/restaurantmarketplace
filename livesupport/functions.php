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
 

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Converts any emotion strings found in the buffer to the img src tags.
  *
  * This is called after html characters are already converted.
  *
  * @param string $buffer the text to convert smiles out of.
  *
  * @global object $mydatabase mysql database object.
  *
  * @return string $buffer the converted string.
  */
function convert_smile($buffer){
	Global $mydatabase,$CSLH_Config,$UNTRUSTED;
	
	 $sqlquery = "SELECT code,smile_url FROM livehelp_smilies order by code DESC";
   $rs = $mydatabase->query($sqlquery); 
   while($row = $rs->fetchRow(DB_FETCHMODE_ORDERED)){
   	 $code = $row[0];
   	 $smile_url = $row[1];
   	 if($code != ""){
       $changethis = $code;
       if ( isset($UNTRUSTED['secure']) || ((isset($_SERVER["HTTPS"] ) && stristr($_SERVER["HTTPS"], "on"))) )
          $tothis = "<img src=".$CSLH_Config['s_webpath']."chat_smiles/" . $smile_url . ">";
       else
          $tothis = "<img src=".$CSLH_Config['webpath']."chat_smiles/" . $smile_url . ">";
           
       $buffer = str_replace($changethis,$tothis,$buffer);
     }
   }

   if ( isset($UNTRUSTED['secure']) || ((isset($_SERVER["HTTPS"] ) && stristr($_SERVER["HTTPS"], "on"))) )   
      $buffer = str_replace("[SMILE]","<img src=".$CSLH_Config['s_webpath']."chat_smiles/",$buffer);
   else
      $buffer = str_replace("[SMILE]","<img src=".$CSLH_Config['webpath']."chat_smiles/",$buffer);
      	
	
	 $buffer = str_replace("[/SMILE]",">",$buffer);
return $buffer;	
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/** 
  * note_access - see if this user has access to this note..
  * 
  * @param string $visiblity    is either Everyone, Department or private
  * @param string $departments  list of departments separated by commas.
  * @param string $user the     username
  *
  * @global object $mydatabase mysql database object.
  * @global int    $myid       users id.
  *
  * @return bool true if they have access false otherwize
  */ 
function note_access($visiblity,$departments,$user){
   global $mydatabase,$myid;
   
   if( ($visiblity == '') || ($visiblity == "Everyone")) return true;
   if($visiblity == 'Private') return ($user==$myid);   
   if($visiblity == 'Department'){
     // get the departments that this user is in...
     $department_array = split(",",$departments);
     if(($n=count($department_array))>0){
       $sqlquery = "SELECT department 
                 FROM livehelp_operator_departments 
                 WHERE user_id=". intval($myid) . "
                   AND ( department='".$department_array[--$n]."' ";
     while($n>0) $sqlquery .= " OR department='".$department_array[--$n]."' ";
     $sqlquery .= " )";
     $rs = $mydatabase->query($sqlquery); 
     if($rs->numrows()>0)
    	     return true; 
     
     }
   }  
 return false; 
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Is operator. given an id return true if this is an operator false otherwize..
 
  *
  * @return bool  
  */ 
function is_operator($thisid){
  global $mydatabase,$dbtype;
  
  $sqlquery = "SELECT jsrn FROM livehelp_users WHERE isoperator='Y' AND user_id=".intval($thisid);
  $rs = $mydatabase->query($sqlquery);	
  if($rs->numrows() != 0){ 
  	return true; 
  }	
  	return false; 
}


  //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * converts excaped values from javascript request.
  * s.replace("&","*amp*");
  * s.replace("=","*equal*");
  * s.replace("+","*plus*");
  * s.replace("%","*percent*");
  *
  * @return string
  */ 
function convertamps($source) {
 $decodedStr = str_replace("*amp*","&",$source);
 $decodedStr = str_replace("*plus*","+",$decodedStr); 
 $decodedStr = str_replace("*equal*","=",$decodedStr); 
 $decodedStr = str_replace("*hash*","#",$decodedStr); 
 $decodedStr = str_replace("*percent*","%",$decodedStr);
return $decodedStr;
} 

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Java Script Registery Number (JSRN) - Used for DHTML is typing script.
  *
  * @param array  $identity   array containing session/user information.
  *
  * @global object $mydatabase mysql database object.
  *
  * @return int  the jsrn number that identifies this user.
  */ 
function get_jsrn($identity){
  global $mydatabase,$dbtype;
  
  $sqlquery = "SELECT jsrn FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
  $rs = $mydatabase->query($sqlquery);	
  if($rs->numrows() != 0){  	  
    $row = $rs->fetchRow(DB_FETCHMODE_ORDERED);
    $jsrn = intval($row[0]);    
    // if jsrn is zero then the user has not been assigned a jsrn find one:
    if( ($jsrn == 0) || ($jsrn=="") ){     	 	   	
    	// DISTINCT does not work well in txt-db-api:
    	if($dbtype=="txt-db-api")
       	$sqlquery = "SELECT jsrn FROM livehelp_users WHERE jsrn!=0 ORDER by jsrn"; 
      else
       	$sqlquery = "SELECT DISTINCT jsrn FROM livehelp_users WHERE jsrn>0 ORDER by jsrn";          	
    	$rs = $mydatabase->query($sqlquery);
    	$jsrn = 1;
    	while($row = $rs->fetchRow(DB_FETCHMODE_ORDERED)){
      	   if($row[0]!=$jsrn) break; // Gap found.
      	   $jsrn++;
    	}
      $sqlquery = "UPDATE livehelp_users SET jsrn=".intval($jsrn)." WHERE sessionid='".$identity['SESSIONID']."' ";
      $mydatabase->query($sqlquery); 
    }
    return $jsrn;        
   } else {
     // No user with that sessionID exists:
     return 9;
  }
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Gets the next Channel Color.
  *
  * @global object $mydatabase mysql database object.
  *
  * @return string  the hex of the next color
  */ 
function get_next_channelcolor(){
    global $mydatabase;    
  
   // generate random Hex..
    $nextcolor = "";
    $highletters = array("C","D","E","F");
    for ($index = 1; $index <= 6; $index++) {
       $randomindex = rand(0,3); 
       $nextcolor .= $highletters[$randomindex];
    }	
   return $nextcolor;  
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**  
  * finds users ip address and returns it. 
  *
  * @param string $PHPSESSID session Id 
  *
  * @global array  $_SERVER 
  * @global string  $REMOTE_ADDR 
  *
  * @return string the ip address.
  */
function get_ipaddress(){
	Global $REMOTE_ADDR;
	
  if(empty($REMOTE_ADDR)) 
    $REMOTE_ADDR = "";
    
	// get their ip address Code from PHPBB2
  if( getenv('HTTP_X_FORWARDED_FOR') != '' ){
    $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
    $entries = explode(',', getenv('HTTP_X_FORWARDED_FOR'));
    reset($entries);
    while (list(, $entry) = each($entries)){
	    $entry = trim($entry);
	    if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list) ){
	    	 $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/', '/^224\..*/', '/^240\..*/');
		     $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
		     if ($client_ip != $found_ip){
			     $client_ip = $found_ip;
			     break;
		     }
	    }
    } // while list.
   } else {
      $client_ip = ( !empty($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : ( ( !empty($_ENV['REMOTE_ADDR']) ) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR );
   }
   return $client_ip;
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**  
  * given the users ip address, and session name create identity string.
  *
  * Identity is the class C of the host they are accessing from concatinated with the 
  * name of their session.. It is used to identify users without cookies or sessions
 
  * EXAMPLE:
  * IP: 152.163.253.35  SessionName: visitor
  * will result with the identity of: 152.163.253-visitor 
  *
  * @param string $hostname the host name
  * @param string $ipaddress the ipaddress
  *
  * @return string the identity string.
  */
function get_identitystring($ipaddress,$sessionname="SESSIONID"){
   
   $hostip_array = split("\.",$ipaddress); 
   $identitystring = "";
   if(!(empty($hostip_array[0]))){ $identitystring .= $hostip_array[0] . "."; }
   if(!(empty($hostip_array[1]))){ $identitystring .= $hostip_array[1] . "."; }
   if(!(empty($hostip_array[2]))){ $identitystring .= $hostip_array[2]; } 
   $identitystring .= "-" . $sessionname;
   
   return $identitystring;
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**  
  * finds user identity : hostname, ip, referer, user agent and starts session 
  *
  * @param string $PHPSESSID session Id 
  * @param string $sessionname - the name of this session..   
  * @param bool   $allow_ip_host_sessions -  finding session id based on ip and host (identity)
  * @param bool   $serversession - create a server session using built in PHP $_SESSION 
  * @param bool   $cookiesession - cookie the session 
  *
  * @global string  $username - username of user passed in query string.  
  * @global object $mydatabase mysql database object.
  * @global int    $department department id.
  * @global array  $REMOTE_ADDR  
  * @global array  $_SERVER  
  * @global array  $_ENV              
  * @global string  $HTTP_USER_AGENT      
  * @global string  $HTTP_REFERER   
  *
  * @return array an associative array with the following keys:
  *  +   HOSTNAME:  Database backend used in PHP (mysql, odbc etc.)
  *  +    IP_ADDR: Database used with regards to SQL syntax etc.
  *  + USER_AGENT: Communication protocol to use (tcp, unix etc.)
  *  +    REFERER: Host specification (hostname[:port])
  *  +  SESSIONID: Database to use on the DBMS server
  *  +   IDENTITY: User name for login
  *  +     HANDLE: Current handle or username
  *  +NEW_SESSION: enum (Y,N) Y if this is a new session N if this is not a new session.
  *  + COOKIE_SET: enum (Y,N) Y if session is set in a cookie N if this is not.
  */ 
function identity($PHPSESSID="",$sessionname="PHPSESSID",$allow_ip_host_sessions=false,$serversession=false,$cookiesession=true,$ghost_session=false){
   global $sess,$CSLH_Config,$username,$mydatabase,$REMOTE_ADDR,$HTTP_USER_AGENT,$HTTP_REFERER;


   if(empty($_COOKIE))
      $_COOKIE = array();  
   if(empty($REMOTE_ADDR))
    $REMOTE_ADDR = "";
   if(empty($HTTP_USER_AGENT))
    $HTTP_USER_AGENT = "";
   if(empty($HTTP_REFERER))
       $HTTP_REFERER = "";
       
    // Set Sessions name:
    $sessionname = str_replace(" ","",$sessionname);
    if($serversession){
    	$autostart = @ini_get('session.auto_start');
      if($autostart==0){  
    	  if(!(empty($sessionname))){ 
          session_name($sessionname);
        }
      }        
      $sessionname = session_name();
    }

   $mysession_id = "";      
                   
   $client_ip = get_ipaddress();
   $client_agent = ( !empty($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : ( ( !empty($_ENV['HTTP_USER_AGENT']) ) ? $_ENV['HTTP_USER_AGENT'] : $HTTP_USER_AGENT );
   $client_referer = ( !empty($_SERVER['HTTP_REFERER']) ) ? $_SERVER['HTTP_REFERER'] : ( ( !empty($_ENV['HTTP_REFERER']) ) ? $_ENV['HTTP_REFERER'] : $HTTP_REFERER );
   if($CSLH_Config['gethostnames']=="Y")
     $hostname = gethostbyaddr($client_ip);
   else
     $hostname = "host_lookup_not_enabled";  

   $identitystring = get_identitystring($client_ip,$sessionname);      
   
   $mysession_id = detectID($sessionname,$allow_ip_host_sessions,$identitystring);
   $mysession_id = str_replace("'","",$mysession_id);
   
   // if a cookie has been successfuly set:
   if(isset($_COOKIE[$sessionname])){
    	 if($mysession_id==$_COOKIE[$sessionname]) 
    	   $cookie_set = "Y";
    	 else
    	   $cookie_set = "N";  
   } else {
    	 $cookie_set = "N";     
   }   
   
   if(empty($mysession_id))
      $newsession = "Y";
    else
      $newsession = "N";  
  
   // if a sessionID does not exist make one up:
   $unique = $client_ip . date("MdHis");
   if(empty($mysession_id)) 
      $mysession_id = strtolower(md5(uniqid($unique)));      	    

 
    // set session id:
    if($ghost_session==false){
     if($serversession){          
      	@session_id($mysession_id);
        @session_start();
     } else {     	  
        if($cookiesession){
           setcookie ($sessionname, $mysession_id,time()+36000,"/");           
        }  
     }
    }

   // if user tracking is enabled and we do not have a cookieid:
   if( ($ghost_session == false) && ($CSLH_Config['usertracking']=="Y") ){   	
   	  if(empty($_COOKIE['cookieid'])){   	    
   	    $cookieid = strtolower(md5(uniqid($client_ip)));
   	    if ($CSLH_Config['rememberusers']=="Y")
   	       setcookie ("cookieid", $cookieid,time()+60*60*24*60,"/"); 
   	    else
   	       setcookie ("cookieid", $cookieid);    	       
   	  } else
   	    $cookieid = $_COOKIE['cookieid'];   
   } else {
     $cookieid = strtolower(md5(uniqid($client_ip)));
   }

  // query database for handle:
  $sqlquery = "SELECT username FROM livehelp_users WHERE sessionid='".filter_sql($mysession_id)."'";
  $people = $mydatabase->query($sqlquery);
  if($people->numrows() != 0){     
             $row = $people->fetchRow(DB_FETCHMODE_ORDERED);
             $HANDLE = $row[0];
  }
  if(empty($HANDLE)) 
  	  $HANDLE = empty($username) ? $client_ip : $username;
  	        
 $identity_array['HOSTNAME'] = str_replace("'","",$hostname); 
 $identity_array['IP_ADDR'] = $client_ip;
 $identity_array['USER_AGENT'] = str_replace("'","",$client_agent); 
 $identity_array['REFERER'] = $client_referer;   
 $identity_array['SESSIONID'] = $mysession_id;
 $identity_array['IDENTITY'] = str_replace("'","",$identitystring); 
 $identity_array['HANDLE'] = $HANDLE; 
 $identity_array['NEW_SESSION'] = $newsession; 
 $identity_array['COOKIE_SET'] = $cookie_set;
 $identity_array['COOKIEID'] = $cookieid;
  
 return $identity_array;
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Tries to find any session id in $_GET, $_POST or $_COOKIE
  *
  * @param string $sessionname - the name of this session..   
  * @param bool   $allow_ip_host_sessions -  finding session id based on ip and host (identity)
  * @param string   $identitystring - host/ip string
  *
  * @global object $mydatabase mysql database object.  
  * @global array  $_POST  
  * @global array  $_GET               
  * @global array  $_COOKIE     
  *  
  * @return string Session ID (if exists) or empty string
  */
  function detectID($sessionname,$allowhostiplogins,$identitystring) {
      Global $mydatabase,$CSLH_Config,$UNTRUSTED;
      
        $PHPSESSID ="";              

        if (!(empty($UNTRUSTED[$sessionname]))) {
          $PHPSESSID = $UNTRUSTED[$sessionname];
        }                
        if (!(empty($_GET[$sessionname]))) {
          $PHPSESSID = $_GET[$sessionname];
        }
        if (!(empty($_POST[$sessionname]))) {
          $PHPSESSID = $_POST[$sessionname];
        }
        if (!(empty($_COOKIE[$sessionname]))) {
          $PHPSESSID = $_COOKIE[$sessionname];
        }                            
        
        // Security : If SESSIONID is aquired make sure it is sent from 
        //            the same class C ip address.. This is so people following referer
        //            links do not gain access to the account by having the 
        //            operators sessionid. the class C is used because of proxies.               
        if( ($CSLH_Config['matchip']=="Y") && (!(empty($PHPSESSID)))){
           $hostip_array = split("\.",get_ipaddress());  
           $classc = "$hostip_array[0].$hostip_array[1].$hostip_array[2]";
           $sqlquery = "SELECT sessionid FROM livehelp_users WHERE sessionid='".filter_sql($PHPSESSID)."' AND ipaddress LIKE '".$classc."%' LIMIT 1";
           $test = $mydatabase->query($sqlquery);
           if($test->numrows() == 0){ 
           	  $PHPSESSID ="";
           }
        }
  
       
        // if we allow same host ip logins and this is a Database session:
        // this is useful when we absolutly can not set the cookie.
        if( ($allowhostiplogins) && (empty($PHPSESSID)) ){
           // query database for this hostip.
           $sqlquery = "SELECT sessionid FROM livehelp_users WHERE identity='".filter_sql($identitystring)."' AND cookied='N'";
           $people = $mydatabase->query($sqlquery);
           if($people->numrows() != 0){     
           	$row = $people->fetchRow(DB_FETCHMODE_ORDERED);
            $PHPSESSID = $row[0];
           }           
        }        
        
      if (!preg_match('/^[A-Za-z0-9]*$/', $PHPSESSID)) 
			{
				$PHPSESSID = "";
			}                  
			     
      return $PHPSESSID;
    }
    
 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Given the session id update the session.
  *
  * @param array  $identity   array containing session/user information.
  *
  * @global object $mydatabase mysql database object.
  */ 
function update_session($identity,$ghost_session=false){
	 Global $mydatabase,$UNTRUSTED,$dbtype;
	 
	 if(empty($UNTRUSTED['department'])) $UNTRUSTED['department'] = "";
	 if(empty($UNTRUSTED['username']) && !(empty($_COOKIE['username'])))
	    $UNTRUSTED['username'] = $_COOKIE['username'];
	 
	 // look for username based on cookieid:   
	 if ( ($dbtype!="txt-db-api") && (empty($UNTRUSTED['username'])) && (ereg("cslhVISITOR",$identity['IDENTITY']) && (!(empty($_COOKIE['cookieid']))))){
	   $sqlquery = "SELECT username FROM livehelp_identity_monthly WHERE cookieid='".filter_sql($_COOKIE['cookieid'])."'";
	   $cooki = $mydatabase->query($sqlquery);   
     if($cooki->numrows() != 0){
       $row = $cooki->fetchRow(DB_FETCHMODE_ORDERED);
       $UNTRUSTED['username'] = $row[0];
      }  
    }  
  
	 if(empty($UNTRUSTED['username']))
	    $UNTRUSTED['username'] = "";
	    
	 $UNTRUSTED['username'] = filter_html($UNTRUSTED['username']);
	 $UNTRUSTED['username'] = substr($UNTRUSTED['username'],0,25);
	 
	 $nowtime = date("YmdHis");  
   $twentyminutes  = date("YmdHis", mktime(date("H"), date("i")+20,date("s"), date("m")  , date("d"), date("Y")));
	   	         
   $sqlquery = "SELECT expires FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
   $people = $mydatabase->query($sqlquery);
   
   // referer:
   if (!(empty($UNTRUSTED['referer'])))
    $referer = substr(filter_sql($UNTRUSTED['referer']),0,250);
   else
    $referer = "";
          
   // if user does not exist create them:
   if($people->numrows() == 0){
     if($ghost_session==false){
      // we do not know them   
      //get a good username..  
      $showedup = date("YmdHis");
      if (empty($UNTRUSTED['username']))
       $isnamed="N";
     else
       $isnamed="Y";
      $ipusername = empty($UNTRUSTED['username']) ? $identity['IP_ADDR'] : $UNTRUSTED['username'];   
      $currentusername = explode("_",$ipusername);
      $ipusername = $currentusername[0];
      $sqlquery = "SELECT sessionid FROM livehelp_users WHERE username='".filter_sql($ipusername)."'";
      $data_tmp = $mydatabase->query($sqlquery);	
      $i = 0;
      $newusername = $ipusername;
      while($data_tmp->numrows() != 0){
      	$i++;
         $newusername = $ipusername . "_" . $i;          
         $sqlquery = "SELECT sessionid FROM livehelp_users WHERE username='".filter_sql($newusername)."'";
         $data_tmp = $mydatabase->query($sqlquery);	
      } 
      $sqlquery = "INSERT INTO livehelp_users (onchannel,identity,lastaction,department,status,username,hostname,useragent,ipaddress,sessionid,expires,showedup,user_alert,isnamed,camefrom) VALUES ('-1','".$identity['IDENTITY']."','$nowtime',".intval($UNTRUSTED['department']).",'Visiting','".filter_sql($newusername)."','".filter_sql($identity['HOSTNAME'])."','".filter_sql($identity['USER_AGENT'])."','".filter_sql($identity['IP_ADDR'])."','".$identity['SESSIONID']."','$twentyminutes','$showedup',0,'$isnamed','$referer')";
      $mydatabase->query($sqlquery);
      
      // inserted twice???
      $sqlquery = "SELECT expires,user_id FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."' ORDER by user_id DESC";	
      $people = $mydatabase->query($sqlquery);
      if($people->numrows() > 1){
         $row = $people->fetchRow(DB_FETCHMODE_ORDERED); 
         $q = "DELETE FROM livehelp_users WHERE user_id=".intval($row[1]);
         $mydatabase->query($q);
      }
     
     } 
    }  else {
      // get the row.
      $row = $people->fetchRow(DB_FETCHMODE_ORDERED);
      $expires = $row[0];     
      // if a username was sent with this request and this user does not have 
      // a username give them a username..
      $currentusername = explode("_",$identity['HANDLE']);
      if( ($identity['IP_ADDR'] == $currentusername[0]) && (!(empty($UNTRUSTED['username'])))){
      	// get good username.
      	$sqlquery = "SELECT expires FROM livehelp_users WHERE username='".filter_sql($UNTRUSTED['username'])."'";
        $data_tmp = $mydatabase->query($sqlquery);	
        $i = 0;
        $newusername = $UNTRUSTED['username'];
        while($data_tmp->numrows() != 0){
        	$i++;
          $newusername = $UNTRUSTED['username'] . "_" . $i;          
          $sqlquery = "SELECT expires FROM livehelp_users WHERE username='".filter_sql($newusername)."'";
          $data_tmp = $mydatabase->query($sqlquery);	
        } 
       if(!(empty($newusername))){
        $sqlquery = "UPDATE livehelp_users SET isnamed='Y',username='".filter_sql($newusername)."' WHERE isoperator='N' AND sessionid='".$identity['SESSIONID']."'";
        $mydatabase->query($sqlquery);   
       }
      } 
      
      // check to see if the session has timed out if so mark as unactive
      if( ($expires!=0) && ($expires!="") ){
       if($expires<$nowtime){
        //This is handled by gc.php but is here as a place holder for when
        // this is a class.      
       }
      }
    }
    $new_session = $identity['NEW_SESSION'];
    $cookied = $identity['COOKIE_SET'];
    $cookieid = $identity['COOKIEID'];
    $sqlquery = "UPDATE livehelp_users SET cookieid='$cookieid',identity='".filter_sql($identity['IDENTITY'])."',ipaddress='".$identity['IP_ADDR']."',cookied='$cookied',new_session='$new_session',lastaction='$nowtime',expires='$twentyminutes' WHERE sessionid='".$identity['SESSIONID']."'";	 
    $mydatabase->query($sqlquery);
    
}
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * validates username and password and sets operator status to authenticated if successful.
  *
  * @param string $username  
  * @param string $password 
  *
  * @global object $mydatabase mysql database object.
  *
  * @return bool true if valid false if not valid.
  */ 
function validate_user($username,$password,$identity){
  global $mydatabase; 
  
  $sqlquery = "SELECT username 
            FROM livehelp_users 
            WHERE username='".filter_sql($username)."' 
              AND password='".filter_sql($password)."' 
              AND isoperator='Y'";
  $data = $mydatabase->query($sqlquery);
  if($data->numrows() != 0){
    $sqlquery = "UPDATE livehelp_users 
              SET 
                authenticated='Y' 
              WHERE sessionid='" . $identity['SESSIONID'] . "'";
    $mydatabase->query($sqlquery);
    return true;
  }
  return false;  
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * validates that the session has been authenticated.
  *
  * @param array  $identity  
  *
  * @global object $mydatabase mysql database object.
  */ 
function validate_session($identity){
  global $mydatabase; 

  // is this user authenticated and is a operator ?
  $sqlquery = "SELECT username FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."' AND isoperator='Y' AND authenticated='Y' ";
  $data = $mydatabase->query($sqlquery);
  if ( $data->numrows() < 1){
    authenticate(1);
    exit;
  }
  
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * authenticate the user.
  *
  * @param string  $err error message  
  */ 
function authenticate($err="na"){ 
	if(!(empty($err)))
  	$err = urlencode($err);     
  Header("Location: login.php?err=$err");
  exit;
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Log out the user.
  *
  * @param array $identity array containing users idenitiy
  *  
  * @global object $mydatabase mysql database object.  
  */ 
 function logout($identitiy){
  global $mydatabase;
   
  // change online status to offline.
  $random = strtolower(MD5(date("MdHis")));
  $sqlquery = "UPDATE livehelp_users set authenticated='N',isonline='N',status='offline',sessionid='$random',externalchats='',chattype='' WHERE sessionid='".$identitiy['SESSIONID']."'";
  $mydatabase->query($sqlquery);    
  Header("Location: login.php?err=3");
  exit;
 } 
  
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * get the transcript 
  *
  * @param int  $channel    
  *
  * @return array : [starttime] [endtime] [duration] [transcript].  
  */ 
function gettrans($channel){
  global $mydatabase,$CSLH_Config;
  $sqlquery = "SELECT message,timeof,id_num,saidfrom,saidto FROM livehelp_messages WHERE channel=".intval($channel) ." ORDER by timeof ";	
  $messages = $mydatabase->query($sqlquery);
  $trans = "";

  $myarray['starttime'] = 0;
  $myarray['endtime'] = 0;
  $myarray['duration'] = 0;
  $myarray['transcript'] = "";    
  $myarray['operators'] = "";   
  
    
  if ($messages->numrows() < 3) 
    return $myarray;

  while($row = $messages->fetchRow(DB_FETCHMODE_ORDERED)){  
    $message = $row[0];
    $timeof = $row[1];
    $id_num = $row[2];
    $saidfrom = $row[3];
    $saidto = $row[4];
    
    // add to comma diliminated list of operators:
    if(is_operator($saidfrom)){
    	$saidfromstring = "X". $saidfrom ."X";
      if(empty($myarray['operators'])){
        $myarray['operators'] = $saidfromstring;
      } else {     	 
        if(!(ereg($saidfromstring,$myarray['operators'])))
          $myarray['operators'] .= ",".$saidfromstring;
      }     
    }
    
  	if($myarray['starttime'] == 0) 
  	   $myarray['starttime'] = $timeof;
  	   
    // this is in a seprate query because left join queries take too long..
    $sqlquery = "SELECT username FROM livehelp_users WHERE user_id=".intval($saidfrom);
    $username_s = $mydatabase->query($sqlquery);
    $username_a = $username_s->fetchRow(DB_FETCHMODE_ORDERED);
    $username = $username_a[0];
    $abort_counter = 0;    	          
    $trans .= " <b>$username : </b> $message <br>";	
                  	
    }
    
    $myarray['endtime'] = $timeof;
    $myarray['duration'] = timediff($myarray['endtime'],$myarray['starttime']);
    $myarray['transcript'] = $trans; 
 
        
  return $myarray;
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * show the messages for this user by type and id and/or channel and/or timestamp.
  *
  * @param int    $myid id of this user.
  * @param string $typeof (either an empty string or a writediv
  * @param int    (reference) $aftertime  optional timestamp to only see from 
  * @param int    $seechannel optional id of the channel to only view
  * @param string hidechannels string of user_ids to hide. 
  * @param bool   $diliminated  return diliminated results or not.
  * @param bool   $omitself  show self typing or not.
  *
  * @global object $mydatabase mysql database object.
  * @global array $lang  array containing the language texts   
  * @global array $CSLH_Config array containing the configuration variables.
  *
  * @return string messages
  */ 
function showmessages($myid,$typeof="",&$aftertime,$seechannel="",$hidechannels="",$diliminated=false,$omitself=true){
   global $mydatabase,$CSLH_Config,$identity;

$chatmessage = ""; // one chat message
$resultchat = "";  // the whole list of messages since aftertime..
$typediliminated = ""; // the type of message in the deliminated message HTML or LAYER

// if the type is writediv then omit messages that you are tying.
  $excludesql = " ";
if($typeof=="writediv"){
  if($omitself){
     $excludesql = " AND saidfrom!=". intval($myid);  
  }   
}  

// if we only want to see one channel:
if($seechannel != ""){
  $sqlqueryrun = "SELECT livehelp_messages.message,livehelp_messages.typeof,livehelp_messages.timeof,livehelp_messages.id_num,livehelp_messages.saidfrom,livehelp_messages.saidto,livehelp_operator_channels.txtcolor,livehelp_operator_channels.txtcolor_alt,livehelp_operator_channels.channelcolor
               FROM livehelp_messages,livehelp_operator_channels 
               WHERE livehelp_operator_channels.user_id=". intval($myid) ."
                  AND livehelp_messages.channel=livehelp_operator_channels.channel
                  AND timeof>'$aftertime' AND livehelp_messages.typeof='$typeof'
                  AND livehelp_messages.channel=".intval($seechannel)."
                  AND livehelp_messages.saidfrom!='0' $excludesql
               ORDER by timeof";
} else {
	$except_ids = explode(",",$hidechannels);
	$except_sql ="";
	for($i=0;$i<count($except_ids);$i++){
	   $except_sql .= " AND livehelp_messages.channel!=". intval($except_ids[$i]);	
	}	
  $sqlqueryrun = "SELECT livehelp_messages.message,livehelp_messages.typeof,livehelp_messages.timeof,livehelp_messages.id_num,livehelp_messages.saidfrom,livehelp_messages.saidto,livehelp_operator_channels.txtcolor,livehelp_operator_channels.txtcolor_alt,livehelp_operator_channels.channelcolor
               FROM livehelp_messages,livehelp_operator_channels 
               WHERE livehelp_operator_channels.user_id=". intval($myid) ."
                 AND livehelp_messages.channel=livehelp_operator_channels.channel 
                 AND timeof>'$aftertime' AND livehelp_messages.typeof='$typeof' 
                 AND livehelp_messages.saidfrom!='0' $except_sql $excludesql 
               ORDER by timeof";	
}

// if we are a visitor we only can see messages said to us or our channel:
if(ereg("cslhVISITOR",$identity['IDENTITY'])){  
 $sqlqueryrun = "SELECT message,typeof,timeof,id_num,saidfrom,saidto 
              FROM livehelp_messages 
              WHERE (saidto=".intval($myid)."
                 OR channel=".intval($seechannel).")
                 AND livehelp_messages.typeof='$typeof'
                 AND timeof>'$aftertime' $excludesql
              ORDER by timeof ";
}                  
 
// run query:  
  //print $sqlqueryrun;	                
$messages = $mydatabase->query($sqlqueryrun);
	if ( $messages->numrows() != 0) {
	$index=0;	
		while($row = $messages->fetchRow(DB_FETCHMODE_ORDERED)){
	          $chatmessage="";
	          $javascript = "";
	          $message = $row[0];
            $typeof = $row[1];
	          $aftertime = $row[2];	          
	          $id_num = $row[3];		
	          $saidfrom = $row[4];			
	          $saidto = $row[5];

	          $typediliminated = "HTML";
	          
	          // look up text color:
	          if(!(isset($row[6]))){
	            $sqlquery = "SELECT txtcolor,txtcolor_alt FROM livehelp_operator_channels WHERE userid=".intval($saidfrom);
	            $res = $mydatabase->query($sqlquery);
	            $row2 = $res->fetchRow(DB_FETCHMODE_ORDERED);
	            $row[6] = $row2[0];
	            $row[7] = $row2[1];
	          }
	          
	          $txtcolor = (isset($row[6]))? $row[6] : "000000";	  
	          $txtcolor_alt = (isset($row[7]))? $row[7] : "000000";	                  
            $channelcolor = (isset($row[8]))? "bgcolor=".$row[8] : "";  
            
            $tablestart = "<table width=100% cellpadding=0 cellspacing=0 border=0 $channelcolor><tr><td>";
            $tableend = "</td></tr></table>";
	         	
	         	if($saidfrom == 0){
	           $register = 12;
	           $whowhat = "";
	           $from = ""; 	
	          } else {                     
	           $sqlquery = "SELECT username,jsrn FROM livehelp_users WHERE user_id=".intval($saidfrom);
	           $username_f = $mydatabase->query($sqlquery);
	           $username_a = $username_f->fetchRow(DB_FETCHMODE_ORDERED);
	           $from = $username_a[0];
	           $haystack = split("_",$from);
	           if(!(empty($haystack[2])))
	              $from2 = $haystack[1];
	           else
	              $from2 = $haystack[0];
	              
	           if($from2 == $identity['IP_ADDR']){ $from = "You"; }
	           $register = $username_a[1];
	           $whowhat = $from; 	         
	          } 
	          if($saidto != 0){
	           $sqlquery = "SELECT username FROM livehelp_users WHERE user_id=".intval($saidto);
	           $username_f = $mydatabase->query($sqlquery);
	           $username_a = $username_f->fetchRow(DB_FETCHMODE_ORDERED);
	           $to = $username_a[0];  
	          } 
	          
	          if(ereg("cslhVISITOR",$identity['IDENTITY'])){ 
	            if($saidfrom != $myid){
	               $cssclass_name = " class=\"operatorName\" ";
	               $cssclass_txt = " class=\"operator\" ";
	            } else {
	               $cssclass_name = " class=\"guestName\" ";
	               $cssclass_txt = " class=\"guest\" ";
	            }
	          } else {
	            if($saidfrom != $myid){
	               $cssclass_name = " class=\"guestName\" ";
	               $cssclass_txt = " class=\"guest\" ";
                 $txtcolor = $txtcolor_alt; 
	            } else {
	               $cssclass_name = " class=\"operatorName\" ";
	               $cssclass_txt = " class=\"operator\" ";
	            }
	          }	
	          if (!(empty($from)))        
	             $whowhat = "<font color=\"#$txtcolor\" $cssclass_name>$from:&nbsp;</font>";  	          
	           
	          $abort_counter = 0;
	          $message = ereg_replace("(\r\n)|(\n)|(\r)","",nl2br($message));
	          $safemessage = convert_smile(filter_html($message));
	          // if we hit a writediv command write to DIV or 
	          // if it does not exist write normal.
	          if($typeof=="writediv"){
	          	    $typediliminated = "LAYER";         
   	              if($diliminated){
   	              	if(!(ereg("nullstring",$safemessage))){ 
                     $chatmessage = $tablestart . "<table cellpadding=0 cellspacing=0 border=0><tr><td valign=top nowrap=nowrap>". $whowhat . "</td><td><img src=images/blank.gif width=400 height=1><br><font color=\"#". $txtcolor ."\"> ". $safemessage ."</font></b></td></tr></table>". $tableend;
                    } else {
                     $chatmessage = "nullstring";
                    }
   	              } else {
   	                $chatmessage = "<SCRIPT type=\"text/javascript\">\n";
   	                if(!(ereg("nullstring",$safemessage))){ 
    	                $chatmessage .= " whatissaid[" . $register . "] = '". $tablestart . $whowhat . "<font color=\"#". $txtcolor ."\"> ' + unescape('". $safemessage ."') + '</font></b><br>". $tableend ."';\n";
   	                } else {
   	                  $chatmessage .= "whatissaid[" . $register . "] = 'nullstring'\n";
   	                } 
   	               $chatmessage .= "update_typing();";
   	               $chatmessage .= "</SCRIPT>";   	                                 
   	              }
	           } else {  
	
	           	 // if we are sending a url we only want to send it once to the visitor.
               if(ereg("\[PUSH\]",$message)){
                 if(!(is_operator($myid))){  // this should work too: (ereg("cslhVISITOR",$identity['IDENTITY'])){
                    	$javascript = preg_replace("/\[PUSH\](.*?)\[\/PUSH\]/","openwindow('\\1\\2','popwindow');", $message);
                    	$message = ereg_replace("\[PUSH\]","",$message);
                    	$message = ereg_replace("\[/PUSH\]","",$message);                     
                                              
                      // convert links :
                      $newmessage = preg_replace('#(\s(www.))([^\s]*)#', ' http://\\2\\3 ', $message);
                      $newmessage = preg_replace('#((http|https|ftp|news|file)://)([^\s]*)#', '<a href="\\1\\3" target=_blank>\\1\\3</a>', $newmessage);
 
                       if(!($diliminated))
                        $message = "<SCRIPT type=\"text/javascript\"> $javascript </SCRIPT> $newmessage";
                       else
                        $message =  $newmessage;
                        
                      //$sqlquery = "UPDATE livehelp_messages set message='$newmessage' Where id_num=".intval($id_num);
                      //$mydatabase->query($sqlquery);
                  } else {
                  	  $message = ereg_replace("\[PUSH\]","",$message);
                    	$message = ereg_replace("\[/PUSH\]","",$message);  
                  	  $newmessage = preg_replace('#(\s(www.))([^\s]*)#', ' http://\\2\\3 ', $message);
                      $newmessage = preg_replace('#((http|https|ftp|news|file)://)([^\s]*)#', '<a href="\\1\\3" target=_blank>\\1\\3</a>', $newmessage);
                      $message =  $newmessage;
                  }	
                
                }       
                    // if we are transfering them
                    if( ereg("cslhVISITOR",$identity['IDENTITY']) && ( ereg("\[transfer\]",$message) )){ 
                    	$message = ereg_replace("\[transfer\]","",$message);
                    	$message = ereg_replace("\[/transfer\]","",$message);
                    	$message_url = $message;
                    	$javascript = "window.parent.location.replace('$message_url');";
                      if(!($diliminated))
                         $message = "<SCRIPT type=\"text/javascript\"> $javascript </SCRIPT>";
	                    $message .= "..transfered.."; 
                      $sqlquery = "UPDATE livehelp_messages set message='<a href=$message_url target=_blank>transfered to: $message_url</a>' Where id_num=".intval($id_num);
                      $mydatabase->query($sqlquery);
                    }   
           	                                       
   	        if($message != "nullstring"){
     	           $chatmessage .= $tablestart;
    	           $chatmessage .= "<table cellpadding=0 cellspacing=0 border=0><tr><td valign=top NOWRAP=NOWRAP NOWRAP width=3><img src=images/blank.gif width=4 height=4></td><td valign=top NOWRAP=NOWRAP NOWRAP>". $whowhat ."</td><td valign=top><img src=images/blank.gif width=400 height=1><br><font color=\"#". $txtcolor . "\"" .  $cssclass_txt . " > ". $message ." </font></b><br></td></tr></table>";
  	             $chatmessage .= $tableend;   	                
  	        } 
  	      }
     if($diliminated){   
       if(empty($resultchat))
       	  $resultchat = "";
       $string = "messages[$index] = new Array(); messages[$index][0]=\"$aftertime\"; messages[$index][1]=\"$register\"; messages[$index][2]=\"$typediliminated\"; messages[$index][3]=\"".addslashes($chatmessage)."\"; messages[$index][4]=\"$javascript\";"; 
       $resultchat .= $string;
     } else { 
       $resultchat .= $chatmessage;
       if($typeof!="writediv")
        $resultchat .= "<SCRIPT type=\"text/javascript\"> whatissaid[$register] = 'nullstring'\n update_typing();\n </SCRIPT>";
     }  
   $index++;	
   } // while more messages to look at
 } // if there are messages to look at.
 return $resultchat;
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * This function extends the buffer (if required) to at least a given length which defaults to 
  * 256 which is the IE magic number for minimum buffer size. 
  *
  */ 
function OB_Stuff_To_Min( $nMinSize=256 ) { 
  static $sOB_Stuff_Str = '<!-- BUFFER STUFFING -->';      // Comment used to fill 
  //Get current buffer size & determine how many we need to add 
  $nCurLen = ob_get_length(); 
  $nToAdd = ceil(($nCurLen>=$nMinSize)? 0: (($nMinSize-$nCurLen)/strlen($sOB_Stuff_Str))); 
  if( $nToAdd > 0 ) { 
    echo str_repeat($sOB_Stuff_Str,$nToAdd); 
    $nCurLen = ob_get_length(); 
    //if( $nCurLen<$nMinSize ) { die("Unable to extend buffer to $nMinSize chars. Ended up only $nCurLen<br>\n");} 
  } 
  print "\n<!-- ob stuff to min end-->\n";
} 

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Writes white space to the screen to load the buffer for IE and other browsers.
  *
  */ 
function sendbuffer() { 
  // Incase a buffer hasn't been opened (but it always is) 
  if( @ob_get_length()==0 ) { @ob_start(); echo('<!--ob start -->'); } 
  OB_Stuff_To_Min(); 
  if (function_exists('ob_flush')) {
     if(ob_get_contents()) @ob_flush();
  }  
  flush();          // Sends main buffer and empty's it
} 
 //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Gets the last timestamp in the livehelp_messages
 
  * @global object $mydatabase mysql database object.
  *
  * @return int channel the user is on
  */ 

function rightnowtime(){ 
	   global $mydatabase, $myid;
	
	 $nowis = $rightnow = date("YmdHis");	 	    
	 while ( ($nowis == $rightnow) || ($nowis < $rightnow) ) { 
		$nowis = date("YmdHis");
	  $sqlquery = "SELECT timeof FROM livehelp_messages WHERE timeof='$rightnow' OR timeof>'$rightnow' ORDER BY timeof DESC LIMIT 1";
    $retch = $mydatabase->query($sqlquery);
    if($retch->numrows() != 0){
       sleep(1);
    } else {
      $rightnow=0;
    } 
   }   
   return $nowis;   
}
 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Creates a channel for the user.
  * @param int    $userid id of this user.
  * 
  * @global object $mydatabase mysql database object.
  *
  * @return int channel the user is on
  */ 

function createchannel($userid){ 
   global $mydatabase, $myid;
   
  // is it an OP
    $sqlquery = "SELECT isoperator FROM livehelp_users WHERE user_id=".intval($userid). " AND isoperator = 'Y'" ;
   $user_operator = $mydatabase->query($sqlquery);
   if($user_operator->numrows() == 0)
   { 
      $sqlquery = "SELECT id FROM livehelp_channels WHERE user_id=".intval($userid);
      $channel_a = $mydatabase->query($sqlquery);
      $timeof = date("YmdHis");
      if($channel_a->numrows() == 0)
      {
         $sqlquery = "INSERT INTO livehelp_channels (user_id,statusof,startdate) VALUES (".intval($userid).",'P','$timeof')";
         $mydatabase->query($sqlquery);
         $sqlquery = "SELECT id FROM livehelp_channels ORDER BY id DESC LIMIT 1";
         $channel_a = $mydatabase->query($sqlquery);
      }
     $channel_a = $channel_a->fetchRow(DB_FETCHMODE_ORDERED);
   
   }
   else
   {
        // if there is a channel assigned to the current user, and $userid, use that channel
      $sqlquery = "SELECT channel FROM livehelp_operator_channels WHERE user_id=".intval($userid). " AND userid = " .intval($myid) ;
          $channel_operator = $mydatabase->query($sqlquery);
      if($channel_operator->numrows() == 0)
      {
   
         $timeof = date("YmdHis");
         $sqlquery = "INSERT INTO livehelp_channels (user_id,statusof,startdate) VALUES (".intval($userid).",'P','$timeof')";
         $mydatabase->query($sqlquery);
         $sqlquery = "SELECT id FROM livehelp_channels ORDER BY id DESC LIMIT 1";
         $channel_a = $mydatabase->query($sqlquery);
         $channel_a = $channel_a->fetchRow(DB_FETCHMODE_ORDERED);
      
      }
      else
      {
         $channel_a = $channel_operator->fetchRow(DB_FETCHMODE_ORDERED);
      }
  }

 
  $whatchannel = $channel_a[0];

  $sqlquery = "UPDATE livehelp_users set onchannel=".intval($whatchannel)." WHERE user_id=".intval($userid);
  $mydatabase->query($sqlquery);
 
  return $whatchannel;
}   


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Returns what department a given ID number is.
  * @param int    $id id of this department
  * 
  * @global object $mydatabase mysql database object.
  *
  * @return string name of the department
  */ 
function whatdep($id){
  global $mydatabase;
  $sqlquery = "SELECT nameof FROM livehelp_departments WHERE recno=" . intval($id);
  $dat = $mydatabase->query($sqlquery);
  $myrow = $dat->fetchRow(DB_FETCHMODE_ORDERED);
  return  $myrow[0];
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Creates a user details page for the session to be stored in the transcripts database.
  * @param int    $userid id of this user.
  * 
  * @global object $mydatabase mysql database object.
  *
  * @return string of session data // TODO: make it return array instead..
  */ 
function getsessiondata($userid,$diliminated=false){  
   global $mydatabase,$lang,$HTTP_USER_AGENT;

$sesssiondata = "";
$diliminatedstring = "";
   
$sqlquery = "SELECT * from livehelp_users WHERE user_id=".intval($userid);
$user_info = $mydatabase->query($sqlquery);
$user_info = $user_info->fetchRow(DB_FETCHMODE_ASSOC);

$sqlquery = "SELECT nameof from livehelp_departments WHERE recno=".intval($user_info['department']);
$tmp = $mydatabase->query($sqlquery);
$nameof = $tmp->fetchRow(DB_FETCHMODE_ORDERED);
$nameof = $nameof[0];

$sesssiondata .= "<table width=100%><tr><td colspan=2 bgcolor=DDDDDD>".  $lang['txt64'] . "</td></tr><tr><td align=left><b>" . $lang['referer'] . ":</b> <a href=" . str_replace(" ","+",$user_info['camefrom']) . " target=_blank>" . $user_info['camefrom'] . "</a><br>";
$diliminatedstring .= "referer=" . urlencode($user_info['camefrom']);

$sesssiondata .= "<b>" . $lang['status'] . ":</b>" . $user_info['status'] . "<br>";
$sesssiondata .= "<b>" . $lang['dept'] . "</b> $nameof<br>";
$sesssiondata .= "<b>E-mail :</b>" . $user_info['email'] . "<br>";
$sesssiondata .= "<b>SessionID :</b>" . $user_info['sessionid'] . "<br>";
$sesssiondata .= "<b>identity :</b>" . $user_info['identity'] . "<br>";
$sesssiondata .= "<b>HostName:</b>" . $user_info['hostname'] . "<br>";
$diliminatedstring .= "&hostname=" . urlencode($user_info['hostname']);
 
  include_once("class/browser_info.php");
    if(empty($user_info['useragent']))
      $client_agent = ( !empty($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : ( ( !empty($_ENV['HTTP_USER_AGENT']) ) ? $_ENV['HTTP_USER_AGENT'] : $HTTP_USER_AGENT );
    else
      $client_agent = $user_info['useragent'];
    $b = new BrowserInfo($client_agent);
$sesssiondata .= "<b>User Agent :</b>" . $b->USER_AGENT  ."<br>"; // STRING - USER_AGENT_STRING
$sesssiondata .= "<b>OS :</b>" . $b->OS  ."<br>"; // STRING - USER_AGENT_STRING
$sesssiondata .= "<b>OS Version :</b>" . $b->OS_Version  ."<br>"; // STRING - USER_AGENT_STRING
$sesssiondata .= "<b>Browser :</b>" . $b->Browser  ."<br>"; // STRING - USER_AGENT_STRING
$sesssiondata .= "<b>Browser_Version :</b>". $b->Browser_Version   ."<br>"; // STRING - USER_AGENT_STRING 
$sesssiondata .= "<b>Type :</b>". $b->Type  ."<br>"; // STRING - USER_AGENT_STRING

$diliminatedstring .= "&ua=" . urlencode($b->USER_AGENT);
$diliminatedstring .= "&os=" . urlencode($b->OS);
$diliminatedstring .= "&os_version=" . urlencode($b->OS_Version);
$diliminatedstring .= "&browser=" . urlencode($b->Browser);	
$diliminatedstring .= "&ip=" . urlencode($user_info['ipaddress']);
 
$sesssiondata .= "<b>Ip :</b>" . $user_info['ipaddress'] . "<br>";
$sesssiondata .= "<b>Cookied :</b>" . $user_info['cookied'] . "<br>";
$sesssiondata .= "<b>New Session :</b>" . $user_info['new_session'] . "<br>";
  // get session data 
  $sqlquery = "SELECT sessiondata FROM livehelp_users WHERE user_id=".intval($userid);
  $userdata = $mydatabase->query($sqlquery);
  $user_row = $userdata->fetchRow(DB_FETCHMODE_ORDERED);
  $sessiondata = $user_row[0];
  $datapairs = explode("&",$sessiondata);
  $datamessage="";
  for($l=0;$l<count($datapairs);$l++){
  	  $dataset = explode("=",$datapairs[$l]);
  	  if(!(empty($dataset[1]))){
  	  	$fieldid = str_replace("field_","",$dataset[0]);
  	  	$sqlquery = "SELECT headertext FROM livehelp_questions WHERE id=".intval($fieldid); 
  	  	$questiondata = $mydatabase->query($sqlquery);
        $question_row = $questiondata->fetchRow(DB_FETCHMODE_ORDERED);    	          
    	  $sesssiondata .= "<b> ". $question_row[0] . ":</b> <br><font color=000000>" . urldecode($dataset[1]) . "</font>";
    	  $diliminatedstring .= "&".$question_row[0]."=" . $dataset[1];
      }
  }
 if($diliminated)
   return $diliminatedstring; 
 else  
   return $sesssiondata;
   
}  

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Sends a e-mail message
  * 
  * @param string  myname - name of the person sending the e-mail
  * @param string  myemail - e-mail of the person sending the e-mail
  * @param string  contactname - name of the person being contacted.
  * @param string  contactemail - e-mail of the person being contacted
  * @param string  subject - subject of teh message.
  * @param string  message - the message.. 
  * @param string  contenttype - content type (text/html / text/plain)
  * @param string  charset - charset (iso-8859-1)
  * @param bool $useCRLF use Carriage Return/Linefeed (CRLF) line breaks or not.. if true uses \r\n else uses \n
  *
  * @return bool true if sent e-mail false otherwise..
  */ 
function send_message($myname, $myemail, $contactname, $contactemail, $subject, $message, $contenttype, $charset,$useCRLF=false) {
	global $CSLH_Config;
	
  if ($useCRLF)
     $newline = "\r\n";
  else
     $newline = "\n";
  $headers = "MIME-Version: 1.0" . $newline;
  $headers .= "Content-type: $contenttype; charset=$charset" . $newline;
  $headers .= "X-Mailer: php" . $newline;

  if(!(good_emailaddress($contactemail))){
     // to avoid relay errors make this do_not_reply@currentdomain.com
    if(!(empty($_SERVER['HTTP_HOST']))){
        $host = str_replace("www.","",$_SERVER['HTTP_HOST']);
        $contactemail  = "do_not_reply@" . $host;
      } else {
      	$contactemail  = "trash@maui.net";
      }       
  }   
  
  if(!(good_emailaddress($myemail))){
     // to avoid relay errors make this do_not_reply@currentdomain.com
    if(!(empty($_SERVER['HTTP_HOST']))){
        $host = str_replace("www.","",$_SERVER['HTTP_HOST']);
        $myemail  = "do_not_reply@" . $host;
      } else {
      	$myemail  = "trash@maui.net";
      }       
  }   
 
  $headers .= "From: " . $myemail . $newline;
  
  if($CSLH_Config['smtp_host']!=""){
    $rtn = smtpmail($contactemail, $subject, $message, $headers);
  } else {
    $rtn = mail($contactemail, $subject, $message, $headers);
  }  
  
  return $rtn;
  
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Auto invite users based on identity of user
  * 
  * @param array  $identity   array containing session/user information.
  * @param string $status current status of the user.
  * @param int $department that user is on.
  *
  * @global object $mydatabase mysql database object.
  *
  * @return bool true if invited
  */ 

function autoinvite($identity,$status="",$department=0){ 
	 global $mydatabase,$dbtype,$CSLH_Config;

	 if ( ($status=='invited') OR ($status=='wentaway') OR ($status=='chat') OR ($status=='operator') OR ($status=='stopped') OR ($status=='request'))
     return false;
     
  // make list of ignored visitors:
 $ipadd = split(",",$CSLH_Config['ignoreips']);
 $ignoreme = false;
 for($i=0;$i<count($ipadd); $i++){
 	if(!(empty($ipadd[$i])))
 	   if(ereg($ipadd[$i],$identity['IP_ADDR']))
 	      $ignoreme = true;
 }      
 if($ignoreme)
   return false;
     
     
   // if this is a Visitor:
   if(ereg("cslhVISITOR",$identity['IDENTITY'])){      
      // see if any online operator in this department has autoinvite on:
      $sqlquery = "SELECT isonline 
                FROM livehelp_users,livehelp_operator_departments 
                WHERE livehelp_users.user_id=livehelp_operator_departments.user_id
                 AND livehelp_users.authenticated='Y'
                 AND livehelp_users.isoperator='Y'";
       if(intval($department) != 0){
    	           $sqlquery .= " AND livehelp_operator_departments.department=".intval($department);
    	                }
            $sqlquery .= " AND livehelp_users.auto_invite='Y' AND livehelp_users.isonline='Y' LIMIT 1";
      $tmpdata = $mydatabase->query($sqlquery);
      // someone is online invite the user:
      if($tmpdata->numrows() != 0){
         // get users track record:
         $sqlquery = "SELECT camefrom,user_id FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."' LIMIT 1";
         $res = $mydatabase->query($sqlquery); 
         $user_info = $res->fetchRow(DB_FETCHMODE_ORDERED);
         $camefrom = $user_info[0]; 
         $user_id =  $user_info[1];       
         $pathstuff = split("\?",$camefrom);
         $camefrom = $pathstuff[0];
         $camefrom = str_replace("http://","",$camefrom);
         $camefrom = str_replace("www.","",$camefrom);   
                  
         $sqlquery = "SELECT location from livehelp_visit_track WHERE sessionid='".$identity['SESSIONID']."' ORDER BY whendone DESC";
         $footprints = $mydatabase->query($sqlquery); 
         $visits = $footprints->numrows();
         $foot = $footprints->fetchRow(DB_FETCHMODE_ORDERED);
         $pathstuff = split("\?",$foot[0]);
         $pageurl = $pathstuff[0];
         $pageurl = str_replace("http://","",$pageurl);
         $pageurl = str_replace("www.","",$pageurl);  
         
         // time online:
         $query = "SELECT whendone from livehelp_visit_track WHERE sessionid='".filter_sql($identity['SESSIONID'])."' Order by whendone LIMIT 1";
         $page_trail = $mydatabase->query($query); 
         $page = $page_trail->fetchRow(DB_FETCHMODE_ASSOC);
         $later = $page['whendone'];
         $timeon = (timediff($later,date("YmdHis")));
   
         $sqlquery = "SELECT message,typeof,page,referer FROM livehelp_autoinvite WHERE ";
         $sqlquery .= " (department=0 OR department=" . intval($department) . " ) ";
         $sqlquery .= " AND (visits=0 OR visits<=$visits) ";
         $sqlquery .= " AND (seconds=0 OR seconds<=$timeon) ";
         $sqlquery .= " AND isactive='Y'";     
        
         // print $sqlquery;
         $data = $mydatabase->query($sqlquery);
         if( (!(empty($pageurl))) && ($data->numrows() != 0) ){ 
         	   // look for match for page and camefrom:
             while($row = $data->fetchRow(DB_FETCHMODE_ORDERED)){
               $comment = $row[0];
               $typeof  = $row[1];     
               $page = $row[2];
               $refpage  = $row[3]; 
               if(empty($refpage)) $refpage = ".";
               if(empty($camefrom)) $camefrom = ".";
               if(empty($page)) $page = ".";
               if(empty($pageurl)) $pageurl = ".";                              

               // if match:
               if ( ereg($refpage,$camefrom) && ereg($page,$pageurl) ){
                 $whatchannel = createchannel($user_id);     
                 $timeof = date("YmdHis");
                 $channel = $whatchannel;  
                if($user_id == ""){ $channel = -1; }
                if($typeof == "layer"){
                  $sqlquery = "UPDATE livehelp_users set status='DHTML',sessiondata='invite=".filter_sql($comment)."' WHERE user_id=".intval($user_id);
                  $mydatabase->query($sqlquery);  
                } else {
                  $sqlquery = "UPDATE livehelp_users set status='request' WHERE user_id=".intval($user_id);
                  $mydatabase->query($sqlquery);    
                  $sqlquery = "INSERT INTO livehelp_messages (message,channel,timeof,saidfrom,saidto) VALUES ('".filter_sql($comment)."',".intval($channel).",'$timeof',0,".intval($user_id).")";	
                  $mydatabase->insert($sqlquery);                    
                }
                return true; 
              } // end ereg match
            } // end loop of auto results    
          } // end of if auto found.
       } // end of if someone is online.       	  
   // this is an operator:
   } else {
   	 // maybe auto invite inactive people .. but that seems like too much work...   
   	 return false;
   } 
 return false;       
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Converts seconds to a string of Hours, Minutes , seconds.
  * 
  * @param int  seconds
  * @param bool $useCRLF use Carriage Return/Linefeed (CRLF) line breaks or not.. if true uses \r\n else uses \n
  *
  * @return bool true if sent e-mail false otherwise..
  */ 
function secondstoHHmmss($seconds){  
    $hours = floor($seconds/3600);
    $minutes = floor(($seconds-($hours*3600))/60);    
    $seconds = $seconds-($hours*3600)-($minutes*60);   
    return " $hours hrs  $minutes min $seconds sec";
} 

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * The difference in seconds between two YYYYMMDDHHIISS format strings.
  * 
  * @param string later date
  * @param string ealier date
  *
  * @return int number of seconds.
  */
function timediff($later,$earlier){
	 
   $later_seconds = mktime ( substr($later,8,2), substr($later,10,2), substr($later,12,2), substr($later,4,2), substr($later,6,2), substr($later,0,4));
	 $earlier_seconds = mktime ( substr($earlier,8,2), substr($earlier,10,2), substr($earlier,12,2), substr($earlier,4,2), substr($earlier,6,2), substr($earlier,0,4));
	 $diff = ($later_seconds - $earlier_seconds);
	 if($diff<0)
	   $diff = $diff * -1;
	 if($diff>110837566)  
	   $diff=0;
 
   	 return $diff;    	 
} 

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * finds the next Id in the database (puka is hole in hawaiian). 
  * 
  * @return int id.
  */
function find_puka(){
	 global $dbtype,$mydatabase;
		if($dbtype=="txt-db-api")
       	$sqlquery = "SELECT layerid FROM livehelp_layerinvites  WHERE layerid!=0 ORDER by layerid"; 
      else
       	$sqlquery = "SELECT DISTINCT layerid FROM livehelp_layerinvites  WHERE layerid>0 ORDER by layerid";          	
    	$rs = $mydatabase->query($sqlquery);
    	$layerid = 1;
    	while($row = $rs->fetchRow(DB_FETCHMODE_ORDERED)){
      	   if($row[0]!=$layerid) break; // Gap found.
      	   $layerid++;
    	}
	return $layerid;
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Given a sessionID this function archives off the footsteps of a given user.
  * counting their page visits only once.
  *
  * @param string SessionID 
  */
function archivefootsteps($sessionid){
	  global $mydatabase;
	          
    $dateof = date("Ym"); 	  
    
    // move all old visit data into visit tracking database counting each page once. 
    $sqlquery = "SELECT location FROM livehelp_visit_track WHERE sessionid='".filter_sql($sessionid)."' ORDER BY whendone ";
    $footsteps = $mydatabase->query($sqlquery);
    $previousrecno = 0;      //id of previous page recno.
    $arrayofurls = array();  //array of urls visited already
    while($foot = $footsteps->fetchRow(DB_FETCHMODE_ORDERED)){
     	$pageurl = $foot[0]; // location     
      $firstvisit = false;     // flag to say if we have visited this or not.                   
  
     	if(!(in_array($pageurl,$arrayofurls))){
     		$arrayofurls[] = $pageurl;
     		$firstvisit = true;
     	  archivepage('livehelp_visits_daily',$pageurl,date("Ymd"));
     	  archivepage('livehelp_visits_monthly',$pageurl,date("Ym"));
     	}

      // simplify the url:
      // separate out querystring from page url
     	$pathstuff = split("\?",$pageurl); 
      $pageurl = $pathstuff[0]; 
      $querystring="";
      if(!(empty($pathstuff[1])))
        $querystring = $pathstuff[1];
      // change the size to max 250 and remove quotes and www.
      $pageurl = str_replace("'","",$pageurl);
      $pageurl = str_replace("www.","",$pageurl);
      $pageurl = substr($pageurl,0,250);     
      // chop off ending slash 
      $lastchar = substr($pageurl,-1);  
      if($lastchar == "/"){ $pageurl = substr($pageurl, 0, -1); }

      // aquire the page visit monthly id:
      $sqlquery = "SELECT recno FROM livehelp_visits_monthly WHERE pageurl='".$pageurl."' AND dateof=$dateof LIMIT 1";
      $referers_res = $mydatabase->query($sqlquery); 
      $result = $referers_res->fetchRow(DB_FETCHMODE_ORDERED);
      $recno = intval($result[0]);	  	           	
     	
     	// Skip if we are still in the same place we were:
     	if( ($previousrecno != $recno) && ($recno!=0) ){     	  
     	 // First time visiting page Paths:
     	 if($firstvisit == true){     		       
     	  // see if we have this path in the database:
     	  $q = "SELECT id,visits FROM livehelp_paths_firsts WHERE visit_recno='$previousrecno' AND exit_recno='$recno' AND dateof='".date("Ym")."'";
        $referers_res = $mydatabase->query($q); 
        if($referers_res->numrows() == 0){
         $q = "INSERT INTO livehelp_paths_firsts (visit_recno,exit_recno,dateof,visits) VALUES ('$previousrecno','$recno','".date("Ym")."','1')";
         $mydatabase->query($q); 
        } else {
         $result = $referers_res->fetchRow(DB_FETCHMODE_ORDERED);
         $id = $result[0];	 
         $visits = $result[1];
         $visits++;
         $q = "UPDATE livehelp_paths_firsts SET visits='$visits' WHERE id='$id'";
         $mydatabase->query($q);          
        }              
       }  
       
      	// All time visiting page Paths.
      	$q = "SELECT id,visits FROM livehelp_paths_monthly WHERE visit_recno='$previousrecno' AND exit_recno='$recno' AND dateof='".date("Ym")."'";
       $referers_res = $mydatabase->query($q); 
       if($referers_res->numrows() == 0){
         $q = "INSERT INTO livehelp_paths_monthly (visit_recno,exit_recno,dateof,visits) VALUES ('$previousrecno','$recno','".date("Ym")."','1')";
         $mydatabase->query($q); 
       } else {
         $result = $referers_res->fetchRow(DB_FETCHMODE_ORDERED);
         $id = $result[0];	 
         $visits = $result[1];
         $visits++;
         $q = "UPDATE livehelp_paths_monthly SET visits='$visits' WHERE id='$id'";
         $mydatabase->query($q);          
        }     	         
      }
       $previousrecno = $recno;
            	  
     } // end while more page visits     
 
    
    // Record END of session Page:
    $recno = 0;
    
    // Skip if we are still in the same place we were:    
    if( ($previousrecno != $recno) && ($previousrecno!=0) ){ 
     		
    // First time visiting page Paths:
    if($firstvisit == true){     		       
     	 // see if we have this path in the database:
     	 $q = "SELECT id,visits FROM livehelp_paths_firsts WHERE visit_recno='$previousrecno' AND exit_recno='$recno' AND dateof='".date("Ym")."'";
       $referers_res = $mydatabase->query($q); 
       if($referers_res->numrows() == 0){
        $q = "INSERT INTO livehelp_paths_firsts (visit_recno,exit_recno,dateof,visits) VALUES ('$previousrecno','$recno','".date("Ym")."','1')";
        $mydatabase->query($q); 
       } else {
        $result = $referers_res->fetchRow(DB_FETCHMODE_ORDERED);
        $id = $result[0];	 
        $visits = $result[1];
        $visits++;
        $q = "UPDATE livehelp_paths_firsts SET visits='$visits' WHERE id='$id'";
        $mydatabase->query($q);          
       }              
     }  
      
     	// All time visiting page Paths.
     	$q = "SELECT id,visits FROM livehelp_paths_monthly WHERE visit_recno='$previousrecno' AND exit_recno='$recno' AND dateof='".date("Ym")."'";
      $referers_res = $mydatabase->query($q); 
      if($referers_res->numrows() == 0){
        $q = "INSERT INTO livehelp_paths_monthly (visit_recno,exit_recno,dateof,visits) VALUES ('$previousrecno','$recno','".date("Ym")."','1')";
        $mydatabase->query($q); 
      } else {
        $result = $referers_res->fetchRow(DB_FETCHMODE_ORDERED);
        $id = $result[0];	 
        $visits = $result[1];
        $visits++;
        $q = "UPDATE livehelp_paths_monthly SET visits='$visits' WHERE id='$id'";
        $mydatabase->query($q);          
      }    
    }
 
    // let get rid of the temp data..
    $sqlquery = "DELETE FROM livehelp_visit_track WHERE sessionid='".filter_sql($sessionid)."'";
    $mydatabase->query($sqlquery); 
    
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Given a sessionid this function archives off the transcript and stops
  * a chat session for a given user.
  *
  * @param string SessionID 
  */
function stopchat($sessionid){
		  global $CSLH_Config,$mydatabase;
		  		  
		  $sqlquery = "SELECT user_id,onchannel,username,lastaction,department,email,sessionid FROM livehelp_users WHERE sessionid='$sessionid'";	
      $rs = $mydatabase->query($sqlquery);  
      $person = $rs->fetchRow(DB_FETCHMODE_ORDERED);  
      $user_id = $person[0];
      $onchannel = $person[1];
      $oldusername = $person[2];
      $lastaction = $person[3];      
      $userdepartment =  $person[4];      
      $email = $person[5];  
      $sessionid = $person[6];
      $sessiondata = getsessiondata($user_id);
      $trans_array = gettrans($onchannel);
   
   	  $sqlquery = "UPDATE livehelp_users SET status='stopped',sessiondata='',chattype='',askquestions='Y' WHERE sessionid='$sessionid'";
  	  $mydatabase->query($sqlquery);  	        
      
 
      // if we talked to them add them to the transcripts.  
      if($trans_array['transcript'] != ""){      
 
      	// multiple channels can be created for a single chat.. be sure we do not have
      	// this chat already:
      	$query = "SELECT recno FROM livehelp_transcripts WHERE sessionid='$sessionid' AND transcript='".filter_sql($trans_array['transcript'])."' ORDER by endtime DESC LIMIT 1";
        $data3 = $mydatabase->query($query);
        
        if(empty($CSLH_Config['offset'])){ $CSLH_Config['offset'] = 0; }
         //YYYYMMDDHHIISS
         //  01234567890123
        $when = mktime ( substr($trans_array['endtime'],8,2)+$CSLH_Config['offset'], substr($trans_array['endtime'],10,2), substr($trans_array['endtime'],12,2), substr($trans_array['endtime'],4,2) , substr($trans_array['endtime'],6,2), substr($trans_array['endtime'],0,4) );
        $trans_array['endtime'] = date("YmdHis",$when);
        $when = mktime ( substr($trans_array['starttime'],8,2)+$CSLH_Config['offset'], substr($trans_array['starttime'],10,2), substr($trans_array['starttime'],12,2), substr($trans_array['starttime'],4,2) , substr($trans_array['starttime'],6,2), substr($trans_array['starttime'],0,4) );
        $trans_array['starttime'] = date("YmdHis",$when);
        
        if($data3->numrows() == 0){
          $sqlquery = "INSERT INTO livehelp_transcripts (who,endtime,starttime,transcript,sessionid,sessiondata,department,email,duration,operators) VALUES ('".filter_sql($oldusername)."','".$trans_array['endtime']."','".$trans_array['starttime']."','".filter_sql($trans_array['transcript'])."','".$sessionid."','".filter_sql($sessiondata)."',".intval($userdepartment).",'".filter_sql($email)."',".$trans_array['duration'].",'".$trans_array['operators']."')";
          $mydatabase->query($sqlquery);
           
          //MYSQL: $transcriptid = mysql_insert_id();          
          $query = "SELECT recno FROM livehelp_transcripts ORDER by endtime DESC LIMIT 1";
          $data3 = $mydatabase->query($query);
          $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
          $transcriptid = $row3['recno'];        
        } else {
          $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
          $transcriptid = $row3['recno'];
        }
        
         // get when they startted the chat on this channel in and how many seconds they have been online:
        $query = "SELECT dateof,opid FROM livehelp_operator_history WHERE action='startchat' AND channel=".intval($onchannel)." ORDER by dateof DESC LIMIT 1";
        $data3 = $mydatabase->query($query);
        $row3 = $data3->fetchRow(DB_FETCHMODE_ASSOC);
 
        $opid=$row3['opid'];
        
        // update history for operator to show login:
        $query = "INSERT INTO livehelp_operator_history (opid,action,dateof,transcriptid) VALUES ($opid,'Stopchat','".date("YmdHis")."','".$transcriptid."')";
        $mydatabase->query($query);
        
      }
      // get rid of old messages.
      $sqlquery = "DELETE FROM livehelp_messages WHERE channel=".intval($onchannel);
      $mydatabase->query($sqlquery);     
      // delete operator channels:
      $sqlquery = "DELETE FROM livehelp_operator_channels WHERE channel=".intval($onchannel);	
      $mydatabase->query($sqlquery);
}

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Given a referer this checks for keywords and then archives the keywords
  *
  * @param string type of page [referer or page visits]
  * @param string url of the page to add to database. 
  * @param int date in YYYYMM or YYYYMMDD depending if this is monthly or daily.
  */
function archivekeywords($tablename,$pageurl,$dateof){
		 global $mydatabase;       	  
       
      // separate out querystring from page url
     	$pathstuff = split("\?",$pageurl); 
      $pageurl = substr($pathstuff[0],0,250); 
      
      if(empty($pathstuff[1]))
        return;
      
      $querystring = $pathstuff[1];
      
      // get the query string variables:
      $pairs = split("&",$querystring);
      $variables = array();
      
      for($i=0;$i<count($pairs); $i++){
      	$var_array = explode("=",$pairs[$i]);
      	$var = $var_array[0];
      	if(!(empty($var_array[1])))
      	   $variables[$var] = $var_array[1];
      	else
      	   $variables[$var] = "";      	   

      }
      
      $keywords = "";
      
      if(!(empty($variables['keywords'])))
        $keywords = urldecode($variables['keywords']);  
      if(!(empty($variables['query'])))
        $keywords = urldecode($variables['query']);        
      if(!(empty($variables['p'])))
        $keywords = urldecode($variables['p']);
      if(!(empty($variables['q'])))       
        $keywords = urldecode($variables['q']);        
     
      $keywords = filter_sql(str_replace("'","",$keywords));
      
    if(!(empty($keywords))){                
        $sqlquery = "SELECT levelvisits,recno FROM $tablename WHERE dateof=$dateof AND keywords='$keywords'";
        $keywords_res = $mydatabase->query($sqlquery);
        // not found for today:
        if($keywords_res->numrows() == 0){ 
        	 $sqlquery = "INSERT INTO $tablename (pageurl,dateof,keywords,levelvisits) VALUES ('".filter_sql($pageurl)."','$dateof','$keywords','1')";
        	 $mydatabase->query($sqlquery);
        } else {
        	 $keywords_array = $keywords_res->fetchRow(DB_FETCHMODE_ORDERED);     
           $levelvisits= $keywords_array[0] + 1; 
           $recno = $keywords_array[1];               
        	 $sqlquery = "UPDATE $tablename SET levelvisits=$levelvisits WHERE recno=$recno";
        	 $mydatabase->query($sqlquery);        	
        }
    
    }

} // end function  


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Given a url this function archives off the url into the visits tables.
  *
  * @param string type of page [referer or page visits]
  * @param string url of the page to add to database. 
  * @param int date in YYYYMM or YYYYMMDD depending if this is monthly or daily.
  */
function archivepage($tablename,$pageurl,$dateof){
		 global $mydatabase;       	  
       
      // separate out querystring from page url
     	$pathstuff = split("\?",$pageurl); 
      $pageurl = $pathstuff[0]; 
      $querystring="";
      if(!(empty($pathstuff[1])))
        $querystring = $pathstuff[1];

      // change the size to max 250 and remove quotes and www.
      $pageurl = str_replace("'","",$pageurl);
      $pageurl = str_replace("www.","",$pageurl);
      $pageurl = substr($pageurl,0,250);
     
       // chop off ending slash 
       $lastchar = substr($pageurl,-1);  
       if($lastchar == "/"){ $pageurl = substr($pageurl, 0, -1); }
       $pageurl_array = split("/",$pageurl);
       
       // urls are recorded by domain then each sub-folder is recorded as 
       // a child of that domain. So craw up the domain split by 
       // slashes and record page:
       // example http:// [parent domain] / [subfolder] / [subfolder] / page.php ? [querystring]
       $currenturl = $pageurl_array[0] . "/" . $pageurl_array[1];
       $currentparent = 0;   // id of current parent
       $level = 0; // level we are on.
       
       // if there is a query string we want to count it:
       if(!(empty($querystring)))
         $endcount = count($pageurl_array)+1;
       else
         $endcount = count($pageurl_array);
           
       for ($i=2; $i< $endcount; $i++){       	
       	$level++;       	
       	// if we are working with the query string:
       	if($i==count($pageurl_array)){
       	  $currenturl .= "?" . $querystring;
       	  // make sure it is under 250 with query string:
       	  $currenturl = substr($currenturl,0,250);

       	  // we only want to record the last 5 query strings from this page per month:
       	  $sqlquery = "SELECT recno FROM $tablename WHERE parentrec=".intval($currentparent)." ORDER BY dateof LIMIT 5";
          $referers_res = $mydatabase->query($sqlquery); 
       	  //if we do not have 5 then insert:
       	  if($referers_res->numrows() < 5){
     	       // zero count notes query string count:
     	       $sqlquery = "INSERT INTO $tablename (pageurl,levelvisits,directvisits,dateof,parentrec,level) VALUES ('".filter_sql($currenturl)."',0,0,$dateof,".intval($currentparent).",$level)";
             $mydatabase->query($sqlquery); 
       	  } else {
       	  	 $result= $referers_res->fetchRow(DB_FETCHMODE_ORDERED);
     	       $sqlquery = "UPDATE $tablename SET pageurl='".filter_sql($currenturl)."',levelvisits=0,directvisits=0,dateof=$dateof WHERE recno=". intval($result[0]);     
             $mydatabase->query($sqlquery); 
       	  }
       	
       	// if we are working with a regular page:  
        } else {
          
          // flag for if we are looking at a leaf of the tree:
       	  if($i==(count($pageurl_array)-1))
            $isleaf = true;
          else
            $isleaf = false; 
          
          // look in stats for page:
          $currenturl .= "/" . $pageurl_array[$i];
          $currenturl = substr($currenturl,0,250);
          
          $sqlquery = "SELECT directvisits,levelvisits,recno FROM $tablename WHERE pageurl='".filter_sql($currenturl)."' AND dateof=".intval($dateof) . " LIMIT 1";
          $referers_res = $mydatabase->query($sqlquery);     	             
          // not found in referers for today:
          if($referers_res->numrows() == 0){     	
           // create in referers table for today:
           if($isleaf)
            $directvisits=1;
           else
            $directvisits=0;
           $sqlquery = "INSERT INTO $tablename (pageurl,dateof,directvisits,levelvisits,parentrec,level) VALUES ('".filter_sql($currenturl)."',".intval($dateof).",$directvisits,1,".intval($currentparent).",$level) ";      
           $mydatabase->query($sqlquery);
           
           // get the new parent rec.. TODO: use common DB function to get last insert id.. 
           //MYSQL: 
           $currentparent = mysql_insert_id();                    
           // get the new parent rec..
           //$sqlquery = "SELECT recno FROM $tablename WHERE pageurl='".filter_sql($currenturl)."' AND dateof=".intval($dateof) . " LIMIT 1";
           //$referers_res = $mydatabase->query($sqlquery);     
           //$result= $referers_res->fetchRow(DB_FETCHMODE_ORDERED);
           //$currentparent = $result[0];                    
        
        // If found in daily table:
        } else {
           $referers_array = $referers_res->fetchRow(DB_FETCHMODE_ORDERED);
           
           if($isleaf)
            $directvisits= $referers_array[0] + 1;
           else
            $directvisits= $referers_array[0];
            
           // update count for daily table: 
           $levelvisits = $referers_array[1] + 1;   // levelvisits + 1
           $currentparent = $referers_array[2];     // recno (current parent daily table)              
           $sqlquery = "UPDATE $tablename set levelvisits=".intval($levelvisits).",directvisits=".intval($directvisits)." WHERE pageurl='".filter_sql($currenturl)."' AND dateof=".intval($dateof);      
           $mydatabase->query($sqlquery); 
                                  	
       } // if found in table                        
     } // if not working with query string: 
   } // while crawling domain and directories
   
} // end function    







//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Archives off a user:
  *
  * @param string SessionID 
  */
function archiveuser($sessionid){
	global $mydatabase,$CSLH_Config,$dbtype;
	 
	 //gather users user_id:
	 $sqlquery = "SELECT user_id,isnamed,camefrom FROM livehelp_users WHERE sessionid='".filter_sql($sessionid)."'";
   $res = $mydatabase->query($sqlquery); 
   $res_array = $res->fetchRow(DB_FETCHMODE_ORDERED); 
	  $user_id = $res_array[0];
	  $isnamed = $res_array[1];
    $camefrom = $res_array[2];
    
 if( ($dbtype != "txt-db-api") && ($CSLH_Config['tracking']=="Y") )
   archivefootsteps($sessionid);       
 else {
   // let get rid of the temp data..
   $sqlquery = "DELETE FROM livehelp_visit_track WHERE sessionid='".filter_sql($sessionid)."'";
   $mydatabase->query($sqlquery); 
 }

 // identity tracking:
 if( ($isnamed=="Y") && ($dbtype != "txt-db-api") && ($CSLH_Config['usertracking']=="Y") ){  
   $thismonth = date("Ym"); 
   $thisday = date("Ymd"); 
   archiveidentity('livehelp_identity_daily',$sessionid,$thisday);
   archiveidentity('livehelp_identity_monthly',$sessionid,$thismonth);
  }

  // Keyword tracking:
 if( ($dbtype != "txt-db-api") && ($CSLH_Config['keywordtrack']=="Y") ){  
   $thismonth = date("Ym"); 
   $thisday = date("Ymd"); 
   archivekeywords('livehelp_keywords_daily',$camefrom,$thisday);
   archivekeywords('livehelp_keywords_monthly',$camefrom,$thismonth);
  }  

  // delete user 
  $sqlquery = "DELETE FROM livehelp_users WHERE sessionid='".filter_sql($sessionid)."'";
  $mydatabase->query($sqlquery);  
  $sqlquery = "DELETE FROM livehelp_channels WHERE user_id=".intval($user_id);
  $mydatabase->query($sqlquery); 

}    

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * Archives off a user:
  *
  * @param string SessionID 
  */
function archiveidentity($tablename,$sessionid,$dateof){
	global $mydatabase,$CSLH_Config;
		 
	// get visitor information:	 
	 $sqlquery = "SELECT user_id,showedup,isnamed,identity,username,lastaction,ipaddress,cookieid,useragent FROM livehelp_users WHERE sessionid='".filter_sql($sessionid)."'";
   $res = $mydatabase->query($sqlquery); 
   $res_array = $res->fetchRow(DB_FETCHMODE_ORDERED); 
	 $user_id = $res_array[0];
	 $showedup = $res_array[1];
	 $isnamed = $res_array[2];
	 $identitystring = $res_array[3]; 
	 $username = $res_array[4]; 
	 $lastaction = $res_array[5];  
   $ipaddress = $res_array[6]; 
   $cookieid = $res_array[7];
   $useragent = $res_array[8];
	 $seconds = timediff($lastaction,$showedup);
     
 // do we have this user in the identity table by cookie:
 $sqlquery = "SELECT uservisits,id,seconds FROM $tablename WHERE cookieid='".filter_sql($cookieid)."' AND dateof=".intval($dateof)." LIMIT 1";
 $identity_res = $mydatabase->query($sqlquery);     	             
 // found by cookie name:
 if($identity_res->numrows() != 0){ 
    $id_array = $identity_res->fetchRow(DB_FETCHMODE_ORDERED); 
	  $uservisits = $id_array[0];
	  $id = $id_array[1];
	  $timesofar = $id_array[2];	  
 } else {	
 	 // look by identity/username:  
   $sqlquery = "SELECT uservisits,id,seconds FROM $tablename WHERE identity='".filter_sql($identitystring)."' AND username='".filter_sql($username)."' AND dateof=".intval($dateof) . " LIMIT 1";
   $identity_res = $mydatabase->query($sqlquery);     	             
   //found by identity and username:
   if($identity_res->numrows() != 0){ 
   	  $id_array = $identity_res->fetchRow(DB_FETCHMODE_ORDERED); 
	    $uservisits = $id_array[0];
	    $id = $id_array[1];
	    $timesofar = $id_array[2];
	 } else { 
	 	  // find groupidentity 
	 	  $sqlquery = "SELECT groupidentity FROM $tablename WHERE identity='".filter_sql($identitystring)."'";
	 	  $sth = $mydatabase->query($sqlquery);
	 	  if($sth->numrows() == 0){
	 	    $sqlquery = "SELECT MAX(groupidentity) as groupidentity FROM $tablename";
	 	    $sth = $mydatabase->query($sqlquery);
	 	    $row3 = $sth->fetchRow(DB_FETCHMODE_ASSOC);
	 	    $groupidentity = $row3['groupidentity'] + 1;
	 	  } else {
	 	  	$row3 = $sth->fetchRow(DB_FETCHMODE_ASSOC);
	 	  	$groupidentity = $row3['groupidentity']; 
	 	  }  
	 	  //groupusername
	 	  $sqlquery = "SELECT groupusername FROM $tablename WHERE username='".filter_sql($username)."'";
	 	  $sth = $mydatabase->query($sqlquery);
	 	  if($sth->numrows() == 0){
	 	    $sqlquery = "SELECT MAX(groupusername) as groupusername FROM $tablename";
	 	    $sth = $mydatabase->query($sqlquery);
	 	    $row3 = $sth->fetchRow(DB_FETCHMODE_ASSOC);
	 	    $groupusername = $row3['groupusername'] + 1;
	 	  } else {
	 	  	$row3 = $sth->fetchRow(DB_FETCHMODE_ASSOC);
	 	  	$groupusername = $row3['groupusername']; 
	 	  }  
	 	  
      $sqlquery = "INSERT INTO $tablename (groupusername,groupidentity,isnamed,identity,ipaddress,username,dateof,uservisits,seconds) VALUES (".intval($groupusername).",".intval($groupidentity).",'$isnamed','".filter_sql($identitystring)."','".filter_sql($ipaddress)."','".filter_sql($username)."',".intval($dateof).",0,0)";
      $mydatabase->query($sqlquery); 
	    $uservisits = 0;
	    $timesofar = 0;	    
	    $id = mysql_insert_id();
   }
 }
 
 // update row:
 $seconds = $seconds + $timesofar;
 $uservisits++;
 $sqlquery = "UPDATE $tablename SET useragent='".filter_sql($useragent)."',isnamed='$isnamed',ipaddress='".filter_sql($ipaddress)."',uservisits=$uservisits,seconds=$seconds,cookieid='".filter_sql($cookieid)."' WHERE id=$id ";
 $mydatabase->query($sqlquery); 

}    

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * sees if we are allowed to do the function:
  *
  * @param string nameoffunction
  * 
  * @return true if can false if no can 
  */
function isallowed($functionname){
  $disable_functions = split(",",ini_get("disable_functions"));
  if (in_array($functionname,$disable_functions))
    return false;
  else
    return true;
}


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * shows the image in filename  
  * requires filetype
  *
  * @param string filename  
  */
function showimage($filepath,$filetype=""){
    
    if(file_exists($filepath)){
      if(!(empty($filetype))){
        myreadfile($filepath,$filetype); 
      } else {
       $size = @getimagesize($filepath);
       $fp=@fopen($filepath, "rb");
      // if($size && $fp){
        if(!(empty($size['mime']))){      
         myreadfile($filepath,$size['mime']); 
         exit;
        // no mime type.. guess:
        } else { 
         // 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
         switch($size[2]){
         		case 1:
           	  $mime = "image/gif";
           	  break;
            case 2:
              $mime = "image/jpg";
              break;
            case 3:
              $mime = "image/png";
              break;
            case 4:
              $mime = "image/swf";
              break;
            case 5:
              $mime = "image/psd";
              break;
            case 6:
              $mime = "image/bmp";
              break;
            case 7:
            case 8:
            	$mime = "image/tiff";
              break;
            case 9:
              $mime = "image/jpc";
              break;
            case 10:
              $mime = "image/jp2";
              break;   		              		              		              		              		              		              		              		              		              		              		              		              		  
            default:
              $mime = "image/gif";
              break; 
           }
      myreadfile($filepath,$mime); 
      exit; 
    }
    // error can not open file or somthing went wrong with getimagesize/fopen.
    //} else {
    // myreadfile("images/cannotopen.gif","image/gif");
    //}
  } // end if file type not defined.
 // error can not find file:
 } else {
   myreadfile("images/cannotfind.gif","image/gif");
 }   

} // end function.

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~   
/**
  * shows the image in filename depending on servers disabled functions.
  * requires filetype
  *
  * @param string filename  
  */
function myreadfile($filepath,$filetype="image/gif"){
    if (isallowed("readfile")){
    	Header("Content-type: $filetype");
      @readfile($filepath);  
    } else {
    	if (isallowed("fpassthru")){
       $fp = fopen($filepath, 'rb'); 
       Header("Content-type: $filetype");
       Header("Content-Length: " . filesize($filepath)); 
       fpassthru($fp);	
       fclose($fp);
      } else {
       Header("Location: $filepath");
      } 
    }
}


function navspaces($string){
	$spaces = "";
	
	$number = floor((10-(strlen($string)))/2);
	while($number>0){
	  $spaces .= "&nbsp;";
	  $number--;
	}
	
	return $spaces;
}
?>