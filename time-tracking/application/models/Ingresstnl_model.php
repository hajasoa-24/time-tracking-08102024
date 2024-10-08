<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingresstnl_model extends CI_Model {
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
        try{
            error_reporting(0);
            $this->_DB = $this->load->database('ingresstnl', TRUE);
            if(!$this->_DB->conn_id){
                $this->isConnected = false;
            }

        }catch(Exception $e){
            $this->isConnected = false;
        }
        
    }

    public $_DB;
    private $_table = 'attendance';
    public $isConnected = true;

    public $_ingress_hour_in;

   public function getUserPointage( $userIngress, $date = false)
   {
        //var_dump($this->isConnected);
       if($this->isConnected){

           $this->_DB->select('att_in, att_break, att_resume, att_out, att_ot, att_done, workhour, userid, date')
                        ->where('userid', $userIngress);
           if($date){
               $this->_DB->where('date', $date);
           }
           $query = $this->_DB->get($this->_table);
           //echo $this->_DB->last_query();
           if($query->num_rows() > 0){
               return $query->result();
           }
        }
        return false;
   }
   

}
