<?php

require ("lib/pagination.php");
require ("lib/template.php");

$t = new Template ("themes");
$t->add_css ("/css/art.css");

$t->print_header();

/* if no data is available, print out a category list */
if (!$view_data)
{
  ?>
  <a href="/">GNOME Art</a> &gt; Themes
  <br><br>
  Choose a category:
  <ul>
  <li><a href="/themes/gtk2">Controls</a></li>
  <li><a href="/themes/metacity">Window Borders</a></li>
  <li><a href="/themes/icon">Icons</a></li>
  <li><a href="/themes/splash_screens">Splash Screens</a></li>
  <li><a href="/themes/gdm_greeter">Login Window</a></li>
  </ul>
  <?php
  $t->print_footer ();
  exit (0);
}

/* get the current page and ensure a default value is set */
$cur_page = $_GET['page'];
if (!is_numeric ($cur_page))
  $cur_page = 1;

$p = new Paginator ($total_themes, 10, $cur_page * 10);

$display_cat = array (
  "gtk2" => "Controls",
  "icon" => "Icons",
  "metacity" => "Window Borders",
  "gdm_greeter" => "Login Screen",
  "splash_screens" => "Splash Screens"
);

  $d_category = $display_cat [$category];

?>
<a href="/">GNOME Art</a> &gt; <a href="/themes">Themes</a>
&gt; <?php echo $d_category ?>
<br><br>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>
<br>

<?php foreach ($view_data as $row): ?>

<div class="list-item">
<table cellpadding="4" width="100%">
  <tr>
    <td colspan='4'><b><?php echo $row['name']?></b> by
      <a href="mailto:<?php echo $row['email']?>"><?php echo $row['realname']?></a>
    </td>
  </tr>
  <tr>
    <td rowspan="3">
      <img width="96" alt="Preview" src='/images/thumbnails/themes/<?php echo $row['themeID']?>.png'>
    </td>
    <td colspan="3" style="width:100%"><?php echo $row['description']?></td>
  </tr>
  <tr>
    <td><?php $tm = strtotime ($row['release_date']); echo date ("d M Y", $tm); ?></td>
    <td><span style="color: gray">License:</span> <?php echo $license_config_array[$row['license']]?></td>
    <td><a href="/download/themes/<?php printf ("%s/%s/%s", $row['category'], $row['themeID'], $row['download_filename'])?>">Download</a></td>
  </tr>
</table>
</div>
<br>
<?php endforeach ?>

<br>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>

<?php $t->print_footer() ?>
