<?php

/*
 * Copyright (C) 2008 Thomas Wood <thos@gnome.org>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
  $limit = 12;

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
