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
