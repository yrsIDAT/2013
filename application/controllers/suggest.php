<?php

class Suggest extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('suggest_model_old');
		$this->fb->init_facebook();
	}


	public function test()
	{

		$data['title'] = 'Thinking of something...'; // Capitalize the first letter
		$data['suggestions'] = $this->suggest_model_old->makeSuggestion('plymouth',50.371389, -4.142222);
		$this->load->view('suggest/header', $data);
		$this->load->view('suggest/results', $data);
		$this->load->view('suggest/footer', $data);

	}
}
