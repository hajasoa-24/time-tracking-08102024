<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Retard extends MY_Controller {
    
     /**
     * Afficher l'historique des retards
     */
    public function histoRetards()
    {

        $filtreHistoRetard = $this->session->userdata('filtre_historetard');

        $filtreHistoRetardDu = (isset($this->session->userdata('filtreHistoRetard')['debut'])) ? $this->session->userdata('filtreHistoRetard')['debut'] : date('Y-m-d', strtotime(' -7 days'));
        $filtreHistoRetardAu = (isset($this->session->userdata('filtreHistoRetard')['fin'])) ? $this->session->userdata('filtreHistoRetard')['fin'] : date('Y-m-d');

        $currentFiltreHistoRetardDu = $this->input->get('filtreHistoRetardDu');
        $currentFiltreHistoRetardAu = $this->input->get('filtreHistoRetardAu');

        if($currentFiltreHistoRetardDu){
            $filtreHistoRetardDu = $currentFiltreHistoRetardDu;
            
        }
        if($currentFiltreHistoRetardAu){
            $filtreHistoRetardAu = $currentFiltreHistoRetardAu;
        }

        $filtreHistoRetard = ['debut' => $filtreHistoRetardDu, 'fin' => $filtreHistoRetardAu];

        $this->session->set_userdata('filtre_historetard', $filtreHistoRetard);

        $header = ['pageTitle' => 'Historique des retards - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('histo/historetards', ['filtreHistoRetardDu' => $filtreHistoRetardDu, 'filtreHistoRetardAu' => $filtreHistoRetardAu]);
        $this->load->view('common/footer', []);
    }

    public function getUsersHistoriqueRetard()
    {
        $filtre = $this->session->userdata('filtre_historetard');
        
        $this->load->model('campagne_model');
        $this->load->model('service_model');
        $this->load->model('user_model');
        
        $userPoids = $this->session->userdata('user')['poids'];
        $filtre['user_poids'] = $userPoids;

        //On définit une valeur par défaut des filtres de date si non définit
        if(!isset($filtre['debut']) || (isset($filtre['debut']) && empty($filtre['debut']))){
            $filtre['debut'] = date('Y-m-d', strtotime('first day of this month'));
        }
        if(!isset($filtre['fin']) || (isset($filtre['fin']) && empty($filtre['fin']))){
            $filtre['fin'] = date('Y-m-d', strtotime('last day of this month'));
        }

        $filtre['list_campagne'] = $filtre['list_service'] = [];
        $currentSup = $this->session->userdata('user')['id'];
        
        $listCampagneSup = $this->campagne_model->getUserCampagne($currentSup);
        if($listCampagneSup){
            foreach($listCampagneSup as $campagne){
                $filtre['list_campagne'][] = $campagne->campagne_id;
            }
        }
        $listServiceSup = $this->service_model->getUserService($currentSup);
        if($listServiceSup){
            foreach($listServiceSup as $service){
                $filtre['list_service'][] = $service->service_id;
            }
        }
        //Récupérer les users apaprtenant aux campagnes et services
        //var_dump($filtre); die;
        $this->load->model('retard_model');
        $listRetards = $this->retard_model->getlistRetard($filtre);
        //Si données récupérés, on boucle pour former le tableau
        if($listRetards){
            echo json_encode(['data' => $listRetards]);
        }else{
            echo json_encode(['data' => []]);
        }

        
    }

    public function getHistoretardsDetails(){
        
        $data = $this->input->post();
        if($data && isset($data['user']) && $data['user'] != ''){
            $user = $data['user'];
            //Récupérer les données
            $filtre = $this->session->userdata('filtre_historetard');
            if($filtre && isset($filtre['debut']) && isset($filtre['fin'])){
                $this->load->model('retard_model');
                $retardsDetails = $this->retard_model->getRetardsDetails($user, $filtre['debut'], $filtre['fin']);
                if($retardsDetails){
                    echo json_encode(['err' => false, 'data' => $retardsDetails]);
                    die;
                } 
            }
        }
        echo json_encode(['err' => true, 'data' => []]);
    }
   

    

}