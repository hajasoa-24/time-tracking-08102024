<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presence_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }

    private $_id,
            $_date,
            $_user,
            $_shift,
            $_present,
            $_incomplet,
            $_motif,
            $_modificateur,
            $_datecrea,
            $_datemodif,
            $_table = 't_presence';
    
    //PRIVATE FUNCTIONS
    /**
     * Getter & setter presence_id
     */
    private function _getId()
    {
        return $this->_id;
    }

    private function _setId($id)
    {
        $this->_id = $id;
    }

    /**
     * Getter & setter presence_date
     */

    private function _getDate()
    {
        return $this->_date;
    }

    private function _setDate($date)
    {
        $this->_date = $date;
    }

    /**
     * Getter & setter presence_user
     */

    private function _getUser()
    {
        return $this->_user;
    }

    private function _setUser($userId)
    {
        $this->_user = $userId;
    }

    /**
     * Getter & setter presence_shift
     */

    private function _getShift()
    {
        return $this->_shift;
    }

    private function _setShift($shiftId)
    {
        $this->_shift = $shiftId;
    }

    /**
     * Getter & setter presence_present
     */

    private function _getPresent()
    {
        return $this->_present;
    }

    private function _setPresent($present)
    {
        $this->_present = $present;
    }

    /**
     * Getter & setter presence_incomplet
     */

    private function _getIncomplet()
    {
        return $this->_incomplet;
    }

    private function _setIncomplet($incomplet)
    {
        $this->_incomplet = $incomplet;
    }

    /**
     * Getter & setter presence_motif
     */

    private function _getMotif()
    {
        return $this->_motif;
    }

    private function _setMotif($motif)
    {
        $this->_motif = $motif;
    }

    /**
     * Getter & setter presence_modificateur
     */

    private function _getModificateur()
    {
        return $this->_modificateur;
    }

    private function _setModificateur($modificateur)
    {
        $this->_modificateur = $modificateur;
    }

    /**
     * Getter & setter presence_datecrea & presence_datemodif
     */

    private function _setDatecrea($datecrea)
    {
        $this->_datecrea = $datecrea;
    }

    private function _setDatemodif($datemodif)
    {
        $this->_datemodif = $datemodif;
    }


    //PUBLIC FUNCTIONS

    public function initPresenceDay()
    {
        $query_string = 'INSERT INTO t_presence ( presence_date, presence_user, presence_present, presence_incomplet, presence_datecrea, presence_datemodif ) 
                    SELECT NOW(), usr_id, 0, 0, NOW(), NOW() 
                    FROM
                        tr_user 
                    WHERE
                        usr_actif = 1
                    AND usr_role != 1';
                    

        $query = $this->db->query($query_string);
        
        return true;
    }

    public function checkPresenceDay()
    {
        $query_string = 'SELECT 
                            presence_id
                        FROM t_presence
                        WHERE presence_date = CURRENT_DATE()';
        $query = $this->db->query($query_string);
        //var_dump($query->num_rows()); die;
        if($query->num_rows() > 0) return true;

        return false;
    }


    public function updatePresence($date = false)
    {
        $query_string = 'UPDATE t_presence
                        LEFT JOIN t_shift ON presence_date = shift_day AND presence_user = shift_userid 
                        LEFT JOIN t_pointage ON pointage_date = presence_date AND pointage_user = presence_user
                        LEFT JOIN t_planning ON planning_date = presence_date AND planning_user = presence_user 
                        LEFT JOIN t_conge ON conge_user = presence_user AND DATE(conge_datedebut) <= presence_date AND DATE(conge_datefin) >= presence_date
                        SET 
                            presence_present = CASE WHEN planning_off = \'1\' THEN 0 ELSE CASE WHEN conge_id IS NOT NULL THEN 0 ELSE CASE WHEN shift_loggedin IS NOT NULL THEN 1 ELSE CASE WHEN pointage_in IS NOT NULL THEN 1 ELSE 0 END END END END, 
                            presence_loggedin = CASE WHEN shift_loggedin IS NOT NULL THEN shift_loggedin ELSE pointage_in END, 
                            presence_loggedout = CASE WHEN shift_end IS NOT NULL THEN shift_end ELSE pointage_out END,
                            presence_retard = CASE 
                                                WHEN pointage_in IS NOT NULL AND shift_begin IS NOT NULL THEN 
                                                    CASE WHEN pointage_in < TIME(shift_begin) THEN IF(planning_entree IS NOT NULL AND TIMEDIFF(pointage_in, planning_entree) > 0, TIMEDIFF(pointage_in, planning_entree), NULL) 
                                                    ELSE 
                                                        IF(planning_entree IS NOT NULL AND TIMEDIFF(TIME(shift_begin), planning_entree) > 0, TIMEDIFF(TIME(shift_begin), planning_entree), NULL)
                                                    END
                                                WHEN pointage_in IS NOT NULL AND shift_begin IS NULL THEN IF(planning_entree IS NOT NULL AND TIMEDIFF(pointage_in, planning_entree) > 0, TIMEDIFF(pointage_in, planning_entree), NULL)
                                                WHEN pointage_in IS NULL AND shift_begin IS NOT NULL THEN IF(planning_entree IS NOT NULL AND TIMEDIFF(TIME(shift_begin), planning_entree) > 0, TIMEDIFF(TIME(shift_begin), planning_entree), NULL)
                                                WHEN pointage_in IS NULL AND shift_begin IS NULL AND conge_id IS NULL THEN IF(planning_entree IS NOT NULL AND planning_off != 1 AND TIMEDIFF(TIME(NOW()), planning_entree) > 0, TIMEDIFF(TIME(NOW()), planning_entree), NULL)
                                                ELSE NULL END,

        
                            presence_shift = shift_id';
        if(!$date){
            $date = date('Y-m-d');
        }
        $query_string .= ' WHERE presence_date = "' . $date . '"';

        $query = $this->db->query($query_string);
    }

    

    public function updatePresenceMotif($date)
    {
        $query_string = 'UPDATE t_presence
                        INNER JOIN t_motifpresence ON presence_date = motifpresence_day AND presence_user = motifpresence_agent
                        SET 
                            presence_incomplet = motifpresence_incomplet,
                            presence_motif = motifpresence_motif,
                            presence_modificateur = motifpresence_modificateur,
                            presence_datemotif = motifpresence_datecrea';
        if(!$date){
            $date = date('Y-m-d');
        }
        $query_string .= ' WHERE presence_date = "' . $date . '"';

        $query = $this->db->query($query_string);
    }


    public function getHistoPresence($du = false, $au = false, $listAgent = false)
    {
        $this->db->select('presence_id, presence_date, presence_user, presence_shift, presence_present, presence_datemotif, presence_loggedin, presence_loggedout, presence_datecrea, presence_datemodif, motif_id, motif_libelle, planning_id, planning_user, planning_off, planning_date, conge_id')
                    ->select('utl.usr_id, utl.usr_prenom, utl.usr_matricule, utl.usr_initiale, utl.usr_username, utl.usr_pseudo')
                    ->select('site_id, site_libelle')
                    ->select('motifpresence_incomplet, motifpresence_motif, motifpresence_modificateur')
                    ->select('md.usr_prenom AS modificateur')
                    ->select('typeconge_libelle')
                    ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = presence_user) AS list_campagne", FALSE)
                    ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = presence_user) AS list_service", FALSE)
                    ->join('tr_user as utl', 'presence_user = utl.usr_id', 'inner')
                    ->join('tr_site', 'usr_site = site_id', 'inner')
                    ->join('t_motifpresence', 'presence_user = motifpresence_agent AND presence_date = motifpresence_day', 'left')
                    ->join('tr_motif', 'motif_id = motifpresence_motif ', 'left')
                    ->join('tr_user as md', 'md.usr_id = motifpresence_modificateur', 'left')
                    
                    ->join('t_conge', 'conge_user = presence_user AND ( DATE(conge_datedebut) <= presence_date AND DATE(conge_datefin) >= presence_date )', 'left')
                    ->join('tr_typeconge', 'typeconge_id = conge_type', 'left')
                    ->join('t_planning', 'planning_user = presence_user AND planning_date = presence_date', 'left')
                    ;
                     
        if($du){
            $this->db->where('presence_date >=', $du);
        }
        if($au){
            $this->db->where('presence_date <=', $au);
        }
        if(is_array($listAgent) && !empty($listAgent)){
            $this->db->where_in('presence_user', $listAgent);
        }
        
        $query = $this->db->get($this->_table);
       //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }


   public function getPresenceGroupByDay($listUser, $du = FALSE, $au = FALSE)
    {

        $this->db->select('COUNT(presence_id) AS nb, presence_present, motifpresence_incomplet, presence_date')
                    ->select('COUNT(conge_id) AS nb_conge, conge_datedebut, conge_datefin, conge_type')
                    //->select('SUM(planning_off = \'1\') AS nb_off, planning_id, planning_user, planning_date')
                    ->select('planning_off, planning_date')
                    ->from($this->_table)
                    ->join('t_motifpresence', 'motifpresence_day = presence_date AND motifpresence_agent = presence_user', 'left')
                    ->join('tr_user', 'usr_id = presence_user', 'inner')
                    ->join('t_conge', 'conge_user = presence_user AND ( DATE(conge_datedebut) <= presence_date AND DATE(conge_datefin) >= presence_date )', 'left')
                    ->join('tr_typeconge', 'typeconge_id = conge_type', 'left')
                    ->join('t_planning', 'planning_user = presence_user AND planning_date = presence_date', 'left')
                    ->where_not_in('usr_role ', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_CADRE2, ROLE_DIRECTION, ROLE_COSTRAT, ROLE_CADRE, ROLE_REPORTING])
                    ->group_by('presence_date, presence_present, motifpresence_incomplet, planning_off, planning_date')
                    ->order_by('presence_date', 'ASC');
        if($du){

            $this->db->where('presence_date >=', $du);
        }
        if($au){
            $this->db->where('presence_date <=', $au);
        }


        if(is_array($listUser) && !empty($listUser)){
            $this->db->where_in('presence_user', $listUser);
        }

        $query = $this->db->get();
        //echo $this->db->last_query();
        if($query->num_rows() > 0) return $query->result();

        return false;
    }

    public function getListUserWhichIs($date, $typeId, $type, $filtre = false)
    {
        $whereSubQuery = false;
        if($type == 'campagne'){
            $this->db->select('usercampagne_user')
                        ->from('t_usercampagne')
                        ->join('tr_user', 'usr_id = usercampagne_user')
                        ->join('tr_role', 'role_id = usr_role')
                        ->where('usercampagne_campagne', $typeId);
    
            if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
            $whereSubQuery = $this->db->get_compiled_select();
        }else if($type == 'service'){

            $this->db->select('userservice_user')
                        ->from('t_userservice')
                        ->join('tr_user', 'usr_id = userservice_user')
                        ->join('tr_role', 'role_id = usr_role')
                        ->where('userservice_service', $typeId);
    
            if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
            $whereSubQuery = $this->db->get_compiled_select();  
        }

        $action = (isset($filtre['action'])) ? $filtre['action'] : false;

        $this->db->select('presence_user, usr_prenom')
                ->from($this->_table)
                ->join('tr_user', 'usr_id = presence_user', 'inner')
                ->join('t_planning', 'planning_user = presence_user AND planning_date = presence_date', 'left')
                ->join('t_conge', 'conge_user = presence_user AND conge_datedebut <= presence_date AND conge_datefin >= presence_date', 'left')
                ->where_not_in('usr_role ', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_CADRE2, ROLE_DIRECTION, ROLE_COSTRAT, ROLE_CADRE, ROLE_REPORTING])
                ->where('presence_date', $date)
                ;
        if($action == 'absent'){
            $this->db->where('presence_present !=', '1')
                        ->where('conge_type IS NULL', null, false)
                        ->where('planning_id IS NULL', null, false)
                        ->where('(planning_off IS NULL OR planning_off != 1)', null, false)
                        ;
        }else if($action == 'present'){
            $this->db->where('presence_present =', 1)
                        ->where('conge_type IS NULL', null, false)
                        ->where('(planning_off IS NULL OR planning_off != 1)', null, false)
                        ;
        }else if($action == 'incomplet'){
            $this->db->join('t_motifpresence', 'motifpresence_day = presence_date AND motifpresence_agent = presence_user', 'left')
                        ->where('motifpresence_incomplet', '1')
                        ;
        }else if($action == 'conge'){
            $this->db->where('conge_type IS NOT NULL', null, false)
                        ;
        }else if($action == 'off'){
            $this->db->where('planning_off', '1')
                        ;
        }else if($action == 'pasencorearrive'){
            $this->db->where('planning_id IS NOT NULL', null, false)
                    ->where('planning_off !=', '1')
                    ->where('presence_present !=', '1')
                        ;
        }

        if($whereSubQuery) $this->db->where('presence_user IN (' . $whereSubQuery . ')', null, false);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }


    public function getAllPresence($du = false, $au = false)
    {
        $this->db->select('presence_id, presence_date, presence_user, presence_shift, presence_present, presence_datemotif, presence_loggedin, presence_loggedout, presence_datecrea, presence_datemodif')
                    ->select('utl.usr_id, utl.usr_prenom, utl.usr_matricule, utl.usr_initiale, utl.usr_username')
                    ->select('site_id, site_libelle')
                    ->select('motifpresence_incomplet, motifpresence_motif, motifpresence_modificateur')
                    ->select('md.usr_prenom AS modificateur')
                    ->join('tr_user as utl', 'presence_user = utl.usr_id', 'inner')
                    ->join('tr_site', 'usr_site = site_id', 'inner')
                    ->join('t_motifpresence', 'presence_user = motifpresence_agent AND presence_date = motifpresence_day', 'left')
                    ->join('tr_user as md', 'md.usr_id = motifpresence_modificateur', 'left')
                    ;
        if($du){
            $this->db->where('presence_date >=', $du);
        }
        if($au){
            $this->db->where('presence_date <=', $au);
        }
       
        
        
        $query = $this->db->get($this->_table);
       //echo $this->db->last_query();
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

   public function getListRetardJour($filtre){
        
        $poids = $filtre['user_poids'];
        $whereSubQuery1 = false;
        $whereSubQuery2 = false;

        if(isset($filtre['list_campagne']) && !empty($filtre['list_campagne'])){
            $this->db->select('usercampagne_user')
                        ->from('t_usercampagne')
                        ->join('tr_user', 'usr_id = usercampagne_user')
                        ->join('tr_role', 'role_id = usr_role')
                        ->where_in('usercampagne_campagne', $filtre['list_campagne']);

            if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
            $whereSubQuery1 = $this->db->get_compiled_select();
        }

        if(isset($filtre['list_service']) && !empty($filtre['list_service'])){
            $this->db->select('userservice_user')
                        ->from('t_userservice')
                        ->join('tr_user', 'usr_id = userservice_user')
                        ->join('tr_role', 'role_id = usr_role')
                        ->where_in('userservice_service', $filtre['list_service']);

            if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
            $whereSubQuery2 = $this->db->get_compiled_select();
        }

        $this->db->select('planning_id, planning_date, planning_user, planning_entree as planning')
                    ->select('pointage_in as pointage')
                    ->select('TIME(shift_loggedin) as shift')
                    ->select('
                            CASE 
                                WHEN pointage_in < TIME( shift_begin ) THEN TIMEDIFF(pointage_in, planning_entree) 
                            ELSE
                                TIMEDIFF(TIME(shift_loggedin), planning_entree)
                            END as retard
                    ', FALSE)
                    
                    ->select('usr_id, usr_prenom as agent, usr_matricule as matricule, usr_initiale as initiale')
                    ->select('site_id, site_libelle as site') 
                    ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = planning_user) AS list_campagne", FALSE)
                    ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = planning_user) AS list_service", FALSE)
                    ->join('t_planning', 'planning_user = usr_id', 'inner')
                    ->join('tr_site', 'usr_site = site_id', 'inner')
                    ->join('t_pointage', 'pointage_user = usr_id AND planning_date = pointage_date', 'left')
                    ->join('t_shift', 'shift_userid = usr_id AND shift_day = planning_date', 'left')
                    ;
                     
        if(isset($filtre['day']) && !empty($filtre['day'])){
            $this->db->where('planning_date =', $filtre['day']);
        }

        $whereIn = '';
        if($whereSubQuery1){
            $whereIn .= "usr_id IN ($whereSubQuery1)";
        }

        if($whereSubQuery2){
            if($whereIn != '') $whereIn .= ' OR ';
            $whereIn .= "usr_id IN ($whereSubQuery2)";
        }

        if($whereIn != ''){
            $this->db->where("($whereIn)", NULL, FALSE);
        }

        $this->db->having('retard > 0', null, FALSE);
        
        $query = $this->db->get('tr_user');
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;

    }

    public function getPresenceDatasPerDay($id, $filtre)
    {
        
        $whereSubQuery = false;
        if(!isset($filtre['type'])) return false;

        $type = $filtre['type'];

        if($type == 'campagne'){

            $this->db->select('usercampagne_user')
                        ->from('t_usercampagne')
                        ->join('tr_user', 'usr_id = usercampagne_user')
                        ->join('tr_role', 'role_id = usr_role')
                        ->where('usercampagne_campagne =', $id);

            if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
            $whereSubQuery = $this->db->get_compiled_select();   
        }else if($type == 'service'){

                $this->db->select('userservice_user')
                            ->from('t_userservice')
                            ->join('tr_user', 'usr_id = userservice_user')
                            ->join('tr_role', 'role_id = usr_role')
                            ->where('userservice_service =', $id);
    
                if(isset($filtre['user_poids']) && !empty($filtre['user_poids'])) $this->db->where('role_poids <', $filtre['user_poids']);
                $whereSubQuery = $this->db->get_compiled_select();
        }


        $this->db->select('presence_id, presence_date, presence_present')
                    ->select('planning_id, planning_off, conge_type, conge_motif')
                    ->select('motifpresence_incomplet, motifpresence_motif, motif_libelle')
                    ->from($this->_table)
                    ->join('tr_user', 'usr_id = presence_user', 'inner')
                    ->join('t_planning', 'planning_user = presence_user AND planning_date = presence_date', 'left')
                    ->join('t_conge', 'conge_user = presence_user AND conge_datedebut <= presence_date AND conge_datefin >= presence_date', 'left')
                    ->join('t_motifpresence', 'motifpresence_agent = presence_user AND motifpresence_day = presence_date', 'left')
                    ->join('tr_motif', 'motifpresence_motif = motif_id', 'left')
                    ->where_not_in('usr_role ', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_CADRE2, ROLE_DIRECTION, ROLE_COSTRAT, ROLE_CADRE, ROLE_CLIENT, ROLE_REPORTING])
                    ->order_by('presence_date ASC, presence_present ASC')
                    ;
                     
        if(isset($filtre['Du']) && isset($filtre['Au'])){
            $this->db->where('presence_date >=', $filtre['Du']);
            $this->db->where('presence_date <=', $filtre['Au']);
        }

        if($whereSubQuery){
            $whereIn = "usr_id IN ($whereSubQuery)";
            $this->db->where("($whereIn)", NULL, FALSE);
        }
        
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

    public function getAbsencesAnormales()
    {
        
        $this->db->select('utl.usr_prenom, utl.usr_matricule, utl.usr_initiale')
                ->select('site_libelle')
                ->select('presence_motif as motif_absence, presence_date, presence_modificateur')
                ->select('md.usr_prenom AS modificateur')
                ->from($this->_table)
                ->join('tr_user as utl', 'utl.usr_id = presence_user', 'inner')
                ->join('tr_user as md', 'md.usr_id = presence_modificateur', 'left')
                ->join('tr_site', 'utl.usr_site = site_id', 'inner')
                ->where('utl.usr_actif', 1)
                ->where_in('presence_motif', LIST_ABSENCESANORMALES)
                ->or_where('( presence_present = 0 AND presence_loggedin IS NULL AND ( presence_date BETWEEN CURDATE() AND CURDATE() - interval 3 day ) )', null, false);
                //->group_by('usr_id');

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

    public function countAgentPresenceBetweenBetweenDays(string $dateBegin, string $dateEnd, int $user)
    {
        $this->db->select('COUNT(presence_id) AS nbPresence')
                ->where('presence_present', 1)
                ->where('presence_user', $user)
                ->where('presence_date >=', $dateBegin)
                ->where('presence_date <=', $dateEnd)
                ;
        $query = $this->db->get('t_presence');
        return ($query->row()) ? $query->row()->nbPresence : 0;
    }

}

