<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Histoetatconge_model extends CI_Model {

    private $_table = 't_histoetatconge';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function insertHistorique($data){
        $entry = array(
            'histoetatconge_conge' => $data['histoetatconge_conge'],
            'histoetatconge_etat' => $data['histoetatconge_etat'],
            'histoetatconge_date' => $data['histoetatconge_date'],
            'histoetatconge_motifrefus' => (isset($data['histoetatconge_motifrefus'])) ? $data['histoetatconge_motifrefus'] : '',
            'histoetatconge_validateur' => (isset($data['histoetatconge_validateur'])) ? $data['histoetatconge_validateur'] : NULL
        );
        $this->db->insert($this->_table, $entry);
        if($this->db->affected_rows() > 0 ) return true;

        return false;
    }

    public function getAllHistoEtat($conge = false)
    {
        $this->db->select('histoetatconge_conge, histoetatconge_etat, histoetatconge_date, histoetatconge_motifrefus, histoetatconge_validateur')
            ->select('etatconge_libelle, utl.usr_prenom, conge_user, conge_id, utl.usr_id, conge_id')
            ->select('vld.usr_prenom AS validateur')
            ->join('t_conge', 'conge_id = histoetatconge_conge', 'inner')
            ->join('tr_etatconge', 'etatconge_id = histoetatconge_etat', 'inner')
            ->join('tr_user as utl', 'utl.usr_id = conge_user', 'inner')
            ->join('tr_user as vld', 'vld.usr_id = histoetatconge_validateur', 'left')
            ->order_by('histoetatconge_date ASC');

        if(is_array($conge)){
            $this->db->where_in('histoetatconge_conge', $conge); 
        }else if($conge){
            $this->db->where('histoetatconge_conge', $conge);
        }

        $query = $this->db->get($this->_table);
        //echo $this->db->last_query(); die;

        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }


}

    