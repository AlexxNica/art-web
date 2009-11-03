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

require ('config.inc.php');
require ("common.inc.php");

/* load model */
require ("models/backgrounds.php");

/* initialise some variables */
$search_text = '';
$total_backgrounds = 0;

$bg = new BackgroundsModel();

preg_match ('/^\/backgrounds\/(abstract|gnome|nature|other|search)\/?([0-9]+)?$/', $_SERVER['PHP_SELF'], $params);

if (array_key_exists (1, $params))
  $category = $params[1];
else
  $category = '';

if (array_key_exists (2, $params))
  $background_id = $params[2];
else
  $background_id = 0;

$page = GET ('page');
if (!is_numeric ($page))
  $page = 1;

$limit = GET ('limit');
if (!is_numeric ($limit))
  $limit = 12;

$start = ($page - 1) * $limit;

if ($category)
  if ($category == "search")
  {
    $search = mysql_escape_string (GET ('text'));
    $search = "background.name LIKE '%".$search."%'";
    $search_text = htmlspecialchars (GET ('text'));

    $view_data = $bg->search_items ($search, $start, $limit, "name");
    $total_backgrounds = $bg->search_total ($search);
  }
  else
  {
    if ($background_id)
    {
      $view_data = $bg->get_single_item ($category, $background_id);
      $total_backgrounds = 1;
    }
    else
    {
      $view_data = $bg->get_items ($category, $start, $limit, "name");
      $total_backgrounds = $bg->get_total ($category);
    }
  }
else
  $view_data = null;

$bg_res = array ();
if ($view_data)
{
  foreach ($view_data as $b)
  {
    $bg_res[$b['backgroundID']] = $bg->get_resolutions ($b['backgroundID']);
  }
}

/* load view */
require ("views/backgrounds.php");

?>
