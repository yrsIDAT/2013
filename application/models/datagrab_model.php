<?php
class Datagrab_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->library('weatherApi');
	}

	public function completeRefresh()
	{
		$locations = array();
		$locations[] = array('plymouth',50.371389, -4.142222);
		$locations[] = array('birmingham',52.483056, -1.893611);

		foreach($locations as $location) {
			$this->refreshWeather($location[1],$location[2]);
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
}