DROP VIEW  IF EXISTS view_retard;
CREATE VIEW `view_retard` AS SELECT
	usr_id,
	usr_prenom,
	usr_matricule,
	usr_initiale,
	site_libelle,
	DATE(planning_date) as jour,
	planning_entree,
	pointage_in,
	shift_loggedin,
	shift_begin,
	SUM(CASE 
		WHEN 
			pointage_in < TIME(shift_begin) THEN TIMEDIFF(pointage_in, planning_entree)
		ELSE 
			TIMEDIFF(TIME(shift_loggedin), planning_entree)
		END) AS duree_retard
FROM
	tr_user 
	INNER JOIN t_planning ON planning_user = usr_id 
	LEFT JOIN t_pointage ON pointage_user = usr_id AND pointage_date = DATE(planning_date)
	LEFT JOIN t_shift ON shift_userid = usr_id  AND shift_day = DATE(planning_date)
	INNER JOIN tr_site ON site_id = usr_site
WHERE
	usr_actif = 1

GROUP BY usr_id, planning_date
HAVING duree_retard > 0;