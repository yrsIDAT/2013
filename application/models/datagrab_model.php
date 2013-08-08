<?php
class Datagrab_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->library('WeatherApi');
		$this->load->library('PlacesApi');
	}

	public function completeRefresh()
	{
		$locations = array();
		$locations[] = array('plymouth',50.371389, -4.142222);
		$locations[] = array('birmingham',52.483056, -1.893611);


		foreach($locations as $location) {
			$this->refreshWeather($location[1],$location[2]);
			$this->refreshPlaces($location[1],$location[2]);
		}
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
}