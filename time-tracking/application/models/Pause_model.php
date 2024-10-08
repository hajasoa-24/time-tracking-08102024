<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pause_model extends CI_Model {

    private $_id,
            $_type,       
            $_shift,     
            $_datecrea,
            $_datemodif,
            $_table = 't_pause';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function getAllPause()
    {
        $this->db->select('pause_id, pause_type, pause_shift, pause_datecrea, pause_datemodif')
                    ->select('typepause_libelle')
                    ->join('tr_typepause','typepause_id = pause_type', 'inner');
        
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function setPause($data)
    {
        if($this->db->insert($this->_table, $data)) return $this->db->insert_id();

        return false;
    }

    public function endPause($shift)
    {

        $this->db->set('pause_end', 'NOW()', FALSE)
                    ->set('pause_etat', 1)
                    ->where('pause_shift', $shift)
                    ->where('pause_etat', 0);
        $this->db->update($this->_table);

        if($this->db->affected_rows() > 0) return true;
        return false;
    }

    public function getAllUserPause($user)
    {
        $this->db->select('pause_id, pause_shift, pause_etat, pause_begin, pause_end, typepause_libelle')
                    ->select('shift_userid, shift_loggedin, shift_day, shift_begin, shift_end, shift_status')
                    ->select('TIMEDIFF(pause_end, pause_begin) as pause_duree', FALSE)
                    ->join('tr_typepause', 'typepause_id = pause_type', 'inner')
                    ->join('t_shift', 'shift_id = pause_shift', 'inner')
                    ->where('shift_userid', $user)
                    ->where('shift_day', date('Y-m-d'))
                    ->order_by('pause_datecrea', 'ASC');
        
        if($query = $this->db->get($this->_table)){
            return $query->result();
        }
        return false;
        
    }

    public function getUserPauseByShift($shift, $day=false)
    {
        $this->db->select('pause_id, pause_shift, pause_etat, pause_begin, pause_end, pause_ajustbegin, pause_ajustend, typepause_libelle')
                    ->select('shift_userid, shift_loggedin, shift_day, shift_begin, shift_end, shift_status')
                    ->join('tr_typepause', 'typepause_id = pause_type', 'inner')
                    ->join('t_shift', 'shift_id = pause_shift', 'inner')
                    ->where('pause_shift', $shift)
                    ->order_by('pause_datecrea', 'ASC');
        if($day){
            $this->db->where('shift_day', $day);
        }
        
        if($query = $this->db->get($this->_table)){
            return $query->result();
        }
        return false;
        
    }

    public function getUserPauseAjustByShift($shift, $day=false)
    {
        $this->db->select('pause_id, pause_shift,  pause_etat, typepause_libelle')
                    ->select('shift_userid, shift_loggedin, shift_day, shift_status')
                    ->select('CASE WHEN (shift_ajustbegin IS NOT NULL AND UNIX_TIMESTAMP(`shift_ajustbegin`) != 0) THEN shift_ajustbegin ELSE shift_begin END as shift_begin', FALSE)
                    ->select('CASE WHEN (shift_ajustend IS NOT NULL AND UNIX_TIMESTAMP(`shift_ajustend`) != 0) THEN shift_ajustend ELSE shift_end END as shift_end', FALSE)
                    ->select('CASE WHEN (pause_ajustbegin IS NOT NULL AND UNIX_TIMESTAMP(`pause_ajustbegin`) != 0)THEN pause_ajustbegin ELSE pause_begin END as pause_begin', FALSE)
                    ->select('CASE WHEN (pause_ajustend IS NOT NULL AND UNIX_TIMESTAMP(`pause_ajustend`) != 0) THEN pause_ajustend ELSE pause_end END as pause_end', FALSE)
                    ->join('tr_typepause', 'typepause_id = pause_type', 'inner')
                    ->join('t_shift', 'shift_id = pause_shift', 'inner')
                    ->where('pause_shift', $shift)
                    ->order_by('pause_datecrea', 'ASC');
        if($day){
            $this->db->where('shift_day', $day);
        }
        
        if($query = $this->db->get($this->_table)){
            return $query->result();
        }
        return false;
        
    }

    public function setAjustementPauseSup($data, $where)
    {
        return $this->db->update($this->_table, $data, $where);
    }


}

    