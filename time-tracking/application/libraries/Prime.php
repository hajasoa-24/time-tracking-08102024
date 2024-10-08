<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Prime {

    protected $CI;
    private  $_day,
            $_month,
            $_year,
            $_date,
            $_firstDateMonth,
            $_endDateMonth,
            $_totalDaysWorkedMonth
            ;
    private $_prime;

    public function __construct(Array $params)
    {
        
        $this->CI =& get_instance();
        $this->_day = isset($params['day']) ? $params['day'] : false;
        $this->_month = $params['month'];
        $this->_year = $params['year'];
        $this->_date = new DateTime($this->_year . '-' . $this->_month . '-' .$this->_day);
        $this->_firstDateMonth = new DateTime($this->_year.'-'.$this->_month.'-01');
        $this->_endDateMonth = (clone $this->_firstDateMonth)->modify('last day of this month');
        $listHolidaysFromDb = $this->getMonthHolydays($this->_month, $this->_year);
        $holidays = [];
        foreach($listHolidaysFromDb as $holiday){
            $holidays[] = $holiday->holidays_date;
        }
        $this->_totalDaysWorkedMonth = $this->getWorkingDays($this->_month, $this->_year, $holidays);
        
        //Load models
        $this->CI->load->model('primeproduction_model');
    }

    public function calculatePrimeMonth() : void
    {
        $this->CI->load->model('presence_model');

        $listAtteinteMois = $this->CI->primeproduction_model->getDataFromReachRateDaysMonth($this->_month, $this->_year);
        
        $dataPrimeAgent = [];
        
        foreach($listAtteinteMois as $atteinteAgentByCritere)
        {
            $agent = $atteinteAgentByCritere->usr_id;
            $campagne = $atteinteAgentByCritere->campagne_id;
            $daysWorkedProd = $atteinteAgentByCritere->dayWorked;
            $reachRate = $atteinteAgentByCritere->tauxatteinte;
            $nbProduction = $atteinteAgentByCritere->nbProduction;
            $base = $atteinteAgentByCritere->primecritere_montant;
            $calculprimeAction = $atteinteAgentByCritere->primemodecalcul_action;
            $condition = $atteinteAgentByCritere->primeatteintejour_condition;
            
            $agentCampagne = $agent . '_' . $campagne;
            if(!isset($dataPrimeAgent[$agentCampagne])){
                $dataPrimeAgent[$agentCampagne] = [
                    'prime_user' => $agent,
                    'prime_campagne' => $campagne,
                    'prime_mois' => $this->_month,
                    'prime_annee' => $this->_year,
                    'prime_basemensuelle' => 0,
                    'prime_datecrea' => $this->_date->format('Y-m-d H:i:s'),
                    'prime_datemodif' => $this->_date->format('Y-m-d H:i:s')
                ];
            }
            $tempLoopData = $dataPrimeAgent[$agentCampagne];

            $paramsCalculPrime = [
                'numberProd' => $nbProduction,
                'base' => $base,
                'totalDaysWorked' => $this->_totalDaysWorkedMonth,
                'daysProd' => $daysWorkedProd,
                'reachRate' => $reachRate,
                'condition' => $condition
            ];
            if($calculprimeAction){
                $newBaseMensuelle = $this->$calculprimeAction($paramsCalculPrime);
                $currentBaseMensuelle = $tempLoopData['prime_basemensuelle'];
                $tempLoopData['prime_basemensuelle'] =  floatval($currentBaseMensuelle) + floatval($newBaseMensuelle);
                $dataPrimeAgent[$agentCampagne] = $tempLoopData;
            } 
            
        }
        $this->_prime = $dataPrimeAgent;
        
    }

    public function savePrime() : bool
    {
        $this->CI->load->model('prime_model');
        $this->CI->db->trans_begin();
        foreach($this->_prime as $primeAgent)
        {
            var_dump($primeAgent);
            $this->CI->prime_model->setUser($primeAgent['prime_user']);
            $this->CI->prime_model->setCampagne($primeAgent['prime_campagne']);
            $this->CI->prime_model->setMois($primeAgent['prime_mois']);
            $this->CI->prime_model->setAnnee($primeAgent['prime_annee']);
            $this->CI->prime_model->setBaseMensuelle($primeAgent['prime_basemensuelle']);
            $this->CI->prime_model->save();
        }
        if ($this->CI->db->trans_status() === FALSE)
        {
            $this->CI->db->trans_rollback();
            return false;
        }
        $this->CI->db->trans_commit();
        return true;
    }

    public function getPrime() : array
    {
        return $this->_prime;
    }

    protected function getReachRate($target, $achieved) : float
    {

        return  round(($achieved / $target), 4);
    }

    protected function getMonthHolydays(int $month, int $year) : array
    {
        $this->CI->load->model('conges_model');
        $holidays = $this->CI->conges_model->getMonthHolydays($month, $year);
        //echo $this->db->last_query(); die;
        if(!$holidays) $holidays = [];
        return $holidays;
    }

    protected function getWorkingDays(int $month, int $year, array $holidays = []) : int 
    {
        $nbDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $nbWorkingDays = 0;
        for ($day = 1; $day <= $nbDays; $day++) {
            $timestamp = mktime(0, 0, 0, $month, $day, $year);
            if (date('N', $timestamp) < 6 && !in_array(date('Y-m-d', $timestamp), $holidays)) {
                $nbWorkingDays++;
            }
        }
        return $nbWorkingDays;
    }

    public function calculatePrimeReachDay() : void
    {
        $this->CI->load->model('presence_model');
        $this->CI->load->model('campagne_model');
        $this->CI->load->model('mission_model');
        $this->CI->load->model('process_model');
        $currentDate = $this->_date->format('Y-m-d');
        $listDataProductionDay = $this->CI->primeproduction_model->getDataProductionMcpByDate($currentDate, TYPECRITERE_BASEPRIME);
        
        foreach($listDataProductionDay as $dataProductionDay)
        {
            $dataPrimeAtteinteJour = [
                'primeatteintejour_day' => $currentDate,
                'primeatteintejour_campagne' => $dataProductionDay->campagne_id,
                'primeatteintejour_primeprofil' => $dataProductionDay->primeprofil_id,
                'primeatteintejour_agent' => $dataProductionDay->usr_id,
                'primeatteintejour_critere' => $dataProductionDay->primecritere_id,
                'primeatteintejour_condition' => $dataProductionDay->primecritere_objectif,
                'primeatteintejour_nbproduction' => $dataProductionDay->nombre_prod,
                'primeatteintejour_datecrea' => date('Y-m-d H:i:s'),
                'primeatteintejour_datemodif' => date('Y-m-d H:i:s')
            ];
            $objectifDay = $this->CI->primeproduction_model->getCriteriaObjectifByDate($dataProductionDay->primecritere_id, $currentDate);
            $primeCritereOjectif = 0;
            
            if($decodedObjectif = json_decode($dataProductionDay->primecritere_objectif))
            {
                $primeCritereOjectif = $decodedObjectif->objectif;
            }
            $objectif = (isset($objectifDay->primeobjectifjour_valeur)) ? $objectifDay->primeobjectifjour_valeur : $primeCritereOjectif;
            $dataPrimeAtteinteJour['primeatteintejour_objectif'] = $objectif; 
            $reachRate = $this->getReachRate($objectif, $dataProductionDay->nombre_prod);
            $dataPrimeAtteinteJour['primeatteintejour_tauxatteinte'] = $reachRate;
            $this->CI->primeproduction_model->savePrimeAtteinteJour($dataPrimeAtteinteJour);
            
        }

    }

    public function calculateBonusMalus()
    {
        $listAjustement = $this->CI->primeproduction_model->getPrimeAjustementByMonthAndYear($this->_month, $this->_year);
        //var_dump($listAjustement);
        foreach($listAjustement as $ajustement)
        {
            $user = $ajustement->prime_user;
            $campagne = $ajustement->prime_campagne;
            $data = ["prime_totalbonus" => $ajustement->bonus, "prime_totalmalus" => $ajustement->malus, "prime_datemodif" => date('Y-m-d H:i:s')];
            $this->CI->primeproduction_model->updatePrimeByUserAndCampagne($user, $campagne, $data);
        }
    }

    public function calculateQualite()
    {
        $this->CI->load->model('primecritere_model');
        //prendre les critères de type qualite et boucler dessus

        $dataQualiteAgent = [];
        $nbError = 0;
        $listQualite = $this->CI->primeproduction_model->getQualiteByMonthAndYear($this->_month, $this->_year);
        foreach($listQualite as $qualite)
        {
            $user = $qualite->primequalite_user;
            $campagne = $qualite->primequalite_campagne;
            $typecritere = $qualite->primequalite_typecritere;
            $mois = $qualite->primequalite_mois;
            $annee = $qualite->primequalite_annee;
            $reachRate = floatval($qualite->primequalite_note) / 100;

            $daysWorkedProd = $this->CI->primeproduction_model->getUserDayWorkedProdMonth($user, $mois, $annee);

            $listCritere = $this->CI->primecritere_model->getprimecriterebyType($typecritere);

            $agentCampagne = $user . '_' . $campagne;
            if(!isset($dataQualiteAgent[$agentCampagne])){
                $dataQualiteAgent[$agentCampagne] = [
                    'prime_user' => $user,
                    'prime_campagne' => $campagne,
                    'prime_mois' => $this->_month,
                    'prime_annee' => $this->_year,
                    'prime_montantqualite' => 0,
                    'prime_datecrea' => $this->_date->format('Y-m-d H:i:s'),
                    'prime_datemodif' => $this->_date->format('Y-m-d H:i:s')
                ];
            }
            $tempLoopData = $dataQualiteAgent[$agentCampagne];

            foreach($listCritere as $critere)
            {
                
                $action = $critere->primemodecalcul_action;
                $paramsCalculPrime = [
                    'base' => $critere->primecritere_montant,
                    'totalDaysWorked' => $this->_totalDaysWorkedMonth,
                    'daysProd' => $daysWorkedProd,
                    'reachRate' => $reachRate,
                    'condition' => $critere->primecritere_objectif
                ];
                
                if($action){
                    $newTotalQualite = $this->$action($paramsCalculPrime);
                    $currentTotalQualite = $tempLoopData['prime_montantqualite'];
                    $tempLoopData['prime_montantqualite'] =  floatval($currentTotalQualite) + floatval($newTotalQualite);
                    $dataQualiteAgent[$agentCampagne] = $tempLoopData;
                } 

            }
        }

        if(!empty($dataQualiteAgent)){
            $nbError = $this->CI->primeproduction_model->savePrimeQualite($dataQualiteAgent);
        }
        return $nbError;
    }

    public function updatePrimeMontantPercu()
	{
        $this->CI->primeproduction_model->updatePrimeMontantPercu($this->_month, $this->_year);
	}

    /**
     * BEGIN CALCUL PRIME ACTIONS
     * ALL implemented Actions for Prime should use the same IN OUT
     */

     /**
      * PrimeSelonAtteint : Taux d'atteinte x base x jours de prod / jours travaillé mois
      * Taux d'atteinte (reachRate) : pourcentage par rapport à l'objectif fixé 
      * base : le montant par defaut
      * jours de prod (daysProd) : nombre de jours où l'agent a travaillé pour la campagne (client)
      * jours travaillé mois (totalDaysOfWorkOfTheMonth) : nombre de jours ouvrés du mois
      *   
      */
    protected function applyPrimeSelonAtteint(Array $params) : float
    {
        $reachRate = $params['reachRate'];
        $base = $params['base'];
        $daysProd = $params['daysProd'];
        $totalDaysOfWorkOfTheMonth = $params['totalDaysWorked'];

        return (is_numeric($totalDaysOfWorkOfTheMonth) && $totalDaysOfWorkOfTheMonth > 0) 
                ? round(($reachRate * $base * $daysProd) / $totalDaysOfWorkOfTheMonth, 2) 
                : 0;
    }

    /**
     * PrimeSelonAtteintSansProrataJoursTravailles : taux d'atteinte x base
     */
    protected function applyPrimeSelonAtteintSansProrataJoursTravailles(Array $params) : float
    {
        $reachRate = $params['reachRate'];
        $base = $params['base'];
        return round(($reachRate * $base), 2);
    }

    /**
     * ProrataSurJoursTravailles : base x jours de prod / jours travaillés mois (on ne tient pas compte du taux d'atteinte prod)
     */
    protected function applyProrataSurJoursTravailles(array $params) : float
    {
        $base = $params['base'];
        $daysProd = $params['daysProd'];
        $totalDaysOfWorkOfTheMonth = $params['totalDaysWorked'];

        return (is_numeric($totalDaysOfWorkOfTheMonth) && $totalDaysOfWorkOfTheMonth > 0)
                ? round(($base * $daysProd) / $totalDaysOfWorkOfTheMonth, 2)
                : 0;
    }

    /**
     * PrimeParPalier : Prime perçu par condition séparés par des '|'  
     * tauxatteint selon palier x base x nb jrs prod / nb jours travaillés mois
     */
    protected function applyPrimeParPalier(Array $params) : float
    {
        //$numberProd = $params['numberProd'];
        $condition = $params['condition'];
        $reachRate = $params['reachRate'];
        $base = $params['base'];
        $daysProd = $params['daysProd'];
        $totalDaysOfWorkOfTheMonth = $params['totalDaysWorked'];
        //var_dump($condition);
        if($decodedCondition = json_decode($condition))
        {
            $paliers = $decodedCondition->palier;
            $multiplier = 0;
            foreach($paliers as $palier)
            {
                
                $checkOperator = $palier->operator;
                $checkCondition = floatval($palier->condition) / 100;
                $checkValue = floatval($palier->value);
                //var_dump($reachRate, $checkOperator, $checkCondition);
                if($checkOperator == '<' && ($reachRate < $checkCondition))
                {
                    $multiplier = $checkValue / 100;
                    break;
                }
                else if($checkOperator == '<=' && ($reachRate <= $checkCondition))
                {
                    $multiplier = $checkValue / 100;
                    break;
                }
                else if($checkOperator == '>' && ($reachRate > $checkCondition))
                {
                    $multiplier = $checkValue / 100;
                    break;
                }
                else if($checkOperator == '>=' && ($reachRate >= $checkCondition))
                {
                    $multiplier = $checkValue / 100;
                    break;
                }
                else if($checkOperator == '=' && ($reachRate == $checkCondition))
                {
                    $multiplier = $checkValue / 100;
                    break;
                }
                
            }
            var_dump($multiplier , $base, $daysProd , $totalDaysOfWorkOfTheMonth);
            return (is_numeric($totalDaysOfWorkOfTheMonth) && $totalDaysOfWorkOfTheMonth > 0)
                ? $multiplier * $base * $daysProd / $totalDaysOfWorkOfTheMonth
                : 0
                ;
        }
        return 0;
    }

    /**
     * PrimeConditionUnique : Prime décrochée dès que la condition donnée est réalisée. 
     * Le montant est proratisé sur le nb de jours travaillés
     */

     protected function applyPrimeConditionUnique(array $params) : float
     {
        
     }

     /**
      * PrimeACondiitionMultiple : Prime obtenue seulement après réalisation de 2 ou plusieurs conditions. 
      Si l'une des conditions ne se réalise pas, l'agent est malusé sur tout ou partie de sa prime sur le critère donné. 
      La prime est ensuite proratisée sur le nb de jrs de prod. 
      (ex : si prod 100% ET si DMT <5min ET nb d'erreur = 0, l'agent perçoit 100% de la base prod. 
      Dès que la 1ere condition n'est pas réalisée, il est à 0, si la première se réalise mais les 2 autres non, il est à 0, 
      si la 1ere et la 3e se réalisent mais la 2e non, il ne gagne que 50% de la prime, etc. 
      Il faudra ainsi prévoir tous les scenarii possibles et le résultat prévu dans chaque cas). 
      */
      protected function applyPrimeConditionMultiple(array $params) : float
     {

     }

     /**
      * PrimeALunite : Un montant fixe est accordé pour chaque unité réalisée (vente, contrat, etc.). Prime = nb unité x montant fixe. Dans ce cas, il n'y a pas de prorata sur le nb de jours travaillés
      */
      protected function applyPrimeUnite(array $params) : float
      {
        
      }

    /* ========== END CALCUL PRIME ACTIONS ===============*/
}