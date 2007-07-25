<?php

class Admin extends Controller{
	function Admin(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('License_model','License');
		$this->load->model('Category_model','Category');
		$this->load->model('Download_model','Download');
		$this->load->model('Moderation_model','Moderation');
		
		
		//$this->authentication->authenticate();
	}
	
	function index(){
		
		redirect('','refresh');
	}
	
	function moderate(){
		// Verify is user is allowed to moderate artwork
		if (!$this->authentication->is_allowed(MODERATE_ARTWORK)){
			show_error('Not Allowed!');
			return false;
		}
		
		// prepare pagination
		$num_elements = 10;
		
		$page = $this->input->get('page');
		if (!$page) $page = 1;
		
		$offset = $num_elements*($page-1);
		// --
		
		$data['moderation_queue'] = $this->Moderation->list_queue($num_elements,$offset);
			
		$this->layout->buildPage('admin/moderate',$data);
	}
}

?>