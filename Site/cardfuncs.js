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
	+"<div class=\"cardbottom\">"
	+ "<div class=\"cardtext\">"
	+ "<a target='_blank' href="+cardData.url+">"
	+ "<h2> " + cardData.title + " </h2>"
	+ "</a>"
	+ "<p>Relevancy Score:" + cardData.score + "</p>"
	+ "<p>Postcode:" + cardData.postcode + "</p>"
	switch(cardData.type)
	{
	
		case TYPE_CINEMA:
			
			+ "<p>Showings:</p>"
			$.each(cardData.showings,function(index,movie)
			{
				+ "<p>"+movie.title+"</p>"
				if (movie.time!=null)
				{
					$.each(movie.time,function(_,movietime)
					{
						+"<p>-"+movietime+"</p>"
					})
				}
			})
			
			break
			
		default:
	
	}
	+"</div></div></div>"
	var newCard=$(cardString)
	$("#cardcontainer").append(newCard)
}
