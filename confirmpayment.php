<?php
ob_start();
session_start();  // if($_REQUEST['custom'] <= 0){$_REQUEST['custom'] = 209; $_REQUEST['txn_id'] = "xp8k9cf3btyuiklmQw"; }
require_once('config.php');
require_once('lib/class.Db.php');
$db = new Db(hostname, username, password, databasename);
require_once('lib/functions.php');

if($_REQUEST['item_name']=="Restaurant Table Reservation")
{
$oridsp=$_REQUEST['custom'];
							$query="Update customer_regervetion set rpstatus='1',gpay='1' where resid='$oridsp'";
							 $result = $db->execQuery($query);
							
							 $queryresf="Select * from customer_regervetion where resid='$oridsp'";
							 $resultresf=mysql_query($queryresf);
							 $rowresf=mysql_fetch_array($resultresf);
							 $rsids=$rowresf["rid"];
							
							 
							 $mlord=$oridsp;
$sqlprc="select * from resturant_service where rid='$rsids'";
$resultprc=mysql_query($sqlprc);
$rowprc=mysql_fetch_array($resultprc);

$sqlprcd="select * from printdomain";
$resultprcd=mysql_query($sqlprcd);
$rowprcd=mysql_fetch_array($resultprcd);


$send_to = $rowprc['pr_code']."@".$rowprcd['pdname'];
if($send_to=="")
{
$send_to="return@indianfoodsonline.co.uk";
}

$email="order@indianfoodsonline.co.uk";
		$name = $rowsb['name'];
		$headers = "From: $email\n"; // From address
		$headers .= "Reply-To: $email\n"; // Reply-to address
		$headers .= "Organization: www.indianfoodsonline.co.uk\n"; // Organisation
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Type
		 $query = "SELECT * FROM customer_regervetion where resid='$mlord'";
						$result = $db->execQuery($query);
						$arinfo = $db->resultArray($result);
							$cuid=$arinfo[0]["customers_id"];	
				$sqlc="Select * from customers where customers_id ='$cuid'";
				$resultc=mysql_query($sqlc);
				$rowc=mysql_fetch_array($resultc);
				

$body_txt1 .=$rowc["customers_firstname"];
$body_txt1 .=' ';
$body_txt1 .=$rowc["customers_lastname"];
$body_txt1 .="\n";
$body_txt1 .='P:';
$body_txt1 .=$rowc["customers_telephone"];
 $body_txt1 .="\n";	
  $body_txt1 .='OT:Reservetion';
   $body_txt1 .="\n";
   $body_txt1 .="AD:";
   $body_txt1 .=$arinfo[0]["ar_date"];
   $body_txt1 .="\n";
   $body_txt1 .="AT:";
   $body_txt1 .=$arinfo[0]["ar_time"];
   $body_txt1 .="\n";
   $body_txt1 .="NP:";
   $body_txt1 .=$arinfo[0]["nop"];
   $subject="";
		$sent = mail($send_to, $subject, $body_txt1, $headers);
		$send_to="order@indianfoodsonline.co.uk";
	$sent = mail($send_to, $subject, $body_txt1, $headers);
}else{
if($_REQUEST['custom']!=""){ 
	
							$oridsp=$_REQUEST['custom'];
							$ipn = $_REQUEST["txn_id"];
							$query="Update customer_order set pstatus='1', author_code='".$ipn."' where orid='$oridsp'";
							 $result = $db->execQuery($query);
							
							 $queryresf="Select * from customer_order where orid='$oridsp'";
							 $resultresf=mysql_query($queryresf);
							 $rowresf=mysql_fetch_array($resultresf);
							 			 
							 $rsids=$rowresf["rid"];
							
							 
							 $mlord=$oridsp;
$sqlprc="select * from resturant_service where rid='$rsids'";
$resultprc=mysql_query($sqlprc);
$rowprc=mysql_fetch_array($resultprc);

$sqlprcd="select * from printdomain";
$resultprcd=mysql_query($sqlprcd);
$rowprcd=mysql_fetch_array($resultprcd);


$send_to = $rowprc['pr_code']."@".$rowprcd['pdname'];
if($send_to=="")
{
$send_to="return@indianfoodsonline.co.uk";
}

$email="order@indianfoodsonline.co.uk";
		$name = $rowsb['name'];
		$headers = "From: $email\n"; // From address
		$headers .= "Reply-To: $email\n"; // Reply-to address
		$headers .= "Organization: www.indianfoodsonline.co.uk\n"; // Organisation
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Type
		  $query = "SELECT * FROM customer_order where orid='$mlord'";
						$result = $db->execQuery($query);
						$arinfo = $db->resultArray($result);
							$cuid=$arinfo[0]["customers_id"];	
				$sqlc="Select * from customers where customers_id ='$cuid'";
				$resultc=mysql_query($sqlc);
				$rowc=mysql_fetch_array($resultc);
				
                
$dtm=$arinfo[0]["ordate"];
$dtm= strtotime($dtm);
$dtm=date('d-m-y G:i',$dtm);
$body_txt1 .=$dtm;
$body_txt1 .="\n";
$body_txt1 .=$rowc["customers_firstname"];
$body_txt1 .=' ';
$body_txt1 .=$rowc["customers_lastname"];
$body_txt1 .="\n";
$body_txt1 .='P:';
$body_txt1 .=$rowc["customers_telephone"];
 $body_txt1 .="\n";
  if($arinfo[0]["order_type"]!="2"){
  if($arinfo[0]["customers_address1"]!=""){
  $body_txt1 .=$arinfo[0]["customers_address1"];
  $body_txt1 .="\n";
  }
  if($arinfo[0]["customers_address2"]!=""){
  $body_txt1 .=$arinfo[0]["customers_address2"];
  $body_txt1 .="\n";
  }
    if($arinfo[0]["customers_town"]!=""){
 
  $body_txt1 .=$arinfo[0]["customers_town"];
 $body_txt1 .="\n";
  }
      if($arinfo[0]["customers_state"]!=""){
 
  $body_txt1 .=$arinfo[0]["customers_state"];
 $body_txt1 .="\n";
  }
  if($arinfo[0]["customers_postcode"]!=""){

  $body_txt1 .=$arinfo[0]["customers_postcode"];
 $body_txt1 .="\n";
  }
  }
  $body_txt1 .="\n";
   $body_txt1 .='Items';
   $body_txt1 .="\n";
  $sqlproduc="select * from order_detail where orid='$mlord'";
		$resultpro=mysql_query($sqlproduc);
		while($rowpro=mysql_fetch_array($resultpro)){
		$pids=$rowpro["product_id"];
						$sqlpn="Select * from product where product_id ='$pids'";
						$resultpn=mysql_query($sqlpn);
						$rowpn=mysql_fetch_array($resultpn);
  $body_txt1 .=$rowpro["qantity"];  
  $body_txt1 .='x';
  $body_txt1 .=$rowpn["food_code"];
  $body_txt1 .=' -';
  $stotal=$rowpro["uprice"]*$rowpro["qantity"];
  	$body_txt1 .=number_format($stotal, 2, '.', '');
			           $body_txt1 .="\n";
            }
  $body_txt1 .="\n";
  $body_txt1 .='GT:';
  $tcalid=$arinfo[0]["orid"];
					$sqltpr="select sum(uprice*qantity) as tprice from order_detail where orid='$tcalid'";
					$resulttpr=mysql_query($sqltpr);
					$rowprt=mysql_fetch_array($resulttpr);
			$ftotal=$rowprt["tprice"]+$arinfo[0]["vat"]+$arinfo[0]["hnd_fee"];
			$body_txt1 .=number_format($ftotal, 2, '.', '');
			
           $body_txt1 .="\n";
		   $body_txt1 .="\n";

$body_txt1 .='OT:';
$ott=$arinfo[0]["order_type"];
if($ott=="1")
{
$ott .='D';
}
else
{
$body_txt1 .='C';
}
$body_txt1 .='PM:';
$pmt=$arinfo[0]["pay_mathod"];
if($pmt=="Online")
{
$ott .='OP';
}
else if($pmt=="Cash On Delivery")
{
$body_txt1 .='CD';
}
else
{
$body_txt1 .='RP';
}
$body_txt1 .='DT:';

$dttm=$arinfo[0]["delevery_time"];
$dttm= strtotime($dttm);
$dttm=date('G:i',$dttm);
$body_txt1 .=$dttm;
$sqlgt="Select * from res_royalty";
$resultrol=mysql_query($sqlgt);
$rowrol=mysql_fetch_array($resultrol);
$rlrate=$rowrol["point"]/$rowrol["poundt"];
$rltp=$rowprt["tprice"]*$rlrate;
$sqlinpoint="insert into customer_roylpoint values('','$cuid','$rltp','')";
$resultpoint = $db->execQuery($sqlinpoint);
		$subject="";
		$body_txt1 = "A new online order is placed, order NO: ".order_id($arinfo[0]["orid"]); // temporarily purpose
		
		// $sent = mail($send_to, $subject, $body_txt1, $headers); // mail has been sent to mobile via SMS // SMS has been skipped after online payment confirmation cause it is now sending before payment confirmation
		
		$send_to="order@indianfoodsonline.co.uk";
		$sent = mail($send_to, $subject, $body_txt1, $headers); // mail has been sent to order@indianfoodsonline.co.uk
							
// mail to 
$email="order@indianfoodsonline.co.uk";
	
	$headers = "From: $email\n"; // From address
	$headers .= "Reply-To: $email\n"; // Reply-to address
	$headers .= "Organization: www.indianfoodsonline.co.uk\n"; // Organisation
	$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Type

	$body_txt = '<style type="text/css">
					<!--
					.bodytext {font-family: Arial, Helvetica, sans-serif; font-size:12px; color:#000000;}
					-->
					</style><table width="100%" border="0" cellspacing="1" cellpadding="1"  class="bodytext">
					  <tr>
						<td>&nbsp;</td>
					  </tr>
					  <tr>
						<td></td>
					  </tr>
					  <tr>
						<td width="81%" valign="top" bgcolor="#F0F0F0"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
						  <tr>
							<td align="center" class="bodytext"><strong>Online Order from IndianFoodsonline.co.uk</strong></td>
						  </tr>';
										
	$query = "SELECT * FROM customer_order where orid='$mlord'";
	$result = $db->execQuery($query);
	$arinfo = $db->resultArray($result);
	
	$cuid=$arinfo[0]["customers_id"];	
	$sqlc="Select * from customers where customers_id ='$cuid'";
	$resultc=mysql_query($sqlc);
	$rowc=mysql_fetch_array($resultc);
	$send_to = $rowc["customers_email_address"];
	
	$body_txt .= '<tr>
					<td align="center">
					  <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#F0F0F0" class="bodytext">
						<tr>
						  <td colspan="4" bgcolor="#F0F0F0" ><strong>Order No: ';
	 $body_txt .=  order_id($arinfo[0]["orid"]);
	 $body_txt .= '</strong></td>
					</tr>
					<tr>
					  <td colspan="4" valign="top" bgcolor="#FFFFFF" ><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#EEEEEE">
						<tr>
						  <td align="left"  valign="top" bgcolor="#FFFFFF" class="bodytext"> Order Date & Time:</td>
						  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
	$dt=explode(' ',$arinfo[0]["ordate"]);
	$dd=explode('-',$dt[0]);
	$tt=explode(':',$dt[1]);
	$body_txt .= date('d/m/Y H:i:s',mktime($tt[0],$tt[1],$tt[2],$dd[1],$dd[2],$dd[0]));
	$body_txt .= '</td>
				  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
	$ridso=$arinfo[0]["rid"];
	$sqlres="Select * from resturant where rid='$ridso'";
	$resultord=mysql_query($sqlres);
	$roword=mysql_fetch_array($resultord);
				  
	$body_txt .= 'Restaurant Name: </td>
				  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
	$body_txt .=$roword["rname"];
	$body_txt .='</td>
				</tr>
			   
				<tr>
				  <td align="left" valign="top" bgcolor="#FFFFFF" class="bodytext">Order Type: </td>
				  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
	if ($arinfo[0]["order_type"]=="1")
	{
		$dst="Order Delivery";
		$dc="Delivery";
	}
	else
	{ 
		$dst="Order Collection"; 
		$dc="Collection";
	}
						
	$body_txt .=$dst;
	$body_txt .='</td>
					  <td width="23%" valign="top" bgcolor="#FFFFFF" class="bodytext">Restaurant Address: </td>
					  <td width="29%" valign="top" bgcolor="#FFFFFF" class="bodytext">';
	$body_txt .=$roword["street"].", ".$roword["zipcode"].", ".$roword["city"];
/////////////////////////
	$body_txt .='</td>
				</tr>
			   
				<tr>
				  <td align="left" valign="top" bgcolor="#FFFFFF" class="bodytext">';
	$body_txt .=$dc;
	$body_txt .=' Date & Time: </td>
				  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
	$dt=explode(' ',$arinfo[0]["delevery_time"]);
	$dd=explode('-',$dt[0]);
	$tt=explode(':',$dt[1]);
	$body_txt .= date('d/m/Y H:i:s',mktime($tt[0],$tt[1],$tt[2],$dd[1],$dd[2],$dd[0]));
	$body_txt .='</td>
					  <td width="23%" valign="top" bgcolor="#FFFFFF" class="bodytext">Restaurant Tel No: </td>
					  <td width="29%" valign="top" bgcolor="#FFFFFF" class="bodytext">';
	$body_txt .=$roword["contact"];

	$body_txt .='</td>
				</tr>
			   
				<tr>
				  <td align="left" valign="top" bgcolor="#FFFFFF" class="bodytext">Payment Status:</td>
				  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
	if($arinfo[0]['pay_mathod']=="Online")
	{
		$body_txt .='Paid';
	}else
	{
		$body_txt .='Not Paid';
	}
	$body_txt .='</td>
					  <td width="23%" valign="top" bgcolor="#FFFFFF" class="bodytext">&nbsp;</td>
					  <td width="29%" valign="top" bgcolor="#FFFFFF" class="bodytext">&nbsp;';
////////////////////


	$body_txt .='</td>
					</tr>
				  </table></td>
				</tr>
				
					  <tr>
						<td align="center"><strong>Payment is made.</strong></td>
					  </tr>
					</table>
					';
	$subject="Order at IndianFoodsOnline Uk";
	
	//echo $body_txt; exit;
	$sent = mail($send_to, $subject, $body_txt, $headers);
			
	//$send_to="order@indianfoodsonline.co.uk";
	$sqlc="Select * from order_del_email where ode_id ='1'";
	$resultc=mysql_query($sqlc);
	$rowc=mysql_fetch_array($resultc);
	$send_to = $rowc["ode_email"];

	$sent = mail($send_to, $subject, $body_txt, $headers);
	
	// restaurant owner mail
	$sqlc="Select userregistration.email from userregistration inner join resturant on userregistration.id=resturant.userid where rid ='".$arinfo[0]["rid"]."'";
	$resultc=mysql_query($sqlc);
	$rowc=mysql_fetch_array($resultc);
	$send_to = $rowc["email"];
	
	$sent = mail($send_to, $subject, $body_txt, $headers);							
							
							
							}
							}
?>