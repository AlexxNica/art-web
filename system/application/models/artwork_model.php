<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Artwork Model
 * 
 * manages everything about artwork (add,edit,delete,etc)
 */

class Artwork_model extends Model{
	
	function Artwork_model(){
		parent::Model();
		
		if (!defined('STATE_DRAFT')) define("STATE_DRAFT", 0);
		if (!defined('STATE_MODERATION')) define("STATE_MODERATION", 1);
		if (!defined('STATE_PUBLIC')) define("STATE_PUBLIC", 2);
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
	
	/**
	 * find - return on work
	 */
	function find($artwork_id){
		$res = $this->search('id = '.$artwork_id,1,0);
		if (@$res[0])
			return $res[0];
		else
			return false;
	}
	
	/**
	 * find originals
	 * 
	 * get the first version of the works of certain user
	 */
	function find_originals($user_id=null){
		$this->db->orderby('name asc');
		$this->db->where('original_id',NULL);
		if ($user_id!=null){
			$this->db->where('user_id',$user_id);
		}
		$this->db->from('artwork');
		$query = $this->db->get();
		
		if ($query->num_rows()>0)
			return $query->result();
		else 
			return false;
	}
	
	
	function update($artwork_id,$fields){
		if ($artwork_id == null)
			return;
			
		$where = "id = $artwork_id";
		$sql = $this->db->update_string('artwork', $fields, $where);
		$this->db->query($sql);
	}
	
	
	function set_state($artwork_id,$state){
		$fields = array('state' => $state);
		$this->update($artwork_id,$fields);
	}
	
	
	
}