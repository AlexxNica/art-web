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
	
	/**
	 * search - generic query function
	 */
	function search($search_query=null, $num=null,$offset=null,$orderby = 'name desc'){
		$this->db->from('category');
		
		if ($search_query != null) { 
			$this->db->where($search_query); 
		}
		
		$this->db->orderby($orderby);
		
		if ($num !=null && $offset != null)
			$this->db->limit($num,$offset);
		
		$query = $this->db->get();
		
		if ($query->num_rows()>0)
			return $query->result();
		else 
			return array();
	}
	
	/**
	 * Find By Parent
	 * returns all the categories with the same $parent_id
	 */
	function find_by_parent($parent_id,$orderby='name asc'){
		return $this->search('parent_id = '.$parent_id,null,null,$orderby);
	}
	
	/**
	 * Find By Name
	 */
	function find_by_name($category_name){
		$res = $this->search('name LIKE '.$this->db->escape($category_name),1,0);
		if (@$res)
			return $res[0];
		else
			return FALSE;
	}
	
	/**
	 * Get All
	 * returns all the categories
	 */
	function get_all($orderby='id asc'){
		return $this->search(null,null,null,$orderby);
	}
}

?>