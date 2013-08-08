<?php
	require "weather.php";
	require "searchdecoder.php";
	require "activities.php";
	require "score.php";
	//include "backend/APIs/geobytes.php";
	
	
	
    //$location=$this->GEOLocation->try_all_methods();
	$conditions = new Condition();
	$conditions->_time = (float)date("G", time()) + ((float)date("i", time()))/60;
	$conditions->position = new Position($_GET["lat"], $_GET["lon"]);
	//get weather - $weather from weather.php, parameters (lat, lon)
	$conditions->weather = new Weather($conditions->position->lat, $conditions->position->lon);
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
	echo(json_encode($activities));
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
		}
	}
?>