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
		
	}
	
	/**
	 * lists artwork in moderation queue
	 */
	function list_queue($num=null,$offset=null,$orderby = 'id desc'){
		$this->db->from('moderation_queue');
		$this->db->addJoin('artwork', 'moderation_queue.artwork_id', 'artwork.id');
		$this->db->orderby($orderby);
		if ($num !=null && $offset != null)
			$this->db->limit($num,$offset);
		
		$query = $this->db->get();
		
		if ($query->num_rows()>0)
			return $query->result();
		else 
			return false;
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
		if ($this->CI->config->config['moderation']['threshold_positive'] < $score){
			//arwork was accepted
			/**
			 *  TODO:
			 * 	* change artwork state to public
			 * 	* warn user
			 * 	* remove from moderation queue
			 */
			echo 'artwork accepted';
		} else if ($this->CI->config->config['moderation']['threshold_negative'] > $score) {
			// artwork was rejected
			/**
			 *  TODO:
			 * 	* change artwork state to draft
			 * 	* warn user
			 * 	* remove from moderation queue
			 */
			echo 'artwork rejected';
		}
	}
	
}

?>