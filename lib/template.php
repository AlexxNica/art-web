<?php

class Template
{
  var $page;

  function Template ($page)
  {
    $this->page = $page;
  }

  function print_header ()
  {
    $page = $this->page;
    include ("templates/header.php");
  }

  function print_footer ()
  {
    include ("templates/footer.php");
  }
}

?>
