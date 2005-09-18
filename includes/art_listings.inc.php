<?php

require_once("mysql.inc.php");
require_once("common.inc.php");

/* helper for create_page_link */
function add_var_if_set($var_name, $value) {
	if (isset($value))
		print('&amp;'.$var_name.'='.htmlentities($value));
}

function print_select_box_with_label ($label, $name, $array, $value)
{
	print('<label for="'. $name.'">'.$label.'</label>');
	
	print_select_box ($name, $array, $value);
}

function get_striped_var($val)
{
	return get_magic_quotes_gpc() ? stripslashes($val) : $val;
}

class general_listing
{
	var $select_result;
	
	/* the following can be changed arbitrarily */
	var $per_page = 12;
	var $view  = 'list';
	var $page  = 1;
	var $date_type = 'relative';
	var $results;
	var $num_pages;
	var $none_message = 'No art has been submitted in this category.';
	var $format = 'html';

	function get_num_comments($artID, $type)
	{
		$result = mysql_query('SELECT COUNT(artID) AS n FROM comment WHERE artID = '."$artID AND type='$type' AND status!='deleted'");

		list($n) = mysql_fetch_row($result);
		return $n;
	}

	
	function get_view_options()
	{
		global $view_array;
		set_session_var_default('per_page', 12);
		set_session_var_default('view', 'list');
		
		$this->per_page = $_SESSION['per_page'] = intval(validate_input_regexp_default ($_GET["thumbnails_per_page"], "^[0-9]+$", $_SESSION['per_page']));
		$this->view     = $_SESSION['view']     = validate_input_array_default  ($_GET["view"], array_keys($view_array), $_SESSION['view']);
		
		$this->page = intval(validate_input_regexp_default ($_GET["page"], "^[0-9]+$", 1));
	}
	
	function get_limit()
	{
		if ($this->page > 1) {
			return ' LIMIT '.(($this->page-1) * $this->per_page).', '.$this->per_page;
		} else {
			return ' LIMIT '.$this->per_page;
		}
	}
	
	function print_shown_slice()
	{
		if ($this->results > 0) {
			print('<strong>Showing ' . (($this->page - 1) * $this->per_page + 1) . ' through ');
			if ($this->page * $this->per_page < $this->results) {
				print($this->page * $this->per_page);
			} else {
				print($this->results);
			}
			print(' of ' . $this->results . ' results.</strong><br />');
		}
	}
	
	/* helper for print_page_numbers */
	function create_page_link ($page, $label = FALSE) {
		if($page == $this->page)
		{
			print(" <strong>$page</strong>\n ");
		}
		else
		{
			if ($label === FALSE) $label = $page;
			
			print('<a class="box" href="'.$_SERVER['PHP_SELF'].'?page='.$page);
			
			add_var_if_set('sort_by', $this->sort_by);
			add_var_if_set('thumbnails_per_page', $this->per_page);
			add_var_if_set('view', $this->view);
			add_var_if_set('resolution', $this->resolution);
			add_var_if_set('order', $this->order);
			add_var_if_set('search_text', $this->search_text);
			add_var_if_set('search_type', $this->search_type);
			
			print("\">$label</a>\n");
		}
	}
		
	function print_page_numbers()
	{
		if (isset($this->num_pages)) {
			print('<!-- Page Navigation System -->');
			print('<div style="clear:both; text-align:center"><p>');

			/* currently there are no arows on a.g.o. So commented it out again
			 * Also this prevents the page numbers to 'jump' around. */
			/*if ($this->page > 1) {
				$this->create_page_link ($this->page - 1, '<<');
			}*/

			for ($page=1; $page <= $this->num_pages; $page++)
			{
				$this->create_page_link($page);
			}
			/* if($this->page < $this->num_pages) {
				$this->create_page_link ($this->page + 1, '>>');
			}*/
			
			print('</p></div>');
		}
	}
	
