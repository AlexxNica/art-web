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
function do_query($query,$table_name){
	if(mysql_query($query)){
		echo ucwords(strtolower($table_name))."			[OK]<br/>";
	} else {
		die('Failed Creating '.ucwords(strtolower($table_name)).' table!');
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
	$query = "DROP TABLE IF EXISTS `artwork`;
	CREATE TABLE `artwork` (
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
	
	do_query($query,'Artowork');
	
	/**
	 * Category
	 */
	$query = "DROP TABLE IF EXISTS `category`;
	CREATE TABLE `category` (
	  `id` int(11) NOT NULL auto_increment,
	  `parent_id` int(11) default NULL,
	  `name` varchar(255) default NULL,
	  `description` tinytext,
	  `breadcrumb` varchar(100) default NULL,
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;";
	
	do_query($query,'Category');
		
	
	
else:?>
	<div id="notice">
		<h2>Install Process</h2>
		<p>You're about to install the databases necessary to  AGO3!</p>
		<p><a href="?install" alt="install">Proceed &raquo;</a></p>
	</div>
<?endif;?>
</body>
</html>