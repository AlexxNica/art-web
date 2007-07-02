# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-02 23:01:59 +0100
# ************************************************************

# Dump of table lost_password
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lost_password`;

CREATE TABLE `lost_password` (
  `user_id` int(11) default NULL,
  `reset_key` varchar(255) default NULL,
  `requested_at` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



