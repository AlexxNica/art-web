<?php

session_start();



function admin_header($title)
{

	if (!array_key_exists('username', $_SESSION) && $title != "ART.GNOME.ORG Administration")
		die("Error: Not logged in");

print("<html><head><title>$title</title>");
?><link rel="stylesheet" href="/main.css" type="text/css">
<link rel="stylesheet" href="/new_layout.css" type="text/css">
</head>
<body>
<div id="hdr">
<a href="/"><img id="logo" src="/images/site/gnome-64.png" alt="Home" title="Back to the Gnome Developer's home page"/></a>

<div id="hdrNav">
<a href="/admin/">Admin Home</a>&nbsp;&middot;&nbsp;
<a href="show_submitted_backgrounds.php">Backgrounds Submissions</a>&nbsp;&middot;&nbsp;
<a href="show_submitted_themes.php">Themes Submissions</a>


</div>
</div>
<div id="body">
<table cellspacing="10" width="100%">
<tr>
<td valign="top" width="100%" nowrap>

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
