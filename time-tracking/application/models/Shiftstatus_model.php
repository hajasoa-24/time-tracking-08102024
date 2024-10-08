<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shiftstatus_model extends CI_Model {

    private $_id,
            $_libelle,            
            $_datecrea,
            $_datemodif,
            $_table = 'tr_shiftstatus';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllStatus()
    {
        $this->db->select('ss_id, ss_libelle, ss_datecrea, ss_datemodif');
        
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getStatusById($id){

        return $this->db->get_where($this->_table, array('ss_id' => $id))->row();
    }


}

    