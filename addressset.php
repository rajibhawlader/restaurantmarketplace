<?php
require_once('lib/class.Db.php');
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
ob_start();
session_start();

if($_REQUEST['add_id']==0)
{
	$sql="Select * from customers where customers_id='".$_SESSION['customers_id']."'";
	$result = $db->execQuery($sql);
	$adrsinfo = $db->resultArray($result);
	$_SESSION['customers_address1']=$adrsinfo[0]['customers_address1'];	
	$_SESSION['customers_address2']=$adrsinfo[0]['customers_address2'];	
	$_SESSION['customers_state']=$adrsinfo[0]['customers_state'];	
	$_SESSION['customers_town']=$adrsinfo[0]['customers_town'];	
	$_SESSION['customers_postcode']=$adrsinfo[0]['customers_postcode'];	
	$_SESSION['customers_country']=$adrsinfo[0]['customers_country'];	
}else
{
	$sql="Select * from customer_address where ca_id='".$_REQUEST['add_id']."'";
	$result = $db->execQuery($sql);
	$adrsinfo = $db->resultArray($result);
	$_SESSION['customers_address1']=$adrsinfo[0]['customers_address1'];	
	$_SESSION['customers_address2']=$adrsinfo[0]['customers_address2'];	
	$_SESSION['customers_state']=$adrsinfo[0]['customers_state'];	
	$_SESSION['customers_town']=$adrsinfo[0]['customers_town'];	
	$_SESSION['customers_postcode']=$adrsinfo[0]['customers_postcode'];	
	$_SESSION['customers_country']=$adrsinfo[0]['customers_country'];	
}
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
		<td align="left"><strong>County : </strong></td>
		<td align="left"><?php echo $_SESSION['customers_state']; ?></td>
	  </tr>
	  <tr>
		<td align="left"><strong>City/Town : </strong></td>
		<td align="left"><?php echo $_SESSION['customers_town']; ?></td>
	  </tr>
	  <tr>
		<td align="left"><strong>Postcode : </strong></td>
		<td align="left" style="text-transform:uppercase;"><?php echo $_SESSION['customers_postcode']; ?></td>
	  </tr>
	  <tr>
		<td align="left"><strong>Country : </strong></td>
		<td align="left"><?php echo $_SESSION['customers_country'] = ($_SESSION['customers_country'] !="")?$_SESSION['customers_country']:"United Kingdom"; ?></td>
	  </tr>
<?php
$customer_area = explode(" ", $_SESSION['customers_postcode']);
$query = "SELECT * FROM resturant   WHERE rid='".$_SESSION['rids']."' AND SUBSTRING(zipcode, 1,4) ='".$customer_area[0]."'";

if( !$db->checkExists($query) ){

	
	$query = "SELECT * FROM delivery_area WHERE rid='".$_SESSION['rids']."' AND SUBSTRING(post_code, 1,4) = '".$customer_area[0]."'";
	
	$result = $db->execQuery($query);
	$postinfo = $db->resultArray($result);
	if(sizeof($postinfo)>0)
	{
		$samepost=1;
	}
	else
	{
		$samepost=0;
	}
}else{
	$samepost=1;
}
?>	  
	  <tr>
		<td align="right"><span id="postcode_containter"><input type="hidden" name="samepost" id="samepost" value="<?=$samepost?>" /></span></td>
		<td align="left"></td>
	  </tr>
	  </table>
</div>
