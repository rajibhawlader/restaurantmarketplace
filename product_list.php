
<?php 
require_once('lib/class.Db.php');	
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
//$catids=$_REQUEST['id'];

$rid=$_GET['id'];
?>
 <table width="100%" border="0" align="center" cellspacing="0">
<tr>
<?php
//					$query1 = "SELECT * FROM product where categories_id='$catids'";
					$query1 = "SELECT
								`product`.`product_title`,
								`product`.`product_id`,
								`product`.`categories_id`,
								`product`.`food_code`,
								`product`.`thumb_image`,
								`product`.`large_image`,
								`product`.`product_desc`,
								`product`.`price`,
								`categories`.`categories_name`
								FROM
								`product`
								Inner Join `categories` ON `product`.`categories_id` = `categories`.`categories_id`
								Inner Join `resturant` ON `categories`.`rid` = `resturant`.`rid`
								WHERE
								`resturant`.`rid` = $rid";
		
                    $result1 = $db->execQuery($query1);
                    $arinfo1= $db->resultArray(result1);
//New
//print_r( $arinfo1);
		$query2 = "SELECT DISTINCT
					`categories`.`categories_name`,
					`categories`.`short_desc`,
					`product`.`categories_id`
					FROM
					`product`
					Inner Join `categories` ON `product`.`categories_id` = `categories`.`categories_id`
					Inner Join `resturant` ON `categories`.`rid` = `resturant`.`rid`
					WHERE
					`resturant`.`rid` =$rid";
		
                    $result2 = $db->execQuery($query2);
                    $arinfo2= $db->resultArray(result2);

//
				for ($j = 0; $j < sizeof($arinfo2); $j++)
				{
				
				echo '<td width="100%" style="color:#6B1C21;font-size:18px;font-weight:bold;">'.$arinfo2[$j]['categories_name'].'</td></tr>'; 
				echo '<tr><td>"'.$arinfo2[$j]['short_desc'].'"</td></tr>'; 										
                    for ($i = 0; $i < sizeof($arinfo1); $i++)
					{	

						
							if($arinfo2[$j]['categories_id']==$arinfo1[$i]['categories_id'])	
							{
							if($i%2==0)
							$class="even";
							else
							$class="odd";
?>

  <tr class="<?=$class?>">
    <td width="15%" rowspan="2"><? if($arinfo1[$i]['thumb_image']!=''){?>
	<img src="<?php echo $arinfo1[$i]['thumb_image'];?>" name="imgMainPic" id="imgMainPic" height="90" width="120"/><?} ?></td>
    <td><strong><?php echo $arinfo1[$i]['product_title']?></strong></td>
    <td>&pound;<?php $number = $arinfo1[$i]['price'];
	$english_format_number = number_format($number, 2, '.', '');
// 1234.57
echo $english_format_number;?></td>
    <td width="5%" valign="top"><a href="#" onclick="javascript:return addtocard('<?php echo $arinfo1[$i]['product_id']?>')"><img src="images/add_item.jpg" width="71" height="26" border="0" /></a></td>
  </tr>
  <tr class="<?=$class?>">
    <td colspan="3"><em><?php echo $arinfo1[$i]['product_desc']?></em></td>
  </tr>
<?php } } }?>

</table>
