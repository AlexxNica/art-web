<?php
require("mysql.inc.php");
require("common.inc.php");
require("includes/headers.inc.php");

// ensure POST special characters are escaped, regardless of magic_quotes_gpc setting
escape_gpc_array ($_POST);

// Extracts the POST variables to global variables
// Not ideal solution, but easiest
extract($_POST, EXTR_SKIP);

admin_header("Delete an FAQ");

// write the updated background text do the database
if($action == "delete")
{
    /* remove from faq database */
    $faq_delete_query = "DELETE FROM faq WHERE faqID='$faqID'";
    $faq_delete_result = mysql_query($faq_delete_query);
    if($faq_delete_result)
    {
        print("Sucessfully deleted FAQ.");
    }
    else
    {
        print("Error deleting FAQ.");
    }
    print("Click <a href=\"" . $_SERVER["PHP_SELF"] . "\">here</a> to return");
}
// display the confirmation window
elseif($action == "confirm")
{
    $faq_select_result = mysql_query("SELECT question FROM faq WHERE faqID='$faqID'");
    list($question) = mysql_fetch_row($faq_select_result);
    print("Are you sure you want to delete \"$question\" (faqID: $faqID) from the database?");
    print("<p>\n");
    print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
    print("<input type=\"submit\" value=\"Continue\">\n");
    print("<input type=\"hidden\" name=\"faqID\" value=\"$faqID\">\n");
    print("<input type=\"hidden\" name=\"action\" value=\"delete\">\n");
    print("</form>\n");
}
else
{
    $faq_select_result = mysql_query("SELECT faqID,question FROM faq ORDER by faqID");
    print("<form action=\"" . $_SERVER["PHP_SELF"] . "\" method=\"post\">\n");
    print("<select name=\"faqID\" size=\"5\">\n");
    while(list($faqID,$question) = mysql_fetch_row($faq_select_result))
    {
        print("<option value=\"$faqID\">$question\n");
    }
    print("</select><input type=\"submit\" value=\"Delete\">");
    print("<input type=\"hidden\" name=\"action\" value=\"confirm\">\n</form>\n");
}

admin_footer();
?>
