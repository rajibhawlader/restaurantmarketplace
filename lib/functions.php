<?php
function pagination($targetpage, $total_pages, $page, $limit){
// $total_pages
// $page 
// $limit
//$targetpage

$adjacents = 3;

	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage&page=$prev\">&laquo; previous</a>";
		else
			$pagination.= "<span class=\"disabled\">&laquo; previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage&page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&page=$next\">next &raquo;</a>";
		else
			$pagination.= "<span class=\"disabled\">next &raquo;</span>";
		$pagination.= "</div>\n";		
		
		
return $pagination;		

	} // end if

} // end func



function show_date($y_m_d=false, $date_format="d/m/Y", $timestamp=false){
	if(!$timestamp){
				
		if($y_m_d == false){
			$y_m_d = date("Y-m-d");		
			$ddata = explode('-', $data[0]);	
		}else{
			$data = explode(' ', $y_m_d);
			$ddata = explode('-', $data[0]);
			$tdata = explode(':', $data[1]);
			$tdata[0] = ($tdata[0] <= 0)?0:$tdata[0];
			$tdata[1] = ($tdata[1] <= 0)?0:$tdata[1];
			$tdata[2] = ($tdata[2] <= 0)?0:$tdata[2];
		}
		
		$date = date($date_format, mktime($tdata[0],$tdata[1],$tdata[2], $ddata[1], $ddata[2], $ddata[0]));
	}else{		
		$date = date($date_format, $y_m_d);
	}
	return $date;
}

function discount_price($rid, $category, $order_type=1, $item_price){
	global $db;
	$query = "SELECT * FROM discount_policy WHERE rid=\"$rid\" AND cat_id=$category AND order_type=$order_type  AND dicount_status = '1' AND '".date("Y-m-d")."' BETWEEN publish_date and expire_date";	
	$ref = $db->execQuery($query);
	$row = $db->row($ref);
	$discount_price = number_format( ($item_price * ($row['amount']/100) ) , 2, '.', '');
	
	return $discount_price;
}

function show_item_price($item_price, $discount_price=0){
	if($discount_price > 0){
		//echo "<span style='text-decoration:line-through;color:#cf2600;'><font color='#000000'>&pound; ".number_format($item_price, 2, '.', '')."</font></span><br />";
		echo "<span>&pound; ".number_format( ($item_price-$discount_price), 2, '.', '')."</span>";
	
	}else{
		echo "<span>&pound; ".number_format($item_price, 2, '.', '')."</span><br />";
	}
}

function order_id($id)
{
	$dn="";
	if(strlen($id)==1) { $dn="11111"; }
	elseif(strlen($id)==2) { $dn="1111"; }
	elseif(strlen($id)==3) { $dn="111"; }
	elseif(strlen($id)==4) { $dn="11"; }
	elseif(strlen($id)==5) { $dn="1"; }			
	return $dn.$id;
}




