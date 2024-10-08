<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ip_model extends CI_Model {

    private $_id,
            $_adresse,
            $_user,
            $_dateLog,
            $_datecrea,
            $_datemodif,
            $_table = 't_ip';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }


    /*
    * Insérer l'adresse ip de sa première connexion de la journée dans t_ip 
    */
     public function insertIp($data)
    {
        //var_dump($this->getUserIPByDate( $data['user'], $data['dateLog'])); die;
        if(is_array($data) && !empty($data) && !$this->getUserIPByDate( $data['user'], $data['dateLog'])){

            $entry = array(
                'ip_adresse' => $data['ip'],
                'ip_user' => $data['user'],
                'ip_datelog' => $data['dateLog'],
                'ip_datecrea' => date('Y-m-d H:i:s'),
                'ip_datemodif' => date('Y-m-d H:i:s')
              );
            
            if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();
        
        }
        return false;
    }

    /*
    * prendre la liste des ip
    */
     public function getAllIp()
    {
        $this->db->select('ip_id, ip_adresse, ip_user, ip_datelog, usr_id,usr_nom, usr_prenom, usr_username, usr_site')
                    ->select('site_libelle')
                    ->select('(SELECT GROUP_CONCAT(campagne_libelle)  FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS campagnes')
                    ->select('(SELECT GROUP_CONCAT(service_libelle)  FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS services')
                    ->join('tr_user', 'usr_id = ip_user', 'inner')
                    ->join('tr_site', 'usr_site = site_id', 'inner');
                    
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    /*
    * Prendre les ip d'utilisateur par date de log 
    */

    public function getUserIPByDate($user, $dateLog)
    {

        $query = $this->db->select('ip_id, ip_adresse, ip_user, ip_datelog')
            ->where('ip_user', $user)
            ->where('ip_datelog', $dateLog)
            ->get($this->_table);
        
        if($query->num_rows() > 0){
            //echo $this->db->last_query(); die;
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

    