<!DOCTYPE html>
<html>
<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="/assets/css/stylesheet.css"><link rel="stylesheet" type="text/css" href="/assets/fonts/fonts.css">
<title>Things2Do</title>
</head>

<body style="background-image: url(/assets/img/background.png); background-size: cover; background-repeat: no-repeat;">
	<div id="logo"> </div>

	<a href="/help"><div id="help"> Help </div></a>
	<div id="searchcontainer">
		<h1>Please wait while we process your profile...</h1>
	</div>
	
    <!--Location and weather -->
	<div id="localhome"> :)</div>
	<script type="text/javascript">
	$(document).ready(function() {

		$.get('/identity/analyse/', function(data) {
			if(data == 1) {
				window.location.href = 'http://things2do.ws/?ref=fba'
			} else {
				alert("Sorry there was a problem! You will not be able to use Facebook services :(");
				window.location.href = 'http://things2do.ws/?ref=fbfail'
			}
		})
	});
	</script>
</body>
</html>
