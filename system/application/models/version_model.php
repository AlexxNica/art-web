<?php

class Version_model extends Model{
	function Version_model(){
		parent::Model();
	}
	
	function parents($son_path, $tree_id){
		$elems = array();
		$atoms =  explode('.',$son_path);
		$tmp = '';
		$tuple = '';
		foreach($atoms as $atom){
			if (!$atom) continue;
			$tmp  .=$atom.'.';
			$elems[] = $tmp;
			$tuple .= "'$tmp',";
		}
		
		$tuple = substr($tuple,0,-1);
		
		$sql = "SELECT *
				FROM version,artwork
				WHERE version.artwork_id = artwork_id
				AND version.path in $tuple
				AND version.tree_id = $tree_id";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows()>0)
			return $query->result();
		else
			return array();
	}
	
	function descendents($root_path,$tree_id){
		$sql = "SELECT * 
				FROM version,artwork
		 		WHERE version.path LIKE '$root_path%'
				AND version.artwork_id = artwork.id
				AND version.tree_id = $tree_id";
				
		$query = $this->db->query($sql);
		
		if ($query->num_rows()>0)
			return $query->result();
		else
			return array();
	}
	
	function direct_sons($root_path,$tree_id){
		$sql = "SELECT * 
				FROM version,artwork
		 		WHERE version.path LIKE '$root_path%.'
				AND version.path NOT LIKE '$root_path%.%.'
				AND version.artwork_id = artwork.id
				AND version.tree_id = $tree_id";
				
		$query = $this->db->query($sql);
		
		if ($query->num_rows()>0)
			return $query->result();
		else
			return array();
	}
	
	function add($parent_path,$artwork_id,$tree_id){
		$sons = $this->direct_sons($parent_path,$tree_id);
		
		$new_path = $parent_path.(count($sons)+1).'.';

		$sql = "INSERT INTO version
				(artwork_id,path,tree_id)
				VALUES ($artwork_id,'$new_path',$tree_id)";
				
		$this->db->query($sql);
	}
	
	function get($artwork_id){
		$sql = "SELECT *
				FROM version
				WHERE artwork_id = $artwork_id";
		$query = $this->db->query($sql);
		
		if ($query->num_rows()>0)
			return $query->row();
		else
			return FALSE;
	}
	
	
}

?>