<?php
/**
 *
 * Opportunities size by industry report.
 *
 * $Id: opportunities-size-by-industry.php,v 1.6 2005/01/03 06:37:19 ebullient Exp $
 */

require_once('../include-locations.inc');

require_once($include_directory . 'vars.php');
require_once($include_directory . 'utils-interface.php');
require_once($include_directory . 'utils-misc.php');
require_once($include_directory . 'utils-graph.php');
require_once($include_directory . 'adodb/adodb.inc.php');
require_once($include_directory . 'adodb-params.php');

$session_user_id = session_check();
$msg = $_GET['msg'];

$con = &adonewconnection($xrms_db_dbtype);
$con->connect($xrms_db_server, $xrms_db_username, $xrms_db_password, $xrms_db_dbname);

$sql1 = "select industry_id, industry_short_name from industries where industry_record_status = 'a'";
$rst1 = $con->execute($sql1);
$industry_count = $rst1->recordcount();
$graph_legend_array = array();
$array_of_total_values = array();
$array_of_total_weighted_values = array();
$total_opportunity_count = 0;

while (!$rst1->EOF) {
    
    $sql2 = "select sum(size*probability)/100 as total, sum(size) - sum(size*probability)/100 as weighted_total 
	from opportunities, companies 
    where opportunities.company_id = companies.company_id 
    and industry_id = " . $rst1->fields['industry_id'] . " 
	and opportunity_record_status = 'a'";
    $rst2 = $con->execute($sql2);
    
    if ($rst2) {
        $total = $rst2->fields['total'];
        $weighted_total = $rst2->fields['weighted_total'];
        $rst2->close();
    }
    
    if (!$total) {
        $total = 0;
    }
    
    if (!$weighted_total) {
        $weighted_total = 0;
    }
    
    
    $total_opportunity_count += $opportunity_count;
    array_push($array_of_total_values, $total);
    array_push($array_of_total_weighted_values, $weighted_total);
    array_push($graph_legend_array, "'" . $rst1->fields['industry_short_name'] . "'");
    $rst1->movenext();
    
}

$graph_rows .= "g.addRow(" . implode(',', $array_of_total_weighted_values) . ");\n";
$graph_rows .= "g.addRow(" . implode(',', $array_of_total_values) . ");\n";


$rst1->close();
$con->close();

$page_title = _("Opportunities (Size) by Industry");
start_page($page_title, true, $msg);

?>

<script language="javascript" src="../js/graph.js"></script>

<div id="Main">
    <div id="ContentFullWidth">

        <table class=widget cellspacing=1>
            <tr>
                <th class=widget_header><?php echo _("Opportunities (Size) by Industry"); ?></th>
            </tr>
            <tr>

                <td class=widget_content_graph>
                <SCRIPT LANGUAGE="JavaScript1.2">
                var g = new Graph(<?php  echo ($industry_count * 50); ?>,<?php echo $report_graph_height; ?>);
                <?php echo $graph_rows; ?>
                g.scale = <?php echo round(array_sum($array_of_total_values) / sizeof($array_of_total_values)) ?>;
                g.stacked = true;
                g.setXScaleValues(<?php  echo implode(',', $graph_legend_array); ?>);
				g.setLegend('<?php echo _("Remainder"); ?>', '<?php echo _("Weighted"); ?>');
                g.build();
                </SCRIPT>
                </td>

            </tr>

        </table>

    </div>

</div>

<?php

end_page();

/**
 * $Log: opportunities-size-by-industry.php,v $
 * Revision 1.6  2005/01/03 06:37:19  ebullient
 * update reports - graphs centered on page, reports surrounded by divs
 *
 * Revision 1.5  2004/07/20 18:36:58  introspectshun
 * - Localized strings for i18n/translation support
 *
 * Revision 1.4  2004/06/12 05:35:58  introspectshun
 * - Add adodb-params.php include for multi-db compatibility.
 * - Corrected order of arguments to implode() function.
 *
 * Revision 1.3  2004/04/17 15:57:03  maulani
 * - Add CSS2 positioning
 * - Add phpdoc
 *
 *
 */
?>
