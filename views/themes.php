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

$t = new Template ("themes");
$t->add_css ("/css/art.css");

$t->print_header();

/* if no data is available, print out a category list */
if (!$view_data)
{
  ?>
  <h2>Categories</h2>
  Choose a category:
  <ul>
  <li><a href="/themes/gtk2">Controls</a></li>
  <li><a href="/themes/metacity">Window Borders</a></li>
  <li><a href="/themes/icon">Icons</a></li>
  <li><a href="/themes/splash_screens">Splash Screens</a></li>
  <li><a href="/themes/gdm_greeter">Login Window</a></li>
  </ul>
  Search for themes:
  <?php if ($total_themes < 1 && $search_text) echo "<p>No search results for &quot;$search_text&quot;</p>" ?>
  <ul>
  <form method="get" action="/themes/search">
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

$p = new Paginator ($total_themes, 12, $cur_page * 12);

$display_cat = array (
  "gtk2" => "Controls",
  "icon" => "Icons",
  "metacity" => "Window Borders",
  "gdm_greeter" => "Login Screen",
  "splash_screens" => "Splash Screens"
);

  if ($category == 'search')
    $d_category = 'Search Results';
  else
    $d_category = $display_cat [$category];

function selected ($a, $b)
{
  if ($a == $b)
    echo ' selected="selected"';
}

?>


<script type='text/javascript'>
String.prototype.rot13 = rot13 = function(s)
{
  return (s ? s : this).replace(/[a-zA-Z]/g,function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);});
}
</script>

<h2><a href="/themes">Themes</a> / <?php echo $d_category; ?></h2>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>

<form method="get" style="text-align:center; font-size: small;">
  <label>Sort By:
  <select name="sort" onchange="this.form.submit()" style="font-size: small">
    <option value="name"<?php selected ($sort, 'name')?>>Name</option>
    <option value="popularity"<?php selected ($sort, 'popularity')?>>Popularity</option>
  </select>
  <noscript>
    <input type="submit" value="Go" style="font-size: small;">
  </noscript>
  </label>
</form>
<br>
<?php foreach ($view_data as $row): ?>

<div class="list-item">
  <b><?php echo $row['name']?></b>
  <br><span class="item-detail"> by <script type='text/javascript'>document.write ('<a href="mailto:' + '<?php echo str_rot13 ($row['email'])?>'.rot13() + '">');</script><?php echo $row['realname']?><script type='text/javascript'>document.write ('<\/a>');</script></span>
   <br><span class="item-detail"><?php echo $license_config_link_array[$row['license']]?></span>
  <br>
  <img style="margin:0.5em;" width="96" alt="Preview" src='/images/thumbnails/<?php echo $row['category']?>/<?php echo $row['thumbnail_filename']?>'>
  <br>
  <span class="item-detail">
  <?php echo number_format ($row['download_count']) ?> downloads
  </span><br>
   <a href="/download/themes/<?php printf ("%s/%s/%s", $row['category'], $row['themeID'], $row['download_filename'])?>">Download</a>
</div>
<?php endforeach ?>
<br clear="both">
<br>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>

<?php $t->print_footer() ?>
