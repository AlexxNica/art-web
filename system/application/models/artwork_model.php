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
	
	/**
	 * search - generic query function
	 */
	function search($search_query=null, $num=null,$offset=null,$orderby = 'id desc'){
		$this->db->from('artwork');
		
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
	 * get_all - returns an array with artwork
	 */
	
	function get_all($num=null,$offset=null,$orderby = 'id desc'){
		return $this->search(null,$num,$offset,$orderby);
	}
	
	/**
	 * find_by_username
	 */
	function find_by_user($user_id,$num=null,$offset=null,$orderby='id desc'){
		return $this->search('user_id = '.$user_id,$num,$offset,$orderby);
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