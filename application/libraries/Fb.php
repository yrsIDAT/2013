<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
require_once 'fb-php-sdk/facebook.php';
class Fb {
	private $fb_app_id = '';
	private $fb_app_secret = '';
	private $fb = null;
    private $fb_redirectUri = '';
    private $fb_logout_redirect = '';

	public $user;
	public $logout_url;
	private function signedRequest(){
		$sr = isset($_REQUEST['signed_request'])?$_REQUEST['signed_request']:'';
		setcookie("fb_signed_request_val", $sr, time()+(60*60*24),'/',$_SERVER['SERVER_NAME'],1);
		// set_cookie($cookie);		
	}
    public function init_facebook(){
    	$this->signedRequest();
    	$this->load_config_file();
    	$this->get_fb_object();
        $url =  $this->fb_redirectUri;
    	if (!$this->have_user()){
    		//$this->redirect_user($this->login_url());
            $this->user = FALSE;
            $this->login_url = $this->login_url();
    	} else {
    	$this->user = $this->fb->getUser();
    	$this->logout_url = $this->logout_url();
        }
    }
    public function api(){
    	return call_user_func_array( array($this->fb,'api'), func_get_args() );
    }
    
    public function fql($fql){
    	if ($this->fb){
			try {
				$sql = $fql;
				$ret_obj = $this->fb->api(array(
					'method' => 'fql.query',
					'query' => $fql,
				));
				return $ret_obj;
			} catch(FacebookApiException $e) {
				$this->logErr($e);
			}   
		}
	}

    private function redirect_user($url){
    	echo "<script>window.top.location.href='{$url}'</script>";
    	exit();
    }
    private function logErr($err){
		error_log($err->getType());
		error_log($err->getMessage());
    }
    private function load_config_file() {
    	$CI =& get_instance();
    	$CI->config->load('facebook');
    	$this->fb_app_id = $CI->config->item('fbAppId');
    	$this->fb_app_secret = $CI->config->item('fbSecret');
    	$this->fb_app_canvas_url = $CI->config->item('fbCanvas');
    	$this->fb_app_scope = $CI->config->item('fbScope');
    	$this->fb_app_display = $CI->config->item('fbDisplay');
        //$this->fb_redirectUri = $CI->config->item('fbRedirect');
        $this->logout_url = $CI->config->item('fbLogoutRedirect');
    }
    private function get_fb_object(){
		$this->fb = new Facebook(array(
		  'appId'  => $this->fb_app_id,
		  'secret' => $this->fb_app_secret,
		));
    }

    private function login_url(){
    	$params = array(
    			'scope' => $this->fb_app_scope,
    			'redirect_uri' => $this->fb_app_canvas_url
    			
    		);
        //'display' => $this->fb_app_display
    	return $this->fb->getLoginUrl($params);
    }

    private function logout_url(){
    	return $this->fb->getLogoutUrl( array(
                'redirect_uri' => $this->fb_logout_redirect
           ) );
    }

    private function have_user(){
    	$user = $this->fb->getUser();
    	if ($user) {
			try {
				$user_profile = $this->fb->api('/me');
				return true;
			} catch (FacebookApiException $e) {
				$this->logErr($e);
				return false;
			}
    	}else{
    		return false;
    	}
    }

}
