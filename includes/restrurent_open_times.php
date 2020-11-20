<?php
								require_once('../lib/class.Db.php');
										require_once('../config.php');
										$db = new Db(hostname, username, password, databasename);
												$rids=$_SESSION['resid'];
										$db = new Db(hostname, username, password, databasename);
                                      	$query = "SELECT * FROM timeschedule   WHERE rid=".$_REQUEST['id'];
     					               	$result = $db->execQuery($query);
                    					$arinfo = $db->resultArray();                    					
                                      ?>
<link href="../style.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#C4C4C4" class="bodytext">
  <tbody>
    <tr>
      <td width="30%" bgcolor="#DFDFDF" class="nrmlink">Opening Hours</td>
      <td width="35%" bgcolor="#DFDFDF" class="nrmlink">Lunch</td>
      <td width="35%" bgcolor="#DFDFDF" class="nrmlink">Dinner</td>
    </tr>
    <tr>
      <td bgcolor="#F5F5F5">Monday</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_mon_start']!="Closed For the day")
											{ 
											$mlst=strtotime($arinfo[0]['open_h_mon_start']);
											
											echo date('g:i a', $mlst);
													if($arinfo[0]['open_h_mon_end']!=""){ echo "-";}
											$mlen=strtotime($arinfo[0]['open_h_mon_end']);
													 echo date('g:i a ', $mlen);
											}else
											{
											echo  $arinfo[0]['open_h_mon_start'];
													if($arinfo[0]['open_h_mon_end']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_mon_end'];
											
											}  ?>
        &nbsp;&nbsp;</td>
      <td bgcolor="#F5F5F5" ><?php 
												  
												if($arinfo[0]['open_h_mon_start2']!="Closed For the day")
												{
												$mdst=strtotime($arinfo[0]['open_h_mon_start2']);
												echo date('g:i a ', $mdst);
													if($arinfo[0]['open_h_mon_end2']!=""){ echo "-";}
												 $mden=strtotime($arinfo[0]['open_h_mon_end2']);
												echo date('g:i a ', $mden);
												}else
											{echo $arinfo[0]['open_h_mon_start2'];
													if($arinfo[0]['open_h_mon_end2']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_mon_end2']; 
													 }
											 ?></td>
    </tr>
    <tr>
      <td bgcolor="#F5F5F5">Tuesday</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_tue_start']!="Closed For the day")
											{ echo date('g:i a ', strtotime($arinfo[0]['open_h_tue_start']));
													if($arinfo[0]['open_h_tue_end']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_tue_end']));
													 }else
											{echo $arinfo[0]['open_h_tue_start'];
													if($arinfo[0]['open_h_tue_end']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_tue_end']; } ?>
        &nbsp;&nbsp;</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_tue_start2']!="Closed For the day")
											{echo date('g:i a ', strtotime($arinfo[0]['open_h_tue_start2']));
													if($arinfo[0]['open_h_tue_end2']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_tue_end2'])); }else
											{echo $arinfo[0]['open_h_tue_start2'];
													if($arinfo[0]['open_h_tue_end2']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_tue_end2']; 
													 } ?></td>
    </tr>
    <tr>
      <td bgcolor="#F5F5F5">Wednesday</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_wed_start']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_wed_start']));
													if($arinfo[0]['open_h_wed_end']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_wed_end'])); 
											}else
											{echo $arinfo[0]['open_h_wed_start'];
													if($arinfo[0]['open_h_wed_end']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_wed_end']; 
													 } ?>
        &nbsp;&nbsp;</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_wed_start2']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_wed_start2']));
													if($arinfo[0]['open_h_wed_end2']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_wed_end2']));
											}else{echo $arinfo[0]['open_h_wed_start2'];
													if($arinfo[0]['open_h_wed_end2']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_wed_end2']; } ?></td>
    </tr>
    <tr>
      <td bgcolor="#F5F5F5">Thursday</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_thu_start']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_thu_start']));
													if($arinfo[0]['open_h_thu_end']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_thu_end']));
											}else{echo $arinfo[0]['open_h_thu_start'];
													if($arinfo[0]['open_h_thu_end']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_thu_end']; } ?>
        &nbsp;&nbsp;</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_thu_start2']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_thu_start2']));
													if($arinfo[0]['open_h_thu_end2']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_thu_end2'])); 
											}else{echo $arinfo[0]['open_h_thu_start2'];
													if($arinfo[0]['open_h_thu_end2']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_thu_end2']; 
													 } ?></td>
    </tr>
    <tr>
      <td bgcolor="#F5F5F5">Friday</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_fri_start']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_fri_start']));
													if($arinfo[0]['open_h_fri_end']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_fri_end'])); 
											}else
											{
											echo $arinfo[0]['open_h_fri_start'];
													if($arinfo[0]['open_h_fri_end']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_fri_end']; 
													 } ?>
        &nbsp;&nbsp;</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_fri_start2']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_fri_start2']));
													if($arinfo[0]['open_h_fri_end2']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_fri_end2']));
											}else
											{echo $arinfo[0]['open_h_fri_start2'];
													if($arinfo[0]['open_h_fri_end2']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_fri_end2'];
													 }  ?></td>
    </tr>
    <tr>
      <td bgcolor="#F5F5F5">Saturday</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_sat_start']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_sat_start']));
													if($arinfo[0]['open_h_sat_end']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_sat_end']));
											}else
											{
											echo $arinfo[0]['open_h_sat_start'];
													if($arinfo[0]['open_h_sat_end']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_sat_end'];
													 }  ?>
        &nbsp;&nbsp;</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_sat_start2']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_sat_start2']));
													if($arinfo[0]['open_h_sat_end2']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_sat_end2'])); 
											}else
											{echo $arinfo[0]['open_h_sat_start2'];
													if($arinfo[0]['open_h_sat_end2']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_sat_end2']; 
													 } ?></td>
    </tr>
    <tr>
      <td bgcolor="#F5F5F5"> Sunday</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_sun_start']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_sun_start']));
													if($arinfo[0]['open_h_sun_end']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_sun_end']));
											}else
											{
											echo $arinfo[0]['open_h_sun_start'];
													if($arinfo[0]['open_h_sun_end']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_sun_end'];}  ?>
        &nbsp;&nbsp;</td>
      <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_sun_start2']!="Closed For the day")
											{
											echo date('g:i a ', strtotime($arinfo[0]['open_h_sun_start2']));
													if($arinfo[0]['open_h_sun_end2']!=""){ echo "-";}
													 echo date('g:i a ', strtotime($arinfo[0]['open_h_sun_end2']));
											}else
											{echo $arinfo[0]['open_h_sun_start2'];
													if($arinfo[0]['open_h_sun_end2']!=""){ echo "-";}
													 echo $arinfo[0]['open_h_sun_end2'];
													 }  ?></td>
    </tr>
  </tbody>
</table>
