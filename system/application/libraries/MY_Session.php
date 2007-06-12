<?php

class MY_Session extends CI_Session{
	var $serverdata;

	function My_Session(){
		parent::CI_Session();
	}

	/**
		*  DB Sessions Patch by Kelvin Luck
		* 
		* 	CI doesn't save all session info into the DB.
		* 	This patch allows to do that.
		*/

	/**
		* Fetch a specific item from the serverside session data
		*
		* @authorKelvin Luck
		* @accesspublic
		* @paramstring
		* @returnstring
		*/

	function serverdata($item)
	{
		if ($this->_readserverdata() ) {
			return ( ! isset($this->serverdata[$item])) ? FALSE : $this->serverdata[$item];
		} else {
			return FALSE;
		}
	}

	/**
		* Add or change data in the serverside database session data
		*
		* @authorKelvin Luck
		* @parammixed
		* @paramstring
		* @returnvoid
		*/
	function set_serverdata($newdata = array(), $newval = '')
	{
		if ($this->_readserverdata()) {
			if (is_string($newdata)) {
				$newdata = array($newdata => $newval);
			}

			if (count($newdata) > 0) {
				foreach ($newdata as $key => $val) {
					$this->serverdata[$key] = $val;
				}
			}

			$this->_writeserverdata();
		}
	} 

	/**
		* Internal function to read and unserialize the data from the database
		*
		* @authorKelvin Luck
		* @accessprivate
		* @returnboolean
		*/
	function _readserverdata()
	{
		if ($this->use_database === TRUE) {
			if (!isset($this->serverdata)) {
				$result = $this->CI->db->query('SELECT session_data FROM `'.$this->session_table.'` WHERE session_id=?', array($this->userdata['session_id']));
				if ($result->num_rows() > 0) {
					$row = $result->row();
					$session = $row->session_data;
					$session = @unserialize($session);
					if ($session == '') {
						$session = array();
					}
					$this->serverdata = $session;
					return TRUE;
				} else {
					log_message('error', '_readserverdata called when there is not a valid session in the database');
					return FALSE;
				}
			} else {
				// already read it!
				return TRUE;
			}
		} else {
			log_message('error', 'You cannot access session->serverdata unless you are using databases for your session!');
			return FALSE;
		}
	} 

	/**
		* Internal function to serialize and write the serverdata to the database
		*
		* @authorKelvin Luck
		* @accessprivate
		* @returnvoid
		*/
	function _writeserverdata()
	{
		$server_data_serialized = serialize($this->serverdata);
		$this->CI->db->query($this->CI->db->update_string(	$this->session_table,
															array('session_data' => $server_data_serialized),
															array('session_id' => $this->userdata['session_id'])
														));
	}

}

?>