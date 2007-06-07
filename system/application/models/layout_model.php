<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Layout Model Class
 *
 * @package		YATS -- The Layout Library
 * @subpackage	Models
 * @category	Template
 * @author		Mario Mariani
 * @copyright	Copyright (c) 2006-2007, mariomariani.net All rights reserved.
 * @license		http://svn.mariomariani.net/yats/trunk/license.txt
 */
class Layout_model extends Model 
{
	var $common;
	var $theme;
	
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function Layout_model()
	{
		parent::Model();
		$this->theme  = $this->config->config['layout']['views_folder'] . '/' . $this->config->config['layout']['views_content'] . '/' ;
		$this->common = $this->config->config['layout']['views_folder'] . '/' . $this->config->config['layout']['views_commons'] . '/' ;
	}

	// --------------------------------------------------------------------
	//  Bellow this point editing is recommended                                 
	// --------------------------------------------------------------------

	/**
	 * Build the menu
	 *
	 * @access	public
	 * @param	null
	 * @return	string
	 */	   
	function menu()
	{
		// you can write your own menu user routine here
		$retval['menu'] = array(
							anchor('#', 'YATS The Layout Library') => array(
								anchor('#', 'About'),
								'<a href="http://www.codeigniter.com/wiki/Yet_Another_Template_System">User guide</a>',
								'<a href="http://www.codeigniter.com/forums/viewthread/2923/">Support</a>'
								),
							'<a href="http://www.codeigniter.com">Code Igniter</a>' => array(
								'<a href="feed://www.codeigniter.com/rss_2.0">News</a>',
								'<a href="http://www.codeigniter.com/forums">Forums</a>',
								'<a href="http://www.codeigniter.com/wiki">Wiki</a>',
								'<a href="http://www.codeigniter.com/user_guide>User guide</a>"'
								),
							mailto('mario.mariani@gmail.com', 'Contact')										 
							);
		return $this->load->view($this->common . "menu", $retval, true);
	}

	// --------------------------------------------------------------------

	/**
	 * Build the footer stuff
	 *
	 * @access	public
	 * @param	null
	 * @return	string
	 */	   
	function copyright()
	{	 
		// you can write your own footer here
		$retval['copyright'] = "Copyright (c) 2006-2007 Mario Mariani <br />YATS &ndash; The Layout Library is released under the BSD License"; 
		return $this->load->view($this->common . "copyright", $retval, true);
	}
}

// EOF
?>