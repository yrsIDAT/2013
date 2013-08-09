<?php

class Extpages extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->fb->init_facebook();
		$this->load->helper('url');
	}


	public function view($page = 'index')
	{
	
		if ( ! file_exists('Site/'.$page))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		$data['title'] = ucfirst($page); // Capitalize the first letter

		//$this->load->view('templates/header', $data);
		//$this->load->view('/Site/'.$page, $data);
		//$this->load->view('templates/footer', $data);
		$filename = 'Site/' . $page . '';
		$finfo = finfo_open(FILEINFO_MIME);
		print_r($finfo);
		$mime =  finfo_file($finfo, $filename);
echo $mime;
		//echo file_get_contents($filename);
		$this->output
    ->set_content_type($mime)
    ->set_output(file_get_contents($filename));
finfo_close($finfo);
	}
}
