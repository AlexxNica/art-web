<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Moderation Model
 * 
 * manages adding, and removing artwork from the moderation queue
 */

class Moderation_model extends Model{
	function Moderation_model(){
		parent::Model();
		
		$this->load->model('Vote_model','Vote');
		$this->load->model('Artwork_model','Artwork');
		
	}
	
	/**
	 * lists artwork in moderation queue
	 */
	function list_queue($num=null,$offset=null,$orderby = 'moderation_queue.id desc'){
		$this->db->from('moderation_queue');
		$this->db->join('artwork', 'moderation_queue.artwork_id', 'artwork.id');
		$this->db->where('artwork.id = moderation_queue.artwork_id');
		$this->db->orderby($orderby);
		
		if ($num !=null && $offset != null)
			$this->db->limit($num,$offset);
		
		$query = $this->db->get();
		
		
		if ($query->num_rows()>0)
			return $query->result();
		else 
			return array();
	}
	
	function get_moderation_queue($user_id,$num=null,$offset=null, $orderby = 'moderation_queue.id desc', $count = false){
		$this->db->select('moderation_queue.*,artwork.*,user.username as user_username');
		$this->db->from('moderation_queue,artwork,user');
		$this->db->where('artwork.id = moderation_queue.artwork_id');
		$this->db->where('artwork.user_id != '.$user_id);
		$this->db->where('user.uid = artwork.user_id');
		if ($orderby){
			$this->db->orderby($orderby);
		}
		
		if ($num!=null){
			$this->db->limit($num,$offset);
		}
		
		$query = $this->db->get();
		
		if ($count)
			return $query->num_rows();
		
		if ($query->num_rows()>0){
			$res = $query->result();
			
			foreach($res as $key => $work){
				$score = $this->Vote->score($work->id,VOTE_MODERATION);
				$total = $this->Vote->count($work->id,VOTE_MODERATION);
				
				$own_vote = $this->Vote->get_one($work->id,$user_id);
				$work->votes_score = $score;
				$work->votes_count = $total;
				if ($own_vote) {
					$work->own_vote = (int)$own_vote->vote;
				}
			}
			
			return $res;
		
		
		} else {
			return array();
		}
	}
	
	/**
	 * adds artwork to moderation queue
	 */
	function add_to_queue($artwork_id){
		$fields = array(
			'artwork_id' => $artwork_id
		);
		
		$this->db->insert('moderation_queue',$fields);
		return $this->db->insert_id();
	}
	
	/**
	 * removes artwork from moderation queue
	 */
	function del_from_queue($mq_id){
		if ($artwork_id != null){
			$this->db->query("Delete from moderation_queue Where id = $mq_id");
		}
	}
	
	/**
	 * removes artwork from moderation queue using the artwork_id
	 */
	function del_by_artwork($artwork_id){
		if ($artwork_id != null){
			$this->db->query("Delete from moderation_queue Where artwork_id = $artwork_id");
		}
	}
	
	/**
	 * adds a moderation vote
	 * checks if the threshold of votes was reached and acts accordingly 
	 */
	function add_vote($artwork_id, $vote, $user_id){
		
		$fields = array(
			'artwork_id' 	=> $artwork_id,
			'user_id'		=> $user_id,
			'vote'			=> $vote,
			'kind'			=> VOTE_MODERATION
		);
		$this->Vote->add($fields);
		
		$score = $this->Vote->score($artwork_id,VOTE_MODERATION);
		
		if ($this->config->config['moderation']['threshold_positive'] < $score){
			
			$this->Artwork->set_state($artwork_id,STATE_PUBLIC);
			$this->del_by_artwork($artwork_id);
			//arwork was accepted
			/**
			 *  TODO:
			 * 	* warn user
			 */
			
		} else if ($this->config->config['moderation']['threshold_negative'] > $score) {
			// artwork was rejected
			/**
			 *  TODO:
			 * 	* change artwork state to draft
			 * 	* warn user
			 * 	* remove from moderation queue
			 */
			$this->Artwork->set_state($artwork_id,STATE_DRAFT);
			$this->del_by_artwork($artwork_id);
		}
	}
	
}

?>