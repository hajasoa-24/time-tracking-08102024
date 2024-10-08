<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingressmcr_model extends CI_Model {
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
        try{
            error_reporting(0);
            $this->_DB = $this->load->database('ingressmcr', TRUE);
            if(!$this->_DB->conn_id){
                $this->isConnected = false;
            }

        }catch(Exception $e){
            $this->isConnected = false;
        }
        
    }


    public $_DB;
    private $_table = 'checkinout';
    public $isConnected = true;

    public $_ingress_hour_in;


    public function getUserPointage( $userIngress, $date = false)
    {
        //echo 'ok';
        if($this->isConnected){
            $this->_DB->select(
                'DATE_FORMAT(checkin, "%H:%i") as att_in, 
                DATE_FORMAT(check1, "%H:%i") as att_break, 
                DATE_FORMAT(check2, "%H:%i") as att_resume,
                DATE_FORMAT(check3, "%H:%i") as att_out, 
                DATE_FORMAT(check4, "%H:%i") as att_ot, 
                DATE_FORMAT(check5, "%H:%i") as att_done, 
                logid, im as userid, DATE(checkin) as date,
                CASE
                    WHEN ( ISNULL( check1 ) OR check1 = 0 ) THEN
                        TIME_FORMAT( TIMEDIFF( NOW(), checkin ), "%H:%i" ) 
                    WHEN
                        ( ISNULL( check2 ) OR check2 = 0 ) THEN
                        TIME_FORMAT( TIMEDIFF( check1, checkin ), "%H:%i" ) 
                    WHEN ( ISNULL( check3 ) OR check3 = 0 ) THEN
                        TIME_FORMAT( SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF( check2, checkin )) - TIME_TO_SEC(TIMEDIFF( check2, check1 ))), "%H:%i" ) 
                    WHEN ( ISNULL( check4 ) OR check4 = 0 ) THEN
                        TIME_FORMAT( SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF( check1, checkin )) + TIME_TO_SEC(TIMEDIFF( check3, check2 ))), "%H:%i" ) 
                    WHEN ( ISNULL( check5 ) OR check5 = 0 ) THEN
                        TIME_FORMAT( SEC_TO_TIME(TIME_TO_SEC(TIMEDIFF( check1, checkin )) + TIME_TO_SEC(TIMEDIFF( check3, check2 ))), "%H:%i" ) 
                    ELSE 
                        TIME_FORMAT(SEC_TO_TIME((TIME_TO_SEC(TIMEDIFF( check1, checkin )) + TIME_TO_SEC(TIMEDIFF( check3, check2 )) + TIME_TO_SEC(TIMEDIFF( check5, check4 )))), "%H:%i") 
                END AS workhour'
            )
                ->where('im', $userIngress)
                ->group_by(array('im', 'id'));
            
            if(is_array($date)){
                $du = $date[0];
                $au = $date[1];

                $this->_DB->where('DATE(checkin) >=', $du)
                            ->where('DATE(checkin) <=', $au);

            }else if($date){
                $this->_DB->where('DATE(checkin)', $date);
            }

            $query = $this->_DB->get($this->_table);
            //echo $this->_DB->last_query();  
            if($query && $query->num_rows() > 0){
                return $query->result();
            }
         }
         return false;
    }

}
