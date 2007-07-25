# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-25 00:06:07 +0100
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



# Dump of table ci_sessions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(16) NOT NULL default '0',
  `user_agent` varchar(50) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `session_data` text,
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



# Dump of table download
# ------------------------------------------------------------

DROP TABLE IF EXISTS `download`;

CREATE TABLE `download` (
  `id` int(11) NOT NULL auto_increment,
  `artwork_id` int(11) default NULL,
  `file` varchar(255) default NULL,
  `download_count` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;



# Dump of table download_resolution
# ------------------------------------------------------------

DROP TABLE IF EXISTS `download_resolution`;

CREATE TABLE `download_resolution` (
  `download_id` int(11) default NULL,
  `resolution_id` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



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



# Dump of table lost_password
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lost_password`;

CREATE TABLE `lost_password` (
  `user_id` int(11) default NULL,
  `reset_key` varchar(255) default NULL,
  `requested_at` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table moderation_queue
# ------------------------------------------------------------

DROP TABLE IF EXISTS `moderation_queue`;

CREATE TABLE `moderation_queue` (
  `id` int(11) NOT NULL auto_increment,
  `artwork_id` int(11) default NULL,
  `date_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;



# Dump of table resolution
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resolution`;

CREATE TABLE `resolution` (
  `id` int(11) NOT NULL auto_increment,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;



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



