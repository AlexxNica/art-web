# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-25 00:06:54 +0100
# ************************************************************

# Dump of table vote
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vote`;

CREATE TABLE `vote` (
  `id` int(11) NOT NULL auto_increment,
  `artwork_id` int(11) default NULL,
  `vote` int(11) default NULL,
  `user_id` int(11) default NULL,
  `kind` int(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;



