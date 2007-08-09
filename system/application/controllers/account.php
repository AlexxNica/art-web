<?php

class Account extends Controller{

	function Account(){
		parent::Controller();

		$this->load->model('User_model','User');
	}

	function index(){
		$data['null'] = null;

		if ($this->authentication->is_logged_in())
			$data['auth'] = "Logged In!";
		else 
			$data['auth'] = "Not Logged In!";

		$this->layout->buildPage('account/index', $data);
	}


	/**
		* login form and user login process
		*/
	function login(){
		$post = $this->input->post('login',TRUE);

		if ($post){
			$remember = $this->input->post('remember',TRUE)?true:false;
			$email = $this->input->post('username',TRUE);
			$refer = $this->input->post('refer',TRUE);

			$user = $this->User->find_by_email($email);

			if ($user && $user->activated_at != null){
				$pass = $this->input->post('password',TRUE);
				if (md5($pass) == $user->password){
					$this->authentication->login($user->uid,$remember);
					flashset('notice','Successful authentication!');
				} else {

				}
			}

			if (!$refer)
				redirect("/account",'refresh');
			else
				redirect($refer,'refresh');  // -> validate and filter

		} else {

			$data['null']=null;
			$refer = $this->input->get('refer',TRUE);
			if (strlen($refer)>0)
				$data['refer'] = $refer;
			$this->layout->buildPage('account/login', $data);

		}
	}

	/**
		* Debug funcion
		*/
	function test(){
		$this->authentication->authenticate();

		echo "congrats! You're logged in!";
		
		$this->load->model('Version_model','Version');
		
		$this->Version->parents('1.1.2.1.');
		$this->Version->parents('1.2.2');
	}


	/**
		* err... user is going to be logged out! duh!
		*/
	function logout(){
		$this->authentication->authenticate();
		$this->authentication->logout();

		redirect('/account','refresh');
	}


	/**
		* Open ID Authentication 
		*/
	function try_auth(){

		$this->load->library('openID');
		if (empty($_POST['openid_url'])) {
			$data['error'] = "Expected an OpenID URL.";
			$this->layout->buildPage('account/login', $data);
			return;
		}

		$scheme = 'http';
		if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') $scheme .= 's';

		$openid = $_POST['openid_url'];
		$processUrl = sprintf("$scheme://%s",
		preg_replace("|http://|", "", site_url('account/finish_auth')));

		$trustRoot = sprintf("$scheme://%s",
		preg_replace("|http://|", "", site_url('')));


		$openid_user = $this->authentication->exists_openid_user('http://'.(preg_replace("|http://|", "", $openid)).'/');
		if (!$openid_user){
			$extensions = array(
				array('sreg','required', 'email'),
				array('sreg','optional', 'nickname,fullname,country,timezone')
				);
		} else {
			$extensions = array();
		}


		// Handle failure status return values.
		if (!$this->openid->authenticate($openid, $processUrl, $trustRoot,$extensions)) {
			$data['error'] = "Authentication error.";
			$this->layout->buildPage('account/login', $data);
			return;
		}
	}

	function finish_auth(){

		$this->load->library('openID');

		// Complete the authentication process using the server's response.
		$response = $this->openid->getResponse();
		$success = null;
		$msg = null;
		if ($response->status == Auth_OpenID_CANCEL) {
			// This means the authentication was cancelled.
			flashset('error','Verification cancelled.');
		} else if ($response->status == Auth_OpenID_FAILURE) {
			flashset('error','OpenID authentication failed!');
			//   $msg = "OpenID authentication failed: " . $response->message;
		} else if ($response->status == Auth_OpenID_SUCCESS) {
			// This means the authentication succeeded.
			$openid = $response->identity_url;
			$esc_identity = htmlspecialchars($openid, ENT_QUOTES);

			$openid_user = $this->authentication->exists_openid_user($esc_identity);
			if (!$openid_user){
				$sreg = $response->extensionResponse('sreg');
				$info['identity'] = $esc_identity;

				if (@$sreg['email']) {
					$info['email'] = $sreg['email'];
				}
				if (@$sreg['nickname']) {
					$info['username'] = $sreg['nickname'];
				}
				if (@$sreg['fullname']) {
					$info['real_name'] = $sreg['fullname'];
				}

				if (@$sreg['country']) {
					$info['country'] = $sreg['country'];
				}

				if (@$sreg['timezone']) {
					$info['timezone'] = $sreg['timezone'];
				}

				$data['info'] = $info;
				$data['openid'] = 'true';

				$this->layout->buildPage('account/register', $data);
				return false;
			} else {
				flashset('notice','OpenId authentication successful!');
				$this->authentication->login($openid_user->uid,FALSE);
				redirect('','refresh');
			}


		}

		$this->layout->buildPage('account/login', null);
	}

