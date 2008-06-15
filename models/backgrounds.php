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
}

?>
