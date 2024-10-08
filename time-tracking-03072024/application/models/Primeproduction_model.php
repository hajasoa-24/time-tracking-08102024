<?php

use phpDocumentor\Reflection\Types\Array_;

defined('BASEPATH') OR exit('No direct script access allowed');

class Primeproduction_model extends CI_Model {

    public function __construct() 
    {
        parent::__construct();
    }

    public function getCampagneDayProduction(int $campagneId, string $day)
    {
        $this->db->select('mcp_id, mcp_date, mcp_datedebut, mcp_datefin, mcp_tempstravail, mcp_quantite, mcp_ca, mcp_status')
                //->select('mcpdetails_commentaire, mcpdetails_detail1, mcpdetails_detail2, mcpdetails_detail3, mcpdetails_detail4, mcpdetails_datedebut, mcpdetails_datefin')
                ->select('mission_libelle')
                ->select('campagne_libelle')
                ->select('process_id, process_libelle')
                ->select('usr_id, usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_email')
                //->join('t_mcpdetails', 'mcpdetails_mcp = mcp_id', 'inner')
                ->join('tr_mission', 'mcp_mission = mission_id', 'inner')
                ->join('tr_campagne', 'mcp_campagne = campagne_id', 'inner')
                ->join('tr_process', 'mcp_process = process_id', 'inner')
                ->join('tr_user', 'mcp_agent = usr_id', 'inner')
                ->join('tr_etatressource', 'mcp_etatressource = etatressource_id', 'inner')
                ->where('mcp_date', $day)
                ->where('mcp_campagne',$campagneId)
                ->order_by('mcp_process, mcp_id')
                ;

        $query = $this->db->get('t_missioncampagneprocess');
        if($query->num_rows() > 0) return $query->result();
        return false;
    }

    public function getPrimeCritereByCampagneAndProcess($campagne, $process, $onlyActive = true)
    {
        $this->db->select('primecritere_id, primecritere_libelle, primecritere_objectif, primecritere_modecalcul, primecritere_montant')
            ->select('primetypecritere_id, primetypecritere_libelle')
            ->select('primeprofil_id, primeprofil_libelle')
            ->select('primemodecalcul_id, primemodecalcul_libelle, primemodecalcul_calcul, primemodecalcul_action')
            //->join('tr_campagne', 'primecritere_campagne = tr_campagne.campagne_id')
            ->join('tr_primetypecritere','primecritere_typecritere = primetypecritere_id')
            ->join('tr_primeprofil','primecritere_profil = primeprofil_id')
            ->join('tr_process','primecritere_process = process_id')
            ->join('tr_primemodecalcul', 'primecritere_modecalcul = primemodecalcul_id', 'inner')
            ->where('primecritere_campagne', $campagne)
            ->where('primecritere_process', $process)
            ;
        if($onlyActive)
            $this->db->where('primecritere_actif', 1)
                ->where('primemodecalcul_actif', 1)
                ;
        $query = $this->db->get('tr_primecritere');
        return $query->result();
    }

    public function getPrimeCritereByCampagneProfilProcess(int $campagne, int $profil, int $process, bool $isActive = true) : array
    {
        $this->db->select('primecritere_id, primecritere_libelle, primecritere_modecalcul, primecritere_frequencecalcul, primecritere_objectif, primecritere_montant')
                ->where('primecritere_actif', $isActive)
                ->where('primecritere_campagne', $campagne)
                ->where('primecritere_profil', $profil)
                ->where('primecritere_process', $process)
                ;
        $query = $this->db->get('tr_primecritere');
        return $query->result();
    }

