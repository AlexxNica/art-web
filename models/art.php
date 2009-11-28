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

require ("mysql.inc.php");

abstract class ArtModel
{
  var $get_items_sql = '';
  var $get_total_sql = '';

  function search_items ($search, $start, $length, $order)
  {
    /* check that start and length values are numeric */
    if (!is_numeric ($start) || !is_numeric ($length))
      return;

    $sql = sprintf ($this->search_items_sql, $search, $order, $start, $length);

    if ($sql === '')
      return;

    $bg_select_result = mysql_query ($sql);
    if (!$bg_select_result)
      printf ("Database error: %s", mysql_error());

    $table = Array ();
    while ($row = mysql_fetch_assoc ($bg_select_result))
    {
      $table[] = $row;
    }

    return $table;
  }

  function search_total ($search)
  {
    $sql = sprintf ($this->search_total_sql, $search);
    $r = mysql_query ($sql);
    if (!$r)
      printf ("Database error: %s", mysql_error());


    $total = mysql_fetch_row ($r);

    return $total[0];
  }

  function get_single_item ($category, $item_id)
  {
    /* check that item_id is numeric */
    if (!is_numeric ($item_id))
      return;

    $sql = sprintf ($this->get_single_item_sql, $category, $item_id);

    if ($sql === '')
      return;

    $bg_select_result = mysql_query ($sql);
    if (!$bg_select_result)
      printf ("Database error: %s", mysql_error());

    $table = Array ();
    while ($row = mysql_fetch_assoc ($bg_select_result))
    {
      $table[] = $row;
    }

    return $table;
  }

  function get_items ($category, $start, $length, $order)
  {
    /* check that start and length values are numeric */
    if (!is_numeric ($start) || !is_numeric ($length))
      return;

    $sql = sprintf ($this->get_items_sql, $category, $order, $start, $length);

    if ($sql === '')
      return;

    $bg_select_result = mysql_query ($sql);
    if (!$bg_select_result)
      printf ("Database error: %s", mysql_error());

    $table = Array ();
    while ($row = mysql_fetch_assoc ($bg_select_result))
    {
      $table[] = $row;
    }

    return $table;
  }

  function get_total ($category)
  {
    $sql = sprintf ($this->get_total_sql, $category);
    $r = mysql_query ($sql);
    if (!$r)
      printf ("Database error: %s", mysql_error());


    $total = mysql_fetch_row ($r);

    return $total[0];
  }

  function get_filtered_items ($category, $start, $length, $order, $filter)
  {
    /* check that start and length values are numeric */
    if (!is_numeric ($start) || !is_numeric ($length))
      return;

    $sql = sprintf ($this->get_filtered_items_sql, $category, $filter, $order,
                    $start, $length);

    if ($sql === '')
      return;

    $bg_select_result = mysql_query ($sql);
    if (!$bg_select_result)
      printf ("Database error: %s", mysql_error());

    $table = Array ();
    while ($row = mysql_fetch_assoc ($bg_select_result))
    {
      $table[] = $row;
    }

    return $table;
  }

  function get_filtered_total ($category, $filter)
  {
    $sql = sprintf ($this->get_filtered_total_sql, $category, $filter);
    $r = mysql_query ($sql);
    if (!$r)
      printf ("Database error: %s", mysql_error());

    $total = mysql_fetch_row ($r);

    return $total[0];
  }

}

?>
