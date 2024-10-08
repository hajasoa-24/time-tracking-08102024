<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends CI_Model {

    private $_id,
            $_libelle,
            $_pole,
            $_site,
            $_ipserveur,
            $_actif,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_service';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllService($onlyActive = false)
    {
        $this->db->select('service_id, service_libelle, service_site, service_actif, service_datecrea, service_datemodif')
                    ->select('site_libelle')    
                    ->join('tr_site', 'service_site = site_id', 'inner');
        if($onlyActive){
            $this->db->where('service_actif', 1);
        }
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getServiceByLib($lib){

        return $this->db->get_where('tr_service', array('service_libelle' => $lib))->row();
    }

    /*
    * insert un nouveau service
    */

    public function insertService($data){

        $entry = array(
          'service_libelle' => (isset($data['service_libelle'])) ? $data['service_libelle'] : '',
          'service_site' => (isset($data['service_site'])) ? $data['service_site'] : '',
          'service_datecrea' => date('Y-m-d H:i:s'),
          'service_datemodif' => date('Y-m-d H:i:s')
        );


        $this->db->insert($this->_table, $entry);
        $insert_id = $this->db->insert_id();

        return $insert_id;

    }


    public function getService($id){

         return $this->db->get_where($this->_table, array('service_id' => $id))->row();
      }


    public function updateService($data){

        $entry = array(
          'service_libelle' => (isset($data['edit_service_libelle'])) ? $data['edit_service_libelle'] : '',
          'service_site' => (isset($data['edit_service_site'])) ? $data['edit_service_site'] : '',
          'service_datecrea' => date('Y-m-d H:i:s'),
          'service_datemodif' => date('Y-m-d H:i:s')
        );

        $this->db->where('service_id', $data['edit_service_id'])
                  ->update('tr_service', $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }



    /**
     * Désactiver un service
     * Modifier service_actif à 0
     */
    public function desactivateService($service_id){

        $this->db->where('service_id', $service_id)
                  ->update($this->_table, array('service_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }

    public function getUserService($user_id, $format = 'object')
    {
        $this->db->select('service_id, service_libelle')
                ->join('tr_service','service_id = userservice_service', 'inner')
                ->where('userservice_user', $user_id);
        $query = $this->db->get('t_userservice');

        if($query->num_rows() > 0){
            if($format == 'array'){
                return $query->result_array();
            }
            return $query->result();
        }
        return false;
    }

   /* public function getAllAgentService($service){
        $this->db->select('usr_id, usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_role')
                ->from('t_userservice')
                ->join('tr_service', 'service_id = userservice_service', 'inner')
                ->join('tr_user', 'usr_id = userservice_user', 'inner')
                ->join('tr_role', 'role_id = usr_role', 'inner')
                ->where('userservice_service', $service)
                ->where('usr_role', ROLE_AGENT)
                ->where('usr_actif', '1');
        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }*/


    public function getAllAgentService($service, $poids = false){
        $this->db->select('usr_id, usr_nom, usr_prenom, usr_matricule, usr_initiale')
                ->from('t_userservice')
                ->join('tr_service', 'service_id = userservice_service', 'inner')
                ->join('tr_user', 'usr_id = userservice_user', 'inner')
                ->where('userservice_service', $service)
                ->where('usr_actif', '1');
        if($poids){
            $this->db->join('tr_role', 'role_id = usr_role', 'inner')
                        ->where('role_poids <=', $poids);
        }else{
            $this->db->where('usr_role', ROLE_AGENT);
        }
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }



}

    