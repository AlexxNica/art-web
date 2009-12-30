<?php

/*
 * Copyright (C) 2008, 2009 Thomas Wood <thos@gnome.org>
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
require ("common.inc.php");

/* load model */
require ("models/themes.php");

/* initialise some vairables */
$search_text = '';
$total_themes = 0;

$themes = new ThemesModel();

preg_match ('/^\/themes\/(gtk2|metacity|icon|gdm_greeter|splash_screens|gtk_engines|search)\/?([0-9]+)?$/',
            $_SERVER['PHP_SELF'], $params);

if (array_key_exists (1, $params))
  $category = $params[1];
else
  $category = '';

if (array_key_exists (2, $params))
  $theme_id = $params[2];
else
  $theme_id = 0;

$page = GET ('page');
if (!is_numeric ($page))
  $page = 1;

$limit = GET ('limit');
if (!is_numeric ($limit))
  $limit = 12;

$set_sort = GET ('sort');
if ($set_sort)
{
  setcookie ('sort', $set_sort);
  $sort = $set_sort;
}
else
  $sort = (array_key_exists ('sort', $_COOKIE)) ? $_COOKIE['sort'] : 'name';

if ($sort)
{
  if ($sort == 'rating')
    $sortby = 'rating DESC';
  else if ($sort == 'name')
    $sortby = 'name';
  else if ($sort == 'popularity')
    $sortby = 'popularity DESC';
  else
    $sortby = 'name';
}
else
  $sortby = 'name';

$start = ($page - 1) * $limit;

if ($category)
  if ($category == 'search')
  {
    $search = mysql_escape_string (GET ('text'));
    $search = "theme.name LIKE '%".$search."%'";
    $search_text = htmlspecialchars (GET ('text'));

    $view_data = $themes->search_items ($search, $start, $limit, $sortby);
    $total_themes = $themes->search_total ($search);
  }
  else
  {
    if ($theme_id)
    {
      $view_data = $themes->get_single_item ($category, $theme_id);
      $total_themes = 1;
    }
    else
    {
      $view_data = $themes->get_items ($category, $start, $limit, $sortby);
      $total_themes = $themes->get_total ($category);
    }
  }
else
  $view_data = null;


/* load view */
require ("views/themes.php");

?>
