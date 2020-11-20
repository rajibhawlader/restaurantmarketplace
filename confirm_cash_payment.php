<?php
require_once('lib/class.Db.php');
	
	
require_once('config.php');
    $db = new Db(hostname, username, password, databasename);
ob_start();
session_start();
$pageName='';
//echo 'action='.$_REQUEST["action"];
 $tbv=$_REQUEST[tbq];
	if($tbv=="")
	{
	$tbv="hm";
	}
if (isset($_REQUEST["action"]) && !empty($_REQUEST["action"])) { 
	
   
	switch ($_REQUEST["action"]) {
     
	 
	 case 'login_verify':
		require_once('lib/class.LoginVerify.php');
		$loginverify = new LoginVerify($_REQUEST["username"], $_REQUEST["password"]);
        $loginstatus = $loginverify->checkUser($db);
		
        if ($loginstatus == true) {
		$uids=$_SESSION['userid'];
		
									$resquery="Select * from resturant where userid='$uids'";
									
									$resultres=mysql_query($resquery);
									$rowres=mysql_fetch_array($resultres);
								$_SESSION['resid']=$rowres["rid"];
								
			$pageName='My Account';
        	$arreturnvalue = array('body' => 'may_account_home.html');
			
        } else {
        	$pageName='Home';
			$arreturnvalue = array('body' => 'resturant_owner_login.html',
	                            'message' => 'Invalid Username or Password!');
        }
        break;
	 case 'pages':
   		
        $arreturnvalue = array('body' => 'pages.html');
		
        break;
	 case 'resturant_owner_login':
   		
        $arreturnvalue = array('body' => 'resturant_owner_login.html');
		
        break;
case 'restaurant_list':

$arreturnvalue = array('body' => 'restaurant_list.html');
$leftFlg = '0';
break;

 case 'pages':
   $arreturnvalue = array('body' => 'pages.html');
	break;
		
	 case 'menu':
   		
        $arreturnvalue = array('body' => 'menu.html');
		
        break;
		
	case 'new_resturant':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'new_resturant.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;
	
	case 'may_account_home':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'may_account_home.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;
	
	case 'my_orderlist':
	 	if($_SESSION['customers_id']!=""){
        $arreturnvalue = array('body' => 'retaurant_orderlist.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;
	case 'orderdetails':
	 	if($_SESSION['customers_id']!=""){
        $arreturnvalue = array('body' => 'orderdetails.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;
	 case 'insert_new_resturant':
		if($_SESSION['userid']!=""){
		//include_once('lib/class.dircreate.php');
		$uids=$_SESSION['userid'];
		$postids=rand();
	
        $query = "SELECT * FROM resturant  where rname  = '".$_REQUEST["rname"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
		$userfile_type='';
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
     			$prefixs = 'small_'.$postids.'_';
				
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
				if (!$userfile_name=="")
     {
		        $prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
				$prod_img_thumbs = $thumbdir2.$prefixs.$userfile_name;
				
				}
			
            $query = "INSERT INTO resturant VALUES('','$uids','".mysql_escape_string($_REQUEST["area_id"])."','".mysql_escape_string($_REQUEST["chinise_id"])."','".mysql_escape_string($_REQUEST["rname"])."','".mysql_escape_string($_REQUEST["street"])."','".mysql_escape_string($_REQUEST["city"])."','".mysql_escape_string($_REQUEST["zipcode"])."','".mysql_escape_string($_REQUEST["country_id"])."','".mysql_escape_string($_REQUEST["contact"])."','".mysql_escape_string($_REQUEST["web"])."','$prod_img_thumbs','".nl2br($_REQUEST["rdesc"])."','".nl2br($_REQUEST["keytitle"])."','','')";
			
            $result = $db->execQuery($query);
			$postid = $db->lastInsert($result);
			$_SESSION['resid']=$postid;
                if ($result) {
                        $arreturnvalue = array('body' => 'my_resturant.html',
	                                        'message' => 'Restaurant set up Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'new_resturant.html',
	                                    'message' => 'Restaurant set up Failed!');
                }
        } else {
        	$arreturnvalue = array('body' => 'new_resturant.html',
	                            'message' => 'Restaurant Already Exists!.');
        }
	
		}else{
			
			$arreturnvalue = array('body' => 'home_body.html',
	                            'message' => '');
			}
	
	        break;
	case 'my_resturant':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'my_resturant.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
		
case 'update_my_resturant':
		if(isset($_SESSION['userid'])){
	
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
     			$prefixs = 'small_'.$postids.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
				if (!$userfile_name=="")
     {
		        $prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
				$prod_img_thumbs = $thumbdir2.$prefixs.$userfile_name;
				
				}
			if($prod_img_thumbs!="")
			{
			
            $query = "Update resturant set area_id='".mysql_escape_string($_REQUEST["area_id"])."',chinise_id='".mysql_escape_string($_REQUEST["chinise_id"])."',rname='".mysql_escape_string($_REQUEST["rname"])."',street='".mysql_escape_string($_REQUEST["street"])."',city='".mysql_escape_string($_REQUEST["city"])."',zipcode='".mysql_escape_string($_REQUEST["zipcode"])."',country_id='".mysql_escape_string($_REQUEST["country_id"])."',contact='".mysql_escape_string($_REQUEST["contact"])."',web='".mysql_escape_string($_REQUEST["web"])."',logo='$prod_img_thumbs',rdesc='".nl2br($_REQUEST["rdesc"])."',keytitle='".nl2br($_REQUEST["keytitle"])."' where rid='".mysql_escape_string($_REQUEST["id"])."'";
			}else
			{
			 $query = "Update resturant set area_id='".mysql_escape_string($_REQUEST["area_id"])."',chinise_id='".mysql_escape_string($_REQUEST["chinise_id"])."',rname='".mysql_escape_string($_REQUEST["rname"])."',street='".mysql_escape_string($_REQUEST["street"])."',city='".mysql_escape_string($_REQUEST["city"])."',zipcode='".mysql_escape_string($_REQUEST["zipcode"])."',country_id='".mysql_escape_string($_REQUEST["country_id"])."',contact='".mysql_escape_string($_REQUEST["contact"])."',web='".mysql_escape_string($_REQUEST["web"])."',rdesc='".nl2br($_REQUEST["rdesc"])."',keytitle='".nl2br($_REQUEST["keytitle"])."' where rid='".mysql_escape_string($_REQUEST["id"])."'";
			}
			
            $result = $db->execQuery($query);
                if ($result) {
                        $arreturnvalue = array('body' => 'my_resturant.html',
	                                        'message' => 'Restaurant Updated Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'my_resturant.html',
	                                    'message' => 'Restaurant Update Failed!');
                }
         
		}else{
			
			$arreturnvalue = array('body' => 'home_body.html',
	                            'message' => '');
			}
	
	        break;
	
	case 'change_accout':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'change_account.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
	
	case 'update_account':
				if($_SESSION['userid']!=""){
				$ids=$_REQUEST["id"];
				
				$query1 = "SELECT * FROM userregistration WHERE email='".$_REQUEST["email"]."' and id!='$ids'";
        		$success1 = $db->checkExists($query1);
		
        if (!$success1) {
		      
			
            $query = "Update  userregistration  set first_name='".mysql_escape_string($_REQUEST["first_name"])."', last_name='".mysql_escape_string($_REQUEST["last_name"])."', phone='".mysql_escape_string($_REQUEST["phone"])."', fax='".mysql_escape_string($_REQUEST["fax"])."', email='".mysql_escape_string($_REQUEST["email"])."', address1='".mysql_escape_string($_REQUEST["address1"])."',password='".mysql_escape_string($_REQUEST["password"])."' where id='$ids'";
			//echo $query;
			
            $result = $db->execQuery($query);
		  	$postid = $db->lastInsert($result);
					
					$pageName="Registration";
                        $arreturnvalue = array('body' => 'change_account.html',
	                            'message' => 'Account Update successfully!!');
			
			
			
       }else {
							
        	$arreturnvalue = array('body' => 'change_account.html',
	                            'message' => 'This mail is resistered by another user please try another!!');
        }
		}else
		{
		
		$arreturnvalue = array('body' => 'home_body.html');
		}
		
	
	    
	        break;	
	
	
	case 'sign_up':
   		
        $arreturnvalue = array('body' => 'sign_up.html');
		
        break;		
		
	case 'registration':
				
				$hfroms=$_REQUEST["hfrom"];
				if($hfroms=="")
				{
				$hfroms=$_REQUEST["thfrom"];
				}
				$tecncode=$_REQUEST["ecode"];
				$oencode=$_REQUEST["intypecode"];
				if($tecncode==$oencode){
				$query1 = "SELECT email FROM  userregistration  where email= '".$_REQUEST["email"]."'";
        		$success1 = $db->checkExists($query1);
		
        if (!$success1) {
		      
			
            $query = "INSERT INTO  userregistration  VALUES (
'', '".mysql_escape_string($_REQUEST["first_name"])."', '".mysql_escape_string($_REQUEST["last_name"])."', '".mysql_escape_string($_REQUEST["phone"])."', '".mysql_escape_string($_REQUEST["fax"])."', '".mysql_escape_string($_REQUEST["email"])."', '".mysql_escape_string($_REQUEST["address1"])."','".mysql_escape_string($_REQUEST["password"])."', '')";
			//echo $query;
			
            $result = $db->execQuery($query);
		  	$postid = $db->lastInsert($result);
					
					$pageName="Registration";
                        $arreturnvalue = array('body' => 'registratiionsuccess.html');
			
			
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
<br>
To activate your account  <a href="http://indianfoodsonline.co.uk/index-action-activeuser-uid-'.$postid.'" target="_blank">Click here</a>. <br />
Many Thanks<br />
The <a href="http://indianfoodsonline.co.uk" target="_blank">indianfoodsonline.co.uk</a> Team - <a href="mailto:info@indianfoodsonline.co.uk">info@indianfoodsonline.co.uk</a>';
		
		$send_to =  $_REQUEST[email];
		$subject="Congratulation and thank you for signup at Indian Food Online";
		
		$sent = mail($send_to, $subject, $body_txt, $headers);
		
			
       }else {
			$first_name=$_REQUEST['first_name'];
			$last_name=$_REQUEST['last_name'];
			$rname=$_REQUEST['rname'];
			$phone=$_REQUEST['phone'];
			$fax=$_REQUEST['fax'];
			$email=$_REQUEST['email'];
			$address1=$_REQUEST['address1'];				
        	$arreturnvalue = array('body' => 'sign_up.html',
	                            'message' => 'You have already registered. Please log in to set up your restaurant');
        }
		}else
		{
		$pageName="Registration";
			$first_name=$_REQUEST['first_name'];
			$last_name=$_REQUEST['last_name'];
			$rname=$_REQUEST['rname'];
			$phone=$_REQUEST['phone'];
			$fax=$_REQUEST['fax'];
			$email=$_REQUEST['email'];
			$address1=$_REQUEST['address1'];
			
		$arreturnvalue = array('body' => 'sign_up.html',
	                            'message' => 'Code Missmatch. Please try again.');
		}
		
	
	    
	        break;	
case 'activeuser':
            $query = "Update userregistration set status='1' WHERE id = '".$_REQUEST["uid"]."'";
            $result = $db->execQuery($query);
           
            	$arreturnvalue = array('body' => 'activationacc.html');
              break;
			  			
case 'menu_list':
$_SESSION['rids']=$_REQUEST['rid'];
if($_SESSION['rids']!="")
{
$_SESSION['order_type']=1;
$arreturnvalue = array('body' => 'order_menu.html');
}else
{
$arreturnvalue = array('body' => 'home_body.html');
}
break;

case 'menu_list_collection':
$_SESSION['rids']=$_REQUEST['rid'];
if($_SESSION['rids']!="")
{
$_SESSION['order_type']=2;
$arreturnvalue = array('body' => 'order_menu.html');
}else
{
$arreturnvalue = array('body' => 'home_body.html');
}
break;

case 'reservetion_order':
$_SESSION['rids']=$_REQUEST['rid'];
if($_SESSION['rids']!="")
{
$arreturnvalue = array('body' => 'reservetion.html');
}else
{
$arreturnvalue = array('body' => 'home_body.html');
}
break;


	case 'catalog':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'catalog.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
		
	case 'addmenu':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'addmenu.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
		
	case 'insert_new_menue':
	 	if($_SESSION['userid']!=""){
		$rids=$_SESSION['resid'];
       	$query = "SELECT * FROM categories  where rid  = '$rids' and categories_name='".$_REQUEST[categories_name]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
		 $query = "INSERT INTO categories VALUES('','$rids','".mysql_escape_string($_REQUEST["categories_name"])."','".mysql_escape_string($_REQUEST["parent_id"])."','','$d','')";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'addmenu.html',
	                                        'message' => 'Menu Inserted Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'addmenu.html',
	                                    'message' => 'Menu Insertion Failed!');
                }
		}else
		{
			$arreturnvalue = array('body' => 'addmenu.html',
	                            'message' => 'Menue Already inserted');
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
	
	case 'additem':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'additem.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
	
	case 'timeschedule':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'timeschedule.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
		
		case 'delivery_policy':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'delivery_policy.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
		
				
case 'insert_reservation':

if($_SESSION['rids']!="")
{
$ostatus=0;
$_SESSION['arival_date']=$_REQUEST['arival_date'];


$_SESSION['nop']=$_REQUEST['nop'];
$_SESSION['celibration']=$_REQUEST['celibration'];
$_SESSION['comments']=nl2br($_REQUEST['comments']);

$rids=$_SESSION['rids'];
$hr=$_REQUEST['h'];

$amck=$_REQUEST['af'];
$_SESSION['aritimes']="$hr:".$_REQUEST['m']." $amck";


if($amck=='PM')
{
$hr=$hr+12;
}
if($hr==24)
{
$hr=0;
}
$_SESSION['atime']="$hr:".$_REQUEST['m'].":00";
$dayt=$_SESSION['arival_date'];
$dayt = strtotime($dayt);
$daytr = date('l', $dayt);

if($daytr=="Monday")
{
$dayconls="open_h_mon_start";
$dayconle="open_h_mon_end";
$dayconds="open_h_mon_start2";
$dayconde="open_h_mon_end2";
}
else if($daytr=="Tuesday")
{
$dayconls="open_h_tue_start";
$dayconle="open_h_tue_end";
$dayconds="open_h_tue_start2";
$dayconde="open_h_tue_end2";
}
else if($daytr=="Wednesday")
{
$dayconls="open_h_wed_start";
$dayconle="open_h_wed_end";
$dayconds="open_h_wed_start2";
$dayconde="open_h_wed_end2";
}
else if($daytr=="Thursday")
{
$dayconls="open_h_thu_start";
$dayconle="open_h_thu_end";
$dayconds="open_h_thu_start2";
$dayconde="open_h_thu_end2";
}
else if($daytr=="Friday")
{
$dayconls="open_h_fri_start";
$dayconle="open_h_fri_end";
$dayconds="open_h_fri_start2";
$dayconde="open_h_fri_end2";
}
else if($daytr=="Saturday")
{
$dayconls="open_h_sat_start";
$dayconle="open_h_sat_end";
$dayconds="open_h_sat_start2";
$dayconde="open_h_sat_end2";
}
else
{
$dayconls="open_h_sun_start";
$dayconle="open_h_sun_end";
$dayconds="open_h_sun_start2";
$dayconde="open_h_sun_end2";
}

$sqlsed="Select $dayconls,$dayconle,$dayconds,$dayconde from timeschedule where rid='$rids'";
$resultsed=mysql_query($sqlsed);
$rowsed=mysql_fetch_array($resultsed);

if($rowsed[$dayconls]!="Closed For the day" and $rowsed[$dayconds]!="Closed For the day")
{
$lst= strtotime("$rowsed[$dayconls]:00");
$len=strtotime("$rowsed[$dayconle]:00");
$dst= strtotime("$rowsed[$dayconds]:00");
$den=strtotime("$rowsed[$dayconde]:00");
$atm=$_SESSION['atime'];

$atm= strtotime($atm);

if($atm>=$lst and $atm<=$len)
{
$ostatus=1;
}else if($atm>=$dst and $atm<=$den)
{
$ostatus=1;
}


}else if($rowsed[$dayconls]=="Closed For the day" and $rowsed[$dayconds]!="Closed For the day")
{

$dst= strtotime("$rowsed[$dayconds]:00");
$den=strtotime("$rowsed[$dayconde]:00");
$atm=$_SESSION['atime'];

$atm= strtotime($atm);

if($atm>=$dst and $atm<=$den)
{
$ostatus=1;
}


}else if($rowsed[$dayconls]!="Closed For the day" and $rowsed[$dayconds]=="Closed For the day")
{
$lst= strtotime("$rowsed[$dayconls]:00");
$len=strtotime("$rowsed[$dayconle]:00");
$atm=$_SESSION['atime'];

$atm= strtotime($atm);

if($atm>=$lst and $atm<=$len)
{
$ostatus=1;
}
}
if($ostatus==1)
{
if($_SESSION['customers_id']==""){			
$arreturnvalue = array('body' => 'reservetion_place.html');
	}else
								{
								
								 $arreturnvalue = array('body' => 'reservetion_confirm.html');
								}
}else
{
$arreturnvalue = array('body' => 'reservetion.html',
	                                    'message' => 'The date or time you have chosen is invalid or the restaurant is closed at that time. Please specify another time or take a look a the restaurant open times.');
}
}else
{
$arreturnvalue = array('body' => 'home_body.html');
}
break;
		
		case 'order_place':
	 	if($_SESSION['customers_id']==""){			
        	$arreturnvalue = array('body' => 'oredr_place.html');
								}else
								{
								if($_SESSION['order_type']=="1")
								{
								 $arreturnvalue = array('body' => 'checkout_shipping.html');
								 }else
								 {
								  $arreturnvalue = array('body' => 'checkout_collection.html');
								 }
								}
          break;	
		 
		
		   
		 case 'customer_login':
		  
		require_once('lib/class.customerLoginVerify.php');
		$customerLoginVerify = new customerLoginVerify($_REQUEST["username"], $_REQUEST["password"]);
        $cloginstatus = $customerLoginVerify->checkUser($db);
		
        if ($cloginstatus == true) {
			if($_REQUEST['otp']=="orp")
			{					
			$pageName='My Account';
        	$arreturnvalue = array('body' => 'checkout_shipping.html');
			}else if($_REQUEST['otp']=="resv")
			{
			$arreturnvalue = array('body' => 'reservetion_confirm.html');
			}else
			{
			$arreturnvalue = array('body' => 'customeraccounthome.html');
			}
			
        } else {
			if($_REQUEST['otp']=="orp")
			{					
			$pageName='My Account';
        	$arreturnvalue = array('body' => 'oredr_place.html',
	                            'message' => 'Invalid Username or Password!');
			}else if($_REQUEST['otp']=="resv")
			{
			$arreturnvalue = array('body' => 'reservetion_place.html',
	                            'message' => 'Invalid Username or Password!');
			}else
			{
			$arreturnvalue = array('body' => 'home_body.html',
	                            'message' => 'Invalid Username or Password!');
			}
        
        }
        break;
		
		
		case 'customer_registration':
	 	$arreturnvalue = array('body' => 'customer_registration.html');
		break;	
		case 'insert_customer':
	
				$query1 = "SELECT customers_email_address FROM  customers  where customers_email_address= '".$_REQUEST["customers_email_address"]."'";
        		$success1 = $db->checkExists($query1);
		
        if (!$success1) {
		      
			
            $query = "INSERT INTO  customers  VALUES ('', '', '".mysql_escape_string($_REQUEST["customers_firstname"])."', '".mysql_escape_string($_REQUEST["customers_lastname"])."', '', '".mysql_escape_string($_REQUEST["customers_email_address"])."', '".mysql_escape_string($_REQUEST["customers_address1"])."', '".mysql_escape_string($_REQUEST["customers_address2"])."', '".mysql_escape_string($_REQUEST["customers_town"])."', '".mysql_escape_string($_REQUEST["customers_state"])."', '".mysql_escape_string($_REQUEST["customers_country"])."', '".mysql_escape_string($_REQUEST["customers_postcode"])."', '".mysql_escape_string($_REQUEST["customers_telephone"])."', '".mysql_escape_string($_REQUEST["customers_fax"])."', '".mysql_escape_string($_REQUEST["customers_password"])."')";
			//echo $query;
			
            $result = $db->execQuery($query);
		  	$postid = $db->lastInsert($result);
			if($_SESSION[cart]!=""){
			$_SESSION['customers_id']=$postid;
			$_SESSION['customers_email_address']=mysql_escape_string($_REQUEST["customers_email_address"]);	
				$_SESSION['customers_address1']=mysql_escape_string($_REQUEST["customers_address1"]);	
				$_SESSION['customers_address2']=mysql_escape_string($_REQUEST["customers_address2"]);	
				$_SESSION['customers_town']=mysql_escape_string($_REQUEST["customers_town"]);	
				$_SESSION['customers_state']=mysql_escape_string($_REQUEST["customers_state"]);	
				$_SESSION['customers_country']=mysql_escape_string($_REQUEST["customers_country"]);	
					$_SESSION['customers_postcode']=mysql_escape_string($_REQUEST["customers_postcode"]);	
				
					$pageName="Registration";
                        $arreturnvalue = array('body' => 'checkout_shipping.html');
						}else
						{
							$pageName="Customer Registration Success";
						   $arreturnvalue = array('body' => 'customerregistratiionsuccess.html');
						}
			
			
		/*$email="info@indianfoodsonline.co.uk";
		
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
<br>
To activate your account  <a href="http://indianfoodsonline.co.uk/index.php?action=active&id='.$unames.'" target="_blank">Click here</a>. <br />
Many Thanks<br />
The <a href="http://indianfoodsonline.co.uk" target="_blank">indianfoodsonline.co.uk</a> Team - <a href="mailto:info@indianfoodsonline.co.uk">info@indianfoodsonline.co.uk</a>';
		
		$send_to =  $_REQUEST[email];
		$subject="Congratulation and thank you for signup at Indian Food Online";
		
		$sent = mail($send_to, $subject, $body_txt, $headers);*/
		
			
       }else {
			if($_SESSION['customers_id']==""){			
        	$arreturnvalue = array('body' => 'oredr_place.html',
	                            'message' => 'You have already registered. Please log in to Place Order');
								}else
								{
								 $arreturnvalue = array('body' => 'checkout_shipping.html');
								}
        }
		
	        break;	
	 	case 'change_address':
	 	if($_SESSION['customers_id']==""){			
        	$arreturnvalue = array('body' => 'oredr_place.html');
								}else
								{
								 $arreturnvalue = array('body' => 'change_address.html');
								}
          break;			
			
		case 'insert_shipping_address':
	 	if($_SESSION['customers_id']==""){			
        	$arreturnvalue = array('body' => 'oredr_place.html');
								}else
								{
								 $_SESSION['customers_address1']=$_REQUEST['customers_address1'];
				$_SESSION['customers_address2']=$_REQUEST['customers_address2'];
				$_SESSION['customers_town']=$_REQUEST['customers_town'];
				$_SESSION['customers_state']=$_REQUEST['customers_state'];
				$_SESSION['customers_country']=$_REQUEST['customers_country'];
					$_SESSION['customers_postcode']=$_REQUEST['customers_postcode'];		
								 $arreturnvalue = array('body' => 'checkout_shipping.html');
								}
          break;		
		case 'mycart':
	 
								 $arreturnvalue = array('body' => 'mycart.php');
				
          break;	
		  
		  case 'current_reservetion':
	 
								 $arreturnvalue = array('body' => 'reservetion_confirm.html');
				
          break;	
		  
		  case 'reorder':
	 	if($_SESSION['customers_id']!=""){		
		$orids=$_REQUEST['orid'];
$sqlord="Select * from customer_order where orid='$orids'";
$resultor=$db->execQuery($sqlord);
$rowor=$db->resultArray($resultor);
$_SESSION['order_type']=="1";
$_SESSION['rids']=$rowor[0]["rid"];
$_SESSION['customers_address1']=$rowor[0]["customers_address1"];
$_SESSION['customers_address2']=$rowor[0]["customers_address2"];
$_SESSION['customers_town']=$rowor[0]["customers_town"];
$_SESSION['customers_state']=$rowor[0]["customers_state"];
$_SESSION['customers_country']=$rowor[0]["customers_country"];
$_SESSION['customers_postcode']=$rowor[0]["customers_postcode"];

$sqlordl="Select * from order_detail where orid='$orids'";
$resultordt = $db->execQuery($sqlordl);
			$rowpord = $db->resultArray($resultordt);
            $ste=sizeof($rowpord);
			unset($_SESSION['cart']);
			unset($_SESSION['qt']);
            for ($i = 0; $i < $ste; $i++)
			{
			
			$_SESSION['cart'][$i]=$rowpord[$i]["product_id"];
			$_SESSION['qty'][$i]=$rowpord[$i]["qantity"];
			}	
        	$arreturnvalue = array('body' => 'checkout_shipping.html');
								}else
								{
								
								 $arreturnvalue = array('body' => 'customeraccounthome.html');
								}
          break;	

		  
		  case 'confirm_reservetion':

if($_SESSION['rids']!="")
{
if($_SESSION['customers_id']!=""){			
$d=date('Y-m-d');
$query="Insert into customer_regervetion values('', '".$_SESSION["customers_id"]."', '".$_SESSION["rids"]."', '".$_SESSION["arival_date"]."', '".$_SESSION["aritimes"]."', '".$_SESSION["celibration"]."', '".$_SESSION["nop"]."', '".$_SESSION['comments']."','$d')";
$result = $db->execQuery($query);
unset($_SESSION["customers_id"]);
 unset($_SESSION["rids"]);
  unset($_SESSION["arival_date"]); 
  unset($_SESSION["aritimes"]); 
  unset($_SESSION["celibration"]); 
  unset($_SESSION["nop"]);
   unset($_SESSION['comments']);
$arreturnvalue = array('body' => 'reservetion_confirmation.html');

}else
{
$arreturnvalue = array('body' => 'reservetion_place.html');
}
}else
{
$arreturnvalue = array('body' => 'home_body.html');
}
break;

			case 'confirm_orders':
	 	if($_SESSION['customers_id']==""){			
        	$arreturnvalue = array('body' => 'oredr_place.html');
								}else
								{
								$ostatus=0;
								putenv ('TZ=GMT');

$order_date=date('Y-m-d');
$order_datel=date('Y-m-d');
$order_dateds=date('Y-m-d');
$order_datede=date('Y-m-d');
$rids=$_SESSION['rids'];

if($_REQUEST['ortime']!="")
{
$atimes=date('g:i a');
$stime=strtotime($atimes);
$stime=date('g:i a',$stime);
$_SESSION['timeH']=$stime;
}else
{
$hours=$_REQUEST['hrs'];
if($hours=="" and $_REQUEST['amp']=="")
{
$hours=$hours+12;
}else
{
$hours=$hours+$_REQUEST['amp'];
}
$mints=$_REQUEST['mints'];
$atimes="$hours:$mints";
$stime=strtotime("$order_date $atimes");
$stime=date('g:i a',$stime);
$_SESSION['timeH']=$stime;
}

$dayt=$order_date;
$dayt = strtotime($dayt);
$daytr = date('l', $dayt);

if($daytr=="Monday")
{
$dayconls="open_h_mon_start";
$dayconle="open_h_mon_end";
$dayconds="open_h_mon_start2";
$dayconde="open_h_mon_end2";
}
else if($daytr=="Tuesday")
{
$dayconls="open_h_tue_start";
$dayconle="open_h_tue_end";
$dayconds="open_h_tue_start2";
$dayconde="open_h_tue_end2";
}
else if($daytr=="Wednesday")
{
$dayconls="open_h_wed_start";
$dayconle="open_h_wed_end";
$dayconds="open_h_wed_start2";
$dayconde="open_h_wed_end2";
}
else if($daytr=="Thursday")
{
$dayconls="open_h_thu_start";
$dayconle="open_h_thu_end";
$dayconds="open_h_thu_start2";
$dayconde="open_h_thu_end2";
}
else if($daytr=="Friday")
{
$dayconls="open_h_fri_start";
$dayconle="open_h_fri_end";
$dayconds="open_h_fri_start2";
$dayconde="open_h_fri_end2";
}
else if($daytr=="Saturday")
{
$dayconls="open_h_sat_start";
$dayconle="open_h_sat_end";
$dayconds="open_h_sat_start2";
$dayconde="open_h_sat_end2";
}
else
{
$dayconls="open_h_sun_start";
$dayconle="open_h_sun_end";
$dayconds="open_h_sun_start2";
$dayconde="open_h_sun_end2";
}

$sqlsed="Select $dayconls,$dayconle,$dayconds,$dayconde from timeschedule where rid='$rids'";
$resultsed=mysql_query($sqlsed);
$rowsed=mysql_fetch_array($resultsed);

if($rowsed[$dayconls]!="Closed For the day" and $rowsed[$dayconds]!="Closed For the day")
{
$lst= strtotime("$order_datel $rowsed[$dayconls]:00");
$len=strtotime("$order_datel $rowsed[$dayconle]:00");
$atm=$atimes;
$atm= strtotime("$order_datede $atm");

$chkd=strtotime("$rowsed[$dayconds]:00");
$chkd1=strtotime("03:01:00"); 
$chkd2=strtotime("00:01:00");
if($chkd<=$chkd1 and $chkd>=$chkd2)
{
$order_dateds=date( "Y-m-d", strtotime( "$order_dateds +1 day" ) );

}
$dst= strtotime("$order_dateds $rowsed[$dayconds]:00");

$chkde=strtotime("$rowsed[$dayconde]:00");
$chkde1=strtotime("03:01:00");
$chkde2=strtotime("00:01:00");
if($chkde<=$chkde1 and $chkde>=$chkde2)
{
$order_datede=date( "Y-m-d", strtotime( "$order_datede +1 day" ) );
}
$den=strtotime("$order_datede $rowsed[$dayconde]:00");

$ckdate=strtotime("$atimes");
$ckdate1=strtotime("03:01:00");
$ckdate2=strtotime("00:01:00");
if($ckdate<=$ckdate1 and $chkde>=$ckdate2)
{
$order_dategt=date( "Y-m-d", strtotime( "$order_date +1 day" ) );
}
$atmd= strtotime("$order_dategt $atimes");

if($atm>=$lst and $atm<=$len)
{
$ostatus=1;
}else if($atmd>=$dst and $atmd<=$den)
{

$ostatus=1;
}


}else if($rowsed[$dayconls]=="Closed For the day" and $rowsed[$dayconds]!="Closed For the day")
{

$atm=$atimes;
$atm= strtotime("$order_datede $atm");

$chkd=strtotime("$rowsed[$dayconds]:00");
$chkd1=strtotime("03:01:00"); 
$chkd2=strtotime("00:01:00");
if($chkd<=$chkd1 and $chkd>=$chkd2)
{
$order_dateds=date( "Y-m-d", strtotime( "$order_dateds +1 day" ) );

}
$dst= strtotime("$order_dateds $rowsed[$dayconds]:00");

$chkde=strtotime("$rowsed[$dayconde]:00");
$chkde1=strtotime("03:01:00");
$chkde2=strtotime("00:01:00");
if($chkde<=$chkde1 and $chkde>=$chkde2)
{
$order_datede=date( "Y-m-d", strtotime( "$order_datede +1 day" ) );
}
$den=strtotime("$order_datede $rowsed[$dayconde]:00");

$ckdate=strtotime("$atimes");
$ckdate1=strtotime("03:01:00");
$ckdate2=strtotime("00:01:00");
if($ckdate<=$ckdate1 and $chkde>=$ckdate2)
{
$order_dategt=date( "Y-m-d", strtotime( "$order_date +1 day" ) );
}
$atmd= strtotime("$order_dategt $atimes");

if($atmd>=$dst and $atmd<=$den)
{

$ostatus=1;
}

}else if($rowsed[$dayconls]!="Closed For the day" and $rowsed[$dayconds]=="Closed For the day")
{
$lst= strtotime("$order_datel $rowsed[$dayconls]:00");
$len=strtotime("$order_datel $rowsed[$dayconle]:00");
$atm=$atimes;

$atm= strtotime($atm);

if($atm>=$lst and $atm<=$len)
{
$ostatus=1;
}
}
if($ostatus==1)
{
$_SESSION['customer_comments']=mysql_escape_string($_REQUEST['customer_comments']);
								$_SESSION['pay_mathod']=$_REQUEST['pay_mathod'];
								if($_REQUEST['pay_mathod']=="Royalty Points"){
								
								if($_SESSION['order_type']=="1")
								 {
								 $arreturnvalue = array('body' => 'confirm_roilty_payment.html');
								 }else
								 {
								 $arreturnvalue = array('body' => 'confirm_roilty_payment_collection.html');
								 }
								 }else if($_REQUEST['pay_mathod']=="Online")
								 {
								 if($_SESSION['order_type']=="1")
								 {
								  $arreturnvalue = array('body' => 'confirm_cash_payment.html');
								  }else
								  {
								  $arreturnvalue = array('body' => 'confirm_cash_payment_collection.html');
								  }
								 }else 
								 {
								 if($_SESSION['order_type']=="1")
								 {
								  $arreturnvalue = array('body' => 'confirm_cash_payment.html');
								  }else
								  {
								  $arreturnvalue = array('body' => 'confirm_cash_payment_collection.html');
								  }
								 }
}else
{
$arreturnvalue = array('body' => 'checkout_shipping.html',
	                                    'message' => 'The date or time you have chosen is invalid or the restaurant is closed at that time. Please specify another time or take a look a the restaurant open times.');
}
								}
          break;			
		 
		 
		  case 'insert_order':
	 	if($_SESSION['customers_id']==""){			
        	$arreturnvalue = array('body' => 'oredr_place.html');
								}else
								{
								if($_SESSION['cart']!="")
								{
								$ridsc=$_SESSION["rids"];
								$sqlfcal="Select * from resturant_service where rid='$ridsc'";
								$resultfcal=mysql_query($sqlfcal);
								$rowfcal=mysql_fetch_array($resultfcal);
								$rcommis=$rowfcal['commission'];
								if($_SESSION['order_type']=="1")
								{
								$rhfees=$rowfcal['hfee'];
								}
								else
								{
								$rhfees="";
								}
								$tprc=$_SESSION['ttpric'];
								$fcommission=$tprc*($rcommis/100);
								
								$d=date('Y-m-d H:m:s');
								 $query="Insert into customer_order values('', '".$_SESSION["customers_id"]."', '".$_SESSION["rids"]."','".$_SESSION['order_type']."', '".$_SESSION["customers_address1"]."', '".$_SESSION["customers_address2"]."', '".$_SESSION["customers_town"]."', '".$_SESSION["customers_state"]."', '".$_SESSION["customers_country"]."', '".$_SESSION["customers_postcode"]."', '".$_SESSION['customer_comments']."', '".$_SESSION["pay_mathod"]."', '','','$fcommission','','$rhfees','".$_SESSION["vat"]."','$d','".$_SESSION['timeH']."')";	
								 $result = $db->execQuery($query);
		  						 $postid = $db->lastInsert($result);
								 $mlord=$postid;
								 for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) {
									$pids=$_SESSION['cart'][$i];
									$qtys=$_SESSION['qty'][$i];
									$sqlpro="Select * from product where product_id='$pids'";
									$resultpro=mysql_query($sqlpro);
									$rowp=mysql_fetch_array($resultpro);
									$uprice=$rowp["price"];
									$queryod="Insert into order_detail values('', '$postid', '$pids', '$qtys','$uprice')";
									$resultod = $db->execQuery($queryod);
									}
									$rsids=$_SESSION["rids"];

$email="order@indianfoodsonline.co.uk";
		$name = $rowsb['name'];
		$headers = "From: $email\n"; // From address
		$headers .= "Reply-To: $email\n"; // Reply-to address
		$headers .= "Organization: www.indianfoodsonline.co.uk\n"; // Organisation
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Type
		
			
		$body_txt = '<style type="text/css">
<!--
.bodytext {font-family: Arial, Helvetica, sans-serif; font-size:12px; color:#000000;}
-->
</style><table width="100%" border="0" cellspacing="1" cellpadding="1"  class="bodytext">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td width="81%" valign="top" bgcolor="#F0F0F0"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
      <tr>
        <td align="center" class="bodytext"><strong>Online Order from IndianFoodsonline.co.uk</strong></td>
      </tr>';
					
				       $query = "SELECT * FROM customer_order where orid='$mlord'";
											
						$result = $db->execQuery($query);
						$arinfo = $db->resultArray($result);
							$cuid=$arinfo[0]["customers_id"];	
				$sqlc="Select * from customers where customers_id ='$cuid'";
				$resultc=mysql_query($sqlc);
				$rowc=mysql_fetch_array($resultc);
				$send_to = $rowc["customers_email_address"];
		$body_txt .= '<tr>
        <td align="center">
          <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#F0F0F0" class="bodytext">
            <tr>
              <td colspan="4" bgcolor="#F0F0F0" ><strong>Order No: ';
		 $body_txt .=  $arinfo[0]["orid"];
		  $body_txt .= '</strong></td>
            </tr>
            <tr>
              <td colspan="4" valign="top" bgcolor="#FFFFFF" ><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#EEEEEE">
                <tr>
                  <td align="left"  valign="top" bgcolor="#FFFFFF" class="bodytext"> Order Date:</td>
                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
		$body_txt .=  $arinfo[0]["ordate"];
		  $body_txt .= '</td>
                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
				  $ridso=$arinfo[0]["rid"];
				  $sqlres="Select * from resturant where rid='$ridso'";
				  $resultord=mysql_query($sqlres);
				  $roword=mysql_fetch_array($resultord);
				  
				 $body_txt .= 'Restaurant Name </td>
                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
				  $body_txt .=$roword["rname"];
				  $body_txt .='</td>
                </tr>
               
                <tr>
                  <td align="left" valign="top" bgcolor="#FFFFFF" class="bodytext">Order Type: </td>
                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
			if ($arinfo[0]["order_type"]=="0"){
						$dst="Order Delivery";
						}else{ $dst="Order Collection"; }
						
			$body_txt .=$dst;
			$body_txt .='</td>
                  <td width="23%" valign="top" bgcolor="#FFFFFF" class="bodytext">&nbsp;</td>
                  <td width="29%" valign="top" bgcolor="#FFFFFF" class="bodytext">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#FFFFFF" ><strong>Customer Address: </strong></td>
              <td colspan="2" bgcolor="#FFFFFF"><strong>Delivery Address: </strong></td>
            </tr>
            <tr>
              <td width="19%" align="left" valign="top" bgcolor="#FFFFFF" >Name:</td>
              <td width="28%" valign="top" bgcolor="#FFFFFF" class="bodytext">';
			$body_txt .= $rowc["customers_firstname"];
			$body_txt .= $rowc["customers_lastname"];
			$body_txt .='</td>
              <td width="22%" align="left" valign="top" bgcolor="#FFFFFF">Address 1: </td>
              <td width="30%" valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $arinfo[0]["customers_address1"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Address 1: </td>
              <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
			  $body_txt .= $rowc["customers_address1"];
			$body_txt .='</td>
              <td align="left" valign="top" bgcolor="#FFFFFF">Address 2: </td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $arinfo[0]["customers_address2"];;
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Address 2: </td>
              <td valign="top" bgcolor="#FFFFFF" class="bodytext"><strong>';
			  $body_txt .=$rowc["customers_address2"];
			  $body_txt .='</strong></td>
              <td align="left" valign="top" bgcolor="#FFFFFF"> Town</td>
              <td valign="top" bgcolor="#FFFFFF">';
			   $body_txt .= $arinfo[0]["customers_town"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF"> Town:</td>
              <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
			   $body_txt .= $rowc["customers_town"];
			$body_txt .='</td>
              <td align="left"  valign="top" bgcolor="#FFFFFF"> Country:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			    $body_txt .= $arinfo[0]["customers_country"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left"  valign="top" bgcolor="#FFFFFF"> Country:</td>
              <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
			  $body_txt .= $rowc["customers_country"];
			$body_txt .='</td>
              <td align="left"  valign="top" bgcolor="#FFFFFF"> State:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $arinfo[0]["customers_state"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left"  valign="top" bgcolor="#FFFFFF"> State:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			    $body_txt .= $rowc["customers_state"];
			$body_txt .='</td>
              <td align="left" valign="top" bgcolor="#FFFFFF">Post Code</td>
              <td valign="top" bgcolor="#FFFFFF">';
			   $body_txt .= $arinfo[0]["customers_postcode"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Post Code:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $rowc["customers_postcode"];
			$body_txt .='</td>
              <td align="left"  valign="top" bgcolor="#FFFFFF">Customer Message: </td>
              <td valign="top" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Fax:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $rowc["customers_fax"];
			$body_txt .='</td>
              <td colspan="2" rowspan="2" align="left"  valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= stripslashes($arinfo[0]["customer_comments"]);
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Email</td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $rowc["customers_email_address"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td colspan="4" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" height="1" bgcolor="#CCCCCC"></td>
      </tr>
      <tr>
        <td align="center"><table width="90%" border="0" cellpadding="1" cellspacing="2" bgcolor="#999999" class="bodytext">
          <tr>
            <td width="54%" align="left" bgcolor="#F0F0F0"><strong>Product Name </strong></td>
            <td width="16%" align="left" bgcolor="#F0F0F0"><strong>Price </strong></td>
            <td width="15%" align="center" bgcolor="#F0F0F0"><strong>Quantity</strong></td>
            <td width="15%" align="left" bgcolor="#F0F0F0"><strong>Sub Total </strong></td>
          </tr>';
		  $cr=0;
		$sqlproduc="select * from order_detail where orid='$mlord'";
		$resultpro=mysql_query($sqlproduc);
		while($rowpro=mysql_fetch_array($resultpro)){
		 
			$body_txt .='<tr>
            <td align="left" bgcolor="#FFFFFF">';
			$pids=$rowpro["product_id"];
						$sqlpn="Select * from product where product_id ='$pids'";
						$resultpn=mysql_query($sqlpn);
						$rowpn=mysql_fetch_array($resultpn);
			$body_txt .=$rowpn["product_title"];
			$body_txt .='</td>
            <td align="left" bgcolor="#FFFFFF">&pound;';
			$body_txt .=$rowpro["uprice"];
			$body_txt .='</td>
            <td align="center" bgcolor="#FFFFFF">';
			$body_txt .=$rowpro["qantity"];
			$body_txt .='</td>
            <td align="left" bgcolor="#FFFFFF">&pound;';
			$stotal=$rowpro["uprice"]*$rowpro["qantity"];
			$body_txt .=$stotal;
			$body_txt .='</td>
          </tr>';
		  }
		  $body_txt .='<tr>
                        <td colspan="3" align="left" bgcolor="#FFFFFF"><strong>Vat</strong></td>
                        <td align="left" bgcolor="#FFFFFF">';
		$body_txt .=$arinfo[0]["vat"];
		 $body_txt .='</td>
                      </tr>
                      <tr>
                        <td colspan="3" align="left" bgcolor="#FFFFFF"><strong>Handling Fees</strong></td>
                        <td align="left" bgcolor="#FFFFFF">';
			 $body_txt .=$arinfo[0]["hnd_fee"];
			 
		  $body_txt .='</td>
                      </tr><tr>
            <td colspan="3" align="left" bgcolor="#FFFFFF"><strong>Total</strong></td>
            <td align="left" bgcolor="#FFFFFF">&pound;';
			$tcalid=$arinfo[0]["orid"];
					$sqltpr="select sum(uprice*qantity) as tprice from order_detail where orid='$tcalid'";
					$resulttpr=mysql_query($sqltpr);
					$rowprt=mysql_fetch_array($resulttpr);
			$ftotal=$rowprt["tprice"]+$arinfo[0]["vat"]+$arinfo[0]["hnd_fee"];
			$body_txt .=$ftotal;
			
			$body_txt .='</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center" ></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
';
$subject="Order at IndianFoodsOnline Uk";
		
	$sent = mail($send_to, $subject, $body_txt, $headers);
								 unset($_SESSION['cart']);
									unset($_SESSION['qty']);
									unset($_SESSION['rids']);
									unset($_SESSION['customer_comments']);
								
								 $arreturnvalue = array('body' => 'confirmation.html');
								 }else
								 {
								 
								  $arreturnvalue = array('body' => 'customeraccounthome.html');
								 }
								}
          break;	
		  
		 case 'insert_order_roylty':
	 	if($_SESSION['customers_id']==""){			
        	$arreturnvalue = array('body' => 'oredr_place.html');
								}else
								{
								if($_SESSION['cart']!="")
								{
								$ridsc=$_SESSION["rids"];
								$sqlfcal="Select * from resturant_service where rid='$ridsc'";
								$resultfcal=mysql_query($sqlfcal);
								$rowfcal=mysql_fetch_array($resultfcal);
								$rcommis=$rowfcal['commission'];
								$rhfees=$rowfcal['hfee'];
								$tprc=$_SESSION['ttpric'];
								$fcommission=$tprc*($rcommis/100);
								
								$d=date('Y-m-d H:m:s');
								 $query="Insert into customer_order values('', '".$_SESSION["customers_id"]."', '".$_SESSION["rids"]."','".$_SESSION['order_type']."', '".$_SESSION["customers_address1"]."', '".$_SESSION["customers_address2"]."', '".$_SESSION["customers_town"]."', '".$_SESSION["customers_state"]."', '".$_SESSION["customers_country"]."', '".$_SESSION["customers_postcode"]."', '".$_SESSION['customer_comments']."', '".$_SESSION["pay_mathod"]."', '','','$fcommission','','$rhfees','".$_SESSION["vat"]."','$d','".$_SESSION['timeH']."')";	
								 $result = $db->execQuery($query);
		  						 $postid = $db->lastInsert($result);
								 $mlord=$postid;
								 for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) {
									$pids=$_SESSION['cart'][$i];
									$qtys=$_SESSION['qty'][$i];
									$sqlpro="Select * from product where product_id='$pids'";
									$resultpro=mysql_query($sqlpro);
									$rowp=mysql_fetch_array($resultpro);
									$uprice=$rowp["price"];
									$queryod="Insert into order_detail values('', '$postid', '$pids', '$qtys','$uprice')";
									$resultod = $db->execQuery($queryod);
									}
									$rsids=$_SESSION["rids"];

$email="order@indianfoodsonline.co.uk";
		$name = $rowsb['name'];
		$headers = "From: $email\n"; // From address
		$headers .= "Reply-To: $email\n"; // Reply-to address
		$headers .= "Organization: www.indianfoodsonline.co.uk\n"; // Organisation
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Type
		
			
		$body_txt = '<style type="text/css">
<!--
.bodytext {font-family: Arial, Helvetica, sans-serif; font-size:12px; color:#000000;}
-->
</style><table width="100%" border="0" cellspacing="1" cellpadding="1"  class="bodytext">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td width="81%" valign="top" bgcolor="#F0F0F0"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
      <tr>
        <td align="center" class="bodytext"><strong>Online Order from IndianFoodsonline.co.uk</strong></td>
      </tr>';
					
				       $query = "SELECT * FROM customer_order where orid='$mlord'";
											
						$result = $db->execQuery($query);
						$arinfo = $db->resultArray($result);
							$cuid=$arinfo[0]["customers_id"];	
				$sqlc="Select * from customers where customers_id ='$cuid'";
				$resultc=mysql_query($sqlc);
				$rowc=mysql_fetch_array($resultc);
				$send_to = $rowc["customers_email_address"];
		$body_txt .= '<tr>
        <td align="center">
          <table width="100%" border="0" cellpadding="4" cellspacing="1" bgcolor="#F0F0F0" class="bodytext">
            <tr>
              <td colspan="4" bgcolor="#F0F0F0" ><strong>Order No: ';
		 $body_txt .=  $arinfo[0]["orid"];
		  $body_txt .= '</strong></td>
            </tr>
            <tr>
              <td colspan="4" valign="top" bgcolor="#FFFFFF" ><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#EEEEEE">
                <tr>
                  <td align="left"  valign="top" bgcolor="#FFFFFF" class="bodytext"> Order Date:</td>
                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
		$body_txt .=  $arinfo[0]["ordate"];
		  $body_txt .= '</td>
                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
				  $ridso=$arinfo[0]["rid"];
				  $sqlres="Select * from resturant where rid='$ridso'";
				  $resultord=mysql_query($sqlres);
				  $roword=mysql_fetch_array($resultord);
				  
				 $body_txt .= 'Restaurant Name </td>
                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
				  $body_txt .=$roword["rname"];
				  $body_txt .='</td>
                </tr>
               
                <tr>
                  <td align="left" valign="top" bgcolor="#FFFFFF" class="bodytext">Order Type: </td>
                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
			if ($arinfo[0]["order_type"]=="1"){
						$dst="Order Delivery";
						}else{ $dst="Order Collection"; }
						
			$body_txt .=$dst;
			$body_txt .='</td>
                  <td width="23%" valign="top" bgcolor="#FFFFFF" class="bodytext">&nbsp;</td>
                  <td width="29%" valign="top" bgcolor="#FFFFFF" class="bodytext">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="2" bgcolor="#FFFFFF" ><strong>Customer Address: </strong></td>
              <td colspan="2" bgcolor="#FFFFFF"><strong>Delivery Address: </strong></td>
            </tr>
            <tr>
              <td width="19%" align="left" valign="top" bgcolor="#FFFFFF" >Name:</td>
              <td width="28%" valign="top" bgcolor="#FFFFFF" class="bodytext">';
			$body_txt .= $rowc["customers_firstname"];
			$body_txt .= $rowc["customers_lastname"];
			$body_txt .='</td>
              <td width="22%" align="left" valign="top" bgcolor="#FFFFFF">Address 1: </td>
              <td width="30%" valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $arinfo[0]["customers_address1"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Address 1: </td>
              <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
			  $body_txt .= $rowc["customers_address1"];
			$body_txt .='</td>
              <td align="left" valign="top" bgcolor="#FFFFFF">Address 2: </td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $arinfo[0]["customers_address2"];;
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Address 2: </td>
              <td valign="top" bgcolor="#FFFFFF" class="bodytext"><strong>';
			  $body_txt .=$rowc["customers_address2"];
			  $body_txt .='</strong></td>
              <td align="left" valign="top" bgcolor="#FFFFFF"> Town</td>
              <td valign="top" bgcolor="#FFFFFF">';
			   $body_txt .= $arinfo[0]["customers_town"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF"> Town:</td>
              <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
			   $body_txt .= $rowc["customers_town"];
			$body_txt .='</td>
              <td align="left"  valign="top" bgcolor="#FFFFFF"> Country:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			    $body_txt .= $arinfo[0]["customers_country"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left"  valign="top" bgcolor="#FFFFFF"> Country:</td>
              <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
			  $body_txt .= $rowc["customers_country"];
			$body_txt .='</td>
              <td align="left"  valign="top" bgcolor="#FFFFFF"> State:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $arinfo[0]["customers_state"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left"  valign="top" bgcolor="#FFFFFF"> State:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			    $body_txt .= $rowc["customers_state"];
			$body_txt .='</td>
              <td align="left" valign="top" bgcolor="#FFFFFF">Post Code</td>
              <td valign="top" bgcolor="#FFFFFF">';
			   $body_txt .= $arinfo[0]["customers_postcode"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Post Code:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $rowc["customers_postcode"];
			$body_txt .='</td>
              <td align="left"  valign="top" bgcolor="#FFFFFF">Customer Message: </td>
              <td valign="top" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Fax:</td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $rowc["customers_fax"];
			$body_txt .='</td>
              <td colspan="2" rowspan="2" align="left"  valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= stripslashes($arinfo[0]["customer_comments"]);
			$body_txt .='</td>
            </tr>
            <tr>
              <td align="left" valign="top" bgcolor="#FFFFFF">Email</td>
              <td valign="top" bgcolor="#FFFFFF">';
			  $body_txt .= $rowc["customers_email_address"];
			$body_txt .='</td>
            </tr>
            <tr>
              <td colspan="4" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" height="1" bgcolor="#CCCCCC"></td>
      </tr>
      <tr>
        <td align="center"><table width="90%" border="0" cellpadding="1" cellspacing="2" bgcolor="#999999" class="bodytext">
          <tr>
            <td width="54%" align="left" bgcolor="#F0F0F0"><strong>Product Name </strong></td>
            <td width="16%" align="left" bgcolor="#F0F0F0"><strong>Price </strong></td>
            <td width="15%" align="center" bgcolor="#F0F0F0"><strong>Quantity</strong></td>
            <td width="15%" align="left" bgcolor="#F0F0F0"><strong>Sub Total </strong></td>
          </tr>';
		  $cr=0;
		$sqlproduc="select * from order_detail where orid='$mlord'";
		$resultpro=mysql_query($sqlproduc);
		while($rowpro=mysql_fetch_array($resultpro)){
		 
			$body_txt .='<tr>
            <td align="left" bgcolor="#FFFFFF">';
			$pids=$rowpro["product_id"];
						$sqlpn="Select * from product where product_id ='$pids'";
						$resultpn=mysql_query($sqlpn);
						$rowpn=mysql_fetch_array($resultpn);
			$body_txt .=$rowpn["product_title"];
			$body_txt .='</td>
            <td align="left" bgcolor="#FFFFFF">&pound;';
			$body_txt .=$rowpro["uprice"];
			$body_txt .='</td>
            <td align="center" bgcolor="#FFFFFF">';
			$body_txt .=$rowpro["qantity"];
			$body_txt .='</td>
            <td align="left" bgcolor="#FFFFFF">&pound;';
			$stotal=$rowpro["uprice"]*$rowpro["qantity"];
			$body_txt .=$stotal;
			$body_txt .='</td>
          </tr>';
		  }
		  $body_txt .='<tr>
                        <td colspan="3" align="left" bgcolor="#FFFFFF"><strong>Vat</strong></td>
                        <td align="left" bgcolor="#FFFFFF">';
		$body_txt .=$arinfo[0]["vat"];
		 $body_txt .='</td>
                      </tr>
                      <tr>
                        <td colspan="3" align="left" bgcolor="#FFFFFF"><strong>Handling Fees</strong></td>
                        <td align="left" bgcolor="#FFFFFF">';
			 $body_txt .=$arinfo[0]["hnd_fee"];
			 
		  $body_txt .='</td>
                      </tr><tr>
            <td colspan="3" align="left" bgcolor="#FFFFFF"><strong>Total</strong></td>
            <td align="left" bgcolor="#FFFFFF">&pound;';
			$tcalid=$arinfo[0]["orid"];
					$sqltpr="select sum(uprice*qantity) as tprice from order_detail where orid='$tcalid'";
					$resulttpr=mysql_query($sqltpr);
					$rowprt=mysql_fetch_array($resulttpr);
			$ftotal=$rowprt["tprice"]+$arinfo[0]["vat"]+$arinfo[0]["hnd_fee"];
			$body_txt .=$ftotal;
			
			$body_txt .='</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="center" ></td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
';
$subject="Order at IndianFoodsOnline Uk";
		
	$sent = mail($send_to, $subject, $body_txt, $headers);
	$oridsp=$mlord;
							$query="Update customer_order set pstatus='1' where orid='$oridsp'";
							 $result = $db->execQuery($query);
							
							 $queryresf="Select * from customer_order where orid='$oridsp'";
							 $resultresf=mysql_query($queryresf);
							 $rowresf=mysql_fetch_array($resultresf);
							 			 
							 $rsids=$rowresf["rid"];
							
							 
							 $mlord=$oridsp;
$sqlprc="select * from resturant_service where rid='$rsids'";
$resultprc=mysql_query($sqlprc);
$rowprc=mysql_fetch_array($resultprc);

$sqlprcd="select * from printdomain";
$resultprcd=mysql_query($sqlprcd);
$rowprcd=mysql_fetch_array($resultprcd);


$send_to = $rowprc['pr_code']."@".$rowprcd['pdname'];
if($send_to=="")
{
$send_to="return@indianfoodsonline.co.uk";
}

$email="order@indianfoodsonline.co.uk";
		$name = $rowsb['name'];
		$headers = "From: $email\n"; // From address
		$headers .= "Reply-To: $email\n"; // Reply-to address
		$headers .= "Organization: www.indianfoodsonline.co.uk\n"; // Organisation
		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Type
		  $query = "SELECT * FROM customer_order where orid='$mlord'";
						$result = $db->execQuery($query);
						$arinfo = $db->resultArray($result);
							$cuid=$arinfo[0]["customers_id"];	
				$sqlc="Select * from customers where customers_id ='$cuid'";
				$resultc=mysql_query($sqlc);
				$rowc=mysql_fetch_array($resultc);
				
                
$dtm=$arinfo[0]["ordate"];
$dtm= strtotime($dtm);
$dtm=date('d-m-y G:i',$dtm);
$body_txt1 .=$dtm;
$body_txt1 .="\n";
$body_txt1 .=$rowc["customers_firstname"];
$body_txt1 .=' ';
$body_txt1 .=$rowc["customers_lastname"];
$body_txt1 .="\n";
$body_txt1 .='P:';
$body_txt1 .=$rowc["customers_telephone"];
 $body_txt1 .="\n";
  if($arinfo[0]["customers_address1"]!=""){
  $body_txt1 .=$arinfo[0]["customers_address1"];
  $body_txt1 .="\n";
  }
  if($arinfo[0]["customers_address2"]!=""){
  $body_txt1 .=$arinfo[0]["customers_address2"];
  $body_txt1 .="\n";
  }
    if($arinfo[0]["customers_town"]!=""){
 
  $body_txt1 .=$arinfo[0]["customers_town"];
 $body_txt1 .="\n";
  }
      if($arinfo[0]["customers_state"]!=""){
 
  $body_txt1 .=$arinfo[0]["customers_state"];
 $body_txt1 .="\n";
  }
  if($arinfo[0]["customers_postcode"]!=""){

  $body_txt1 .=$arinfo[0]["customers_postcode"];
 $body_txt1 .="\n";
  }
  $body_txt1 .="\n";
   $body_txt1 .='Items';
   $body_txt1 .="\n";
  $sqlproduc="select * from order_detail where orid='$mlord'";
		$resultpro=mysql_query($sqlproduc);
		while($rowpro=mysql_fetch_array($resultpro)){
		$pids=$rowpro["product_id"];
						$sqlpn="Select * from product where product_id ='$pids'";
						$resultpn=mysql_query($sqlpn);
						$rowpn=mysql_fetch_array($resultpn);
  $body_txt1 .=$rowpro["qantity"];  
  $body_txt1 .='x';
  $body_txt1 .=$rowpn["food_code"];
  $body_txt1 .=' -';
  $stotal=$rowpro["uprice"]*$rowpro["qantity"];
  	$body_txt1 .=number_format($stotal, 2, '.', '');
			           $body_txt1 .="\n";
            }
  $body_txt1 .="\n";
  $body_txt1 .='GT:';
  $tcalid=$arinfo[0]["orid"];
					$sqltpr="select sum(uprice*qantity) as tprice from order_detail where orid='$tcalid'";
					$resulttpr=mysql_query($sqltpr);
					$rowprt=mysql_fetch_array($resulttpr);
			$ftotal=$rowprt["tprice"]+$arinfo[0]["vat"]+$arinfo[0]["hnd_fee"];
			$body_txt1 .=number_format($ftotal, 2, '.', '');
			
           $body_txt1 .="\n";
		   $body_txt1 .="\n";

$body_txt1 .='OT:';
$ott=$arinfo[0]["order_type"];
if($body_txt1=="1")
{
$body_txt1 .='D';
}
else
{
$body_txt1 .='C';
}
$body_txt1 .='DT:';

$dttm=$arinfo[0]["delevery_time"];
$dttm= strtotime($dttm);
$dttm=date('G:i',$dttm);
$body_txt1 .=$dttm;
$sqlgt="Select * from res_royalty";
$resultrol=mysql_query($sqlgt);
$rowrol=mysql_fetch_array($resultrol);
$ebpt=$rowrol["pointt"]*$ftotal;

$sqlinpoint="insert into customer_roylpoint values('','$cuid','','$ebpt')";
$resultpoint = $db->execQuery($sqlinpoint);
		$subject="";
		$sent = mail($send_to, $subject, $body_txt1, $headers);
		$send_to="order@indianfoodsonline.co.uk";

	$sent = mail($send_to, $subject, $body_txt1, $headers);
								 unset($_SESSION['cart']);
									unset($_SESSION['qty']);
									unset($_SESSION['rids']);
									unset($_SESSION['customer_comments']);
								
								 $arreturnvalue = array('body' => 'thankyou_paypal.html');
								 }else
								 {
								 
								  $arreturnvalue = array('body' => 'customeraccounthome.html');
								 }
								}
          break;	
		
		  
		case 'customeraccounthome':
	 
								 $arreturnvalue = array('body' => 'customeraccounthome.html');
				
          break;	
		  	
	//----------		
	case 'insert_deliver_policy':
	 	if($_SESSION['userid']!=""){
		$rids=$_SESSION['resid'];
       	
        if ($_REQUEST["dpid"]=="") {
		 $query = "INSERT INTO delevary_policy VALUES('','$rids','".mysql_escape_string($_REQUEST["radious"])."','".mysql_escape_string($_REQUEST["take_time"])."','".mysql_escape_string($_REQUEST["del_time"])."','".mysql_escape_string($_REQUEST["min_order"])."')";
			
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
			$query = "Update delevary_policy set radious='".mysql_escape_string($_REQUEST["radious"])."',take_time='".mysql_escape_string($_REQUEST["take_time"])."',del_time='".mysql_escape_string($_REQUEST["del_time"])."',min_order='".mysql_escape_string($_REQUEST["min_order"])."' where dpid='".$_REQUEST["dpid"]."'";
			
            $result = $db->execQuery($query);
			  if ($result) {
                        $arreturnvalue = array('body' => 'delivery_policy.html',
	                                        'message' => 'Policy Inserted Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'delivery_policy.html',
	                                    'message' => 'Policy Insertion Failed!');
                }
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	


	case 'resturantpolicy_policy':
	 	if($_SESSION['userid']!=""){
        $arreturnvalue = array('body' => 'resturantpolicy_policy.html');
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
	case 'insert_resturant_policy':
	 	if($_SESSION['userid']!=""){
		$rids=$_SESSION['resid'];
       	$query = "SELECT * FROM resturant_policy  where rid  = '$rids' and policy_id='".$_REQUEST[policy_id]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
		 $query = "INSERT INTO resturant_policy VALUES('','$rids','".mysql_escape_string($_REQUEST["policy_id"])."')";
			
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
		}
	   
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;	
	case 'del_policy_acc':
	
		if($_SESSION['userid']!=""){
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
		}else{
			
			$arreturnvalue = array('body' => 'home_body.html');
			}
        break;
	
	
	case 'insert_new_resturant':
		if($_SESSION['userid']!=""){
		//include_once('lib/class.dircreate.php');
		$uids=$_SESSION['userid'];
		$postids=rand();
	
        $query = "SELECT * FROM resturant  where rname  = '".$_REQUEST["rname"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        if (!$success) {
		$userfile_type='';
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
     			$prefixs = 'small_'.$postids.'_';
				
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
				if (!$userfile_name=="")
     {
		        $prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
				$prod_img_thumbs = $thumbdir2.$prefixs.$userfile_name;
				
				}
			
            $query = "INSERT INTO resturant VALUES('','$uids','".mysql_escape_string($_REQUEST["area_id"])."','".mysql_escape_string($_REQUEST["chinise_id"])."','".mysql_escape_string($_REQUEST["rname"])."','".mysql_escape_string($_REQUEST["street"])."','".mysql_escape_string($_REQUEST["city"])."','".mysql_escape_string($_REQUEST["zipcode"])."','".mysql_escape_string($_REQUEST["country_id"])."','".mysql_escape_string($_REQUEST["contact"])."','".mysql_escape_string($_REQUEST["web"])."','$prod_img_thumbs','".nl2br($_REQUEST["rdesc"])."','".nl2br($_REQUEST["keytitle"])."','','')";
			
            $result = $db->execQuery($query);
			$postid = $db->lastInsert($result);
			$_SESSION['resid']=$postid;
                if ($result) {
                        $arreturnvalue = array('body' => 'my_resturant.html',
	                                        'message' => 'Restaurant set up Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'new_resturant.html',
	                                    'message' => 'Restaurant set up Failed!');
                }
        } else {
        	$arreturnvalue = array('body' => 'new_resturant.html',
	                            'message' => 'Restaurant Already Exists!.');
        }
	
		}else{
			
			$arreturnvalue = array('body' => 'home_body.html',
	                            'message' => '');
			}
	
	        break;
			
	case 'insert_item':
		if($_SESSION['userid']!=""){
		//include_once('lib/class.dircreate.php');
		$uids=$_SESSION['userid'];
		$postids=rand();
	
        $query = "SELECT * FROM product  where categories_id  = '".$_REQUEST["categories_id"]."' and product_title='".$_REQUEST["product_title"]."'";
        $success = $db->checkExists($query);
		$d=date('Y-m-d H:m:s');
        include_once('lib/class.imgResize.php');
				
        		
				
				$userfile_name = strtolower($_FILES['file']['name']);
				$userfile_tmp = $_FILES['file']['tmp_name'];
				$userfile_size = $_FILES['file']['size'];
				$userfile_type = $_FILES['file']['type'];
				
				if($userfile_name!=""){
				Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
				$filedir = 'product_image/';
	 			$thumbdir = 'product_image/'; 
				$filedir1 = 'product_image/';
	 			$thumbdir1 = 'product_image/'; 
     			$prefix = 'small_'.$postid.'_';
				//$prod_img = $filedir.$postid.'_'.$userfile_name;
		        $prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
				$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
				$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
				
			
            $query = "INSERT INTO product VALUES('','".mysql_escape_string($_REQUEST["categories_id"])."','".mysql_escape_string($_REQUEST["product_title"])."','".strtolower($prod_img_thumb)."','".strtolower($prod_img_thumb1)."','".mysql_escape_string($_REQUEST["product_desc"])."','".mysql_escape_string($_REQUEST["price"])."')";
			
            $result = $db->execQuery($query);
			
                if ($result) {
                        $arreturnvalue = array('body' => 'additem.html',
	                                        'message' => 'Itemk added Successfully!');
                } else {
                    $arreturnvalue = array('body' => 'additem.html',
	                                    'message' => 'Item addition Failed!');
                }
        } else {
        	$arreturnvalue = array('body' => 'additem.html',
	                            'message' => 'Item Already Exists!.');
        }
		
	
		}else{
			
			$arreturnvalue = array('body' => 'home_body.html',
	                            'message' => '');
			}
	
	        break;
	case 'insert_timeschedule':
	 	if($_SESSION['userid']!=""){
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
		$lstarttime=$_REQUEST["sttimeh1"].":".$_REQUEST["sttimem1"]."  ".$_REQUEST["sttimea1"];
		$lendtime=$_REQUEST["entimeh1"].":".$_REQUEST["entimem1"]."  ".$_REQUEST["entimea1"];
		}
		
		if($_REQUEST["dinerc"]!="")
		{
		$dstarttime=$_REQUEST["dinerc"];
		$dendtime="";
		}else
		{
		$dstarttime=$_REQUEST["sttimeh2"].":".$_REQUEST["sttimem2"]."  ".$_REQUEST["sttimea2"];
		$dendtime=$_REQUEST["entimeh2"].":".$_REQUEST["entimem2"]."  ".$_REQUEST["entimea2"];
		}
		
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
		 $query = "INSERT INTO timeschedule VALUES $insvalue";
			
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
	   
		}else
		{
		$arreturnvalue = array('body' => 'home_body.html');
		}
        break;		
	case 'contact':
   		
        $arreturnvalue = array('body' => 'contact.html');
		
        break;
	
	
	case 'email':
	// Email code start
	
	require_once('lib/class.SendMail.php');
	
	// Date
		$date_month = date(m);
		$date_year = date(Y);
		$date_day = date(d);
		$time_hour = date(H);
		$time_min = date(i);
		// Date
		$Date = "$date_day/$date_month/$date_year - $time_hour:$time_min";
		
		//$send_to = "info@rightroommateonline.co.uk";
		$to  = "info@indianfoodsonline.co.uk";
		$cc = "";
		$from=$_REQUEST["email"];
		$subject = $_REQUEST["subject"];
		$name = $_REQUEST["name"];
		$address = $_REQUEST["address"];
		$country = $_REQUEST["country"];
		$city=$_REQUEST["city"];
		$zip = $_REQUEST["zip"];
		
		$contact = $_REQUEST["phone"];
		
		$email = $_REQUEST["email"];
		
		$comments = nl2br($_REQUEST["comments"]);
		
			
		$message1 = 'You have have a feedback from Client(' .$name.')';
		$message .= '<BR>';
		$message .= 'Address		              	: ' .$address; 
		$message .= '<BR>';
		$message .= 'city			              	: ' .$city; 
		$message .= '<BR>';  
		$message .= 'Post Code		              	: ' .$zip; 
		$message .= '<BR>';  
		$message .= 'Country		              	: ' .$country; 
		$message .= '<BR>';  
		$message .= 'Contact No             		: ' .$contact; 
		$message .= '<BR>';
		$message .= 'Contact Email           		: ' .$email;
		$message .= '<BR>';
		$message .= '<BR><BR>';  
		$message3 = 'M E S S A G E<br><br>';
		$message2 = $comments; 

		/*$msg= "<b>$message1</b></p>
		<font face=verdana color=#000000 size=2>$message</font>
		<p><font face=verdana color=#CC0000 size=2><b>$message3</font></b></p>
		<p><font face=verdana color=#000000 size=2>$message2</font></p>
		";	*/
		$msg=$message1.$message.$message3.$message2;
		
		$sendmail= new SendMail($to, $cc, $bcc, $from, $subject, $msg, $headers);
		$success = $sendmail->send();
		
		$arreturnvalue = array('body' => 'contact.html',
									'message' => 'Your request has sent successfully.We will contact you soon');
		break;
		case 'customerlogout':
         session_unset();
        session_destroy();
     	$pageName="Home";
        $arreturnvalue = array('body' => 'home_body.html');
        break;  	 	
		
		 case 'logout':
        session_unset();
        session_destroy();
		$pageName="Home";
        $arreturnvalue = array('body' => 'home_body.html');
        break;  	 	
		                           
    default:
        $arreturnvalue = array('body' => 'home_body.html');
    }
    
} else {
	
	$pageName="Home";
	$arreturnvalue = array('body' => 'home_body.html');
}


$body = $arreturnvalue['body'];
$message = $arreturnvalue['message'];

?>
<title>index</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="style.css" rel="stylesheet" type="text/css">
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
function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>


<body bgcolor=#FFFFFF leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="MM_preloadImages('images/index_over_06.jpg','images/index_over_07.jpg','images/index_over_08.jpg','images/index_over_09.jpg','images/button_find_restaurant_ovr.jpg')">
<table width="1000" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td height="6"><?php require_once('includes/header.html'); ?></td>
  </tr>
  <tr>
    <td background="images/body_26.jpg" align="center">
	 <? if($body=='order_menu.html' or $body=='restaurant_list.html' or $body=='reservetion.html'){
	 require_once("includes/$body");
	 }else
	 {
	 ?> 
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="4">&nbsp;</td>
        </tr>
      <tr>
        <td width="4%">&nbsp;</td>
       <? if($body!='order_menu.html' and $body!='restaurant_list.html' and $body!='reservetion.html' and $body!='checkout_shipping.html' and $body!='confirm_cash_payment.html' and $body!='oredr_place.html' and $body!='reservetion_place.html' and $body!='sign_up.html' and $body!='confirm_roilty_payment.html' and $body!='reservetion_confirm.html' and $body!='checkout_collection.html' and $body!='confirm_cash_payment_collection.html' and $body!='confirm_roilty_payment_collection.html' and $body!='orderdetails.html' and $body!='retaurant_orderlist.html'){?> <td width="22%" valign="top"><?php  require_once('includes/left_panal.html');?></td><?}?>
        <td width="48%" valign="top"><?php  require_once("includes/$body");?></td>
        <? if($_SESSION['userid']=="" and $_REQUEST[action]=='' or $_REQUEST[action]=='home_body' or $_REQUEST[action]=='logout' or $_REQUEST[action]=='pages' or $body=='home_body.html'){
        ?><td width="23%" valign="top"><?php  require_once('includes/reight_panal.html');?></td><?}?>
        <td width="3%" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="4" height="10"></td>
        </tr>
      <tr>
        <td colspan="4"><?php  require_once('includes/footerhead.html');?></td>
        </tr>
     
    </table>
	<?php } ?>
	</td>
  </tr>
  <tr>
    <td valign="top"><?php require_once('includes/footer.html');?></td>
  </tr>
</table>
<!-- ImageReady Slices (index.psd) -->
<!-- End ImageReady Slices -->
</body>
</html>