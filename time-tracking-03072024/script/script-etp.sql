SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tr_proprio
-- ----------------------------
DROP TABLE IF EXISTS `tr_proprio`;
CREATE TABLE `tr_proprio`  (
  `proprio_id` int NOT NULL AUTO_INCREMENT,
  `proprio_libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `proprio_actif` bit(1) NULL DEFAULT NULL,
  `proprio_datecrea` datetime NULL DEFAULT NULL,
  `proprio_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`proprio_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_proprio
-- ----------------------------
INSERT INTO `tr_proprio` VALUES (1, 'DB', b'1', '2024-01-29 11:57:27', '2024-01-29 11:57:27');
INSERT INTO `tr_proprio` VALUES (2, 'OM ', b'1', '2024-01-29 11:59:22', '2024-01-29 11:59:22');
INSERT INTO `tr_proprio` VALUES (3, 'FZ', b'1', '2024-03-08 10:59:40', '2024-03-08 10:59:42');

SET FOREIGN_KEY_CHECKS = 1;



-- Lier la table tr_proprio avec tr_campagne

ALTER TABLE `tr_campagne` 
ADD COLUMN `campagne_proprio` int NULL AFTER `campagne_actif`,
ADD FOREIGN KEY (`campagne_proprio`) REFERENCES `tr_proprio` (`proprio_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tr_mission
-- ----------------------------
DROP TABLE IF EXISTS `tr_mission`;
CREATE TABLE `tr_mission`  (
  `mission_id` int NOT NULL AUTO_INCREMENT,
  `mission_libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mission_actif` bit(1) NULL DEFAULT NULL,
  `mission_datecrea` datetime NULL DEFAULT NULL,
  `mission_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`mission_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tr_mission
-- ----------------------------
INSERT INTO `tr_mission` VALUES (1, 'SERVICE CLIENT', b'1', '2024-01-29 12:00:10', '2024-01-29 12:00:10');
INSERT INTO `tr_mission` VALUES (2, 'SAISIE', b'1', '2024-01-29 12:00:16', '2024-01-29 12:00:16');
INSERT INTO `tr_mission` VALUES (3, 'RÉDACTION', b'1', '2024-01-29 12:00:22', '2024-01-29 12:00:22');
INSERT INTO `tr_mission` VALUES (4, 'SAISIE REDAC', b'1', '2024-01-29 12:00:28', '2024-01-29 12:00:28');
INSERT INTO `tr_mission` VALUES (5, 'FINTECH', b'1', '2024-01-29 12:00:37', '2024-01-29 12:00:37');
INSERT INTO `tr_mission` VALUES (6, 'MODÉRATION/SC', b'1', '2024-01-29 12:00:54', '2024-01-29 12:00:54');
INSERT INTO `tr_mission` VALUES (7, 'TELEOPERATION', b'1', '2024-01-29 12:01:01', '2024-01-29 12:01:01');
INSERT INTO `tr_mission` VALUES (8, 'BO', b'1', '2024-01-29 12:01:17', '2024-01-29 12:01:17');
INSERT INTO `tr_mission` VALUES (9, 'INTÉGRATION', b'1', '2024-01-29 12:01:41', '2024-01-29 12:01:41');
INSERT INTO `tr_mission` VALUES (10, 'COMPTABILITE', b'1', '2024-02-19 10:32:00', '2024-02-19 10:32:00');

SET FOREIGN_KEY_CHECKS = 1;


SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tr_process
-- ----------------------------
DROP TABLE IF EXISTS `tr_process`;
CREATE TABLE `tr_process`  (
  `process_id` int NOT NULL AUTO_INCREMENT,
  `process_libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `process_actif` bit(1) NULL DEFAULT NULL,
  `process_datecrea` datetime NULL DEFAULT NULL,
  `process_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`process_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tr_process
-- ----------------------------
INSERT INTO `tr_process` VALUES (1, 'APELLS ENTRANTS ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (2, 'APPELS SORTANTS ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (3, 'APPELS ENTRANTS + SORTANTS', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (4, 'APPELS BORDEAUX ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (5, 'APPELS PARIS', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (6, 'APPELS IAD', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (7, 'BO', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (8, 'COURRIER BO', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (9, 'CREATION BO', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (10, 'TRAITEMENT BO ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (11, 'TRAITEMENT BO RECLAMATION ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (12, 'DIRECT DEBIT', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (13, 'MAILS', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (14, 'MAILS KYC', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (15, 'MAILS CS', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (16, 'MAILS HELPDESK', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (17, 'MAILS DE', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (18, 'MAILS /AVIS/MFDS', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (19, 'MAILS/TCHAT', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (20, 'TCHAT', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (21, 'SC TCHAT', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (22, 'TICKETS', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (23, 'MODERATION ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (24, 'CREATION NOUVEAU COMPTE ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (25, 'TRAITEMENT RELEVES ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (26, 'TOTAL DE TRAITEMENTS MENSUEL', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (27, 'COMPARATIF EDL', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (28, 'TRAITEMENT DE DOSSIER ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (29, 'TRAITEMENT DES AUTRES TACHES ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (30, 'TRAITEMENT COURRIER ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (31, 'TRAITEMENT AVIS', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (32, 'TRAITEMENT KYC', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (33, 'SAISIE COMMANDE ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (34, 'COTATION', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (35, 'SAISIE ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (36, 'REDACTION ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (37, 'SAISIE REDACTION ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');
INSERT INTO `tr_process` VALUES (38, 'INTEGRATION ', b'1', '2024-03-07 12:57:21', '2024-03-07 12:57:21');

SET FOREIGN_KEY_CHECKS = 1;


SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_affectationmcp
-- ----------------------------
DROP TABLE IF EXISTS `t_affectationmcp`;
CREATE TABLE `t_affectationmcp`  (
  `affectationmcp_id` int NOT NULL AUTO_INCREMENT,
  `affectationmcp_mission` int NOT NULL,
  `affectationmcp_campagne` int NOT NULL,
  `affectationmcp_process` int NOT NULL,
  `affectationmcp_datecrea` datetime NULL DEFAULT NULL,
  `affectationmcp_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`affectationmcp_id`) USING BTREE,
  UNIQUE INDEX `affectationmcp_mission`(`affectationmcp_mission` ASC, `affectationmcp_campagne` ASC, `affectationmcp_process` ASC) USING BTREE,
  INDEX `affectationmcp_campagne`(`affectationmcp_campagne` ASC) USING BTREE,
  INDEX `affectationmcp_process`(`affectationmcp_process` ASC) USING BTREE,
  CONSTRAINT `t_affectationmcp_ibfk_1` FOREIGN KEY (`affectationmcp_mission`) REFERENCES `tr_mission` (`mission_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_affectationmcp_ibfk_2` FOREIGN KEY (`affectationmcp_campagne`) REFERENCES `tr_campagne` (`campagne_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_affectationmcp_ibfk_3` FOREIGN KEY (`affectationmcp_process`) REFERENCES `tr_process` (`process_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;




SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_missioncampagneprocess
-- ----------------------------
DROP TABLE IF EXISTS `t_missioncampagneprocess`;
CREATE TABLE `t_missioncampagneprocess`  (
  `mcp_id` int NOT NULL AUTO_INCREMENT,
  `mcp_mission` int NULL DEFAULT NULL,
  `mcp_campagne` int NULL DEFAULT NULL,
  `mcp_process` int NULL DEFAULT NULL,
  `mcp_date` date NULL DEFAULT NULL,
  `mcp_datedebut` datetime NULL DEFAULT NULL,
  `mcp_datefin` datetime NULL DEFAULT NULL,
  `mcp_lastpause` datetime NULL DEFAULT NULL,
  `mcp_tempspause` time NULL DEFAULT NULL,
  `mcp_tempstravail` time NULL DEFAULT NULL,
  `mcp_agent` int NULL DEFAULT NULL,
  `mcp_quantite` int NULL DEFAULT NULL,
  `mcp_status` tinyint NULL DEFAULT NULL,
  `mcp_etatressource` int NULL DEFAULT NULL,
  `mcp_datecrea` datetime NULL DEFAULT NULL,
  `mcp_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`mcp_id`) USING BTREE,
  INDEX `mcp_mission`(`mcp_mission` ASC) USING BTREE,
  INDEX `mcp_campagne`(`mcp_campagne` ASC) USING BTREE,
  INDEX `mcp_process`(`mcp_process` ASC) USING BTREE,
  INDEX `mcp_agent`(`mcp_agent` ASC) USING BTREE,
  INDEX `t_missioncampagneprocess_ibfk_5`(`mcp_etatressource` ASC) USING BTREE,
  CONSTRAINT `t_missioncampagneprocess_ibfk_1` FOREIGN KEY (`mcp_mission`) REFERENCES `tr_mission` (`mission_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_missioncampagneprocess_ibfk_2` FOREIGN KEY (`mcp_campagne`) REFERENCES `tr_campagne` (`campagne_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_missioncampagneprocess_ibfk_3` FOREIGN KEY (`mcp_process`) REFERENCES `tr_process` (`process_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_missioncampagneprocess_ibfk_4` FOREIGN KEY (`mcp_agent`) REFERENCES `tr_user` (`usr_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_missioncampagneprocess_ibfk_5` FOREIGN KEY (`mcp_etatressource`) REFERENCES `tr_etatressource` (`etatressource_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;



SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_etpressource
-- ----------------------------
DROP TABLE IF EXISTS `t_etpressource`;
CREATE TABLE `t_etpressource`  (
  `etpressource_id` int NOT NULL AUTO_INCREMENT,
  `etpressource_campagne` int NULL DEFAULT NULL,
  `etpressource_mission` int NULL DEFAULT NULL,
  `etpressource_date` date NULL DEFAULT NULL,
  `etpressource_nombre` int NULL DEFAULT NULL,
  `etpressource_datecrea` datetime NULL DEFAULT NULL,
  `etpressource_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`etpressource_id`) USING BTREE,
  UNIQUE INDEX `etpressource_campagne`(`etpressource_campagne` ASC, `etpressource_mission` ASC, `etpressource_date` ASC) USING BTREE,
  INDEX `etpressource_mission`(`etpressource_mission` ASC) USING BTREE,
  CONSTRAINT `t_etpressource_ibfk_1` FOREIGN KEY (`etpressource_campagne`) REFERENCES `tr_campagne` (`campagne_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_etpressource_ibfk_2` FOREIGN KEY (`etpressource_mission`) REFERENCES `tr_mission` (`mission_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;


SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tr_etatressource
-- ----------------------------
DROP TABLE IF EXISTS `tr_etatressource`;
CREATE TABLE `tr_etatressource`  (
  `etatressource_id` int NOT NULL AUTO_INCREMENT,
  `etatressource_libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `etatressource_facturation` tinyint NULL DEFAULT NULL,
  `etatressource_datecrea` datetime NULL DEFAULT NULL,
  `etatressource_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`etatressource_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tr_etatressource
-- ----------------------------
INSERT INTO `tr_etatressource` VALUES (1, 'Prod facturée', 1, '2024-03-04 10:06:56', '2024-03-04 10:07:08');
INSERT INTO `tr_etatressource` VALUES (2, 'Formation facturée', 1, '2024-03-04 10:06:59', '2024-03-04 10:07:10');
INSERT INTO `tr_etatressource` VALUES (3, 'Renfort', 0, '2024-03-04 10:07:02', '2024-03-04 10:07:15');
INSERT INTO `tr_etatressource` VALUES (4, 'Support ', 0, '2024-03-04 10:07:04', '2024-03-04 10:07:13');
INSERT INTO `tr_etatressource` VALUES (5, 'Formation', 0, '2024-03-04 10:07:06', '2024-03-04 10:07:17');

SET FOREIGN_KEY_CHECKS = 1;


-- 27022024
ALTER TABLE `tr_campagne` 
ADD COLUMN `campagne_client` varchar(100) NULL AFTER `campagne_libelle`;

-- 19/03/2024
CREATE TABLE `t_livrepaie`  (
  `livrepaie_id` int NOT NULL AUTO_INCREMENT,
  `livrepaie_month` int NULL,
  `livrepaie_year` int NULL,
  `livrepaie_valeur` float NULL,
  `livrepaie_datecrea` datetime NULL,
  `livrepaie_datemodif` datetime NULL,
  PRIMARY KEY (`livrepaie_id`)
);
ALTER TABLE `t_livrepaie` ENGINE = InnoDB;
ALTER TABLE `t_livrepaie` 
ADD UNIQUE INDEX(`livrepaie_month`, `livrepaie_year`) USING BTREE;