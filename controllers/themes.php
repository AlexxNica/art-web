<?php
/* load model */
require ("models/themes.php");

$themes = new Themes();

preg_match ('/^\/themes\/(gtk2|metacity|icon|gdm_greeter|splash_screens|gtk_engines)\/?$/',
            $_SERVER['PHP_SELF'], $params);
$category = $params[1];

$page = $_GET['page'];
if (!is_numeric ($page))
  $page = 1;

$limit = $_GET['limit'];
if (!is_numeric ($limit))
  $limit = 10;

$start = ($page - 1) * $limit;

if ($category)
  $view_data = $themes->get_themes ($category, $start, $limit, "name");
else
  $view_data = null;

$total_themes = $themes->get_total ($category);

/* load view */
require ("views/themes.php");

?>
