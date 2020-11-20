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
		$query = "select * from tbladmininfo  where UserID  = '$this->username' and UserPassword   = '$this->password'";
		
		$result = $db->execQuery($query);
		$arpostinfo = $db->resultArray($result);

		if (sizeof($arpostinfo)>0) 
		{
		    $this->userid = $arpostinfo[0]['ID'];
			$this->usertype = $arpostinfo[0]['usertype'];
		    
			session_start();
        	$_SESSION['adminuserid'] = $this->userid;
			$_SESSION['adminusertype'] = $this->usertype;				        
			return $loginstatus = true;    
	    } 
		else 
		{
	    	return $loginstatus = false;
	    }
    }
}
?>