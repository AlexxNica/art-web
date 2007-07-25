<?php

class MY_Input extends CI_Input{
	function MY_Input(){
		//parent::CI_Input();
		
		log_message('debug', "MY_Input Class Initialized");

		$CFG =& load_class('Config');
		$this->use_xss_clean	= ($CFG->item('global_xss_filtering') === TRUE) ? TRUE : FALSE;
		$this->allow_get_array	= ($CFG->item('enable_query_strings') === TRUE
								OR $CFG->item('enable_get_requests')  === TRUE) ? TRUE : FALSE;
								
		$this->_sanitize_globals();
	}
	
}

?>