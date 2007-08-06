<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Artwork Model
 * 
 * manages everything about artwork (add,edit,delete,etc)
 */

class Artwork_model extends Model{
	
	function Artwork_model(){
		parent::Model();
		
		// State field options:
		if (!defined('STATE_DRAFT')) define("STATE_DRAFT", 0);
		if (!defined('STATE_MODERATION')) define("STATE_MODERATION", 1);
		if (!defined('STATE_PUBLIC')) define("STATE_PUBLIC", 2);
		
		$this->load->model('Vote_model','Vote');
	}
	
	/**
	 * Creates a new work
	 */
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
	 * returns an array with artwork in the public gallery
	 */
	function get_public($num=null,$offset=null,$orderby='date_accepted desc'){
		return $this->search('state = '.STATE_PUBLIC,$num,$offset,$orderby);
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
	
	function find_by_category($categories,$num=null,$offset=null,$orderby='date_accepted desc'){
		if (is_array($categories)){
			$sql = 'state = '.STATE_PUBLIC.' AND (';
			foreach($categories as $key => $category){
				if ($key!=0) { 
					$sql .=' OR';
				}	
				$sql .= ' category_id = '.$category;
			}
			$sql .= ')';
		} else {
			$sql = 'state = '.STATE_PUBLIC.' AND ( category_id = '.$categories.')';
		}
		
		return $this->search($sql,$num,$offset,$orderby);
	}
	
	
	/**
	 * find - return on work
	 */
	function find($artwork_id,$complete=FALSE){
		if ($complete){
			$sql = "
			Select artwork.*, user.username,
			license.name as licence_name, license.summary as license_summary, license.link as license_link
			from vote,artwork,user,license
			where artwork.id = vote.artwork_id
			AND artwork.id = $artwork_id
			AND artwork.user_id = user.uid
			AND license.id = artwork.license_id
			";
			$res = $this->db->query($sql);
			if ($res->num_rows()>0){
				$res = $res->result();
			}
		} else {
			$res = $this->search('id = '.$artwork_id,1,0);
		}
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
	
	/**
	 * Update
	 * 
	 * update the fields of a work
	 */
	function update($artwork_id,$fields){
		if ($artwork_id == null)
			return;
			
		$where = "id = $artwork_id";
		$sql = $this->db->update_string('artwork', $fields, $where);
		$this->db->query($sql);
	}
	
	/**
	 * Set State
	 * 
	 * changes the state field of a work
	 */
	function set_state($artwork_id,$state){
		$fields = array('state' => $state);
		$this->update($artwork_id,$fields);
	}
	
	
	/**
	 * 
	 */
	function top_rated($category_id=null,$num=null,$offset=null){
		$where = null;
		if ($category_id!=null){
			$where = "AND artwork.category_id = $category_id";
		} 
		$sql = "
		Select count(vote) as total_votes,(sum(vote)/count(vote)) as rating, artwork.*
		From vote,artwork
		Where artwork.id = vote.artwork_id
		AND artwork.state = ".STATE_PUBLIC."
		$where
		group by artwork.id
		order by rating desc, total_votes desc";
		if ($num!=null){
			$sql.=" Limit $offset,$num";
		}
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows()>0)
			return $query->result();
		else
			return array();
	}
	
	
}