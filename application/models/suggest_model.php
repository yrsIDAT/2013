<?php
class Suggest_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->library('MikeScore');
		$this->radius = 30;
	}

	public function makeSuggestion($city,$lat,$lon,$genScore=TRUE)
	{
		//$weather = $this->getWeather($city);

		$places = $this->staticPlaces($lat,$lon);
		$b = array();
		foreach($places as $place):
			$place->distance = $this->calculateDistance($lat,$lon,$place->lat,$place->lon);
			$place->distance_adjusted = ($place->distance/1000) / $this->radius;
			unset($place->source);
			unset($place->expiry);
			$place->score = NULL;

			if($place->type == 2 && isset($place->postcode) && $place->postcode != "") {
				// find movie times
				$place->postcode = str_replace(" ","",$place->postcode);
				$url1 = 'http://moviesapi.herokuapp.com/cinemas/find/' . $place->postcode;
				$json = json_decode(file_get_contents($url1));
				$cinema = $json[0];
				if(isset($cinema->venue_id)) {
					$url2 = 'http://moviesapi.herokuapp.com/cinemas/' .  $cinema->venue_id . '/showings';
					$json2 = json_decode(file_get_contents($url2));
					$place->showings = $json2;
				}
				
			}


			$b[] = $place;

		endforeach;
		$places = $b;

		/*$conditions = (object) array(
			'weather'=>$weather,
			'_time' => (float)date("G", time()) + ((float)date("i", time()))/60
			);*/

		// process scores
		/*if($genScore == TRUE) {
			$suggestions = $this->mikescore->calculateScores($places,$conditions);
			/ sort them
			usort($suggestions, function($a, $b) {
			    if( $a->score > $b->score) {
			    	return 1;
			    } else {
			    	return 0;
			    }
			});
		} */

		return $b;
	}

	private function getWeather($city) {
		$query = $this->db->get_where('weather', array('city' => $city));
		return $query->row_object();
	}

	public function staticPlaces($lat,$lon) {
		$bounding_box = $this->getBoundingBox($lat,$lon,$this->radius);
		$this->db->where('lat >=', $bounding_box[0]);
		$this->db->where('lat <=', $bounding_box[1]);
		$this->db->where('lon >=', $bounding_box[2]);
		$this->db->where('lon <=', $bounding_box[3]);
		$query =$this->db->get('places');
		$result = $query->result_object();
		
		return $result;
	}

	private function calculateDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
	{
		// STOLEN FROM http://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * $earthRadius;
	}

	function getBoundingBox($lat_degrees,$lon_degrees,$distance_in_km) {
		// STOLEN from  http://stackoverflow.com/questions/2628039/php-library-calculate-a-bounding-box-for-a-given-lat-lng-location
	    $radius = 6371; // of earth in miles

	    // bearings - FIX   
	    $due_north = deg2rad(0);
	    $due_south = deg2rad(180);
	    $due_east = deg2rad(90);
	    $due_west = deg2rad(270);

	    // convert latitude and longitude into radians 
	    $lat_r = deg2rad($lat_degrees);
	    $lon_r = deg2rad($lon_degrees);

	    // find the northmost, southmost, eastmost and westmost corners $distance_in_km away
	    // original formula from
	    // http://www.movable-type.co.uk/scripts/latlong.html

	    $northmost  = asin(sin($lat_r) * cos($distance_in_km/$radius) + cos($lat_r) * sin ($distance_in_km/$radius) * cos($due_north));
	    $southmost  = asin(sin($lat_r) * cos($distance_in_km/$radius) + cos($lat_r) * sin ($distance_in_km/$radius) * cos($due_south));

	    $eastmost = $lon_r + atan2(sin($due_east)*sin($distance_in_km/$radius)*cos($lat_r),cos($distance_in_km/$radius)-sin($lat_r)*sin($lat_r));
	    $westmost = $lon_r + atan2(sin($due_west)*sin($distance_in_km/$radius)*cos($lat_r),cos($distance_in_km/$radius)-sin($lat_r)*sin($lat_r));


	    $northmost = rad2deg($northmost);
	    $southmost = rad2deg($southmost);
	    $eastmost = rad2deg($eastmost);
	    $westmost = rad2deg($westmost);

	    // sort the lat and long so that we can use them for a between query        
	    if ($northmost > $southmost) { 
	        $lat1 = $southmost;
	        $lat2 = $northmost;

	    } else {
	        $lat1 = $northmost;
	        $lat2 = $southmost;
	    }


	    if ($eastmost > $westmost) { 
	        $lon1 = $westmost;
	        $lon2 = $eastmost;

	    } else {
	        $lon1 = $eastmost;
	        $lon2 = $westmost;
	    }

	    return array($lat1,$lat2,$lon1,$lon2);
	}

}
