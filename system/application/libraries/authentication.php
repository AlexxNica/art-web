<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Authentication Library Class
 *
 * @package		AGO - art.gnome.org
 * @subpackage	Libraries
 * @category	Authentication
 * @author		Bruno Santos
 *
 */
class Authentication 
{
	var $CI;
	var $settings;
	var $uid;
	var $acl;
	var $permissions;
	
	function Authentication(){
		$this->CI =& get_instance();
		$this->initialize();
		
		log_message('info','Authentication library loaded.');
	}
	
	/**
	 * initialize
	 * 
	 * Load library settings
	 */
	function initialize(){
		$this->CI->config->load('authentication', true);
		$this->CI->load->model('User_model','User');
		$config = $this->CI->config->config['authentication'];
		
		$settings['permissions'] = $config['permissions'];
		$settings['regular_user'] = $config['regular'];
		
		$this->settings = $settings;
	}
	
	
	
	function authenticate(){
		if ($this->CI->session->serverdata('auth') == 'yes'){
			$this->uid = $this->CI->session->serverdata('uid');
			$this->acl = $this->CI->User->get_acl($this->uid);
			if ($this->acl==NULL)
				return false;
			return true;
		}	
		return false;
	}
	
	function is_logged_in(){
		if ($this->CI->session->serverdata('auth') == 'yes')
			return true;
		else
			return false;
	}
	
	
	function login($uid){
		$this->CI->session->set_serverdata(array('uid' => $uid, 'auth' => 'yes'));
	}
	
	function logout(){
		$this->CI->session->sess_destroy();
		$this->CI->session->sess_gc();
	}
	
	function get_permissions(){
		$this->permissions = $this->load_permissions();
	}
	
	/**
	 * load_permissions
	 * 
	 * interprets the ACL from the current instance against the defined levels in authentication config file 
	 */
	function load_permissions(){
		if ($this->acl == null) { 
			log_message('error', 'get_permissions called without $acl being loaded');
			return false;
		}
		
		$i=0;
		foreach($this->settings['permissions'] as $key=>$value){
			$permissions[$key]= (($this->acl & pow(2,$i)) !=0) ? true: false;
			if (DEBUG) echo $key . " i= ".strval($i)." power=" . strval(pow(2,$i)). "bitwise & = " . strval($this->acl & pow(2,$i))."<br>";
			$i++;
		}
		return $permissions;
	}
	
	function set_permissions($new_permissions){
		$this->get_permissions();
		
		foreach($new_permissions as $key=>$value){
			$this->permissions[$key] = $new_permissions[$key];
		}
		
		
		$new_acl = $this->toBitmask($this->permissions);
		
		$this->CI->User->set_acl($this->uid,$new_acl);
		
	}
	
	function is_allowed($role){
		return (($this->acl & pow(2,$role)) !=0) ? true: false;
	}
	
	function toBitmask($permissions){
		$bitmask=0;
		foreach($permissions as $key=>$value){
			if($value){
				$bitmask+=pow(2,$key);
			}
		}
		return $bitmask;
	}
}

?>