<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mission extends MY_Controller {
    /*
    * Afficher la liste des missions
    */
    public function listMission()
    {
        $header = ['pageTitle' => 'Gestion des missions - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('mission/listmission', []);

        $this->load->view('common/footer', []);
    }
    /**
     * Prendre la liste des missions 
     */
    public function getListMission()
    {
        $this->load->model('mission_model');
        $listMission = $this->mission_model->getAllMission();
        if(!$listMission) $listMission = [];
        
        echo json_encode(array('data' => $listMission));
    }

    /**
     * Enregister une nouvelle mission
     */
    public function saveNewMission()
    {
        
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')

        {
            $info_mission = $this->input->post();
            //load model
            $this->load->model('mission_model');
            $inserted_mission = $this->mission_model->insertMission($info_mission);

            //Si tt c'est bien passé, on commit la transaction sinon on fait un rollback
            if ($inserted_mission === FALSE){
                redirect('mission/listmission?msg=error');
            }else{
                $this->db->trans_commit();
                redirect('mission/listmission?msg=success');
            }

        }

        
    }
    /**
    *Prendre les infos d'une mission
    */
    public function getInfoMission(){

        $mission_id = $this->input->post('mission_id');

        if($mission_id){
            $this->load->model('mission_model');
            $info_mission = $this->mission_model->getMission($mission_id); 
            if($info_mission)
                echo json_encode(array('error' => false, 'info_mission' => $info_mission));
            else
                echo json_encode(array('error' => true));
            die();
        }   

        echo json_encode(array('error' => true));

    }

    /**
     * Enregister les informations modifiés d'une mission
     */
    public function saveEditMission(){

        if($this->input->post() && $this->input->post('send_edit') && $this->input->post('send_edit') == 'sent')
        {
            $edit_mission_data = $this->input->post();

            //load model
            $this->load->model('mission_model');
            $updated_mission = $this->mission_model->updateMission($edit_mission_data);

            if ($updated_mission){
                redirect('mission/listmission?msg=succes');
            }else{
                redirect('mission/listmission?msg=error');
            }

        }
    }

    /**
     * désactiver une mission
     */

    public function desactivateMission()
    {


        $mission_id = $this->input->post('missionToDesactivate');
        
        if($mission_id){
            $this->load->model('mission_model');
            $is_desactivate = $this->mission_model->desactivateMission($mission_id);

            if($is_desactivate)
                redirect('mission/listmission?msg=success');
            else
                redirect('mission/listmission?msg=error');
        }
    }

}