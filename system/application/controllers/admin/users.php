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
				$data['permissions'] = $this->authentication->load_permissions($data['user']->acl);
				$this->layout->buildPage('admin/user_show',$data);
				return false;
				
				
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
	
	function edit(){
		
		// Verify if user is allowed to change user permissions
		if (!$this->authentication->is_allowed(EDIT_USER) AND 
			!$this->authentication->is_allowed(DEL_USER)){
			show_error('Not Allowed!');
			return false;
		}
		
		if ($_POST){
			$user_id = $this->input->post('user_id');
			
			if ($this->authentication->is_it_me($user_id)){
				flashset('notice','You can\'t edit your own permissions');
				redirect('admin/users/'.$user_id,'refresh');
				return false;
			}
			
			$tmp_p = $this->input->post('permissions');
			if (!$tmp_p) $tmp_p = array();
			$new_permissions = $this->config->config['authentication']['permissions'];
			
			foreach($tmp_p as $key => $value){
				$new_permissions[$key] = true;
			}
			
			$new_acl = $this->authentication->toBitmask($new_permissions);
			
			$this->User->set_acl($user_id,$new_acl);
			
			redirect('admin/users/'.$user_id,'refresh');
			
		} else {
			redirect('admin/users','refresh');
		}
	}
}

?>