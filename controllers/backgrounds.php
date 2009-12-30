<?php
$t_start = microtime (true);
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

function GET_COOKIE ($name, $default)
{
  $set = GET ($name);
  if ($set)
  {
    setcookie ($name, $set, 0, '/');
    $value = $set;
  }
  else
    $value = (array_key_exists ($name, $_COOKIE)) ? $_COOKIE[$name] : $default;

  return $value;
}

$sort = GET_COOKIE ('sort', 'name');

if ($sort)
{
  if ($sort == 'rating')
    $sortby = 'background.rating DESC';
  else if ($sort == 'name')
    $sortby = 'background.name';
  else if ($sort == 'popularity')
    $sortby = 'background.download_count DESC';
  else
    $sortby = 'name';
}
else
  $sortby = 'name';

$filter = GET_COOKIE ('filter', null);
if ($filter == 'none')
  $filter = null;

$start = ($page - 1) * $limit;

if ($category)
  if ($category == "search")
  {
    $search = mysql_escape_string (GET ('text'));
    $search = "background.name LIKE '%".$search."%'";
    $search_text = htmlspecialchars (GET ('text'));

    $view_data = $bg->search_items ($search, $start, $limit, $sortby);
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
      if ($filter)
      {
        $filter_sql = 'resolution="' . mysql_real_escape_string ($filter) . '"';
        $view_data = $bg->get_filtered_items ($category, $start, $limit,
                                              $sortby, $filter_sql);
        $total_backgrounds = $bg->get_filtered_total ($category, $filter_sql);
      }
      else
      {
        $view_data = $bg->get_items ($category, $start, $limit, $sortby);
        $total_backgrounds = $bg->get_total ($category);
      }
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

if ($category)
  $resolution_filter = $bg->get_resolution_list ($category);
else
  $resolution_filter = array ();

/* load view */
require ("views/backgrounds.php");

$t_load = microtime (true) - $t_start;
print ("<!-- $t_load -->")

?>
