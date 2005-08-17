<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

admin_header("Delete a Background");
admin_auth(2);

// write the updated background text do the database
if($action == "delete")
{
	/* remove from background database */
	$background_delete_query = "DELETE FROM background WHERE backgroundID='$backgroundID'";
	//print("$background_delete_query\n");
	$background_delete_result = mysql_query($background_delete_query);
	if($background_delete_result)
	{
		print("Successfully deleted background.");
		
		$background_resolution_delete_query = "DELETE FROM background_resolution WHERE backgroundID='$backgroundID'";
		$background_resolution_delete_result = mysql_query($background_delete_query);
		if ($background_resolution_delete_result === FALSE) {
			print('<p class="error">Error deleting the resolutions of the background.</p>');
		}
	}
	else
	{
		print("Error deleting background.");
	}
	print("Click <a href=\"" . $_SERVER["PHP_SELF"] . "\">here</a> to return");

}
// display the confirmation window
elseif($action == "confirm")
{
	$background_select_result = mysql_query("SELECT background_name FROM background WHERE backgroundID='$backgroundID'");
	list($background_name) = mysql_fetch_row($background_select_result);
	print("Are you sure you want to delete $background_name (backgroundID: $backgroundID) from the database?");
	print("<p>\n");
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<input type=\"submit\" value=\"Continue\">\n");
	print("<input type=\"hidden\" name=\"backgroundID\" value=\"$backgroundID\">\n");
	print("<input type=\"hidden\" name=\"action\" value=\"delete\">\n");
	print("</form>\n");
}
else
{
	print("<table>\n");
	$background_categories = array("gnome","other");
	for($count=0;$count<count($background_categories);$count++)
	{
		$category = $background_categories[$count];
		$background_select_result = mysql_query("SELECT backgroundID, background_name FROM background WHERE category='$category' ORDER by backgroundID");
//		print("<table border=\"0\">\n");
		print("<tr><td><label for=\"$category\"><strong>$category</strong></label></td>");
		if(mysql_num_rows($background_select_result)==0)
		{
			print("<td colspan=\"2\">None</td></tr>\n");
		}
		else
		{
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
			print("<td><select name=\"backgroundID\" size=\"5\" id=\"$category\">\n");
			while(list($backgroundID,$background_name) = mysql_fetch_row($background_select_result))
			{
				print("<option value=\"$backgroundID\">".html_parse_text($backgroundID .": " . $background_name)."</option>\n");
			}
			print("</select></td><td><input type=\"submit\" value=\"Delete\"></td></tr>");
			print("<input type=\"hidden\" name=\"action\" value=\"confirm\">\n</form>\n");
		}
//		print("</table>\n<p>\n");
	}
	print("</table>");
}

admin_footer();

?>
