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
	 * get_user_by_username
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
	
	function update($uid,$fields){
		$this->db->set($fields);
		$this->db->where('uid',$uid);
		$this->db->update('user');
	}
	
	function validate_token($user,$token){
		
		$user = $this->find_by_username($user);
		
		if (!$user) return false;
		
		if ($user->token != '_' && $user->token == $token)
			return $user;
		
		return false;
	}
	
}


?>