<?php

require("mysql.inc.php");
require("common.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

function create_select_box($name,$options,$selected)
{
	$select = "selected";
   print("<select name=\"$name\">\n");
   while ( list($key,$val) = each($options) )
   {
   	if($key == $selected)
      {
      	print("<option value=\"$key\" $select>$val</option>\n");
      }
      else
      {
      	print("<option value=\"$key\">$val</option>\n");
   	}
   }
	print("</select>\n");
}

print("<html>\n<head><title>Add a Theme</title></head>\n<body>\n");
print("<div align=\"center\">");
print("<font size=\"+2\">Add a Theme</font>\n<p>\n");
if($action == "add_theme")
{
	if($theme_name && $theme_author && $month && $day && $year && $description && $thumbnail_filename && $small_thumbnail_filename && (count($download_toggles)>0) )
   {
   	$date = $year . "-" . $month . "-" . $day;
      $timestamp = time();
      $theme_insert_query  = "INSERT INTO theme(themeID,status,theme_name,category,author,author_email,add_timestamp,release_date,description,thumbnail_filename,small_thumbnail_filename, download_start_timestamp) ";
      $theme_insert_query .= "VALUES('','active','$theme_name','$category','$theme_author','$author_email','$timestamp','$date','$description','$thumbnail_filename','$small_thumbnail_filename', UNIX_TIMESTAMP())";
   	//print($theme_insert_query);
      $theme_insert_result = mysql_query($theme_insert_query);
      $themeID = mysql_insert_id();
   	while(list($key,$val)=each($download_toggles))
      {
      	$theme_download_insert_query  = "INSERT INTO theme_download(theme_downloadID,themeID,name,download_name) ";
      	$theme_download_insert_query .= "VALUES('','$themeID','$theme_names[$key]','$theme_downloads[$key]')";
      	//print($theme_download_insert_query);
         $theme_download_insert_result = mysql_query($theme_download_insert_query);
      }
   	if($theme_insert_result && $theme_download_insert_result)
      {
      	print("Successed added theme to the database.\n<p>\nClick <a href=\"$PHP_SELF\">here</a> to add another.");	
	if($theme_submitID) 
		{
        		print("<form action=\"show_submitted_themes.php\"><input type=\"hidden\" name=\"mark_theme\" value=$theme_submitID><input type=\"hidden\" name=\"new_status\" value=\"added\"><input type=\"submit\" value=\"Mark as Added\"></form>");
		}
      }
      else
      {
      	print("There were database errors adding theme into database.  Contact Alex.");
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
	"other" => "other"
	);
	$date = date("m/d/Y");
   list($month,$day,$year) = explode("/",$date);
   print("<form action=\"$PHP_SELF\" method=\"post\">\n");
   print("<table border=\"0\">\n");
   print("<tr><td><b>Theme Name:</b></td><td><input type=\"text\" name=\"theme_name\" size=\"40\" value=\"$theme_name\"></td></tr>\n");
   print("<tr><td><b>Category</b></td><td>");
	create_select_box("category",$category_array,$theme_category);
	print("</td></tr>\n");
   print("<tr><td><b>Theme Author:</b></td><td><input type=\"text\" name=\"theme_author\" size=\"40\" value=\"$theme_author\"></td></tr>\n");
   print("<tr><td><b>Author Email:</b></td><td><input type=\"text\" name=\"author_email\" size=\"40\" value=\"$author_email\"></td></tr>\n");
   print("<tr><td><b>Release Date:</b></td><td><input type=\"text\" name=\"month\" value=\"$month\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"day\" value=\"$day\" size=\"2\" maxlenght=\"2\">/<input type=\"text\" name=\"year\" value=\"$year\" size=\"4\" maxlenght=\"4\"></td></tr>\n");
   print("<tr><td><b>Description:</b></td><td><textarea name=\"description\" cols=\"40\" rows=\"5\" wrap>$description</textarea></td></tr>\n");
	print("<tr><td><b>Thumbnail Filename:</b></td><td><input type=\"text\" name=\"thumbnail_filename\" size=\"40\"></td></tr>\n");
	print("<tr><td><b>Small Thumbnail Filename:</b></td><td><input type=\"text\" name=\"small_thumbnail_filename\" size=\"40\"></td></tr>\n");
	print("</table>\n<p>\n");
   
   print("<table border=\"0\">\n");
   print("<tr><td><b>X</b></td><td>Name</td><td><b>Download Name</b></td><td><b>Size</b></td></tr>\n");
   for($count=0;$count<8;$count++)
   {
   	print("<tr><td><input type=\"checkbox\" name=\"download_toggles[$count]\"></td><td><input type=\"text\" name=\"theme_names[$count]\"></td><td><input type=\"text\" name=\"theme_downloads[$count]\"></td><td><input type=\"text\" name=\"theme_sizes[$count]\"></td></tr>\n");
   }
   print("</table>\n");
   
   print("<input type=\"hidden\" name=\"action\" value=\"add_theme\"><input type=\"hidden\" name=\"theme_submitID\" value=\"$theme_submitID\">\n");
	print("<input type=\"submit\" value=\"Add Theme\">\n");
   print("</form>\n");
}
print("</div>\n</body>\n</html>\n");
?>
