<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Performance monitoring filter.
*
* Logs message if the action processing time exceeded given threshold
* ($warning_time config property).
* 
* Example usages:
*  - detection of slowly executing controllers / actions
*  - warning administrator about the degradation of the app performance
*
* Remember that measuring processing time from the inside of the PHP script
* doesn't give you the real times experienced by the customer, because it
* doesn't count the time needed to connect to the Apache server, obtain mod_php
* or php-cgi, "compile" the PHP script, initiate the framework and send
* the results to the web browser.
*
* Author: Dariusz Debowczyk
*/
class Perfmon_filter extends Filter {
	var $started;
	var $default_warning_time = 0.1;
	
	/**
	* Start checkpoint
	*/
	function before() {
		$this->started = microtime();
		
		if (!isset($this->config['warning_time']))
		{
			$this->config['warning_time'] = $this->default_warning_time;
		}
	}
	
	/**
	* End checkpoint
	*
	* Record the time and log it if the threshold is exceeded.
	*/
	function after() {
		$finished = microtime();
		
		$diff = $this->mt_diff($finished, $this->started);
		
		if ($diff >= $this->config['warning_time']) {
			log_message('error', 'Action processing time exceeded allowed limit = '. $this->config['warning_time'] . ' sec(s) - action executed in ' .$diff. ' sec(s).');
		}		
	}
	
	/**
	* Returns microtime unified into one string
	* (without the separating space)
	*/
	function mt_unify($mt)
	{
		$et = explode(" ", $mt);
		return $et[1] . substr($et[0], 1);
	}
	
	/**
	* Returns difference of $mt1 and $mt2
	*
	* @param mt1 microtime to subtract from (end time)
	* @param mt2 microtime which will be subtracted (start time)
	* @return difference between mt1 and mt2 (literally: mt1 - mt2)
	*/
	function mt_diff($mt1, $mt2)
	{
		return $this->mt_unify($mt1) - $this->mt_unify($mt2);
	}
}
?>