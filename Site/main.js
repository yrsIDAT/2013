
var queryPage="results.php"
var geoObj=null
var resultsId=null
var resultsObj=null


function initialise()
{
	getLocation()
	
}



function setGeoObject(object)
{
	geoObj=object
	
}
function getLocation()
{
 if (navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(setGeoObject)
	}
}
function sendSearch()
{

	var sendQuery=$.trim(document.getElementById("searchterm").value)
	if (sendQuery!=null && sendQuery!="")
	{
		//Add loading page
		$.get(queryPage, { query: sendQuery, lat: geoObj.coords.latitude, lon: geoObj.coords.longitude }, 
		function(data)
		{
			resultsObj=JSON.parse(data)
			
			
			Object.keys(resultsObj).forEach(function(key) {
				console.log(key, resultsObj[key]);
			})							
						
			
		})
		
	
	}

}


//Do simulations of the div adding functions and whatnot



	