<?php 
//===========================================================================
//* --    ~~                Crafty Syntax Live Help                ~~    -- *
//===========================================================================
//           URL:   http://www.craftysyntax.com/    EMAIL: ericg@craftysyntax.com
//         Copyright (C) 2003-2008 Eric Gerdes   (http://www.craftysyntax.com )
// --------------------------------------------------------------------------
// $              CVS will be released with version 3.1.0                   $
// $    Please check http://www.craftysyntax.com/ or REGISTER your program for updates  $
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
// --------------------------------------------------------------------------  

require_once 'security.php';
require_once 'config.php';
require_once 'config_cslh.php';

$errors ="";
$installationtype = "";
$skipwrite = false;
if(empty($UNTRUSTED['txtpath'])) $UNTRUSTED['txtpath'] = "";
if(!(empty($UNTRUSTED['installationtype'])))
  $installationtype = $UNTRUSTED['installationtype'];
else {
  $installationtype = "";
  $UNTRUSTED['installationtype']  = "";
}
          
if(!(isset($UNTRUSTED['manualinstall']))){ $UNTRUSTED['manualinstall']=""; }
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
  $UNTRUSTED['manualinstall']="YES";
  
// The version that this setup.php installs:
$version = "2.15.0";

if(empty($UNTRUSTED['action'])){ $UNTRUSTED['action']  = ""; }

// first check to see if CSLH is already installed:
if ($installed == true){     
	  // check version installed.
	  if($version == $CSLH_Config['version']){
	  	 // already installed ask to delete setup.php:
       print "<br><br><font color=990000>Please delete file named:<b>setup.php</b></font><br> File is only used for installation and the installation program has already been run. <br><br>";
       print "<a href=config_cslh.php?removesetup=1>Click here to have server try to remove file.</a> (This may fail if server does not run as user. If so, delete it manually and then re-visit index.php file)";
       print "<br><br>";
       print "<br>After removing file <a href=index.php>click here</a>";
       exit;	
    } else {
       $UNTRUSTED['action'] = "INSTALL";
        // set upgrade installationtype
        if($CSLH_Config['version'] == "2.2"){ $installationtype = "upgrade22"; }
         if($CSLH_Config['version'] == "2.3"){ $installationtype = "upgrade22"; }
         if($CSLH_Config['version'] == "2.4"){ $installationtype = "upgrade24"; }
         if($CSLH_Config['version'] == "2.5"){ $installationtype = "upgrade25"; }
         if($CSLH_Config['version'] == "2.6"){ $installationtype = "upgrade26"; }
         if($CSLH_Config['version'] == "2.7"){ $installationtype = "upgrade27"; }
         if($CSLH_Config['version'] == "2.7.1"){ $installationtype = "upgrade271"; }
         if($CSLH_Config['version'] == "2.7.2"){ $installationtype = "upgrade272"; }
         if($CSLH_Config['version'] == "2.7.3"){ $installationtype = "upgrade273"; }
         if($CSLH_Config['version'] == "2.7.4"){ $installationtype = "upgrade274"; }    
         if($CSLH_Config['version'] == "2.8.0"){ $installationtype = "upgrade280"; }    
         if($CSLH_Config['version'] == "2.8.1"){ $installationtype = "upgrade281"; } 
         if($CSLH_Config['version'] == "2.8.2"){ $installationtype = "upgrade282"; }     
         if($CSLH_Config['version'] == "2.8.3"){ $installationtype = "upgrade283"; }  
         if($CSLH_Config['version'] == "2.8.4"){ $installationtype = "upgrade284"; }     
         if($CSLH_Config['version'] == "2.9.0"){ $installationtype = "upgrade290"; }     
         if($CSLH_Config['version'] == "2.9.1"){ $installationtype = "upgrade291"; }  
         if($CSLH_Config['version'] == "2.9.2"){ $installationtype = "upgrade292"; }  
         if($CSLH_Config['version'] == "2.9.3"){ $installationtype = "upgrade293"; }  
         if($CSLH_Config['version'] == "2.9.4"){ $installationtype = "upgrade294"; }  
         if($CSLH_Config['version'] == "2.9.5"){ $installationtype = "upgrade295"; }  
         if($CSLH_Config['version'] == "2.9.6"){ $installationtype = "upgrade296"; }  
         if($CSLH_Config['version'] == "2.9.7"){ $installationtype = "upgrade297"; }      
         if($CSLH_Config['version'] == "2.9.8"){ $installationtype = "upgrade298"; }  
         if($CSLH_Config['version'] == "2.9.9"){ $installationtype = "upgrade299"; }  
         if($CSLH_Config['version'] == "2.10.0"){ $installationtype = "upgrade2100"; }  
         if($CSLH_Config['version'] == "2.10.1"){ $installationtype = "upgrade2101"; }  
         if($CSLH_Config['version'] == "2.10.2"){ $installationtype = "upgrade2102"; }  
         if($CSLH_Config['version'] == "2.10.3"){ $installationtype = "upgrade2103"; }  
         if($CSLH_Config['version'] == "2.10.4"){ $installationtype = "upgrade2104"; } 
         if($CSLH_Config['version'] == "2.10.5"){ $installationtype = "upgrade2105"; }
         if($CSLH_Config['version'] == "2.11.0"){ $installationtype = "upgrade2110"; }
         if($CSLH_Config['version'] == "2.11.1"){ $installationtype = "upgrade2111"; }
         if($CSLH_Config['version'] == "2.11.2"){ $installationtype = "upgrade2112"; }
         if($CSLH_Config['version'] == "2.11.3"){ $installationtype = "upgrade2113"; }
         if($CSLH_Config['version'] == "2.11.4"){ $installationtype = "upgrade2114"; }
         if($CSLH_Config['version'] == "2.11.5"){ $installationtype = "upgrade2115"; }
         if($CSLH_Config['version'] == "2.11.6"){ $installationtype = "upgrade2116"; }
         if($CSLH_Config['version'] == "2.11.7"){ $installationtype = "upgrade2117"; }
         if($CSLH_Config['version'] == "2.12.0"){ $installationtype = "upgrade2120"; }    
         if($CSLH_Config['version'] == "2.12.1"){ $installationtype = "upgrade2121"; }      
         if($CSLH_Config['version'] == "2.12.2"){ $installationtype = "upgrade2122"; }  
         if($CSLH_Config['version'] == "2.12.3"){ $installationtype = "upgrade2123"; } 
         if($CSLH_Config['version'] == "2.12.4"){ $installationtype = "upgrade2124"; } 
         if($CSLH_Config['version'] == "2.12.5"){ $installationtype = "upgrade2125"; }
         if($CSLH_Config['version'] == "2.12.6"){ $installationtype = "upgrade2126"; }
         if($CSLH_Config['version'] == "2.12.7"){ $installationtype = "upgrade2127"; }
         if($CSLH_Config['version'] == "2.12.8"){ $installationtype = "upgrade2128"; }
         if($CSLH_Config['version'] == "2.12.9"){ $installationtype = "upgrade2129"; } 
         if($CSLH_Config['version'] == "2.13.0"){ $installationtype = "upgrade2130"; }                  
         if($CSLH_Config['version'] == "2.13.1"){ $installationtype = "upgrade2131"; }  
         if($CSLH_Config['version'] == "2.14.0"){ $installationtype = "upgrade2140"; } 
         if($CSLH_Config['version'] == "2.14.1"){ $installationtype = "upgrade2141"; } 
         if($CSLH_Config['version'] == "2.14.2"){ $installationtype = "upgrade2142"; } 
         if($CSLH_Config['version'] == "2.14.3"){ $installationtype = "upgrade2143"; } 
         if($CSLH_Config['version'] == "2.14.4"){ $installationtype = "upgrade2144"; } 
         if($CSLH_Config['version'] == "2.14.5"){ $installationtype = "upgrade2145"; } 
         if($CSLH_Config['version'] == "2.14.6"){ $installationtype = "upgrade2146"; } 
        
         if(!(empty($installationtype))){
         	  $UNTRUSTED['password1'] = "na";
         	  $UNTRUSTED['password2'] = "na";
         	  $UNTRUSTED['email'] = "na";
         	  $UNTRUSTED['homepage'] = "na";
         	  $UNTRUSTED['dbtype_setup'] = "mysql";
         	  $UNTRUSTED['txtpath'] = "";
         	  $UNTRUSTED['dbpath'] = "";
         	  $UNTRUSTED['speaklanguage'] = "";
         	  $UNTRUSTED['username'] = "";
         	  $UNTRUSTED['opening'] = "";
         	  
         	  
         	  $UNTRUSTED['server_setup'] = $server;
            $UNTRUSTED['database_setup'] = $database;
            $UNTRUSTED['datausername_setup'] = $datausername;
            $UNTRUSTED['mypassword_setup'] = $password; 
            $UNTRUSTED['rootpath'] = $application_root;
            $skipwrite = true;
         }
      }   
  }

 
