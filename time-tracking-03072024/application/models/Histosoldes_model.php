<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Histosoldes_model extends CI_Model {

    private $_table = 't_histosoldes';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    

    public function insertHistoSoldes($data)
    {

        if($this->db->insert($this->_table, $data)) return $this->db->insert_id();

        return false;
    }


    public function getAllHistory()
    {
        $this->db->select('histosoldes_id, histosoldes_datemodif, histosoldes_anciensolde, histosoldes_nouveausolde, histosoldes_anciendroitpermission, histosoldes_nouveaudroitpermission, histosoldes_modificateur, histosoldes_commentaire, histosoldes_ipmodificateur')
                ->select('utl.usr_prenom as agent_concerne , mdf.usr_prenom as modificateur')    
                ->join('tr_user as utl', 'utl.usr_id = histosoldes_user', 'inner')
                ->join('tr_user as mdf', 'mdf.usr_id = histosoldes_modificateur', 'inner');
                    
        $query = $this->db->get($this->_table);
//echo $this->db->last_query();
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

}

    