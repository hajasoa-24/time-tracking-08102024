<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Primeprofil extends MY_Controller {
    /*
    * Afficher la liste des profils
    */
    public function list()
    {
        $header = ['pageTitle' => 'Gestion des profils des primes - TimeTracking'];
        //Recuperation de la liste des campagnes de l'user connecté
        $this->load->model('campagne_model');
        $cUser = $this->_userId;
        $listCampagneUser = $this->campagne_model->getUserCampagne($cUser);

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('primeprofil/list', ['user' => $cUser, 'listCampagneUser' => $listCampagneUser]);

        $this->load->view('common/footer', []);
    }


    /**
     * Prendre la liste des profils 
     */
    public function getListprofil()
    {
        $this->load->model('primeprofil_model');
        $listProfil = $this->primeprofil_model->getAllProfil();
        if(!$listProfil) $listProfil = [];

        
        echo json_encode(array('data' => $listProfil));
    }

    public function saveNewPrimeProfil()
    {
        $data = $this->input->post();
        
        if($data && isset($data['save_primeprofil']) && $data['save_primeprofil'] == 'sent'){
            $this->load->model('primeprofil_model');
            $lastProfilId = $this->primeprofil_model->getLastPrimeProfilIdByCampagne($data['primeprofil_campagne']);
            $data['primeprofil_id'] = $lastProfilId + 1;

            $this->db->trans_begin();
            $newProfilId = $this->primeprofil_model->insertProfil($data);
            if($newProfilId) {
                //Affectation des agents
                $entry = [];
                foreach($data['primeaffectationupc_user'] as $usr){
                    $entry[] = [
                        'primeaffectationupc_user' => $usr,
                        'primeaffectationupc_profil' => $newProfilId,
                        'primeaffectationupc_campagne' => $data['primeprofil_campagne'],
                        'primeaffectationupc_datecrea' => date('Y-m-d H:i:s'),
                        'primeaffectationupc_datemodif' => date('Y-m-d H:i:s')
                    ];
                }
                $this->primeprofil_model->setAffectationUPC($entry, $newProfilId, $data['primeprofil_campagne']);

                $entry = [];
                if(isset($data['primeprofilprocess_process']) && !empty($data['primeprofilprocess_process'])){
                    foreach($data['primeprofilprocess_process'] as $process){
                        $entry[] = [
                            'primeprofilprocess_profil' => $newProfilId,
                            'primeprofilprocess_campagne' => $data['primeprofil_campagne'],
                            'primeprofilprocess_process' => $process,
                            'primeprofilprocess_datecrea' => date('Y-m-d H:i:s'),
                            'primeprofilprocess_datemodif' => date('Y-m-d H:i:s')
                        ];
                    }
                    $this->primeprofil_model->setProfilProcess($entry, $newProfilId, $data['primeprofil_campagne']);
                }
                $this->db->trans_commit();
                $this->session->set_flashdata('msg', "Profil ajouté");
                redirect('primeprofil/list');
            }
            $this->db->trans_rollback();
        }
        $this->session->set_flashdata('msg', "Erreur survenu!");
        redirect('primeprofil/list');
    }

    public function saveEditPrimeProfil()
    {
        $data = $this->input->post();
        if($data && isset($data['save_editprimeprofil']) && $data['save_editprimeprofil'] == 'sent'){
            $this->load->model('primeprofil_model');

            $this->db->trans_begin();

            $isUpdated = $this->primeprofil_model->updateProfil($data);
            if($isUpdated) {
                //Affectation des agents
                $entry = [];
                if(isset($data['edit_primeaffectationupc_user']) && !empty($data['edit_primeaffectationupc_user'])){

                    foreach($data['edit_primeaffectationupc_user'] as $usr){
                        $entry[] = [
                            'primeaffectationupc_user' => $usr,
                            'primeaffectationupc_profil' => $data['edit_primeprofil_id'],
                            'primeaffectationupc_campagne' => $data['edit_primeprofil_campagne'],
                            'primeaffectationupc_datecrea' => date('Y-m-d H:i:s'),
                            'primeaffectationupc_datemodif' => date('Y-m-d H:i:s')
                        ];
                    }
                    $this->primeprofil_model->setAffectationUPC($entry, $data['edit_primeprofil_id'], $data['edit_primeprofil_campagne']);
                }

                $entry = [];
                if(isset($data['edit_primeprofilprocess_process']) && !empty($data['edit_primeprofilprocess_process'])){
                    foreach($data['edit_primeprofilprocess_process'] as $process){
                        $entry[] = [
                            'primeprofilprocess_profil' => $data['edit_primeprofil_id'],
                            'primeprofilprocess_campagne' => $data['edit_primeprofil_campagne'],
                            'primeprofilprocess_process' => $process,
                            'primeprofilprocess_datecrea' => date('Y-m-d H:i:s'),
                            'primeprofilprocess_datemodif' => date('Y-m-d H:i:s')
                        ];
                    }
                    $this->primeprofil_model->setProfilProcess($entry, $data['edit_primeprofil_id'], $data['edit_primeprofil_campagne']);
                }
                $this->db->trans_commit();
                
                redirect('primeprofil/list?msg=success');
            }
        }
        //$this->session->set_flashdata('msg', "Erreur survenu lors de l'enregistrement!");
        redirect('primeprofil/list?msg=err');
    }

    public function desactivateProfil()
    {
        $data = $this->input->post();
        if($data && isset($data['profilToDesactivate'])  
                    && $data['profilToDesactivate'] != ''
                    && isset($data['sendDesactivate']) 
                    && $data['sendDesactivate'] == 'sent'){

            $this->load->model('primeprofil_model');
            $isUpdated = $this->primeprofil_model->desactivateProfil($data['profilToDesactivate']);
            if($isUpdated) {
                //$this->session->set_flashdata('msg', "Profil ajouté");
                redirect('primeprofil/list?msg=success');
            }
        }
        //$this->session->set_flashdata('msg', "Erreur survenu lors de l'enregistrement!");
        redirect('primeprofil/list?msg=err');
    }

    public function getInfoprofil()
    {
        $data = $this->input->get();
        $jsonData = ['error' => true, 'data' => 'Erreur de traitement des données'];
        if($data && isset($data['id'])){
            $this->load->model('primeprofil_model');
            $dataProfil = $this->primeprofil_model->getProfil($data['id']);
            if(!$dataProfil) 
                $jsonData = ['error' => true, 'data' => 'Erreur de recuperation de données'];
            else
                $jsonData = ['error' => false, 'data' => $dataProfil];
        }
        echo json_encode($jsonData);
    }
    
    

    

}