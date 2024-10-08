<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Etp_model extends CI_Model {

    private $_mcptable = "t_missioncampagneprocess";
    private $_mcpdetailstable = "t_mcpdetails";
    private $_etpressourcetable = "t_etpressource";

    private $_user = '';
    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getUserActivityByStatus($status = false)
    {
        $this->db->select('mcp_id, mcp_campagne, mcp_process, mcp_mission, mcp_datedebut, mcp_datefin, mcp_lastpause, mcp_tempspause, mcp_status, mcp_agent, mcp_quantite')
            ->select('CONCAT(campagne_libelle, " >> ", mission_libelle, " >> ", process_libelle) AS mcp_libelle', FALSE)
            ->select('campagne_libelle')
            ->select('process_libelle')
            ->select('mission_libelle')
            ->join('tr_campagne', 'campagne_id = mcp_campagne', 'inner')    
            ->join('tr_mission', 'mission_id = mcp_mission', 'inner')    
            ->join('tr_process', 'process_id = mcp_process', 'inner')  
            ->where('mcp_agent', $this->_user)
            ;
        if($status !== false){
            if(is_array($status)){
                $this->db->where_in('mcp_status', $status);
            }else{
                $this->db->where('mcp_status', $status);
            }
        }

        $query = $this->db->get($this->_mcptable);

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getInfoActivity($id)
    {
        $this->db->select('mcp_id, mcp_campagne, mcp_process, mcp_mission, mcp_datedebut, mcp_datefin, mcp_lastpause, mcp_tempspause, mcp_status, mcp_agent, mcp_quantite')
                ->select('campagne_libelle')
                ->select('process_libelle')
                ->select('mission_libelle')
                ->join('tr_campagne', 'campagne_id = mcp_campagne', 'inner')    
                ->join('tr_mission', 'mission_id = mcp_mission', 'inner')    
                ->join('tr_process', 'process_id = mcp_process', 'inner')    
                ->where('mcp_id', $id)
                    ;
        $query = $this->db->get($this->_mcptable);

        if($query->num_rows() > 0){
            return $query->row();
        }
        return false;
    }

    public function setDebutActivity($entry)
    {
        if($this->db->insert($this->_mcptable, $entry)) return $this->db->insert_id();

        return false;
    }

    /*public function setFinActivity($entry, $where)
    {
        
        $this->db->update($this->_mcptable, $entry, $where);

        return ($this->db->affected_rows() > 0);
    }*/
    
    public function updateActivity($entry, $where, $where_in = false)
    {
        if(is_array($where)){
            foreach($where as $key => $value){
                $this->db->where($key, $value);
            }
        }
        if(is_array($where_in)){
            foreach($where_in as $key => $value){
                $this->db->where_in($key, $value);
            }
        }
        
        $this->db->update($this->_mcptable, $entry);

        return ($this->db->affected_rows() > 0);
    }

    public function deleteRessource($days, $campagne = false, $mission = false)
    {   
        $this->db->where_in('etpressource_date', $days);
        if($campagne) $this->db->where('etpressource_campagne', $campagne);
        if($mission) $this->db->where('etpressource_mission', $mission);
        $this->db->delete($this->_etpressourcetable);
        return $this->db->affected_rows() > 0;
    }
    
    public function setBatchRessource($entries)
    {
        $this->db->insert_batch($this->_etpressourcetable, $entries);

        return $this->db->affected_rows() > 0;
    }


    public function getEtpRessource($du, $au)
    {
        $this->db->select('etpressource_id, etpressource_campagne, etpressource_mission, etpressource_date, etpressource_nombre')
                    ->select('CONCAT(campagne_libelle, " > ", mission_libelle) AS etpressource_libelle')
                    ->join('tr_campagne', 'campagne_id = etpressource_campagne', 'inner')
                    ->join('tr_mission', 'mission_id = etpressource_mission', 'inner')
                    ->where('etpressource_date >=', $du)
                    ->where('etpressource_date <=', $au)
                    ;
        $query = $this->db->get($this->_etpressourcetable);
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getEtpRessourceBy($filtre)
    {
        $this->db->select('etpressource_id, etpressource_campagne, etpressource_mission, etpressource_date, etpressource_nombre')

                    ->select('CONCAT(campagne_libelle, " > ", mission_libelle) AS etpressource_libelle')
                    ->join('tr_campagne', 'campagne_id = etpressource_campagne', 'inner')
                    ->join('tr_mission', 'mission_id = etpressource_mission', 'inner')
                    ;

        if(isset($filtre['du']) && ($filtre['du'])!= "" && isset($filtre['au']) && ($filtre['au'])!= "") 
            $this->db->where('etpressource_date >=', $filtre['du'])
                        ->where('etpressource_date <=', $filtre['au']);

        if(isset($filtre['id']) && ($filtre['id'])!= "") 
            $this->db->where('etpressource_id', $filtre['id']);

        if(isset($filtre['campagne']) && ($filtre['campagne'])!= "") 
            $this->db->where('etpressource_campagne', $filtre['campagne']);

        if(isset($filtre['mission']) && ($filtre['mission'])!= "") 
            $this->db->where('etpressource_mission', $filtre['mission']);

        if(isset($filtre['process']) && ($filtre['process'])!= "") 
            $this->db->where('etpressource_process', $filtre['process']);

        $query = $this->db->get($this->_etpressourcetable);
        if($query->num_rows() > 0){
            if(isset($filtre['id']) && ($filtre['id'])!= "") return $query->row();
            return $query->result();
        }
        return false;
    }

    public function deleteRessourceById($id)
    {
        $this->db->delete($this->_etpressourcetable, ['etpressource_id' => $id]);
        return ($this->db->affected_rows() > 0);
    }


    public function getInfosMcp($user = false, $du = false, $au = false) 
    {
        $this->db->select('mcp_id, mcp_date, mcp_campagne, mcp_process, mcp_mission, mcp_datedebut, mcp_datefin, mcp_lastpause, mcp_tempspause, mcp_tempstravail, mcp_status, mcp_agent, mcp_quantite, mcp_etatressource, mcp_ca')
                ->select('campagne_libelle')
                ->select('process_libelle')
                ->select('mission_libelle')
                ->select('usr_id, usr_prenom')
                ->select('etatressource_id, etatressource_libelle, etatressource_facturation')
                ->join('tr_campagne', 'campagne_id = mcp_campagne', 'inner')    
                ->join('tr_mission', 'mission_id = mcp_mission', 'inner')    
                ->join('tr_process', 'process_id = mcp_process', 'inner')   
                ->join('tr_user', 'usr_id = mcp_agent', 'inner') 
                ->join('tr_etatressource', 'etatressource_id = mcp_etatressource', 'left') 
                    ;
        if($user) $this->db->where('mcp_campagne IN (SELECT usercampagne_campagne FROM t_usercampagne WHERE usercampagne_user = "' . $user . '")', null, false);

        if($user){

        }
        if($du){
            $this->db->where('mcp_date >=', $du);
        }
        if($au){
            $this->db->where('mcp_date <=', $au);
        }
        $query = $this->db->get($this->_mcptable);
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getMcpInfosBy($filtre)
    {
        $this->db->select('mcp_id, mcp_date, mcp_campagne, mcp_process, mcp_mission, mcp_datedebut, mcp_datefin, mcp_lastpause, mcp_tempspause, mcp_status, mcp_agent, mcp_quantite, mcp_etatressource')
                ->select('campagne_libelle')
                ->select('process_libelle')
                ->select('mission_libelle')
                ->select('usr_id, usr_prenom')
                ->select('etatressource_id, etatressource_libelle')
                ->join('tr_campagne', 'campagne_id = mcp_campagne', 'inner')    
                ->join('tr_mission', 'mission_id = mcp_mission', 'inner')    
                ->join('tr_process', 'process_id = mcp_process', 'inner')   
                ->join('tr_user', 'usr_id = mcp_agent', 'inner') 
                ->join('tr_etatressource', 'etatressource_id = mcp_etatressource', 'left')
                    ;

        if(isset($filtre['du']) && ($filtre['du'])!= "" && isset($filtre['au']) && ($filtre['au'])!= "") 
            $this->db->where('mcp_date >=', $filtre['du'])
                        ->where('mcp_date <=', $filtre['au']);

        if(isset($filtre['id']) && ($filtre['id'])!= "") 
            $this->db->where('mcp_id', $filtre['id']);

        if(isset($filtre['campagne']) && ($filtre['campagne'])!= "") 
            $this->db->where('mcp_campagne', $filtre['campagne']);

        if(isset($filtre['mission']) && ($filtre['mission'])!= "") 
            $this->db->where('mcp_mission', $filtre['mission']);

        if(isset($filtre['process']) && ($filtre['process'])!= "") 
            $this->db->where('mcp_process', $filtre['process']);

        $query = $this->db->get($this->_mcptable);
        if($query->num_rows() > 0){
            if(isset($filtre['id']) && ($filtre['id'])!= "") return $query->row();
            return $query->result();
        }
        return false;
    }

    public function getSuiviEtp($user = false)
    {
        $this->db->select('mcp_id, mcp_date, mcp_campagne, mcp_process, mcp_mission')
            ->select('campagne_libelle as client, site_libelle as site, proprio_libelle')
            ->select('mission_libelle')
            ->select('etatressource_id, etatressource_libelle, etatressource_facturation')
            ->select('SUM(TIME_TO_SEC(mcp_tempstravail)) as joursTravaille', false)
            ->select('MONTH(mcp_date) AS mois, YEAR(mcp_date) AS annee')
            ->select('role_id, role_libelle')
            ->join('tr_campagne', 'campagne_id = mcp_campagne', 'inner')    
            ->join('tr_site', 'site_id = campagne_site')
            ->join('tr_mission', 'mission_id = mcp_mission', 'inner')    
            ->join('tr_process', 'process_id = mcp_process', 'inner') 
            ->join('tr_proprio', 'proprio_id = campagne_proprio', 'inner')  
            ->join('tr_user', 'usr_id = mcp_agent', 'inner') 
            ->join('tr_role', 'usr_role = role_id', 'inner') 
            ->join('tr_etatressource', 'etatressource_id = mcp_etatressource', 'left')
            ->group_by('mcp_campagne, mcp_mission, MONTH(mcp_date), YEAR(mcp_date), etatressource_id, usr_role')
                ;
        if($user){
            $this->db->where('mcp_campagne IN (SELECT usercampagne_campagne FROM t_usercampagne WHERE usercampagne_user = "' . $user . '")', null, false);
        }
        $query = $this->db->get($this->_mcptable);
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            
            return $query->result();
        }
        return false;
    }

    /*

    select 
    service_libelle,
    role_libelle,
    MONTH(shift_day) as mois,
    YEAR(shift_day) as annee,
    SUM(TIME_TO_SEC(shift_end) - TIME_TO_SEC(shift_begin)) as duree_shift
    FROM t_userservice
    INNER JOIN tr_service on userservice_service = service_id
    INNER JOIN t_shift ON shift_userid = userservice_user
    INNER JOIN tr_user ON usr_id = userservice_user
    INNER JOIN tr_role ON usr_role = role_id
    WHERE YEAR(shift_day) = '2023'
    GROUP BY service_id, usr_role, MONTH(shift_day), YEAR(shift_day)
    ORDER BY service_libelle ASC

    */
    public function getDataService($user = false) //EDIT
    {
        $this->db->select('service_libelle, role_id, role_libelle')
                    ->select('MONTH(shift_day) as mois', false)
                    ->select('YEAR(shift_day) as annee', false)
                    ->select('SUM(TIME_TO_SEC(shift_end) - TIME_TO_SEC(shift_begin) - TIME_TO_SEC(totalpause)) as duree_shift', false)
                    ->join('tr_service', 'userservice_service = service_id', 'inner')
                    ->join('t_shift', 'shift_userid = userservice_user', 'inner')
                    ->join('tr_user', 'usr_id = userservice_user', 'inner')
                    ->join('tr_role', 'usr_role = role_id', 'inner')
                    ->join('view_pauseshift', 'shift = shift_id', 'left')
                    //->where('YEAR(shift_day)', date('Y'))
                    ->where('YEAR(shift_day)', '2023')
                    ->group_by('service_id, usr_role, MONTH(shift_day), YEAR(shift_day)')
                    ->order_by('service_libelle ASC')
                    ;
        if($user){ //ADD
            $this->db->where('userservice_service IN (SELECT userservice_service FROM t_userservice WHERE userservice_user = "' . $user . '")', null, false);
        }
        $query = $this->db->get('t_userservice');
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }
    
    public function getInfosMcpByUser($user = false) 
    {
        $this->db->select('mcp_id, mcp_date, mcp_campagne, mcp_process, mcp_mission, mcp_datedebut, mcp_datefin, mcp_lastpause, mcp_tempspause, mcp_tempstravail, mcp_status, mcp_agent, mcp_quantite, mcp_etatressource')
                ->select('campagne_libelle')
                ->select('process_libelle')
                ->select('mission_libelle')
                ->select('usr_id, usr_prenom')
                ->select('etatressource_id, etatressource_libelle, etatressource_facturation')
                ->join('tr_campagne', 'campagne_id = mcp_campagne', 'inner')    
                ->join('tr_mission', 'mission_id = mcp_mission', 'inner')    
                ->join('tr_process', 'process_id = mcp_process', 'inner')   
                ->join('tr_user', 'usr_id = mcp_agent', 'inner') 
                ->join('tr_etatressource', 'etatressource_id = mcp_etatressource', 'left') 
                    ;
        if(is_array($user)){
            $this->db->where_in('mcp_agent', $user);
        }else if($user){
            $this->db->where('mcp_agent', $user);
        }

        $query = $this->db->get($this->_mcptable);
        //echo $this->db->last_query(); die;
        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function addQuantityMcpProd($data,$id)
    {
        $entry = array(
            'mcp_quantite' => $data['mcp_quantite']
        );
        $this->db->where('mcp_id', $id);
        $this->db->update('t_missioncampagneprocess', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;

    }


    public function insertMcpDetails($data)
    {
        $entry = array(
            'mcpdetails_mcp' => $data['mcpdetails_mcp'],
            'mcpdetails_datecrea' => $data['mcpdetails_datecrea'],
            'mcpdetails_datedebut' => $data['mcpdetails_datedebut'],
        );

        $this->db->insert('t_mcpdetails', $entry);

        return $this->db->insert_id();
       
    }   
        
    public function addDetailsMcp ($data, $id)
    {
        $entry = array(
            'mcpdetails_commentaire' => $data['mcpdetails_commentaire'],
            'mcpdetails_detail1' => $data['mcpdetails_detail1'],
            'mcpdetails_detail2' => $data['mcpdetails_detail2'],
            'mcpdetails_detail3' => $data['mcpdetails_detail3'],
            'mcpdetails_detail4' => $data['mcpdetails_detail4'],
            'mcpdetails_datefin' => $data['mcpdetails_datefin'],

        );

        $this->db->where('mcpdetails_id ', $id);
        $this->db->update('t_mcpdetails', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;
    }


    public function getDetailsTask($id_mcp)
    {
        $this->db->select('mcpdetails_id, mcpdetails_mcp, mcpdetails_commentaire, mcpdetails_detail1, mcpdetails_detail2, mcpdetails_detail3, mcpdetails_detail4')
                    ->select('mcpdetails_datedebut, mcpdetails_datefin, mcpdetails_datecrea, mcpdetails_datemodif')
                    ->select('mcp_id, mcp_mission, mcp_campagne, mcp_process, mcp_date, mcp_quantite, mcp_ca, mcp_status')
                    ->join($this->_mcptable, 'mcpdetails_mcp = mcp_id', 'inner')
                    ->where('mcpdetails_mcp',$id_mcp);
        
       $query = $this->db->get('t_mcpdetails');
       return $query->result();
    }


    public function getDetailsTaskById($id)
    {
        $this->db->select('mcpdetails_id, mcpdetails_mcp, mcpdetails_commentaire, mcpdetails_detail1, mcpdetails_detail2, mcpdetails_detail3, mcpdetails_detail4')
                    ->select('mcpdetails_datedebut, mcpdetails_datefin, mcpdetails_datecrea, mcpdetails_datemodif')
                    ->select('mcp_id, mcp_mission, mcp_campagne, mcp_process, mcp_date, mcp_quantite, mcp_ca, mcp_status')
                    ->join($this->_mcptable, 'mcpdetails_mcp = mcp_id', 'inner')
                    ->where('mcpdetails_id',$id);
        
       $query = $this->db->get('t_mcpdetails');
       return $query->row();
    }


    public function getOngoingUserDetailTask($mcp)
    {
        $nullcommantaire = NULL;
        $this->db->select('mcpdetails_id')
                    // ->join($this->_mcptable, 'mcpdetails_mcp = mcp_id', 'inner')
                    ->where('mcpdetails_mcp', $mcp)
                    // ->where('mcp_agent', $user)
                    ->where('mcpdetails_commentaire', $nullcommantaire);
        $query = $this->db->get($this->_mcpdetailstable);
        if($query->num_rows() > 0){
            return $query->row();
        }
        return false;
       
    }


    public function updateCAMcpProd($ca, $id)
    {
        $entry = array(
            'mcp_ca' => $ca
        );
        $this->db->where('mcp_id', $id);
        $this->db->update($this->_mcptable, $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;

    }



}

    