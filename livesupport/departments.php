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
require_once("department_function.php");
validate_session($identity);
  
// get the info of this user.. 
$sqlquery = "SELECT user_id,onchannel,show_arrival,user_alert,isadmin FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$res = $mydatabase->query($sqlquery);
$row = $res->fetchRow(DB_FETCHMODE_ORDERED);
$myid = $row[0];
$channel = $row[1];
$show_arrival = $row[2]; 
$user_alert = $row[3];
$isadminsetting = $row[4];


if(!(isset($UNTRUSTED['createnew']))) { $UNTRUSTED['createnew'] = ""; }
if(!(isset($UNTRUSTED['createit']))) { $UNTRUSTED['createit'] = ""; }
if(!(isset($UNTRUSTED['removeit']))) { $UNTRUSTED['removeit'] = ""; }
if(!(isset($UNTRUSTED['updateit']))) { $UNTRUSTED['updateit'] = 0; } 
if(!(isset($UNTRUSTED['edit']))) { $UNTRUSTED['edit'] = ""; }
if(!(isset($UNTRUSTED['html']))) { $UNTRUSTED['html'] = ""; }
if(!(isset($UNTRUSTED['help']))) { $UNTRUSTED['help'] = ""; }
if(!(isset($UNTRUSTED['whattodo']))){ $UNTRUSTED['whattodo'] = ""; }

if( ($isadminsetting != "Y") &&  ($isadminsetting != "N") ){
 print $lang['txt41'];
 if(!($serversession))
   $mydatabase->close_connect();
 exit;	
}
?>
<link title="new" rel="stylesheet" href="style.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lang['charset']; ?>" > 

<body bgcolor=<?php echo $color_background;?>><center>
<?php


if($UNTRUSTED['createnew'] != ""){
  // get one row as default values to create the new department:
  $sqlquery = "SELECT * FROM livehelp_departments LIMIT 1";
  $data = $mydatabase->query($sqlquery);
  $row = $data->fetchRow(DB_FETCHMODE_ASSOC);
  ?><table width=600><tr><td><?php
  $row['nameof'] = " ";
  showdetails($row,0);
  ?></td></tr></table><?php
  exit;
}
 
