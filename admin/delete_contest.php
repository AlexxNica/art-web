<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

admin_header("Delete an item from a Contest");
admin_auth(2);

// write the updated background text do the database
if($action == "delete")
{
	/* remove from theme database */
	$contest_delete_query = "DELETE FROM contest WHERE contestID='$contestID'";
	
	$contest_delete_result = mysql_query($contest_delete_query);
	if($contest_delete_result)
	{
		print("Successfully deleted theme.");
	}
	else
	{
		print("Error deleting theme.");
	}
	print("Click <a href=\"" . $_SERVER["PHP_SELF"] . "\">here</a> to return");
	

}
// display the confirmation window
elseif($action == "confirm")
{
	$theme_select_result = mysql_query("SELECT name FROM contest WHERE contestID='$contestID'");
	list($contest_name) = mysql_fetch_row($theme_select_result);
	print("Are you sure you want to delete $contest_name (contestID: $contestID) from the database?");
	print("<p>\n");
	print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
	print("<input type=\"submit\" value=\"Continue\">\n");
	print("<input type=\"hidden\" name=\"contestID\" value=\"$contestID\">\n");
	print("<input type=\"hidden\" name=\"action\" value=\"delete\">\n");
	print("</form>\n");
}
else
{
	print("<table>\n");
	
	foreach ($contest_config_array as $contest => $value)
	{
		$contest_select_result = mysql_query("SELECT contestID, name FROM contest WHERE contest='$contest' ORDER by name");
		
		print("<tr><td><label for=\"$contest\"><strong>$contest</strong></label></td>");
		if(mysql_num_rows($contest_select_result)==0)
		{
			print("<td colspan=\"2\">None</td></tr>\n");
		}
		else
		{
			print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
			print("<td><select name=\"contestID\" size=\"5\" id=\"$category\">\n");
			while(list($contestID,$name) = mysql_fetch_row($contest_select_result))
			{
				print("<option value=\"$contestID\">".html_parse_text($name)."</option>\n");
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
