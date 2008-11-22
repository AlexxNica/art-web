<?php
function tab ($tab, $page)
{
  if ($tab == $page) echo ' class="selected"';
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html><head>


  <link href="/css/layout.css" rel="stylesheet" type="text/css" media="screen">
  <link href="/css/style.css" rel="stylesheet" type="text/css" media="all">
  <?php foreach ($css as $s): ?>
    <link href="<?php echo $s?>" rel="stylesheet" type="text/css" media="all">
  <?php endforeach ?>
  <link rel="icon" type="image/png" href="http://www.gnome.org/img/logo/foot-16.png">
  <link rel="SHORTCUT ICON" type="image/png" href="http://www.gnome.org/img/logo/foot-16.png">
  <title>GNOME: The Free Software Desktop Project</title>


<link rel="stylesheet" type="text/css" href="css/frontpage.css"></head><body>
  <!-- site header -->
  <div id="page">
    <ul id="general">
      <li id="siteaction-gnome_home" class="home">
        <a href="http://www.gnome.org/" title="Home">Home</a>
      </li>
      <li id="siteaction-gnome_news">
        <a href="http://news.gnome.org/" title="News">News</a>
      </li>
      <li id="siteaction-gnome_projects">
        <a href="http://www.gnome.org/projects/" title="Projects">Projects</a>
      </li>
      <li id="siteaction-gnome_art">
        <a href="http://art.gnome.org/" title="Art">Art</a>
      </li>
      <li id="siteaction-gnome_support">
        <a href="http://www.gnome.org/support/" title="Support">Support</a>
      </li>
      <li id="siteaction-gnome_development">
        <a href="http://developer.gnome.org/" title="Development">Development</a>
      </li>
      <li id="siteaction-gnome_community">
        <a href="http://www.gnome.org/community/" title="Community">Community</a>
      </li>
    </ul>
    <div id="header">
      <h1>GNOME Art</h1>
      <div id="tabs">
        <ul id="portal-globalnav">
          <li<?php tab('art', $page)?>><a href="/"><span>Art</span></a></li>
          <li<?php tab('themes', $page)?>><a href="/themes"><span>Themes</span></a></li>
          <li<?php tab('backgrounds', $page)?>><a href="/backgrounds"><span>Backgrounds</span></a></li>
          <li<?php tab('faq', $page)?>><a href="/faq.php"><span>FAQ</span></a></li>
        </ul>
      </div> <!-- end of #tabs -->
    </div> <!-- end of #header -->
  </div>
<!-- end site header -->

<div<?php if ($options['sidebar']) echo ' id="body"'?>>
<div id="content">

