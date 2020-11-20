<?php
require_once('lib/class.Db.php');
require_once('config.php');
$address= array();
$radious=array();
$db = new Db(hostname, username, password, databasename);
$rids=$_SESSION['resid'];
$db = new Db(hostname, username, password, databasename);

$query = "SELECT * FROM resturant   WHERE rid=".$_REQUEST['id'];
$result = $db->execQuery($query);
$arinfo2 = $db->resultArray(); 

$query = "SELECT * FROM delivery_area   WHERE rid=".$_REQUEST['id'];
$result = $db->execQuery($query);
$arinfo = $db->resultArray();      
$query = "SELECT radious FROM delevary_policy   WHERE rid=".$_REQUEST['id'];
$result = $db->execQuery($query);
$radious = $db->resultArray(); 
$restaurant_postcode = explode(" ", $arinfo2[0]['zipcode']); 
$arinfo[] = array('post_code'=>trim($restaurant_postcode[0]), "delivery_radius"=>$radious[0]["radious"]);


foreach($arinfo as $value)
	{
	if($value['delivery_radius']==NULL){$value['delivery_radius']=$radious[0]['radious'];}
$address[]=array('post_code'=>$value['post_code'],'radius'=>$value['delivery_radius']);

}

$count=count($address);

$address=json_encode($address);
$resturent_zipcode=$arinfo2[0]['zipcode'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Delivery Area with radious</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false&amp;key=<?php echo GOOGLE_MAP_API_KEY;?>"></script>
    <script type="text/javascript">

    var map = null;
    var geocoder = null;
   var zipcode;
   var totalzipcode;
    var zip;
    var r;
    var t=0;
    var radius=0;
    function initialize() {
      if (GBrowserIsCompatible()) {
      	totalzipcode=<?php echo $count;?>;
      	 zipcode=<?php echo $address;?>;

        map = new GMap2(document.getElementById("map_canvas"));

        map.addControl(new GLargeMapControl3D());
		map.addControl(new GMenuMapTypeControl());
     geocoder = new GClientGeocoder();

       geocoder.getLocations(zipcode[totalzipcode-1].post_code, setViewPoint);      
   
   for( var i in zipcode)
   		{
			
			window.zip=zipcode[i].post_code;
			window.r=zipcode[i].radius;
			geocoder.getLocations(zipcode[i].post_code, addAddressToMap);
			
 		}
      }
    }
    
 function setViewPoint(response)
    	{
    		//alert(response.Status.code);
    		if (!response || response.Status.code != 200) {
    		alert("\"" + window.zip + "\" not found");
  		} 
  else {
  		 place = response.Placemark[0];
    	 point = new GLatLng(place.Point.coordinates[1],
                       place.Point.coordinates[0]);
  		 map.setCenter(point, 11);		 
		 map.addOverlay(new GMarker(point));
		}
	}
  
function addAddressToMap(response) {
	radius=0;
  	radius = zipcode[t].radius;
	t=t+1;  
	
  	if (!response || response.Status.code != 200) {
    	//alert("\"" + window.zip + "\" not found");
    	response=null;
  	} else {
  	    	
    place = response.Placemark[0];
    point = new GLatLng(place.Point.coordinates[1],
                       place.Point.coordinates[0]);
  
  
      map.addOverlay(new GMarker(point));
 		var center = new GLatLng(place.Point.coordinates[1],place.Point.coordinates[0]);
		var radius = radius * 1.609344;
		
		// The offset creates an, ummm... offset around the center to help build the polygon
		var latOffset = 0.01;
		var lonOffset = 0.01;
		var latConv = center.distanceFrom(new GLatLng(center.lat()+0.1, center.lng()))/100;
    	var lngConv = center.distanceFrom(new GLatLng(center.lat(), center.lng()+0.1))/100; 
		
		// nodes = number of points to create polygon
		var nodes = 40;
		
		// Create an array of points
        var points = [];
		
		// set the amount of steps from node
        var step = parseInt(360/nodes);
        
		// the for loop creates a series of points that define the circle, counting by the amount of steps, by 9 in the case of 40 nodes
		for(var i=0; i<=360; i+=step){
			var point1 = new GLatLng(center.lat() + (radius / latConv * Math.cos(i * Math.PI / 180)), 
						center.lng() + (radius / lngConv * Math.sin(i * Math.PI / 180)));
			// push "point" onto the points array 
			
			points.push(point1);
        }//end for statement
		
		// GPolygon creates a polygon from an array of vertices, in this example, points is our array
		// GPolygon(array of points, stroke color, stroke weight(from 0-1), stroke opacity(from 0-1), fill color, fill opacity (from 0-1))
		var polygon = new GPolygon(points, "#000000", 1, 1, "#CC99FF", 0.3);
    	map.addOverlay(polygon);
    
      
  }
}

    </script>
  </head>
  <body onload="initialize()" onunload="GUnload()" style="font-family: Arial;border: 0 none;">
   	<p style="font-weight:bold">Delivery Area of This Restaurant</p>
       <div id="map_canvas" style="width: 600px; height: 400px"></div>
    <link href="../style.css" rel="stylesheet" type="text/css">
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#C4C4C4" class="bodytext">
  <tbody>
    <tr>      
      <td width="10%" bgcolor="#DFDFDF" class="nrmlink">S/N</td>
      <td  width="30%" bgcolor="#DFDFDF" class="nrmlink">Area</td>
      <td  width="30%"bgcolor="#DFDFDF" class="nrmlink">Delivery Radious</td>
    </tr>
	<?php foreach($arinfo as $k=>$data){?>
    <tr>
      <td bgcolor="#F5F5F5"><?=($k+1)?></td>
      <td bgcolor="#F5F5F5" ><?=$data['post_code']?></td>
      <td bgcolor="#F5F5F5" ><?=$data['delivery_radius']?></td>
    </tr> 
	<?php } ?>
  </tbody>
</table>
  </body>
</html>
  
  	


<!-------------------------------------------------------------------->
