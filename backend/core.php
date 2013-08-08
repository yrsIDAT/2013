<?php
// Begin Standard Definitions
define("THINGS2DO", true);
$root=$_SERVER['DOCUMENT_ROOT'].'/';
include "$root/backend/keystore.php";
include "$root/backend/apikeys.php";
// End Standard Definitions

include "$root/backend/APIs/YahooSQL.php";
include "$root/backend/APIs/geobytes.php";
include "$root/backend/APIs/alcemyapi.php";

define("QUERY_MODE", "GET"); //what method do we recieve a query
define("LAT_LON_MODE", "GET"); //what method do we recieve the latitude and logitude

class things2do {
    // The master class
    public $query;
    public $analysis;
    public $location;
    public $category;
    public function __construct() {
        $this->YQL=new YahooSQL();
        $this->GEOLocation=new LocationManager();
        $this->alc=new AlcAPI();
        $qname='q';
        $qtype=QUERY_MODE=="GET"?$_GET:$_POST;
        $this->query=isset($qtype[$qname])?$qtype[$qname]:"";
        $this->check_query();
    }
    private function check_query() {
        if ($this->query) {
            $this->dostuff();
        }
    }
    protected function dostuff() {
        // use yahoo content analysis
        $this->analysis=$this->YQL->do_contentanalysis_query($this->query);
        // use location
        $this->location=$this->GEOLocation->try_all_methods();
        // use alchemyapi
        $this->category=$this->alc->get_category($this->query);
    }
}
var_dump(new things2do());
var_dump($key);
?>