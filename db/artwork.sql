# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-25 00:03:32 +0100
# ************************************************************

# Dump of table artwork
# ------------------------------------------------------------

DROP TABLE IF EXISTS `artwork`;

CREATE TABLE `artwork` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) default NULL,
  `category_id` int(11) default NULL,
  `license_id` int(11) default NULL,
  `original_id` int(11) default NULL,
  `version` varchar(50) default NULL,
  `name` varchar(255) default NULL,
  `description` text,
  `download_id` int(11) default NULL,
  `state` int(4) default NULL,
  `date_added` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;



