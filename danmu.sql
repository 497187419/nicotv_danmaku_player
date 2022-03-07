/*
Navicat MySQL Data Transfer

Source Server         : 本地：Mysql5.7
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : danmu

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2021-08-25 10:09:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `lk_bangumi`
-- ----------------------------
DROP TABLE IF EXISTS `lk_bangumi`;
CREATE TABLE `lk_bangumi` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '番剧对照表',
  `old_name` varchar(200) DEFAULT NULL COMMENT '传来的名称',
  `url` varchar(500) DEFAULT NULL COMMENT '对应弹幕视频地址',
  `is_enable` tinyint(1) DEFAULT '1',
  `is_show` tinyint(1) DEFAULT '1',
  `is_checked` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lk_bangumi
-- ----------------------------
INSERT INTO `lk_bangumi` VALUES ('1', '关于我转生后成为史莱姆的那件事 第二季', '', '1', '1', '1');

-- ----------------------------
-- Table structure for `lk_bangumi_key_index`
-- ----------------------------
DROP TABLE IF EXISTS `lk_bangumi_key_index`;
CREATE TABLE `lk_bangumi_key_index` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '集数名称对照表',
  `bangumi_id` int(11) DEFAULT NULL COMMENT '番剧表外键',
  `old_key` varchar(100) DEFAULT NULL COMMENT '传来集数名称',
  `new_key` varchar(100) DEFAULT NULL COMMENT '对应集数名称',
  `is_enable` tinyint(1) DEFAULT '1',
  `is_show` tinyint(1) DEFAULT '1',
  `is_checked` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lk_bangumi_key_index
-- ----------------------------

-- ----------------------------
-- Table structure for `lk_bangumi_name_map`
-- ----------------------------
DROP TABLE IF EXISTS `lk_bangumi_name_map`;
CREATE TABLE `lk_bangumi_name_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '番剧名称映射表',
  `bangumi_id` int(11) NOT NULL COMMENT '外键番剧id',
  `name` varchar(200) DEFAULT NULL COMMENT '调整的名称',
  `is_enable` tinyint(1) DEFAULT '1',
  `is_show` tinyint(1) DEFAULT '1',
  `is_checked` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lk_bangumi_name_map
-- ----------------------------

-- ----------------------------
-- Table structure for `lk_guests_voice`
-- ----------------------------
DROP TABLE IF EXISTS `lk_guests_voice`;
CREATE TABLE `lk_guests_voice` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '建议或意见表',
  `mark` varchar(1000) DEFAULT NULL COMMENT '留言',
  `bangumi_name` varchar(100) DEFAULT NULL COMMENT '番剧名称',
  `num` varchar(100) DEFAULT NULL COMMENT '集数',
  `add_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of lk_guests_voice
-- ----------------------------
