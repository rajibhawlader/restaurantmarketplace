<?
require_once('lib/class.Db.php');
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
ob_start();
session_start();

$query = "SELECT * FROM delivery_area WHERE rid='".$_SESSION['rids']."' AND SUBSTRING(post_code, 1,4) = '".substr($_SESSION['customers_postcode'], 0, 4)."'";
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

?>
	<table width="90%" border="0" cellpadding="1" cellspacing="1" class="normal_text">	
	  <tr>
	    <td height="30" colspan="2" align="left" style="font-size:14px; font-weight:bold">Add New Address</td>
	    <td align="left">&nbsp;</td>
      </tr>
	  <tr>
	    <td width="9%" align="left">&nbsp;</td>
		<td width="33%" align="left"><strong>Address1 : </strong></td>
		<td width="58%" align="left"><input name="customers_address1" type="text"  id="customers_address1" value="" size="25"/> *</td>
	  </tr>
	  <tr>
	    <td align="left">&nbsp;</td>
		<td align="left"><strong>Address2 : </strong></td>
		<td align="left"><input name="customers_address2" type="text"  id="customers_address2" value="" size="25"/></td>
	  </tr>
	  <tr>
	    <td align="left">&nbsp;</td>
		<td align="left"><strong>State : </strong></td>
		<td align="left"><input name="customers_state" type="text"  id="customers_state" value="" size="25"/></td>
	  </tr>
	  <tr>
	    <td align="left">&nbsp;</td>
		<td align="left"><strong>City : </strong></td>
		<td align="left"><input name="customers_town" type="text"  id="customers_town" value="" size="25"/> *</td>
	  </tr>
	  <tr>
	    <td align="left">&nbsp;</td>
		<td align="left"><strong>Postcode : </strong></td>
		<td align="left"><input style="text-transform:uppercase;" name="customers_postcode" type="text"  id="customers_postcode" value="" size="5" maxlength="4"/> <input name="customers_postcode2" type="text" style="text-transform:uppercase;"  id="customers_postcode2" value="" size="5" maxlength="4"/> *</td>
	  </tr>
	  <tr>
	    <td align="left">&nbsp;</td>
		<td align="left"><strong>Country : </strong></td>
		<td align="left"><select name="customers_country" id="customers_country" style="width:150px">
          <option value="United Kingdom">United Kingdom</option>
        </select></td>
	  </tr>
	  <tr>
	    <td align="left">&nbsp;</td>
	    <td align="left"><strong>Address Label:</strong></td>
	    <td align="left"><input name="customers_add_label" type="text"  id="customers_add_label" value="" size="25"/> *</td>
      </tr>
	  <tr>
	    <td height="46" align="right">&nbsp;</td>
		<td align="right"><input type="hidden" name="samepost" id="samepost" value="<?=$samepost?>" /></td>
		<td align="left"><input type="button" name="submit" id="submit" value="Save Address" onclick="return validation();" /></td>
	  </tr>
	  </table>
