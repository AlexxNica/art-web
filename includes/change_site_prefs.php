<?php

/* change the number of thumbnails on the background and theme list page */
if($change_thumbnails_per_page)
{
	if(in_array($thumbs_per_page,$thumbnails_per_page_array))
   {
   	$thumbnails_per_page = $thumbs_per_page;
   }
	ago_redirect($referrer);
   exit(0);	
}

/* change the site theme */
if($change_site_theme)
{
	if($new_site_theme == "slick" || $new_site_theme == "lite")
   {
   	$site_theme = $new_site_theme;
   }
   $referrer = $HTTP_REFERER;
   ago_redirect($referrer);
   exit(0);
}

?>
