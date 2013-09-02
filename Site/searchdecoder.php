<?php

class Search {
	public $categoryanalysis;
    public function __construct($searchstring) {
		$this->categoryanalysis = array_fill(0, 50, 0);
		$this->categoryanalysis = $this->MikeSearchStringAlgorithm($this->categoryanalysis, $searchstring);
    }

	function MikeSearchStringAlgorithm($categoryanalysis, $search)
	{
		$keywords = array(
			new Keyword("beach", 1, 1),
			new Keyword("sun", 1, 0.5),
			new Keyword("spade", 1, 0.5),
			new Keyword("bucket", 1, 0.5),
			new Keyword("towel", 1, 0.5),
			new Keyword("dig", 1, 0.5),
			new Keyword("tan", 1, 0.5),
			new Keyword("sea", 1, 0.5),
			new Keyword("swim", 1, 0.5),
			new Keyword("wave", 1, 0.5),
			new Keyword("bathe", 1, 0.5),
			new Keyword("sand", 1, 0.7),
			new Keyword("bikini", 1, 0.3),
			new Keyword("cinema", 2, 1),
			new Keyword("film", 2, 0.8),
			new Keyword("action", 2, 0.5),
			new Keyword("plot", 2, 0.5),
			new Keyword("watch", 2, 0.7),
			new Keyword("adventure", 2, 0.5),
			new Keyword("comedy", 2, 0.5),
			new Keyword("crime", 2, 0.5),
			new Keyword("drama", 2, 0.5),
			new Keyword("histor", 2, 0.5),
			new Keyword("horror", 2, 0.5),
			new Keyword("musical", 2, 0.5),
			new Keyword("science", 2, 0.5),
			new Keyword("war", 2, 0.5),
			new Keyword("western", 2, 0.5),
			new Keyword("cafe", 3, 1),
			new Keyword("restaurant", 3, 1),
			new Keyword("food", 3, 0.75),
			new Keyword("eat", 3, 0.5),
			new Keyword("breakfast", 3, 0.5),
			new Keyword("lunch", 3, 0.5),
			new Keyword("brunch", 3, 0.5),
			new Keyword("dinner", 3, 0.5),
			new Keyword("tea", 3, 0.3),
			new Keyword("supper", 3, 0.5),
			new Keyword("hungry", 3, 0.4),
			new Keyword("drink", 3, 0.4),
			new Keyword("stadium", 4, 1),
			new Keyword("football", 4, 0.6),
			new Keyword("rugby", 4, 0.5),
			new Keyword("game", 4, 0.5),
			new Keyword("sport", 4, 0.3),
			new Keyword("shop", 5, 1),
			new Keyword("buy", 5, 0.7),
			new Keyword("get", 5, 0.3),
			new Keyword("purchase", 5, 0.7),
			new Keyword("mall", 5, 0.8),
			new Keyword("thing", 5, 0.3),
			new Keyword("stuff", 5, 0.3),
			new Keyword("food", 5, 0.3),
			new Keyword("cloth", 5, 0.3),
			new Keyword("goods", 5, 0.3),
			new Keyword("music", 6, 1),
			new Keyword("track", 6, 0.7),
			new Keyword("record", 6, 0.7),
			new Keyword("song", 6, 0.8),
			new Keyword("buy", 6, 0.5),
			new Keyword("get", 6, 0.3),
			new Keyword("purchase", 6, 0.5),
			new Keyword("listen", 6, 0.8),
			new Keyword("record", 6, 0.8),
			new Keyword("rock", 6, 0.5),
			new Keyword("pop", 6, 0.5),
			new Keyword("metal", 6, 0.5),
			new Keyword("alternative", 6, 0.5),
			new Keyword("rap", 6, 0.5),
			new Keyword("classic", 6, 0.5),
			new Keyword("electric", 6, 0.5),
			new Keyword("buy", 7, 0.3),
			new Keyword("get", 7, 0.2),
			new Keyword("purchase", 7, 0.3),
			new Keyword("book", 7, 1),
			new Keyword("read", 7, 0.8),
			new Keyword("plot", 7, 0.5),
			new Keyword("genre", 7, 0.5),
			new Keyword("adventure", 7, 0.5),
			new Keyword("children", 7, 0.5),
			new Keyword("classic", 7, 0.5),
			new Keyword("fantasy", 7, 0.5),
			new Keyword("fiction", 7, 0.5),
			new Keyword("horror", 7, 0.5),
			new Keyword("story", 7, 0.5),
			new Keyword("liter", 7, 0.5),
			new Keyword("poet", 7, 0.5),
			new Keyword("poem", 7, 0.5),
			new Keyword("romance", 7, 0.5),
			new Keyword("sci", 7, 0.5),
			new Keyword("stori", 7, 0.5),
			new Keyword("thrill", 7, 0.5),
			new Keyword("action", 8, 0.5),
			new Keyword("adventure", 8, 0.5),
			new Keyword("role", 8, 0.5),
			new Keyword("simulat", 8, 0.5),
			new Keyword("strateg", 8, 0.5),
			new Keyword("rpg", 8, 0.5),
			new Keyword("fps", 8, 0.5),
			new Keyword("tbs", 8, 0.5),
			new Keyword("play", 8, 0.5),
			new Keyword("game", 8, 1),
			new Keyword("fun", 8, 0.5),
			new Keyword("buy", 8, 0.3),
			new Keyword("get", 8, 0.2),
			new Keyword("purchase", 8, 0.3),
			new Keyword("computer", 8, 0.8),
			new Keyword("water", 9, 0.5),
			new Keyword("fish", 9, 0.65),
			new Keyword("sea", 9, 0.3),
			new Keyword("animal", 9, 0.3),
			new Keyword("nature", 9, 0.3),
			new Keyword("aquarium", 9, 1),
			new Keyword("museum", 10, 1),
			new Keyword("old", 10, 0.5),
			new Keyword("histori", 10, 0.6),
			new Keyword("war", 10, 0.5),
			new Keyword("teach", 10, 0.5),
			new Keyword("learn", 10, 0.5),
			new Keyword("education", 10, 0.5),
			new Keyword("animal", 11, 0.8),
			new Keyword("zoo", 11, 1),
			new Keyword("carnivore", 11, 0.5),
			new Keyword("herbivore", 11, 0.5),
			new Keyword("education", 11, 0.5),
			new Keyword("learn", 11, 0.5),
			new Keyword("teach", 11, 0.5),
			new Keyword("nature", 11, 0.3),
			new Keyword("bowl", 12, 0.8),
			new Keyword("ing ball", 12, 0.7),
			new Keyword("game", 12, 0.5),
			new Keyword("play", 12, 0.2),
			new Keyword("sport", 12, 0.5),
			new Keyword("water", 13, 0.5),
			new Keyword("park", 13, 0.5),
			new Keyword("water park", 13, 1),
			new Keyword("swim", 13, 0.5),
			new Keyword("slide", 13, 0.6),
			new Keyword("fun", 13, 0.5),
			new Keyword("theme park", 15, 1),
			new Keyword("rollercoaster", 15, 0.75),
			new Keyword("fun", 15, 0.5),
			new Keyword("fast", 15, 0.5),
			new Keyword("wheel", 15, 0.5),
			new Keyword("park", 16, 1),
			new Keyword("body", 16, 0.3),
			new Keyword("fresh air", 16, 0.5),
			new Keyword("green", 16, 0.5),
			new Keyword("grass", 16, 0.5),
			new Keyword("nature", 16, 0.5),
			new Keyword("walk", 16, 0.5),
			new Keyword("relax", 16, 0.5),
			new Keyword("sun", 16, 0.5),
			new Keyword("enjoy", 16, 0.5),
			new Keyword("tree", 16, 0.5),
			new Keyword("flower", 16, 0.5),
			new Keyword("outside", 16, 0.5),
			new Keyword("outdoor", 16, 0.5),
			new Keyword("animal", 16, 0.5),
			new Keyword("bench", 16, 0.5),
			new Keyword("path", 16, 0.3),
			new Keyword("scenic point", 17, 1),
			new Keyword("view", 17, 0.7),
			new Keyword("park", 17, 0.7),
			new Keyword("nature", 17, 0.5),
			new Keyword("walk", 17, 0.5),
			new Keyword("green", 17, 0.5),
			new Keyword("see", 17, 0.5),
			new Keyword("watch", 17, 0.5),
			new Keyword("far", 17, 0.5),
			new Keyword("music", 18, 0.6),
			new Keyword("music venue", 18, 0.4),//lower as it builds on music
			new Keyword("concert", 18, 0.8),
			new Keyword("listen", 18, 0.5),
			new Keyword("song", 18, 0.5),
			new Keyword("fun", 18, 0.3),
			new Keyword("gig", 18, 0.5),
			new Keyword("band", 18, 0.5),
			new Keyword("group", 18, 0.5),
			new Keyword("dance", 18, 0.5),
			new Keyword("rave", 18, 0.5),
			new Keyword("mosh", 18, 0.5)
		);
		$search = strtolower($search);
		$length = sizeOf($keywords);
		for ($i = 0; $i < $length; $i++)
		{
			if ($this->isIn($search, $keywords[$i]->word))
			{
				$categoryanalysis[$keywords[$i]->type] += 0.17 * $keywords[$i]->value;
				$start = $this->startsWord($search, $keywords[$i]->word);
				$end = $this->endsWord($search, $keywords[$i]->word);
				if ($start || $end)
				{
					$categoryanalysis[$keywords[$i]->type] += 0.33 * $keywords[$i]->value;
				}
				if ($start && $end)
				{
					$categoryanalysis[$keywords[$i]->type] += 0.5 * $keywords[$i]->value;
				}
			}
		}
		return $categoryanalysis;
	}
	
