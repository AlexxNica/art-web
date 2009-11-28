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

require ('models/art.php');

class BackgroundsModel extends ArtModel
{
  var $get_single_item_sql = "SELECT * FROM background,user
            WHERE status='active' AND category = '%s'
            AND background.userID = user.userID
            AND background.backgroundID = %s";

  var $get_items_sql = "SELECT * FROM background,user
            WHERE status='active' AND category = '%s'
            AND background.userID = user.userID
            AND background.backgroundID > 1000
            ORDER BY %s LIMIT %s,%s";

  var $get_filtered_items_sql = "SELECT * FROM background
            INNER JOIN user ON background.userID = user.userID
            RIGHT JOIN background_resolution
            ON background_resolution.backgroundID = background.backgroundID
            WHERE status='active' AND category = '%s'
            AND background.backgroundID > 1000
            AND (%s)
            ORDER BY %s LIMIT %s,%s";

  var $get_filtered_total_sql = "SELECT COUNT(name) FROM background
            INNER JOIN user ON background.userID = user.userID
            RIGHT JOIN background_resolution
            ON background_resolution.backgroundID = background.backgroundID
            WHERE status='active' AND category = '%s'
            AND background.backgroundID > 1000
            AND (%s)";

  var $get_total_sql = "SELECT COUNT(name) FROM background
            WHERE category = '%s' AND status='active'
            AND background.backgroundID > 1000";

  var $search_items_sql = "SELECT * FROM background,user
            WHERE status='active'
            AND background.userID = user.userID
            AND (%s)
            AND background.backgroundID > 1000
            ORDER BY %s LIMIT %s,%s";

  var $search_total_sql = "SELECT COUNT(*) FROM background
            WHERE status='active' AND (%s)
            AND background.backgroundID > 1000";

  function get_resolutions ($backgroundID)
  {
    if (!is_numeric ($backgroundID))
      return;

    $sql = "SELECT * FROM background_resolution
            WHERE backgroundID = $backgroundID";

    $r = mysql_query ($sql);
    $res = array ();

    while ($rr = mysql_fetch_assoc ($r))
      $res[] = $rr;

    return $res;
  }

  function get_resolution_list ($category)
  {
    $sql = "SELECT resolution FROM background_resolution
            INNER JOIN background
            ON background.backgroundID = background_resolution.backgroundID
            WHERE background.category = '$category'
            AND background.status = 'active'
            AND background.backgroundID > 1000
            GROUP BY resolution
            ORDER BY resolution";
    $r = mysql_query ($sql);
    $res = array ();
    $res['none'] = 'All';

    while ($rr = mysql_fetch_row ($r))
      $res[$rr[0]] = $rr[0];

    return $res;
  }
}

?>
