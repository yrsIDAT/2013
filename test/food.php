<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('pdo.php');
header('Content-Type: text/html; charset=utf-8');
// get places from foursquare

// api url
$url = 'https://api.foursquare.com/v2/venues/search';
$id = ""; $key = ""; //hidden
$key = "?v=20130805&client_id=$id&client_secret=$key&";
$query = 'll=50.3964,-4.1386&radius=10000&categoryId=4d4b7105d754a06374d81259&intent=browse';
$furl = $url . $key . $query;
echo $furl;

$json = file_get_contents($furl);

$array = json_decode($json);
$code = $array->meta->code;
if($code == 200) {
	foreach($array->response->venues as $place) {
		$cat = $place->categories[0]->name;
		$subtype = ($place->categories[0]->name);
		if($subtype == 'Café') { $subtype = 'Cafe';}	
		if($subtype == 'Cafe') {
			echo '<pre>';
			print_r($place);
			echo '</pre>';

			  $stmt = $db->prepare('INSERT INTO places (type,subtype,title,lat,lon,source,sourceid,fetchdate,expiry) VALUES(:type,:subtype,:title,:lat,:lon,:source,:sourceid,:fetchdate,:expiry)');
			  $stmt->bindValue(':type', 3);
			  $stmt->bindParam(':title', $place->name);
			  $stmt->bindParam(':lat',$place->location->lat);
			  $stmt->bindParam(':lon',$place->location->lng);
			  $stmt->bindValue(':source','foursquare');
			  $stmt->bindValue(':sourceid',$place->id);
			  $stmt->bindParam(':fetchdate', date("Y-m-d H:i:s"));
			  $stmt->bindValue(':subtype',$subtype);
			  $stmt->bindValue(':expiry', date("Y-m-d H:i:s",(time()+(3600*7))));
			  try {
			  	$stmt->execute();
			} 
			catch(Exception $e) {
				echo 'error';
			}
		}
	}
}

?>