// INSTALL CHECK AND SETUP OF config.php 
//========================================================================================= 
if ($UNTRUSTED['action'] == "INSTALL"){
	
 // check for errors.
 if ($UNTRUSTED['password1'] == ""){  
   $errors .= "<li>You did not enter in a password.";
 }

 if ($UNTRUSTED['password1'] != $UNTRUSTED['password2']){  
    $errors .= "<li>The two passwords you entered do not equal eachother .. you might of mistyped it password the second time . please retype the passwords you entered in again.";
 }

 if(empty($UNTRUSTED['email'])){ $errors .= "<li>You did not enter in a e-mail address. This is important for if you ever lose your password. "; }

 if($UNTRUSTED['dbtype_setup'] == "mysql"){
   if (empty($UNTRUSTED['database_setup'])){ $errors .= "<li>You did not enter in a Mysql database name "; }
   if (empty($UNTRUSTED['datausername_setup'])){ $errors .= "<li>You did not enter in a Mysql Username name "; }        
   if($errors == ""){
     $conn = mysql_connect($UNTRUSTED['server_setup'],$UNTRUSTED['datausername_setup'],$UNTRUSTED['mypassword_setup']);		
     if(!$conn) {
    	  $errors .= "<li>Connection to the database failed. You may have the wrong database username/password "; 
     } 
     if(!mysql_select_db($UNTRUSTED['database_setup'],$conn)) {
		    $errors .= "<li>Database select failed. You may have the wrong database "; 
     }
   } // errors == ""
  } // dbtype_setup = mysql

 if( (empty($UNTRUSTED['homepage']) || ($UNTRUSTED['homepage'] == "http://www.urltoyourwebsite.com"))){ 
	  $errors .= "<li>You did not enter in a valid homepage address. ";
 } 

 if ($errors == ""){
  
  // define undefined:
  if(empty($UNTRUSTED['dbpath'])) 
     $UNTRUSTED['dbpath'] = "";
  if(empty($UNTRUSTED['dbpath_setup'])) 
     $UNTRUSTED['dbpath_setup'] = "";

  // setup the config file..
  $fcontents = @implode ('', @file('config.orig.php'));
  if(empty($fcontents)){
  	$fcontents = "<?php 
 
// set this to true if false you will be re-directed to the setup page
\$installed=true;  

if(empty(\$_SERVER)){ \$_SERVER = \$HTTP_SERVER_VARS; }

// if this program has not been installed and this is not the setup.php
// re-direct to that page to setup database..
if( (\$installed == false) && (!eregi(\"setup.php\", \$_SERVER['PHP_SELF'])) ){ 
 Header(\"Location: setup.php\");
 exit;
}

// if this file has insecure permissions:
if(\$installed==true){
 \$perm = @stat(\"config.php\");
 if(!(empty(\$perm[2]))){
  if( (\$perm[2] == \"33279\") || (\$perm[2] == \"33278\") || (\$perm[2] == \"33270\")  ){
  	@chmod(\"config.php\", 0755);
  	\$perm = @stat(\"config.php\");
    if(!(empty(\$perm[2]))){
      if( (\$perm[2] == \"33279\") || (\$perm[2] == \"33278\") || (\$perm[2] == \"33270\")  ){
  	      print \"<font color=990000>You must secure this program. Insecure permissions on config.php</font>\";
  	      print \"<br>While installing CSLH you might of needed to change the permissions of config.php so \";
  	      print \"that it is writable by the web server. config.php no longer needs to be written to so \";
  	      print \" please chmod config.php to not have write permissions for everyone. you can do this by\";
  	      print \" UNCHECKING the box that reads write permissions for the file:\";
          print \"<br><br><img src=directions2.gif>\";    
          exit;
       }
     }
    }      
  }
 } 
 
 // dbtype either is either:
 // mysql       - this is for Mysql support
 // txt-db-api  - txt database support. 
 \$dbtype = \"INPUT-DBTYPE\";

 //database connections for MYSQL 
 \$server = \"INPUT-SERVER\";
 \$database = \"INPUT-DATABASE\";
 \$datausername = \"INPUT-DATAUSERNAME\";
 \$password = \"INPUT-PASSWORD\";

 // change this to the full SERVER path to your files 
 // on the server .. not the HTTP PATH.. for example enter in
 // \$application_root = \"/usr/local/apache/htdocs/livehelp/\"
 // not /livehelp/
 // keep ending slash.
 // WINDOWS would look something like:
 // \$application_root = \"D:\\virtual www customers\\craftysyntax\\livehelp_1_6\\\";
 \$application_root = \"INPUT-ROOTPATH\";

 // if using txt-db-api need the path to the txt databases directory
 \$DB_DIR = \"INPUT-TXTPATH\";
 // if using txt-db-api need to have the full path to the txt-db-api
 // you must set this property to something like /home/website/livehelp/txt-db-api/ 
 \$API_HOME_DIR = \"INPUT-ROOTPATH\" . \"txt-db-api/\";
 ?>";
  }
  $fcontents = ereg_replace("installed=false","installed=true",$fcontents);
  $fcontents = ereg_replace("INPUT-DBTYPE",addslashes($UNTRUSTED['dbtype_setup']),$fcontents);
  $fcontents = ereg_replace("INPUT-SERVER",addslashes($UNTRUSTED['server_setup']),$fcontents);
  $fcontents = ereg_replace("INPUT-DATABASE",addslashes($UNTRUSTED['database_setup']),$fcontents);
  $fcontents = ereg_replace("INPUT-DATAUSERNAME",addslashes($UNTRUSTED['datausername_setup']),$fcontents);
  $fcontents = ereg_replace("INPUT-PASSWORD",addslashes($UNTRUSTED['mypassword_setup']),$fcontents);
  $lastchar = substr($UNTRUSTED['txtpath'],-1);
  $txtpath = ($lastchar != C_DIR) ?  $UNTRUSTED['txtpath'] . C_DIR : $UNTRUSTED['txtpath'];
  $lastchar = substr($UNTRUSTED['rootpath'],-1);  
  $rootpath = ($lastchar != C_DIR) ?  $UNTRUSTED['rootpath'] . C_DIR : $UNTRUSTED['rootpath'];
  $lastchar = substr($UNTRUSTED['dbpath'],-1);  
  $dbpath = ($lastchar != C_DIR) ?  $UNTRUSTED['dbpath'] . C_DIR:  $UNTRUSTED['dbpath'];
  $lastchar = substr($UNTRUSTED['homepage'],-1);
  $homepage = ($lastchar != "/") ?  $UNTRUSTED['homepage'] . "/" :  $UNTRUSTED['homepage'];
  
  if(empty($UNTRUSTED['s_homepage']))
     $UNTRUSTED['s_homepage'] = $UNTRUSTED['homepage'];
     
  $lastchar = substr($UNTRUSTED['s_homepage'],-1);
  $s_homepage = ($lastchar != "/") ?  $UNTRUSTED['s_homepage'] . "/" :  $UNTRUSTED['s_homepage'];
   
  $fcontents = ereg_replace("INPUT-TXTPATH",addslashes($txtpath),$fcontents);
  $fcontents = ereg_replace("INPUT-ROOTPATH",addslashes($rootpath),$fcontents);
  //$fcontents = ereg_replace("INPUT-DBPATH",addslashes($dbpath_setup),$fcontents);
  $fcontents = ereg_replace("INPUT-HTTP",$homepage,$fcontents);
 
  $insert_query = "INSERT INTO livehelp_config (version, site_title, use_flush, membernum, offset, show_typing,webpath,s_webpath,speaklanguage,maxexe,refreshrate,chatmode,adminsession,maxreferers,maxvisits,maxmonths,maxoldhits,maxrecords,admin_refresh,owner_email) VALUES ('$version', 'Live Help!', 'YES', 0, 0, 'Y','".filter_sql($homepage)."','".filter_sql($s_homepage)."','".filter_sql($UNTRUSTED['speaklanguage'])."',180,1,'xmlhttp-flush-refresh','Y',50,75,12,1,75000,'auto','".filter_sql($UNTRUSTED['email'])."')";
  $insert_query2 = "INSERT INTO livehelp_users (username,password,isonline,isoperator,isadmin,isnamed,email,show_arrival,user_alert,auto_invite,greeting,photo) VALUES ('".filter_sql($UNTRUSTED['username'])."','".filter_sql($UNTRUSTED['password1'])."','N','Y','Y','Y','".filter_sql($UNTRUSTED['email'])."','N','N','Y','How may I help You?','')";   
  $insert_query3 = "INSERT INTO livehelp_departments (nameof, onlineimage, offlineimage, requirename, messageemail, leaveamessage, opening, offline, creditline, layerinvite, imagemap,whilewait,timeout,topframeheight,topbackground,colorscheme,busymess) VALUES ('default', 'onoff_images/online1.gif', 'onoff_images/offline1.gif', 'Y', '".filter_sql($UNTRUSTED['email'])."', 'YES', '<blockquote>".filter_sql($UNTRUSTED['opening'])."</blockquote>', '<blockquote>Sorry no operators are currently online to provide Live support at this time.</blockquote>', 'L','dhtmlimage.gif','<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,0,400,197><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,157,213,257><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=237,157,400,257></MAP>','Please be patient while an operator is contacted... ','150','45','topclouds.gif','blue','<blockquote>Sorry all operators are currently helping other clients and are unable to provide Live support at this time.<br>Would you like to continue to wait for an operator or leave a message?<br><table width=450><tr><td width=40%><a href=livehelp.php?page=livehelp.php&department=[department]&tab=1 target=_top><font size=+1>Continue to wait</font></a></td><td width=20% align=center><b>or</b></td><td width=40%><a href=leavemessage.php?department=[department]><font size=+1>Leave A Message</a></td></tr></table><blockquote>')";
  $insert_query4 = "INSERT INTO livehelp_operator_departments (recno, user_id, department, extra) VALUES (1, 1, 1, '')";

  // update the config file.
 if($UNTRUSTED['manualinstall'] != "YES"){
    if(!($skipwrite)){
      $fp = @fopen ("config.php", "w+");
      fwrite($fp,$fcontents);
      fclose($fp);
    }
 } else {
    print "<b>config.php:</b> Select all of the code below and then copy and paste it over your existing config.php file on the server <br><br><font color=990000><b>Note:</b> Be sure there is no Returns or spaces before and after the &lt;?php and ?&gt; markers";
    print "<table bgcolor=DDDDDD><tr><td><pre>" . htmlspecialchars($fcontents) . "</pre></td></tr></table>";
  }
 } // errors == ""
} // action == INSTALL


// INSTALL/UPGRADE DATABASE 
//========================================================================================= 
if ($UNTRUSTED['action'] == "INSTALL"){

 if($errors == ""){
 	 if(empty($installationtype))
   	 $installationtype = $UNTRUSTED['installationtype'];

   if($installationtype == "upgrade22"){
     $sql = "ALTER TABLE `livehelp_config` ADD `speaklanguage` VARCHAR(60) DEFAULT 'English' NOT NULL ";
     mysql_query($sql,$conn);
     $sql = "UPDATE livehelp_users set onchannel=user_id where isadmin='Y'";	
     mysql_query($sql,$conn);
     $installationtype = "upgrade24";
   }

   if($installationtype == "upgrade24"){
     $sql = "UPDATE livehelp_config set speaklanguage='English',version='$version'";
     mysql_query($sql,$conn);
     $sql = "UPDATE livehelp_users set onchannel=user_id where isadmin='Y'";	
     mysql_query($sql,$conn);
     $installationtype = "upgrade25";
   }

   if($installationtype == "upgrade25"){
 
       $sql = "ALTER TABLE livehelp_users ADD auto_invite CHAR(1) NOT NULL ";
       mysql_query($sql,$conn);

       $sql = "ALTER TABLE livehelp_users ADD istyping CHAR(1) NOT NULL  default '1' ";
       mysql_query($sql,$conn);

       $sql = "ALTER TABLE livehelp_users ADD visits INT(8) NOT NULL ";
       mysql_query($sql,$conn);
	
       $sql = "DROP TABLE IF EXISTS livehelp_modules_dep";
       mysql_query($sql,$conn);
       $sql = 
       "CREATE TABLE livehelp_modules_dep (
         rec int(10) NOT NULL auto_increment,
         departmentid int(10) NOT NULL default '0',
         modid int(10) NOT NULL default '0',
         ordernum int(8) NOT NULL default '0',
         defaultset char(1) NOT NULL default '',
         PRIMARY KEY  (rec)
       )TYPE=MyISAM 
       ";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

       $sql = "DROP TABLE IF EXISTS livehelp_autoinvite";
       mysql_query($sql,$conn);
       $sql = 
       "CREATE TABLE `livehelp_autoinvite` (
         `idnum` int(10) NOT NULL auto_increment,
          isactive char(1) NOT NULL default '',  
         `department` int(10) NOT NULL default '0',
         `message` text NULL,
         `page` varchar(255) NOT NULL default '',
         `visits` int(8) NOT NULL default '0',
         `referer` varchar(255) NOT NULL default '',
         `typeof` varchar(30) NOT NULL default '',     
         `seconds` int(11) unsigned NOT NULL default '0',               
          PRIMARY KEY  (idnum)
        )TYPE=MyISAM ";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

       $sql = "DROP TABLE IF EXISTS livehelp_modules";
       mysql_query($sql,$conn);
       $sql = 
       "CREATE TABLE livehelp_modules (
         id int(10) NOT NULL auto_increment,
         name varchar(30) NOT NULL default '',
         path varchar(255) NOT NULL default '',
         adminpath varchar(255) NOT NULL default '',
         query_string varchar(255) NOT NULL default '',  
         PRIMARY KEY  (id)
       )TYPE=MyISAM
       ";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
       $results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path) VALUES (1, 'Live Help!', 'livehelp.php')",$conn);
       $results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path) VALUES (2, 'Contact', 'leavemessage.php')",$conn);
       $results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path,adminpath) VALUES (3, 'Q & A', 'user_qa.php','qa.php')",$conn);
       $results = mysql_query("INSERT INTO `livehelp_modules_dep` VALUES (1, 1, 1, 1, '')",$conn);
       $results = mysql_query("INSERT INTO `livehelp_modules_dep` VALUES (2, 1, 2, 2, 'Y')",$conn);
 
      
      $installationtype = "upgrade26";
  }

    if($installationtype == "upgrade26"){
       $sql = "UPDATE livehelp_config set speaklanguage='English',version='$version'";
       mysql_query($sql,$conn);
       $installationtype = "upgrade27";
     }

     if($installationtype == "upgrade27"){
        $sql = "ALTER TABLE `livehelp_config` CHANGE `version` `version` VARCHAR( 10 ) DEFAULT '$version' NOT NULL ";
        mysql_query($sql,$conn);
        $installationtype = "upgrade271";
     }

     if( ($installationtype == "upgrade271") || 
         ($installationtype == "upgrade272") || 
         ($installationtype == "upgrade273") ||         
         ($installationtype == "upgrade274") ){


         $sql = "ALTER TABLE `livehelp_departments` ADD `layerinvite` VARCHAR( 255 ) NOT NULL";
         mysql_query($sql,$conn);       
                  
         $sql = "ALTER TABLE `livehelp_config` ADD `s_webpath` VARCHAR(255) NOT NULL ";
         mysql_query($sql,$conn);

         $sql = "ALTER TABLE `livehelp_config` ADD `scratch_space` TEXT NULL ";
         mysql_query($sql,$conn);

        $sql = "ALTER TABLE `livehelp_users` ADD `jsrn` INT(5) NOT NULL ";
         mysql_query($sql,$conn);
  
        $sql = "ALTER TABLE `livehelp_messages` ADD `typeof` VARCHAR(30) NOT NULL ";
         mysql_query($sql,$conn);                                      
     
         // select out all of the online and offline images and remove the path 
         // to the live help (this is taken care of by webpath and s_webpath now.
         $sql = "SELECT * FROM livehelp_departments";
         $result = mysql_query($sql,$conn);
         while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
             $onlineimage = str_replace($UNTRUSTED['homepage'],"",$row['onlineimage']);
             $offlineimage = str_replace($UNTRUSTED['homepage'],"",$row['offlineimage']);
             $layerinvite = str_replace($UNTRUSTED['homepage'],"",$row['layerinvite']);
             $recno = $row['recno'];
             $sql = "UPDATE livehelp_departments set onlineimage='".filter_sql($onlineimage)."',offlineimage='".filter_sql($offlineimage)."',layerinvite='".filter_sql($layerinvite)."' WHERE recno=".intval($recno);
             mysql_query($sql,$conn);
         }          

        $installationtype = "upgrade280";
     }

     if( ($installationtype == "upgrade280") || 
         ($installationtype == "upgrade281")
        ){         	
        
        $sql = "ALTER TABLE `livehelp_users` ADD `hostname` VARCHAR( 255 ) NOT NULL ,ADD `useragent` VARCHAR( 255 ) NOT NULL ,ADD `ipaddress` VARCHAR( 255 ) NOT NULL ;";
        mysql_query($sql,$conn);      

        $scratch = "
          Welcome to Crafty Syntax Live Help 
 
All the administrative functions are located to the left of this text. 
 
You can use this section to keep notes for yourself and other admins, etc.  

To change the text that is located in this box just click on the small 
edit button on the top right corner of this box. 

        ";
        $sql = "UPDATE livehelp_config set scratch_space='$scratch'";
        mysql_query($sql,$conn);
        
        $installationtype = "upgrade282";
     }
     
if( ($installationtype == "upgrade282") ){ 
	 
     $sql = "ALTER TABLE `livehelp_referers` ADD `parentrec` INT( 10 ) NOT NULL";
     mysql_query($sql,$conn);

     $sql = "ALTER TABLE `livehelp_referers_total` ADD `dateof` INT( 7 ) NOT NULL";
     mysql_query($sql,$conn);
     
     $sql = "ALTER TABLE `livehelp_referers_total` ADD `parentrec` INT( 10 ) NOT NULL";
     mysql_query($sql,$conn);

     $sql = "ALTER TABLE `livehelp_visits_total` ADD `dateof` INT( 7 ) NOT NULL";
     mysql_query($sql,$conn);
          
     $sql = "ALTER TABLE `livehelp_config` ADD `admin_refresh` VARCHAR(30) NOT NULL";
     mysql_query($sql,$conn);

     $sql = "ALTER TABLE `livehelp_autoinvite` ADD `typeof` VARCHAR(30) NOT NULL";
     mysql_query($sql,$conn);

     $sql = "ALTER TABLE `livehelp_departments` ADD `imagemap` TEXT NULL";
     mysql_query($sql,$conn);

     $sql = "ALTER TABLE `livehelp_operator_channels` ADD `txtcolor` VARCHAR( 10 ) NOT NULL";
     mysql_query($sql,$conn);     

     $sql = "ALTER TABLE `livehelp_operator_channels` ADD `channelcolor` VARCHAR( 10 ) NOT NULL";
     mysql_query($sql,$conn);  
                         
     $sql = "UPDATE livehelp_departments set creditline='L',imagemap='<MAP NAME=myimagemap><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,0,400,197><AREA HREF=javascript:openLiveHelp() SHAPE=RECT COORDS=0,157,213,257><AREA HREF=javascript:closeDHTML() SHAPE=RECT COORDS=237,157,400,257></MAP>'";
     mysql_query($sql,$conn);

$installationtype = "upgrade284";
}

if( ($installationtype == "upgrade284") ){                                 
     $sql = "ALTER TABLE `livehelp_departments` ADD `whilewait` TEXT NULL";
     mysql_query($sql,$conn);
     
     $sql = "ALTER TABLE `livehelp_quick` ADD `ishtml` VARCHAR( 3 ) NOT NULL ";
     mysql_query($sql,$conn);

     $sql = "ALTER TABLE `livehelp_users` ADD `sessionid` VARCHAR( 40 ) NOT NULL ";
     mysql_query($sql,$conn);

     $sql = "ALTER TABLE `livehelp_users` ADD `sessiondata` TEXT NULL ";
     mysql_query($sql,$conn);
     
     $sql = "ALTER TABLE `livehelp_users` ADD `cookied` CHAR( 1 ) DEFAULT 'N' NOT NULL";
     mysql_query($sql,$conn);
     
     $sql = "ALTER TABLE `livehelp_users` ADD `expires` bigint(14) NOT NULL";
     mysql_query($sql,$conn);
          
     $sql = "ALTER TABLE `livehelp_users` ADD `authenticated` CHAR(1) NOT NULL ";
     mysql_query($sql,$conn);

    $sql = "ALTER TABLE `livehelp_departments` ADD `timeout` INT( 4 ) NOT NULL";
    mysql_query($sql,$conn);

    $sql = "UPDATE `livehelp_departments` SET `timeout`=150";
    mysql_query($sql,$conn);
      
    $sql = "ALTER TABLE `livehelp_transcripts` ADD `sessionid` VARCHAR( 40 ) NOT NULL , ADD `sessiondata` TEXT NULL , ADD `department` INT( 10 ) NOT NULL ";
    mysql_query($sql,$conn);      
      
    $sql = "
      CREATE TABLE `livehelp_questions` (
      `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
      `department` INT( 10 ) NOT NULL  default '0',
      `ordering` INT( 8 ) NOT NULL default '0',
      `headertext` TEXT NULL,
      `fieldtype` VARCHAR( 30 ) NOT NULL default '0',
      `options` TEXT NULL,
      `flags` VARCHAR( 60 ) NOT NULL default '',
      `module` VARCHAR( 60 ) NOT NULL default '',
      `required` CHAR( 1 ) DEFAULT 'N' NOT NULL,
      PRIMARY KEY ( `id` ) ,
      INDEX ( `department` ) 
      )TYPE=MyISAM
    ";
    mysql_query($sql,$conn);
          
    $sql = 
       "CREATE TABLE `livehelp_smilies` (
        `smilies_id` smallint(5) unsigned NOT NULL auto_increment,
        `code` varchar(50) default NULL,
        `smile_url` varchar(100) default NULL,
        `emoticon` varchar(75) default NULL,
        PRIMARY KEY  (`smilies_id`)
       ) TYPE=MyISAM
       ";
 		   $results = mysql_query($sql,$conn);
 		$sql = "INSERT INTO `livehelp_smilies` VALUES (1, ':D', 'icon_biggrin.gif', 'Very Happy')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (2, ':-D', 'icon_biggrin.gif', 'Very Happy')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (3, ':grin:', 'icon_biggrin.gif', 'Very Happy')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (4, ':)', 'icon_smile.gif', 'Smile')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (5, ':-)', 'icon_smile.gif', 'Smile')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (6, ':smile:', 'icon_smile.gif', 'Smile')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (7, ':(', 'icon_sad.gif', 'Sad')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (8, ':-(', 'icon_sad.gif', 'Sad')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (9, ':sad:', 'icon_sad.gif', 'Sad')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (10, ':o', 'icon_surprised.gif', 'Surprised')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (11, ':-o', 'icon_surprised.gif', 'Surprised')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (12, ':eek:', 'icon_surprised.gif', 'Surprised')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (13, ':shock:', 'icon_eek.gif', 'Shocked')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (14, ':?', 'icon_confused.gif', 'Confused')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (15, ':-?', 'icon_confused.gif', 'Confused')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (16, ':???:', 'icon_confused.gif', 'Confused')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (17, '8)', 'icon_cool.gif', 'Cool')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (18, '8-)', 'icon_cool.gif', 'Cool')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (19, ':cool:', 'icon_cool.gif', 'Cool')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (20, ':lol:', 'icon_lol.gif', 'Laughing')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (21, ':x', 'icon_mad.gif', 'Mad')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (22, ':-x', 'icon_mad.gif', 'Mad')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (23, ':mad:', 'icon_mad.gif', 'Mad')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (24, ':P', 'icon_razz.gif', 'Razz')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (25, ':-P', 'icon_razz.gif', 'Razz')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (26, ':razz:', 'icon_razz.gif', 'Razz')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (27, ':oops:', 'icon_redface.gif', 'Embarassed')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (28, ':cry:', 'icon_cry.gif', 'Crying or Very sad')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (32, ':wink:', 'icon_wink.gif', 'Wink')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (33, ';)', 'icon_wink.gif', 'Wink')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (34, ';-)', 'icon_wink.gif', 'Wink')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (35, ':!:', 'icon_exclaim.gif', 'Exclamation')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (36, ':?:', 'icon_question.gif', 'Question')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (37, ':idea:', 'icon_idea.gif', 'Idea')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (38, ':arrow:', 'icon_arrow.gif', 'Arrow')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (39, ':|', 'icon_neutral.gif', 'Neutral')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (40, ':-|', 'icon_neutral.gif', 'Neutral')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (41, ':neutral:', 'icon_neutral.gif', 'Neutral')";
 		$results = mysql_query($sql,$conn);
$sql = "INSERT INTO  `livehelp_smilies` VALUES (42, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green')";
 		$results = mysql_query($sql,$conn);
     mysql_query($sql,$conn);		          
     
        $scratch = "
           Welcome to Crafty Syntax Live Help 

All the administrative functions are located to the left of this text. 
 
You can use this section to keep notes for yourself and other admins, etc. 
 
To change the text that is located in this box just click on the small 
edit button on the top right corner of this box. 
 
        ";
        $sql = "UPDATE livehelp_config set scratch_space='$scratch'";

$installationtype = "upgrade290";
}
if( ($installationtype == "upgrade290") ){ 
	 
	  $sql = "ALTER TABLE `livehelp_config` CHANGE `speaklanguage` `speaklanguage` VARCHAR( 60 ) DEFAULT 'English' NOT NULL";
	  mysql_query($sql,$conn);
    
    $sql = "ALTER TABLE `livehelp_departments` ADD `leavetxt` TEXT NULL";
    mysql_query($sql,$conn);               

    $sql = "UPDATE livehelp_departments set leavetxt='<h3><SPAN CLASS=wh>LEAVE A MESSAGE:</SPAN></h3>Please type in your comments/questions in the below box <br> and provide an e-mail address so we can get back to you'";
    mysql_query($sql,$conn);    
    
$installationtype = "upgrade291";
}
if( ($installationtype == "upgrade291") || ($installationtype == "upgrade292") ){ 

 
    $sql = "ALTER TABLE `livehelp_users` ADD `greeting` TEXT NULL";
    mysql_query($sql,$conn); 
    
    $sql = "ALTER TABLE `livehelp_users` ADD `photo` VARCHAR( 255 ) NOT NULL";
    mysql_query($sql,$conn); 
    
    $sql = "UPDATE `livehelp_users` set `greeting`='How may I help You?',photo=''";
    mysql_query($sql,$conn); 

$installationtype = "upgrade293"; 
}

if($installationtype == "upgrade293") {
	
	 $sql = "ALTER TABLE `livehelp_users` ADD `chataction` BIGINT( 14 ) NOT NULL";
	 mysql_query($sql,$conn);
	 $sql = "ALTER TABLE `livehelp_config` ADD PRIMARY KEY ( `version` )";
	 mysql_query($sql,$conn);
	 
$installationtype = "upgrade294"; 
} 

if( ($installationtype == "upgrade294") || ($installationtype == "upgrade295") ){

   $sql = "ALTER TABLE `livehelp_users` ADD `new_session` CHAR( 1 ) DEFAULT 'Y' NOT NULL";
   mysql_query($sql,$conn);
$installationtype = "upgrade296"; 
}

if($installationtype == "upgrade296"){
   $sql = "ALTER TABLE `livehelp_users` ADD `showtype` INT( 10 ) DEFAULT '1' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_config` ADD `maxexe` int(5) DEFAULT '180'";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_transcripts` CHANGE `daytime` `daytime` BIGINT( 14 ) DEFAULT NULL";
   mysql_query($sql,$conn);      	
   $sql = "ALTER TABLE `livehelp_visit_track` CHANGE `whendone` `whendone` BIGINT(14) DEFAULT NULL"; 
   mysql_query($sql,$conn);  
   $sql = "ALTER TABLE `livehelp_config` ADD `refreshrate` INT(4) DEFAULT '1' NOT NULL";
   mysql_query($sql,$conn);    
   $sql = "UPDATE livehelp_config set admin_refresh='auto',membernum='0',speaklanguage='English',version='$version'";
   mysql_query($sql,$conn);
   
   $installationtype = "upgrade297";    
}
if($installationtype == "upgrade297"){


   $sql = "ALTER TABLE `livehelp_departments` ADD `topframeheight` INT( 10 ) DEFAULT '35' NOT NULL";
   mysql_query($sql,$conn);   
   $sql = "ALTER TABLE `livehelp_departments` ADD `topbackground` VARCHAR( 255 ) NOT NULL";
   mysql_query($sql,$conn);   
   $sql = "ALTER TABLE `livehelp_departments` ADD `colorscheme` VARCHAR( 255 ) NOT NULL";
   mysql_query($sql,$conn);   
   $sql = "ALTER TABLE `livehelp_transcripts` ADD `email` VARCHAR( 60 ) NOT NULL";
   mysql_query($sql,$conn);   
   $sql = "UPDATE livehelp_departments set topframeheight='45',topbackground='topclouds.gif',colorscheme='blue'";
   mysql_query($sql,$conn);   
   $installationtype = "upgrade298";   
 }
   
if( ($installationtype == "upgrade298") || ($installationtype == "upgrade299") ){
   $sql = "ALTER TABLE `livehelp_config` ADD `chatmode` VARCHAR( 60 ) NOT NULL";
   mysql_query($sql,$conn);   

   $sql = "ALTER TABLE `livehelp_config` ADD `adminsession` CHAR( 1 ) DEFAULT 'Y' NOT NULL";
   mysql_query($sql,$conn);  

   $sql = "ALTER TABLE `livehelp_users` ADD `chattype` CHAR( 1 ) DEFAULT 'Y' NOT NULL";
   mysql_query($sql,$conn);
   
   $sql = "ALTER TABLE `livehelp_users` ADD `externalchats` VARCHAR( 255 ) DEFAULT '' NOT NULL";
   mysql_query($sql,$conn);

   $sql = "ALTER TABLE `livehelp_operator_channels` ADD `txtcolor_alt` VARCHAR( 10 ) NOT NULL";
   mysql_query($sql,$conn);      

        $scratch = "
        
  Welcome to Crafty Syntax Live Help 
  
All the administrative functions are located to the left of this text. 
 
You can use this section to keep notes for yourself and other admins, etc.
 
To change the text that is located in this box just click on the small 
edit button on the top right corner of this box. 
 
        ";
        $sql = "UPDATE livehelp_config set scratch_space='$scratch'";
        mysql_query($sql,$conn);
        
  $installationtype = "upgrade2100";
}   

if($installationtype == "upgrade2100"){

   $sql = "ALTER TABLE `livehelp_quick` CHANGE `department` `department` VARCHAR( 60 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);        
   
   $installationtype = "upgrade2101";
  }
  
if($installationtype == "upgrade2101"){  

    $sql = "
CREATE TABLE `livehelp_operator_history` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `opid` int(11) unsigned NOT NULL default '0',
  `action` varchar(60) NOT NULL default '',
  `dateof` bigint(14) NOT NULL default '0',
  `sessionid` varchar(40) NOT NULL default '',
  `transcriptid` int(10) NOT NULL default '0',
  `totaltime` int(10) NOT NULL default '0',
  `channel` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  INDEX ( `opid` ), 
  INDEX ( `dateof` )   
) TYPE=MyISAM AUTO_INCREMENT=1
    ";
    mysql_query($sql,$conn);
   
   $sql = "ALTER TABLE `livehelp_departments` ADD `speaklanguage` VARCHAR( 60 ) NOT NULL";
   mysql_query($sql,$conn);

   $sql = "ALTER TABLE `livehelp_departments` ADD `busymess` TEXT NULL";
   mysql_query($sql,$conn);

   $sql = "ALTER TABLE `livehelp_config` ADD `ignoreips` TEXT NULL";
   mysql_query($sql,$conn);   
     
   $sql = "UPDATE livehelp_departments set busymess='<blockquote>Sorry all operators are currently helping other clients and are unable to provide Live support at this time.<br>Would you like to continue to wait for an operator or leave a message?<br><table width=450><tr><td width=40%><a href=livehelp.php?page=livehelp.php?page=livehelp.php&department=[department]&tab=1 target=_top><font size=+1>Continue to wait</font></a></td><td width=20% align=center><b>or</b></td><td width=40%><a href=leavemessage.php?department=[department]><font size=+1>Leave a message</a></td></tr></table><blockquote>'";
   mysql_query($sql,$conn);
         
   $installationtype = "upgrade2102";
  }
  
 
if( ($installationtype == "upgrade2103") || ($installationtype == "upgrade2102") ){ 
 
   $sql = "ALTER TABLE `livehelp_config` ADD `directoryid` VARCHAR( 32 ) NOT NULL";
   mysql_query($sql,$conn); 

   $sql = "ALTER TABLE `livehelp_config` ADD `tracking` CHAR( 1 ) DEFAULT 'N' NOT NULL";
   mysql_query($sql,$conn); 

   $sql = "ALTER TABLE `livehelp_config` ADD `colorscheme` VARCHAR( 30 ) DEFAULT 'blue' NOT NULL";
   mysql_query($sql,$conn); 

   $sql = "ALTER TABLE `livehelp_users` ADD `layerinvite` INT( 10 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn); 
   
    $sql = "
    CREATE TABLE `livehelp_layerinvites` (
  `layerid` int(10) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `imagename` varchar(60) NOT NULL default '',
  `imagemap` text NULL,
  `department` varchar(60) NOT NULL default '',
  `user` int(10) NOT NULL default '0',
  PRIMARY KEY  (`layerid`)
   ) TYPE=MyISAM";
   mysql_query($sql,$conn); 

   $installationtype = "upgrade2104";
}

if($installationtype == "upgrade2104"){
	 
	 // auto invites are now in the database by id number but is is only 1 to 99:
   for($i=0;$i<26;$i++){
   	 $j= $i+100;
  	 $sql = "UPDATE livehelp_autoinvite SET message='$i' WHERE message='$j'";
	   mysql_query($sql,$conn);
 	 } 
	    
   $installationtype = "upgrade2105";
}

if($installationtype == "upgrade2105"){
	 
	 //turn off max execution timeout .. this might take 45 seconds or 
	 // more to do it the tables are big:
  @ini_set("max_execution_time",0);

	 // most of the data inside livehelp_visit_track is orphaned data
	 // and is the cause of most of the performance issues in 2.10.x
	 // this optimizes the tables after truncate:
	 $sql = "TRUNCATE livehelp_visit_track";
	 mysql_query($sql,$conn); 	    
	 $sql = "ALTER TABLE `livehelp_visit_track` CHANGE `location` `location` VARCHAR( 255 ) NOT NULL";
   mysql_query($sql,$conn);       
   $sql = "ALTER TABLE `livehelp_visit_track` CHANGE `id` `sessionid` VARCHAR( 40 ) DEFAULT '0' NOT NULL"; 
   mysql_query($sql,$conn);             
   $sql = "ALTER TABLE `livehelp_visit_track` ADD INDEX ( `location` )";
   mysql_query($sql,$conn);                
   $sql = "ALTER TABLE `livehelp_visit_track` ADD INDEX ( `whendone` )"; 
   mysql_query($sql,$conn);    
   $sql = "ALTER TABLE `livehelp_visit_track` CHANGE `referrer` `referrer` VARCHAR( 255 ) NOT NULL";
   mysql_query($sql,$conn);    
   
   $sql = "ALTER TABLE `livehelp_channels` ADD `sessionid` VARCHAR( 40 ) NOT NULL";
   mysql_query($sql,$conn);    
 
   $sql = "ALTER TABLE `livehelp_users` CHANGE `camefrom` `camefrom` VARCHAR( 255 ) NOT NULL";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_users` ADD `askquestions` CHAR( 1 ) DEFAULT 'Y' NOT NULL";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_users` ADD `showvisitors` CHAR( 1 ) DEFAULT 'N' NOT NULL";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_users` CHANGE `showedup` `showedup` BIGINT( 14 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn); 
 
   $sql = "ALTER TABLE `livehelp_operator_history` ADD INDEX ( `opid` )";
   mysql_query($sql,$conn);      
   $sql = "ALTER TABLE `livehelp_operator_history` ADD INDEX ( `dateof` )"; 
   mysql_query($sql,$conn);                  

	 $sql = "ALTER TABLE `livehelp_config` ADD `matchip` CHAR(1) DEFAULT 'N' NOT NULL";
   mysql_query($sql,$conn); 
	 $sql = "ALTER TABLE `livehelp_config` ADD `gethostnames` CHAR(1) DEFAULT 'N' NOT NULL";
   mysql_query($sql,$conn); 
	 $sql = "ALTER TABLE `livehelp_config` ADD `maxreferers` INT( 10 ) DEFAULT '50' NOT NULL";
   mysql_query($sql,$conn); 
	 $sql = "ALTER TABLE `livehelp_config` ADD `maxvisits` INT( 10 ) DEFAULT '100' NOT NULL";
   mysql_query($sql,$conn);  
	 $sql = "ALTER TABLE `livehelp_config` ADD `maxmonths` INT( 10 ) DEFAULT '12' NOT NULL";
   mysql_query($sql,$conn);    
	 $sql = "ALTER TABLE `livehelp_config` ADD `maxoldhits` INT( 10 ) NOT NULL";
   mysql_query($sql,$conn);  
	 $sql = "ALTER TABLE `livehelp_config` ADD `maxrecords` INT( 10 ) NOT NULL";
   mysql_query($sql,$conn);                
	 $sql = "ALTER TABLE `livehelp_config` ADD `showgames` CHAR(1) NOT NULL";
   mysql_query($sql,$conn);   
	 $sql = "ALTER TABLE `livehelp_config` ADD `showsearch` CHAR(1) NOT NULL";
   mysql_query($sql,$conn); 
	 $sql = "ALTER TABLE `livehelp_config` ADD `showdirectory` CHAR(1) NOT NULL";
   mysql_query($sql,$conn); 
 
   $sql = "ALTER TABLE `livehelp_referers` RENAME `livehelp_referers_daily`";
   mysql_query($sql,$conn);      
   $sql = "ALTER TABLE `livehelp_referers_daily` DROP INDEX `uniquevisits`";
   mysql_query($sql,$conn);    
   $sql = "ALTER TABLE `livehelp_referers_daily` DROP INDEX `camefrom`";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_daily` DROP INDEX `dayof`";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_daily` CHANGE `camefrom` `pageurl` VARCHAR( 255 ) DEFAULT '' NOT NULL";    
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_daily` CHANGE `dayof` `dateof` INT( 8 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);         
   $sql = "ALTER TABLE `livehelp_referers_daily` CHANGE `uniquevisits` `levelvisits` int(11) unsigned NOT NULL default '0'";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_daily` ADD `directvisits` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_daily` ADD `level` INT( 10 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_daily` ADD INDEX ( `pageurl` )";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_daily` ADD INDEX ( `parentrec` )";
   mysql_query($sql,$conn);      
   $sql = "ALTER TABLE `livehelp_referers_daily` ADD INDEX ( `levelvisits` )";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_referers_daily` ADD INDEX ( `directvisits` )";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_referers_daily` ADD INDEX ( `dateof` )";
   mysql_query($sql,$conn);       
   
                           
   $sql = "ALTER TABLE `livehelp_referers_total` RENAME `livehelp_referers_monthly`";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_referers_monthly` DROP INDEX `ctotal`";
   mysql_query($sql,$conn);    
   $sql = "ALTER TABLE `livehelp_referers_monthly` DROP INDEX `camefrom`";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_monthly` CHANGE `camefrom` `pageurl` VARCHAR( 255 ) DEFAULT '' NOT NULL";    
   mysql_query($sql,$conn);       
   $sql = "ALTER TABLE `livehelp_referers_monthly` CHANGE `ctotal` `levelvisits` int(11) unsigned NOT NULL default '0'";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_monthly` ADD `directvisits` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_monthly` ADD `level` INT( 10 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_referers_monthly` ADD INDEX ( `pageurl` )";
   mysql_query($sql,$conn);     
   $sql = "ALTER TABLE `livehelp_referers_monthly` ADD INDEX ( `levelvisits` )";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_referers_monthly` ADD INDEX ( `directvisits` )";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_referers_monthly` ADD INDEX ( `dateof` )";
   mysql_query($sql,$conn);     
   
   $sql = "ALTER TABLE `livehelp_visits` RENAME `livehelp_visits_daily`";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_visits_daily` DROP INDEX `uniquevisits`";
   mysql_query($sql,$conn);    
   $sql = "ALTER TABLE `livehelp_visits_daily` DROP INDEX `dayof`";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_visits_daily` CHANGE `dayof` `dateof` INT( 8 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);         
   $sql = "ALTER TABLE `livehelp_visits_daily` CHANGE `uniquevisits` `directvisits` int(11) unsigned NOT NULL default '0'";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_visits_daily` ADD `levelvisits` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_visits_daily` ADD `parentrec` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "UPDATE `livehelp_visits_daily` SET `levelvisits`=`directvisits`";
   mysql_query($sql,$conn);   
   $sql = "ALTER TABLE `livehelp_visits_daily` ADD `level` INT( 10 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_visits_daily` ADD INDEX ( `pageurl` )";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_visits_daily` ADD INDEX ( `parentrec` )";
   mysql_query($sql,$conn);      
   $sql = "ALTER TABLE `livehelp_visits_daily` ADD INDEX ( `levelvisits` )";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_visits_daily` ADD INDEX ( `directvisits` )";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_visits_daily` ADD INDEX ( `dateof` )";
   mysql_query($sql,$conn); 

   $sql = "ALTER TABLE `livehelp_visits_total` RENAME `livehelp_visits_monthly`";
   mysql_query($sql,$conn);          
   $sql = "ALTER TABLE `livehelp_visits_monthly` DROP INDEX `ctotal`";
   mysql_query($sql,$conn);          
   $sql = "ALTER TABLE `livehelp_visits_monthly` CHANGE `ctotal` `directvisits` int(11) unsigned NOT NULL default '0'";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_visits_monthly` ADD `levelvisits` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "UPDATE `livehelp_visits_monthly` SET `levelvisits`=`directvisits`";
   mysql_query($sql,$conn);  
   $sql = "ALTER TABLE `livehelp_visits_monthly` ADD `level` INT( 10 ) DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);
   $sql = "ALTER TABLE `livehelp_visits_monthly` ADD `parentrec` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL";
   mysql_query($sql,$conn);   
   $sql = "ALTER TABLE `livehelp_visits_monthly` ADD INDEX ( `parentrec` )";
   mysql_query($sql,$conn);      
   $sql = "ALTER TABLE `livehelp_visits_monthly` ADD INDEX ( `pageurl` )";
   mysql_query($sql,$conn);     
   $sql = "ALTER TABLE `livehelp_visits_monthly` ADD INDEX ( `levelvisits` )";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_visits_monthly` ADD INDEX ( `directvisits` )";
   mysql_query($sql,$conn); 
   $sql = "ALTER TABLE `livehelp_visits_monthly` ADD INDEX ( `dateof` )";
   mysql_query($sql,$conn);   
            
   $installationtype = "upgrade2111";
  }   
  
if( ($installationtype == "upgrade2110") || ($installationtype == "upgrade2111")){  
	
    $sql = "DROP TABLE IF EXISTS livehelp_referers";
    mysql_query($sql,$conn);		
 
    $sql = "DROP TABLE IF EXISTS livehelp_referers_total";
    mysql_query($sql,$conn);	

    $sql = "DROP TABLE IF EXISTS livehelp_visits";
    mysql_query($sql,$conn);
 
    $sql = "DROP TABLE IF EXISTS livehelp_visits_total";
    mysql_query($sql,$conn);
    
    $sql = "ALTER TABLE `livehelp_config` ADD `usertracking` CHAR( 1 ) DEFAULT 'N' NOT NULL ";
    mysql_query($sql,$conn);

    $sql = "ALTER TABLE `livehelp_config` ADD `resetbutton` CHAR( 1 ) DEFAULT 'N' NOT NULL ";
    mysql_query($sql,$conn);
    
    $sql = "ALTER TABLE `livehelp_users` ADD `cookieid` VARCHAR( 40 ) NOT NULL";
    mysql_query($sql,$conn);
    
    $sql = "CREATE TABLE `livehelp_identity_daily` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `isnamed` char(1) NOT NULL default 'N',
  `groupidentity` int(11) NOT NULL default '0',
  `groupusername` int(11) NOT NULL default '0',
  `identity` varchar(100) NOT NULL default '',
  `cookieid` varchar(40) NOT NULL default '',
  `ipaddress` varchar(30) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `dateof` bigint(14) NOT NULL default '0',
  `uservisits` int(10) NOT NULL default '0',
  `seconds` int(10) NOT NULL default '0',
  `useragent` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `isnamed` (`isnamed`),
  KEY `groupidentity` (`groupidentity`),
  KEY `groupusername` (`groupusername`),
  KEY `identity` (`identity`),
  KEY `cookieid` (`cookieid`),
  KEY `username` (`username`),
  KEY `dateof` (`dateof`)
) TYPE=MyISAM";
    mysql_query($sql,$conn);


    $sql = "CREATE TABLE `livehelp_identity_monthly` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `isnamed` char(1) NOT NULL default 'N',
  `groupidentity` int(11) NOT NULL default '0',
  `groupusername` int(11) NOT NULL default '0',
  `identity` varchar(100) NOT NULL default '',
  `cookieid` varchar(40) NOT NULL default '',
  `ipaddress` varchar(30) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `dateof` bigint(14) NOT NULL default '0',
  `uservisits` int(10) NOT NULL default '0',
  `seconds` int(10) NOT NULL default '0',
  `useragent` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `isnamed` (`isnamed`),
  KEY `groupidentity` (`groupidentity`),
  KEY `groupusername` (`groupusername`),
  KEY `identity` (`identity`),
  KEY `cookieid` (`cookieid`),
  KEY `username` (`username`),
  KEY `dateof` (`dateof`)
) TYPE=MyISAM";
    mysql_query($sql,$conn);
    

   $installationtype = "upgrade2112";
              	
   $sql = "UPDATE livehelp_config set showgames='Y',showsearch='Y',showdirectory='Y',maxreferers=50,maxvisits=75,maxmonths=12,maxoldhits=1,maxrecords=75000,chatmode='xmlhttp-flush-refresh',admin_refresh='auto',membernum='0',speaklanguage='English',version='$version'";
   mysql_query($sql,$conn);
   
   }

if( ($installationtype == "upgrade2112") || ($installationtype == "upgrade2113") || ($installationtype == "upgrade2114") || ($installationtype == "upgrade2115") || ($installationtype == "upgrade2116") ){
   $sql = "UPDATE livehelp_config set showgames='Y',showsearch='Y',showdirectory='Y',maxreferers=50,maxvisits=75,maxmonths=12,maxoldhits=1,maxrecords=75000,chatmode='xmlhttp-flush-refresh',admin_refresh='auto',version='$version'";
   mysql_query($sql,$conn);
   $installationtype = "upgrade2117";
 }
 
if($installationtype == "upgrade2117"){
	 
	  $sql = "ALTER TABLE `livehelp_departments` ADD `emailfun` CHAR( 1 ) DEFAULT 'Y' NOT NULL";
    mysql_query($sql,$conn);

	  $sql = "ALTER TABLE `livehelp_departments` ADD `dbfun` CHAR( 1 ) DEFAULT 'Y' NOT NULL";
    mysql_query($sql,$conn);    

	  $sql = "ALTER TABLE `livehelp_departments` ADD `everythingelse` text NULL";
    mysql_query($sql,$conn);   

	  $sql = "ALTER TABLE `livehelp_config` ADD `reftracking` CHAR( 1 ) DEFAULT 'N' NOT NULL";
    mysql_query($sql,$conn);   
 
 	  $sql = "ALTER TABLE `livehelp_config` ADD `keywordtrack` CHAR( 1 ) DEFAULT 'N' NOT NULL";
    mysql_query($sql,$conn); 

 	  $sql = "ALTER TABLE `livehelp_config` ADD `topkeywords` int(10) DEFAULT '50' NOT NULL";
    mysql_query($sql,$conn);     
 
 	  $sql = "ALTER TABLE `livehelp_config` ADD `rememberusers` CHAR( 1 ) DEFAULT 'Y' NOT NULL";
    mysql_query($sql,$conn);   
  
 	  $sql = "ALTER TABLE `livehelp_config` ADD `everythingelse` text NULL";
    mysql_query($sql,$conn);   
   
 	  $sql = "ALTER TABLE `livehelp_transcripts` CHANGE `daytime` `endtime` BIGINT( 14 ) DEFAULT NULL";
    mysql_query($sql,$conn);   
   
 	  $sql = "ALTER TABLE `livehelp_transcripts` ADD `starttime` BIGINT( 14 ) NOT NULL";
    mysql_query($sql,$conn);          
   
 	  $sql = "ALTER TABLE `livehelp_transcripts` ADD `duration` INT( 11 ) UNSIGNED NOT NULL";
    mysql_query($sql,$conn);     
   
 	  $sql = "ALTER TABLE `livehelp_transcripts` ADD `operators` VARCHAR( 255 ) NOT NULL";
    mysql_query($sql,$conn); 
   
 	  $sql = "UPDATE `livehelp_transcripts` SET starttime=endtime";
    mysql_query($sql,$conn); 
   
 	  $sql = "UPDATE `livehelp_transcripts` SET duration=0";
    mysql_query($sql,$conn); 
    
  	$sql = "
  CREATE TABLE `livehelp_leavemessage` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `subject` varchar(200) NOT NULL default '',
  `department` int(11) unsigned NOT NULL default '0',
  `dateof` bigint(14) NOT NULL default '0',
  `sessiondata` text NULL,
  `deliminated` text NULL,
  PRIMARY KEY  (`id`),
  KEY `department` (`department`)
) TYPE=MyISAM
  	";
    mysql_query($sql,$conn);   
 
   	$sql = "
CREATE TABLE `livehelp_keywords_daily` (
  `recno` int(11) NOT NULL auto_increment,
  `parentrec` int(11) unsigned NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
  `pageurl` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `dateof` int(8) NOT NULL default '0',
  `levelvisits` int(11) unsigned NOT NULL default '0',
  `directvisits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`recno`),
  KEY `levelvisits` (`levelvisits`),
  KEY `dateof` (`dateof`),
  KEY `referer` (`referer`)
) TYPE=MyISAM
  	";
    mysql_query($sql,$conn);  
 
 
    	$sql = "
CREATE TABLE `livehelp_keywords_monthly` (
  `recno` int(11) NOT NULL auto_increment,
  `parentrec` int(11) unsigned NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
  `pageurl` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `dateof` int(8) NOT NULL default '0',
  `levelvisits` int(11) unsigned NOT NULL default '0',
  `directvisits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`recno`),
  KEY `levelvisits` (`levelvisits`),
  KEY `dateof` (`dateof`),
  KEY `referer` (`referer`)
) TYPE=MyISAM
  	";
    mysql_query($sql,$conn);  
	
	 $installationtype = "upgrade2120";
} 


