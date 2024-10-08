<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pointage extends MY_Controller {
    /**
     * prendre toutes les dates entre Du et Au
     */
    private function _displayDates($du, $au, $format = 'Y-m-d'){
        $dates = array();
        $current = strtotime($du);
        $end = strtotime($au);
        $stepval = '+1 day';
        while($current <= $end){
            $dates[] = date($format, $current);
            $current = strtotime($stepval, $current);
        }
        return $dates;
    }
    
    /*
    * Afficher la liste des pointages des agents de sécurité
    */
    public function pointageSecurite()
    {
       
        $header = ['pageTitle' => 'Pointage des agent de sécurité - TimeTracking'];

        //Init des filtres à la date du jour
        $filtrePointageDu = (isset($this->session->userdata('filtrePointage')['Du'])) ? $this->session->userdata('filtrePointage')['Du'] : date('Y-m-d');
        $filtrePointageAu = (isset($this->session->userdata('filtrePointage')['Au'])) ? $this->session->userdata('filtrePointage')['Au'] : date('Y-m-d');

        $currentFiltrePointageDu = $this->input->get('filtrePointageDu');
        $currentFiltrePointageAu = $this->input->get('filtrePointageAu');
       

        if($currentFiltrePointageDu){
            $filtrePointageDu = $currentFiltrePointageDu;
            //Enregistrement en session
            
        }
        if($currentFiltrePointageAu){
            $filtrePointageAu = $currentFiltrePointageAu;
        }
        $sessionFiltrePointage = array('Du' => $filtrePointageDu, 'Au' => $filtrePointageAu);
        $this->session->set_userdata('filtrePointage', $sessionFiltrePointage);
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('pointage/pointagesecurite', ['filtrePointageDu' => $filtrePointageDu, 'filtrePointageAu' => $filtrePointageAu]);

        $this->load->view('common/footer', []);
    }


    /**
     * Données pointage des agents de sécurité  
     */
    public function getPointageSecurite()
    {
        $this->load->model('ingress_model');
        $this->load->model('user_model'); 
        $this->load->model('ingressmcr_model');
        $this->load->model('ingresstnl_model');
        $serviceID = SECURITE_SERVICE_ID;
        //Récupérer la liste des agents de securité
        $listuserSecurite = $this->user_model->getUsersByService($serviceID);
        //No date set, so today"s date is used
        $du = date("Y-m-d");
        $au = date("Y-m-d");

        $rangeDate = array();
        $currFiltre = $this->session->userdata('filtrePointage');
        if($currFiltre){
            $du =  $currFiltre['Du'];
            $au = $currFiltre['Au'];
        }
        $listDates = $this->_displayDates($du, $au);
  
        foreach($listDates as $cDate){
            if(!in_array($cDate, $rangeDate)){
                $rangeDate[] = $cDate;
            }
        }
        $datas = [];
        foreach($listuserSecurite as $user){

            foreach($rangeDate as $currDate){
                
  		 $site = $user->usr_site;
                if($site == SITE_SETEX){
                    $pointageUser = $this->ingress_model->getUserPointage($user->usr_ingress, $currDate);
                }elseif($site == SITE_MCR){
                    $pointageUser = $this->ingressmcr_model->getUserPointage($user->usr_ingress, $currDate);
                }else{
                    $pointageUser = $this->ingresstnl_model->getUserPointage($user->usr_ingress, $currDate);
                }

                $data = (object)array(
                    'date' => $currDate,
                    'att_in' => '',
                    'att_break' => '',
                    'site_libelle' => $user->site_libelle,
                    'usr_prenom' => $user->usr_prenom,
                    'usr_matricule' => $user->usr_matricule,
                    'service_libelle' => $user->service_libelle
                );
                
                if($pointageUser && is_array($pointageUser)){
                    $pointageUser = $pointageUser[0];
                    $data->att_in = $pointageUser->att_in;
                    $data->att_break = $pointageUser->att_break;
                    $data->date = $pointageUser->date;
                }

                
                $datas[] = $data;
            }
        }
        echo json_encode(array('data' => $datas));
    }


    /*
    * Afficher la liste des pointages des agents de transport
    */
    public function pointageTransport()
    {
       
        $header = ['pageTitle' => 'Pointage des agent de transport - TimeTracking'];

        //Init des filtres à la date du jour
        $filtrePointageDu = (isset($this->session->userdata('filtrePointage')['Du'])) ? $this->session->userdata('filtrePointage')['Du'] : date('Y-m-d');
        $filtrePointageAu = (isset($this->session->userdata('filtrePointage')['Au'])) ? $this->session->userdata('filtrePointage')['Au'] : date('Y-m-d');

        $currentFiltrePointageDu = $this->input->get('filtrePointageDu');
        $currentFiltrePointageAu = $this->input->get('filtrePointageAu');
       

        if($currentFiltrePointageDu){
            $filtrePointageDu = $currentFiltrePointageDu;
            //Enregistrement en session
            
        }
        if($currentFiltrePointageAu){
            $filtrePointageAu = $currentFiltrePointageAu;
        }
        $sessionFiltrePointage = array('Du' => $filtrePointageDu, 'Au' => $filtrePointageAu);
        $this->session->set_userdata('filtrePointage', $sessionFiltrePointage);
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('pointage/pointagetransport', ['filtrePointageDu' => $filtrePointageDu, 'filtrePointageAu' => $filtrePointageAu]);

        $this->load->view('common/footer', []);
    }


    /**
     * Données pointage des agents de transport  
     */
    public function getPointageTransport()
    {
        $this->load->model('ingressmcr_model');
        $this->load->model('user_model'); 
       
        $serviceID = TRANSPORT_SERVICE_ID;
        //Récupérer la liste des agents de transport
        $listuserTransport = $this->user_model->getUsersByService($serviceID);
        //No date set, so today"s date is used
        $du = date("Y-m-d");
        $au = date("Y-m-d");

        $rangeDate = array();
        $currFiltre = $this->session->userdata('filtrePointage');
        if($currFiltre){
            $du =  $currFiltre['Du'];
            $au = $currFiltre['Au'];
        }
        $listDates = $this->_displayDates($du, $au);
  
        foreach($listDates as $cDate){
            if(!in_array($cDate, $rangeDate)){
                $rangeDate[] = $cDate;
            }
        }
        $datas = [];

        foreach($listuserTransport as $user){

            foreach($rangeDate as $currDate){
                
                $pointageUser = $this->ingressmcr_model->getUserPointage($user->usr_ingress, $currDate);

                $data = (object)array(
                    'date' => $currDate,
                    'att_in' => '',
                    'att_break' => '',
                    'site_libelle' => $user->site_libelle,
                    'usr_prenom' => $user->usr_prenom,
                    'usr_matricule' => $user->usr_matricule,
                    'service_libelle' => $user->service_libelle
                );
                
                if($pointageUser && is_array($pointageUser)){
                    $pointageUser = $pointageUser[0];
                    $data->att_in = $pointageUser->att_in;
                    $data->att_break = $pointageUser->att_break;
                    $data->date = $pointageUser->date;
                }

                
                $datas[] = $data;
            }
        }
        echo json_encode(array('data' => $datas));
    }



    /*
    * Afficher la liste des pointages des médecins
    */
    public function pointageMedical()
    {
        $header = ['pageTitle' => 'Pointage des médécins - TimeTracking'];

        //Init des filtres à la date du jour
        $filtrePointageDu = (isset($this->session->userdata('filtrePointage')['Du'])) ? $this->session->userdata('filtrePointage')['Du'] : date('Y-m-d');
        $filtrePointageAu = (isset($this->session->userdata('filtrePointage')['Au'])) ? $this->session->userdata('filtrePointage')['Au'] : date('Y-m-d');

        $currentFiltrePointageDu = $this->input->get('filtrePointageDu');
        $currentFiltrePointageAu = $this->input->get('filtrePointageAu');
       

        if($currentFiltrePointageDu){
            $filtrePointageDu = $currentFiltrePointageDu;
            //Enregistrement en session
            
        }
        if($currentFiltrePointageAu){
            $filtrePointageAu = $currentFiltrePointageAu;
        }
        $sessionFiltrePointage = array('Du' => $filtrePointageDu, 'Au' => $filtrePointageAu);
        $this->session->set_userdata('filtrePointage', $sessionFiltrePointage);
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('pointage/pointagemedical', ['filtrePointageDu' => $filtrePointageDu, 'filtrePointageAu' => $filtrePointageAu]);

        $this->load->view('common/footer', []);
    }



    /**
     * Données pointage des medecins 
     */
    public function getPointageMedical()
    {
        $this->load->model('ingress_model');
        $this->load->model('ingressmcr_model');
        $this->load->model('ingresstnl_model');
        $this->load->model('user_model'); 
       
        $serviceID = MEDICAL_SERVICE_ID;
        //Récupérer la liste des agents de transport
        $listuserSupportAdmin = $this->user_model->getUsersByService($serviceID);
        //No date set, so today"s date is used
        $du = date("Y-m-d");
        $au = date("Y-m-d");

        $rangeDate = array();
        $currFiltre = $this->session->userdata('filtrePointage');
        if($currFiltre){
            $du =  $currFiltre['Du'];
            $au = $currFiltre['Au'];
        }
        $listDates = $this->_displayDates($du, $au);
  
        foreach($listDates as $cDate){
            if(!in_array($cDate, $rangeDate)){
                $rangeDate[] = $cDate;
            }
        }
        $datas = [];

        foreach($listuserSupportAdmin as $user){

            foreach($rangeDate as $currDate){
                
                $site = $user->usr_site;

                $pointageUser = false;

                    if($site == SITE_SETEX){
                        $this->load->model('ingress_model');
                        $pointageUser = $this->ingress_model->getUserPointage($user->usr_ingress, $currDate);
                    }else if($site == SITE_MCR){
                        $this->load->model('ingressmcr_model');
                        $pointageUser = $this->ingressmcr_model->getUserPointage($user->usr_ingress, $currDate);
                    }else if($site == SITE_TNL){
                        $this->load->model('ingresstnl_model');
                        $pointageUser = $this->ingresstnl_model->getUserPointage($user->usr_ingress, $currDate);
                    }

                $data = (object)array(
                    'date' => $currDate,
                    'att_in' => '',
                    'att_break' => '',
                    'site_libelle' => $user->site_libelle,
                    'usr_prenom' => $user->usr_prenom,
                    'usr_matricule' => $user->usr_matricule,
                    'service_libelle' => $user->service_libelle
                );
                
                if( $pointageUser && is_array($pointageUser)){
                    $pointageUser = $pointageUser[0];
                    $data->att_in = $pointageUser->att_in;
                    $data->att_break = $pointageUser->att_break;
                    $data->date = $pointageUser->date;
                }

                
                $datas[] = $data;
            }
        }
        echo json_encode(array('data' => $datas));
    }



    /*
    * Afficher la liste des pointages des femmes de menages , jardinier 
    */
    public function pointageAutres()
    {
        $header = ['pageTitle' => 'Pointage des agents de support - TimeTracking'];

        //Init des filtres à la date du jour
        $filtrePointageDu = (isset($this->session->userdata('filtrePointage')['Du'])) ? $this->session->userdata('filtrePointage')['Du'] : date('Y-m-d');
        $filtrePointageAu = (isset($this->session->userdata('filtrePointage')['Au'])) ? $this->session->userdata('filtrePointage')['Au'] : date('Y-m-d');

        $currentFiltrePointageDu = $this->input->get('filtrePointageDu');
        $currentFiltrePointageAu = $this->input->get('filtrePointageAu');
       

        if($currentFiltrePointageDu){
            $filtrePointageDu = $currentFiltrePointageDu;
            //Enregistrement en session
            
        }
        if($currentFiltrePointageAu){
            $filtrePointageAu = $currentFiltrePointageAu;
        }
        $sessionFiltrePointage = array('Du' => $filtrePointageDu, 'Au' => $filtrePointageAu);
        $this->session->set_userdata('filtrePointage', $sessionFiltrePointage);
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('pointage/pointageautres', ['filtrePointageDu' => $filtrePointageDu, 'filtrePointageAu' => $filtrePointageAu]);

        $this->load->view('common/footer', []);
    }



    /**
     * Données pointage des FM , jardienier etc  
     */
    public function getPointageAutres()
    {
        $this->load->model('ingress_model');
        $this->load->model('ingressmcr_model');
        $this->load->model('ingresstnl_model');
        $this->load->model('user_model'); 
       
        $serviceID = SUPPORTADMIN_SERVICE_ID;
        //Récupérer la liste des agents de transport
        $listuserSupportAdmin = $this->user_model->getUsersByService($serviceID);
        //No date set, so today"s date is used
        $du = date("Y-m-d");
        $au = date("Y-m-d");

        $rangeDate = array();
        $currFiltre = $this->session->userdata('filtrePointage');
        if($currFiltre){
            $du =  $currFiltre['Du'];
            $au = $currFiltre['Au'];
        }
        $listDates = $this->_displayDates($du, $au);
  
        foreach($listDates as $cDate){
            if(!in_array($cDate, $rangeDate)){
                $rangeDate[] = $cDate;
            }
        }
        $datas = [];

        foreach($listuserSupportAdmin as $user){

            foreach($rangeDate as $currDate){
                
                $site = $user->usr_site;

                $pointageUser = false;

                    if($site == SITE_SETEX){
                        $this->load->model('ingress_model');
                        $pointageUser = $this->ingress_model->getUserPointage($user->usr_ingress, $currDate);
                    }else if($site == SITE_MCR){
                        $this->load->model('ingressmcr_model');
                        $pointageUser = $this->ingressmcr_model->getUserPointage($user->usr_ingress, $currDate);
                    }else if($site == SITE_TNL){
                        $this->load->model('ingresstnl_model');
                        $pointageUser = $this->ingresstnl_model->getUserPointage($user->usr_ingress, $currDate);
                    }

                $data = (object)array(
                    'date' => $currDate,
                    'att_in' => '',
                    'att_break' => '',
                    'site_libelle' => $user->site_libelle,
                    'usr_prenom' => $user->usr_prenom,
                    'usr_matricule' => $user->usr_matricule,
                    'service_libelle' => $user->service_libelle
                );
                
                if( $pointageUser && is_array($pointageUser)){
                    $pointageUser = $pointageUser[0];
                    $data->att_in = $pointageUser->att_in;
                    $data->att_break = $pointageUser->att_break;
                    $data->date = $pointageUser->date;
                }

                
                $datas[] = $data;
            }
        }
        echo json_encode(array('data' => $datas));
    }


}