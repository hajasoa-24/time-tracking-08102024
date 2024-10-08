<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Process_model extends CI_Model {

    private $_id,
            $_libelle,
            $_actif,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_process',
            $_affectationmcptable = 't_affectationmcp'
            ;
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllProcess($onlyActive = false)
    {
        $this->db->select('process_id, process_libelle, process_actif, process_datecrea, process_datemodif');
        if($onlyActive){
            $this->db->where('process_actif', 1)
            ->order_by('process_libelle ASC');

        }
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertProcess($data)
    {
        $entry = array(
            'process_libelle' => (isset($data['process_libelle'])) ? $data['process_libelle'] : '',
            'process_actif' => (isset($data['process_actif'])) ? $data['process_pole'] : 1,
            'process_datecrea' => date('Y-m-d H:i:s'),
            'process_datemodif' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }


    public function getProcess($id){

        return $this->db->get_where('tr_process', array('process_id' => $id))->row();
    }

    public function getProcessByLib($lib){

        return $this->db->get_where('tr_process', array('process_libelle' => $lib))->row();
    }


    public function updateProcess($data){


        $entry = array(
          'process_libelle' => (isset($data['edit_process_libelle'])) ? $data['edit_process_libelle'] : '',
          'process_datecrea' => date('Y-m-d H:i:s'),
          'process_datemodif' => date('Y-m-d H:i:s')

        );

        $this->db->where('process_id', $data['edit_process_id'])
                  ->update('tr_process', $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }

    /**
     * Désactiver une campagne
     * Modifier process_actif à 0
     */
    public function desactivateProcess($process_id){

        $this->db->where('process_id', $process_id)
                  ->update($this->_table, array('process_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }

    public function getListProcessCampagneMission($campagne, $mission)
    {
        $this->db->select('process_id, process_libelle, mission_libelle, campagne_libelle')
            ->join('tr_process', 'process_id = affectationmcp_process', 'inner')
            ->join('tr_mission', 'mission_id = affectationmcp_mission', 'inner')
            ->join('tr_campagne', 'campagne_id = affectationmcp_campagne', 'inner')
            ->where('affectationmcp_campagne', $campagne)
            ->where('affectationmcp_mission', $mission)
            ;
        $query = $this->db->get($this->_affectationmcptable);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getListProcessCampagne($campagne)
    {
        $this->db->select('affectationmcp_id, process_id, process_libelle, mission_libelle, campagne_libelle')
            ->join('tr_process', 'process_id = affectationmcp_process', 'inner')
            ->join('tr_mission', 'mission_id = affectationmcp_mission', 'inner')
            ->join('tr_campagne', 'campagne_id = affectationmcp_campagne', 'inner')
            ->where('affectationmcp_campagne', $campagne)
            ;
        $query = $this->db->get($this->_affectationmcptable);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }


    public function getListAffectedProcessCampagne($profil, $campagne, $onlyActive = true)
    {
        $this->db->select('primeprofilprocess_profil, primeprofilprocess_process, process_libelle')
            ->join('tr_primeprofil', 'primeprofil_id = primeprofilprocess_profil', 'inner')
            ->join('t_affectationmcp', 'affectationmcp_id = primeprofilprocess_process', 'inner')
            ->join('tr_process', 'process_id = affectationmcp_process', 'inner')
            ->where('process_actif', $onlyActive)
            ->where('primeprofilprocess_profil', $profil)
            ->where('primeprofilprocess_campagne', $campagne)
            ;
        $query = $this->db->get('t_primeprofilprocess');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }


}

    