if( ($installationtype == "upgrade2120") || ($installationtype == "upgrade2121") ){
	 $sql = "ALTER TABLE `livehelp_visit_track` ADD INDEX ( `page` )";
   mysql_query($sql,$conn);	 
	
	 $sql = "UPDATE livehelp_config set version='$version'";
   mysql_query($sql,$conn);
   
   $installationtype = "upgrade2122";
}
 
if( ($installationtype == "upgrade2122") || ($installationtype == "upgrade2123") ){
 
	 $sql = "ALTER TABLE `livehelp_departments` ADD `ordering` INT( 8 ) NOT NULL";
   mysql_query($sql,$conn);	    
  
     $installationtype = "upgrade2124"; 
 } 

if($installationtype == "upgrade2124" ){
 
   
	 $sql = "UPDATE `livehelp_users` SET `showtype`='1'";
   mysql_query($sql,$conn);	    
	 
	 $sql = "ALTER TABLE `livehelp_users` CHANGE `showtype` `showtype` INT( 10 ) DEFAULT '1' NOT NULL ";
   mysql_query($sql,$conn);	

   $installationtype = "upgrade2125";
 } 

if( ($installationtype == "upgrade2125" ) || ($installationtype == "upgrade2126" ) || ($installationtype == "upgrade2127") ){           	 
 
   $sql = "CREATE TABLE `livehelp_paths_firsts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `visit_recno` int(11) unsigned NOT NULL default '0',
  `exit_recno` int(11) unsigned NOT NULL default '0',
  `dateof` int(8) NOT NULL default '0',
  `visits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `visit_recno` (`visit_recno`,`dateof`,`visits`)
) TYPE=MyISAM";
   mysql_query($sql,$conn);
   
  $sql = "CREATE TABLE `livehelp_paths_monthly` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `visit_recno` int(11) unsigned NOT NULL default '0',
  `exit_recno` int(11) unsigned NOT NULL default '0',
  `dateof` int(8) NOT NULL default '0',
  `visits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `visit_recno` (`visit_recno`,`dateof`,`visits`)
) TYPE=MyISAM AUTO_INCREMENT=1";
   mysql_query($sql,$conn);
   
  $sql = "ALTER TABLE `livehelp_departments` ADD `smiles` CHAR( 1 ) NOT NULL DEFAULT 'Y'";
  mysql_query($sql,$conn);  
 
  $sql = "ALTER TABLE livehelp_autoinvite ADD seconds int(11) unsigned NOT NULL default '0'";
  mysql_query($sql,$conn);     

  $installationtype = "upgrade2128";
  
  } 

 if($installationtype == "upgrade2128" ){
   	 
	 $sql = "ALTER TABLE `livehelp_departments` ADD `visible` INT( 1 ) DEFAULT '1' NOT NULL";
   mysql_query($sql,$conn);	   
   
   $installationtype = "upgrade2129";
 } 
 
 if( ($installationtype == "upgrade2129" ) || ($installationtype == "upgrade2130" ) || ($installationtype == "upgrade2131" ) || ($installationtype == "upgrade2140" ) ){
  
   $sql = "CREATE TABLE `livehelp_sessions` (
  `session_id` varchar(100) NOT NULL default '',
  `session_data` text NOT NULL,
  `expires` int(11) NOT NULL default '0',
   PRIMARY KEY  (`session_id`)
   ) TYPE=MyISAM;";
   mysql_query($sql,$conn);
   
   $sql = "UPDATE livehelp_config set maxexe=180,refreshrate=1,chatmode='xmlhttp-flush-refresh',version='$version'";
   mysql_query($sql,$conn);
   
   $installationtype = "upgrade2141"; 
 }    
 
  if($installationtype == "upgrade2141" ){
   	 
	 $sql = "ALTER TABLE `livehelp_config` ADD `smtp_host` VARCHAR( 255 ) NOT NULL default ''";
   mysql_query($sql,$conn);	
	 $sql = "ALTER TABLE `livehelp_config` ADD `smtp_username` VARCHAR( 60 ) NOT NULL default ''";
   mysql_query($sql,$conn);	
	 $sql = "ALTER TABLE `livehelp_config` ADD `smtp_password` VARCHAR( 60 ) NOT NULL default ''";
   mysql_query($sql,$conn);	
	 $sql = "ALTER TABLE `livehelp_config` ADD `owner_email` VARCHAR( 255 ) NOT NULL default ''";
   mysql_query($sql,$conn);	      
 
   $installationtype = "upgrade2142";    
 }

  if( ($installationtype == "upgrade2142") || ($installationtype == "upgrade2143" ) ){

	 $sql = "ALTER TABLE `livehelp_config` ADD `smtp_portnum` int( 10 ) NOT NULL default '25'";
   mysql_query($sql,$conn);	    

   $installationtype = "upgrade2144";    
 }

  if( ($installationtype == "upgrade2144") || ($installationtype == "upgrade2145") || ($installationtype == "upgrade2146") ){
       	  
   $sql = "UPDATE livehelp_config set everythingelse='YY',version='$version'";
   mysql_query($sql,$conn);
 
 }
  
}

