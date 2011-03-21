/*
Navicat MySQL Data Transfer

Source Server         : Rumah
Source Server Version : 50508
Source Host           : localhost:3306
Source Database       : zhopie

Target Server Type    : MYSQL
Target Server Version : 50508
File Encoding         : 65001

Date: 2011-03-21 11:41:09
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `tb_wishes_product`
-- ----------------------------
DROP TABLE IF EXISTS `tb_wishes_product`;
CREATE TABLE `tb_wishes_product` (
  `id_product` varchar(30) NOT NULL DEFAULT '',
  `count_wishes_product` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of tb_wishes_product
-- ----------------------------
