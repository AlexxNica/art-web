<?php

class Template
{
  var $page;
  var $css;

  function Template ($page)
  {
    $this->page = $page;
    $this->css = array ();
  }

  function add_css ($file)
  {
    $this->css[] = $file;
  }

  function print_header ()
  {
    $page = $this->page;
    $css = $this->css;
    include ("templates/header.php");
  }

  function print_footer ()
  {
    include ("templates/footer.php");
  }
}

?>
