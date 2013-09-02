<?php
	class WeatherAPI
	{
		private $secretkey = "";
		public $lat;
		public $lon;
		private $locationlist;
		private $id;
		private $city;
		public $precipitation;
		public $temperature;
		public $type;
		private $weather;
		
		function __construct()
		{

			$this->getKeys();
		}

		private function getKeys() {
			$CI =& get_instance();
    		$CI->config->load('apikeys');

    		$this->secretkey = $CI->config->item('metkey');
		}

		function setCity($x,$y) {
			$lat = $x;
			$lon = $y;
			
			//Get met office location list
			$url = "http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/json/sitelist?key=".$this->secretkey;
			$response = file_get_contents($url);
			$locationlist = json_decode(mb_convert_encoding($response, "UTF-8"));
			
			//Get closest met office location
			$length = sizeOf($locationlist->Locations->Location);
			$closex = $locationlist->Locations->Location[0]->latitude;
			$closey = $locationlist->Locations->Location[0]->longitude;
			$closedist2 = ($x - $closex) * ($x - $closex) + ($y - $closey) * ($y - $closey);
			$dx = $closex - $x;
			$dy = $closey - $y;
			if ($closex - $x > 180) $dy = 360 - ($closex - $x);
			$id = 0;
			for ($i = 0; $i < $length; $i++)
			{
				$tempx = $locationlist->Locations->Location[$i]->latitude;
				$tempy = $locationlist->Locations->Location[$i]->longitude;
				$dx = $tempx - $x;
				$dy = $tempy - $y;
				$tempclosedist2 = $dx * $dx + $dy * $dy;
				if ($tempclosedist2 < $closedist2)
				{
					$closex = $tempx;
					$closey = $tempy;
					$closedist2 = $tempclosedist2;
					$id = $i;
				}
			}
			$this->id = (string)$locationlist->Locations->Location[$id]->id;
			$this->city = (string)$locationlist->Locations->Location[$id]->name;
			//$this->updateWeather();

			$this->updateWeather();
			return $this->getWeather();
		}
		
		function updateWeather()
		{

			$url = "http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/json/".$this->id."?res=3hourly&key=".$this->secretkey;
			$response = file_get_contents($url);
			//echo $response;
			$this->weather = json_decode(mb_convert_encoding($response, "UTF-8"));
			$this->precipitation = $this->weather->SiteRep->DV->Location->Period[0]->Rep[1]->Pp;
			$this->temperature = $this->weather->SiteRep->DV->Location->Period[0]->Rep[1]->F;
			$this->type = $this->weather->SiteRep->DV->Location->Period[0]->Rep[1]->W;

		}
		
		function getWeather()
		{

			if ($this->type <= 1)
			{
				$type = "sun";
			}
			else if ($this->type <= 8)
			{
				$type = "cloud";
			}
			else
			{
				$type = "rain";
			}
			return Array($this->precipitation, $this->temperature, $type, $this->city);
		}
	}
	?>