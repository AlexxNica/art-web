<?xml version="1.0"?>
<rss version="2.0" xmlns="http://backend.userland.com/rss2">
	<channel>
   	<title>art.gnome.org releases</title>
      <link>http://art.gnome.org/</link>
      <description>A list of recent backgrounds and themes released on art.gnome.org</description>
      <webmaster>aldug@gnome.org</webmaster>
      <?php
      	require("mysql.inc.php");
         require("common.inc.php");
         $num_updates = 12;
         $updates_array = get_updates_array($num_updates);
         for($count=0;$count<$num_updates;$count++)
         {
         	print("\t\t<item>\n");
            list($add_timestamp,$type,$ID) = explode("|",$updates_array[$count]);
	         if($type == "background")
            {
   	         $background_select_result = mysql_query("SELECT background_name, category, author,thumbnail_filename FROM background WHERE backgroundID='$ID'");
   	         list($background_name,$category,$author,$thumbnail_filename) = mysql_fetch_row($background_select_result);
               print("\t\t\t<title>$background_name</title>\n");
               print("\t\t\t<link>http://art.gnome.org/show_background.php?backgroundID=$ID&category=$category</link>\n");
               print("\t\t\t<description>Backgrounds - $category</description>\n");
            }
            else
            {
   	         $theme_select_result = mysql_query("SELECT theme_name, category, author, small_thumbnail_filename FROM theme WHERE themeID='$ID'");
   	         list($theme_name,$category,$author,$thumbnail_filename) = mysql_fetch_row($theme_select_result);
               $category_good = $linkbar["themes_" . $category]["alt"];
               print("\t\t\t<title>$theme_name</title>\n");
               print("\t\t\t<link>http://art.gnome.org/show_theme.php?themeID=$ID&category=$category</link>\n");
               print("\t\t\t<description>Themes - $category</description>\n");
            }
            print("\t\t</item>\n");
      	}
      ?>
   </channel>
</rss>
