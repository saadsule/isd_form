/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100121
 Source Host           : localhost:3306
 Source Schema         : isd_form

 Target Server Type    : MySQL
 Target Server Version : 100121
 File Encoding         : 65001

 Date: 15/02/2026 13:13:03
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for child_health_detail
-- ----------------------------
DROP TABLE IF EXISTS `child_health_detail`;
CREATE TABLE `child_health_detail`  (
  `detail_id` int NOT NULL AUTO_INCREMENT,
  `master_id` int NULL DEFAULT NULL,
  `question_id` int NULL DEFAULT NULL,
  `option_id` int NULL DEFAULT NULL,
  `answer` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`detail_id`) USING BTREE,
  INDEX `master_id`(`master_id`) USING BTREE,
  INDEX `question_id`(`question_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 214 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of child_health_detail
-- ----------------------------
INSERT INTO `child_health_detail` VALUES (1, 1, 1, NULL, '2', '2026-02-13 11:32:53');
INSERT INTO `child_health_detail` VALUES (2, 1, 2, NULL, '4', '2026-02-13 11:32:53');
INSERT INTO `child_health_detail` VALUES (3, 1, 3, 6, '17.2.2.  Vaccine not due', '2026-02-13 11:32:53');
INSERT INTO `child_health_detail` VALUES (4, 1, 4, 11, 'Religious Refusal', '2026-02-13 11:32:53');
INSERT INTO `child_health_detail` VALUES (5, 1, 5, 15, 'PENTA III', '2026-02-13 11:32:53');
INSERT INTO `child_health_detail` VALUES (6, 1, 5, 16, 'MR I', '2026-02-13 11:32:53');
INSERT INTO `child_health_detail` VALUES (7, 1, 11, 65, 'No', '2026-02-13 11:32:53');
INSERT INTO `child_health_detail` VALUES (8, 1, 13, NULL, '72', '2026-02-13 11:32:53');
INSERT INTO `child_health_detail` VALUES (26, 2, 2, NULL, '4', '2026-02-14 00:10:01');
INSERT INTO `child_health_detail` VALUES (27, 2, 3, 5, '17.2.1.  Fully immunized as per Age', '2026-02-14 00:10:01');
INSERT INTO `child_health_detail` VALUES (28, 2, 3, 6, '17.2.2.  Vaccine not due', '2026-02-14 00:10:01');
INSERT INTO `child_health_detail` VALUES (29, 2, 12, NULL, '67', '2026-02-14 00:10:01');
INSERT INTO `child_health_detail` VALUES (30, 2, 13, NULL, '72', '2026-02-14 00:10:01');
INSERT INTO `child_health_detail` VALUES (31, 2, 15, 76, 'No', '2026-02-14 00:10:01');
INSERT INTO `child_health_detail` VALUES (32, 2, 16, 78, 'No', '2026-02-14 00:10:01');
INSERT INTO `child_health_detail` VALUES (33, 2, 17, 80, 'No', '2026-02-14 00:10:01');
INSERT INTO `child_health_detail` VALUES (60, 3, 4, 9, 'Demand Refusal', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (61, 3, 4, 10, 'Misconception Refusal ', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (62, 3, 4, 11, 'Religious Refusal', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (63, 3, 5, 28, 'ROTA II', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (64, 3, 5, 29, 'IPV I', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (65, 3, 6, 30, 'PENTA I', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (66, 3, 6, 31, 'PENTA II', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (67, 3, 6, 32, 'PENTA III', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (68, 3, 6, 33, 'MR I', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (69, 3, 6, 34, 'MR II', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (70, 3, 8, 57, 'OPV', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (71, 3, 12, NULL, '67', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (72, 3, 13, NULL, '72', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (73, 3, 15, 75, 'Yes', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (74, 3, 15, 76, 'No', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (75, 3, 16, 77, 'Yes', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (76, 3, 16, 78, 'No', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (77, 3, 17, 79, 'Yes', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (78, 3, 17, 80, 'No', '2026-02-14 00:17:08');
INSERT INTO `child_health_detail` VALUES (79, 4, 6, 30, 'PENTA I', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (80, 4, 6, 31, 'PENTA II', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (81, 4, 6, 32, 'PENTA III', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (82, 4, 6, 33, 'MR I', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (83, 4, 6, 34, 'MR II', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (84, 4, 6, 35, 'PCV I', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (85, 4, 15, 75, 'Yes', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (86, 4, 15, 76, 'No', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (87, 4, 16, 77, 'Yes', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (88, 4, 16, 78, 'No', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (89, 4, 17, 80, 'No', '2026-02-14 00:35:11');
INSERT INTO `child_health_detail` VALUES (100, 5, 3, 5, '17.2.1.  Fully immunized as per Age', '2026-02-14 14:20:12');
INSERT INTO `child_health_detail` VALUES (101, 5, 3, 6, '17.2.2.  Vaccine not due', '2026-02-14 14:20:12');
INSERT INTO `child_health_detail` VALUES (102, 5, 3, 7, '17.2.3.  Child is unwell', '2026-02-14 14:20:12');
INSERT INTO `child_health_detail` VALUES (103, 5, 3, 8, '17.2.4.  Refusal', '2026-02-14 14:20:12');
INSERT INTO `child_health_detail` VALUES (104, 5, 17, 80, 'No', '2026-02-14 14:20:12');
INSERT INTO `child_health_detail` VALUES (114, 6, 3, 7, '17.2.3.  Child is unwell', '2026-02-14 21:32:24');
INSERT INTO `child_health_detail` VALUES (115, 6, 3, 8, '17.2.4.  Refusal', '2026-02-14 21:32:24');
INSERT INTO `child_health_detail` VALUES (116, 6, 15, 76, 'No', '2026-02-14 21:32:24');
INSERT INTO `child_health_detail` VALUES (117, 6, 16, 78, 'No', '2026-02-14 21:32:24');
INSERT INTO `child_health_detail` VALUES (118, 6, 17, 80, 'No', '2026-02-14 21:32:24');
INSERT INTO `child_health_detail` VALUES (121, 7, 1, NULL, '1', '2026-02-14 22:34:33');
INSERT INTO `child_health_detail` VALUES (122, 7, 2, NULL, '4', '2026-02-14 22:34:33');
INSERT INTO `child_health_detail` VALUES (123, 8, 1, NULL, '2', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (124, 8, 2, NULL, '4', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (125, 8, 3, 5, '17.2.1.  Fully immunized as per Age', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (126, 8, 3, 6, '17.2.2.  Vaccine not due', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (127, 8, 3, 7, '17.2.3.  Child is unwell', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (128, 8, 3, 8, '17.2.4.  Refusal', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (129, 8, 5, 20, 'PCV III', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (130, 8, 5, 21, 'IPV II', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (131, 8, 5, 22, 'Hep', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (132, 8, 6, 34, 'MR II', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (133, 8, 6, 35, 'PCV I', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (134, 8, 7, 56, 'TCV', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (135, 8, 8, 59, 'Measles', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (136, 8, 9, 61, 'No', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (137, 8, 10, 63, 'No', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (138, 8, 11, 65, 'No', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (139, 8, 12, NULL, '66', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (140, 8, 13, NULL, '68', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (141, 8, 15, 75, 'Yes', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (142, 8, 16, 78, 'No', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (143, 8, 17, 80, 'No', '2026-02-15 11:15:56');
INSERT INTO `child_health_detail` VALUES (144, 9, 1, NULL, '2', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (145, 9, 2, NULL, '4', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (146, 9, 3, 8, '17.2.4.  Refusal', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (147, 9, 4, 9, 'Demand Refusal', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (148, 9, 4, 11, 'Religious Refusal', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (149, 9, 5, 17, 'OPV 0', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (150, 9, 5, 18, 'PCV I', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (151, 9, 5, 19, 'PCV II', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (152, 9, 5, 20, 'PCV III', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (153, 9, 5, 21, 'IPV II', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (154, 9, 6, 31, 'PENTA II', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (155, 9, 6, 32, 'PENTA III', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (156, 9, 7, 47, 'OPV III', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (157, 9, 8, 58, 'IPV', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (158, 9, 10, 62, 'Yes', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (159, 9, 12, NULL, '67', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (160, 9, 13, NULL, '70', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (161, 9, 15, 76, 'No', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (162, 9, 16, 78, 'No', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (163, 9, 17, 80, 'No', '2026-02-15 11:38:28');
INSERT INTO `child_health_detail` VALUES (164, 11, 1, 2, 'No', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (165, 11, 2, 4, 'No', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (166, 11, 3, 5, '17.2.1.  Fully immunized as per Age', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (167, 11, 3, 6, '17.2.2.  Vaccine not due', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (168, 11, 5, 15, 'PENTA III', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (169, 11, 5, 16, 'MR I', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (170, 11, 8, 59, 'Measles', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (171, 11, 9, 60, 'Yes', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (172, 11, 10, 63, 'No', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (173, 11, 12, 67, 'No', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (174, 11, 13, 68, '1st', '2026-02-15 11:51:19');
INSERT INTO `child_health_detail` VALUES (192, 12, 1, 1, 'Yes', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (193, 12, 2, 4, 'No', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (194, 12, 3, 6, '17.2.2.  Vaccine not due', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (195, 12, 4, 10, 'Misconception Refusal ', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (196, 12, 4, 11, 'Religious Refusal', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (197, 12, 5, 12, 'BCG', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (198, 12, 5, 13, 'PENTA I', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (199, 12, 5, 16, 'MR I', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (200, 12, 5, 29, 'IPV I', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (201, 12, 6, 43, 'ROTA I', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (202, 12, 6, 44, 'ROTA II', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (203, 12, 7, 45, 'OPV I', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (204, 12, 7, 56, 'TCV', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (205, 12, 8, 58, 'IPV', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (206, 12, 9, 60, 'Yes', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (207, 12, 10, 62, 'Yes', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (208, 12, 11, 65, 'No', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (209, 12, 12, 67, 'No', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (210, 12, 13, 70, '3rd', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (211, 12, 15, 75, 'Yes', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (212, 12, 16, 78, 'No', '2026-02-15 11:56:55');
INSERT INTO `child_health_detail` VALUES (213, 12, 17, 80, 'No', '2026-02-15 11:56:55');

-- ----------------------------
-- Table structure for child_health_master
-- ----------------------------
DROP TABLE IF EXISTS `child_health_master`;
CREATE TABLE `child_health_master`  (
  `master_id` int NOT NULL AUTO_INCREMENT,
  `form_date` date NULL DEFAULT NULL,
  `qr_code` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `client_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `district` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `uc` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `facility_id` int NULL DEFAULT NULL,
  `village` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vaccinator_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `patient_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `guardian_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dob` date NULL DEFAULT NULL,
  `age_year` int NULL DEFAULT NULL,
  `age_month` int NULL DEFAULT NULL,
  `age_day` int NULL DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `marital_status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pregnancy_status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `disability` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `play_learning_kit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nutrition_package` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visit_type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `age_group` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`master_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of child_health_master
-- ----------------------------
INSERT INTO `child_health_master` VALUES (1, '2026-02-20', '1231', 'New', '94', '6', 1, 'Bannu', 'No', 'Shagufta', 'Khan', '1993-02-10', NULL, NULL, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, '2026-02-13 11:32:53', 'Outreach', 1, NULL);
INSERT INTO `child_health_master` VALUES (2, '2026-02-19', '12311111111111', 'Followup', '94', '3', 1, 'qwe', '342222222222', '234', 'wdaaaaaaaaaa', '2026-02-26', NULL, NULL, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, '2026-02-13 12:12:38', 'Outreach', 1, NULL);
INSERT INTO `child_health_master` VALUES (3, '2026-02-14', '1231s', 'Followup', '94', '8', 1, 'Attocks', '2131s', 'Razias', 'Khans', '2026-02-12', NULL, NULL, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, '2026-02-13 23:57:14', 'Outreach', 1, NULL);
INSERT INTO `child_health_master` VALUES (4, '2026-03-06', '12321111111111111111111111', 'New', '94', '1', 1, 'ABC', '2131s', 'e1e', '123123', '2026-03-13', NULL, NULL, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, '2026-02-14 00:28:54', 'Fixed Site', 1, NULL);
INSERT INTO `child_health_master` VALUES (5, '2026-02-13', '123456', 'Followup', '94', '2', 2, 'Attock', '342222222222', 'Saad', 'Azam', '2026-02-21', NULL, NULL, NULL, 'Male', NULL, NULL, NULL, NULL, NULL, '2026-02-14 13:51:25', 'Fixed Site', 1, NULL);
INSERT INTO `child_health_master` VALUES (6, '2026-02-13', '123213123', 'Followup', '94', '2', 0, '1esads', '11wq', '1323', '123', '1997-03-10', 199, 3, 1, 'Female', 'Married', 'Pregnant', 'Yes', 'Yes', 'Yes', '2026-02-14 21:22:59', 'Outreach', 1, '1-2 Year');
INSERT INTO `child_health_master` VALUES (7, '2026-02-20', '1223344', 'New', '94', '1', 1, 'Attock', '2131wer', 'hahaha', 'abc', '2026-02-28', 12, 33, 4, 'Female', 'Married', 'Non-Pregnant', 'Yes', 'No', 'Yes', '2026-02-14 22:33:54', 'Fixed Site', 1, '1-2 Year');
INSERT INTO `child_health_master` VALUES (8, '2026-02-11', '1231', 'Followup', '94', '12', 0, 'Attock', '342222222222', 'Saad', 'Khan', '2022-02-02', 4, 0, 13, 'Male', 'Un-Married', 'Non-Pregnant', 'No', 'No', 'No', '2026-02-15 11:15:56', 'Outreach', 1, '2-5 Year');
INSERT INTO `child_health_master` VALUES (9, '2026-02-12', '1231', 'Followup', '94', '2', 0, '1esads', '2131', 'Saad', 'Azam', '2026-02-04', 0, 0, 11, 'Male', 'Un-Married', 'Non-Pregnant', 'No', 'No', 'No', '2026-02-15 11:38:28', 'Outreach', 1, '<1 Year');
INSERT INTO `child_health_master` VALUES (11, '2026-02-27', '1231', 'New', '94', '1', 0, 'qwe', '2131', '1323', 'wdaaaaaaaaaa', '2026-02-06', 0, 0, 9, 'Male', 'Married', 'Non-Pregnant', 'No', 'No', 'No', '2026-02-15 11:51:19', 'Outreach', 1, '<1 Year');
INSERT INTO `child_health_master` VALUES (12, '2026-02-19', '21312323', 'Followup', '94', '1', 0, '1212', '2131', 'Saad', 'Azamsaasas', '2026-02-13', 0, 0, 2, 'Male', 'Un-Married', 'Non-Pregnant', 'No', 'No', 'Yes', '2026-02-15 11:55:02', 'Outreach', 1, '<1 Year');

-- ----------------------------
-- Table structure for districts
-- ----------------------------
DROP TABLE IF EXISTS `districts`;
CREATE TABLE `districts`  (
  `district_id` int NOT NULL AUTO_INCREMENT,
  `district_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `province_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`district_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of districts
-- ----------------------------
INSERT INTO `districts` VALUES (94, 'North Waziristan', 3);

-- ----------------------------
-- Table structure for facilities
-- ----------------------------
DROP TABLE IF EXISTS `facilities`;
CREATE TABLE `facilities`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `uc_id` int NOT NULL,
  `facility_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `latitude` decimal(10, 7) NULL DEFAULT NULL,
  `longitude` decimal(10, 7) NULL DEFAULT NULL,
  `province_id` int NULL DEFAULT NULL,
  `district_id` int NULL DEFAULT NULL,
  `isd_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of facilities
-- ----------------------------
INSERT INTO `facilities` VALUES (1, 1, 'xyz', 123.0000000, 222.0000000, NULL, NULL, NULL);
INSERT INTO `facilities` VALUES (2, 2, 'abc', 3.0000000, 123.0000000, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for form_logs
-- ----------------------------
DROP TABLE IF EXISTS `form_logs`;
CREATE TABLE `form_logs`  (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `form_type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `master_id` int NOT NULL,
  `data_json` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of form_logs
-- ----------------------------
INSERT INTO `form_logs` VALUES (1, 'child_health_form', 12, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-20\",\"qr_code\":\"1231\",\"client_type\":\"Followup\",\"district\":\"1\",\"uc\":\"10\",\"village\":\"Attock\",\"vaccinator_name\":\"2131\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"dob\":\"2026-03-13\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":12,\"question_id\":1,\"option_id\":null,\"answer\":\"1\"},{\"master_id\":12,\"question_id\":2,\"option_id\":null,\"answer\":\"4\"},{\"master_id\":12,\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":12,\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":12,\"question_id\":6,\"option_id\":\"30\",\"answer\":\"PENTA I\"},{\"master_id\":12,\"question_id\":6,\"option_id\":\"31\",\"answer\":\"PENTA II\"}]}', '2026-02-13 10:45:58');
INSERT INTO `form_logs` VALUES (2, 'opd_mnch_form', 7, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-28\",\"anc_card_no\":\"324\",\"client_type\":\"Followup\",\"district\":\"1\",\"uc\":\"5\",\"village\":\"Attock\",\"lhv_name\":\"1123\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"disability\":\"No\",\"age_group\":\"1-5\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":7,\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":7,\"question_id\":20,\"option_id\":null,\"answer\":\"88\"},{\"master_id\":7,\"question_id\":21,\"option_id\":\"98\",\"answer\":\"9. Hepas\"},{\"master_id\":7,\"question_id\":21,\"option_id\":\"99\",\"answer\":\"10. Obstetric\"}]}', '2026-02-13 10:54:14');
INSERT INTO `form_logs` VALUES (3, 'opd_mnch_form', 1, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-12\",\"anc_card_no\":\"12345\",\"client_type\":\"New\",\"district\":\"1\",\"uc\":\"5\",\"village\":\"ABC\",\"lhv_name\":\"Razia\",\"patient_name\":\"Razia\",\"guardian_name\":\"Sultan\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":1,\"question_id\":18,\"option_id\":null,\"answer\":\"82\"},{\"master_id\":1,\"question_id\":21,\"option_id\":\"93\",\"answer\":\"4. Eclampsia\"},{\"master_id\":1,\"question_id\":21,\"option_id\":\"94\",\"answer\":\"5. Gestaonal Diabetes\"},{\"master_id\":1,\"question_id\":24,\"option_id\":null,\"answer\":\"103\"},{\"master_id\":1,\"question_id\":27,\"option_id\":null,\"answer\":\"112\"},{\"master_id\":1,\"question_id\":26,\"option_id\":\"110\",\"answer\":\"No\"},{\"master_id\":1,\"question_id\":30,\"option_id\":\"128\",\"answer\":\"12.Crimean\\u2013Congo Hemorrhagic Fever (Suspected)\"},{\"master_id\":1,\"question_id\":30,\"option_id\":\"129\",\"answer\":\"13. Cutaneous Leishmaniasis (Suspected)\"},{\"master_id\":1,\"question_id\":30,\"option_id\":\"130\",\"answer\":\"14. Meningis Suspected Likely (Suspected Meningis\"},{\"master_id\":1,\"question_id\":33,\"option_id\":\"149\",\"answer\":\"2. Hygiene Kit (NW)\"},{\"master_id\":1,\"question_id\":35,\"option_id\":null,\"answer\":\"152\"}]}', '2026-02-13 11:31:30');
INSERT INTO `form_logs` VALUES (4, 'child_health_form', 1, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-20\",\"qr_code\":\"1231\",\"client_type\":\"New\",\"district\":\"1\",\"uc\":\"6\",\"village\":\"Bannu\",\"vaccinator_name\":\"No\",\"patient_name\":\"Shagufta\",\"guardian_name\":\"Khan\",\"dob\":\"1993-02-10\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Female\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":1,\"question_id\":1,\"option_id\":null,\"answer\":\"2\"},{\"master_id\":1,\"question_id\":2,\"option_id\":null,\"answer\":\"4\"},{\"master_id\":1,\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":1,\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":1,\"question_id\":5,\"option_id\":\"15\",\"answer\":\"PENTA III\"},{\"master_id\":1,\"question_id\":5,\"option_id\":\"16\",\"answer\":\"MR I\"},{\"master_id\":1,\"question_id\":11,\"option_id\":\"65\",\"answer\":\"No\"},{\"master_id\":1,\"question_id\":13,\"option_id\":null,\"answer\":\"72\"}]}', '2026-02-13 11:32:53');
INSERT INTO `form_logs` VALUES (5, 'opd_mnch_form', 2, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-19\",\"anc_card_no\":\"324\",\"client_type\":\"Followup\",\"district\":\"1\",\"uc\":\"12\",\"village\":\"Attock\",\"lhv_name\":\"1123\",\"patient_name\":\"Ahmed\",\"guardian_name\":\"Azam\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":2,\"question_id\":30,\"option_id\":\"139\",\"answer\":\"23. AT Animal Bite \\/ An-Rabies Treatment\"},{\"master_id\":2,\"question_id\":30,\"option_id\":\"140\",\"answer\":\"24.Mumps Viral Infecon\"},{\"master_id\":2,\"question_id\":32,\"option_id\":null,\"answer\":\"147\"},{\"master_id\":2,\"question_id\":33,\"option_id\":\"149\",\"answer\":\"2. Hygiene Kit (NW)\"}]}', '2026-02-13 12:00:57');
INSERT INTO `form_logs` VALUES (6, 'opd_mnch_form', 3, '{\"master\":{\"visit_type\":\"OPD\",\"form_date\":\"2026-02-20\",\"anc_card_no\":\"wdqd\",\"client_type\":\"New\",\"district\":\"1\",\"uc\":\"12\",\"village\":\"qw\",\"lhv_name\":\"wqe\",\"patient_name\":\"qwe\",\"guardian_name\":\"12\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[]}', '2026-02-13 12:08:30');
INSERT INTO `form_logs` VALUES (7, 'opd_mnch_form', 4, '{\"master\":{\"visit_type\":\"OPD\",\"form_date\":\"2026-02-13\",\"anc_card_no\":\"324\",\"client_type\":\"Followup\",\"district\":\"1\",\"uc\":\"12\",\"village\":\"123\",\"lhv_name\":\"wqe\",\"patient_name\":\"e1e\",\"guardian_name\":\"qwe\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":4,\"question_id\":18,\"option_id\":null,\"answer\":\"82\"},{\"master_id\":4,\"question_id\":35,\"option_id\":null,\"answer\":\"151\"}]}', '2026-02-13 12:12:07');
INSERT INTO `form_logs` VALUES (8, 'child_health_form', 2, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-02-19\",\"qr_code\":\"1231\",\"client_type\":\"Followup\",\"district\":\"1\",\"uc\":\"3\",\"village\":\"qwe\",\"vaccinator_name\":\"342\",\"patient_name\":\"234\",\"guardian_name\":\"wda\",\"dob\":\"2026-02-26\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":2,\"question_id\":3,\"option_id\":\"5\",\"answer\":\"17.2.1.  Fully immunized as per Age\"},{\"master_id\":2,\"question_id\":13,\"option_id\":null,\"answer\":\"72\"}]}', '2026-02-13 12:12:38');
INSERT INTO `form_logs` VALUES (9, 'opd_mnch_form_update', 4, '{\"master\":{\"visit_type\":\"OPD\",\"form_date\":\"2026-02-13\",\"anc_card_no\":\"324\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"3\",\"village\":\"123\",\"lhv_name\":\"wqe\",\"patient_name\":\"e1e\",\"guardian_name\":\"qwe\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":\"4\",\"question_id\":18,\"option_id\":null,\"answer\":\"82\"},{\"master_id\":\"4\",\"question_id\":24,\"option_id\":null,\"answer\":\"103\"},{\"master_id\":\"4\",\"question_id\":27,\"option_id\":null,\"answer\":\"112\"},{\"master_id\":\"4\",\"question_id\":25,\"option_id\":null,\"answer\":\"107\"},{\"master_id\":\"4\",\"question_id\":28,\"option_id\":null,\"answer\":\"114\"},{\"master_id\":\"4\",\"question_id\":26,\"option_id\":\"110\",\"answer\":\"No\"},{\"master_id\":\"4\",\"question_id\":29,\"option_id\":null,\"answer\":\"116\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"141\",\"answer\":\"25. Severe Complicated Burns\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"142\",\"answer\":\"26. Unexplained Fever\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"143\",\"answer\":\"27.Severe Acute Malnutrion\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"144\",\"answer\":\"28. Injury All accidental or trauma-related injuries.\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"145\",\"answer\":\"29. Other\"},{\"master_id\":\"4\",\"question_id\":35,\"option_id\":null,\"answer\":\"151\"}]}', '2026-02-13 23:11:47');
INSERT INTO `form_logs` VALUES (10, 'opd_mnch_form_update', 4, '{\"master\":{\"visit_type\":\"OPD\",\"form_date\":\"2026-02-13\",\"anc_card_no\":\"324\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"3\",\"village\":\"1233333333\",\"lhv_name\":\"wqeeeeeeee\",\"patient_name\":\"e1eeeeeee\",\"guardian_name\":\"qweeeeeeee\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":\"4\",\"question_id\":18,\"option_id\":null,\"answer\":\"82\"},{\"master_id\":\"4\",\"question_id\":20,\"option_id\":null,\"answer\":\"89\"},{\"master_id\":\"4\",\"question_id\":19,\"option_id\":null,\"answer\":\"87\"},{\"master_id\":\"4\",\"question_id\":21,\"option_id\":\"93\",\"answer\":\"4. Eclampsia\"},{\"master_id\":\"4\",\"question_id\":21,\"option_id\":\"94\",\"answer\":\"5. Gestaonal Diabetes\"},{\"master_id\":\"4\",\"question_id\":21,\"option_id\":\"95\",\"answer\":\"6. Asthma\"},{\"master_id\":\"4\",\"question_id\":21,\"option_id\":\"96\",\"answer\":\"7. Type 2 Diabetes\"},{\"master_id\":\"4\",\"question_id\":24,\"option_id\":null,\"answer\":\"103\"},{\"master_id\":\"4\",\"question_id\":27,\"option_id\":null,\"answer\":\"112\"},{\"master_id\":\"4\",\"question_id\":25,\"option_id\":null,\"answer\":\"107\"},{\"master_id\":\"4\",\"question_id\":28,\"option_id\":null,\"answer\":\"114\"},{\"master_id\":\"4\",\"question_id\":26,\"option_id\":\"110\",\"answer\":\"No\"},{\"master_id\":\"4\",\"question_id\":29,\"option_id\":null,\"answer\":\"116\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"141\",\"answer\":\"25. Severe Complicated Burns\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"142\",\"answer\":\"26. Unexplained Fever\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"143\",\"answer\":\"27.Severe Acute Malnutrion\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"144\",\"answer\":\"28. Injury All accidental or trauma-related injuries.\"},{\"master_id\":\"4\",\"question_id\":30,\"option_id\":\"145\",\"answer\":\"29. Other\"},{\"master_id\":\"4\",\"question_id\":35,\"option_id\":null,\"answer\":\"151\"}]}', '2026-02-13 23:12:40');
INSERT INTO `form_logs` VALUES (11, 'opd_mnch_form_update', 3, '{\"master\":{\"visit_type\":\"OPD\",\"form_date\":\"2026-02-20\",\"anc_card_no\":\"wdqd\",\"client_type\":\"New\",\"district\":\"94\",\"uc\":\"12\",\"village\":\"qw\",\"lhv_name\":\"wqe\",\"patient_name\":\"qwe\",\"guardian_name\":\"12\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":\"3\",\"question_id\":26,\"option_id\":\"110\",\"answer\":\"No\"},{\"master_id\":\"3\",\"question_id\":29,\"option_id\":null,\"answer\":\"116\"},{\"master_id\":\"3\",\"question_id\":34,\"option_id\":null,\"answer\":\"sada\"},{\"master_id\":\"3\",\"question_id\":35,\"option_id\":null,\"answer\":\"152\"}]}', '2026-02-13 23:33:29');
INSERT INTO `form_logs` VALUES (12, 'child_health_form', 2, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-02-19\",\"qr_code\":\"12311111111111\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"3\",\"village\":\"qwe\",\"vaccinator_name\":\"342222222222\",\"patient_name\":\"234\",\"guardian_name\":\"wdaaaaaaaaaa\",\"dob\":\"2026-02-26\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":\"2\",\"question_id\":12,\"option_id\":null,\"answer\":\"66\"},{\"master_id\":\"2\",\"question_id\":13,\"option_id\":null,\"answer\":\"70\"},{\"master_id\":\"2\",\"question_id\":15,\"option_id\":\"75\",\"answer\":\"Yes\"},{\"master_id\":\"2\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"2\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-13 23:54:23');
INSERT INTO `form_logs` VALUES (13, 'child_health_form', 2, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-02-19\",\"qr_code\":\"12311111111111\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"3\",\"village\":\"qwe\",\"vaccinator_name\":\"342222222222\",\"patient_name\":\"234\",\"guardian_name\":\"wdaaaaaaaaaa\",\"dob\":\"2026-02-26\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":\"2\",\"question_id\":2,\"option_id\":null,\"answer\":\"4\"},{\"master_id\":\"2\",\"question_id\":3,\"option_id\":\"5\",\"answer\":\"17.2.1.  Fully immunized as per Age\"},{\"master_id\":\"2\",\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":\"2\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"2\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-13 23:54:51');
INSERT INTO `form_logs` VALUES (14, 'child_health_form', 3, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-14\",\"qr_code\":\"1231\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"8\",\"village\":\"Attock\",\"vaccinator_name\":\"2131\",\"patient_name\":\"Razia\",\"guardian_name\":\"Khan\",\"dob\":\"2026-02-12\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":3,\"question_id\":4,\"option_id\":\"9\",\"answer\":\"Demand Refusal\"},{\"master_id\":3,\"question_id\":4,\"option_id\":\"10\",\"answer\":\"Misconception Refusal \"},{\"master_id\":3,\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":3,\"question_id\":8,\"option_id\":\"57\",\"answer\":\"OPV\"},{\"master_id\":3,\"question_id\":16,\"option_id\":\"77\",\"answer\":\"Yes\"}]}', '2026-02-13 23:57:14');
INSERT INTO `form_logs` VALUES (15, 'child_health_form', 2, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-19\",\"qr_code\":\"12311111111111\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"3\",\"village\":\"qwe\",\"vaccinator_name\":\"342222222222\",\"patient_name\":\"234\",\"guardian_name\":\"wdaaaaaaaaaa\",\"dob\":\"2026-02-26\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":\"2\",\"question_id\":2,\"option_id\":null,\"answer\":\"4\"},{\"master_id\":\"2\",\"question_id\":3,\"option_id\":\"5\",\"answer\":\"17.2.1.  Fully immunized as per Age\"},{\"master_id\":\"2\",\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":\"2\",\"question_id\":12,\"option_id\":null,\"answer\":\"67\"},{\"master_id\":\"2\",\"question_id\":13,\"option_id\":null,\"answer\":\"72\"},{\"master_id\":\"2\",\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":\"2\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"2\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 00:10:01');
INSERT INTO `form_logs` VALUES (16, 'child_health_form', 3, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-14\",\"qr_code\":\"1231\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"8\",\"village\":\"Attock\",\"vaccinator_name\":\"2131\",\"patient_name\":\"Razia\",\"guardian_name\":\"Khan\",\"dob\":\"2026-02-12\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"9\",\"answer\":\"Demand Refusal\"},{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"10\",\"answer\":\"Misconception Refusal \"},{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":\"3\",\"question_id\":8,\"option_id\":\"57\",\"answer\":\"OPV\"},{\"master_id\":\"3\",\"question_id\":12,\"option_id\":null,\"answer\":\"67\"},{\"master_id\":\"3\",\"question_id\":13,\"option_id\":null,\"answer\":\"70\"},{\"master_id\":\"3\",\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":\"3\",\"question_id\":16,\"option_id\":\"77\",\"answer\":\"Yes\"},{\"master_id\":\"3\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 00:10:32');
INSERT INTO `form_logs` VALUES (17, 'child_health_form', 3, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-14\",\"qr_code\":\"1231\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"8\",\"village\":\"Attock\",\"vaccinator_name\":\"2131\",\"patient_name\":\"Razia\",\"guardian_name\":\"Khan\",\"dob\":\"2026-02-12\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"9\",\"answer\":\"Demand Refusal\"},{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"10\",\"answer\":\"Misconception Refusal \"},{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"30\",\"answer\":\"PENTA I\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"31\",\"answer\":\"PENTA II\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"32\",\"answer\":\"PENTA III\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"33\",\"answer\":\"MR I\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"34\",\"answer\":\"MR II\"},{\"master_id\":\"3\",\"question_id\":8,\"option_id\":\"57\",\"answer\":\"OPV\"},{\"master_id\":\"3\",\"question_id\":12,\"option_id\":null,\"answer\":\"67\"},{\"master_id\":\"3\",\"question_id\":13,\"option_id\":null,\"answer\":\"72\"},{\"master_id\":\"3\",\"question_id\":15,\"option_id\":\"75\",\"answer\":\"Yes\"},{\"master_id\":\"3\",\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":\"3\",\"question_id\":16,\"option_id\":\"77\",\"answer\":\"Yes\"},{\"master_id\":\"3\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"3\",\"question_id\":17,\"option_id\":\"79\",\"answer\":\"Yes\"},{\"master_id\":\"3\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 00:11:01');
INSERT INTO `form_logs` VALUES (18, 'child_health_form', 3, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-14\",\"qr_code\":\"1231s\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"8\",\"village\":\"Attocks\",\"vaccinator_name\":\"2131s\",\"patient_name\":\"Razias\",\"guardian_name\":\"Khans\",\"dob\":\"2026-02-12\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"9\",\"answer\":\"Demand Refusal\"},{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"10\",\"answer\":\"Misconception Refusal \"},{\"master_id\":\"3\",\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":\"3\",\"question_id\":5,\"option_id\":\"28\",\"answer\":\"ROTA II\"},{\"master_id\":\"3\",\"question_id\":5,\"option_id\":\"29\",\"answer\":\"IPV I\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"30\",\"answer\":\"PENTA I\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"31\",\"answer\":\"PENTA II\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"32\",\"answer\":\"PENTA III\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"33\",\"answer\":\"MR I\"},{\"master_id\":\"3\",\"question_id\":6,\"option_id\":\"34\",\"answer\":\"MR II\"},{\"master_id\":\"3\",\"question_id\":8,\"option_id\":\"57\",\"answer\":\"OPV\"},{\"master_id\":\"3\",\"question_id\":12,\"option_id\":null,\"answer\":\"67\"},{\"master_id\":\"3\",\"question_id\":13,\"option_id\":null,\"answer\":\"72\"},{\"master_id\":\"3\",\"question_id\":15,\"option_id\":\"75\",\"answer\":\"Yes\"},{\"master_id\":\"3\",\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":\"3\",\"question_id\":16,\"option_id\":\"77\",\"answer\":\"Yes\"},{\"master_id\":\"3\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"3\",\"question_id\":17,\"option_id\":\"79\",\"answer\":\"Yes\"},{\"master_id\":\"3\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 00:17:08');
INSERT INTO `form_logs` VALUES (19, 'child_health_form', 4, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-03-06\",\"qr_code\":\"12321\",\"client_type\":\"New\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"ABC\",\"vaccinator_name\":\"2131s\",\"patient_name\":\"e1e\",\"guardian_name\":\"123123\",\"dob\":\"2026-03-13\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null,\"created_by\":\"1\"},\"details\":null}', '2026-02-14 00:28:54');
INSERT INTO `form_logs` VALUES (20, 'child_health_form', 4, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-03-06\",\"qr_code\":\"12321111111111111111111111\",\"client_type\":\"New\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"ABC\",\"vaccinator_name\":\"2131s\",\"patient_name\":\"e1e\",\"guardian_name\":\"123123\",\"dob\":\"2026-03-13\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":\"4\",\"question_id\":6,\"option_id\":\"30\",\"answer\":\"PENTA I\"},{\"master_id\":\"4\",\"question_id\":6,\"option_id\":\"31\",\"answer\":\"PENTA II\"},{\"master_id\":\"4\",\"question_id\":6,\"option_id\":\"32\",\"answer\":\"PENTA III\"},{\"master_id\":\"4\",\"question_id\":6,\"option_id\":\"33\",\"answer\":\"MR I\"},{\"master_id\":\"4\",\"question_id\":6,\"option_id\":\"34\",\"answer\":\"MR II\"},{\"master_id\":\"4\",\"question_id\":6,\"option_id\":\"35\",\"answer\":\"PCV I\"},{\"master_id\":\"4\",\"question_id\":15,\"option_id\":\"75\",\"answer\":\"Yes\"},{\"master_id\":\"4\",\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":\"4\",\"question_id\":16,\"option_id\":\"77\",\"answer\":\"Yes\"},{\"master_id\":\"4\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"4\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 00:35:11');
INSERT INTO `form_logs` VALUES (21, 'child_health_form', 5, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-02-13\",\"qr_code\":\"testttt\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"Attock\",\"vaccinator_name\":\"342222222222\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"dob\":\"2026-02-21\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null,\"created_by\":\"1\",\"facility_id\":\"1\"},\"details\":[{\"master_id\":5,\"question_id\":3,\"option_id\":\"5\",\"answer\":\"17.2.1.  Fully immunized as per Age\"},{\"master_id\":5,\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":5,\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":5,\"question_id\":3,\"option_id\":\"8\",\"answer\":\"17.2.4.  Refusal\"},{\"master_id\":5,\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 13:51:25');
INSERT INTO `form_logs` VALUES (22, 'opd_mnch_form', 5, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-13\",\"anc_card_no\":\"fads\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"asda\",\"lhv_name\":\"ewq\",\"patient_name\":\"asda\",\"guardian_name\":\"asd\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"notes\":null},\"details\":[{\"master_id\":5,\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":5,\"question_id\":35,\"option_id\":null,\"answer\":\"152\"}]}', '2026-02-14 14:01:48');
INSERT INTO `form_logs` VALUES (23, 'opd_mnch_form_update', 5, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-13\",\"anc_card_no\":\"fads\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"asda\",\"lhv_name\":\"ewq\",\"patient_name\":\"asda\",\"guardian_name\":\"asd\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"facility_id\":\"2\",\"notes\":null},\"details\":[{\"master_id\":\"5\",\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":\"5\",\"question_id\":35,\"option_id\":null,\"answer\":\"152\"}]}', '2026-02-14 14:05:14');
INSERT INTO `form_logs` VALUES (24, 'child_health_form', 5, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-02-13\",\"qr_code\":\"testttt2nd\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"Attock\",\"vaccinator_name\":\"342222222222\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"dob\":\"2026-02-21\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null,\"created_by\":\"1\",\"facility_id\":\"2\"},\"details\":[{\"master_id\":\"5\",\"question_id\":3,\"option_id\":\"5\",\"answer\":\"17.2.1.  Fully immunized as per Age\"},{\"master_id\":\"5\",\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":\"5\",\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":\"5\",\"question_id\":3,\"option_id\":\"8\",\"answer\":\"17.2.4.  Refusal\"},{\"master_id\":\"5\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 14:09:09');
INSERT INTO `form_logs` VALUES (25, 'opd_mnch_form_update', 5, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-13\",\"anc_card_no\":\"fads3rd\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"asda\",\"lhv_name\":\"ewq\",\"patient_name\":\"asda\",\"guardian_name\":\"asd\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"facility_id\":\"1\",\"notes\":null},\"details\":[{\"master_id\":\"5\",\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":\"5\",\"question_id\":35,\"option_id\":null,\"answer\":\"152\"}]}', '2026-02-14 14:09:24');
INSERT INTO `form_logs` VALUES (26, 'child_health_form', 5, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-02-13\",\"qr_code\":\"123456\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"Attock\",\"vaccinator_name\":\"342222222222\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"dob\":\"2026-02-21\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null,\"created_by\":\"1\",\"facility_id\":\"2\"},\"details\":[{\"master_id\":\"5\",\"question_id\":3,\"option_id\":\"5\",\"answer\":\"17.2.1.  Fully immunized as per Age\"},{\"master_id\":\"5\",\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":\"5\",\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":\"5\",\"question_id\":3,\"option_id\":\"8\",\"answer\":\"17.2.4.  Refusal\"},{\"master_id\":\"5\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 14:20:12');
INSERT INTO `form_logs` VALUES (27, 'opd_mnch_form_update', 5, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-13\",\"anc_card_no\":\"fads3rd\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"asda\",\"lhv_name\":\"ewq\",\"patient_name\":\"asda\",\"guardian_name\":\"asd\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"facility_id\":\"2\",\"notes\":null},\"details\":[{\"master_id\":\"5\",\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":\"5\",\"question_id\":22,\"option_id\":null,\"answer\":\"edddd\"},{\"master_id\":\"5\",\"question_id\":23,\"option_id\":null,\"answer\":\"explainnnnn\"},{\"master_id\":\"5\",\"question_id\":27,\"option_id\":null,\"answer\":\"112\"},{\"master_id\":\"5\",\"question_id\":25,\"option_id\":null,\"answer\":\"106\"},{\"master_id\":\"5\",\"question_id\":30,\"option_id\":\"117\",\"answer\":\"1. Acute Upper Respiratory Tract Infecon\"},{\"master_id\":\"5\",\"question_id\":30,\"option_id\":\"119\",\"answer\":\"3. Severe Acute Respiratory Infecon\"},{\"master_id\":\"5\",\"question_id\":30,\"option_id\":\"121\",\"answer\":\"5. Acute Watery Diarrhea\"},{\"master_id\":\"5\",\"question_id\":33,\"option_id\":\"148\",\"answer\":\"1. Clean Delivery Kit (NW)\"},{\"master_id\":\"5\",\"question_id\":33,\"option_id\":\"149\",\"answer\":\"2. Hygiene Kit (NW)\"},{\"master_id\":\"5\",\"question_id\":33,\"option_id\":\"150\",\"answer\":\"3. Learning Kit (NW\\/SW)\"},{\"master_id\":\"5\",\"question_id\":34,\"option_id\":null,\"answer\":\"test\"},{\"master_id\":\"5\",\"question_id\":35,\"option_id\":null,\"answer\":\"152\"}]}', '2026-02-14 15:15:32');
INSERT INTO `form_logs` VALUES (28, 'child_health_form', 6, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-13\",\"qr_code\":\"123213123\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"1esads\",\"vaccinator_name\":\"11wq\",\"patient_name\":\"1323\",\"guardian_name\":\"123\",\"dob\":\"2026-02-20\",\"age_year\":\"2023\",\"age_month\":\"12\",\"age_day\":\"1\",\"age_group\":\"1-2 Year\",\"gender\":\"Female\",\"marital_status\":\"Married\",\"pregnancy_status\":\"Pregnant\",\"disability\":\"Yes\",\"play_learning_kit\":\"Yes\",\"nutrition_package\":\"Yes\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":6,\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":6,\"question_id\":3,\"option_id\":\"8\",\"answer\":\"17.2.4.  Refusal\"},{\"master_id\":6,\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":6,\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 21:22:59');
INSERT INTO `form_logs` VALUES (29, 'child_health_form', 6, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-13\",\"qr_code\":\"123213123\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"1esads\",\"vaccinator_name\":\"11wq\",\"patient_name\":\"1323\",\"guardian_name\":\"123\",\"dob\":\"2026-02-20\",\"age_year\":\"2024\",\"age_month\":\"12\",\"age_day\":\"1\",\"age_group\":\"1-2 Year\",\"gender\":\"Female\",\"marital_status\":\"Married\",\"pregnancy_status\":\"Pregnant\",\"disability\":\"Yes\",\"play_learning_kit\":\"Yes\",\"nutrition_package\":\"Yes\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":\"6\",\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":\"6\",\"question_id\":3,\"option_id\":\"8\",\"answer\":\"17.2.4.  Refusal\"},{\"master_id\":\"6\",\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":\"6\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"6\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 21:26:23');
INSERT INTO `form_logs` VALUES (30, 'child_health_form', 6, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-13\",\"qr_code\":\"123213123\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"1esads\",\"vaccinator_name\":\"11wq\",\"patient_name\":\"1323\",\"guardian_name\":\"123\",\"dob\":\"1997-03-10\",\"age_year\":\"199\",\"age_month\":\"03\",\"age_day\":\"01\",\"age_group\":\"1-2 Year\",\"gender\":\"Female\",\"marital_status\":\"Married\",\"pregnancy_status\":\"Pregnant\",\"disability\":\"Yes\",\"play_learning_kit\":\"Yes\",\"nutrition_package\":\"Yes\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":\"6\",\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":\"6\",\"question_id\":3,\"option_id\":\"8\",\"answer\":\"17.2.4.  Refusal\"},{\"master_id\":\"6\",\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":\"6\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"6\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-14 21:32:24');
INSERT INTO `form_logs` VALUES (31, 'child_health_form', 7, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-20\",\"qr_code\":\"1223344\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"Attock\",\"vaccinator_name\":\"2131\",\"patient_name\":\"tessst\",\"guardian_name\":\"abc\",\"dob\":\"2026-02-28\",\"age_year\":\"12\",\"age_month\":\"33\",\"age_day\":\"4\",\"age_group\":\"1-2 Year\",\"gender\":\"Female\",\"marital_status\":\"Married\",\"pregnancy_status\":\"Non-Pregnant\",\"disability\":\"Yes\",\"play_learning_kit\":\"No\",\"nutrition_package\":\"Yes\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":7,\"question_id\":1,\"option_id\":null,\"answer\":\"1\"},{\"master_id\":7,\"question_id\":2,\"option_id\":null,\"answer\":\"4\"}]}', '2026-02-14 22:33:54');
INSERT INTO `form_logs` VALUES (32, 'child_health_form', 7, '{\"master\":{\"visit_type\":\"Fixed Site\",\"form_date\":\"2026-02-20\",\"qr_code\":\"1223344\",\"client_type\":\"New\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"Attock\",\"vaccinator_name\":\"2131wer\",\"patient_name\":\"hahaha\",\"guardian_name\":\"abc\",\"dob\":\"2026-02-28\",\"age_year\":\"12\",\"age_month\":\"33\",\"age_day\":\"4\",\"age_group\":\"1-2 Year\",\"gender\":\"Female\",\"marital_status\":\"Married\",\"pregnancy_status\":\"Non-Pregnant\",\"disability\":\"Yes\",\"play_learning_kit\":\"No\",\"nutrition_package\":\"Yes\",\"created_by\":\"1\",\"facility_id\":\"1\"},\"details\":[{\"master_id\":\"7\",\"question_id\":1,\"option_id\":null,\"answer\":\"1\"},{\"master_id\":\"7\",\"question_id\":2,\"option_id\":null,\"answer\":\"4\"}]}', '2026-02-14 22:34:33');
INSERT INTO `form_logs` VALUES (33, 'opd_mnch_form', 6, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-21\",\"anc_card_no\":\"123213\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"1231\",\"lhv_name\":\"wqqqqq\",\"patient_name\":\"fahad\",\"guardian_name\":\"12312312\",\"disability\":\"No\",\"age_group\":\"15-49 Y\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"facility_id\":\"2\",\"qr_code\":\"123131231123\",\"notes\":null},\"details\":[{\"master_id\":6,\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":6,\"question_id\":20,\"option_id\":null,\"answer\":\"89\"}]}', '2026-02-14 22:56:45');
INSERT INTO `form_logs` VALUES (34, 'opd_mnch_form_update', 6, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-21\",\"anc_card_no\":\"123213\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"1231\",\"lhv_name\":\"wqqqqq\",\"patient_name\":\"fahad\",\"guardian_name\":\"12312312\",\"disability\":\"No\",\"age_group\":\"15-49 Y\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"facility_id\":\"2\",\"qr_code\":\"1231312311233\",\"notes\":null},\"details\":[{\"master_id\":\"6\",\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":\"6\",\"question_id\":20,\"option_id\":null,\"answer\":\"89\"}]}', '2026-02-14 22:57:22');
INSERT INTO `form_logs` VALUES (35, 'child_health_form', 8, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-11\",\"qr_code\":\"1231\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"12\",\"village\":\"Attock\",\"vaccinator_name\":\"342222222222\",\"patient_name\":\"Saad\",\"guardian_name\":\"Khan\",\"dob\":\"2022-02-02\",\"age_year\":\"4\",\"age_month\":\"0\",\"age_day\":\"13\",\"age_group\":\"2-5 Year\",\"gender\":\"Male\",\"marital_status\":\"Un-Married\",\"pregnancy_status\":\"Non-Pregnant\",\"disability\":\"No\",\"play_learning_kit\":\"No\",\"nutrition_package\":\"No\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":8,\"question_id\":1,\"option_id\":null,\"answer\":\"2\"},{\"master_id\":8,\"question_id\":2,\"option_id\":null,\"answer\":\"4\"},{\"master_id\":8,\"question_id\":3,\"option_id\":\"5\",\"answer\":\"17.2.1.  Fully immunized as per Age\"},{\"master_id\":8,\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":8,\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":8,\"question_id\":3,\"option_id\":\"8\",\"answer\":\"17.2.4.  Refusal\"},{\"master_id\":8,\"question_id\":5,\"option_id\":\"20\",\"answer\":\"PCV III\"},{\"master_id\":8,\"question_id\":5,\"option_id\":\"21\",\"answer\":\"IPV II\"},{\"master_id\":8,\"question_id\":5,\"option_id\":\"22\",\"answer\":\"Hep\"},{\"master_id\":8,\"question_id\":6,\"option_id\":\"34\",\"answer\":\"MR II\"},{\"master_id\":8,\"question_id\":6,\"option_id\":\"35\",\"answer\":\"PCV I\"},{\"master_id\":8,\"question_id\":7,\"option_id\":\"56\",\"answer\":\"TCV\"},{\"master_id\":8,\"question_id\":8,\"option_id\":\"59\",\"answer\":\"Measles\"},{\"master_id\":8,\"question_id\":9,\"option_id\":\"61\",\"answer\":\"No\"},{\"master_id\":8,\"question_id\":10,\"option_id\":\"63\",\"answer\":\"No\"},{\"master_id\":8,\"question_id\":11,\"option_id\":\"65\",\"answer\":\"No\"},{\"master_id\":8,\"question_id\":12,\"option_id\":null,\"answer\":\"66\"},{\"master_id\":8,\"question_id\":13,\"option_id\":null,\"answer\":\"68\"},{\"master_id\":8,\"question_id\":15,\"option_id\":\"75\",\"answer\":\"Yes\"},{\"master_id\":8,\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":8,\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-15 11:15:56');
INSERT INTO `form_logs` VALUES (36, 'opd_mnch_form', 7, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-19\",\"anc_card_no\":\"2131\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"ISB\",\"lhv_name\":\"wqeeeeeeee\",\"patient_name\":\"1323\",\"guardian_name\":\"123\",\"disability\":\"No\",\"age_group\":\"50 Y +\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"facility_id\":\"1\",\"qr_code\":\"1231\",\"notes\":null},\"details\":[{\"master_id\":7,\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":7,\"question_id\":20,\"option_id\":null,\"answer\":\"89\"},{\"master_id\":7,\"question_id\":19,\"option_id\":null,\"answer\":\"86\"},{\"master_id\":7,\"question_id\":21,\"option_id\":\"93\",\"answer\":\"4. Eclampsia\"},{\"master_id\":7,\"question_id\":21,\"option_id\":\"94\",\"answer\":\"5. Gestaonal Diabetes\"},{\"master_id\":7,\"question_id\":22,\"option_id\":\"123\",\"answer\":\"7. Typhoid Fever\"},{\"master_id\":7,\"question_id\":24,\"option_id\":null,\"answer\":\"103\"},{\"master_id\":7,\"question_id\":27,\"option_id\":null,\"answer\":\"112\"},{\"master_id\":7,\"question_id\":25,\"option_id\":null,\"answer\":\"106\"},{\"master_id\":7,\"question_id\":28,\"option_id\":null,\"answer\":\"114\"},{\"master_id\":7,\"question_id\":26,\"option_id\":\"110\",\"answer\":\"No\"},{\"master_id\":7,\"question_id\":29,\"option_id\":null,\"answer\":\"116\"},{\"master_id\":7,\"question_id\":30,\"option_id\":\"117\",\"answer\":\"1. Acute Upper Respiratory Tract Infecon\"},{\"master_id\":7,\"question_id\":30,\"option_id\":\"118\",\"answer\":\"2. Lower Respiratory Tract Infecon\"},{\"master_id\":7,\"question_id\":31,\"option_id\":null,\"answer\":\"qweqweqw\"},{\"master_id\":7,\"question_id\":32,\"option_id\":null,\"answer\":\"147\"},{\"master_id\":7,\"question_id\":33,\"option_id\":\"149\",\"answer\":\"2. Hygiene Kit (NW)\"},{\"master_id\":7,\"question_id\":33,\"option_id\":\"150\",\"answer\":\"3. Learning Kit (NW\\/SW)\"},{\"master_id\":7,\"question_id\":34,\"option_id\":\"123\",\"answer\":\"7. Typhoid Fever\"},{\"master_id\":7,\"question_id\":35,\"option_id\":null,\"answer\":\"152\"}]}', '2026-02-15 11:23:16');
INSERT INTO `form_logs` VALUES (37, 'child_health_form', 9, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-12\",\"qr_code\":\"1231\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"1esads\",\"vaccinator_name\":\"2131\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"dob\":\"2026-02-04\",\"age_year\":\"0\",\"age_month\":\"0\",\"age_day\":\"11\",\"age_group\":\"<1 Year\",\"gender\":\"Male\",\"marital_status\":\"Un-Married\",\"pregnancy_status\":\"Non-Pregnant\",\"disability\":\"No\",\"play_learning_kit\":\"No\",\"nutrition_package\":\"No\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":9,\"question_id\":1,\"option_id\":null,\"answer\":\"2\"},{\"master_id\":9,\"question_id\":2,\"option_id\":null,\"answer\":\"4\"},{\"master_id\":9,\"question_id\":3,\"option_id\":\"8\",\"answer\":\"17.2.4.  Refusal\"},{\"master_id\":9,\"question_id\":4,\"option_id\":\"9\",\"answer\":\"Demand Refusal\"},{\"master_id\":9,\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":9,\"question_id\":5,\"option_id\":\"17\",\"answer\":\"OPV 0\"},{\"master_id\":9,\"question_id\":5,\"option_id\":\"18\",\"answer\":\"PCV I\"},{\"master_id\":9,\"question_id\":5,\"option_id\":\"19\",\"answer\":\"PCV II\"},{\"master_id\":9,\"question_id\":5,\"option_id\":\"20\",\"answer\":\"PCV III\"},{\"master_id\":9,\"question_id\":5,\"option_id\":\"21\",\"answer\":\"IPV II\"},{\"master_id\":9,\"question_id\":6,\"option_id\":\"31\",\"answer\":\"PENTA II\"},{\"master_id\":9,\"question_id\":6,\"option_id\":\"32\",\"answer\":\"PENTA III\"},{\"master_id\":9,\"question_id\":7,\"option_id\":\"47\",\"answer\":\"OPV III\"},{\"master_id\":9,\"question_id\":8,\"option_id\":\"58\",\"answer\":\"IPV\"},{\"master_id\":9,\"question_id\":10,\"option_id\":\"62\",\"answer\":\"Yes\"},{\"master_id\":9,\"question_id\":12,\"option_id\":null,\"answer\":\"67\"},{\"master_id\":9,\"question_id\":13,\"option_id\":null,\"answer\":\"70\"},{\"master_id\":9,\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":9,\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":9,\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-15 11:38:28');
INSERT INTO `form_logs` VALUES (38, 'child_health_form', 11, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-27\",\"qr_code\":\"1231\",\"client_type\":\"New\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"qwe\",\"vaccinator_name\":\"2131\",\"patient_name\":\"1323\",\"guardian_name\":\"wdaaaaaaaaaa\",\"dob\":\"2026-02-06\",\"age_year\":\"0\",\"age_month\":\"0\",\"age_day\":\"9\",\"age_group\":\"<1 Year\",\"gender\":\"Male\",\"marital_status\":\"Married\",\"pregnancy_status\":\"Non-Pregnant\",\"disability\":\"No\",\"play_learning_kit\":\"No\",\"nutrition_package\":\"No\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":11,\"question_id\":1,\"option_id\":2,\"answer\":\"No\"},{\"master_id\":11,\"question_id\":2,\"option_id\":4,\"answer\":\"No\"},{\"master_id\":11,\"question_id\":3,\"option_id\":\"5\",\"answer\":\"17.2.1.  Fully immunized as per Age\"},{\"master_id\":11,\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":11,\"question_id\":5,\"option_id\":\"15\",\"answer\":\"PENTA III\"},{\"master_id\":11,\"question_id\":5,\"option_id\":\"16\",\"answer\":\"MR I\"},{\"master_id\":11,\"question_id\":8,\"option_id\":\"59\",\"answer\":\"Measles\"},{\"master_id\":11,\"question_id\":9,\"option_id\":\"60\",\"answer\":\"Yes\"},{\"master_id\":11,\"question_id\":10,\"option_id\":\"63\",\"answer\":\"No\"},{\"master_id\":11,\"question_id\":12,\"option_id\":67,\"answer\":\"No\"},{\"master_id\":11,\"question_id\":13,\"option_id\":68,\"answer\":\"1st\"}]}', '2026-02-15 11:51:19');
INSERT INTO `form_logs` VALUES (39, 'child_health_form', 12, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-19\",\"qr_code\":\"21312323\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"1212\",\"vaccinator_name\":\"2131\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"dob\":\"2026-02-13\",\"age_year\":\"0\",\"age_month\":\"0\",\"age_day\":\"2\",\"age_group\":\"<1 Year\",\"gender\":\"Male\",\"marital_status\":\"Un-Married\",\"pregnancy_status\":\"Non-Pregnant\",\"disability\":\"No\",\"play_learning_kit\":\"No\",\"nutrition_package\":\"No\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":12,\"question_id\":1,\"option_id\":1,\"answer\":\"Yes\"},{\"master_id\":12,\"question_id\":2,\"option_id\":4,\"answer\":\"No\"},{\"master_id\":12,\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":12,\"question_id\":4,\"option_id\":\"10\",\"answer\":\"Misconception Refusal \"},{\"master_id\":12,\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":12,\"question_id\":5,\"option_id\":\"12\",\"answer\":\"BCG\"},{\"master_id\":12,\"question_id\":5,\"option_id\":\"13\",\"answer\":\"PENTA I\"},{\"master_id\":12,\"question_id\":5,\"option_id\":\"16\",\"answer\":\"MR I\"},{\"master_id\":12,\"question_id\":7,\"option_id\":\"45\",\"answer\":\"OPV I\"},{\"master_id\":12,\"question_id\":7,\"option_id\":\"56\",\"answer\":\"TCV\"},{\"master_id\":12,\"question_id\":8,\"option_id\":\"58\",\"answer\":\"IPV\"},{\"master_id\":12,\"question_id\":9,\"option_id\":\"61\",\"answer\":\"No\"},{\"master_id\":12,\"question_id\":12,\"option_id\":66,\"answer\":\"Yes\"},{\"master_id\":12,\"question_id\":13,\"option_id\":69,\"answer\":\"2nd\"},{\"master_id\":12,\"question_id\":15,\"option_id\":\"76\",\"answer\":\"No\"},{\"master_id\":12,\"question_id\":16,\"option_id\":\"77\",\"answer\":\"Yes\"},{\"master_id\":12,\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-15 11:55:03');
INSERT INTO `form_logs` VALUES (40, 'child_health_form', 12, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-19\",\"qr_code\":\"21312323\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"1\",\"village\":\"1212\",\"vaccinator_name\":\"2131\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azamsaasas\",\"dob\":\"2026-02-13\",\"age_year\":\"0\",\"age_month\":\"0\",\"age_day\":\"2\",\"age_group\":\"<1 Year\",\"gender\":\"Male\",\"marital_status\":\"Un-Married\",\"pregnancy_status\":\"Non-Pregnant\",\"disability\":\"No\",\"play_learning_kit\":\"No\",\"nutrition_package\":\"Yes\",\"created_by\":\"1\",\"facility_id\":\"\"},\"details\":[{\"master_id\":\"12\",\"question_id\":1,\"option_id\":1,\"answer\":\"Yes\"},{\"master_id\":\"12\",\"question_id\":2,\"option_id\":4,\"answer\":\"No\"},{\"master_id\":\"12\",\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":\"12\",\"question_id\":4,\"option_id\":\"10\",\"answer\":\"Misconception Refusal \"},{\"master_id\":\"12\",\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":\"12\",\"question_id\":5,\"option_id\":\"12\",\"answer\":\"BCG\"},{\"master_id\":\"12\",\"question_id\":5,\"option_id\":\"13\",\"answer\":\"PENTA I\"},{\"master_id\":\"12\",\"question_id\":5,\"option_id\":\"16\",\"answer\":\"MR I\"},{\"master_id\":\"12\",\"question_id\":5,\"option_id\":\"29\",\"answer\":\"IPV I\"},{\"master_id\":\"12\",\"question_id\":6,\"option_id\":\"43\",\"answer\":\"ROTA I\"},{\"master_id\":\"12\",\"question_id\":6,\"option_id\":\"44\",\"answer\":\"ROTA II\"},{\"master_id\":\"12\",\"question_id\":7,\"option_id\":\"45\",\"answer\":\"OPV I\"},{\"master_id\":\"12\",\"question_id\":7,\"option_id\":\"56\",\"answer\":\"TCV\"},{\"master_id\":\"12\",\"question_id\":8,\"option_id\":\"58\",\"answer\":\"IPV\"},{\"master_id\":\"12\",\"question_id\":9,\"option_id\":\"60\",\"answer\":\"Yes\"},{\"master_id\":\"12\",\"question_id\":10,\"option_id\":\"62\",\"answer\":\"Yes\"},{\"master_id\":\"12\",\"question_id\":11,\"option_id\":\"65\",\"answer\":\"No\"},{\"master_id\":\"12\",\"question_id\":12,\"option_id\":67,\"answer\":\"No\"},{\"master_id\":\"12\",\"question_id\":13,\"option_id\":70,\"answer\":\"3rd\"},{\"master_id\":\"12\",\"question_id\":15,\"option_id\":\"75\",\"answer\":\"Yes\"},{\"master_id\":\"12\",\"question_id\":16,\"option_id\":\"78\",\"answer\":\"No\"},{\"master_id\":\"12\",\"question_id\":17,\"option_id\":\"80\",\"answer\":\"No\"}]}', '2026-02-15 11:56:55');
INSERT INTO `form_logs` VALUES (41, 'opd_mnch_form', 8, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-20\",\"anc_card_no\":\"2131\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"Attock\",\"lhv_name\":\"1123\",\"patient_name\":\"asda\",\"guardian_name\":\"12\",\"disability\":\"No\",\"age_group\":\"50 Y +\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"facility_id\":\"2\",\"qr_code\":\"12311111111111\",\"notes\":null},\"details\":[{\"master_id\":8,\"question_id\":18,\"option_id\":83,\"answer\":\"3rd\"},{\"master_id\":8,\"question_id\":20,\"option_id\":89,\"answer\":\"No\"},{\"master_id\":8,\"question_id\":19,\"option_id\":87,\"answer\":\"4th\"},{\"master_id\":8,\"question_id\":21,\"option_id\":\"93\",\"answer\":\"4. Eclampsia\"},{\"master_id\":8,\"question_id\":21,\"option_id\":\"94\",\"answer\":\"5. Gestaonal Diabetes\"},{\"master_id\":8,\"question_id\":22,\"option_id\":null,\"answer\":\"edddddd\"},{\"master_id\":8,\"question_id\":23,\"option_id\":null,\"answer\":\"explainnnnn\"},{\"master_id\":8,\"question_id\":24,\"option_id\":103,\"answer\":\"No\"},{\"master_id\":8,\"question_id\":27,\"option_id\":111,\"answer\":\"Yes\"},{\"master_id\":8,\"question_id\":25,\"option_id\":106,\"answer\":\"3rd\"},{\"master_id\":8,\"question_id\":28,\"option_id\":114,\"answer\":\"No\"},{\"master_id\":8,\"question_id\":29,\"option_id\":116,\"answer\":\"No\"},{\"master_id\":8,\"question_id\":30,\"option_id\":\"127\",\"answer\":\"11. Dengue Fever Hemorrhagic\"},{\"master_id\":8,\"question_id\":30,\"option_id\":\"128\",\"answer\":\"12.Crimean\\u2013Congo Hemorrhagic Fever (Suspected)\"},{\"master_id\":8,\"question_id\":32,\"option_id\":146,\"answer\":\"Yes\"},{\"master_id\":8,\"question_id\":33,\"option_id\":\"148\",\"answer\":\"1. Clean Delivery Kit (NW)\"},{\"master_id\":8,\"question_id\":33,\"option_id\":\"150\",\"answer\":\"3. Learning Kit (NW\\/SW)\"},{\"master_id\":8,\"question_id\":34,\"option_id\":null,\"answer\":\"notessssssssssssssssss\"},{\"master_id\":8,\"question_id\":35,\"option_id\":152,\"answer\":\"No\"}]}', '2026-02-15 11:58:29');
INSERT INTO `form_logs` VALUES (42, 'opd_mnch_form_update', 8, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-20\",\"anc_card_no\":\"2131\",\"client_type\":\"Followup\",\"district\":\"94\",\"uc\":\"2\",\"village\":\"Attock\",\"lhv_name\":\"1123\",\"patient_name\":\"asda\",\"guardian_name\":\"121\",\"disability\":\"No\",\"age_group\":\"50 Y +\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"created_by\":\"1\",\"facility_id\":\"2\",\"qr_code\":\"12311111111111\",\"notes\":null},\"details\":[{\"master_id\":\"8\",\"question_id\":18,\"option_id\":83,\"answer\":\"3rd\"},{\"master_id\":\"8\",\"question_id\":20,\"option_id\":89,\"answer\":\"No\"},{\"master_id\":\"8\",\"question_id\":19,\"option_id\":87,\"answer\":\"4th\"},{\"master_id\":\"8\",\"question_id\":21,\"option_id\":\"93\",\"answer\":\"4. Eclampsia\"},{\"master_id\":\"8\",\"question_id\":21,\"option_id\":\"94\",\"answer\":\"5. Gestaonal Diabetes\"},{\"master_id\":\"8\",\"question_id\":22,\"option_id\":null,\"answer\":\"edddddd\"},{\"master_id\":\"8\",\"question_id\":23,\"option_id\":null,\"answer\":\"explainnnnn\"},{\"master_id\":\"8\",\"question_id\":24,\"option_id\":103,\"answer\":\"No\"},{\"master_id\":\"8\",\"question_id\":27,\"option_id\":111,\"answer\":\"Yes\"},{\"master_id\":\"8\",\"question_id\":25,\"option_id\":106,\"answer\":\"3rd\"},{\"master_id\":\"8\",\"question_id\":28,\"option_id\":114,\"answer\":\"No\"},{\"master_id\":\"8\",\"question_id\":29,\"option_id\":116,\"answer\":\"No\"},{\"master_id\":\"8\",\"question_id\":30,\"option_id\":\"127\",\"answer\":\"11. Dengue Fever Hemorrhagic\"},{\"master_id\":\"8\",\"question_id\":30,\"option_id\":\"128\",\"answer\":\"12.Crimean\\u2013Congo Hemorrhagic Fever (Suspected)\"},{\"master_id\":\"8\",\"question_id\":30,\"option_id\":\"145\",\"answer\":\"29. Other\"},{\"master_id\":\"8\",\"question_id\":31,\"option_id\":null,\"answer\":\"qweqweqw\"},{\"master_id\":\"8\",\"question_id\":32,\"option_id\":147,\"answer\":\"No\"},{\"master_id\":\"8\",\"question_id\":33,\"option_id\":\"148\",\"answer\":\"1. Clean Delivery Kit (NW)\"},{\"master_id\":\"8\",\"question_id\":33,\"option_id\":\"149\",\"answer\":\"2. Hygiene Kit (NW)\"},{\"master_id\":\"8\",\"question_id\":33,\"option_id\":\"150\",\"answer\":\"3. Learning Kit (NW\\/SW)\"},{\"master_id\":\"8\",\"question_id\":34,\"option_id\":null,\"answer\":\"notes\"},{\"master_id\":\"8\",\"question_id\":35,\"option_id\":151,\"answer\":\"Yes\"}]}', '2026-02-15 11:59:59');

-- ----------------------------
-- Table structure for opd_mnch_detail
-- ----------------------------
DROP TABLE IF EXISTS `opd_mnch_detail`;
CREATE TABLE `opd_mnch_detail`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `master_id` int NOT NULL,
  `question_id` int NOT NULL,
  `option_id` int NULL DEFAULT NULL,
  `answer` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 138 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of opd_mnch_detail
-- ----------------------------
INSERT INTO `opd_mnch_detail` VALUES (1, 1, 18, NULL, '82', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (2, 1, 21, 93, '4. Eclampsia', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (3, 1, 21, 94, '5. Gestaonal Diabetes', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (4, 1, 24, NULL, '103', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (5, 1, 27, NULL, '112', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (6, 1, 26, 110, 'No', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (7, 1, 30, 128, '12.Crimean–Congo Hemorrhagic Fever (Suspected)', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (8, 1, 30, 129, '13. Cutaneous Leishmaniasis (Suspected)', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (9, 1, 30, 130, '14. Meningis Suspected Likely (Suspected Meningis', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (10, 1, 33, 149, '2. Hygiene Kit (NW)', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (11, 1, 35, NULL, '152', '2026-02-13 11:31:30');
INSERT INTO `opd_mnch_detail` VALUES (12, 2, 30, 139, '23. AT Animal Bite / An-Rabies Treatment', '2026-02-13 12:00:57');
INSERT INTO `opd_mnch_detail` VALUES (13, 2, 30, 140, '24.Mumps Viral Infecon', '2026-02-13 12:00:57');
INSERT INTO `opd_mnch_detail` VALUES (14, 2, 32, NULL, '147', '2026-02-13 12:00:57');
INSERT INTO `opd_mnch_detail` VALUES (15, 2, 33, 149, '2. Hygiene Kit (NW)', '2026-02-13 12:00:57');
INSERT INTO `opd_mnch_detail` VALUES (31, 4, 18, NULL, '82', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (32, 4, 20, NULL, '89', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (33, 4, 19, NULL, '87', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (34, 4, 21, 93, '4. Eclampsia', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (35, 4, 21, 94, '5. Gestaonal Diabetes', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (36, 4, 21, 95, '6. Asthma', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (37, 4, 21, 96, '7. Type 2 Diabetes', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (38, 4, 24, NULL, '103', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (39, 4, 27, NULL, '112', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (40, 4, 25, NULL, '107', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (41, 4, 28, NULL, '114', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (42, 4, 26, 110, 'No', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (43, 4, 29, NULL, '116', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (44, 4, 30, 141, '25. Severe Complicated Burns', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (45, 4, 30, 142, '26. Unexplained Fever', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (46, 4, 30, 143, '27.Severe Acute Malnutrion', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (47, 4, 30, 144, '28. Injury All accidental or trauma-related injuries.', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (48, 4, 30, 145, '29. Other', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (49, 4, 35, NULL, '151', '2026-02-13 23:12:40');
INSERT INTO `opd_mnch_detail` VALUES (50, 3, 26, 110, 'No', '2026-02-13 23:33:29');
INSERT INTO `opd_mnch_detail` VALUES (51, 3, 29, NULL, '116', '2026-02-13 23:33:29');
INSERT INTO `opd_mnch_detail` VALUES (52, 3, 34, NULL, 'sada', '2026-02-13 23:33:29');
INSERT INTO `opd_mnch_detail` VALUES (53, 3, 35, NULL, '152', '2026-02-13 23:33:29');
INSERT INTO `opd_mnch_detail` VALUES (60, 5, 18, NULL, '83', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (61, 5, 22, NULL, 'edddd', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (62, 5, 23, NULL, 'explainnnnn', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (63, 5, 27, NULL, '112', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (64, 5, 25, NULL, '106', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (65, 5, 30, 117, '1. Acute Upper Respiratory Tract Infecon', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (66, 5, 30, 119, '3. Severe Acute Respiratory Infecon', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (67, 5, 30, 121, '5. Acute Watery Diarrhea', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (68, 5, 33, 148, '1. Clean Delivery Kit (NW)', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (69, 5, 33, 149, '2. Hygiene Kit (NW)', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (70, 5, 33, 150, '3. Learning Kit (NW/SW)', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (71, 5, 34, NULL, 'test', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (72, 5, 35, NULL, '152', '2026-02-14 15:15:32');
INSERT INTO `opd_mnch_detail` VALUES (75, 6, 18, NULL, '83', '2026-02-14 22:57:22');
INSERT INTO `opd_mnch_detail` VALUES (76, 6, 20, NULL, '89', '2026-02-14 22:57:22');
INSERT INTO `opd_mnch_detail` VALUES (77, 7, 18, NULL, '83', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (78, 7, 20, NULL, '89', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (79, 7, 19, NULL, '86', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (80, 7, 21, 93, '4. Eclampsia', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (81, 7, 21, 94, '5. Gestaonal Diabetes', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (82, 7, 22, 123, '7. Typhoid Fever', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (83, 7, 24, NULL, '103', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (84, 7, 27, NULL, '112', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (85, 7, 25, NULL, '106', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (86, 7, 28, NULL, '114', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (87, 7, 26, 110, 'No', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (88, 7, 29, NULL, '116', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (89, 7, 30, 117, '1. Acute Upper Respiratory Tract Infecon', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (90, 7, 30, 118, '2. Lower Respiratory Tract Infecon', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (91, 7, 31, NULL, 'qweqweqw', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (92, 7, 32, NULL, '147', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (93, 7, 33, 149, '2. Hygiene Kit (NW)', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (94, 7, 33, 150, '3. Learning Kit (NW/SW)', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (95, 7, 34, 123, '7. Typhoid Fever', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (96, 7, 35, NULL, '152', '2026-02-15 11:23:16');
INSERT INTO `opd_mnch_detail` VALUES (116, 8, 18, 83, '3rd', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (117, 8, 20, 89, 'No', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (118, 8, 19, 87, '4th', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (119, 8, 21, 93, '4. Eclampsia', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (120, 8, 21, 94, '5. Gestaonal Diabetes', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (121, 8, 22, NULL, 'edddddd', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (122, 8, 23, NULL, 'explainnnnn', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (123, 8, 24, 103, 'No', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (124, 8, 27, 111, 'Yes', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (125, 8, 25, 106, '3rd', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (126, 8, 28, 114, 'No', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (127, 8, 29, 116, 'No', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (128, 8, 30, 127, '11. Dengue Fever Hemorrhagic', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (129, 8, 30, 128, '12.Crimean–Congo Hemorrhagic Fever (Suspected)', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (130, 8, 30, 145, '29. Other', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (131, 8, 31, NULL, 'qweqweqw', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (132, 8, 32, 147, 'No', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (133, 8, 33, 148, '1. Clean Delivery Kit (NW)', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (134, 8, 33, 149, '2. Hygiene Kit (NW)', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (135, 8, 33, 150, '3. Learning Kit (NW/SW)', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (136, 8, 34, NULL, 'notes', '2026-02-15 11:59:59');
INSERT INTO `opd_mnch_detail` VALUES (137, 8, 35, 151, 'Yes', '2026-02-15 11:59:59');

-- ----------------------------
-- Table structure for opd_mnch_master
-- ----------------------------
DROP TABLE IF EXISTS `opd_mnch_master`;
CREATE TABLE `opd_mnch_master`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `form_date` date NULL DEFAULT NULL,
  `anc_card_no` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `client_type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `district` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `uc` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `village` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lhv_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `patient_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `guardian_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `disability` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `age_group` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `marital_status` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pregnancy_status` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `notes` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `visit_type` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `facility_id` int NULL DEFAULT NULL,
  `qr_code` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of opd_mnch_master
-- ----------------------------
INSERT INTO `opd_mnch_master` VALUES (1, '2026-02-12', '12345', 'New', '94', '5', 'ABC', 'Razia', 'Razia', 'Sultan', 'No', '15-49', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-13 11:31:30', 'MNCH', 1, NULL, NULL);
INSERT INTO `opd_mnch_master` VALUES (2, '2026-02-19', '324', 'Followup', '94', '12', 'Attock', '1123', 'Ahmed', 'Azam', 'No', '15-49', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-13 12:00:57', 'MNCH', 1, NULL, NULL);
INSERT INTO `opd_mnch_master` VALUES (3, '2026-02-20', 'wdqd', 'New', '94', '12', 'qw', 'wqe', 'qwe', '12', 'No', '15-49', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-13 12:08:30', 'OPD', 1, NULL, NULL);
INSERT INTO `opd_mnch_master` VALUES (4, '2026-02-13', '324', 'Followup', '94', '3', '1233333333', 'wqeeeeeeee', 'e1eeeeeee', 'qweeeeeeee', 'No', '15-49', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-13 12:12:07', 'OPD', 1, NULL, NULL);
INSERT INTO `opd_mnch_master` VALUES (5, '2026-02-13', 'fads3rd', 'Followup', '94', '2', 'vilage', 'ewq', 'patient ALI', 'asd', 'No', '15-49', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-14 14:01:48', 'MNCH', 1, 2, NULL);
INSERT INTO `opd_mnch_master` VALUES (6, '2026-02-21', '123213', 'Followup', '94', '2', '1231', 'wqqqqq', 'fahad', '12312312', 'No', '15-49 Y', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-14 22:56:45', 'MNCH', 1, 2, '1231312311233');
INSERT INTO `opd_mnch_master` VALUES (7, '2026-02-19', '2131', 'Followup', '94', '1', 'ISB', 'wqeeeeeeee', '1323', '123', 'No', '50 Y +', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-15 11:23:16', 'MNCH', 1, 1, '1231');
INSERT INTO `opd_mnch_master` VALUES (8, '2026-02-20', '2131', 'Followup', '94', '2', 'Attock', '1123', 'asda', '121', 'No', '50 Y +', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-15 11:58:29', 'MNCH', 1, 2, '12311111111111');

-- ----------------------------
-- Table structure for provinces
-- ----------------------------
DROP TABLE IF EXISTS `provinces`;
CREATE TABLE `provinces`  (
  `province_id` int NOT NULL AUTO_INCREMENT,
  `province_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`province_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of provinces
-- ----------------------------
INSERT INTO `provinces` VALUES (3, 'Khyber Pakhtunkhwa');

-- ----------------------------
-- Table structure for question_options
-- ----------------------------
DROP TABLE IF EXISTS `question_options`;
CREATE TABLE `question_options`  (
  `option_id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NULL DEFAULT NULL,
  `option_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `option_order` int NULL DEFAULT NULL,
  `status` tinyint NULL DEFAULT 1,
  PRIMARY KEY (`option_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 153 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of question_options
-- ----------------------------
INSERT INTO `question_options` VALUES (1, 1, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (2, 1, 'No', 2, 1);
INSERT INTO `question_options` VALUES (3, 2, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (4, 2, 'No', 2, 1);
INSERT INTO `question_options` VALUES (5, 3, '17.2.1.  Fully immunized as per Age', 1, 1);
INSERT INTO `question_options` VALUES (6, 3, '17.2.2.  Vaccine not due', 2, 1);
INSERT INTO `question_options` VALUES (7, 3, '17.2.3.  Child is unwell', 3, 1);
INSERT INTO `question_options` VALUES (8, 3, '17.2.4.  Refusal', 4, 1);
INSERT INTO `question_options` VALUES (9, 4, 'Demand Refusal', 1, 1);
INSERT INTO `question_options` VALUES (10, 4, 'Misconception Refusal ', 2, 1);
INSERT INTO `question_options` VALUES (11, 4, 'Religious Refusal', 3, 1);
INSERT INTO `question_options` VALUES (12, 5, 'BCG', 1, 1);
INSERT INTO `question_options` VALUES (13, 5, 'PENTA I', 2, 1);
INSERT INTO `question_options` VALUES (14, 5, 'PENTA II', 3, 1);
INSERT INTO `question_options` VALUES (15, 5, 'PENTA III', 4, 1);
INSERT INTO `question_options` VALUES (16, 5, 'MR I', 5, 1);
INSERT INTO `question_options` VALUES (17, 5, 'OPV 0', 6, 1);
INSERT INTO `question_options` VALUES (18, 5, 'PCV I', 7, 1);
INSERT INTO `question_options` VALUES (19, 5, 'PCV II', 8, 1);
INSERT INTO `question_options` VALUES (20, 5, 'PCV III', 9, 1);
INSERT INTO `question_options` VALUES (21, 5, 'IPV II', 10, 1);
INSERT INTO `question_options` VALUES (22, 5, 'Hep', 11, 1);
INSERT INTO `question_options` VALUES (23, 5, 'OPV I', 12, 1);
INSERT INTO `question_options` VALUES (24, 5, 'OPV II', 13, 1);
INSERT INTO `question_options` VALUES (25, 5, 'OPV III', 14, 1);
INSERT INTO `question_options` VALUES (26, 5, 'TCV', 15, 1);
INSERT INTO `question_options` VALUES (27, 5, 'ROTA I', 16, 1);
INSERT INTO `question_options` VALUES (28, 5, 'ROTA II', 17, 1);
INSERT INTO `question_options` VALUES (29, 5, 'IPV I', 18, 1);
INSERT INTO `question_options` VALUES (30, 6, 'PENTA I', 1, 1);
INSERT INTO `question_options` VALUES (31, 6, 'PENTA II', 2, 1);
INSERT INTO `question_options` VALUES (32, 6, 'PENTA III', 3, 1);
INSERT INTO `question_options` VALUES (33, 6, 'MR I', 4, 1);
INSERT INTO `question_options` VALUES (34, 6, 'MR II', 5, 1);
INSERT INTO `question_options` VALUES (35, 6, 'PCV I', 6, 1);
INSERT INTO `question_options` VALUES (36, 6, 'PCV II', 7, 1);
INSERT INTO `question_options` VALUES (37, 6, 'OPV III', 8, 1);
INSERT INTO `question_options` VALUES (38, 6, 'IPV II', 9, 1);
INSERT INTO `question_options` VALUES (39, 6, 'OPV I', 10, 1);
INSERT INTO `question_options` VALUES (40, 6, 'OPV II', 11, 1);
INSERT INTO `question_options` VALUES (41, 6, 'IPV I', 12, 1);
INSERT INTO `question_options` VALUES (42, 6, 'TCV', 13, 1);
INSERT INTO `question_options` VALUES (43, 6, 'ROTA I', 14, 1);
INSERT INTO `question_options` VALUES (44, 6, 'ROTA II', 15, 1);
INSERT INTO `question_options` VALUES (45, 7, 'OPV I', 1, 1);
INSERT INTO `question_options` VALUES (46, 7, 'OPV II', 2, 1);
INSERT INTO `question_options` VALUES (47, 7, 'OPV III', 3, 1);
INSERT INTO `question_options` VALUES (48, 7, 'IPV II', 4, 1);
INSERT INTO `question_options` VALUES (49, 7, 'MR II', 5, 1);
INSERT INTO `question_options` VALUES (50, 7, 'PENTA I', 6, 1);
INSERT INTO `question_options` VALUES (51, 7, 'PENTA II', 7, 1);
INSERT INTO `question_options` VALUES (52, 7, 'PENTA III', 8, 1);
INSERT INTO `question_options` VALUES (53, 7, 'MR I', 9, 1);
INSERT INTO `question_options` VALUES (54, 7, 'PCV', 10, 1);
INSERT INTO `question_options` VALUES (55, 7, 'IPV I', 11, 1);
INSERT INTO `question_options` VALUES (56, 7, 'TCV', 12, 1);
INSERT INTO `question_options` VALUES (57, 8, 'OPV', 1, 1);
INSERT INTO `question_options` VALUES (58, 8, 'IPV', 2, 1);
INSERT INTO `question_options` VALUES (59, 8, 'Measles', 3, 1);
INSERT INTO `question_options` VALUES (60, 9, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (61, 9, 'No', 0, 1);
INSERT INTO `question_options` VALUES (62, 10, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (63, 10, 'No', 2, 1);
INSERT INTO `question_options` VALUES (64, 11, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (65, 11, 'No', 2, 1);
INSERT INTO `question_options` VALUES (66, 12, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (67, 12, 'No', 2, 1);
INSERT INTO `question_options` VALUES (68, 13, '1st', 1, 1);
INSERT INTO `question_options` VALUES (69, 13, '2nd', 2, 1);
INSERT INTO `question_options` VALUES (70, 13, '3rd', 3, 1);
INSERT INTO `question_options` VALUES (71, 13, '4th ', 4, 1);
INSERT INTO `question_options` VALUES (72, 13, '5th', 5, 1);
INSERT INTO `question_options` VALUES (75, 15, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (76, 15, 'No', 2, 1);
INSERT INTO `question_options` VALUES (77, 16, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (78, 16, 'No', 2, 1);
INSERT INTO `question_options` VALUES (79, 17, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (80, 17, 'No', 2, 1);
INSERT INTO `question_options` VALUES (81, 18, '1st', 1, 1);
INSERT INTO `question_options` VALUES (82, 18, '2nd', 2, 1);
INSERT INTO `question_options` VALUES (83, 18, '3rd', 3, 1);
INSERT INTO `question_options` VALUES (84, 19, '1st', 1, 1);
INSERT INTO `question_options` VALUES (85, 19, '2nd', 2, 1);
INSERT INTO `question_options` VALUES (86, 19, '3rd', 3, 1);
INSERT INTO `question_options` VALUES (87, 19, '4th', 4, 1);
INSERT INTO `question_options` VALUES (88, 20, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (89, 20, 'No', 2, 1);
INSERT INTO `question_options` VALUES (90, 21, '1. Anemia', 1, 1);
INSERT INTO `question_options` VALUES (91, 21, '2. Gestaonal Hypertension', 2, 1);
INSERT INTO `question_options` VALUES (92, 21, '3. Pre-Eclampsia', 3, 1);
INSERT INTO `question_options` VALUES (93, 21, '4. Eclampsia', 4, 1);
INSERT INTO `question_options` VALUES (94, 21, '5. Gestaonal Diabetes', 5, 1);
INSERT INTO `question_options` VALUES (95, 21, '6. Asthma', 6, 1);
INSERT INTO `question_options` VALUES (96, 21, '7. Type 2 Diabetes', 7, 1);
INSERT INTO `question_options` VALUES (97, 21, '8. Urinary Tract Infecon', 8, 1);
INSERT INTO `question_options` VALUES (98, 21, '9. Hepas', 9, 1);
INSERT INTO `question_options` VALUES (99, 21, '10. Obstetric', 10, 1);
INSERT INTO `question_options` VALUES (100, 21, '11. Pulmonary Embolism', 11, 1);
INSERT INTO `question_options` VALUES (101, 21, '12.Deep Vein Thrombosis', 12, 1);
INSERT INTO `question_options` VALUES (102, 24, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (103, 24, 'No', 2, 1);
INSERT INTO `question_options` VALUES (104, 25, '1st', 1, 1);
INSERT INTO `question_options` VALUES (105, 25, '2nd', 2, 1);
INSERT INTO `question_options` VALUES (106, 25, '3rd', 3, 1);
INSERT INTO `question_options` VALUES (107, 25, '4th', 4, 1);
INSERT INTO `question_options` VALUES (108, 25, '5th', 7, 1);
INSERT INTO `question_options` VALUES (109, 26, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (110, 26, 'No', 2, 1);
INSERT INTO `question_options` VALUES (111, 27, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (112, 27, 'No', 2, 1);
INSERT INTO `question_options` VALUES (113, 28, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (114, 28, 'No', 2, 1);
INSERT INTO `question_options` VALUES (115, 29, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (116, 29, 'No', 2, 1);
INSERT INTO `question_options` VALUES (117, 30, '1. Acute Upper Respiratory Tract Infecon', 1, 1);
INSERT INTO `question_options` VALUES (118, 30, '2. Lower Respiratory Tract Infecon', 2, 1);
INSERT INTO `question_options` VALUES (119, 30, '3. Severe Acute Respiratory Infecon', 3, 1);
INSERT INTO `question_options` VALUES (120, 30, '4. Acute Diarrhea', 4, 1);
INSERT INTO `question_options` VALUES (121, 30, '5. Acute Watery Diarrhea', 5, 1);
INSERT INTO `question_options` VALUES (122, 30, '6. Bloody Diarrhea (Bacillary Dysentery)', 6, 1);
INSERT INTO `question_options` VALUES (123, 30, '7. Typhoid Fever', 7, 1);
INSERT INTO `question_options` VALUES (124, 30, '8. Acute Viral Hepas', 8, 1);
INSERT INTO `question_options` VALUES (125, 30, '9. Suspected Measles', 9, 1);
INSERT INTO `question_options` VALUES (126, 30, '10. Acute Hemorrhagic Fever', 10, 1);
INSERT INTO `question_options` VALUES (127, 30, '11. Dengue Fever Hemorrhagic', 11, 1);
INSERT INTO `question_options` VALUES (128, 30, '12.Crimean–Congo Hemorrhagic Fever (Suspected)', 12, 1);
INSERT INTO `question_options` VALUES (129, 30, '13. Cutaneous Leishmaniasis (Suspected)', 13, 1);
INSERT INTO `question_options` VALUES (130, 30, '14. Meningis Suspected Likely (Suspected Meningis', 14, 1);
INSERT INTO `question_options` VALUES (131, 30, '15.Diphtheria.', 15, 1);
INSERT INTO `question_options` VALUES (132, 30, '16. Pertussis (Whooping Cough)', 16, 1);
INSERT INTO `question_options` VALUES (133, 30, '17. Neonatal Tetanus (Suspected)', 17, 1);
INSERT INTO `question_options` VALUES (134, 30, '18.Acute flaccid paralysis', 18, 1);
INSERT INTO `question_options` VALUES (135, 30, '19. Tuberculosis – Confirmed', 19, 1);
INSERT INTO `question_options` VALUES (136, 30, '20. Meningis (Suspected)', 20, 1);
INSERT INTO `question_options` VALUES (137, 30, '21.Chronic Viral Hepas B suspected', 21, 1);
INSERT INTO `question_options` VALUES (138, 30, '22. Chronic Viral Hepas C', 22, 1);
INSERT INTO `question_options` VALUES (139, 30, '23. AT Animal Bite / An-Rabies Treatment', 23, 1);
INSERT INTO `question_options` VALUES (140, 30, '24.Mumps Viral Infecon', 24, 1);
INSERT INTO `question_options` VALUES (141, 30, '25. Severe Complicated Burns', 25, 1);
INSERT INTO `question_options` VALUES (142, 30, '26. Unexplained Fever', 26, 1);
INSERT INTO `question_options` VALUES (143, 30, '27.Severe Acute Malnutrion', 27, 1);
INSERT INTO `question_options` VALUES (144, 30, '28. Injury All accidental or trauma-related injuries.', 28, 1);
INSERT INTO `question_options` VALUES (145, 30, '29. Other', 27, 1);
INSERT INTO `question_options` VALUES (146, 32, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (147, 32, 'No', 2, 1);
INSERT INTO `question_options` VALUES (148, 33, '1. Clean Delivery Kit (NW)', 1, 1);
INSERT INTO `question_options` VALUES (149, 33, '2. Hygiene Kit (NW)', 2, 1);
INSERT INTO `question_options` VALUES (150, 33, '3. Learning Kit (NW/SW)', 3, 1);
INSERT INTO `question_options` VALUES (151, 35, 'Yes', 1, 1);
INSERT INTO `question_options` VALUES (152, 35, 'No', 2, 1);

-- ----------------------------
-- Table structure for questions
-- ----------------------------
DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions`  (
  `question_id` int NOT NULL AUTO_INCREMENT,
  `form_type` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `q_section` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `q_num` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `q_text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `q_order` int NULL DEFAULT NULL,
  `q_type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` tinyint NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`question_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of questions
-- ----------------------------
INSERT INTO `questions` VALUES (1, 'CHF', '17. Vaccination History (For Under Five Years Children)', '17.1.', 'Ever received any Vaccination earlier?', 1, 'radio', 1, '2026-02-04 13:47:50');
INSERT INTO `questions` VALUES (2, 'CHF', '17. Vaccination History (For Under Five Years Children)', '17.2. ', 'Child vaccinated during this session.', 2, 'radio', 1, '2026-02-04 13:48:53');
INSERT INTO `questions` VALUES (3, 'CHF', '17. Vaccination History (For Under Five Years Children)', '17.2', 'In case of \"No\" to 17.2', 3, 'checkbox', 1, '2026-02-04 13:51:09');
INSERT INTO `questions` VALUES (4, 'CHF', '17. Vaccination History (For Under Five Years Children)', '17.2.4', 'In case of \"Yes\" to 17.2.4\r\n1. Type of Refusal.', 4, 'checkbox', 1, '2026-02-04 13:52:54');
INSERT INTO `questions` VALUES (5, 'CHF', '18. Antigens Administered to child < 1 year', '', '', 5, 'checkbox', 1, '2026-02-04 13:58:03');
INSERT INTO `questions` VALUES (6, 'CHF', '19. Antigens Administered to child 1-2 year', '', '', 6, 'checkbox', 1, '2026-02-04 14:04:40');
INSERT INTO `questions` VALUES (7, 'CHF', '20. Antigens Administered to child 2-5 year', '', '', 7, 'checkbox', 1, '2026-02-04 14:08:02');
INSERT INTO `questions` VALUES (8, 'CHF', '21. Vaccination', '', '21. Supplementary Vaccination', 8, 'checkbox', 1, '2026-02-05 06:09:42');
INSERT INTO `questions` VALUES (9, 'CHF', '21. Vaccination', '', '22. Vaccination Card Issued to the client', 8, 'checkbox', 1, '2026-02-05 06:12:24');
INSERT INTO `questions` VALUES (10, 'CHF', '21. Vaccination', '', '23. Adverse Event Following Immunization (AEFI)', 8, 'checkbox', 1, '2026-02-05 06:14:53');
INSERT INTO `questions` VALUES (11, 'CHF', '21. Vaccination', '', '24. Referred', 8, 'checkbox', 1, '2026-02-05 06:15:32');
INSERT INTO `questions` VALUES (12, 'CHF', '22. CBA Vaccination (For Females age from 15-49 years)', '', '1. Tetanus Vaccine Administered: (If yes skip Q2)', 9, 'radio', 1, '2026-02-05 06:17:15');
INSERT INTO `questions` VALUES (13, 'CHF', '22. CBA Vaccination (For Females age from 15-49 years)', '', '1.1. In case of Yes, mention Dose:', 9, 'radio', 1, '2026-02-05 06:19:07');
INSERT INTO `questions` VALUES (15, 'CHF', 'If the answer to Question 22 is \'No\', please select a reason from options 2.1 to 2.3:', '', '2.1 Refused for TT:', 10, 'checkbox', 1, '2026-02-05 06:21:38');
INSERT INTO `questions` VALUES (16, 'CHF', 'If the answer to Question 22 is \'No\', please select a reason from options 2.1 to 2.3:', '', '2.2 Complete TT Schedule: ', 10, 'checkbox', 1, '2026-02-05 06:22:25');
INSERT INTO `questions` VALUES (17, 'CHF', 'If the answer to Question 22 is \'No\', please select a reason from options 2.1 to 2.3:', '', '2.3 Dose Not Due: ', 10, 'checkbox', 1, '2026-02-05 06:22:50');
INSERT INTO `questions` VALUES (18, 'opd', '18. Antenatal Care', '1', 'Trimester', 1, 'radio', 1, '2026-02-06 06:49:13');
INSERT INTO `questions` VALUES (19, 'opd', '18. Antenatal Care', '2', 'Visit', 2, 'radio', 1, '2026-02-06 06:50:13');
INSERT INTO `questions` VALUES (20, 'opd', '18. Antenatal Care', '3', 'Any\r\ncomplicaon?', 1, 'radio', 1, '2026-02-06 06:50:44');
INSERT INTO `questions` VALUES (21, 'opd', '18. Antenatal Care', '4', 'If “Yes” what is the complicaon', 4, 'checkbox', 1, '2026-02-06 06:53:44');
INSERT INTO `questions` VALUES (22, 'opd', '18. Antenatal Care', '5', 'EDD:', 5, 'text', 1, '2026-02-06 06:54:10');
INSERT INTO `questions` VALUES (23, 'opd', '18. Antenatal Care', '6', 'Explain:', 6, 'text', 1, '2026-02-06 06:54:34');
INSERT INTO `questions` VALUES (24, 'opd', '19. Vaccinaon Services Provided (For Females age from 15-49 years)', '1.', 'Tetanus Vaccine Administered (If yes skip Q2)', 1, 'radio', 1, '2026-02-06 06:58:44');
INSERT INTO `questions` VALUES (25, 'opd', '19. Vaccinaon Services Provided (For Females age from 15-49 years)', '1.1.', 'In case of Yes, menon Dose', 2, 'radio', 1, '2026-02-06 07:00:24');
INSERT INTO `questions` VALUES (26, 'opd', '19. Vaccinaon Services Provided (For Females age from 15-49 years)', '1.2.', 'TD Card issued:', 3, 'checkbox', 1, '2026-02-06 07:01:56');
INSERT INTO `questions` VALUES (27, 'opd', '19. Vaccinaon Services Provided (For Females age from 15-49 years)', '2.1', 'Refused for TT ', 1, 'radio', 1, '2026-02-06 07:03:42');
INSERT INTO `questions` VALUES (28, 'opd', '19. Vaccinaon Services Provided (For Females age from 15-49 years)', '2.2', 'Complete TT Schedule', 2, 'radio', 1, '2026-02-06 07:04:30');
INSERT INTO `questions` VALUES (29, 'opd', '19. Vaccinaon Services Provided (For Females age from 15-49 years)', '2.3', 'Dose Not Due', 3, 'radio', 1, '2026-02-06 07:05:11');
INSERT INTO `questions` VALUES (30, 'opd', '20. Diagnoses', NULL, '', 1, 'checkbox', 1, '2026-02-06 07:14:13');
INSERT INTO `questions` VALUES (31, 'opd', '20. Diagnoses', NULL, 'If Other Explain:', 2, 'text', 1, '2026-02-06 07:15:14');
INSERT INTO `questions` VALUES (32, 'opd', '21. Prescribed Medicines', '19.', 'Prescribed Medicines Issued:', 1, 'radio', 1, '2026-02-06 07:21:11');
INSERT INTO `questions` VALUES (33, 'opd', '21. Prescribed Medicines', '20.', '20. Kits Issued', 1, 'checkbox', 1, '2026-02-06 07:22:20');
INSERT INTO `questions` VALUES (34, 'opd', '21. Prescribed Medicines', '', 'Addional Notes:', 3, 'text', 1, '2026-02-06 07:23:02');
INSERT INTO `questions` VALUES (35, 'opd', '21. Referral', '', 'Referred:', 1, 'radio', 1, '2026-02-06 07:23:45');

-- ----------------------------
-- Table structure for uc
-- ----------------------------
DROP TABLE IF EXISTS `uc`;
CREATE TABLE `uc`  (
  `pk_id` int NOT NULL AUTO_INCREMENT,
  `district_id` int NULL DEFAULT NULL,
  `uc` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`pk_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of uc
-- ----------------------------
INSERT INTO `uc` VALUES (1, 94, 'Datta Khel-1');
INSERT INTO `uc` VALUES (2, 94, 'Datta Khel-2');
INSERT INTO `uc` VALUES (3, 94, 'Datta Khel-3');
INSERT INTO `uc` VALUES (4, 94, 'Mir Ali-5');
INSERT INTO `uc` VALUES (5, 94, 'Mir Ali-6');
INSERT INTO `uc` VALUES (6, 94, 'Mir Ali-7');
INSERT INTO `uc` VALUES (7, 94, 'Shewa-1');
INSERT INTO `uc` VALUES (8, 94, 'Shewa-2');
INSERT INTO `uc` VALUES (9, 94, 'Spinwam-1');
INSERT INTO `uc` VALUES (10, 94, 'Spinwam-2');
INSERT INTO `uc` VALUES (11, 94, 'Garyum');
INSERT INTO `uc` VALUES (12, 94, 'Razmak');
INSERT INTO `uc` VALUES (13, 94, 'Dossali-2');

-- ----------------------------
-- Table structure for user_access_log
-- ----------------------------
DROP TABLE IF EXISTS `user_access_log`;
CREATE TABLE `user_access_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ip_address` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `login_time` datetime NULL DEFAULT NULL,
  `logout_time` datetime NULL DEFAULT NULL,
  `login_success` tinyint(1) NULL DEFAULT 0,
  `remarks` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user_access_log
-- ----------------------------
INSERT INTO `user_access_log` VALUES (1, 1, 'internee1', '::1', '2026-02-13 06:37:48', '2026-02-13 06:42:25', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (2, 1, 'internee1', '::1', '2026-02-13 06:42:29', '2026-02-13 06:43:35', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (3, 1, 'internee1', '::1', '2026-02-13 06:43:57', '2026-02-13 06:44:22', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (4, 1, 'internee1', '::1', '2026-02-13 06:44:35', '2026-02-13 07:46:47', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (5, 1, 'internee1', '::1', '2026-02-13 07:46:51', NULL, 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (6, 1, 'internee1', '::1', '2026-02-13 14:36:22', NULL, 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (7, 1, 'internee1', '::1', '2026-02-13 18:58:17', '2026-02-13 19:07:49', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (8, 2, 'internee2', '::1', '2026-02-13 19:07:57', '2026-02-13 19:08:15', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (9, 1, 'internee1', '::1', '2026-02-13 19:08:28', NULL, 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (10, 0, '', '::1', '2026-02-14 09:09:36', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (11, 1, 'internee1', '::1', '2026-02-14 09:10:05', '2026-02-14 09:15:41', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (12, 0, '', '::1', '2026-02-14 09:15:45', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (13, 0, '', '::1', '2026-02-14 09:15:50', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (14, 0, '', '::1', '2026-02-14 09:15:56', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (15, 0, '', '::1', '2026-02-14 09:16:00', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (16, 1, 'internee1', '::1', '2026-02-14 09:16:06', '2026-02-14 11:29:37', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (17, 2, 'internee2', '::1', '2026-02-14 11:29:42', '2026-02-14 11:35:58', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (18, 5, 'admin', '::1', '2026-02-14 11:36:02', '2026-02-14 11:36:15', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (19, 2, 'internee2', '::1', '2026-02-14 11:36:20', NULL, 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (20, 0, '', '::1', '2026-02-14 15:41:53', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (21, 0, '', '::1', '2026-02-14 15:41:53', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (22, 1, 'internee1', '::1', '2026-02-14 15:41:58', NULL, 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (23, 0, '', '::1', '2026-02-15 06:33:12', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (24, 0, 'Administrator', '::1', '2026-02-15 06:33:14', NULL, 0, 'Invalid credentials');
INSERT INTO `user_access_log` VALUES (25, 1, 'internee1', '::1', '2026-02-15 06:33:19', NULL, 1, 'Login successful');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `province_id` int NULL DEFAULT NULL,
  `district_id` int NULL DEFAULT NULL,
  `role` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NULL DEFAULT 1,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'internee1', 'internee1', '202cb962ac59075b964b07152d234b70', 3, 94, '1', '2026-02-03 14:08:56', 1);
INSERT INTO `users` VALUES (2, 'internee2', 'internee2', '202cb962ac59075b964b07152d234b70', 3, 94, '1', '2026-02-13 09:04:44', 1);
INSERT INTO `users` VALUES (3, 'tawha', 'tawha', '202cb962ac59075b964b07152d234b70', 3, 94, '1', '2026-02-13 09:04:50', 1);
INSERT INTO `users` VALUES (4, 'readonly', 'readonly', '202cb962ac59075b964b07152d234b70', 3, 94, '2', '2026-02-13 09:05:19', 1);
INSERT INTO `users` VALUES (5, 'Admin', 'admin', '202cb962ac59075b964b07152d234b70', 3, 94, '3', '2026-02-14 15:35:01', 1);

SET FOREIGN_KEY_CHECKS = 1;
