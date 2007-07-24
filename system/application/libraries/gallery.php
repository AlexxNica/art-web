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
	var $images_info;
	var $config;
	
	function Gallery(){
		$this->CI =& get_instance();
		
		$this->CI->config->load('gallery', true);
		$this->config = $this->CI->config->config['gallery'];
		
		$this->CI->load->library('image_lib');
		
		log_message('info','Gallery library loaded.');
	}
	
	/**
	 * Create Size Variations
	 * 
	 * Creates all the lower possible resolutions of the original image.
	 */
	function create_size_variations($file,$resolutions){
		$config['image_library'] = 'ImageMagick';
		$config['library_path'] = '/opt/local/bin/';
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
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
				
				$this->CI->image_lib->clear();
				$this->CI->image_lib->initialize($config);
				
				if (!$this->CI->image_lib->resize()){
					echo $this->CI->image_lib->display_errors('<p class="error">', '</p>');
					die();
				}
			}
		}
		
		return $return_resolutions;
	}
	
	function process_images($resolutions, $upload_data, $info){
		$used_resolutions = array(array());
		
		$new_filename = time().'_'.$info['name'].'_by_'.$info['username'];
		
		$r_matrix = $this->matrixify_resolutions($resolutions);
		
		foreach($upload_data as $image){
			$value = $this->validate_image($r_matrix,$image);
			
			if ((!$value) OR (isset($used_resolutions[$value['width']][$value['height']]))){
				$this->delete_images($this->images_info);
				$this->delete_images($upload_data);
				return false;
			} else {
				$fn = $new_filename.'_'.$value['width'].$image['file_ext'];
				rename($image['full_path'],$this->config['artwork_path'].$fn);
				$this->images_info[] = array(
						'file_name' => $fn,
						'file_path' => $this->config['artwork_path'],
						'full_path' => $this->config['artwork_path'].$fn,
						'width' 	=> $value['width'],
						'height'	=> $value['height'],
						'file_ext'	=> $image['file_ext'],
						'file_size' => $image['file_size'],
						'file_type' => $image['file_type'],
						'image_type' => $image['image_type']
					);
				$used_resolutions[$value['width']][$value['height']] = 1;
			}
		}
		
		return true;
	}
	
	function data(){
		return $this->images_info;
	}
	
	function create_thumbnail($name,$info){
		$config['source_image'] = $info['full_path'];
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $this->config['thumb_width'];
		$config['height'] = $this->config['thumb_height'];;
		$config['new_image'] = $this->config['thumb_path'].$name.$info['file_ext'];
		$this->CI->image_lib->initialize($config);
		
		$this->CI->image_lib->resize();
	}
	
	function delete_images($files){
		if (is_array($files)){
			foreach($files as $file){
				$this->delete_images($file['full_path']);
			}
		} else {
			@unlink($files);
		}
	}
	
	function validate_image($matrix,$image){
		if (@$matrix[$image['image_width']][$image['image_height']]){
			$image_data = array(
				'width'=> $image['image_width'],
				'height' => $image['image_height']);
				
			return $image_data;
		} else 
			return false;
	}
	
	function matrixify_resolutions($resolutions){
		$matrix = array(array());
		foreach($resolutions as $resolution){
			$matrix[$resolution->width][$resolution->height] = $resolution->id;
			
		}
		
		return $matrix;
	}
}