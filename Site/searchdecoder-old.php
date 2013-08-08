<?php
	if (isSet($_POST["search"]))
	{
		$categories = getCategoryList($_POST["search"]);
	}
	
	function getCategoryList($search)
	{
		$keywords = new Array(
		
		);
		$categories = new Array(0, 0, 0, 0, 0);
		
		for ($i = 0; $i < $length; $i++)
		{
			if (isIn($search, $keywords[$i]))
			{
				$categories[$keywords[$i]->type += 0.025;
			}
			if (startsWord($search, $keywords[$i]))
			{
				$categories[$keywords[$i]->type += 0.1;
			}
			if (endsWord($search, $keywords[$i]))
			{
				$categories[$keywords[$i]->type += 0.1;
			}
		}
		
		return $categories;
	}
	
	function isIn($search, $keyword)
	{
		
	}
	
	function startsWord($search, $keyword)
	{
		
	}
	
	function endsWord($search, $keyword)
	{
		
	}
	
	class KeyWord
	{
		public $type;
		public $word;
		
		function __construct($type, $word)
		{
			$this->type = $type;
			$this->word = $word;
		}
	}
?>