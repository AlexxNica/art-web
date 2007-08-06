<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Test filter - logs message on filter enter and exit
*/
class Auth_filter extends Filter {
	
	var $CI;
	
    function before() {
		$this->CI =& get_instance();
		
		$this->CI->authentication->authenticate();
		
    }
    
    function after() {
    }
}
?>