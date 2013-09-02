<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('pdo.php');

// get places from foursquare

// api url
$url = 'https://api.foursquare.com/v2/venues/search';
$id = ""; $key = ""; //hidden
$key = "?v=20130805&client_id=$id&client_secret=$key&";
$query = 'll=50.3964,-4.1386&radius=10000&categoryId=4bf58dd8d48988d1e2941735&intent=browse';
$furl = $url . $key . $query;
echo $furl;

$json = file_get_contents($furl);
$array = json_decode($json);
$code = $array->meta->code;
if($code == 200) {
	foreach($array->response->venues as $place) {
		$cat = $place->categories[0]->name;
		if($cat == 'Beach') {
			echo '<pre>';
			print_r($place);
			echo '</pre>';

			  $stmt = $db->prepare('INSERT INTO places (type,title,lat,lon,source,sourceid,fetchdate,expiry) VALUES(:type,:title,:lat,:lon,:source,:sourceid,:fetchdate,:expiry)');
			  $stmt->bindValue(':type', 'beach');
			  $stmt->bindParam(':title', $place->name);
			  $stmt->bindParam(':lat',$place->location->lat);
			  $stmt->bindParam(':lon',$place->location->lng);
			  $stmt->bindValue(':source','foursquare');
			  $stmt->bindValue(':sourceid',$place->id);
			  $stmt->bindParam(':fetchdate', date("Y-m-d H:i:s"));
			  $stmt->bindParam(':expiry', date("Y-m-d H:i:s",(time()+(3600*7))));
			  $stmt->execute();
		}
	}
}

?>