if($UNTRUSTED['createit'] != ""){
	
	// fix online offline images:
	if(substr($UNTRUSTED['onlineimage'],0,1) =="/")
	  $UNTRUSTED['onlineimage'] = substr($UNTRUSTED['onlineimage'],1);
	if(substr($UNTRUSTED['offlineimage'],0,1) =="/")
	  $UNTRUSTED['offlineimage'] = substr($UNTRUSTED['offlineimage'],1);
	  
	  	  
  $sqlquery = "INSERT INTO livehelp_departments 
  (visible,nameof,offline,opening,messageemail,leaveamessage,requirename,onlineimage,offlineimage,whilewait,timeout,leavetxt,topframeheight,topbackground,colorscheme,speaklanguage,busymess,smiles) 
  VALUES (1,'".filter_sql($UNTRUSTED['nameof'])."','".filter_sql($UNTRUSTED['offline'])."',
  '".filter_sql($UNTRUSTED['opening'])."','".filter_sql($UNTRUSTED['messageemail'])."',
  '".filter_sql($UNTRUSTED['leaveamessage'])."','".filter_sql($UNTRUSTED['requirename'])."',
  '".filter_sql($UNTRUSTED['onlineimage'])."','".filter_sql($UNTRUSTED['offlineimage'])."',
  '".filter_sql($UNTRUSTED['whilewait'])."',".intval($UNTRUSTED['timeout']).",
  '".filter_sql($UNTRUSTED['leavetxt'])."'," . intval($UNTRUSTED['topframeheight']) . ",
  '".filter_sql($UNTRUSTED['topbackground']) . "','". filter_sql($UNTRUSTED['colorscheme'])."',
  '".filter_sql($UNTRUSTED['dep_speaklanguage']) . "','". filter_sql($UNTRUSTED['busymess'])."','". filter_sql($UNTRUSTED['smiles'])."')";
  $mydatabase->insert($sqlquery);	
  
	$sqlquery = "SELECT MAX(recno) FROM livehelp_departments";
	$temp = $mydatabase->query($sqlquery);
	$temprow = $temp->fetchRow(DB_FETCHMODE_ORDERED);		 
  $newdep = $temprow[0];

  // add tabs. 
  $sqlquery = "SELECT id FROM livehelp_modules";
  $data = $mydatabase->query($sqlquery);
  while($row = $data->fetchRow(DB_FETCHMODE_ORDERED)){
  	$id = $row[0];
    $varname = "modules_" . $id;     
    if(!(empty($UNTRUSTED[$varname]))) {  
    	 $varname_ord = "modules_ord_" . $id; 
       $varname_def = "modules_def_" . $id; 
       if(!(empty($UNTRUSTED[$varname_ord])))
           $ord_ = $UNTRUSTED[$varname_ord];        
        else 
           $ord_ = 0;
        if(!(empty($UNTRUSTED[$varname_def])))        
           $def_ = $UNTRUSTED[$varname_def];
        else
           $def_ = "";
                       
        $sqlquery = "INSERT INTO livehelp_modules_dep (modid,departmentid,ordernum,defaultset) VALUES ('" . $id . "',".intval($newdep).",'$ord_','$def_')";	
        $mydatabase->query($sqlquery);
    }	
  }
  
  // add livehelp questions:
  $query = "SELECT * FROM livehelp_questions WHERE module='livehelp' AND department=". intval($UNTRUSTED['templatedep']);
  $data = $mydatabase->query($query); 
  while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){     
  	 $id = $row['id'];
     $ordering_f = "questions_" . intval($UNTRUSTED['templatedep']) . "_".$id."_ordering";
     $required_f = "questions_" . intval($UNTRUSTED['templatedep']) . "_".$id."_required";
     $headertext_f = "questions_" . intval($UNTRUSTED['templatedep']) . "_".$id."_headertext";
     $fieldtype_f ="questions_" . intval($UNTRUSTED['templatedep']) . "_".$id."_fieldtype";
     $options_f = "questions_" . intval($UNTRUSTED['templatedep']) . "_".$id."_options";

     $ordering = $UNTRUSTED[$ordering_f];
     $required = ( empty($UNTRUSTED[$required_f]))? "N" : "Y";
     $headertext = $UNTRUSTED[$headertext_f];
     $fieldtype = $UNTRUSTED[$fieldtype_f];
     $options = $UNTRUSTED[$options_f];  	
     	
     $sqlquery = "INSERT INTO livehelp_questions (department,ordering,headertext,fieldtype,options,module,required) VALUES (".intval($newdep).",".intval($ordering).",'".filter_sql($headertext) ."','".filter_sql($fieldtype)."','".filter_sql($options)."','livehelp','".filter_sql($required)."')";         
     $mydatabase->query($sqlquery); 
  }

  // add leavemessage questions:
  $query = "SELECT * FROM livehelp_questions WHERE module='leavemessage' AND department=". intval($UNTRUSTED['templatedep']);
  $data = $mydatabase->query($query); 
  while($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){     
  	 $id = $row['id'];
     $ordering_f = "leavemessage_" . intval($UNTRUSTED['templatedep']) . "_".$id."_ordering";
     $required_f = "leavemessage_" . intval($UNTRUSTED['templatedep']) . "_".$id."_required";
     $headertext_f = "leavemessage_" . intval($UNTRUSTED['templatedep']) . "_".$id."_headertext";
     $fieldtype_f ="leavemessage_" . intval($UNTRUSTED['templatedep']) . "_".$id."_fieldtype";
     $options_f = "leavemessage_" . intval($UNTRUSTED['templatedep']) . "_".$id."_options";

     $ordering = $UNTRUSTED[$ordering_f];
     $required = ( empty($UNTRUSTED[$required_f]))? "N" : "Y";
     $headertext = $UNTRUSTED[$headertext_f];
     $fieldtype = $UNTRUSTED[$fieldtype_f];
     $options = $UNTRUSTED[$options_f];  	
     	
     $sqlquery = "INSERT INTO livehelp_questions (department,ordering,headertext,fieldtype,options,module,required) VALUES (".intval($newdep).",".intval($ordering).",'".filter_sql($headertext) ."','".filter_sql($fieldtype)."','".filter_sql($options)."','leavemessage','".filter_sql($required)."')";         
     $mydatabase->query($sqlquery); 
  }  
   
  // Add all Admin users to this department:
  $sqlquery = "SELECT user_id FROM livehelp_users WHERE isadmin='Y'";
  $data = $mydatabase->query($sqlquery);
  while($row = $data->fetchRow(DB_FETCHMODE_ORDERED)){
            $query = "INSERT INTO livehelp_operator_departments (user_id,department) 
                  VALUES (".intval($row[0])."," . intval($newdep) . ")";	
        $mydatabase->query($query);
  }  	  
  
  print "<table width=500 bgcolor=$color_alt1><tr><td><b>".$lang['txt63']."</b></td></tr></table>";  
}

