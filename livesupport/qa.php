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

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
$spacing = 1;

if(!(isset($UNTRUSTED['answeredit']))){ $UNTRUSTED['answeredit'] = 0; }
if(!(isset($UNTRUSTED['whatdo']))){ $UNTRUSTED['whatdo'] = ""; }
if(!(isset($UNTRUSTED['current']))){ $UNTRUSTED['current'] = 0; }
if(empty($UNTRUSTED['editit'])) $UNTRUSTED['editit'] = 0;

// create a folder
if(!(empty($UNTRUSTED['newfolder']))){
 $query = "INSERT INTO livehelp_qa (parent,question,typeof,status,ordernum) 
           VALUES (".intval($UNTRUSTED['current']).",'".filter_sql($UNTRUSTED['newfolder'])."','folder','A',0)";
 $mydatabase->query($query);	
}

// create a question
if(!(empty($UNTRUSTED['newquestion']))){
 $query = "INSERT INTO livehelp_qa (parent,question,typeof,status,ordernum) 
           VALUES (".intval($UNTRUSTED['current']).",'".filter_sql($UNTRUSTED['newquestion'])."','question','A',0)";
 $mydatabase->query($query);	
}

// update a question
if($UNTRUSTED['whatdo'] == "UPDATE"){
  if(isset($UNTRUSTED['nltobr'])){ $UNTRUSTED['question'] = nl2br($UNTRUSTED['question']); }
  if(!(isset($UNTRUSTED['newinsert']))){
  $query = "UPDATE livehelp_qa 
            SET question='".filter_sql($UNTRUSTED['question'])."' 
            WHERE recno=". intval($UNTRUSTED['recno']);
  $mydatabase->query($query);  
  } else {
   $query = "INSERT INTO livehelp_qa (parent,question,typeof) VALUES (".intval($UNTRUSTED['answer']).",'".filter_sql($UNTRUSTED['question'])."','answer')";
   $mydatabase->query($query);  
  }
}

// remove a question
if($UNTRUSTED['whatdo'] == "REMOVE"){
  $query = "DELETE FROM livehelp_qa WHERE recno=". intval($UNTRUSTED['recno']);
  $mydatabase->query($query);  
}

// re-order folders/questions.
if($UNTRUSTED['whatdo'] == "REORDER"){
  $query = "SELECT * from livehelp_qa";
  $myarray = $mydatabase->query($query); 
  while($row = $myarray->fetchRow(DB_FETCHMODE_ASSOC)){
     $lookingfor = "ordering__" . $row['recno'];
     if(isset($UNTRUSTED[$lookingfor])){
       $value = $UNTRUSTED[$lookingfor];
       $query = "UPDATE livehelp_qa 
                 SET ordernum='".filter_sql($value)."' 
                 WHERE recno=" . intval($row['recno']);
       $mydatabase->query($query); 
     }        
  }   
}


// get the depth and the path.. 
function depthof($id){
  global $mydatabase;	
  $pathto = array();
  while ($id != 0){     
     $query = "SELECT * FROM livehelp_qa WHERE recno=". intval($id);
     $children = $mydatabase->query($query); 
     $row = $children->fetchRow(DB_FETCHMODE_ASSOC);
     array_push ($pathto, $id);   
     $id = $row['parent'];
   } 	
  return $pathto;
}

