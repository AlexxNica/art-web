<?php

class User extends Controller{
	function User(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('License_model','License');
		$this->load->model('Category_model','Category');
		$this->load->model('Download_model','Download');
		$this->load->model('Moderation_model','Moderation');
		$this->load->model('User_model','User');
		
		// check if user exists!!
		$this->username = $this->uri->segment(2);
		
		if (!$this->username) {
			redirect('','refresh');
			die();
		}

		$this->user = $this->User->find_by_username($this->username);

		if (!$this->user){
			show_error('There isn\'t any user with nickname');
			die();
		}
	}
	
	function index(){
		
		$data['user'] = $this->user;
		$works = $this->Artwork->find_by_user($this->user->uid,10,0,'id desc');
		if (count($works)>0){
			$data['latest'] = $works[0];
			array_shift($works);
		}
		$data['works'] = $works;
		
		$this->layout->buildPage('user/user_page',$data);
	}
	
	function gallery(){
		
		$data['user'] = $this->user;
		$data['works'] = $this->Artwork->find_by_user($this->user->uid,10,0,'id desc');
		$this->layout->buildPage('user/gallery',$data);
	}
	
	/**
	 * profile section
	 * 
	 * user can edit info related to its identity
	 */
	function profile(){
		$this->authentication->authenticate();
		
		if ($this->authentication->get_uid() == $this->user->uid)
			echo "you are the owner";
		else
			echo "get lost!!!";
	}
}

?>