if(!(empty($UNTRUSTED['removeq']))){
  $sqlquery = "DELETE FROM livehelp_questions WHERE id=". intval($UNTRUSTED['removeq']);
	$mydatabase->query($sqlquery);
}

if(!(empty($UNTRUSTED['removecq']))){
  $sqlquery = "DELETE FROM livehelp_questions WHERE id=" . intval($UNTRUSTED['removecq']);
	$mydatabase->query($sqlquery);
}

if($UNTRUSTED['removeit'] !=""){
$sqlquery = "DELETE FROM livehelp_departments WHERE recno=" . intval($UNTRUSTED['removeit']);
$mydatabase->query($sqlquery);
$sqlquery = "DELETE FROM livehelp_questions WHERE department=" . intval($UNTRUSTED['removeit']);
$mydatabase->query($sqlquery);
$sqlquery = "DELETE FROM livehelp_operator_departments WHERE department=" . intval($UNTRUSTED['removeit']);
$mydatabase->query($sqlquery);
}

// update questions:
$updateit = intval($UNTRUSTED['updateit']);		 
if($updateit != 0){
	// update existing Live Help Questions:
	$sqlquery = "SELECT id FROM livehelp_questions WHERE  module='livehelp' AND department=" . intval($UNTRUSTED['updateit']);
	$res = $mydatabase->query($sqlquery);
	while($questions = $res->fetchRow(DB_FETCHMODE_ORDERED)){
		 $id = $questions[0];
     $ordering_f = "questions_" . $updateit . "_".$id."_ordering";
     $required_f = "questions_" . $updateit . "_".$id."_required";
     $headertext_f = "questions_" . $updateit . "_".$id."_headertext";
     $fieldtype_f ="questions_" . $updateit . "_".$id."_fieldtype";
     $options_f = "questions_" . $updateit . "_".$id."_options";
     $department = $updateit;
     $ordering = $UNTRUSTED[$ordering_f];
     $required = ( empty($UNTRUSTED[$required_f]))? "N" : "Y";
     $headertext = $UNTRUSTED[$headertext_f];
     $fieldtype = $UNTRUSTED[$fieldtype_f];
     $options = $UNTRUSTED[$options_f];  		 
     $sqlquery = "UPDATE livehelp_questions SET required='". filter_sql($required) . "',ordering=". intval($ordering) . ",headertext='". filter_sql($headertext) . "',fieldtype='". filter_sql($fieldtype) . "',options='". filter_sql($options) . "' WHERE id=". intval($id);
     $mydatabase->query($sqlquery);    
  } 	

  // see if there is a new question . If so add it:
  $ordering_f = "questions_" . $updateit . "_-1_ordering";
  $required_f = "questions_" . $updateit . "_-1_required";
  $headertext_f = "questions_" . $updateit . "_-1_headertext";
  $fieldtype_f ="questions_" . $updateit . "_-1_fieldtype";
  $options_f = "questions_" . $updateit . "_-1_options";
  $department = $updateit;
  $ordering = $UNTRUSTED[$ordering_f];
  $required = ( empty($UNTRUSTED[$required_f]))? "N" : "Y";
  $headertext = $UNTRUSTED[$headertext_f];
  $fieldtype = $UNTRUSTED[$fieldtype_f];
  $options = $UNTRUSTED[$options_f];         
  if(!(empty($headertext))){
      $sqlquery = "INSERT INTO livehelp_questions (department,ordering,headertext,fieldtype,options,module,required) VALUES (".intval($department).",".intval($ordering).",'".filter_sql($headertext) ."','".filter_sql($fieldtype) . "','" . filter_sql($options) . "','livehelp','".filter_sql($required) . "')";         
      $mydatabase->query($sqlquery);
  }       
  
 	// update existing Contact Questions:
	$sqlquery = "SELECT id FROM livehelp_questions WHERE  module='leavemessage' AND department=".intval($updateit);
	$res = $mydatabase->query($sqlquery);
	while($questions = $res->fetchRow(DB_FETCHMODE_ORDERED)){
		 $id = $questions[0];
     $ordering_f = "leavemessage_" . $updateit . "_".$id."_ordering";
     $required_f = "leavemessage_" . $updateit . "_".$id."_required";
     $headertext_f = "leavemessage_" . $updateit . "_".$id."_headertext";
     $fieldtype_f ="leavemessage_" . $updateit . "_".$id."_fieldtype";
     $options_f = "leavemessage_" . $updateit . "_".$id."_options";
     $department = $updateit;
     $ordering = $UNTRUSTED[$ordering_f];
     $required = ( empty($UNTRUSTED[$required_f]))? "N" : "Y";
     $headertext = $UNTRUSTED[$headertext_f];
     $fieldtype = $UNTRUSTED[$fieldtype_f];
     $options = $UNTRUSTED[$options_f];  		 
     $sqlquery = "UPDATE livehelp_questions SET required='".filter_sql($required)."',ordering=". intval($ordering) .",headertext='".filter_sql($headertext)."',fieldtype='".filter_sql($fieldtype)."',options='".filter_sql($options) ."' WHERE id=". intval($id);
     $mydatabase->query($sqlquery);    
  } 	

  // see if there is a new question . If so add it:
  $ordering_f = "leavemessage_" . $updateit . "_-1_ordering";
  $required_f = "leavemessage_" . $updateit . "_-1_required"; 
  $headertext_f = "leavemessage_" . $updateit . "_-1_headertext";
  $fieldtype_f ="leavemessage_" . $updateit . "_-1_fieldtype";
  $options_f = "leavemessage_" . $updateit . "_-1_options";
  $department = $updateit;
  $ordering = $UNTRUSTED[$ordering_f];
  $required = ( empty($UNTRUSTED[$required_f]))? "N" : "Y";
  $headertext = $UNTRUSTED[$headertext_f];
  $fieldtype = $UNTRUSTED[$fieldtype_f];
  $options = $UNTRUSTED[$options_f];         
  if(!(empty($headertext))){
      $sqlquery = "INSERT INTO livehelp_questions (department,ordering,headertext,fieldtype,options,module,required) VALUES (".intval($department).",".intval($ordering).",'".filter_sql($headertext) ."','".filter_sql($fieldtype)."','".filter_sql($options)."','leavemessage','".filter_sql($required)."')";         
      $mydatabase->query($sqlquery);
  }   
}  // end of update questions.
 
 
 
