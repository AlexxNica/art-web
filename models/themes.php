<?php
require ('models/art.php');

class ThemesModel extends ArtModel
{
  var $get_items_sql = "SELECT * FROM theme,user
            WHERE status='active' AND category = '%s'
            AND theme.userID = user.userID
            ORDER BY %s LIMIT %s,%s";

  var $get_total_sql = "SELECT COUNT(name) FROM theme
            WHERE category = '%s' AND status='active'";
}

?>
