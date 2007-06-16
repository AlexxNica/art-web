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
		$this->CI->load->helper('cookie');
		$config = $this->CI->config->config['authentication'];

		$settings['permissions'] = $config['permissions'];
		$settings['regular_user'] = $config['regular'];
		$settings['login_page'] = $config['login_page'];

		$this->settings = $settings;

		$this->uid = null;
		$this->acl = null;

		$this->validate_certificate();
	}


	/**
		* authenticate
		* 
		* if user is authenticated it lets it proceed
		* if not page is redirected to login page.
		*/
	function authenticate(){
		if ($this->CI->session->serverdata('auth') == 'yes'){
			$this->uid = $this->CI->session->serverdata('uid');
			$this->settings['username'] = $this->CI->session->serverdata('username');
			$this->acl = $this->CI->User->get_acl($this->uid);
			if ($this->acl!=NULL)
				return true;
		}
		redirect($this->settings['login_page'].'?refer='.$this->CI->uri->uri_string(),"refresh");
		return false;
	}

	/**
		* is_logged_in
		* 
		* return true if current user session is authenticated
		* return false if current user session is not authenticated
		*/
	function is_logged_in(){
		if ($this->CI->session->serverdata('auth') == 'yes'){
			$this->settings['username'] = $this->CI->session->serverdata('username');
			$this->acl = $this->CI->User->get_acl($this->uid);
			$this->uid = $this->CI->session->serverdata('uid');
			return true;
		} else
			return false;
	}

	/**
		* validate_certificate
		* 
		* checks the presence of the remember me cookie
		* 
		*/
	function validate_certificate(){
		if (!$this->is_logged_in()){
			$cookie = get_cookie('agov3_remember_cookie', TRUE);
			if ($cookie){
				$elements = explode("_", $cookie);
				
				if (count($elements)!=2) return false;
				
				$validated_user = $this->CI->User->validate_token($elements[0],$elements[1]);
				
				if (!$validated_user){
					delete_cookie("agov3_remember_cookie");
					return false;
				} else {
					$this->login($validated_user->uid,true);
					//echo "permanent cookies in action";
				}
				} else return false;
			}
			return false;
		}

		/**
			* login($uid)
			* 
			* sets the user (to who $uid belongs to) as authenticated
			*/
		function login($uid,$remember=false){
			$user = $this->CI->User->find($uid);
			if (!$user) return false;
			
			$this->CI->session->set_serverdata(array('uid' => $uid, 'auth' => 'yes', 'username' => $user->username));
			// if remember flag is set and remember cookie is not set
			if ($remember && !get_cookie('agov3_remember_cookie')){
				
				$username = $user->username;
				
				//generate random token
				$token = $this->random_bit_sequence();
				
				//update user token in db
				$fields = array('token' => $token);
				$this->CI->User->update($uid,$fields);
				
				//set remember me cookie
				$cookie = array(
					'name'   => 'remember_cookie',
					'value'  => $username.'_'.$token,
					'expire' => '221184000',
					'domain' => '',
					'path'   => '/',
					'prefix' => 'agov3_',
					);

				set_cookie($cookie);
			}
		}

		/**
			* logout
			* 
			* destroys current user session and calls garbage collector for DB Session
			*/
		function logout(){
			$fields = array('token' => "_");
			$this->CI->User->update($this->uid,$fields);
			delete_cookie("agov3_remember_cookie");

			$this->CI->session->sess_destroy();
			$this->CI->session->sess_gc();
		}

		/**
			* get_permissions
			* 
			* calls load_permissions and saves it in $this->permissions
			*/
		function get_permissions(){
			$this->permissions = $this->load_permissions();
		}

		/**
			* load_permissions
			* 
			* interprets the ACL from the current instance against the defined levels in authentication config file
			* 
			* returns the list of permissions of the current user.
			*/
		function load_permissions(){
			if ($this->acl == null) { 
				log_message('error', 'get_permissions called without user\' $acl being loaded');
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

		/**
			* set_permissions($new_permissions)
			* 
			* $new_permissions = 	array with roles that will change
		* 						i.e : array(
			*							ADD_ARTWORK			=> true,
			*							CREATE_COLLECTION 	=> false);
		* 
			* if a permission is not defined in this list and it exists, it will be let as it is.
			* 
			* returns void;
		*/
		function set_permissions($new_permissions){
			$this->get_permissions();

			foreach($new_permissions as $key=>$value){
				$this->permissions[$key] = $new_permissions[$key];
			}


			$new_acl = $this->toBitmask($this->permissions);

			$this->CI->User->set_acl($this->uid,$new_acl);

		}

		/**
			* is_allowed($role)
			* 
			* $role - Constant identifying certain role
			* 
			* returns true if current user is allowed to access that role, or false if he does not.
			*/
		function is_allowed($role){
			if ($this->uid == null || $this->acl == null) return false;
			return (($this->acl & pow(2,$role)) !=0) ? true: false;
		}
		
		function get_username(){
			return $this->settings['username'];
		}

		/**
			* toBitmask($permissions)
			* 
			* $permissions list of users permissions to encode
			* 
			* receives the list of permissions and encodes it using powers of two
			* (bit operations are used to recover the permissions)
			* 
			* returns an integer value that represents this user acl level.
			*/
		function toBitmask($permissions){
			$bitmask=0;
			foreach($permissions as $key=>$value){
				if($value){
					$bitmask+=pow(2,$key);
				}
			}
			return $bitmask;
		}

		/**
			* random_bit_sequence
			* 
			* 
			* returns a 128 bit random sequence
			*/
		function random_bit_sequence() {
			$n_bits = 128;
			// Generate $n_bits bit random sequence
			$randmax_bits = strlen(base_convert(mt_getrandmax(), 10, 2));  // how many bits is mt_getrandmax()
			$x = '';
			while (strlen($x) < $n_bits) {
				$maxbits = ($n_bits - strlen($x) < $randmax_bits) ? $n_bits - strlen($x) :  $randmax_bits;
				$x .= str_pad(base_convert(mt_rand(0, pow(2,$maxbits)), 10, 2), $maxbits, "0", STR_PAD_LEFT);
			}
			return base_convert($x,2,10);
		}
		
	}

	?>