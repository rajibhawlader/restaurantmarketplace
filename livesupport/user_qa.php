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
require_once("visitor_common.php");
  
// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");
sleep(1);

if(empty($UNTRUSTED['tab'])){ $UNTRUSTED['tab'] = ""; }
 
  
$query = "UPDATE livehelp_users set status='qna' WHERE sessionid='".$identity['SESSIONID']."'";	
$mydatabase->query($query);

// get the depth and the path.. 
function depthof($id){
  global $mydatabase,$department;	
  $pathto = array();
  while ($id != 0){     
     $query = "SELECT * FROM livehelp_qa WHERE recno=".intval($id);
     $children = $mydatabase->query($query); 
     $row = $children->fetchRow(DB_FETCHMODE_ASSOC);
     array_push ($pathto, $id);   
     $id = $row['parent'];
   } 	
  return $pathto;
}

function questions($current){
  global $lang,$spacing,$mydatabase,$UNTRUSTED,$department;
  $query = "SELECT * 
            FROM livehelp_qa 
            WHERE parent=".intval($current)." 
              AND typeof='question' 
            ORDER BY ordernum,question";
  $children = $mydatabase->query($query);  
  if(empty($spacing)){ $spacing = 1;}
  while($row = $children->fetchRow(DB_FETCHMODE_ASSOC)){     	 
     print "<tr><td NOWRAP valign=top>";
     print "<img src=images/blank.gif width=$spacing height=2>";
     print "<img src=images/help_q.gif width=18 height=16>";    
     print "<A href=user_qa.php?department=$department&current=$current&answer=".$row['recno'].">".$row['question']."</a>";    
     print "</td></tr>";
  }
  if( $children->numrows() == 0){
   print "<tr><td valign=top>".$lang['user_choose']."</td></tr>";	
  }
}

function showtree($parent,$depth){
  global $UNTRUSTED,$mydatabase,$mypath,$department;

  $query = "SELECT * 
            FROM livehelp_qa 
            WHERE parent=".intval($parent)."
              AND typeof='folder' 
            ORDER by ordernum,question";
  $children = $mydatabase->query($query);  
  $spacing = ($depth * 10) + 1;
  $children = $mydatabase->query($query);  
  while($row = $children->fetchRow(DB_FETCHMODE_ASSOC)){     
     
     if( in_array($row['recno'],$mypath) ){ $opened = 1; } else { $opened = 0; }
         	 
     print "<tr><td NOWRAP valign=top>";

     print "<img src=images/blank.gif width=$spacing height=2>";
     if($opened == 0){ 
     	print "<img src=images/help_folder.gif width=18 height=16>"; 
     }
     if($opened == 1){ 
     	print "<img src=images/help_folder_open.gif width=18 height=16>"; 
     }     
     print "<A href=user_qa.php?department=$department&current=".$row['recno'].">".$row['question']."</a>";
    
     print "</td></tr>";
     if( in_array($row['recno'],$mypath) ){
       showtree($row['recno'],$depth+1);       
     }
  }

}
?>
<body bgcolor=<?php echo $color_background;?> >
<br>
 
<center>
<?php
if(!(empty($UNTRUSTED['answer']))){
  $query = "SELECT * 
            FROM livehelp_qa 
            WHERE recno=".intval($UNTRUSTED['answer'])." 
             AND typeof='question'";
  $question = $mydatabase->query($query); 
  $question = $question->fetchRow(DB_FETCHMODE_ASSOC);
  $query = "SELECT * 
            FROM livehelp_qa 
            WHERE parent=".intval($UNTRUSTED['answer'])."
              AND typeof='answer'";
  $question_a = $mydatabase->query($query); 
  $question_a = $question_a->fetchRow(DB_FETCHMODE_ASSOC);
?>
<table width=100%>
<tr bgcolor=<?php echo $color_alt2; ?>>
<td colspan=2 valign=top><b><?php echo $lang['question']; ?>:</b> <?php echo $question['question']; ?></b></td></tr>
<tr bgcolor=<?php echo $color_background;?>>
<td colspan=2 valign=top>
<img src=images/help_a.gif width=18 height=16><b><?php echo $lang['answer']; ?>:</b><br>
<table width=90%><tr><td bgcolor=<?php echo $color_alt1;?> valign=top>

<?php
 print $question_a['question'];
?>

</td></tr></table>
<p><p>

<?php
  $query = "SELECT * FROM livehelp_qa WHERE parent=".intval($UNTRUSTED['answer'])." AND typeof='comment'";
  $comments_a = $mydatabase->query($query); 
  while($comment = $comments_a->fetchRow(DB_FETCHMODE_ASSOC)){ 
 
  print "<p>";
  print $comment['question'];
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
<a href=user_qa.php?department=<?php echo $department; ?>>top</a>
<table>
<?php 
if(empty($UNTRUSTED['current'])){ $current = 0; } else { $current =$UNTRUSTED['current']; }
$mypath = depthof($current);
showtree(0,1); ?>
</table>

</td><td valign=top>
<table>
<?php
questions($current); 
?>
</table>
</td>
</tr></table>
</center>
<p><p>
<?php
if(!($serversession))
  $mydatabase->close_connect();
?>
<pre>


</pre>
<font size=-2 color=DDDDDD>
<!-- Note if you remove this line you will be violating the license even if you have modified the program -->
Crafty Syntax Live Help &copy; 2003 - 2008 by Eric Gerdes  
<br>
CSLH is  Software released 
under the GNU/GPL license
</font>