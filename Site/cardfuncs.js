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
	
	var cardString="<div class=\"smallcard\">"
	cardString=cardString+cardData.title+"<div class=\"cardbottom\">"
	+ "<div class=\"cardtext\">"
	
	
	switch(cardData.type)
	{
		case TYPE_BEACH:
	  
			break
		case TYPE_CINEMA:

			break
		case TYPE_FOOD:
		
			break
		default:
	  
	}
	cardString=cardString+"</div></div>"
	var newCard=$(cardString)
	$("#cardcontainer").append(newCard)
}
