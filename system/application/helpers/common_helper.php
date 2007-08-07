<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Common Helper
 *
 * @package		AGOv3
 * @subpackage	Helpers
 * @category	Misc functionalities
 * @author		Bruno Santos
 */

// ------------------------------------------------------------------------

/**
 * redirect_external
 *
 * The same as redirect but for an external url.
 *
 * Prototype: redirect_external("url", 'method');
 * Example:	  display("http://example.com", 'refresh');
 *
 * @access	public
 * @param	string	redirect url
 * @param	string	redirect method: refresh or location
 * @return	void	
 */
function redirect_external($uri = '', $method = 'location')
{

    switch($method)
    {
        case 'refresh': header("Refresh:0;url=".$uri);
            break;
        default       : header("location:".$uri);
            break;
    }
    exit;
}


/**
 * 	Menu Marker
 *	Checks if the url class name is the same as the parameter
 *	if so, returns marked class
 */ 
function menu_marker($option=FALSE){
	$CI =& get_instance();
	if ($CI->uri->segment(1) == $option){
		return ' class="marked" ';
	}
}

/**
 * 	Thumb URL
 *  Constructs the thumbnail url using the artwork id
 */ 
function thumb_url($artwork_id){
	$CI =& get_instance();
	return base_url().substr($CI->config->config['gallery']['thumb_path'],2).'thumb_'.$artwork_id.'.jpg';
}

/**
 * 
 */
function artwork_url($artwork_id,$category_id){
	$CI =& get_instance();
	
	/*
	// just in case the gallery is accessed by (ago)/browse/(...)
	$uri = $CI->uri->segment_array();
	if ($uri[1]=='browse') {
		$prefix = 'browse/';
	} else {*/
		$prefix = '';
	//}
	
	$categories = $CI->config->config['preload']['categories'];
	$parent_id = null;
	$url = $artwork_id;
	$cur_id = $category_id;
	
	do{
		$parent_id = $categories[$cur_id]['parent'];
		$url = strtolower($categories[$cur_id]['name']).'/'.$url;
		$cur_id = $parent_id;
	} while ($parent_id!=null);
	
	return base_url().$prefix.$url;
}

function categories_breadcrumb($category_id,$separator=' > ',$links=FALSE){
	$CI =& get_instance();
	
	$categories = $CI->config->config['preload']['categories'];
	$parent_id = null;
	$breadcrumbs = array();
	$path = '';
	$cur_id = $category_id;
	
	do{
		$parent_id = $categories[$cur_id]['parent'];
		$breadcrumbs[] = strtolower($categories[$cur_id]['name']);
		$path = strtolower($categories[$cur_id]['name']).$separator.$path;
		$cur_id = $parent_id;
	} while ($parent_id!=null);
	
	$breadcrumbs = array_reverse($breadcrumbs);
	
	if ($links){
		$tmp = '';
		$url = '';
		foreach($breadcrumbs as $unit){
			$tmp .= $unit.'/';
			$url .= '<a href="'.base_url().$tmp.'">'.$unit.'</a>'.$separator;
		}
		return $url;
	} else {
		return $path;
	}
}

/**
 * 	Pagination
 *	Constructs the pagination links
 */
function pagination($total_rows,$per_page,$cur_page,$base_url,$options=array(
											 	'full_tag_open' 	=> '<p>',
											 	'full_tag_close' 	=> '</p>',
											 	'first_link'	  	=> 'First',
												'first_link_tag_open' => '<span>',
												'first_link_tag_close' => '</span>',
												'last_link'			=> 'Last',
												'last_link_tag_open'	=> '<span>',
												'last_link_tag_close'	=> '</span>',
												'next_link'			=> '&gt;',
												'next_tag_open'		=> '<span>',
												'next_tag_close'	=> '</span>',
												'prev_link'		=> '&lt;',
												'prev_tag_open'	=> '<span>',
												'prev_tag_close' => '</span>',
												'cur_tag_open'	=> '<b>',
												'cur_tag_close'	=> '<b>',
												'num_tag_open'		=> '<span>',
												'num_tag_close'		=> '</span>',
												'num_links'			=> 2
											)){
	
	$num_pages = ceil($total_rows/$per_page);
	
	// if item count our per_page is zero no need to continue
	if ($total_rows == 0 OR $per_page == 0)
		return '';
	
	// if there is only one page no need to continue
	if ($num_pages == 1)
		return '';
	
	// if current page number is above the the total number of pages 
	// if so , show last page
	if ($cur_page > $num_pages){
		$cur_page = $num_pages;
	}
		
	// Calculate the start and end numbers. These determine
	// which number to start and end the digit links with
	$start = (($cur_page - $options['num_links']) > 0) ? $cur_page - ($options['num_links'] - 1) : 1;
	$end   = (($cur_page + $options['num_links']) < $num_pages) ? $cur_page + $options['num_links'] : $num_pages;
	
	
	// let the games begin...
	$output = '';
	
	// render the "first" link
	if  ($cur_page > $options['num_links'])
	{
		$output .= $options['first_tag_open'].'<a href="'.$base_url.'?page=1">'.$options['first_link'].'</a>'.$options['first_tag_close'];
	}
	
	// Render the "previous" link
 	if  (($cur_page - $options['num_links']) >= 0)
	{
		$i = $cur_page - 1;
		if ($i == 0) $i = '';
		$output .= $options['prev_tag_open'].'<a href="'.$base_url.'?page='.$i.'">'.$options['prev_link'].'</a>'.$options['prev_tag_close'];
	}
	
	// Write the digit links
	for ($loop = $start; $loop <= $end; $loop++)
	{
		$i = $loop;
				
		if ($i >= 0)
		{
			if ($cur_page == $loop)
			{
				$output .= $options['cur_tag_open'].$loop.$options['cur_tag_close']; // Current page
			}
			else
			{
				$n = ($i == 0) ? '' : $i;
				$output .= $options['num_tag_open'].'<a href="'.$base_url.'?page='.$n.'">'.$loop.'</a>'.$options['num_tag_close'];
			}
		}
	}

	// Render the "next" link
	if ($cur_page < $num_pages)
	{
		$output .= $options['next_tag_open'].'<a href="'.$base_url.'?page='.($cur_page +1).'">'.$options['next_link'].'</a>'.$options['next_tag_close'];
	}

	// Render the "Last" link
	if (($cur_page + $options['num_links']) < $num_pages)
	{
		$i = $num_pages;
		$output .= $options['last_tag_open'].'<a href="'.$base_url.'?page='.$i.'">'.$options['last_link'].'</a>'.$options['last_tag_close'];
	}
	
	// Add the wrapper HTML if exists
	$output = $options['full_tag_open'].$output.$options['full_tag_close'];
	
	return $output;
}



?>
