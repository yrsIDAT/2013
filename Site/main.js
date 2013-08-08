
var queryPage="results.php"
var geoObj=null
var resultsId=null
var resultsObj=null


function initialise()
{
	getLocation()
	preventSubmit()
}

function hideHome()
{
	//This is to be replaced with a fancier style e.g. 
	
	var homeScreen=$("#home")
	homeScreen.css("display", "none")
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
function sendSearch(boxid)
{

	var sendQuery=$.trim($(boxid)).value
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


function cardAdd(cardData) //object=parsed JSON
{
	
	var cardString="<div class=\"card\">"
	cardString=cardString+cardData.title+"<div class=\"cardtop\">"
	
	
	switch(cardData.type)
	{
		case TYPE_BEACH:
	  
			break
		case TYPE_CINEMA:

			break
		case TYPE_CAFE:
		
			break
		default:
	  
	}
	cardString=cardString+"</div></div>"
	var newCard=$(cardString)
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



	