    public function getPrimeCritereByMCP($campagne, $mission, $process, $onlyActive = true)
    {
        $this->db->select('primecritere_id, primecritere_libelle, primecritere_objectif, primecritere_modecalcul, primecritere_montant')
            ->select('primetypecritere_id, primetypecritere_libelle')
            ->select('primeprofil_id, primeprofil_libelle')
            ->select('primemodecalcul_id, primemodecalcul_libelle, primemodecalcul_calcul, primemodecalcul_action')
            //->join('tr_campagne', 'primecritere_campagne = tr_campagne.campagne_id')
            ->join('tr_primetypecritere','primecritere_typecritere = primetypecritere_id')
            ->join('tr_primeprofil','primecritere_profil = primeprofil_id')
            ->join('tr_process','primecritere_process = process_id')
            ->join('tr_primemodecalcul', 'primecritere_modecalcul = primemodecalcul_id', 'inner')
            ->where('primecritere_campagne', $campagne)
            ->where('primecritere_mission', $campagne)
            ->where('primecritere_process', $process)
            ;
        if($onlyActive)
            $this->db->where('primecritere_actif', 1)
                ->where('primemodecalcul_actif', 1)
                ;
        $query = $this->db->get('tr_primecritere');
        return $query->result();
    }

    public function getDataProductionByMonthAndYear(int $month, int $year) : Array
    {
        $this->db->select('SUM(mcp_quantite) AS nombre_prod', false)
            ->select('usr_id, usr_nom, usr_prenom')
            ->select('campagne_id, campagne_libelle')
            ->select('process_id, process_libelle')
            ->join('tr_user', 'mcp_agent = usr_id', 'inner')
            ->join('tr_campagne', 'mcp_campagne = campagne_id', 'inner')
            ->join('tr_process', 'mcp_process = process_id', 'inner')
            ->where('MONTH(mcp_date)', $month)
            ->where('YEAR(mcp_date)', $year)
            ->group_by('usr_id, campagne_id, process_id')
            ->order_by('usr_id ASC')
            ;
        $query = $this->db->get('t_missioncampagneprocess');
        return $query->result();
    }

    public function getDataProductionMcpByDate(string $date) : array
    {
        $this->db->select('SUM(mcp_quantite) AS nombre_prod', false)
            ->select('usr_id, usr_nom, usr_prenom')
            ->select('campagne_id, campagne_libelle')
            ->select('process_id, process_libelle')
            ->select('primeprofil_id, primeprofil_libelle')
            ->select('primecritere_id, primecritere_libelle, primecritere_modecalcul, primecritere_frequencecalcul, primecritere_objectif, primecritere_montant')
            ->join('tr_user', 'mcp_agent = usr_id', 'inner')
            ->join('tr_campagne', 'mcp_campagne = campagne_id', 'inner')
            ->join('tr_primeprofil', 'mcp_primeprofil = primeprofil_id AND mcp_campagne = primeprofil_campagne', 'inner')
            ->join('tr_process', 'mcp_process = process_id', 'inner')
            ->join('tr_primecritere', 'primecritere_campagne = campagne_id AND primecritere_profil = primeprofil_id AND primecritere_process = process_id')
            ->where('mcp_date', $date)
            ->group_by('usr_id, campagne_id, primeprofil_id, primecritere_id')
            ->order_by('usr_id ASC')
            ;
            return $this->db->get('t_missioncampagneprocess')->result();
        
    }

    public function getAtteinteJournaliereByDateAndUser(string $date, int $user) : array
    {
        $this->db
                ->select('DATE_FORMAT(primeatteintejour_day, "%d/%m/%Y") as primeatteintejour_day , primeatteintejour_objectif, primeatteintejour_tauxatteinte, primeatteintejour_nbproduction')
                ->select('usr_id, usr_nom, usr_prenom')
                ->select('primeprofil_id, primeprofil_libelle')
                ->select('primecritere_id, primecritere_libelle')
                ->select('campagne_id, campagne_libelle')
                ->select('process_id, process_libelle')
                ->join('tr_user', 'primeatteintejour_agent = usr_id', 'inner')
                ->join('tr_campagne', 'primeatteintejour_campagne = campagne_id', 'inner')
                ->join('tr_primeprofil', 'primeatteintejour_primeprofil = primeprofil_id', 'inner')
                ->join('tr_primecritere', 'primeatteintejour_critere = primecritere_id', 'inner')
                ->join('tr_process', 'primecritere_process = process_id', 'inner')
                ->where('primeatteintejour_agent', $user)
                ->where('MONTH(primeatteintejour_day)', date('m', strtotime($date)))
                ->where('primeatteintejour_day <=', $date)
                ->order_by('usr_id, campagne_id, primeprofil_id, process_id')
                ;

        $query = $this->db->get('t_primeatteintejournaliere');
        return $query->result();
    }

