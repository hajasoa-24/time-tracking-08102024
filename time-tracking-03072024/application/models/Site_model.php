<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Site_model extends CI_Model {

    private $_id,
            $_libelle,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_site';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllSite()
    {
        $this->db->select('site_id, site_libelle, site_datecrea, site_datemodif');
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

}

    