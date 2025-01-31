/*
 Navicat Premium Data Transfer

 Source Server         : workbench_3308
 Source Server Type    : MySQL
 Source Server Version : 80035 (8.0.35)
 Source Host           : localhost:3308
 Source Schema         : ticketing

 Target Server Type    : MySQL
 Target Server Version : 80035 (8.0.35)
 File Encoding         : 65001

 Date: 31/01/2025 12:16:16
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
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of account
-- ----------------------------
INSERT INTO `account` VALUES (1, 'SYSTEM ADMINISTRATOR', 'ADMINISTRATOR', '0', 'admin@gmail.com', '3b612c75a7b5048a435fb6ec81e52ff92d6d795a8b5a9c17070f6a63c97a53b2', 'YES', '2025-01-10 11:22:37', '2025-01-10 10:13:35', '2025-01-17 10:54:52');

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
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES (1, 'IT DEPARTMENT', 'SYSTEM ADMINISTRATOR', '2025-01-17 10:39:46', '2025-01-17 11:13:02');
INSERT INTO `department` VALUES (2, 'MARKETING', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:14:16', NULL);
INSERT INTO `department` VALUES (3, 'ACCOUNTING', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:15:00', '2025-01-17 11:15:28');
INSERT INTO `department` VALUES (4, 'HUMAN RESOURCE', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:23:09', NULL);
INSERT INTO `department` VALUES (5, 'SALES', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:24:37', NULL);
INSERT INTO `department` VALUES (6, 'OPERATION', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:24:43', NULL);
INSERT INTO `department` VALUES (7, 'RESEARCH AND DEVELOPMENT', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:24:57', NULL);
INSERT INTO `department` VALUES (8, 'CUSTOMER SERVICE', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:25:20', NULL);
INSERT INTO `department` VALUES (9, 'PRODUCTION', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:25:27', NULL);
INSERT INTO `department` VALUES (10, 'JOB ORDER', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:32:19', NULL);
INSERT INTO `department` VALUES (11, 'INVENTORY', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:33:12', NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of position
-- ----------------------------
INSERT INTO `position` VALUES (1, 'NETWORK ADMINISTRATOR', 'SYSTEM ADMINISTRATOR', '2025-01-17 10:41:54', '2025-01-17 11:21:17');
INSERT INTO `position` VALUES (2, 'SR DEVELOPER', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:40:00', NULL);
INSERT INTO `position` VALUES (3, 'JR DEVELOPER', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:40:04', NULL);
INSERT INTO `position` VALUES (4, 'ACCOUNTANT 1', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:40:08', NULL);
INSERT INTO `position` VALUES (5, 'ACCOUNTANT 2', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:40:12', NULL);
INSERT INTO `position` VALUES (6, 'HEAD CHIEF', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:40:16', NULL);
INSERT INTO `position` VALUES (7, 'ASST. CHIEF', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:40:21', NULL);
INSERT INTO `position` VALUES (8, 'SECRETARY - ACCOUNTING', 'SYSTEM ADMINISTRATOR', '2025-01-17 11:40:25', NULL);

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ticket
-- ----------------------------

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
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ticket_feedback
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
