<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * A simple OpenID consumer library.
 * 
 * Requires PHP OpenID library from http://www.openidenabled.com/openid/libraries/php
 * 
 * This library was based in the component developed by Daniel Hofstetter  (http://cakebaker.42dh.com).
 * 
 * License: MIT
 */


class OpenID
{
	
	var $storePath;
	
	function OpenID()
	{
		$this->storePath = "/tmp/openid";
		
		$this->CI =& get_instance();
		$this->CI->load->helper('common');
		$this->CI->load->library('QwstOpenIdSessionAdapter');
		/* 
			You need to have this line in index.php (or here)
			ini_set('include_path', ini_get('include_path').':'.APPPATH.'vendors/');
			so vendors' path is in include_path.
		*/
		
		log_message('info','OpenID class initialized');
	}
	
	function authenticate($openId, $processUrl, $trustRoot, $extensionArguments = null)
	{
		$consumer = $this->__getInfo();
		$authRequest = $consumer->begin($openId);
		if (!$authRequest) {
		    return false; // authentication error.
		}
		
		/*
		 * Register needed info from the openID account
		 * e.g. email : $authRequest->addExtensionArg('sreg', 'optional', 'email');
		 */
		if ($extensionArguments != null) {
			foreach ($extensionArguments as $extensionArgument) {
				if (count($extensionArgument) == 3)
				{
					$authRequest->addExtensionArg($extensionArgument[0], $extensionArgument[1], $extensionArgument[2]);
				}
			}
		}
		
		$redirectUrl = $authRequest->redirectURL($trustRoot, $processUrl);
		
		redirect_external($redirectUrl);
	}
	
	function getResponse()
	{
		$consumer = $this->__getInfo();
		$response = $consumer->complete($_GET);
		
		return $response;
	}
	
	function __getInfo()
	{
		
		/**
		 * Require the OpenID consumer code.
		 */
		require_once "Auth/OpenID/Consumer.php";
		/**
		 * Require the "file store" module, which we'll need to store OpenID
		 * information.
		 */
		require_once "Auth/OpenID/FileStore.php";

		if (!file_exists($this->storePath) && !mkdir($this->storePath)) {
		    print "Could not create the FileStore directory '$this->storePath'. Please check the effective permissions.";
		    exit(0);
		}

		$store = new Auth_OpenID_FileStore($this->storePath);
		$consumer = new Auth_OpenID_Consumer($store,$this->CI->qwstopenidsessionadapter);
		
		return $consumer;
	}
}