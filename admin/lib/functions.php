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
	$query = "SELECT * FROM discount_policy WHERE rid=$rid AND cat_id=$category AND order_type=$order_type";	
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

function resturant_del($restaurant_id=false){
global $db;
	if($restaurant_id == false){return false;}
	
	$query = "SELECT r.*, u.* FROM resturant as r, userregistration as u WHERE r.userid=u.id AND rid=".$restaurant_id;	
	$db->execQuery($query);
	$info = $db->row();
	
	//print_r ($info); exit;
	remove_image("../".$info["logo"]);
	remove_image("../". str_replace('small_', 'large_', $info["logo"]) );
	for($i=1; $i<=8; $i++){  
		remove_image("../restaurant_image/large_".$info["image".$i]);
		remove_image("../restaurant_image/mid_".$info["image".$i]);
		remove_image("../restaurant_image/small_".$info["image".$i]);
	}
	
	
	// remove  delevary_policy
	$sql = "DELETE FROM delevary_policy WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
	
	// remove delivery_area
	$sql = "DELETE FROM delivery_area WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
	
	// remove discount_policy
	$sql = "DELETE FROM discount_policy WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
	
	// remove  ratings
	$sql = "DELETE FROM ratings WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
		
	// remove resturant_paymnt_method
	$sql = "DELETE FROM resturant_paymnt_method WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
	
	// remove resturant_policy
	$sql = "DELETE FROM resturant_policy WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
	
	// remove resturant_service
	$sql = "DELETE FROM resturant_service WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
	
	// remove timeschedule
	$sql = "DELETE FROM timeschedule WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
	
// DELETE MENU
	$query = "SELECT * FROM categories WHERE  rid=".$restaurant_id;	
	$db->execQuery($query);
	$categories = $db->resultArray();
	if($categories)
	foreach($categories as $category){
		$query = "SELECT * FROM product WHERE  categories_id=".$category['categories_id'];
		$db->execQuery($query);
		$products = $db->resultArray();
		if($products){
			foreach($products as $product){
				remove_image("../".$product["thumb_image"]);
				remove_image("../".$product["large_image"]);
			}
		}
		// remove product
		$sql = "DELETE FROM product WHERE categories_id =".$category['categories_id'];
		$db->execQuery($sql);
	}
	// remove product/food category
	$sql = "DELETE FROM categories WHERE rid=".$restaurant_id;
	$db->execQuery($sql);

// _DELETE ORDER HISTORY
	
	$query = "SELECT * FROM customer_order WHERE  rid=".$restaurant_id;	
	$db->execQuery($query);
	$orders = $db->resultArray();
	if($orders)
	foreach($orders as $order){
		// remove order_details
		$sql = "DELETE FROM order_details WHERE orid =".$order['orid'];
		$db->execQuery($sql);
	}
	// remove customer_order
	$query = "DELETE FROM customer_order WHERE  rid=".$restaurant_id;	
	$db->execQuery($query);	
	
	// remove order_statements
	$query = "DELETE FROM order_statements WHERE  rid=".$restaurant_id;	
	$db->execQuery($query);	
	
// _END DELETING ORER HISTORY	

	// remove  resturant
	$sql = "DELETE FROM resturant WHERE rid=".$restaurant_id;
	$db->execQuery($sql);
	
	// remove  userregistration
	$sql = "DELETE FROM userregistration WHERE id=".$info['userid'];
	$db->execQuery($sql);
return true;
}

function remove_image($path = false){
	if($path == false){return false;}
	
	if(!is_dir($path) && file_exists($path)){ 
		@unlink($path);
		//echo '<br />'.$path;
	}	
		
}


function customer_del($customers_id=false){
global $db;
	if($customers_id == false){return false;}
			
	// remove  customer
	$sql = "DELETE FROM customers WHERE customers_id=".$customers_id;
	$db->execQuery($sql);
	
	// remove  customer_address
	$sql = "DELETE FROM customer_address WHERE customers_id=".$customers_id;
	$db->execQuery($sql);
	
	$query = "SELECT * FROM customer_order WHERE  customers_id=".$customers_id;
	$db->execQuery($query);
	$orders = $db->resultArray();
		if($orders)
		foreach($orders as $order){
			// remove order_details
			$sql = "DELETE FROM order_details WHERE orid =".$order['orid'];
			$db->execQuery($sql);
		}
	
	// remove  customer_order
	$sql = "DELETE FROM customer_order WHERE customers_id=".$customers_id;
	$db->execQuery($sql);
		
return true;
}

function order_delete($orid=false){
global $db;
	if($orid == false){return false;}

	
	$query = "SELECT * FROM customer_order WHERE  orid=".$orid;
	$db->execQuery($query);
	$orders = $db->resultArray();
		if($orders)
		foreach($orders as $order){
			// remove order_details
			$sql = "DELETE FROM order_details WHERE orid =".$order['orid'];
			$db->execQuery($sql);
		}
	
	// remove  customer_order
	$sql = "DELETE FROM customer_order WHERE orid=".$orid;
	$db->execQuery($sql);
		
return true;
}

?>