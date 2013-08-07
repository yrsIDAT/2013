<?php
if (!defined("THINGS2DO")) {
    exit(1);
}
class AlcAPI {
    private $url;
    private $api_key;
    public function __construct() {
        $this->url="http://access.alchemyapi.com";
        $this->api_key="410cfc5dc59550b6d9fa62946e01379380d746ff";
    }
    public function get_category($text) {
        // find the category they are looking for
        $url=$this->make_query("/calls/text/TextGetCategory", $text);
        return $this->get_json($url, Array("category"=>"score"));
    }
    public function get_keywords($text) {
        // words that influenced decisions
        $url=$this->make_query("/calls/text/TextGetRankedKeywords", $text);
        return $this->get_json($url, Array(0=>"keywords"));
    }
    private function make_query($url, $text, $data=Array()) {
        $q=str_replace('&amp;', '&', http_build_query(array_merge($data, Array("apikey"=>$this->api_key, "text"=>$text, "outputMode"=>"json"))));
        return $this->url.$url."?".$q;
    }
    private function get_json($url, $format=null) {
        $json=json_decode(file_get_contents($url), true);
        $output=Array();
        if ($format) {
            foreach ($format as $key=>$value) {
                if ($key===0) {
                    $output=$json[$value];
                }
                else {
                    $output[$json[$key]]=$json[$value];
                }
            }
        }
        else {
            $output=$json;
        }
        return $output;
    }
}
?>