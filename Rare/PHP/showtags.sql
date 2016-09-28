# Host: localhost  (Version: 5.5.40)
# Date: 2016-06-23 16:28:21
# Generator: MySQL-Front 5.3  (Build 4.120)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "showtags"
#

DROP TABLE IF EXISTS `showtags`;
CREATE TABLE `showtags` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) DEFAULT NULL COMMENT '标签名',
  `status` varchar(255) DEFAULT '0' COMMENT '状态，0为可用，1为不可用',
  PRIMARY KEY (`Id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='可展示标签';

#
# Data for table "showtags"
#

/*!40000 ALTER TABLE `showtags` DISABLE KEYS */;
INSERT INTO `showtags` VALUES (1,'素描','0'),(2,'速写','1'),(3,'色彩','1'),(4,'儿童','1'),(5,'123213','1');
/*!40000 ALTER TABLE `showtags` ENABLE KEYS */;
