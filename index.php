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

$news_result = mysql_query ("SELECT title, date FROM news ORDER BY date DESC LIMIT 1");
list ($latest_news_title, $latest_news_date) = mysql_fetch_row ($news_result);

// OUTPUT ///////////////////////////////////////////////////////////////////////

art_header("Artwork &amp; Themes");
?>

<h1>Art.gnome.org</h1>
<div class="subtitle">Artwork and Themes for the GNOME desktop</div>
<p>
Welcome to art.gnome.org - a place for high quality artwork and themes for the <a href="http://www.gnome.org/">GNOME desktop</a>. All themes and artwork on art.gnome.org are tested and moderated (see the <a href="http://live.gnome.org/GnomeArt/SubmissionPolicy">Submission Policy</a>) to ensure a high standard of quality and to make certain they work with your GNOME desktop.</p>

<div>
<img src="/images/site/stock_dnd_32.png" style="float:left;margin-right: 0.3em;" alt="News"/>
<a href="news.php">Latest News</a><br/>
<?php echo $latest_news_title ?>
</div><br clear="left"/>

<!-- News Section -->
<!--<div class="subtitle">Posted by Foo Bar - 2006-09-09</div>
<div class="subtitle"><a href="news.php">Read More...</a></div>-->

<!-- Recent Updates -->
<div style="width:48%; float:left;">
	<h1>Latest Updates</h1>
	<div class="subtitle">The most recent additions to art.gnome.org</div>
	<?php $latest->print_listing(); ?>
		<p class="subtitle"><a href="/updates.php">More updates</a> - <a href="backend.php">RSS Updates Feed</a></p>
</div>

<!-- Top 5 Rated List -->
<div style="width:48%; float:right;">
	<h1>Top Rated</h1>
	<div class="subtitle">The top rated items on art.gnome.org</div>
	<?php $top5->print_listing(); ?>
</div>
<?php art_footer(); ?>
