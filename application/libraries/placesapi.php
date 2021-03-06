<?php
	class PlacesAPI
	{
		private $fscid;
		private $fssecret;
		private $lat;
		private $lon;
		private $quickcache;
		function __construct()
		{
			
			$CI =& get_instance();
    		$CI->config->load('apikeys');
			//$CI->load->library('quickcache');
			//$quickcache = $CI->quickcache;
    		$this->fscid = $CI->config->item('fscid');
			$this->fssecret = $CI->config->item('fssecret');
		}

		public function getCity($lat,$lon) {
			$this->lat = $lat;
			$this->lon = $lon;

			$all = array();

			$beaches = $this->getBeaches();
			$all = array_merge($all,$beaches);

			$cinemas = $this->getCinemas();
			$all = array_merge($all,$cinemas);

			$stadiums = $this->getStadiums();
			$all = array_merge($all,$stadiums);

			$cafes = $this->getFood();
			$all = array_merge($all,$cafes);

			$malls = $this->quickCat('4bf58dd8d48988d1fd941735',5);
			$all = array_merge($all,$malls);

			$recordShops = $this->quickCat('4bf58dd8d48988d10d951735',6);
			$all = array_merge($all,$recordShops);

			$bookShops = $this->quickCat('4bf58dd8d48988d114951735',7);
			$all = array_merge($all,$bookShops);

			$videoGameShops = $this->quickCat('4bf58dd8d48988d10b951735',8);
			$all = array_merge($all,$videoGameShops);

			$aquariums = $this->quickCat('4fceea171983d5d06c3e9823',9);
			$all = array_merge($all,$aquariums);

			$museums = $this->quickCat('4bf58dd8d48988d181941735',10);
			$all = array_merge($all,$museums);

			$zoos = $this->quickCat('4bf58dd8d48988d17b941735',11);
			$all = array_merge($all,$zoos);

			$bowlingAlley = $this->quickCat('4bf58dd8d48988d1e4931735',12);
			$all = array_merge($all,$bowlingAlley);

			$waterPark = $this->quickCat('4bf58dd8d48988d193941735',13);
			$all = array_merge($all,$waterPark);

			/*$artGallery = $this->quickCat('4bf58dd8d48988d1e2931735',14);
			$all = array_merge($all,$artGallery);*/

			$themeParks = $this->quickCat('4bf58dd8d48988d182941735',15);
			$all = array_merge($all,$themeParks);

			$parks = $this->quickCat('4bf58dd8d48988d163941735',16);
			$all = array_merge($all,$parks);

			$scenicPoint = $this->quickCat('4bf58dd8d48988d165941735',17);
			$all = array_merge($all,$scenicPoint);

			$musicVenues = $this->quickCat('4bf58dd8d48988d1e5931735',18);
			$all = array_merge($all,$musicVenues);
//$newAll = $this->getImage($all);
			//$allPlaces = array_merge($beaches,$cinemas,$stadiums,$cafes,$malls,$recordShops,$bookShops,$videoGameShops);
			
			return $all;
		}

		private function getImage($all) {
			echo 'images </br>';

			for($i = 0; $i <= count($all); $i++ ) {
				$sid = $all[$i]['sourceid'];
				//https://api.foursquare.com/v2/venues/VENUE_ID/photos
				
				$url = 'https://api.foursquare.com/v2/venues/' . $sid . '/photos';
				$key = '?v=20130805&client_id='.$this->fscid.'&client_secret='. $this->fssecret;
				$query = '';
				$full_url = $url . $key . $query;

				$json = file_get_contents($full_url);
				$array = json_decode($json);
				//echo "<pre>";print_r($array);echo"</pre>";
				$images = array();
				foreach($array->response->photos->items as $p) {
					$images[] = $p->prefix . 'width'. '300'. $p->suffix;
				}
				if($array->meta->code == 200) {
					$all[$i]['images'] = implode($images,",");
				}
				
				//echo 'images loaded ' . $i . ' / ' . count($all) . '<br>';
			}
			return $all;
		}

		private function getBeaches() {
			$url = 'https://api.foursquare.com/v2/venues/search';
			$key = '?v=20130805&client_id='.$this->fscid.'&client_secret='. $this->fssecret;
			$query = '&ll='.$this->lat.','.$this->lon.'&radius=20000&categoryId=4bf58dd8d48988d1e2941735&intent=browse';
			$full_url = $url . $key . $query;

			$json = file_get_contents($full_url);
			$array = json_decode($json);
			$code = $array->meta->code;
			$aplaces = array();
			if($code == 200) {
				foreach($array->response->venues as $place) {

					$cat = $place->categories[0]->name;
					if($cat == 'Beach') {
						$aplace = array();
						$aplace['title'] = $place->name;
						$aplace['type'] = 1;
						$aplace['url'] = $place->canonicalUrl;
						$aplace['lat'] = $place->location->lat;
						$aplace['lon'] = $place->location->lng;
						$aplace['source'] = 'foursquare';
						$aplace['sourceid'] = $place->id;
						if(isset($place->location->postalCode)) {
								$aplace['postcode'] = $place->location->postalCode;
							}
						$aplaces[] = $aplace;
					}
				}
			}

			return $aplaces;
		}


		private function getCinemas() {
			$url = 'https://api.foursquare.com/v2/venues/search';
			$key = '?v=20130805&client_id='.$this->fscid.'&client_secret='. $this->fssecret;
			$query = '&ll='.$this->lat.','.$this->lon.'&radius=10000&query=cinema&intent=browse';
			$full_url = $url . $key . $query;

			$json = file_get_contents($full_url);
			$array = json_decode($json);
			$code = $array->meta->code;
			$aplaces = array();
			if($code == 200) {

				foreach($array->response->venues as $place) {
					
					if(isset($place->categories[0]->name)) {
						$cat = $place->categories[0]->name;
						if(strpos($cat,"Movie Theater") || $cat == 'Movie Theater') {
							$aplace = array();
							$aplace['title'] = $place->name;
							$aplace['type'] = 2;
							$aplace['url'] = $place->canonicalUrl;
							$aplace['lat'] = $place->location->lat;
							$aplace['lon'] = $place->location->lng;
							$aplace['source'] = 'foursquare';
							$aplace['sourceid'] = $place->id;
							if(isset($place->location->postalCode)) {
								$aplace['postcode'] = $place->location->postalCode;
							}
							$aplaces[] = $aplace;
						}
					}
				}
			}

			return $aplaces;
		}

		private function getStadiums() {
			$url = 'https://api.foursquare.com/v2/venues/search';
			$key = '?v=20130805&client_id='.$this->fscid.'&client_secret='. $this->fssecret;
			$query = '&ll='.$this->lat.','.$this->lon.'&radius=10000&categoryId=4bf58dd8d48988d184941735&intent=browse';
			$full_url = $url . $key . $query;
			//echo $full_url;
			//die();
			$json = file_get_contents($full_url);
			$array = json_decode($json);
			$code = $array->meta->code;
			$aplaces = array();
			if($code == 200) {
				foreach($array->response->venues as $place) {
					$cat = $place->categories[0]->name;
					$subtype = ($place->categories[0]->name);
					
			
					
						$aplace = array();
						$aplace['title'] = $place->name;
						$aplace['type'] = 4;
						$aplace['url'] = $place->canonicalUrl;
						$aplace['lat'] = $place->location->lat;
						$aplace['lon'] = $place->location->lng;
						$aplace['source'] = 'foursquare';
						$aplace['sourceid'] = $place->id;
						$aplace['subtype'] = $subtype;
						$aplaces[] = $aplace;
					
				}
			}

			return $aplaces;
		}

		private function getFood() {
			$url = 'https://api.foursquare.com/v2/venues/search';
			$key = '?v=20130805&client_id='.$this->fscid.'&client_secret='. $this->fssecret;
			$query = '&ll='.$this->lat.','.$this->lon.'&radius=10000&categoryId=4d4b7105d754a06374d81259&intent=browse';
			$full_url = $url . $key . $query;

			$json = file_get_contents($full_url);
			$array = json_decode($json);
			$code = $array->meta->code;
			$aplaces = array();
			if($code == 200) {
				foreach($array->response->venues as $place) {
					$cat = $place->categories[0]->name;
					$subtype = ($place->categories[0]->name);
					if($subtype == 'Café') { $subtype = 'Cafe';}	
			
					
						$aplace = array();
						$aplace['title'] = $place->name;
						$aplace['type'] = 3;
						$aplace['url'] = $place->canonicalUrl;
						$aplace['lat'] = $place->location->lat;
						$aplace['lon'] = $place->location->lng;
						$aplace['source'] = 'foursquare';
						$aplace['sourceid'] = $place->id;
						$aplace['subtype'] = $subtype;
						$aplaces[] = $aplace;
					
				}
			}

			return $aplaces;
		}



		private function quickCat($catID,$typeNo) {
			$url = 'https://api.foursquare.com/v2/venues/search';
			$key = '?v=20130805&client_id='.$this->fscid.'&client_secret='. $this->fssecret;
			$query = '&ll='.$this->lat.','.$this->lon.'&radius=5000&categoryId='.$catID.'&intent=browse';
			$url1 = $url . $key . $query;

			$json = file_get_contents($url1);
			/*if($this->quickcache->isCached($url1)) {
					$json = json_decode($this->quickcache->retrieveFromCache($url1));
				} else {
					$contents = file_get_contents($url1);
					$json = json_decode($contents);
					$this->quickcache->addToCache($url1,$contents);
				}*/

			$array = json_decode($json);
			$code = $array->meta->code;
			$aplaces = array();
			if($code == 200) {
				foreach($array->response->venues as $place) {
					$cat = $place->categories[0]->name;
					$subtype = ($place->categories[0]->name);
					
						$aplace = array();
						$aplace['title'] = $place->name;
						$aplace['type'] = $typeNo;
						$aplace['url'] = $place->canonicalUrl;
						$aplace['lat'] = $place->location->lat;
						$aplace['lon'] = $place->location->lng;
						$aplace['source'] = 'foursquare';
						$aplace['sourceid'] = $place->id;
						if(isset($place->location->postalCode)) {
								$aplace['postcode'] = $place->location->postalCode;
							}
						$aplaces[] = $aplace;
					
				}
			}

			return $aplaces;
		}

	}
?>