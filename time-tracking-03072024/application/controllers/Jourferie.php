<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jourferie extends MY_Controller {


    public function ferie()
    {
        
        $header = ['pageTitle' => 'Jours férié'];
        $this->load->model('');
        $this->load->model('Tache_model');
        $current_user = $this->session->userdata('user')['id'];
        $datas = $this->Tache_model->getTask($current_user);
                                                                
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('jourferie/ferie');

        $this->load->view('common/footer', []);
    }

    public function addJourferie()
    {
        $ferie = array();
        
        $ferie['holidays_libelle'] =  $this->input->post('description_ferie');
        $ferie['holidays_date'] = $this->input->post('date_ferie');
        $this->load->model('Jourferie_model');
        $add = $this->Jourferie_model->addFerie($ferie);


        $page = 'jourferie/ferie';

        if ($add){
            redirect($page);
        }
       

    }


    public function getferie()
    {
        $this->load->model('Jourferie_model');

        $datas = $this->Jourferie_model->getholidays();

        echo json_encode(array('data' => $datas));
    }

    public function delete()
    {
        $id = $this->input->post('id_ferie');
        $this->load->model('Jourferie_model');
       $requete =  $this->Jourferie_model->deleteFerie($id);

       if ($requete){
        echo json_encode(array('success' => true));

       
       }
       else{
        echo json_encode(array('success' => false));

       }
       


    }



}

?>