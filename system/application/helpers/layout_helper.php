<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Layout Helper
 *
 * @package		YATS -- The Layout Library
 * @subpackage	Helpers
 * @category	Template
 * @author		Mario Mariani
 * @copyright	Copyright (c) 2006-2007, mariomariani.net All rights reserved.
 * @license		http://svn.mariomariani.net/yats/trunk/license.txt
 */

// ------------------------------------------------------------------------

/**
 * Display
 *
 * Tests and outputs a template variable to display it on a view. Its use
 * is recommended directly in the view files.
 *
 * Prototype: display("template-variable", array("library" => "function"));
 * Example:	  display("hello_user", array("agent" => "is_browser"));
 * Note: to execute the validation correctly all validators must 
 *		 return a boolean value -- if FALSE display() will echo NULL. 
 *
 * @access	public
 * @param	string	template variable i.e. data to display
 * @param	array	validation calls
 * @return	mixed	string with data to display or null
 */ 
function display($item, $validators = null)
{
	if (is_array($validators))
	{
		$object =& get_instance();
		foreach ($validators as $key => $value)
		{
			if ($object->$key->$value() === FALSE) return;
		}
	}

	return $item;
}

// ------------------------------------------------------------------------

/**
 * Dump
 *
 * Tests and returns a template variable to display it on a view. 
 *
 * Prototype: dump("template-variable", array("library" => "function"));
 * Example:	  dump("hello_user", array("agent" => "is_browser"));
 * Note: to execute the validation correctly all validators must 
 *		 return a boolean value -- if FALSE dump() will return NULL. 
 *
 * @access	public
 * @param	string	template variable i.e. data to display
 * @param	array	validation calls
 * @return	mixed	string with data to display or null
 */ 
function dump($item, $validators = null)
{
	if (is_array($validators))
	{
		$object =& get_instance();
		foreach ($validators as $key => $value)
		{
			if ($object->$key->$value() === FALSE) return;
		}
	}

	return $item;
}

// ------------------------------------------------------------------------

/**
 * Property
 *
 * Outputs a template property (those config variables started by 'app_').
 *
 * Prototype: property("template-property");
 * Example:	  property("app_title");
 *
 * @access	public
 * @param	string	the property name
 * @return	mixed	string with the property value or null if it's not found
 */ 
function property($item)
{
	$object =& get_instance();
	return (!empty($item) && strstr($item, 'app_') !== FALSE) ? $object->config->config['layout'][$item] : null;
}

// ------------------------------------------------------------------------

/**
 * Style
 *
 * Outputs a css link tag
 *
 * Prototype: style("archive.css", additional-attributes);
 * Example:	  style("main.css", array('media'=>'screen', 'charset'=>'utf-8'));
 *
 * @access	public
 * @param	string	the filename inside the template's css folder
 * @param	string	array with miscellaneous tag attributes
 * @return	string	string with the property value 
 */ 
function style($file, $attributes = null)
{
	if (empty($file)) return;
	
	if (is_array($attributes))
	{
		$retval = '<link rel="stylesheet" href="'. getPath($file, 'styles') .'" type="text/css" ';
		foreach ($attributes as $key => $value)
		{
			$retval .= "$key=\"$value\" ";
		}
		$retval .= "/>\n";
	}
	else
	{
		$retval = '<link rel="stylesheet" href="'. getPath($file, 'styles') .'" type="text/css" media="all" />' . "\n";
	}

	return $retval;
}

// ------------------------------------------------------------------------

/**
 * Script
 *
 * Outputs a script tag
 *
 * Prototype: script("archive.js");
 * Example:	  script("main.js");
 *
 * @access	public
 * @param	string	the filename inside the template's css folder
 * @return	string	string with the property value
 */ 
function script($file)
{
	if (empty($file)) return;

	return '<script src="'. getPath($file, 'script') .'" type="text/javascript" charset="utf-8"></script>' . "\n";
}

// ------------------------------------------------------------------------

/**
 * Image
 *
 * Outputs a img tag
 *
 * Prototype: image("image.yyz", "alt/title-attribute", "additional-attributes");
 * Example:	  image("movingpictures.jpg", "Moving Pictures", array('style'=>'border:0;float:right;margin:10px;'));
 *
 * @access	public
 * @param	string	the filename inside the template's image folder
 * @param	string	image description
 * @param	string	array with miscelaneous tag attributes
 * @return	string	string with the property value 
 */ 