if($UNTRUSTED['updateit'] != 0){
  if( (ereg("http:",$UNTRUSTED['onlineimage']))){
    print "<br><br><table width=450><tr><td><font color=990000>" . $lang['error1'] . "</font>";
    exit;	
  }
  if( (ereg("http:",$UNTRUSTED['offlineimage']))){
    print "<br><br><table width=450><tr><td><font color=990000>" . $lang['error1'] . "</font>";
    exit;	
  }
 	// fix online offline images:
	if(substr($UNTRUSTED['onlineimage'],0,1) =="/")
	  $UNTRUSTED['onlineimage'] = substr($UNTRUSTED['onlineimage'],1);
	if(substr($UNTRUSTED['offlineimage'],0,1) =="/")
	  $UNTRUSTED['offlineimage'] = substr($UNTRUSTED['offlineimage'],1);
  $sqlquery = "UPDATE livehelp_departments 
            SET topframeheight=".intval($UNTRUSTED['topframeheight']).",
                topbackground='".filter_sql($UNTRUSTED['topbackground'])."',
                colorscheme='".filter_sql($UNTRUSTED['colorscheme'])."',
                leavetxt='".filter_sql($UNTRUSTED['leavetxt'])."',
                timeout=".intval($UNTRUSTED['timeout']).",
                whilewait='".filter_sql($UNTRUSTED['whilewait'])."',
                creditline='".filter_sql($UNTRUSTED['creditline'])."',
                nameof='".filter_sql($UNTRUSTED['nameof'])."',
                offline='".filter_sql($UNTRUSTED['offline'])."',
                opening='".filter_sql($UNTRUSTED['opening'])."',
                messageemail='".filter_sql($UNTRUSTED['messageemail'])."',
                leaveamessage='".filter_sql($UNTRUSTED['leaveamessage'])."',
                requirename='".filter_sql($UNTRUSTED['requirename'])."',
                onlineimage='".filter_sql($UNTRUSTED['onlineimage'])."',
                offlineimage='".filter_sql($UNTRUSTED['offlineimage'])."',
                speaklanguage='".filter_sql($UNTRUSTED['dep_speaklanguage'])."',
                emailfun='".filter_sql($UNTRUSTED['emailfun'])."',
                dbfun='".filter_sql($UNTRUSTED['dbfun'])."',                
                busymess='".filter_sql($UNTRUSTED['busymess'])."', 
                smiles='".filter_sql($UNTRUSTED['smiles'])."'                
            WHERE recno=". intval($UNTRUSTED['updateit']);
  $mydatabase->query($sqlquery);
 
  // clear old tabs.
  $sqlquery = "DELETE FROM livehelp_modules_dep WHERE departmentid=". intval($UNTRUSTED['updateit']); 
  $mydatabase->query($sqlquery);

  // add tabs. 
  $sqlquery = "SELECT id FROM livehelp_modules";
  $data = $mydatabase->query($sqlquery);
  while($row = $data->fetchRow(DB_FETCHMODE_ORDERED)){
  	$id = $row[0];
    $varname = "modules_" . $id;     
    if(!(empty($UNTRUSTED[$varname]))) {  
    	 $varname_ord = "modules_ord_" . $id; 
       $varname_def = "modules_def_" . $id; 
       if(!(empty($UNTRUSTED[$varname_ord])))
           $ord_ = $UNTRUSTED[$varname_ord];        
        else 
           $ord_ = 0;
        if(!(empty($UNTRUSTED[$varname_def])))        
           $def_ = $UNTRUSTED[$varname_def];
        else
           $def_ = "";
                       
        $sqlquery = "INSERT INTO livehelp_modules_dep (modid,departmentid,ordernum,defaultset) VALUES ('" . $id . "',".intval($UNTRUSTED['updateit']).",'$ord_','$def_')";	
        $mydatabase->query($sqlquery);
    }	
  }
  print "<table width=500 bgcolor=$color_alt1><tr><td><b>" . $lang['txt63'] . "</b></td></tr></table>";
}