    public function getPrimeCriteriaByCampagne(int $campagneId, string $date) : Array
    {
        $this->db->select('primecritere_id, primecritere_libelle, primecritere_objectif, primecritere_modecalcul, primecritere_montant')
            ->select('primeprofil_id, primeprofil_libelle')
            ->select('primemodecalcul_id, primemodecalcul_libelle, primemodecalcul_calcul, primemodecalcul_action')
            ->select('primeobjectifjour_valeur')
            ->join('tr_primetypecritere','primecritere_typecritere = primetypecritere_id', 'inner')
            ->join('tr_primeprofil','primecritere_profil = primeprofil_id', 'inner')
            ->join('t_primeobjectifjournalier', 'primeobjectifjour_critere = primecritere_id AND primeobjectifjour_day = "' . $date . '"', 'left')
            ->where('primecritere_campagne', $campagneId)
            ;
        return $this->db->get('tr_primecritere')->result();    
    }

    public function savePrimeAtteinteJour(array $data) : int
    {
        $day = $data['primeatteintejour_day'];
        $critere = $data['primeatteintejour_critere'];
        $primeatteintejour = $this->getPrimeAtteinteJourByCritere($critere, $day);
        
        if($primeatteintejour !== false){
            $this->db->where('primeatteintejour_id', $primeatteintejour->primeatteintejour_id);
            $this->db->update('t_primeatteintejournaliere', $data);
        }else{
            $this->db->insert('t_primeatteintejournaliere', $data);
        }
        return $this->db->affected_rows();
    }

    public function getPrimeAtteinteJourByCritere(int $critere, string $day)
    {
        $this->db->select('primeatteintejour_id, primeatteintejour_day, primeatteintejour_critere, primeatteintejour_objectif, primeatteintejour_tauxatteinte, primeatteintejour_nbproduction')
            ->where('primeatteintejour_day', $day)
            ->where('primeatteintejour_critere', $critere)
            ;
        $query = $this->db->get('t_primeatteintejournaliere');
        return ($query->num_rows() > 0) ? $query->row() : false;
    }

    public function getCriteriaObjectifByDate(int $critere, string $date) : object
    {
        $this->db->select('primeobjectifjour_id, primeobjectifjour_day, primeobjectifjour_critere, primeobjectifjour_valeur')
            ->where('primeobjectifjour_day', $date)
            ->where('primeobjectifjour_critere', $critere)
            ;
        $query = $this->db->get('t_primeobjectifjournalier');
        return ($query->num_rows() > 0) ? $query->row() :  new stdClass();
    }