	function print_listing()
	{
		global $theme_config_array;
		global $background_config_array;
		global $site_url;
		while($row = mysql_fetch_assoc($this->select_result))
		{
			$any_result = TRUE;
			
			
			$itemID = $row['ID'];
			$type   = $row['type']; /* Has to be set in the SQL statement. */
			$name   = $row['name'];
			$rating = $row['rating'];
			$category  = $row['category'];
			$add_date  = $row['add_timestamp'];
			
			$thumbnail = get_thumbnail_url($row['thumbnail_filename'], $itemID, $type, $category);
			$thumbnail_class = get_thumbnail_class ($category);
			
			if ($this->date_type == 'absolute')
				$date = date("j F Y", $add_date);
			else
				$date = ucfirst(FormatRelativeDate(time(), $add_date));
			
			/* XXX: absolute url ... */
			$link = $site_url."/{$type}s/$category/$itemID";
			
			$category_name = get_category_name($type, $category);
			
			/*----*/
			if ($this->format == 'rss') {
				print("<item>\n");
				print("\t<title>[$category_name] ".htmlentities($name)."</title>\n");
				print("\t<link>$link</link>\n");
				print("\t<guid>$link</guid>\n");
				print("\t<pubDate>".date("r", $add_date)."</pubDate>\n");
				print("\t<description><![CDATA[\n");
			}
			switch ($this->view) {
				case 'icons':
					print("<div class=\"icon_view\">\n<a href=\"$link\">");
					print("\t<img src=\"$thumbnail\" alt=\"Thumbnail of $item_name\" class=\"$thumbnail_class\" border=\"0\" />");
					print("</a><br/>\n");
					rating_bar($rating);
					print("</div>\n");
				break;
				
				case 'list':
				default:
					print("<table border=\"0\" style=\"margin-bottom:1em;\"><tr>\n");
					print("\t<td style=\"width:120px\"><a href=\"$link\"><img src=\"$thumbnail\" alt=\"Thumbnail\" class=\"$thumbnail_class\"/></a>");
					print("</td>\n");
					print("\t<td><a href=\"$link\" class=\"h2\"><strong>".htmlentities($name)."</strong></a><br/>\n");
					print("\t\t<span class=\"subtitle\">$category_name<br/>$date</span><br/>\n");
					$rating = rating_bar($rating);
					$comment_count = $this->get_num_comments($itemID, $type);
					if ($comment_count > 0)
					{
						print('<small style="white-space: nowrap">');
						print('<a href="'.$link.'#comments" style="text-decoration: none">');
						print('<img src="/images/site/stock_draw-callouts-16.png" />&nbsp;&nbsp;');
						print($comment_count . ' comments</small>');
					}
					print("\n\t</td>\n");
					print("</tr></table>\n");
				break;
			}
			if ($this->format == 'rss') {
				print("]]>\n\t</description>\n");
				print("</item>\n");
			}
		}
		
		if ($any_result != TRUE) {
			print('<p class="info">'.$this->none_message.'</p>');
		}
	}
}

class top_rated_list extends general_listing
{
	function top_rated_list()
	{
		$this->per_page = 5;
	}
	
	function select()
	{
		$this->select_result = mysql_query('SELECT backgroundID AS ID, \'background\' AS type, background_name AS name, rating, category, add_timestamp, thumbnail_filename FROM background UNION '.
		                                   'SELECT themeID AS ID, \'theme\' AS type, theme_name AS name, rating, category, add_timestamp, small_thumbnail_filename AS thumbnail_filename FROM theme '.
		                                   'ORDER BY rating DESC '.$this->get_limit());
	}
}

class latest_updates_list extends general_listing
{
	function latest_updates_list()
	{
		$this->per_page = 5;
	}
	
