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
	
	switch(cardData.type)
	{
	
		case TYPE_CINEMA:

			break
		
		default:
		
		cardString="<div class=\"smallcard\">"
		+"<div class=\"cardbottom\">"
		+ "<div class=\"cardtext\">"
		+ "<a href="+cardData.url+">"
		+ "<h2> " + cardData.title + " </h2>"
		+ "</a>"
		+ "<p>Relevancy Score:" + cardData.score + "</p>"
		
	}
	+"</div></div></div>"
	var newCard=$(cardString)
	$("#cardcontainer").append(newCard)
}
