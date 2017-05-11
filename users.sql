git /*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50717
Source Host           : localhost:3306
Source Database       : hotel

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-05-09 15:20:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '酒店登陆账号',
  `name` varchar(48) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '酒店名称',
  `password` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `province_id` mediumint(8) unsigned DEFAULT '1',
  `city_id` mediumint(8) unsigned DEFAULT '1' COMMENT '城市id',
  `area_id` mediumint(8) unsigned DEFAULT '1' COMMENT '区域id',
  `area_info` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '详细地址',
  `phone` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '联系电话',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '上级id',
  `level` tinyint(1) unsigned DEFAULT '1' COMMENT '1加盟店 2分店',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `h_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10003 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('2', 'lkd0769@126.com', 'kk', '$2y$10$KVoEEARt8/MH1wqiWmI0.eqzXMgMH/.nlDL11FnbqV9W5lIyCd6AC', null, null, '0', null, null, '1', '1', 'dEDrkFCcx593wcbWLR6Uih98x5j6vs7eGHkQh25m8J6AdctWHG2xgdDFnXz4', '2017-05-04 09:36:40', '2017-05-04 09:36:40');
INSERT INTO `users` VALUES ('3', 'sevenHotel', '7天连锁酒店', '$2y$10$HodL8PyBPJ40aoqYqRHKQes98VRHm4gxbFr1M.eYomE8OcCvjQwmu', '1', '1', '1', null, null, '0', '1', null, '2017-05-05 01:13:28', '2017-05-05 01:13:28');
INSERT INTO `users` VALUES ('10000', '515961601@qq.com', 'krlee', '$2y$10$KVoEEARt8/MH1wqiWmI0.eqzXMgMH/.nlDL11FnbqV9W5lIyCd6AC', '1', '1', '1', '131', '222', '0', '1', 'fb1r4Ed04v5KXah73aQtpuIMofZcBAGpnv4SqcGexf6HWdPjhWVWlaWQ2u8E', '2017-05-05 17:21:14', '2017-05-05 17:21:14');
INSERT INTO `users` VALUES ('10001', 'eight1', '8天3123', '$2y$10$vTAaBA13xvVgE8utrJ1AZeKZZIdirYkQxJWOs6D65AdMyB7IPhQ9G', '19', '305', '5044', '广东 东莞市 樟木头镇 23123', '13172166171', '0', '1', null, '2017-05-05 15:15:24', '2017-05-05 15:15:24');
INSERT INTO `users` VALUES ('10002', 'eight', '8天3123', '$2y$10$agbfh2P3/X0gPXMq25EgCepnem.RsSFq4px2GGWGxJ9I8eR62aMNO', '19', '305', '5044', '广东 东莞市 樟木头镇 23123', '13172166171', '10000', '2', null, '2017-05-05 15:15:51', '2017-05-05 07:15:51');
