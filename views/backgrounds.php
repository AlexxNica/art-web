<?php

require ("lib/pagination.php");
require ("lib/template.php");

$t = new Template ("backgrounds");

$t->print_header();

if (!$view_data)
{
  print ('

  <a href="/">GNOME Art</a> &gt; Backgrounds
  <br><br>
  Choose a category:
  <ul>
  <li><a href="/backgrounds/gnome">GNOME</a></li>
  <li><a href="/backgrounds/nature">Nature</a></li>
  <li><a href="/backgrounds/abstract">Abstract</a></li>
  <li><a href="/backgrounds/other">Other</a></li>
  </ul>');
  $t->print_footer ();
  exit;
}

$p = new Paginator (400, 10, $_GET['page'] * 10);

if ($category == "gnome")
  $d_category = "GNOME";
else
  $d_category = ucwords ($category);

?>
<a href="/">GNOME Art</a> &gt; <a href="/backgrounds">Backgrounds</a>
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
    print ("<tr><td rowspan='2'><img width=96' src='/images/thumbnails/backgrounds/{$row['backgroundID']}.jpg'/></td>");
    print ("<td colspan='2'>{$row['description']}</td></tr>");
    print ("<tr><td>{$row['release_date']}</td><td width='75%'>{$row['license']}</td></tr>");
    print ("<tr><td colspan='3'>&nbsp;</td></tr>");
  }
?>

</table>
<br/>
<center><?php $p->print_pagination (); ?></center>

<?php $t->print_footer() ?>
