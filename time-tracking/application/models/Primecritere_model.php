<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Primecritere_model extends CI_Model {

    
    public function __construct() 
    {
        parent::__construct();
    }


    public function getallcritere(){

        $this->db->select('primetypecritere_id,primetypecritere_libelle');
        
        $query = $this->db->get('tr_primetypecritere');

        return $query->result();
    }

    public function getallprofilbycampagne($id){

        $this->db->select('primeprofil_id,primeprofil_libelle')
        ->where("primeprofil_campagne", $id);

        $query = $this->db->get('tr_primeprofil');

        return $query->result();
    }

    public function getallfrequence(){

        $this->db->select('primefrequencecritere_id,primefrequencecritere_libelle');
        
        $query = $this->db->get('tr_primefrequencecritere');

        return $query->result();
    }


    public function getallprimecriterebycampagne($id)
    {
        $this->db->select('campagne_libelle,primecritere_id,primecritere_libelle,primetypecritere_libelle,primecritere_objectif,primeprofil_libelle,process_libelle,primecritere_etat,primecritere_actif')
        ->join('tr_primecritere', 't_primeaffectationtc.primeaffectationtc_critere = tr_primecritere.primecritere_id')
        ->join('tr_campagne', 't_primeaffectationtc.primeaffectationtc_campagne = tr_campagne.campagne_id')
        ->join('tr_primetypecritere','t_primeaffectationtc.primeaffectationtc_type = tr_primetypecritere.primetypecritere_id')
        ->join('tr_primeprofil','t_primeaffectationtc.primeaffectationtc_profil = tr_primeprofil.primeprofil_id')
        ->join('tr_process','t_primeaffectationtc.primeaffectationtc_process = tr_process.process_id')
        ->where('t_primeaffectationtc.primeaffectationtc_campagne', $id)
        ->where('tr_primecritere.primecritere_actif', '1');

        $query = $this->db->get('t_primeaffectationtc');
        return $query->result();

    }

    /*public function getallprimecritere()
    {
        $this->db->select('campagne_libelle,primecritere_id,primecritere_libelle, primetypecritere_libelle, primecritere_objectif,primeprofil_libelle,process_libelle,primecritere_etat,primecritere_actif')
        ->join('tr_primecritere', 't_primeaffectationtc.primeaffectationtc_critere = tr_primecritere.primecritere_id')
        ->join('tr_campagne', 't_primeaffectationtc.primeaffectationtc_campagne = tr_campagne.campagne_id')
        ->join('tr_primetypecritere','t_primeaffectationtc.primeaffectationtc_type = tr_primetypecritere.primetypecritere_id')
        ->join('tr_primeprofil','t_primeaffectationtc.primeaffectationtc_profil = tr_primeprofil.primeprofil_id')
        ->join('tr_process','t_primeaffectationtc.primeaffectationtc_process = tr_process.process_id')
        ->where('tr_primecritere.primecritere_actif', '1');


        $query = $this->db->get('t_primeaffectationtc');
        return $query->result();

    }*/

    public function getallprimecritere()
    {
        $this->db->select('campagne_libelle,primecritere_id,primecritere_libelle, primetypecritere_libelle, primecritere_objectif,primeprofil_libelle,process_libelle,primecritere_etat,primecritere_actif')
        ->select('process_libelle')
        ->select('primeobjectifjour_valeur')
        ->join('tr_campagne', 'primecritere_campagne = tr_campagne.campagne_id')
        ->join('tr_primetypecritere','primecritere_typecritere = primetypecritere_id')
        ->join('tr_primeprofil','primecritere_profil = primeprofil_id AND primecritere_campagne = primeprofil_campagne', 'left')
        ->join('tr_process','primecritere_process = process_id', 'left')
        ->join('t_primeobjectifjournalier', 'primeobjectifjour_critere = primecritere_id AND primeobjectifjour_day = CURDATE()', 'left')
        ->where('primecritere_actif', '1');


        $query = $this->db->get('tr_primecritere');
        //echo $this->db->last_query();
        return $query->result();

    }

    public function getprimecriteredirectionbycampagne($id)
    {
        $this->db->select('primecritere_id,primecritere_libelle,primetypecritere_libelle,primecritere_objectif,primeprofil_libelle,primecritere_etat,primecritere_actif')
        ->join('tr_primecritere', 't_primeaffectationtc.primeaffectationtc_critere = tr_primecritere.primecritere_id')
        ->join('tr_campagne', 't_primeaffectationtc.primeaffectationtc_campagne = tr_campagne.campagne_id')
        ->join('tr_primetypecritere','tr_primecritere.primecritere_critere = tr_primetypecritere.primetypecritere_id')
        ->join('tr_primeprofil','t_primeaffectationtc.primeaffectationtc_profil = tr_primeprofil.primeprofil_id')

        ->where('t_primeaffectationtc.primeaffectationtc_campagne', $id)
        ->where('tr_primecritere.primecritere_actif', '1');


        $query = $this->db->get('t_primeaffectationtc');
        return $query->result();

    }

    public function getprimecriterebyid($id)
    {
        $this->db->select('primecritere_id,primecritere_libelle,primetypecritere_libelle,primecritere_objectif,primeprofil_libelle,primecritere_etat')
        ->join('tr_primecritere', 't_primeaffectationtc.primeaffectationtc_critere = tr_primecritere.primecritere_id')
        ->join('tr_campagne', 't_primeaffectationtc.primeaffectationtc_campagne = tr_campagne.campagne_id')
        ->join('tr_primetypecritere','tr_primecritere.primecritere_critere = tr_primetypecritere.primetypecritere_id')
        ->join('tr_primeprofil','t_primeaffectationtc.primeaffectationtc_profil = tr_primeprofil.primeprofil_id')

        ->where('t_primeaffectationtc.primeaffectationtc_campagne', $id);


        $query = $this->db->get('t_primeaffectationtc');
        return $query->result();

    }

    public function addcritere($critere){
        //verification de doublon
        if(!$this->isDuplicateCritere($critere)){
            $this->db->insert('tr_primecritere', $critere);
            return true;
        }
        return false;
    }

    public function isDuplicateCritere($critere)
    {
        $this->db->select('primecritere_id')
                ->where('primecritere_profil', $critere['primecritere_profil'])
                ->where('primecritere_campagne', $critere['primecritere_campagne'])
                ->where('primecritere_mission', $critere['primecritere_mission'])
                ->where('primecritere_process', $critere['primecritere_process'])
                ->where('primecritere_typecritere', $critere['primecritere_typecritere'])
                ;
        $query = $this->db->get('tr_primecritere');
        if($query->num_rows() > 0) return true;
        return false;
    }

    public function addprofilcritere($critereprofil){
        $this->db->insert('t_primeaffectationtc',$critereprofil);
        return true;
    }

    public function get_last_inserted_id() {
        return $this->db->insert_id();
    }


    public function applicationtachedirection($data,$id)
    {
        $entry = array(
            'primecritere_etat' => $data['primecritere_etat']
        );
        $this->db->where('primecritere_id', $id);
        $this->db->update(' tr_primecritere', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;

    }

    
    public function desactivationcriteredirection($data,$id)
    {
        $entry = array(
            'primecritere_actif' => $data['primecritere_actif']
        );
        $this->db->where('primecritere_id', $id);
        $this->db->update(' tr_primecritere', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;

    }

    public function desactivationcriterecadre($data,$id)
    {
        $entry = array(
            'primecritere_etat' => $data['primecritere_etat']
        );
        $this->db->where('primecritere_id', $id);
        $this->db->update(' tr_primecritere', $entry);

        if($this->db->affected_rows() > 0){
            return true;
        }
  
        return false;

    }


    public function getListProfilProcess($id_profil, $id_campagne)
    {
        $this->db->select('process_id, process_libelle')
            ->join('t_affectationmcp', 'affectationmcp_id = primeprofilprocess_process', 'inner')
            ->join('tr_process', 'process_id = affectationmcp_process', 'inner')
            //->join('tr_primeprofil', '	primeprofil_id = primeprofilprocess_profil', 'inner')
            ->where('primeprofilprocess_profil ', $id_profil)
            ->where('primeprofilprocess_campagne ', $id_campagne)
            ;
        $query = $this->db->get('t_primeprofilprocess');
        //echo $this->db->last_query();
        if($query->num_rows() > 0)
        {
            return $query->result();
        }
        return false;
    }


    public function getallmodecalculprime()
    {
        $this->db->select('primemodecalcul_id,primemodecalcul_libelle');
        $query = $this->db->get('tr_primemodecalcul');
        return $query->result();
    }

    public function getprimecriterebyType($type, $onlyActive = true)
    {
        $this->db->select('primecritere_id, primecritere_libelle, primecritere_montant, primecritere_objectif')
                ->select('campagne_libelle')
                ->select('primemodecalcul_libelle, primemodecalcul_calcul, primemodecalcul_action')
                ->join('tr_primemodecalcul', 'primemodecalcul_id = primecritere_modecalcul', 'inner')
                ->join('tr_campagne', 'primecritere_campagne = campagne_id', 'inner')
                ->where('primecritere_typecritere', $type)
                ;
        if($onlyActive){
            $this->db
                ->where('primecritere_actif', 1)
                ->where('primemodecalcul_actif', 1)
                ;
        }
        $query = $this->db->get('tr_primecritere');
        return $query->result();
    }

    public function getListTypeCritere(bool $onlyActive = true)
    {
        $this->db->select('primetypecritere_id, primetypecritere_libelle');
        if($onlyActive) $this->db->where('primetypecritere_actif', 1);

        $query = $this->db->get('tr_primetypecritere');
        return $query->result();
    }

    public function addObjectifJournalier($data)
    {
        $this->db->select('primeobjectifjour_id')
                ->where('primeobjectifjour_day', $data['primeobjectifjour_day'])
                ->where('primeobjectifjour_critere', $data['primeobjectifjour_critere'])
                ->get('t_primeobjectifjournalier')
                ;
        if($this->db->affected_rows() > 0){
            $this->db
                ->set('primeobjectifjour_valeur', $data['primeobjectifjour_valeur'])
                ->set('primeobjectifjour_datemodif', $data['primeobjectifjour_datemodif'])
                ->where('primeobjectifjour_day', $data['primeobjectifjour_day'])
                ->where('primeobjectifjour_critere', $data['primeobjectifjour_critere'])
                ->update('t_primeobjectifjournalier')
                ;
            if($this->db->affected_rows() > 0) return true;
        }else{
            $this->db->insert('t_primeobjectifjournalier', $data);
            if($this->db->affected_rows() > 0) return true;
        }
        return false;
    }

}