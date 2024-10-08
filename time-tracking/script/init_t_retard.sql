SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE t_retard;

INSERT INTO t_retard (retard_user, retard_day, retard_planningentree, retard_pointagein, retard_shiftloggedin, retard_shiftbegin, retard_duree, retard_datecrea, retard_datemodif)
SELECT
	`tr_user`.`usr_id` AS `usr_id`,
	cast( `t_planning`.`planning_date` AS date ) AS `jour`,
	`t_planning`.`planning_entree` AS `planning_entree`,
	`t_pointage`.`pointage_in` AS `pointage_in`,
	`t_shift`.`shift_loggedin` AS `shift_loggedin`,
	`t_shift`.`shift_begin` AS `shift_begin`,
	SEC_TO_TIME(sum((
		CASE
				
				WHEN (((
							`t_pointage`.`pointage_in` < cast( `t_shift`.`shift_begin` AS time )) 
						AND ( `t_pointage`.`pointage_in` IS NOT NULL )) 
					OR ( `t_shift`.`shift_begin` IS NULL )) THEN
					timediff( `t_pointage`.`pointage_in`, `t_planning`.`planning_entree` ) ELSE timediff( cast( `t_shift`.`shift_loggedin` AS time ), `t_planning`.`planning_entree` ) 
				END 
				))) AS `duree`,
				NOW(),
				NOW()
		FROM
			((((
							`tr_user`
							JOIN `t_planning` ON ((
									`t_planning`.`planning_user` = `tr_user`.`usr_id` 
								)))
						LEFT JOIN `t_pointage` ON (((
									`t_pointage`.`pointage_user` = `tr_user`.`usr_id` 
									) 
								AND (
								`t_pointage`.`pointage_date` = cast( `t_planning`.`planning_date` AS date )))))
					LEFT JOIN `t_shift` ON (((
								`t_shift`.`shift_userid` = `tr_user`.`usr_id` 
								) 
							AND (
							`t_shift`.`shift_day` = cast( `t_planning`.`planning_date` AS date )))))
				) 
		WHERE
			( `tr_user`.`usr_actif` = 1 ) 
		GROUP BY
			`tr_user`.`usr_id`,
			`t_planning`.`planning_date` 
		HAVING
			(
			`duree` > 0 
	)
		ORDER BY `t_planning`.`planning_date` ASC, usr_id ASC;

SET FOREIGN_KEY_CHECKS = 1;