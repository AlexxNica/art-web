<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * License Model
 * 
 * manages everything about licenses 
 */
class License_model extends Model{
	function License_model(){
		parent::Model();
	}
	
	function find_all($orderby='name asc'){
		$this->db->orderby($orderby);
		$this->db->from('license');
		$query = $this->db->get();
		
		if ($query->num_rows()>0)
			return $query->result();
		else
			return false;
	}
	
	function find($id){
		$this->db->where('id',$id);
		$this->db->from('license');
		$query = $this->db->get();
		
		if ($query->num_rows()>0)
			return $query->row();
		else
			return false;
	}
}
?>