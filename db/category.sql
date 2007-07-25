# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-25 00:03:58 +0100
# ************************************************************

# Dump of table category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `name` varchar(255) default NULL,
  `description` tinytext,
  `breadcrumb` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;



