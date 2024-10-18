<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campagne_model extends CI_Model {

    private $_id,
            $_libelle,
            $_pole,
            $_site,
            $_proprio,
            $_ipserveur,
            $_actif,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_campagne';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllCampagne($onlyActive = false)
    {
        $this->db->select('campagne_id, campagne_libelle, campagne_pole, campagne_site, campagne_proprio, campagne_client, campagne_ipserveur, campagne_actif, campagne_datecrea, campagne_datemodif')
                    ->select('pole_libelle, site_libelle, proprio_libelle')    
                    ->join('tr_pole', 'campagne_pole = pole_id', 'inner')
                    ->join('tr_proprio', 'campagne_proprio = proprio_id', 'left')
                    ->join('tr_site', 'campagne_site = site_id', 'inner');
        if($onlyActive){
            $this->db->where('campagne_actif', 1);
        }
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertCampagne($data)
    {
        $entry = array(
            'campagne_libelle' => (isset($data['campagne_libelle'])) ? $data['campagne_libelle'] : '',
            'campagne_pole' => (isset($data['campagne_pole'])) ? $data['campagne_pole'] : '',
            'campagne_site' => (isset($data['campagne_site'])) ? $data['campagne_site'] : '',
            'campagne_proprio' => (isset($data['campagne_proprio'])) ? $data['campagne_proprio'] : '',
            'campagne_client' => (isset($data['campagne_client'])) ? $data['campagne_client'] : '',
            'campagne_ipserveur' => (isset($data['campagne_ipserveur'])) ? $data['campagne_ipserveur'] : '', 
            'campagne_datecrea' => date('Y-m-d H:i:s'),
            'campagne_datemodif' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }


    public function getCampagne($id){

        return $this->db->get_where('tr_campagne', array('campagne_id' => $id))->row();
    }

    public function getCampagneByLib($lib){

        return $this->db->get_where('tr_campagne', array('campagne_libelle' => $lib))->row();
    }


    public function updateCampagne($data){


        $entry = array(
          'campagne_libelle' => (isset($data['edit_campagne_libelle'])) ? $data['edit_campagne_libelle'] : '',
          'campagne_pole' => (isset($data['edit_campagne_pole'])) ? $data['edit_campagne_pole'] : '',
          'campagne_site' => (isset($data['edit_campagne_site'])) ? $data['edit_campagne_site'] : '',
          'campagne_proprio' => (isset($data['edit_campagne_proprio'])) ? $data['edit_campagne_proprio'] : '',
          'campagne_client' => (isset($data['campagne_client'])) ? $data['campagne_client'] : '',
          'campagne_ipserveur' => (isset($data['edit_campagne_ipserveur'])) ? $data['edit_campagne_ipserveur'] : '',
          'campagne_datecrea' => date('Y-m-d H:i:s'),
          'campagne_datemodif' => date('Y-m-d H:i:s')

        );

        $this->db->where('campagne_id', $data['edit_campagne_id'])
                  ->update('tr_campagne', $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }


    /**
     * Désactiver une campagne
     * Modifier campagne_actif à 0
     */
    public function desactivateCampagne($campagne_id){

        $this->db->where('campagne_id', $campagne_id)
                  ->update($this->_table, array('campagne_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }

    public function getUserCampagne($user_id, $format = 'object')
    {
        $this->db->select('campagne_id, campagne_libelle')
                ->join('tr_campagne','campagne_id = usercampagne_campagne', 'inner')
                ->where('usercampagne_user', $user_id);
                
        $query = $this->db->get('t_usercampagne');
        if($query->num_rows() > 0){

            if($format == 'array'){
    
                return $query->result_array();
            }
            return $query->result();
        }
        return false;
    }

    


    public function getAllAgentCampagne($campagne, $poids = false){
        $this->db->select('usr_id, usr_nom, usr_prenom, usr_matricule, usr_initiale')
                ->select('site_libelle')
                ->from('t_usercampagne')
                ->join('tr_campagne', 'campagne_id = usercampagne_campagne', 'inner')
                ->join('tr_user', 'usr_id = usercampagne_user', 'inner')
                ->join('tr_site', 'usr_site = site_id', 'inner')
                ->where('usercampagne_campagne', $campagne)
                ->where('usr_actif', '1');
        if($poids){
            $this->db->join('tr_role', 'role_id = usr_role', 'inner')
                        ->where('role_poids <=', $poids);
        }else{
            $this->db->where('usr_role', ROLE_AGENT);
        }
        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getListMissionCampagne($campagne_id)
    {
        $this->db->select('affectationmcp_mission, affectationmcp_campagne')
                ->from('t_affectationmcp')
                ->where('affectationmcp_campagne', $campagne_id)
                ->group_by('affectationmcp_mission')
        ;

        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    

    public function getAffectationCampagneMission($campagne_id, $mission_id)
    {
        $this->db->select('affectationmcp_id, affectationmcp_mission, affectationmcp_campagne, affectationmcp_process')
                ->from('t_affectationmcp')
                ->where('affectationmcp_campagne', $campagne_id)
                ->where('affectationmcp_mission', $mission_id)
        ;

        $query = $this->db->get();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function saveAffectationCampagne($data)
    {
        if($this->db->insert('t_affectationmcp', $data)) return $this->db->insert_id();
        return false;
    }

    public function removeAffectationCampagne($data)
    {
        $this->db->delete('t_affectationmcp', $data);
        
        if($this->db->affected_rows() > 0){
            return true;
        }
        return false;
    }


}

    