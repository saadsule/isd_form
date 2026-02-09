/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50621
 Source Host           : 127.0.0.1:3306
 Source Schema         : isd_form

 Target Server Type    : MySQL
 Target Server Version : 50621
 File Encoding         : 65001

 Date: 09/02/2026 16:11:14
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for child_health_detail
-- ----------------------------
DROP TABLE IF EXISTS `child_health_detail`;
CREATE TABLE `child_health_detail`  (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `master_id` int(11) NULL DEFAULT NULL,
  `question_id` int(11) NULL DEFAULT NULL,
  `option_id` int(11) NULL DEFAULT NULL,
  `answer` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`detail_id`) USING BTREE,
  INDEX `master_id`(`master_id`) USING BTREE,
  INDEX `question_id`(`question_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 124 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of child_health_detail
-- ----------------------------
INSERT INTO `child_health_detail` VALUES (94, 5, 2, 3, 'Yes', '2026-02-05 12:13:23');
INSERT INTO `child_health_detail` VALUES (95, 5, 4, 9, 'Demand Refusal', '2026-02-05 12:13:23');
INSERT INTO `child_health_detail` VALUES (96, 5, 6, 36, 'PCV II', '2026-02-05 12:13:23');
INSERT INTO `child_health_detail` VALUES (97, 5, 6, 37, 'OPV III', '2026-02-05 12:13:23');
INSERT INTO `child_health_detail` VALUES (98, 5, 7, 49, 'MR II', '2026-02-05 12:13:23');
INSERT INTO `child_health_detail` VALUES (99, 5, 7, 50, 'PENTA I', '2026-02-05 12:13:23');
INSERT INTO `child_health_detail` VALUES (100, 5, 7, 51, 'PENTA II', '2026-02-05 12:13:23');
INSERT INTO `child_health_detail` VALUES (101, 5, 16, 77, 'Yes', '2026-02-05 12:13:23');
INSERT INTO `child_health_detail` VALUES (102, 7, 1, 2, 'No', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (103, 7, 2, 4, 'No', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (104, 7, 3, 5, '17.2.1.  Fully immunized as per Age', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (105, 7, 4, 10, 'Misconception Refusal ', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (106, 7, 5, 12, 'BCG', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (107, 7, 5, 16, 'MR I', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (108, 7, 5, 17, 'OPV 0', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (109, 7, 5, 18, 'PCV I', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (110, 7, 6, 31, 'PENTA II', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (111, 7, 6, 32, 'PENTA III', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (112, 7, 8, 57, 'OPV', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (113, 7, 15, 76, 'No', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (114, 7, 16, 77, 'Yes', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (115, 7, 17, 79, 'Yes', '2026-02-05 13:21:16');
INSERT INTO `child_health_detail` VALUES (116, 9, 2, 3, 'Yes', '2026-02-06 21:57:42');
INSERT INTO `child_health_detail` VALUES (117, 9, 2, 4, 'No', '2026-02-06 21:57:42');
INSERT INTO `child_health_detail` VALUES (118, 9, 7, 49, 'MR II', '2026-02-06 21:57:42');
INSERT INTO `child_health_detail` VALUES (119, 9, 7, 50, 'PENTA I', '2026-02-06 21:57:42');
INSERT INTO `child_health_detail` VALUES (120, 9, 7, 51, 'PENTA II', '2026-02-06 21:57:42');
INSERT INTO `child_health_detail` VALUES (121, 9, 7, 56, 'TCV', '2026-02-06 21:57:42');
INSERT INTO `child_health_detail` VALUES (122, 9, 10, 63, 'No', '2026-02-06 21:57:42');
INSERT INTO `child_health_detail` VALUES (123, 10, 7, 48, 'IPV II', '2026-02-09 16:09:54');

-- ----------------------------
-- Table structure for child_health_master
-- ----------------------------
DROP TABLE IF EXISTS `child_health_master`;
CREATE TABLE `child_health_master`  (
  `master_id` int(11) NOT NULL AUTO_INCREMENT,
  `form_date` date NULL DEFAULT NULL,
  `qr_code` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `client_type` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `district` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `uc` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `village` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `vaccinator_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `patient_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `guardian_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dob` date NULL DEFAULT NULL,
  `age_year` int(11) NULL DEFAULT NULL,
  `age_month` int(11) NULL DEFAULT NULL,
  `age_day` int(11) NULL DEFAULT NULL,
  `gender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `marital_status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pregnancy_status` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `disability` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `play_learning_kit` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nutrition_package` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`master_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of child_health_master
-- ----------------------------
INSERT INTO `child_health_master` VALUES (5, '2026-02-13', '1231', NULL, 'Attock', 'Abc', 'Attock', '2131', 'Saad', 'Azam', '0000-00-00', 11, 12, 12, 'Male', NULL, 'Non-Pregnant', 'No', 'No', 'No', '2026-02-05 12:13:23');
INSERT INTO `child_health_master` VALUES (6, '0000-00-00', '', 'New', '', '', '', '', '', '', '0000-00-00', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-05 12:14:07');
INSERT INTO `child_health_master` VALUES (7, '2026-02-03', '1231', NULL, 'Islamabad', 'ISB', 'ISB', 'XYZ', 'Ahmed', '', '2026-02-13', 222, 1, 12, 'Male', 'Married', 'Non-Pregnant', NULL, NULL, NULL, '2026-02-05 13:21:16');
INSERT INTO `child_health_master` VALUES (8, '0000-00-00', '', NULL, '', '', '', '', '', '', '0000-00-00', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-06 11:38:09');
INSERT INTO `child_health_master` VALUES (9, '2026-02-28', '123', NULL, 'Attock', 'Abc', 'Attock', '2131', 'Saad', 'Azam', '0000-00-00', 123, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-06 21:57:42');
INSERT INTO `child_health_master` VALUES (10, '2026-02-10', '', 'New', '', '', '', '', '', '', '0000-00-00', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-09 16:09:54');

-- ----------------------------
-- Table structure for districts
-- ----------------------------
DROP TABLE IF EXISTS `districts`;
CREATE TABLE `districts`  (
  `district_id` int(11) NOT NULL AUTO_INCREMENT,
  `district_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `province_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`district_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of districts
-- ----------------------------

-- ----------------------------
-- Table structure for opd_mnch_detail
-- ----------------------------
DROP TABLE IF EXISTS `opd_mnch_detail`;
CREATE TABLE `opd_mnch_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `master_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_id` int(11) NULL DEFAULT NULL,
  `answer` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of opd_mnch_detail
-- ----------------------------
INSERT INTO `opd_mnch_detail` VALUES (1, 1, 19, 85, '2nd', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (2, 1, 19, 86, '3rd', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (3, 1, 21, 91, '2. Gestaonal Hypertension', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (4, 1, 21, 92, '3. Pre-Eclampsia', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (5, 1, 22, 123, '7. Typhoid Fever', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (6, 1, 23, 0, '', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (7, 1, 30, 131, '15.Diphtheria.', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (8, 1, 30, 132, '16. Pertussis (Whooping Cough)', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (9, 1, 30, 134, '18.Acute flaccid paralysis', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (10, 1, 30, 145, '29. Other', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (11, 1, 31, 0, '', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (12, 1, 32, 147, 'No', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (13, 1, 33, 150, '3. Learning Kit (NW/SW)', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (14, 1, 34, 2312, '', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (15, 1, 28, 113, 'Yes', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (16, 1, 35, 152, 'No', '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_detail` VALUES (17, 2, 19, 86, '3rd', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (18, 2, 22, NULL, 'edddddd', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (19, 2, 23, NULL, 'explainnnnn', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (20, 2, 30, 132, '16. Pertussis (Whooping Cough)', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (21, 2, 30, 136, '20. Meningis (Suspected)', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (22, 2, 34, NULL, 'notessssssssssssssssss', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (23, 2, 25, 104, '1st', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (24, 2, 25, 105, '2nd', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (25, 2, 25, 106, '3rd', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (26, 2, 28, 113, 'Yes', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (27, 2, 35, 152, 'No', '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_detail` VALUES (28, 3, 21, 98, '9. Hepas', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (29, 3, 21, 99, '10. Obstetric', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (30, 3, 22, NULL, 'eeeeddd', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (31, 3, 23, NULL, 'exxp', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (32, 3, 30, 122, '6. Bloody Diarrhea (Bacillary Dysentery)', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (33, 3, 30, 124, '8. Acute Viral Hepas', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (34, 3, 30, 125, '9. Suspected Measles', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (35, 3, 31, NULL, 'qweqweqw', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (36, 3, 34, NULL, 'notess', '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_detail` VALUES (37, 3, 35, 152, 'No', '2026-02-06 21:55:45');

-- ----------------------------
-- Table structure for opd_mnch_master
-- ----------------------------
DROP TABLE IF EXISTS `opd_mnch_master`;
CREATE TABLE `opd_mnch_master`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of opd_mnch_master
-- ----------------------------
INSERT INTO `opd_mnch_master` VALUES (1, '2026-02-05', '324', 'New', 'Attock', 'ISB', 'Attock', '1123', 'Saad', 'Azam', 'No', '15-49', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-06 11:30:23');
INSERT INTO `opd_mnch_master` VALUES (2, '2026-02-27', '2131', 'Followup', 'Attock', 'Abc', 'ISB', '', '', '', NULL, '15-49', NULL, 'Pregnant', NULL, '2026-02-06 21:48:34');
INSERT INTO `opd_mnch_master` VALUES (3, '2026-03-06', '1231', NULL, 'Attock', 'ISB', '', '', '', '', 'No', NULL, 'Married', 'Non-Pregnant', NULL, '2026-02-06 21:55:45');
INSERT INTO `opd_mnch_master` VALUES (4, '0000-00-00', '', NULL, '', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, '2026-02-09 15:57:37');

-- ----------------------------
-- Table structure for provinces
-- ----------------------------
DROP TABLE IF EXISTS `provinces`;
CREATE TABLE `provinces`  (
  `province_id` int(11) NOT NULL AUTO_INCREMENT,
  `province_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`province_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of provinces
-- ----------------------------

-- ----------------------------
-- Table structure for question_options
-- ----------------------------
DROP TABLE IF EXISTS `question_options`;
CREATE TABLE `question_options`  (
  `option_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NULL DEFAULT NULL,
  `option_text` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `option_order` int(11) NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT 1,
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
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `form_type` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `q_section` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `q_num` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `q_text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `q_order` int(11) NULL DEFAULT NULL,
  `q_type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `status` tinyint(4) NULL DEFAULT 1,
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
INSERT INTO `questions` VALUES (8, 'CHF', '21. Vaccination', '', '21. Supplementary Vaccination', 1, 'checkbox', 1, '2026-02-05 06:09:42');
INSERT INTO `questions` VALUES (9, 'CHF', '21. Vaccination', '', '22. Vaccination Card Issued to the client', 2, 'checkbox', 1, '2026-02-05 06:12:24');
INSERT INTO `questions` VALUES (10, 'CHF', '21. Vaccination', '', '23. Adverse Event Following Immunization (AEFI)', 3, 'checkbox', 1, '2026-02-05 06:14:53');
INSERT INTO `questions` VALUES (11, 'CHF', '21. Vaccination', '', '24. Referred', 4, 'checkbox', 1, '2026-02-05 06:15:32');
INSERT INTO `questions` VALUES (12, 'CHF', '22. CBA Vaccination (For Females age from 15-49 years)', '', '1. Tetanus Vaccine Administered: (If yes skip Q2)', 1, 'radio', 1, '2026-02-05 06:17:15');
INSERT INTO `questions` VALUES (13, 'CHF', '22. CBA Vaccination (For Females age from 15-49 years)', '', '1.1. In case of Yes, mention Dose:', 2, 'radio', 1, '2026-02-05 06:19:07');
INSERT INTO `questions` VALUES (15, 'CHF', 'If the answer to Question 22 is \'No\', please select a reason from options 2.1 to 2.3:', '', '2.1 Refused for TT:', 1, 'checkbox', 1, '2026-02-05 06:21:38');
INSERT INTO `questions` VALUES (16, 'CHF', 'If the answer to Question 22 is \'No\', please select a reason from options 2.1 to 2.3:', '', '2.2 Complete TT Schedule: ', 2, 'checkbox', 1, '2026-02-05 06:22:25');
INSERT INTO `questions` VALUES (17, 'CHF', 'If the answer to Question 22 is \'No\', please select a reason from options 2.1 to 2.3:', '', '2.3 Dose Not Due: ', 3, 'checkbox', 1, '2026-02-05 06:22:50');
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
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `province_id` int(11) NULL DEFAULT NULL,
  `district_id` int(11) NULL DEFAULT NULL,
  `role` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NULL DEFAULT 1,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Saadi', 'Administrator', '202cb962ac59075b964b07152d234b70', 1, 1, NULL, '2026-02-03 14:08:56', 1);

SET FOREIGN_KEY_CHECKS = 1;
