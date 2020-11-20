<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php 

$get_pid=$_REQUEST["pid"];

if($body =='customer_registration.html')
		   {
	   		 
			  require_once('meta/customer_registration.html');		    
			 
		  }
		   elseif($body =='sign_up.html')
		  {

			  require_once('meta/sign_up.html');		    
		  }
		  elseif($body =='pages.html')
		  {
			  $query_meta = "SELECT * FROM cms_posts where ID='$get_pid'";
			  $result_meta = $db->execQuery($query_meta);
			  $meta_reslt = $db->resultArray($result_meta);
			  
			  echo stripslashes($meta_reslt[0]['meta_info']);
		  }
		  else{
?>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Indian Foods Online .co.uk | UK's leading online indian takeaway and delivery food ordering provider</title>

<meta name="author" content="Indian Foods Online" />
<meta http-equiv="expires" content="NEVER" />
<meta name="publisher" content="Indian Foods Online" />
<meta name="copyright" content="Indian Foods Online" />
<meta name="page-topic" content="Restaurants" />
<meta name="keywords" content="order indian foods online, indian takeaway, online currys, online table booking, tikka, masala, curry house, just eat, food delivery, online order, takeaways, takeaway" />
<meta name="description" content="Order curry online with Indian Foods Online, The Europe's market leader in online Indian food. Order a Takeaway or Delivery in minutes and just eat and enjoy!!" />
<meta name="page-type" content="Commercial" />
<meta name="audience" content="All" />
<meta name="robots" content="INDEX,FOLLOW" />
<meta http-equiv="content-language" content="EN" />

<meta name="verify-v1" content="1o3acjhVzBcxv0QRsk0PRXKqFlr7DqYg+hYUr1ebDZU=" >

<?
}
?>

<script type="text/javascript"  src="topbanner.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="menu.css" rel="stylesheet" type="text/css" />
<link href="map.css" rel="stylesheet" type="text/css" />
</head>

<body>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-2755059-3");
pageTracker._trackPageview();
} catch(err) {}</script>
<div id="container">
   <div id="top"></div>
      <div id="wbody">
      <!----- header + brade crum + menu+ baner start---->
        <div id="topcontent">
       <?php 
	   
	  
	   
	   require_once('includes/new_header.html'); 

	   ?>
	 
	   <?   if($body !='restaurant_list.html'and $body !='order_menu.html'and $body !='reservetion.html')
		   {
	   		   require_once('includes/new_header_searchbanner.html');		    

		   }


	   ?>
        </div>
		 <!----- header + brade crum + menu+ baner end---->
		 
		<!----- left content + right content  + mid content  start---->
		 <div id="middle"> 
		<? if($body=='order_menu.html' or $body=='restaurant_list.html' or $body=='reservetion.html')
		   {
				 require_once("includes/$body");
		   }
		   else
		   {
				if($body!='order_menu.html' and $body!='restaurant_list.html' and $body!='reservetion.html' and $body!='checkout_shipping.html' and $body!='confirm_cash_payment.html' and $body!='oredr_place.html' and $body!='reservetion_place.html' and $body!='sign_up.html' and $body!='confirm_roilty_payment.html' and $body!='reservetion_confirm.html' and $body!='checkout_collection.html' and $body!='confirm_cash_payment_collection.html' and $body!='confirm_roilty_payment_collection.html' and $body!='orderdetails.html' and $body!='retaurant_orderlist.html' and $body!='restaurant_view_details.html' and $body!='confirm_online_payment.html' and $body!='confirm_online_payment_collection.html' and $body!='restaurants.html')
		    	{
	     ?>
		
        			<div id="left_cont">
        			
        			<?php  require_once('includes/new_left_panal.html');?>
        			
        			</div>
		
		   <?	 }	?>
		   
		   	
		  
		
  <? 
  
  //if($_SESSION['userid']=="" and $_REQUEST[action]=='' or $_REQUEST[action]=='home_body' or $_REQUEST[action]=='logout' or $_REQUEST[action]=='pages' or $body=='home_body.html')
  if($_SESSION['userid']=="" and $_REQUEST[action]=='' or $_REQUEST[action]=='home_body' or $_REQUEST[action]=='logout' or $body=='home_body.html')
				   {
				?>
        <div id="right_cont"><?php  require_once('includes/new_reight_panal.html');?></div>

		<? }	?>
			<table width="51%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td><img src="images/box_01.jpg" width="24" height="30" alt="" /></td>
        <td background="images/box_02.jpg"><span class="box_title_text">Thank For shopping with us </span></td>
        <td><img src="images/box_03.jpg" width="21" height="30" alt="" /></td>
      </tr>
      <tr>
        <td background="images/box_04.jpg">&nbsp;</td>
        <td width="100%" valign="top"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="bodytext">
          <tr>
            <td align="center" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td colspan="2" valign="top"><table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="bodytext">
                  <tr>
                    <td width="3%" >&nbsp;</td>
                    <td width="95%" valign="top" align="center" bgcolor="#F0F0F0"><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#FFFFFF" class="bodytext">
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td colspan="2">Your order/reservetion is posted to Indian Foods Online UK.<br />
                                          <br />
                                          <br />
                        </td>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>
                    </table></td>
                    <td width="2%" >&nbsp;</td>
                  </tr>
                  <tr>
                    <td >&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                    <td >&nbsp;</td>
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
  <iframe width="174" height="189" name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="calander/ipopeng.htm" scrolling="No" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"> </iframe>
</table>
		<?php } ?>
		  </div><!----- left content + right content  + mid content  start---->
   
    <div style="height:25px; clear:both"></div>

   <div id="footer">
		  <?php require_once('includes/new_footer.html');?>
   </div>
</div>
</body>
</html>
