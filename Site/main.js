
var queryPage="results.php"
var geoObj=null
var resultsId=null
var resultsObj=null


function initialise()
{
	getLocation()
	preventSubmit()
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
	//alert("asdsandjsn dn")
/*
	var sendQuery=$.trim(document.getElementById("searchterm").value)
	if (sendQuery!=null && sendQuery!="")
	{
		//Add loading page
		$.get(queryPage, { query: sendQuery, lat: geoObj.coords.latitude, lon: geoObj.coords.longitude }, 
		function(data)
		{
			//Parse the JSON callback and add a div for every element in the object
			resultsObj=JSON.parse(data)
			
			
			Object.keys(resultsObj).forEach(function(key) {
				console.log(key, resultsObj[key]);
			})							
						
			
		})
		
	
	}*/

}


//Do simulations of the div adding functions and whatnot

function cardAdd(object) //object=parsed JSON
{
	/*
	
	*/
	var newCard = $("<div class=\"card\"><div class=\"cardtop\"> </div></div>")
	
	$("#cardcontainer").append(newCard)

}

function cardRemoveAll()
{

	$(function(){
	  $("div.card").remove()
	})
	

}

function preventSubmit()
{

	
	$(this).submit(function(){
		return false
	})
}




	