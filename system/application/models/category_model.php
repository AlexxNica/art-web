<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Category Model
 * 
 * manages everything about categories
 */

class Category_model extends Model{
	function Category_model(){
		parent::Model();
	}
	
	function find_by_parent($parent_id,$orderby='name asc'){
		$this->db->orderby($orderby);
		$this->db->where('parent_id',$parent_id);
		$this->db->from('category');
		$query = $this->db->get();
		
		if ($query->num_rows()>0)
			return $query->result();
		else
			return false;
	}
}

?>