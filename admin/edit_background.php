<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");


$resolution_array = Array("1024x768" => "1024x768", "1280x1024" => "1280x1024", "1600x1200" => "1600x1200");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

if ($_GET)
	$category = validate_input_array_default($_GET["category"], Array('gnome', 'other'), "");

admin_header("Edit a Background");
admin_auth(2);

// write the updated background text do the database
if($action == "write")
{
	if($background_name && $userID && $month && $day && $year && $background_description && $thumbnail_filename && $license && $resolution)
	{
		$date = $year . "-" . $month . "-" . $day;
		$background_update_query  = "UPDATE background SET background.background_name='$background_name', background.license='$license', background.version='$version', background.category='$category', background.userID='$userID', background.parent='$parentID', background.release_date='$date', background.background_description='$background_description', background.thumbnail_filename='$thumbnail_filename' WHERE background.backgroundID='$backgroundID'";
		if(!$background_update_result = mysql_query($background_update_query)) {
			$error = 1;
		}
		
		$i=0;
		while($resID = $background_resolutionID[$i]) {
			$background_res_update_query[$i] = "UPDATE background_resolution SET resolution='{$resolution[$i]}',filename='{$filename[$i]}' WHERE background_resolutionID=$resID";
			if(!$background_res_update_result = mysql_query($background_res_update_query[$i])) {
			$error++;
			}
			
			$i++;
		}
		if(!$error)
		{
			print("Successfully edited background text in database.");
			print("<table><tr><td>background_name</td><td>'$background_name'</td></td><tr><td>license</td><td>'$license'</td></td><tr><td>version</td><td>'$version'</td></td><tr><td>parent</td><td>$parentID</td></tr><tr><td>category</td><td>'$category'</td></td><tr><td>userID</td><td>'$userID'</td></td><tr><td>release_date</td><td>'$date'</td></td><tr><td>background_description</td><td>'$background_description'</td></td><tr><td>thumbnail_filename</td><td>'$thumbnail_filename'</td></tr>");
			$i=0;
			while($resID = $background_resolution[$i]) {
				print("<tr><td>resolution ($resID) </td><td>$resolution[$i]</td></tr>");
				$i++;
			}
			print("</table>");
			print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.");
		}
		else
		{
			print("<p class=\"warning\">There were $error error(s).</p>");
			print("<p class=\"info\">Query was:<br/>$background_update_query</p>");
			print("<tt>".mysql_error()."</tt>");
			foreach($background_res_update_query as $query) {
				print("<p class=\"info\">Query was:<br/>$query</p>");
			}
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
	$background_select_query = "SELECT * FROM background WHERE backgroundID='$backgroundID'";
	$background_select_result = mysql_query($background_select_query);
	if (mysql_num_rows($background_select_result)==0)
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
		print("<tr><td><strong>Background Name:</strong></td><td><input type=\"text\" name=\"background_name\" size=\"40\" value=\"$background_name\"></td></tr>\n");
		print("<tr><td><strong>Category</strong></td><td>");print_select_box("category", Array("gnome"=>"GNOME","other"=>"Other"), $category);print("</td></tr>\n");
		$user_select = mysql_query("SELECT userID,username FROM user");
		while (list($uid, $uname) = mysql_fetch_row($user_select)) $user_array[$uid] = $uname;
		print("<tr><td><strong>UserID:</strong></td><td>");print_select_box("userID", $user_array, $userID);print("</td></tr>\n");
		print("<tr><td><strong>License</strong></td><td>");print_select_box("license",$license_config_array, $license); print("</td></tr>\n");
		print("<tr><td><strong>Version</strong></td><td><input type=\"text\" name=\"version\" value=\"$version\"></td></tr>\n");
		print("<tr><td><strong>Variation of </strong></td><td><select name=\"parentID\"><option value=\"0\">N/A</option>");

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

		print("<tr><td><strong>Release Date:</strong></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
		print("<tr><td><strong>Background Description:</strong></td><td><textarea name=\"background_description\" cols=\"40\" rows=\"5\" wrap>$background_description</textarea></td></tr>\n");
		print("<tr><td><strong>Thumbnail Filename:</strong></td><td><input type=\"text\" name=\"thumbnail_filename\" size=\"40\" value=\"$thumbnail_filename\"></td></tr>\n");

		$background_resolution_result = mysql_query("SELECT background_resolutionID,resolution,filename FROM background_resolution WHERE backgroundID=$backgroundID");
		$i = 0;
		while($resolution_row = mysql_fetch_array($background_resolution_result)) {
			extract($resolution_row);
			print("<tr><td><strong>Resolution #$i:</strong></td>");
			print("<td>");print_select_box("resolution[$i]", $resolution_array, $resolution);
			print("&nbsp;<input type=\"text\" name=\"filename[$i]\" size=\"40\" value=\"$filename\"></td>");
			print("<input type=\"hidden\" name=\"background_resolutionID[$i]\" value=\"$background_resolutionID\"></tr>\n");
			$i++;
		}
		
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
		print("<strong>$category</strong><br /><select name=\"backgroundID\" size=\"24\">\n");
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
	print("<strong>Category</strong>");
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
