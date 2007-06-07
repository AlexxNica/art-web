<?php

class Account extends Controller{
	
	function Account(){
		parent::Controller();
		
		$this->load->helper('form');
		$this->load->helper('url');
		
		$this->load->model('User_model','User');
	}
	
	function index(){
		$data['null'] = null;
		
		if ($this->authentication->authenticate())
			$data['auth'] = "Logged In!";
		else 
			$data['auth'] = "Not Logged In!";
			
		$this->layout->buildPage('account/index', $data);
	}
	
	function login(){
		$user = $_POST['username'];
		
		if ($this->User->exists_user($user)){
			$user = $this->User->get_user_by_username($user);
			$this->authentication->login($user->uid);
			$this->authentication->set_permissions($this->authentication->settings['regular_user']);
		}
		
		redirect('/account','refresh');
	}
	
	function logout(){
		$this->authentication->authenticate();
		$this->authentication->logout();
		
		redirect('/account','refresh');
	}
}

?>