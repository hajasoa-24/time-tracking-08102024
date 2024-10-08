<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dayprime_lib {

    protected $CI;
    private $_day
            ;
    private $_prime;

    public function __construct(Array $params)
    {
        
        $this->CI =& get_instance();
        $this->_day = $params['day'];
        //Load models
        $this->CI->load->model('primeproduction_model');

        $this->_prime = new stdClass();
        $this->_prime->day = $this->_day;
        $this->_prime->data = [];
    }

    public function calculatePrimeBaseCampagne(Object $campagne) : Void
    {
        $campagneId = $campagne->campagne_id;
        $day = $this->_day->format('Y-m-d');

        //get all criteria as base type
        $listProductionCampagne = $this->CI->primeproduction_model->getCampagneDayProduction($campagneId, $day);
        if($listProductionCampagne === false) $listProductionCampagne = [];

        if(!empty($listProductionCampagne)){
            foreach($listProductionCampagne as $productionCampagne){
                $currentUser = $productionCampagne->usr_id;
                $processId = $productionCampagne->process_id;
                //Init
                if(!isset($this->_prime->data[$currentUser])){
                    $this->_prime->data[$currentUser] = new stdClass();
                    $this->_prime->data[$currentUser]->campagne = [];
                } 
                if(!isset($this->_prime->data[$currentUser]->campagne[$campagneId])){
                    $newObjectCampagne = new stdClass();
                    $newObjectCampagne->process = [];
                    $newObjectCampagne->campagne_details = $campagne;
                    $this->_prime->data[$currentUser]->campagne[$campagneId] = $newObjectCampagne;
                }
                if(!isset($this->_prime->data[$currentUser]->campagne[$campagneId]->process[$processId])){
                    $newObjectProcess = new stdClass();
                    $newObjectProcess->qte = 0;
                    $newObjectProcess->ca = 0;
                    $newObjectProcess->process_libelle = $productionCampagne->process_libelle;
                    $this->_prime->data[$currentUser]->campagne[$campagneId]->process[$processId] = $newObjectProcess;
                }
                $currentProcess = $this->_prime->data[$currentUser]->campagne[$campagneId]->process[$processId];
                $currentProcess->qte += $productionCampagne->mcp_quantite;
                $currentProcess->ca += $productionCampagne->mcp_ca;

                $this->_prime->data[$currentUser]->campagne[$campagneId]->process[$processId] = $currentProcess;

            }
        }
        $this->_applyObectif();
    }

    private function _applyObectif() : Void
    {
        $listProduction = $this->_prime->data;
        foreach($listProduction as $currentUser => $productionAgent){

            foreach($productionAgent->campagne as $currentCampagne => $productionCampagneAgent){

                foreach($productionCampagneAgent->process as $currentProcess => $processAgent){

                    $listCritereCampagneProcess = $this->CI->primeproduction_model->getPrimeCritereByCampagneAndProcess($currentCampagne, $currentProcess);
                    //var_dump($listCritereCampagneProcess);
                    if($listCritereCampagneProcess !== false){
                        $this->_prime->data[$currentUser]->campagne[$currentCampagne]->process[$currentProcess]->critere = $listCritereCampagneProcess;
                    }
                }
            }
        }
    }

    public function getDayPrime() : Object
    {
        return $this->_prime;
    }

}