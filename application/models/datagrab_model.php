<?php
class Datagrab_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->library('WeatherApi');
		$this->load->library('quickcache');
		$this->load->library('PlacesApi');
	}

	public function completeRefresh()
	{
		$locations = array();
		$locations[] = array('plymouth',50.371389, -4.142222);
		$locations[] = array('birmingham',52.483056, -1.893611);


		foreach($locations as $location) {
		//	$this->refreshWeather($location[1],$location[2]);
			$this->refreshPlaces($location[1],$location[2]);
		}

		//$this->refreshProducts();
		//$this->refreshEvents();
	}

	private function refreshWeather($lat,$lon) {
		$weather = ($this->weatherapi->setCity($lat,$lon));
		$data = array(
			'lat' => $lat,
			'lon' => $lon,
			'city' => $weather[3],
			'sourceid' => 'met',
			'date' => date("Y-m-d"),
			'condition' => $weather[2],
			'precipitation' => $weather[0],
			'temp' => $weather[1] 
			);

		$query = $this->db->get_where('weather',array('city' => $weather[3]));

		if($query->num_rows() > 0) {
			// update 
			$row = $query->row_array();
			$this->db->where('id', $row['id']);
			$this->db->update('weather',$data);
		} else {
			// new city
			$this->db->insert('weather', $data);
		}
	}

	private function refreshPlaces($lat,$lon) {
		$places = $this->placesapi->getCity($lat,$lon);
		 $count = 0;
		foreach($places as $place) {
		$place['fetchdate'] =  date("Y-m-d H:i:s");
		$place['expiry'] = date("Y-m-d H:i:s",(time()+(3600*7)));

		//echo"<pre>";print_r($place);echo"</pre>";
			// check for exist
		
			$query = $this->db->get_where('places', array('sourceid'=>$place['sourceid']));
			$status = 'no';
			//echo $this->db->last_query();
			//echo $query->num_rows() ."<br>";
				if($query->num_rows() > 0) {

					$this->db->where('sourceid',$place['sourceid']);
					$this->db->update('places',$place);
					$status = 'update';
					$count++;
				} else {
					
					$this->db->insert('places',$place);
					$status = 'insert';
					$count++;
				}
			//echo $status . "<br>";
			//ob_flush();

		}
		echo $count . ' places updated <br>';

	}
	
	private function refreshProducts() {
		$products = array();
		$gamesUrl = 'http://www.amazon.co.uk/gp/rss/bestsellers/videogames/ref=zg_bs_videogames_rsslink';
		$games = $this->amazonRssToProducts($gamesUrl,8);
		$products = array_merge($products,$games);

		$booksUrl = 'http://www.amazon.co.uk/gp/rss/bestsellers/books/ref=zg_bs_books_rsslink';
		$books = $this->amazonRssToProducts($booksUrl,7);
		$products = array_merge($products,$books);

		$musicUrl = 'http://www.amazon.co.uk/gp/rss/bestsellers/music/ref=zg_bs_music_rsslink';
		$music = $this->amazonRssToProducts($musicUrl,6);
		$products = array_merge($products,$music);

		$this->db->truncate('products');
		foreach($products as $product) {
			$this->db->insert('products',$product);
		}
	}

	private function amazonRssToProducts($amazonurl,$typeno) {
		$xml = simplexml_load_file($amazonurl);
		$products = array();
		foreach($xml->channel->item as $product) {
			//echo "<pre>";
		
			$html = ((string)$product->description);
			$game = array();
			$game['title'] =(string)substr($product->title,4);
			$game['url'] = (string)$product->link;
			$doc = new DOMDocument();
		  $doc->strictErrorChecking = FALSE;
		  @$doc->loadHTML($html);
		  $dxml = simplexml_import_dom($doc);
			$game['image'] = (string)$dxml->body->div->a->img->attributes()->src;
			$game['placetype'] = $typeno;
			$products[] = $game;

			//print_r($game);
			//echo "</pre>";
		}
		return $products;

	}

	private function refreshEvents() {
    		$this->config->load('apikeys');
    		$this->db->truncate('events');
    		$fscid = $this->config->item('fscid');
			$fssecret = $this->config->item('fssecret');
			
			$this->db->where('type', 18);
			$this->db->or_where('type', 2); 
			$query = $this->db->get('places');
			foreach ($query->result() as $row)
			{
			   	$url = 'https://api.foursquare.com/v2/venues/'.$row->sourceid.'/events';
				$key = '?v=20130805&client_id='.$fscid.'&client_secret='. $fssecret;
				$query = '';
				$full_url = $url . $key . $query;
//echo $full_url . "<br>";
				//echo "<pre>";
				$json = json_decode(file_get_contents($full_url));
			//	print_r($json);
			//	echo "</pre>";
				if($json->response->events->count != 0) {

					$events = array();
					foreach($json->response->events->items as $e):
					//print_r($e); 
						$event = array();
						$event['title'] = $e->name;
						$event['allday'] = $e->allDay;
						$event['time'] = date("Y-m-d H:i:s",$e->date);
						$event['placeid'] = $row->id;
						$event['placetype'] = $row->type;
						$event['sourceid'] = $e->id;
						$event['type'] = $e->categories[0]->shortName;
						if(isset($e->url)) {
							$event['url'] = $e->url;
						} else {
							$event['url'] = NULL;
						}
						//print_r($event);
						$events[] =$event;

						//echo "E processed <br>";
					//ob_flush();
					//die();
					endforeach;
					
					
					foreach($events as $event) {

						$this->db->insert('events',$event);
					}
				}
			}


	}
}