	function select()
	{
		$this->select_result = mysql_query('SELECT backgroundID AS ID, \'background\' AS type, background_name AS name, rating, category, add_timestamp, thumbnail_filename FROM background UNION '.
		                                   'SELECT themeID AS ID, \'theme\' AS type, theme_name AS name, rating, category, add_timestamp, small_thumbnail_filename AS thumbnail_filename FROM theme '.
		                                   'ORDER BY add_timestamp DESC '.$this->get_limit());
	}
}

class theme_list extends general_listing
{
	var $sort_by;
	var $order;
	
	function get_view_options()
	{
		global $sort_by_array;
		
		set_session_var_default('sort_by', 'add_timestamp');
		set_session_var_default('order', 'DESC');
		
		$this->sort_by = $_SESSION['sort_by'] = validate_input_array_default ($_GET['sort_by'], array_keys($sort_by_array), $_SESSION['sort_by']);
		$this->order   = $_SESSION['order']   = validate_input_array_default ($_GET['order'], array("ASC","DESC"), $_SESSION['order']);
		
		parent::get_view_options();
	}

	function print_search_form () {
		global $sort_by_array, $thumbnails_per_page_array, $view_array, $order_array;
		print('<form action="'.$_SERVER['PHP_SELF'].'" method="get">');
		print('<p>');
	
		print_select_box_with_label('Sort By:', 'sort_by', $sort_by_array, $this->sort_by);
		print_select_box_with_label('Show: ', 'thumbnails_per_page', $thumbnails_per_page_array, $this->per_page);
		print_select_box_with_label('View:', 'view', $view_array, $this->view);
		print_select_box_with_label('Order:', 'order', $order_array, $this->order);
		print('<input type="submit" value="Change"/>');
		print('</p></form>');
	}
	
	function get_where_clause($category)
	{
		global $theme_config_array;
		if (!array_key_exists($category, $theme_config_array)) art_file_not_found(); /* needed? */
		
		return ' WHERE category=\'' . $category . '\' AND parent = 0 AND status="active" ';
	}
	
	function select($category)
	{
		if ($this->per_page < 1000) { /* XXX: maybe change this to 'all' */
			$this->num_pages = mysql_fetch_array(mysql_query('SELECT count(themeID) FROM theme '.$this->get_where_clause($category)));
			$this->num_pages = ceil($this->num_pages[0] / $this->per_page);
		}
		
		$this->select_result = mysql_query('SELECT themeID AS ID, \'theme\' AS type, theme_name AS name, rating, category, add_timestamp, small_thumbnail_filename AS thumbnail_filename, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS downloads_per_day FROM theme '.
		                                   $this->get_where_clause($category). ' ORDER BY '. $this->sort_by. ' '. $this->order. $this->get_limit());
	}
}

class contest_list extends theme_list
{
	function get_where_clause($category)
	{
		global $contest_config_array;
		if (!array_key_exists($category, $contest_config_array)) art_file_not_found(); /* needed? */
		
		return ' WHERE contest=\'' . $category . '\' AND parent = 0 AND status="active" ';
	}
	
	function select($category)
	{
		if ($this->per_page < 1000) { /* XXX: maybe change this to 'all' */
			$this->num_pages = mysql_fetch_array(mysql_query('SELECT count(contestID) FROM contest '.$this->get_where_clause($category)));
			$this->num_pages = ceil($this->num_pages[0] / $this->per_page);
		}
		
		$this->select_result = mysql_query('SELECT contestID AS ID, \'contest\' AS type, name, rating, contest AS category, add_timestamp, small_thumbnail_filename AS thumbnail_filename, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS downloads_per_day FROM contest '.
		                                   $this->get_where_clause($category). ' ORDER BY '. $this->sort_by. ' '. $this->order. $this->get_limit());
	}
}

class background_list extends general_listing
{
	var $sort_by;
	var $order;
	var $resolution;
	
