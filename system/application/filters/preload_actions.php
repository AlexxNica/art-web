<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* Test filter - logs message on filter enter and exit
*/
class Preload_actions_filter extends Filter {
	
	var $CI;
	
    function before() {
		$this->CI =& get_instance();
		
		$config = array();
		$config['categories'] = $this->preload_categories();
		
		$this->CI->config->set_item('preload',$config);
    }
    
    function after() {
    }

	function preload_categories(){
		$this->CI->load->model('Category_model','Category');
		
		$catgories = array();
		$query = $this->CI->Category->get_all();
		foreach($query as $category){
			$info = array();
			if ($category->parent_id != null){
				$info['parent'] = $category->parent_id;
			} else {
				$info['parent'] = null;
			}
			$info['name'] = $category->name;
			$categories[$category->id] = $info;
		}
		
		return $categories;
	}
}
?>