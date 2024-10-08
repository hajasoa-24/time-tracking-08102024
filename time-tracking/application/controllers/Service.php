<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends MY_Controller {
    /*
    * Afficher la liste des services
    */
    public function listService()
    {
        $header = ['pageTitle' => 'Gestion des services - TimeTracking'];

        $this->load->model('site_model');
        $listSite = $this->site_model->getAllSite();
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('service/listservice', []);
        $this->load->view('service/modalservice', array(
            'listSite'=> $listSite ));
        $this->load->view('common/footer', []);
    }
     /**
     * Prendre la liste des services 
     */
    public function getListService()
    {
        $this->load->model('service_model');
        $listService = $this->service_model->getAllService();
        if(!$listService) $listService = [];
        
        echo json_encode(array('data' => $listService));
    }
     /**
     * Enregister une nouvelle service
     */
    public function saveNewService(){
        //$header_data = $this->get_header_info();
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')
        {
            $info_service = $this->input->post();

            //load model
            $this->load->model('service_model');
            $inserted_service = $this->service_model->insertService($info_service);

            if ($inserted_service){
                redirect('service/listService?msg=succes');
            }else{
                redirect('service/listService?msg=error');
            }

        }
    }

    /**
    *Prendre les infos d'une service
    */

    public function getInfoService(){

        $service_id = $this->input->post('service_id');

        if($service_id){
            $this->load->model('service_model');
            $info_service = $this->service_model->getService($service_id); 
            if($info_service)
                echo json_encode(array('error' => false, 'info_service' => $info_service));
            else
                echo json_encode(array('error' => true));
            die();
        }   

        echo json_encode(array('error' => true));

    }
    /**
     * Enregister les informations modifiés d'une service
     */
    public function saveEditService(){

        if($this->input->post() && $this->input->post('send_edit') && $this->input->post('send_edit') == 'sent')
        {
            $edit_service_data = $this->input->post();
            
            //load model
            $this->load->model('service_model');
            $updated_service = $this->service_model->updateService($edit_service_data);

            if ($updated_service){
                redirect('service/listService?msg=succes');
            }else{
                redirect('service/listService?msg=error');
            }

        }
    }


    /**
     * désactiver une service
     */

    public function desactivateService()
    {


        $service_id = $this->input->post('serviceToDesactivate');
        
        if($service_id){
            $this->load->model('service_model');
            $is_desactivate = $this->service_model->desactivateService($service_id);

            if($is_desactivate)
                redirect('service/listService?msg=success');
            else
                redirect('service/listService?msg=error');
        }
    }

}