    public function getDataFromReachRateDaysMonth(int $month, int $year) : array
    {
        $this->db
            ->select('COUNT(primeatteintejour_id) as dayWorked', false)
            ->select('SUM(primeatteintejour_nbproduction) as nbProduction', false)
            //->select('(SUM(primeatteintejour_nbproduction / primecritere_objectif)) / COUNT(primeatteintejour_id) as tauxatteinte')
            ->select('SUM(primeatteintejour_nbproduction) / SUM(primeatteintejour_objectif) as tauxatteinte')
            ->select('SUM(primeatteintejour_objectif) as objectifs')
            ->select('primeatteintejour_condition')
            ->select('usr_id, usr_nom, usr_prenom, usr_username')
            ->select('primeprofil_id, primeprofil_libelle')
            ->select('campagne_id, campagne_libelle')
            ->select('primecritere_id, primecritere_frequencecalcul, primecritere_montant')
            ->select('primemodecalcul_calcul, primemodecalcul_action')
            ->select('process_id, process_libelle')
            ->join('tr_user', 'primeatteintejour_agent = usr_id', 'inner')
            ->join('tr_primeprofil', 'primeatteintejour_primeprofil = primeprofil_id', 'inner')
            ->join('tr_campagne', 'primeatteintejour_campagne = campagne_id', 'inner')
            ->join('tr_primecritere', 'primeatteintejour_critere = primecritere_id', 'inner')
            ->join('tr_process', 'primecritere_process = process_id', 'inner')
            ->join('tr_primemodecalcul', 'primecritere_modecalcul = primemodecalcul_id', 'inner')
            ->where('MONTH(primeatteintejour_day)', $month)
            ->where('YEAR(primeatteintejour_day)', $year)
            ->group_by('usr_id, campagne_id, primeprofil_id, primecritere_id')
            ->order_by('usr_id ASC, campagne_id ASC, primeprofil_id, primecritere_id')
            ;
        $query = $this->db->get('t_primeatteintejournaliere');
        //echo $this->db->last_query(); die;
        return $query->result();

    }

    public function getPrimeMonthData(int $month, int $year, array $users) : array
    {
        $this->db
            ->select('prime_id, prime_basemensuelle, prime_datecrea, prime_datemodif')
            ->select('campagne_libelle')
            ->select('usr_id, usr_nom, usr_prenom, CONCAT(usr_prenom, " ", usr_nom) as usr_nomcomplet')
            ->select('CONCAT(prime_annee, "/",prime_mois) as prime_date')
            ->select('(SELECT SUM(primeajustement_bonus) FROM t_primeajustement WHERE primeajustement_prime = prime_id AND primeajustement_validateur IS NOT NULL) as bonus', FALSE)
            ->select('(SELECT SUM(primeajustement_malus) FROM t_primeajustement WHERE primeajustement_prime = prime_id AND primeajustement_validateur IS NOT NULL) as malus', FALSE)
            ->join('tr_user', 'prime_user = usr_id', 'inner')
            ->join('tr_campagne', 'prime_campagne = campagne_id', 'inner')
            ->where('prime_mois ', $month)
            ->where('prime_annee', $year)
            ->where_in('prime_user', $users)
            
            ;
        $query = $this->db->get('t_prime');
        //echo $this->db->last_query(); die;
        return $query->result();
    }

    public function savePrimeAjustement($data) : bool
    {
        if($this->db->insert('t_primeajustement', $data)) return true;
        return false;
    }

    public function getPrimeAjustementByPrime(int $prime, bool $validated = false) : array
    {
        $this->db
            ->select('primeajustement_id, primeajustement_bonus, primeajustement_malus, primeajustement_commentaire, primeajustement_ajusteur, primeajustement_dateajustement')
            ->select('CONCAT(usr_prenom, " ", usr_nom) as ajusteur')
            ->join('tr_user', 'primeajustement_ajusteur = usr_id', 'inner')
            ->where('primeajustement_prime', $prime)
        ;
        if(!$validated){
            $this->db->where('primeajustement_validateur IS NULL', null, false);
        }
        $query = $this->db->get('t_primeajustement');
        return $query->result();
    }

    public function updatePrimeAjustement(int $id, array $data)
    {
        $this->db->where('primeajustement_id', $id);
        $this->db->update('t_primeajustement', $data);

        if($this->db->affected_rows()) return true;
        return false;
    }


    public function getAllBonus($onlyActive = false)
    {
        $this->db->select('primebonus_id, primebonus_libelle, primebonus_actif, primebonus_datecrea, primebonus_datemodif');
        
        if($onlyActive){
            $this->db->where('primebonus_actif', 1);

        }

        $query = $this->db->get('tr_primebonus');

        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }

}

     