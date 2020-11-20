<?php
$_SESSION['adminmenupath']="http://" . $_SERVER['HTTP_HOST']  . $_SERVER['REQUEST_URI'];
$rids=$_SESSION['resid'];
$query = "SELECT * FROM categories where parent_id='0' and rid='$rids' order by sort_order";

$result = $db->execQuery($query);	
$categories = $db->resultArray($result);

if($_POST["submit"] !=""){
	$discount_id = mysql_escape_string($_POST['discount_id']);
	$cat_id = mysql_escape_string($_POST['category']);
	$discount = mysql_escape_string($_POST['discount']);
	$order_type = mysql_escape_string($_POST['order_type']);
	$sdate = mysql_escape_string($_POST['sdate']);
	$edate = mysql_escape_string($_POST['edate']);
	$status = mysql_escape_string($_POST['status']);
	$rid = $rids;
	
	$post = $_POST;
	
	if($discount_id <= 0){
		if($cat_id == 'all'){
			
		foreach($categories as $category){
			$query = "SELECT * FROM discount_policy WHERE rid='".$rid."' AND cat_id='".$category["categories_id"]."' AND order_type ='".$order_type."'";
			if(!$db->checkExists($query)){
				$query = "INSERT INTO discount_policy VALUES('', '".$rid."', '".$category["categories_id"]."', '".$order_type."', '".$discount."', '".$sdate."', '".$edate."', '".$status."')";
				$db->execQuery($query);
			}else{
				$query = "UPDATE discount_policy SET cat_id='".$category["categories_id"]."', order_type='".$order_type."', amount='".$discount."', publish_date='".$sdate."', expire_date='".$edate."', dicount_status='".$status."' WHERE rid='".$rid."' AND cat_id='".$category["categories_id"]."' AND order_type='".$order_type."'";
				$db->execQuery($query);
			}
		}// end foreach	
			
		}// end if
		else{
			$query = "SELECT * FROM discount_policy WHERE rid='".$rid."' AND cat_id='".$cat_id."' AND order_type ='".$order_type."'";
			if(!$db->checkExists($query)){
				$query = "INSERT INTO discount_policy VALUES('', '".$rid."', '".$cat_id."', '".$order_type."', '".$discount."', '".$sdate."', '".$edate."', '".$status."')";
				$db->execQuery($query);
			}else{
				$query = "UPDATE discount_policy SET order_type='".$order_type."', amount='".$discount."', publish_date='".$sdate."', expire_date='".$edate."', dicount_status='".$status."' WHERE rid='".$rid."' AND cat_id='".$cat_id."' AND order_type='".$order_type."'";
				$db->execQuery($query);
			}
		
		} //end else
		
	}else{
		$query = "UPDATE discount_policy SET order_type='".$order_type."', amount='".$discount."', publish_date='".$sdate."', expire_date='".$edate."', dicount_status='".$status."' WHERE rid='".$rid."' AND dsc_id='".$discount_id."'";
		$db->execQuery($query);
	}
}


if($_REQUEST["do"] == "edit"){
	$dsc_id = $_REQUEST['dsc_id'];
	$query = "SELECT * FROM discount_policy WHERE dsc_id='".$dsc_id."'";
	$ref = $db->execQuery($query);	
	$post = $db->row($ref);
	$post['discount_id'] = $post['dsc_id'];
	$post['category'] = $post['cat_id'];
	$post['discount_id'] = $post['dsc_id'];
	$post['discount'] = $post['amount'];
	$post['sdate'] = $post['publish_date'];
	$post['edate'] = $post['expire_date'];
	$post['status'] = $post['dicount_status'];
	$post['order_type'] = $post['order_type'];
	
}elseif($_REQUEST["do"] == "delete"){
	$dsc_id = $_REQUEST['dsc_id'];
	$query = "DELETE FROM discount_policy WHERE dsc_id='".$dsc_id."'";
	$db->execQuery($query);	
}

