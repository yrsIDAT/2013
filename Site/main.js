
var queryPage="server.php"
var geoObj=null
var resultsId=null
var resultsObj=null


function initialise()
{
	getLocation()
    hideResults()
}

function hideHome()
{
	//This is to be replaced with a fancier style e.g. 
	
	var homeScreen=$("#home")
	homeScreen.css("display", "none")

}

function hideResults()
{
	//This is to be replaced with a fancier style e.g. 
	
	$("#resultsdiv").hide()

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

function showResults(show)
{
	var resultsBox=$("#resultscontainer")
	if (!show)
	{
	
		resultsBox.css("display", "none")
	}else{
		resultsBox.css("display", "block")
	}

}

function showError(show)
{
	var errorMessage=$("#errormessage")
	
	if (show)
	{
		errorMessage.css("display", "block")
	}else{
		
		errorMessage.css("display", "none")
	}
}

function sendSearch(boxid)
{
	
	var sendQuery=$.trim($("#"+boxid).val())
	if (sendQuery!=null && sendQuery!="")
	{
		//Delete all cards and possible error
		cardRemoveAll()
		showError(false)
		showResults(true)
		showLoading(true)
		
		
		$.get(queryPage, { query: sendQuery, lat: geoObj.coords.latitude, lon: geoObj.coords.longitude }, function(data)
		{
			
			showLoading(false)
			if (data!="-1")
			{
				resultsObj=data
				
				
				Object.keys(resultsObj).forEach(function(key) {
					//Here you will need to add a card for each result...
					cardAdd(resultsObj[key])
					
				})		
			}else{
				showError(true)
				showResults(false)
				
			}
						
			
		})
		
	
	}
    else
    {
        return -1 // no search query entered
    }
    

}


//Do simulations of the div adding functions and whatnot

function showLoading(show)
{
	var loadGif=$("#loading")
	if (show) 
	{
		loadGif.css("display", "block")
	}else{
		loadGif.css("display", "none")
	}
}

function cardRemoveAll()
{

	$(function(){
	  $("div.card").remove()
	  $("div.smallcard").remove()
	})
	

}



function setResultsToHome()
{
	$("#searchterm").val($("#homesearch").val())

}
