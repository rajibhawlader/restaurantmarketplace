<?php
/*
$Id: Livehelp.php,v 1.0 2005/09/14 22:31:00 hpdl Exp $
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com
Copyright (c) 2003 osCommerce
Released under the GNU General Public License
*/
?>
<!-- Livehelp //-->
<tr>
<td>
<?php

$info_box_contents = array();
$info_box_contents[] = array('text' => BOX_HEADING_LIVEHELP);

new infoBoxHeading($info_box_contents, false, false);

$info_box_contents = array();
if (tep_session_is_registered('customer_id')) {
      $customer_query = tep_db_query("select customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
      $customer = tep_db_fetch_array($customer_query);

      $wo_full_name = $customer['customers_firstname'] . ' ' . $customer['customers_lastname'];

$info_box_contents[] = array('align' => 'center', 'text' => '<script language="javascript" src="/includes/livehelp/livehelp_js.php?relative=Y&amp;pingtimes=15&amp;username=' . $customer['customers_firstname'] . '"></script>' . '<br>' . BOX_LIVEHELP_TEXT . '<br>');
} else {
$info_box_contents[] = array('align' => 'center', 'text' => '<script language="javascript" src="/includes/livehelp/livehelp_js.php?relative=Y&amp;pingtimes=15"></script>' . '<br>' . BOX_LIVEHELP_TEXT . '<br>');
}
new infoBox($info_box_contents);
?>
</td>
</tr>
<!-- Livehelp_eof //-->

