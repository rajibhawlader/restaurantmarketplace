<?php
require_once('lib/class.Db.php');	
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
require_once('lib/functions.php');
ob_start();
session_start();

$_SESSION['order_type'] = (isset($_SESSION['order_type']))?$_SESSION['order_type']:1;

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
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="bodytext">
  <tr>
    <td height="24" bgcolor="#F0F0F0"><strong>&nbsp;&nbsp;Item </strong></td>
    <td width="20%" align="center" bgcolor="#F0F0F0"><strong>Quantity</strong></td>
    <td width="16%" bgcolor="#F0F0F0"><strong>Unique Price </strong></td>
    <td align="right" bgcolor="#F0F0F0"><strong>Sub Total </strong></td>
    <td align="center" bgcolor="#F0F0F0"><strong>Action</strong></td>
  </tr>
  <? if($_SESSION[cart]!=""){ 
  $totalpr=0;
  for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) {
$pids=$_SESSION['cart'][$i];
$qtys=$_SESSION['qty'][$i];
$sqlpro="Select * from product where product_id='$pids'";
$resultpro=mysql_query($sqlpro);
$rowp=mysql_fetch_array($resultpro);
?>
  <tr>
    <td width="33%" height="25" valign="middle" bgcolor="#FFFFFF">&nbsp;&nbsp;<? echo $rowp["product_title"]; ?></td>
    <td align="center" valign="middle" bgcolor="#FFFFFF"><? echo $qtys; ?></td>
    <td valign="middle" bgcolor="#FFFFFF"><? echo ($rowp["price"] - discount_price($_SESSION['rids'], $rowp["categories_id"], $_SESSION['order_type'], $rowp["price"]));   // discount price has been deducted here. ?></td>
    <td width="17%" align="right" valign="middle" bgcolor="#FFFFFF">&pound;&nbsp;
        <?php
		 $item_price = ($rowp["price"] - discount_price($_SESSION['rids'], $rowp["categories_id"], $_SESSION['order_type'], $rowp["price"]));   // discount price has been deducted here.
		 $sbtotal= $item_price*$qtys;
		 // $sbtotal= $rowp["price"]*$qtys;
	$totalpr=$totalpr+$sbtotal;
	$_SESSION['ttpric']=$totalpr;
	 echo number_format($sbtotal, 2, '.', '');?></td>
    <td width="14%" align="center" valign="middle" bgcolor="#FFFFFF"><a href="#" onclick="javascript:return addtocard('<?php echo $rowp['product_id']?>')"><img src="images/plus.jpg" width="10" height="10" border="0" title="Add Quantity" /></a><a href="#" onclick="javascript:return addtocardminus('<?php echo $rowp['product_id']?>')"><img src="images/minus.jpg" width="10" height="10" border="0" title="Less quantity" /></a><a href="#" onclick="javascript:return addtocarddel('<?php echo $rowp['product_id']?>')"><img src="images/multiple.jpg" width="10" height="10" border="0" title="Delete Product" /></a></td>
  </tr>
  <? }?>
  <tr>
    <td colspan="5" align="left" valign="middle" bgcolor="#006666" height="1px"></td>
  </tr>
  <tr>
    <td align="left" valign="middle" bgcolor="#FFFFFF"><a href="index-action-<?php if($_SESSION['order_type']=="1")
								{?>menu_list<?php }else{?>menu_list_collection<?php }?>-rid-<? echo $_SESSION['rids']; ?>" class="nav"><img src="images/button_continue_shooping.jpg" width="123" height="39" border="0" /></a></td>
    <td align="right" valign="middle" bgcolor="#FFFFFF"><div align="center"><a href="index-action-order_place"><img src="images/button_check_out.jpg" width="88" height="39" border="0" /></a></div></td>
    <td align="right" valign="top" bgcolor="#FFFFFF"><strong>Total:</strong></td>
    <td align="right" valign="top" bgcolor="#FFFFFF"><strong>&pound;&nbsp;<? echo number_format($totalpr, 2, '.', ''); ?></strong></td>
    <td align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <? $rids=$_SESSION['rids'];
   $sqlvc="Select * from resturant_service where rid='$rids'";
   $resultvc=mysql_query($sqlvc);
   $rowvc=mysql_fetch_array($resultvc);
   $rvat=$rowvc["vat"];
   $rhfee=$rowvc["hfee"];
   $rvrate=$rvat/100;
   $avat=$totalpr*$rvrate;
   $ftprice=$_SESSION['ttpric']+$avat+$rhfee;
   $_SESSION['gtotal']=$ftprice; }else{?>
  <tr>
    <td align="left" valign="top" bgcolor="#FFFFFF">Your Cart is Empty </td>
    <td bgcolor="#FFFFFF"><a href="index-action-<?php if($_SESSION['order_type']=="1")
								{?>menu_list<?php }else{?>menu_list_collection<?php }?>-rid-<? echo $_SESSION['rids']; ?>" class="nav"><img src="images/button_continue_shooping.jpg" width="123" height="39" border="0" /></a></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <? }?>
</table>
