<?php
require("mysql.inc.php");
require("session.inc.php");

print("<td><img src=\"/images/site/pixel.png\" width=\"10\" alt=\" \"></td>\n");
?>
<!-- Right Column -->
<td width="225">

<!-- Screenshot -->
<div class="mb_lite-title"><img src="/images/site/pill-icons/screenshot.png" alt="SCREENSHOT"> SCREENSHOT</div>
<div class="mb_lite-contents">

<?php
print("<div align=\"center\">\n");
$screenshot_select_result = mysql_query("SELECT screenshotID, thumbnail_filename FROM screenshot ORDER BY date DESC LIMIT 1");
list($screenshotID,$thumbnail_filename) = mysql_fetch_row($screenshot_select_result);
print("<a href=\"show_screenshot.php?screenshotID=$screenshotID\"><img src=\"/images/thumbnails/screenshots/$thumbnail_filename\" border=\"0\" alt=\" \"></a>\n");
print("<br><a href=\"screenshot_list.php\">More ...</a>");
print("</div>\n</div>\n");
?>
<!-- End Screenshot -->
<br> 
<!-- Background -->
<div class="mb_lite-title"><img src="/images/site/pill-icons/background.png" alt="BACKGROUND"> BACKGROUND</div>
<div class="mb_lite-contents">

<?php
print("<div align=\"center\">\n");
$background_select_result = mysql_query("SELECT backgroundID,category,thumbnail_filename FROM background ORDER BY add_timestamp DESC LIMIT 1");
list($backgroundID,$background_category,$thumbnail_filename) = mysql_fetch_row($background_select_result);
print("<a href=\"show_background.php?backgroundID=$backgroundID&category=$background_category\"><img src=\"/images/thumbnails/backgrounds/$thumbnail_filename\" border=\"0\" alt=\"thumbnail\"></a>\n");
print("</div>\n</div>\n");
?>
<!-- End Background -->
<br>
<!-- Theme -->
<div class="mb_lite-title"><img src="/images/site/pill-icons/theme.png" alt="THEME"> THEME</div>
<div class="mb_lite-contents">

<?php
print("<div align=\"center\">\n");
$theme_select_result = mysql_query("SELECT themeID,category,small_thumbnail_filename FROM theme ORDER BY add_timestamp DESC LIMIT 1");
list($themeID,$theme_category,$thumbnail_filename) = mysql_fetch_row($theme_select_result);
print("<a href=\"show_theme.php?themeID=$themeID&category=$theme_category\"><img src=\"/images/thumbnails/$theme_category/$thumbnail_filename\" border=\"0\" alt=\"thumbnail\"></a>\n");
print("</div>\n</div>\n");
?>
<!-- End Theme -->

<img src="/images/site/pixel.png" width="225" height="1" alt=" ">
</td>
<td><img src="/images/site/pixel.png" width="10" height="1" alt=" "></td>
<!--  End Right Column -->

</tr>
</table>
<div align="center">
<font color="black" size="-2">
Copyright &copy; 2002<br><a class="footer" href="copyright.php"><b>The art.gnome.org team</b></a>
</font> 
</div>
<p>
</body>
</html>
