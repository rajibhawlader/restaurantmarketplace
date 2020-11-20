<script language="javascript"> 
function IsNumeric(sText)
{
   var ValidChars = "0123456789";
   var IsNumber=true;
   var Char;

 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
   }
function validation()
{

  if (document.edit.customers_firstname.value == "" )
  {
    alert("First Name is a required field.");
    document.edit.customers_firstname.focus();
    return false;
  }
  
   if (document.edit.customers_lastname.value == "" )
  {
    alert("Last Name is a required field.");
    document.edit.customers_lastname.focus();
    return false;
  }
  
 
  
  if (document.edit.customers_dob.value == "" )
  {
    alert("DOB is a required field.");
    document.edit.customers_dob.focus();
    return false;
  }
   if (document.edit.customers_email_address.value.indexOf("@") == -1 || document.edit.customers_email_address.value == "")
  {
    alert("Email is a required field!, may be you have missd @ symbol");
    document.edit.customers_email_address.focus();
    return false;
  }
  
  if (document.edit.customers_email_address.value.indexOf(".") == -1 || document.edit.customers_email_address.value == "")
  {
    alert("Email is a required field!, may be you have missd . symbol");
    document.edit.customers_email_address.focus();
    return false;
  }
   if (document.edit.customers_address1.value == "" )
  {
    alert("Customer Address is a required field.");
    document.edit.customers_address1.focus();
    return false;
  }
  
   if (document.edit.customers_town.value == "" )
  {
    alert("Town is a required field.");
    document.edit.customers_town.focus();
    return false;
  }
    if (document.edit.customers_postcode.value == "" )
  {
    alert("Post Code is a required field.");
    document.edit.customers_postcode.focus();
    return false;
  }
   if (document.edit.customers_telephone.value == "" )
  {
    alert("Phone No is a required field.");
    document.edit.customers_telephone.focus();
    return false;
  }
  if (document.edit.customers_password.value == "" )
  {
    alert("Password is a required field!");
    document.edit.customers_password.focus();
    return false;
  }
  
   if (document.edit.recustomers_password.value == "" )
  {
    alert("Re-Type Password is a required field!");
    document.edit.recustomers_password.focus();
    return false;
  }
  
  if (document.edit.recustomers_password.value!=document.edit.customers_password.value)
  {
    alert("Passwords don't match. Please try again");
	document.edit.c_password.value = "";
    document.edit.c_password.focus();
    return false;
  }
 
  return true;
  
}
</script>
<script type="text/JavaScript">
<!--



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<body>
<table width="710" border="0" align="right" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><?php  require_once('includes/account_home.html');?></td>
  </tr>
  <tr>
    <td height="10"></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
        <td><img src="images/red_box1_01.jpg" width="15" height="39" alt="" /></td>
        <td width="100%" background="images/red_box1_02.jpg" class="heading">Cart Contents</td>
        <td><img src="images/red_box1_03.jpg" width="14" height="39" alt="" /></td>
      </tr>
      <tr>
        <td background="images/red_box1_04.jpg">&nbsp;</td>
        <td valign="top" bgcolor="#9E0112"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellpadding="0" cellspacing="0" class="stitle">
              <tr>
                <td valign="top" bgcolor="#9E0112"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bodytext">
                  <?php
				
				$sql="Select * from customers where customers_id='".$_SESSION['customers_id']."'";
				$result=mysql_query($sql);
				$row=mysql_fetch_array($result);
				?>
                  <tr>
                    <td width="95%" valign="middle" align="center"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                      <tr>
                        <td align="center" valign="middle"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="bodytext">
                          <tr>
                            <td align="right" >&nbsp;</td>
                          </tr>
                          <tr>
                            <td align="center" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                              <tr>
                                <td colspan="2" valign="top"><table width="98%" border="0" align="center" cellpadding="0" cellspacing="0" class="bodytext">
                                  <tr>
                                    <td width="3%" >&nbsp;</td>
                                    <td width="95%" valign="middle" align="center" bgcolor="#F0F0F0"><div id="morderList">
                                      <?php  require_once('mcart.php');?>
                                    </div></td>
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
                      </tr>
                    </table>
                                </form></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td background="images/red_box1_06.jpg">&nbsp;</td>
      </tr>
      <tr>
        <td><img src="images/red_box1_07.jpg" width="15" height="17" alt="" /></td>
        <td background="images/red_box1_08.jpg"></td>
        <td><img src="images/red_box1_09.jpg" width="14" height="17" alt="" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <iframe width=174 height=189 name="gToday:normal:agenda.js" id="gToday:normal:agenda.js" src="calander/ipopeng" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;"></iframe>
</table>
