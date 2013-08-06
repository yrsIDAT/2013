<?php
	function calculateScores($activities, $conditions)
	{
		$weightings = Array(
			new Weighting(0, 0, 0),
			new Weighting(1, 1, 0.5),
			new Weighting(-0.5, 0, 0.5),
			new Weighting(0.5, 1, 1),
			new Weighting(0, 1, 0.2)
		);
		
		$activitiesLength = sizeOf($activities);
		
		for ($i = 0; $i < activitiesLength; $i++)
		{
			$activities[$i]->score = 0;
			$activities[$i]->score += $weightings[$activities[$i]->type]->condition * $conditions->weather->condition;
			$activities[$i]->score += $weightings[$activities[$i]->type]->temperature * $conditions->weather->temperature;
			$activities[$i]->score += $weightings[$activities[$i]->type]->precipitation * $conditions->weather->precipitation;
			
			$activities[$i]->score += $weightings[$activities[$i]->type]->distance * (1 - $activities[$i]->distance);
		}
		
		return $activities;
	}
	
	class Weighting
	{
		public $condition;
		public $temperature;
		public $precipitation;
		public $_time;
		public $distance;
		
		function __construct($weather, $_time, $distance)
		{
			$this->condition = $weather / 3;
			$this->temperature = $weather / 3;
			$this->precipitation = -$weather / 3;
			$this->_time = $_time;
			$this->distance = $distance;
		}
	}
?>