CREATE TABLE `t_retard`  (
  `retard_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `retard_user` int NULL,
  `retard_day` date NULL,
  `retard_duree` time NULL,
  `retard_datecrea` datetime NULL,
  `retard_datemodif` datetime NULL,
  PRIMARY KEY (`retard_id`),
  FOREIGN KEY (`retard_user`) REFERENCES `timetracking_db_prod`.`tr_user` (`usr_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB;

ALTER TABLE `t_retard` 
ADD COLUMN `retard_planningentree` time NULL AFTER `retard_day`,
ADD COLUMN `retard_pointagein` time NULL AFTER `retard_planningentree`,
ADD COLUMN `retard_shiftloggedin` datetime NULL AFTER `retard_pointagein`,
ADD COLUMN `retard_shiftbegin` datetime NULL AFTER `retard_shiftloggedin`;

