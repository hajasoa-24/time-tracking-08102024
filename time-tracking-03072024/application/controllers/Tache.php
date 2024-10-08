<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tache extends MY_Controller {


    public function taches()
    {
        
        $header = ['pageTitle' => 'Mes tâches - TimeTracking'];
        $this->load->model('user_model');
        $this->load->model('Tache_model');
        $current_user = $this->session->userdata('user')['id'];
        $datas = $this->Tache_model->getTask($current_user);
                                                                
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('taches/modaltache');
        $this->load->view('taches/tache');

        $this->load->view('common/footer', []);
    }

    public function suivitaches()
    {
        
        $header = ['pageTitle' => 'Mes tâches - TimeTracking'];
        $this->load->model('user_model');
        $this->load->model('Tache_model');
        $current_user = $this->session->userdata('user')['id'];
        $datas = $this->Tache_model->getTask($current_user);

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('taches/modaltache');
        $this->load->view('suivi/tache');

        $this->load->view('common/footer', []);
    }


    public function getmestaches()
    {

        $this->load->model('Tache_model');
        $current_user = $this->session->userdata('user')['id'];
        $datas = $this->Tache_model->getTask($current_user);

        echo json_encode(array('data' => $datas));
    }


    public function getmessuivis()
    {

        $this->load->model('Tache_model');
        $current_user = $this->session->userdata('user')['id'];
        $datas = $this->Tache_model->getTasksuivis($current_user);

        echo json_encode(array('data' => $datas));
    }


    public function commentaire(){
        $demande = array();
        $id = $this->input->post('id_tache');

        $demande['tache_id'] =  $this->input->post('id_tache');
        $demande['tache_status'] = 2;
        $demande['tache_commentaire'] = $this->input->post('commentaire');


        $this->load->model('Tache_model');
        $updated = $this->Tache_model->validation($demande, $id);
        $page = 'tache/taches';

        if ($updated){
            redirect($page);
        }

    }
    public function suivitachescadre()
    {
        
        $filtretache = $this->session->userdata('filtretache');
      

            $filtretache = [
                'debut' => date('Y-m-d', strtotime("-1 days")),
                'fin' => date('Y-m-d', strtotime("0 days"))
            ];
            $this->session->set_userdata('filtretache',$filtretache);
        

        
        $header = ['pageTitle' => 'Mes tâches - TimeTracking'];
        $this->load->model('user_model');
        $this->load->model('Tache_model');
        $current_user = $this->session->userdata('user')['id'];
        $datas = $this->Tache_model->getTask($current_user);

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('taches/modaltache');
        $this->load->view('taches/cadre',['filtretache'=> $filtretache]);

        $this->load->view('common/footer', []);

    }
        

    public function suivicadre()
    {
        $filtretache = $this->session->userdata('filtretache');

        if(!isset($filtretache['debut']) || (isset($filtretache['debut']) && empty($filtretache['debut']))){
            $filtretache['debut'] = date('Y-m-d', strtotime(' -1 days'));}
        if(!isset($filtretache['fin']) || (isset($filtretache['fin']) && empty($filtretache['fin']))){
                $filtretache['fin'] = date('Y-m-d');
        }
       
        $this->load->model('Tache_model');
        $datas = $this->Tache_model->suivicadre($filtretache);

        echo json_encode(array('data' => $datas));

    } 
    public function  setfilter(){
        $filtretache = $this->session->userdata('filtretache');

        $start = $this->input->post('debut');
        $end = $this->input->post('fin');


       
            $filtretache = [
                'debut' => $start,
                'fin' => $end
            ];
            $this->session->set_userdata('filtretache', $filtretache);
            echo json_encode(['err' => false]);

        }

    
    

    public function validationtache(){
        $demande = array();
        $id = $this->input->post('id_tache');
        $current_user = $this->session->userdata('user')['prenom'];


        $demande['tache_status'] = 3;
        $demande['tache_usr_validation'] = $current_user;


        $this->load->model('Tache_model');
        $updated = $this->Tache_model->validationtache($demande, $id);
        $page = 'tache/suivitachescadre';

        if ($updated){
            redirect($page);
        }

    }
    public function encours()
    {
        $demande = array();
        $id = $this->input->post('id_tache');
        $current_user = $this->session->userdata('user')['prenom'];


        $demande['tache_status'] = 1.5;
        $demande['tache_usr_validation'] = $current_user;


        $this->load->model('Tache_model');
        $updated = $this->Tache_model->encourstache($demande, $id);

    }

 
        


}

?>