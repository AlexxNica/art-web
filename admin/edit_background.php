<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

if ($_GET)
	$category = validate_input_array_default($_GET["category"], Array('gnome', 'other'), "");

admin_header("Edit a Background");

// write the updated background text do the database
if($action == "write")
{
	if($background_name && $userID && $month && $day && $year && $background_description && $thumbnail_filename && $license)
	{
		$date = $year . "-" . $month . "-" . $day;
		$background_update_query  = "UPDATE background SET background_name='$background_name', license='$license', version='$version', category='$category', userID='$userID', parent='$parentID', release_date='$date', background_description='$background_description', thumbnail_filename='$thumbnail_filename' WHERE backgroundID='$backgroundID'";
		$background_update_result = mysql_query($background_update_query);
		if(mysql_affected_rows() == 1)
		{
			print("Successfully edited background text in database.");
			print("<table><tr><td>background_name</td><td>'$background_name'</td></td><tr><td>license</td><td>'$license'</td></td><tr><td>version</td><td>'$version'</td></td><tr><td>parent</td><td>$parentID</td></tr><tr><td>category</td><td>'$category'</td></td><tr><td>userID</td><td>'$userID'</td></td><tr><td>release_date</td><td>'$date'</td></td><tr><td>background_description</td><td>'$background_description'</td></td><tr><td>thumbnail_filename</td><td>'$thumbnail_filename'</td></tr></table>");
			print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.");
		}
		else
		{
			print("<p class=\"warning\">No rows updated</p>");
			print("<p class=\"info\">Query was:<br/>$background_update_query</p>");
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
	$background_select_result = mysql_query("SELECT * FROM background WHERE backgroundID='$backgroundID'");
	if(mysql_num_rows($background_select_result)==0)
	{
		print("<p>Could not select background to be updated</p>");
		print("<tt>".mysql_error()."</tt>");
	}
	else
	{
		$background_array = mysql_fetch_array($background_select_result);
		foreach ($background_array as $key => $value) $background_array[$key] = htmlspecialchars($value);
		extract($background_array);

		list($year,$month,$day) = explode("-",$release_date);
		print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
		print("<table border=\"0\">\n");
		print("<tr><td><b>Background Name:</b></td><td><input type=\"text\" name=\"background_name\" size=\"40\" value=\"$background_name\"></td></tr>\n");
		print("<tr><td><b>Category</b></td><td>");print_select_box("category", Array("gnome"=>"GNOME","other"=>"Other"), $category);print("</td></tr>\n");
		$user_select = mysql_query("SELECT userID,username FROM user");
		while (list($uid, $uname) = mysql_fetch_row($user_select)) $user_array[$uid] = $uname;
		print("<tr><td><b>UserID:</b></td><td>");print_select_box("userID", $user_array, $userID);print("</td></tr>\n");
		print("<tr><td><b>License</b></td><td>");print_select_box("license",$license_config_array, $license); print("</td></tr>\n");
		print("<tr><td><b>Version</b></td><td><input type=\"text\" name=\"version\" value=\"$version\"></td></tr>\n");
		print("<tr><td><b>Variation of </b></td><td><select name=\"parentID\"><option value=\"0\">N/A</option>");

		$background_var_select_result = mysql_query("SELECT backgroundID,background_name,category FROM background WHERE userID=$userID AND parent=0 ORDER BY category");
		while(list($var_themeID,$var_theme_name, $var_category)=mysql_fetch_row($background_var_select_result))
		{
			if ($var_themeID == $parent)
				$selected = "selected=\"true\"";
			else
				$selected = "";
			print("<option $selected value=\"$var_themeID\">$var_theme_name ($var_category)</option>");
		}
		print("</td></tr>");


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
