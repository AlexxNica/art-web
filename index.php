<?php

/* $Id$ */

require("mysql.inc.php");
require("common.inc.php");
require("ago_headers.inc.php");
require("news.inc.php");
require("art_listings.inc.php");

$news = new artweb_news;
$news->select_news(1);

$latest = new latest_updates_list;
$latest->select();

$top5 = new top_rated_list;
$top5->select();


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
	<?php $latest->print_listing(); ?>
	<div style="text-align:center">
		<p><a href="/updates.php">More updates</a> - <a href="backend.php">RSS Updates Feed</a></p>
	</div>
</div>

<!-- Top 5 Rated List -->
<div style="width:48%; float:right; clear: right;">
	<h1>Top Rated</h1>
	<div class="subtitle">The five top rated items</div>
	<?php $top5->print_listing(); ?>
</div>
<?php ago_footer(); ?>
