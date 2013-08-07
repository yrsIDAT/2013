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
<input type="text" value="<?php echo $T2D->query;?>" name="q" style="width:350px;">
<input type="hidden" name="lat">
<input type="hidden" name="lon">
<input type="submit" value="search">
</form>
<h2>From Your search, here is a breakdown</h2>
<?php
if ($T2D->analysis['categories']) {
    echo "<h3>Categories Found</h3>";
    foreach ($T2D->analysis['categories'] as $cat=>$score) {
        echo "<b>$cat</b> Score = $score<br>";
    }
}
if ($T2D->category) {
    if (!isset($T2D->category['unknown'])) {
        $cat=key($T2D->category);
        echo "<b>$cat</b> Score = ".$T2D->category[$cat]."<br>";
    }
}
?>
<h3>Keywords (that influenced the decision)</h3>
<?php
foreach ($T2D->alc->get_keywords($T2D->query) as $pack) {
    echo "<b>".$pack['text']."</b> Score = ".$pack['relevance']."<br>";
}
?>
<h3>Your Location</h3>
Latitude <?php echo $T2D->location[0]; ?><br>
Longitude <?php echo $T2D->location[1]; ?><br>
</body>
</html>