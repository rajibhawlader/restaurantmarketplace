<?php

include("spaw/spaw.inc.php");
//$spaw1 = new SpawEditor("spaw1");



?>
<script language="javascript" src="../inc/js/formvalidation.js"></script>
<script language="javascript">
	function validate()
	{
		
			
		if(document.newsletterform.from.value=="")
		{
			alert("From Address is a required field!");
			return false;
		}
		if(document.newsletterform.subject.value=="")
		{
			alert("Subject is a required field!");
			return false;
		}
		
		if(document.newsletterform.pagecontent.value=="")
		{
			alert("Message is a required field!");
			return false;
		}
		
		//if(flag)	flag = validateBlank("newsletterform","pagecontent","Message is a required field!");

		//alert(document.newsletterform.testlive + " " +document.newsletterform.testlive.length);
		//alert(document.newsletterform.Submit2.value);
		if (document.newsletterform.Submit2.value=='Send Newsletter'){
		for(i=0;i<document.newsletterform.testlive.length;i++)
		{
//			alert(document.newsletterform.testlive(i).checked +" "+document.newsletterform.testlive(i).value);
			if(document.newsletterform.testlive(i).checked && document.newsletterform.testlive(i).value==0)
			{
				
				if (document.newsletterform.testemail.value.indexOf("@") == -1 || document.UserSignUp.UserEmail.value == ""){
					alert("Please insert a valid email address.");
					document.UserSignUp.UserEmail.focus()
					return false;
				}
				
				if (document.newsletterform.testemail.value.indexOf(".") == -1 || document.UserSignUp.UserEmail.value == ""){
					alert("Please insert a valid email address.");
					document.UserSignUp.UserEmail.focus()
					return false;
				}
			}
		}
//		alert(flag);
		}
		//if(flag) return true;
		//return false;
		
	}
</script>
<style type="text/css">
<!--
.style1 {color: #990000; 
font-family:Verdana, Arial, Helvetica, sans-serif; 
font-size:11px;
}
-->
</style>

 <table border="0" cellspacing="" cellpadding="2" width="100%" align="center"> 
  <tr> 
     
      <td width="100%" > <table width="100%" border="0" cellspacing="2" cellpadding="2" class="bodytext"> 
          <tr> 
             <td colspan="3" align="center" ><br /> 
              <strong><span class="style1">News</span><span class="bodytext">letter</span> </strong> </td> 
          </tr> 
          <tr>
		  <form action="index.php?action=newsletter" method="post" name="newsletter">
            <td align="right" class="bodytext"><strong>Sample:</strong></td>
            <td colspan="2" align="left"><strong>
			<?php
			$query = "Select id, newsSubject from newsletter ;";
			$result = mysql_query($query);// or echo(mysql_error());
			?>
              <select name="newsSubject" onchange="submit();" class="bodytext">
                <option value=""  >New Newsletter</option>
				<? 
					while($rs = mysql_fetch_assoc($result)){
						echo '<option value="'.$rs['id'].'"';
						if ($_REQUEST['newsSubject']==$rs['id']) echo ' selected ';
						echo'>'.$rs['newsSubject'].'</option>';
					}
				?>
              </select>
            </strong></td>
			</form>
          </tr>
		  <?php
		  	if (!$_REQUEST['newsSubject']==""){
				$sSQL = "Select * from newsletter where id=".$_REQUEST['newsSubject'].";";
				$result = mysql_query($sSQL);
				while($rs1 = mysql_fetch_assoc($result)){
					$newsId=$rs1['id'];
					$newsFrom=$rs1['newsFrom'];
					$newsSubject=stripslashes($rs1['newsSubject']);
					$newsDesc=stripslashes($rs1['newsDesc']);
				}
			}
		  ?>
		  <form method="post" name="newsletterform" action="index.php?action=newsletter_save_and_send" onSubmit="return validate();">
          <tr> 
             <td width="13%" align="right" class="bodytext"><strong>From:</strong> </td> 
             <td colspan="2" align="left"> <input type="text" name="from" size="35" value="<?php echo $newsFrom;?>"  class="textbox"/> </td> 
          </tr> 
          <tr> 
             <td width="13%" align="right" class="bodytext"><strong>Subject: </strong> </td> 
             <td colspan="2" align="left"> 
			 	<input type="text" name="subject" id="subject" value="<?php echo $newsSubject;?>" class="textbox">			</td> 
          </tr> 
          <tr>
            <td align="right"  class="bodytext"><strong>Message: </strong></td>
            <td colspan="2" align="left"><?php 
					//$sw = new SPAW_Wysiwyg('pagecontent' /*name*/,isset($get_content)?stripslashes($get_content):'' /*value*/);
					//$sw = new SpawEditor('newsletter' /*name*/,isset($newsletter)?stripslashes($pagecontent):'' /*value*/);
					//$sw = new SpawEditor('newsletter' /*name*/,isset($newsletter)?stripslashes($newsletter):'' /*value*/);
					//$sw->show();
					$sw = new SpawEditor('pagecontent',$newsDesc);
					$sw->show();
				?></td>
          </tr>
          <tr> 
             <td width="13%" align="left">&nbsp;</td> 
             <td width="31%" align="left"><!--<input type="radio" name="testlive" value="0" checked>Test&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="submit" type="submit" value="Send Newsletter" />  -->
			  <input type="hidden" name="sendaction" value="send_newsletter">
			  <input name="testlive" type="radio" value="0">Test&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  <input type="radio" name="testlive" value="1" />
			  Real </td> 
             <td width="56%" align="left">&nbsp;</td>
          </tr>
		  <tr> 
             <td width="13%" align="left">&nbsp;</td> 
             <td colspan="2" align="left">&nbsp;</td> 
          </tr>
          <tr> 
		   <td width="13%" align="left">&nbsp;</td> 
             <td width="31%"  align="left">Test Address: &nbsp;<INPUT TYPE="text" NAME="testemail" value="" class="textbox">			</td> 
             <td width="56%"  align="left"><input type="submit" name="Submit" value="Send Newsletter" class="button" />
            <input type="submit" name="Submit" value="Save Newsletter" class="button"/> 
			 <input type="hidden" name="newsId" value="<?php echo $newsId;?>" />			 </td>
          </tr> 
		  
		  <tr> 
		   <td colspan="3" align="left"><div align="center"><?php if(!$message=="") echo $message;?></div></td> 
            </tr> 
		  </form> 
        </table>	  </td>
     
   </tr> 
</table> 