	function get_view_options()
	{
		global $sort_by_array, $resolution_array;
		
		set_session_var_default('sort_by', 'add_timestamp');
		set_session_var_default('order', 'DESC');
		set_session_var_default('resolution', '%');
		
		$this->sort_by = $_SESSION['sort_by'] = validate_input_array_default ($_GET["sort_by"], array_keys($sort_by_array), $_SESSION['sort_by']);
		$this->order   = $_SESSION['order']   = validate_input_array_default ($_GET['order'], array("ASC","DESC"), $_SESSION['order']);
		$this->resolution = $_SESSION['resolution'] = validate_input_array_default ($_GET["resolution"], array_keys($resolution_array), $_SESSION['resolution']);
		
		parent::get_view_options();
	}
	
	function print_search_form () {
		global $sort_by_array, $thumbnails_per_page_array, $view_array, $order_array, $resolution_array;
		print('<form action="'.$_SERVER['PHP_SELF'].'" method="get">');
		print('<p>');
	
		print_select_box_with_label('Sort By:', 'sort_by', $sort_by_array, $this->sort_by);
		print_select_box_with_label('Show: ', 'thumbnails_per_page', $thumbnails_per_page_array, $this->per_page);
		print_select_box_with_label('View:', 'view', $view_array, $this->view);
		print_select_box_with_label('Order:', 'order', $order_array, $this->order);
		print_select_box_with_label('Resolution:', 'resolution', $resolution_array, $this->resolution);
		print('<input type="submit" value="Change"/>');
		print('</p></form>');
	}
	
	function get_where_clause($category)
	{
		global $background_config_array;
		if (!array_key_exists($category, $background_config_array)) art_file_not_found();
		if ($this->resolution == '%') {
			return " WHERE category='$category' AND status='active' AND parent='0'";
		} else {
			return ' RIGHT JOIN background_resolution ON background.backgroundID=background_resolution.backgroundID'.
			       " WHERE category='$category' AND status='active' AND parent='0' AND resolution='".mysql_real_escape_string($this->resolution)."'";
		}
	}
	
	function select($category)
	{
		if ($this->resolution == '%') {
			$id_sql = 'backgroundID';
		} else {
			$id_sql = 'DISTINCT(background.backgroundID)';
		}
		if ($this->per_page < 1000) { /* XXX: maybe change this to 'all' */
			$this->results = mysql_fetch_array(mysql_query("SELECT count($id_sql) FROM background ".
			                                               $this->get_where_clause($category)));
			$this->results = $this->results[0];
			$this->num_pages = ceil($this->results / $this->per_page);
		}
		
		$this->select_result = mysql_query("SELECT $id_sql AS ID, 'background' AS type, background_name AS name, rating, category, add_timestamp, thumbnail_filename, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS downloads_per_day FROM background ".
		                                   $this->get_where_clause($category). ' ORDER BY '. $this->sort_by. ' '. $this->order. $this->get_limit());
	}
}

class search_result extends general_listing
{
	var $search_text;
	var $search_type;
	var $type;
	var $sort_by;
	var $order = 'DESC';
	
	function search_result()
	{
		$this->none_message = 'No results matched your search, please try again.';
	}
	
	function print_search_form()
	{
		global $search_type_array, $thumbnails_per_page_array, $sort_by_array, $order_array;
		
		print("<form action=\"" . $GLOBALS["PHP_SELF"] . "\" method=\"get\">");
		print("<table border=\"0\">\n");
		
		print("\t<tr><td>Search in:</td><td>");
		print_select_box("search_type", $search_type_array, $this->search_type);
		print("</td></tr>\n");
		
		print("\t<tr><td>For The Text:</td><td><input type=\"text\" name=\"search_text\" value=\"".$this->search_text."\"/></td></tr>\n");
		
		print("\t<tr><td>Sort By:</td><td>");
		print_select_box("sort_by", $sort_by_array, $this->sort_by);
		print("</td></tr>\n");
		
		print("\t<tr><td>Results Per Page:</td><td>");
		print_select_box("thumbnails_per_page", $thumbnails_per_page_array, $this->per_page);
		print("</td></tr>\n");
	
		print("\t<tr><td>Order:</td><td>");
		print_select_box("order", $order_array, $this->order);
		print("</td></tr>\n");
		
		print("\t<tr><td colspan=\"2\"><input type=\"submit\" value=\"Search\"/></td></tr>");
	
		print("</table>\n");
		print("</form>\n");
	}
	
