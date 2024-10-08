<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proprio_model extends CI_Model {

    private $_id,
            $_libelle,
            $_actif,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_proprio';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllProprio($onlyActive = false)
    {
        $this->db->select('proprio_id, proprio_libelle, proprio_actif, proprio_datecrea, proprio_datemodif');
        if($onlyActive){
            $this->db->where('proprio_actif', 1);
        }
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertProprio($data)
    {
        $entry = array(
            'proprio_libelle' => (isset($data['proprio_libelle'])) ? $data['proprio_libelle'] : '',
            'proprio_actif' => (isset($data['proprio_actif'])) ? $data['proprio_pole'] : 1,
            'proprio_datecrea' => date('Y-m-d H:i:s'),
            'proprio_datemodif' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }


    public function getProprio($id){

        return $this->db->get_where('tr_proprio', array('proprio_id' => $id))->row();
    }

    public function getProprioByLib($lib){

        return $this->db->get_where('tr_proprio', array('proprio_libelle' => $lib))->row();
    }


    public function updateProprio($data){


        $entry = array(
          'proprio_libelle' => (isset($data['edit_proprio_libelle'])) ? $data['edit_proprio_libelle'] : '',
          'proprio_datecrea' => date('Y-m-d H:i:s'),
          'proprio_datemodif' => date('Y-m-d H:i:s')

        );

        $this->db->where('proprio_id', $data['edit_proprio_id'])
                  ->update('tr_proprio', $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }

    /**
     * Désactiver une campagne
     * Modifier proprio_actif à 0
     */
    public function desactivateProprio($proprio_id){

        $this->db->where('proprio_id', $proprio_id)
                  ->update($this->_table, array('proprio_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }

}

    