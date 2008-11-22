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

class ThemesModel extends ArtModel
{
  var $get_items_sql = "SELECT * FROM theme,user
            WHERE status='active' AND category = '%s'
            AND theme.userID = user.userID
            AND theme.themeID > 1000
            ORDER BY %s LIMIT %s,%s";

  var $get_total_sql = "SELECT COUNT(name) FROM theme
            WHERE category = '%s' AND status='active'
            AND theme.themeID > 1000";

  var $search_items_sql = "SELECT * FROM theme,user
            WHERE status='active' AND (%s)
            AND theme.userID = user.userID
            AND theme.themeID > 1000
            ORDER BY %s LIMIT %s,%s";

  var $search_total_sql = "SELECT COUNT(name) FROM theme
            WHERE (%s) AND status='active'
            ORDER BY %s LIMIT %s,%s";
}

?>
