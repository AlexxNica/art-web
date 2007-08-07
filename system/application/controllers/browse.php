<?php

class Browse extends Controller{
	function Browse(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('Category_model','Category');
	}
	
	function index(){
		/* in case the url scheme used was (ago)/browse/<category>/<sub_category>/id */
		if($this->uri->segment(1)=='browse'){
			$this->category = $this->uri->segment(2);
			$this->subcategory = $this->uri->segment(3);
			$this->artwork_id = $this->uri->segment(4);
		} else {
			$this->category = $this->uri->segment(1);
			$this->subcategory = $this->uri->segment(2);
			$this->artwork_id = $this->uri->segment(3);
		}
		
		/* sets the initial config of pagination with 10 elements per page*/
		$this->_pagination(10);
		
		/* check if there is any GET Variable to be processed */
		$this->_handle_get_variables();
		
		// calls the browsing action
		$this->artwork();
	}
	
	/**
	 * handles the (ago)/<category>/<sub_category>/<id> scheme
	 * and calls the corresponding views
	 */
	function artwork(){
		// in case the action was called was artwork
		if($this->uri->segment(1)=='browse'){
			$this->category = $this->uri->segment(2);
			$this->subcategory = $this->uri->segment(3);
			$this->artwork_id = $this->uri->segment(4);
		}
		
		if ($this->artwork_id){
			
			$this->_show_artwork();
			
		} elseif ($this->subcategory){ // if subcategory is set
			
			$images = $this->_show_subcategory();
			
		} elseif ($this->category){ // else if category is set
			
			$images = $this->_show_category();
			
		} else {  // if neither id, subcategory, or category were set
			$images = $this->Artwork->get_public(	$this->pagination['num_elements'],
													$this->pagination['offset'],
													$this->pagination['orderby']);
		}
		
		
	
		$data['artworks'] = $images;
		$this->layout->buildPage('browse/browse',$data);
	}
	
	
	/**
	 * Show Artwork
	 */
	function _show_artwork(){
		
		$artwork = $this->Artwork->find($this->artwork_id,TRUE);
		$data['artwork'] = $artwork;
		$data['artwork_rating'] = $this->Vote->rating($this->artwork_id,VOTE_NORMAL);
		
		/* if the user is logged in, gets the user rating in the work */
		if ($this->authentication->is_logged_in()){
			$data['artwork_my_rating'] = $this->Vote->rating($this->artwork_id,VOTE_NORMAL,$this->authentication->get_uid());
		}
		
		$this->layout->buildPage('browse/artwork_page',$data);
		return false;
	}
	
	
	/**
	 * Show subcategory
	 * 
	 * lists all the artwork of a subcategory
	 */
	function _show_subcategory(){
		$category = $this->Category->find_by_name($this->subcategory);
		
		if (!$category){
			flashset('notice','There isn\'t a category with that name');
			redirect('browse','refresh');	
		}
		
		$images = $this->Artwork->find_by_category(	$category->id,
													$this->pagination['num_elements'],
													$this->pagination['offset'],
													$this->pagination['orderby']);
													
		return $images;
	}
	
	
	/**
	 * Show Category
	 * 
	 * lists all the artwork of a category
	 */
	function _show_category(){
		$category = $this->Category->find_by_name($this->category);
		
		// if there isn't any category with that name redirect to the base
		if (!$category){
			flashset('notice','There isn\'t a category with that name');
			redirect('browse','refresh');	
		} 
		
		$categories = $this->Category->find_by_parent($category->id);
		
		// if there is any subcategory with that parent
		if (count($categories)>0){
			// group categories in a array
			foreach($categories as $category){
				$group[] = $category->id;
			}
			
			$images = $this->Artwork->find_by_category(	$group,
														$this->pagination['num_elements'],
														$this->pagination['offset'],
														$this->pagination['orderby']);
		} else {
			$images = array();
		}
		
		return $images;
	}
	
	
	/**
	 *  Browse a users gallery
	 */
	function user(){
		$username = $this->uri->segment(3);
		if (!$username){
			redirect('browse','refresh');
		}
		
		echo $username;
	}
	
	
	/**
	 * handle get variables
	 * 
	 * this method searches for GET Variables 
	 * and calls the methods which should handle them.
	 * 
	 */
	function _handle_get_variables(){
		if ($this->input->get('rating')){
			$this->_rating();
		}
	}
	
	
	/**
	 * _rating - handles the rating process of artworks
	 * 
	 * it should be called by _handle_get_variables
	 */
	function _rating(){
		$this->authentication->authenticate();
		
		// if artwork_id wasn't set it is probably a good idea to stop here!
		if (!$this->artwork_id) return false;
		
	 	$rating = $this->input->get('rating',TRUE);
		if ($rating>=1 AND $rating <=5){
			
			$fields = array(
				'user_id' => $this->authentication->get_uid(),
				'artwork_id' => $this->artwork_id,
				'kind'	=> VOTE_NORMAL,
				'vote'	=> $rating);
				
			$this->Vote->add($fields);
		}
	}
	
	
	/**
	 * _pagination - set variables necessary for the pagination
	 */
	function _pagination($num_elements = 10){
		$this->pagination['num_elements'] = $num_elements;
		
		$page = $this->input->get('page');
		if (!$page) $page = 1;
		
		$this->pagination['offset'] = $num_elements*($page-1);
		$this->pagination['page'] = $page;
		
		$this->pagination['orderby'] = 'date_accepted desc';
	}
}

?>