?>

<script type="text/JavaScript">
<!--



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<script type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="8%" height="400" valign="top">&nbsp;</td>
    <td width="92%" align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td><img src="images/box_01.jpg" width="24" height="30" alt="" /></td>
        <td background="images/box_02.jpg"><span class="box_title_text">&quot;<? echo $_SESSION[rname];?>&quot; Discount Management </span></td>
        <td><img src="images/box_03.jpg" width="21" height="30" alt="" /></td>
      </tr>
      <tr>
        <td background="images/box_04.jpg">&nbsp;</td>
        <td width="100%" valign="top">
		<table width="100%" border="0" cellspacing="1" cellpadding="1"  class="bodytext">
		<tr>
              <td colspan="3"><?php require_once("restaurant_top_nav.html");?></td>
            </tr>
	<TR>
		<TD colspan="3"><table width="94%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td width="79%">&nbsp;</td>
  <td width="21%" valign="top">&nbsp;</td>
              </tr>
</table></td>
          </tr>
<tr>
<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top"><table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td><img src="images_menu/red_box_01.jpg" width="15" height="39" alt="" /></td>
			<td width="100%" background="images_menu/red_box_02.jpg" class="message1" abbr="left"><strong>DISCOUNT</strong></td>
            <td><img src="images_menu/red_box_03.jpg" width="14" height="39" alt="" /></td>
       </tr>
	   <tr>
			<td background="images_menu/red_box_04.jpg"></td>
            <td>
<form action="index.php?action=discount_policy&tabm=rcttb" method="post" name="orderlist"><input type="hidden" name="discount_id" value="<?php echo $post['discount_id'];?>" />	
<div style="text-align:center; padding:5px; border:1px solid #CCCCCC;">
  <table width="100%" border="0" cellpadding="2" align="center" class="bodytext">
  	<tr style="border-bottom:1px solid #CCCCCC;">
		<td>
			Category: &nbsp; <select name="category">
				<option value="all">All</option>
				<?php foreach($categories as $category){ $selected = ($category["categories_id"] == $post["category"])?"selected='selected'":"";?>
				<option <?php echo $selected;?> value="<?php echo $category["categories_id"]?>"><?php echo $category["categories_name"]?></option>
				<?php } ?>
			</select>
		</td>
	
		<td>Discount (%): <input type="text" name="discount" value="<?php echo $post['discount'];?>" /></td>
		<td>Order Type: 
			<select name="order_type">
				<option <?php if($post['order_type'] == 1){?> selected="selected" <?php } ?> value="1">Delivery</option>
				<option <?php if($post['order_type'] == 2){?> selected="selected" <?php } ?> value="2">Collection</option>
			</select>
		</td>
	</tr>
	<tr>
		  <?php $sdate= ($post['sdate'] != "")?$post['sdate']:date('Y-m-d'); ?>
		<td>Start Date: <input name="sdate"  id="sdate" value="<?php echo $sdate;?>" size="12" />
                            <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.orderlist.sdate);return false;" ><img class="PopcalTrigger" align="top" src="calander/calbtn.gif" width="34" height="22" border="0" alt=""  /></td>
		<?php $edate= ($post['edate'] != "")?$post['edate']:date('Y-m-d'); ?>
		<td>End Date: &nbsp; &nbsp;  &nbsp;  &nbsp;<input name="edate"  id="edate" value="<?php echo $edate;?>" size="12"/>
                              <a href="javascript:void(0)" onClick="if(self.gfPop)gfPop.fPopCalendar(document.orderlist.edate);return false;" ><img class="PopcalTrigger" align="absmiddle" src="calander/calbtn.gif" width="34" height="22" border="0" alt="" /></td>
		<td align="left">Status: <select name="status"><option <?php if($post['statis'] == 1){?> selected="selected" <?php } ?> value="1">Active</option><option value="0" <?php if($post['status'] == 0){?> selected="selected" <?php } ?>>Inactive</option></select> &nbsp; <input type="submit" name="submit" value="Save" /></td>
	</tr>
  </table>
