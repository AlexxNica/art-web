<?php

/* $Id$ */

require("mysql.inc.php");
require("common.inc.php");
require("art_headers.inc.php");
require("news.inc.php");
require("art_listings.inc.php");

$news = new artweb_news;
$news->heading = 'h1';
$news->select_news(1);

$latest = new latest_updates_list;
$latest->per_page = 8;
$latest->select();

$top5 = new top_rated_list;
$top5->per_page = 8;
$top5->select();


// OUTPUT ///////////////////////////////////////////////////////////////////////

art_header("Artwork &amp; Themes");
?>
<!-- News Section -->

<?php $news->print_news(); ?>
<div class="subtitle" style="margin-left:3px"><p><a href="news.php">View Older News</a></p></div>

<!-- Recent Updates -->
<div style="width:48%; float:left; clear: left;">
	<h1>Recent Updates</h1>
	<div class="subtitle">The latest updates to art.gnome.org</div>
	<?php $latest->print_listing(); ?>
		<p class="subtitle"><a href="/updates.php">More updates</a> - <a href="backend.php">RSS Updates Feed</a></p>
</div>

<!-- Top 5 Rated List -->
<div style="width:48%; float:right; clear: right;">
	<h1>Top Rated</h1>
	<div class="subtitle">The top rated items on art.gnome.org</div>
	<?php $top5->print_listing(); ?>
</div>
<?php art_footer(); ?>
