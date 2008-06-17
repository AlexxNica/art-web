<?php

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
    <td colspan="5" style="width:100%"><?php echo $row['description']?></td>
  </tr>
  <tr>
    <td class="label">Date:</td>
    <td><?php $tm = strtotime ($row['release_date']); echo date ("d M Y", $tm); ?></td>
    <td class="label">License:</td>
    <td><?php echo $license_config_array[$row['license']]?></td>
    <td><a href="#">Download</a></td>
  </tr>
</table>
</div>
<br>
<?php endforeach ?>


<br>
<div style="text-align:center"><?php $p->print_pagination (); ?></div>

<?php $t->print_footer() ?>
