<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retard_model extends CI_Model {
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function getUsersHistoriqueRetards($filtre)
    {
        $du = (isset($filtre['debut']) && !empty($filtre['debut'])) ? $filtre['debut'] : false;
        $au = (isset($filtre['fin']) && !empty($filtre['fin'])) ? $filtre['fin'] : false;

        $user = (isset($filtre['user']) && !empty($filtre['user'])) ? $filtre['user'] : false;
        $userPoids = (isset($filtre['user_poids']) && !empty($filtre['user_poids'])) ? $filtre['user_poids'] : false;
        $listCampagne = (isset($filtre['list_campagne']) && !empty($filtre['list_campagne'])) ? $filtre['list_campagne'] : false;
        $listService = (isset($filtre['list_service']) && !empty($filtre['list_service'])) ? $filtre['list_service'] : false;
        $whereSubQuery1 = $whereSubQuery2 = false;

        if($listCampagne !== FALSE){

            $this->db->select('usercampagne_user')
                        ->from('t_usercampagne')
                        ->join('tr_user', 'usr_id = usercampagne_user')
                        ->join('tr_role', 'role_id = usr_role')
                        ->where_in('usercampagne_campagne', $listCampagne);
            if($userPoids) $this->db->where('role_poids <', $userPoids);
            $whereSubQuery1 = $this->db->get_compiled_select();
        }

        if($listService !== FALSE){
            $this->db->select('userservice_user')
                        ->from('t_userservice')
                        ->join('tr_user', 'usr_id = userservice_user')
                        ->join('tr_role', 'role_id = usr_role')
                        ->where_in('userservice_service', $listService);
            if($userPoids) $this->db->where('role_poids <', $userPoids);
            $whereSubQuery2 = $this->db->get_compiled_select();
        }

        $this->db
            ->select('site_libelle, usr_prenom, usr_matricule, usr_initiale')
            ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS list_campagne", FALSE)
            ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS list_service", FALSE)    
            ->select("CASE WHEN shift_begin IS NOT NULL THEN 1 ELSE CASE WHEN pointage_in IS NOT NULL THEN 1 ELSE 0 END END AS presence", FALSE)  
            ->select('typeconge_libelle, motifpresence_incomplet, motifpresence_motif')
            ->from('tr_user')
            ->join('tr_site','site_id = usr_site', 'inner')
            ->join('t_shift', "shift_userid = usr_id AND shift_day = '$day'", 'left')
            ->join('t_pointage', "pointage_user = usr_id AND pointage_date = '$day'", 'left')
            ->join('t_conge', "conge_user = usr_id AND DATE(conge_datedebut) <= '$day' AND DATE(conge_datefin) >= '$day'", 'left')
            ->join('t_motifpresence', "motifpresence_day = '$day'", 'left')
            ->join('tr_typeconge', 'typeconge_id = conge_type', 'left')
            ->where('usr_actif', 1)
            ;

        $whereIn = '';
        if($whereSubQuery1 !== false){
            $whereIn = "usr_id IN ($whereSubQuery1)";
        }
        if($whereSubQuery2 !== false){
            if($whereIn) $whereIn .= ' OR ';
            $whereIn .= "usr_id IN ($whereSubQuery2)";
        }
        if($whereIn){
            $this->db->where("($whereIn)", NULL, FALSE);
        }

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }


    public function getlistRetard($filtre)
    {

        $du = (isset($filtre['debut']) && !empty($filtre['debut'])) ? $filtre['debut'] : false;
        $au = (isset($filtre['fin']) && !empty($filtre['fin'])) ? $filtre['fin'] : false;
        $user = (isset($filtre['user']) && !empty($filtre['user'])) ? $filtre['user'] : false;

        $this->db->select('usercampagne_user')
                    ->from('t_usercampagne')
                    ->join('tr_user', 'usr_id = usercampagne_user')
                    ->join('tr_role', 'role_id = usr_role')
                    ->where_in('usercampagne_campagne', $filtre['list_campagne']);
        if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
        $whereSubQuery1 = $this->db->get_compiled_select();

        $this->db->select('userservice_user')
                    ->from('t_userservice')
                    ->join('tr_user', 'usr_id = userservice_user')
                    ->join('tr_role', 'role_id = usr_role')
                    ->where_in('userservice_service', $filtre['list_service']);
        if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
        $whereSubQuery2 = $this->db->get_compiled_select();

        $this->db->select('usr_id, usr_prenom, usr_matricule, usr_initiale, site_libelle')
                ->select("COUNT('usr_id') AS nombre_retard", false)
                ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS listcampagne", false)
                ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS listservice", false)
                ->select("SEC_TO_TIME(SUM(TIME_TO_SEC(retard_duree))) AS total_retard", false)
                ->from('t_retard')
                ->join('tr_user', 'retard_user = usr_id', 'inner')
                ->join('tr_site', 'usr_site = site_id', 'left')
                ->group_by('usr_id');

        $whereIn = '';
        if(isset($filtre['list_campagne']) && !empty($filtre['list_campagne'])){
            $whereIn = "usr_id IN ($whereSubQuery1)";
        }
        if(isset($filtre['list_service']) && !empty($filtre['list_service'])){
            if($whereIn != '') $whereIn .= ' OR ';
            $whereIn .= "usr_id IN ($whereSubQuery2)";
        }
        if($whereIn != ''){
            $this->db->where("($whereIn)", NULL, FALSE);
        }

        if($du !== false) $this->db->where('retard_day >=', $du);
        if($au !== false) $this->db->where('retard_day <=', $au);

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

    /*public function getlistRetard__old($filtre)
    {

        $du = (isset($filtre['debut']) && !empty($filtre['debut'])) ? $filtre['debut'] : false;
        $au = (isset($filtre['fin']) && !empty($filtre['fin'])) ? $filtre['fin'] : false;
        $user = (isset($filtre['user']) && !empty($filtre['user'])) ? $filtre['user'] : false;

        $this->db->select('usercampagne_user')
                    ->from('t_usercampagne')
                    ->join('tr_user', 'usr_id = usercampagne_user')
                    ->join('tr_role', 'role_id = usr_role')
                    ->where_in('usercampagne_campagne', $filtre['list_campagne']);
        if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
        $whereSubQuery1 = $this->db->get_compiled_select();

        $this->db->select('userservice_user')
                    ->from('t_userservice')
                    ->join('tr_user', 'usr_id = userservice_user')
                    ->join('tr_role', 'role_id = usr_role')
                    ->where_in('userservice_service', $filtre['list_service']);
        if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
        $whereSubQuery2 = $this->db->get_compiled_select();

        $this->db->select('usr_id, usr_prenom, usr_matricule, usr_initiale, site_libelle')
                ->select("COUNT('usr_id') AS nombre_retard", false)
                ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS listcampagne", false)
                ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS listservice", false)
                ->select("SEC_TO_TIME(SUM(TIME_TO_SEC(duree_retard))) AS total_retard", false)
                ->from('view_retard')
                ->group_by('usr_id');

        $whereIn = '';
        if(isset($filtre['list_campagne']) && !empty($filtre['list_campagne'])){
            $whereIn = "usr_id IN ($whereSubQuery1)";
        }
        if(isset($filtre['list_service']) && !empty($filtre['list_service'])){
            if($whereIn != '') $whereIn .= ' OR ';
            $whereIn .= "usr_id IN ($whereSubQuery2)";
        }
        if($whereIn != ''){
            $this->db->where("($whereIn)", NULL, FALSE);
        }

        if($du !== false) $this->db->where('jour >=', $du);
        if($au !== false) $this->db->where('jour <=', $au);

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }*/

    public function getRetardsDetails($user, $du, $au){
        $this->db->select('usr_id, usr_prenom, usr_matricule, usr_initiale, site_libelle, retard_day as jour, retard_planningentree as planning_entree, retard_pointagein as pointage_in, retard_shiftloggedin as shift_loggedin, retard_shiftbegin as shift_begin, retard_duree as duree_retard_formatted')
                ->from('t_retard')
                ->join('tr_user', 'retard_user = usr_id', 'inner')
                ->join('tr_site', 'usr_site = site_id', 'left')
                ->where('usr_id =', $user)
                ->where('retard_day >=', $du)
                ->where('retard_day <=', $au);

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    /*public function getRetardsDetails__old($user, $du, $au){
        $this->db->select('usr_id, usr_prenom, usr_matricule, usr_initiale, site_libelle, jour, planning_entree, pointage_in, shift_loggedin, shift_begin, duree_retard, duree_retard_formatted')
                ->from('view_retard')
                ->where('usr_id =', $user)
                ->where('jour >=', $du)
                ->where('jour <=', $au);

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }*/

    public function getRetardDatas($day)
    {
        $sqlString = "SELECT
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
                    ( `tr_user`.`usr_actif` = 1 AND planning_date = '" . $day . "') 
                GROUP BY
                    `tr_user`.`usr_id`,
                    `t_planning`.`planning_date` 
                HAVING
                    (
                    `duree` > 0 
            )
            ORDER BY `t_planning`.`planning_date` ASC, usr_id ASC";

        $query = $this->db->query($sqlString);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getUserRetardByDay($day, $user)
    {
        $this->db->select('retard_id, retard_user, retard_day')
                ->from('t_retard')
                ->where('retard_day', $day)
                ->where('retard_user', $user)
                ;
        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->row();
        }
        return false;
    }   

    public function insertUserRetardDayData($entry)
    {
        $this->db->insert('t_retard', $entry);
        return $this->db->insert_id();
    }


    public function updateUserRetardDayData($entry, $id)
    {
        $this->db->update('t_retard', $entry, ['retard_id' => $id]);
        if($this->db->affected_rows() > 0) return true;
        return false;
    }

}