function questions(){
  global $mydatabase,$UNTRUSTED,$lang,$spacing;
  $query = "SELECT * FROM livehelp_qa 
            WHERE parent=".intval($UNTRUSTED['current'])." 
              AND typeof='question' 
            ORDER BY ordernum,question";
  $children = $mydatabase->query($query);  

  while($row = $children->fetchRow(DB_FETCHMODE_ASSOC)){         	 
     print "<tr><td NOWRAP valign=top>";
     print "<img src=images/blank.gif width=$spacing height=2>";
     print "<img src=images/help_q.gif width=18 height=16>";    
     if(empty($UNTRUSTED['editit'])){
     	   $UNTRUSTED['editit'] = "";
     	   print "<input type=text size=3 name=ordering__" . $row['recno'] . " value=" . $row['ordernum'] . ">"; 
     }
     if($UNTRUSTED['editit'] != $row['recno']){
        print "<A href=qa.php?current=".$UNTRUSTED['current']."&answer=" . $row['recno'] . ">" . $row['question'] . "</a> [<a href=qa.php?current=".$UNTRUSTED['current']."&editit=" . $row['recno'] . ">Edit Question</a>] [<A href=qa.php?current=".$UNTRUSTED['current']."&answer=" . $row['recno'] . "&answeredit=1>Edit Answer</a>]";
     } else {
      print "<FORM action=qa.php method=post>";
      print "<input type=hidden name=current value=".$UNTRUSTED['current'].">";
      print "<input type=hidden name=recno value=".$UNTRUSTED['editit'].">";
      print "#<input type=text name=ordernum size=4 value=\"" . $row['ordernum'] . "\" size=3> <input type=text size=45 name=question value=\"" . $row['question'] . "\">";
      print "<input type=submit name=whatdo value=UPDATE>";
      print "<input type=submit name=whatdo value=REMOVE>";            
      print "</FORM>";
     }     
     print "</td></tr>";
  }
  if($children->numrows() == 0){
   print "<tr><td valign=top> ".$lang['user_choose']." </td></tr>";	
  }
}
function showlevels($current,$parent,$spaces){
  global $UNTRUSTED,$mydatabase,$mypath;
  	
	$query = "SELECT * 
            FROM livehelp_qa 
            WHERE parent=".intval($parent)." 
              AND typeof='folder' 
            ORDER by ordernum,question";
  $children = $mydatabase->query($query);  
  $spaces .= "|--";
  while($row = $children->fetchRow(DB_FETCHMODE_ASSOC)){
     print "<option value=".$row['recno'];
     if($current == $row['recno'])  
         print " SELECTED ";
     print ">$spaces".$row['question']."</option>\n";
     showlevels($current,$row['recno'],$spaces);
  }
}
function showtree($parent,$depth){
  global $UNTRUSTED,$mydatabase,$mypath;

  $query = "SELECT * 
            FROM livehelp_qa 
            WHERE parent=".intval($parent)." 
              AND typeof='folder' 
            ORDER by ordernum,question";
  $children = $mydatabase->query($query);  
  $spacing = ($depth * 10) + 1;
  while($row = $children->fetchRow(DB_FETCHMODE_ASSOC)){ 
    	   
     if( in_array($row['recno'],$mypath) ){ $opened = 1; } else { $opened = 0; }
         	 
     print "<tr><td NOWRAP valign=top>";
     if($UNTRUSTED['editit'] != $row['recno']){
     print "<img src=images/blank.gif width=$spacing height=2>";
     if(empty($UNTRUSTED['editit'])){
     	  print "<input type=text size=3 name=ordering__" . $row['recno'] . " value=" . $row['ordernum'] . ">";}
     if($opened == 0){ 
     	print "<img src=images/help_folder.gif width=18 height=16>"; 
     }
     if($opened == 1){ 
     	print "<img src=images/help_folder_open.gif width=18 height=16>"; 
     }     
     print "<A href=qa.php?current=" . $row['recno'] . ">" . $row['question'] . "</a> [<a href=qa.php?current=".$UNTRUSTED['current']."&editit=" . $row['recno'] . ">Edit</a>]";
     } else {
      print "<FORM action=qa.php method=post>";
      print "<input type=hidden name=current value=". $UNTRUSTED['current'] .">";
      print "<input type=hidden name=recno value=". $UNTRUSTED['editit'] .">";
      print "#<input type=text name=ordernum size=4 value=\"" . $row['ordernum'] . "\"> <input type=text name=question value=\"" . $row['question'] . "\">";
      print "<input type=submit name=whatdo value=UPDATE>";
      print "<input type=submit name=whatdo value=REMOVE>";            
      print "</FORM>";
     }
     print "</td></tr>";
     if( in_array($row['recno'],$mypath) ){
       showtree($row['recno'],$depth+1);       
     }
  }

}
?>
<body bgcolor=<?php echo $color_background;?>><center>
<?php
if(isset($UNTRUSTED['answer'])){
  $query = "SELECT * 
            FROM livehelp_qa 
            WHERE recno=" .intval($UNTRUSTED['answer']) ."
              AND typeof='question'";
  $question = $mydatabase->query($query); 
  $question = $question->fetchRow(DB_FETCHMODE_ASSOC);
  $query = "SELECT * 
            FROM livehelp_qa 
            WHERE parent=" .intval($UNTRUSTED['answer']) ." 
              AND typeof='answer'";
  $question_a = $mydatabase->query($query); 
  if($question_a->numrows() == 0){ $answeredit = 1; }
  $question_a = $question_a->fetchRow(DB_FETCHMODE_ASSOC);
?>
<table width=100%>
<tr bgcolor=<?php echo $color_alt2; ?>>
<td colspan=2 valign=top><b>Question:</b> <?php echo $question['question']; ?></b></td></tr>
<tr bgcolor=<?php echo $color_background;?>>
<td colspan=2 valign=top>
<img src=images/help_a.gif width=18 height=16><b><?php echo $lang['answer']; ?>:</b><br>
<table width=90%><tr><td bgcolor=<?php echo $color_alt1;?> valign=top>

<?php
if ($UNTRUSTED['answeredit'] == 1){ 
?>
<FORM ACTION=qa.php METHOD=POST>
<input type=hidden name=whatdo value=UPDATE>
<input type=hidden name=answer value=<?php echo $UNTRUSTED['answer']; ?> >
<input type=hidden name=current value=<?php echo $UNTRUSTED['current']; ?> >
<?php 
   if(empty($question_a['recno'])){ 
   	  print "<input type=hidden name=newinsert value=yes>\n"; 
   	  $defaultif = " CHECKED "; 
   } else { 
   	  $defaultif = ""; 
   } 
?>
<input type=hidden name=recno value=<?php echo $question_a['recno']; ?> >
<textarea cols=65 rows=9 name=question><?php echo htmlspecialchars($question_a['question']); ?></textarea><br>
<input type=checkbox name=nltobr value=YES <?php echo $defaultif; ?> >Convert new lines to breaks.<br>
<input type=submit value=UPDATE>
</FORM>
<?php
} else {
 print $question_a['question'];
 print "<br><a href=qa.php?current=".$UNTRUSTED['current']."&answer=".$UNTRUSTED['answer']."&answeredit=1>EDIT THIS</A><br><br><br>";
 print "<br><a href=qa.php?current=".$UNTRUSTED['current']."&answer=".$UNTRUSTED['answer']."&whatdo=REMOVE&recno=" . $question_a['recno'] . ">REMOVE THIS</A><br><br><br>"; 
}

?>

</td></tr></table>
<p><p>

<?php
  $query = "SELECT * 
            FROM livehelp_qa 
            WHERE parent=".intval($UNTRUSTED['answer'])." 
              AND typeof='comment'";
  $comments_a = $mydatabase->query($query); 
  while($comment = $comments_a->fetchRow(DB_FETCHMODE_ASSOC)){ 
  print "<p>";
  print $comment['question'];
 print "<br><a href=qa.php?current=".$UNTRUSTED['current']."&answer=".$UNTRUSTED['answer']."&whatdo=REMOVE&recno=" . $comment['recno'] . ">REMOVE THIS</A><br><br><br>"; 

}
?>
<p></td></tr>
</table>
<?php   	
}
?>
<table width=100%>
<tr bgcolor=<?php echo $color_alt2; ?>>
<td valign=top><b><?php echo $lang['help']; ?>:</b></td>
<td valign=top><b><?php echo $lang['help2']; ?>:</b></td>
</tr>
<tr><td valign=top>
<a href=qa.php>top</a>
<FORM action=qa.php method=POST>
<input type=hidden name=current value=<?php echo $UNTRUSTED['current']; ?> >
<table>
<?php 

$mypath = depthof($UNTRUSTED['current']);
showtree(0,1); ?>
</table>

</td><td valign=top>
<table>
<?php
questions(); 
?>
</table>
</td>
</tr></table>
</center>
<input type=submit value=REORDER name=whatdo></form>
<hr>
Create NEW:<br>
<form action=qa.php method=POST>
Level:<select name=current>
<option value=0>Top</option>
<?php
$parent=0;
$spaces=""; 
showlevels($UNTRUSTED['current'],$parent,$spaces);
?>
</select><br>
+<img src=images/help_folder.gif width=18 height=16> new folder: <input type=text name=newfolder size=15><input type=submit value=ADD><br>
+<img src=images/help_q.gif width=18 height=16> new Question: <input type=text name=newquestion size=45><input type=submit value=ADD><br>


<p><p>
 
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