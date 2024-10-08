<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/RestController.php');
use chriskacerguis\RestServer\RestController;

class Apiprimecritere extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function typecritere_get(){
        $this->load->model("Primecritere_model");
        $listTypecritere = $this->Primecritere_model->getallcritere();

        $successData = [
            'status' => true,
            'data' => $listTypecritere,
            'message' => 'Liste des types critère récupérée'
        ];

        $this->response($successData, 200);


        
    }
    
    public function frequencecritere_get(){
        $this->load->model("Primecritere_model");
        $listeFrequenceCritere = $this->Primecritere_model->getallfrequence();

        $successData = [
            'status' => true,
            'data' => $listeFrequenceCritere,
            'message' => 'Liste des frequences récupérée'
        ];

        $this->response($successData, 200);
   
    }

    public function critere_post(){
        $action = $this->input->post('');
        $this->response($action, 200);

        
    }

    public function getallprimemodecalcul_get()
    {
        $this->load->model('Primecritere_model');
        $listprimemodecalculmodel = $this->Primecritere_model->getallmodecalculprime();

        $successData = [
            'status' => true,
            'data' => $listprimemodecalculmodel,
            'message' => 'Liste des modes de calcul réussite'
        ];
        $this->response($successData, 200);


    }

    public function primecritere_get($idcampagne)
    {
        $this->load->model("Primecritere_model");
        $listDonnne = $this->Primecritere_model->getallprimecriterebycampagne($idcampagne);
        $this->response($listDonnne, 200);

    }
    public function primecritereprofilbycampagne_get($idcampagne)
    {
        $this->load->model("Primecritere_model");
        $listDonnne = $this->Primecritere_model->getallprofilbycampagne($idcampagne);
        
        $successData = [
            'status' => true,
            'data' => $listDonnne,
            'message' => 'Liste des frequences récupérée'
        ];

        $this->response($successData, 200);

    }

    public function primeprofilprocess_get($profil = false, $campagne = false)
    {
        if(!$profil){
            $this->response([
                'status' => false,
                'message' => 'profil requis'
            ], 400);
        }
        if(!$campagne){
            $this->response([
                'status' => false,
                'message' => 'campagne requise'
            ], 400);
        }
        $this->load->model('Primecritere_model');
        $listProcess = $this->Primecritere_model->getListProfilProcess($profil, $campagne);
        //echo $this->db->last_query(); die;
        if(!$listProcess){
            $this->response([
                'status' => false,
                'message' => 'Erreur lors de la récupération de la liste des process'
            ], 400);
        }
        $successData = [
            'status' => true,
            'data' => $listProcess,
            'message' => 'Liste des process récupérée'
        ];

        $this->response($successData, 200);
    }

}  
   

