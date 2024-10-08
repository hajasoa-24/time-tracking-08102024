<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tache_model extends CI_Model 
{

    private $_table = 'tr_tache';

         
    

    //PUBLIC FUNCTIONS

	public function __construct()  
    {
        parent::__construct();
    }

    public function getTask($id=false)
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

    public function getTasksuivis($id=false)
    {
        $this->db->select('tache_date,tache_status,tache_commentaire, tache_id, tache_usr_validation')
                  ->select('checking_libelle')
                  ->select('usr_prenom')
                  ->join('t_checking', 'tache_check = checking_id','inner')
                  ->join('tr_user','usr_id = tache_usr_id','inner');
                 

        if(is_array($id))
        {
            $this->db->where_in('tache_usr_suivi',$id)
                     ->where("tache_status = 2")
                     ->order_by('tache_frequence', 'asc');;
        }
        else if($id){
                $this->db->where("(tache_usr_suivi = $id OR tache_suiviASR = 1) AND (tache_status = 2 OR tache_status = 3 )")
                         ->order_by('tache_frequence', 'asc');
                    
            }
            $query = $this->db->get($this->_table);
            if($query->num_rows() > 0)
            {
                return $query->result();
            }
            return false;
    }



    public function validation ($data,$id)
    {

        $this->db->where('tache_id',$id);
        $query = $this->db->get('tr_tache');

        $taches = $query->result();

        foreach($taches as $tache)
        {

            if ($tache->tache_frequence == '2')
            {
                $date = new DateTime($tache->tache_date);
                $date->add(new DateInterval('P7D'));

                $newDate = $date->format('Y-m-d');

                $taskData = array(
                    'tache_usr_id' => $tache->tache_usr_id,
                    'tache_check' => $tache->tache_check,
                    'tache_date' => $newDate,
                    'tache_usr_suivi' => $tache->tache_usr_suivi,
                    'tache_status' => 1,
                    'tache_frequence'=>$tache->tache_frequence
                );
                $this->db->insert($this->_table, $taskData);

            }
            else if($tache->tache_frequence == '3')
            {
                $date = new DateTime($tache->tache_date);
                $date->modify('+1 month');

                $newDate = $date->format('Y-m-d');

                $taskData = array(
                    'tache_usr_id' => $tache->tache_usr_id,
                    'tache_check' => $tache->tache_check,
                    'tache_date' => $newDate,
                    'tache_usr_suivi' => $tache->tache_usr_suivi,
                    'tache_status' => 1,
                    'tache_frequence'=>$tache->tache_frequence
                );
                $this->db->insert($this->_table, $taskData);

            }
            else if($tache->tache_frequence == '4')
            {
                $date = new DateTime($tache->tache_date);
                $date->modify('+3 month');

                $newDate = $date->format('Y-m-d');

                $taskData = array(
                    'tache_usr_id' => $tache->tache_usr_id,
                    'tache_check' => $tache->tache_check,
                    'tache_date' => $newDate,
                    'tache_usr_suivi' => $tache->tache_usr_suivi,
                    'tache_status' => 1,
                    'tache_frequence'=>$tache->tache_frequence
                );
                $this->db->insert($this->_table, $taskData);

            }
        }


        $entry = array(
            'tache_commentaire' => $data['tache_commentaire'],
            'tache_status' =>  $data['tache_status'],
        );
        $this->db->where('tache_id', $id);
        $this->db->update('tr_tache', $entry);
    
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
      
            return false;
        

    }
    public function suivicadre ($filter){
        $start = $filter['debut'];
        $end = $filter['fin'];
        $this->db->select('tache_date,tache_id,tache_commentaire,tache_status,tache_usr_validation,tache_frequence')
        ->select('checking_libelle')
        ->select('usr_prenom')
        ->join('t_checking', 'tache_check = checking_id','inner')
        ->join('tr_user','usr_id = tache_usr_id','inner')
        ->where("tache_date BETWEEN '$start'AND '$end'"); // 'desc' pour tri décroissant
        
        // $this->db->where("tache_status = 2 OR tache_status = 1");
        
        $query = $this->db->get($this->_table);
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        return false;
        
    }

    public function getAllUsers()
	{
		$query = $this->db->get('tr_user');
		return $query->result();
	}

    public function getAllTasks()
	{
		$query = $this->db->get('t_checking');
		return $query->result();
	}

	public function assignTasksToUsers()
	{
        $query = $this->db->get('t_attributiontache');
		$attribution = $query->result();

        $query = $this->db->get('t_attributiontache');
		$attribution = $query->result();


        foreach ($attribution as  $tache)
        {

            $usertask = $tache->attributiontache_user;
            
            $users_task = explode(",", $usertask);


            for ($i=0; $i<count($users_task) ; $i++)
            {
               
                if ($tache->attributiontache_frequence == 6)
                {
                    $request = $this->db->get('tr_holidays');

                    $holidays = $request->result();

                    foreach ($holidays as $holiday)
                    {
                        $date = new DateTime($holiday->holidays_date);
                        $date->add(new DateInterval('P1D'));
                        $newDate = $date->format('Y-m-d');
                        $taskData = array(
                            'tache_usr_id' => $users_task[$i],
                            'tache_check' => $tache->attributiontache_checking_id,
                            'tache_date' => $newDate,
                            'tache_usr_suivi' => $tache->attributiontache_suivi,
                            'tache_status' => 1,
                            'tache_frequence' => $tache->attributiontache_frequence,
    
                        );
                        $this->db->insert($this->_table, $taskData);

                    }
                  
                }
                else
                {
                    $taskData = array(
                        'tache_usr_id' => $users_task[$i],
                        'tache_check' => $tache->attributiontache_checking_id,
                        'tache_date' => date('Y-m-d'),
                        'tache_usr_suivi' => $tache->attributiontache_suivi,
                        'tache_status' => 1,
                        'tache_frequence' => $tache->attributiontache_frequence,

                    );
                    $this->db->insert($this->_table, $taskData);

                }

            }

               
        }
    }

    public function validationtache($data,$id)
    {
        $entry = array(
            'tache_usr_validation' => $data['tache_usr_validation'],
            'tache_status' =>  $data['tache_status'],
        );
        $this->db->where('tache_id', $id);
        $this->db->update('tr_tache', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;

    }
    public function encourstache($data,$id)
    {
        $entry = array(
            'tache_usr_validation' => $data['tache_usr_validation'],
            'tache_status' =>  $data['tache_status'],
        );
        $this->db->where('tache_id', $id);
        $this->db->update('tr_tache', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;

    }

    public function assignDailyTask()
    {
        $this->db->select("*")->where("attributiontache_frequence = 1");
        $query = $this->db->get('t_attributiontache');

		$attribution = $query->result();


        foreach ($attribution as $tache)
        {
            $usertask = $tache->attributiontache_user;
            $users_task = explode(",", $usertask);
            for ($i=0; $i<count($users_task) ; $i++)
            {
                
                    $taskData = array
                    (
                        'tache_usr_id' => $users_task[$i],
                        'tache_check' => $tache->attributiontache_checking_id,
                        'tache_date' => date('Y-m-d'),
                        'tache_usr_suivi' => $tache->attributiontache_suivi,
                        'tache_status' => 1,
                        'tache_frequence' => $tache->attributiontache_frequence,
                    );
                    $this->db->insert($this->_table, $taskData);
                
            }
        }

    }

    

        




		
    

}  