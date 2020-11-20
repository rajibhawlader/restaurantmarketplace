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


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~    
/**
  * Shows the child links of a common url as <tr> rows.
  *
  * @param int $recno the recno of the parent 
  * @param int $spacer the amount of space to indent.
  *
  * @global object $mydatabase mysql database object.
  * @global array  $UNTRUSTED  array of user inputed variables.  
  */
function showchildrenof($recno,$spacer,$whatYm,$expand_array,$tablename,$typeof="refer",$parentstring="",$urlsofar=""){
  global $UNTRUSTED,$lang,$bgcolor,$color_background,$mydatabase,$color_alt2,$color_alt1;

 $query = "SELECT count(*) as totalrows FROM $tablename WHERE parentrec=".intval($recno)." AND dateof=".intval($whatYm) ." ORDER by levelvisits DESC";
 $sth = $mydatabase->query($query);
 $row = $sth->fetchRow(DB_FETCHMODE_ASSOC);
 $num_rows = $row['totalrows'];  
 $bgcolor=$color_alt2; 
 $lastfive  = true;
 $leveltop = "p" . $recno;
 if(empty($UNTRUSTED[$leveltop])) 
   $UNTRUSTED[$leveltop] = 0;
 
 $nextparentsstring = $parentstring . "&$leveltop=".$UNTRUSTED[$leveltop];
    
 print "<tr bgcolor=#dedede><td>&nbsp;</td><td colspan=4>";
 $pageUrl = "data.php";
 $perPage = 25;
 $varstring = "&tab=".$UNTRUSTED['tab']."&typeofview=levelvisits&show=".$UNTRUSTED['show']."&month=".$UNTRUSTED['month']."&year=".$UNTRUSTED['year']."&expand=".$UNTRUSTED['expand'].$parentstring;
 print pagingLinks($pageUrl, $num_rows, $varstring, $UNTRUSTED[$leveltop], $perPage,$leveltop);
 print "</td></tr>"; 	

 $query = "SELECT * FROM $tablename WHERE parentrec=".intval($recno)." AND dateof=".intval($whatYm) ." ORDER by levelvisits DESC LIMIT $UNTRUSTED[$leveltop],$perPage";
 $sth = $mydatabase->query($query);  
 while($row = $sth->fetchRow(DB_FETCHMODE_ASSOC)){
  $contract = $UNTRUSTED['expand'];
  $reg1 = "," . $row['recno'] . "\$";
  $contract = ereg_replace($reg1,"",$contract);
  $reg2 = "," . $row['recno'] . ",";
  $contract = ereg_replace($reg2,"",$contract);
  $reg3 = "^" . $row['recno'] . ",";
  $contract = ereg_replace($reg3,"",$contract);
  if(in_array($row['recno'],$expand_array))
   $bgcolor="#CED9FA";
  
  if ($urlsofar != "")
      $displaytxt = str_replace($urlsofar,"",$row['pageurl']);
  else 
      $displaytxt = $row['pageurl'];
    
  if($row['levelvisits'] == 0){
  	if($lastfive){
  	  print "<tr bgcolor=$bgcolor><td>&nbsp;</td><td colspan=3>Last five Query Strings for this refering page:</td></tr>";
      $lastfive=false;  
  	}  	
    print "\n<tr bgcolor=$bgcolor><td>";   
    print "&nbsp;";                   
    print "</td><td NOWRAP><img src=images/blank.gif width=$spacer height=10 border=0><a href=" . str_replace(" ","+",$row['pageurl']) . " target=_blank>" . $displaytxt . "</a></td><td colspan=2> -   </td></tr>\n\n";
  } else {	  	 	
  print "\n<tr bgcolor=$bgcolor><td>";  
  if(!(in_array($row['recno'],$expand_array))){
   print "<a href=data.php?&tab=".$UNTRUSTED['tab']."&month=".$UNTRUSTED['month']."&year=".$UNTRUSTED['year']."&typeofview=levelvisits&$leveltop=".$UNTRUSTED[$leveltop]."&show=".$UNTRUSTED['show']."&expand=" . $UNTRUSTED['expand'] . "," . $row['recno'] . $parentstring . "><img src=images/plus.gif border=0></a>";
  } else {
  	 $contract = $UNTRUSTED['expand'];
  	 $reg1 = "," . $row['recno'] . "\$";
  	 $contract = ereg_replace($reg1,"",$contract);
  	 $reg2 = "," . $row['recno'] . ",";
  	 $contract = ereg_replace($reg2,"",$contract);
  	 $reg3 = "^" . $row['recno'] . ",";
  	 $contract = ereg_replace($reg3,"",$contract);
    print "<a href=data.php?tab=".$UNTRUSTED['tab']."&typeofview=levelvisits&$leveltop=".$UNTRUSTED[$leveltop]."&show=".$UNTRUSTED['show']."&expand=$contract".$parentstring."><img src=images/minus.gif border=0></a>";
   }  
   print "</td><td NOWRAP><img src=images/blank.gif width=$spacer height=10 border=0><a href=" . str_replace(" ","+",$row['pageurl']) . " target=_blank>" . substr($displaytxt,0,100) . "</a> <font color=#999999>(#".$row['levelvisits'].")</font></td><td>" . $row['levelvisits'] . " </td><td>" . $row['directvisits'] . " </td><td NOWRAP> <a href=graph.php?item=" . $row['recno'] . "&type=".$typeof."&typeof=levelvisits target=_blank>" . $lang['graph'] . "</a></td></tr>\n\n";

   if(in_array($row['recno'],$expand_array)){
    $spacer2 = $spacer + 10;
    ?><tr><td>&nbsp;</td><td colspan=4><table width=100%><?php
    showchildrenof($row['recno'],$spacer2,$whatYm,$expand_array,$tablename,$typeof,$nextparentsstring,$row['pageurl']);
    print "<tr bgcolor=#000000><td colspan=5><img scr=images/blank.gif width=1 height=1></td></tr>";
    print "<tr ><td colspan=5><img scr=images/blank.gif width=1 height=10></td></tr>";      
    ?></table></td></tr><?php
   }
   if($bgcolor==$color_alt2){$bgcolor=$color_alt1; } else { $bgcolor=$color_alt2; }

  }
 }
}


