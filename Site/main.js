
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

function showError(show)
{
	var errorMessage=$("#errormessage")
	var resultsBox=$("#resultscontainer")
	if (show)
	{
		resultsBox.css("display", "none")
		errorMessage.css("display", "block")
	}else{
		resultsBox.css("display", "block")
		errorMessage.css("display", "none")
	}
}

function sendSearch(boxid)
{
	
	var sendQuery=$.trim($("#"+boxid)).value
	if (sendQuery!=null && sendQuery!="")
	{
		//Delete all cards and possible error
		cardRemoveAll()
		showErrorMessage(false)
		//Add loading page
		$.get(queryPage, { query: sendQuery, lat: geoObj.coords.latitude, lon: geoObj.coords.longitude }, 
		function(data)
		{
			if (data!="-1")
			{
				resultsObj=JSON.parse(data)
				
				
				Object.keys(resultsObj).forEach(function(key) {
					console.log(key, resultsObj[key]);
				})		
			}else{
				showErrorMessage(true)
			}
						
			
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



	