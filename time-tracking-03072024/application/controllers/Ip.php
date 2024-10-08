<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ip extends MY_Controller {
    
    /*
    * Afficher la liste des adresses ip
    */
    public function listIp()
    {
        //securisation d'accès
        if($this->session->userdata('user')['istech'] != '1'){
            redirect('auth/login');
        }
        $header = ['pageTitle' => 'Liste des Ip - TimeTracking'];

        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('adresse_ip/listadresseip', []);

        $this->load->view('common/footer', []);
    }


    /**
     * Prendre la liste des adresses ip 
     */
    public function getListIp()
    {
        $this->load->model('ip_model');
        $listAdresseIp = $this->ip_model->getAllIp();
        if(!$listAdresseIp) $listAdresseIp = [];
        
        echo json_encode(array('data' => $listAdresseIp));
    }

    

}