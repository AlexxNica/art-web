# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-06-08 01:01:19 +0100
# ************************************************************

# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `uid` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(255) default NULL,
  `password` varchar(255) default NULL,
  `openid` varchar(255) default NULL,
  `real_name` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `homepage` varchar(255) default NULL,
  `info` text,
  `timezone` int(11) default NULL,
  `location` varchar(11) default NULL,
  `acl` int(11) unsigned default '0',
  `status` int(4) default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


