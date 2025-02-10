/*
 Navicat Premium Data Transfer

 Source Server         : localhost_wb
 Source Server Type    : MySQL
 Source Server Version : 80200 (8.2.0)
 Source Host           : localhost:3307
 Source Schema         : neo-ticketing

 Target Server Type    : MySQL
 Target Server Version : 80200 (8.2.0)
 File Encoding         : 65001

 Date: 10/02/2025 18:30:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for account
-- ----------------------------
DROP TABLE IF EXISTS `account`;
CREATE TABLE `account`  (
  `account_no` int NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_verified` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `verified_date` datetime NULL DEFAULT NULL,
  `date_added` datetime NULL DEFAULT NULL,
  `date_updated` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`account_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of account
-- ----------------------------
INSERT INTO `account` VALUES (1, 'SYSTEM ADMINISTRATOR', 'ADMINISTRATOR', '0', 'admin@gmail.com', '3b612c75a7b5048a435fb6ec81e52ff92d6d795a8b5a9c17070f6a63c97a53b2', 'YES', '2025-01-10 11:22:37', '2025-01-10 10:13:35', '2025-01-17 10:54:52');
INSERT INTO `account` VALUES (9, 'REYMARK EBIO', '12', '24', 'reymarksamarebio@gmail.com', 'd2b97286496cdd443bd34bba9b8e258613b77ff7fd69f58b03855af6a8b7a897', 'YES', NULL, '2025-02-10 17:37:51', NULL);
INSERT INTO `account` VALUES (10, 'JHON A. MONTEMAYOR', '13', '1', 'john@gmail.com', 'b4b597c714a8f49103da4dab0266af0ee0ae4f8575250a84855c3d76941cd422', 'YES', NULL, '2025-02-10 17:39:49', NULL);
INSERT INTO `account` VALUES (11, 'JEAN A SUMALPONG', '14', '25', 'sumalpongjean06@gmail.com', '4ff17bc8ee5f240c792b8a41bfa2c58af726d83b925cf696af0c811627714c85', 'YES', NULL, '2025-02-10 17:44:56', NULL);

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department`  (
  `department_id` int NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `added_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date_added` datetime NULL DEFAULT NULL,
  `date_updated` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`department_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES (1, 'IT DEPARTMENT', 'SYSTEM ADMINISTRATOR', '2025-01-17 10:39:46', '2025-01-17 11:13:02');
INSERT INTO `department` VALUES (24, 'HR DEPARTMENT', 'SYSTEM ADMINISTRATOR', '2025-02-10 17:36:27', NULL);
INSERT INTO `department` VALUES (25, 'BNB DEPARTMENT', 'SYSTEM ADMINISTRATOR', '2025-02-10 17:42:00', NULL);

-- ----------------------------
-- Table structure for position
-- ----------------------------
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position`  (
  `position_id` int NOT NULL AUTO_INCREMENT,
  `position_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `added_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date_added` datetime NULL DEFAULT NULL,
  `date_updated` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`position_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of position
-- ----------------------------
INSERT INTO `position` VALUES (12, 'MANAGER', 'SYSTEM ADMINISTRATOR', '2025-02-10 17:37:15', NULL);
INSERT INTO `position` VALUES (13, 'TECHNICIAN', 'SYSTEM ADMINISTRATOR', '2025-02-10 17:38:59', NULL);
INSERT INTO `position` VALUES (14, 'ACCOUNTANT', 'SYSTEM ADMINISTRATOR', '2025-02-10 17:41:39', NULL);

-- ----------------------------
-- Table structure for ticket
-- ----------------------------
DROP TABLE IF EXISTS `ticket`;
CREATE TABLE `ticket`  (
  `ticket_no` int NOT NULL AUTO_INCREMENT,
  `ticket_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `request_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `priority_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `request_date` datetime NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `assigned_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `updated_date` datetime NULL DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `feedback` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'NO',
  PRIMARY KEY (`ticket_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ticket
-- ----------------------------
INSERT INTO `ticket` VALUES (1, '1157391409', 'd', 'REYMARK ', 'HIGN', '2025-02-07 11:57:39', 'DONE', '8', '2025-02-07 12:02:39', 'Software Issues', 'YES');
INSERT INTO `ticket` VALUES (2, '1742125033', 'guba among prenter', 'REYMARK EBIO', 'HIGN', '2025-02-10 17:42:12', 'DONE', '10', '2025-02-10 17:48:42', 'Hardware Issues', 'YES');
INSERT INTO `ticket` VALUES (3, '1746265900', 'guba ang printer', 'JEAN A SUMALPONG', 'HIGN', '2025-02-10 17:46:26', 'DONE', '10', '2025-02-10 17:51:40', 'Hardware Issues', 'YES');
INSERT INTO `ticket` VALUES (4, '1749513184', 'need mi ug assest sa among connection', 'REYMARK EBIO', 'HIGN', '2025-02-10 17:49:51', 'DONE', '10', '2025-02-10 17:54:12', 'Network Issues', 'YES');
INSERT INTO `ticket` VALUES (5, '1807225402', 'Guba ang mouse', 'JEAN A SUMALPONG', 'HIGN', '2025-02-10 18:07:22', 'ACCEPTED', '10', '2025-02-10 18:08:53', 'Hardware Issues', 'NO');

-- ----------------------------
-- Table structure for ticket_feedback
-- ----------------------------
DROP TABLE IF EXISTS `ticket_feedback`;
CREATE TABLE `ticket_feedback`  (
  `ticket_fb_no` int NOT NULL AUTO_INCREMENT,
  `ticket_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `rating` int NULL DEFAULT NULL,
  `it_assigned` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `date_added` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ticket_fb_no`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of ticket_feedback
-- ----------------------------
INSERT INTO `ticket_feedback` VALUES (1, '1157391409', 'okay si sir', 4, '8', '2025-02-07 12:02:39');
INSERT INTO `ticket_feedback` VALUES (2, '1742125033', 'salamat sa enyong serbesyong totoo', 4, '10', '2025-02-10 17:48:42');
INSERT INTO `ticket_feedback` VALUES (3, '1746265900', 'way ayo', 2, '10', '2025-02-10 17:51:40');
INSERT INTO `ticket_feedback` VALUES (4, '1749513184', 'way klaro mag ayo si  jhonny', 0, '10', '2025-02-10 17:54:12');

SET FOREIGN_KEY_CHECKS = 1;