?>
<br><br>
<table bgcolor=DDDDDD width=600><tr><td>
<b><?php echo $lang['dept']; ?></b>
</b></td></tr></table>
<SCRIPT type="text/javascript">
function notpartof(){
	 alert('You are not currently part of this Department. To add yourself to this department click on the Operators tab, edit your username and then check the checkbox for this department for your login name.');
 }
</SCRIPT>
<?php
if($UNTRUSTED['whattodo'] == "reorder"){
 $sqlquery = "SELECT * FROM livehelp_departments";
 $data = $mydatabase->query($sqlquery);
 while ($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
	 $recno = $row['recno'];
	 $ordernumber_val = "order_" . $recno;
	 $visible_val = "visible_" . $recno;
	 if(empty($UNTRUSTED[$visible_val])) $UNTRUSTED[$visible_val] = 0;
	 
	 $sql = "UPDATE livehelp_departments SET visible=".intval($UNTRUSTED[$visible_val]).",ordering=".intval($UNTRUSTED[$ordernumber_val])." WHERE recno=$recno";
   $mydatabase->query($sql);
 }	 
}
$sqlquery = "SELECT * FROM livehelp_departments ORDER by ordering";
$data = $mydatabase->query($sqlquery);
$bgcolor="$color_alt2";

if(empty($UNTRUSTED['edit'])){
	print "<form action=departments.php METHOD=POST>";
	print "<input type=hidden name=whattodo value=reorder>";
 }
