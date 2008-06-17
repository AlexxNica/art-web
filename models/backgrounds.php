<?php
require ('models/art.php');

class BackgroundsModel extends ArtModel
{
  var $get_items_sql = "SELECT * FROM background,user
            WHERE status='active' AND category = '%s'
            AND background.userID = user.userID
            ORDER BY %s LIMIT %s,%s";

  var $get_total_sql = "SELECT COUNT(name) FROM background
            WHERE category = '%s'";

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
}

?>
