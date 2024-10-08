<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model {

    private $_id,
            $_libelle,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_role';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function getAllRole()
    {
        $this->db->select('role_id, role_libelle, role_datecrea, role_datemodif');
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

}
