<?
require_once('lib/class.Db.php');
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
ob_start();
session_start();

if($_REQUEST['adlebel']!="")
{
	$query = "INSERT INTO  customer_address( `ca_id` , `customers_id` , `customers_address1` , `customers_address2` , `customers_town` , `customers_state` , `customers_country` , `customers_postcode` , `customers_add_label` ) VALUES ('', '".$_SESSION['customers_id']."', '".mysql_escape_string($_REQUEST["ad1"])."', '".mysql_escape_string($_REQUEST["ad2"])."', '".mysql_escape_string($_REQUEST["town"])."', '".mysql_escape_string($_REQUEST["state"])."', '".mysql_escape_string($_REQUEST["country"])."', '".mysql_escape_string($_REQUEST["post"])."', '".mysql_escape_string($_REQUEST["adlebel"])."')";
	$result = $db->execQuery($query);
	$ca_id=$db->lastInsert($result);
}
	$sql="Select * from customer_address where ca_id='".$ca_id."'";
	$result = $db->execQuery($sql);
	$adrsinfo = $db->resultArray($result);
	$_SESSION['customers_address1']=$adrsinfo[0]['customers_address1'];	
	$_SESSION['customers_address2']=$adrsinfo[0]['customers_address2'];	
	$_SESSION['customers_state']=$adrsinfo[0]['customers_state'];	
	$_SESSION['customers_town']=$adrsinfo[0]['customers_town'];	
	$_SESSION['customers_postcode']=$adrsinfo[0]['customers_postcode'];	
	$_SESSION['customers_country']=$adrsinfo[0]['customers_country'];	
?>
<div id="test">
	<table width="90%" border="0" cellpadding="1" cellspacing="1" class="normal_text">	
	  <tr>
		<td width="28%" align="left"><strong>Address1 : </strong></td>
		<td width="72%" align="left"><?php echo $_SESSION['customers_address1']; ?></td>
	  </tr>
	  <tr>
		<td align="left"><strong>Address2 : </strong></td>
		<td align="left"><?php echo $_SESSION['customers_address2']; ?></td>
	  </tr>
	  <tr>
		<td align="left"><strong>State : </strong></td>
		<td align="left"><?php echo $_SESSION['customers_state']; ?></td>
	  </tr>
	  <tr>
		<td align="left"><strong>City : </strong></td>
		<td align="left"><?php echo $_SESSION['customers_town']; ?></td>
	  </tr>
	  <tr>
		<td align="left"><strong>Postcode : </strong></td>
		<td align="left"><?php echo $_SESSION['customers_postcode']; ?></td>
	  </tr>
	  <tr>
		<td align="left"><strong>Country : </strong></td>
		<td align="left"><?php echo $_SESSION['customers_country']; ?></td>
	  </tr>
	  <tr>
		<td align="right">&nbsp;</td>
		<td align="left"></td>
	  </tr>
	  </table>
</div>