$installationtype = $UNTRUSTED['installationtype'];
if($installationtype == "newinstall"){

  if( ($UNTRUSTED['dbtype_setup'] == "txt-db-api") && ($errors == "")){
    $lastchar = substr($UNTRUSTED['rootpath'],-1);  
    if($lastchar != "/"){ $UNTRUSTED['rootpath'] .= "/"; }
    $lastchar = substr($UNTRUSTED['txtpath'],-1);  
    if($lastchar != "/"){ $UNTRUSTED['txtpath'] .= "/"; }
    $DB_DIR = $UNTRUSTED['txtpath']; 
    $API_HOME_DIR = $UNTRUSTED['rootpath'] . "txt-db-api/"; 
    define("API_HOME_DIR" ,$API_HOME_DIR);
    define("DB_DIR" ,$DB_DIR);
    define('DB_FETCHMODE_ORDERED', 1);
    define('DB_FETCHMODE_ASSOC', 2);
    include_once("$API_HOME_DIR/resultset.php");
    include_once("$API_HOME_DIR/database.php");
    $mydatabase = new Database("livehelp");	
    $mydatabase->query($insert_query);
    $mydatabase->query($insert_query2);
    $mydatabase->query($insert_query3);
    $mydatabase->query($insert_query4);
    $scratch = "
                    Welcome to Crafty Syntax Live Help 
  
  All the administrative functions are located to the left of this text. 
 
 You can use this section to keep notes for yourself and other admins, etc. 
 
To change the text that is located in this box just click on the small edit 
button on the top right corner of this box. 
 
        ";
        $sql = "UPDATE livehelp_config set scratch_space='$scratch',everythingelse='YY'";
        $mydatabase->query($sql);


$mydatabase->query("INSERT INTO livehelp_modules (id,name,path) VALUES (1, 'Live Help!', 'livehelp.php')");
$mydatabase->query("INSERT INTO livehelp_modules (id,name,path) VALUES (2, 'Contact', 'leavemessage.php')");
$mydatabase->query("INSERT INTO livehelp_modules (id,name,path,adminpath) VALUES (3, 'Q & A', 'user_qa.php','qa.php')");
$mydatabase->query("INSERT INTO livehelp_modules_dep VALUES (1, 1, 1, 1, '')");
$mydatabase->query("INSERT INTO livehelp_modules_dep VALUES (2, 1, 2, 2, 'Y')");

		$sql = "INSERT INTO livehelp_autoinvite VALUES (1, 'Y', 0, '1', '', 0, '', 'layer','60')";
$mydatabase->query($sql);

		$sql = "INSERT INTO livehelp_questions VALUES (1, 1, 0, 'E-mail:', 'email', '', '', 'leavemessage', 'Y')";
    $mydatabase->query($sql);
		$sql = "INSERT INTO livehelp_questions VALUES (2, 1, 0, 'Question:', 'textarea', '', '', 'leavemessage', 'N')";
    $mydatabase->query($sql);
		$sql = "INSERT INTO livehelp_questions VALUES (3, 1, 0, 'Name', 'username', '', '', 'livehelp', 'N')";
    $mydatabase->query($sql);
	//	$sql = "INSERT INTO livehelp_questions VALUES (4, 1, 1, 'E-mail', 'email', '', '', 'livehelp', 'N')";
  //  $mydatabase->query($sql);
    $sql = "INSERT INTO livehelp_questions VALUES (5, 1, 1, 'Question', 'textarea', '', '', 'livehelp', 'N')";
    $mydatabase->query($sql);
    $sql = "UPDATE livehelp_departments set leavetxt='<h3><SPAN CLASS=wh>LEAVE A MESSAGE:</SPAN></h3>Please type in your comments/questions in the box below <br> and provide an e-mail address so we can get back to you'";
    $mydatabase->query($sql);		 
    $sql = "UPDATE livehelp_departments set topframeheight='45',topbackground='topclouds.gif',colorscheme='blue'";
   $mydatabase->query($sql);  

} // END: $UNTRUSTED['dbtype_setup'] == "txt-db-api") && ($errors == "")

if ($UNTRUSTED['dbtype_setup'] == "mysql"){
  if($errors == ""){


   $sql = "DROP TABLE IF EXISTS livehelp_paths_firsts";
   mysql_query($sql,$conn); 
   $sql = "CREATE TABLE `livehelp_paths_firsts` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `visit_recno` int(11) unsigned NOT NULL default '0',
  `exit_recno` int(11) unsigned NOT NULL default '0',
  `dateof` int(8) NOT NULL default '0',
  `visits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `visit_recno` (`visit_recno`,`dateof`,`visits`)
) TYPE=MyISAM";
   mysql_query($sql,$conn);


   $sql = "DROP TABLE IF EXISTS livehelp_paths_monthly";
   mysql_query($sql,$conn);    
  $sql = "CREATE TABLE `livehelp_paths_monthly` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `visit_recno` int(11) unsigned NOT NULL default '0',
  `exit_recno` int(11) unsigned NOT NULL default '0',
  `dateof` int(8) NOT NULL default '0',
  `visits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `visit_recno` (`visit_recno`,`dateof`,`visits`)
) TYPE=MyISAM";
   mysql_query($sql,$conn);

   $sql = "DROP TABLE IF EXISTS livehelp_sessions";
   mysql_query($sql,$conn); 
   $sql = "CREATE TABLE `livehelp_sessions` (
  `session_id` varchar(100) NOT NULL default '',
  `session_data` text NOT NULL,
  `expires` int(11) NOT NULL default '0',
   PRIMARY KEY  (`session_id`)
   ) TYPE=MyISAM;";
   mysql_query($sql,$conn);
         
   $sql = "DROP TABLE IF EXISTS livehelp_leavemessage";
   mysql_query($sql,$conn);  
 	$sql = "
  CREATE TABLE `livehelp_leavemessage` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `subject` varchar(200) NOT NULL default '',
  `department` int(11) unsigned NOT NULL default '0',
  `dateof` bigint(14) NOT NULL default '0',
  `sessiondata` text NULL,
  `deliminated` text NULL,  
  PRIMARY KEY  (`id`),
  KEY `department` (`department`)
) TYPE=MyISAM
  	";
    mysql_query($sql,$conn);   

   $sql = "DROP TABLE IF EXISTS livehelp_keywords_daily";
   mysql_query($sql,$conn);   
   	$sql = "
