<?php
require("mysql.inc.php");
require("common.inc.php");
require("art_listings.inc.php");

$style = validate_input_array_default ($_GET['style'], Array('icons','list'), 'list');

if ($header = apache_request_headers ()) {
	$result = mysql_query ('SELECT add_timestamp FROM background UNION '.
	                       'SELECT add_timestamp FROM theme '.
	                       'ORDER BY add_timestamp DESC LIMIT 1');
	                       
	if ($result)
	{
		list($update_time) = mysql_fetch_row ($result);
		
		$time = gmdate('D, d M Y H:i:s', $update_time) . ' GMT';
		$etag = "\"$time-$style-01\""; /* time-style-some string which can be changed*/
		
		if (array_key_exists ('If-Modified-Since', $header) || array_key_exists ('If-None-Match', $header))
		{
			$modified = 0;
			if (array_key_exists ('If-Modified-Since', $header))
				$modified = $header['If-Modified-Since'] != $time;
			if (!$modified && array_key_exists ('If-None-Match', $header))
				$modified = $header['If-None-Match'] != $etag;
			
			if (!$modified)
			{
				/* nothing was modified */
				header('HTTP/1.0 304 Not Modified');
				header("ETag: $etag");
				
				exit;
			}
		}
		header("Last-Modified: $time");
		header("ETag: $etag");
		
	}
}            

$list = new latest_updates_list;
$list->per_page  = 12;
$list->view      = $style;
$list->format    = 'rss';
$list->date_type = 'absolute';
$list->select();


// using text/xml until firefox bug is fixed.
//header("Content-type: application/rss+xml");
header("Content-type: text/xml");

print('<?xml version="1.0" encoding="ISO-8859-1" ?>');
?>
<rss version="2.0">
	<channel>
	<title>art.gnome.org releases</title>
	<image><link><?php print $site_url?></link><url><?php print $site_url?>/images/site/art-icon.png</url><title>art.gnome.org</title></image>
	<link><?php print $site_url?></link>
	<description>A list of recent backgrounds and themes released on art.gnome.org</description>
	<webMaster>thos@nospam.gnome.org</webMaster>
<?php
$list->print_listing();
?>
	</channel>
</rss>