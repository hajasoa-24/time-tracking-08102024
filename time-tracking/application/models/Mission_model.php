<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mission_model extends CI_Model {

    private $_id,
            $_libelle,
            $_actif,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_mission',
            $_affectationmcptable = 't_affectationmcp'
            ;
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllMission($onlyActive = false)
    {
        $this->db->select('mission_id, mission_libelle, mission_actif, mission_datecrea, mission_datemodif');
        if($onlyActive){
            $this->db->where('mission_actif', 1);
        }
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertMission($data)
    {
        $entry = array(
            'mission_libelle' => (isset($data['mission_libelle'])) ? $data['mission_libelle'] : '',
            'mission_actif' => (isset($data['mission_actif'])) ? $data['mission_pole'] : 1,
            'mission_datecrea' => date('Y-m-d H:i:s'),
            'mission_datemodif' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }


    public function getMission($id){

        return $this->db->get_where('tr_mission', array('mission_id' => $id))->row();
    }

    public function getMissionByLib($lib){

        return $this->db->get_where('tr_mission', array('mission_libelle' => $lib))->row();
    }


    public function updateMission($data){


        $entry = array(
          'mission_libelle' => (isset($data['edit_mission_libelle'])) ? $data['edit_mission_libelle'] : '',
          'mission_datecrea' => date('Y-m-d H:i:s'),
          'mission_datemodif' => date('Y-m-d H:i:s')

        );

        $this->db->where('mission_id', $data['edit_mission_id'])
                  ->update('tr_mission', $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }

    /**
     * Désactiver une campagne
     * Modifier mission_actif à 0
     */
    public function desactivateMission($mission_id){

        $this->db->where('mission_id', $mission_id)
                  ->update($this->_table, array('mission_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }

    public function getListMissionForCampagne($campagne)
    {
        $this->db->select('mission_id, mission_libelle')
            ->join('tr_mission', 'mission_id = affectationmcp_mission', 'inner')
            ->where('affectationmcp_campagne', $campagne)
            ->group_by('mission_id')
            ;
        $query = $this->db->get($this->_affectationmcptable);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getListMissionForCampagneAndProfil($campagne, $profil)
    {

        $this->db->select('mission_id, mission_libelle')
            ->join($this->_affectationmcptable, 'primeprofilprocess_process = affectationmcp_id', 'inner')
            ->join('tr_mission', 'affectationmcp_mission = mission_id', 'inner')
            ->where('primeprofilprocess_profil', $profil)
            ->where('primeprofilprocess_campagne', $campagne)
            ->group_by('mission_id')
            ;
        $query = $this->db->get('t_primeprofilprocess');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

}

    