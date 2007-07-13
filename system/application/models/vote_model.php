<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vote_model extends Model{
	function Vote_model(){
		parent::Model();
		
		if (!defined('VOTE_NORMAL')) define("VOTE_NORMAL", 0);
		if (!defined('VOTE_MODERATION')) define("VOTE_MODERATION", 1);
		if (!defined('VOTE_FEATURED')) define("VOTE_FEATURED", 2);
	}
	
	function get(){
		$query = $this->db->get('vote');
		
		if ($query->num_rows()>0)
			return $query->result();
		else 
			return false;
	}
	
	function score($artwork_id, $kind = NORMAL){
		$where = array( 'kind' => $kind, 'artwork_id' => $artwork_id );
		$this->db->where($where);
		$query = $this->db->query("	Select sum(vote) as 'score'
									From vote
									Where artwork_id = $artwork_id
									AND kind = $kind");
		
		$tmp = $query->row();
		return $tmp->score;
	}
	
	function count($artwork_id, $kind = NORMAL){
		$where = array( 'kind' => $kind, 'artwork_id' => $artwork_id );
		$this->db->where($where);
		$query = $this->db->query("	Select count(vote) as 'count'
									From vote
									Where artwork_id = $artwork_id
									AND kind = $kind");
		
		$tmp = $query->row();
		return $tmp->count;
	})
	
	function get_by_kind($artwork_id , $kind = NORMAL){
		
		$where = array( 'kind' => $kind, 'artwork_id' => $artwork_id );
		$this->db->where($where);
		$query = $this->db->get('vote');
		
		if ($query->num_rows()>0)
			return $query->result();
		else 
			return false;
	}
	
	function get_by_id($id){
		$this->db->where('id',$id);
		$query = $this->db->get('vote');
		
		if ($query->num_rows()>0)
			return $query->row();
		else 
			return false;
	}
	
	function get_one($artwork,$id){
		$this->db->where('user_id',$id);
		$query = $this->db->get('vote');
		
		if ($query->num_rows()>0)
			return $query->row();
		else 
			return false;
	}
	
	function add($fields){
		$vote = $this->get_one($fields['artwork_id'],$fields['user_id']);
		if (!vote){
			$vote->vote = $fields['vote'];
			$this->db->update('vote',$vote);
		} else {
			$this->db->insert('vote',$fields);
		}
	}
}
?>