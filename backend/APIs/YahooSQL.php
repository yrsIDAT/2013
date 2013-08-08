<?php
if (!defined("THINGS2DO")) {
    exit(1);
}
class YahooSQL {
    private $api_version;
    private $url;
    public function __construct() {
        $this->api_version=1;
        $this->url="http://query.yahooapis.com/v$this->api_version/public/yql";
    }
    private function build_select_query($from, $key, $value) {
        $this->query=urlencode("select * from $from where $key=\"$value\"");
        return $this->query;
    }
    public function get_json($query=null) {
        if (!$query) {$query=$this->query;}
        $jsonstring=file_get_contents("$this->url?q=$query&format=json");
        $this->json=(array)json_decode($jsonstring);
        return $this->json;
    }
    public function get_results($jsonarray=null) {
        if (!$jsonarray){$jsonarray=$this->json;}
        $output=Array();
        if (isset($jsonarray['query'])) {
            $query=(array)$jsonarray['query'];
            $output['created']=strtotime($query['created']);
            $results=(array)$query['results'];
            if (isset($results['yctCategories'])) {
                $output['categories']=Array();
                foreach ($results['yctCategories']->yctCategory as $category) {
                    $category=(array)$category;
                    if (isset($category['content'])) {
                        $output['categories'][$category['content']]=(float)$category['score'];
                    }
                    else {
                        if (isset($output['categories'][0])) {
                            $output['categories'][$category[0]]=(float)$output['categories'][0];
                            unset($output['categories'][0]);
                        }
                        else {
                            ARRAY_PUSH($output['categories'],$category[0]);
                        }
                    }
                    
                }
            }
            else {
                $output['categories']=null;
            }
            if (isset($results["entities"])) {
                $output["entities"]=Array();
                foreach ($results['entities']->entity as $entity) {
                    $entity=(array)$entity;
                    if (isset($entity['text'])) {
                        $output['entities'][$entity['text']->content]=(float)$entity['score'];
                    }
                    if (isset($entity['wiki_url'])){$output['wiki']=$entity['wiki_url'];}
                }
            }
            else {
                $output["entities"]=null;
            }
        }
        return $output;
    }
    public function do_contentanalysis_query($value) {
        $this->build_select_query("contentanalysis.analyze", "text", $value);
        $this->get_json();
        return $this->get_results();
    }
    
}
?>