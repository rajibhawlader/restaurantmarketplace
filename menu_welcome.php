<?php 
require_once('lib/class.Db.php');	
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
$ridap=$_SESSION['rids'];
?>

<table width="100%" border="0" cellpadding="0" cellspacing="3" class="bodytext" align="center">
<?php
					$query1 = "SELECT * FROM resturant where rid='$ridap'";
                    $result1 = $db->execQuery($query1);
                    $arinfo1= $db->resultArray(result1);
					?>
                  
  <tr valign="top">
    <td height="9"><strong><?php echo $arinfo1[0]['rname']?></strong></td>
  </tr>
  <tr>
    <td height="1" bgcolor="#666666" ></td>
  </tr>
  <tr valign="top">
    <td height="9"><em><?php echo $arinfo1[0]['keytitle'];?></em></td>
  </tr>
  <tr>
    <td height="24"><em><?php echo $arinfo1[0]['rdesc'];?></em></td>
  </tr>
  <tr>
    <td height="24"><em>
      <?php if($arinfo1[0]['street']!=""){?>
    Street: 
	<?php echo $arinfo1[0]['street'];
	echo '<br />';}?>
	<?php if($arinfo1[0]['city']!=""){?>
    City: 
	<?php echo $arinfo1[0]['city'];
	echo '<br />';}?>
	<?php if($arinfo1[0]['zipcode']!=""){?>
    Post Code: 
	<?php echo $arinfo1[0]['zipcode'];
	echo '<br />';}?>
	<?php if($arinfo1[0]['country_id']!=""){?>
    Country: 
	<?php echo $arinfo1[0]['country_id'];
	echo '<br />';}?>
	<?php if($arinfo1[0]['contact']!=""){?>
    Contact: 
	<?php echo $arinfo1[0]['contact'];
	}?>
	<?php if($arinfo1[0]['contact2']!=""){?>
    , 
	<?php echo $arinfo1[0]['contact2'];
	}?>
	<?php if($arinfo1[0]['contact3']!=""){?>
    , 
	<?php echo $arinfo1[0]['contact3'];
	}?>
	<?php if($arinfo1[0]['contact4']!=""){?>
    , 
	<?php echo $arinfo1[0]['contact4'];
	}?><br />
	<?php if($arinfo1[0]['web']!=""){?>
    Web Address: 
	<?php echo $arinfo1[0]['web'];
	echo '<br />';}?></em></td>
  </tr>
</table>

