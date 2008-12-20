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


class Paginator
{
  var $limit;
  var $start;
  var $total;

  function Paginator ($total, $limit, $start)
  {
    $this->total = $total;
    $this->limit = $limit;
    $this->start = $start;
  }

  function print_pagination ()
  {
    $context = 8;
    
    $num_pages = ceil ($this->total / $this->limit);

    /* only one page, no point in printing navigation! */
    if ($num_pages <= 1)
      return;

    if ($context > $num_pages)
      $context = $num_pages;

    $cur_page = ceil ($this->start / $this->limit);

    $prev_page = $cur_page - 1;
    $get_params = '';
    foreach ($_GET as $key => $value) {if ($key != 'page') $get_params .= "&$key=$value";}
    if ($cur_page > 1)
    {
      print ("<a href=\"?page=1$get_params\">First</a> &middot; ");
      print ("<a href=\"?page=$prev_page$get_params\">Previous</a>");
    }
    else
    {
      print ("First &middot; ");
      print ('Previous');
    }

    $first = max (1, $cur_page - $context / 2);
    $last = min ($first + $context, ceil ($this->total / $this->limit));

    if ($last - $first < $context)
      $first = max (1, $last - $context);

    for ($i = $first; $i <= $last; $i++)
    {
      if ($i == $cur_page)
        print (" &middot; $i ");
      else
        print (" &middot; <a href=\"?page=$i$get_params\">$i</a>");
    }


    $next_page = $cur_page + 1;
    if ($cur_page < $num_pages)
    {
      print (" &middot; <a href=\"?page=${next_page}$get_params\">Next</a>");
      print (" &middot; <a href=\"?page=${num_pages}$get_params\">Last</a>");
    }
    else
    {
      print (' &middot; Next');
      print (" &middot; Last");
    }


  }
}

?>
