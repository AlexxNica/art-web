<?php

class Browse extends Controller{
	function Browse(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('Category_model','Category');
	}
	
	function index(){
		$category_n = $this->uri->segment(2);
		$sub_category_n = $this->uri->segment(3);
		$this->artwork_id = $this->uri->segment(4);
		
		$this->_handle_get_variables();
		
		// prepare pagination
		$num_elements = 10;
		
		$page = $this->input->get('page');
		if (!$page) $page = 1;
		
		$offset = $num_elements*($page-1);
		// --
		
		if ($this->artwork_id){
			
			$artwork = $this->Artwork->find($this->artwork_id,TRUE);
			$data['artwork'] = $artwork;
			$data['artwork_rating'] = $this->Vote->rating($this->artwork_id,VOTE_NORMAL);
			if ($this->authentication->is_logged_in()){
				$data['artwork_my_rating'] = $this->Vote->rating($this->artwork_id,VOTE_NORMAL,$this->authentication->get_uid());
			}
			
			$this->layout->buildPage('browse/artwork_page',$data);
			return false;
			
		} elseif ($sub_category_n){ // if subcategory is set
			
			$category = $this->Category->find_by_name($sub_category_n);
			
			if (!$category){
				flashset('notice','There isn\'t a category with that name');
				redirect('browse','refresh');	
			}
			
			$images = $this->Artwork->find_by_category($category->id,$num_elements,$offset,'date_accepted desc');
			
		} elseif ($category_n){ // else if category is set
			
			$category = $this->Category->find_by_name($category_n);
			
			// if there isn't any category with that name redirect to the base
			if (!$category){
				flashset('notice','There isn\'t a category with that name');
				redirect('browse','refresh');	
			} 
			
			$categories = $this->Category->find_by_parent($category->id);
			
			// if there is any sub_category with that parent
			if (count($categories)>0){
				foreach($categories as $category){
					$tmp[] = $category->id;
				}
				$images = $this->Artwork->find_by_category($tmp,$num_elements,$offset,'date_accepted desc');
			} else {
				$images = array();
			}
			
		} else {  // if neither id, subcategory, or category were set
			$images = $this->Artwork->get_public($num_elements,$offset,'date_accepted desc');
		}
		
		
		
		
		$data['artworks'] = $images;
		$this->layout->buildPage('browse/browse',$data);
	}
	
	
	// browse the gallery of a user;
	function user(){
		$username = $this->uri->segment(3);
		if (!$username){
			redirect('browse','refresh');
		}
		
		echo $username;
	}
	
	
	function _handle_get_variables(){
		if ($this->input->get('rating')){
			$this->_rating();
		}
	}
	
	function _rating(){
		$this->authentication->authenticate();
		
	 	$rating = $this->input->get('rating',TRUE);
		if ($rating>=0 AND $rating <=5){
			$fields = array(
				'user_id' => $this->authentication->get_uid(),
				'artwork_id' => $this->artwork_id,
				'kind'	=> VOTE_NORMAL,
				'vote'	=> $rating);
				
			$this->Vote->add($fields);
		}
	}
}

?>