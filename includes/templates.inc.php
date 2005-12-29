<?php

/* Find the absolute path for the templates */
$template_root = ereg_replace ('includes/.*', '', __FILE__) . 'templates/';

class template
{
	var $_variables = array ();
	var $filename;
	var $language = 'en';
	var $template_data = '';

	/* consutrctor for php5 */
	function __construct ($filename)
	{
		$this->filename = $filename;
		$this->_variables = array ('{SELF}' => $_SERVER['PHP_SELF']);
		if (array_key_exists ('lang', $_GET))
		{
			$this->language = validate_input_regexp_default ( $_GET['lang'], '^[a-z][a-z]$', 'en');
			$_SESSION['lang'] = $this->language;
		}
		elseif (isset ($_SESSION) && array_key_exists ('lang', $_SESSION))
			$this->language = $_SESSION['lang'];
	}

	/* constructor for php4 */
	function template ($filename)
	{
		$this->__construct ($filename);
	}

	function load_template ()
	{
		global $template_root;
		$filename = $template_root . $this->language .'/'. $this->filename;
		if (!file_exists ($filename)) /* if can't find the localised template, load the english one */
			$filename = $template_root . 'en/'. $this->filename;
		$this->template_data = file_get_contents ($filename);
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

		/* load template if not already loaded */
		if ($this->template_data == '')
			$this->load_template ();

		$output = str_replace (
			array_keys ($this->_variables),
			array_values ($this->_variables),
			$this->template_data
		);
		return $output;
	}

	function write ()
	{
		echo $this->parse();
	}
}

?>
