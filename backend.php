<?php
/* Redirect to updates.php now that it can do exactly the same as this page */

if ($_SERVER['HTTPS']) $protocol = 'https'; else $protocol = 'http';
header ("Location: $protocol://{$_SERVER['SERVER_NAME']}/updates.php?format=rss");
?>
