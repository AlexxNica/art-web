<?php

function admin_header($title)
{
?>
<html><head><title>art.gnome.org config</title>
<link rel="stylesheet" href="/main.css" type="text/css">
<link rel="stylesheet" href="/new_layout.css" type="text/css">
</head>
<body>
<div id="hdr">
<a href="/"><img id="logo" src="/images/site/gnome-64.png" alt="Home" title="Back to the Gnome Developer's home page"/></a>

<div id="hdrNav">
<a href="http://www.gnome.org/about/">About GNOME</a> &middot;

<a href="http://www.gnome.org/start/stable/">Download</a> &middot;
<a href="http://www.gnome.org/">Users</a> &middot;
<a href="http://developer.gnome.org/">Developers</a> &middot;
<a href="http://foundation.gnome.org/">Foundation</a> &middot;
<a href="http://art.gnome.org/"><b>Art &amp; Themes</b></a> &middot;

<a href="http://www.gnome.org/contact/">Contact</a>
</div>
</div>
<div id="body">
<table cellspacing="10" width="100%">
<tr>
<td valign="top" width="200" nowrap>


<div align="left" class="mb-lite-title">
Admin
</div>

<div align="left" class="mb-lite-contents">

<h3>Backgrounds</h3>
&nbsp;&nbsp;&nbsp;<a href="add_background.php">Add A New Background</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_background.php">Edit A Background</a><br>
&nbsp;&nbsp;&nbsp;Delete A Background<br>


<h3>Themes</h3>
&nbsp;&nbsp;&nbsp;<a href="add_theme.php">Add A New Theme</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_theme.php">Edit A Theme</a><br>
&nbsp;&nbsp;&nbsp;<a href="delete_theme.php">Delete A Theme</a><br>

<h3>News</h3>
&nbsp;&nbsp;&nbsp;<a href="add_news_item.php">Add a News Item</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_news_item.php">Edit News Item</a><br>
&nbsp;&nbsp;&nbsp;Delete News Item<br>

<h3>FAQ</h3>
&nbsp;&nbsp;&nbsp;<a href="add_faq.php">Add FAQ Entry</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_faq.php">Edit FAQ Entry</a><br>
&nbsp;&nbsp;&nbsp;<a href="delete_faq.php">Delete FAQ Entry</a><br>
&nbsp;&nbsp;&nbsp;Re-Order FAQ<br>
<!--
<h3>Tips &amp; Tricks</h3>
&nbsp;&nbsp;&nbsp;<a href="add_tip.php">Add Tips & Tricks Entry</a><br>
&nbsp;&nbsp;&nbsp;<a href="edit_tip.php">Edit Tips &amp; Tricks Entry</a><br>

<h3>Misc</h3>
&nbsp;&nbsp;&nbsp;<a href="add_screenshot.php">Add A New Screenshot</a><br>
&nbsp;&nbsp;&nbsp;Delete A Screenshot<br>
-->
<h3>Submissions</h3>
&nbsp;&nbsp;&nbsp;<a href="show_submitted_backgrounds.php">Submitted Backgrounds</a><br>
&nbsp;&nbsp;&nbsp;<a href="show_submitted_themes.php">Submitted Themes</a><br>

</div>

</td>
<td width="100%" valign="top">
<div class="mb-lite-title"><?php echo $title; ?></div>
<div class="mb-lite-contents">

<?php
}

function admin_footer()
{
?>
</td>
</tr>
</table>
</div>
</body>
</html>
<?php
}

?>
