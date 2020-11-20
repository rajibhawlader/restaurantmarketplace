<?php
ob_start();
session_start();
require_once('lib/class.Db.php');	
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
require_once('lib/functions.php');
$action = trim($_REQUEST["action"]);
switch($action){
	case "rating": $result = doRating(); echo $result; break; 
	
	
}

function doRating(){
global $db;	
	$rid = (int)$_REQUEST["rid"];
	$orid = (int)$_REQUEST["orid"];	
	$rate = (int)$_REQUEST["rate"];	
	$type = $_REQUEST["type"]."_rating";		
	if($_SESSION["customers_id"] < 1){
		echo " Please login ";
	}else{
		$sql = "SELECT * FROM ratings where customer_id=".$_SESSION["customers_id"]." AND rid=".$rid." AND orid=".$orid;
		$db->execQuery($sql);
		$ratings = $db->row();
		if($ratings) {			
			$sql = "UPDATE ratings SET $type=".$rate." WHERE customer_id=".$_SESSION["customers_id"]." AND rid=".$rid." AND orid=".$orid;		
			$db->execQuery($sql);
			$msg = " Rating posted successfully. ";	
			$css =  "<style>#".$_REQUEST["type"]." {background-position:0 -". ($rate*16) ."px;}</style>";		
		}else{
			$sql = "INSERT INTO ratings SET customer_id=".$_SESSION["customers_id"].", rid=".$rid.", orid=".$orid.", $type=".$rate;		
			$db->execQuery($sql);
			$msg = " Rating posted successfully. ";						
			$css =  "<style>#".$_REQUEST["type"]." {background-position:0 -". ($rate*16) ."px;}</style>";		
		}
	}

return $css.$msg;
}



?>