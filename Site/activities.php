<?php
	function getActivities($lat, $lon)
	{
		$result = @file_get_contents("http://things2do.ws/activities.json?lat=".$lat."&lon=".$lon);
		if ($result === FALSE)
		{
			return "-1";
		}
		else
		{
			return json_decode($result);
		}
	}
?>