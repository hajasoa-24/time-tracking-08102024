<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Histopermission_model extends CI_Model {

    private $_table = 't_histopermission';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function insertHistopermission($data){
        $entry = array(
            'histopermission_conge' => $data['histopermission_conge'],
            'histopermission_user' => $data['histopermission_user'],
            'histopermission_duree' => $data['histopermission_duree'],
            'histopermission_datecrea' => date('Y-m-d H:i:s'),
            'histopermission_datemodif' => date('Y-m-d H:i:s')
        );
        $this->db->insert($this->_table, $entry);
        if($this->db->affected_rows() > 0 ) return true;

        return false;
    }

    public function getTotalPermissionUser($user)
    {
        $this->db->select('sum(histopermission_duree) as total')
                    ->where('histopermission_user', $user);

        $query = $this->db->get($this->_table);
        
        if($query->num_rows() > 0){
            return ($query->row()->total) ? $query->row()->total : 0;
        }
        return 0;
    }

}

    