	function register(){
		$this->load->library('validation');
		$data['openid'] = 'false';
		if ($_POST){
			$info['username'] = $this->input->post('username',TRUE);
			$info['email'] = $this->input->post('email',TRUE);
			$info['country'] = $this->input->post('country',TRUE);
			$info['timezone'] = $this->input->post('timezone',TRUE);
			$info['real_name'] = $this->input->post('real_name',TRUE);
			$info['homepage'] = $this->input->post('homepage',TRUE);
			$info['identity'] = $this->input->post('identity',TRUE);
			$data['info'] = $info;
			$data['openid'] = $this->input->post('openid',TRUE);


			$this->validation->set_error_delimiters('<div class="error">', '</div>');

			$rules['username']	= "trim|required|callback_exists_username|xss_clean";

			if (!($this->input->post('openid',TRUE)=='true')){
				$rules['re_password']	= "trim|required";
				$rules['password']	= "trim|required|matches[re_password]|md5";
			}

			$rules['email']		= "trim|required|valid_email|callback_exists_email";

			$fields['username'] = 'Username';
			$fields['password'] = 'Password';
			$fields['re_password'] = 'Password Confirmation';
			$fields['email'] = 'Email Address';

			$this->validation->set_fields($fields);
			$this->validation->set_rules($rules);


			if ($this->validation->run() == FALSE){
				$this->layout->buildPage('account/register',$data);
				return false;
			} else {
				$fields = array(
					'username' => $this->input->post('username',TRUE),
					'password' => $this->input->post('password',TRUE),
					'email' => $this->input->post('email',TRUE),
					'openid' => $this->input->post('identity',TRUE),
					'country' => $this->input->post('country',TRUE),
					'timezone' => $this->input->post('timezone',TRUE),
					'homepage' => $this->input->post('homepage',TRUE),
					'acl' => $this->authentication->toBitmask($this->authentication->settings['regular_user'])
					);
				$this->User->create($fields);
				//flashset('notice','Registration Successful!<br/> You\'ll receive an activation email in a few minutes.');
				redirect('/account/register?success','refresh');
			}

		} else {
			if (isset($_GET['success'])){
				$data['success'] = true;
			}
		}

		$this->layout->buildPage('account/register', $data);
	}

	function activate($id=null){
		if ($id==null) exit(0);
		$user = $this->User->find_by_activation_code($id);
		if ($user && $this->User->activate($user->uid)){
			flashset('notice','Account activation successful!<br/> You may login now.');
			redirect('/account','refresh');
		}
	}

	function lost_password(){
		$data['null'] = null;
		if ($_POST){
		 	$username = $this->input->post('username');
				
			
			// if username wasn't empty
			if ($username){
				//if lost_password didn't go right
				if (!$this->User->lost_password($username)){
					$data['baduser'] = true;
				} else { // success
					$data['sent'] = true;
				}
			}
		}

		$this->layout->buildPage('/account/lost_password',$data);
	}

	function resetpwd($id=null){
		$data['null'] = null;
		
		if ($id==null) exit(0);
		$data['id'] = $id;
		
		if (!$this->User->valid_reset_key($id)){
			if (isset($_GET['success'])){
				$data['success'] = true;
			} else {
				$data['no_valid_key'] = true;
			}
		} else {

			if ($_POST){
				$this->load->library('validation');
				$this->validation->set_error_delimiters('<div class="error">', '</div>');

				$rules['re_password']	= "trim|required";
				$rules['password']	= "trim|required|matches[re_password]|md5";

				$fields['password'] = 'Password';
				$fields['re_password'] = 'Password Confirmation';

				$this->validation->set_fields($fields);
				$this->validation->set_rules($rules);

				if ($this->validation->run() == FALSE){

				} else {
					if ($this->User->reset_password($id,$this->input->post('password'))){
						//flashset('notice','Password reset successful!<br/> You may login with your new password now.');
						redirect('/account/resetpwd/'.$id.'?success','refresh');
					}
				}
			}
		}
		
		$this->layout->buildPage('/account/reset_password',$data);
	}


	/** callback functions for validation process */

	function exists_username($str){
		if ($this->User->exists($str)){
			$this->validation->set_message('exists_username', 'Username '.$str.' already exists.');
			return false;
		} else{
			return true;
		}
	}

	function exists_email($str){
		if (!$this->User->find_by_email($str)){
			return true;
		} else{
			$this->validation->set_message('exists_email', 'There is already an account associated to the email '.$str.'.');
			return false;
		}
	}
	
}

?>