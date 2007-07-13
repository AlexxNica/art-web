<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Artwork Model
 * 
 * manages everything about artwork (add,edit,delete,etc)
 */

class Artwork_model extends Model{
	
	function Artwork_model(){
		parent::Model();
	}
	
	function add($fields){
		$this->db->insert('artwork',$fields);
		
		return $this->db->insert_id();
	}
	
	function find_originals(){
		$this->db->orderby('name asc');
		$this->db->where('original_id',NULL);
		$this->db->from('artwork');
		$query = $this->db->get();
		
		if ($query->num_rows()>0)
			return $query->result();
		else 
			return false;
	}
	
}