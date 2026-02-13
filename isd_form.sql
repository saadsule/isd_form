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

 Date: 13/02/2026 11:45:22
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
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

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
  PRIMARY KEY (`master_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of child_health_master
-- ----------------------------
INSERT INTO `child_health_master` VALUES (1, '2026-02-20', '1231', 'New', '1', '6', 'Bannu', 'No', 'Shagufta', 'Khan', '1993-02-10', NULL, NULL, NULL, 'Female', NULL, NULL, NULL, NULL, NULL, '2026-02-13 11:32:53', 'Outreach');

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
INSERT INTO `districts` VALUES (1, 'North Waziristan', 1);

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of form_logs
-- ----------------------------
INSERT INTO `form_logs` VALUES (1, 'child_health_form', 12, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-20\",\"qr_code\":\"1231\",\"client_type\":\"Followup\",\"district\":\"1\",\"uc\":\"10\",\"village\":\"Attock\",\"vaccinator_name\":\"2131\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"dob\":\"2026-03-13\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Male\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":12,\"question_id\":1,\"option_id\":null,\"answer\":\"1\"},{\"master_id\":12,\"question_id\":2,\"option_id\":null,\"answer\":\"4\"},{\"master_id\":12,\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":12,\"question_id\":3,\"option_id\":\"7\",\"answer\":\"17.2.3.  Child is unwell\"},{\"master_id\":12,\"question_id\":6,\"option_id\":\"30\",\"answer\":\"PENTA I\"},{\"master_id\":12,\"question_id\":6,\"option_id\":\"31\",\"answer\":\"PENTA II\"}]}', '2026-02-13 10:45:58');
INSERT INTO `form_logs` VALUES (2, 'opd_mnch_form', 7, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-28\",\"anc_card_no\":\"324\",\"client_type\":\"Followup\",\"district\":\"1\",\"uc\":\"5\",\"village\":\"Attock\",\"lhv_name\":\"1123\",\"patient_name\":\"Saad\",\"guardian_name\":\"Azam\",\"disability\":\"No\",\"age_group\":\"1-5\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":7,\"question_id\":18,\"option_id\":null,\"answer\":\"83\"},{\"master_id\":7,\"question_id\":20,\"option_id\":null,\"answer\":\"88\"},{\"master_id\":7,\"question_id\":21,\"option_id\":\"98\",\"answer\":\"9. Hepas\"},{\"master_id\":7,\"question_id\":21,\"option_id\":\"99\",\"answer\":\"10. Obstetric\"}]}', '2026-02-13 10:54:14');
INSERT INTO `form_logs` VALUES (3, 'opd_mnch_form', 1, '{\"master\":{\"visit_type\":\"MNCH\",\"form_date\":\"2026-02-12\",\"anc_card_no\":\"12345\",\"client_type\":\"New\",\"district\":\"1\",\"uc\":\"5\",\"village\":\"ABC\",\"lhv_name\":\"Razia\",\"patient_name\":\"Razia\",\"guardian_name\":\"Sultan\",\"disability\":\"No\",\"age_group\":\"15-49\",\"marital_status\":\"Unmarried\",\"pregnancy_status\":\"Non-Pregnant\",\"notes\":null},\"details\":[{\"master_id\":1,\"question_id\":18,\"option_id\":null,\"answer\":\"82\"},{\"master_id\":1,\"question_id\":21,\"option_id\":\"93\",\"answer\":\"4. Eclampsia\"},{\"master_id\":1,\"question_id\":21,\"option_id\":\"94\",\"answer\":\"5. Gestaonal Diabetes\"},{\"master_id\":1,\"question_id\":24,\"option_id\":null,\"answer\":\"103\"},{\"master_id\":1,\"question_id\":27,\"option_id\":null,\"answer\":\"112\"},{\"master_id\":1,\"question_id\":26,\"option_id\":\"110\",\"answer\":\"No\"},{\"master_id\":1,\"question_id\":30,\"option_id\":\"128\",\"answer\":\"12.Crimean\\u2013Congo Hemorrhagic Fever (Suspected)\"},{\"master_id\":1,\"question_id\":30,\"option_id\":\"129\",\"answer\":\"13. Cutaneous Leishmaniasis (Suspected)\"},{\"master_id\":1,\"question_id\":30,\"option_id\":\"130\",\"answer\":\"14. Meningis Suspected Likely (Suspected Meningis\"},{\"master_id\":1,\"question_id\":33,\"option_id\":\"149\",\"answer\":\"2. Hygiene Kit (NW)\"},{\"master_id\":1,\"question_id\":35,\"option_id\":null,\"answer\":\"152\"}]}', '2026-02-13 11:31:30');
INSERT INTO `form_logs` VALUES (4, 'child_health_form', 1, '{\"master\":{\"visit_type\":\"Outreach\",\"form_date\":\"2026-02-20\",\"qr_code\":\"1231\",\"client_type\":\"New\",\"district\":\"1\",\"uc\":\"6\",\"village\":\"Bannu\",\"vaccinator_name\":\"No\",\"patient_name\":\"Shagufta\",\"guardian_name\":\"Khan\",\"dob\":\"1993-02-10\",\"age_year\":null,\"age_month\":null,\"age_day\":null,\"gender\":\"Female\",\"marital_status\":null,\"pregnancy_status\":null,\"disability\":null,\"play_learning_kit\":null,\"nutrition_package\":null},\"details\":[{\"master_id\":1,\"question_id\":1,\"option_id\":null,\"answer\":\"2\"},{\"master_id\":1,\"question_id\":2,\"option_id\":null,\"answer\":\"4\"},{\"master_id\":1,\"question_id\":3,\"option_id\":\"6\",\"answer\":\"17.2.2.  Vaccine not due\"},{\"master_id\":1,\"question_id\":4,\"option_id\":\"11\",\"answer\":\"Religious Refusal\"},{\"master_id\":1,\"question_id\":5,\"option_id\":\"15\",\"answer\":\"PENTA III\"},{\"master_id\":1,\"question_id\":5,\"option_id\":\"16\",\"answer\":\"MR I\"},{\"master_id\":1,\"question_id\":11,\"option_id\":\"65\",\"answer\":\"No\"},{\"master_id\":1,\"question_id\":13,\"option_id\":null,\"answer\":\"72\"}]}', '2026-02-13 11:32:53');

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
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

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
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of opd_mnch_master
-- ----------------------------
INSERT INTO `opd_mnch_master` VALUES (1, '2026-02-12', '12345', 'New', '1', '5', 'ABC', 'Razia', 'Razia', 'Sultan', 'No', '15-49', 'Unmarried', 'Non-Pregnant', NULL, '2026-02-13 11:31:30', 'MNCH');

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
INSERT INTO `provinces` VALUES (1, 'Khyber Pakhtunkhwa');

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
INSERT INTO `uc` VALUES (1, 1, 'Datta Khel-1');
INSERT INTO `uc` VALUES (2, 1, 'Datta Khel-2');
INSERT INTO `uc` VALUES (3, 1, 'Datta Khel-3');
INSERT INTO `uc` VALUES (4, 1, 'Mir Ali-5');
INSERT INTO `uc` VALUES (5, 1, 'Mir Ali-6');
INSERT INTO `uc` VALUES (6, 1, 'Mir Ali-7');
INSERT INTO `uc` VALUES (7, 1, 'Shewa-1');
INSERT INTO `uc` VALUES (8, 1, 'Shewa-2');
INSERT INTO `uc` VALUES (9, 1, 'Spinwam-1');
INSERT INTO `uc` VALUES (10, 1, 'Spinwam-2');
INSERT INTO `uc` VALUES (11, 1, 'Garyum');
INSERT INTO `uc` VALUES (12, 1, 'Razmak');
INSERT INTO `uc` VALUES (13, 1, 'Dossali-2');

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user_access_log
-- ----------------------------
INSERT INTO `user_access_log` VALUES (1, 1, 'internee1', '::1', '2026-02-13 06:37:48', '2026-02-13 06:42:25', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (2, 1, 'internee1', '::1', '2026-02-13 06:42:29', '2026-02-13 06:43:35', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (3, 1, 'internee1', '::1', '2026-02-13 06:43:57', '2026-02-13 06:44:22', 1, 'Login successful');
INSERT INTO `user_access_log` VALUES (4, 1, 'internee1', '::1', '2026-02-13 06:44:35', NULL, 1, 'Login successful');

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
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'internee1', 'internee1', '202cb962ac59075b964b07152d234b70', 1, 1, '1', '2026-02-03 14:08:56', 1);
INSERT INTO `users` VALUES (2, 'internee2', 'internee2', '202cb962ac59075b964b07152d234b70', 1, 1, '1', '2026-02-13 09:04:44', 1);
INSERT INTO `users` VALUES (3, 'tawha', 'tawha', '202cb962ac59075b964b07152d234b70', 1, 1, '1', '2026-02-13 09:04:50', 1);
INSERT INTO `users` VALUES (4, 'readonly', 'readonly', '202cb962ac59075b964b07152d234b70', 1, 1, '2', '2026-02-13 09:05:19', 1);

SET FOREIGN_KEY_CHECKS = 1;
