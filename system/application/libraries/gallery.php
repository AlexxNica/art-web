<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
	* Gallery Library Class
	*
	* @package		AGO - art.gnome.org
	* @subpackage	Libraries
	* @category		Gallery
	* @author		Bruno Santos
	*
	*/
class Gallery
{
	var $CI;
	
	function Gallery(){
		$this->CI =& get_instance();
		
		$config['image_library'] = 'GD2';
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		
		$this->CI->load->library('image_lib',$config);
		
		log_message('info','Gallery library loaded.');
	}
	
	/**
	 * Create Size Variations
	 * 
	 * Creates all the lower possible resolutions of the original image.
	 */
	function create_size_variations($file,$resolutions){
		$config['source_image'] = $file['path'].$file['name'];
		$config['maintain_ratio'] = TRUE;
		$config['quality'] = 100;
		
		$image = $this->CI->image_lib->get_image_properties($file['path'].$file['name'],TRUE);
		$pos = strrpos($file['name'],'.');
		$ext = substr($file['name'],(strrpos($file['name'],'.')));
		$name = substr($file['name'],0,(strrpos($file['name'],'.')));
		
		foreach($resolutions as $resolution){
			if ($image['width'] > $resolution[0] && $image['height'] > $resolution[1] ){
				$config['width'] = $resolution[0];
				$config['height'] = $resolution[1];
				$config['new_image'] = $name.'_'.$resolution[0].$ext;
				
				$return_resolutions[] = array($resolution[0],$resolution[1],$config['new_image']);
				
				$this->CI->image_lib->initialize($config);
				
				if (!$this->CI->image_lib->resize()){
					echo $this->CI->image_lib->display_errors('<p class="error">', '</p>');
					die();
				}
			}
		}
		
		return $return_resolutions;
		
	}
}