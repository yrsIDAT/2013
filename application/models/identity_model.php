<?php
class Identity_model extends CI_Model {

	public function __construct()
	{
		$this->load->database();
		$this->load->library('quickcache');
		$this->fb->init_facebook();
		$this->rtApiKey = ''; //key hidden
		$this->lfmApiKey = ''; //key hidden
	}

	public function getMovieGenres() {
		$user = $this->fb->user;
		//$this->updateFacebookProfile();
		$query = $this->db->get_where('facebook', array('uid' => $user));
		$row = $query->row_array();
		return $row['movies_genres'];
	}

	public function updateProfile() {
		$user = $this->fb->user; // get facebook id

		// lookup new facebook profile
		$fbquery = $this->fb->fql('SELECT name, birthday, movies, music  FROM user WHERE uid = me()');
		$username = $fbquery[0]['name'];
		$movies = $fbquery[0]['movies'];
		$music = $fbquery[0]['music'];
		$birthday = strtotime($fbquery[0]['birthday']);
		$userdata = array(
				'uid' => $user,
				'name' =>$username,
				'fetchdate' => date("Y-m-d H:i:s"),
				'movies' => $movies,
				'music' => $music,
				'birthday' => date("Y-m-d H:i:s",$birthday)
				);
		
		$query = $this->db->get_where('facebook', array('uid' => $user));
		$row = $query->row_array();

		if($query->num_rows() == 0) {  // insert into DB if new

			$insert = $this->db->insert('facebook',$userdata);
		} else { // update profile
			$this->db->where('uid',$this->fb->user);
			$update = $this->db->update('facebook',$userdata);
		}

		// process genres 
		$this->analyseProfile();

		$query = $this->db->get_where('facebook', array('uid' => $user));
		$row = $query->row_array();
		/*echo "<pre>";
		print_r(unserialize($row['movies_genres']));
		print_r(unserialize($row['music_genres']));
		echo "</pre>";*/
	}

	private function analyseProfile() {
		$this->analyseProfile_Movies();
		$this->analyseProfile_Music();

	}

	private function analyseProfile_Movies() {
		// analyse movies to get new genres
		$query = $this->db->get_where('facebook', array('uid' => $this->fb->user));
		$row = $query->row_array();
		
		$movies = explode(",",$row['movies']);
		$limit = 25;

		$i = 0;
		$favouriteGenres = array();
		foreach($movies as $m) { // foreach movie in facebook profile 
			// find the movie id using RT api + caching response
			$url = 'http://api.rottentomatoes.com/api/public/v1.0/movies.json?q='.urlencode($m).'&page_limit=1&page=1&apikey=' . $this->rtApiKey;
			$cachecount = 0;
			if($this->quickcache->isCached($url)) {
				$cachecount++;
				//echo $this->quickcache->retrieveFromCache($url);
				$json1 = json_decode($this->quickcache->retrieveFromCache($url));
			} else {
				$contents = file_get_contents($url);
				$json1 = json_decode($contents);
				$this->quickcache->addToCache($url,$contents);
			}

			// then try and get a genre for the movie
			if(isset($json1->movies[0]->links->self)) {
				$url = $json1->movies[0]->links->self . '?apikey=' . $this->rtApiKey;
				if($this->quickcache->isCached($url)) {
					$cachecount++;
					$json2 = json_decode($this->quickcache->retrieveFromCache($url));
				} else {
					$contents = file_get_contents($url);
					$json2 = json_decode($contents);
					$this->quickcache->addToCache($url,$contents);
				}

				foreach($json2->genres as $genre) { // tally each genre
					if(isset($favouriteGenres[$genre])) {
						$favouriteGenres[$genre]++;
					} else {
						$favouriteGenres[$genre] = 1;
					}
				}
			}
			$i++;
			if($i>=$limit) { break; }
			if($cachecount < 2) { // if no cache used then delay iteration to avoid hitting request limit
				sleep(1);
			}
		}
		
		// save genre tally
		$updateData = array(
			'movies_genres'=>serialize($favouriteGenres)
			);
		$this->db->where('uid',$this->fb->user);
		$update = $this->db->update('facebook',$updateData);
	}

	private function analyseProfile_Music() {
		// analyse movies to get new genres
		$query = $this->db->get_where('facebook', array('uid' => $this->fb->user));
		$row = $query->row_array();
		
		$music = explode(",",$row['music']);
		$limit = 25;

		$i = 0;
		$favouriteGenres = array();
		foreach($music as $m) { // foreach movie in facebook profile 
			// find the artist info using lastfm + caching response
			$url = 'http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist='.urlencode($m).'&api_key=' . $this->lfmApiKey . '&format=json';
			$cachecount = 0;
				$json2 = NULL;
				if($this->quickcache->isCached($url)) {
					$cachecount++;
					$json2 = json_decode($this->quickcache->retrieveFromCache($url));
				} else {
					$contents = file_get_contents($url);
					$json2 = json_decode($contents);
					$this->quickcache->addToCache($url,$contents);
				}
				if(isset($json2->artist->tags->tag) && is_array($json2->artist->tags->tag)) { 
					// artist found
					$tags = $json2->artist->tags->tag;
					//echo"<pre>";print_r($tags);echo"</pre>";
					
						//print_r($tags);
						foreach($tags as $tag) { // tally each genre
							$key = $tag->name;
							if(isset($favouriteGenres[$key])) {
								$favouriteGenres[$key]++;
							} else {
								$favouriteGenres[$key] = 1;
							}
						}
					
				}
			
			$i++;
			if($i>=$limit) { break; }
			if($cachecount < 1) { // if no cache used then delay iteration to avoid hitting request limit
				//sleep(1);
			}
		}
		// save genre tally
		$updateData = array(
			'music_genres'=>serialize($favouriteGenres)
			);
		$this->db->where('uid',$this->fb->user);
		$update = $this->db->update('facebook',$updateData);
	}


}
