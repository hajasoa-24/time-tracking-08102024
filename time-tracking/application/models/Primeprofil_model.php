<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Primeprofil_model extends CI_Model {

    private $_id,
            $_libelle,
            $_actif,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_primeprofil'
            ;
    
    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }


    public function getAllProfil($onlyActive = false)
    {
        $this->db->select('primeprofil_id, primeprofil_libelle, primeprofil_campagne, primeprofil_actif, primeprofil_datecrea, primeprofil_datemodif')
                    ->select('campagne_libelle')
                    ->select("(SELECT GROUP_CONCAT(process_libelle) FROM t_primeprofilprocess INNER JOIN t_affectationmcp ON affectationmcp_id = primeprofilprocess_process INNER JOIN tr_process ON process_id = affectationmcp_process WHERE primeprofilprocess_profil = primeprofil_id ) AS list_process", FALSE)
                    ->select("(SELECT GROUP_CONCAT(usr_prenom) FROM t_primeaffectationupc INNER JOIN tr_user ON usr_id = primeaffectationupc_user  WHERE primeaffectationupc_profil = primeprofil_id ) AS list_agent", FALSE)
                    ->join('tr_campagne', 'campagne_id = primeprofil_campagne', 'inner');

        if($onlyActive){
            $this->db->where('primeprofil_actif', 1);
        }
        $query = $this->db->get($this->_table);
        
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertProfil($data)
    {
        if(!isset($data['primeprofil_libelle'])) return false;

        $entry = array(
            'primeprofil_id' => $data['primeprofil_id'],
            'primeprofil_libelle' => $data['primeprofil_libelle'],
            'primeprofil_campagne' => $data['primeprofil_campagne'],
            'primeprofil_actif' => (isset($data['primeprofil_actif'])) ? $data['primeprofil_actif'] : 1,
            'primeprofil_datecrea' => date('Y-m-d H:i:s'),
            'primeprofil_datemodif' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->_table, $entry)) return $data['primeprofil_id'];

        return false;
    }

    public function getLastPrimeProfilIdByCampagne(int $campagne) : int
    {
        $this->db->select('primeprofil_id')
            ->where('primeprofil_campagne', $campagne)
            ->order_by('primeprofil_id DESC')
            ->limit(1)
            ;
        $query = $this->db->get('tr_primeprofil');
        if($query->num_rows() > 0) return $query->row()->primeprofil_id;
        else return 0;
    }


    public function getProfil($id){
        $this->db->select('primeprofil_id, primeprofil_libelle, primeprofil_campagne, primeprofil_actif, primeprofil_datecrea, primeprofil_datemodif')
                ->select('campagne_libelle')
                ->join('tr_campagne', 'campagne_id = primeprofil_campagne', 'inner');

        return $this->db->get_where('tr_primeprofil', array('primeprofil_id' => $id))->row();
    }

    public function getProfilByLib($lib){

        return $this->db->get_where('tr_primeprofil', array('primeprofil_libelle' => $lib))->row();
    }


    public function updateProfil($data){


        $entry = array(
          'primeprofil_libelle' => (isset($data['edit_primeprofil_libelle'])) ? $data['edit_primeprofil_libelle'] : '',
          'primeprofil_libelle' => (isset($data['edit_primeprofil_campagne'])) ? $data['edit_primeprofil_campagne'] : '',
          'primeprofil_datecrea' => date('Y-m-d H:i:s'),
          'primeprofil_datemodif' => date('Y-m-d H:i:s')

        );

        $this->db->where('primeprofil_id', $data['edit_primeprofil_id'])
                  ->update('tr_primeprofil', $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }

    public function getAffectationUPC($profil, $activeOnly = true)
    {
        $this->db->select('primeaffectationupc_id, primeaffectationupc_user, primeaffectationupc_profil, primeaffectationupc_campagne')
                ->select('primeprofil_libelle')
                ->join('tr_primeprofil', 'primeaffectationupc_profil = primeprofil_id', 'inner')
                ->join('tr_user', 'primeaffectationupc_user = usr_id', 'inner')
                ->where('primeprofil_actif', $activeOnly)
                ->where('primeaffectationupc_profil', $profil)
                ;
        $query = $this->db->get('t_primeaffectationupc');

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function setAffectationUPC($datas, $profil, $campagne)
    {
        $this->removeAffectationUPC($profil, $campagne);
        return $this->db->insert_batch('t_primeaffectationupc', $datas);
    }

    public function setProfilProcess($datas, $profil, $campagne)
    {

        $this->removeProfilProcess($profil,$campagne);
        return $this->db->insert_batch('t_primeprofilprocess', $datas);
    }

    public function removeAffectationUPC($profil, $campagne)
    {
        $this->db->where('primeaffectationupc_profil', $profil)
                    ->where('primeaffectationupc_campagne', $campagne);
        return $this->db->delete('t_primeaffectationupc');
    }

    public function removeProfilProcess($profil,$campagne)
    {
        $this->db->where('primeprofilprocess_profil', $profil);
        $this->db->where('primeprofilprocess_campagne', $campagne);
        return $this->db->delete('t_primeprofilprocess');
    }

    /**
     * 
     * Modifier primeprofil_actif à 0
     */
    public function desactivateProfil($primeprofil_id){

        $this->db->where('primeprofil_id', $primeprofil_id)
                  ->update($this->_table, array('primeprofil_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }

   /* public function getListProcessCampagneMission($campagne, $mission)
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
    }*/

}

    