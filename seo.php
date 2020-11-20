<?php
require_once('lib/class.Db.php');
require_once('config.php');
$db = new Db(hostname, username, password, databasename);
$sql = "SELECT rid, rname FROM resturant WHERE aactive='1'";
$db->execQuery($sql);
$list = $db->resultArray();
$host = "http://www.indianfoodsonline.co.uk";

xmlSiteMap($host, $list);
urlListSiteMap();

function xmlSiteMap(){
global $host, $list;


$time =  date("Y-m-d\TH:i:sP");
$xml_top_common = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"><url><loc>$host/</loc><lastmod>$time</lastmod><priority>1.0</priority><changefreq>always</changefreq></url><url><loc>$host/about-us</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/contact-us</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/customer-registration</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/faq</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/help</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/index-action-forget_password</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/indian-curry-club</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/privacy-policy</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/restaurant-info</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/royalty-points</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/sign-up</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/site-map</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/terms-of-use</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url>";

foreach($list as $data){
$loc = "$host/".str_replace(" ", "-", $data["rname"])."--".$data["rid"];
$body .= "<url><loc>$loc</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url>";
}

$xml_bottom_common = "<url><loc>$host/curry-blog/?cat=6</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?m=200906</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?m=200907</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?m=200908</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?p=10</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?p=17</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?p=27</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?p=29</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?p=3</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?p=35</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?p=39</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?p=42</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?page_id=13</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?page_id=2</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/wp-login.php</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/index-action-menu_list_collection-rid-87</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/index-action-menu_list-rid-87</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/index-action-menu_list_collection-rid-88</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/index-action-menu_list-rid-88</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/?cat=1</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/curry-blog/wp-login.php?action=lostpassword</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/index-action-restaurant_details_view-rid-87</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url><url><loc>$host/index-action-restaurant_details_view-rid-88</loc><lastmod>$time</lastmod><priority>0.5</priority><changefreq>always</changefreq></url></urlset>";


if($fp = fopen("sitemap.xml", "w+") ){
	fwrite($fp, $xml_top_common.$body.$xml_bottom_common);
	fclose($fp);
	}else{
		echo "<br />sitemap.xml is not created";
	}

}


function urlListSiteMap(){
global $host, $list;
	$links[] = array("url"=>"$host/");
	$links[] = array("url"=>"$host/about-us");
	$links[] = array("url"=>"$host/contact-us");
	$links[] = array("url"=>"$host/curry-blog");
	$links[] = array("url"=>"$host/customer-registration");
	$links[] = array("url"=>"$host/faq");
	$links[] = array("url"=>"$host/help");
	$links[] = array("url"=>"$host/index-action-forget_password");
	$links[] = array("url"=>"$host/indian-curry-club");
	$links[] = array("url"=>"$host/privacy-policy");
	$links[] = array("url"=>"$host/restaurant-info");
	$links[] = array("url"=>"$host/royalty-points");
	$links[] = array("url"=>"$host/sign-up");
	$links[] = array("url"=>"$host/site-map");
	$links[] = array("url"=>"$host/terms-of-use");
	
	foreach($list as $data){
	$loc = "$host/".str_replace(" ", "-", $data["rname"])."--".$data["rid"];
	$links[] = array("url"=>"$loc");
	}
	
	$links[] = array("url"=>"$host/curry-blog/?cat=5");
	$links[] = array("url"=>"$host/curry-blog/?cat=6");
	$links[] = array("url"=>"$host/curry-blog/?m=200906");
	$links[] = array("url"=>"$host/curry-blog/?m=200907");
	$links[] = array("url"=>"$host/curry-blog/?m=200908");
	$links[] = array("url"=>"$host/curry-blog/?p=10");
	$links[] = array("url"=>"$host/curry-blog/?p=17");
	$links[] = array("url"=>"$host/curry-blog/?p=27");
	$links[] = array("url"=>"$host/curry-blog/?p=29");
	$links[] = array("url"=>"$host/curry-blog/?p=3");
	$links[] = array("url"=>"$host/curry-blog/?p=35");
	$links[] = array("url"=>"$host/curry-blog/?p=39");
	$links[] = array("url"=>"$host/curry-blog/?p=42");
	$links[] = array("url"=>"$host/curry-blog/?page_id=13");
	$links[] = array("url"=>"$host/curry-blog/?page_id=2");
	$links[] = array("url"=>"$host/curry-blog/wp-login.php");
	$links[] = array("url"=>"$host/index-action-menu_list_collection-rid-87");
	$links[] = array("url"=>"$host/index-action-menu_list-rid-87");
	$links[] = array("url"=>"$host/index-action-menu_list_collection-rid-88");
	$links[] = array("url"=>"$host/index-action-menu_list-rid-88");
	$links[] = array("url"=>"$host/curry-blog/");
	$links[] = array("url"=>"$host/curry-blog/?cat=1");
	$links[] = array("url"=>"$host/curry-blog/wp-login.php?action=lostpassword");
	$links[] = array("url"=>"$host/index-action-restaurant_details_view-rid-87");
	$links[] = array("url"=>"$host/index-action-restaurant_details_view-rid-88");

foreach($links as $link){
	$link_text .= $link["url"]."\r\n";
}



if($fp = fopen("urllist.txt", "w+") ){
	fwrite($fp, $link_text);
	fclose($fp);
	}else{
		echo "<br />urllist.txt is not created";
	}

	
}

?>