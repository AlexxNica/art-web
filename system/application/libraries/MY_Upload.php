<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
	* Multiple Upload Library Class
	*
	* @package		AGO - art.gnome.org
	* @subpackage	Libraries
	* @category		Multiple Upload
	* @author		Bruno Santos
	*
	*/
class MY_Upload extends CI_Upload
{

	var $m_data = array();
	var $m_errors = array();
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function MY_Upload($props = array()){
		parent::CI_Upload($props);
		log_message('debug', "MultiUpload Class Initialized");
	}
	
	function do_multiple_upload($userfields){
		$files = array();
		
		if (count($userfields) <= 0){
			$this->set_error('upload_no_file_selected');
			return FALSE;
		}
		
		foreach($userfields as $userfield){
			if ($this->do_upload($userfield)){
				$files[] = $this->upload_path.$this->file_name;
				$this->m_data[] = $this->data();
			} else {
				$this->remove_temp_upload($files);
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	function multiple_data(){
		return $this->m_data;
	}
	
	function remove_temp_upload($files){
		foreach($files as $file){
			@unlink($file);
		}
	}
	
	function config_this($array){
		foreach($array as $key => $val){
			
			$method = 'set_'.$key;
			if (method_exists($this, $method))
			{
				$this->$method($array[$key]);
			}
			else
			{
				$this->$key = $array[$key];
			}
		}
	}
	
	// --------------------------------------------------------------------
	/**
	 * Verify that the filetype is allowed
	 *
	 * @access	public
	 * @return	bool
	 */	
	function is_allowed_filetype()
	{	
		if (count($this->allowed_types) == 0 OR ($this->allowed_types[0] == ""))
		{
			$this->set_error('upload_no_file_types');
			return TRUE;
		}
			 	
		foreach ($this->allowed_types as $val)
		{
			$mime = $this->mimes_types(strtolower($val));
		
			if (is_array($mime))
			{
				if (in_array($this->file_type, $mime, TRUE))
				{
					return TRUE;
				}
			}
			else
			{
				if ($mime == $this->file_type)
				{
					return TRUE;
				}	
			}		
		}
		
		return FALSE;
	}
	// --------------------------------------------------------------------
	
}