	function get_view_options()
	{
		global $search_type_array, $sort_by_array;
		set_session_var_default('search_type', 'author');
		set_session_var_default('search_sort_by', 'name');
		
		$this->search_type = $_SESSION['search_type'] = validate_input_array_default ($_GET["search_type"], array_keys($search_type_array), $_SESSION['search_type']);
		$this->sort_by = $_SESSION['search_sort_by'] = validate_input_array_default ($_GET["sort_by"], array_keys($sort_by_array), $_SESSION['search_sort_by']);
		$this->search_text = get_striped_var($_GET['search_text']);
		
		parent::get_view_options();
		
		/* force list view */
		$this->view = 'list';
	}
	
	function get_order_by()
	{
		return ' ORDER BY '.$this->sort_by.' '.$this->order;
	}
	
	function get_where_clause()
	{
		return ' WHERE '.$this->type.'_name LIKE \'%'.mysql_real_escape_string($this->search_text)."%' AND status='active' AND parent='0' ";
	}
	
	function print_listing()
	{
		if ($this->search_type != 'author') {
			parent::print_listing();
		} else {
			if(mysql_num_rows($this->select_result) > 0) {
			
				print("<div class=\"h2\">Search Results</div><ul>");
				while (list($userID, $realname) = mysql_fetch_row($this->select_result)) {
					print("\t<li><a href=\"/users/$userID\">$realname</a></li>");
				}
				print("</ul>");
				
			} else {
				print('<p class="info">'.$this->none_message.'</p>');
			}
		}
	}
	
	function select()
	{
		if ($this->search_type != 'author') {
			if ($this->search_type == 'theme_name') {
				$this->type = 'theme';
			} elseif ($this->search_type == 'background_name') {
				$this->type = 'background';
			} elseif ($this->search_type == 'all') {
				$this->type = 'all';
			}
			else {
				print_error("Something impossible happend.");
			}
			
			if ($this->per_page < 1000) { /* XXX: maybe change this to 'all' */
				if ($this->type == 'all') {
					$this->type = 'theme';
					$sql = 'SELECT count(*) FROM theme '. $this->get_where_clause();
					$result = mysql_fetch_array(mysql_query($sql));
					$this->results = $result[0];

					$this->type = 'background';
					$sql = 'SELECT count(*) FROM background' . $this->get_where_clause();
					$result = mysql_fetch_array(mysql_query($sql));
					$this->results += $result[0];
					$this->type = 'all';
				}
				else {
					$this->results = mysql_fetch_array(mysql_query('SELECT count(*) FROM '. $this->type . $this->get_where_clause()));
					$this->results = $this->results[0];
				}
				$this->num_pages = ceil($this->results / $this->per_page);
			}
			
			if ($this->type == 'theme') {
				$sql = 'SELECT themeID AS ID, \'theme\' AS type, theme_name AS name, rating, category, add_timestamp, small_thumbnail_filename AS thumbnail_filename, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS downloads_per_day FROM theme';
				$this->select_result = mysql_query($sql . $this->get_where_clause() . $this->get_order_by() . $this->get_limit());
			} elseif ($this->type == 'background') {
				$sql = 'SELECT backgroundID AS ID, \'background\' AS type, background_name AS name, rating, category, add_timestamp, thumbnail_filename, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS downloads_per_day FROM background';
				$this->select_result = mysql_query($sql . $this->get_where_clause() . $this->get_order_by() . $this->get_limit());
			} else {
				$this->type = 'background';
				$sql = 'SELECT backgroundID AS ID, \'background\' AS type, background_name AS name, rating, category, add_timestamp, thumbnail_filename, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS downloads_per_day FROM background';
				$sql .= $this->get_where_clause();

				$this->type = 'theme';
				$sql .= ' UNION SELECT themeID AS ID, \'theme\' AS type, theme_name AS name, rating, category, add_timestamp, small_thumbnail_filename AS thumbnail_filename, (download_count / ((UNIX_TIMESTAMP() - download_start_timestamp)/(60*60*24))) AS downloads_per_day FROM theme';
				$sql .= $this->get_where_clause() . $this->get_order_by() . $this->get_limit();
				$this->select_result = mysql_query($sql);
			}
			
		} else {
			$this->select_result = mysql_query('SELECT userID, realname FROM user WHERE realname LIKE \'%'.mysql_real_escape_string($this->search_text)."%' ORDER BY realname $order");
		}
	}
}


