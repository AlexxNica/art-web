<?php

/* $Id$ */

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");
require("news.inc.php");

$news = new artweb_news;
$news->select_news(1);

$latest_select = mysql_query("SELECT backgroundID, 0 AS themeID, add_timestamp FROM background UNION SELECT 0 AS backgroundID, themeID, add_timestamp FROM theme ORDER BY add_timestamp DESC LIMIT 5");
$top5_select = mysql_query("SELECT backgroundID, 0 AS themeID, rating FROM background UNION SELECT 0 AS backgroundID, themeID, rating FROM theme ORDER BY rating DESC LIMIT 5");


// OUTPUT ///////////////////////////////////////////////////////////////////////

ago_header("Artwork &amp; Themes");
?>
<!-- News Section -->

<h1>Latest News</h1>
<div class="subtitle">Latest news from art.gnome.org</div>
<?php $news->print_news(); ?>
<div style="text-align: center"><p><a href="news.php">View Older News</a></p></div>

<!-- Recent Updates -->
<div style="width:48%; float:left; clear: left;">
	<h1>Recent Updates</h1>
	<div class="subtitle">The latest five additions to art.gnome.org</div>
	<?php print_art_query($latest_select); ?>
	<div style="text-align:center">
		<p><a href="/updates.php">More updates</a> - <a href="backend.php">RSS Updates Feed</a></p>
	</div>
</div>

<!-- Top 5 Rated List -->
<div style="width:48%; float:right; clear: right;">
	<h1>Top Rated</h1>
	<div class="subtitle">The five top rated items</div>
	<?php print_art_query($top5_select); ?>
</div>
<?php ago_footer(); ?>
