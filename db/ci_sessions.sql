# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-06-13 20:45:59 +0100
# ************************************************************

# Dump of table ci_sessions
# ------------------------------------------------------------

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(16) NOT NULL default '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `session_data` text,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `ci_sessions` (`session_id`,`ip_address`,`user_agent`,`last_activity`,`session_data`) VALUES ('e86a66d87c0ac490c129f1e089dfe29f','127.0.0.1','Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; ','1181669730','a:2:{s:3:\"uid\";s:1:\"1\";s:4:\"auth\";s:3:\"yes\";}');


# Dump of table user
# ------------------------------------------------------------

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
  `token` varchar(255) default NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `user` (`uid`,`username`,`password`,`openid`,`real_name`,`email`,`homepage`,`info`,`timezone`,`location`,`acl`,`status`,`token`) VALUES ('1','pheres','cenas','',NULL,NULL,NULL,NULL,'0',NULL,'79','0','132893999294108586604442064686020442862');


