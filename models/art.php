<?php
require ("mysql.inc.php");

abstract class ArtModel
{
  var $get_items_sql = '';
  var $get_total_sql = '';

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

}

?>