</div></form>  	
<br />

<table width="100%" border="0" cellspacing="0" cellpadding="2" class="bodytext">
  		<tr style="background-color:#CCCCCC;" height="20">
			<th width="20%">Category</th>
			<th width="16%">Order Type</th>
			<th align="right" width="16%">Discount (%)</th>
			<th align="right" width="16%">Start Date</th>
			<th align="right" width="16%">End Date</th>
			<th width="16%">Action</th>
		</tr>
<?php 
foreach($categories as $category){
		$query = "SELECT * FROM discount_policy WHERE rid='".$rids."' AND cat_id='".$category["categories_id"]."' ORDER BY order_type";
		$ref = $db->execQuery($query);
		$discounts = $db->resultArray($ref);
		
?>
    <tr>
      <td valign="top"><a href="#" class="nav3"><?=$category['categories_name'];?></a></td>	
	   <td colspan="5"><div style="border-bottom:1px solid #CCCCCC;">
	   	<?php if($discounts){?>
	   	<table width="100%" cellpadding="2" cellspacing="0" border="0" class="bodytext">	
		<?php foreach($discounts as $data){?>
	   	 	<tr>
			   <td  width="20%" align="center" valign="top" bgcolor="#FFFFFF"><?php echo ($data["order_type"] == 1)?"Delivery":"Collection";?></td>	
			  <td  width="20%" align="right" valign="top" bgcolor="#FFFFFF"><?php echo number_format($data["amount"], 2, '.', '');?></td>
			  <td  width="20%" align="right" valign="top" bgcolor="#FFFFFF"><?php echo show_date($data["publish_date"], 'd/m/Y'); ?></td>
			  <td  width="20%" align="right" valign="top" bgcolor="#FFFFFF"><?php echo show_date($data["expire_date"], 'd/m/Y'); ?></td>		
			  <td  width="20%" bgcolor="#FFFFFF" align="center">
		<a href="index.php?action=discount_policy&amp;do=edit&amp;dsc_id=<?php echo $data["dsc_id"];?>&amp;tabm=rcttb"><img src="images/b_edit.png" border="0" width="15" height="17" /></a>
		<a href="index.php?action=discount_policy&amp;&amp;do=delete&amp;dsc_id=<?php echo $data["dsc_id"];?>&amp;tabm=rcttb" class="nav"onclick="return confirm('Are you sure you want to delete this?');"><img src="images/b_drop.png" width="15" height="17" border="0"></a> 
			  </td> 
			</tr>
		<?php } ?>
		</table>
		<?php }else{?>
			<div style="text-align:center;"><strong>There are no discount in this category.</strong></div>
		<?php } ?>	
		</div>
	  </td>		   
    </tr>      
<?php } ?>    
  </table>
 					</td>
                    <td width="6" background="images_menu/red_box_06.jpg"></td>
                  </tr>
                  <tr>
                    <td><img src="images_menu/red_box_07.jpg" width="15" height="17" alt="" /></td>
                    <td background="images_menu/red_box_08.jpg"></td>
                    <td width="66"><img src="images_menu/red_box_09.jpg" width="14" height="17" alt="" /></td>
                  </tr>
                </table></td>
                
              </tr>
            </table></td>
          </tr>
        </table></TD>
	</TR>
	
	<TR>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
		<TD>&nbsp;</TD>
	</TR>
</TABLE></td>
        <td background="images/box_06.jpg">&nbsp;</td>
      </tr>
      <tr>
        <td><img src="images/box_07.jpg" width="24" height="31" alt="" /></td>
        <td background="images/box_08.jpg">&nbsp;</td>
        <td><img src="images/box_09.jpg" width="21" height="31" alt="" /></td>
      </tr>
    </table></td>
  </tr>
</table>
<iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="calander/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>