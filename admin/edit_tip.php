<?php
require("mysql.inc.php");
print("<html>\n<head><title>Edit a Tip</title></head>\n<body>\n");
print("<div align=\"center\">");
print("<font size=\"+2\">Edit a Tip &amp; Trick Entry</font>\n<p>\n");
print("</div>\n");

// write the updated FAQ do the database
if($action == "write")
{
	if($tipID && $title && $body)
   {
   	$tip_update_result = mysql_query("UPDATE tip SET title='$title', body='$body' WHERE tipID='$tipID'");
      if(mysql_affected_rows() == 1)
      {
      	print("Successfully updated Tip in database.");
         print("<p>\n<a href=\"" . $_SERVER["PHP_SELF"] . "\">Click Here</a> to edit another.");
   	}
      else
      {
      	print("Database Error, unable to update database.  Contact Alex.");
      }	
   }
   else
   {
   	print("Error, you must have a question and an answer.");
   }
}
// display the question and answer in a textarea for editing
elseif($action == "edit")
{
	$tip_select_result = mysql_query("SELECT title,body FROM tip WHERE tipID='$tipID'");
   if(mysql_num_rows($tip_select_result) == 0)
   {
   	print("Error, invalid tipID.");
   }
   else
   {
   	list($title,$body) = mysql_fetch_row($tip_select_result);
      $title = htmlspecialchars($title);
      $body = htmlspecialchars($body);
      print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
   	print("<p>Title:<br>\n");
   	print("<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\">\n");
   	print("<p>Body Text:<br>\n");
   	print("<textarea name=\"body\" cols=\"60\" rows=\"16\">$body</textarea>\n");
   	print("<input type=\"hidden\" name=\"action\" value=\"write\">\n");
      print("<input type=\"hidden\" name=\"tipID\" value=\"$tipID\">\n");
      print("<p><input type=\"submit\" value=\"Update Tip\">\n");
   	print("</form>\n");
   }
}
else
{
	$tip_select_result = mysql_query("SELECT tipID,title FROM tip ORDER by tipID");
	if(mysql_num_rows($tip_select_result)==0)
	{
		print("There are no tips &amp; tricks available for editing.");
	}
	else
	{
		print("<ol>\n");
	   while(list($tipID,$title)=mysql_fetch_row($tip_select_result))
	   {
	   	print("<li>$title [<a href=\"" . $_SERVER["PHP_SELF"] . "?action=edit&tipID=$tipID\">Edit</a>]\n\n\n");
	   }
	   print("</ol>\n");
	}
}

print("</body>\n</html>\n");
?>
