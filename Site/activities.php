<?php
	function getActivities($lat, $lon)
	{
		return json_decode(file_get_contents("http://things2do.ws/activities.json?lat=".$lat."&lon=".$lon));
	}
?>