<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * News Model
 *
 * Manages everything about news items
 */

class News_model extends Model{
	function News_model(){
		parent::Model();
	}
	
	/**
	 * page_list - get news items for a page
	 */
	function page_list ($items_per_page, $start) {
		$this->db->orderby ('date', 'desc');
		$query = $this->db->get ('news', $items_per_page, $start);
		return $query->result ();
	}

	/**
	 * get_total - get the total number of news items
	 */
	function get_total () {
		$query = $this->db->query ('SELECT count(*) AS c FROM news');
		$row = $query->row();
		return $row->c;
	}

	function find($news_id){
		$this->db->where ('id = '.$news_id);
		$query = $this->db->get ('news');
		return $query->result ();
	}

}

?>
