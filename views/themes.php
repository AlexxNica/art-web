<?php

require ("lib/pagination.php");
require ("lib/template.php");

$t = new Template ("themes");

$t->print_header();

if (!$view_data)
{
  print ('

  <a href="/">GNOME Art</a> &gt; Themes
  <br><br>
  Choose a category:
  <ul>
  <li><a href="/themes/gtk2">Controls</a></li>
  <li><a href="/themes/metacity">Window Borders</a></li>
  <li><a href="/themes/icon">Icons</a></li>
  <li><a href="/themes/splash_screens">Splash Screens</a></li>
  <li><a href="/themes/gdm_greeter">Login Window</a></li>
  </ul>');
  $t->print_footer ();
  exit;
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
<br/><br/>
<center><?php $p->print_pagination (); ?></center>
<br/>
<table>

<?php
  foreach ($view_data as $row)
  {
    print ("<tr><td colspan='3'><b>{$row['name']}</b></td></tr>");
    print ("<tr><td colspan='3'><a href=\"mailto:{$row['email']}\">{$row['realname']}</a></td></tr>");
    print ("<tr><td rowspan='2'><img width=96' src='/images/thumbnails/themes/{$row['themeID']}.png'/></td>");
    print ("<td colspan='2'>{$row['description']}</td></tr>");
    print ("<tr><td>{$row['release_date']}</td><td width='75%'>{$row['license']}</td></tr>");
    print ("<tr><td colspan='3'>&nbsp;</td></tr>");
  }
?>

</table>
<br/>
<center><?php $p->print_pagination (); ?></center>

<?php $t->print_footer() ?>
