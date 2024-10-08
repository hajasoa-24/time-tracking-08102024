<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agence extends MY_Controller {
    /*
    * Afficher la liste des campagnes
    */
    public function gestionAgence()
    {
        $header = ['pageTitle' => 'Gestion des agences - TimeTracking'];

        $this->load->model('agence_model');
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('agence/gestionagence', []);
        $this->load->view('agence/modalagence', []);

        $this->load->view('common/footer', []);
    }
    /**
     * Prendre la liste des campagnes 
     */
    public function getListAgence()
    {
        $this->load->model('agence_model');
        $listAgence = $this->agence_model->getAllAgence();
        if(!$listAgence) $listAgence = [];
        
        echo json_encode(array('data' => $listAgence));
    }
    
    public function tableauAgence()
    {
        $header = ['pageTitle' => 'Tableau des agences - TimeTracking'];

        $this->load->model('agence_model');
        

        //Init des filtres à la date du jour
        $filtreCalendrierAgenceDu = (isset($this->session->userdata('filtreCalendrierAgence')['Du'])) ? $this->session->userdata('filtreCalendrierAgence')['Du'] : date('Y-m-d');
        $filtreCalendrierAgenceAu = (isset($this->session->userdata('filtreCalendrierAgence')['Au'])) ? $this->session->userdata('filtreCalendrierAgence')['Au'] : date('Y-m-d', strtotime('+14 days'));
        
        $currentfiltreCalendrierAgenceDu = $this->input->get('filtreCalendrierAgenceDu');
        $currentfiltreCalendrierAgenceAu = $this->input->get('filtreCalendrierAgenceAu');

        if($currentfiltreCalendrierAgenceDu){
            $filtreCalendrierAgenceDu = $currentfiltreCalendrierAgenceDu;
        }
        if($currentfiltreCalendrierAgenceAu){
            $filtreCalendrierAgenceAu = $currentfiltreCalendrierAgenceAu;
        }

        $sessionfiltreCalendrierAgence = array('Du' => $filtreCalendrierAgenceDu, 'Au' => $filtreCalendrierAgenceAu);
        $this->session->set_userdata('filtreCalendrierAgence', $sessionfiltreCalendrierAgence);

        $period = new DatePeriod(new DateTime($filtreCalendrierAgenceDu), new DateInterval('P1D'), new DateTime($filtreCalendrierAgenceAu . ' +1 days'));
        foreach ($period as $date) {
            $dates[] = $date->format("Y-m-d");
        }
        $datas = $this->getCalendarDataAgence();
        $etatAgence = $this->agence_model->getEtatAgence();

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('agence/tableauagence', [
            'filtreCalendrierAgenceDu' => $filtreCalendrierAgenceDu, 
            'filtreCalendrierAgenceAu' => $filtreCalendrierAgenceAu, 
            'dates' => $dates,
            'datas' => $datas,
            'defaultEtat' => $etatAgence[0],
        ]);
        $this->load->view('agence/modaltableauagence', [ 'listEtat' => $etatAgence ]);

        $this->load->view('common/footer', []);
    }

    public function getCalendarDataAgence()
    {
        $this->load->model('agence_model');

        $debut = $this->session->userdata('filtreCalendrierAgence')['Du'];
        $fin = $this->session->userdata('filtreCalendrierAgence')['Au'];

        $datas = $this->agence_model->getCalendarDatas($debut, $fin);
        if(!$datas) $datas = [];

        $formattedData = [];

        //Formatage des données
        //On boucle sur les données
        foreach($datas as $data){
            $agence_id = $data->agence_id;

            if(!isset($formattedData[$agence_id])){
                $temp = [
                    'id' =>  $agence_id,
                    'libelle' => $data->agence_libelle,
                    'datas' => []
                ];
                $formattedData[$agence_id] = $temp;
            }
            
            $period = new DatePeriod(new DateTime($debut), new DateInterval('P1D'), new DateTime($fin . ' +1 days'));
            $is_inserted = false;
            
            foreach ($period as $dateRange) {
                $day = $dateRange->format('Y-m-d');
                if($day == $data->calendagence_date){
                    $formattedData[$agence_id]['datas'][] = [
                        'date' => $day, 
                        'etat' => $data->etatagence_id,
                        'etat_libelle' => $data->etatagence_libelle,
                        'couleur' => $data->coulagence_hexa
                    ];
                    $is_inserted = true;
                    break;
                }

            }

            //$dates[] = $date->format("d/m");
        }
        return $formattedData;
        //echo json_encode(array('data' => $formattedData));
    }
    /**
     * Enregister une nouvelle campagne
     */
    public function saveNewAgence()
    {
        //$header_data = $this->get_header_info();
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')

        {
            $inserted_agence = false;

            $info_agence = $this->input->post();
            if($info_agence['agence_id']){

                //load model
                $this->load->model('agence_model');
                $inserted_agence = $this->agence_model->insertAgence($info_agence);
            }

            //Si tt c'est bien passé, on commit la transaction sinon on fait un rollback
            if ($inserted_agence === FALSE){
                redirect('agence/gestionAgence?msg=error');
            }else{
                redirect('agence/gestionAgence?msg=success');
            }

        }

        
    }
    /**
    *Prendre les infos d'une campagne
    */
    public function getInfoAgence(){

        $agence_id = $this->input->post('agence_id');

        if($agence_id){
            $this->load->model('agence_model');
            $info_agence = $this->agence_model->getAgence($agence_id); 
            if($info_agence)
                echo json_encode(array('error' => false, 'info_agence' => $info_agence));
            else
                echo json_encode(array('error' => true));
            die();
        }   

        echo json_encode(array('error' => true));

    }

    /**
     * Enregister les informations modifiés d'une campagne
     */
    public function saveEditAgence(){

        if($this->input->post() && $this->input->post('send_edit') && $this->input->post('send_edit') == 'sent')
        {
            $edit_agence_data = $this->input->post();
            //var_dump($edit_user_data); die;

            //load model
            $this->load->model('agence_model');
            $updated_agence = $this->agence_model->updateAgence($edit_agence_data);

            if ($updated_agence){
                redirect('agence/gestionAgence?msg=success');
            }else{
                redirect('agence/gestionAgence?msg=error');
            }

        }
    }


    public function desactivateAgence()
    {


        $agence_id = $this->input->post('agenceToDesactivate');
        
        if($agence_id){
            $this->load->model('agence_model');
            $is_desactivate = $this->agence_model->desactivateAgence($agence_id);

            if($is_desactivate)
                redirect('agence/gestionAgence?msg=success');
            else
                redirect('agence/gestionAgence?msg=error');
        }
    }

    public function editCalendar()
    {
        if($this->input->post() && $this->input->post('save_editcalendar'))
        {
            $edit_data = $this->input->post();

            //load model
            $this->load->model('agence_model');
            $updated_data = $this->agence_model->updateCalendarAgence($edit_data);

            if ($updated_data){
                redirect('agence/tableauAgence?msg=success');
            }else{
                redirect('agence/tableauAgence?msg=error');
            }

        }
    }

    public function activateagence(){
        $edit_data_activate = $this->input->post('agence_id');

        $this->load->model('agence_model');
        $updated_data = $this->agence_model->activateAgence($edit_data_activate);
        if($updated_data){
            echo json_encode( array('data'=> "true"));
        }

        else{
            echo json_encode( array('data'=> "false"));


        }

    }

    public function descactivateagence(){
        $edit_data_desactivate = $this->input->post('agence_id');

        $this->load->model('agence_model');
        $updated_data = $this->agence_model->desactivateAgencemodal($edit_data_desactivate);
        if($updated_data){
            echo json_encode( array('data'=> "true"));
        }
        else{
            echo json_encode( array('data'=> "false"));


        }

    }

}