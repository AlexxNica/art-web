<?php

require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

admin_header("Add a Theme");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

if($action == "add_theme")
{
	if($theme_name && $theme_author && $month && $day && $year && $description && $thumbnail_filename && $small_thumbnail_filename && $download_filename )
	{
		$date = $year . "-" . $month . "-" . $day;
		$timestamp = time();
		$theme_insert_query  = "INSERT INTO theme(themeID,status,theme_name,category,author,author_email,add_timestamp,release_date,description,thumbnail_filename,small_thumbnail_filename, download_start_timestamp, download_filename) ";
		$theme_insert_query .= "VALUES('','active','$theme_name','$category','$theme_author','$author_email','$timestamp','$date','$description','$thumbnail_filename','$small_thumbnail_filename', UNIX_TIMESTAMP(), '$download_filename')";
		$theme_insert_result = mysql_query($theme_insert_query);
		$themeID = mysql_insert_id();
		if($theme_insert_result)
		{
			print("Successed added theme to the database.\n<p>\nClick <a href=\"" . $_SERVER["PHP_SELF"] . "\">here</a> to add another.");	
			if($theme_submitID)
			{
				$incoming_theme_update_result = mysql_query("UPDATE incoming_theme SET status='added' WHERE themeID='$theme_submitID'");
				print("Successfully marked submitted theme as added in incoming themes list.<p><a href=\"/admin/show_submitted_themes.php\">Return</a> to incoming themes list.");
			}
		}
		else
		{
			print("There were database errors adding theme into database.  Contact Thomas.");
		}
	}
	else
	{
		print("Error, all of the form fields are not filled in.");
	}
}
else
{
	$category_array = array(
	"gdm_greeter" => "gdm_greeter",
	"gtk" => "gtk",
	"gtk2" => "gtk2",
	"icon" => "icon",
	"metacity" => "metacity",
	"sawfish" => "sawfish",
	"sounds" => "sounds",
	"splash_screens" => "splash_screens",
	"other" => "other",
	"gtk_engines" => "gtk_engines"
	);
	$date = date("m/d/Y");
	list($month,$day,$year) = explode("/",$date);
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<table border=\"0\">\n");
	print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\" value=\"$theme_name\"></td></tr>\n");
	print("<tr><td><b>Category</b></td><td>");
	create_select_box("category",$category_array,$theme_category);
	print("</td></tr>\n");
	print("<tr><td><b>Theme Author:</b></td><td><input type=\"text\" name=\"theme_author\" size=\"40\" value=\"$theme_author\"></td></tr>\n");
	print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\" value=\"$author_email\"></td></tr>\n");
	print("<tr><td><b>Release Date:</b></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
	print("<tr><td><b>Description:</b></td><td><textarea name=\"description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");

	print("<tr><td><b>Thumbnail Filename:</b></td><td>");
	if (isset($theme_category)) file_chooser("thumbnail_filename", "/usr/local/www/art-web/images/thumbnails/$theme_category/");
	else print("<input type=\"text\" name=\"thumbnail_filename\" size=\"40\">");
	print("</td></tr>\n");

	print("<tr><td><b>Small Thumbnail Filename:</b></td><td>");
	if (isset($theme_category)) file_chooser("small_thumbnail_filename", "/usr/local/www/art-web/images/thumbnails/$theme_category/");
	else print("<input type=\"text\" name=\"small_thumbnail_filename\" size=\"40\">");
	print("</td></tr>\n");

	print("<tr><td><b>Download Filename:</b></td><td>"); 
	if (isset($theme_category)) file_chooser("download_filename", "$sys_theme_dir/$theme_category/"); 
	else print("<input type=\"text\" name=\"download_filename\" size=\"40\">");
	print("</td></tr>\n");
	
	print("</table>\n");
	
	print("<input type=\"hidden\" name=\"action\" value=\"add_theme\"><input type=\"hidden\" name=\"theme_submitID\" value=\"$theme_submitID\">\n");
	print("<input type=\"submit\" value=\"Add Theme\">\n");
	print("</form>\n");
}
admin_footer();
?>
