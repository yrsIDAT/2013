
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
    else
    {
        geoObj={coords:{latitude:"",longitude:""}}
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
		showResults(false)
		showLoading(true)
		
		if (!geoObj)
		{
			geoObj={coords:{latitude:"",longitude:""}}
		}
		
		$.ajax({url:queryPage, data:{ query: sendQuery, lat: geoObj.coords.latitude, lon: geoObj.coords.longitude }, 
		success:function(data,textStatus)
			{
				
				showLoading(false)
				showResults(true)
				resultsObj=data
				if (data!="-1" && resultsObj.length != 0 && resultsObj === Object(resultsObj))
				{
					var numberofcardstoshow = resultsObj.length
					if (numberofcardstoshow > 24) numberofcardstoshow = 24
					for (index=0;index<numberofcardstoshow;index++)
					{
						cardAdd(resultsObj[index])
					}
				}else{
					showError(true)
					showResults(false)
					showLoading(false)
				}
							
				
			},error:function()
			{
				showError(true)
				showResults(false)
				showLoading(false)
			}
			,timeout:60000
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
