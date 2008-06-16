<?php
require ("mysql.inc.php");

class Themes
{
  function get_themes ($category, $start, $length, $order)
  {
    $sql = "SELECT * FROM theme,user
            WHERE status='active' AND category = '$category'
            AND theme.userID = user.userID
            ORDER BY $order LIMIT $start,$length ";

    $r = mysql_query ($sql);
    if (!$r)
      printf ("Database error: %s", mysql_error());
    $table = Array ();
    while ($row = mysql_fetch_assoc ($r))
    {
      $table[] = $row;
    }

    return $table;
  }

  function get_total ($category)
  {
    $sql = "SELECT COUNT(name) FROM theme
            WHERE category = '$category'";
    $r = mysql_query ($sql);
    if (!$r)
      printf ("Database error: %s", mysql_error());


    $total = mysql_fetch_row ($r);

    return $total[0];
  }
}

?>
