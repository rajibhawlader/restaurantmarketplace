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

// set this to true if false you will be re-directed to the setup page
$installed=false;  

if(empty($_SERVER)){ $_SERVER = $HTTP_SERVER_VARS; }

// if this program has not been installed and this is not the setup.php
// re-direct to that page to setup database..
if( ($installed == false) && (!eregi("setup.php", $_SERVER['PHP_SELF'])) ){ 
 Header("Location: setup.php");
 exit;
}

// if this file has insecure permissions:
if($installed==true){
 $perm = @stat("config.php");
 if(!(empty($perm[2]))){
  if( ($perm[2] == "33279") || ($perm[2] == "33278") || ($perm[2] == "33270")  ){
  	@chmod("config.php", 0755);
  	$perm = @stat("config.php");
    if(!(empty($perm[2]))){
      if( ($perm[2] == "33279") || ($perm[2] == "33278") || ($perm[2] == "33270")  ){
  	      print "<font color=990000>You must secure this program. Insecure permissions on config.php</font>";
  	      print "<br>While installing CSLH you might of needed to change the permissions of config.php so ";
  	      print "that it is writable by the web server. config.php no longer needs to be written to so ";
  	      print " please chmod config.php to not have write permissions for everyone. you can do this by";
  	      print " UNCHECKING the box that reads write permissions for the file:";
          print "<br><br><img src=directions2.gif>";    
          exit;
       }
     }
    }      
  }
 } 
 
 // dbtype either is either:
 // mysql       - this is for Mysql support
 // txt-db-api  - txt database support. 
 $dbtype = 'INPUT-DBTYPE';

 //database connections for MYSQL 
 $server = 'INPUT-SERVER';
 $database = 'INPUT-DATABASE';
 $datausername = 'INPUT-DATAUSERNAME';
 $password = 'INPUT-PASSWORD';

 // change this to the full SERVER path to your files 
 // on the server .. not the HTTP PATH.. for example enter in
 // $application_root = "/usr/local/apache/htdocs/livehelp/"
 // not /livehelp/
 // keep ending slash.
 // WINDOWS would look something like:
 // $application_root = "D:\\virtual www customers\\craftysyntax\\livehelp_1_6\\";
 $application_root = 'INPUT-ROOTPATH';

 // if using txt-db-api need the path to the txt databases directory
 $DB_DIR = 'INPUT-TXTPATH';
 // if using txt-db-api need to have the full path to the txt-db-api
 // you must set this property to something like /home/website/livehelp/txt-db-api/ 
 $API_HOME_DIR = 'INPUT-ROOTPATH' . 'txt-db-api/';
 
	

?>