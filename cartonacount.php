<script src="js/ajax.js"></script>

<script type="text/javascript">
function changemenucard(value)
{
	var url = 'ordertypeset.php?ordertypeset='+value;
	makeRequest(url,'ord');
	setTimeout("window.location.reload()", 300);
}

function addtocard(valu)
{
//alert(document.getElementById('orderList'));
	var url = 'cartonacount.php?idp='+valu;
	makeRequest(url,'orderList');
	
//alert(document.getElementById('orderList'));	

return false;
}
function addtocardminus(valu)
{
	var url = 'cartonacount.php?qt=m&idp='+valu;
	makeRequest(url,'orderList');

return false;
}
function addtocarddel(valu)
{

	var url = 'cartonacount.php?qt=y&idp='+valu;
	makeRequest(url,'orderList');
	

return false;
}
</script>


<?php
require_once('lib/class.Db.php');	
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
require_once('lib/functions.php');
ob_start();
session_start();
//print_r ($_SESSION); exit;
if($_REQUEST['idp']!="" and $_REQUEST['qt']=="")
{
	
		for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) 
		{
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
}
else if($_REQUEST['idp']!="" and $_REQUEST['qt']=="m")
{
	for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) 
	{
		if($_REQUEST['idp']==$_SESSION['cart'][$i])
		{
			$_SESSION['qty'][$i]=$_SESSION['qty'][$i]-1;
			if($_SESSION['qty'][$i]==0)
			{
				$_SESSION['qty'][$i]=1;
			}
		}
	}
}
else if($_REQUEST['idp']!="" and $_REQUEST['qt']=="y")
{
	if(sizeof($_SESSION[cart])>1)
	{
		$d=0;
		for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) 
		{
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
	}
	else
	{
		unset($_SESSION['cart']);
		unset($_SESSION['qty']);
	}
}

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="bodytext3">
  <tr>
    <td height="22" colspan="3" align="center" valign="middle"><strong>
	<?php if($_SESSION['otypevalue']==1 or $_SESSION['otypevalue']>=3) { ?>
	<input type="radio" name="dc" value="1" <?php if($_SESSION['order_type']==1) echo "checked";?> onclick="changemenucard(this.value);" />Delivery&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php } if($_SESSION['otypevalue']==2 or $_SESSION['otypevalue']>=3) { ?>
	<input type="radio" name="dc" value="2" <?php if($_SESSION['order_type']==2) echo "checked";?> onclick="changemenucard(this.value);" />Collection
	<?php } ?>
	</strong></td>
    </tr>
  <tr>
    <td colspan="3" align="center" valign="middle"><div style="border-top:1px dashed #999999"></div></td>
  </tr>
	  <? if($_SESSION[cart]!="")
	  	 { 
			  $totalpr=0;
				for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) 
				{
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
					    <td width="61%" align="left"><? echo $qtys; ?>x<? echo $rowp["product_title"].'&nbsp;'.$rowpp["product_title"]?></td>
					    <td width="23%" align="right" valign="top">&pound;&nbsp;<?php
						 $item_price = ($rowp["price"] - discount_price($_SESSION['rids'], $rowp["categories_id"], $_SESSION['order_type'], $rowp["price"]));   // discount price has been deducted here.
						 $sbtotal= $item_price*$qtys;
						$totalpr=$totalpr+$sbtotal;
						$_SESSION['ttpric']=$totalpr;
						 echo number_format($sbtotal, 2, '.', '');?></td>
					    <td width="16%" align="right" valign="top" style="padding-top:5px"><a href="#" onclick="javascript:return addtocard('<?php echo $rowp['product_id']?>')"><img src="images/plus.jpg" width="10" height="10" border="0" /></a><a href="#" onclick="javascript:return addtocardminus('<?php echo $rowp['product_id']?>')"><img src="images/minus.jpg" width="10" height="10" border="0" /></a><a href="#" onclick="javascript:return addtocarddel('<?php echo $rowp['product_id']?>')"><img src="images/multiple.jpg" width="10" height="10" border="0" /></a></td>
					</tr>
		 <? }?>
  <tr>
    <td colspan="3" align="right"><div style="border-top:1px dashed #999999"></div></td>
  </tr>
		 
<?php
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
		$totalpr=$totalpr+$hfee;
  ?>
  <tr>
    <td align="right"><strong>Handling Fees:&nbsp;</strong></td>
    <td align="right">&pound;&nbsp;<? echo number_format($hfee, 2, '.', ''); ?></td>
    <td align="left">&nbsp;</td>
  </tr>
  <?php } ?>
  <?php
    if($_SESSION['order_type']=="1") { 
	if($_SESSION['ttpric']>=$dev_pol[0]['min_order'])
	{
	$dcost=$dev_pol[0]['del_cost'];
	$totalpr=$totalpr+$dcost;
  ?>
  <tr>
    <td align="right"><strong>Delivery Cost:&nbsp;</strong></td>
    <td align="right">&pound;&nbsp;<? echo number_format($dcost, 2, '.', ''); ?></td>
    <td align="left">&nbsp;</td>
  </tr>
  <?php } } ?>

			  <tr>
			    <td align="right"><strong>Total:</strong></td>
			    <td align="right">&pound;&nbsp;<? echo number_format($totalpr, 2, '.', ''); ?></td>
			    <td align="left">&nbsp;</td>
			  </tr>
			<tr>
			<td align="center" colspan="3">			  
	<?php
	if($_SESSION['order_type']=="2") { 
		$ordr=1;
	} else {
	if($_SESSION['ttpric']>=$dev_pol[0]['min_order'])
	{
		$ordr=1;
	}else
	{
		$ordr=0;
		echo "<strong style='color:#FF0000'>Minimum delivery order of ( &pound; ".$dev_pol[0]['min_order']." ) not yet met. Please add more items.</strong>";
	} }
	?>	 
	<input type="hidden" name="ordr" id="ordr" value="<?=$ordr?>" />
	</td>
	</tr>
				  <? 
			   $_SESSION['gtotal']=$totalpr; }
			   else
			   {
			?>
				  <tr>
				    <td align="center" colspan="3">Your Cart is Empty</td>
			      </tr>
		    <? }?>
</table>
