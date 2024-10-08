<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shift_model extends CI_Model {

    private $_day,
            $_userid,
            $_begin,
            $_end,
            $_loggedin,
            $_status,
            $_datecrea,
            $_datemodif, 
            $_shift,           
            $_table = 't_shift';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    /**
     * GETTER AND SETTER
     */
    public function getDay()
    {
        return $this->_day;
    }
    public function getUserId()
    {
        return $this->_userId;
    }
    public function getBegin()
    {
        return $this->_begin;
    }
    public function getEnd()
    {
        return $this->_end;
    }
    public function getLoggedIn()
    {
        return $this->_loggedin;
    }

    public function setDay($day)
    {
        $this->_day = $day;
    }
    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }
    public function setBegin($begin)
    {
        $this->_begin = $begin;
    }
    public function setEnd($end)
    {
        $this->_end = $end;
    }
    public function setLoggedIn($loggedIn)
    {
        $this->_loggedIn = $loggedIn;
    }
    public function setData($data)
    {
        if(!empty($data)){
            foreach($data as $key => $value){
                $this->{$key} = $value;
            }
        }
    }
     /* ================ END GETTER AND SETTER ============== */


    /**
     * Récuperer le dernier shift de l'agent (utilisateur)
     * - On suppose qu'un shift a une durée maximun de MAXSHIFTHOUR heure
     * - shift_loggedin et shift_begin devraient être executé le même jour. Si shift_begin est attribué le jour suivant, on doit démarrer un nouveau shift et terminer le shift en cours
     * 
     */
    public function getCurrentShift()
    {
        //On prend les détails du dernier shift
        $currShift = $this->getUserLastShift($this->_userId);
        //Si shift absent, on demarre un nouveau shift (return false)
        if(!$currShift) return false;
        //On récupère les infos du shift en cours (dernier shift)
        $now = date('Y-m-d H:i:s');

        $loggedIn = $currShift->shift_loggedin;
        $shiftBegin = $currShift->shift_begin;
        $shiftStatus = $currShift->shift_status;
        $shiftID = $currShift->shift_id;

        //Si shift_loggedin est SET mais shift_begin à vide, on verifie si c'est toujours sur la même jour sinon, on démarre un nouveau shift
        if($loggedIn && !$shiftBegin){
            $today = date('Y-m-d');
            $loggedInDay = date('Y-m-d', strtotime($loggedIn));
            //Si loggedin est inférieur à la date du jour, on démarre un nouveau shift
            if($loggedInDay < $today){
                //On termine le shift actuel et on retourne false
                $this->endProd($shiftID);
                return false;
            }
        }
        $hourdiff = '00:00';
        if($loggedIn){
            $hourdiff = round((strtotime($now) - strtotime($loggedIn))/3600, 1);
        }
        if($hourdiff > MAXSHIFTHOUR && $shiftStatus == DONEWORKING) return false;
        /*if($hourdiff > MAXSHIFTHOUR) return false;*/
        
        return $currShift;
    }

    public function getUserLastShift($userId)
    {
        $this->db->select('shift_id, shift_day, shift_userid, shift_begin, shift_end, shift_loggedin, shift_status, shift_ajustbegin, shift_ajustend')
                    ->order_by('shift_day', 'DESC')
                    ->limit(1);
        $this->db->where('shift_userid', $userId);
        //$this->db->where('shift_status <>', DONEWORKING);
        $query = $this->db->get($this->_table);
        if($query->num_rows() > 0) return $query->row();

        return false;
    }

    public function getUserShiftByDate($userId, $date)
    {
        $this->db->select('shift_id, shift_day, shift_userid, shift_begin, shift_end, shift_loggedin, shift_status')
                    ->select('CASE WHEN (shift_ajustbegin IS NOT NULL AND UNIX_TIMESTAMP(`shift_ajustbegin`) != 0) THEN shift_ajustbegin ELSE shift_begin END as shift_ajustbegin', FALSE)
                    ->select('CASE WHEN (shift_ajustend IS NOT NULL AND UNIX_TIMESTAMP(`shift_ajustend`) != 0) THEN shift_ajustend ELSE shift_end END as shift_ajustend', FALSE)
                    ->limit(1);
        $this->db->where('shift_userid', $userId)
                    ->where('shift_day', $date);
                    
        $query = $this->db->get($this->_table);
        if($query->num_rows() > 0) return $query->row();

        return false;
    }

    /**
     * Definir un nouveau shift
     */
    public function setCurrentShift($data)
    {
        $this->db->insert($this->_table, $data);
        return $this->db->insert_id();
    }

    /**
     * COmmencer la production pour un user et date donné
     */
    public function beginProd($user, $day)
    {
        $this->db->set('shift_begin', 'NOW()', FALSE)
                ->set('shift_status', WORKING)
                ->where('shift_userid', $user)
                ->where('shift_day', $day);
        $this->db->update($this->_table);

        if($this->db->affected_rows() > 0) return true;

        return false;
    }

    /**
     * Terminer le shift (production) pour un user (shiftId) donné
     */
    public function endProd($shiftId, $endedBy = false)
    {
        //Récupérer les infos du shift
        $shift = $this->getShiftById($shiftId);
        if($shift && $shift->shift_begin){
            $this->db->set('shift_end', 'NOW()', FALSE);
        }
        $this->db->set('shift_status', DONEWORKING)
                    ->set('shift_endedby', $endedBy)
                    ->where('shift_id', $shiftId);
        
        $this->db->update($this->_table);
        if($this->db->affected_rows() > 0) return true;

        return false;
    }

    /**
     * Mise à jour du status
     */
    public function updateStatus($status, $shiftId)
    {
        $this->db->set('shift_status', $status)
                ->where('shift_id', $shiftId);
        $this->db->update($this->_table);
        if($this->db->affected_rows() > 0) return true;
        return false;
    }

    /**
     * Get All shift 
     * 
     */
    public function getAllShift($user, $filtre = [])
    {
        $this->db->select('shift_id,shift_day, shift_userid, shift_begin, shift_end, shift_loggedin, shift_status, shift_ajustbegin, shift_ajustend, shift_ajustmodifier')
                ->select('usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_ingress, usr_site, usr_contrat')
                ->select('contrat.site_libelle as site_contrat')
                ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = '" . $user . "') AS list_campagne", FALSE)
                ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = '" . $user . "') AS list_service", FALSE)
                ->join('tr_user', 'usr_id = shift_userid', 'inner')
                ->join('tr_site as contrat', 'usr_contrat = contrat.site_id', 'left')
                ->where('shift_userid', $user);
        if(isset($filtre['debut']) && !empty($filtre['debut'])){
            $this->db->where('shift_day >= ', $filtre['debut']);
        }
        if(isset($filtre['fin']) && !empty($filtre['fin'])){
            $this->db->where('shift_day <= ', $filtre['fin']);
        }

        $query = $this->db->get($this->_table);
        //echo($sql_query);
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

    public function getShiftById($id)
    {
        $this->db->select('shift_id,shift_day, shift_userid, shift_begin, shift_end, shift_loggedin, shift_status, shift_ajustbegin, shift_ajustend, shift_ajustmodifier')
                ->select('usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_ingress')
                ->join('tr_user', 'usr_id = shift_userid', 'inner')
                ->where('shift_id', $id);

        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->row();
        }

        return false;
    }



    public function getShiftByUserAndDay($user, $day)
    {
        $this->db->select('shift_id,shift_day, shift_userid, shift_begin, shift_end, shift_loggedin, shift_status, shift_ajustbegin, shift_ajustend, shift_ajustmodifier')
                ->select('usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_ingress, usr_pseudo')
                 ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = '" . $user . "') AS list_campagne", FALSE)
                ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = '" . $user . "') AS list_service", FALSE)
                ->join('tr_user', 'usr_id = shift_userid', 'inner')
                ->where('shift_userid', $user)
                ->where('shift_day', $day)
                ->group_by('usr_id');


        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->row();
        }

        return false;
    }

    public function setAjustementSup($data, $where)
    {
        return $this->db->update($this->_table, $data, $where);
    }

    /**
     * Récuperer les données shift des users associé à un superviseur
     */
    public function getHistoriqueAgents($superviseur)
    {

    }

    public function getShiftDatasByCampagneAndDate($campagne_id, $day)
    {
        
    }

    public function getSuiviRhProdData($du, $au){

        $query_sql = "
                        SELECT
                        shift_day,
                        shift_id,
                        usr_site,
                        usr_ingress,
                        usr_prenom AS agent,
                        sitecontrat.site_libelle AS contrat,
                        usr_matricule AS matricule,
                        ( SELECT GROUP_CONCAT( campagne_libelle ) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id ) AS listcampagne,
                        ( SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id ) AS listservice,
                        shift_begin,
                        shift_end,
                        TIMEDIFF(shift_end,shift_begin) AS shiftTotal,
                        shift_ajustbegin,
                        shift_ajustend,
                        TIMEDIFF(shift_ajustend,shift_ajustbegin) AS ajustTotal,
                        ( SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(pause_end,pause_begin)))) FROM t_pause WHERE pause_shift = shift_id ) AS total_pause,
                        ( SELECT COUNT(pause_id) FROM t_pause WHERE pause_shift = shift_id ) AS nb_pause
                    FROM
                        t_shift
                        INNER JOIN tr_user ON usr_id = shift_userid
                        INNER JOIN tr_site AS sitecontrat ON sitecontrat.site_id = usr_contrat 
                    WHERE
                        shift_day >= '$du' 
                        AND shift_day <= '$au' 
                    ORDER BY
                        shift_day ASC,
                        shift_userid ASC
        ";

        $query = $this->db->query($query_sql);

        if($query->num_rows() > 0){
            $result = $query->result();
            return $result;
        }else{
            return false;
        }
    }

    public function getUsersHistorique($filtre){

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

        $this->db
            ->select('shift_id, shift_day, shift_loggedin, shift_begin, shift_end, shift_ajustbegin, shift_ajustend, shift_ajustmodifier')
            ->select('usr_id, usr_prenom, usr_matricule, usr_pseudo')
            ->select('contrat.site_libelle as site_contrat, site.site_libelle as user_site')
            ->select('pause_begin, pause_end, pause_type, pause_etat, typepause_libelle, pause_ajustbegin, pause_ajustend,')
            ->select("TIMEDIFF(pause_end, pause_begin) AS pause_duration", FALSE)
            ->select("TIMEDIFF(pause_ajustend, pause_ajustbegin) AS pause_duration_ajust,", FALSE)
            ->select('pointage_in, pointage_break, pointage_resume, pointage_out, pointage_ot, pointage_done, pointage_workhour')
            ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS list_campagne", FALSE)
            ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS list_service", FALSE)    
            ->select('planning_entree, planning_sortie, planning_off')
            ->select('
                        CASE 
                            WHEN pointage_in < TIME( shift_begin ) THEN TIMEDIFF(pointage_in, planning_entree) 
                        ELSE
                            TIMEDIFF(TIME(shift_begin), planning_entree)
                        END as retard', FALSE)
            ->from($this->_table)
            ->join('tr_user', 'usr_id = shift_userid', 'inner')
            ->join('tr_site as contrat', 'contrat.site_id = usr_contrat', 'inner')
            ->join('tr_site as site', 'site.site_id = usr_site', 'inner')
            ->join('t_pause', 'pause_shift = shift_id', 'left')
            ->join('tr_typepause', 'typepause_id = pause_type', 'left')
            ->join('t_pointage', 'pointage_user = usr_id AND pointage_date = shift_day', 'left')
            
            ->join('t_planning', 'planning_user = usr_id AND planning_date = shift_day', 'left')
            ->order_by('retard DESC, shift_day DESC, usr_prenom ASC, pause_begin ASC')
            ;
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

        if($du !== false) $this->db->where('shift_day >=', $du);
        if($au !== false) $this->db->where('shift_day <=', $au);
        if($user !== false) $this->db->where('usr_id', $user);

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;

    }
    
}
