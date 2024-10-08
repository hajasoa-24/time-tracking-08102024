<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motif_model extends CI_Model {

    private $_id,
            $_libelle,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_motif';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllMotif()
    {
        $this->db->select('motif_id, motif_libelle, motif_datecrea, motif_datemodif');
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

}

    