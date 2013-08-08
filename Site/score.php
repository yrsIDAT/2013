<?php
	function calculateScores($activities, $conditions, $categories)
	{
		$weightings = Array(
			new Weighting(0, 0, Array()),
			new Weighting(1, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(18, 1), new TimeFeel(21, -1))),//1-beach
			new Weighting(-0.5, 0.5, Array(new TimeFeel(0, 0), new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(21, 1))),//2-cinema
			new Weighting(0.2, 1, Array(new TimeFeel(6, -1), new TimeFeel(10, 1), new TimeFeel(11.5, 0), new TimeFeel(13, 1), new TimeFeel(16, 0), new TimeFeel(19, 1), new TimeFeel(23,-1))),//3-cafe
			new Weighting(0.25, 0.2, Array(new TimeFeel(0, -0.5), new TimeFeel(6, -0.5), new TimeFeel(12, 0.5), new TimeFeel(21, 0.5))),//4-stadium
			new Weighting(0, 0.5, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(18, 1), new TimeFeel(21, -1))),//mall
			new Weighting(0, 0.5, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(18, 1), new TimeFeel(21, -1))),//record shop
			new Weighting(0, 0.5, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(18, 1), new TimeFeel(21, -1))),//book shop
			new Weighting(0, 0, Array(new TimeFeel(0, 0))),//video game
			new Weighting(0, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(16, 1), new TimeFeel(21, -1))),//aquarium
			new Weighting(0, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(16, 1), new TimeFeel(21, -1))),//museum
			new Weighting(0.5, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(16, 1), new TimeFeel(21, -1))),//zoo
			new Weighting(-0.5, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(20, 1), new TimeFeel(23, -1))),//bowling
			new Weighting(1, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(16, 1), new TimeFeel(21, -1))),//water park
			new Weighting(0, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(18, 1), new TimeFeel(21, -1))),//art gallery
			new Weighting(0.75, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(16, 1), new TimeFeel(21, -1))),//theme park
			new Weighting(1, 0.6, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(20, 1), new TimeFeel(23, -1))),//park
			new Weighting(1, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(18, 1), new TimeFeel(21, -1)))///scenic point
		);
		
		$activitiesLength = sizeOf($activities);
		for ($i = 0; $i < $activitiesLength; $i++)
		{
			$activities[$i]->score = 0;
			$activities[$i]->score += $weightings[$activities[$i]->type]->condition * getConditionNumber($conditions->weather->type) / 10;
			$activities[$i]->score += $weightings[$activities[$i]->type]->temperature * standardise($conditions->weather->temperature, 30, 0) / 10;
			$activities[$i]->score += $weightings[$activities[$i]->type]->precipitation * standardise($conditions->weather->precipitation, 50, 0) / 10;
			
			$activities[$i]->score += $weightings[$activities[$i]->type]->distance * standardise($activities[$i]->distance, 0, 30000) / 10;
			$activities[$i]->score += timeAppropriateness($weightings[$activities[$i]->type]->timefeel, $conditions->_time) / 5;
			$activities[$i]->score += $categories[$activities[$i]->type];
		}
		return $activities;
	}
	
	function timeAppropriateness($timefeel, $time)
	{
		$score = 0;
		$length = sizeOf($timefeel);
		if ($length > 0)
		{
			if ($length == 1)
			{
				$score = $timefeel[0]->_time;
			}
			else
			{
				$closestup = $timefeel[0];
				$closestdown = $timefeel[0];
				for ($i = 0; $i < $length; $i++)
				{
					$curupdist = $closestup->_time - $time;
					if ($curupdist < 0) $curupdist += 24;
					$curdowndist = $time - $closestdown->_time;
					if ($curdowndist < 0) $curdowndist += 24;
					$potupdist = $timefeel[$i]->_time - $time;
					if ($potupdist < 0) $potupdist += 24;
					$potdowndist = $time - $timefeel[$i]->_time;
					if ($potdowndist < 0) $potdowndist += 24;
					if ($potupdist < $curupdist) $closestup = $timefeel[$i];
					if ($potdowndist < $curdowndist) $closestdown = $timefeel[$i];
				}
				$deltatime = $time - $closestdown->_time;
				if ($deltatime < 0) $deltatime += 24;
				$otherdeltatime = $closestup->_time - $closestdown->_time;
				if ($otherdeltatime < 0) $otherdeltatime += 24;
				if ($otherdeltatime != 0)
				{
					$score = ($deltatime / $otherdeltatime) * ($closestup->feel - $closestdown->feel) + $closestdown->feel;
				}
				else
				{
					$score = ($closestup->feel + $closestdown->feel) / 2;
				}
			}
		}
		return $score;
	}
	
	function standardise($number, $upper, $lower)
	{
		$number = ($number - $lower) / ($upper - $lower) * 2 - 1;
		if ($number < -1) $number = -1;
		if ($number > 1) $number = 1;
		return $number;
	}
	
	function getConditionNumber($condition)
	{
		if ($condition == "sun")
		{
			return 1;
		}
		else if ($condition == "cloud")
		{
			return 0;
		}
		else
		{
			return -1;
		}
	}
	
	class TimeFeel
	{
		public $_time;
		public $feel;
		
		function __construct($time, $feel)
		{
			$this->_time = $time;
			$this->feel = $feel;
		}
	}
	
	class Weighting
	{
		public $condition;
		public $temperature;
		public $precipitation;
		public $timefeel;
		public $distance;
		
		function __construct($weather, $distance, $timefeel)
		{
			$this->condition = $weather / 3;
			$this->temperature = $weather / 3;
			$this->precipitation = -$weather / 3;
			$this->timefeel = $timefeel;
			$this->distance = $distance;
		}
	}
?>