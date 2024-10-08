<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Typepause_model extends CI_Model {

    private $_id,
            $_libelle,            
            $_datecrea,
            $_datemodif,
            $_table = 'tr_typepause';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllTypePause()
    {
        $this->db->select('typepause_id, typepause_libelle, typepause_datecrea, typepause_datemodif');
        
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }


}

    