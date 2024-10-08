CREATE TABLE `tr_primecritere`  (
  `primecritere_id` int NOT NULL AUTO_INCREMENT,
  `primecritere_libelle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `primecritere_profil` int NULL DEFAULT NULL,
  `primecritere_campagne` int NULL DEFAULT NULL,
  `primecritere_mission` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `primecritere_process` int NULL DEFAULT NULL,
  `primecritere_typecritere` int NULL DEFAULT NULL,
  `primecritere_modecalcul` int NULL DEFAULT NULL,
  `primecritere_frequencecalcul` enum('day','month') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'month',
  `primecritere_objectif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `primecritere_montant` float NULL DEFAULT NULL,
  `primecritere_etat` int NULL DEFAULT NULL,
  `primecritere_actif` binary(1) NULL DEFAULT NULL,
  `primecritere_datecrea` datetime NULL DEFAULT NULL,
  `primecritere_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`primecritere_id`) USING BTREE,
  INDEX `primecritere_profil`(`primecritere_profil` ASC) USING BTREE,
  INDEX `primecritere_typecritere`(`primecritere_typecritere` ASC) USING BTREE,
  INDEX `primecritere_modecalcul`(`primecritere_modecalcul` ASC) USING BTREE,
  INDEX `primecritere_process`(`primecritere_process` ASC) USING BTREE,
  INDEX `primecritere_campagne`(`primecritere_campagne` ASC) USING BTREE,
  CONSTRAINT `tr_primecritere_ibfk_1` FOREIGN KEY (`primecritere_profil`) REFERENCES `tr_primeprofil` (`primeprofil_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tr_primecritere_ibfk_2` FOREIGN KEY (`primecritere_typecritere`) REFERENCES `tr_primetypecritere` (`primetypecritere_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tr_primecritere_ibfk_3` FOREIGN KEY (`primecritere_modecalcul`) REFERENCES `tr_primemodecalcul` (`primemodecalcul_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tr_primecritere_ibfk_4` FOREIGN KEY (`primecritere_process`) REFERENCES `tr_process` (`process_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tr_primecritere_ibfk_5` FOREIGN KEY (`primecritere_campagne`) REFERENCES `tr_campagne` (`campagne_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic
;
-- 12/06/2024
ALTER TABLE `tr_primemodecalcul` 
ADD COLUMN `primemodecalcul_action` varchar(100) NULL AFTER `primemodecalcul_calcul`
;
-- 13/06/2024
ALTER TABLE `t_prime` 
MODIFY COLUMN `prime_basemensuelle` float(10, 2) NOT NULL AFTER `prime_annee`;
-- 15/06/2024
CREATE TABLE `t_primeatteintejournaliere`  (
  `primeatteintejour_id` int NOT NULL AUTO_INCREMENT,
  `primeatteintejour_day` datetime NULL DEFAULT NULL,
  `primeatteintejour_agent` int NULL DEFAULT NULL,
  `primeatteintejour_primeprofil` int NULL DEFAULT NULL,
  `primeatteintejour_campagne` int NULL DEFAULT NULL,
  `primeatteintejour_critere` int NULL DEFAULT NULL,
  `primeatteintejour_objectif` float(10, 2) NULL DEFAULT NULL,
  `primeatteintejour_tauxatteinte` float(10, 2) NULL DEFAULT NULL,
  `primeatteintejour_nbproduction` float(10, 2) NULL DEFAULT NULL,
  `primeatteintejour_datecrea` datetime NULL DEFAULT NULL,
  `primeatteintejour_datemodif` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`primeatteintejour_id`) USING BTREE,
  INDEX `primeatteintejour_critere`(`primeatteintejour_critere` ASC) USING BTREE,
  INDEX `primeatteintejour_agent`(`primeatteintejour_agent` ASC) USING BTREE,
  INDEX `primeatteintejour_primeprofil`(`primeatteintejour_primeprofil` ASC) USING BTREE,
  INDEX `primeatteintejour_campagne`(`primeatteintejour_campagne` ASC) USING BTREE,
  CONSTRAINT `t_primeatteintejournaliere_ibfk_1` FOREIGN KEY (`primeatteintejour_agent`) REFERENCES `tr_user` (`usr_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_primeatteintejournaliere_ibfk_2` FOREIGN KEY (`primeatteintejour_primeprofil`) REFERENCES `tr_primeprofil` (`primeprofil_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_primeatteintejournaliere_ibfk_3` FOREIGN KEY (`primeatteintejour_critere`) REFERENCES `tr_primecritere` (`primecritere_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_primeatteintejournaliere_ibfk_4` FOREIGN KEY (`primeatteintejour_campagne`) REFERENCES `tr_campagne` (`campagne_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic
;
CREATE TABLE `t_primeobjectifjournalier`  (
  `primeobjectifjour_id` int NOT NULL AUTO_INCREMENT,
  `primeobjectifjour_day` datetime NULL,
  `primeobjectifjour_critere` int NULL,
  `primeobjectifjour_valeur` float(10, 2) NULL,
  `primeobjectifjour_datecrea` datetime NULL,
  `primeobjectifjour_datemodif` datetime NULL,
  PRIMARY KEY (`primeobjectifjour_id`),
  FOREIGN KEY (`primeobjectifjour_critere`) REFERENCES `tr_primecritere` (`primecritere_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci
;
-- 18/06/2024
ALTER TABLE `t_missioncampagneprocess` 
ADD COLUMN `mcp_primeprofil` int NULL AFTER `mcp_process`,
ADD FOREIGN KEY (`mcp_primeprofil`) REFERENCES `tr_primeprofil` (`primeprofil_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
SET FOREIGN_KEY_CHECKS=0
;
ALTER TABLE `tr_primeprofil` 
MODIFY COLUMN `primeprofil_id` int NOT NULL FIRST,
MODIFY COLUMN `primeprofil_campagne` int NOT NULL AFTER `primeprofil_id`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`primeprofil_id`, `primeprofil_campagne`) USING BTREE;
SET FOREIGN_KEY_CHECKS=1
;
ALTER TABLE `t_primeprofilprocess` 
ADD COLUMN `primeprofilprocess_campagne` int NOT NULL AFTER `primeprofilprocess_process`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`primeprofilprocess_profil`, `primeprofilprocess_process`, `primeprofilprocess_campagne`) USING BTREE,
ADD FOREIGN KEY (`primeprofilprocess_campagne`) REFERENCES `tr_campagne` (`campagne_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;
ALTER TABLE `timetracking_db_prod`.`t_primeatteintejournaliere` 
ADD COLUMN `primeatteintejour_condition` text NULL AFTER `primeatteintejour_objectif`;
-- 23/06/2024
ALTER TABLE `t_prime` 
ADD COLUMN `prime_campagne` int NULL AFTER `prime_user`,
ADD UNIQUE INDEX(`prime_user`, `prime_campagne`) USING BTREE,
ADD FOREIGN KEY (`prime_campagne`) REFERENCES `tr_campagne` (`campagne_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

-- 27/06/2024
ALTER TABLE `t_prime` 
ADD COLUMN `prime_notequalitesetex` float(10, 2) NULL AFTER `prime_basemensuelle`,
ADD COLUMN `prime_montantqualitesetex` float(10, 2) NULL AFTER `prime_notequalitesetex`,
ADD COLUMN `prime_montantpercu` float(10, 2) NULL AFTER `prime_montantqualitesetex`;
ALTER TABLE `t_prime` 
ADD COLUMN `prime_totalbonus` float(10, 2) NULL AFTER `prime_montantqualitesetex`,
ADD COLUMN `prime_totalmalus` float(10, 2) NULL AFTER `prime_totalbonus`;

CREATE TABLE `t_primequalite`  (
  `primequalite_user` int NOT NULL,
  `primequalite_campagne` int NOT NULL,
  `primequalite_typecritere` int NOT NULL,
  `primequalite_note` float(10, 2) NULL,
  `primequalite_commentaire` varchar(255) NULL,
  `primequalite_datecrea` datetime NULL,
  `primequalite_datemodif` datetime NULL,
  PRIMARY KEY (`primequalite_user`, `primequalite_campagne`, `primequalite_typecritere`)
);
ALTER TABLE `t_primequalite` 
ADD FOREIGN KEY (`primequalite_user`) REFERENCES `tr_user` (`usr_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
ADD FOREIGN KEY (`primequalite_campagne`) REFERENCES `tr_campagne` (`campagne_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
ADD FOREIGN KEY (`primequalite_typecritere`) REFERENCES `tr_primetypecritere` (`primetypecritere_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
ENGINE = InnoDB;
ALTER TABLE `t_primequalite` 
ADD COLUMN `primequalite_mois` int NOT NULL AFTER `primequalite_typecritere`,
ADD COLUMN `primequalite_annee` int NOT NULL AFTER `primequalite_mois`,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`primequalite_user`, `primequalite_campagne`, `primequalite_typecritere`, `primequalite_mois`, `primequalite_annee`) USING BTREE;

ALTER TABLE `t_prime` 
DROP COLUMN `prime_notequalitesetex`,
CHANGE COLUMN `prime_montantqualitesetex` `prime_montantqualite` float(10, 2) NULL DEFAULT NULL AFTER `prime_basemensuelle`;

ALTER TABLE `tr_primecritere` 
MODIFY COLUMN `primecritere_objectif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL AFTER `primecritere_frequencecalcul`;