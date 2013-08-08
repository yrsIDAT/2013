<?php

class DataGrab extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('datagrab_model');
	}


	public function refreshAll()
	{
		$this->datagrab_model->completeRefresh();
		//$this->datagrab_model->completeRefresh();
	}

}
