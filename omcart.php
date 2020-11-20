<?php
require_once('lib/class.Db.php');	
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
require_once('lib/functions.php');
ob_start();
session_start();

//print_r($_REQUEST);
//$_SESSION['qty']
if($_REQUEST['idp']!="" and $_REQUEST['qt']==""){
for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) {
if($_REQUEST['idp']==$_SESSION['cart'][$i])
{
$_SESSION['qty'][$i]=$_SESSION['qty'][$i]+1;
$dup=1;
}
}
if($dup=="")
{
$j=sizeof($_SESSION['cart']);
$_SESSION['cart'][$j]=$_REQUEST['idp'];
$_SESSION['qty'][$j]=1;
}
}else if($_REQUEST['idp']!="" and $_REQUEST['qt']=="m")
{
for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) {
if($_REQUEST['idp']==$_SESSION['cart'][$i])
{
$_SESSION['qty'][$i]=$_SESSION['qty'][$i]-1;
if($_SESSION['qty'][$i]==0)
{
$_SESSION['qty'][$i]=1;
}
}
}
}else if($_REQUEST['idp']!="" and $_REQUEST['qt']=="y")
{
if(sizeof($_SESSION[cart])>1){
$d=0;
for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) {
if($_SESSION[cart][$d]==$_REQUEST['idp'])
{
$d=$d+1;
}
if($d!=sizeof($_SESSION[cart]))
{
$_SESSION[carttemp][$i]=$_SESSION[cart][$d];
$_SESSION['qtytemp'][$i]=$_SESSION['qty'][$d];
$d=$d+1;
}
}
unset($_SESSION['cart']);
unset($_SESSION['qty']);
	$_SESSION['cart']=$_SESSION[carttemp];
	$_SESSION['qty']=$_SESSION['qtytemp'];
	unset($_SESSION['carttemp']);
	unset($_SESSION['qtytemp']);
	}else
	{
	unset($_SESSION['cart']);
unset($_SESSION['qty']);
	}
}

