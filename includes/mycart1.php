<?php
require_once('lib/class.Db.php');	
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
ob_start();
session_start();

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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><img src="images/box_01.jpg" width="24" height="30" alt="" /></td>
        <td background="images/box_02.jpg"><span class="box_title_text">My Cart  Information </span></td>
        <td><img src="images/box_03.jpg" width="21" height="30" alt="" /></td>
      </tr>
      <tr>
        <td background="images/box_04.jpg">&nbsp;</td>
        <td width="100%" valign="top"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="bodytext">
          
          <tr>
            <td align="right" ><?php  require_once('includes/customermenue.html');?></td>
          </tr>
         
          <tr>
            <td align="center" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td colspan="2" valign="top"><table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="bodytext">

                    <tr>
                      <td width="3%" >&nbsp;</td>
                      <td width="95%" valign="middle" align="center" bgcolor="#F0F0F0"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF" class="bodytext">
                        <tr>
                          <td><strong>Item </strong></td>
                          <td width="17%"><strong>Quantity</strong></td>
                          <td width="15%"><strong>Unique Price </strong></td>
                          <td colspan="2" align="right">&nbsp;</td>
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
                          <td width="33%"><? echo $rowp["product_title"]; ?></td>
                          <td><input name="qn" type="text" id="qn" value="<? echo $qtys; ?>" size="2" disabled="disabled" /></td>
                          <td><? echo $rowp["price"];?></td>
                          <td width="19%" align="right" valign="top">&pound;&nbsp;
                              <? $sbtotal= $rowp["price"]*$qtys;
	$totalpr=$totalpr+$sbtotal;
	$_SESSION['ttpric']=$totalpr;
	 echo $sbtotal;?></td>
                          <td width="16%" align="right" valign="top"><a href="index-action-mycart-idp-<?php echo $rowp['product_id']?>"> <img src="images/plus.jpg" width="10" height="10" border="0" alt="Add Quantity" /></a><a href="#" onclick="javascript:return addtocardminus('<?php echo $rowp['product_id']?>')"><img src="images/minus.jpg" width="10" height="10" border="0" /></a><a href="#" onclick="javascript:return addtocarddel('<?php echo $rowp['product_id']?>')"><img src="images/multiple.jpg" width="10" height="10" border="0" /></a></td>
                        </tr>
                        <? }?>
                        <tr>
                          <td colspan="3" align="right"><strong>Total:</strong></td>
                          <td align="right">&pound;&nbsp;<? echo $totalpr; ?></td>
                          <td align="left">&nbsp;</td>
                        </tr>
                        <? }else{?>
                        <tr>
                          <td colspan="3">Your Cart is Empty</td>
                          <td colspan="2">&nbsp;</td>
                        </tr>
                        <? }?>
                      </table></td>
                      <td width="2%" >&nbsp;</td>
                    </tr>
                 
                  
                                </table></td>
                </tr>
              <tr>
                <td width="51%">&nbsp;</td>
                <td width="49%">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td background="images/box_06.jpg">&nbsp;</td>
      </tr>
      <tr>
        <td><img src="images/box_07.jpg" width="24" height="31" alt="" /></td>
        <td background="images/box_08.jpg">&nbsp;</td>
        <td><img src="images/box_09.jpg" width="21" height="31" alt="" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  
</table>
