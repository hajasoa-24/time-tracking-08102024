<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motifpresence_model extends CI_Model {

    private $_id,
            $_day,
            $_shift,
            $_motif,
            $_modifcateur,
            $_datecrea,
            $_datemodif,
            $_table = 't_motifpresence';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    /*
    * insert un nouveau service
    */

    public function insertMotif($data){
        $day = date('Y-m-d');
        if(isset($data['motif_day']) && $data['motif_day'] != ""){
            $day = date_create($data['motif_day']);
            $day = date_format($day, 'Y-m-d');
        }
        $entry = array(
            'motifpresence_agent' => (isset($data['motif_agent']) && $data['motif_agent'] != "") ? $data['motif_agent'] : null,
            'motifpresence_shift' => (isset($data['motif_shift']) && $data['motif_shift'] != "") ? $data['motif_shift'] : null,
            'motifpresence_motif' => (isset($data['motif_libelle'])) ? $data['motif_libelle'] : '',
            'motifpresence_modificateur' => (isset($data['modif_modificateur']) && $data['modif_modificateur'] != "") ? $data['modif_modificateur'] : null,
            'motifpresence_day' => $day,
            'motifpresence_incomplet' => (isset($data['motif_incomplet'])) ? true : false,
            'motifpresence_datecrea' => date('Y-m-d H:i:s'),
            'motifpresence_datemodif' => date('Y-m-d H:i:s')
        );

        //Delete and Insert
        $this->db->where(['motifpresence_day' => $entry['motifpresence_day'], 'motifpresence_agent' => $entry['motifpresence_agent']])
                    ->delete($this->_table);

        $this->db->insert($this->_table, $entry);

        return ($this->db->affected_rows() >= 0) ? true : false;

    }


  

}

    