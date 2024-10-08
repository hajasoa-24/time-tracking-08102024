<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JourFerie_model extends CI_Model 
{

    //PUBLIC FUNCTIONS

	public function __construct()  
    {
        parent::__construct();
    }

    public function getJours($id=false)
    {
        $this->db->select('tache_date,tache_status,tache_id,tache_encour')
                  ->select('checking_libelle')
                  ->join('t_checking', 'tache_check = checking_id','inner')
                  ->join('tr_user','usr_id = tache_usr_id','inner');

        if(is_array($id))
        {
            $this->db->where_in('tache_usr_id',$id)
                     ->where("tache_status = 1 OR tache_status = 1.5")
                     ->order_by('tache_frequence', 'asc');
                     
        }
        else if($id){
                $this->db->where("(tache_usr_id = $id) AND (tache_status = 1 OR tache_status = 1.5)")
                        ->order_by('tache_frequence', 'asc');
                        
            }
            $query = $this->db->get($this->_table);
            if($query->num_rows() > 0)
            {
                return $query->result();
            }
            return false;
    }


    public function getholidays()
	{
        $currentDate = new DateTime();
        $year = $currentDate->format("Y"); 

		$query = $this->db->order_by('holidays_date','asc')->where('YEAR(holidays_date)', $year)->get('tr_holidays');
		return $query->result();
	}

    public function getAllTasks()
	{
		$query = $this->db->get('t_checking');
		return $query->result();
	}
    public function addFerie($taskData)
	{
        $this->db->insert('tr_holidays', $taskData);
        return true;

    
    }
    public function deleteFerie($id)
    {
        $this->db
        ->where('holidays_id', $id)
        ->delete('tr_holidays');
        return true;

    }
}  