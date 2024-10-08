<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planning_model extends CI_Model {

    private $_table = 't_planning';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }


    public function insertPlanning($data)
    {
        
        if(is_array($data) && !empty($data)){
            
            if($this->db->insert($this->_table, $data)) return $this->db->insert_id();
        
        }
        return false;
    }

    public function insertBatchPlanning($entry){
        $result = false;
        if(is_array($entry) && !empty($entry)){
            $result = $this->db->insert_batch($this->_table, $entry);
        }
        return $result;
    }

    public function getPlanning($id){
        $query = $this->db->select('planning_id, planning_date, planning_user, planning_entree, planning_sortie, planning_off, planning_hs, planning_datecrea')
                    //->join('tr_user', 'usr_id = planning_user', 'inner')
                    ->where('planning_id', $id)
                    //->where_not_in('usr_role ', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_CADRE2, ROLE_DIRECTION, ROLE_COSTRAT, ROLE_CADRE, ROLE_REPORTING])
                    ->get($this->_table);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return false;
    }

    public function updatePlanning($id, $data)
    {
        
        if(is_array($data) && !empty($data)){
            $this->db->where('planning_id', $id);
            $this->db->update($this->_table, $data);
            if($this->db->affected_rows() > 0) return true;
            return false;
        }
        return false;
    }

    /**
    * Prendre la ligne de planning d'un user donné  
    */

    public function getUserPlanningByDate($user, $date, $site)
    {

        $query = $this->db->select('planning_id, planning_date, planning_user, planning_in, planning_break, planning_resume, planning_out, planning_ot, planning_done')
            ->where('planning_user', $user)
            ->where('planning_date', $date)
            ->where('planning_site', $site)
            ->get($this->_table);
        
        if($query->num_rows() > 0){
            return $query->row();
        }

        return false;
        
    }

    public function deletePlanningByDateAndAgents($du, $au, $listAgent){
        $this->db->where('planning_date >=', $du)
                    ->where('planning_date <=', $au)
                    ->where_in('planning_user', $listAgent)
                    ->delete($this->_table);

        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }

    public function ___getPlanningByTypeAndDates($type, $id, $duPlanning, $auPlanning, $poids){
        
        
        $this->db->where('role_poids <', $poids);
        $whereSubQuery = $this->db->get_compiled_select();
        
        $this->db->select('planning_id, planning_date, planning_user, usr_prenom, planning_entree, planning_sortie, planning_off') 
        ->from('t_planning')
        ->join('tr_user', 'usr_id = planning_user', 'inner')
        ->where('planning_date >=', $duPlanning)
        ->where('planning_date <=', $auPlanning)
        ->where_not_in('usr_role ', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_CADRE2, ROLE_DIRECTION, ROLE_COSTRAT, ROLE_CADRE, ROLE_REPORTING])
        ->order_by('usr_prenom ASC, planning_date ASC');
        
        if($type == 'campagne'){
            
            $this->db->where("EXISTS 
                        (SELECT `usercampagne_user` 
                            FROM `t_usercampagne`  
                            INNER JOIN `tr_user` ON `usr_id` = `usercampagne_user` 
                            INNER JOIN `tr_role` ON `role_id` = `usr_role` 
                            WHERE 
                                `usercampagne_campagne` = '".$id."' 
                                AND `role_poids` < '".$poids."' 
                                AND planning_user = usercampagne_user
                        )");
        }else if($type == 'service'){
            
            $this->db->where("EXISTS 
                        (SELECT `userservice_user` 
                            FROM `t_userservice`  
                            INNER JOIN `tr_user` ON `usr_id` = `userservice_user` 
                            INNER JOIN `tr_role` ON `role_id` = `usr_role` 
                            WHERE 
                                `userservice_service` = '".$id."' 
                                AND `role_poids` < '".$poids."' 
                                AND planning_user = userservice_user
                        )");
        }

        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }
    public function getPlanningByTypeAndDates($type, $id, $duPlanning, $auPlanning, $poids){
        
        
        $this->db->select('planning_id, planning_date, planning_user, usr_id,site_libelle,usr_matricule, usr_prenom, usr_pseudo, planning_entree, planning_sortie, planning_off, planning_hs') 
        ->from('tr_user')
        ->join('tr_site', 'usr_contrat= site_id', 'inner')
        ->join('t_planning', 'usr_id = planning_user AND planning_date >= "' . $duPlanning . '" AND planning_date <= "' . $auPlanning . '"', 'left')
        ->where('usr_actif', '1')
        ->where_not_in('usr_role ', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_DIRECTION, ROLE_COSTRAT, ROLE_REPORTING])
        ->order_by('usr_prenom ASC, planning_date ASC');
        
        if($type == 'campagne'){
            
            $this->db->where("usr_id IN  
                        (SELECT `usercampagne_user` 
                            FROM `t_usercampagne`  
                            INNER JOIN `tr_user` ON `usr_id` = `usercampagne_user` 
                            INNER JOIN `tr_role` ON `role_id` = `usr_role` 
                            WHERE 
                                `usercampagne_campagne` = '".$id."' 
                                AND `role_poids` <= '".$poids."' 
                                AND `role_id` != '9'
                        )", null, false);
        }else if($type == 'service'){
            
            $this->db->where("usr_id IN  
                        (SELECT `userservice_user` 
                            FROM `t_userservice`  
                            INNER JOIN `tr_user` ON `usr_id` = `userservice_user` 
                            INNER JOIN `tr_role` ON `role_id` = `usr_role` 
                            WHERE 
                                `userservice_service` = '".$id."' 
                                AND `role_poids` <= '".$poids."'
                                AND `role_id` != '9' 
                        )", null, false);
        }

        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }


}

    