<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Conges_model extends CI_Model {

    private $_table = 't_conge';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function startTransaction(){
        $this->db->trans_begin();
    }

    public function endTransaction(){
        if ($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_commit();
            return true;
        }
    }

    public function getAllConges($user = false)
    {
        $this->db->select('conge_id, conge_user, conge_datedebut, conge_datefin, conge_dateretour, conge_duree, conge_type, conge_etat, conge_motif, conge_datecrea, conge_datemodif')
                    ->select('etatconge_libelle, typeconge_libelle, usr_prenom')    
                    ->join('tr_etatconge', 'conge_etat = etatconge_id', 'inner')
                    ->join('tr_user', 'conge_user = usr_id', 'inner')
                    ->join('tr_typeconge', 'conge_type = typeconge_id', 'inner');
        if(is_array($user)){
            $this->db->where_in('conge_user', $user);
        }else if($user){
            $this->db->where('conge_user', $user);
        }
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertConge($data)
    {
        $entry = array(
            'conge_user' => (isset($data['conge_user'])) ? $data['conge_user'] : '',
            'conge_datedebut' => (isset($data['conge_datedebut'])) ? $data['conge_datedebut'] : '',
            'conge_datefin' => (isset($data['conge_datefin'])) ? $data['conge_datefin'] : '',
            'conge_dateretour' => (isset($data['conge_dateretour'])) ? $data['conge_dateretour'] : '', 
            'conge_duree' => (isset($data['conge_duree'])) ? $data['conge_duree'] : '', 
            'conge_type' => (isset($data['conge_type'])) ? $data['conge_type'] : '', 
            'conge_motif' => (isset($data['conge_motif'])) ? $data['conge_motif'] : '', 
            'conge_etat' => (isset($data['conge_etat'])) ? $data['conge_etat'] : '', 
            'conge_datecrea' => date('Y-m-d H:i:s'),
            'conge_datemodif' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }

    public function updatetConge($data, $id){
        $entry = array(
            'conge_user' => $data['conge_user'],
            'conge_datedebut' =>  $data['conge_datedebut'],
            'conge_datefin' => $data['conge_datefin'],
            'conge_dateretour' => $data['conge_dateretour'], 
            'conge_duree' => $data['conge_duree'], 
            'conge_type' => $data['conge_type'], 
            'conge_motif' => $data['conge_motif'], 
            //'conge_etat' => $data['conge_etat'], 
            //'conge_datecrea' => date('Y-m-d H:i:s'),
            'conge_datemodif' => date('Y-m-d H:i:s')
        );
        $this->db->where('conge_id', $id);
        $this->db->update('t_conge', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;
    }

    public function updateType($id, $type){
        $entry = array(
            'conge_type' => $type,
            'conge_datemodif' => date('Y-m-d H:i:s')
        );
        $this->db->where('conge_id', $id);
        $this->db->update('t_conge', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;
    }

    public function deleteConge($id){
        $return = $this->db->where('histoetatconge_conge', $id)->delete('t_histoetatconge');
        if($return)
            $return = $this->db->where('conge_id', $id)->delete('t_conge');

        return $return;
    }


    public function getConge($id){

        return $this->db->get_where( $this->_table, array('conge_id' => $id))->row();
    }

    public function getAllTypeConge(){
        $this->db->select('typeconge_id, typeconge_libelle')
                ->from('tr_typeconge');
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }


    /**
     * Récupérer la liste des holidays (jours fériés) en array
     */
    public function countHolidays($from, $to){

        $this->db->where('holidays_date >=', $from)
                    ->where('holidays_date <=', $to);
        $count = $this->db->count_all_results('tr_holidays');

        return $count;
    }

    public function getMonthHolydays($month, $year)
    {
        $this->db->select('holidays_date, holidays_libelle')
                ->where('MONTH(holidays_date)', $month, false)
                ->where('YEAR(holidays_date)', $year, false);

        $query = $this->db->get('tr_holidays');

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    /**
     * Vérifier si des demandes de congés sont déjà en cours pour la plage de date demandée
     */
    public function isAvailableDate($from, $to, $conge_user, $conge_id = false){

        $this->db
                    ->where_not_in('conge_etat', '5')
                    ->where("(conge_datedebut < '" . $to . "' AND conge_datefin > '" . $from . "')")
                    //->or_where("(conge_datedebut <= '" . $to . "' AND conge_datefin >= '" . $to . "'))")
                    ->where('conge_user', $conge_user);
        if($conge_id){
            $this->db->where('conge_id != ', $conge_id);
        }
        
        $count = $this->db->count_all_results($this->_table);
        //  var_dump($this->db->last_query()); die;
        return ($count > 0) ? false : true;
    }

    public function getSoldeConge($user){
        $this->db->select('usr_soldeconge')
                ->where('usr_id', $user);
        $query = $this->db->get('tr_user');

        if($query->num_rows() > 0) return $query->row()->usr_soldeconge;

        return 0;
    }
    public function getSoldePermission($user){
        $this->db->select('usr_droitpermission')
                ->where('usr_id', $user);
        $query = $this->db->get('tr_user');

        if($query->num_rows() > 0) return $query->row()->usr_droitpermission;

        return 0;
    }

    public function setSoldeConge($user, $solde){
        $entry = array(
            'usr_soldeconge' => $solde
        );
        $this->db->where('usr_id', $user)
                    ->update('tr_user', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    public function getDroitPermission($user){
        $this->db->select('usr_droitpermission')
                ->where('usr_id', $user);
        $query = $this->db->get('tr_user');

        if($query->num_rows() > 0) return $query->row()->usr_droitpermission;

        return 0;
    }

    public function setDroitPermission($user, $droitPermission){
        $entry = array(
            'usr_droitpermission' => $droitPermission
        );
        $this->db->where('usr_id', $user)
                    ->update('tr_user', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }


    public function getAllCongesToValidate($listAgent = false, $etat = false)
    {
         $this->db->select('conge_id, conge_user, conge_datedebut, conge_datefin, conge_dateretour, conge_duree, conge_type, conge_etat, conge_motif, conge_commentaire, conge_datecrea, conge_datemodif')
                    ->select('etatconge_libelle, typeconge_libelle')
                    ->select('usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_site, usr_contrat, usr_soldeconge, usr_droitpermission, usr_pseudo')
                    ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = conge_user) AS list_campagne", FALSE)
                    ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = conge_user) AS list_service", FALSE)
                    ->join('tr_etatconge', 'conge_etat = etatconge_id', 'inner')
                    ->join('tr_user', 'conge_user = usr_id', 'inner')
                    ->join('tr_typeconge', 'conge_type = typeconge_id', 'inner');
        
        if(is_array($listAgent) && !empty($listAgent)){
            $this->db->where_in('conge_user', $listAgent);
        }
        if(is_array($etat) && !empty($etat)){
            $this->db->where_in('conge_etat', $etat);
        }else if($etat){
            $this->db->where('conge_etat', $etat);
        }
        $query = $this->db->get($this->_table);
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }


    public function validateConge($id, $etat){
        $entry = [
            'conge_etat' => $etat
        ];
        $this->db->where('conge_id', $id);
        $this->db->update('t_conge', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;
    }

    public function refuserConge($id){
        $entry = [
            'conge_etat' => REFUSE
        ];
        $this->db->where('conge_id', $id);
        $this->db->update('t_conge', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;
    }
    
    public function commentConge($id, $commentaire){
        
        //$commentaire = 
        $entry = [
            'conge_commentaire' => $commentaire
        ];
        $this->db->where('conge_id', $id);
        $this->db->update('t_conge', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;
    }
  


    public function getAllCongesToTreate($listAgent = false, $etat = false)
    {
        $this->db->select('conge_id, conge_user, conge_datedebut, conge_datefin, conge_dateretour, conge_duree, conge_type, conge_etat, conge_motif, conge_datecrea, conge_datemodif')
                    ->select('etatconge_libelle, typeconge_libelle, usr_prenom, usr_contrat, usr_soldeconge, usr_droitpermission') 
                    ->select('contrat.site_libelle as site_contrat')   
                    ->join('tr_etatconge', 'conge_etat = etatconge_id', 'inner')
                    ->join('tr_user', 'conge_user = usr_id', 'inner')
                    ->join('tr_site as contrat', 'usr_contrat = contrat.site_id', 'left')
                    ->join('tr_typeconge', 'conge_type = typeconge_id', 'inner');
        
        if(is_array($listAgent) && !empty($listAgent)){
            $this->db->where_in('conge_user', $listAgent);
        }
        if(is_array($etat)){
            $this->db->where_in('conge_etat', $etat);
        }
        $query = $this->db->get($this->_table);
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertAttributionConge($entry){

        if($this->db->insert('t_attributionconge', $entry)) return $this->db->insert_id();

        return false;
    }

    public function isSetSolde($user, $month, $year){
        $query = $this->db->select('attribconge_id')
                    ->where('attribconge_user', $user)
                    ->where('attribconge_month', $month)
                    ->where('attribconge_year', $year)
                    ->get('t_attributionconge');
        if($query->num_rows() > 0) return true;

        return false;
    }

    public function getCongesByEtat($etat){

        $this->db->select('conge_id, conge_user, conge_datedebut, conge_datefin, conge_dateretour, conge_duree, conge_type, conge_etat, conge_motif, conge_datecrea, conge_datemodif')
                    ->select('etatconge_libelle, typeconge_libelle, usr_prenom')    
                    ->join('tr_etatconge', 'conge_etat = etatconge_id', 'inner')
                    ->join('tr_user', 'conge_user = usr_id', 'inner')
                    ->join('tr_typeconge', 'conge_type = typeconge_id', 'inner');
        
        if(is_array($etat)){
            $this->db->where_in('conge_etat', $etat);
        }else if($etat != ''){
            $this->db->where('conge_etat', $etat);
        }
        $query = $this->db->get($this->_table);
        //echo $this->db->last_query(); 
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getInfosListEtatConge($list){

        $this->db->select('etatconge_id, etatconge_libelle')
                ->where_in('etatconge_id', $list);
        $query = $this->db->get('tr_etatconge');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

}

    