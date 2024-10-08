<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport_model extends CI_Model 
{

    //PUBLIC FUNCTIONS

	public function __construct()  
    {
        parent::__construct();
    }

    public function getHeure()
    {
        $query = $this->db->get('tr_heuretransport');
        return $query->result();
    }
    public function getAllAxe()
    {
        $query = $this->db->get('tr_axe');
        return $query->result();
    }

    // public function getAllAxequartier(){
    //     $query =  $this->db->select('axe_libelle,quartier_libelle')
    //     ->join('tr_quartier', 'tr_quartier.quartier_id = t_axequartier.axequartier_fokontany')
    //     ->join('tr_axe', 'tr_axe.axe_id = t_axequartier.axequartier_axe')->group_by('axe_id')
    //     ->get('t_axequartier');
    //     return $query->result();

    // }
    public function getAllAxequartier(){
        $query =  $this->db->select('axe_libelle,quartier_libelle,heuretransport_heure')
        ->join('tr_quartier', 'tr_quartier.quartier_id = t_axequartier.axequartier_fokontany')
        ->join('t_axeheure', 't_axeheure.heureaxe_id = t_axequartier.axequartier_axe')
        ->join('tr_axe', 'tr_axe.axe_id = t_axeheure.heureaxe_axe', 'left')
        ->join('tr_heuretransport', 'tr_heuretransport.heuretransport_id = t_axeheure.heureaxe_heure', 'left')


        ->get('t_axequartier');
        return $query->result();

    }


    public function getAllAxequartierlist(){
        // $this->db->select('heuretransport_heure, axe_libelle, GROUP_CONCAT(tr_quartier.quartier_libelle) AS quartier_libelle');
        // $this->db->from('t_axeheure');
        // $this->db->join('t_axeheure','t_axeheure.heureaxe_id = t_quartieraxe.axequartier_axe','left');
        // $this->db->join('tr_axe', 'tr_axe.axe_id = t_axeheure.heureaxe_axe', 'left');
        // $this->db->join('tr_heuretransport', 'tr_heuretransport.heuretransport_id = t_axeheure.heureaxe_heure', 'left');
        // $this->db->join('tr_quartier', 'tr_quartier.quartier_id = t_quartieraxe.axequartier_fokontany', 'left');
        // $this->db->group_by('t_axeheure.heureaxe_id');
        // $query = $this->db->get();

        $this->db->select('t_axeheure.heureaxe_id,heuretransport_heure, axe_libelle, GROUP_CONCAT(tr_quartier.quartier_libelle) AS quartier_libelle');
        $this->db->from('t_axeheure');
        $this->db->join('t_axequartier','t_axeheure.heureaxe_id = t_axequartier.axequartier_axe','left');
        $this->db->join('tr_axe', 'tr_axe.axe_id = t_axeheure.heureaxe_axe', 'left');
        $this->db->join('tr_heuretransport', 'tr_heuretransport.heuretransport_id = t_axeheure.heureaxe_heure', 'left');
        $this->db->join('tr_quartier', 'tr_quartier.quartier_id = t_axequartier.axequartier_fokontany', 'left');
        $this->db->group_by('t_axeheure.heureaxe_id');
        $query = $this->db->get();
        
        // return $query->result();
    // $this->db->select('t_axeheure.heureaxe_id,tr_heuretransport.heuretransport_heure, tr_axe.axe_libelle, GROUP_CONCAT(tr_quartier.quartier_libelle) AS quartier_libelle');
    // $this->db->from('tr_heuretransport');
    // $this->db->join('t_axeheure', 'tr_heuretransport.heuretransport_id = t_axeheure.heureaxe_heure');
    // $this->db->join('tr_axe', 'tr_axe.axe_id = t_axeheure.heureaxe_axe', 'left');
    // $this->db->join('t_quartieraxe', 't_quartieraxe.axequartier_axe = t_axeheure.heureaxe_axe', 'left');
    // $this->db->join('tr_quartier', 'tr_quartier.quartier_id = t_quartieraxe.axequartier_fokontany', 'left');
    // $this->db->group_by('tr_heuretransport.heuretransport_heure, tr_axe.axe_libelle');
    // $query = $this->db->get();
    
    return $query->result();
    }
    

    public function transportuserannulation($id){
        $this->db
        ->where('transportuser_id', $id)
        ->delete('t_transportuser');
        return true;

    }
    public function getAxe()
    {
        $query = $this->db->select("heureaxe_id,axe_libelle, heuretransport_heure")
        ->join('tr_heuretransport', ' heuretransport_id = heureaxe_heure ','inner')
        ->join('tr_axe', ' axe_id = heureaxe_axe ','inner')->get("t_axeheure");


        return $query->result();
    }
    
    public function getAxebytime($id)
    {
        $query = $this->db->select("axe_libelle,axe_id")->join('tr_heuretransport', ' heuretransport_id = heureaxe_heure ','inner')
        ->join('tr_axe', ' axe_id = heureaxe_axe ','inner')->where("heureaxe_heure", "$id")->get("t_axeheure");

        return $query->result();
    }

    public function getquartier()
    {
        $this->db->select("quartier_id as quartier_id");
        $this->db->select("quartier_libelle as quartier_libelle");

        $query = $this->db->get('tr_quartier');
        return $query->result();
    }

    public function add($taskData)
    {
        $this->db->insert('t_transportuser', $taskData);
        return true;
    }

    public function transportuserupdate($id, $data){
        $entry = array(
            'transportuser_axe' => $data['transportuser_axe'],
            'transportuser_status' => $data['transportuser_axe']

        );
        $this->db->where('transportuser_id', $id);
        $this->db->update('t_transportuser', $entry);

    }

    public function transportuserupdatebyuser($id, $data){
        $entry = array(
            'transportuser_axe' => $data['transportuser_axe'],
            'transportuser_heure' => $data['transportuser_heure'],
            'transportuser_quartier' => $data['transportuser_quartier']
        );
        $this->db->where('transportuser_id', $id);
        $this->db->update('t_transportuser', $entry);

    }

    public function suiviTransport ($filter)
    {
        $start = $filter['debut'];
        $end = $filter['fin'];
        $query = $this->db->select("DATE_FORMAT(ttu.transportuser_date, '%Y/%m/%d') AS date");
        $this->db->select("ttu.transportuser_quartier");

        $this->db->select("ttu.transportuser_id");
        $this->db->select("tht.heuretransport_heure");
        $this->db->select("tu.usr_prenom");
        $this->db->select("tat.axe_libelle");
        $this->db->select("ts.site_libelle");
        $this->db->select("TIME_FORMAT(pl.planning_sortie,'%H:%i') AS planning_sortie");
        
        $this->db->select("CONCAT_WS('/', GROUP_CONCAT(DISTINCT tc.campagne_libelle ORDER BY tc.campagne_libelle SEPARATOR '/'), GROUP_CONCAT(DISTINCT tserv.service_libelle ORDER BY tserv.service_libelle SEPARATOR '/')) AS campagnes_et_services");
        
        $this->db->from("t_transportuser AS ttu");
        $this->db->join("tr_heuretransport AS tht", "ttu.transportuser_heure = tht.heuretransport_id");
        $this->db->join("tr_user AS tu", "ttu.transportuser_user = tu.usr_id");
        $this->db->join("t_planning AS pl", "ttu.transportuser_user = pl.planning_user AND DATE_FORMAT(ttu.transportuser_date,'%Y/%m/%d') = DATE_FORMAT(pl.planning_date,'%Y/%m/%d')", "left");


        $this->db->join("tr_axe AS tat", "ttu.transportuser_axe = tat.axe_id");
        $this->db->join("tr_site AS ts", "tu.usr_site = ts.site_id", "inner");
        $this->db->join("t_usercampagne AS tuc", "tu.usr_id = tuc.usercampagne_user", "left");
        $this->db->join("tr_campagne AS tc", "tuc.usercampagne_campagne = tc.campagne_id", "left");
        $this->db->join("t_userservice AS tus", "tu.usr_id = tus.userservice_user", "left");
        $this->db->join("tr_service AS tserv", "tus.userservice_service = tserv.service_id", "left");
        
        $this->db->where('DATE(ttu.transportuser_date) >=', $start)->where('DATE(ttu.transportuser_date) <=', $end);
        $this->db->where('DATE(ttu.transportuser_date) >=', $start)->where('DATE(ttu.transportuser_date) <=', $end);



        $this->db->group_by("tht.heuretransport_heure, tu.usr_prenom, tat.axe_libelle, ts.site_libelle");
            
        $query = $this->db->get();

        $result = $query->result_array();


        return $result;
        
    }

    public function getaxeuser($id){
        $query = $this->db->select("transportuser_heure")->where("transportuser_id", "$id")->get("t_transportuser");
        return $query->result();
    }


    public function addAxetransport($axetransport){
        $this->db->insert('t_axeheure', $axetransport);
        return true;

    }
    public function deleteAxe($id)
    {
        $this->db
        ->where('heureaxe_id', $id)
        ->delete('t_axeheure');
        return true;

    }
    public function getaxebyquartier($axelibelle){
        $query = $this->db->select("axe_libelle")
        ->join('tr_heuretransport', ' heuretransport_id = heureaxe_heure ','inner')
        ->join('tr_axe', ' axe_id = heureaxe_axe ','inner')->get("t_axeheure");    
    }

    public function getaxebyquartiertest(){

        $this->db->select('heuretransport_heure,heuretransport_id,axe_libelle,axe_id,t_axequartier.axequartier_fokontany');
        $this->db->from('tr_heuretransport');
        $this->db->join('t_axeheure', 'tr_heuretransport.heuretransport_id = t_axeheure.heureaxe_heure');
        $this->db->join('tr_axe', 't_axeheure.heureaxe_axe = tr_axe.axe_id');
        $this->db->join('t_axequartier', 'tr_axe.axe_id = t_axequartier.axequartier_axe');
        // $this->db->where('t_axequartier.axequartier_fokontany', $quartier_id);

        $query = $this->db->get();
        
        return $query->result();
    }

    public function axetransportday($id)
    {

        $date = new DateTime();
        $formatted_date = $date->format('Y-m-d');

        $this->db->select("tht.heuretransport_heure");

        $this->db->select("tat.axe_libelle");
        $this->db->select("ttu.transportuser_quartier");
        $this->db->select("ttu.transportuser_status");
        $this->db->select("ttu.transportuser_id");


        $this->db->from("t_transportuser AS ttu");
        $this->db->join("tr_heuretransport AS tht", "ttu.transportuser_heure = tht.heuretransport_id");
        $this->db->join("tr_user AS tu", "ttu.transportuser_user = tu.usr_id");

        $this->db->join("tr_axe AS tat", "ttu.transportuser_axe = tat.axe_id")->where('ttu.transportuser_user', $id)->where('DATE(ttu.transportuser_date)',$formatted_date);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;

    }

    public function test()
    {

        $query = $this->db->get('tr_heuretransport');
        $heuretransport =  $query->result();
        $nombre_lignes = $query->num_rows();

        return $heuretransport;
    }

    public function export ($filter, $heure, $axe)
    {
        $start = $filter['debut'];
        $end = $filter['fin'];

        $this->db->select("CONCAT_WS('/', GROUP_CONCAT(DISTINCT tc.campagne_libelle ORDER BY tc.campagne_libelle SEPARATOR '/'), GROUP_CONCAT(DISTINCT tserv.service_libelle ORDER BY tserv.service_libelle SEPARATOR '/')) AS campagnes_et_services");
        $this->db->select("tu.usr_prenom");
        $this->db->select("ttu.transportuser_quartier");
        $this->db->select("ts.site_libelle");
        $this->db->select("tht.heuretransport_heure");
        

        $this->db->from("t_transportuser AS ttu");
        $this->db->join("tr_heuretransport AS tht", "ttu.transportuser_heure = tht.heuretransport_id");
        $this->db->join("tr_user AS tu", "ttu.transportuser_user = tu.usr_id");
        $this->db->join("tr_axe AS tat", "ttu.transportuser_axe = tat.axe_id");
        $this->db->join("tr_site AS ts", "tu.usr_site = ts.site_id", "inner");
        $this->db->join("t_usercampagne AS tuc", "tu.usr_id = tuc.usercampagne_user", "left");
        $this->db->join("tr_campagne AS tc", "tuc.usercampagne_campagne = tc.campagne_id", "left");
        $this->db->join("t_userservice AS tus", "tu.usr_id = tus.userservice_user", "left");
        $this->db->join("tr_service AS tserv", "tus.userservice_service = tserv.service_id", "left");
        
        $this->db->where('DATE(ttu.transportuser_date) >=', $start)->where('DATE(ttu.transportuser_date) <=', $end);
        $this->db->where('DATE(ttu.transportuser_date) >=', $start)->where('DATE(ttu.transportuser_date) <=', $end);
        $this->db->where('ttu.transportuser_axe', $axe);
        $this->db->where('ttu.transportuser_heure',$heure);

        $this->db->group_by("tht.heuretransport_heure, tu.usr_prenom, tat.axe_libelle, ts.site_libelle");

          
        $query = $this->db->get();

         $result = $query->result_array();



        return $result;
    }

    public function getInfoquartier($id, $format='object'){
        $this->db->select('quartier_id,quartier_libelle')
                ->join('tr_quartier','quartier_id = axequartier_fokontany ', 'inner')
                ->where('axequartier_axe', $id);
                
        $query = $this->db->get('t_axequartier');
        if($query->num_rows() > 0){

            if($format == 'array'){
    
                return $query->result_array();
            }
            return $query->result();
        }
        return false;

    }

    public function insertassignquatieraxe($id,$data){
        $this->db
                ->where('axequartier_axe', $id)
                ->delete('t_axequartier');

          //Insert datas
          if(!empty($data)){
            foreach($data as $k => $fokontany){
              $entry[] = array(
                'axequartier_axe' => $id,
                'axequartier_fokontany' => $fokontany,
               
              );
            }
            
            $query = $this->db
                            ->insert_batch('t_axequartier', $entry);

            return $this->db->insert_id();
          }
          
          return false;
    }

}  