<?php
require("mysql.inc.php");
require("common.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);
escape_gpc_array ($_GET);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);
extract($_GET, EXTR_SKIP);

?>
<html>
<head>
<title>Submitted Themes</title>
<style type="text/css">
	table { border: thin black solid; border-spacing: 0;}
	th { border: 1px black solid; background: silver; font-weight: bold;}
	td { border: 1px black solid; }
</style>
</head>

<body>
<?

if($mark_theme)
{
	$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='$new_status' WHERE themeID='$mark_theme'");
	print("Successfully marked theme as $new_status.<p><a href=\"$PHP_SELF\">Return</a> to incoming themes list.");
}
else
{
	print("<h1>Submitted Themes</h1>");
	print("<form method=\"GET\" action=\"$PHP_SELF\">Show only <select name=\"theme_type\"><option value=\"\"><option value=\"metacity\">metacity<option value=\"icon\">icon<option value=\"gtk2\">gtk2<option value=\"gdm_greeter\">gdm greeter <option value=\"splash_screens\">splash screen</select> themes <input type=\"submit\" value=\"Go\"></form>");
	print("<a href=\"$PHP_SELF?theme_type=$theme_type\">Table</a>|<a href=\"$PHP_SELF?theme_type=$theme_type&list=urls\">URL List</a><br>");


	if($theme_type) 
	{
   		$query = "SELECT * FROM incoming_theme WHERE status='new' AND category='$theme_type'";
		print("Showing only $theme_type themes<br>");
	}
	else
	{
		$query = "SELECT * FROM incoming_theme WHERE status='new'";
	}
   $incoming_theme_select_result = mysql_query($query);
	print("<hr>");
   if(mysql_num_rows($incoming_theme_select_result)==0)
   {
	   print("There are no theme submissions.");
   }
   else
   {
	if ($list=="urls")
	{
		while($incoming_theme_select_row = mysql_fetch_array($incoming_theme_select_result))
		{
			$theme_url = $incoming_theme_select_row["theme_url"];
			print("$theme_url<br>\n");
		}
		die();
	}
      print("<table>");
      print("<tr><th>ID<th>Category<th>Theme Name<th>Author<th>Download<th>Description<th>Action<th>Status</tr>\n");
	   while($incoming_theme_select_row = mysql_fetch_array($incoming_theme_select_result))
      {
   	   $themeID = $incoming_theme_select_row["themeID"];
   	   $theme_name = $incoming_theme_select_row["theme_name"];
   	   $category = $incoming_theme_select_row["category"];
   	   $author = $incoming_theme_select_row["author"];
   	   $author_email = $incoming_theme_select_row["author_email"];
   	   $theme_url = $incoming_theme_select_row["theme_url"];
   	   $theme_description = $incoming_theme_select_row["theme_description"];
   	   print("<tr><td>$themeID</td><td>$category</td><td>$theme_name</td><td><a href=\"mailto:$author_email\">$author</a></td><td><a href=\"$theme_url\">Download</td><td>$theme_description</td><td><form action=\"add_theme.php\" method=\"post\"><input type=\"hidden\" name=\"theme_submitID\" value=\"$themeID\"><input type=\"hidden\" name=\"theme_name\" value=\"$theme_name\"><input type=\"hidden\" name=\"theme_category\" value=\"$category\"><input type=\"hidden\" name=\"theme_author\" value=\"$author\"><input type=\"hidden\" name=\"author_email\" value=\"$author_email\"><input type=\"hidden\" name=\"description\" value=\"$theme_description\"><input type=\"submit\" value=\"Add\"></form></td><td><form action=\"$PHP_SELF\" method=\"post\"><select name=\"new_status\"><option value=\"new\">New<option value=\"added\">Added<option value=\"rejected\">Rejected</select><input type=\"submit\" value=\"Change\"><input type=\"hidden\" name=\"mark_theme\" value=\"$themeID\"></form></td></tr>\n");
	   }
      print("</table>\n");
	}
}
print("</body>\n</html>\n");
?>
