# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-25 00:06:07 +0100
# ************************************************************

# Dump of table moderation_queue
# ------------------------------------------------------------

DROP TABLE IF EXISTS `moderation_queue`;

CREATE TABLE `moderation_queue` (
  `id` int(11) NOT NULL auto_increment,
  `artwork_id` int(11) default NULL,
  `date_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;



