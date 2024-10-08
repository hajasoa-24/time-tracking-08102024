<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Heuresup_model extends CI_Model {

    private $_id,
            $_responsable,
            $_shiftid,
            $_begin,
            $_end,
            $_datecrea,
            $_datemodif,
            $_table = 't_hsupplementaire';
    

    //PUBLIC FUNCTIONS

	public function __construct() 
    {
        parent::__construct();
    }

    public function getAllHS()
    {
        $this->db->select('hs_id, hs_responsable, hs_shiftid, hs_begin, hs_end, hs_datecrea, hs_datemodif')
                    ->join('tr_shift', 'hs_shiftid = shift_id', 'inner');
        
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function insertHS($data)
    {
        $entry = [
            'hs_responsable' => (isset($data['hs_responsable'])) ? $data['hs_responsable'] : '',
            'hs_begin' => (isset($data['hs_begin'])) ? $data['hs_begin'] : '',
            'hs_end' => (isset($data['hs_end'])) ? $data['hs_end'] : '',
            'hs_shiftid' => (isset($data['hs_shiftid'])) ? $data['hs_shiftid'] : '', 
            'hs_datecrea' => date('Y-m-d H:i:s'),
            'hs_datemodif' => date('Y-m-d H:i:s')
        ];

        if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }


    

}

    