<?php

class MY_Loader extends CI_Loader{
	function MY_Loader(){
		parent::CI_Loader();

		log_message('debug', 'MY_Loader Class initialized');
	}

	// --------------------------------------------------------------------

	/**
		* Autoloader
		*
		* The config/autoload.php file contains an array that permits sub-systems,
		* libraries, plugins, and helpers to be loaded automatically.
		*
		* @access	private
		* @param	array
		* @return	void
		*/
	function _ci_autoloader()
	{	
		include(APPPATH.'config/autoload'.EXT);

		if ( ! isset($autoload))
		{
			return FALSE;
		}

		// Load any custom config file
		if (count($autoload['config']) > 0)
		{			
			$CI =& get_instance();
			foreach ($autoload['config'] as $key => $val)
			{
				if ($val == TRUE){
					$CI->config->load($key,TRUE);
				} else {
					$CI->config->load($val);
				}
			}
		}		

		// Autoload plugins, helpers, scripts and languages
		foreach (array('helper', 'plugin', 'script', 'language') as $type)
		{			
			if (isset($autoload[$type]) AND count($autoload[$type]) > 0)
			{
				$this->$type($autoload[$type]);
			}		
		}

		// A little tweak to remain backward compatible
		// The $autoload['core'] item was deprecated
		if ( ! isset($autoload['libraries']))
		{
			$autoload['libraries'] = $autoload['core'];
		}

		// Load libraries
		if (isset($autoload['libraries']) AND count($autoload['libraries']) > 0)
		{
			// Load the database driver.
			if (in_array('database', $autoload['libraries']))
			{
				$this->database();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('database'));
			}

			// Load the model class.
			if (in_array('model', $autoload['libraries']))
			{
				$this->model();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('model'));
			}

			// Load scaffolding
			if (in_array('scaffolding', $autoload['libraries']))
			{
				$this->scaffolding();
				$autoload['libraries'] = array_diff($autoload['libraries'], array('scaffolding'));
			}

			// Load all other libraries
			foreach ($autoload['libraries'] as $item)
			{
				$this->library($item);
			}
		}		
	}

}

?>