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
		
		if ($this->authentication->is_logged_in())
			$data['auth'] = "Logged In!";
		else 
			$data['auth'] = "Not Logged In!";
		
		$this->layout->buildPage('account/index', $data);
	}
	
	
	/**
	 * login form and user login process
	 */
	function login(){
		$post = $this->input->post('login',TRUE);
		
		if ($post){
			$remember = $this->input->post('remember',TRUE)?true:false;
			$user = $this->input->post('username',TRUE);
			$refer = $this->input->post('refer',TRUE);
			
			if ($this->User->exists($user)){
				$user = $this->User->find_by_username($user);
				$this->authentication->login($user->uid,$remember);
				$this->authentication->set_permissions($this->authentication->settings['regular_user']);
			}
			
			if (!$refer)
				redirect("/account",'refresh');
			else
				redirect($refer,'refresh');  // -> validate and filter
			
		} else {
			
			$data['refer'] = $this->input->get('refer',TRUE);
			$this->layout->buildPage('account/login', $data);
			
		}
	}
	
	/**
	 * Debug funcion
	 */
	function test(){
		$this->authentication->authenticate();
		
		echo "congrats! You're logged in!";
	}
	
	
	/**
	 * err... user is going to be logged out! duh!
	 */
	function logout(){
		$this->authentication->authenticate();
		$this->authentication->logout();
		
		redirect('/account','refresh');
	}
}

?>