<?php

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
    
    $num_pages = $this->total / $this->limit;

    if ($context > $num_pages)
      $context = $num_pages;

    $cur_page = ceil ($this->start / $this->limit);

    $prev_page = $cur_page - 1;
    if ($cur_page > 1)
    {
      print ("<a href=\"?page=1\">First</a> &middot; ");
      print ("<a href=\"?page=$prev_page\">Previous</a>");
    }
    else
    {
      print ("First &middot; ");
      print ('Previous');
    }

    $first = max (1, $cur_page - $context / 2);
    $last = min ($first + $context, $this->total / $this->limit);

    if ($last - $first < $context)
      $first = max (1, $last - $context);

    for ($i = $first; $i <= $last; $i++)
    {
      if ($i == $cur_page)
        print (" &middot; $i ");
      else
        print (" &middot; <a href=\"?page=$i\">$i</a>");
    }


    $next_page = $cur_page + 1;
    if ($cur_page < $num_pages)
    {
      print (" &middot; <a href=\"?page=${next_page}\">Next</a>");
      print (" &middot; <a href=\"?page=${num_pages}\">Last</a>");
    }
    else
    {
      print (' &middot; Next');
      print (" &middot; Last");
    }


  }
}

?>
