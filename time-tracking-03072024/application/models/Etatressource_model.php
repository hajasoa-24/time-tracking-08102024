<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etatressource_model extends CI_Model {

    private $_id,
            $_libelle,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_etatressource';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllEtatRessource()
    {
        $this->db->select('etatressource_id, etatressource_libelle, etatressource_facturation, etatressource_datecrea, etatressource_datemodif');
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

}

   