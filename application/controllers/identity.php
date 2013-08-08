<?php

class Identity extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');	
		$this->load->model('identity_model');
		$this->fb->init_facebook();
	}


	public function portal()
	{
		// check login
		if($this->fb->user == FALSE) {
			echo '<h1>Please <a href="' . $this->fb->login_url .'">click here to log in via Facebook</a></h1>';
		} else {
			// process profile
			$data['fb_logged_in'] = TRUE;
			$user = $this->fb->fql('SELECT name, uid FROM user WHERE uid = me() ');
			if($user) {
				//$data['fb_name'] = $user[0]['name'];
				//$data['fb_uid'] = $user[0]['uid'];
				//$this->identity_model->updateProfile();

				//print_r($this->identity_model->getMovieGenres());
				$data['title'] = 'Processing...'; // Capitalize the first letter

				//$this->load->view('templates/header', $data);
				$this->load->view('identity/processing', $data);
				//$this->load->view('templates/footer', $da
			}
			
		}

		

	}

	public function analyse()
	{
		// check login
		if($this->fb->user == FALSE) {
			echo '<h1>Please <a href="' . $this->fb->login_url .'">click here to log in via Facebook</a></h1>';
		} else {
			// process profile
			$data['fb_logged_in'] = TRUE;
			$user = $this->fb->fql('SELECT name, uid FROM user WHERE uid = me() ');
			if($user) {
				
				$this->identity_model->updateProfile();
				$this->output
    ->set_content_type('application/json')
    ->set_output(json_encode(TRUE));
				//print_r($this->identity_model->getMovieGenres());
			} else {
				$this->output
    ->set_content_type('application/json')
    ->set_output(json_encode(FALSE));
			}
			
		}

	}

	public function logout() {
		//echo 'hi';
		$logouturl = $this->fb->logout_url ;
		//echo $logouturl;
		session_destroy();
		
		redirect($logouturl);
		echo '<h1>Redirecting...</h1>';
	}
}