?>
<table width=600>
<tr bgcolor=FFFFFF>
<?php
  if( ($isadminsetting == "Y") && (empty($UNTRUSTED['edit'])))
    print "<td><b>Visible</b></td><td><b>". $lang['txt47'] ."</b></td>";
?>	
	<td width=200><b><?php echo $lang['name']; ?></b></td><td><b><?php echo $lang['options']; ?></b></td></tr>

<?php
while ($row = $data->fetchRow(DB_FETCHMODE_ASSOC)){
	 $recno = $row['recno'];
	 $nameof= $row['nameof'];
	 $ordering= $row['ordering'];
	 $visible= $row['visible'];
	 if($visible)
	   $visible_check = " CHECKED ";
	 else
	   $visible_check = " ";
	   	     
 if($recno == $UNTRUSTED['edit']){
 showdetails($row,1);
 } else {
 	if($bgcolor=="$color_alt2"){ $bgcolor="$color_alt1"; } else { $bgcolor="$color_alt2"; }
   $sqlquery = "SELECT user_id FROM livehelp_operator_departments WHERE user_id='" . $myid . "' and department='" . $recno . "' ";
   $check = $mydatabase->query($sqlquery);
    if( $check->numrows() == 0){
     print "<tr bgcolor=$bgcolor>";
  if( ($isadminsetting == "Y") && (empty($UNTRUSTED['edit'])) )
     print "<td><input type=checkbox name=visible_$recno value=1 $visible_check></td><td><input type=text size=4 name=order_$recno value=\"$ordering\"></td>";
     print "<td><b>" . $nameof . "</b></td><td NOWRAP> ";
     if($isadminsetting == "Y")
      print "<a href=javascript:notpartof()><img src=images/settings.gif width=21 height=22 border=0>".$lang['settings']."</a>&nbsp; | &nbsp;";
     print " <a href=javascript:notpartof()><img src=images/html.gif width=21 height=22 border=0>".$lang['txt109']."</a>  ";
    } else {
     print "<tr bgcolor=$bgcolor>";
  if( ($isadminsetting == "Y") && (empty($UNTRUSTED['edit'])) )
     print "<td><input type=checkbox name=visible_$recno value=1 $visible_check></td><td><input type=text size=4 name=order_$recno value=\"$ordering\"></td>";
     print "<td><b>" . $nameof . "</b></td><td NOWRAP> ";
     if($isadminsetting == "Y")
       print " <a href=departments.php?edit=" . $recno . "><img src=images/settings.gif width=21 height=22 border=0>".$lang['settings']."</a>&nbsp; &nbsp; &nbsp;";
     print " <a href=htmltags.php?department=" . $recno . "><img src=images/html.gif width=21 height=22 border=0><b>".$lang['txt109']."</b></a> ";
    }   
 if( ($isadminsetting == "Y") && ($data->numrows() != 1) ){
 	  print " &nbsp;&nbsp; <a href=departments.php?removeit=" . $recno . " onClick=\"return confirm('".$lang['areyousure']."')\"><img src=images/delete.gif width=21 height=22 border=0><font color=990000>".$lang['REMOVE']."</font>";
 } 
 print "</td></tr>";
 }
 if($recno == $UNTRUSTED['html']){
 print "<tr><td colspan=3 width=500>";
 ?>
<?php echo $lang['how_to_add']; ?>
<br>
<table width=700 bgcolor=<?php echo $color_alt1;?> border=1>
<tr><td NOWRAP><br><br>
<b>
&lt;!-- Powered by: Crafty Syntax Live Help        http://www.craftysyntax.com/ --&gt;<br>
&lt;script language="javascript" src="<?php echo $CSLH_Config['webpath']; ?>livehelp_js.php?department=<?php echo $recno; ?>"&gt;&lt;/script&gt;<br>
&lt;!-- copyright 2003 - 2008 by Eric Gerdes --&gt;<br><br>
</b></td></tr></table><br><br>
<?php echo $lang['how_to_add0']; ?>
<br>
<table width=700 bgcolor=<?php echo $color_alt1;?> border=1>
<tr><td NOWRAP><br><br>
<b>
&lt;!-- Powered by: Crafty Syntax Live Help        http://www.craftysyntax.com/ --&gt;<br>
&lt;a href="<?php echo $CSLH_Config['webpath']; ?>livehelp.php?department=<?php echo $recno; ?>"&gt;&lt;img src=<?php echo $CSLH_Config['webpath']; ?>image.php?what=getstate&department=<?php echo $recno; ?> border=0&gt;&lt;/a&gt;&lt;br&lt; <br>
&lt;a name=byRef href=http://www.CSLH.com &gt;&lt;img name=myIcon src=<?php echo $CSLH_Config['webpath']; ?>image.php?what=getcredit&department=<?php echo $recno; ?>&gt;&lt;/a&gt;<br>
&lt;!-- copyright 2003 - 2008 by Eric Gerdes --&gt;<br><br>
</b></td></tr></table><br><br>
<?php echo $lang['how_to_add2']; ?><br>
<table width=700 bgcolor=<?php echo $color_alt1;?> border=1>
<tr><td NOWRAP><br><br>
<b>
&lt;!-- Powered by: Crafty Syntax Live Help        http://www.craftysyntax.com/ --&gt;<br>
&lt;a href="<?php echo $CSLH_Config['webpath']; ?>livehelp.php?department=<?php echo $recno; ?>"&gt;&lt;/a&gt;<br>
&lt;!-- copyright 2003 - 2008 by Eric Gerdes --&gt;<br>
</b><br><br>
</td></tr></table><br><br><bR>
<?php echo $lang['how_to_add3']; ?>
<table width=700 bgcolor=<?php echo $color_alt1;?> border=1>
<tr><td NOWRAP><br><br>
<b>
&lt;!-- Powered by: Crafty Syntax Live Help        http://www.craftysyntax.com/ --&gt;<br>
&lt;script language="javascript" src="<?php echo $CSLH_Config['webpath']; ?>livehelp_js.php?what=hidden&department=<?php echo $recno; ?>"&gt;&lt;/script&gt;<br>
&lt;!-- copyright 2003 - 2008 by Eric Gerdes --&gt;<br>
</b><br><br>
</td></tr></table><br><br><bR>
<?php
 print "</td></tr>";
 }
}
 
if( ($isadminsetting == "Y") && (empty($UNTRUSTED['edit'])) ){
	print "<tr><td align=left><input type=submit value=\"".$lang['UPDATE']."\"></td></tr>";
 }
?>
</form></table>
<table bgcolor=DDDDDD width=600><tr><td>
&nbsp;
</b></td></tr></table>
<?php  if( ($isadminsetting == "Y") && (empty($UNTRUSTED['edit'])) ){ ?>
<font size=+1><a href=departments.php?createnew=yes><?php echo $lang['txt62']; ?></a></font>
<?php } ?>
<br>
<?php
if(!($serversession))
  $mydatabase->close_connect();
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