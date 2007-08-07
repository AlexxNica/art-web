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
	
	function score($artwork_id, $kind = VOTE_NORMAL){
		$query = $this->db->query("	Select sum(vote) as 'score'
									From vote
									Where artwork_id = $artwork_id
									AND kind = $kind");
		$tmp = $query->row();
		if ($tmp->score)
			return $tmp->score;
		else 
			return 0;
	}
	
	function count($artwork_id, $kind = VOTE_NORMAL){
		$query = $this->db->query("	Select count(vote) as 'count'
									From vote
									Where artwork_id = $artwork_id
									AND kind = $kind");
		
		$tmp = $query->row();
		return $tmp->count;
	}
	
	function rating($artwork_id, $kind = VOTE_NORMAL,$user_id=null){
		$add_sql = "";
		
		// in case we just want the rating of one user!
		if ($user_id!=null){
			$add_sql = "AND user_id = $user_id";
		}
		
		$sql = "
		Select sum(vote) as score, count(vote) as total_votes, (sum(vote)/count(vote)) as rating
		From vote
		Where artwork_id = $artwork_id
		AND kind = $kind
		$add_sql 
		group by artwork_id;
		";
		
		$query = $this->db->query($sql);
		if ($query->num_rows()>0)
			return $query->row();
		else{
			// return empty object
			$empty = new stdClass;
			$empty->votes = 0;
			$empty->total_votes = 0;
			$empty->rating = 0;
			return $empty;
		}
			
	}
	
	function get_by_kind($artwork_id , $kind = VOTE_NORMAL){
		
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
	
	function get_one($artwork_id,$user_id,$kind){
		
		$this->db->where('user_id',$user_id);
		$this->db->where('artwork_id',$artwork_id);
		$this->db->where('kind',$kind);
		$query = $this->db->get('vote');

		if ($query->num_rows()>0)
			return $query->row();
		else 
			return false;
	}
	
	function add($fields){
		if ($fields['artwork_id'] == null OR $fields['user_id'] == null) return false;
		
		$vote = $this->get_one($fields['artwork_id'],$fields['user_id'],$fields['kind']);
		if ($vote){
			$where = "artwork_id = $vote->artwork_id AND user_id = $vote->user_id AND kind = $vote->kind";
			$sql = $this->db->update_string('vote', $fields, $where);
			$this->db->query($sql);
		} else {
			$this->db->insert('vote',$fields);
		}
	}

}
?>