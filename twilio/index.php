<?php
define("THINGS2DO", true);
$root=$_SERVER['DOCUMENT_ROOT'].'/';
include "$root/Site/backend/keystore.php";
include "$root/Site/backend/apikeys.php";
$GoogleApiKey = $key->getkey('googleapi');

function goo_gl_short_url($longUrl) {
    global $GoogleApiKey;
    $postData = array('longUrl' => $longUrl, 'key' => $GoogleApiKey);
    $jsonData = json_encode($postData);
    $curlObj = curl_init();
    curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url');
    curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
    //As the API is on https, set the value for CURLOPT_SSL_VERIFYPEER to false. This will stop cURL from verifying the SSL certificate.
    curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlObj, CURLOPT_HEADER, 0);
    curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
    curl_setopt($curlObj, CURLOPT_POST, 1);
    curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
    $response = curl_exec($curlObj);
    $json = json_decode($response);
    curl_close($curlObj);
    //print_r($json);
    return $json->id;
}

	header("content-type: text/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>";
if( strstr(strtolower($_REQUEST['Body']), 'i want to') ) {

    //Query here, then text back results
    $q = substr($_REQUEST['Body'], 9, 50);
    //echo $q;
    $lat="52.486242999999995";
    $lon="-1.8904010000000002";
    if (isset($_REQUEST['FromZip'])) {
        $postcode=$_REQUEST['FromZip'];
        $location=json_decode(file_get_contents("http://uk-postcodes.com/postcode/$postcode.json"));
        $lat=$location->geo->lat;
        $lon=$location->geo->lng;
    }
    $f=fopen('twilio.log', 'a');
    fwrite($f, print_r($_REQUEST, true));
    fclose($f);
    $queryUrl = "http://things2do.ws/server.php?query=" .urlencode($q)."&lat=$lat&lon=$lon";
    //echo $queryUrl;
    $content = file_get_contents($queryUrl);
    $json = json_decode($content);
    //print_r($json);
    if(count($json) >= 5) {
        $results = array_splice($json, 0,5);

        shuffle($results);
        $place = $results[0];
        $url = ($results[0]->url);
        //print_r($json);
        $shortUrl = goo_gl_short_url($url);

        // extra string
        $extra = '';
        if(isset($place->data->events)) {
            $extra = 'You could go to ' . $place->data->events[0]->title;
        }
        elseif(isset($place->data->product)) {
            $extra = 'You could buy ' . $place->data->product->title;
        }
        elseif($place->type == 3) {
            $extra = 'It\'s a ' . $place->subtype . '. ';
        }

        echo "<Sms>Why not try ".htmlentities($results[0]->title)."(".round(($results[0]->distance/1000),2)."km away)? ".htmlentities($extra)."  More info at $shortUrl</Sms>";
    }
    else {
        echo "<Sms>Sorry we could not find something to do!</Sms>";

    }
}
else {
    echo "<Sms>You can use things2do by texting:
I WANT TO, then what you want to do.
Then we'll ask you for your location :)</Sms>";
}
echo "</Response>";
?>