	function isIn($search, $keyword)
	{
		$slen = strlen($search);
		$klen = strlen($keyword);
		$result = false;
		for ($i = 0; $i < $slen - $klen + 1; $i++)
		{
			if ($keyword == substr($search, $i, $klen)) $result = true;
		}
		return $result;
	}
	
	function startsWord($search, $keyword)
	{
		$slen = strlen($search);
		$klen = strlen($keyword);
		$result = false;
		for ($i = 0; $i < $slen - $klen + 1; $i++)
		{
			if ($keyword == substr($search, $i, $klen))
			{
				if ($i == 0) $result = true;
				else if (substr($search, $i - 1, 1) == " ") $result = true;
			}
		}
		return $result;
	}
	
	function endsWord($search, $keyword)
	{
		$slen = strlen($search);
		$klen = strlen($keyword);
		$result = false;
		for ($i = 0; $i < $slen - $klen + 1; $i++)
		{
			if ($keyword == substr($search, $i, $klen))
			{
				if ($i + $klen == $slen) $result = true;
				else if (substr($search, $i + $klen, 1) == " " || substr($search, $i + $klen, 1) == ",") $result = true;
			}
		}
		return $result;
	}
}

class KeyWord
{
	public $word;
	public $type;
	public $value;
	
	function __construct($word, $type, $value)
	{
		$this->word = $word;
		$this->type = $type;
		$this->value = $value;
	}
}
?>