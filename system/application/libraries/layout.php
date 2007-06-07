<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Layout Library Class
 *
 * @package		YATS -- The Layout Library
 * @subpackage	Libraries
 * @category	Template
 * @author		Mario Mariani
 * @copyright	Copyright (c) 2006-2007, mariomariani.net All rights reserved.
 * @license		http://svn.mariomariani.net/yats/trunk/license.txt
 */
class Layout 
{
	var $layout;
	var $settings;
	
	/**
	 * Constructor
	 *
	 * @access	public
	 */	   
	function Layout()
	{
		$this->layout =& get_instance();
		$this->settings();
		$this->layout->load->model($this->settings['model'], 'layoutmodel');

		log_message('info','YATS/Layout class initialized');
	}
	
	// --------------------------------------------------------------------

	/**
	 * buildPage
	 *
	 * Build the whole thing
	 *
	 * @access	public
	 * @param	string	view file
	 * @param	mixed	array with the output data 
	 * @param	string	loader file
	 * @return	void
	 */	   
	function buildPage($view, $data = null, $loader = 'loader')
	{
		$data['settings'] = $this->settings;
		
		foreach ($this->settings['elements'] as $key => $item)
		{
			$data[$key] = $this->layout->layoutmodel->$key($item);
		}
		
		$this->layout->load->view($loader, array('view'=>$view, 'data'=>$data));
	}

	// --------------------------------------------------------------------

	/**
	 * dumpPage
	 *
	 * Dump the whole thing
	 *
	 * @access	public
	 * @param	string	view file
	 * @param	mixed	array with the output data 
	 * @param	boolean	whether or not to return the entire page
	 * @return	string
	 */	   
	function dumpPage($view, $data = null, $fullpage = false)
	{
		$data['settings'] = $this->settings;
		
		if ($fullpage)
		{
			foreach ($this->settings['elements'] as $key => $item)
			{
				$data[$key] = $this->layout->layoutmodel->$key($item);
			}

			$retval = $this->layout->load->view($loader, array('view'=>$view, 'data'=>$data), true);
		}
		else
		{
			$retval = $this->layout->load->view($this->settings['views'] . $this->settings['content'] . $view, $data, true);
		}
		
		return $retval;
	}

	// --------------------------------------------------------------------

	/**
	 * resetTheme
	 *
	 * Reset a theme
	 *
	 * @access	public
	 * @param	string	theme name
	 * @return	void
	 */ 
	function resetTheme($theme)
	{
		$this->settings['design'] = $theme;
	}	

	// --------------------------------------------------------------------

	/**
	 * resetView
	 *
	 * Reset a view folder
	 *
	 * @access	public
	 * @param	string	view folder name
	 * @return	void
	 */ 
	function resetView($view)
	{
		$this->settings['views']   = $view . '/';
		$this->layoutmodel->theme  = $this->settings['views'] . $this->settings['content'];
		$this->layoutmodel->common = $this->settings['views'] . $this->settings['commons'];
	}	

	// --------------------------------------------------------------------

	/**
	 * resetElement
	 *
	 * Reset an item of layout_elements array
	 *
	 * @access	public
	 * @param	string	function name
	 * @param	string	function parameters
	 * @return	void
	 */ 
	function resetElement($element, $parameters = null)
	{
		if (is_array($element))
		{
			foreach ($element as $key => $value)
			{
				if (isset($this->settings['elements'][$key]))
				{
					$this->settings['elements'][$key] = $value;
				}
			}
		}
		else
		{
			if (isset($this->settings['elements'][$element]))
			{
				$this->settings['elements'][$element] = $parameters;
			}
		}
	}
		
	// --------------------------------------------------------------------

	/**
	 * getSetting
	 *
	 * Returns a layout_config item
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */ 
	function getSetting($item)
	{
		return (isset($this->settings[$item])) ? $this->settings[$item] : false;
	}
	
	
	// --------------------------------------------------------------------
	// PRIVATE FUNCTIONS
	// --------------------------------------------------------------------


	/**
	 * Settings
	 *
	 * Returns an array with all template's properties
	 *
	 * @access	private
	 * @return	void
	 */ 
	function settings()
	{
		$this->layout->config->load('layout', true);
		$config = $this->layout->config->config['layout'];
		$settings['assets_url']	 = base_url() . $config['assets_folder'] . '/' . $config['assets_design'] . '/';
		$settings['assets_path'] = dirname(FCPATH) . '/' . $config['assets_folder'] . '/' . $config['assets_design'] . '/';
		$settings['shared_url']	 = base_url() . $config['assets_folder'] . '/' . $config['assets_shared'] . '/';
		$settings['shared_path'] = dirname(FCPATH) . '/' . $config['assets_folder'] . '/' . $config['assets_shared'] . '/';
		$settings['design']		 = $config['assets_design'] . '/';
		$settings['styles']		 = $config['assets_styles'] . '/';
		$settings['images']		 = $config['assets_images'] . '/';
		$settings['script']		 = $config['assets_script'] . '/';
		$settings['views']		 = $config['views_folder'] . '/';
		$settings['commons']	 = $config['views_commons'] . '/';
		$settings['content']	 = $config['views_content'] . '/';
		$settings['model']		 = $config['layout_model'];
		$settings['elements']	 = $config['layout_elements'];
		$this->settings			 = $settings;
	}
}

// EOF
?>