CREATE TABLE `livehelp_keywords_daily` (
  `recno` int(11) NOT NULL auto_increment,
  `parentrec` int(11) unsigned NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
  `pageurl` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `dateof` int(8) NOT NULL default '0',
  `levelvisits` int(11) unsigned NOT NULL default '0',
  `directvisits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`recno`),
  KEY `levelvisits` (`levelvisits`),
  KEY `dateof` (`dateof`),
  KEY `referer` (`referer`)
) TYPE=MyISAM
  	";
    mysql_query($sql,$conn);  
 
    $sql = "DROP TABLE IF EXISTS livehelp_keywords_monthly";
   mysql_query($sql,$conn); 
    	$sql = "
CREATE TABLE `livehelp_keywords_monthly` (
  `recno` int(11) NOT NULL auto_increment,
  `parentrec` int(11) unsigned NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
  `pageurl` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `dateof` int(8) NOT NULL default '0',
  `levelvisits` int(11) unsigned NOT NULL default '0',
  `directvisits` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`recno`),
  KEY `levelvisits` (`levelvisits`),
  KEY `dateof` (`dateof`),
  KEY `referer` (`referer`)
) TYPE=MyISAM
  	";
    mysql_query($sql,$conn);  
 
  
   $sql = "DROP TABLE IF EXISTS livehelp_identity_daily";
   mysql_query($sql,$conn);  
    $sql = "CREATE TABLE `livehelp_identity_daily` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `isnamed` char(1) NOT NULL default 'N',
  `groupidentity` int(11) NOT NULL default '0',
  `groupusername` int(11) NOT NULL default '0',
  `identity` varchar(100) NOT NULL default '',
  `cookieid` varchar(40) NOT NULL default '',
  `ipaddress` varchar(30) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `dateof` bigint(14) NOT NULL default '0',
  `uservisits` int(10) NOT NULL default '0',
  `seconds` int(10) NOT NULL default '0',
  `useragent` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `isnamed` (`isnamed`),
  KEY `groupidentity` (`groupidentity`),
  KEY `groupusername` (`groupusername`),
  KEY `identity` (`identity`),
  KEY `cookieid` (`cookieid`),
  KEY `username` (`username`),
  KEY `dateof` (`dateof`)
) TYPE=MyISAM";
    mysql_query($sql,$conn);

   $sql = "DROP TABLE IF EXISTS livehelp_identity_monthly";
   mysql_query($sql,$conn);  
    $sql = "CREATE TABLE `livehelp_identity_monthly` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `isnamed` char(1) NOT NULL default 'N',
  `groupidentity` int(11) NOT NULL default '0',
  `groupusername` int(11) NOT NULL default '0',
  `identity` varchar(100) NOT NULL default '',
  `cookieid` varchar(40) NOT NULL default '',
  `ipaddress` varchar(30) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `dateof` bigint(14) NOT NULL default '0',
  `uservisits` int(10) NOT NULL default '0',
  `seconds` int(10) NOT NULL default '0',
  `useragent` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `isnamed` (`isnamed`),
  KEY `groupidentity` (`groupidentity`),
  KEY `groupusername` (`groupusername`),
  KEY `identity` (`identity`),
  KEY `cookieid` (`cookieid`),
  KEY `username` (`username`),
  KEY `dateof` (`dateof`)
) TYPE=MyISAM";
    mysql_query($sql,$conn);

   $sql = "DROP TABLE IF EXISTS livehelp_referers_monthly";
   mysql_query($sql,$conn);   
  $sql = "
   CREATE TABLE `livehelp_referers_monthly` (
  `recno` int(11) NOT NULL auto_increment,
  `pageurl` varchar(255) NOT NULL default '0',
  `dateof` int(8) NOT NULL default '0',
  `levelvisits` int(11) unsigned NOT NULL default '0',
  `directvisits` int(11) unsigned NOT NULL default '0',
  `parentrec` int(11) unsigned NOT NULL default '0',
  `level` int(10) NOT NULL default '0',
  PRIMARY KEY  (`recno`),
  KEY `pageurl` (`pageurl`),
  KEY `parentrec` (`parentrec`),
  KEY `levelvisits` (`levelvisits`),
  KEY `directvisits` (`directvisits`),
  KEY `dateof` (`dateof`)
) TYPE=MyISAM";
   mysql_query($sql,$conn); 

   $sql = "DROP TABLE IF EXISTS livehelp_referers_daily";
   mysql_query($sql,$conn);   
  $sql = "
  CREATE TABLE `livehelp_referers_daily` (
  `recno` int(11) NOT NULL auto_increment,
  `pageurl` varchar(255) NOT NULL default '0',
  `dateof` int(8) NOT NULL default '0',
  `levelvisits` int(11) unsigned NOT NULL default '0',
  `directvisits` int(11) unsigned NOT NULL default '0',
  `parentrec` int(11) unsigned NOT NULL default '0',
  `level` int(10) NOT NULL default '0',
  PRIMARY KEY  (`recno`),
  KEY `pageurl` (`pageurl`),
  KEY `parentrec` (`parentrec`),
  KEY `levelvisits` (`levelvisits`),
  KEY `directvisits` (`directvisits`),
  KEY `dateof` (`dateof`)
) TYPE=MyISAM";
   mysql_query($sql,$conn); 

   $sql = "DROP TABLE IF EXISTS livehelp_visits_monthly";
   mysql_query($sql,$conn);
  $sql = "
   CREATE TABLE `livehelp_visits_monthly` (
  `recno` int(11) NOT NULL auto_increment,
  `pageurl` varchar(255) NOT NULL default '0',
  `dateof` int(8) NOT NULL default '0',
  `levelvisits` int(11) unsigned NOT NULL default '0',
  `directvisits` int(11) unsigned NOT NULL default '0',
  `parentrec` int(11) unsigned NOT NULL default '0',
  `level` int(10) NOT NULL default '0',
  PRIMARY KEY  (`recno`),
  KEY `pageurl` (`pageurl`),
  KEY `parentrec` (`parentrec`),
  KEY `levelvisits` (`levelvisits`),
  KEY `directvisits` (`directvisits`),
  KEY `dateof` (`dateof`)
) TYPE=MyISAM";
   mysql_query($sql,$conn); 

   $sql = "DROP TABLE IF EXISTS livehelp_visits_daily";
   mysql_query($sql,$conn);   
  $sql = "
  CREATE TABLE `livehelp_visits_daily` (
  `recno` int(11) NOT NULL auto_increment,
  `pageurl` varchar(255) NOT NULL default '0',
  `dateof` int(8) NOT NULL default '0',
  `levelvisits` int(11) unsigned NOT NULL default '0',
  `directvisits` int(11) unsigned NOT NULL default '0',
  `parentrec` int(11) unsigned NOT NULL default '0',
  `level` int(10) NOT NULL default '0',
  PRIMARY KEY  (`recno`),
  KEY `pageurl` (`pageurl`),
  KEY `parentrec` (`parentrec`),
  KEY `levelvisits` (`levelvisits`),
  KEY `directvisits` (`directvisits`),
  KEY `dateof` (`dateof`)
) TYPE=MyISAM";
   mysql_query($sql,$conn); 
   
   $sql = "DROP TABLE IF EXISTS livehelp_layerinvites";
   mysql_query($sql,$conn);
   $sql = "
    CREATE TABLE `livehelp_layerinvites` (
  `layerid` int(10) NOT NULL default '0',
  `name` varchar(60) NOT NULL default '',
  `imagename` varchar(60) NOT NULL default '',
  `imagemap` text NULL,
  `department` varchar(60) NOT NULL default '',
  `user` int(10) NOT NULL default '0',
  PRIMARY KEY  (`layerid`)
   ) TYPE=MyISAM";
   mysql_query($sql,$conn); 
      
  $sql = "DROP TABLE IF EXISTS livehelp_operator_history";
  mysql_query($sql,$conn);
    $sql = "
CREATE TABLE `livehelp_operator_history` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `opid` int(11) unsigned NOT NULL default '0',
  `action` varchar(60) NOT NULL default '',
  `dateof` bigint(14) NOT NULL default '0',
  `sessionid` varchar(40) NOT NULL default '',
  `transcriptid` int(10) NOT NULL default '0',
  `totaltime` int(10) NOT NULL default '0',
  `channel` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  INDEX ( `opid` ), 
  INDEX ( `dateof` )         
) TYPE=MyISAM AUTO_INCREMENT=1
    ";
    mysql_query($sql,$conn);
    
  $sql = "DROP TABLE IF EXISTS livehelp_questions";
  mysql_query($sql,$conn);
    $sql = "
         CREATE TABLE `livehelp_questions` (
      `id` INT( 10 ) NOT NULL AUTO_INCREMENT,
      `department` INT( 10 ) NOT NULL default '0',
      `ordering` INT( 8 ) NOT NULL default '0',
      `headertext` TEXT NULL,
      `fieldtype` VARCHAR( 30 ) NOT NULL default '',
      `options` TEXT NULL,
      `flags` VARCHAR( 60 ) NOT NULL default '',
      `module` VARCHAR( 60 ) NOT NULL default '',
      `required` CHAR( 1 ) DEFAULT 'N' NOT NULL,
      PRIMARY KEY ( `id` ) ,
      INDEX ( `department` ) 
      )TYPE=MyISAM

    ";
    mysql_query($sql,$conn);
    
  $sql = "DROP TABLE IF EXISTS livehelp_smilies";
  mysql_query($sql,$conn);
    $sql = 
       "CREATE TABLE `livehelp_smilies` (
        `smilies_id` smallint(5) unsigned NOT NULL auto_increment,
        `code` varchar(50) default NULL,
        `smile_url` varchar(100) default NULL,
        `emoticon` varchar(75) default NULL,
        PRIMARY KEY  (`smilies_id`)
       )TYPE=MyISAM
       ";
 		   $results = mysql_query($sql,$conn);
 		$sql = "INSERT INTO `livehelp_smilies` VALUES (1, ':D', 'icon_biggrin.gif', 'Very Happy')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (2, ':-D', 'icon_biggrin.gif', 'Very Happy')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (3, ':grin:', 'icon_biggrin.gif', 'Very Happy')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (4, ':)', 'icon_smile.gif', 'Smile')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (5, ':-)', 'icon_smile.gif', 'Smile')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (6, ':smile:', 'icon_smile.gif', 'Smile')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (7, ':(', 'icon_sad.gif', 'Sad')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (8, ':-(', 'icon_sad.gif', 'Sad')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (9, ':sad:', 'icon_sad.gif', 'Sad')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (10, ':o', 'icon_surprised.gif', 'Surprised')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (11, ':-o', 'icon_surprised.gif', 'Surprised')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (12, ':eek:', 'icon_surprised.gif', 'Surprised')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (13, ':shock:', 'icon_eek.gif', 'Shocked')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (14, ':?', 'icon_confused.gif', 'Confused')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (15, ':-?', 'icon_confused.gif', 'Confused')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (16, ':???:', 'icon_confused.gif', 'Confused')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (17, '8)', 'icon_cool.gif', 'Cool')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (18, '8-)', 'icon_cool.gif', 'Cool')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (19, ':cool:', 'icon_cool.gif', 'Cool')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (20, ':lol:', 'icon_lol.gif', 'Laughing')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (21, ':x', 'icon_mad.gif', 'Mad')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (22, ':-x', 'icon_mad.gif', 'Mad')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (23, ':mad:', 'icon_mad.gif', 'Mad')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (24, ':P', 'icon_razz.gif', 'Razz')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (25, ':-P', 'icon_razz.gif', 'Razz')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (26, ':razz:', 'icon_razz.gif', 'Razz')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (27, ':oops:', 'icon_redface.gif', 'Embarassed')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (28, ':cry:', 'icon_cry.gif', 'Crying or Very sad')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (32, ':wink:', 'icon_wink.gif', 'Wink')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (33, ';)', 'icon_wink.gif', 'Wink')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (34, ';-)', 'icon_wink.gif', 'Wink')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (35, ':!:', 'icon_exclaim.gif', 'Exclamation')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (36, ':?:', 'icon_question.gif', 'Question')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (37, ':idea:', 'icon_idea.gif', 'Idea')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (38, ':arrow:', 'icon_arrow.gif', 'Arrow')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (39, ':|', 'icon_neutral.gif', 'Neutral')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (40, ':-|', 'icon_neutral.gif', 'Neutral')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (41, ':neutral:', 'icon_neutral.gif', 'Neutral')";
 		$results = mysql_query($sql,$conn);
    $sql = "INSERT INTO  `livehelp_smilies` VALUES (42, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green')";
 		$results = mysql_query($sql,$conn);
     mysql_query($sql,$conn);
     
     
    $sql = "DROP TABLE IF EXISTS livehelp_autoinvite";
    mysql_query($sql,$conn);
    $sql = 
    "CREATE TABLE `livehelp_autoinvite` (
  `idnum` int(10) NOT NULL auto_increment,
   isactive char(1) NOT NULL default '',  
  `department` int(10) NOT NULL default '0',
  `message` text NULL,
  `page` varchar(255) NOT NULL default '',
  `visits` int(8) NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
  `typeof` varchar(255) NOT NULL default '',
  `seconds` int(11) unsigned NOT NULL default '0',  
   PRIMARY KEY  (idnum)
)TYPE=MyISAM ";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

$sql = "DROP TABLE IF EXISTS livehelp_modules_dep";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE livehelp_modules_dep (
  rec int(10) NOT NULL auto_increment,
  departmentid int(10) NOT NULL default '0',
  modid int(10) NOT NULL default '0',
  ordernum int(8) NOT NULL default '0',
  defaultset char(1) NOT NULL default '',
  PRIMARY KEY  (rec)
)TYPE=MyISAM 
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

$sql = "DROP TABLE IF EXISTS livehelp_modules";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE livehelp_modules (
  id int(10) NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  path varchar(255) NOT NULL default '',
  adminpath varchar(255) NOT NULL default '',
  query_string varchar(255) NOT NULL default '',  
  PRIMARY KEY  (id)
)TYPE=MyISAM  
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path) VALUES (1, 'Live Help!', 'livehelp.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path) VALUES (2, 'Contact', 'leavemessage.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules` (id,name,path,adminpath) VALUES (3, 'Q & A', 'user_qa.php','qa.php')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules_dep` VALUES (1, 1, 1, 1, '')",$conn);
$results = mysql_query("INSERT INTO `livehelp_modules_dep` VALUES (2, 1, 2, 2, 'Y')",$conn);
		
	
$sql = "DROP TABLE IF EXISTS livehelp_channels";
mysql_query($sql,$conn);
$sql = 
"CREATE TABLE livehelp_channels (
  id int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  statusof char(1) NOT NULL default '',
  startdate bigint(8) NOT NULL default '0',
  `sessionid` VARCHAR( 40 ) NOT NULL default '',
  PRIMARY KEY  (id)
)TYPE=MyISAM
";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
		
$sql = "DROP TABLE IF EXISTS livehelp_config";
mysql_query($sql,$conn);  
  $sql  =  
"CREATE TABLE `livehelp_config` (
  `version` varchar(10) NOT NULL default '2.14.6',
  `site_title` varchar(100) NOT NULL default '',
  `use_flush` varchar(10) NOT NULL default 'YES',
  `membernum` int(8) NOT NULL default '0',
  `offset` int(5) NOT NULL default '0',
  `show_typing` char(1) NOT NULL default '',
  `webpath` varchar(255) NOT NULL default '',
  `s_webpath` varchar(255) NOT NULL default '',
  `speaklanguage` varchar(60) NOT NULL default 'English',
  `scratch_space` text NULL,
  `admin_refresh` varchar(30) NOT NULL default 'auto',
  `maxexe` int(5) default '180',
  `refreshrate` int(5) NOT NULL default '1',
  `chatmode` varchar(60) NOT NULL default 'xmlhttp-flush-refresh',
  `adminsession` char(1) NOT NULL default 'Y',
  `ignoreips` text NULL,
  `directoryid` varchar(32) NOT NULL default '',
  `tracking` char(1) NOT NULL default 'N',
  `colorscheme` varchar(30) NOT NULL default 'blue',
  `matchip` char(1) NOT NULL default 'N',
  `gethostnames` char(1) NOT NULL default 'N',
  `maxrecords` int(10) NOT NULL default '75000',
  `maxreferers` int(10) NOT NULL default '50',
  `maxvisits` int(10) NOT NULL default '75',
  `maxmonths` int(10) NOT NULL default '12',
  `maxoldhits` int(10) NOT NULL default '1',
  `showgames` char(1) NOT NULL default 'Y',
  `showsearch` char(1) NOT NULL default 'Y',
  `showdirectory` char(1) NOT NULL default 'Y',
  `usertracking` char(1) NOT NULL default 'N',
  `resetbutton` char(1) NOT NULL default 'N',
  `keywordtrack` char(1) NOT NULL default 'N',
  `reftracking` char(1) NOT NULL default 'N',
  `topkeywords` int(10) NOT NULL default '50',
  `everythingelse` text NULL,
  `rememberusers` char(1) NOT NULL default 'Y',
  `smtp_host` VARCHAR( 255 ) NOT NULL default '',
  `smtp_username` VARCHAR( 60 ) NOT NULL default '',
  `smtp_password` VARCHAR( 60 ) NOT NULL default '',
  `owner_email` VARCHAR( 255 ) NOT NULL default '',
  `smtp_portnum` int( 10 ) NOT NULL default '25',  
  PRIMARY KEY  (`version`)
) TYPE=MyISAM";
 
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
 		$results = mysql_query($insert_query,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
$sql = "DROP TABLE IF EXISTS livehelp_departments";
mysql_query($sql,$conn); 
$sql  =  "		
CREATE TABLE `livehelp_departments` (
  `recno` int(5) NOT NULL auto_increment,
  `nameof` varchar(30) NOT NULL default '',
  `onlineimage` varchar(255) NOT NULL default '',
  `offlineimage` varchar(255) NOT NULL default '',
  `layerinvite` varchar(255) NOT NULL default '',
  `requirename` char(1) NOT NULL default '',
  `messageemail` varchar(60) NOT NULL default '',
  `leaveamessage` varchar(10) NOT NULL default '',
  `opening` text NULL,
  `offline` text NULL,
  `creditline` char(1) NOT NULL default 'L',
  `imagemap` text NULL,
  `whilewait` text NULL,
  `timeout` int(5) NOT NULL default '150',
  `leavetxt` text NULL,
  `topframeheight` int(10) NOT NULL default '35',
  `topbackground` varchar(255) NOT NULL default '',
  `colorscheme` varchar(255) NOT NULL default '',
  `speaklanguage` varchar(60) NOT NULL default '',
  `busymess` text NULL,
  `emailfun` char(1) NOT NULL default 'Y',
  `dbfun` char(1) NOT NULL default 'Y',
  `everythingelse` text NULL,
  `ordering` INT( 8 ) NOT NULL default '0',
  `smiles` CHAR( 1 ) NOT NULL DEFAULT 'Y',
  `visible` INT( 1 ) DEFAULT '1' NOT NULL,   
  PRIMARY KEY  (`recno`)
) TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
  $sql = "DROP TABLE IF EXISTS livehelp_messages";
mysql_query($sql,$conn);   
  $sql  =  "	
CREATE TABLE livehelp_messages (
  id_num int(10) NOT NULL auto_increment,
  message text NULL,
  channel int(10) NOT NULL default '0',
  timeof bigint(14) NOT NULL default '0',
  saidfrom int(10) NOT NULL default '0',
  saidto int(10) NOT NULL default '0',
  typeof varchar(30) NOT NULL default '',
  PRIMARY KEY  (id_num),
  KEY channel (channel),
  KEY timeof (timeof)
)TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
  $sql = "DROP TABLE IF EXISTS livehelp_operator_channels";
mysql_query($sql,$conn);  
$sql  =  "	
CREATE TABLE livehelp_operator_channels (
  id int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  channel int(10) NOT NULL default '0',
  userid int(10) NOT NULL default '0',
  statusof char(1) NOT NULL default '',
  startdate bigint(8) NOT NULL default '0',
  bgcolor varchar(10) NOT NULL default '000000',
  txtcolor varchar(10) NOT NULL default '000000',
  channelcolor varchar(10) NOT NULL default 'F7FAFF',  
  txtcolor_alt varchar(10) NOT NULL default '000000', 
  PRIMARY KEY  (id),
  KEY channel (channel),
  KEY user_id (user_id)
)TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}	 
  $sql = "DROP TABLE IF EXISTS livehelp_operator_departments";
mysql_query($sql,$conn);  
$sql  =  "	
CREATE TABLE livehelp_operator_departments (
  recno int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  department int(10) NOT NULL default '0',
  extra varchar(100) NOT NULL default '',
  PRIMARY KEY  (recno),
  KEY user_id (user_id),
  KEY department (department)
)TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
  $sql = "DROP TABLE IF EXISTS livehelp_qa";
mysql_query($sql,$conn);  
$sql  =  "	
CREATE TABLE livehelp_qa (
  recno int(10) NOT NULL auto_increment,
  parent int(10) NOT NULL default '0',
  question text NULL,
  typeof varchar(10) NOT NULL default '',
  status VARCHAR(20) NOT NULL default '',
  username varchar(60) NOT NULL default '',
  ordernum int(10) NOT NULL default '0',   
  PRIMARY KEY  (recno)
)TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

  $sql = "DROP TABLE IF EXISTS livehelp_quick";
mysql_query($sql,$conn); 		
  $sql  =  "	
CREATE TABLE livehelp_quick (
  id int(10) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  typeof varchar(30) NOT NULL default '',
  message text NULL,
  visiblity varchar(20) NOT NULL default '',
  department varchar(60) NOT NULL default '0',
  user int(10) NOT NULL default '0',
  ishtml varchar(3) NOT NULL default '',
  PRIMARY KEY  (id)
)TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}
	
 
$sql = "DROP TABLE IF EXISTS livehelp_transcripts";
mysql_query($sql,$conn); 	
  $sql  =  "	
CREATE TABLE `livehelp_transcripts` (
  `recno` int(10) NOT NULL auto_increment,
  `who` varchar(100) NOT NULL default '',
  `endtime` bigint(14) default NULL,
  `transcript` text NULL,
  `sessionid` varchar(40) NOT NULL default '',
  `sessiondata` text NULL,
  `department` int(10) NOT NULL default '0',
  `email` varchar(100) NOT NULL default '',
  `starttime` bigint(14) NOT NULL default '0',
  `duration` int(11) unsigned NOT NULL default '0',
  `operators` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`recno`)
) TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}

$sql = "DROP TABLE IF EXISTS livehelp_users";
mysql_query($sql,$conn); 			
  $sql  =  "	
CREATE TABLE livehelp_users (
  user_id int(10) NOT NULL auto_increment,
  lastaction BIGINT(14) DEFAULT '0',
  username varchar(30) NOT NULL default '',
  password varchar(60) NOT NULL default '',
  isonline char(1) NOT NULL default '',
  isoperator char(1) NOT NULL default 'N',
  onchannel int(10) NOT NULL default '0',
  isadmin char(1) NOT NULL default 'N',
  department int(5) NOT NULL default '0',
  identity varchar(255) NOT NULL default '',
  status varchar(30) NOT NULL default '',
  isnamed char(1) NOT NULL default 'N',
  showedup bigint(14) default NULL,
  email varchar(60) NOT NULL default '',
  camefrom varchar(255) NOT NULL default '',
  show_arrival char(1) NOT NULL default 'N',
  user_alert char(1) NOT NULL default '',
  auto_invite CHAR( 1 ) NOT NULL default 'N',
  istyping  CHAR( 1 ) NOT NULL default '1',
  visits int(8) NOT NULL default '0',
  jsrn int(5) NOT NULL default '0',
  hostname varchar(255) NOT NULL default '',
  useragent varchar(255) NOT NULL default '',
  ipaddress varchar(255) NOT NULL default '',  
  `sessionid` varchar(40) NOT NULL default '',
  `authenticated` char(1) NOT NULL default '',
  `cookied` CHAR( 1 ) DEFAULT 'N' NOT NULL,
  sessiondata text NULL,
  expires bigint(14) NOT NULL default '0',
  `greeting` text NULL,
  photo varchar(255) NOT NULL default '',
  chataction BIGINT(14) DEFAULT '0',
  `new_session` CHAR( 1 ) NOT NULL DEFAULT 'Y',
  `showtype` INT( 10 ) NOT NULL DEFAULT '1',
  `chattype` CHAR( 1 ) NOT NULL DEFAULT 'Y',
  `externalchats` VARCHAR( 255 ) NOT NULL DEFAULT '',  
  `layerinvite` INT( 10 ) DEFAULT '0' NOT NULL,
  `askquestions` CHAR( 1 ) DEFAULT 'Y' NOT NULL,
  `showvisitors` CHAR( 1 ) DEFAULT 'N' NOT NULL,
  `cookieid` VARCHAR( 40 ) NOT NULL default '',
  PRIMARY KEY  (user_id)
)TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}		
$sql = "DROP TABLE IF EXISTS livehelp_visit_track";
mysql_query($sql,$conn); 	
  $sql  =  "	
CREATE TABLE livehelp_visit_track (
  recno int(10) NOT NULL auto_increment,
  sessionid varchar(40) NOT NULL default '0',
  location varchar(255) NOT NULL default '',
  page bigint(14) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  whendone BIGINT( 14 ) NOT NULL default '0',
  referrer varchar(255) NOT NULL default '',
  PRIMARY KEY  (recno),
  KEY sessionid (sessionid),
  KEY `location` (`location`),
  KEY `page` (`page`),
  KEY `whendone` (`whendone`) 
)TYPE=MyISAM";
 		$results = mysql_query($sql,$conn);
		if(!$results) 
		{
			echo "<H2>Query went bad!</H2>\n";
			echo mysql_errno().":  ".mysql_error()."<P>";
			$wentwrong = true;
		}		
 
        $scratch = "
 Welcome to Crafty Syntax Live Help 

 All the administrative functions are located to the left of this text. 
 
You can use this section to keep notes for yourself and other admins, etc. 
 
To change the text that is located in this box just click on the small edit 
button on the top right corner of this box. 
        ";
        $sql = "UPDATE livehelp_config set scratch_space='$scratch'";
        mysql_query($sql,$conn);

// default stuff...  				
 		$results = mysql_query($insert_query2,$conn);
 		$results = mysql_query($insert_query3,$conn);
		$results = mysql_query($insert_query4,$conn);
		$sql = "INSERT INTO `livehelp_autoinvite` VALUES (1, 'Y', 0, '5', '', 0, '', 'layer','60')";
    mysql_query($sql,$conn);

		$sql = "INSERT INTO `livehelp_questions` VALUES (1, 1, 0, 'E-mail:', 'email', '', '', 'leavemessage', 'Y')";
    mysql_query($sql,$conn);
		$sql = "INSERT INTO `livehelp_questions` VALUES (2, 1, 0, 'Question:', 'textarea', '', '', 'leavemessage', 'N')";
    mysql_query($sql,$conn);
		$sql = "INSERT INTO `livehelp_questions` VALUES (3, 1, 0, 'Name', 'username', '', '', 'livehelp', 'N')";
    mysql_query($sql,$conn);
	//	$sql = "INSERT INTO `livehelp_questions` VALUES (4, 1, 1, 'E-mail', 'email', '', '', 'livehelp', 'N')";
  //  mysql_query($sql,$conn);
    $sql = "INSERT INTO `livehelp_questions` VALUES (5, 1, 1, 'Question', 'textarea', '', '', 'livehelp', 'N')";
    mysql_query($sql,$conn);
    $sql = "UPDATE livehelp_departments set leavetxt='<h3><SPAN CLASS=wh>LEAVE A MESSAGE:</SPAN></h3>Please type in your comments/questions in the below box <br> and provide an e-mail address so we can get back to you'";
    mysql_query($sql,$conn);		 
    $sql = "UPDATE livehelp_departments set topframeheight='45',topbackground='topclouds.gif',colorscheme='blue'";
    mysql_query($sql,$conn);  
  } // END : if($errors == "")
 } // END: $UNTRUSTED['dbtype_setup'] == "mysql"
} // END : ($installationtype == "newinstall")


if($errors != ""){
  print "<font color=990000 size=+3>THERE WAS A FEW PROBLEMS:</font><ul>";	
  print "$errors";
  print "<a href=javascript:history.go(-1)>CLICK HERE TO TRY AGAIN</a>";
  exit;
}

?>
<center>
<table width=400><tr><td bgcolor=D4DCF2> <b><font size=+3>INSTALLATION IS DONE!!</font></td></tr>
<tr><td bgcolor=F7FAFF>
	<?php  if(!($skipwrite)){ ?>
	You will now need to log into the admin of this Live Help and 
start adding pages. To do this click the link below. <b><font color=990000>write it down</font></b>
<br><br><center>
<b>username:</b><?php echo $UNTRUSTED['username'] ?> <br>
<b>password:</b><?php echo $UNTRUSTED['password1'] ?><br>
 </center>
<?php } ?>
<br>
<hr>
<a href=http://security.craftysyntax.com/updates/?v=<?php echo $version?>&db=<?php echo $UNTRUSTED['dbtype_setup'] ?>><font size=+3>CLICK HERE TO BEGIN REGISTRATION</font></a>
<br><br>
<a href=index.php>Skip REGISTRATION</a><br><br>
<SCRIPT type="text/javascript">
function gothere(){
  // This opens a window to the Live help News page. It sends in the query string the version 
  // being installed, type of database used (mysql or text based) and referer. This basic
  // info is just to get an idea of how many successful installations of what versions and 
  // what type of databases for the program are made.    
  url = 'http://www.craftysyntax.com/remote/updates.php?v=$version&d=<?php echo $UNTRUSTED['dbtype_setup']; ?>&referer=' + window.location;
  window.open(url, 's872', 'width=590,height=350,menubar=no,scrollbars=1,resizable=1');
}
setTimeout('gothere();',200);
</SCRIPT>
<?php
print "<a href=index.php>CLICK HERE to get started.. </a></td></tr></table>";
exit;
}
?>
<SCRIPT type="text/javascript">

function openwindow(theURL,winName,features) {//v1.0 
win2 = window.open(theURL,winName,features); 
window.win2.focus(); 
}
 
</script>

<h2>Crafty Syntax Live Help verison <?php echo $version?> Installation
<?php
$test_dir = getcwd();
?>
</h2>
problems on this page?!? <a href=http://www.craftysyntax.com/help/ TARGET=_blank>CLICK HERE TO GO TO SUPPORT PAGE</a>
<hr>
<?php
if ($errors != ""){
  print "<table width=600><tr><td><font color=990000>ERRORS: $errors</font></td></tr></table>";	
}

// Check to see if we can write to the config file.
if($UNTRUSTED['manualinstall'] != "YES"){
$fp = fopen ("config.php", "r+");
} else {
$fp = True;	
}

if(!$fp){
?>
<table width=500 bgcolor=F7FAFF><tr><td>
<font color=990000 size=+2><b>Can not open <font color=000099>config.php</font> file for writing:</b></font><br>


<hr>
<font color=007700><b>HOW TO FIX THIS:</b></font><br><br>
In order to configure the Live Help using this web wizard,
 the web server needs to be able to read and write to the
 file named <b><i>config.php</i></b>. if you can not change
 the permissions of this file then there is a manual change 
 option listed at the bottom of this page... 
 if you are planning on 
 using a text based database you will also need to change the
 permissions of the directory <b>txt-database</b>. Directions on doing  this follows:
<br>
Installing and configuring CSLH is done via a set of web pages.
To enable these web pages you need to log onto your web server using
telnet or (preferably) ssh, go to the CSLH directory and at the
prompt (which usually ends in '%' or '$') type:
<br><br>
chmod 777 txt-database <font color=990000><i>(if using a text based database)</i></font><br>
chmod 777 config.php<br>
<br><br>
After installation you can change the permissions of config.php to chmod 755.
<br><br>
if you can not ssh or telent into your website you can to the same task by
FTP: 
<br><br>	
Using WS_FTP this would be right hand clicking on the 
file config.php , selecting chmod, and then giving all
permissions to that file/directory. 
<br>
<img src=directions.gif>
<br>
<hr>
<br>
<h2><b>Manual config change option:</b></h2>
if you do not have access to, or can not change the permissions of config.php you 
also have the Option to update config.php yourself. 
<a href=setup.php?manualinstall=YES>CLICK HERE TO RUN INSTALLAION BY MANUALLY CHANGING config.php</a><br>
<br>
</td></tr></table><br>
<a href=setup.php>AFTER YOU HAVE CHANGED THE PERMISSIONS OF THE 
FILES HOLD DOWN THE shift KEY and PRESS REFRESH or RELOAD</a>
<br>
<br><br>
<table bgcolor=FFFFFF><tr><td>
<h3>if you can not change the permissions of the files here are Manual Installation Directions:</h3>
<hr>
if you do not have access to, or can not change the permissions of config.php you 
also have the Option to update config.php yourself after the database has been created. 
<a href=setup.php?manualinstall=YES>CLICK HERE TO RUN INSTALLAION BY MANUALLY CHANGING config.php</a><br>

</td></tr></table>

</td></tr></table>
<?php
exit;
}


?>
<FORM action=setup.php method=post name=erics>
<input type=hidden name=manualinstall value="<?php echo $UNTRUSTED['manualinstall']; ?>">
<table width=600 bgcolor=F7FAFF>
<?php

if (empty($UNTRUSTED['site_title'])){
  $UNTRUSTED['site_title'] = "My Online Live Help";
}

if(empty($UNTRUSTED['speaklanguage']))
  $UNTRUSTED['speaklanguage'] = "English";

if(empty($UNTRUSTED['installationtype']))
  $UNTRUSTED['installationtype'] = "newinstall";

if(empty($UNTRUSTED['username']))
  $UNTRUSTED['username'] = "";
  
if(empty($UNTRUSTED['password1']))
  $UNTRUSTED['password1'] = "";

if(empty($UNTRUSTED['password2']))
  $UNTRUSTED['password2'] = "";

if(empty($UNTRUSTED['email']))
  $UNTRUSTED['email'] = "";
  
if(empty($UNTRUSTED['rootpath']))
  $UNTRUSTED['rootpath'] = "";        

if(empty($UNTRUSTED['opening']))
  $UNTRUSTED['opening'] = "";   

if(empty($UNTRUSTED['dbtype_setup']))
  $UNTRUSTED['dbtype_setup'] = "";   

if(empty($UNTRUSTED['server_setup']))
  $UNTRUSTED['server_setup'] = "localhost"; 

if(empty($UNTRUSTED['database_setup']))
  $UNTRUSTED['database_setup'] = ""; 

if(empty($UNTRUSTED['datausername_setup']))
  $UNTRUSTED['datausername_setup'] = ""; 

if(empty($UNTRUSTED['mypassword_setup']))
  $UNTRUSTED['mypassword_setup'] = ""; 

if(empty($UNTRUSTED['txtpath']))
  $UNTRUSTED['txtpath'] = ""; 

if(empty($UNTRUSTED['s_homepage']))
  $UNTRUSTED['s_homepage'] = ""; 

if (empty($UNTRUSTED['homepage'])){ 
	$dir = dirname((!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : $_ENV['PHP_SELF']);
  $host = ( !empty($_SERVER['HTTP_HOST']) ) ? $_SERVER['HTTP_HOST'] : ( ( !empty($_ENV['HTTP_HOST']) ) ? $_ENV['HTTP_HOST'] : $HTTP_HOST );
  $UNTRUSTED['homepage']  = "http://" . $host . $dir;
}
  
if(empty($UNTRUSTED['openingmessage']))
  $UNTRUSTED['openingmessage'] = ""; 
        
 
 
?>
<tr><td bgcolor=DDDDDD colspan=2><b>LANGUAGE:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
All of the text for CSLH that is shown on the
users side can be in :<br>
</td></tr>
<tr><td>Language:</td>
<td><select name=speaklanguage>
 
<?php
// get list of installed langages..
$dir = "lang" . C_DIR;
if($handle=opendir($dir)){
	while (false !== ($file = readdir($handle))) {
	if ( (ereg("lang-",$file)) && ($file != "lang-.php"))
		{
		if (is_file("$dir/$file"))
			{
			$language = str_replace("lang-","",$file);
			$language = str_replace(".php","",$language);
			?><option value=<?php echo $language ?> <?php if ($UNTRUSTED['speaklanguage'] == $language){ print " SELECTED "; } ?> ><?php echo $language ?> </option><?php
			}
		}
	}
// no list guess...
} else {
      $languages = array("Dutch","English","English_uk","French","German","Italian","Portuguese_Brazilian","Spanish","Swedish");
			for($i=0;$i<count($languages); $i++){
  			$language = $languages[$i];
  			?><option value=<?php echo $language ?> <?php if ($UNTRUSTED['speaklanguage'] == $language){ print " SELECTED "; } ?> ><?php echo $language ?> </option><?php		
  		}	
}	
?>
 
</select>
</td></tr>

<tr><td bgcolor=DDDDDD colspan=2><b>INSTALLATION OPTION:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
You can upgrade to 
the newest version of the Live Help and not lose any of your data...<br>
</td></tr>
<tr><td>Installation:</td>
<td><select name=installationtype>
<option value=newinstall>NEW INSTALLATION</option>
<option value=upgrade2146 <?php if ($installationtype == "upgrade2146"){ print " SELECTED "; } ?> >UPGRADE from version 2.14.6 </option>
<option value=upgrade2145 <?php if ($installationtype == "upgrade2145"){ print " SELECTED "; } ?> >UPGRADE from version 2.14.5 </option>
<option value=upgrade2144 <?php if ($installationtype == "upgrade2144"){ print " SELECTED "; } ?> >UPGRADE from version 2.14.4 </option>
<option value=upgrade2143 <?php if ($installationtype == "upgrade2143"){ print " SELECTED "; } ?> >UPGRADE from version 2.14.3 </option>
<option value=upgrade2142 <?php if ($installationtype == "upgrade2142"){ print " SELECTED "; } ?> >UPGRADE from version 2.14.2 </option>
<option value=upgrade2141 <?php if ($installationtype == "upgrade2141"){ print " SELECTED "; } ?> >UPGRADE from version 2.14.1 </option>
<option value=upgrade2140 <?php if ($installationtype == "upgrade2140"){ print " SELECTED "; } ?> >UPGRADE from version 2.14.0 </option>
<option value=upgrade2131 <?php if ($installationtype == "upgrade2131"){ print " SELECTED "; } ?> >UPGRADE from version 2.13.1 </option>
<option value=upgrade2130 <?php if ($installationtype == "upgrade2130"){ print " SELECTED "; } ?> >UPGRADE from version 2.13.0 </option>
<option value=upgrade2129 <?php if ($installationtype == "upgrade2129"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.9 </option>
<option value=upgrade2128 <?php if ($installationtype == "upgrade2128"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.8 </option>
<option value=upgrade2127 <?php if ($installationtype == "upgrade2127"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.7 </option>
<option value=upgrade2126 <?php if ($installationtype == "upgrade2126"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.6 </option>
<option value=upgrade2125 <?php if ($installationtype == "upgrade2125"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.5 </option>
<option value=upgrade2124 <?php if ($installationtype == "upgrade2124"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.4 </option>
<option value=upgrade2123 <?php if ($installationtype == "upgrade2123"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.3 </option>
<option value=upgrade2122 <?php if ($installationtype == "upgrade2122"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.2 </option>
<option value=upgrade2121 <?php if ($installationtype == "upgrade2121"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.1 </option>
<option value=upgrade2120 <?php if ($installationtype == "upgrade2120"){ print " SELECTED "; } ?> >UPGRADE from version 2.12.0 </option>
<option value=upgrade2117 <?php if ($installationtype == "upgrade2117"){ print " SELECTED "; } ?> >UPGRADE from version 2.11.7 </option>
<option value=upgrade2116 <?php if ($installationtype == "upgrade2116"){ print " SELECTED "; } ?> >UPGRADE from version 2.11.6 </option>
<option value=upgrade2115 <?php if ($installationtype == "upgrade2115"){ print " SELECTED "; } ?> >UPGRADE from version 2.11.5 </option>
<option value=upgrade2114 <?php if ($installationtype == "upgrade2114"){ print " SELECTED "; } ?> >UPGRADE from version 2.11.4 </option>
<option value=upgrade2113 <?php if ($installationtype == "upgrade2113"){ print " SELECTED "; } ?> >UPGRADE from version 2.11.3 </option>
<option value=upgrade2112 <?php if ($installationtype == "upgrade2112"){ print " SELECTED "; } ?> >UPGRADE from version 2.11.2 </option>
<option value=upgrade2111 <?php if ($installationtype == "upgrade2111"){ print " SELECTED "; } ?> >UPGRADE from version 2.11.1 </option>
<option value=upgrade2110 <?php if ($installationtype == "upgrade2110"){ print " SELECTED "; } ?> >UPGRADE from version 2.11.0 </option>
<option value=upgrade2105 <?php if ($installationtype == "upgrade2105"){ print " SELECTED "; } ?> >UPGRADE from version 2.10.5 </option>
<option value=upgrade2104 <?php if ($installationtype == "upgrade2104"){ print " SELECTED "; } ?> >UPGRADE from version 2.10.4 </option>
<option value=upgrade2103 <?php if ($installationtype == "upgrade2103"){ print " SELECTED "; } ?> >UPGRADE from version 2.10.3 </option>
<option value=upgrade2102 <?php if ($installationtype == "upgrade2102"){ print " SELECTED "; } ?> >UPGRADE from version 2.10.2 </option>
<option value=upgrade2101 <?php if ($installationtype == "upgrade2101"){ print " SELECTED "; } ?> >UPGRADE from version 2.10.1 </option>
<option value=upgrade2100 <?php if ($installationtype == "upgrade2100"){ print " SELECTED "; } ?> >UPGRADE from version 2.10.0 </option>
<option value=upgrade299 <?php if ($installationtype == "upgrade299"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.9 </option>
<option value=upgrade298 <?php if ($installationtype == "upgrade298"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.8 </option>
<option value=upgrade297 <?php if ($installationtype == "upgrade297"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.7 </option>
<option value=upgrade296 <?php if ($installationtype == "upgrade296"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.6 </option>
<option value=upgrade295 <?php if ($installationtype == "upgrade295"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.5 </option>
<option value=upgrade294 <?php if ($installationtype == "upgrade294"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.4 </option>
<option value=upgrade293 <?php if ($installationtype == "upgrade293"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.3 </option>
<option value=upgrade292 <?php if ($installationtype == "upgrade292"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.2 </option>
<option value=upgrade291 <?php if ($installationtype == "upgrade291"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.1 </option>
<option value=upgrade290 <?php if ($installationtype == "upgrade290"){ print " SELECTED "; } ?> >UPGRADE from version 2.9.0 </option>
<option value=upgrade284 <?php if ($installationtype == "upgrade284"){ print " SELECTED "; } ?> >UPGRADE from version 2.8.4 </option>
<option value=upgrade283 <?php if ($installationtype == "upgrade283"){ print " SELECTED "; } ?> >UPGRADE from version 2.8.3 </option>
<option value=upgrade282 <?php if ($installationtype == "upgrade282"){ print " SELECTED "; } ?> >UPGRADE from version 2.8.2 </option>
<option value=upgrade281 <?php if ($installationtype == "upgrade281"){ print " SELECTED "; } ?> >UPGRADE from version 2.8.1 </option>
<option value=upgrade280 <?php if ($installationtype == "upgrade280"){ print " SELECTED "; } ?> >UPGRADE from version 2.8.0 </option>
<option value=upgrade274 <?php if ($installationtype == "upgrade274"){ print " SELECTED "; } ?> >UPGRADE from version 2.7.4 </option>
<option value=upgrade273 <?php if ($installationtype == "upgrade273"){ print " SELECTED "; } ?> >UPGRADE from version 2.7.3 </option>
<option value=upgrade272 <?php if ($installationtype == "upgrade272"){ print " SELECTED "; } ?> >UPGRADE from version 2.7.2 </option>
<option value=upgrade271 <?php if ($installationtype == "upgrade271"){ print " SELECTED "; } ?> >UPGRADE from version 2.7.1 </option>
<option value=upgrade27 <?php if ($installationtype == "upgrade27"){ print " SELECTED "; } ?> >UPGRADE from version 2.7 </option>
<option value=upgrade26 <?php if ($installationtype == "upgrade26"){ print " SELECTED "; } ?> >UPGRADE from version 2.6 </option>
<option value=upgrade25 <?php if ($installationtype == "upgrade25"){ print " SELECTED "; } ?> >UPGRADE from version 2.5 </option>
<option value=upgrade24 <?php if ($installationtype == "upgrade24"){ print " SELECTED "; } ?> >UPGRADE from version 2.4 </option>
<option value=upgrade22 <?php if ($installationtype == "upgrade22"){ print " SELECTED "; } ?> >UPGRADE from version 2.2 or 2.3 </option>
<option value=upgrade <?php if ($installationtype == "upgrade"){ print " SELECTED "; } ?> >UPGRADE from Before version 2.2 </option>
</select>
</td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Title of your Live Help:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>This is the title of your Live Help.</td></tr>
<tr><td>Title of your Live Help:</td><td><input type=text name=site_title size=40 value="<?php echo $UNTRUSTED['site_title']; ?>"></td></tr> 
<tr><td bgcolor=DDDDDD colspan=2><b>Web path to Live Help:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>This is the url to the Live Help on your server. It will be used 
to access the online help for your site..</td></tr>
<tr><td>Live Help HTTP path:</td><td><input type=text name=homepage size=40 value="<?php echo $UNTRUSTED['homepage']; ?>"></td></tr>
<tr><td>Live Help HTTPS path:</td><td><input type=text name=s_homepage size=40 value="<?php echo $UNTRUSTED['s_homepage']; ?>"></td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Administration user/Password:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
Although you can create multiple Operators for the Live Help, there is
a main administrator that can create users, edit,
add, delete everything. Create that account here.</td></tr>
<tr><td>username:</td><td><input type=text name=username size=10 value="<?php echo $UNTRUSTED['username']; ?>"></td></tr>
<tr><td>password:</td><td><input type=password name=password1 size=10 value="<?php echo $UNTRUSTED['password1']; ?>"></td></tr>
<tr><td>password (again):</td><td><input type=password name=password2 size=10 value="<?php echo $UNTRUSTED['password2']; ?>"></td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Administration e-mail:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>This is the e-mail address for the administrator of this Live Help program (used for lost password ) .</td></tr>
<tr><td>email:</td><td><input type=text name=email size=30 value="<?php echo $UNTRUSTED['email']; ?>"></td></tr>

<tr><td bgcolor=DDDDDD colspan=2><b>Full Path to Live Help:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
This is the Full Path to Live Help. 
This file path should be like /www/username/public_html/livehelp NOT http://yoursite.com/livehelp/ </td></tr>
<tr><td>Full Path to Live Help:</td><td><input type=text name=rootpath size=55 value="<?php if(!(empty($UNTRUSTED['rootpath']))){ print $UNTRUSTED['rootpath']; } else { print getcwd(); } ?>"></td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Opening message:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
When users first open up the Live Help they are directed to a
page to enter in their name so that Operators can identify them
easy. This is the text shown on that opening page.</td></tr>
<tr><td colspan=2>
<b>Opening Message:</b><br>
<textarea cols=45 rows=4 name=opening>
<?php
if(empty($UNTRUSTED['openingmessage'])){
print "Welcome to our Live Help. Please enter your name in the input box below to begin.";
} else {
print $UNTRUSTED['openingmessage'];
}
?>
</textarea>
</td></tr>
<tr><td bgcolor=DDDDDD colspan=2><b>Type of Database:</b></td></tr>
<tr><td bgcolor=EEEECC colspan=2>
This is the type of database that you are using. 
If you are having trouble with your database settings you can 
look for help at the <a href=http://www.craftysyntax.com/mysqlsupport.php target=_blank>CSLH Database Support Page.</a>.
<!--
It is HIGHLY recommended that you use 
MySQL and NOT use txt-db-api. Please use Mysql if you have a
mysql database. Use txt-db-api only as a LAST resort as it is VERY slower then mysql
and also can corrupt easy.. If you must use txt-db-api then <a href=txt-database/README.txt target=_blank>
read the install</a> and copy the folder located at
<br>
<?php 
 $default_db = $test_dir . "/txt-database"; 
?>
<br>
To a location OUTSIDE the web accessible htdocs folder of your website and place
the full path to the txt-database folder below:
-->
This version of Crafty Syntax requires a Mysql Database. If you would like to 
install Crafty Syntax without using a Mysql Database please visit :
<a href=http://craftysyntax.com/installation.php target=_blank>http://craftysyntax.com/installation.php</a>
and see what is the latest version supports.
<br>
</td></tr>

<tr><td>Database:</td><td><select name=dbtype_setup>
<option value=mysql <?php if ($UNTRUSTED['dbtype_setup'] == "mysql"){ print " SELECTED "; } ?> >MySQL </option>
<!--<option value=txt-db-api <?php if ($UNTRUSTED['dbtype_setup'] == "txt-db-api"){ print " SELECTED "; } ?> >txt-db-api (simple Flat text files)</option>-->
</select></td></tr>
<tr><td colspan=2><ul>
<table bgcolor=D4DCF2>
<tr><td colspan=2>If <b>MySQL</b> is selected above:</td></tr>
<?php if($UNTRUSTED['server_setup'] == ""){ $server_setup = "localhost"; } ?>
<tr><td>SQL server:</td><td><input type=text name=server_setup size=20 value="<?php echo $UNTRUSTED['server_setup']; ?>" ></td></tr>
<tr><td>SQL database:</td><td><input type=text name=database_setup size=20 value="<?php echo $UNTRUSTED['database_setup']; ?>"></td></tr>
<tr><td>SQL user:</td><td><input type=text name=datausername_setup size=20 value="<?php echo $UNTRUSTED['datausername_setup']; ?>"></td></tr>
<tr><td>SQL password:</td><td><input type=password name=mypassword_setup size=20 value="<?php echo $UNTRUSTED['mypassword_setup']; ?>"></td></tr>
</table><br>
<!--
<table bgcolor=D4DCF2>
<tr><td colspan=2>If <b>txt-db-api (simple Flat text files)</b> is selected above you need to provide a 
full path to the directory where the txt files will be stored. This directory must be writable 
by the web and should not be in a web accessible directory. NOTE that it is <font color=990000><b><u>HIGHLY</u></b></font> suggested that
the txt-database directory be located OUTSIDE the public html files. See <a href=txt-database/README.txt target=_blank>Directions</a></td></tr>
<tr><td>txt path:</td><td><input type=text name=txtpath size=55 value="<?php if($UNTRUSTED['txtpath'] != ""){ print $UNTRUSTED['txtpath']; } else print $default_db;  ?>" ></td></tr> 
</table>
-->

</td></tr>
</table></td></tr>
</table>

<input type=SUBMIT name=action value=INSTALL>
</form>