class user_theme_list extends general_listing
{
	function print_listing($realname)
	{
		if (mysql_num_rows ($this->select_result) > 0)
		{
			create_title ("Themes", "Themes created by $realname");
			parent::print_listing();
		}
	}
	
	function select($userID)
	{
		$this->select_result = mysql_query('SELECT themeID AS ID, \'theme\' AS type, theme_name AS name, rating, category, add_timestamp, small_thumbnail_filename AS thumbnail_filename FROM theme '.
		                                   "WHERE userID=$userID AND status='active' ".
		                                   'ORDER BY add_timestamp DESC');
	}
}

class user_background_list extends general_listing
{
	function print_listing($realname)
	{
		if (mysql_num_rows ($this->select_result) > 0)
		{
			create_title ("Backrounds", "Backgrounds created by $realname");
			parent::print_listing();
		}
	}
	
	function select($userID)
	{
		$this->select_result = mysql_query('SELECT backgroundID AS ID, \'background\' AS type, background_name AS name, rating, category, add_timestamp, thumbnail_filename FROM background '.
		                                   "WHERE userID=$userID AND status='active' ".
		                                   'ORDER BY add_timestamp DESC');
	}
}

class user_contest_list extends general_listing
{
	function print_listing($realname)
	{
		if (mysql_num_rows ($this->select_result) > 0)
		{
			create_title ("Contests", "Contest entries submitted by $realname");
			parent::print_listing();
		}
	}
	
	function select($userID)
	{
		$this->select_result = mysql_query('SELECT contestID AS ID, \'contest\' AS type, name, rating, contest AS category, add_timestamp, small_thumbnail_filename AS thumbnail_filename FROM contest '.
		                                   "WHERE userID=$userID AND status='active' ".
		                                   'ORDER BY add_timestamp DESC');
	}
}

class variations_list extends general_listing
{
	function print_listing()
	{
		if (mysql_num_rows ($this->select_result) > 0)
		{
			create_title("Variations", "This {$this->type} has one or more variations");
			parent::print_listing();
		}
	}
	
	function select($parentID)
	{
		if ($this->type == 'theme') {
			$sql = 'SELECT themeID AS ID, \'theme\' AS type, theme_name AS name, rating, category, add_timestamp, small_thumbnail_filename AS thumbnail_filename FROM theme';
		} elseif ($this->type == 'contest') {
			$sql = 'SELECT contestID AS ID, \'contest\' AS type, name, rating, contest AS category, add_timestamp, small_thumbnail_filename AS thumbnail_filename FROM contest';
		} else {
			$sql = 'SELECT backgroundID AS ID, \'background\' AS type, background_name AS name, rating, category, add_timestamp, thumbnail_filename FROM background';
		}
	
		$this->select_result = mysql_query($sql." WHERE parent=$parentID AND status='active'".
		                                        ' ORDER BY add_timestamp DESC');
	}
}
?>
