<?php

include "mysql.inc.php";
include "common.inc.php";
require("art_headers.inc.php");
include "news.inc.php";

$news = new artweb_news;

$news->select_news(20);
// HTML ///////////////////////////////////////////////////////////////////////
art_header("Artwork &amp; Themes"); ?>
<h1>News</h1>
<?php $news->print_news(); ?>

<?php art_footer(); ?>
