                                      <?php
										require_once('../lib/class.Db.php');
										require_once('../config.html');
										$db = new Db(hostname, username, password, databasename);
                                      	$query = "SELECT * FROM timeschedule   WHERE rid=".$_REQUEST['id'];
     					               	$result = $db->execQuery($query);
                    					$arinfo = $db->resultArray();                    					
                                      ?>
<link href="../style.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#C4C4C4" class="bodytext">
                                              <tbody>
                                                <tr>
                                                  <td colspan="2" bgcolor="#DFDFDF" class="nrmlink">Opening Hours</td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#F5F5F5">Monday</td>
                                                  <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_mon_start']!='' and $arinfo[0]['open_h_mon_end']!='')echo $arinfo[0]['open_h_mon_start'].' - '.$arinfo[0]['open_h_mon_end']; ?>&nbsp;&nbsp; 
<?php if($arinfo[0]['open_h_mon_start2']!='' and $arinfo[0]['open_h_mon_end2']!='') echo $arinfo[0]['open_h_mon_start2'].' - '.$arinfo[0]['open_h_mon_end2']; ?></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#F5F5F5">Tuesday</td>
                                                  <td bgcolor="#F5F5F5" >
                                                  <?php 
                                                  if($arinfo[0]['open_h_tue_start']!='' or $arinfo[0]['open_h_tue_end']!='')
                                                  echo $arinfo[0]['open_h_tue_start'].' - '.$arinfo[0]['open_h_tue_end']; 
                                                  else
                                                  echo 'Closed';                                                                                                   
                                                  echo '&nbsp;&nbsp;';
                                                  if($arinfo[0]['open_h_tue_start2']!='' or $arinfo[0]['open_h_tue_end2']!='') 
                                                  echo $arinfo[0]['open_h_tue_start2'].' - '.$arinfo[0]['open_h_tue_end2']; 
                                                  else
                                                  echo 'Closed'
                                                    ?></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#F5F5F5">Wednesday</td>
                                                  <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_mon_start']!='' and $arinfo[0]['open_h_mon_end']!='')echo $arinfo[0]['open_h_mon_start'].' - '.$arinfo[0]['open_h_mon_end']; ?>
                                                    &nbsp;&nbsp;
                                                    <?php if($arinfo[0]['open_h_mon_start2']!='' and $arinfo[0]['open_h_mon_end2']!='') echo $arinfo[0]['open_h_mon_start2'].' - '.$arinfo[0]['open_h_mon_end2']; ?></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#F5F5F5">Thursday</td>
                                                  <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_mon_start']!='' and $arinfo[0]['open_h_mon_end']!='')echo $arinfo[0]['open_h_mon_start'].' - '.$arinfo[0]['open_h_mon_end']; ?>
                                                    &nbsp;&nbsp;
                                                    <?php if($arinfo[0]['open_h_mon_start2']!='' and $arinfo[0]['open_h_mon_end2']!='') echo $arinfo[0]['open_h_mon_start2'].' - '.$arinfo[0]['open_h_mon_end2']; ?></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#F5F5F5">Friday</td>
                                                  <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_mon_start']!='' and $arinfo[0]['open_h_mon_end']!='')echo $arinfo[0]['open_h_mon_start'].' - '.$arinfo[0]['open_h_mon_end']; ?>
                                                    &nbsp;&nbsp;
                                                    <?php if($arinfo[0]['open_h_mon_start2']!='' and $arinfo[0]['open_h_mon_end2']!='') echo $arinfo[0]['open_h_mon_start2'].' - '.$arinfo[0]['open_h_mon_end2']; ?></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#F5F5F5">Saturday</td>
                                                  <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_mon_start']!='' and $arinfo[0]['open_h_mon_end']!='')echo $arinfo[0]['open_h_mon_start'].' - '.$arinfo[0]['open_h_mon_end']; ?>
                                                    &nbsp;&nbsp;
                                                    <?php if($arinfo[0]['open_h_mon_start2']!='' and $arinfo[0]['open_h_mon_end2']!='') echo $arinfo[0]['open_h_mon_start2'].' - '.$arinfo[0]['open_h_mon_end2']; ?></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#F5F5F5"> Sunday</td>
                                                  <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_mon_start']!='' and $arinfo[0]['open_h_mon_end']!='')echo $arinfo[0]['open_h_mon_start'].' - '.$arinfo[0]['open_h_mon_end']; ?>
                                                    &nbsp;&nbsp;
                                                    <?php if($arinfo[0]['open_h_mon_start2']!='' and $arinfo[0]['open_h_mon_end2']!='') echo $arinfo[0]['open_h_mon_start2'].' - '.$arinfo[0]['open_h_mon_end2']; ?></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#F5F5F5"><span style="padding: 10px 0pt 0pt; width: 100px;"><span>Bank Holidays</span></span></td>
                                                  <td bgcolor="#F5F5F5" ><?php if($arinfo[0]['open_h_mon_start']!='' and $arinfo[0]['open_h_mon_end']!='')echo $arinfo[0]['open_h_mon_start'].' - '.$arinfo[0]['open_h_mon_end']; ?>
&nbsp;&nbsp;
<?php if($arinfo[0]['open_h_mon_start2']!='' and $arinfo[0]['open_h_mon_end2']!='') echo $arinfo[0]['open_h_mon_start2'].' - '.$arinfo[0]['open_h_mon_end2']; ?></td>
                                                </tr>
                                              </tbody>
                                            </table>