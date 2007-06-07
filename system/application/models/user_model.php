<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends Model
{
	function User_model(){
		parent::Model();
	}
	
	function exists_user($username){
		$query = $this->db->query(" Select count(1) as count
									From `user`
									Where username = '$username'");
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
	function get_user_by_username($username){
		$this->db->where("username LIKE '$username'");
		$query = $this->db->get('user',1,0);
		return $query->row();
	}
	
}


?>