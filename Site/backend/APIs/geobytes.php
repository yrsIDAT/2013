<?php
if (!defined("THINGS2DO")) {
    exit(1);
}
class LocationManager {
    public $lat;
    public $lon;
    function __construct() {
        
    }
    public function try_all_methods() {
        $this->get_geolocation();
        if ($this->lat==null||$this->lon==null) {
            $this->get_web_location();
        }
        return Array($this->lat, $this->lon);
    }
    public function get_geolocation() {
        $qtype=LAT_LON_MODE=="GET"?$_GET:$_POST;
        if (isset($qtype["lat"])) {
            $this->lat=$qtype["lat"];
        }
        if (isset($qtype["lon"])) {
            $this->lon=$qtype["lon"];
        }
    }
    public function get_web_location() {
        $ip=$_SERVER["REMOTE_ADDR"];
        $tags = get_meta_tags("http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress=$ip");
        $this->lat=$tags['latitude'];
        $this->lon=$tags['longitude'];
    }
}
//    http://maps.googleapis.com/maps/api/geocode/json?latlng=50.3756457,-4.1410831&sensor=false
?>