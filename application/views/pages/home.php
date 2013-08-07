<!DOCTYPE html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="http://ricostacruz.com/jquery.transit/jquery.transit.min.js"> </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="/assets/css/stylesheet.css"><link rel="stylesheet" type="text/css" href="/assets/fonts/fonts.css">
<title>Things2Do</title>
</head>

<body OnLoad="document.s.search.focus();" style="background-image: url(/assets/img/background.png); background-size: cover; background-repeat: no-repeat;">
	<div id="logo"> </div>

	<a href="/help"><div id="help"> Help </div></a>
	<div id="searchcontainer">
		<div id="search">
    		<div id="searchbox">
        		<!-- Main search box -->
                <form name="s" action="/suggest/" method="get">I want to... <input name="search" type="text" placeholder="" class="homesearch" autocomplete="off" id="theFieldID">
            		<input name="submit" type="submit" value="" class="submithome">
            	</form>
        	</div>
		</div>
	</div>
	
    <!--Location and weather -->
	<div id="localhome"> Weather <br> Location: <span id="loc"></span><br>Facebook: <?php if($fb_logged_in) { echo $fb_name; echo ' ('.$fb_uid.') <a href="'. $fb_logout_url . '">Logout</a>'; } else { echo "<a href=\"".$fb_login_url."\">Log In</a>"; } ?></div>
	<script type="text/javascript">
	window.onload = function(){
    if(navigator.geolocation)
        navigator.geolocation.getCurrentPosition(handleGetCurrentPosition, onError);
	}

	function handleGetCurrentPosition(location){
		$("#loc").text(location.coords.latitude + ", " + location.coords.longitude);   
	}
	function onError() 
	{

	}
	</script>
</body>
</html>
