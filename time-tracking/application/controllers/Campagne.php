<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campagne extends MY_Controller {
    /*
    * Afficher la liste des campagnes
    */
    public function listCampagne()
    {
        $header = ['pageTitle' => 'Gestion des campagnes - TimeTracking'];

        $this->load->model('site_model');
        $listSite = $this->site_model->getAllSite();

        $this->load->model('pole_model');
        $listPole = $this->pole_model->getAllPole();

        $this->load->model('proprio_model');
        $listProprio = $this->proprio_model->getAllProprio(true);

        $this->load->model('mission_model');
        $listMission = $this->mission_model->getAllMission(true);
        if(!$listMission) $listMission = [];

        $this->load->model('process_model');
        $listProcess = $this->process_model->getAllProcess(true);
        if(!$listProcess) $listProcess = [];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('campagne/listcampagne', []);
        $this->load->view('campagne/modalcampagne', array(
            'listSite'=> $listSite,
            'listPole'=> $listPole,
            'listProprio' => $listProprio,
            'listMission' => $listMission,
            'listProcess' => $listProcess,
         ));

        $this->load->view('common/footer', []);
    }
    /**
     * Prendre la liste des campagnes 
     */
    public function getListCampagne()
    {
        $this->load->model('campagne_model');
        $listCampagne = $this->campagne_model->getAllCampagne();
        if(!$listCampagne) $listCampagne = [];
        
        echo json_encode(array('data' => $listCampagne));
    }

    /**
     * Enregister une nouvelle campagne
     */
    public function saveNewCampagne()
    {
        //$header_data = $this->get_header_info();
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')

        {
            $info_campagne = $this->input->post();
            //load model
            $this->load->model('campagne_model');
            $inserted_campagne = $this->campagne_model->insertCampagne($info_campagne);

            //Si tt c'est bien passé, on commit la transaction sinon on fait un rollback
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                redirect('campagne/listCampagne?msg=error');
            }else{
                $this->db->trans_commit();
                redirect('campagne/listCampagne?msg=success');
            }

        }

        
    }
    /**
    *Prendre les infos d'une campagne
    */
    public function getInfocampagne(){

        $campagne_id = $this->input->post('campagne_id');

        if($campagne_id){
            $this->load->model('campagne_model');
            $info_campagne = $this->campagne_model->getCampagne($campagne_id); 
            if($info_campagne)
                echo json_encode(array('error' => false, 'info_campagne' => $info_campagne));
            else
                echo json_encode(array('error' => true));
            die();
        }   

        echo json_encode(array('error' => true));

    }

    /**
     * Enregister les informations modifiés d'une campagne
     */
    public function saveEditCampagne(){

        if($this->input->post() && $this->input->post('send_edit') && $this->input->post('send_edit') == 'sent')
        {
            $edit_campagne_data = $this->input->post();
            //var_dump($edit_user_data); die;

            //load model
            $this->load->model('campagne_model');
            $updated_campagne = $this->campagne_model->updateCampagne($edit_campagne_data);

            if ($updated_campagne){
                redirect('campagne/listCampagne?msg=succes');
            }else{
                redirect('campagne/listCampagne?msg=error');
            }

        }
    }


    /**
     * désactiver une campagne
     */

    public function desactivateCampagne()
    {


        $campagne_id = $this->input->post('campagneToDesactivate');
        
        if($campagne_id){
            $this->load->model('campagne_model');
            $is_desactivate = $this->campagne_model->desactivateCampagne($campagne_id);

            if($is_desactivate)
                redirect('campagne/listCampagne?msg=success');
            else
                redirect('campagne/listCampagne?msg=error');
        }
    }
    

    public function getAffectationCampagne()
    {
        $campagne_id = $this->input->post('campagne_id');
        $err = false;
        if($campagne_id){
            $this->load->model('campagne_model');

            $infoCampagne = $this->campagne_model->getCampagne($campagne_id);

            $listMission = $this->campagne_model->getListMissionCampagne($campagne_id);
            if(!$listMission) $listMission = [];
        }
        
        echo json_encode([
            'error' => $err,
            'info_campagne' => $infoCampagne,
            'affected_mission' => $listMission,
        ]);
    }

    public function getAffectationCampagneMission()
    {
        $campagne_id = $this->input->post('campagne_id');
        $mission_id = $this->input->post('mission_id');
        $err = false;
        if($campagne_id && $mission_id){
            $this->load->model('campagne_model');

            $list = $this->campagne_model->getAffectationCampagneMission($campagne_id, $mission_id);
            if(!$list) $list = [];
        }
        
        echo json_encode([
            'error' => $err,
            'affected_process' => $list,
        ]);
    }

    public function setAffectationCampagne()
    {
        $campagne_id = $this->input->post('campagne_id');
        $mission_id = $this->input->post('mission_id');
        $process_id = $this->input->post('process_id');
        $action = $this->input->post('action');

        if($campagne_id && $mission_id && $process_id && $action){
            $this->load->model('campagne_model');
            $data = [
                'affectationmcp_campagne' => $campagne_id,
                'affectationmcp_mission' => $mission_id,
                'affectationmcp_process' => $process_id,
            ];
            $return = false;
            if($action == 'set'){
                $data['affectationmcp_datecrea'] = date('Y-m-d H:i:s');
                $data['affectationmcp_datemodif'] = date('Y-m-d H:i:s');
                $return = $this->campagne_model->saveAffectationCampagne($data);
            }else if($action == 'unset'){
                $return = $this->campagne_model->removeAffectationCampagne($data);
            }
            if($return){
                $listProcess = $this->campagne_model->getAffectationCampagneMission($campagne_id, $mission_id);
                
                echo json_encode([
                    'error' => false,
                    'affected_process' => $listProcess,
                    
                ]);

            }

        }
    }

    public function getListUserCampagne()
    {
        $user = $this->input->get('user');
        $campagne = $this->input->get('campagne');
        $isPoids = $this->input->get('is_poids');
        $profil = $this->input->get('profil');
        $userPoids = false;
        if($isPoids){
            //On récupère le poids de l'user
            $this->load->model('user_model');
            $infoUser = $this->user_model->getUser($user);
            //var_dump($infoUser); die; 
            if($infoUser) $userPoids = $infoUser->role_poids; 
        } 
        if(!$user){
            echo json_encode([
                'error' => true,
                'data' => 'Erreur de traitement des données',
                
            ]);
            die;
        } 
        $this->load->model('campagne_model');
        
        $listUser = $this->campagne_model->getAllAgentCampagne($campagne, $userPoids);
        $listAffectedUsers = [];
        
        if($profil){
            $this->load->model('primeprofil_model');
            $listAffectedUsers = $this->primeprofil_model->getAffectationUPC($profil);
        }



        echo json_encode([
            'error' => false,
            'data' => $listUser,
            'affected' => $listAffectedUsers 
        ]);
            
    }
}