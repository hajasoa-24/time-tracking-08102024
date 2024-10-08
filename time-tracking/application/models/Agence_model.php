<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class agence_model extends CI_Model {

    private $_table = 'tr_agence';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function getAllAgence($onlyActive = false)
    {
        $this->db->select('agence_id, agence_libelle, agence_actif, agence_datecrea, agence_datemodif');
        if($onlyActive){
            $this->db->where('agence_actif', 1);
        }
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertAgence($data)
    {
        $entry = array(
            'agence_id' => $data['agence_id'], 
            'agence_libelle' => (isset($data['agence_libelle'])) ? $data['agence_libelle'] : '', 
            'agence_datecrea' => date('Y-m-d H:i:s'),
            'agence_datemodif' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }


    public function getAgence($id){

        return $this->db->get_where('tr_agence', array('agence_id' => $id))->row();
    }

    public function getAgenceByLib($lib){

        return $this->db->get_where('tr_agence', array('agence_libelle' => $lib))->row();
    }


    public function updateAgence($data){


        $entry = array(
          'agence_libelle' => (isset($data['edit_agence_libelle'])) ? $data['edit_agence_libelle'] : '',
          'agence_id' => $data['edit_agence_code'],
          'agence_datecrea' => date('Y-m-d H:i:s'),
          'agence_datemodif' => date('Y-m-d H:i:s')

        );

        $this->db->where('agence_id', $data['edit_agence_id'])
                  ->update('tr_agence', $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }


    /**
     * Désactiver une campagne
     * Modifier agence_actif à 0
     */
    public function desactivateAgence($agence_id){

        $this->db->where('agence_id', $agence_id)
                  ->update($this->_table, array('agence_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }

    public function getCalendarDatas($debut, $fin)
    {
        $this->db->select('agence_id, agence_libelle')
                    ->select('calendagence_date, etatagence_id, etatagence_libelle, coulagence_hexa')
                    ->join('t_calendrieragence', 'calendagence_agence = agence_id AND calendagence_date >= "' . $debut . '" AND calendagence_date <= "' . $fin . '"' , 'left')
                    ->join('tr_etatagence', 'etatagence_id = calendagence_etat', 'left')
                    ->join('tr_couleuragence', 'coulagence_id = etatagence_couleur', 'left')
                    ->where('agence_actif', 1)
                    ->order_by('agence_id ASC, calendagence_date ASC')
        ;

        $query = $this->db->get($this->_table);
       // echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getEtatAgence()
    {
        $this->db->select('etatagence_id, etatagence_libelle, coulagence_hexa')
                ->join('tr_couleuragence', 'etatagence_couleur = coulagence_id')
                ->order_by('etatagence_id ASC')
                ;
        $query = $this->db->get('tr_etatagence');
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getCalendrierAgenceByAgenceAndDate($agence, $date)
    {
        $this->db->select('*')
                    ->from('t_calendrieragence')
                    ->where('calendagence_agence', $agence)
                    ->where('calendagence_date', $date);
        
        $query = $this->db->get();

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function updateCalendarAgence($data)
    {

        $entry = array(
            'calendagence_etat' => $data['editcalendar_etat'],
            'calendagence_datemodif' => date('Y-m-d H:i:s')
  
        );

        $is_calendar = $this->getCalendrierAgenceByAgenceAndDate($data['editcalendar_agence'], $data['editcalendar_date']);
        if($is_calendar === false){

            $entry['calendagence_agence'] = $data['editcalendar_agence'];
            $entry['calendagence_date'] = $data['editcalendar_date'];
            $entry['calendagence_datecrea'] = date('Y-m-d H:i:s');
            
            if($this->db->insert('t_calendrieragence', $entry)) return $this->db->insert_id();

        }else{

            $this->db->where('calendagence_agence', $data['editcalendar_agence'])
                      ->where('calendagence_date', $data['editcalendar_date'])
                      ->update('t_calendrieragence', $entry);
    
            if($this->db->affected_rows() > 0) return true;
            
        }

        return false;
    }

    public function activateAgence($id){
        $this->db->where('agence_id', $id)
                  ->update($this->_table, array('agence_actif' => 1));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }
    public function desactivateAgencemodal($id){
        $this->db->where('agence_id', $id)
                  ->update($this->_table, array('agence_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }


  

}

    