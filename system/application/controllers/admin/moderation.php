<?php

class Moderation extends Controller{
	function Moderation(){
		parent::Controller();
		
		$this->load->model('Artwork_model','Artwork');
		$this->load->model('License_model','License');
		$this->load->model('Category_model','Category');
		$this->load->model('Download_model','Download');
		$this->load->model('Moderation_model','Moderation');
		$this->load->model('User_model','User');
		
		
		$this->authentication->authenticate();
		
		// Verify if user is allowed to moderate artwork
		if (!$this->authentication->is_allowed(MODERATE_ARTWORK)){
			show_error('Not Allowed!');
			return false;
		}
		
		$this->_pagination(array('per_page' => 10));
		
	}
	
	function index(){
		$this->moderate();
	}
	
	function moderate(){
		
		// prepare pagination
		$total_rows = $this->Moderation->get_moderation_queue($this->authentication->get_uid(),null,null,null,true); 
		$this->_pagination(array('total_rows'=>$total_rows));
		$data['pagination'] = pagination_helper($this->pagination,base_url().'admin/moderation');
		// --
		
		$data['moderation_queue'] = $this->Moderation->get_moderation_queue($this->authentication->get_uid(),$this->pagination['per_page'],$this->pagination['offset']);
			
		$this->layout->buildPage('admin/moderate',$data);
	}
	
	function vote_up(){
		$this->vote(1);
	}
	
	function vote_down(){
		$this->vote(-1);
	}
	
	function vote_nil(){
		$this->vote(0);
	}
	
	function _vote($vote_value){
		$artwork_id = $this->uri->segment(4);
		
		if (!$artwork_id){
			flashset('notice','Error while voting!');
			redirect('/admin/moderation','refresh');
		}
		
		$artwork = $this->Artwork->find($artwork_id);
		
		//  if work exists and doesn't belong to the voting user, let the vote be cast!
		if ($artwork && $artwork->user_id != $this->authentication->get_uid())
			$this->Moderation->add_vote($artwork_id,$vote_value,$this->authentication->get_uid());
			
		redirect('/admin/moderation','refresh');
	}
	
	/**
	 * _pagination - set variables necessary for the pagination
	 */
	function _pagination($elems = array()){
		/* set the number of items per page */
		if (@$elems['per_page']){
		$this->pagination['per_page'] = $elems['per_page'];
		} elseif (!isset($this->pagination['per_page'])){
			$this->pagination['per_page'] = 10;
		}
		
		/* set the total */
		if (@$elems['total_rows']){
		$this->pagination['total_rows'] = $elems['total_rows'];
		}
		
		/* set the order */
		if (@$elems['orderby']){
			$this->pagination['orderby'] = $elems['orderby'];
		} elseif (!isset($this->pagination['orderby'])) {
			$this->pagination['orderby'] = 'date_accepted desc';
		}
		
		/* calculate the offset */
		$page = $this->input->get('page');
		if (!$page) $page = 1;
		
		$this->pagination['offset'] = $this->pagination['per_page']*($page-1);
		$this->pagination['page'] = $page; 
	}
	
}

?>