<?php

require ("config.inc.php");

/* load model */
require ("models/themes.php");

$themes = new ThemesModel();

preg_match ('/^\/themes\/(gtk2|metacity|icon|gdm_greeter|splash_screens|gtk_engines|search)\/?$/',
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
  if ($category == 'search')
  {
    $search = mysql_escape_string ($_GET['text']);
    $search = "theme.name LIKE '%".$search."%'";
    $search_text = htmlspecialchars ($_GET['text']);

    $view_data = $themes->search_items ($search, $start, $limit, "name");
    $total_themes = $themes->search_total ($search);
  }
  else
  {
    $view_data = $themes->get_items ($category, $start, $limit, "name");
    $total_themes = $themes->get_total ($category);
  }
else
  $view_data = null;


/* load view */
require ("views/themes.php");

?>
