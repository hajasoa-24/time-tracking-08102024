<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extranet extends CI_Controller {
    
    
    public function visualisationAgence()
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
        //$this->load->view('common/sidebar', $this->_sidebar);
        //$this->load->view('common/top', array('top' => $this->_top));

        $this->load->view('agence/visualisationagence', [
            'filtreCalendrierAgenceDu' => $filtreCalendrierAgenceDu, 
            'filtreCalendrierAgenceAu' => $filtreCalendrierAgenceAu, 
            'dates' => $dates,
            'datas' => $datas,
            'defaultEtat' => $etatAgence[0],
        ]);

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

        }
        return $formattedData;
    }

    

}