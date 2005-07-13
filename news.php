<?php

include "mysql.inc.php";
include "common.inc.php";
require("ago_headers.inc.php");
include "news.inc.php";

$news = new artweb_news;

$news->select_news(20);
?>
// HTML ///////////////////////////////////////////////////////////////////////

<?php ago_header("Artwork &amp; Themes"); ?>
<?php create_title("News"); ?>

<?php $news->print_news(); ?>

<?php ago_footer(); ?>
