/*
<!-- Some Examples of smaller cards -->
<div class="smallcard">
	<div class="cardbottom">
		<div class="cardtext"><!-- Text Goes here -->
			<h2> Elysium </h2>
			<p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu quam tellus. Ut scelerisque justo eget nunc congue tristique. Fusce blandit arcu neque. Donec rutrum, dui eget condimentum elementum, eros enim dapibus leo, at lobortis nisi ante vel est. Curabitur faucibus rhoncus enim eget malesuada. Aenean libero felis, rhoncus a purus a, laoreet pellentesque arcu. </p>
		</div>
	</div>
</div><!-- End Small Card -->
*/


function cardAdd(cardData) //object=parsed JSON
{
	var cardString
	cardString="<div class=\"smallcard\">"
	if (cardData.url != "")
	{
		cardString =cardString+ "<a target='_blank' href="+cardData.url+">"
	}
	cardString=cardString+"<div class=\"cardbottom\">"
	cardString=cardString+ "<div class=\"cardtext\">"
	cardString=cardString+ "<h2> " + cardData.title + " </h2>"
	if (cardData.url != "")
	{
		cardString=cardString+ "</a>"
	}
	//cardString=cardString+ "<p>Relevancy Score: " + cardData.score + "</p>"
	if (cardData.postcode != "" && cardData.postcode != null)
	{
		cardString=cardString+ "<p>Postcode: " + cardData.postcode + "</p>"
	}
	switch(cardData.type)
	{
	
		case TYPE_CINEMA:
			if (cardData.data.events != undefined)
			{
				cardString=cardString+ "<p>Showings:</p>"
				$.each(cardData.data.events,function(index,movie)
				{
					cardString=cardString+ "<p>"+movie.title+"</p>"
					/*if (movie.time!=null)
					{
						$.each(movie.time,function(_,movietime)
						{
							cardString=cardString+"<p>-"+movietime+"</p>"
						})
					}*/
				})
			}
			
			break
			
			case TYPE_RECORDSHOP:
				if (cardData.data.product != undefined)
				{
					cardString=cardString+ "<p>Recommended:</p>"
					if (cardData.data.product.url != "")
					{
						cardString=cardString+ "<a href=\""+cardData.data.product.url+"\">"
					}
					cardString=cardString+ "<p>"+cardData.data.product.title+"</p>"
					if (cardData.data.product.url != "")
					{
						cardString=cardString+ "</a>"
					}
				}
			break
			
			case TYPE_BOOKSHOP:
				if (cardData.data.product != undefined)
				{
					cardString=cardString+ "<p>Recommended:</p>"
					if (cardData.data.product.url != "")
					{
						cardString=cardString+ "<a href=\""+cardData.data.product.url+"\">"
					}
					cardString=cardString+ "<p>"+cardData.data.product.title+"</p>"
					if (cardData.data.product.url != "")
					{
						cardString=cardString+ "</a>"
					}
				}
			break
			
			case TYPE_VIDEOGAMES:
				if (cardData.data.product != undefined)
				{
					cardString=cardString+ "<p>Recommended:</p>"
					if (cardData.data.product.url != "")
					{
						cardString=cardString+ "<a href=\""+cardData.data.product.url+"\">"
					}
					cardString=cardString+ "<p>"+cardData.data.product.title+"</p>"
					if (cardData.data.product.url != "")
					{
						cardString=cardString+ "</a>"
					}
				}
			break
			
		default:
	
	}
	cardString=cardString+"</div>"
	if (cardData.image != "")
	{
		cardString=cardString+"<div style=\"background-image: url("+cardData.image+");\" class=\"cardimage\"></div>"
	}
	cardString=cardString+"</div></div>"
	var newCard=$(cardString)
	$("#cardcontainer").append(newCard)
}
