# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-02 23:02:14 +0100
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
  `timezone` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `acl` int(11) unsigned default '0',
  `status` int(4) default NULL,
  `token` varchar(255) default NULL,
  `activation_code` varchar(255) default NULL,
  `activated_at` datetime default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;



