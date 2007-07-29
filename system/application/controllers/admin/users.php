<?php

class Users extends Controller{
	function Users(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('License_model','License');
		$this->load->model('Category_model','Category');
		$this->load->model('Download_model','Download');
		$this->load->model('Moderation_model','Moderation');
		$this->load->model('User_model','User');
		
		
		//$this->authentication->authenticate();
	}
	
	function index(){
		$id = $this->uri->segment(3);
		
		// Verify if user is allowed to change user permissions
		if (!$this->authentication->is_allowed(EDIT_USER) AND 
			!$this->authentication->is_allowed(DEL_USER)){
			show_error('Not Allowed!');
			return false;
		}
		
		// prepare pagination
		$num_elements = 10;
		
		$page = $this->input->get('page');
		if (!$page) $page = 1;
		
		$offset = $num_elements*($page-1);
		// --
		
		if ($_POST){
			$search = $this->input->post('search');
			$users = $this->User->search_by_username($search,$num_elements,$offset);
		} else {
			// in case a user was selected
			if (@$id) {
				$data['user'] = $this->User->find($id);
				
			} else { // else list all users
				$users = $this->User->get_all($num_elements,$offset);
			}
		}
		
		if (count(@$users)>0) {
			$data['users'] = $users;
		} else {
			$data['users'] = false;
		}
		
		$this->layout->buildPage('admin/users',$data);
	}
}

?>