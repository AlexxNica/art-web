<?php


class MY_Router extends CI_Router{
	function MY_Router(){
		parent::CI_Router();
		
		log_message('debug', "MY_Router Class Initialized");
	}
	
	/**
	 * Decode URL entities, remove GET requests from the URI, and filter 
	 * segments for malicious characters
	 *
	 * @access	private
	 * @param	string
	 * @return	string
	 */	
	function _filter_uri($str)
	{
		$str = rawurldecode($str);
		
		if ($this->config->item('enable_get_requests')) 
		{
			$qmark = strpos($str, '?');
			if ($qmark !== FALSE)
			{
				$str = substr($str, 0, $qmark);
			}
		}
		
		if ($this->config->item('permitted_uri_chars') != '')
		{
			if ( ! preg_match("|^[".preg_quote($this->config->item('permitted_uri_chars'))."]+$|i", $str))
			{
				exit('The URI you submitted has disallowed characters.');
			}
		}	
			return $str;
	}
}

?>