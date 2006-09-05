<?php

include "mysql.inc.php";
include "common.inc.php";
include "includes/headers.inc.php";

admin_header('ART.GNOME.ORG Administration');
$admin_level = admin_auth (1);
?>
<h1>Submissions</h1>
<ul>
<li><a href="show_submitted_backgrounds.php">Submitted Backgrounds</a></li>
<li><a href="show_submitted_themes.php">Submitted Themes</a></li>
</ul>

<h1>Edit/Delete Artwork</h1>
<ul>
<li><a href="edit_art.php?type=background">Background</a></li>
<li><a href="edit_art.php?type=theme">Theme</a></li>
<li><a href="edit_art.php?type=screenshot">Screenshot</a></li>
<li><a href="edit_art.php?type=contest">Contest Item</a></li>
</ul>

<h1>Comments</h1>
<ul>
<li><a href="comments.php">Moderate Comments</a></li>
<li><a href="show_recent_comments.php">Recent Comments</a></li>
</ul>

<h1>News</h1>
<ul>
<li><a href="add_news_item.php">Add a News Item</a></li>
<li><a href="edit_news_item.php">Edit News Item</a></li>
<li>Delete News Item</li>
</ul>

<h1>FAQ</h1>
<ul>
<li><a href="add_faq.php">Add FAQ Entry</a></li>
<li><a href="edit_faq.php">Edit FAQ Entry</a></li>
<li><a href="delete_faq.php">Delete FAQ Entry</a></li>
<li><a href="show_submitted_faq.php">Pending FAQ Entries</a></li>
<li>Re-Order FAQ</li>
</ul>

<h1>Users</h1>
<ul>
<li><a href="edit_user.php">Edit a user</a></li>
</ul>

<?php
admin_footer();


?>