function restaurant_status($rids){
global $db;
putenv ('TZ=GMT');


$dayt=date('Y-m-d');
$dayt = strtotime($dayt);
$daytr = date('l', $dayt);
$order_date=date('Y-m-d');
$order_datel=date('Y-m-d');
$order_dateds=date('Y-m-d');
$order_datede=date('Y-m-d');
if($daytr=="Monday")
{
$dayconls="open_h_mon_start";
$dayconle="open_h_mon_end";
$dayconds="open_h_mon_start2";
$dayconde="open_h_mon_end2";
$delstime="open_h_mon_del";
}
else if($daytr=="Tuesday")
{
$dayconls="open_h_tue_start";
$dayconle="open_h_tue_end";
$dayconds="open_h_tue_start2";
$dayconde="open_h_tue_end2";
$delstime="open_h_tue_del";
}
else if($daytr=="Wednesday")
{
$dayconls="open_h_wed_start";
$dayconle="open_h_wed_end";
$dayconds="open_h_wed_start2";
$dayconde="open_h_wed_end2";
$delstime="open_h_wed_del";
}
else if($daytr=="Thursday")
{
$dayconls="open_h_thu_start";
$dayconle="open_h_thu_end";
$dayconds="open_h_thu_start2";
$dayconde="open_h_thu_end2";
$delstime="open_h_thu_del";
}
else if($daytr=="Friday")
{
$dayconls="open_h_fri_start";
$dayconle="open_h_fri_end";
$dayconds="open_h_fri_start2";
$dayconde="open_h_fri_end2";
$delstime="open_h_fri_del";
}
else if($daytr=="Saturday")
{
$dayconls="open_h_sat_start";
$dayconle="open_h_sat_end";
$dayconds="open_h_sat_start2";
$dayconde="open_h_sat_end2";
$delstime="open_h_sat_del";
}
else
{
$dayconls="open_h_sun_start";
$dayconle="open_h_sun_end";
$dayconds="open_h_sun_start2";
$dayconde="open_h_sun_end2";
$delstime="open_h_sun_del";
}


$datn=date('Y-m-d');
$sqlsed="Select $dayconls,$dayconle,$dayconds,$dayconde from timeschedule where rid='$rids'";
$resultsed=mysql_query($sqlsed);
$rowsed=mysql_fetch_array($resultsed);
$queryd = "SELECT * FROM delevary_policy where  rid='$rids'";
$resultd = $db->execQuery($queryd);
$arinfod= $db->resultArray($resultd);



putenv ('TZ=GMT'); 
$atimes= date('H:i');
$ostatus=0;


$order_date=date('Y-m-d');
$order_datel=date('Y-m-d');
$order_dateds=date('Y-m-d');
$order_datede=date('Y-m-d');

// $atimes=date('H:i');
$stime=strtotime("$order_date $atimes");
$stime=date('H:i',$stime);
$_SESSION['timeH']=$stime;

$dayt=$order_date;
$dayt = strtotime($dayt);
$daytr = date('l', $dayt);

if($daytr=="Monday")
{
$dayconls="open_h_mon_start";
$dayconle="open_h_mon_end";
$dayconds="open_h_mon_start2";
$dayconde="open_h_mon_end2";
$delstime="open_h_mon_del";
}
else if($daytr=="Tuesday")
{
$dayconls="open_h_tue_start";
$dayconle="open_h_tue_end";
$dayconds="open_h_tue_start2";
$dayconde="open_h_tue_end2";
$delstime="open_h_tue_del";
}
else if($daytr=="Wednesday")
{
$dayconls="open_h_wed_start";
$dayconle="open_h_wed_end";
$dayconds="open_h_wed_start2";
$dayconde="open_h_wed_end2";
$delstime="open_h_wed_del";
}
else if($daytr=="Thursday")
{
$dayconls="open_h_thu_start";
$dayconle="open_h_thu_end";
$dayconds="open_h_thu_start2";
$dayconde="open_h_thu_end2";
$delstime="open_h_thu_del";
}
else if($daytr=="Friday")
{
$dayconls="open_h_fri_start";
$dayconle="open_h_fri_end";
$dayconds="open_h_fri_start2";
$dayconde="open_h_fri_end2";
$delstime="open_h_fri_del";
}
else if($daytr=="Saturday")
{
$dayconls="open_h_sat_start";
$dayconle="open_h_sat_end";
$dayconds="open_h_sat_start2";
$dayconde="open_h_sat_end2";
$delstime="open_h_sat_del";
}
else
{
$dayconls="open_h_sun_start";
$dayconle="open_h_sun_end";
$dayconds="open_h_sun_start2";
$dayconde="open_h_sun_end2";
$delstime="open_h_sun_del";
}

$sqlsed="Select $dayconls,$dayconle,$dayconds,$dayconde from timeschedule where rid='$rids'";
$resultsed=mysql_query($sqlsed);
$rowsed=mysql_fetch_array($resultsed);

if($rowsed[$dayconls]!="Closed For the day" or $rowsed[$dayconds]!="Closed For the day")
{
$lst= strtotime("$order_datel $rowsed[$dayconls]:00");
$len=strtotime("$order_datel $rowsed[$dayconle]:00");

$firststart1=$rowsed[$dayconls];
$first=(explode(":",$firststart1));
$firststhour=$first[0];
$firststmin=$first[1];

//echo $firststhour."  ";
//echo $firststmin."  ";

$firsten=$rowsed[$dayconle];
$firstend=(explode(":",$firsten));
$firsrendhour= $firstend[0];
$firsrendmin= $firstend[1];

if($firsrendhour!="" && $firsrendhour!=":00" && $firsrendmin!="" && $firsrendmin!=":00")
{
	$lhour=$firsrendhour;
	$lasth=$firsrendhour;
	$lastm=$firsrendmin;
}

//echo $firsrendhour."  ";
//echo $firsrendmin."  ";

$secondst= $rowsed[$dayconds];
$secndst=(explode(":",$secondst));
$secondstarthour= $secndst[0];
$secondstartmin= $secndst[1];

//echo $secondstarthour." SSH ";
//echo $secondstartmin."  SSM";

$seconden=$rowsed[$dayconde];
$secnded=(explode(":",$seconden));
$secondendhour= $secnded[0];
$secondendtmin= $secnded[1];

if($secondendhour!="" && $secondendhour!=":00" && $secondendtmin!="" && $secondendtmin!=":00")
{
	$lhour=$secondendhour;
	if($secondendhour=="00") { $lasth="24"; }
	elseif($secondendhour=="01") { $lasth="25"; }
	elseif($secondendhour=="02") { $lasth="26"; }
	elseif($secondendhour=="03") { $lasth="27"; }
	else { $lasth=$secondendhour; }
	$lastm=$secondendtmin;
	$secondendhour=$lasth;
}

$atm=$atimes;
$atm= strtotime("$order_datede $atm");

$chkd=strtotime("$rowsed[$dayconds]:00");
$chkd1=strtotime("03:55:00"); 
$chkd2=strtotime("00:00:00");
if($chkd<=$chkd1 and $chkd>=$chkd2)
{
$order_dateds=date( "Y-m-d", strtotime( "$order_dateds +1 day" ) );

}
$dst= strtotime("$order_dateds $rowsed[$dayconds]:00");

$chkde=strtotime("$rowsed[$dayconde]:00");
$chkde1=strtotime("03:55:00");
$chkde2=strtotime("00:00:00");
if($chkde<=$chkde1 and $chkde>=$chkde2)
{
$order_datede=date( "Y-m-d", strtotime( "$order_datede +1 day" ) );
}
$den=strtotime("$order_datede $rowsed[$dayconde]:00");

$ckdate=strtotime("$atimes");
$ckdate1=strtotime("03:55:00");
$ckdate2=strtotime("00:00:00");
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
$chkd1=strtotime("03:55:00"); 
$chkd2=strtotime("00:00:00");
if($chkd<=$chkd1 and $chkd>=$chkd2)
{
$order_dateds=date( "Y-m-d", strtotime( "$order_dateds +1 day" ) );

}

$dst= strtotime("$order_dateds $rowsed[$dayconds]:00");

$chkde=strtotime("$rowsed[$dayconde]:00");
$chkde1=strtotime("03:55:00");
$chkde2=strtotime("00:00:00");
if($chkde<=$chkde1 and $chkde>=$chkde2)
{
$order_datede=date( "Y-m-d", strtotime( "$order_datede +1 day" ) );
}
$den=strtotime("$order_datede $rowsed[$dayconde]:00");

$ckdate=strtotime("$atimes");
$ckdate1=strtotime("03:55:00");
$ckdate2=strtotime("00:00:00");
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

return $ostatus;

} // end func



?>