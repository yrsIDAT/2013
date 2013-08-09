<?php
	define("THINGS2DO", true);
	require "backend/keystore.php";
	require "backend/apikeys.php";
	require "weather.php";
	require "searchdecoder.php";
	require "activities.php";
	require "score.php";
	//include "backend/APIs/geobytes.php";
	
    //$location=$this->GEOLocation->try_all_methods();
	$conditions = new Condition();
	$conditions->_time = (float)date("G", time()) + ((float)date("i", time()))/60;
	//$conditions->position = new Position($_GET["lat"], $_GET["lon"]);
	$conditions->position = new Position(52.483056, -1.893611);//Fixed coordinates, how naughty
	//get weather - $weather from weather.php, parameters (lat, lon)
	$conditions->weather = new Weather($conditions->position->lat, $conditions->position->lon, $key->getkey("metoffice"));
	//get string category list -  $categories from searchdecoder.php, parameters (searchstring)
	$searchstringresults = new Search($_GET["query"]);
	$categories = $searchstringresults->categoryanalysis;
	//get results from apis - $activities from activities.php, parameters ()
	$activities = getActivities($conditions->position->lat, $conditions->position->lon);
	//calculate score - $activities from score.php, parameters, (activities, conditions, categories)
	$activities = calculateScores($activities, $conditions, $categories);
	//order results
	usort($activities, function($a, $b) {
		    if( $a->score < $b->score) {
		    	return 1;
		    } else {
		    	return 0;
		    }
		});
	//return results
	header("Content-type: text/json");
	$reply = json_encode($activities);
	if ($reply == "" || $reply == null || sizeOf($activities) == 0) $reply = "-1"; 
	echo($reply);
/*
	"activities":
	[{
		"title":
		"image":
		"description":
		"uniquedata":
		"url":
		"type":
		"lat":
		"lon":
		"distance":
		"score":
	}]
	
	"conditions":
	{
		"weather":
		{
			"condition":
			"temperature":
			"precipitation":
		}
		"time":
		"position":
		{
			"lat":
			"lon":
		}
	}
*/
	class Condition
	{
		public $weather;
		public $_time;
		public $position;
		
		function __construct(){}
	}
	
	class Position
	{
		public $lat;
		public $lon;
		
		function __construct($lat, $lon)
		{
			$this->lat = $lat;
			$this->lon = $lon;
			if (!$lat || !$lon)
			{
				include "backend/APIs/geobytes.php";
                $loc=new LocationManager();
                $loc->get_web_location();
                $this->lat=$loc->lat;
                $this->lon=$loc->lon;
            }

		}
	}
?>