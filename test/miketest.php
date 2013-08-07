<?php
	date_default_timezone_set("Europe/London");
	$places = unserialize(file_get_contents('C:\Users\Jynx\Documents\GitHub\2013\test\example_data.txt'));
	//$weather = unserialize('O:8:"stdClass":7:{s:2:"id";s:1:"1";s:4:"city";s:8:"plymouth";s:8:"sourceid";s:6:"manual";s:4:"date";s:10:"2013-08-06";s:9:"condition";s:3:"sun";s:13:"precipitation";s:1:"5";s:4:"temp";s:2:"25";}');
	$weather = (object) Array(
		'city'=> 'plymouth',
		'condition'=> 'sun',
		'precipitation' => 0,
		'temp' => 30);
	$conditions = (object) Array(
		'weather'=>$weather,
		'_time'=>(float)date("G", time()) + ((float)date("i", time()))/60
		);
	
	$conditions->_time = 21;
	
	function calculateScores($activities, $conditions)
	{
		$weightings = Array(
			new Weighting(0, 0, Array()),
			new Weighting(1, 0.3, Array(new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(18, 1), new TimeFeel(21, -1))),//1-beach
			new Weighting(-0.5, 0.5, Array(new TimeFeel(0, 0), new TimeFeel(6, -1), new TimeFeel(12, 1), new TimeFeel(21, 1))),//2-cinema
			new Weighting(0.2, 1, Array(new TimeFeel(6, -1), new TimeFeel(10, 1), new TimeFeel(11.5, 0), new TimeFeel(13, 1), new TimeFeel(16, 0), new TimeFeel(19, 1), new TimeFeel(23,-1))),//3-cafe
			new Weighting(0.5, 0.2, Array(new TimeFeel(0, -0.5), new TimeFeel(6, -0.5), new TimeFeel(12, 0.5), new TimeFeel(21, 0.5)))//4-stadium
		);
		
		$activitiesLength = sizeOf($activities);
		for ($i = 0; $i < $activitiesLength; $i++)
		{
			$activities[$i]->score = 0;
			$activities[$i]->score += $weightings[$activities[$i]->type]->condition * getConditionNumber($conditions->weather->condition);
			$activities[$i]->score += $weightings[$activities[$i]->type]->temperature * standardise($conditions->weather->temp, 30, 0);
			$activities[$i]->score += $weightings[$activities[$i]->type]->precipitation * standardise($conditions->weather->precipitation, 50, 0);
			
			$activities[$i]->score += $weightings[$activities[$i]->type]->distance * standardise($activities[$i]->distance, 0, 30000);
			$activities[$i]->score += timeAppropriateness($weightings[$activities[$i]->type]->timefeel, $conditions->_time);
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
	
	
	echo "<h1>Activities:</h1><pre>";
	$scores = calculateScores($places,$conditions);
	usort($scores, function($a, $b) {
		    if( $a->score < $b->score) {
		    	return 1;
		    } else {
		    	return 0;
		    }
		});
	print_r($scores);
	echo "</pre>";
?>