?>
<table width="92%" border="0" cellpadding="0" cellspacing="0" class="bodytext">
  <tr>
    <td height="24" bgcolor="#F0F0F0"><strong>&nbsp;&nbsp;Item </strong></td>
    <td width="24%" align="center" bgcolor="#F0F0F0"><strong>Quantity</strong></td>
    <td width="19%" bgcolor="#F0F0F0"><strong>Unique Price </strong></td>
    <td colspan="2" align="right" bgcolor="#F0F0F0"><strong>Sub Total </strong>&nbsp;</td>
  </tr>
  <? if($_SESSION[cart]!=""){ 
  $totalpr=0;
  for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) {
$pids=$_SESSION['cart'][$i];
$qtys=$_SESSION['qty'][$i];
$sqlpro="Select * from product where product_id='$pids'";
$resultpro=mysql_query($sqlpro);
$rowp=mysql_fetch_array($resultpro);
if($rowp['parent_id']!=0)
{
	$prdo=$rowp['parent_id'];
	$sqlprop="Select * from product where product_id='$prdo'";
	$resultprop=mysql_query($sqlprop);
	$rowpp=mysql_fetch_array($resultprop);
	
}
else{

$rowpp["product_title"]='';
}

?>
  <tr>
    <td width="36%" height="25" valign="middle" bgcolor="#FFFFFF">&nbsp;&nbsp;<? echo $rowp["product_title"].'&nbsp;'.$rowpp["product_title"]; ?></td>
    <td align="center" valign="middle" bgcolor="#FFFFFF"><? echo $qtys; ?></td>
    <td valign="middle" bgcolor="#FFFFFF"><? $mprice=$rowp["price"];
	// echo number_format($mprice, 2, '.', '');
	echo number_format($rowp["price"] - discount_price($_SESSION['rids'], $rowp["categories_id"], $_SESSION['order_type'], $rowp["price"]), 2, '.', '');
	?></td>
    <td colspan="2" align="right" valign="middle" bgcolor="#FFFFFF">&pound;&nbsp;
    <?php
	 $item_price = ($rowp["price"] - discount_price($_SESSION['rids'], $rowp["categories_id"], $_SESSION['order_type'], $rowp["price"]));
	 $sbtotal= $item_price*$qtys;
	 // $sbtotal= $rowp["price"]*$qtys;
	 $totalpr=$totalpr+$sbtotal;
	$_SESSION['ttpric']=$totalpr;
	 echo number_format($sbtotal, 2, '.', '');?> &nbsp;</td>
  </tr>
<?php }
   $_SESSION['del_cost']=0;
   $_SESSION['hfee']=0;
   $_SESSION['ccfee']=0;
   
	$query7 = "SELECT * FROM delevary_policy WHERE rid='".$_SESSION['rids']."'";
	$result7 = $db->execQuery($query7);
	$dev_pol = $db->resultArray($result7);
   
	$query8 = "SELECT * FROM resturant_service WHERE rid='".$_SESSION['rids']."'";
	$result8 = $db->execQuery($query8);
	$res_ser = $db->resultArray($result8);
	$hfee="";
	if($res_ser[0]['hfee']!=0 && $res_ser[0]['hadeling_pay']==2)
  	{
  		$hfee=$res_ser[0]['hfee'];
		$_SESSION['hfee']=$hfee;
		$totalpr=$totalpr+$hfee;
?>
  
  <tr>
    <td align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><strong>Handling Fees:&nbsp;</strong></td>
    <td align="right" bgcolor="#FFFFFF">&pound;&nbsp;<? echo number_format($hfee, 2, '.', ''); ?> &nbsp;</td>
  </tr>
  <?php } ?>
  <?php
    if($_SESSION['order_type']=="1") { 
	if($_SESSION['ttpric']>=$dev_pol[0]['min_order'])
	{
	$dcost=$dev_pol[0]['del_cost'];
	$_SESSION['del_cost']=$dcost;
	$totalpr=$totalpr+$dcost;
  ?>
  <tr>
    <td align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><strong>Delivery Cost:&nbsp;</strong></td>
    <td align="right" bgcolor="#FFFFFF">&pound;&nbsp;<? echo number_format($dcost, 2, '.', ''); ?> &nbsp;</td>
  </tr>
  <?php } } ?>
  
  <?php 
	if($_SESSION['pay_mathod']=="Online")
	{ 
		if($res_ser[0]['ccfee']!=0 && $res_ser[0]['cc_pay']==2)
		{
			$_SESSION['ccfee']=$res_ser[0]['ccfee'];
			$ftprice=$totalpr+$res_ser[0]['ccfee'];
?>
  <tr>
    <td align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" bgcolor="#FFFFFF"><strong>Credit Card Fee:&nbsp;</strong></td>
    <td align="right" bgcolor="#FFFFFF">&pound;&nbsp;<? echo number_format($res_ser[0]['ccfee'], 2, '.', ''); ?> &nbsp;</td>
  </tr>
<?php
		}else
		{
			$ftprice=$totalpr;
		}
	}else
	{
	 	$ftprice=$totalpr;
	}
?>

  <tr>
    <td colspan="5" align="left" valign="middle" bgcolor="#006666" height="1"></td>
  </tr>
  <tr>
    <td align="left" valign="middle" bgcolor="#FFFFFF">[<a href="index-action-mycart" class="nav">Edit</a>]</td>
    <td align="right" valign="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="right" valign="top" bgcolor="#FFFFFF"><strong>Total:</strong></td>
    <td colspan="2" align="right" valign="top" bgcolor="#FFFFFF"><strong>&pound;&nbsp;
	<?php
	$_SESSION['gtotal']=$ftprice;
	echo number_format($ftprice, 2, '.', ''); ?></strong> &nbsp;</td>
  </tr>
  <? }else{?>
  <tr>
    <td colspan="5" bgcolor="#FFFFFF">Your Cart is Empty </td>
  </tr>
  <? }?>
</table>