/**
  * function pagingLinks($pageUrl, $num_rows, $varstring, $offset, $perPage, $showCount=1)
  *
  * @access public
  * @param  pageUrl - the page we link to
  * @param  num_rows  - how far from the first result
  * @param  varstring  - all search vars, in the format &var1=$var1&var2=$var2
  * @param  offset  - how far from the first result
  * @param  perPage  - rows to print per page
  * @param  showCount  - 1 to show count of results/pages; set to zero to hide this row     
  * @return string  - html of the paging.
  */

function pagingLinks($pageUrl, $num_rows, $varstring, $offset, $perPage, $offsetname="offset"){
  $showCount=0;
  $maxPageLinks = 20; //set max number of page links to show at one time; should be even integer
  $halfOfPages = $maxPageLinks/2; //number of page links to display to right of current page
  $smallHalfOfPages = $halfOfPages -1; //number of page links to display to left of current page

  // Get total pages
  $num_pages = intval($num_rows / $perPage);
  if ($num_rows % $perPage) {
     $num_pages++;
  }

  //what page are we on?
  $currentPage = ($offset/$perPage) + 1;

  //determine page links to display
  if($currentPage >= 1 && $currentPage <= ($halfOfPages + 1)){
    $firstPageLink = 1;
    if($num_pages < $maxPageLinks)
       $lastPageLink = $num_pages;
    else
       $lastPageLink = $maxPageLinks;  
 
    //$lastPageLink = $currentPage + $smallHalfOfPages;  //theoretical last page link
    //if($lastPageLink > $num_pages)  //how many pages do we actually have?
    //  $lastPageLink = $num_pages;
  }
  elseif($currentPage > ($halfOfPages + 1)){
    $firstPageLink = $currentPage - $halfOfPages;
    $lastPageLink = $currentPage + $smallHalfOfPages;
    if($lastPageLink > $num_pages)
      $lastPageLink = $num_pages;
  }
  else{
    $firstPageLink = $currentPage;
    if($num_pages >$halfOfPages)
      $lastPageLink = $halfOfPages;
    else
      $lastPageLink = $num_pages;
  }
   
 
  if(!empty($showCount)){
    $pagingCode = "<table cellpadding=2 cellspacing=0 border=0>\n";
    $pagingCode .= "<tr>
                    <td colspan=3>
                    <font face=arial size=2>" . number_format($num_rows) . " results ";
    if($num_pages > 1)
      $pagingCode .= " in $num_pages pages";
    $pagingCode .= "</font></td>
                   </tr>";
  }
  elseif(empty($showCount) && $num_pages > 1){
    $pagingCode = "<table cellpadding=2 cellspacing=0 border=0>\n";
  }
  else{ //note that if $showCount = 0 and $num_pages ==1, nothing but an nbsp; will be printed
    $pagingCode ="";
  }
 
  if($num_pages > 1){ //don't print anything else if there is only one page of results
  $pagingCode .= "<tr>
  <td valign=bottom>\n<font face=arial size=2>\n";

  // Check to see if we need to have previous button; if so, print it
  if ($offset >= $perPage) {
    $prevoffset=$offset-$perPage;
    $pagingCode .= "<a href=" . $pageUrl . "?$offsetname=" . $prevoffset . $varstring;
    $pagingCode .= ">PREV</a>&nbsp;\n";
  }
  else{
    $pagingCode .= "";
  }

  $pagingCode .= "</font></td>\n<td align=center>\n<font face=arial size=2>\n";


   if ($num_pages != 1){
     $pagingCode .= "|&nbsp;";
     for ($i=$firstPageLink;$i<=$lastPageLink;$i++) {
        if (($offset < $i*$perPage) && ($offset >= ($i-1)*$perPage)) {
           $pagenumber = "<font color=000000 size=+1><b>$i</b></font>";
        }
        else
           $pagenumber = $i;
        $newoffset = $perPage * ($i-1);
        $pagingCode .= "<a href=" . $pageUrl . "?$offsetname=" . $newoffset . $varstring;
        $pagingCode .= ">$pagenumber</a>&nbsp;|&nbsp;";
     }
   }
   else{
    $pagingCode .= "";
   }

   $pagingCode .= "</font>\n</td>\n<td valign=bottom>\n<font face=arial size=2>\n";

   // Check to see if we need to have a next button; if so, print it
   if (($offset+$perPage < $num_rows) && $num_pages != 1) {
      $newoffset = $offset + $perPage;
      $pagingCode .= "<a href=" . $pageUrl . "?$offsetname=" . $newoffset . $varstring;
      $pagingCode .=  ">NEXT</A>\n";
   }
   else{
    $pagingCode .= "";
   }

   $pagingCode .= "</font></td>\n</tr>\n";

   } //end if($num_pages > 1)



   if(!empty($showCount)){
     $pagingCode .= "</table>\n";
   }
   elseif(empty($showCount) && $num_pages > 1){
     $pagingCode .= "</table>\n";
   }

   return $pagingCode;
}

function listoperators($list){
	global $mydatabase;
	 
	 $mylist = "";
	 
	 $myarray = explode(",",str_replace("X","",$list));
	 for($i=0;$i<count($myarray);$i++){
	 	   $query = "SELECT username FROM livehelp_users WHERE user_id=".intval($myarray[$i]);
       $sth = $mydatabase->query($query);  
       $row = $sth->fetchRow(DB_FETCHMODE_ASSOC);
       if($i!=0) $mylist .= "<br>";
       $mylist .= $row['username'];
	 }
  
  return $mylist;
}

?>