<?php
require ("mysql.inc.php");

class Backgrounds
{
  function get_backgrounds ($category, $start, $length, $order)
  {
    $sql = "SELECT * FROM background,user
            WHERE status='active' AND category = '$category'
            AND background.userID = user.userID
            ORDER BY $order LIMIT $start,$length ";

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
    $sql = "SELECT COUNT(name) FROM background
            WHERE category = '$category'";
    $r = mysql_query ($sql);
    if (!$r)
      printf ("Database error: %s", mysql_error());


    $total = mysql_fetch_row ($r);

    return $total[0];
  }

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
