<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pole_model extends CI_Model {

    private $_id,
            $_libelle,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_pole';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function getAllPole()
    {
        $this->db->select('pole_id, pole_libelle, pole_datecrea, pole_datemodif');
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        
        return false;
    }

}
