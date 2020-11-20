<?php		
class LoginVerify {
	var $username = '';
	var $password = '';
	var $loginstatus = false;
	
	function LoginVerify($username, $password) {
		$this->username = $username;
		$this->password = $password;
	}
	
	function checkUser($db) 
	{
		$query = "select * from userregistration,resturant  where userregistration.id=resturant.userid and email  = '$this->username' and password   = '$this->password' and status='1' and aactive='1'";
		
		$result = $db->execQuery($query);
		$arpostinfo = $db->resultArray($result);

		if (sizeof($arpostinfo)>0) 
		{
		    $this->userid = $arpostinfo[0]['id'];
			
		    
			session_start();
        	$_SESSION['userid'] = $this->userid;
							        
			return $loginstatus = true;    
	    } 
		else 
		{
	    	return $loginstatus = false;
	    }
    }
}
?>