<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function flashget($var){
	$CI =& get_instance();
	return $CI->phpsession->flashget($var);
}

function flashset($var,$value){
	$CI =& get_instance();
	return $CI->phpsession->flashsave($var,$value);
}

?>