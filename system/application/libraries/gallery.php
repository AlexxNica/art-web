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
	var $file_info;
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
		
		$config = $this->config['image_lib'];
		$config['source_image'] = $file['path'].$file['name'];
		
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
		
		$new_filename = time().'_'.str_replace(' ','',ucwords(strtolower($info['name']))).'_by_'.$info['username'];
		$new_dir = str_replace('/',DIRECTORY_SEPARATOR,(realpath(dirname(FCPATH)).'/'.substr($this->config['artwork_path'],2).'backgrounds/'.$info['category_data']->breadcrumb.'/'));
		@mkdir($new_dir,0777,true);
		$r_matrix = $this->matrixify_resolutions($resolutions);
		
		foreach($upload_data as $image){
			$value = $this->validate_image($r_matrix,$image);
			
			if ((!$value) OR (isset($used_resolutions[$value['width']][$value['height']]))){
				$this->delete_images($this->file_info);
				$this->delete_images($upload_data);
				return false;
			} else {
				$fn = $new_filename.'_'.$value['width'].$image['file_ext'];
				rename($image['full_path'],$new_dir.$fn);
				$this->file_info[] = array(
						'file_name' => $fn,
						'file_path' => $new_dir,
						'full_path' => $new_dir.$fn,
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
		return $this->file_info;
	}
	
	function create_thumbnail($name,$info){
		
		$config = $this->config['image_lib'];
		$config['source_image'] = $info['full_path'];
		
		$config['source_image'] = $info['full_path'];
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $this->config['thumb_width'];
		$config['height'] = $this->config['thumb_height'];
		$config['new_image'] = $this->config['thumb_path'].$name.'.png';//$info['file_ext'];
		
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
	
	function process_theme($file,$info){
		/*find if extension is .tar.gz */
		if (ereg(".*(\.tar\.gz)$", $file['orig_name'])){
			$ext = '.tar.gz';
		} else {
			$ext = $file['file_ext'];
		}
		
		/* new theme name */
		$new_filename = strtoupper($info['category_data']->breadcrumb).'-'.str_replace(' ','',ucwords(strtolower($info['name']))).time();
		/* new theme directory path */
		$new_dir = str_replace('/',DIRECTORY_SEPARATOR,(realpath(dirname(FCPATH)).'/'.substr($this->config['artwork_path'],2).'themes/'.$info['category_data']->breadcrumb.'/'));
		
		/* in case the directory doesn't exists */
		@mkdir($new_dir,0777,true);
		
		/* move it */
		rename($file['full_path'],$new_dir.$new_filename.$ext);
		
		
		$this->file_info[] = array(
				'file_name' => $new_filename.$ext,
				'file_path' => $new_dir,
				'full_path' => $new_dir.$new_filename.$ext,
				'file_ext'	=> $ext,
				'file_size' => $file['file_size'],
				'file_type' => $file['file_type'],
				'is_image' 	=> $file['is_image'],
				'image_width' => $file['image_width'],
				'image_height' =>  $file['image_height']
			);
		
		return true;
	}
	
	function process_screenshot($file,$info){

		/* new screenshot name */
		$new_filename = strtoupper($info['category_data']->breadcrumb).'-'.str_replace(' ','',ucwords(strtolower($info['name']))).time();
		/* new screenshot directory path */
		$new_dir = str_replace('/',DIRECTORY_SEPARATOR,(realpath(dirname(FCPATH)).'/'.substr($this->config['artwork_path'],2).'themes/'.$info['category_data']->breadcrumb.'/'));
		
		/* in case the directory doesn't exists */
		@mkdir($new_dir,0777,true);
		
		/* move it */
		rename($file['full_path'],$new_dir.$new_filename.$file['file_ext']);
		
		
		$this->file_info[] = array(
				'file_name' => $new_filename.$file['file_ext'],
				'file_path' => $new_dir,
				'full_path' => $new_dir.$new_filename.$file['file_ext'],
				'file_ext'	=> $file['file_ext'],
				'file_size' => $file['file_size'],
				'file_type' => $file['file_type'],
				'is_image' 	=> $file['is_image'],
				'image_width' => $file['image_width'],
				'image_height' =>  $file['image_height']
			);
		
		return true;
	}
	
}