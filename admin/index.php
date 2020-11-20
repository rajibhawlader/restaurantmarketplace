<?php
ob_start();
session_start();
if (isset($_REQUEST["action"]) && !empty($_REQUEST["action"])) { require_once('lib/class.Db.php');
require_once('lib/functions.php');	
	
require_once('config.php');
    $db = new Db(hostname, username, password, databasename);
    $tabm=$_REQUEST['tabm'];
	switch ($_REQUEST["action"]) {
     
	 case 'login_verify':
		require_once('lib/class.LoginVerify.php');
		  if($_SESSION['image_random_value']==md5(strtoupper($_REQUEST['vCode'])) and $_REQUEST['vCode']!="")
		  {
		 // echo "hi";
				$alphanum  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				$rand = substr(str_shuffle($alphanum), 0, 6);
				$loginverify = new LoginVerify($_REQUEST["email"], MD5($_REQUEST["txtPassword"]));
				$loginstatus = $loginverify->checkUser($db);
				
				if ($loginstatus == true) {
				
					$arreturnvalue = array('body' => 'welcome.html');
				} else {
					$arreturnvalue = array('body' => 'login.html',
										'message' => 'Invalid Username or Password!');
				}
				
		  }
		  else
			{	

			//	echo $_SESSION['image_random_value'];
						
				$_SESSION['image_random_value']='';
				$vc=1;
				$arreturnvalue = array('body' => 'login.html',
									'message' => ' ');
			}
		
		
        break;
		
	//@shafiq - New Menu
	 case 'add_cetegory':
		 
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'add_menu.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		

		 case 'edit_cetegory':
		 
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'edit_menu.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	//	
		
	 case 'home':
	 	if($_SESSION['adminuserid']!=""){
        
		$arreturnvalue = array('body' => 'welcome.html');
		
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;
	
	case 'edit_profile':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'edit_profile.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
	 case 'update':
		if($_SESSION['adminuserid']!=""){
			
        	 $ID = $_SESSION['adminuserid'];
			$newPass= $_REQUEST["UserPassword"];
			if ($newPass!="")
			{
			 $query = "update tbladmininfo SET 
								UserName = '".mysql_escape_string($_REQUEST["UserName"])."',
								UserID  ='".mysql_escape_string($_REQUEST["UserID"])."',
								UserPassword = '".MD5($newPass)."'
								WHERE ID = '$ID'";	
			}else
			{
			 $query = "update tbladmininfo SET 
								UserName = '".mysql_escape_string($_REQUEST["UserName"])."',
								UserID  ='".mysql_escape_string($_REQUEST["UserID"])."'
								WHERE ID = '$ID'";	
			}					
		
		
		$result = $db->execQuery($query);
        if ($result) {
            $arreturnvalue = array('body' => 'edit_profile.html',
	                            'message' => 'Profile updated Successfully!');
        } else {
            $arreturnvalue = array('body' => 'edit_profile.html',
	                            'message' => 'Profile update Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
		
        break;	
		
		case 'create_user':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'create_user.html');
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
		
	// Customer Management Start
	
	case 'existing_customer':
		if($_SESSION['adminuserid']!=""){
        	$arreturnvalue = array('body' => 'existing_customer.html');
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;

	case 'customer_view':
		if($_SESSION['adminuserid']!=""){
        	$arreturnvalue = array('body' => 'customer_edit.html');
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
	

	case 'update_customer':
		$cids=$_REQUEST['cid'];
		$query1 = "SELECT customers_email_address FROM  customers  where customers_id !='$cids' and customers_email_address='".mysql_escape_string($_REQUEST["customers_email_address"])."'";
		$chk=$db->checkExists($query1);
		
		if (!$chk) 
		{

		$cdob=$_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day'];
		$postcode=mysql_escape_string($_REQUEST["customers_postcode"])." ".mysql_escape_string($_REQUEST["customers_postcode2"]);
		if($_REQUEST['newcustomers_password']!="")
		{
		$query = "Update  customers  set customers_firstname='".mysql_escape_string($_REQUEST["customers_firstname"])."', customers_lastname='".mysql_escape_string($_REQUEST["customers_lastname"])."', customers_dob='".$cdob."',customers_email_address='".mysql_escape_string($_REQUEST["customers_email_address"])."', customers_address1='".mysql_escape_string($_REQUEST["customers_address1"])."', customers_address2='".mysql_escape_string($_REQUEST["customers_address2"])."', customers_town='".mysql_escape_string($_REQUEST["customers_town"])."',customers_state='".mysql_escape_string($_REQUEST["customers_state"])."', customers_country='".mysql_escape_string($_REQUEST["customers_country"])."',customers_postcode='".$postcode."', customers_telephone='".mysql_escape_string($_REQUEST["customers_telephone"])."',customers_fax='".mysql_escape_string($_REQUEST["customers_fax"])."', customers_password='".mysql_escape_string($_REQUEST["newcustomers_password"])."' , customers_ccode='".mysql_escape_string($_REQUEST["customers_ccode"])."', customers_howhear='".mysql_escape_string($_REQUEST["customers_howhear"])."', customers_add_label='".mysql_escape_string($_REQUEST["customers_add_label"])."' where customers_id='$cids'";
		}else
		{
		 $query = "Update  customers  set customers_firstname='".mysql_escape_string($_REQUEST["customers_firstname"])."', customers_lastname='".mysql_escape_string($_REQUEST["customers_lastname"])."', customers_dob='".$cdob."',customers_email_address='".mysql_escape_string($_REQUEST["customers_email_address"])."', customers_address1='".mysql_escape_string($_REQUEST["customers_address1"])."', customers_address2='".mysql_escape_string($_REQUEST["customers_address2"])."', customers_town='".mysql_escape_string($_REQUEST["customers_town"])."',customers_state='".mysql_escape_string($_REQUEST["customers_state"])."', customers_country='".mysql_escape_string($_REQUEST["customers_country"])."',customers_postcode='".$postcode."', customers_telephone='".mysql_escape_string($_REQUEST["customers_telephone"])."',customers_fax='".mysql_escape_string($_REQUEST["customers_fax"])."', customers_ccode='".mysql_escape_string($_REQUEST["customers_ccode"])."', customers_howhear='".mysql_escape_string($_REQUEST["customers_howhear"])."', customers_add_label='".mysql_escape_string($_REQUEST["customers_add_label"])."' where customers_id='$cids'";
			}
			//echo $query;
			
			$result = $db->execQuery($query);
			
			
			
				$_SESSION['customers_email_address']=mysql_escape_string($_REQUEST["customers_email_address"]);	
				$_SESSION['customers_address1']=mysql_escape_string($_REQUEST["customers_address1"]);	
				$_SESSION['customers_address2']=mysql_escape_string($_REQUEST["customers_address2"]);	
				$_SESSION['customers_town']=mysql_escape_string($_REQUEST["customers_town"]);	
				$_SESSION['customers_state']=mysql_escape_string($_REQUEST["customers_state"]);	
				$_SESSION['customers_country']=mysql_escape_string($_REQUEST["customers_country"]);	
				$_SESSION['customers_postcode']=mysql_escape_string($_REQUEST["customers_postcode"]);	
				
				$pageName="Registration";
				if(!$result)
				{
					$arreturnvalue = array(
											'body' => 'customer_edit.html',
											'message' => 'Update Failed'
										   );
				}
				else
				{
					 $arreturnvalue = array(
												'body' => 'customer_edit.html',
												'message' => 'Update Successful'
											);
				}
					
		 }
		 else 
		 {
				$arreturnvalue = array(
										'body' => 'customer_edit.html',
										'message' => 'This Email address already registered,please try another');
		 }

	break;

	case 'address_book':
			if($_REQUEST['cid']!="")
			{
				$arreturnvalue = array('body' => 'address_book.html');
			}
			else
			{
				$arreturnvalue = array('body' => 'existing_customer.html');
			}
			break;
	
	case 'update_address':
							$cid=$_REQUEST['cid'];
							$postcode=mysql_escape_string($_REQUEST["customers_postcode"])." ".mysql_escape_string($_REQUEST["customers_postcode2"]);
							if($_REQUEST['new_address']=="store new")
							{
								$query = "INSERT INTO  customer_address( `ca_id` , `customers_id` , `customers_address1` , `customers_address2` , `customers_town` , `customers_state` , `customers_country` , `customers_postcode` , `customers_add_label` ) VALUES ('', '$cid', '".mysql_escape_string($_REQUEST["customers_address1"])."', '".mysql_escape_string($_REQUEST["customers_address2"])."', '".mysql_escape_string($_REQUEST["customers_town"])."', '".mysql_escape_string($_REQUEST["customers_state"])."', '".mysql_escape_string($_REQUEST["customers_country"])."', '".$postcode."', '".mysql_escape_string($_REQUEST["customers_add_label"])."')";
								$result = $db->execQuery($query);
								
								$pageName="Registration";
								if(!$result)
								{
									$arreturnvalue = array(
															'body' => 'address_book.html',
															'message' => 'New Address Store Failed'
														   );
								}
								else
								{
									 $arreturnvalue = array(
																'body' => 'address_book.html',
																'message' => 'New Address Store Successful'
															);
								}
							}
							else
							{
								if($_REQUEST['ca_id']=="")
								{
								$query = "Update  customers  set customers_address1='".mysql_escape_string($_REQUEST["customers_address1"])."', customers_address2='".mysql_escape_string($_REQUEST["customers_address2"])."', customers_town='".mysql_escape_string($_REQUEST["customers_town"])."',customers_state='".mysql_escape_string($_REQUEST["customers_state"])."', customers_country='".mysql_escape_string($_REQUEST["customers_country"])."',customers_postcode='".$postcode."',  customers_add_label='".mysql_escape_string($_REQUEST["customers_add_label"])."' where customers_id='$cid'";
								}else
								{
								 $query = "Update  customer_address set customers_address1='".mysql_escape_string($_REQUEST["customers_address1"])."', customers_address2='".mysql_escape_string($_REQUEST["customers_address2"])."', customers_town='".mysql_escape_string($_REQUEST["customers_town"])."',customers_state='".mysql_escape_string($_REQUEST["customers_state"])."', customers_country='".mysql_escape_string($_REQUEST["customers_country"])."',customers_postcode='".$postcode."',  customers_add_label='".mysql_escape_string($_REQUEST["customers_add_label"])."' where customers_id='$cid' and ca_id='".$_REQUEST['ca_id']."'";
								}
								//echo $query;
								
								$result = $db->execQuery($query);
								
									$pageName="Registration";
									if(!$result)
									{
										$arreturnvalue = array(
																'body' => 'address_book.html',
																'message' => 'Update Failed'
															   );
									}
									else
									{
										 $arreturnvalue = array(
																	'body' => 'address_book.html',
																	'message' => 'Update Successful'
																);
									}
							}
					
		    break;
	case 'address_delete':
							if($_REQUEST['ca_id']!="")
							{
								$query = "DELETE FROM customer_address WHERE ca_id = '".$_REQUEST["ca_id"]."'";
								$result = $db->execQuery($query);
								
								$pageName="Registration";
								if(!$result)
								{
									$arreturnvalue = array(
															'body' => 'address_book.html',
															'message' => 'Address Delete Failed'
														   );
								}
								else
								{
									 $arreturnvalue = array(
																'body' => 'address_book.html',
																'message' => 'Address Delete Successful'
															);
															$_REQUEST["ca_id"]="";
								}
							}
	break;
	
	case 'customer_order_history':
			if($_REQUEST['cid']!="")
			{
				$arreturnvalue = array('body' => 'customer_order_history.html');
			}
			else
			{
				$arreturnvalue = array('body' => 'existing_customer.html');
			}
			break;
	case 'customer_ordered_details':
		if($_SESSION['adminuserid']!=""){
        	$arreturnvalue = array('body' => 'customer_ordered_details.html');
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
	// end customper panel
	case 'customer_order':
		if($_SESSION['adminuserid']!=""){
        	$arreturnvalue = array('body' => 'customer_orderlist.html');
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
	
	case 'customer_orderdetails':
		if($_SESSION['adminuserid']!=""){
        	$arreturnvalue = array('body' => 'customer_orderdetails.html');
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
	
// Customer Management End	
		
    case 'insert_user':
		if($_SESSION['adminuserid']!=""){
		
        $query = "SELECT ID FROM tbladmininfo  where UserID  = '".$_REQUEST["UserID"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
			
            $query = "INSERT INTO tbladmininfo(`ID`,`UserName` ,`UserID` ,`UserPassword` ,`usertype` ,`submitdate`) VALUES ('','".mysql_escape_string($_REQUEST["UserName"])."','".mysql_escape_string($_REQUEST["UserID"])."',MD5('".mysql_escape_string($_REQUEST["UserPassword"])."'),'0','$d')";
			
            $result = $db->execQuery($query);
                if ($result) {
                        $arreturnvalue = array('body' => 'create_user.html',
	                                        'message' => 'User Created Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'create_user.html',
	                                    'message' => 'User Creattion Failed!');
                }
        } else {
        	$arreturnvalue = array('body' => 'create_user.html',
	                            'message' => 'User Already Exists!.');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
	
	        break;
	
	case 'existing_users':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'existing_users.html');
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
		
	case 'edit_user_profile':
   		if($_SESSION['adminuserid']!=""){
        
        $arreturnvalue = array('body' => 'edit_user_profile.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;
	
	
	
	
    case 'update_user':
	
		
		if($_SESSION['adminuserid']!=""){
        
        if (isset($_REQUEST["adminID"]) && !empty($_REQUEST["adminID"])) {
       	$ID = $_REQUEST["adminID"];
       
        $query = "update tbladmininfo SET 
								
								
								UserName = '".mysql_escape_string($_REQUEST["UserName"])."',
								UserID  ='".mysql_escape_string($_REQUEST["UserID"])."',
								UserPassword = '".mysql_escape_string($_REQUEST["UserPassword"])."'
								WHERE ID = '$ID'";	
		
		$result = $db->execQuery($query);
        if ($result) {
            $arreturnvalue = array('body' => 'existing_users.html',
	                            'message' => 'Profile updated Successfully!');
        } else {
            $arreturnvalue = array('body' => 'existing_users.html',
	                            'message' => 'Profile update Failed!');
        }
		
		}
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
		
        break;
	
    case 'delete_user':
	
		if($_SESSION['adminuserid']!=""){
        if (isset($_REQUEST["ID"]) && !empty($_REQUEST["ID"])) {
            $query = "DELETE FROM tbladmininfo WHERE ID = '".$_REQUEST["ID"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'existing_users.html',
	                        'message' => 'User Deleted Successfully!');
            } else {
                $arreturnvalue = array('body' => 'existing_users.html',
	                        'message' => 'User Deletion Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'existing_users.html',
        	                    'message' => 'User Deletion Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
	
	case 'new_page_post':
	 if($_SESSION['adminuserid']!=""){
	  	$arreturnvalue = array('body' => 'new_page_post.html');
		
	 }else{
			
			$arreturnvalue = array('body' => 'login.html');
			} 	
		break;
		
	case 'insert_page':
	if($_SESSION['adminuserid']!=""){
        $query = "SELECT post_title FROM  cms_posts  where post_title= '".$_REQUEST["post_title"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
		$queryc = "SELECT count(*) as pos FROM  cms_posts";
       $resultc = $db->execQuery($queryc);
			$arstinfoc = $db->resultArray($resultc);
			$pos=$arstinfoc[0]["pos"];
			$pos=$pos+1;
        if (!$success) {
			
            $query = "INSERT INTO  cms_posts(`ID`,`post_date`,`post_content`,`post_title`,`post_status`,`post_modified`,`post_parent`,`menu_order`,`meta_info`) VALUES ('','$d','".mysql_escape_string($_REQUEST["post_content"])."','".mysql_escape_string($_REQUEST["post_title"])."','Publish','','','$pos')";
			//echo $query;
			
            $result = $db->execQuery($query);
		  	$postid = $db->lastInsert($result);
					$pageName="Property Type";
                        $arreturnvalue = array('body' => 'new_page_post.html',
	                            'message' => 'This Page posted successfully');
		
        } else {
			
		
			
        	$arreturnvalue = array('body' => 'new_page_post.html',
	                            'message' => 'This Page posted has already been added. Please Change title.');
        }
	}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
	        break;	
	
	case 'cms_page_list':
	 if($_SESSION['adminuserid']!=""){
	  	$arreturnvalue = array('body' => 'cms_page_list.html');
		
	 }else{
			
			$arreturnvalue = array('body' => 'login.html');
			} 	
		break;
		
	case 'change_morder':
	 if($_SESSION['adminuserid']!=""){
			
		$newNo=$_REQUEST['newOrderNo'];
		$oldNo=$_REQUEST['oldOrderNo'];
		$query="SELECT * FROM cms_posts where  menu_order =".$oldNo."";
		$result=$db->execQuery($query);
		$arrinfo=$db->resultArray($result);
		if (sizeof($arrinfo)>0){
			$groupID=$arrinfo[0]['ID'];
			$query="Update cms_posts set menu_order=".$newNo." where ID=".$groupID."";
			$result=$db->execQuery($query);
		}
			$query="select * from cms_posts where ID <>".$groupID." order by menu_order";
			$result=$db->execQuery($query);
			$arrayinfo=$db->resultArray($result);
			$sL=1;
			for($i=0; $i<sizeof($arrayinfo); $i++){
				if ($sL==$newNo){
					$sL=$sL+1;
				}
				$sID=$arrayinfo[$i]['ID'];
				$query="Update cms_posts set menu_order=".$sL." where ID=".$sID."";
				$result=$db->execQuery($query);
				$sL=$sL+1;
			}
		$arreturnvalue = array('body' => 'cms_page_list.html',
	                            'message' => 'Updated Successfully!');  
								
		 }else{
			
			$arreturnvalue = array('body' => 'login.html');
			} 						
		break;	
	
	 case 'change_page_status':
		if($_SESSION['adminuserid']!=""){
			
        	 $ID = $_SESSION['adminuserid'];
			
			 $query = "update cms_posts SET 
								post_status = 'Publish' WHERE ID = '".mysql_escape_string($_REQUEST["ID"])."'";	
								
		
		
		$result = $db->execQuery($query);
        if ($result) {
            $arreturnvalue = array('body' => 'cms_page_list.html',
	                            'message' => 'Page updated Successfully!');
        } else {
            $arreturnvalue = array('body' => 'cms_page_list.html',
	                            'message' => 'Page update Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
		
        break;	
	
	case 'cms_page_del':
	
       if($_SESSION['adminuserid']!=""){
        if (isset($_REQUEST["ID"]) && !empty($_REQUEST["ID"])) {
		
		
		
            $query = "DELETE FROM cms_posts WHERE ID= '".$_REQUEST["ID"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'cms_page_list.html',
	                        'message' => 'CMS Page Deleted Successfully!');
            } else {
                $arreturnvalue = array('body' => 'cms_page_list.html',
	                        'message' => 'CMS Page Deletion Failed!');
            }
			
        } else {
        	$arreturnvalue = array('body' => 'cms_page_list.html',
        	                    'message' => 'CMS Page Deletion Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
	        break;	
			
case 'cms_page_view':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'cms_page_view.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
 case 'update_cms_page':
		if($_SESSION['adminuserid']!=""){
			
        	 $d=date('Y-m-d H:m:s');
			
			 $query = "update cms_posts SET 
								post_content = '".mysql_escape_string($_REQUEST["post_content"])."',
								post_title  ='".mysql_escape_string($_REQUEST["post_title"])."',
								meta_info  ='".mysql_escape_string($_REQUEST["meta_content"])."', post_status = 'Publish',post_modified='$d'
								WHERE ID = '".mysql_escape_string($_REQUEST["ID"])."'";	
								
		
		
		$result = $db->execQuery($query);
        if ($result) {
            $arreturnvalue = array('body' => 'cms_page_list.html',
	                            'message' => 'Page updated Successfully!');
        } else {
            $arreturnvalue = array('body' => 'cms_page_list.html',
	                            'message' => 'Page update Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
		
        break;	
	//---------------------------------Restaurant Management-----------------------
		case 'menu_admin':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'menu_admin.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
  		
		 case 'insert_new_menutitle':
		if($_SESSION['adminuserid']!=""){
		
        $query = "SELECT * FROM menu_admin  where menutitle  = '".$_REQUEST["menutitle"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
			
            $query = "INSERT INTO menu_admin(`mid`,`menutitle`) VALUES ('','".mysql_escape_string($_REQUEST["menutitle"])."')";
			
            $result = $db->execQuery($query);
                if ($result) {
                        $arreturnvalue = array('body' => 'menu_admin.html',
	                                        'message' => 'Category Created Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'menu_admin.html',
	                                    'message' => 'Category Creattion Failed!');
                }
        } else {
        	$arreturnvalue = array('body' => 'menu_admin.html',
	                            'message' => 'Category Already Exists!.');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
	
	        break;
	
		case 'menutitle_del':
	
		if($_SESSION['adminuserid']!=""){
        if (isset($_REQUEST["mid"]) && !empty($_REQUEST["mid"])) {
            $query = "DELETE FROM menu_admin WHERE mid = '".$_REQUEST["mid"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'menu_admin.html',
	                        'message' => 'Category Deleted Successfully!');
            } else {
                $arreturnvalue = array('body' => 'menu_admin.html',
	                        'message' => 'Category Deletion Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'menu_admin.html',
        	                    'message' => 'Category Deletion Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
		
		case 'edit_menutitle':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'edit_menutitle.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
			
		case 'orderdetails':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'orderdetails.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	
	case 'allpaidorderdetails':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'allpaidorderdetails.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	
	case 'update_status':
		if($_SESSION['adminuserid']!=""){
		if (isset($_REQUEST["orid"]) && !empty($_REQUEST["orid"]))
		{
		
	if($_POST['update_method'] == "Update"){
		$query = "Update customer_order set pay_mathod='".$_REQUEST['pay_mathod']."', ccfee='' WHERE orid= '".$_REQUEST["orid"]."'";
		if($db->execQuery($query)){
			$upate_msg = 'Payment Method Changed.';
		}else{
			$upate_msg = 'Payment Method Change Failed!';
		}	
		 $arreturnvalue = array('body' => 'orderdetails.html',
	                        'message' => $upate_msg);	
	}else{					
		$order_status = array("Pending", "Confirmed", "Concluded", "Canceled by Customer", "Rejected by Restaurant", "Bad Order");
		
		 $query = "Update customer_order set order_status='".$_REQUEST['order_status']."' WHERE orid= '".$_REQUEST["orid"]."'";
		
            $result = $db->execQuery($query);
            if ($result) {
	if($_REQUEST['notification'] == 1){
		$cuid=$_REQUEST['customer_id'];	
		$sqlc="Select * from customers where customers_id=$cuid";
		$resultc=mysql_query($sqlc);
		$customer=mysql_fetch_object($resultc);
// _sending mail
		$message = '<div style="border:1px solid #6a0004; background-color:#F4F2EE;width:700px;padding:0px 20px 0px 20px;"><div style="padding:20px;background-color:#FFFFFF;"><table width="100%" bgcolor="#FFFFFF" cellpadding="3" cellspacing="3" style="margin:0px auto;text-align:center; font-size:12px; font-weight:normal; font-family:verdana,arial;" border="0">';		
		$message .= '<tr style="background:#FFF url(\'http://www.indianfoodsonline.co.uk/mail/logo_bg.gif\') repeat-x bottom;"><td colspan="2" style="padding:0px;color:#000000;" align="right"><img style="float:left;" src="http://www.indianfoodsonline.co.uk/mail/logo.jpg" /><h2>Order Notification</h2> <div style="background-color:#fff;font-size:11px;text-align:right;">'. date('d/m/Y H:i:s A') .'&nbsp; </div></td></tr>';
		$message .= '<tr><td align="left" colspan="2">** Do not reply to this e-mail as you will not receive a response **</td></tr>';	
		$message .= '<tr><td align="left" colspan="2"><div style="color:#c73810; font-weight:bold; font-size:17px;">Welcome to Indianfoodsonline.co.uk!</div></td></tr>';	
		$message .= '<tr><td align="left" colspan="2"><br />Hello '.$customer->customers_firstname.' '.$customer->customers_lastname.', <p style="padding:0px 10px 20px 0px;margin:0px;"><br />Your order ID#'.order_id($_REQUEST["orid"]).' at '.$_SESSION[rname].' has been '.$order_status[$_REQUEST['order_status']].'.</p></td></tr>';	
		
		$message .= '<tr><td align="left" colspan="2"><br /><br><br></td></tr>';
		$message .= '<tr><td align="left" colspan="2"><br />Thanks <br />Indianfoodsonline.co.uk<br /><br /></td></tr>';	
		$message .= '<tr bgcolor="#6a0004" height="5"><td align="left" colspan="2"></td></tr>';
		$message .= '</table></div></div>';
		
			
		$body_txt = "<html><head><title>Auto generated mail</title>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
		</head><body><font face=verdana color=#000000 size=2>$message</font></body></html>";
		$org = 'Indianfoodsonline'; // organization name
		$email = "NoReply@indianfoodsonline.co.uk"; // sender email
		$send_to = $customer->customers_email_address; // reciever email	
		$subject = "Order status notification";
		require_once("../mail/htmlMimeMail5.php");

		$mail = new htmlMimeMail5();
	
		$mail->setFrom("$org<$email>");
		
		$mail->setSubject($subject);

		$mail->setPriority('high');
		$mail->setHTML($body_txt);
		$mail->send(array($send_to));
	} // end sending email notification to customer after changeing order status
				
            	$arreturnvalue = array('body' => 'orderdetails.html',
	                        'message' => 'Status Changed!');
            } else {
                $arreturnvalue = array('body' => 'orderdetails.html',
	                        'message' => 'Status Change Failed!');
            }
			
		
       
	   	}
		
		
		}
		else {
                $arreturnvalue = array('body' => 'orderdetails.html',
	                        'message' => 'Status Change Failed!');
            }
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	case 'paystatus_update':
		if($_SESSION['adminuserid']!=""){
		 if($_REQUEST[pg]=="ro")
            {
            $bod="retaurant_orderlist.html";
            }else if($_REQUEST[pg]=="oao")
            {
            $bod="online_acc_order.html";
            }else if($_REQUEST[pg]=="onaop")
            {
            $bod="online_acc_order_ponline.html";
            }
		    else if($_REQUEST[pg]=="allro")
            {
            $bod="allretaurant_orderlist.html";
            }else if($_REQUEST[pg]=="ofaop")
            {
            $bod="ofline_acc_order_ponline.html";
            }else
            {
            $bod="retaurant_orderlist.html";
            }
		  if (isset($_REQUEST["orid"]) && !empty($_REQUEST["orid"]) && !empty($_REQUEST["ps"]))
		{
        if($_REQUEST["ps"]=="y")
        {
        $vl=1;
        }
        else
        {
        $vl=0;
        }
		 $query = "Update customer_order set pstatus='$vl' WHERE orid= '".$_REQUEST["orid"]."'";
		
            $result = $db->execQuery($query);
           
            if ($result) {
            	$arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Changed!');
            } else {
                $arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Change Failed!');
            }
       
		}
		else {
                $arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Change Failed!');
            }
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	

		case 'delevary_update':
		if($_SESSION['adminuserid']!=""){
		 if($_REQUEST[pg]=="ro")
            {
            $bod="retaurant_orderlist.html";
            }else if($_REQUEST[pg]=="oao")
            {
            $bod="online_acc_order.html";
            }else if($_REQUEST[pg]=="onaop")
            {
            $bod="online_acc_order_ponline.html";
            }
		else if($_REQUEST[pg]=="allro")
            {
            $bod="allretaurant_orderlist.html";
            }	
		else if($_REQUEST[pg]=="ofaop")
            {
            $bod="ofline_acc_order_ponline.html";
            }else
            {
            $bod="retaurant_orderlist.html";
            }
		  if (isset($_REQUEST["orid"]) && !empty($_REQUEST["orid"]) && !empty($_REQUEST["ds"]))
		{
        if($_REQUEST["ds"]=="y")
        {
        $vl=1;
        }
        else
        {
        $vl=0;
        }
		 $query = "Update customer_order set dstatus='$vl' WHERE orid= '".$_REQUEST["orid"]."'";
		
            $result = $db->execQuery($query);
           
            if ($result) {
            	$arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Changed!');
            } else {
                $arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Change Failed!');
            }
       
		}
		else {
                $arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Change Failed!');
            }
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	case 'comfee_update':
		if($_SESSION['adminuserid']!=""){
		 if($_REQUEST[pg]=="ro")
            {
            $bod="retaurant_orderlist.html";
            }else if($_REQUEST[pg]=="oao")
            {
            $bod="online_acc_order.html";
            }else if($_REQUEST[pg]=="onaop")
            {
            $bod="online_acc_order_ponline.html";
            }else if($_REQUEST[pg]=="ofaop")
            {
            $bod="ofline_acc_order_ponline.html";
            }else if($_REQUEST[pg]=="am")
            {
            $bod="accounts_mng.html";
            }else
            {
            $bod="retaurant_orderlist.html";
            }
		  if (isset($_REQUEST["orid"]) && !empty($_REQUEST["orid"]) && !empty($_REQUEST["ps"]))
		{
        if($_REQUEST["ps"]=="y")
        {
        $vl=1;
        }
        else
        {
        $vl=0;
        }
		 $query = "Update customer_order set com_fee_status='$vl' WHERE orid= '".$_REQUEST["orid"]."'";
		
            $result = $db->execQuery($query);
           
            if ($result) {
            	$arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Changed!');
            } else {
                $arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Change Failed!');
            }
       
		}
		else {
                $arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Change Failed!');
            }
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		case 'handfee_update':
		if($_SESSION['adminuserid']!=""){
		 if($_REQUEST[pg]=="ro")
            {
            $bod="retaurant_orderlist.html";
            }else if($_REQUEST[pg]=="oao")
            {
            $bod="online_acc_order.html";
            }else if($_REQUEST[pg]=="onaop")
            {
            $bod="online_acc_order_ponline.html";
            }else if($_REQUEST[pg]=="ofaop")
            {
            $bod="ofline_acc_order_ponline.html";
            }else if($_REQUEST[pg]=="am")
            {
            $bod="accounts_mng.html";
            }else
            {
            $bod="retaurant_orderlist.html";
            }
		  if (isset($_REQUEST["orid"]) && !empty($_REQUEST["orid"]) && !empty($_REQUEST["ps"]))
		{
        if($_REQUEST["ps"]=="y")
        {
        $vl=1;
        }
        else
        {
        $vl=0;
        }
		 $query = "Update customer_order set hnd_fee_status='$vl' WHERE orid= '".$_REQUEST["orid"]."'";
		
            $result = $db->execQuery($query);
           
            if ($result) {
            	$arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Changed!');
            } else {
                $arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Change Failed!');
            }
       
		}
		else {
                $arreturnvalue = array('body' => $bod,
	                        'message' => 'Status Change Failed!');
            }
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	case 'update_new_menutitle':
		if($_SESSION['adminuserid']!=""){
			
        	 $ID = $_SESSION['adminuserid'];
			
			 $query = "Update menu_admin  set menutitle='".mysql_escape_string($_REQUEST["menutitle"])."' where mid='".$_REQUEST["mid"]."'";	
								
		$result = $db->execQuery($query);
        if ($result) {
            $arreturnvalue = array('body' => 'menu_admin.html',
	                            'message' => 'Menu Category updated Successfully!');
        } else {
            $arreturnvalue = array('body' => 'menu_admin.html',
	                            'message' => 'Menu Category update Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
		
        break;	
		
		case 'admin_product':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'admin_product.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'insert_admin_item':
		if($_SESSION['adminuserid']!=""){
			
        	$postids=rand();
	
        $query = "SELECT * FROM admin_product  where product_title='".$_REQUEST["product_title"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
		if (!$success) {
        include_once('lib/class.imgResize.php');
				
        		
				
				$userfile_name = strtolower($_FILES['file']['name']);
				$userfile_tmp = $_FILES['file']['tmp_name'];
				$userfile_size = $_FILES['file']['size'];
				$userfile_type = $_FILES['file']['type'];
				
				if($userfile_name!=""){
				Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				$filedir = '../product_image/';
	 			$thumbdir = '../product_image/'; 
				$filedir1 = 'product_image/';
	 			$thumbdir1 = 'product_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
				
			
            $query = "INSERT INTO admin_product(`aproduct_id`,`product_title`,`thumb_image`,`large_image`,`product_desc`) VALUES('','".mysql_escape_string($_REQUEST["product_title"])."','".strtolower($prod_img_thumb)."','".strtolower($prod_img_thumb1)."','".mysql_escape_string($_REQUEST["product_desc"])."')";
			
            $result = $db->execQuery($query);
			
                if ($result) {
                        $arreturnvalue = array('body' => 'admin_product.html',
	                                        'message' => 'Item added Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'admin_product.html',
	                                    'message' => 'Item addition Failed!');
                }
			} else {
                    $arreturnvalue = array('body' => 'admin_product.html',
	                                    'message' => 'Item could not insert with out image!');
                }
        } else {
        	$arreturnvalue = array('body' => 'admin_product.html',
	                            'message' => 'Item Already Exists!.');
        }
		
		
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
		
        break;	
		
		case 'item_list':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'item_list.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'assign_resturantitem':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'assign_resturantitem.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'select_item':
		if($_SESSION['adminuserid']!=""){
	
        $arreturnvalue = array('body' => 'assign_resturantitem.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
	case 'admin_menu_insert':
		if($_SESSION['adminuserid']!=""){
		$d=date('Y-m-d H:m:s');
		$rsize=sizeof($_SESSION['resid']);
		for($i=0;$i<$rsize;$i++)
		{
		 for($ct=0;$ct<sizeof($_REQUEST['menutitle']);$ct++)
		 {
		  $query = "SELECT * FROM categories where categories_name='".$_REQUEST["menutitle"][$ct]."' and rid ='".$_SESSION['resid'][$i]."'";
        $success = $db->checkExists($query);
		if (!$success) {
			 $query = "INSERT INTO categories(`categories_id`,`rid`,`categories_name`,`short_desc`,`parent_id`,`sort_order`,`date_added`,`last_modified`) VALUES('','".$_SESSION['resid'][$i]."','".$_REQUEST["menutitle"][$ct]."','','','$d','')";
			
            $result = $db->execQuery($query);
			$postid = $db->lastInsert($result);
		}
		else
		{
		$query = "SELECT * FROM categories where categories_name='".$_REQUEST["menutitle"][$ct]."' and rid ='".$_SESSION['resid'][$i]."'";
        $result = mysql_query($query);
		$row=mysql_fetch_array($result);
		$postid=$row["categories_id"];
		}
		 $query = "SELECT * FROM product  where categories_id  = '$postid' and product_title='".$_REQUEST["product_title"][$ct]."'";
         $success = $db->checkExists($query);
		 if (!$success) {
		  $query = "INSERT INTO product(`product_id`,`categories_id` ,`product_title` ,`food_code` ,`thumb_image` ,`large_image` ,`product_desc` ,`price` ,`parent_id` ,`product_number`) VALUES('','$postid','".mysql_escape_string($_REQUEST["product_title"][$ct])."','".mysql_escape_string($_REQUEST["thumb_image"][$ct])."','".mysql_escape_string($_REQUEST["large_image"][$ct])."','".mysql_escape_string($_REQUEST["product_desc"][$ct])."','".mysql_escape_string($_REQUEST["price"][$ct])."')";
			
            $result = $db->execQuery($query);
		 }
		
		
		 }
		 if($_REQUEST['cproduct_title']!="")
		 {
		 

$query = "SELECT * FROM categories where categories_name='".$_REQUEST["cmenutitle"]."' and rid ='".$_SESSION['resid'][$i]."'";
        $success = $db->checkExists($query);
		if (!$success) {
			 $query = "INSERT INTO categories(`categories_id` ,`rid` ,`categories_name` ,`short_desc` ,`parent_id` ,`sort_order` ,`date_added` ,`last_modified`) VALUES('','".$_SESSION['resid'][$i]."','".$_REQUEST["cmenutitle"]."','','','$d','')";
			
            $result = $db->execQuery($query);
			$postid = $db->lastInsert($result);
		}
		else
		{
		$query = "SELECT * FROM categories where categories_name='".$_REQUEST["cmenutitle"]."' and rid ='".$_SESSION['resid'][$i]."'";
        $result = mysql_query($query);
		$row=mysql_fetch_array($result);
		$postid=$row["categories_id"];
		}
		 $query = "SELECT * FROM product  where categories_id  = '$postid' and product_title='".$_REQUEST["cproduct_title"]."'";
         $success = $db->checkExists($query);
		 if (!$success) {
		  $query = "INSERT INTO product(`product_id` ,`categories_id` ,`product_title` ,`food_code` ,`thumb_image` ,`large_image` ,`product_desc` ,`price` ,`parent_id` ,`product_number`) VALUES('','$postid','".mysql_escape_string($_REQUEST["cproduct_title"])."','','','','".mysql_escape_string($_REQUEST["cprice"])."')";
			
            $result = $db->execQuery($query);
			}
		
		 }
		}
		unset($_SESSION['aproduct_id']);
		$arreturnvalue = array('body' => 'assign_resturantitem.html',
	                                    'message' => 'Item inserted to resturant');
		
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;		
		
		case 'add_resturant':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'add_resturant.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'request_list':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'requestlist.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'insert_new_resturant':
									if($_SESSION['adminuserid']!="")
									{
											
          
		  include('lib/class.imgResize_resimg.php');
		  for($i=1;$i<=8;$i++)                                  
								
			{					
				$postid=rand();
				$userfile_name = strtolower($_FILES['image'.$i]['name']);
				if($userfile_name != ""){
				$userfile_tmp = $_FILES['image'.$i]['tmp_name'];
				$userfile_size = $_FILES['image'.$i]['size'];
				$userfile_type = $_FILES['image'.$i]['type'];
				
				if($userfile_name!=""){
				
				
Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				//$filedir = '../restaurant_image/';
	 			//$thumbdir = '../restaurant_image/'; 
				//$filedir1 = 'restaurant_image/';
	 			//$thumbdir1 = 'restaurant_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $res_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$res_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$res_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
				}
				else
				{
				
				$res_img = "";
				$res_img_thumb = "";
				$res_img_thumb1 = "";
				}
				
		  	$img[$i-1]=$postid.'_'.$userfile_name;		
			}
		}		
		$restaura_image=implode("|",$img);				
						
											
										
									if($_REQUEST[rid]!="")
									{
									 		
											
											
											
											
												$ids=$_REQUEST["cid"];
											
												$query = "Update  userregistration  set first_name='".mysql_escape_string($_REQUEST["first_name"])."', last_name='"
														.mysql_escape_string($_REQUEST["last_name"])."', phone='".mysql_escape_string($_REQUEST["phone"])."', fax='"
														.mysql_escape_string($_REQUEST["fax"])."', email='".mysql_escape_string($_REQUEST["email"])."', address1='"
														.$_REQUEST["address1"]."',status='1' where id='$ids'";
														
														
														
														

												$result = $db->execQuery($query);
												$userfile_tmp='';
												$prod_img_thumbs='';
												$userfile_name = $_FILES['c_img']['name'];
												$userfile_tmp = $_FILES['c_img']['tmp_name'];
												$userfile_size = $_FILES['c_img']['size'];
												$userfile_type = $_FILES['c_img']['type'];
												$postids=rand();
												include_once('lib/class.imgResizes.php');
												Image_resizes($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postids);
												$filedir = '../companylogo/';
												$thumbdir = '../companylogo/'; 
												$filedir2 = 'companylogo/';
												$thumbdir2 = 'companylogo/'; 
												$prefixs = 'large_'.$postids.'_';
												//$prod_img = $filedir.$postid.'_'.$userfile_name;
												if (!$userfile_name=="")
												{
													$prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
													$prod_img_thumbs = strtolower($thumbdir2.$prefixs.$userfile_name);
												}
												if($prod_img_thumbs!="")
												{
													
													$query = "Update resturant set area_id='".mysql_escape_string($_REQUEST["area_id"])."',chinise_id='".
													mysql_escape_string($_REQUEST["chinise_id"])."',rname='".mysql_escape_string($_REQUEST["rname"])."',street='".
													mysql_escape_string($_REQUEST["street"])."',city='".mysql_escape_string($_REQUEST["city"])."',zipcode='".
													mysql_escape_string($_REQUEST["zipcode"])."',country_id='".mysql_escape_string($_REQUEST["country_id"]).
													"',contact='".mysql_escape_string($_REQUEST["contact"])."',contact2='".mysql_escape_string($_REQUEST["contact2"]).
													"',contact3='".mysql_escape_string($_REQUEST["contact3"])."',contact4='".mysql_escape_string($_REQUEST["contact4"])
													."',logo='$prod_img_thumbs',rdesc='".nl2br($_REQUEST["rdesc"]).
													"',profile='".mysql_escape_string($_REQUEST["profile"])."',keytitle='".nl2br($_REQUEST["keytitle"])."', aactive='1' where rid='".mysql_escape_string($_REQUEST["rid"])."'";
												}
												else
												{
													 $query = "Update resturant set area_id='".mysql_escape_string($_REQUEST["area_id"])."',chinise_id='".
													 mysql_escape_string($_REQUEST["chinise_id"])."',rname='".mysql_escape_string($_REQUEST["rname"])."',street='".
													 mysql_escape_string($_REQUEST["street"])."',city='".mysql_escape_string($_REQUEST["city"])."',zipcode='".
													 mysql_escape_string($_REQUEST["zipcode"])." ".mysql_escape_string($_REQUEST["zipcode2"])."',country_id='".mysql_escape_string($_REQUEST["country_id"]).
													 "',contact='".mysql_escape_string($_REQUEST["contact"])."',contact2='".mysql_escape_string($_REQUEST["contact2"]).
													 "',contact3='".mysql_escape_string($_REQUEST["contact3"])."',contact4='".mysql_escape_string($_REQUEST["contact4"
													 ])."',rdesc='".nl2br($_REQUEST["rdesc"])."',profile='".mysql_escape_string($_REQUEST["profile"])."',keytitle='".nl2br($_REQUEST["keytitle"])."', aactive='1' where rid='".mysql_escape_string($_REQUEST["rid"])."'";
												}
											
											$result = $db->execQuery($query);
											$arreturnvalue = array('body' => 'add_resturant.html',
																		'message' => 'Restaurant registered successfully');
										}
									else
									{
											$query1 = "SELECT email FROM  userregistration  where email= '".$_REQUEST["email"]."'";
											$success1 = $db->checkExists($query1);
									//	 if (!$success1) 
									//	 {
					$query = "INSERT INTO  userregistration(`id` ,`first_name` ,`last_name` ,`phone` ,`mobile` ,`fax` ,`email` ,`address1` 	,`password` ,`status`)
								 VALUES ('', '".mysql_escape_string($_REQUEST["first_name"])."', '".mysql_escape_string($_REQUEST["last_name"])."', '".
								 mysql_escape_string($_REQUEST["phone"])."', '".mysql_escape_string($_REQUEST["mobile"])."', 
								 '".mysql_escape_string($_REQUEST["fax"])."', '".mysql_escape_string($_REQUEST["email"])."', '".$_REQUEST["address1"]."',
								 '".mysql_escape_string($_REQUEST["password"])."', '1')";
											//echo $query;
												$result = $db->execQuery($query);
												$postid = $db->lastInsert($result);
												$userfile_tmp='';
												$prod_img_thumbs='';
												$userfile_name = $_FILES['c_img']['name'];
										$userfile_tmp = $_FILES['c_img']['tmp_name'];
										$userfile_size = $_FILES['c_img']['size'];
										$userfile_type = $_FILES['c_img']['type'];
										$postids=rand();
											include_once('lib/class.imgResizes.php');
Image_resizes($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postids);
												$filedir = '../companylogo/';
												$thumbdir = '../companylogo/'; 
												$filedir2 = 'companylogo/';
												$thumbdir2 = 'companylogo/'; 
												$prefixs = 'large_'.$postids.'_';
												//$prod_img = $filedir.$postid.'_'.$userfile_name;
												if (!$userfile_name=="")
												{
													$prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
													$prod_img_thumbs = strtolower($thumbdir2.$prefixs.$userfile_name);
												}		
												 $query = "INSERT INTO resturant(`rid` ,`userid` ,`area_id` ,`chinise_id` ,`rname` ,`street` ,`city` ,`zipcode` ,	
														`country_id` ,`contact` ,`contact2` ,`contact3` ,`contact4` ,`web` ,`logo`,`rdesc` ,`profile` 	,`keytitle` ,`aactive` ,`rating`,`restaurant_image`,`image1`,`image2`,`image3`,`image4`,
`image5`,`image6`,`image7`,`image8`,`building`,`address2`,`county`) VALUES('','$postid','".mysql_escape_string($_REQUEST["area_id"])."','".mysql_escape_string($_REQUEST["chinise_id"])."','".mysql_escape_string($_REQUEST["rname"])."','".mysql_escape_string(
												$_REQUEST["street"])."','".mysql_escape_string($_REQUEST["city"])."','".mysql_escape_string($_REQUEST["zipcode"])." ".mysql_escape_string($_REQUEST["zipcode2"])."','"
												.mysql_escape_string($_REQUEST["country_id"])."','".mysql_escape_string($_REQUEST["contact"])."','".mysql_escape_string
												($_REQUEST["contact2"])."','".mysql_escape_string($_REQUEST["contact3"])."','".mysql_escape_string($_REQUEST["contact4"
												])."','','$prod_img_thumbs','".nl2br(mysql_escape_string($_REQUEST["rdesc"]))."','".
												mysql_escape_string($_REQUEST["profile"])."','".nl2br(mysql_escape_string($_REQUEST["keytitle"]))."','0','','$restaura_image','$img[0]','$img[1]','$img[2]','$img[3]','$img[4]','$img[5]','$img[6]','$img[7]','".mysql_escape_string($_REQUEST["building"])."','".mysql_escape_string($_REQUEST["address2"])."','".mysql_escape_string($_REQUEST["county"])."')";
											
												

												$result = $db->execQuery($query);		

												$getlastid=mysql_insert_id();
												
												$rnameset=$_REQUEST["rname"];

												$weblink="http://indianfoodsonline.co.uk/$rnameset-rid-$getlastid";
												
												
											    $querynew = "UPDATE `resturant` SET `web`='$weblink' WHERE (`rid`='$getlastid')";				
												$result = $db->execQuery($querynew);									
												$email="info@indianfoodsonline.co.uk";
										
												$headers = "From: $email\n"; // From address
												$headers .= "Reply-To: $email\n"; // Reply-to address
												$headers .= "Organization: www.indianfoodsonline.co.uk\n"; // Organisation
												$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Type
												$semail=$_REQUEST["email"];
												$password=$_REQUEST["password"];
												$fname=$_REQUEST["first_name"];
												$lname=$_REQUEST["last_name"];
												$unames=$_REQUEST["email"];
												$body_txt = ' Dear '.$fname.' '.$lname.'  <br />
												Thank you for registering with us.
												<br /><br /> User Name: '.$unames.'<br />Password:'.$password.'<br />
												Your Restaurant Link: 
												<a href='.$weblink.' target="_blank">'.$weblink.'</a>
												 <br>
												
												Many Thanks<br />
												The <a href="http://indianfoodsonline.co.uk" target="_blank">indianfoodsonline.co.uk</a> Team - <a 
												href="mailto:info@indianfoodsonline.co.uk">info@indianfoodsonline.co.uk</a>';
										
										$send_to =  $_REQUEST[email];
										$subject="Congratulation and thank you for signup at Indian Food Online";
										
										$sent = mail($send_to, $subject, $body_txt, $headers);
											$arreturnvalue = array('body' => 'add_resturant.html',
																		'message' => 'Restaurant registered successfully');
											/*}
											else
											{		
												$arreturnvalue = array('body' => 'add_resturant.html',
																		'message' => 'Restaurant have alreay registered');
											}*/
										}
									
									}
									else
									{
		
										$arreturnvalue = array('body' => 'login.html');
									}
        break;	
		
		case 'resturantlist':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'resturantlist.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'inactive_resturant':
		if($_SESSION['adminuserid']!=""){
		  if (isset($_REQUEST["rid"]) && !empty($_REQUEST["rid"]))
		{
		 $query = "Update resturant set aactive='2' WHERE rid= '".$_REQUEST["rid"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'resturantlist.html',
	                        'message' => 'Restaurant Is Inactivated!');
            } else {
                $arreturnvalue = array('body' => 'resturantlist.html',
	                        'message' => 'Inactivation Failed!');
            }
       
		}
		else {
                $arreturnvalue = array('body' => 'resturantlist.html',
	                        'message' => 'Inactivation Failed!');
            }
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'inacresturant_list':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'inacresturant_list.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'activate_resturant':
		if($_SESSION['adminuserid']!=""){
		  if (isset($_REQUEST["rid"]) && !empty($_REQUEST["rid"]))
		{
		 $query = "Update resturant set aactive='1' WHERE rid= '".$_REQUEST["rid"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'inacresturant_list.html',
	                        'message' => 'Restaurant Is activated!');
            } else {
                $arreturnvalue = array('body' => 'inacresturant_list.html',
	                        'message' => 'Activation Failed!');
            }
       
		}
		else {
                $arreturnvalue = array('body' => 'inacresturant_list.html',
	                        'message' => 'Activation Failed!');
            }
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'new_location':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'new_location.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		case 'insert_new_location':
	if($_SESSION['adminuserid']!=""){
        $query = "SELECT name FROM  area  where name   = '".$_REQUEST["lname"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
			
            $query = "INSERT INTO  area(`id` ,`name` ,`status`) VALUES ('','".mysql_escape_string($_REQUEST["lname"])."','1')";
			//echo $query;
			
            $result = $db->execQuery($query);
		  	$postid = $db->lastInsert($result);
					$pageName="Property Type";
                        $arreturnvalue = array('body' => 'new_location.html',
	                            'message' => 'This Area added successfully');
		
        } else {
			
		
			
        	$arreturnvalue = array('body' => 'new_location.html',
	                            'message' => 'This Area has already been added. Please try again.');
        }
	}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
	        break;	
		case 'location_del':
	
       if($_SESSION['adminuserid']!=""){
        if (isset($_REQUEST["lid"]) && !empty($_REQUEST["lid"])) {
            $query = "DELETE FROM area WHERE id= '".$_REQUEST["lid"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'new_location.html',
	                        'message' => 'Area Deleted Successfully!');
            } else {
                $arreturnvalue = array('body' => 'new_location.html',
	                        'message' => 'Area Deletion Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'new_location.html',
        	                    'message' => 'Area Deletion Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
	        break;	
		case 'edit_area':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'edit_area.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'update_location':
	if($_SESSION['adminuserid']!=""){
        $query = "update area SET name  = '".mysql_escape_string($_REQUEST["lname"])."' WHERE id  = '".$_REQUEST["lid"]."'";	
		
		
		$result = $db->execQuery($query);
        if ($result) {
            $arreturnvalue = array('body' => 'edit_area.html',
	                            'message' => 'Area updated Successfully!');
        } else {
            $arreturnvalue = array('body' => 'edit_area.html',
	                            'message' => 'Area update Failed!');
        }
		 }else{
			
			$arreturnvalue = array('body' => 'login.html');
			} 
		
        break;
		
		case 'new_type':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'new_type.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'insert_new_type':
	if($_SESSION['adminuserid']!=""){
        $query = "SELECT name FROM  cusin  where name   = '".$_REQUEST["lname"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
			
            $query = "INSERT INTO  cusin(`id` ,`name` ,`status`) VALUES ('','".mysql_escape_string($_REQUEST["lname"])."','1')";
			//echo $query;
			
            $result = $db->execQuery($query);
		  	$postid = $db->lastInsert($result);
					$pageName="Property Type";
                        $arreturnvalue = array('body' => 'new_type.html',
	                            'message' => 'This Restaurant type added successfully');
		
        } else {
			
		
			
        	$arreturnvalue = array('body' => 'new_type.html',
	                            'message' => 'This Restaurant type has already been added. Please try again.');
        }
	}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
	        break;	
			
			
			case 'type_del':
	
       if($_SESSION['adminuserid']!=""){
        if (isset($_REQUEST["lid"]) && !empty($_REQUEST["lid"])) {
            $query = "DELETE FROM cusin WHERE id= '".$_REQUEST["lid"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'new_type.html',
	                        'message' => 'Restaurant type Deleted Successfully!');
            } else {
                $arreturnvalue = array('body' => 'new_type.html',
	                        'message' => 'Restaurant type Deletion Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'new_type.html',
        	                    'message' => 'Restaurant type Deletion Failed!');
        }
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
	        break;	
			
		case 'edit_type':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'edit_type.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		case 'update_type':
	if($_SESSION['adminuserid']!=""){
        $query = "update cusin SET name  = '".mysql_escape_string($_REQUEST["lname"])."' WHERE id  = '".$_REQUEST["lid"]."'";	
		
		
		$result = $db->execQuery($query);
        if ($result) {
            $arreturnvalue = array('body' => 'edit_type.html',
	                            'message' => 'Restaurant Type updated Successfully!');
        } else {
            $arreturnvalue = array('body' => 'edit_type.html',
	                            'message' => 'Restaurant Type update Failed!');
        }
		 }else{
			
			$arreturnvalue = array('body' => 'login.html');
			} 
		
        break;
		
			case 'printdomain':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'printdomain.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
					case 'royalty':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'royalty.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
///////////////sagar
			case 'order_conf_email':
		if($_SESSION['adminuserid']!=""){
        $arreturnvalue = array('body' => 'order_conf_email.html');
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
/////////////////////////sagar /////////////////

			case 'insert_service_domain':
	if($_SESSION['adminuserid']!=""){
       
		if($_REQUEST["pid"]=="")
        {	
            $query = "INSERT INTO  printdomain(`pid` ,`pdname`) VALUES ('','".mysql_escape_string($_REQUEST["pdname"])."')";
			
			}else
            {
                  $query = "Update  printdomain  set pdname='".mysql_escape_string($_REQUEST["pdname"])."' where pid='".$_REQUEST["pid"]."'";
            }
            $result = $db->execQuery($query);
		    if ($result) {
					$pageName="Property Type";
                        $arreturnvalue = array('body' => 'printdomain.html',
	                            'message' => 'Service domain set up complete ');
                                }
                                else
                                {
                                  $arreturnvalue = array('body' => 'printdomain.html',
	                            'message' => 'Service domain set up not completed');
                                }
		
       
	}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
	        break;	
		case 'insert_roaylty':
	if($_SESSION['adminuserid']!=""){
       
		if($_REQUEST["rlid"]=="")
        {	
            $query = "INSERT INTO  res_royalty(`rlid` ,`poundt` ,`point` ,`pointt` ,`pound`) VALUES ('','".mysql_escape_string($_REQUEST["poundt"])."','".mysql_escape_string($_REQUEST["point"])."','".mysql_escape_string($_REQUEST["pointt"])."','".mysql_escape_string($_REQUEST["pound"])."')";
			
			}else
            {
                  $query = "Update  res_royalty  set poundt='".mysql_escape_string($_REQUEST["poundt"])."',point='".mysql_escape_string($_REQUEST["point"])."',pointt='".mysql_escape_string($_REQUEST["pointt"])."',pound='".mysql_escape_string($_REQUEST["pound"])."' where rlid='".$_REQUEST["rlid"]."'";
            }
            $result = $db->execQuery($query);
		    if ($result) {
					$pageName="Property Type";
                        $arreturnvalue = array('body' => 'royalty.html',
	                            'message' => 'Royalty set up complete ');
                                }
                                else
                                {
                                  $arreturnvalue = array('body' => 'royalty.html',
	                            'message' => 'Royalty set up not completed');
                                }
		
       
	}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
	        break;	
		case 'resturant_menu':
		if($_SESSION['adminuserid']!=""){
		if($_REQUEST['rid']!="")
		{
		$_SESSION['resid']=$_REQUEST['rid'];
		$sqlrs="Select * from resturant where rid='".$_SESSION['resid']."'";
		$resultrs=mysql_query($sqlrs);
		$rowrs=mysql_fetch_array($resultrs);
		$_SESSION[rname]=$rowrs["rname"];
        $arreturnvalue = array('body' => 'resturant_menu.html');
		}else
		{
		 $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	//@shafiq Menu
	case 'admin_menu_cetegory':
		 
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'admin_menu.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'search_menu':
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'admin_menu.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
        
	//
		case 'menu_cetegory':
		 
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'menu_cetegory.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'search_menu':
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'menu_cetegory.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
		
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
        
        //@shafiq
        case 'insert_new_admin_menu':
									 	if($_SESSION['adminuserid']!="")
										{
												if($_SESSION['resid']!="")
												{
													if($_REQUEST['catid']=="")
													{
														$rids=$_SESSION['resid'];
												       	$query = "SELECT * FROM categories  where rid  = '$rids' and categories_name='".$_REQUEST[categories_name]."'";
												        $success = $db->checkExists($query);
														$d=date('Y-m-d H:m:s');
												        if (!$success) 
													    {
															$queryc = "SELECT count(*) as pos FROM  categories  where rid  = '$rids'";
													       $resultc = $db->execQuery($queryc);
																$arstinfoc = $db->resultArray($resultc);
																$pos=$arstinfoc[0]["pos"];
																$pos=$pos+1;
																
															 $query = "INSERT INTO categories(`categories_id` ,`rid` ,`categories_name` ,`short_desc` ,`parent_id` ,`sort_order` ,`date_added` ,`last_modified`) VALUES('','$rids','".mysql_escape_string($_REQUEST["categories_name"])."',
															 													'".mysql_escape_string($_REQUEST["short_description"])."','".mysql_escape_string($_REQUEST["parent_id"])."','$pos','$d','')";
																
													            $result = $db->execQuery($query);
																  if ($result) {
													                        $arreturnvalue = array('body' => 'add_menu.html',
														                                        'message' => 'Menu Inserted Successfully!');
													                } else {
													                    $arreturnvalue = array('body' => 'add_menu.html',
														                                    'message' => 'Menu Insertion Failed!');
													                }
																	
														}
														else
														{
															$arreturnvalue = array('body' => 'add_menu.html',
													                            'message' => 'Menue Already inserted');
														}
													}
										
												}
												else
												{
												 $arreturnvalue = array('body' => 'resturantlist.html');
												}
											   
										}
										else
										{
											$arreturnvalue = array('body' => 'login.html');
										}
										break;	
        
        //Shafiq
        	case 'edit_new_menue':
								 	if($_SESSION['adminuserid']!="")
									{
											
										if($_SESSION['resid']!="")
										{
										
											$catidsu=$_REQUEST['categid'];
										
											$d=date('Y-m-d H:m:s');
											$query = "Update categories set categories_name='".mysql_escape_string($_REQUEST["categories_name"])."',short_desc='".mysql_escape_string($_REQUEST["short_description"])."',parent_id='".mysql_escape_string($_REQUEST["parent_id"])."',date_added='$d' where categories_id='$catidsu'";
												
									        $result = $db->execQuery($query);
											 if ($result) 
											 {
									                       $arreturnvalue = array('body' => 'edit_menu.html',
										    	                                   'message' => 'Menu Updated Successfully!');
									         } 
									         else 
									         {
									         	          $arreturnvalue = array('body' => 'edit_menu.html',
										                                    'message' => 'Menu Update Failed!');
									         }
										}
										else
										{
												 $arreturnvalue = array('body' => 'resturantlist.html');
										}
										   
									}
									else
									{
												$arreturnvalue = array('body' => 'login.html');
									}
									break;	
									        
        
		case 'insert_new_menue':
								 	if($_SESSION['adminuserid']!="")
									{
											
										if($_SESSION['resid']!="")
										{
											
										if($_REQUEST['catid']=="")
										{
											$rids=$_SESSION['resid'];
									       	$query = "SELECT * FROM categories  where rid  = '$rids' and categories_name='".$_REQUEST[categories_name]."'";
									        $success = $db->checkExists($query);
											$d=date('Y-m-d H:m:s');
									        if (!$success) 
										    {
											   $queryc = "SELECT count(*) as pos FROM  categories  where rid  = '$rids'";
										       $resultc = $db->execQuery($queryc);
												$arstinfoc = $db->resultArray($resultc);
												$pos=$arstinfoc[0]["pos"];
												$pos=$pos+1;
													
												 $query = "INSERT INTO categories(`categories_id` ,`rid` ,`categories_name` ,`short_desc` ,`parent_id` ,`sort_order` ,
`date_added` ,`last_modified`) VALUES('','$rids','".mysql_escape_string($_REQUEST["categories_name"])."',
												 													'".mysql_escape_string($_REQUEST["short_description"])."','".mysql_escape_string($_REQUEST["parent_id"])."','$pos','$d','')";
													
										            $result = $db->execQuery($query);
													  if ($result) {
										                        $arreturnvalue = array('body' => 'menu_cetegory.html',
											                                        'message' => 'Menu Inserted Successfully!');
										                } else {
										                    $arreturnvalue = array('body' => 'menu_cetegory.html',
											                                    'message' => 'Menu Insertion Failed!');
										                }
														
											 }
											 else
											 {
												$arreturnvalue = array('body' => 'menu_cetegory.html',
										                            'message' => 'Menue Already inserted');
											 }
											 }
											 else
											 {
													
													$catidsu=$_REQUEST['catid'];
													$d=date('Y-m-d H:m:s');
													$query = "Update categories set categories_name='".mysql_escape_string($_REQUEST["categories_name"])."',short_desc='".mysql_escape_string($_REQUEST["short_description"])."',parent_id='".mysql_escape_string($_REQUEST["parent_id"])."',date_added='$d' where categories_id='$catidsu'";
												
									            $result = $db->execQuery($query);
												 if ($result) {
									                        $arreturnvalue = array('body' => 'menu_cetegory.html',
										                                        'message' => 'Menu Updated Successfully!');
									                } else {
									                    $arreturnvalue = array('body' => 'menu_cetegory.html',
										                                    'message' => 'Menu Update Failed!');
									                }
													}
											
											}else
											{
											 $arreturnvalue = array('body' => 'resturantlist.html');
											}
										   
											}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
        
		//@shafiq
		case 'insert_new_food_item':
								 	if($_SESSION['adminuserid']!="")
								 	{
									
										if($_SESSION['resid']!="")
										{
											
											if($_REQUEST['catid']=="")
											{
												$rids=$_SESSION['resid'];
										       	$query = "SELECT * FROM categories  where rid  = '$rids' and categories_name='".$_REQUEST[categories_name]."'";
										        $success = $db->checkExists($query);
												$d=date('Y-m-d H:m:s');
										        if (!$success) 
											    {
													$queryc = "SELECT count(*) as pos FROM  categories  where rid  = '$rids'";
											       $resultc = $db->execQuery($queryc);
														$arstinfoc = $db->resultArray($resultc);
														$pos=$arstinfoc[0]["pos"];
														$pos=$pos+1;
														
													 $query = "INSERT INTO categories(`categories_id` ,`rid` ,`categories_name` ,`short_desc` ,`parent_id` ,`sort_order` ,`date_added` ,`last_modified`) VALUES('','$rids','".mysql_escape_string($_REQUEST["categories_name"])."',
													 													'".mysql_escape_string($_REQUEST["short_description"])."','".mysql_escape_string($_REQUEST["parent_id"])."','$pos','$d','')";
														
											            $result = $db->execQuery($query);
														  if ($result) {
											                        $arreturnvalue = array('body' => 'menu_food_item.html',
												                                        'message' => 'Menu Inserted Successfully!');
											                } else {
											                    $arreturnvalue = array('body' => 'menu_food_item.html',
												                                    'message' => 'Menu Insertion Failed!');
											                }
															
													}else
												{
													$arreturnvalue = array('body' => 'menu_food_item.html',
											                            'message' => 'Menue Already inserted');
												}}else
													{
													
													$catidsu=$_REQUEST['catid'];
													$d=date('Y-m-d H:m:s');
													$query = "Update categories set categories_name='".mysql_escape_string($_REQUEST["categories_name"])."',short_desc='".mysql_escape_string($_REQUEST["short_description"])."',parent_id='".mysql_escape_string($_REQUEST["parent_id"])."',date_added='$d' where categories_id='$catidsu'";
												
									            $result = $db->execQuery($query);
												 if ($result) {
									                        $arreturnvalue = array('body' => 'menu_food_item.html',
										                                        'message' => 'Menu Updated Successfully!');
									                } else {
									                    $arreturnvalue = array('body' => 'menu_food_item.html',
										                                    'message' => 'Menu Update Failed!');
									                }
													}
											
											}else
									{
									 $arreturnvalue = array('body' => 'resturantlist.html');
									}
								   
									}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		case 'change_menu_order':
	if($_SESSION['adminuserid']!=""){
			
		$newNo=$_REQUEST['newOrderNo'];
		$oldNo=$_REQUEST['oldOrderNo'];
		$rids=$_SESSION['resid'];
		$query="SELECT * FROM categories where  sort_order ='".$oldNo."' and rid='$rids'";
		$result=$db->execQuery($query);
		$arrinfo=$db->resultArray($result);
		if (sizeof($arrinfo)>0){
			$groupID=$arrinfo[0]['categories_id'];
			$query="Update categories set sort_order=".$newNo." where categories_id=".$groupID." and rid='$rids'";
			$result=$db->execQuery($query);
		}
		if($groupID != ""){
			$query="select * from categories where categories_id <>".$groupID." and rid='$rids' order by sort_order";
			$result=$db->execQuery($query);
			$arrayinfo=$db->resultArray($result);
			$sL=1;
			for($i=0; $i<sizeof($arrayinfo); $i++){
				if ($sL==$newNo){
					$sL=$sL+1;
				}
				$sID=$arrayinfo[$i]['categories_id'];
				$query="Update categories set sort_order='".$sL."' where categories_id=".$sID."";
				$result=$db->execQuery($query);
				$sL=$sL+1;
			}
		}
		$arreturnvalue = array('body' => 'admin_menu.html',
	                            'message' => 'Updated Successfully!');  
								
		 }else{
			
			$arreturnvalue = array('body' => 'login.html');
			} 						
		break;	
		
	  case 'cat_del':
	
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        if (isset($_REQUEST["catid"]) && !empty($_REQUEST["catid"])) {
            $query = "DELETE FROM categories WHERE categories_id = '".$_REQUEST["catid"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'admin_menu.html',
	                        'message' => 'Category Deleted Successfully!');
            } else {
                $arreturnvalue = array('body' => 'admin_menu.html',
	                        'message' => 'Category Deletion Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'admin_menu.html',
        	                    'message' => 'Category Deletion Failed!');
        }
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
			
			
	case 'additem':
	 	if($_SESSION['adminuserid']!="")
		{
			if(!$_SESSION['resid']=="")
			{
				$arreturnvalue = array('body' => 'additem.html');
			}
			else
			{
			  $arreturnvalue = array('body' => 'resturantlist.html');
			}
       
		}
		else
		{
			$arreturnvalue = array('body' => 'login.html');
		}
        break;	
    
    
    case 'addselectionitem':
    
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'addselectionitem.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;
      //@shafiq  	    
    case 'addfooditem':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'addFood.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	
	 case 'addselectionfooditem':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'addFoodSelection.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;		
//		
		
	case 'search_food_item':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'additem.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
        
	//@Shafiq
		case 'insert_food_item':
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$uids=$_SESSION['userid'];
		$postid=rand();
	
        $query = "SELECT * FROM product  where categories_id  = '".$_REQUEST["categories_id"]."' and product_title='".$_REQUEST["product_title"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
		if(!$success){
        include_once('lib/class.imgResize.php');
				
        		
				
				$userfile_name = strtolower($_FILES['file']['name']);
				$userfile_tmp = $_FILES['file']['tmp_name'];
				$userfile_size = $_FILES['file']['size'];
				$userfile_type = $_FILES['file']['type'];
				
				if($userfile_name!=""){
				Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				$filedir = '../product_image/';
	 			$thumbdir = '../product_image/'; 
				$filedir1 = 'product_image/';
	 			$thumbdir1 = 'product_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
				}else
				{
				  $prod_img = "";
				$prod_img_thumb = "";
				$prod_img_thumb1 = "";
				}
			
             $query = "INSERT INTO product(`product_id` ,`categories_id` ,`product_title` ,`food_code` ,`thumb_image` ,`large_image` ,`product_desc` ,`price` ,`parent_id` ,`product_number`) VALUES('','".mysql_escape_string($_REQUEST["categories_id"])."','".mysql_escape_string($_REQUEST["product_title"])."','".mysql_escape_string($_REQUEST["food_code"])."','".strtolower($prod_img_thumb)."','".strtolower($prod_img_thumb1)."','".mysql_escape_string($_REQUEST["product_desc"])."','".mysql_escape_string($_REQUEST["price"])."','','".mysql_escape_string($_REQUEST["product_number"])."')";
			
            $result = $db->execQuery($query);
			
                if ($result) 
                {
                        $arreturnvalue = array('body' => 'addFood.html',
	                                        'message' => 'Item added Successfully!');
                } 
                else 
                {
                	
                    $arreturnvalue = array('body' => 'addFood.html',
	                                    'message' => 'Item addition Failed!');
                }
        } else {
        	$arreturnvalue = array('body' => 'addFood.html',
	                            'message' => 'Item Already Exists!.');
        }
		
	
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}}else{
			
			$arreturnvalue = array('body' => 'login.html',
	                            'message' => '');
			}
	
	        break;
	        
	        
	    case 'insert_food_selection_item':
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$uids=$_SESSION['userid'];
		$postid=rand();
	
        $query = "SELECT * FROM product  where categories_id  = '".$_REQUEST["categories_id"]."' and product_title='".$_REQUEST["product_title"]."' and parent_id='".$_REQUEST["prodid"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
		if(!$success){
        include_once('lib/class.imgResize.php');
				
        		
				
				$userfile_name = strtolower($_FILES['file']['name']);
				$userfile_tmp = $_FILES['file']['tmp_name'];
				$userfile_size = $_FILES['file']['size'];
				$userfile_type = $_FILES['file']['type'];
				
				if($userfile_name!=""){
				Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				$filedir = '../product_image/';
	 			$thumbdir = '../product_image/'; 
				$filedir1 = 'product_image/';
	 			$thumbdir1 = 'product_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
				}else
				{
				  $prod_img = "";
				$prod_img_thumb = "";
				$prod_img_thumb1 = "";
				}
			
            $query = "INSERT INTO product(`product_id` ,`categories_id` ,`product_title` ,`food_code` ,`thumb_image` ,`large_image` ,`product_desc` ,`price` ,`parent_id` ,`product_number`) VALUES('','".mysql_escape_string($_REQUEST["categories_id"])."','".mysql_escape_string($_REQUEST["product_title"])."','".mysql_escape_string($_REQUEST["food_code"])."','".strtolower($prod_img_thumb)."','".strtolower($prod_img_thumb1)."','".mysql_escape_string($_REQUEST["product_desc"])."','".mysql_escape_string($_REQUEST["price"])."','".mysql_escape_string($_REQUEST["product_id"])."','')";
			
            $result = $db->execQuery($query);
			
                if ($result) {
                        $arreturnvalue = array('body' => 'addFoodSelection.html',
	                                        'message' => 'Item added Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'addFoodSelection.html',
	                                    'message' => 'Item addition Failed!');
                }
        } else {
        	$arreturnvalue = array('body' => 'addFoodSelection.html',
	                            'message' => 'Item Already Exists!.');
        }
		
	
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}}else{
			
			$arreturnvalue = array('body' => 'login.html',
	                            'message' => '');
			}
	
	        break;
	//	
	case 'insert_item':
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$uids=$_SESSION['userid'];
		$postid=rand();
	
        $query = "SELECT * FROM product  where categories_id  = '".$_REQUEST["categories_id"]."' and product_title='".$_REQUEST["product_title"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
		if(!$success){
        include_once('lib/class.imgResize.php');
				
        		
				
				$userfile_name = strtolower($_FILES['file']['name']);
				$userfile_tmp = $_FILES['file']['tmp_name'];
				$userfile_size = $_FILES['file']['size'];
				$userfile_type = $_FILES['file']['type'];
				
				if($userfile_name!=""){
				Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				$filedir = '../product_image/';
	 			$thumbdir = '../product_image/'; 
				$filedir1 = 'product_image/';
	 			$thumbdir1 = 'product_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
				}else
				{
				  $prod_img = "";
				$prod_img_thumb = "";
				$prod_img_thumb1 = "";
				}
			
            $query = "INSERT INTO product(`product_id` ,`categories_id` ,`product_title` ,`food_code` ,`thumb_image` ,`large_image` ,`product_desc` ,`price` ,`parent_id` ,`product_number`) VALUES('','".mysql_escape_string($_REQUEST["categories_id"])."','".mysql_escape_string($_REQUEST["product_title"])."','".mysql_escape_string($_REQUEST["food_code"])."','".strtolower($prod_img_thumb)."','".strtolower($prod_img_thumb1)."','".mysql_escape_string($_REQUEST["product_desc"])."','".mysql_escape_string($_REQUEST["price"])."')";
			
            $result = $db->execQuery($query);
			
                if ($result) {
                        $arreturnvalue = array('body' => 'additem.html',
	                                        'message' => 'Item added Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'additem.html',
	                                    'message' => 'Item addition Failed!');
                }
        } else {
        	$arreturnvalue = array('body' => 'additem.html',
	                            'message' => 'Item Already Exists!.');
        }
		
	
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}}else{
			
			$arreturnvalue = array('body' => 'login.html',
	                            'message' => '');
			}
	
	        break;
	case 'pro_edit':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'pro_edit.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;			
		
	//@shafiq 29-06-09
	case 'food_item_edit':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'food_item_edit.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;		
		
	case 'selection_food_item_edit':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'selection_food_item_edit.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;			
			
	case 'update_item':
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$uids=$_SESSION['userid'];
		$postid=rand();
	
       
		$d=date('Y-m-d H:m:s');
        include_once('lib/class.imgResize.php');
				
        		
				
				$userfile_name = strtolower($_FILES['file']['name']);
				$userfile_tmp = $_FILES['file']['tmp_name'];
				$userfile_size = $_FILES['file']['size'];
				$userfile_type = $_FILES['file']['type'];
				
				if($userfile_name!=""){
				Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				$filedir = '../product_image/';
	 			$thumbdir = '../product_image/'; 
				$filedir1 = 'product_image/';
	 			$thumbdir1 = 'product_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
				}else
				{
				  $prod_img = "";
				$prod_img_thumb = "";
				$prod_img_thumb1 = "";
				}
			if($prod_img_thumb==""){
            $query = "Update product Set categories_id='".mysql_escape_string($_REQUEST["categories_id"])."',product_title='".mysql_escape_string($_REQUEST["product_title"])."',food_code='".mysql_escape_string($_REQUEST["food_code"])."',product_desc='".mysql_escape_string($_REQUEST["product_desc"])."',price='".mysql_escape_string($_REQUEST["price"])."' where product_id='".$_REQUEST["product_id"]."'";
			}else
			{
			$query = "Update product Set categories_id='".mysql_escape_string($_REQUEST["categories_id"])."',product_title='".mysql_escape_string($_REQUEST["product_title"])."',food_code='".mysql_escape_string($_REQUEST["food_code"])."',thumb_image='".strtolower($prod_img_thumb)."',large_image='".strtolower($prod_img_thumb1)."',product_desc='".mysql_escape_string($_REQUEST["product_desc"])."',price='".mysql_escape_string($_REQUEST["price"])."' where product_id='".$_REQUEST["product_id"]."'";
			
			}
			
            $result = $db->execQuery($query);
			
                if ($result) {
                        $arreturnvalue = array('body' => 'pro_edit.html',
	                                        'message' => 'Item Updated Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'pro_edit.html',
	                                    'message' => 'Item Update Failed!');
                }
        	
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}}else{
			
			$arreturnvalue = array('body' => 'login.html',
	                            'message' => '');
			}
	
	        break;
			
			//@shafiq
			case 'update_food_item':
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$uids=$_SESSION['userid'];
		$postid=rand();
	
       
		$d=date('Y-m-d H:m:s');
        include_once('lib/class.imgResize.php');
				
        		
				
				$userfile_name = strtolower($_FILES['file']['name']);
				$userfile_tmp = $_FILES['file']['tmp_name'];
				$userfile_size = $_FILES['file']['size'];
				$userfile_type = $_FILES['file']['type'];
				
				if($userfile_name!=""){
				Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				$filedir = '../product_image/';
	 			$thumbdir = '../product_image/'; 
				$filedir1 = 'product_image/';
	 			$thumbdir1 = 'product_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
				}else
				{
				  $prod_img = "";
				$prod_img_thumb = "";
				$prod_img_thumb1 = "";
				}
			if($prod_img_thumb==""){
            $query = "Update product Set categories_id='".mysql_escape_string($_REQUEST["categories_id"])."',product_title='".mysql_escape_string($_REQUEST["product_title"])."',product_number='".mysql_escape_string($_REQUEST["product_number"])."',food_code='".mysql_escape_string($_REQUEST["food_code"])."',product_desc='".mysql_escape_string($_REQUEST["product_desc"])."',price='".mysql_escape_string($_REQUEST["price"])."' where product_id='".$_REQUEST["product_id"]."'";
			}else
			{
			$query = "Update product Set categories_id='".mysql_escape_string($_REQUEST["categories_id"])."',product_title='".mysql_escape_string($_REQUEST["product_title"])."',product_number='".mysql_escape_string($_REQUEST["product_number"])."',food_code='".mysql_escape_string($_REQUEST["food_code"])."',thumb_image='".strtolower($prod_img_thumb)."',large_image='".strtolower($prod_img_thumb1)."',product_desc='".mysql_escape_string($_REQUEST["product_desc"])."',price='".mysql_escape_string($_REQUEST["price"])."' where product_id='".$_REQUEST["product_id"]."'";
			
			}
			
            $result = $db->execQuery($query);
			
                if ($result) {
                        $arreturnvalue = array('body' => 'food_item_edit.html',
	                                        'message' => 'Item Updated Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'food_item_edit.html',
	                                    'message' => 'Item Update Failed!');
                }
        	
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}}else{
			
			$arreturnvalue = array('body' => 'login.html',
	                            'message' => '');
			}
	
	        break;
		
		case 'update_selection_food_item':
											if($_SESSION['adminuserid']!="")
											{
													if(!$_SESSION['resid']=="")
													{
															$uids=$_SESSION['userid'];
															$postid=rand();
														
																	$d=date('Y-m-d H:m:s');
																	include_once('lib/class.imgResize.php');
																	$userfile_name = strtolower($_FILES['file']['name']);
																	$userfile_tmp = $_FILES['file']['tmp_name'];
																	$userfile_size = $_FILES['file']['size'];
																	$userfile_type = $_FILES['file']['type'];
																	
																	if($userfile_name!="")
																	{
																		Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
																		$filedir = '../product_image/';
																		$thumbdir = '../product_image/'; 
																		$filedir1 = 'product_image/';
																		$thumbdir1 = 'product_image/'; 
																		$prefix = 'small_'.$postid.'_';
																		//$prod_img = $filedir.$postid.'_'.$userfile_name;
																		$prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
																		$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
																		$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
																	}
																	else
																	{
																	    $prod_img = "";
																		$prod_img_thumb = "";
																		$prod_img_thumb1 = "";
																	}
																if($prod_img_thumb=="")
																{
																	$query = "Update product Set categories_id='".mysql_escape_string($_REQUEST["categories_id"])."',product_title='".mysql_escape_string($_REQUEST["product_title"])."',parent_id='".mysql_escape_string($_REQUEST["parent_product_id"])."',food_code='".mysql_escape_string($_REQUEST["food_code"])."',product_desc='".mysql_escape_string($_REQUEST["product_desc"])."',price='".mysql_escape_string($_REQUEST["price"])."' where product_id='".$_REQUEST["product_id"]."'";
																}
																else
																{
																	$query = "Update product Set categories_id='".mysql_escape_string($_REQUEST["categories_id"])."',product_title='".mysql_escape_string($_REQUEST["product_title"])."',parent_id='".mysql_escape_string($_REQUEST["parent_product_id"])."',food_code='".mysql_escape_string($_REQUEST["food_code"])."',thumb_image='".strtolower($prod_img_thumb)."',large_image='".strtolower($prod_img_thumb1)."',product_desc='".mysql_escape_string($_REQUEST["product_desc"])."',price='".mysql_escape_string($_REQUEST["price"])."' where product_id='".$_REQUEST["product_id"]."'";
																}
																
																$result = $db->execQuery($query);
																
																	if ($result) 
																	{
																			$arreturnvalue = array('body' => 'selection_food_item_edit.html',
																									'message' => 'Item Updated Successfully!');
																	} 
																	else 
																	{
																		$arreturnvalue = array('body' => 'selection_food_item_edit.html',
																								'message' => 'Item Update Failed!');
																	}
																
													}
													else
													{
													  $arreturnvalue = array('body' => 'resturantlist.html');
													}
									}
													else
													{
														
														$arreturnvalue = array('body' => 'login.html',
																			   'message' => '');
													}
	
	        break;
		
		 case 'pro_del':
	
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        if (isset($_REQUEST["product_id"]) && !empty($_REQUEST["product_id"])) {
            $query = "DELETE FROM product WHERE product_id = '".$_REQUEST["product_id"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'additem.html',
	                        'message' => 'Item Deleted Successfully!');
            } else {
                $arreturnvalue = array('body' => 'additem.html',
	                        'message' => 'Item Deletion Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'additem.html',
        	                    'message' => 'Item Deletion Failed!');
        }
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
		
		
		//@shafiq 29-0609
		
		 case 'product_remove':
	
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        if (isset($_REQUEST["product_id"]) && !empty($_REQUEST["product_id"])) {
            $query = "DELETE FROM product WHERE product_id = '".$_REQUEST["product_id"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'admin_menu.html',
	                        'message' => 'Item Deleted Successfully!');
            } else {
                $arreturnvalue = array('body' => 'admin_menu.html',
	                        'message' => 'Item Deletion Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'admin_menu.html',
        	                    'message' => 'Item Deletion Failed!');
        }
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
		
		
	case 'my_resturant':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'my_resturant.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	
	case 'update_my_resturant':
		if(isset($_SESSION['adminuserid'])){
		
	if(!$_SESSION['resid']==""){
	
	
	
	
	//here
	
	include('lib/class.imgResize_resimg.php');
		$gotimage=0;
		  for($i=1;$i<=8;$i++)                                  
								
			{
			   				
				$postid=rand();
				$userfile_name = strtolower($_FILES['image'.$i]['name']);
				$userfile_tmp = $_FILES['image'.$i]['tmp_name'];
				$userfile_size = $_FILES['image'.$i]['size'];
				$userfile_type = $_FILES['image'.$i]['type'];
				
				if($userfile_name!=""){
				$gotimage=1;
				
Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				//$filedir = '../restaurant_image/';
	 			//$thumbdir = '../restaurant_image/'; 
				//$filedir1 = 'restaurant_image/';
	 			//$thumbdir1 = 'restaurant_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $res_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$res_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$res_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
              $img[$i-1]=$postid.'_'.$userfile_name;
			    $image_change[]=$i;
				}
				else
				{
				
				$res_img = "";
				$res_img_thumb = "";
				$res_img_thumb1 = "";
				}
				
				
		}		
		//upto
		$userfile_tmp='';
		$prod_img_thumbs='';
		$userfile_name = $_FILES['c_img']['name'];
				$userfile_tmp = $_FILES['c_img']['tmp_name'];
				$userfile_size = $_FILES['c_img']['size'];
				$userfile_type = $_FILES['c_img']['type'];
				$postids=rand();
				include_once('lib/class.imgResizes.php');
				Image_resizes($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postids);
				$filedir = 'companylogo/';
	 			$thumbdir = 'companylogo/'; 
				$filedir2 = 'companylogo/';
	 			$thumbdir2 = 'companylogo/'; 
     			$prefixs = 'large_'.$postids.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
				if (!$userfile_name=="")
     			{
		        $prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
				$prod_img_thumbs = strtolower($thumbdir2.$prefixs.$userfile_name);
				
				}
			if($prod_img_thumbs!="")
			{
			
            $query = "Update resturant set area_id='".mysql_escape_string($_REQUEST["area_id"])."',chinise_id='".mysql_escape_string($_REQUEST["chinise_id"])."',rname='".mysql_escape_string($_REQUEST["rname"])."',street='".mysql_escape_string($_REQUEST["street"])."',city='".mysql_escape_string($_REQUEST["city"])."',zipcode='".mysql_escape_string($_REQUEST["zipcode"])." ".mysql_escape_string($_REQUEST["zipcode2"])."',country_id='".mysql_escape_string($_REQUEST["country_id"])."',contact='".mysql_escape_string($_REQUEST["contact"])."',contact2='".mysql_escape_string($_REQUEST["contact2"])."',contact3='".mysql_escape_string($_REQUEST["contact3"])."',contact4='".mysql_escape_string($_REQUEST["contact4"])."',web='".mysql_escape_string($_REQUEST["web"])."',logo='$prod_img_thumbs',rdesc='".nl2br(mysql_escape_string($_REQUEST["rdesc"]))."',keytitle='".nl2br(mysql_escape_string($_REQUEST["keytitle"]))."',profile='".mysql_escape_string($_REQUEST["profile"])."',building='".mysql_escape_string($_REQUEST["building"])."',address2='".mysql_escape_string($_REQUEST["address2"])."',county='".mysql_escape_string($_REQUEST["county"])."' where rid='".mysql_escape_string($_REQUEST["id"])."'";
			}
			else
			{
			             $query = "Update resturant set area_id='".mysql_escape_string($_REQUEST["area_id"])."',chinise_id='".mysql_escape_string($_REQUEST["chinise_id"])."',rname='".mysql_escape_string($_REQUEST["rname"])."',street='".mysql_escape_string($_REQUEST["street"])."',city='".mysql_escape_string($_REQUEST["city"])."',zipcode='".mysql_escape_string($_REQUEST["zipcode"])." ".mysql_escape_string($_REQUEST["zipcode2"])."',country_id='".mysql_escape_string($_REQUEST["country_id"])."',contact='".mysql_escape_string($_REQUEST["contact"])."',contact2='".mysql_escape_string($_REQUEST["contact2"])."',contact3='".mysql_escape_string($_REQUEST["contact3"])."',contact4='".mysql_escape_string($_REQUEST["contact4"])."',web='".mysql_escape_string($_REQUEST["web"])."',rdesc='".nl2br(mysql_escape_string($_REQUEST["rdesc"]))."',keytitle='".nl2br(mysql_escape_string($_REQUEST["keytitle"]))."',profile='".mysql_escape_string($_REQUEST["profile"])."',building='".mysql_escape_string($_REQUEST["building"])."',address2='".mysql_escape_string($_REQUEST["address2"])."',county='".mysql_escape_string($_REQUEST["county"])."' where rid='".mysql_escape_string($_REQUEST["id"])."'";
			}
			
            $result = $db->execQuery($query);
			



			if($gotimage==1)
			{
				foreach($image_change as $key=>$value)
				{
				 $field="image".$value;
				 $no=$value-1;
				 $sql="update resturant set $field='$img[$no]' where rid='".mysql_escape_string($_REQUEST["id"])."'";
				$result = $db->execQuery($sql) or die(mysql_error()); 
				}
			}
			
			
                if ($result) {
                        $arreturnvalue = array('body' => 'my_resturant.html',
	                                        'message' => 'Resturant Updated Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'my_resturant.html',
	                                    'message' => 'Resturant Update Failed!');
                }
				}else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
         
		}else{
			
			$arreturnvalue = array('body' => 'login.html',
	                            'message' => '');
			}
	
	        break;
			
////////////////////////////sagar

	case 'resturant_owner':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'resturant_owner.html');
		}
		else{
		  	$arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
			$arreturnvalue = array('body' => 'login.html');
		}
        break;	

	case 'update_resturant_owner':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
			if($_REQUEST['userid']!="")
			{
				$query = "Update userregistration set 
					first_name='".mysql_escape_string($_REQUEST["first_name"])."',
					last_name='".mysql_escape_string($_REQUEST["last_name"])."',
					phone='".mysql_escape_string($_REQUEST["phone"])."',
					mobile='".mysql_escape_string($_REQUEST["mobile"])."',
					fax='".mysql_escape_string($_REQUEST["fax"])."',
					email='".mysql_escape_string($_REQUEST["email"])."',
					address1='".mysql_escape_string($_REQUEST["address1"])."',
					password='".mysql_escape_string($_REQUEST["password"])."',
					status='".mysql_escape_string($_REQUEST["status"])."'
					where id='".mysql_escape_string($_REQUEST["userid"])."'";
				$result = $db->execQuery($query);
				if($result)
				{
					$message="Update Successfuly";
				}else
				{
					$message="Update Failed";
				}
				$arreturnvalue = array('body' => 'resturant_owner.html');
			}
		}
		else{
		  	$arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	


//////////////////////////////sagar//////////
	
	case 'timeschedule':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'timeschedule.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'retaurant_orderlist':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'retaurant_orderlist.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		// bof: mahadeb
		case 'restaurant_account':
			if($_SESSION['adminuserid']!=""){
				if(!$_SESSION['resid']==""){
					$arreturnvalue = array('body' => 'retaurant_account.html');
				}else{
			  		$arreturnvalue = array('body' => 'resturantlist.html');
				}
		   
			}else{
				$arreturnvalue = array('body' => 'login.html');
			}
        break;	
		case 'discount_policy':
			if($_SESSION['adminuserid']!=""){
				if(!$_SESSION['resid']==""){
					$arreturnvalue = array('body' => 'discount_policy.php');
				}else{
			  		$arreturnvalue = array('body' => 'resturantlist.html');
				}
		   
			}else{
				$arreturnvalue = array('body' => 'login.html');
			}
        break;	
		case "invoice_pdf" :
			
			require_once("pdf/index.php");
			
			exit;
			break;
		// eof: mahadeb
		case 'restaurant_statements':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        	$arreturnvalue = array('body' => 'restaurant_statements.html');
		}else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	

		case 'restaurant_statement_details':
			if($_SESSION['adminuserid']!=""){
				if(!$_SESSION['resid']==""){
					$arreturnvalue = array('body' => 'restaurant_statement_details.html');
				}else{
					$arreturnvalue = array('body' => 'resturantlist.html');
				}
		   
			}else{
				$arreturnvalue = array('body' => 'login.html');
			}
			break;	
		
		case 'restaurant_statement_delete':
			if($_SESSION['adminuserid']!=""){
				if(!$_SESSION['resid']==""){
					$stm_id = (int)$_REQUEST['stm_id'];	
					$sql = "DELETE FROM order_statements WHERE rid='".$_SESSION['resid']."' AND stm_id='".$stm_id."'";
					$db->execQuery($sql);
					$_SESSION['message'] = "<font color='green'>Statement deleted successfully.</font>";
					header("location: index.php?action=restaurant_statements&tabm=rcttb"); exit;
				}else{
					$arreturnvalue = array('body' => 'resturantlist.html');
				}
		   
			}else{
				$arreturnvalue = array('body' => 'login.html');
			}
			break;	
case 'all_invoices':
	 	if($_SESSION['adminuserid']!=""){		
        	$arreturnvalue = array('body' => 'all_invoices.html');		
		}
		else{
			$arreturnvalue = array('body' => 'login.html');
		}
        break;		
case 'restaurant_invoice':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        	$arreturnvalue = array('body' => 'restaurant_invoice.html');
		}else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	

case 'restaurant_invoice_details':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'restaurant_invoice_details.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		case 'invoice':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'invoice.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		case 'online_acc_order':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'online_acc_order.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		case 'accounts_mng':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'accounts_mng.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		case 'online_acc_order_ponline':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'online_acc_order_ponline.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'allonlinepaidorderdetails':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'allonlinepaidorderdetails.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'allretaurant_orderlist':
	 	if($_SESSION['adminuserid']!=""){
		
		  $arreturnvalue = array('body' => 'allretaurant_orderlist.html');
		
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'ofline_acc_order_ponline':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'ofline_acc_order_ponline.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	
	case 'allofflinepaidorderdetails':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'allofflinepaidorderdetails.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		case 'send_paymeny':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'send_paymeny.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;		
	
		case 'insert_payment':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$rid=$_SESSION['resid'];
		  $query = "INSERT INTO  restaurant_payment(`pid` ,`rid` ,`ammount` ,`pay_desc` ,`pay_date`) VALUES ('','$rid','".mysql_escape_string($_REQUEST["ammount"])."','".nl2br($_REQUEST["pay_desc"])."','".mysql_escape_string($_REQUEST["pay_date"])."')";
			//echo $query;
			
            $result = $db->execQuery($query);
		  	if(!$result)
			{
					
                        $arreturnvalue = array('body' => 'send_paymeny.html',
	                            'message' => 'Payment Could not Posted');
			}else
			{
			                    $arreturnvalue = array('body' => 'send_paymeny.html',
	                            'message' => 'Payment Posted successfully');
			}
		
		
		
      
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;		
			
	case 'morderdetails':
	 	if($_SESSION['adminuserid']!=""){
		
		  $arreturnvalue = array('body' => 'morderdetails.html');
		
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	case 'insert_timeschedule':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$rids=$_SESSION['resid'];
		$open_h_mon_start="";
		$open_h_mon_end="";
		$open_h_mon_start2="";
		$open_h_mon_end2="";
		$open_h_tue_start="";
		$open_h_tue_end="";
		$open_h_tue_start2="";
		$open_h_tue_end2="";
		$open_h_wed_start="";
		$open_h_wed_end="";
		$open_h_wed_start2="";
		$open_h_wed_end2="";
		$open_h_thu_start="";
		$open_h_thu_end="";
		$open_h_thu_start2="";
		$open_h_thu_end2="";
		$open_h_fri_start="";
		$open_h_fri_end="";
		$open_h_fri_start2="";
		$open_h_fri_end2="";
		$open_h_sat_start="";
		$open_h_sat_end="";
		$open_h_sat_start2="";
		$open_h_sat_end2="";
		$open_h_sun_start="";
		$open_h_sun_end="";
		$open_h_sun_start2="";
		$open_h_sun_end2="";
		$open_h_bank_holiday_start="";
		$open_h_bank_holiday_end="";
		$open_h_bank_holiday_start2="";
		$open_h_bank_holiday_end2="";
		if($_REQUEST["lunchc"]!="")
		{
		$lstarttime=$_REQUEST["lunchc"];
		$lendtime="";
		}else
		{
		$lstarttime=$_REQUEST["sttimeh1"].":".$_REQUEST["sttimem1"];
		$lendtime=$_REQUEST["entimeh1"].":".$_REQUEST["entimem1"];
		}
		
		if($_REQUEST["dinerc"]!="")
		{
		$dstarttime=$_REQUEST["dinerc"];
		$dendtime="";
		}else
		{
		$dstarttime=$_REQUEST["sttimeh2"].":".$_REQUEST["sttimem2"];
		$dendtime=$_REQUEST["entimeh2"].":".$_REQUEST["entimem2"];
		}
		
		if($_REQUEST["wday"])
		foreach($_REQUEST["wday"] as $key=>$eday )    //new user friendly time schedule set **** SAGAR ****
		{
		 $_REQUEST["days"]=$eday;
		
		
		if($_REQUEST["days"]=="Monday")
		{
		$open_h_mon_start=$lstarttime;
		$open_h_mon_end=$lendtime;
		$open_h_mon_start2=$dstarttime;
		$open_h_mon_end2=$dendtime;
		$insvalue="('' ,'$rids', '$open_h_mon_start', '$open_h_mon_end', '$open_h_mon_start2', '$open_h_mon_end2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
		$upvalue="open_h_mon_start='$open_h_mon_start',open_h_mon_end= '$open_h_mon_end',open_h_mon_start2= '$open_h_mon_start2', open_h_mon_end2='$open_h_mon_end2' where rid='$rids'";
		}
		else if($_REQUEST["days"]=="Tuesday")
		{
		$open_h_tue_start=$lstarttime;
		$open_h_tue_end=$lendtime;
		$open_h_tue_start2=$dstarttime;
		$open_h_tue_end2=$dendtime;
		$insvalue="('' ,'$rids', '', '', '', '', '$open_h_tue_start', '$open_h_tue_end', '$open_h_tue_start2', '$open_h_tue_end2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
		$upvalue="open_h_tue_start='$open_h_tue_start',open_h_tue_end= '$open_h_tue_end',open_h_tue_start2= '$open_h_tue_start2', open_h_tue_end2='$open_h_tue_end2' where rid='$rids'";
		}
		else if($_REQUEST["days"]=="Wednesday")
		{
		$open_h_wed_start=$lstarttime;
		$open_h_wed_end=$lendtime;
		$open_h_wed_start2=$dstarttime;
		$open_h_wed_end2=$dendtime;
		$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '$open_h_wed_start', '$open_h_wed_end', '$open_h_wed_start2', '$open_h_wed_end2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
		$upvalue="open_h_wed_start='$open_h_wed_start',open_h_wed_end= '$open_h_wed_end',open_h_wed_start2= '$open_h_wed_start2', open_h_wed_end2='$open_h_wed_end2' where rid='$rids'";
		}else if($_REQUEST["days"]=="Thursday")
		{
		$open_h_thu_start=$lstarttime;
		$open_h_thu_end=$lendtime;
		$open_h_thu_start2=$dstarttime;
		$open_h_thu_end2=$dendtime;
		$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '', '', '', '', '$open_h_thu_start', '$open_h_thu_end', '$open_h_thu_start2', '$open_h_thu_end2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
		$upvalue="open_h_thu_start='$open_h_thu_start',open_h_thu_end= '$open_h_thu_end',open_h_thu_start2= '$open_h_thu_start2', open_h_thu_end2='$open_h_thu_end2' where rid='$rids'";
		}else if($_REQUEST["days"]=="Friday")
		{
		$open_h_fri_start=$lstarttime;
		$open_h_fri_end=$lendtime;
		$open_h_fri_start2=$dstarttime;
		$open_h_fri_end2=$dendtime;
		$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$open_h_fri_start', '$open_h_fri_end', '$open_h_fri_start2', '$open_h_fri_end2', '', '', '', '', '', '', '', '', '', '', '', '')";
		$upvalue="open_h_fri_start='$open_h_fri_start',open_h_fri_end= '$open_h_fri_end',open_h_fri_start2= '$open_h_fri_start2', open_h_fri_end2='$open_h_fri_end2' where rid='$rids'";
		}else if($_REQUEST["days"]=="Saturday")
		{
		$open_h_sat_start=$lstarttime;
		$open_h_sat_end=$lendtime;
		$open_h_sat_start2=$dstarttime;
		$open_h_sat_end2=$dendtime;
		$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$open_h_sat_start', '$open_h_sat_end', '$open_h_sat_start2', '$open_h_sat_end2', '', '', '', '', '', '', '', '')";
		$upvalue="open_h_sat_start='$open_h_sat_start',open_h_sat_end= '$open_h_sat_end',open_h_sat_start2= '$open_h_sat_start2', open_h_sat_end2='$open_h_sat_end2' where rid='$rids'";
		}
		else if($_REQUEST["days"]=="Sunday")
		{
		$open_h_sun_start=$lstarttime;
		$open_h_sun_end=$lendtime;
		$open_h_sun_start2=$dstarttime;
		$open_h_sun_end2=$dendtime;
		$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$open_h_sun_start', '$open_h_sun_end', '$open_h_sun_start2', '$open_h_sun_end2', '', '', '', '')";
		$upvalue="open_h_sun_start='$open_h_sun_start',open_h_sun_end= '$open_h_sun_end',open_h_sun_start2= '$open_h_sun_start2', open_h_sun_end2='$open_h_sun_end2' where rid='$rids'";
		}
		else if($_REQUEST["days"]=="Bank Holidays")
		{
		$open_h_bank_holiday_start=$lstarttime;
		$open_h_bank_holiday_end=$lendtime;
		$open_h_bank_holiday_start2=$dstarttime;
		$open_h_bank_holiday_end2=$dendtime;
		$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$open_h_bank_holiday_start', '$open_h_bank_holiday_end', '$open_h_bank_holiday_start2', '$open_h_bank_holiday_end2')";
		$upvalue="open_h_bank_holiday_start='$open_h_bank_holiday_start',open_h_bank_holiday_end= '$open_h_bank_holiday_end',open_h_bank_holiday_start2= '$open_h_bank_holiday_start2', open_h_bank_holiday_end2='$open_h_bank_holiday_end2' where rid='$rids'";
		}
		
       	$query = "SELECT * FROM timeschedule  where rid  = '$rids'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
		 $query = "INSERT INTO timeschedule (`id` ,`rid` ,`open_h_mon_start` ,`open_h_mon_end` ,`open_h_mon_start2` ,`open_h_mon_end2` ,`open_h_tue_start` ,											`open_h_tue_end` ,`open_h_tue_start2` ,`open_h_tue_end2` ,`open_h_wed_start` ,`open_h_wed_end` ,`open_h_wed_start2` ,`open_h_wed_end2` ,`open_h_thu_start` ,											`open_h_thu_end` ,`open_h_thu_start2` ,`open_h_thu_end2` ,`open_h_fri_start` ,`open_h_fri_end` ,`open_h_fri_start2` ,`open_h_fri_end2` ,`open_h_sat_start` ,											`open_h_sat_end` ,`open_h_sat_start2` ,`open_h_sat_end2` ,`open_h_sun_start` ,`open_h_sun_end` ,`open_h_sun_start2` ,`open_h_sun_end2` ,`open_h_bank_holiday_start` ,								`open_h_bank_holiday_end` ,	`open_h_bank_holiday_start2` ,`open_h_bank_holiday_end2`) VALUES $insvalue";
			
           $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'timeschedule.html',
	                                        'message' => 'Time Inserted Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'timeschedule.html',
	                                    'message' => 'Time Insertion Failed!');
                }
		}else
		{
			$query = "Update timeschedule set $upvalue";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'timeschedule.html',
	                                        'message' => 'Time Inserted Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'timeschedule.html',
	                                    'message' => 'Time Insertion Failed!');
                }
		}
		
		}  //end ****SAGAR ***
		$arreturnvalue['body'] = 'timeschedule.html';
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
	case 'resturantpolicy_policy':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'resturantpolicy_policy.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
        
	case 'insert_resturant_policy':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$rids=$_SESSION['resid'];
       	$query = "SELECT * FROM resturant_policy  where rid  = '$rids' and policy_id='".$_REQUEST[policy_id]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
		 $query = "INSERT INTO resturant_policy(`id` ,`rid` ,`policy_id`) VALUES('','$rids','".mysql_escape_string($_REQUEST["policy_id"])."')";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'resturantpolicy_policy.html',
	                                        'message' => 'Policy Added Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'resturantpolicy_policy.html',
	                                    'message' => 'Policy Insertion Failed!');
                }
		}else
		{
			$arreturnvalue = array('body' => 'resturantpolicy_policy.html',
	                            'message' => 'Policy Already taken into resturant account');
		}}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
		//@shafiq
		case 'insert_payment_method':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$rids=$_SESSION['resid'];
		
		
       	$query = "SELECT * FROM resturant_paymnt_method  where rid  = '$rids' and payment_method_id='".$_REQUEST[payment_method_id]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
		 $query = "INSERT INTO resturant_paymnt_method(`id` ,`rid` ,`payment_method_id`) VALUES('','$rids','".mysql_escape_string($_REQUEST["payment_method_id"])."')";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'resturant_payment_method.html',
	                                        'message' => 'Payment Method  Added Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'resturant_payment_method.html',
	                                    'message' => 'Payment Method Insertion Failed!');
                }
		}else
		{
			$arreturnvalue = array('body' => 'resturant_payment_method.html',
	                            'message' => 'Payment Method  Already taken into resturant account');
		}
		
		
		
		}else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;
		
		case 'del_payment_method':
			if (isset($_REQUEST["mid"]) && !empty($_REQUEST["mid"])) {
				$query = "DELETE FROM resturant_paymnt_method WHERE id = '".$_REQUEST["mid"]."'";
				$result = $db->execQuery($query);
				if ($result) {
					$arreturnvalue = array('body' => 'resturant_payment_method.html',
								'message' => 'Payment Method Withdraw Successfull!');
				} else {
					$arreturnvalue = array('body' => 'resturant_payment_method.html',
								'message' => 'Payment Method Withdraw Failed!');
				}
        	}else{
				$arreturnvalue = array('body' => 'resturant_payment_method.html');
			}	
		break;
		
		//@shafiq
		
		case 'del_policy_acc':
	
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        if (isset($_REQUEST["plid"]) && !empty($_REQUEST["plid"])) {
            $query = "DELETE FROM resturant_policy WHERE id = '".$_REQUEST["plid"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'resturantpolicy_policy.html',
	                        'message' => 'Policy Withdraw Successfull!');
            } else {
                $arreturnvalue = array('body' => 'resturantpolicy_policy.html',
	                        'message' => 'Policy Withdraw Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'resturantpolicy_policy.html',
        	                    'message' => 'Policy Withdraw Failed!');
        }
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;
		
	case 'delivery_policy':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'delivery_policy.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
	case 'insert_deliver_policy':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$rids=$_SESSION['resid'];
       	
        if ($_REQUEST["dpid"]=="") {
		 $query = "INSERT INTO delevary_policy(`dpid` ,`rid` ,`radious` ,`take_time` ,`del_time` ,`min_order`,`del_cost`,`del_start_at`,`note`) VALUES('','$rids','".mysql_escape_string($_REQUEST["radious"])."','".mysql_escape_string($_REQUEST["take_time"])."','".mysql_escape_string($_REQUEST["del_time"])."','".mysql_escape_string($_REQUEST["min_order"])."','".mysql_escape_string($_REQUEST["del_cost"])."','".mysql_escape_string($_REQUEST["del_start_at"])."','".mysql_escape_string($_REQUEST["note"])."')";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'delivery_policy.html',
	                                        'message' => 'Policy Inserted Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'delivery_policy.html',
	                                    'message' => 'Policy Insertion Failed!');
                }
		}else
		{
			$query = "Update delevary_policy set radious='".mysql_escape_string($_REQUEST["radious"])."',take_time='".mysql_escape_string($_REQUEST["take_time"])."',del_time='".mysql_escape_string($_REQUEST["del_time"])."',min_order='".mysql_escape_string($_REQUEST["min_order"])."',del_cost='".mysql_escape_string($_REQUEST["del_cost"])."',del_start_at='".mysql_escape_string($_REQUEST["del_start_at"])."',note='".mysql_escape_string($_REQUEST["note"])."' where dpid='".$_REQUEST["dpid"]."'";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'delivery_policy.html',
	                                        'message' => 'Policy Inserted/Updated Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'delivery_policy.html',
	                                    'message' => 'Policy Insertion/Update Failed!');
                }
		}
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	

/////////////////////////////////////////////Sagar

	case 'delivery_area':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        	$arreturnvalue = array('body' => 'delivery_area.html');
		}
		else{
		  	$arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	

	case 'insert_deliver_area':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$rids=$_SESSION['resid'];
       	
        if ($_REQUEST["area_id"]=="") {
			$query = "SELECT * FROM delivery_area WHERE rid='".$rids."' and post_code='".$_REQUEST["post_code"]."'";
			$exist = $db->checkExists($query);
			if(!$exist)
			{
				$query = "INSERT INTO delivery_area(`area_id` ,`rid` ,`post_code`,`delivery_radius`) VALUES('','$rids','".mysql_escape_string($_REQUEST["post_code"])."','".(float)$_REQUEST["delivery_radius"]."')";
				
				$result = $db->execQuery($query);
				  if ($result) {
							$arreturnvalue = array('body' => 'delivery_area.html',
												'message' => 'Delivery Area Inserted Successfully!');
					} else {
						$arreturnvalue = array('body' => 'delivery_area.html',
											'message' => 'Delivery Area Insertion Failed!');
					}
			}else
			{
				$arreturnvalue = array('body' => 'delivery_area.html',
									'message' => 'Delivery Area Already Exist');
			}
		}else
		{
			$query = "SELECT * FROM delivery_area WHERE rid='".$rids."' and post_code='".$_REQUEST["post_code"]."'";
			$exist = $db->checkExists($query);
			if(!$exist)
			{
				$query = "Update delivery_area set post_code='".mysql_escape_string($_REQUEST["post_code"])."',delivery_radius='".(float)$_REQUEST["delivery_radius"]."' where area_id='".$_REQUEST["area_id"]."'";
				
				$result = $db->execQuery($query);
				  if ($result) {
							$arreturnvalue = array('body' => 'delivery_area.html',
												'message' => 'Delivery Area Updated Successfully!');
					} else {
						$arreturnvalue = array('body' => 'delivery_area.html',
											'message' => 'Delivery Area Update Failed!');
					}
			}else
			{
				
		/**********************for Delivery Area update****************************/		
					$query = "SELECT * FROM delivery_area WHERE rid='".$rids."' and post_code='".$_REQUEST["post_code"]."'and delivery_radius='".(float)$_REQUEST["delivery_radius"]."'";
					$exist = $db->checkExists($query);
						
						if(!$exist)
					{
						$query = "Update delivery_area set delivery_radius='".(float)$_REQUEST["delivery_radius"]."' where area_id='".$_REQUEST["area_id"]."'";
						
						$result = $db->execQuery($query);
						  if ($result) {
									$arreturnvalue = array('body' => 'delivery_area.html',
														'message' => 'Radius Updated Successfully!');
							} else {
								$arreturnvalue = array('body' => 'delivery_area.html',
													'message' => 'Radius Update Failed!');
							}
					}
						
				
				else{
					$arreturnvalue = array('body' => 'delivery_area.html',
									'message' => 'Delivery Area and Radius Already Exist');
					}
			}
		}
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	

	case 'del_delivery_area':
	
		if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        if (isset($_REQUEST["area_id"]) && !empty($_REQUEST["area_id"])) {
            $query = "DELETE FROM delivery_area WHERE area_id = '".$_REQUEST["area_id"]."'";
            $result = $db->execQuery($query);
            if ($result) {
            	$arreturnvalue = array('body' => 'delivery_area.html',
	                        'message' => 'Delivery Area Delete Successfull!');
            } else {
                $arreturnvalue = array('body' => 'delivery_area.html',
	                        'message' => 'Delivery Area Deletion Failed!');
            }
        } else {
        	$arreturnvalue = array('body' => 'delivery_area.html',
        	                    'message' => 'Delivery Area Deletion Failed!');
        }
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
		}else{
			
			$arreturnvalue = array('body' => 'login.html');
			}
        break;


/////////////////////////////////////////////////////

	case 'service_set':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
        $arreturnvalue = array('body' => 'service_set.html');
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
       
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
		
			case 'insert_service_set':
	 	if($_SESSION['adminuserid']!=""){
		if(!$_SESSION['resid']==""){
		$rids=$_SESSION['resid'];
       	
        if ($_REQUEST["rsid"]=="") {
		 $query = "INSERT INTO resturant_service(`rsid` ,`rid` ,`commission` ,`commission_pay` ,`hfee` ,`hadeling_pay` ,`ccfee` ,`cc_pay` ,`vat` ,`pr_code`) VALUES('','$rids','".mysql_escape_string($_REQUEST["commission"])."','1','".mysql_escape_string($_REQUEST["hfee"])."','".mysql_escape_string($_REQUEST["hadeling_pay"])."','".mysql_escape_string($_REQUEST["ccfee"])."','".mysql_escape_string($_REQUEST["cc_pay"])."','".mysql_escape_string($_REQUEST["vat"])."','".mysql_escape_string($_REQUEST["pr_code"])."')";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'service_set.html',
	                                        'message' => 'Service Data Inserted Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'service_set.html',
	                                    'message' => 'Service Data Insertion Failed!');
                }
		}else
		{         
			$query = "Update resturant_service set commission='".mysql_escape_string($_REQUEST["commission"])."',hfee='".mysql_escape_string($_REQUEST["hfee"])."',ccfee='".mysql_escape_string($_REQUEST["ccfee"])."',commission_pay='1',hadeling_pay='".mysql_escape_string($_REQUEST["hadeling_pay"])."',cc_pay='".mysql_escape_string($_REQUEST["cc_pay"])."',vat='".mysql_escape_string($_REQUEST["vat"])."',pr_code='".mysql_escape_string($_REQUEST["pr_code"])."' where rsid='".$_REQUEST["rsid"]."'";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'service_set.html',
	                                        'message' => 'Service Data Inserted/Updated Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'service_set.html',
	                                    'message' => 'Service Data Insertion/Update Failed!');
                }
		}
		}
		else{
		  $arreturnvalue = array('body' => 'resturantlist.html');
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'login.html');
		}
        break;	
// DELETE process
		case 'resturant_del':
		
			$rid = $_REQUEST['rid'];
			$ref = $_REQUEST['ref'];
			
        	$status = resturant_del($rid);
			if($status){
				$_SESSION["message"]="<font color='green'>Restaurant deleted successfully.</font>";
			}else{
				$_SESSION["message"]="<font color='green'>Restaurant haven't deleted.</font>";	
			}
			
			if($ref == "request_list"){
				header("location: index.php?action=request_list&tabm=rcttb"); exit;
			}else{
				header("location: index.php?action=inacresturant_list&tabm=rcttb"); exit;
			}
			
        	break; 	
	case 'customer_del':
		
			$id = $_REQUEST['id'];
			$type = $_REQUEST['type'];
			
        	$status = customer_del($id);
			if($status){
				$_SESSION["message"]="<font color='green'>Customer deleted successfully.</font>";
			}else{
				$_SESSION["message"]="<font color='green'>Customer haven't deleted.</font>";	
			}
			
			header("location: index.php?action=existing_customer&tabm=&type=".$type); exit;
			
			
        	break; 		
	case 'order_delete':
		
			$id = $_REQUEST['id'];
			$type = $_REQUEST['type'];
			
        	$status = order_delete($id);
			if($status){
				$_SESSION["message"]="<font color='green'>Order deleted successfully.</font>";
			}else{
				$_SESSION["message"]="<font color='green'>Order haven't deleted.</font>";	
			}
			
			header("location: index.php?action=allretaurant_orderlist&tabm=actd"); exit;
			
			
        	break; 		
// _END DELETE PROCESS	
	//--------------------------------
    case 'logout':
        session_unset();
        session_destroy();
        $arreturnvalue = array('body' => 'logout.html');
        break; 
		
		     
    default:
        $arreturnvalue = array('body' => 'login.html');
    }
    
} else {
	session_unset();
    session_destroy();
	$arreturnvalue = array('body' => 'login.html');
}


$body = $arreturnvalue['body'];
$message = $arreturnvalue['message'];
?>
<HTML>
<HEAD>
<TITLE>admin</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
<link href="pagination.css" rel="stylesheet" type="text/css">
<script type="text/javascript" language="javascript" src="js/script.js"></script>
<style type="text/css">
<!--
.style2 {font-weight: bold}
-->
</style>
<script type="text/JavaScript">
<!--



function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
</HEAD>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<!-- ImageReady Slices (admin.psd) -->
<TABLE WIDTH=100% BORDER=0 align="center" CELLPADDING=0 CELLSPACING=0>
	<TR>
	  <TD> <?php
  if ($body!="invoice.html" && $body!="add_menu.html" && $body!="addFood.html" && $body!="addFoodSelection.html" && $body!="edit_menu.html" && $body!="food_item_edit.html" && $body!="selection_food_item_edit.html"){
	
	if ($body!="welcome.html" && $body!="login.html" && $body!="logout.html"){
	require_once "template/admin_header.html";
	 
	}else
	{
	require_once "template/header.html";
	}

	
	?>

	</TD>
	</TR>
	
	<TR>
	 <TD><table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
         <td background="images/index_10.jpg"><img src="images/index_10.jpg" width="33" height="33"></td>
         <td width="100%" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
             <?php	if ($tabm!="" and $_SESSION['adminuserid']!=""){ ?>
			<?php if($body != "allretaurant_orderlist.html" && $body != "all_invoices.html"){ ?>
             <? if($tabm!="cmst"){?>
			 <td width="232" valign="top"><?php if($tabm=="admu"){require_once "template/admin_user_menue.html";}
			else if($tabm=="rcttb"){require_once "template/resturant_control_menue.html";} else if($tabm=="cusm"){require_once "template/customer_menue.html";}
			  ?></td>
			  <?php }?>
             <?php  
			 		}		 
			 	}
			 ?>
             <td><?php require_once "template/$body"; ?></td>
			 
			
           </tr>
         </table>
        </td>
         <td background="images/index_12.jpg"><img src="images/index_12.jpg" width="38" height="76"></td>
       </tr>
     </table></TD>
	
  </TR>
	
	
	
	<TR>
	  <TD><?php require_once "template/footer.html";
	  
	  }
	  else
	  {
	  	require_once "template/$body"; 
	  }
	  ?></TD>
  </TR>
	
	<TR>
		<TD>
			<IMG SRC="images/spacer.gif" WIDTH=33 HEIGHT=1 ALT=""></TD>
	</TR>
</TABLE>
<!-- End ImageReady Slices -->
</BODY>
</HTML>