<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Livrepaie_model extends CI_Model {

    private $_table = 't_livrepaie';

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function getAllLivrepaie()
    {
        $this->db->select('livrepaie_id, livrepaie_month, livrepaie_year, livrepaie_datecrea, livrepaie_datemodif');
        $query = $this->db->get($this->_table);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertLivrepaie($data)
    {
        $entry = array(
            'livrepaie_month' => (isset($data['mois'])) ? $data['mois'] : '',
            'livrepaie_year' => (isset($data['annee'])) ? $data['annee'] : '',
            'livrepaie_valeur' => (isset($data['valeur'])) ? $data['valeur'] : '',
            'livrepaie_datecrea' => date('Y-m-d H:i:s'),
            'livrepaie_datemodif' => date('Y-m-d H:i:s')
        );

        if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }


    public function getLivrepaie($id){

        return $this->db->get_where($this->_table, array('livrepaie_id' => $id))->row();
    }
    

    public function getLivrepaieByYear($annee){

        return $this->db->get_where($this->_table, array('livrepaie_year' => $annee))->result();
    }

    public function getLivrepaieByMonthYear($mois, $annee){
        
        $query = $this->db->get_where($this->_table, array('livrepaie_month' => $mois , 'livrepaie_year' => $annee));
        if($query->num_rows() > 0){
            return $query->row();
        }
        return false; 
    }


    public function updateLivrepaie($data){

        if(!isset($data['mois']) && !isset($data['annee'])) return false;

        $entry = array(
            'livrepaie_month' => $data['mois'],
            'livrepaie_year' => $data['annee'],
            'livrepaie_valeur' => (isset($data['valeur'])) ? $data['valeur'] : '',
            'livrepaie_datemodif' => date('Y-m-d H:i:s')
        );

        $this->db->where('livrepaie_id', $data['id'])
                  ->update($this->_table, $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;

    }

}

    