<?php
/*
// Begin Standard Definitions
define("THINGS2DO", true);
include "backend/keystore.php";
include "backend/apikeys.php";
// End Standard Definitions

include "backend/APIs/YahooSQL.php";
include "backend/APIs/alcemyapi.php";

define("QUERY_MODE", "GET"); //what method do we recieve a query
define("LAT_LON_MODE", "GET"); //what method do we recieve the latitude and logitude
*/

class Search {
    // The master class
	public $categoryanalysis;
    public function __construct($searchstring) {
		$this->categoryanalysis = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        /*$YQL=new YahooSQL();
        $GEOLocation=new LocationManager();
        $alc=new AlcAPI();
        // use yahoo content analysis
        $analysis=$YQL->do_contentanalysis_query($searchstring);
        // use alchemyapi
        $category=$alc->get_category($searchstring);
		$this->categoryanalysis = categorytoid($analysis, $category);*/
		$this->categoryanalysis = $this->MikeSearchStringAlgorithm($this->categoryanalysis, $searchstring);
    }
	
	function categorytoid($analysis, $category)
	{
		
	}
	
	function MikeSearchStringAlgorithm($categoryanalysis, $search)
	{
		$keywords = array(
			new Keyword("beach", 1, 1),
			new Keyword("sun", 1, 1),
			new Keyword("tan", 1, 1),
			new Keyword("sea", 1, 1),
			new Keyword("swim", 1, 1),
			new Keyword("wave", 1, 1),
			new Keyword("bathe", 1, 1),
			new Keyword("sand", 1, 1),
			new Keyword("bikini", 1, 1),
			new Keyword("cinema", 2, 1),
			new Keyword("film", 2, 1),
			new Keyword("action", 2, 1),
			new Keyword("plot", 2, 1),
			new Keyword("watch", 2, 1),
			new Keyword("cafe", 3, 1),
			new Keyword("restaurant", 3, 1),
			new Keyword("food", 3, 1),
			new Keyword("eat", 3, 1),
			new Keyword("hungry", 3, 1),
			new Keyword("drink", 3, 1)
		);
		$search = strtolower($search);
		$length = sizeOf($keywords);
		for ($i = 0; $i < $length; $i++)
		{
			if ($this->isIn($search, $keywords[$i]->word))
			{
				$categoryanalysis[$keywords[$i]->type] += 0.025 * $keywords[$i]->value;
				$start = $this->startsWord($search, $keywords[$i]->word);
				$end = $this->endsWord($search, $keywords[$i]->word);
				if ($start || $end)
				{
					$categoryanalysis[$keywords[$i]->type] += 0.05 * $keywords[$i]->value;
				}
				if ($start && $end)
				{
					$categoryanalysis[$keywords[$i]->type] += 0.1 * $keywords[$i]->value;
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