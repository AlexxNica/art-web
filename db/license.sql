# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-25 00:05:31 +0100
# ************************************************************

# Dump of table license
# ------------------------------------------------------------

DROP TABLE IF EXISTS `license`;

CREATE TABLE `license` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `summary` text,
  `link` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;