function image($file, $alt = null, $attributes = null)
{
	if (empty($file)) return;

	$file = getPath($file, 'images', true);
	if ($file['exists'] && !isset($attributes['width']) && !isset($attributes['height'])) list($width, $height, $type, $attr) = @getimagesize($file['path']);	
	$retval = '<img src="'. $file['path'] .'"'. (isset($attr) ? " $attr" : null);
	if (is_array($attributes)) foreach ($attributes as $key => $value) $retval .= "$key=\"$value\" ";
	if (!is_null($alt)) $retval .= ' alt="'. $alt .'" title="'. $alt .'" ';
	$retval .= "/>";

	return $retval;
}

// ------------------------------------------------------------------------

/**
 * FavIcon
 *
 * Outputs a favorite icon tag
 *
 * Prototype: favicon("file.ext");
 * Example:	 favicon("site-icon.ico");
 *
 * @access	public
 * @param	string	the filename within the template's images folder
 * @return	string	string with the property value
 */
function favicon($file)
{
	if (empty($file)) return;

	return '<link rel="shortcut icon" href="'. getPath($file, 'images') .'" type="image/ico" />' . "\n";
}

// ------------------------------------------------------------------------

/**
 * Hyperlink
 *
 * Outputs a link tag to other places on the web
 *
 * Prototype: hyperlink("url", "link-title", "target-attribute");
 * Example:	 hyperlink("http://www.mariomariani.net", "M2/Blog", "blank");
 *
 * @access	public
 * @param	string	the URL
 * @param	string	link's title
 * @param	string	target window (no underscore before the target)
 *					- blank: all the links will open in new windows
 * 					- self: all the links will open in the same frame they where clicked (default)
 * 					- parent: all the links will open in the parent frameset
 * 					- top: all the links will open in the full body of the window
 * @param	string	array with miscelaneous tag attributes
 * @return	string	string with the property value
 */
function hyperlink($location, $title, $target = 'self', $attributes = null)
{
	if (empty($location) || empty($title)) return;
	
	$retval = '<a href="'. $location .'" target="_'. $target .'" title="'. $title .'"';
	if (is_array($attributes)) 
	{
		foreach ($attributes as $key => $value) $retval .= " $key=\"$value\"";
	}
	$retval .= ">$title</a>\n";

	return $retval;
}


// ------------------------------------------------------------------------
// PRIVATE FUNCTIONS
// ------------------------------------------------------------------------


/**
 * Get Settings
 *
 * Returns an array with the theme settings section of the config file
 *
 * Prototype: getSettings();
 * Example:	 $array = getSettings();
 *
 * @access	private
 * @return	array
 */
function getSettings()
{
	$object =& get_instance();
	$retval['assets_url']  = $object->layout->getSetting('assets_url');
	$retval['assets_path'] = $object->layout->getSetting('assets_path');
	$retval['shared_url']  = $object->layout->getSetting('shared_url');
	$retval['shared_path'] = $object->layout->getSetting('shared_path');
	$retval['images']	   = $object->layout->getSetting('images');
	$retval['styles']	   = $object->layout->getSetting('styles');
	$retval['script']  	   = $object->layout->getSetting('script');

	return $retval;
}

// ------------------------------------------------------------------------

/**
 * Get Path
 *
 * Outputs a link tag to other places on the web
 *
 * Prototype: getPath('filename.ext', 'type');
 * Example:	 $filepath = getPath($file, 'images');
 *
 * @access	private
 * @return	string
 */
function getPath($file, $type, $exists = false)
{
	$settings = getSettings();
	
	if (file_exists($settings['assets_path'] . $settings[$type] . $file))
	{
		$filepath = $settings['assets_url'] . $settings[$type] . $file;
		$existent = true;
	}
	elseif (file_exists($settings['shared_path'] . $settings[$type] . $file))
	{
		$filepath = $settings['shared_url'] . $settings[$type] . $file;
		$existent = true;
	}
	else
	{
		$filepath = $settings['assets_url'] . $settings[$type] . $file;
		$existent = false;
		log_message('error', "Layout helper was unable to load the requested file: $file");
	}
	
	if ($exists)
	{
		$retval['path']   = $filepath;
		$retval['exists'] = $existent;
	}
	else
	{
		$retval = $filepath;
	}

	return $retval;
}
// EOF
?>