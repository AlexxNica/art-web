<?php

/*
 * Copyright (C) 2008, 2009 Thomas Wood <thos@gnome.org>
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
if (!$view_data && !$category)
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

function selected ($a, $b)
{
  if ($a == $b)
    echo ' selected="selected"';
}

?>
<h2><a href="/backgrounds">Backgrounds</a>
<?php if ($d_category) echo '/ '.$d_category ?></h2>

<div style="text-align:center"><?php $p->print_pagination (); ?></div>
<form method="get" style="text-align:center; font-size: small;">

  <label>Category:
  <select name="category"
    onchange="this.form.action='/backgrounds/'+this.form.category.value;
    this.form.submit()"
    style="font-size: small">
  <?php foreach ($category_filter as $value => $name): ?>
    <option value=<?php echo "'$value'"; selected ($category, $value) ?>>
    <?php echo $name ?></option>
  <?php endforeach ?>
  </select>
  </label>

  <label>Sort By:
  <select name="sort" onchange="this.form.submit()" style="font-size: small">
    <option value="name"<?php selected ($sort, 'name')?>>Name</option>
    <option value="popularity"<?php selected ($sort, 'popularity')?>>Popularity</option>
  </select>
  </label>

  <label>Size:
  <select name="resolution" onchange="this.form.submit()" style="font-size: small">
  <?php foreach ($resolution_filter as $value => $name): ?>
    <option value=<?php echo "'$value'"; selected ($resolution, $value) ?>>
    <?php echo $name ?></option>
  <?php endforeach ?>
  </select>
  <script type="text/javascript">
  /* highlight the user's screen resolution */
  var options = document.forms[0].resolution.options;
  var screenres = screen.width + 'x' + screen.height;
  for (var i = 0; i < options.length; i++)
  {
    if (options[i].text == screenres)
    {
      options[i].text = options[i].text + ' *'
      break;
    }
  }
  </script>
  </label>

  <?php if ($search_text): ?>
    <input type="hidden" name="text" value="<?php echo $search_text ?>">
  <?php endif ?>

  <noscript>
    <input type="submit" value="Go" style="font-size: small;">
  </noscript>
</form>

<script type='text/javascript'>
// add a rot13 function to strings.
// found at: http://stackoverflow.com/questions/617647/where-is-my-one-line-implementation-of-rot13-in-javascript-going-wrong
String.prototype.rot13 = rot13 = function(s)
{
  return (s ? s : this).replace(/[a-zA-Z]/g,function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);});
}
</script>

<?php if (!$view_data): ?>
<div align="center">
<i>No results, try adjusting the size filter.</i>
</div>

<?php else: ?>

<?php foreach ($view_data as $row): ?>
<div class="list-item">
    <b><?php echo $row['name']?></b>
    <br>
    <span class="item-detail">by <script type='text/javascript'>document.write ('<a href="mailto:' + '<?php echo str_rot13 ($row['email'])?>'.rot13() + '">');</script><?php echo $row['realname']?><script type='text/javascript'>document.write ('<\/a>');</script></span>
    <br><span class="item-detail"><?php echo $license_config_link_array[$row['license']]?></span>
    <br>
    <img width="96" alt="Preview" src='/images/thumbnails/backgrounds/<?php echo $row['thumbnail_filename']?>'>
    <br>
    <span class="item-detail">
      <?php echo number_format ($row['download_count']) ?> downloads
    </span>
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

<?php endif /* (!$view_data) */ ?>

<br clear="both">
<br>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>

<?php $t->print_footer() ?>
