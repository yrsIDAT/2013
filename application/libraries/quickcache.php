<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class QuickCache {
	private $ci;
	public function __construct() {
		$this->ci =& get_instance();
	}

	public function isCached($url) {
		$query = $this->ci->db->get_where('cache', array('url' => $url));

		if($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function addToCache($url,$content) {
		$data = array(
			'url' => $url,
			'contents' => $content
			);
		return $this->ci->db->insert('cache',$data);
	}

	public function retrieveFromCache($url) {

		$query = $this->ci->db->get_where('cache', array('url' => $url));

		if($query->num_rows() > 0) {
			$row = $query->row_array();
			return $row['contents'];
		} else {
			return false;
		}
	}
}

?>