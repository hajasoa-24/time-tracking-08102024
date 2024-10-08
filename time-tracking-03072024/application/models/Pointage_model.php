<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pointage_model extends CI_Model {

    private $_table = 't_pointage';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }


    public function insertPointage($data)
    {
        
        if(is_array($data) && !empty($data)){
            
            if($this->db->insert($this->_table, $data)) return $this->db->insert_id();
        
        }
        return false;
    }

    public function updatePointage($id, $data)
    {
        
        if(is_array($data) && !empty($data)){
            $this->db->where('pointage_id', $id);
            $this->db->update($this->_table, $data);
            if($this->db->affected_rows() > 0) return true;
            return false;
        }
        return false;
    }

    /**
    * Prendre la ligne de pointage d'un user donné  
    */

    public function getUserPointageByDate($user, $date, $site)
    {

        $query = $this->db->select('pointage_id, pointage_date, pointage_user, pointage_in, pointage_break, pointage_resume, pointage_out, pointage_ot, pointage_done')
            ->where('pointage_user', $user)
            ->where('pointage_date', $date)
            ->where('pointage_site', $site)
            ->get($this->_table);
        
        if($query->num_rows() > 0){
            return $query->row();
        }

        return false;
        
    }


    /*
    * Supprimer les données dans t_ip et ne garder que les 8 derniers jours 
    */
    public function deleteIp()
    {
        $this->db->where('ip_datelog < curdate() - interval 8 day');
        $this->db->delete($this->_table);
    }


}

    