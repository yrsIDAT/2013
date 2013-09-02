<?php
	class Weather
	{
		public $precipitation;
		public $temperature;
		public $type;
		
		function __construct($x, $y, $secretkey)
		{
			$lat = $x;
			$lon = $y;
			
			//Get met office location list
			$url = "http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/json/sitelist?key=".$secretkey;
			$response = @file_get_contents($url);
			if ($response === FALSE)
			{
				$this->precipitation = 25;
				$this->temperature = 15;
				$this->type = "cloud";
			}
			else
			{
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
				$id = (string)$locationlist->Locations->Location[$id]->id;
				
				//Get current weather
				$url = "http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/json/".$id."?res=3hourly&key=".$secretkey;
				$response = file_get_contents($url);
				$weather = json_decode(mb_convert_encoding($response, "UTF-8"));
				$this->precipitation = $weather->SiteRep->DV->Location->Period[0]->Rep[0]->Pp;
				$this->temperature = $weather->SiteRep->DV->Location->Period[0]->Rep[0]->F;
				$type = $weather->SiteRep->DV->Location->Period[0]->Rep[0]->W;
				if ($type <= 1)
				{
					$this->type = "sun";
				}
				else if ($type <= 8)
				{
					$this->type = "cloud";
				}
				else
				{
					$this->type = "rain";
				}
			}
		}
	}
?>