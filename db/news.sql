# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-09-12 22:40:19 +0100
# ************************************************************

# Dump of table news
# ------------------------------------------------------------

CREATE TABLE `news` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) default NULL,
  `title` varchar(255) default NULL,
  `body` text,
  `user_id` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



