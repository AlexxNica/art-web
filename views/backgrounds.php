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
  <a href="/">GNOME Art</a> &gt; Backgrounds
  <br><br>
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
$cur_page = $_GET['page'];
if (!is_numeric ($cur_page))
  $cur_page = 1;

$p = new Paginator ($total_backgrounds, 10, $cur_page * 10);

if ($category == "gnome")
  $d_category = "GNOME";
elseif ($category == 'search')
  $d_category = 'Search Results';
else
  $d_category = ucwords ($category);

?>
<a href="/">GNOME Art</a> &gt; <a href="/backgrounds">Backgrounds</a>
&gt; <?php echo $d_category ?>
<br><br>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>
<br>

<?php foreach ($view_data as $row): ?>
<div class="list-item">
<table cellpadding="4" width="100%">
  <tr>
    <td colspan='6'><b><?php echo $row['name']?></b> by
      <a href="mailto:<?php echo $row['email']?>"><?php echo $row['realname']?></a>
    </td>
  </tr>
  <tr>
    <td rowspan="3">
      <img width="96" alt="Preview" src='/images/thumbnails/backgrounds/<?php echo $row['backgroundID']?>.jpg'>
    </td>
    <td colspan="4" style="width:100%"><?php echo $row['description']?></td>
    <td rowspan="2">
     <?php foreach ($bg_res[$row['backgroundID']] as $res):?>
       <a href="/download/backgrounds/<?php printf ("%s/%s/%s", $row['category'], $res['background_resolutionID'], $res['filename'])?>">
       <?php echo $res['resolution']?></a><br>
     <?php endforeach ?>
    </td>
  </tr>
  <tr>
    <td class="label">Date:</td>
    <td><?php $tm = strtotime ($row['release_date']); echo date ("d M Y", $tm); ?></td>
    <td class="label">License:</td>
    <td><?php echo $license_config_array[$row['license']]?></td>
  </tr>
</table>
</div>
<br>
<?php endforeach ?>


<br>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>

<?php $t->print_footer() ?>
