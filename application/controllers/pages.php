<?php

class Pages extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fb->init_facebook();
	}


	public function view($page = 'home')
	{
		if($this->fb->user == FALSE) {
			$data['fb_logged_in'] = FALSE;
			$data['fb_login_url'] = $this->fb->login_url;
		} else {
			$data['fb_logged_in'] = TRUE;
			$user = $this->fb->fql('SELECT name, uid FROM user WHERE uid = me() ');
			$data['fb_name'] = $user[0]['name'];
			$data['fb_uid'] = $user[0]['uid'];
			$data['fb_logout_url'] = $this->fb->logout_url;
		}

		if ( ! file_exists('application/views/pages/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		//$this->load->view('templates/header', $data);
		$this->load->view('pages/'.$page, $data);
		//$this->load->view('templates/footer', $data);

	}
}
