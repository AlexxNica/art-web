<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

admin_header("Delete a Screenshot");
admin_auth(2);

function delete_file ($file)
{
	if (file_exists ($file))
	{
		if (!unlink ($file))
			print ("<p class=\"error\">Could not delete $file</p>");
		else
			print ("<p>File $file deleted.</p>");
	}
	else
	{
		print ("<p class=\"info\">No file with the name $file exists.</p>");
	}
}

// write the updated background text do the database
if($action == "delete")
{
	/* get data from the db, to be able to delete the files */
	$query = "SELECT * FROM screenshot WHERE screenshotID='$ID'";
	$result = mysql_query ($query);
	if (!$result)
		art_fatal_error ("$ID is not a valid screenshot ID, or the SELECT statement failed for some other reason.");
	$row = mysql_fetch_assoc ($result);
	/* paths are relative to the admin dir. */
	$file_path = '../images/screenshots/'.$row['category'].'/'.$row['download_filename'];
	$thumb_path = '../images/thumbnails/screenshots/'.$row['category'].'/'.$row['thumbnail_filename'];
	
	/* remove item from the database */
	$query = "DELETE FROM screenshot WHERE screenshotID='$ID'";
	$result = mysql_query($query);
	if($result)
	{
		print("<p>Successfully deleted entry from the database.</p>");
		
		/* delete the files */
		delete_file ($file_path);
		delete_file ($thumb_path);
	}
	else
	{
		print("Error deleting screenshot from the database. Not deleting files either");
	}
	print("Click <a href=\"" . $_SERVER["PHP_SELF"] . "\">here</a> to return");
}
// display the confirmation window
elseif($action == "confirm")
{
	$result = mysql_query("SELECT name FROM screenshot WHERE screenshotID='$ID'");
	list($screenshot_name) = mysql_fetch_row($result);
	print("Are you sure you want to delete $screenshot_name (screenshotID: $ID) from the database?");
	print("<p>\n");
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<input type=\"submit\" value=\"Continue\">\n");
	print("<input type=\"hidden\" name=\"ID\" value=\"$ID\">\n");
	print("<input type=\"hidden\" name=\"action\" value=\"delete\">\n");
	print("</form>\n");
}
else
{
	print("<table>\n");
	
	foreach ($screenshot_config_array as $category => $data)
	{
		$select_result = mysql_query("SELECT screenshotID, name FROM screenshot WHERE category='$category' ORDER by name");
		print("<tr><td><label for=\"$category\"><strong>$category</strong></label></td>");
		if(mysql_num_rows($select_result) == 0)
		{
			print("<td colspan=\"2\">None</td></tr>\n");
		}
		else
		{
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
			print("<td><select name=\"ID\" size=\"5\" id=\"$category\">\n");
			while (list($ID,$name) = mysql_fetch_row($select_result))
			{
				print("<option value=\"$ID\">".html_parse_text($name)."</option>\n");
			}
			print("</select></td><td><input type=\"submit\" value=\"Delete\"></td></tr>");
			print("<input type=\"hidden\" name=\"action\" value=\"confirm\">\n</form>\n");
		}
	}
	print("</table>");
}

admin_footer();

?>
