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
	
	/**
	 * find_by_activation_code($code)
	 * 
	 * $code - activation code to find
	 * returns user info or false
	 */
	function find_by_activation_code($code){
		$this->db->where('activation_code LIKE \''.$this->db->escape_str($code).'\'');
		$query = $this->db->get('user',1,0);
		if ($query->num_rows()>0)
			return $query->row();
		else
			return false;
	}
	
	/**
	 * find_by_email($email)
	 * 
	 * $email - email address to find
	 * returns user info or false
	 */
	function find_by_email($email){
		$this->db->where('email LIKE \''.$this->db->escape_str($email).'\'');
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
	 * search - generic query function
	 */
	function search($search_query=null, $num=null,$offset=null,$orderby = 'username asc'){
		$this->db->from('user');
		
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
	 * list - returns an array with users
	 */
	
	function get_all($num=null,$offset=null,$orderby = 'uid desc'){
		return $this->search(null,$num,$offset,$orderby);
	}
	
	function search_by_username($username,$num=null,$offset=null,$orderby = 'username asc'){
		return $this->search(	'username LIKE \'%'.$this->db->escape_str($username).'%\'',
								$num,
								$offset,
								$orderby
							);
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
		$templates = $this->config->item('email_template');
		$activation_info = $templates['activation'];
		
		/* Send activation email */
		$this->load->library('Email');
		$this->email->to($fields['email']);
		$this->email->from('noreply@art.gnome.org');
		$this->email->subject($activation_info['subject']);
		$this->email->message(sprintf($activation_info['body'],$fields['username'],base_url().'account/activate/'.$fields['activation_code']));
		
		if (!$this->email->send()){
			show_error('A problem occurred during registration process. Please contact a website administrator.');
		}
	}
	
	function activate($uid){
		$fields = array(
			'activation_code'	=> null,
			'activated_at'		=> date('Y-m-d H-i-s',time()));
		$this->update($uid,$fields);
		return true;
	}
	
	/**
	 * lost_password($user_id) - creates a temporary license to reset a user's password
	 * 
	 * $user_id - user id having password reset
	 */
	function lost_password($username){
		$user = false;
		$user = $this->find_by_username($username);
		if (!$user)
			$user = $this->find_by_email($username);
		
		if (!$user) return false;
		
		$fields['user_id'] = $user->uid;
		$fields['requested_at'] = date('Y-m-d H-i-s',time());
		$fields['reset_key'] = $this->make_activation_code();
		
		$this->db->insert('lost_password',$fields);
		
		/* Send lost password email */
		$templates = $this->config->item('email_template');
		$reset_info = $templates['lost_password'];
		$this->load->library('Email');
		$this->email->to($user->email);
		$this->email->from('noreply@art.gnome.org','noreply');
		$this->email->subject($reset_info['subject']);
		$this->email->message(sprintf($reset_info['body'],
															$user->username,
															base_url().'account/resetpwd/'.$fields['reset_key'],
															base_url().'help/contact'
									));
		
		if (!$this->email->send()){
			show_error('A problem occurred while sending the lost password mail. Please contact a website administrator.');
		}
		
		return true;
	}
	
	function valid_reset_key($reset_key){
		$this->db->where('lost_password.reset_key LIKE '.$this->db->escape($reset_key).' AND lost_password.user_id = user.uid');
		$query = $this->db->get('lost_password,user',1,0);
		if ($query->num_rows()>0){
			$user = $query->row();
			$request_at = strtotime($user->requested_at);
			$dif = abs(time()-$request_at);
			if ($dif>43200){ // request timed out!
				return false;
			} else {
				return $user;
			}
		} else
			return false;
	}
	
	function reset_password($reset_key,$new_password){
		$user = $this->valid_reset_key($reset_key);
		if (!$user){
			return false;
		} else {
			//$this->db->where('reset_key LIKE '.$this->db->escape($reset_key));
			$this->db->where('user_id',$user->uid);
			$this->db->delete('lost_password');
			
			$this->db->where('uid',$user->uid);
			$this->db->update('user',array('password'=>$new_password));
			return true;
		}
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