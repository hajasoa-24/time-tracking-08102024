<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Process extends MY_Controller {
    /*
    * Afficher la liste des process
    */
    public function listProcess()
    {
        $header = ['pageTitle' => 'Gestion des process - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('process/listprocess', []);

        $this->load->view('common/footer', []);
    }
    /**
     * Prendre la liste des process 
     */
    public function getListProcess()
    {
        $this->load->model('process_model');
        $listProcess = $this->process_model->getAllProcess();
        if(!$listProcess) $listProcess = [];
        
        echo json_encode(array('data' => $listProcess));
    }

    public function getListProcessCampagne($campagne, $profil = false)
    {

        $this->load->model('process_model');
        $listProcess = $this->process_model->getListProcessCampagne($campagne);
        if(!$listProcess) $listProcess = [];
        
        if($profil) $listAffectedProcess = $this->process_model->getListAffectedProcessCampagne($profil, $campagne);
        if(!$profil) $listAffectedProcess = [];
        
        echo json_encode(array('data' => $listProcess, 'affected' => $listAffectedProcess));
    }

    /**
     * Enregister une nouvelle process
     */
    public function saveNewProcess()
    {
        
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')

        {
            $info_process = $this->input->post();
            //load model
            $this->load->model('process_model');
            $inserted_process = $this->process_model->insertProcess($info_process);

            //Si tt c'est bien passé, on commit la transaction sinon on fait un rollback
            if ($inserted_process === FALSE){
                redirect('process/listprocess?msg=error');
            }else{
                $this->db->trans_commit();
                redirect('process/listprocess?msg=success');
            }

        }

        
    }
    /**
    *Prendre les infos d'un process
    */  
    public function getInfoProcess(){

        $process_id = $this->input->post('process_id');

        if($process_id){
            $this->load->model('process_model');
            $info_process = $this->process_model->getProcess($process_id); 
            if($info_process)
                echo json_encode(array('error' => false, 'info_process' => $info_process));
            else
                echo json_encode(array('error' => true));
            die();
        }   

        echo json_encode(array('error' => true));

    }

    /**
     * Enregister les informations modifiés d'un process
     */
    public function saveEditProcess(){

        if($this->input->post() && $this->input->post('send_edit') && $this->input->post('send_edit') == 'sent')
        {
            $edit_process_data = $this->input->post();

            //load model
            $this->load->model('process_model');
            $updated_process = $this->process_model->updateProcess($edit_process_data);

            if ($updated_process){
                redirect('process/listprocess?msg=succes');
            }else{
                redirect('process/listprocess?msg=error');
            }

        }
    }

    /**
     * désactiver une process
     */

    public function desactivateProcess()
    {


        $process_id = $this->input->post('processToDesactivate');
        
        if($process_id){
            $this->load->model('process_model');
            $is_desactivate = $this->process_model->desactivateProcess($process_id);

            if($is_desactivate)
                redirect('process/listprocess?msg=success');
            else
                redirect('process/listprocess?msg=error');
        }
    }

    

}