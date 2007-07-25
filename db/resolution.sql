# CocoaMySQL dump
# Version 0.7b4
# http://cocoamysql.sourceforge.net
#
# Host: localhost (MySQL 5.0.24)
# Database: artweb
# Generation Time: 2007-07-25 00:06:31 +0100
# ************************************************************

# Dump of table resolution
# ------------------------------------------------------------

DROP TABLE IF EXISTS `resolution`;

CREATE TABLE `resolution` (
  `id` int(11) NOT NULL auto_increment,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('1','800','600');
INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('3','1280','1024');
INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('2','1024','768');
INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('4','1440','900');
INSERT INTO `resolution` (`id`,`width`,`height`) VALUES ('5','1600','1200');


