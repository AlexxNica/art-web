<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

$category = validate_input_array_default($_GET["category"], Array('gnome', 'other'), "");

admin_header("Edit a Background");

// write the updated background text do the database
if($action == "write")
{
	if($background_name && $userID && $month && $day && $year && $background_description && $thumbnail_filename )
	{
		$date = $year . "-" . $month . "-" . $day;
		$background_update_query  = "UPDATE background SET background_name='$background_name', category='$category', userID='$userID', release_date='$date', background_description='$background_description', thumbnail_filename='$thumbnail_filename' WHERE backgroundID='$backgroundID'";
		$background_update_result = mysql_query($background_update_query);
		if(mysql_affected_rows() == 1)
		{
			print("Successfully edited background text in database.");
			print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.");
		}
		else
		{
			print("<p>Database Error, unable to update database.</p>");
			print($background_update_query);
			print("<tt>".mysql_error()."</tt>");
		}
		
	}
	else
	{
		print("Error, all of the form fields are not filled in.");
	}
}
// display the background text fields for editing
elseif($action == "edit")
{
	$background_select_result = mysql_query("SELECT background_name,userID,release_date,background_description,thumbnail_filename FROM background WHERE backgroundID='$backgroundID'");
	if(mysql_num_rows($background_select_result)==0)
	{
		print("<p>Could not select background to be updated</p>");
		print("<tt>".mysql_error()."</tt>");
	}
	else
	{
		list($background_name,$userID,$release_date,$background_description,$thumbnail_filename) = mysql_fetch_row($background_select_result);
		$background_name = htmlspecialchars($background_name);
		$background_description = htmlspecialchars($background_description);
		$thumbnail_filename = htmlspecialchars($thumbnail_filename);

		list($year,$month,$day) = explode("-",$release_date);
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<table border=\"0\">\n");
		print("<tr><td><b>Background Name:</b></td><td><input type=\"text\" name=\"background_name\" size=\"40\" value=\"$background_name\"></td></tr>\n");
		print("<tr><td><b>Category</b></td><td><select name=\"category\"><option value=\"\">Choose<option value=\"gnome\">GNOME<option value=\"other\">Other</select></td></tr>\n");
		print("<tr><td><b>UserID:</b></td><td><input type=\"text\" name=\"userID\" size=\"40\" value=\"$userID\"></td></tr>\n");
		print("<tr><td><b>Release Date:</b></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
		print("<tr><td><b>Background Description:</b></td><td><textarea name=\"background_description\" cols=\"40\" rows=\"5\" wrap>$background_description</textarea></td></tr>\n");
		print("<tr><td><b>Thumbnail Filename:</b></td><td><input type=\"text\" name=\"thumbnail_filename\" size=\"40\" value=\"$thumbnail_filename\"></td></tr>\n");
		print("</table>\n<p>\n");
		print("<input type=\"submit\" value=\"Update Background\">");
		print("<input type=\"hidden\" name=\"action\" value=\"write\">\n");
		print("<input type=\"hidden\" name=\"backgroundID\" value=\"$backgroundID\">\n");
		print("</form>");
 	}
}
elseif($category != "")
{
	$background_select_result = mysql_query("SELECT backgroundID, background_name FROM background WHERE category='$category' $user_sql ORDER BY backgroundID");
	print(mysql_error());
	if(mysql_num_rows($background_select_result)==0)
	{
		print("$category: None\n");
	}
	else
	{
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<b>$category</b><br /><select name=\"backgroundID\" size=\"24\">\n");
		while(list($backgroundID,$background_name) = mysql_fetch_row($background_select_result))
		{
			print("<option value=\"$backgroundID\">$backgroundID: $background_name\n");
		}
		print("</select><br /><input type=\"submit\" value=\"Edit\">");
		print("<input type=\"hidden\" name=\"action\" value=\"edit\">\n</form>\n");
	}
}
else
{
	$background_categories = array("gnome","other");
	print("<b>Category</b>");
	print("<ul>");
	for($count=0;$count<count($background_categories);$count++)
	{
		$category = $background_categories[$count];
		print("<li><a href=\"{$_SERVER['PHP_SELF']}?category=$category\">$category</a></li>");
	}
	print("</ul>");
}

admin_footer();
?>
