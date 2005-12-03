<?php

/* Find the absolute path for the templates */
$template_root = ereg_replace ('includes/.*', '', __FILE__) . 'templates/';

class template
{
	var $_variables = array ();
	var $filename;
	var $language = 'en';

	/* consutrctor for php5 */
	function __construct ($filename)
	{
		$this->filename = $filename;
		$this->_variables = array ('{SELF}' => $_SERVER['PHP_SELF']);
	}

	/* constructor for php4 */
	function template ($filename)
	{
		$this->__construct ($filename);
	}

	function add_var ($name, $value)
	{
		$this->_variables['{'.$name.'}'] = $value;
	}

	function add_vars ($array)
	{
		/*
		 * Add several variables at once using an array
		 * TODO: See if there is a more efficiant way of doing this
		 */
		foreach ($array as $key => $value)
			$this->add_var ($key, $value);
	}

	function parse ()
	{
		/*
		 * Parse template file and return the result
		 */
		global $template_root;
		$template = file_get_contents ($template_root . $this->language .'/'. $this->filename);
		$output = str_replace (
			array_keys ($this->_variables),
			array_values ($this->_variables),
			$template
		);
		return $output;
	}

	function write ()
	{
		echo $this->parse();
	}
}

?>
