<?php
require_once('lib/class.Db.php');
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
ob_start();
session_start();

$pageName='';
$tbv=$_REQUEST[tbq];

	if($tbv=="")
	{
		$tbv="hm";
	}
	if (isset($_REQUEST["action"]) && !empty($_REQUEST["action"])) 
	{ 
		switch ($_REQUEST["action"]) 
		{
			case 'login_verify':
								require_once('lib/class.LoginVerify.php');
								$loginverify = new LoginVerify($_REQUEST["username"], $_REQUEST["password"]);
						        $loginstatus = $loginverify->checkUser($db);
						        if ($loginstatus == true) 
						        {
									$uids=$_SESSION['userid'];
									$resquery="Select * from resturant where userid='$uids'";
									$resultres=mysql_query($resquery);
									$rowres=mysql_fetch_array($resultres);
									$_SESSION['resid']=$rowres["rid"];
									$pageName='My Account';
							        $arreturnvalue = array('body' => 'may_account_home.html');
								} 
								else 
								{
						        	$pageName='Home';
									$arreturnvalue = array(
															'body' => 'resturant_owner_login.html',
							                            	'message' => 'Invalid Username or Password!'
							                               );
						        }
						        break;
			case 'pages':
		   		         $arreturnvalue = array('body' => 'pages.html');
				         break;
					        
			case 'forget_password':
	 								$arreturnvalue = array('body' => 'forget_password.html');
									break;
			//@shafiq
			case 'forget_password_submit':
										 $customers_email=$_REQUEST['customers_email'];
										 $query = "select * from customers where customers_email_address='".$customers_email."'";
										 $result = $db->execQuery($query); 
										 $arinfo = $db->resultArray();
										 $customers_fname=$arinfo[0]["customers_firstname"];
										 $customers_lname=$arinfo[0]["customers_lastname"];
										
										 if(sizeof($arinfo)==1)
										 {
											$password=$arinfo[0]["customers_password"];
											$send_to = $customers_email;
																		
																		$subject = "Forget Password mail";
																		$email = "info@indianfoodsonline.co.uk";
																		
																		$name='indianfoodsonline';
																				
																		$headers = "From: $email\n"; // From address
																		$headers .= "Reply-To: $email\n"; // Reply-to address
																		$headers .= "Organization: www.indianfoodsonline.co.uk\n"; // Organisation
																		$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; // Type
																		$message = 'Dear '.$customers_fname.' '. $customers_lname. ',';
																		
																		$message .= '<BR><BR><BR>';
																		$message .= 'Your password is: <b>'.$password.'</b><BR> Please change your password asap.';
																		$message .= '<br><br>To sign in please Click here,<a href="http://indianfoodsonline.co.uk">http://indianfoodsonline.co.uk</a> <br><br>';
																		
																		$message .= '<BR>Thank You';
																		$message .= '<BR><BR>';
																		$message .= 'Indian Foods Online';
																		$message .= '<BR>';
																		$body_txt = "<html><head><title>Auto generated mail</title>
																		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
																		</head><body><font face=verdana color=#000000 size=2>$message</font>
																		</p></body></html>";
																		require_once("mail/htmlMimeMail5.php");
																		
																		$mail = new htmlMimeMail5();
																		$mail->setFrom("$name<$email>");
																		$mail->setSubject($subject);
																		$mail->setPriority('high');
																		$mail->sethtml($body_txt);
																		$mail->send(array($send_to));
											
											 $arreturnvalue = array('body' => 'password_success.html');
											 break;	
											
										 }else
										 {
										  $arreturnvalue = array('body' => 'password_fail.html',
									                                    'message' => 'Email address does\'nt match');
										 break;
								
										}	
		
			//@Sagar
			
			
			case 'resturant_owner_login':
		   								 $arreturnvalue = array('body' => 'resturant_owner_login.html');
										 break;
										 
			case 'restaurant_list':
									$arreturnvalue = array('body' => 'restaurant_list.html');
									$leftFlg = '0';
									break;
									
			case 'restaurants':
		 					    $arreturnvalue = array('body' => 'restaurants.html');
							    $leftFlg = '0';
								break;
			
		 	/*case 'pages':
						  $arreturnvalue = array('body' => 'pages.html');
						  break;*/
							
			case 'menu':
		   		        $arreturnvalue = array('body' => 'menu.html');
				        break;
				
			case 'new_resturant':
							 	if($_SESSION['userid']!="")
							 	{
						        	$arreturnvalue = array('body' => 'new_resturant.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;
			
			case 'may_account_home':
								 	if($_SESSION['userid']!="")
								 	{
							        	$arreturnvalue = array('body' => 'may_account_home.html');
									}
									else
									{
										$arreturnvalue = array('body' => 'home_body.html');
									}
							        break;
			
			case 'my_orderlist':
							 	if($_SESSION['customers_id']!="")
							 	{
						        	$arreturnvalue = array('body' => 'retaurant_orderlist.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;
				
			case 'customer_edit':
							 	if($_SESSION['customers_id']!="")
							 	{
						        	$arreturnvalue = array('body' => 'customer_edit.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;

			case 'address_book':
							 	if($_SESSION['customers_id']!="")
							 	{
						        	$arreturnvalue = array('body' => 'address_book.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;

			case 'update_address':
							$cids=$_REQUEST['cid'];
							if($_REQUEST['new_address']=="store new")
							{
								$query = "INSERT INTO  customer_address  VALUES ('', '$cids', '".mysql_escape_string($_REQUEST["customers_address1"])."', '".mysql_escape_string($_REQUEST["customers_address2"])."', '".mysql_escape_string($_REQUEST["customers_town"])."', '".mysql_escape_string($_REQUEST["customers_state"])."', '".mysql_escape_string($_REQUEST["customers_country"])."', '".mysql_escape_string($_REQUEST["customers_postcode"])."', '".mysql_escape_string($_REQUEST["customers_add_label"])."')";
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
								$query = "Update  customers  set customers_address1='".mysql_escape_string($_REQUEST["customers_address1"])."', customers_address2='".mysql_escape_string($_REQUEST["customers_address2"])."', customers_town='".mysql_escape_string($_REQUEST["customers_town"])."',customers_state='".mysql_escape_string($_REQUEST["customers_state"])."', customers_country='".mysql_escape_string($_REQUEST["customers_country"])."',customers_postcode='".mysql_escape_string($_REQUEST["customers_postcode"])."',  customers_add_label='".mysql_escape_string($_REQUEST["customers_add_label"])."' where customers_id='$cids'";
								}else
								{
								 $query = "Update  customer_address set customers_address1='".mysql_escape_string($_REQUEST["customers_address1"])."', customers_address2='".mysql_escape_string($_REQUEST["customers_address2"])."', customers_town='".mysql_escape_string($_REQUEST["customers_town"])."',customers_state='".mysql_escape_string($_REQUEST["customers_state"])."', customers_country='".mysql_escape_string($_REQUEST["customers_country"])."',customers_postcode='".mysql_escape_string($_REQUEST["customers_postcode"])."',  customers_add_label='".mysql_escape_string($_REQUEST["customers_add_label"])."' where customers_id='$cids' and ca_id='".$_REQUEST['ca_id']."'";
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

			case 'tell_your_friends':
							 	if($_SESSION['customers_id']!="")
							 	{
						        	$arreturnvalue = array('body' => 'tell_your_friends.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;
			case 'send_email':
							$to="";
							$subject = "Invitation mail";
							$msg=$_REQUEST['email_msg'];
							$from = "info@indianfoodsonline.co.uk";
							$headers = "From: $from\nContent-Type: text/html";
							if($_REQUEST['email']!="")
							{
							$to=$_REQUEST['email'];
							mail($to,$subject,$msg,$headers);
							}
							if($_REQUEST['email2']!="")
							{
							$to=$_REQUEST['email2'];
							mail($to,$subject,$msg,$headers);
							}
							if($_REQUEST['email3']!="")
							{
							$to=$_REQUEST['email3'];
							mail($to,$subject,$msg,$headers);
							}
							if($_REQUEST['email4']!="")
							{
							$to=$_REQUEST['email4'];
							mail($to,$subject,$msg,$headers);
							}
						 	$arreturnvalue = array(
													'body' => 'tell_your_friends.html',
													'message' => 'Mail Send Successfully'
													);
			
			break;
				
			case 'orderdetails':
							 	if($_SESSION['customers_id']!="")
							 	{
						        	$arreturnvalue = array('body' => 'orderdetails.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;
						        
			case 'insert_new_resturant':
										if($_SESSION['userid']!="")
										{
											$uids=$_SESSION['userid'];
											$postids=rand();
										
									        $query = "SELECT * FROM resturant  where rname  = '".$_REQUEST["rname"]."'";
									        $success = $db->checkExists($query);
											$d=date('Y-m-d H:m:s');
									        
									        if (!$success) 
										    {
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
														
												
											 if (!$userfile_name=="")
										     {
											 	$prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
												$prod_img_thumbs = $thumbdir2.$prefixs.$userfile_name;
											 }
											
											        $query = "INSERT INTO resturant VALUES('','$uids','".mysql_escape_string($_REQUEST["area_id"])."','".mysql_escape_string($_REQUEST["chinise_id"])."','".mysql_escape_string($_REQUEST["rname"])."','".mysql_escape_string($_REQUEST["street"])."','".mysql_escape_string($_REQUEST["city"])."','".mysql_escape_string($_REQUEST["zipcode"])."','".mysql_escape_string($_REQUEST["country_id"])."','".mysql_escape_string($_REQUEST["contact"])."','".mysql_escape_string($_REQUEST["web"])."','$prod_img_thumbs','".nl2br($_REQUEST["rdesc"])."','".nl2br($_REQUEST["keytitle"])."','','')";
											        $result = $db->execQuery($query);
													$postid = $db->lastInsert($result);
													$_SESSION['resid']=$postid;
										            if ($result) 
										            {
										               	$arreturnvalue = array(
																					'body' => 'my_resturant.html',
											                                        'message' => 'Restaurant set up Successfully!'
											                                   );
										            }
										            else 
										            {
										                 $arreturnvalue = array(
																					'body' => 'new_resturant.html',
												                                    'message' => 'Restaurant set up Failed!'
											                                    );
										            }
										        } 
										        else 
										        {
									        		$arreturnvalue = array(
																			'body' => 'new_resturant.html',
										                            		'message' => 'Restaurant Already Exists!.'
										                            	   );
									        	}
										
											}
											else
											{
												$arreturnvalue = array(
																		'body' => 'home_body.html',
										                            	'message' => ''
										                               );
											}
			
			        						break;
			        						
			case 'my_resturant':
							 	if($_SESSION['userid']!="")
							 	{
						        	$arreturnvalue = array('body' => 'my_resturant.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;	
				
			case 'update_my_resturant':
										if(isset($_SESSION['userid']))
										{
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
											
											if (!$userfile_name=="")
									     	{
											        $prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
													$prod_img_thumbs = $thumbdir2.$prefixs.$userfile_name;
											}
											if($prod_img_thumbs!="")
											{
											    $query = "Update resturant set area_id='".mysql_escape_string($_REQUEST["area_id"])."',chinise_id='".mysql_escape_string($_REQUEST["chinise_id"])."',rname='".mysql_escape_string($_REQUEST["rname"])."',street='".mysql_escape_string($_REQUEST["street"])."',city='".mysql_escape_string($_REQUEST["city"])."',zipcode='".mysql_escape_string($_REQUEST["zipcode"])."',country_id='".mysql_escape_string($_REQUEST["country_id"])."',contact='".mysql_escape_string($_REQUEST["contact"])."',web='".mysql_escape_string($_REQUEST["web"])."',logo='$prod_img_thumbs',rdesc='".nl2br($_REQUEST["rdesc"])."',keytitle='".nl2br($_REQUEST["keytitle"])."' where rid='".mysql_escape_string($_REQUEST["id"])."'";
											}
											else
											{
												 $query = "Update resturant set area_id='".mysql_escape_string($_REQUEST["area_id"])."',chinise_id='".mysql_escape_string($_REQUEST["chinise_id"])."',rname='".mysql_escape_string($_REQUEST["rname"])."',street='".mysql_escape_string($_REQUEST["street"])."',city='".mysql_escape_string($_REQUEST["city"])."',zipcode='".mysql_escape_string($_REQUEST["zipcode"])."',country_id='".mysql_escape_string($_REQUEST["country_id"])."',contact='".mysql_escape_string($_REQUEST["contact"])."',web='".mysql_escape_string($_REQUEST["web"])."',rdesc='".nl2br($_REQUEST["rdesc"])."',keytitle='".nl2br($_REQUEST["keytitle"])."' where rid='".mysql_escape_string($_REQUEST["id"])."'";
											}
												
									            $result = $db->execQuery($query);
									            if ($result) 
									            {
									            	$arreturnvalue = array(
																				'body' => 'my_resturant.html',
										                                        'message' => 'Restaurant Updated Successfully!'
										                                   );
									             } 
									             else 
									             {
									                 $arreturnvalue = array('body' => 'my_resturant.html',
										                                    'message' => 'Restaurant Update Failed!');
									              }
									         
											}
											else
											{
					
												$arreturnvalue = array(
																		'body' => 'home_body.html',
										                            	'message' => ''
										                               );
											}
										
									        break;
			
			case 'change_accout':
							 	if($_SESSION['userid']!="")
							 	{
						        	$arreturnvalue = array('body' => 'change_account.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;	
			
			case 'update_account':
								 if($_SESSION['userid']!="")
								 {
										$ids=$_REQUEST["id"];
										$query1 = "SELECT * FROM userregistration WHERE email='".$_REQUEST["email"]."' and id!='$ids'";
						        		$success1 = $db->checkExists($query1);
								
						        	if (!$success1) 
							        {
									    $query = "Update  userregistration  set first_name='".mysql_escape_string($_REQUEST["first_name"])."', last_name='".mysql_escape_string($_REQUEST["last_name"])."', phone='".mysql_escape_string($_REQUEST["phone"])."', fax='".mysql_escape_string($_REQUEST["fax"])."', email='".mysql_escape_string($_REQUEST["email"])."', address1='".mysql_escape_string($_REQUEST["address1"])."',password='".mysql_escape_string($_REQUEST["password"])."' where id='$ids'";
									    $result = $db->execQuery($query);
									  	$postid = $db->lastInsert($result);
												
										$pageName="Registration";
							            $arreturnvalue = array(
																'body' => 'change_account.html',
								                           		'message' => 'Account Update successfully!!'
								                           	   );
										
							       }
							       else 
							       {
										$arreturnvalue = array(
																'body' => 'change_account.html',
							                            		'message' => 'This mail is resistered by another user please try another!!'
							                            	   );
						          }
								}
							else
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
								if($tecncode==$oencode)
								{
									$query = "INSERT INTO  userregistration  VALUES ('', '".
																							mysql_escape_string($_REQUEST["first_name"])."', '".
																							mysql_escape_string($_REQUEST["last_name"])."', '".
																							mysql_escape_string($_REQUEST["phone"])."', '".
																							mysql_escape_string($_REQUEST["mobile"])."', '".
																							mysql_escape_string($_REQUEST["fax"])."', '".
																							mysql_escape_string($_REQUEST["email"])."', '".
																							mysql_escape_string($_REQUEST["address1"])."','".
																							mysql_escape_string($_REQUEST["password"])."', '')";
									$result = $db->execQuery($query);
								  	$postid = $db->lastInsert($result);
											
								    $queryr = "INSERT INTO  resturant  VALUES ('','$postid','','','".mysql_escape_string($_REQUEST["rname"])."','','','','',
									 											 '','','','','','','','','','','')";
								   $resultr = $db->execQuery($queryr);	
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

												Many Thanks<br />
												The <a href="http://indianfoodsonline.co.uk" target="_blank">indianfoodsonline.co.uk</a> Team - <a href="mailto:info@indianfoodsonline.co.uk">info@indianfoodsonline.co.uk</a>';
														
									$send_to =  $_REQUEST[email];
									$subject="Congratulation and thank you for signup at Indian Food Online";
									
									$sent = mail($send_to, $subject, $body_txt, $headers);
									
										
							      
								}
								else
								{
									$pageName="Registration";
									$first_name=$_REQUEST['first_name'];
									$last_name=$_REQUEST['last_name'];
									$rname=$_REQUEST['rname'];
									$phone=$_REQUEST['phone'];
									$fax=$_REQUEST['fax'];
									$email=$_REQUEST['email'];
									$address1=$_REQUEST['address1'];
									
									$arreturnvalue = array(
															'body' => 'sign_up.html',
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
							}
							else
							{
								$arreturnvalue = array('body' => 'home_body.html');
							}
							break;
			
			case 'restaurant_details_view':
							$_SESSION['rids']=$_REQUEST['rid'];
							if($_SESSION['rids']!="")
							{
								$_SESSION['order_type']=1;
								$arreturnvalue = array('body' => 'restaurant_view_details.html');
							}
							else
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
										}
										else
										{
											$arreturnvalue = array('body' => 'home_body.html');
										}
										break;
						
			case 'reservetion_order':
									$_SESSION['rids']=$_REQUEST['rid'];
									if($_SESSION['rids']!="")
									{
										$arreturnvalue = array('body' => 'reservetion.html');
									}
									else
									{
										$arreturnvalue = array('body' => 'home_body.html');
									}
									break;
									
		
			case 'catalog':
						 	if($_SESSION['userid']!="")
						 	{
					        	$arreturnvalue = array('body' => 'catalog.html');
							}
							else
							{
								$arreturnvalue = array('body' => 'home_body.html');
							}
					        break;	
							
			case 'addmenu':
						 	if($_SESSION['userid']!="")
						 	{
					        	$arreturnvalue = array('body' => 'addmenu.html');
							}
							else
							{
								$arreturnvalue = array('body' => 'home_body.html');
							}
					        break;	
							
			case 'insert_new_menue':
								 	if($_SESSION['userid']!="")
									{
										$rids=$_SESSION['resid'];
								       	$query = "SELECT * FROM categories  where rid  = '$rids' and categories_name='".$_REQUEST[categories_name]."'";
								        $success = $db->checkExists($query);
										$d=date('Y-m-d H:m:s');
								        if (!$success) 
									    {
											$query = "INSERT INTO categories VALUES('','$rids','".mysql_escape_string($_REQUEST["categories_name"])."','".mysql_escape_string($_REQUEST["parent_id"])."','','$d','')";
											$result = $db->execQuery($query);
											
											if ($result) 
											{
									        	$arreturnvalue = array(
																		'body' => 'addmenu.html',
										                                'message' => 'Menu Inserted Successfully!'
										                               );
									         }
									         else 
									         {
									            $arreturnvalue = array(
																		'body' => 'addmenu.html',
										                                'message' => 'Menu Insertion Failed!'
										                               );
									         }
										}
										else
										{
											$arreturnvalue = array(
																	'body' => 'addmenu.html',
									                            	'message' => 'Menue Already inserted'
									                               );
										}
									   
									}
									else
									{
										$arreturnvalue = array('body' => 'home_body.html');
									}
								    break;	
								
			case 'additem':
						 	if($_SESSION['userid']!="")
						 	{
					        	$arreturnvalue = array('body' => 'additem.html');
							}
							else
							{
								$arreturnvalue = array('body' => 'home_body.html');
							}
					        break;	
			
			case 'timeschedule':
							 	if($_SESSION['userid']!="")
							 	{
						        	$arreturnvalue = array('body' => 'timeschedule.html');
								}
								else
								{
									$arreturnvalue = array('body' => 'home_body.html');
								}
						        break;	
								
			case 'delivery_policy':
								 	if($_SESSION['userid']!="")
								 	{
							        	$arreturnvalue = array('body' => 'delivery_policy.html');
									}
									else
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
										
										$amck=$_REQUEST['amp'];
										if($amck=="12")
										{
											$amck="PM";
										}
										else
										{
											$amck="AM";
										}
										
										$ostatus=0;
										putenv ('TZ=GMT');
										
										$order_date=$_SESSION['arival_date'];
										$order_datel=$_SESSION['arival_date'];
										$order_dateds=$_SESSION['arival_date'];
										$order_datede=$_SESSION['arival_date'];
										$rids=$_SESSION['rids'];
										
										$hours=$_REQUEST['hrs'];
										if($hours=="" and $_REQUEST['amp']=="")
										{
											$hours=$hours+12;
										}
										else
										{
											$hours=$hours+$_REQUEST['amp'];
										}
										
										$mints=$_REQUEST['mints'];
										$atimes="$hours:$mints";
										$stime=strtotime("$order_date $atimes");
										$stime=date('g:i a',$stime);
										$_SESSION['timeH']=$stime;
										
										$_SESSION['aritimes']=date('g:i a', strtotime($atimes));
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
										elseif($daytr=="Tuesday")
										{
											$dayconls="open_h_tue_start";
											$dayconle="open_h_tue_end";
											$dayconds="open_h_tue_start2";
											$dayconde="open_h_tue_end2";
										}
										elseif($daytr=="Wednesday")
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
											}
											elseif($atmd>=$dst and $atmd<=$den)
											{
												$ostatus=1;
											}
											
											
										 }
										elseif($rowsed[$dayconls]=="Closed For the day" and $rowsed[$dayconds]!="Closed For the day")
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
											
										 }
										 elseif($rowsed[$dayconls]!="Closed For the day" and $rowsed[$dayconds]=="Closed For the day")
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
											if($_SESSION['customers_id']=="")
											{			
												$_SESSION['otp']="resv";
												$arreturnvalue = array('body' => 'reservetion_place.html');
											}
											else
											{
												$arreturnvalue = array('body' => 'reservetion_confirm.html');
											}
											}
											else
											{
												$arreturnvalue = array(
																		'body' => 'reservetion.html',
												                        'message' => 'The date or time you have chosen is invalid or the restaurant is closed at that time. Please specify another time or take a look a the restaurant open times.');
											}
										}
										else
										{
											$arreturnvalue = array('body' => 'home_body.html');
										}
										break;
											
			case 'order_place':
								if($_SESSION['customers_id']=="")
								{			
									$arreturnvalue = array('body' => 'oredr_place.html');
								}
								else
								{
									if($_SESSION['order_type']=="1")
									{
										$arreturnvalue = array('body' => 'checkout_shipping.html');
									}
									else
									{
										$arreturnvalue = array('body' => 'checkout_collection.html');
									}
								}
								break;	
																 
				
				   
			case 'customer_login':
								  require_once('lib/class.customerLoginVerify.php');
								  $customerLoginVerify = new customerLoginVerify($_REQUEST["username"], $_REQUEST["password"]);
								  $cloginstatus = $customerLoginVerify->checkUser($db);
								  if ($cloginstatus == true) 
								  {
										if($_REQUEST['otp']=="orp")
										{					
											$pageName='My Account';
											if($_SESSION['order_type']=="1")
											{
												$arreturnvalue = array('body' => 'checkout_shipping.html');
											}
											else
											{
												$arreturnvalue = array('body' => 'checkout_collection.html');
											}
										}
										elseif($_REQUEST['otp']=="resv")
										{
											$arreturnvalue = array('body' => 'reservetion_confirm.html');
										}
										else
										{
											$arreturnvalue = array('body' => 'customeraccounthome.html');
										}
								}
								else 
								{
									if($_REQUEST['otp']=="orp")
									{					
										$pageName='My Account';
										$arreturnvalue = array(
																'body' => 'oredr_place.html',
																'message' => 'Invalid Username or Password!'
															  );
									}
									elseif($_REQUEST['otp']=="resv")
									{
										$arreturnvalue = array(
																'body' => 'reservetion_place.html',
											   		            'message' => 'Invalid Username or Password!'
															   );
									}
									else
									{
										$arreturnvalue = array(
																'body' => 'home_body.html',
															    'message' => 'Invalid Username or Password!'
															   );
									}
								        
								}
								break;
				
				
			case 'customer_registration':
										$arreturnvalue = array('body' => 'customer_registration.html');
										break;
																	 	
			case 'insert_customer':
								   $query1 = "SELECT customers_email_address FROM  customers  where customers_email_address= '".$_REQUEST["customers_email_address"]."'";
								   $success1 = $db->checkExists($query1);
									if (!$success1) 
									{
									//	$query = "INSERT INTO  customers  VALUES ('', '', '".mysql_escape_string($_REQUEST["customers_firstname"])."', '".mysql_escape_string($_REQUEST["customers_lastname"])."', '', '".mysql_escape_string($_REQUEST["customers_email_address"])."', '".mysql_escape_string($_REQUEST["customers_address1"])."', '".mysql_escape_string($_REQUEST["customers_address2"])."', '".mysql_escape_string($_REQUEST["customers_town"])."', '".mysql_escape_string($_REQUEST["customers_state"])."', '".mysql_escape_string($_REQUEST["customers_country"])."', '".mysql_escape_string($_REQUEST["customers_postcode"])."', '".mysql_escape_string($_REQUEST["customers_telephone"])."', '".mysql_escape_string($_REQUEST["customers_fax"])."', '".mysql_escape_string($_REQUEST["customers_password"])."')";
	$query = "INSERT INTO  customers  VALUES ('', '', '".mysql_escape_string($_REQUEST["customers_firstname"])."', '".mysql_escape_string($_REQUEST["customers_lastname"])."', '', '".mysql_escape_string($_REQUEST["customers_email_address"])."', '".mysql_escape_string($_REQUEST["customers_address1"])."', '".mysql_escape_string($_REQUEST["customers_address2"])."', '".mysql_escape_string($_REQUEST["customers_town"])."', '".mysql_escape_string($_REQUEST["customers_state"])."', '".mysql_escape_string($_REQUEST["customers_country"])."', '".mysql_escape_string($_REQUEST["customers_postcode"]).mysql_escape_string($_REQUEST["customers_postcode2"])."', '".mysql_escape_string($_REQUEST["customers_telephone"])."', '".mysql_escape_string($_REQUEST["customers_fax"])."', '".mysql_escape_string($_REQUEST["customers_password"])."','','','Standard')";
										$result = $db->execQuery($query);
										$postid = $db->lastInsert($result);
																	
										if($_SESSION[cart]!="")
										{
											$_SESSION['customers_id']=$postid;
											$_SESSION['customers_firstname']=mysql_escape_string($_REQUEST["customers_firstname"]);	
											$_SESSION['customers_lastname']=mysql_escape_string($_REQUEST["customers_lastname"]);	
											$_SESSION['customers_email_address']=mysql_escape_string($_REQUEST["customers_email_address"]);	
											$_SESSION['customers_address1']=mysql_escape_string($_REQUEST["customers_address1"]);	
											$_SESSION['customers_address2']=mysql_escape_string($_REQUEST["customers_address2"]);	
											$_SESSION['customers_town']=mysql_escape_string($_REQUEST["customers_town"]);	
											$_SESSION['customers_state']=mysql_escape_string($_REQUEST["customers_state"]);	
											$_SESSION['customers_country']=mysql_escape_string($_REQUEST["customers_country"]);	
											$_SESSION['customers_postcode']=mysql_escape_string($_REQUEST["customers_postcode"]);	
																		
											$pageName="Registration";
											if($_SESSION['order_type']=="1")
											{
												$arreturnvalue = array('body' => 'checkout_shipping.html');
											}
											else
											{
												$arreturnvalue = array('body' => 'checkout_collection.html');
											}
										}
										elseif($_REQUEST['otp']=="resv")
										{
											unset($_SESSION['otp']);
											$_SESSION['customers_id']=$postid;
											$_SESSION['customers_firstname']=mysql_escape_string($_REQUEST["customers_firstname"]);	
											$_SESSION['customers_lastname']=mysql_escape_string($_REQUEST["customers_lastname"]);	
											$_SESSION['customers_email_address']=mysql_escape_string($_REQUEST["customers_email_address"]);	
											$_SESSION['customers_address1']=mysql_escape_string($_REQUEST["customers_address1"]);	
											$_SESSION['customers_address2']=mysql_escape_string($_REQUEST["customers_address2"]);	
											$_SESSION['customers_town']=mysql_escape_string($_REQUEST["customers_town"]);	
											$_SESSION['customers_state']=mysql_escape_string($_REQUEST["customers_state"]);	
											$_SESSION['customers_country']=mysql_escape_string($_REQUEST["customers_country"]);	
											$_SESSION['customers_postcode']=mysql_escape_string($_REQUEST["customers_postcode"]);	
																	
											$arreturnvalue = array('body' => 'reservetion_confirm.html');
										}
										else
										{
											$pageName="Customer Registration Success";
											$arreturnvalue = array('body' => 'customerregistratiionsuccess.html');
										}
									}
									else 
									{
										if($_SESSION['customers_id']=="")
										{			
											$arreturnvalue = array(
																	'body' => 'oredr_place.html',
																    'message' => 'You have already registered. Please log in to Place Order');
										}
										else
										{
											if($_REQUEST['otp']=="resv")
											{
												unset($_SESSION['otp']);
												$arreturnvalue = array('body' => 'reservetion_confirm.html');
											}
											else
											{	
												if($_SESSION['order_type']=="1")
												{
													$arreturnvalue = array('body' => 'checkout_shipping.html');
												}
												else
												{
													$arreturnvalue = array('body' => 'checkout_collection.html');
												}
											}
										}
									 }
													
								 break;	
															
					
			case 'update_customer':
									$cids=$_REQUEST['cid'];
									$query1 = "SELECT customers_email_address FROM  customers  where customers_id !='$cids' and customers_email_address='".mysql_escape_string($_REQUEST["customers_email_address"])."'";
					        		$chk=$db->checkExists($query1);
									echo $chk;
					        		if (!$chk) 
							        {
										$cids=$_REQUEST['cid'];
										$cdob=$_REQUEST['year']."-".$_REQUEST['month']."-".$_REQUEST['day'];
										if($_REQUEST['newcustomers_password']!="")
										{
							            $query = "Update  customers  set customers_firstname='".mysql_escape_string($_REQUEST["customers_firstname"])."', customers_lastname='".mysql_escape_string($_REQUEST["customers_lastname"])."', customers_dob='".$cdob."',customers_email_address='".mysql_escape_string($_REQUEST["customers_email_address"])."', customers_address1='".mysql_escape_string($_REQUEST["customers_address1"])."', customers_address2='".mysql_escape_string($_REQUEST["customers_address2"])."', customers_town='".mysql_escape_string($_REQUEST["customers_town"])."',customers_state='".mysql_escape_string($_REQUEST["customers_state"])."', customers_country='".mysql_escape_string($_REQUEST["customers_country"])."',customers_postcode='".mysql_escape_string($_REQUEST["customers_postcode"])."', customers_telephone='".mysql_escape_string($_REQUEST["customers_telephone"])."',customers_fax='".mysql_escape_string($_REQUEST["customers_fax"])."', customers_password='".mysql_escape_string($_REQUEST["newcustomers_password"])."' , customers_ccode='".mysql_escape_string($_REQUEST["customers_ccode"])."', customers_howhear='".mysql_escape_string($_REQUEST["customers_howhear"])."' where customers_id='$cids'";
										}else
										{
										 $query = "Update  customers  set customers_firstname='".mysql_escape_string($_REQUEST["customers_firstname"])."', customers_lastname='".mysql_escape_string($_REQUEST["customers_lastname"])."', customers_dob='".$cdob."',customers_email_address='".mysql_escape_string($_REQUEST["customers_email_address"])."', customers_address1='".mysql_escape_string($_REQUEST["customers_address1"])."', customers_address2='".mysql_escape_string($_REQUEST["customers_address2"])."', customers_town='".mysql_escape_string($_REQUEST["customers_town"])."',customers_state='".mysql_escape_string($_REQUEST["customers_state"])."', customers_country='".mysql_escape_string($_REQUEST["customers_country"])."',customers_postcode='".mysql_escape_string($_REQUEST["customers_postcode"])."', customers_telephone='".mysql_escape_string($_REQUEST["customers_telephone"])."',customers_fax='".mysql_escape_string($_REQUEST["customers_fax"])."', customers_ccode='".mysql_escape_string($_REQUEST["customers_ccode"])."', customers_howhear='".mysql_escape_string($_REQUEST["customers_howhear"])."' where customers_id='$cids'";
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
							
			case 'ctestimonial':
			 			 	    $arreturnvalue = array('body' => 'ctestimonial.html');
							    break;	
								
			case 'insert_testimonial':
									 $query = "INSERT customer_testimonial values('', '".mysql_escape_string($_REQUEST["customers_firstname"])."', '".mysql_escape_string($_REQUEST["customers_lastname"])."', '".mysql_escape_string($_REQUEST["customers_email_address"])."', '".mysql_escape_string($_REQUEST["customers_address"])."', '".mysql_escape_string($_REQUEST["customers_company"])."', '".nl2br($_REQUEST["customer_text"])."', '".mysql_escape_string($_REQUEST["astatus"])."')";
									 $result = $db->execQuery($query);
									 if(!$result)
									 {
										 $customers_firstname=$_REQUEST["customers_firstname"];
										 $customers_lastname=$_REQUEST["customers_lastname"];
										 $customers_email_address=$_REQUEST["customers_email_address"];
										 $customers_address=$_REQUEST["customers_address"];
										 $customers_company=$_REQUEST["customers_company"];
										  $customers_company=nl2br($_REQUEST["customer_text"]);
										 $arreturnvalue = array('body' => 'ctestimonial.html',
									                                    'message' => 'Testimonial Posting failed!');
									 }
									 else
									 {
									 	$arreturnvalue = array('body' => 'ctestimonialsuccess.html');
									 }
								 	
									break;	
								
			case 'change_address':
								 if($_SESSION['customers_id']=="")
								 {			
							     	$arreturnvalue = array('body' => 'oredr_place.html');
								 }
								 else
									{
										 $arreturnvalue = array('body' => 'change_address.html');
									}
					          break;			
								
			case 'insert_shipping_address':
										 	if($_SESSION['customers_id']=="")
										 	{			
								        		$arreturnvalue = array('body' => 'oredr_place.html');
											}
											else
											{
												$_SESSION['customers_address1']=$_REQUEST['customers_address1'];
												$_SESSION['customers_address2']=$_REQUEST['customers_address2'];
												$_SESSION['customers_town']=$_REQUEST['customers_town'];
												$_SESSION['customers_state']=$_REQUEST['customers_state'];
												$_SESSION['customers_country']=$_REQUEST['customers_country'];
												$_SESSION['customers_postcode']=$_REQUEST['customers_postcode'];		
											    
											    if($_SESSION['order_type']=="1")
												{
													$arreturnvalue = array('body' => 'checkout_shipping.html');
												}
												else
												{
													$arreturnvalue = array('body' => 'checkout_collection.html');
												}
											}
							         		break;		
			case 'mycart':
						  $arreturnvalue = array('body' => 'mycart.php');
						  break;	
							  
			case 'current_reservetion':
									 	$arreturnvalue = array('body' => 'reservetion_confirm.html');
										break;	
							  
			case 'reorder':
						 	if($_SESSION['customers_id']!="")
							{		
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
							        	if($_SESSION['order_type']=="1")
										{
											$arreturnvalue = array('body' => 'checkout_shipping.html');
										}
										else
										{
											$arreturnvalue = array('body' => 'checkout_collection.html');
										}
							}
							else
								{
									 $arreturnvalue = array('body' => 'customeraccounthome.html');
								}
					        break;	
					
							  
			case 'confirm_reservetion':
									  if($_SESSION['rids']!="")
									  {
											if($_SESSION['customers_id']!="")
											{			
												$d=date('Y-m-d');
												$query="Insert into customer_regervetion values('', '".$_SESSION["customers_id"]."', '".$_SESSION["rids"]."', '".$_SESSION["arival_date"]."', '".$_SESSION["aritimes"]."', '".$_SESSION["celibration"]."', '".$_SESSION["nop"]."', '".$_SESSION['comments']."','$d','','')";
												$result = $db->execQuery($query);
												$postid = $db->lastInsert($result);
												$mlrid=$postid;
												unset($_SESSION["customers_id"]);
											    unset($_SESSION["rids"]);
												unset($_SESSION["arival_date"]); 
												unset($_SESSION["aritimes"]); 
												unset($_SESSION["celibration"]); 
												unset($_SESSION["nop"]);
												unset($_SESSION['comments']);
												$arreturnvalue = array('body' => 'reservetion_confirmation.html');
												
											}
											else
											{
												$arreturnvalue = array('body' => 'reservetion_place.html');
											}
										}
									else
									{
										$arreturnvalue = array('body' => 'home_body.html');
									}
									break;
					
			case 'confirm_orders':
								 	if($_SESSION['customers_id']=="")
								 	{			
							        	$arreturnvalue = array('body' => 'oredr_place.html');
									}
									else
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
											}
											else
											{
												$hours=$_REQUEST['hrs'];
												if($hours=="" and $_REQUEST['amp']=="")
												{
													$hours=$hours+12;
												}
												else
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
											elseif($daytr=="Tuesday")
											{
												$dayconls="open_h_tue_start";
												$dayconle="open_h_tue_end";
												$dayconds="open_h_tue_start2";
												$dayconde="open_h_tue_end2";
											}
											elseif($daytr=="Wednesday")
											{
												$dayconls="open_h_wed_start";
												$dayconle="open_h_wed_end";
												$dayconds="open_h_wed_start2";
												$dayconde="open_h_wed_end2";
											}
											elseif($daytr=="Thursday")
											{
												$dayconls="open_h_thu_start";
												$dayconle="open_h_thu_end";
												$dayconds="open_h_thu_start2";
												$dayconde="open_h_thu_end2";
											}
											elseif($daytr=="Friday")
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
												}
												elseif($atmd>=$dst and $atmd<=$den)
												{
													$ostatus=1;
												}
											}
											elseif($rowsed[$dayconls]=="Closed For the day" and $rowsed[$dayconds]!="Closed For the day")
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
												
											}
											elseif($rowsed[$dayconls]!="Closed For the day" and $rowsed[$dayconds]=="Closed For the day")
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
												if($_REQUEST['pay_mathod']=="Royalty Points")
												{
													if($_SESSION['order_type']=="1")
													{
														$arreturnvalue = array('body' => 'confirm_roilty_payment.html');
													}
													else
													{
														$arreturnvalue = array('body' => 'confirm_roilty_payment_collection.html');
													}
												}
												elseif($_REQUEST['pay_mathod']=="Online")
												{
													if($_SESSION['order_type']=="1")
													{
														$arreturnvalue = array('body' => 'confirm_online_payment.html');
													}
													else
													{
														$arreturnvalue = array('body' => 'confirm_online_payment_collection.html');
													}
												}
												else 
												{
													if($_SESSION['order_type']=="1")
													{
														$arreturnvalue = array('body' => 'confirm_cash_payment.html');
													}
													else
													{
														$arreturnvalue = array('body' => 'confirm_cash_payment_collection.html');
													}
												}
											}
											else
											{
												if($_SESSION['order_type']=="1")
												{
													$arreturnvalue = array(
																			'body' => 'checkout_shipping.html',
																			'message' => 'The date or time you have chosen is invalid or the restaurant is closed at that time. Please specify another time or take a look a the restaurant open times.'
																			  );
												}
												else
												{
													$arreturnvalue = array(
																			'body' => 'checkout_collection.html',
													                        'message' => 'The date or time you have chosen is invalid or the restaurant is closed at that time. Please specify another time or take a look a the restaurant open times.'
													                       );
												}
											
											}
									}
					          		break;			
							 
							 
			case 'insert_order':
										 	if($_SESSION['customers_id']=="")
										 	{			
									        	$arreturnvalue = array('body' => 'oredr_place.html');
											}
											else
											{
												if($_SESSION['cart']!="")
												{
													$ridsc=$_SESSION["rids"];
													$sqlfcal="Select * from resturant_service where rid='$ridsc'";
													$resultfcal=mysql_query($sqlfcal);
													$rowfcal=mysql_fetch_array($resultfcal);
													$rcommis=$rowfcal['commission'];
													$rccfee=$rowfcal['ccfee'];
										
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
												 $query="Insert into customer_order values('', '".$_SESSION["customers_id"]."', '".$_SESSION["rids"]."','".$_SESSION['order_type']."', '".$_SESSION["customers_address1"]."', '".$_SESSION["customers_address2"]."', '".$_SESSION["customers_town"]."', '".$_SESSION["customers_state"]."', '".$_SESSION["customers_country"]."', '".$_SESSION["customers_postcode"]."', '".$_SESSION['customer_comments']."', '".$_SESSION["pay_mathod"]."', '','','$fcommission','','$rhfees','".$_SESSION["vat"]."','$d','".$_SESSION['timeH']."','$rccfee')";	

													//echo $query;
													//exit;	
													 $result = $db->execQuery($query);
							  						 $postid = $db->lastInsert($result);
													
													
													 $mlord=$postid;
													 
													 for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) 
													 {
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
													if ($arinfo[0]["order_type"]=="0")
													{
														$dst="Order Delivery";
													}
													else
													{ 
														$dst="Order Collection"; 
													}
																		
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
														while($rowpro=mysql_fetch_array($resultpro))
														{
															$body_txt .='<tr><td align="left" bgcolor="#FFFFFF">';
															$pids=$rowpro["product_id"];
															$sqlpn="Select * from product where product_id ='$pids'";
															$resultpn=mysql_query($sqlpn);
															$rowpn=mysql_fetch_array($resultpn);
															$body_txt .=$rowpn["product_title"];
															$body_txt .='</td><td align="left" bgcolor="#FFFFFF">&pound;';
															$body_txt .=$rowpro["uprice"];
															$body_txt .='</td><td align="center" bgcolor="#FFFFFF">';
															$body_txt .=$rowpro["qantity"];
															$body_txt .='</td><td align="left" bgcolor="#FFFFFF">&pound;';
															$stotal=$rowpro["uprice"]*$rowpro["qantity"];
															$body_txt .=$stotal;
															$body_txt .='</td></tr>';
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
															
													//$send_to="order@indianfoodsonline.co.uk";
													$sqlc="Select * from order_del_email where ode_id ='1'";
													$resultc=mysql_query($sqlc);
													$rowc=mysql_fetch_array($resultc);
													$send_to = $rowc["ode_email"];
								
													$sent = mail($send_to, $subject, $body_txt, $headers);
													
																			 unset($_SESSION['cart']);
																			 unset($_SESSION['qty']);
																			 unset($_SESSION['rids']);
																			unset($_SESSION['customer_comments']);
																			
												     $arreturnvalue = array('body' => 'confirmation.html');
												}
												else
												{
													$arreturnvalue = array('body' => 'customeraccounthome.html');
												}
											}
					         				break;	
							  
			case 'insert_order_roylty':
									 	if($_SESSION['customers_id']=="")
									 	{			
					        				$arreturnvalue = array('body' => 'oredr_place.html');
										}
										else
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
													if($_SESSION['order_type']=="2")
													{
														$rhfees=0;
													}
													$d=date('Y-m-d H:m:s');
													 $query="Insert into customer_order values('', '".$_SESSION["customers_id"]."', '".$_SESSION["rids"]."','".$_SESSION['order_type']."', '".$_SESSION["customers_address1"]."', '".$_SESSION["customers_address2"]."', '".$_SESSION["customers_town"]."', '".$_SESSION["customers_state"]."', '".$_SESSION["customers_country"]."', '".$_SESSION["customers_postcode"]."', '".$_SESSION['customer_comments']."', '".$_SESSION["pay_mathod"]."', '','','$fcommission','','$rhfees','".$_SESSION["vat"]."','$d','".$_SESSION['timeH']."')";	
													 $result = $db->execQuery($query);
							  						 $postid = $db->lastInsert($result);
													 $mlord=$postid;
													 for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) 
													 {
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
													if ($arinfo[0]["order_type"]=="1")
													{
																	$dst="Order Delivery";
													}
													else
													{ 
														$dst="Order Collection"; 
													}
											
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
												while($rowpro=mysql_fetch_array($resultpro))
												{
													$body_txt .='<tr><td align="left" bgcolor="#FFFFFF">';
													$pids=$rowpro["product_id"];
													$sqlpn="Select * from product where product_id ='$pids'";
													$resultpn=mysql_query($sqlpn);
													$rowpn=mysql_fetch_array($resultpn);
													$body_txt .=$rowpn["product_title"];
													$body_txt .='</td><td align="left" bgcolor="#FFFFFF">&pound;';
													$body_txt .=$rowpro["uprice"];
													$body_txt .='</td><td align="center" bgcolor="#FFFFFF">';
													$body_txt .=$rowpro["qantity"];
													$body_txt .='</td><td align="left" bgcolor="#FFFFFF">&pound;';
													$stotal=$rowpro["uprice"]*$rowpro["qantity"];
													$body_txt .=$stotal;
													$body_txt .='</td></tr>';
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
											
											//$send_to="order@indianfoodsonline.co.uk";
											$sqlc="Select * from order_del_email where ode_id ='1'";
											$resultc=mysql_query($sqlc);
											$rowc=mysql_fetch_array($resultc);
											$send_to = $rowc["ode_email"];
						
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
															
											$ordid=$arinfo[0]["orid"];
											$odt=$arinfo[0]["ordate"];
											$odt= strtotime($odt);
											$odt=date('d',$odt);
											$body_txt1 = '*';
											
											if(strlen($ordid)==1)
											{
												$mkzr='000';
											}
											elseif(strlen($ordid)==2)
											{
												$mkzr='00';
											}
											elseif(strlen($ordid)==3)
											{
												$mkzr='0';
											}
											elseif(strlen($ordid)>=4)
											{
												$mkzr='';
											}
											$body_txt1 .=$odt.$mkzr.$ordid; 
											$body_txt1= '*';
											$body_txt1 .=$rowc["customers_firstname"];
											$body_txt1 .=' ';
											$body_txt1 .=$rowc["customers_lastname"];
											$body_txt1 .= '**';
											  if($arinfo[0]["order_type"]!="2")
											  {
												  if($arinfo[0]["customers_address1"]!="")
												  {
													  $body_txt1 .=$arinfo[0]["customers_address1"];
												  }
												  if($arinfo[0]["customers_address2"]!="")
												  {
												  	$body_txt1 .=$arinfo[0]["customers_address2"];
												  }
												  if($arinfo[0]["customers_town"]!="")
												  {
												 	 $body_txt1 .=$arinfo[0]["customers_town"];
												  }
												  if($arinfo[0]["customers_state"]!="")
												  {
													 $body_txt1 .=$arinfo[0]["customers_state"];
												  }
												  if($arinfo[0]["customers_postcode"]!="")
												  {
													  $body_txt1 .=$arinfo[0]["customers_postcode"];
												  }
											  }
											  $body_txt1 .='***';
											  $body_txt1 .=$rowc["customers_telephone"];
											  $body_txt1 .="++++"; 
											  
											  $sqlproduc="select * from order_detail where orid='$mlord'";
											  $resultpro=mysql_query($sqlproduc);
											  $cdeb =0;
													
											  while($rowpro=mysql_fetch_array($resultpro))
											  {
													if ($cdeb!=0) 
													{
														$body_txt1 .=";";
													}
												  $cdeb = $cdeb + 1; 
												  $pids=$rowpro["product_id"];
												  $sqlpn="Select * from product where product_id ='$pids'";
												  $resultpn=mysql_query($sqlpn);
	 											  $rowpn=mysql_fetch_array($resultpn);
												  $body_txt1 .=$rowpro["qantity"];  
												  $body_txt1 .=',';
												  $body_txt1 .=$rowpn["food_code"];
												  $body_txt1 .=',';
												  $stotal=$rowpro["uprice"]*$rowpro["qantity"];
												  $body_txt1 .=number_format($stotal, 2, '.', '');
												  
											  }
											  $body_txt1 .="****";
											  $body_txt1 .='Total,';
											  
											  $tcalid=$arinfo[0]["orid"];
											  $sqltpr="select sum(uprice*qantity) as tprice from order_detail where orid='$tcalid'";
											  $resulttpr=mysql_query($sqltpr);
											  $rowprt=mysql_fetch_array($resulttpr);
											  $ftotal=$rowprt["tprice"]+$arinfo[0]["vat"]+$arinfo[0]["hnd_fee"];
											  $body_txt1 .=number_format($ftotal, 2, '.', '');
											  $body_txt1 .="#";
								
											$sqlgt="Select * from res_royalty";
											$resultrol=mysql_query($sqlgt);
											$rowrol=mysql_fetch_array($resultrol);
											$ebpt=$rowrol["pointt"]*$ftotal;
											$sqlinpoint="insert into customer_roylpoint values('','$cuid','','$ebpt')";
											$resultpoint = $db->execQuery($sqlinpoint);
											$subject="";
							
											$sent = mail($send_to, $subject, $body_txt1, $headers);
							
									# aql API Call start @Shafiq
											$rsid=$_SESSION["rids"];
					
											$query1 = "SELECT * FROM resturant_service where rid='$rsid'";
											$result1 = $db->execQuery($query1);
											$corinfo = $db->resultArray($result1);
											$number=$corinfo[0]['pr_code'];
					
											$username='indianfoods';
											$password='online121';
												
											$weburl = "http://gw1.aql.com/sms/postmsg.php";	
											$message = urlencode($body_txt1);
											$smsurl = "$weburl?username=$username&password=$password&to_num=$number&message=$message";
												
											 // if $flash is set to 1, it will add the flash request onto the query
												  if ($flash == "1")
												  {
												    $smsurl .= "&flash=1";
												  }	
												  // connects to server to send message
												  if ($content_array = file($smsurl))
												  {
													    $content = implode("", $content_array);
													
													    // check for response
													    if (eregi("AQSMS-AUTHERROR",$content))
													    { 
													      //echo "There was an authenication error";
													    }
													    elseif (eregi("AQSMS-NOMSG",$content))
													    {
													      //echo "There was no message or mobile number";
													    }
													    elseif (eregi("AQSMS-OK",$content))
													    { 
													      //echo "The Message was queued succcessfully";
													    }
													    elseif (eregi("AQSMS-NOCREDIT",$content))
													    {
													      //echo "Your account has no credit";
														}
													  }
												   else
												   {
												    //echo "There was an error connecting to the server";
												   } 
											
									#aql api end @Shafiq		
							
										//////////////////sagar//////////////
										
										//$send_to="order@indianfoodsonline.co.uk";
										$sqlc="Select * from order_del_email where ode_id ='1'";
										$resultc=mysql_query($sqlc);
										$rowc=mysql_fetch_array($resultc);
										$send_to = $rowc["ode_email"];
										
										///////////////////////////sagar////////////////////////////
										
										$sent = mail($send_to, $subject, $body_txt1, $headers);
										unset($_SESSION['cart']);
										unset($_SESSION['qty']);
										unset($_SESSION['rids']);
										unset($_SESSION['customer_comments']);
													
										$arreturnvalue = array('body' => 'thankyou_paypal.html');
									}
									else
									{
								    	$arreturnvalue = array('body' => 'customeraccounthome.html');
									}
								}
					            break;	
							
							  
			case 'customeraccounthome':
									   $arreturnvalue = array('body' => 'customeraccounthome.html');
									   break;	
							  	
			case 'insert_order_cashdelivery':
										 	if($_SESSION['customers_id']=="")
										 	{			
					        					$arreturnvalue = array('body' => 'oredr_place.html');
											}
											else
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
														if($_SESSION['order_type']=="2")
														{
															$rhfees=0;
														}
														//print_r($_SESSION);
														//exit;
														 $d=date('Y-m-d H:m:s');
														 $query="Insert into customer_order values('', '".$_SESSION["customers_id"]."', 
														 											   '".$_SESSION["rids"]."','".$_SESSION['order_type']."', 
																									   	'".$_SESSION["customers_address1"]."', 
																										'".$_SESSION["customers_address2"]."', 
																										'".$_SESSION["customers_town"]."', 
																										'".$_SESSION["customers_state"]."', 
																										'".$_SESSION["customers_country"]."', 
																										'".$_SESSION["customers_postcode"]."', 
																										'".$_SESSION['customer_comments']."', 
																										'".$_SESSION["pay_mathod"]."', 
																										'',
																										'',
																										'$fcommission',
																										'','$rhfees',
																										'".$_SESSION["vat"]."',
																										'$d',
																										'".$_SESSION['timeH']."',
																										''
																										)";	
														 $result = $db->execQuery($query);
								  						 $postid = $db->lastInsert($result);
														 $mlord=$postid;
														 
														 for ($i = 0; $i < sizeof($_SESSION[cart]); $i++) 
														 {
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
											  		$body_txt .= '</td><td valign="top" bgcolor="#FFFFFF" class="bodytext">';
													$ridso=$arinfo[0]["rid"];
													$sqlres="Select * from resturant where rid='$ridso'";
													$resultord=mysql_query($sqlres);
									
													$roword=mysql_fetch_array($resultord);
													  
													$body_txt .= 'Restaurant Name </td><td valign="top" bgcolor="#FFFFFF" class="bodytext">';
													$body_txt .=$roword["rname"];
													$body_txt .='</td></tr><tr>
												                  <td align="left" valign="top" bgcolor="#FFFFFF" class="bodytext">Order Type: </td>
												                  <td valign="top" bgcolor="#FFFFFF" class="bodytext">';
													if ($arinfo[0]["order_type"]=="1")
													{
																$dst="Order Delivery";
													}
													else{ $dst="Order Collection"; }
																
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
													  $body_txt .='</td><td colspan="2" rowspan="2" align="left"  valign="top" bgcolor="#FFFFFF">';
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
													while($rowpro=mysql_fetch_array($resultpro))
													{
														$body_txt .='<tr><td align="left" bgcolor="#FFFFFF">';
														$pids=$rowpro["product_id"];
														$sqlpn="Select * from product where product_id ='$pids'";
														$resultpn=mysql_query($sqlpn);
														$rowpn=mysql_fetch_array($resultpn);
														$body_txt .=$rowpn["product_title"];
														$body_txt .='</td><td align="left" bgcolor="#FFFFFF">&pound;';
														$body_txt .=$rowpro["uprice"];
														$body_txt .='</td><td align="center" bgcolor="#FFFFFF">';
														$body_txt .=$rowpro["qantity"];
														$body_txt .='</td><td align="left" bgcolor="#FFFFFF">&pound;';
														$stotal=$rowpro["uprice"]*$rowpro["qantity"];
														$body_txt .=$stotal;
														$body_txt .='</td></tr>';
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

													//$send_to="order@indianfoodsonline.co.uk";
													$sqlc="Select * from order_del_email where ode_id ='1'";
													$resultc=mysql_query($sqlc);
													$rowc=mysql_fetch_array($resultc);
													$send_to = $rowc["ode_email"];

													$sent = mail($send_to, $subject, $body_txt, $headers);
													
													$oridsp=$mlord;
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
															
											                
													$ordid=$arinfo[0]["orid"];
													
													echo strlen($ordid);
													
													
													$odt=$arinfo[0]["ordate"];
													$odt= strtotime($odt);
													$odt=date('d',$odt);
													$body_txt1 = '*';
													
													
													if(strlen($ordid)==1)
													{
														$mkzr='000';
													}
													elseif(strlen($ordid)==2)
													{
														$mkzr='00';
													}
													elseif(strlen($ordid)==3)
													{
														$mkzr='0';
													}
													elseif(strlen($ordid)>=4)
													{
														$mkzr='';
													}
													
													$body_txt1 .=$odt.$mkzr.$ordid; 
													
													$body_txt1 .= '*';
													$body_txt1 .=$rowc['customers_firstname'];
													$body_txt1 .=' ';
													$body_txt1 .=$rowc['customers_lastname'];
													$body_txt1 .= '**';
													
													if($arinfo[0]["order_type"]!="2")
													{
														  if($arinfo[0]["customers_address1"]!="")
														  {
															  $body_txt1 .=$arinfo[0]["customers_address1"];
															  $body_txt1 .=" ";
														  }
														  if($arinfo[0]["customers_address2"]!="")
														  {
															  $body_txt1 .=$arinfo[0]["customers_address2"];
															  $body_txt1 .=" ";
														  }
														  if($arinfo[0]["customers_town"]!="")
														  {
															  $body_txt1 .=$arinfo[0]["customers_town"];
															  $body_txt1 .=" ";
														  }
														  if($arinfo[0]["customers_state"]!="")
														  {
															  $body_txt1 .=$arinfo[0]["customers_state"];
															  $body_txt1 .=" ";
														  }
														  if($arinfo[0]["customers_postcode"]!="")
														  {
															  $body_txt1 .=$arinfo[0]["customers_postcode"];
														  }
													  }
													
													 $body_txt1 .='***';
													 $body_txt1 .=$rowc["customers_telephone"];
													 $body_txt1 .="++++";
													
													 $sqlproduc="select * from order_detail where orid='$mlord'";
													 $resultpro=mysql_query($sqlproduc);
													 $cdeb =0;
													 
													 while($rowpro=mysql_fetch_array($resultpro))
													 {
														if ($cdeb!=0) 
														{
															$body_txt1 .=";";
														}
														  $cdeb = $cdeb + 1; 
														  $pids=$rowpro["product_id"];
														  $sqlpn="Select * from product where product_id ='$pids'";
														  $resultpn=mysql_query($sqlpn);
														  $rowpn=mysql_fetch_array($resultpn);
													      $body_txt1 .=$rowpn["food_code"];
														  $body_txt1 .=',';
														  $body_txt1 .=$rowpro["qantity"];  
														  $body_txt1 .=',';
														  $stotal=$rowpro["uprice"]*$rowpro["qantity"];
														  $body_txt1 .=number_format($stotal, 2, '.', '');
															
																		       				  
													    }
											
														$body_txt1 .="****";
														$body_txt1 .='Total,';
														$tcalid=$arinfo[0]["orid"];
														$sqltpr="select sum(uprice*qantity) as tprice from order_detail where orid='$tcalid'";
														$resulttpr=mysql_query($sqltpr);
														$rowprt=mysql_fetch_array($resulttpr);
														$ftotal=$rowprt["tprice"]+$arinfo[0]["vat"]+$arinfo[0]["hnd_fee"];
														$body_txt1 .=number_format($ftotal, 2, '.', '');
												  	    $body_txt1 .="#";
										
														$resultpoint = $db->execQuery($sqlinpoint);
														$subject="";
														$sent = mail($send_to, $subject, $body_txt1, $headers);
														
														//////////////////sagar//////////////
														
														//$send_to="order@indianfoodsonline.co.uk";
														$sqlc="Select * from order_del_email where ode_id ='1'";
														$resultc=mysql_query($sqlc);
														$rowc=mysql_fetch_array($resultc);
														$send_to = $rowc["ode_email"];
														
														///////////////////////////sagar////////////////////////////
											
														$sent = mail($send_to, $subject, $body_txt1, $headers);
												
														# aql API Call start @shafiq
														$rsid=$_SESSION["rids"];
													
														$query1 = "SELECT * FROM resturant_service where rid='$rsid'";
														$result1 = $db->execQuery($query1);
														$corinfo = $db->resultArray($result1);
													
														$number=$corinfo[0]['pr_code'];
														$username='indianfoods';
														$password='online121';
														
														# @shafiq Stop for development
														$weburl = "http://gw1.aql.com/sms/postmsg.php";	
														$message = urlencode($body_txt1);
														// the request string
														 $smsurl = "$weburl?username=$username&password=$password&to_num=$number&message=$message";
															
															
															  // if $flash is set to 1, it will add the flash request onto the query
														 if ($flash == "1")
														 {
														    $smsurl .= "&flash=1";
														 }	
															  // connects to server to send message
														  if ($content_array = file($smsurl))
														  {
														    $content = implode("", $content_array);
															
															    // check for response
															    if (eregi("AQSMS-AUTHERROR",$content))
															    { 
															      //echo "There was an authenication error";
															    }
															    elseif (eregi("AQSMS-NOMSG",$content))
															    {
															      //echo "There was no message or mobile number";
															    }
															    elseif (eregi("AQSMS-OK",$content))
															    { 
															      //echo "The Message was queued succcessfully";
															    }
															    elseif (eregi("AQSMS-NOCREDIT",$content))
															    {
															      //echo "Your account has no credit";
																}
														  }
														  else
														  {
															    //echo "There was an error connecting to the server";
														   } 
											
											     #aql api end		
												
												 unset($_SESSION['cart']);
												 unset($_SESSION['qty']);
												 unset($_SESSION['rids']);
												 unset($_SESSION['customer_comments']);
												 $arreturnvalue = array('body' => 'thankyou_paypal.html');
										}
										else
										{
										  $arreturnvalue = array('body' => 'customeraccounthome.html');
										}
									}
					            	break;	
							
			case 'insert_deliver_policy':
						 	if($_SESSION['userid']!="")
							{
								$rids=$_SESSION['resid'];
						       	
						        if ($_REQUEST["dpid"]=="") 
							    {
									 $query = "INSERT INTO delevary_policy VALUES('','$rids','".mysql_escape_string($_REQUEST["radious"])."','".mysql_escape_string($_REQUEST["take_time"])."','".mysql_escape_string($_REQUEST["del_time"])."','".mysql_escape_string($_REQUEST["min_order"])."')";
								     $result = $db->execQuery($query);
										  if ($result) 
										  {
							                    $arreturnvalue = array(
																		'body' => 'delivery_policy.html',
								                                        'message' => 'Policy Inserted Successfully!'
								                                       );
							               } 
							               else 
							               {
							                    $arreturnvalue = array(
																		'body' => 'delivery_policy.html',
								                                    	'message' => 'Policy Insertion Failed!'
								                                       );
							               }
								}
								else
								{
									$query = "Update delevary_policy set radious='".mysql_escape_string($_REQUEST["radious"])."',take_time='".mysql_escape_string($_REQUEST["take_time"])."',del_time='".mysql_escape_string($_REQUEST["del_time"])."',min_order='".mysql_escape_string($_REQUEST["min_order"])."' where dpid='".$_REQUEST["dpid"]."'";
									$result = $db->execQuery($query);
									  if ($result) 
									  {
						              	$arreturnvalue = array(
																'body' => 'delivery_policy.html',
							                                    'message' => 'Policy Inserted Successfully!'
							                                   );
						                } 
						                else 
						                {
						                    $arreturnvalue = array(
																	'body' => 'delivery_policy.html',
							                                    	'message' => 'Policy Insertion Failed!'
							                                       );
						                }
								}
							   
							 }
							else
							{
								$arreturnvalue = array('body' => 'home_body.html');
							}
							break;	
					
					
			case 'resturantpolicy_policy':
										 	if($_SESSION['userid']!="")
										 	{
									        	$arreturnvalue = array('body' => 'resturantpolicy_policy.html');
											}
											else
											{
												$arreturnvalue = array('body' => 'home_body.html');
											}
									        break;
									        	
			case 'insert_resturant_policy':
										 	if($_SESSION['userid']!="")
											{
													$rids=$_SESSION['resid'];
											       	$query = "SELECT * FROM resturant_policy  where rid  = '$rids' and policy_id='".$_REQUEST[policy_id]."'";
											        $success = $db->checkExists($query);
													$d=date('Y-m-d H:m:s');
											        if (!$success) 
												    {
														$query = "INSERT INTO resturant_policy VALUES('','$rids','".mysql_escape_string($_REQUEST["policy_id"])."')";
														$result = $db->execQuery($query);
														
														if ($result) 
														{
												        	$arreturnvalue = array(
																					'body' => 'resturantpolicy_policy.html',
													                                'message' => 'Policy Added Successfully!');
												         }
												         else
												         {
												            $arreturnvalue = array(
																					'body' => 'resturantpolicy_policy.html',
													                               	'message' => 'Policy Insertion Failed!'
													                               );
												         }
													  }
													  else
													  {
													  	$arreturnvalue = array(
																				'body' => 'resturantpolicy_policy.html',
													                            'message' => 'Policy Already taken into resturant account'
													                           );
													  }
												   
											}
											else
											{
												$arreturnvalue = array('body' => 'home_body.html');
											}
											break;	
			case 'del_policy_acc':
						
									if($_SESSION['userid']!="")
									{
								        if (isset($_REQUEST["plid"]) && !empty($_REQUEST["plid"])) 
									    {
									            $query = "DELETE FROM resturant_policy WHERE id = '".$_REQUEST["plid"]."'";
									            $result = $db->execQuery($query);
									            if ($result) 
									            {
									            	$arreturnvalue = array(
																			'body' => 'resturantpolicy_policy.html',
										                        			'message' => 'Policy Withdraw Successfull!'
										                        		   );
									            } 
									            else 
									            {
									                $arreturnvalue = array(
																			'body' => 'resturantpolicy_policy.html',
										                        			'message' => 'Policy Withdraw Failed!'
										                        		   );
									            }
									        }
									        else 
									        {
								        		$arreturnvalue = array(
																		'body' => 'resturantpolicy_policy.html',
								        	                    		'message' => 'Policy Withdraw Failed!'
								        	                    	   );
								        	}
									}
									else
									{
										
										$arreturnvalue = array('body' => 'home_body.html');
									}
							        break;
			case 'insert_new_resturant':
							if($_SESSION['userid']!="")
							{
									$uids=$_SESSION['userid'];
									$postids=rand();
								    $query = "SELECT * FROM resturant  where rname  = '".$_REQUEST["rname"]."'";
							        $success = $db->checkExists($query);
									$d=date('Y-m-d H:m:s');
							        
							        if (!$success) 
								    {
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
										
										if (!$userfile_name=="")
						    			{
									        $prod_imgs = $filedir2.'large_'.$postids.'_'.$userfile_name;
											$prod_img_thumbs = $thumbdir2.$prefixs.$userfile_name;
										}
									
						            	$query = "INSERT INTO resturant VALUES('','$uids','".mysql_escape_string($_REQUEST["area_id"])."','".mysql_escape_string($_REQUEST["chinise_id"])."','".mysql_escape_string($_REQUEST["rname"])."','".mysql_escape_string($_REQUEST["street"])."','".mysql_escape_string($_REQUEST["city"])."','".mysql_escape_string($_REQUEST["zipcode"])."','".mysql_escape_string($_REQUEST["country_id"])."','".mysql_escape_string($_REQUEST["contact"])."','".mysql_escape_string($_REQUEST["web"])."','$prod_img_thumbs','".nl2br($_REQUEST["rdesc"])."','".nl2br($_REQUEST["keytitle"])."','','')";
									
							            $result = $db->execQuery($query);
										$postid = $db->lastInsert($result);
										$_SESSION['resid']=$postid;
						                if ($result) 
						                {
						                        $arreturnvalue = array(
																		'body' => 'my_resturant.html',
							                                        	'message' => 'Restaurant set up Successfully!'
							                                           );
						                } 
						                else 
						                {
						                    $arreturnvalue = array(
																	'body' => 'new_resturant.html',
							                                    	'message' => 'Restaurant set up Failed!'
							                                       );
						                }
						        } 
						        else 
						        {
					        		$arreturnvalue = array(
															'body' => 'new_resturant.html',
						                            		'message' => 'Restaurant Already Exists!.'
						                            	  );
					        	}
						
							}
							else
							{
								$arreturnvalue = array(
														'body' => 'home_body.html',
						                            	'message' => '');
							}
						    break;
								
			case 'insert_item':
								if($_SESSION['userid']!="")
								{
								
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
									
									if($userfile_name!="")
									{
										Image_resize($userfile_name, $userfile_tmp, $userfile_size, $userfile_type,0,$postid);
										$filedir = 'product_image/';
							 			$thumbdir = 'product_image/'; 
										$filedir1 = 'product_image/';
							 			$thumbdir1 = 'product_image/'; 
						     			$prefix = 'small_'.$postid.'_';
										
								        $prod_img = $filedir1.'large_'.$postid.'_'.$userfile_name;
										$prod_img_thumb = $thumbdir1.$prefix.$userfile_name;
										$prod_img_thumb1 = $thumbdir1.'mid_'.$postid.'_'.$userfile_name;
										$query = "INSERT INTO product VALUES('','".mysql_escape_string($_REQUEST["categories_id"])."','".mysql_escape_string($_REQUEST["product_title"])."','".strtolower($prod_img_thumb)."','".strtolower($prod_img_thumb1)."','".mysql_escape_string($_REQUEST["product_desc"])."','".mysql_escape_string($_REQUEST["price"])."')";
										$result = $db->execQuery($query);
									
						                if ($result) 
						                {
						                        $arreturnvalue = array(
																		'body' => 'additem.html',
							                                        	'message' => 'Itemk added Successfully!'
							                                           );
						                } 
						                else 
						                {
						                    $arreturnvalue = array(
																	'body' => 'additem.html',
							                                    	'message' => 'Item addition Failed!'
							                                       );
						                }
						        } 
						        else 
						        {
					        		$arreturnvalue = array(
															'body' => 'additem.html',
						                            		'message' => 'Item Already Exists!.'
						                            	  );
					        	}
							
						
							}
							else{
									$arreturnvalue = array(
															'body' => 'home_body.html',
						                            		'message' => ''
						                            	  );
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
																		}
																		else
																		{
																			$lstarttime=$_REQUEST["sttimeh1"].":".$_REQUEST["sttimem1"]."  ".$_REQUEST["sttimea1"];
																			$lendtime=$_REQUEST["entimeh1"].":".$_REQUEST["entimem1"]."  ".$_REQUEST["entimea1"];
																		}
																		if($_REQUEST["dinerc"]!="")
																		{
																			$dstarttime=$_REQUEST["dinerc"];
																			$dendtime="";
																		}
																		else
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
																		elseif($_REQUEST["days"]=="Tuesday")
																		{
																			$open_h_tue_start=$lstarttime;
																			$open_h_tue_end=$lendtime;
																			$open_h_tue_start2=$dstarttime;
																			$open_h_tue_end2=$dendtime;
																			$insvalue="('' ,'$rids', '', '', '', '', '$open_h_tue_start', '$open_h_tue_end', '$open_h_tue_start2', '$open_h_tue_end2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
																			$upvalue="open_h_tue_start='$open_h_tue_start',open_h_tue_end= '$open_h_tue_end',open_h_tue_start2= '$open_h_tue_start2', open_h_tue_end2='$open_h_tue_end2' where rid='$rids'";
																		}
																		elseif($_REQUEST["days"]=="Wednesday")
																		{
																			$open_h_wed_start=$lstarttime;
																			$open_h_wed_end=$lendtime;
																			$open_h_wed_start2=$dstarttime;
																			$open_h_wed_end2=$dendtime;
																			$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '$open_h_wed_start', '$open_h_wed_end', '$open_h_wed_start2', '$open_h_wed_end2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
																			$upvalue="open_h_wed_start='$open_h_wed_start',open_h_wed_end= '$open_h_wed_end',open_h_wed_start2= '$open_h_wed_start2', open_h_wed_end2='$open_h_wed_end2' where rid='$rids'";
																		}
																		elseif($_REQUEST["days"]=="Thursday")
																		{
																			$open_h_thu_start=$lstarttime;
																			$open_h_thu_end=$lendtime;
																			$open_h_thu_start2=$dstarttime;
																			$open_h_thu_end2=$dendtime;
																			$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '', '', '', '', '$open_h_thu_start', '$open_h_thu_end', '$open_h_thu_start2', '$open_h_thu_end2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '')";
																			$upvalue="open_h_thu_start='$open_h_thu_start',open_h_thu_end= '$open_h_thu_end',open_h_thu_start2= '$open_h_thu_start2', open_h_thu_end2='$open_h_thu_end2' where rid='$rids'";
																		}
																		elseif($_REQUEST["days"]=="Friday")
																		{
																			$open_h_fri_start=$lstarttime;
																			$open_h_fri_end=$lendtime;
																			$open_h_fri_start2=$dstarttime;
																			$open_h_fri_end2=$dendtime;
																			$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$open_h_fri_start', '$open_h_fri_end', '$open_h_fri_start2', '$open_h_fri_end2', '', '', '', '', '', '', '', '', '', '', '', '')";
																			$upvalue="open_h_fri_start='$open_h_fri_start',open_h_fri_end= '$open_h_fri_end',open_h_fri_start2= '$open_h_fri_start2', open_h_fri_end2='$open_h_fri_end2' where rid='$rids'";
																		}
																		elseif($_REQUEST["days"]=="Saturday")
																		{
																			$open_h_sat_start=$lstarttime;
																			$open_h_sat_end=$lendtime;
																			$open_h_sat_start2=$dstarttime;
																			$open_h_sat_end2=$dendtime;
																			$insvalue="('' ,'$rids', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '$open_h_sat_start', '$open_h_sat_end', '$open_h_sat_start2', '$open_h_sat_end2', '', '', '', '', '', '', '', '')";
																			$upvalue="open_h_sat_start='$open_h_sat_start',open_h_sat_end= '$open_h_sat_end',open_h_sat_start2= '$open_h_sat_start2', open_h_sat_end2='$open_h_sat_end2' where rid='$rids'";
																		}
																		elseif($_REQUEST["days"]=="Sunday")
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
																        if (!$success) 
																        {
																			 $query = "INSERT INTO timeschedule VALUES $insvalue";
																			
																           	 $result = $db->execQuery($query);
																			  if ($result) 
																			  {
																              	$arreturnvalue = array(
																										'body' => 'timeschedule.html',
																	                                   	'message' => 'Time Inserted Successfully!');
																                } 
																                else
																                {
																                    $arreturnvalue = array(
																											'body' => 'timeschedule.html',
																	                                    	'message' => 'Time Insertion Failed!'
																	                                       );
																                }
																		}
																		else
																		{
																			$query = "Update timeschedule set $upvalue";
																		    $result = $db->execQuery($query);
																			  if ($result) 
																			  {
																              		$arreturnvalue = array(
																											'body' => 'timeschedule.html',
																	                                        'message' => 'Time Inserted Successfully!'
																	                                       );
																                } 
																                else
																                {
																                    $arreturnvalue = array(
																											'body' => 'timeschedule.html',
																	                                    	'message' => 'Time Insertion Failed!'
																	                                       );
																                }
																		}
																	   
																		}
																		else
																		{
																			$arreturnvalue = array('body' => 'home_body.html');
																		}
																	    break;		
			case 'contact':
					        $arreturnvalue = array('body' => 'contact.html');
					        break;
			case 'email':
							require_once('lib/class.SendMail.php');
							$date_month = date(m);
							$date_year = date(Y);
							$date_day = date(d);
							$time_hour = date(H);
							$time_min = date(i);
				
							$Date = "$date_day/$date_month/$date_year - $time_hour:$time_min";
							
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
				
							$msg=$message1.$message.$message3.$message2;
							
							$sendmail= new SendMail($to, $cc, $bcc, $from, $subject, $msg, $headers);
							$success = $sendmail->send();
							
							$arreturnvalue = array(
														'body' => 'contact.html',
														'message' => 'Your request has sent successfully.We will contact you soon'
												   );
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
				    
     } 
	 else 
	 {
		$pageName="Home";
		$arreturnvalue = array('body' => 'home_body.html');
	 }

	$body = $arreturnvalue['body'];
	$message = $arreturnvalue['message'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php 

$get_pid=$_REQUEST["pid"];


if($body =='customer_registration.html')
		   {
	   		 
			  require_once('meta/customer_registration.html');		    
			 
		  }
		   elseif($body =='sign_up.html')
		  {

			  require_once('meta/sign_up.html');		    
		  }
		  elseif($body =='pages.html')
		  {

			  $query_meta = "SELECT * FROM cms_posts where ID='$get_pid'";
			  $result_meta = $db->execQuery($query_meta);
			  $meta_reslt = $db->resultArray($result_meta);

			  echo stripslashes($meta_reslt[0]['meta_info']);
		  }
		  else
		  {
?>

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Indian Foods Online .co.uk | UK's leading online indian takeaway and delivery food ordering provider</title>

<meta name="author" content="Indian Foods Online" />
<meta http-equiv="expires" content="NEVER" />
<meta name="publisher" content="Indian Foods Online" />
<meta name="copyright" content="Indian Foods Online" />
<meta name="page-topic" content="Restaurants" />
<meta name="keywords" content="order indian foods online, indian takeaway, online currys, online table booking, tikka, masala, curry house, just eat, food delivery, online order, takeaways, takeaway" />
<meta name="description" content="Order curry online with Indian Foods Online, The Europe's market leader in online Indian food. Order a Takeaway or Delivery in minutes and just eat and enjoy!!" />
<meta name="page-type" content="Commercial" />
<meta name="audience" content="All" />
<meta name="robots" content="INDEX,FOLLOW" />
<meta http-equiv="content-language" content="EN" />

<meta name="verify-v1" content="1o3acjhVzBcxv0QRsk0PRXKqFlr7DqYg+hYUr1ebDZU=" >

<? }?>

<script type="text/javascript"  src="topbanner.js"></script>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="menu.css" rel="stylesheet" type="text/css" />
<link href="map.css" rel="stylesheet" type="text/css" />
</head>

<body>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-2755059-3");
pageTracker._trackPageview();
} catch(err) {}</script>
<div id="container">
   <div id="top"></div>
      <div id="wbody">
      <!----- header + brade crum + menu+ baner start---->
        <div id="topcontent">
       <?php require_once('includes/new_header.html'); 

	   ?>
	 
	   <?   if($body !='restaurant_list.html'and $body !='order_menu.html'and $body !='reservetion.html')
		   {
	   		   require_once('includes/new_header_searchbanner.html');		    

		   }


	   ?>
        </div>
		 <!----- header + brade crum + menu+ baner end---->
		 
		<!----- left content + right content  + mid content  start---->
		 <div id="middle"> 
		<? if($body=='order_menu.html' or $body=='restaurant_list.html' or $body=='reservetion.html')
		   {
				 require_once("includes/$body");
		   }
		   else
		   {
				if($body!='order_menu.html' and $body!='restaurant_list.html' and $body!='reservetion.html' and $body!='checkout_shipping.html' and $body!='confirm_cash_payment.html' and $body!='oredr_place.html' and $body!='reservetion_place.html' and $body!='sign_up.html' and $body!='confirm_roilty_payment.html' and $body!='reservetion_confirm.html' and $body!='checkout_collection.html' and $body!='confirm_cash_payment_collection.html' and $body!='confirm_roilty_payment_collection.html' and   $body!='restaurant_view_details.html' and $body!='confirm_online_payment.html' and $body!='confirm_online_payment_collection.html' and $body!='restaurants.html' and $body!='thankyou_paypal.html')
		    	{
	     ?>
		
        			<div id="left_cont">
        			
        			<?php  require_once('includes/new_left_panal.html');?>
        			
        			</div>
		
		   <?	 }	?>
		   
		   	
		  
		
  <? 
  
  //if($_SESSION['userid']=="" and $_REQUEST[action]=='' or $_REQUEST[action]=='home_body' or $_REQUEST[action]=='logout' or $_REQUEST[action]=='pages' or $body=='home_body.html')
  if($_SESSION['userid']=="" and $_REQUEST[action]=='' or $_REQUEST[action]=='home_body' or $_REQUEST[action]=='logout' or $body=='home_body.html')
				   {
				?>
        <div id="right_cont"><?php  require_once('includes/new_reight_panal.html');?></div>

		<? }	?>
			<?php require_once("includes/$body");?>
		<?php } ?>
		  </div><!----- left content + right content  + mid content  start---->
   
    <div style="height:25px; clear:both"></div>

   <div id="footer">
		  <?php require_once('includes/new_footer.html');?>
   </div>
</div>
</body>
</html>
