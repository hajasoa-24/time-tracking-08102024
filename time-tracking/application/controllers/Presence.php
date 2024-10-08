<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Presence extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
    }

    public function index() 
    {
        $this->suiviPresence();
    }

    public function suiviPresence()
    {
        $header = ['pageTitle' => 'Suivi présence - TimeTracking'];

        $this->load->model('motif_model');
        $listMotif = $this->motif_model->getAllMotif();
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('suivi/presence', ['role' => $this->_userRole]);
        $this->load->view('suivi/modalmotif', array('listMotif'=> $listMotif));
        $this->load->view('common/footer', []);
    }

    public function suiviRetard()
    {
        $header = ['pageTitle' => 'Suivi retard - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('suivi/retard', []);
        $this->load->view('common/footer', []);
    }


    public function getListPresence($day = false, $return = false)
    {
        //Date par defaut date du jour
        if(!$day) 
            $day = date('Y-m-d');

        $this->load->model('user_model');
        $this->load->model('campagne_model');
        $this->load->model('service_model');
        $currentSup = $this->session->userdata('user')['id'];
        $userPoids = ($this->session->userdata('user')['poids']) ? $this->session->userdata('user')['poids'] : false;
        $listCampagneSup = $this->campagne_model->getUserCampagne($currentSup);
        $listServiceSup = $this->service_model->getUserService($currentSup);
        $listCampagne = [];
        if(!empty($listCampagneSup)){
            foreach($listCampagneSup as $campagne){
                $listCampagne[] = $campagne->campagne_id;
            }
        }

        $listService = [];
        if(!empty($listServiceSup)){
            foreach($listServiceSup as $service){
                $listService[] = $service->service_id;
            }
        }
        $listCampagneStr = (!empty($listCampagne)) ? implode(",", $listCampagne) : false;
        $listServiceStr = (!empty($listService)) ? implode(",", $listService) : false;
        
        $filtre = [];
        $filtre['day'] = $day;
        $filtre['user'] = $currentSup;
        $filtre['user_poids'] =  $userPoids;
        $filtre['list_campagne'] = $listCampagne;
        $filtre['list_service'] = $listService;
        
        //$presenceData = $this->user_model->getPresenceAgents($filtre);
        $presenceData = $this->user_model->getPresenceAgents($day, $listCampagneStr, $listServiceStr, $this->_userRole);

        if($return){
            return $presenceData;
        }else{
            echo json_encode(array('data' => $presenceData));
        }
    }

    public function getListRetard($day = false, $return = false)
    {
        //Date par defaut date du jour
        if(!$day) 
            $day = date('Y-m-d');

        $this->load->model('presence_model');
        
        $currentSup = $this->session->userdata('user')['id'];
        $userPoids = ($this->session->userdata('user')['poids']) ? $this->session->userdata('user')['poids'] : false;

        $campagnes = $this->session->userdata('user')['listcampagne'];
        $listCampagne = [];
        if(is_array($campagnes)){
            foreach($campagnes as $campagne){
                $listCampagne[] = $campagne->campagne_id;
            }
        }
        $services = $this->session->userdata('user')['listservice'];
        $listService = [];
        if(is_array($services)){
            foreach($services as $service){
                $listService[] = $service->service_id;
            }
        }
        
        $filtre = [];
        $filtre['day'] = $day;
        $filtre['user_poids'] =  $userPoids;
        $filtre['list_campagne'] = $listCampagne;
        $filtre['list_service'] = $listService;
        
        $presenceData = $this->presence_model->getListRetardJour($filtre);

        if($return){
            return $presenceData;
        }else{
            echo json_encode(array('data' => $presenceData));
        }
    }


    /**
     * Enregister un nouveau motif
    */
    public function saveNewMotif(){
        //$header_data = $this->get_header_info();
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')
        {
            $info_motif = $this->input->post();
            //Add motifpresence_modificateur 
            $info_motif['modif_modificateur'] = $this->session->userdata('user')['id'];
            //load model
            $this->load->model('Motifpresence_model');
            $inserted_motif = $this->Motifpresence_model->insertMotif($info_motif);
            
            if ($inserted_motif){

                if(isset($info_motif['motif_redirect_back']) && $info_motif['motif_redirect_back'] == 'true'){
                    redirect('presence/histoPresence?msg=succes');
                }else{
                    redirect('presence/suiviPresence?msg=succes');
                }
            }else{
                redirect('presence/suiviPresence?msg=error');
            }

        }
    }


    public function histoPresence()
    {
        $header = ['pageTitle' => 'Suivi présence - TimeTracking'];
        
        //Init des filtres à la date du jour
        $filtreHistoPresenceDu = (isset($this->session->userdata('filtreHistoPresence')['Du'])) ? $this->session->userdata('filtreHistoPresence')['Du'] : date('Y-m-d', strtotime('-1 days'));
        $filtreHistoPresenceAu = (isset($this->session->userdata('filtreHistoPresence')['Au'])) ? $this->session->userdata('filtreHistoPresence')['Au'] : date('Y-m-d', strtotime('-1 days'));

        $currentFiltreHistoPresenceDu = $this->input->get('filtreHistoPresenceDu');
        $currentFiltreHistoPresenceAu = $this->input->get('filtreHistoPresenceAu');

        if($currentFiltreHistoPresenceDu){
            $filtreHistoPresenceDu = $currentFiltreHistoPresenceDu;
            //Enregistrement en session
            
        }
        if($currentFiltreHistoPresenceAu){
            $filtreHistoPresenceAu = $currentFiltreHistoPresenceAu;
        }
        
        $sessionFiltreHistoPresence = array('Du' => $filtreHistoPresenceDu, 'Au' => $filtreHistoPresenceAu);
        $this->session->set_userdata('filtreHistoPresence', $sessionFiltreHistoPresence);

        $this->load->model('motif_model');
        $listMotif = $this->motif_model->getAllMotif();

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('histo/histopresences', ['filtreHistoPresenceDu' => $filtreHistoPresenceDu, 'filtreHistoPresenceAu' => $filtreHistoPresenceAu, 'role' => $this->_userRole]);
        $this->load->view('histo/modaleditmotif', array('listMotif'=> $listMotif));
        $this->load->view('common/footer', []);
    }



    public function getListhistoPresence()
    {
        $this->load->model('user_model');
        $this->load->model('campagne_model');
        $this->load->model('service_model');
        $currentSup = $this->session->userdata('user')['id'];
        $listCampagneSup = $this->campagne_model->getUserCampagne($currentSup);
        $listServiceSup = $this->service_model->getUserService($currentSup);
        $listAgentCampagne = [];
        $listAgentService = [];
        $listAgent = [];

        $datas = [];
        if(!empty($listCampagneSup)){
            $listCampagne = [];
            foreach($listCampagneSup as $campagne){
                $listCampagne[] = $campagne->campagne_id;
            }

            if(!empty($listCampagne)){
                $listAgentCampagne = $this->user_model->getListAgentByCampagne($listCampagne);
                foreach($listAgentCampagne as $agent){
                    $listAgent[] = $agent->usr_id;
                }
            }

            //Vérifier que le profil est cadre si OUI on ajoute les SUPs
            if($this->_userRole == ROLE_CADRE || $this->_userRole == ROLE_CADRE2){
                $listSupCampagne = $this->user_model->getListSupByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }
            //Vérifier que le profil est adminrh si OUI on ajoute les cadres
            if($this->_userRole == ROLE_ADMINRH || $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_REPORTING){
                $listCadreCampagne = $this->user_model->getListCadreByCampagne($listCampagne);
                foreach($listCadreCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }
        }

        if(!empty($listServiceSup)){
            $listService = [];
            foreach($listServiceSup as $service){
                $listService[] = $service->service_id;
            }

            if(!empty($listService)){
                $listAgentService = $this->user_model->getListAgentByService($listService);
                foreach ($listAgentService as $agent) {
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

            
            //Vérifier que le profil est cadre si OUI on ajoute les SUPs
            if($this->_userRole == ROLE_CADRE || $this->_userRole == ROLE_CADRE2){
                $listSupService = $this->user_model->getListSupByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }
            //Vérifier que le profil est adminrh si OUI on ajoute les cadres
            if($this->_userRole == ROLE_ADMINRH || $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_REPORTING){
                $listCadreService = $this->user_model->getListCadreByService($listService);
                foreach($listCadreService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }
        }
        
        //Chargement des filtres
        $filtre = $this->session->userdata('filtreHistoPresence');
        if(is_array($filtre) && !empty($filtre)){
            $filtreDu = (isset($filtre['Du']) ? $filtre['Du'] : date('Y-m-d'));
            $filtreAu = (isset($filtre['Au']) ? $filtre['Au'] : date('Y-m-d'));
        }
        $this->load->model('presence_model');
        $listPresence = $this->presence_model->getHistoPresence($filtreDu, $filtreAu, $listAgent);
        if(!$listPresence) $listPresence = [];
        //var_dump($listPresence); die;
        
        echo json_encode(array('data' => $listPresence));
    }


    public function suiviRhPresence()
    {
        $header = ['pageTitle' => 'Suivi présence - TimeTracking'];
        
        //Init des filtres à la date du jour
        $filtreHistoPresenceDu = (isset($this->session->userdata('filtreHistoPresence')['Du'])) ? $this->session->userdata('filtreHistoPresence')['Du'] : date('Y-m-d', strtotime('-1 days'));
        $filtreHistoPresenceAu = (isset($this->session->userdata('filtreHistoPresence')['Au'])) ? $this->session->userdata('filtreHistoPresence')['Au'] : date('Y-m-d', strtotime('-1 days'));

        $currentFiltreHistoPresenceDu = $this->input->get('filtreHistoPresenceDu');
        $currentFiltreHistoPresenceAu = $this->input->get('filtreHistoPresenceAu');

        if($currentFiltreHistoPresenceDu){
            $filtreHistoPresenceDu = $currentFiltreHistoPresenceDu;
            //Enregistrement en session
            
        }
        if($currentFiltreHistoPresenceAu){
            $filtreHistoPresenceAu = $currentFiltreHistoPresenceAu;
        }
        
        $sessionFiltreHistoPresence = array('Du' => $filtreHistoPresenceDu, 'Au' => $filtreHistoPresenceAu);
        $this->session->set_userdata('filtreHistoPresence', $sessionFiltreHistoPresence);

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('suivirh/suivirhpresence', ['filtreHistoPresenceDu' => $filtreHistoPresenceDu, 'filtreHistoPresenceAu' => $filtreHistoPresenceAu]);
       
        $this->load->view('common/footer', []);
    }

    /**
     * Afficher les présence pour tout le monde 
     */
    public function getAllListPresence()
    {
        $this->load->model('user_model');
        $this->load->model('campagne_model');
        $this->load->model('service_model');

        $datas = [];
        
        //Chargement des filtres
        $filtre = $this->session->userdata('filtreHistoPresence');
        if(is_array($filtre) && !empty($filtre)){
            $filtreDu = (isset($filtre['Du']) ? $filtre['Du'] : date('Y-m-d'));
            $filtreAu = (isset($filtre['Au']) ? $filtre['Au'] : date('Y-m-d'));
        }

        $this->load->model('presence_model');
        $listPresence = $this->presence_model->getAllPresence($filtreDu, $filtreAu);
        if(!$listPresence) $listPresence = [];
        //var_dump($listPresence); die;
        
        echo json_encode(array('data' => $listPresence));
    }

    
}