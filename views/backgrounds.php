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

require ("lib/pagination.php");
require ("lib/template.php");

$t = new Template ("backgrounds");
$t->add_css ("/css/art.css");

$t->print_header();

/* if no data is available, print out a category list */
if (!$view_data)
{
  ?>
  <h2>Categories</h2>
  Choose a category:
  <ul>
  <li><a href="/backgrounds/gnome">GNOME</a></li>
  <li><a href="/backgrounds/nature">Nature</a></li>
  <li><a href="/backgrounds/abstract">Abstract</a></li>
  <li><a href="/backgrounds/other">Other</a></li>
  </ul>
  Search for backgrounds:
  <?php if ($total_backgrounds < 1 && $search_text) echo "<p>No search results for &quot;$search_text&quot;</p>" ?>
  <ul>
  <form method="get" action="/backgrounds/search">
    <input name="text" type="text" value="<?php echo $search_text?>">
    <input type="submit" value="Search">
  </form>
  </ul>
  <?php
  $t->print_footer ();
  exit (0);
}

/* get the current page and ensure a default value is set */
$cur_page = GET ('page');
if (!is_numeric ($cur_page))
  $cur_page = 1;

$p = new Paginator ($total_backgrounds, 12, $cur_page * 12);

if ($category == "gnome")
  $d_category = "GNOME";
elseif ($category == 'search')
  $d_category = 'Search Results';
else
  $d_category = ucwords ($category);

?>
<h2><a href="/backgrounds">Backgrounds</a> / <?php echo $d_category?></a></h2>

<div style="text-align:center"><?php $p->print_pagination (); ?></div>
<br>

<?php foreach ($view_data as $row): ?>
<div class="list-item">
    <b><?php echo $row['name']?></b>
    <br>
    <span class="item-detail">by <a href="mailto:<?php echo $row['email']?>"><?php echo $row['realname']?></a></span>
    <br><span class="item-detail"><?php echo $license_config_link_array[$row['license']]?></span>
    <br>
    <img width="96" alt="Preview" src='/images/thumbnails/backgrounds/<?php echo $row['thumbnail_filename']?>'>
    <br>
    <form method="get" action="/download">
    <select name="d">
     <?php foreach ($bg_res[$row['backgroundID']] as $res):?>
       <option value="/backgrounds/<?php printf ("%s/%s/%s", $row['category'], $res['background_resolutionID'], $res['filename'])?>">
     <?php echo $res['resolution']?>
       </option>
     <?php endforeach ?>
    </select>
    <input type="submit" value="Go">
    </form>
</div>
<?php endforeach ?>


<br clear="both">
<br>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>

<?php $t->print_footer() ?>
