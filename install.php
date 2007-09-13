<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<title>AGO install script</title>
	<style type="text/css" media="screen">
		body{
			font-family: "Verdana","Arial",serif;
			font-size: 11px;
			background: #333;
			color: #fdfdfd;
		}
	</style>
</head>
<body id="install" onload="">
<?php
function do_query($query,$table_name=null){
	if(mysql_query($query)){
		if ($table_name)
			echo ucwords(strtolower($table_name))."			[OK]<br/>";
	} else {
		die('Failed Creating '.ucwords(strtolower($table_name)).' table!<br/><br/>'.mysql_error());
	}
}

if (isset($_GET['install'])):
	echo "<p>Installing DB Tables...</p>";
	define('BASEPATH','');
	include_once('system/application/config/database.php');
	
	$mysql = $db['default'];
	if ($mysql['hostname']=='localhost') die('<h1><blink>Don\'t even think in running this in here!!!</blink></h1>');

	
	echo "Connecting to MySQL";
	mysql_connect($mysql['hostname'], $mysql['username'], $mysql['password']) or die(mysql_error());
	echo " [OK]<br/>";
	
	echo "Connecting to Database";
	mysql_select_db($mysql['database']) or die(mysql_error());
	echo " [OK]<br/>";
	
	echo "<br/>";
	/***/
	echo "Creating Tables:<br/><br/>";
	
	/**
	 * Artwork
	 */
	$query = "DROP TABLE IF EXISTS `artwork`";
	do_query($query,'Drop Artwork Table');
	
	$query = "CREATE TABLE `artwork` (
	  `id` int(11) NOT NULL auto_increment,
	  `user_id` int(11) default NULL,
	  `category_id` int(11) default NULL,
	  `license_id` int(11) default NULL,
	  `version` varchar(50) default NULL,
	  `name` varchar(255) default NULL,
	  `description` text,
	  `state` int(4) default NULL,
	  `date_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  `date_accepted` timestamp NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;";
	
	do_query($query,'Artwork');
	
	/**
	 * Category
	 */
	$query = "DROP TABLE IF EXISTS `category`";
	do_query($query,'Drop Category Table');
	
	$query = "CREATE TABLE `category` (
	  `id` int(11) NOT NULL auto_increment,
	  `parent_id` int(11) default NULL,
	  `name` varchar(255) default NULL,
	  `description` tinytext,
	  `breadcrumb` varchar(100) default NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;";
	
	do_query($query,'Category');
	
	echo "<strong>Adding Categories:</strong><br/>";
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('1',NULL,'Backgrounds','The terms wallpaper and desktop picture refer to an image used as a background on a computer screen, usually for the desktop of a graphical user interface.','backgrounds');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('2',NULL,'Themes','A theme is a preset package containing graphical appearance details, used to customise the look and feel of (typically) an operating system, widget set or window manager.\r\rGraphics themes for individual applications are often referred to as skins, and the','themes');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('3',NULL,'Screenshot','A screenshot is an image taken by the computer to record the visible items displayed on the monitor or another visual output device.','screenshots');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('8','1','GNOME','Gnome related backgrounds','gnome');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('5','1','Nature','Nature related backgrounds','nature');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('6','1','Abstract','Hmm, well abstract-related ?_? backgrounds','abstract');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('7','1','Misc','For those which don\'t fit in any of the other categories','misc');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('4',NULL,'Contests','Let you creative side come out','contests');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('9','2','Applications (gtk+)','Applications(gtk+)','gtk2');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('10','2','Window Borders (metacity)','Window Borders (metacity)','metacity');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('11','2','Icon','Icons','icon');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('12','2','Login Manager (gdm)','Login Manager (gdm)','gdm');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('13','2','Splash Screen','Splash Screen','splash');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('14','3','GNOME 2.10','GNOME 2.10','gnome210');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('15','3','GNOME 2.11','GNOME 2.11','gnome211');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('16','3','GNOME 2.12','GNOME 2.12','gnome212');");
	do_query("INSERT INTO `category` (`id`,`parent_id`,`name`,`description`,`breadcrumb`) VALUES ('17','3','GNOME 2.18','GNOME 2.18','gnome218');");
	
	/**
	 * Ci_Sessions
	 */
	$query = "DROP TABLE IF EXISTS `ci_sessions`;";
	do_query($query,'Drop CI_Sessions Table');
	
	$query = "CREATE TABLE `ci_sessions` (
	  `session_id` varchar(40) NOT NULL default '0',
	  `ip_address` varchar(16) NOT NULL default '0',
	  `user_agent` varchar(50) NOT NULL,
	  `last_activity` int(10) unsigned NOT NULL default '0',
	  `session_data` text,
	  PRIMARY KEY  (`session_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
	
	do_query($query,'CI_Sessions');
	
	/**
	 * Downloads
	 */	
	$query = "DROP TABLE IF EXISTS `download`;";
	do_query($query,'Drop Download Table');
	
	$query = "CREATE TABLE `download` (
	  `id` int(11) NOT NULL auto_increment,
	  `artwork_id` int(11) default NULL,
	  `file` varchar(255) default NULL,
	  `download_count` int(11) default NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;";
	
	do_query($query,'Download');
	
	/**
	 * Download_Resolutions
	 */
	$query = "DROP TABLE IF EXISTS `download_resolution`;";
	do_query($query,"Drop Download_Resolution Table");
	
	$query = "CREATE TABLE `download_resolution` (
	  `download_id` int(11) default NULL,
	  `resolution_id` int(11) default NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	do_query($query,"Download_Resolution");
	
	/**
	 * License
	 */
	
	$query = "DROP TABLE IF EXISTS `license`;";
	do_query($query,"Drop License Table");
	
	$query = "CREATE TABLE `license` (
	  `id` int(11) NOT NULL auto_increment,
	  `name` varchar(255) default NULL,
	  `summary` text,
	  `link` varchar(255) default NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;";
	
	do_query($query,"License");
	
	/**
	 * Lost_Password
	 */
	
	$query = "DROP TABLE IF EXISTS `lost_password`;";
	do_query($query,"Drop Lost_Password Table");
	
	$query = "CREATE TABLE `lost_password` (
	  `user_id` int(11) default NULL,
	  `reset_key` varchar(255) default NULL,
	  `requested_at` datetime default NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	
	do_query($query,"Lost_Password");
	
	/**
	 * Moderation Queue
	 */
	
	$query = "DROP TABLE IF EXISTS `moderation_queue`;";
	do_query($query,"Drop Moderation_Queue Table");
	
	$query = "CREATE TABLE `moderation_queue` (
	  `id` int(11) NOT NULL auto_increment,
	  `artwork_id` int(11) default NULL,
	  `date_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;";
	
	do_query($query,"Moderation_Queue");
	
	/**
	 * Resolution
	 */
	
	$query = "DROP TABLE IF EXISTS `resolution`;";
	do_query($query,"Drop Resolution Table");
	
	$query = "CREATE TABLE `resolution` (
	  `id` int(11) NOT NULL auto_increment,
	  `width` int(11) default NULL,
	  `height` int(11) default NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;";
	
	do_query($query,"Resolution");

	do_query("INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('1','800','600');","Added Resolution: 800x600");
	do_query("INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('2','1024','768');","Added Resolution: 1024x768");
	do_query("INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('3','1280','1024');","Added Resolution: 1280x1024");
	do_query("INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('4','1440','900');","Added Resolution: 1440x900");
	do_query("INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('5','1600','1200');","Added Resolution: 1600x1200");
	
	/**
	 * User
	 */
	
	$query = "DROP TABLE IF EXISTS `user`;";
	do_query($query,"Drop User Table");
	
	$query = "CREATE TABLE `user` (
	  `uid` int(11) unsigned NOT NULL auto_increment,
	  `username` varchar(255) default NULL,
	  `password` varchar(255) default NULL,
	  `openid` varchar(255) default NULL,
	  `real_name` varchar(255) default NULL,
	  `email` varchar(255) default NULL,
	  `homepage` varchar(255) default NULL,
	  `info` text,
	  `timezone` varchar(255) default NULL,
	  `country` varchar(255) default NULL,
	  `acl` int(11) unsigned default '0',
	  `status` int(4) default NULL,
	  `token` varchar(255) default NULL,
	  `activation_code` varchar(255) default NULL,
	  `activated_at` datetime default NULL,
	  PRIMARY KEY  (`uid`)
	) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;";
	
	do_query($query,"User");
	
	
	echo "<br/>Adding user <strong>admin@ago.com</strong> with password: <strong>teste</strong><br/><br/>";
	
	do_query("INSERT INTO `user` (`uid`,`username`,`password`,`openid`,`real_name`,`email`,`homepage`,`info`,`timezone`,`country`,`acl`,`status`,`token`,`activation_code`,`activated_at`) VALUES ('1','admin','698dc19d489c4e4db73e28a713eab07b','','Admin','admin@ago.com',NULL,NULL,'0',NULL,'65535','0','_',NULL,'2007-07-02 23:53:14');");
	
	/**
	 * Vote
	 */
	
	$query = "DROP TABLE IF EXISTS `vote`;";
	do_query($query,"Drop Vote Table");
	
	$query = "CREATE TABLE `vote` (
	  `id` int(11) NOT NULL auto_increment,
	  `artwork_id` int(11) default NULL,
	  `vote` int(11) default NULL,
	  `user_id` int(11) default NULL,
	  `kind` int(4) default NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;";
	
	do_query($query,"Vote");
	
	/**
	 * Version
	 */
	
	$query = "DROP TABLE IF EXISTS `version`;";
	do_query($query,"Drop Version Table");
	
	$query = "CREATE TABLE `version` (
	  `artwork_id` int(11) default NULL,
	  `path` varchar(255) default NULL,
	  `tree_id` int(11) default NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	do_query($query,"Version");
	
	/**
	 * News
	 */
	$query = "DROP TABLE IF EXISTS `news`;";
	do_query($query,"Drop News Table");
	
	$query = "CREATE TABLE `news` (
	  `id` int(11) NOT NULL auto_increment,
	  `date` int(11) default NULL,
	  `title` varchar(255) default NULL,
	  `body` text,
	  `user_id` int(11) default NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
	do_query($query,"News");
	
	
	echo "<br/><br/><h3>Success</h3>";
else:?>
	<div id="notice">
		<h2>Install Process</h2>
		<p>You're about to install the databases necessary to  AGO3!</p>
		<p><a href="?install" alt="install">Proceed &raquo;</a></p>
	</div>
<?endif;?>
</body>
</html>