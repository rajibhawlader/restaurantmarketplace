<?php
/*
V3.40 7 April 2003  (c) 2000-2003 John Lim (jlim@natsoft.com.my). All rights reserved.
  Released under both BSD license and Lesser GPL library license.
  Whenever there is any discrepancy between the two licenses,
  the BSD license will take precedence.

  Some pretty-printing by Chris Oxenreider <oxenreid@state.net>
*/

// specific code for tohtml
GLOBAL $gSQLMaxRows,$gSQLBlockRows;

$gSQLMaxRows   = 1000; // max no of rows to download
$gSQLBlockRows = 20; // max no of rows per table block

// RecordSet to HTML Table
//------------------------------------------------------------
// Convert a recordset to a html table. Multiple tables are generated
// if the number of rows is > $gSQLBlockRows. This is because
// web browsers normally require the whole table to be downloaded
// before it can be rendered, so we break the output into several
// smaller faster rendering tables.
//
// $rs: the recordset
// $ztabhtml: the table tag attributes (optional)
// $zheaderarray: contains the replacement strings for the headers (optional)
//
//  USAGE:
//  include('adodb.inc.php');
//  $db = ADONewConnection('mysql');
//  $db->Connect('mysql','userid','password','database');
//  $rs = $db->Execute('select col1,col2,col3 from table');
//  rs2html($rs, 'BORDER=2', array('Title1', 'Title2', 'Title3'));
//  $rs->Close();
//
// RETURNS: number of rows displayed
// *** added parameters selected_column and selected_column_html to indicate which column is sorted and how
function rs2html(&$rs,$ztabhtml=false,$zheaderarray=false,$htmlspecialchars=true,$selected_column=1,$selected_column_html='****') {

    $s ='';
    $rows=0;
    $docnt = false;
    GLOBAL $gSQLMaxRows,$gSQLBlockRows;

    if (!$rs) {
        printf(ADODB_BAD_RS,'rs2html');
        return false;
    }

    // *** got rid of ztabhtml attributes here:
    if (! $ztabhtml) $ztabhtml = "";
    //else $docnt = true;
    $typearr = array();
    $ncols = $rs->FieldCount();
    // *** commented out the line below b/c we don't want *another* freakin' table -- we just want rows
    // $hdr = "<TABLE COLS=$ncols $ztabhtml>\n\n";
    // made it 'ncols - 1' so that you can't sort by the delete button
    $hdr = '';
    for ($i=0; $i < $ncols; $i++) {
        $field = $rs->FetchField($i);
        if ($zheaderarray) $fname = $zheaderarray[$i];
        else $fname = htmlspecialchars($field->name);
        $typearr[$i] = $rs->MetaType($field->type,$field->max_length);
        //print " $field->name $field->type $typearr[$i] ";
        // no &nbsp; here... we don't want the link visible
        if (strlen($fname)==0) $fname = '';
        // *** and here below we just want stylized <td> elements, not <th>'s
        // *** also we need to make these headers re-sort the results if asked
        $hdr .= "<td class=widget_label><a href='javascript: resort($i);'>$fname</a>";

        if ($i == $selected_column) {
            $hdr .= $selected_column_html;
        }

        $hdr .= "</td>";
    }

    // *** added <tr> and </tr> tags around $hdr
    print "<tr>" . $hdr . "</tr>\n\n";
    // smart algorithm - handles ADODB_FETCH_MODE's correctly!
    $numoffset = isset($rs->fields[0]);
    // added this for colors
    $color_counter = 0;

    while (!$rs->EOF) {

        $color_counter++;
        $classname = (($color_counter % 2) == 1) ? "widget_content" : "widget_content_alt";

        $s .= "<tr valign=top>\n";

        for ($i=0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields);
            $i < $ncols;
            $i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {

            $type = $typearr[$i];

            $s .= "<td class=$classname>" . $v . "</td>\n";

            // *** for each of these types we want stylized <td> elements
            /*
            switch($type) {
            case 'T':
                $s .= " <td class=$classname>" . $rs->UserTimeStamp($v,"Y-M-d") . "&nbsp;</td>\n";
            break;
            case 'D':
                $s .= " <td class=$classname>" . $rs->UserDate($v,"D d, M Y") . "&nbsp;</td>\n";
            break;
            case 'I':
                $s .= " <td class=$classname>" . stripslashes((trim($v))) . "&nbsp;</TD>\n";
                break;
            case 'N':
                if ($i == 8) {
                    $s .= " <td class=$classname>$" . number_format(stripslashes((trim($v))), 2) . "&nbsp;</TD>\n";
                } else {
                    $s .= " <td class=$classname>" . stripslashes((trim($v))) . "&nbsp;</TD>\n";
                }
            break;
            default:
                if ($htmlspecialchars) $v = htmlspecialchars($v);
                // *** good one $s .= " <td class=$classname>". str_replace("\n",'<br>',stripslashes((trim($v)))) ."&nbsp;</TD>\n";
                $s .= " <td class=$classname>". stripslashes((trim($v))) ."&nbsp;</TD>\n";
                break;
            } // switch
            */

            // print "<li>$v - $i - $type - $numoffset - $color_counter</li>";

        } // for
        $s .= "</tr>\n\n";

        $rows += 1;
        if ($rows >= $gSQLMaxRows) {
            $rows = "<p>Truncated at $gSQLMaxRows</p>";
            break;
        } // switch

        $rs->MoveNext();

        // additional EOF check to prevent a widow header
        if (!$rs->EOF && $rows % $gSQLBlockRows == 0) {
            //if (connection_aborted()) break;// not needed as PHP aborts script, unlike ASP
            echo $s . "\n\n";
            $s = $hdr;
        }
    } // end while

    if (strlen($s) == 0) {
        $s = "<tr><td colspan=13 class=widget_content>"._("No matches")."</td></tr>";
    }
    // if ($docnt) print "<H2>".$rows." Rows</H2>";

    echo $s."\n\n";
    return $rows;
 }

// pass in 2 dimensional array
function arr2html(&$arr,$ztabhtml='',$zheaderarray='')
{
    if (!$ztabhtml) $ztabhtml = 'BORDER=1';

    $s = "<TABLE $ztabhtml>";//';print_r($arr);

    if ($zheaderarray) {
        $s .= '<TR>';
        for ($i=0; $i<sizeof($zheaderarray); $i++) {
            $s .= " <TH>{$zheaderarray[$i]}</TH>\n";
        }
        $s .= "\n</TR>";
    }

    for ($i=0; $i<sizeof($arr); $i++) {
        $s .= '<TR>';
        $a = &$arr[$i];
        if (is_array($a))
            for ($j=0; $j<sizeof($a); $j++) {
                $val = $a[$j];
                if (empty($val)) $val = '&nbsp;';
                $s .= " <TD>$val</TD>\n";
            }
        else if ($a) {
            $s .=  '    <TD>'.$a."</TD>\n";
        } else $s .= "  <TD>&nbsp;</TD>\n";
        $s .= "\n</TR>\n";
    }
    $s .= '</TABLE>';
    echo $s;
}
?>