<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proprio extends MY_Controller {
    /*
    * Afficher la liste des proprios
    */
    public function listProprio()
    {
        $header = ['pageTitle' => 'Gestion des proprios - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('proprio/listproprio', []);

        $this->load->view('common/footer', []);
    }
    /**
     * Prendre la liste des proprios 
     */
    public function getListProprio()
    {
        $this->load->model('proprio_model');
        $listProprio = $this->proprio_model->getAllProprio();
        if(!$listProprio) $listProprio = [];
        
        echo json_encode(array('data' => $listProprio));
    }

    /**
     * Enregister une nouvelle proprio
     */
    public function saveNewProprio()
    {
        
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')

        {
            $info_proprio = $this->input->post();
            //load model
            $this->load->model('proprio_model');
            $inserted_proprio = $this->proprio_model->insertProprio($info_proprio);

            //Si tt c'est bien passé, on commit la transaction sinon on fait un rollback
            if ($inserted_proprio === FALSE){
                redirect('proprio/listproprio?msg=error');
            }else{
                $this->db->trans_commit();
                redirect('proprio/listproprio?msg=success');
            }

        }

        
    }
    /**
    *Prendre les infos d'une proprio
    */
    public function getInfoProprio(){

        $proprio_id = $this->input->post('proprio_id');

        if($proprio_id){
            $this->load->model('proprio_model');
            $info_proprio = $this->proprio_model->getProprio($proprio_id); 
            if($info_proprio)
                echo json_encode(array('error' => false, 'info_proprio' => $info_proprio));
            else
                echo json_encode(array('error' => true));
            die();
        }   

        echo json_encode(array('error' => true));

    }

    /**
     * Enregister les informations modifiés d'une proprio
     */
    public function saveEditProprio(){

        if($this->input->post() && $this->input->post('send_edit') && $this->input->post('send_edit') == 'sent')
        {
            $edit_proprio_data = $this->input->post();

            //load model
            $this->load->model('proprio_model');
            $updated_proprio = $this->proprio_model->updateProprio($edit_proprio_data);

            if ($updated_proprio){
                redirect('proprio/listproprio?msg=succes');
            }else{
                redirect('proprio/listproprio?msg=error');
            }

        }
    }

    /**
     * désactiver une proprio
     */

    public function desactivateProprio()
    {


        $proprio_id = $this->input->post('proprioToDesactivate');
        
        if($proprio_id){
            $this->load->model('proprio_model');
            $is_desactivate = $this->proprio_model->desactivateProprio($proprio_id);

            if($is_desactivate)
                redirect('proprio/listproprio?msg=success');
            else
                redirect('proprio/listproprio?msg=error');
        }
    }

}