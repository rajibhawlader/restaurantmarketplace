<?php		
class customerLoginVerify {
	var $username = '';
	var $password = '';
	var $cloginstatus = false;
	
	function customerLoginVerify($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}
	
	function checkUser($db) 
	{
		$query = "select * from customers  where customers_email_address  = '$this->username' and customers_password   = '$this->password'";
		 
		$result = $db->execQuery($query);
		$arpostinfo = $db->resultArray($result);

		if (sizeof($arpostinfo)>0) 
		{
			if($arpostinfo[0]['status'] ==1){
		    $this->customers_id = $arpostinfo[0]['customers_id'];
			$this->customers_firstname = $arpostinfo[0]['customers_firstname'];
			$this->customers_lastname = $arpostinfo[0]['customers_lastname'];
			$this->customers_email_address = $arpostinfo[0]['customers_email_address'];
			$this->customers_address1 = $arpostinfo[0]['customers_address1'];
			$this->customers_address2 = $arpostinfo[0]['customers_address2'];
			$this->customers_town = $arpostinfo[0]['customers_town'];
			$this->customers_state = $arpostinfo[0]['customers_state'];
			$this->customers_country = $arpostinfo[0]['customers_country'];
			$this->customers_postcode = $arpostinfo[0]['customers_postcode'];
			
		    
			session_start();
			
			$_SESSION['customers_id']=$this->customers_id;
			$_SESSION['customers_firstname']=$this->customers_firstname;
			$_SESSION['customers_lastname']=$this->customers_lastname;
			$_SESSION['customers_email_address']=$this->customers_email_address;
			$_SESSION['customers_address1']=$this->customers_address1;
			$_SESSION['customers_address2']=$this->customers_address2;
			$_SESSION['customers_town']=$this->customers_town;	
			$_SESSION['customers_state']=$this->customers_state;
			$_SESSION['customers_country']=$this->customers_country;
			$_SESSION['customers_postcode']=$this->customers_postcode;					        
			return $cloginstatus = 1;    
			}elseif($arpostinfo[0]['status'] ==2){
				return 's';
			}else{
				return 'p';
			}
			
	    } 
		else 
		{
	    	return $loginstatus = false;
	    }
    }
}
?>