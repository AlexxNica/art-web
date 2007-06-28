<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends Model
{
	function User_model(){
		parent::Model();
	}
	
	
	function exists($username){
		$query = $this->db->query(' Select count(1) as count
									From `user`
									Where username = '.$this->db->escape($username));
		$count = $query->row();
		if ($count->count > 0) return true;
		else return false;
	}
	
	/**
	 * get_acl
	 *
	 * returns the acl level of the user with id = $uid
	 */
	function get_acl($uid){
		$this->db->where('uid',$uid);
		$query = $this->db->get('user');
		$query = $query->row();
		if ($query==NULL) return NULL;
		return $query->acl;
	}
	
	function set_acl($uid,$acl){
		$this->db->where('uid',$uid);
		$this->db->update('user',array('acl'=>$acl));
	}
	
	/**
	 * find_by_username
	 * 
	 * $username - user's username to get
	 * returns user info
	 */
	function find_by_username($username){
		$this->db->where('username LIKE '.$this->db->escape($username));
		$query = $this->db->get('user',1,0);
		if ($query->num_rows()>0)
			return $query->row();
		else
			return false;
	}
	
	/**
	 * find_by_openid($identifier)
	 * 
	 * $identifier - user's openid to find
	 * returns user info
	 */
	function find_by_openid($identifier){
		$this->db->where('openid LIKE '.$this->db->escape($identifier));
		$query = $this->db->get('user',1,0);
		if ($query->num_rows()>0)
			return $query->row();
		else
			return false;
	}
	
	function find_by_activation_code($code){
		$this->db->where('activation_code LIKE \''.$this->db->escape_str($code).'\'');
		$query = $this->db->get('user',1,0);
		if ($query->num_rows()>0)
			return $query->row();
		else
			return false;
	}
	
	/**
	 * get_user
	 * 
	 * $uid - user's uid to get
	 * returns user info
	 */
	function find($uid){
		$this->db->where('uid',$uid);
		$query = $this->db->get('user',1,0);
		
		if ($query->num_rows()>0)
			return $query->row();
		else
			return false;
	}
	
	/**
	 * update($uid,$fields) - update users info
	 * 
	 * $uid - user id
	 * $fields - array with fields to update
	 */
	function update($uid,$fields){
		$this->db->set($fields);
		$this->db->where('uid',$uid);
		$this->db->update('user');
	}
	
	/**
	 * create($fields) - creates a new user
	 * 
	 * $fields - array with new users info
	 */
	function create($info){
		$fields = $info;
		$fields['activation_code'] = $this->make_activation_code();
		
		$this->db->insert('user',$fields);
	}
	
	function activate($uid){
		$fields = array(
			'activation_code'	=> null,
			'activated_at'		=> date('Y-m-d H-i-s',time()));
		$this->update($uid,$fields);
		return true;
	}

	/**
	 * validate_token($user,$token)
	 * 
	 * checks if the session token provided belongs to the user provided
	 */
	function validate_token($user,$token){
		
		$user = $this->find_by_username($user);
		
		if (!$user) return false;
		
		if ($user->token != '_' && $user->token == $token)
			return $user;
		
		return false;
	}
	
	/**
	 * make_activation_code
	 * 
	 * generates an activation code for user validation after registration process
	 * 
	 */
	protected function make_activation_code(){
		$code = explode('-',date('Y-m-day-H-i-s',time()));
		shuffle($code);
		return sha1(join('_',$code));
	}
	
}


?>