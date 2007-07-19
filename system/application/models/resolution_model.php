<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Resolution_model extends Model{
	function Resolution_model(){
		parent::Model();
	}
	
	function get_all(){
		$this->db->from('resolution');
		$query = $this->db->get();
		
		return $query->result();
	}
	
	function find_by_resolution($width,$height){
		$this->db->where(array('width' => $width, 'height' => $height));
		$this->db->from('resolution');
		$query = $this->db->get();
		
		if ($query->num_rows()>0)
			return $query->row();
		else
			return false;
	}
}

?>