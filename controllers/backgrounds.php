<?php
/* load model */
require ("models/backgrounds.php");

$bg = new Backgrounds();

preg_match ('/^\/backgrounds\/(abstract|gnome|nature|other)\/?$/', $_SERVER['PHP_SELF'], $params);
$category = $params[1];

$page = $_GET['page'];
if (!is_numeric ($page))
  $page = 1;

$limit = $_GET['limit'];
if (!is_numeric ($limit))
  $limit = 10;

$start = ($page - 1) * $limit;

if ($category)
  $view_data = $bg->get_backgrounds ($category, $start, $limit, "name");
else
  $view_data = null;

$total_backgrounds = $bg->get_total ($category);

/* load view */
require ("views/backgrounds.php");

?>
