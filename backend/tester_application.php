<?php
define("THINGS2DO", true);
define("QUERY_MODE", "GET");
define("LAT_LON_MODE", "GET");

include "apiRecievers/YahooSQL.php";
include "apiRecievers/Location.php";
include "apiRecievers/alcemyapi.php";

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
$T2D=new things2do();
?>
<html>
<head>
<script>
var x=document.getElementById("demo");
function getLocation()
  {
  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition);
    }
  else{x.innerHTML="Geolocation is not supported by this browser.";}
  }
function showPosition(position)
  {
  document.getElementsByName("lat")[0].value=position.coords.latitude
  document.getElementsByName("lon")[0].value=position.coords.longitude
  }
  getLocation()
</script>
</head>
<body>
<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="get">
<input type="text" value="<?php echo $T2D->query;?>" name="q">
<input type="hidden" name="lat">
<input type="hidden" name="lon">
<input type="submit" value="search">
</form>
<?php
echo "<hr style='height:5px;background-color:black;'><b>Analysis</b>";
var_dump($T2D->analysis);
echo "<hr style='height:5px;background-color:black;'><b>Location</b>";
var_dump($T2D->location);
echo "<hr style='height:5px;background-color:black;'><b>Category</b>";
var_dump($T2D->category);
echo "<hr style='height:5px;background-color:black;'><b>Keywords</b>";
var_dump($T2D->alc->get_keywords($T2D->query));
echo "<hr style='height:5px;background-color:black;'><b>Concepts</b>";
var_dump($T2D->alc->get_concepts($T2D->query));
echo "<hr style='height:5px;background-color:black;'><b>Entities</b>";
var_dump($T2D->alc->get_entities($T2D->query));
?>
</body>
</html>