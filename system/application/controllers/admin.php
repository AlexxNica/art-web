<?php

class Admin extends Controller{
	function Admin(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('License_model','License');
		$this->load->model('Category_model','Category');
		$this->load->model('Download_model','Download');
		$this->load->model('Moderation_model','Moderation');
	}
	
	function index(){
		
		$this->layout->buildPage();
	}
	
	function moderate(){
		
		$this->layout->buildPage('admin/moderate');
	}
}

?>