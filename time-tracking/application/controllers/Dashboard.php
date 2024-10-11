<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Dashboard extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
    }

    public function index() 
    {
        //On personalise la page d'accueil selon le role
        if($this->_userRole == ROLE_ADMIN){
            redirect('user/listUtilisateur');
        }else if($this->_userRole == ROLE_ADMINRH){
             redirect('conges/congesATraiter');
        }else if($this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_COSTRAT){
             redirect('conges/congesAValider');
        }else if($this->_userRole == ROLE_CLIENT){
             redirect('presence/suiviPresence');
        }else if($this->_userRole == ROLE_REPORTING){
             redirect('dashboard/historiqueAgents');
        }else{
            $this->progression();
        }
        
    }

    public function admin()
    {
        $header = ['pageTitle' => 'Administration - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('user/listutilisateur', []);
        $this->load->view('common/footer', []);
    }

    public function progression()
    {
        //var_dump($this->session->userdata());die;
        //var_dump($this->_top); die;
        $header = ['pageTitle' => 'Progression - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('suivi/progression', []);
        $this->load->view('common/footer', []);

    }

    public function tempsreel()
    {
        $header = ['pageTitle' => 'Temps réel - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('suivi/tempsreel', []);
        $this->load->view('common/footer', []);
    }

    public function tdb()
    {
        $header = ['pageTitle' => 'Tableau de bord - TimeTracking'];

        //Init des filtres à la date du jour
        $filtreDashboardDu = (isset($this->session->userdata('filtreDashboard')['Du'])) ? $this->session->userdata('filtreDashboard')['Du'] : date('Y-m-d');
        $filtreDashboardAu = (isset($this->session->userdata('filtreDashboard')['Au'])) ? $this->session->userdata('filtreDashboard')['Au'] : date('Y-m-d');

        $currentFiltreDashboardDu = $this->input->get('filtreDashboardDu');
        $currentFiltreDashboardAu = $this->input->get('filtreDashboardAu');
        //var_dump($filtreDashboardDu, $filtreDashboardAu);

        if($currentFiltreDashboardDu){
            $filtreDashboardDu = $currentFiltreDashboardDu;
            //Enregistrement en session
            
        }
        if($currentFiltreDashboardAu){
            $filtreDashboardAu = $currentFiltreDashboardAu;
        }
        $sessionFiltreDashboard = array('Du' => $filtreDashboardDu, 'Au' => $filtreDashboardAu);
        $this->session->set_userdata('filtreDashboard', $sessionFiltreDashboard);
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('dashboard/tdb', ['filtreDashboardDu' => $filtreDashboardDu, 'filtreDashboardAu' => $filtreDashboardAu]);
        $this->load->view('common/footer', []);
    }
    

    /**
     * Afficher la liste des pauses prises pour un utilisateur dans la journée
     */
    public function getListPause()
    {   $idUser = $this->session->userdata('user')['id'];
        $this->load->model('pause_model');
        $listPause = $this->pause_model->getAllUserPause($idUser);
        if(!$listPause) $listPause = [];
        
        echo json_encode(array('data' => $listPause));
    }

    /**
     * Afficher la liste des données pour le temps réel la liste des agents d'un sup pour une journée
     */
    public function getTempsReel($day = false, $return = false)
    {   

        //Date par defaut date du jour
        if(!$day) 
            $day = date('Y-m-d');

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

            //Vérifier que le profil sont cadre , dir , cadre2 si OUI on ajoute les SUPs
            if($this->_userRole == ROLE_CADRE || $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_CLIENT){
                $listSupCampagne = $this->user_model->getListSupByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }
             //Vérifier que le profil sont cadre2 et  direction  si OUI on ajoute les cadres
            if($this->_userRole == ROLE_CADRE2  || $this->_userRole == ROLE_DIRECTION){
                $listSupService = $this->user_model->getListCadreByCampagne($listCampagne);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

            //Vérifier que le profil est adminrh si OUI on ajoute les cadres
            if($this->_userRole == ROLE_ADMINRH || $this->_userRole == ROLE_REPORTING){
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

           //Vérifier que le profil sont cadre , dir , cadre2 si OUI on ajoute les SUPs
            if($this->_userRole == ROLE_CADRE || $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_CLIENT){
                $listSupService = $this->user_model->getListSupByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

            //Vérifier que le profil sont  dir , cadre2 si OUI on ajoute les SUPs
            if( $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_DIRECTION ){
                $listSupService = $this->user_model->getListCadreByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }
            //Vérifier que le profil est adminrh si OUI on ajoute les cadres
            if($this->_userRole == ROLE_ADMINRH || $this->_userRole == ROLE_REPORTING){
                $listCadreService = $this->user_model->getListCadreByService($listService);
                foreach($listCadreService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }
        }
        //On va appeler la fonction getTempsReelAgent par utilisateur avec une boucle selon $lisData
        foreach($listAgent as $agentId){
            $datasAgent = $this->getTempsReelAgent($day, $agentId, true);
            foreach($datasAgent as $dataAgent){
                $datas[] = $dataAgent;
            }
        }
        if($return){
            return $datas;
        }else{
            echo json_encode(array('data' => $datas));
        }
    }

    public function getTempsReelAgent($day, $idUser = false, $return = false)
    {
        if(!$idUser) $idUser = $this->session->userdata('user')['id'];
        
        $this->load->model('shift_model');
        $shift = $this->shift_model->getShiftByUserAndDay($idUser, $day);
        /* On va boucler par shift pour récupérer les données ingress */
        $datas = [];
        if($shift){

            $data = [
                'shift_id' => $shift->shift_id,
                'shift_day' => $shift->shift_day,
                'shift_begin' => $shift->shift_begin,
                'shift_end' => $shift->shift_end,
                'shift_loggedin' => $shift->shift_loggedin,
                'shift_status' => $shift->shift_status,
                'shift_temps_am' => '',
                'shift_temps_pm' => '',
                'pointage_in' => '',
                'pointage_out' => '',
                'pointage_temps_total' => '',
                'pointage_temps_am' => '',    
                'pointage_temps_pm' => '',
                'nb_pause' => 0,
                'usr_prenom' => $shift->usr_prenom,
                'usr_pseudo' => $shift->usr_pseudo,
                'list_campagne' => $shift->list_campagne,      
                'list_service' => $shift->list_service       
            ];

            $details = $this->getProgressData($day, 'return', $idUser);
            if($details){
                //var_dump($details);die;
                $data['shift_temps_am'] = $details['prod_am'];
                $data['shift_temps_pm'] = $details['prod_pm'];
                $data['nb_pause'] = $details['nbPause'];
                $data['total_pause'] = $details['totalPause'];
                $data['ajust_begin'] = $details['debutAjustProd'];
                $data['ajust_end'] = $details['debutAjustProd'];
                $data['ajust_temps_am'] = $details['ajustement_am'];
                $data['ajust_temps_pm'] = $details['ajustement_pm'];
                $data['total_pause_ajustement'] = $details['totalPauseAjustement'];
                $data['total_work'] = $details['totalWork'];
                $data['dayTimeline'] = $details['dayTimeline'];

            }
            $datas[] = $data;
        }
        
        if($return)
        {
            return $datas;
        }else{            
            echo json_encode(array('data' => $datas));
        }
    }
    /**
     * Afficher mes historiques 
     */
    public function historique()
    {

        $filtreMonHistorique = $this->session->userdata('filtre_monhistorique');
        if(!$filtreMonHistorique){
            $filtreMonHistorique = [
                'debut' => date('Y-m-d', strtotime("-7 days")),
                'fin' => date('Y-m-d')
            ];
            $this->session->set_userdata('filtre_monhistorique', $filtreMonHistorique);
        }

        $header = ['pageTitle' => 'Mon historique - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('histo/historique', [
            'user' => $this->session->userdata('user')['id'],
            'filtre' => $filtreMonHistorique 
        ]);
        $this->load->view('common/footer', []);
    }
    /**
     * Afficher les historiques de mes agents
     */
    public function historiqueAgents()
    {
        //Definition filtre date
        $filtreHistoriqueAgents = $this->session->userdata('filtre_historiqueagents');
        if(!$filtreHistoriqueAgents){
            $filtreHistoriqueAgents = [
                'debut' => date('Y-m-d', strtotime("-1 days")),
                'fin' => date('Y-m-d', strtotime("-1 days"))
            ];
            $this->session->set_userdata('filtre_historiqueagents', $filtreHistoriqueAgents);
        }
        //var_dump($filtreHistoriqueAgents); die;
        $header = ['pageTitle' => 'Historiques agents - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('histo/historiqueagents', ['filtre' => $filtreHistoriqueAgents, 'role' => $this->_userRole]);
        $this->load->view('histo/modalhistoriqueagents', []);
        $this->load->view('common/footer', []);
    }

    public function getTdbDatas()
    {
         //Récupérer les campagnes de l'utilisateur connecté
         
         
         $this->load->model('campagne_model');
         $userCampagneList = $this->campagne_model->getUserCampagne($this->_userId);
 
         //Récupérer les services de l'utilisateur connecté 
         $this->load->model('service_model');
         $userServiceList = $this->service_model->getUserService($this->_userId);

        //Chargement des filtres
        $filtreDu = FALSE;
        $filtreAu = FALSE;
        $filtre = $this->session->userdata('filtreDashboard');
        if(is_array($filtre) && !empty($filtre)){
            $filtreDu = (isset($filtre['Du']) ? $filtre['Du'] : date('Y-m-d'));
            $filtreAu = (isset($filtre['Au']) ? $filtre['Au'] : date('Y-m-d'));
        }
        $filtre['user_poids'] = $this->session->userdata('user')['poids'];
        $datas = [];
        
        $datas = $this->_getTdbCampagneServiceDatas($this->_userId, $datas, $filtre, 'campagne');
        $datas = $this->_getTdbCampagneServiceDatas($this->_userId, $datas, $filtre, 'service');

        echo json_encode(array('datas' => $datas));
    }
 
    private function _getTdbCampagneServiceDatas($userId, $datas, $filtre, $type)
    {
        if($type == 'campagne'){
            $this->load->model('campagne_model');
            $userList = $this->campagne_model->getUserCampagne($userId);
        }else if($type == 'service'){
            $this->load->model('service_model');
            $userList = $this->service_model->getUserService($userId);
        }
        //Boucler et récupérer les données
        if($userList){

            foreach($userList as $user){
                $typeId = $user->{$type . '_id'};
                $typeLib = $user->{$type . '_libelle'};

                $this->load->model('user_model');
                $this->load->model('presence_model');
                $filtre['type'] = $type;
                $presenceDatas = $this->presence_model->getPresenceDatasPerDay($typeId, $filtre);
                //var_dump($presenceDatas); die;
                if($presenceDatas){

                    foreach($presenceDatas as $presence){
                        //Definition des datas
                        $presenceDate = $presence->presence_date;
                        $presenceIsOff = ($presence->planning_off == '1') ? true : false;
                        $presenceIsPresent = ($presence->presence_present == '1') ? true : false;
                        $presenceIsConge = ($presence->conge_type != '') ? true : false;
                        $presenceIsIncomplet = ($presence->motifpresence_incomplet == '1') ? true : false;
                        $isPlanifie = ($presence->planning_id) ? true : false;

                        $defaultData = [
                            'day' => $presenceDate,
                            'present' => 0,
                            'absent' => 0,
                            'incomplet' => 0,
                            'conge' => 0,
                            'off' => 0,
                            'pasencorearrive' => 0,
                            'type' => $type,
                            'typeID' => $typeId,
                            'libelle' => $typeLib
                        ];
                        //Récupérer l'info existant si présent ou sinon charger l'element par defaut
                        $currData = isset($datas[$presenceDate][$type][$typeId]) ? $datas[$presenceDate][$type][$typeId] : $defaultData;
                        if($presenceIsOff)
                            $currData['off'] += 1;
                        else if($presenceIsConge)
                            $currData['conge'] += 1;
                        else if($presenceIsPresent) 
                            $currData['present'] += 1;
                        else if($isPlanifie)                        
                            $currData['pasencorearrive'] += 1;
                        else
                            $currData['absent'] += 1; 

                        if($presenceIsIncomplet) $currData['incomplet'] += 1;

                        //Remettre le array
                        $datas[$presenceDate][$type][$typeId] = $currData;
                    }
                }
                
                
            }
           
        }
        return $datas;
    }

    public function getListUsersWhich(){

        $type = $this->input->post('type');
        $typeID = $this->input->post('typeID');
        $date = $this->input->post('date');
        $action = $this->input->post('action');
        
        $datas = [];
        if($date && $type){ 
            $this->load->model('presence_model');
            $filtre['user_poids'] = $this->session->userdata('user')['poids'];
            $filtre['action'] = $action;
            $datas = $this->presence_model->getListUserWhichIs($date, $typeID, $type, $filtre);
        }
        echo json_encode(array('datas' => $datas)); die;
    }


    /**
    * Afficher les histo de production pour tout le monde 
    */
    public function suiviRhProduction()
    {
        
        //Definition filtre date
        $filtreSuiviProduction = $this->session->userdata('filtre_historiqueagents');
        if(!$filtreSuiviProduction){
            $filtreSuiviProduction = [
                'debut' => date('Y-m-d', strtotime("-1 days")),
                'fin' => date('Y-m-d', strtotime("-1 days"))
            ];
            $this->session->set_userdata('filtre_historiqueagents', $filtreSuiviProduction);
        }
        //var_dump($filtreSuiviProduction); die;
        $header = ['pageTitle' => 'Historiques agents - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('suivirh/suivirhproduction', ['filtre' => $filtreSuiviProduction]);
        $this->load->view('histo/modalhistoriqueagents', []);
        $this->load->view('common/footer', []);
    }

    public function exportSuiviRhProduction($du = false, $au = false){

        $fileName = "Suivi_production_du_.xlsx";
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        

        //Header 
        $header = [
            'Date', 
            'Contrat',
            'Matricule',
            'Agent',
            'Campagne',
            'Service',
            'Pointage IN',
            'Pointage OUT',
            'Pointage Prod AM',
            'Pointage Prod PM',
            'Shift IN',
            'Shift OUT',
            'Shift Prod',
            'Shift Pause',
            'Ajustement AM',
            'Ajustement PM',
            'Ajustement Prod',
            'H.Sup',
            'Nb pause'
        ];
        $sheet->fromArray($header, NULL, 'A1');

        //On programme le contenu
        $data = $this->_prepareExportData($du, $au);
        $sheet->fromArray($data, NULL, 'A2');
        //Creation du document
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        $writer->save('php://output');
    }

    private function _prepareExportData($du, $au){

        $sortie = [];

        $this->output->enable_profiler(TRUE);

        $this->load->model('shift_model');
        $shiftData = $this->shift_model->getSuiviRhProdData($du, $au);
        
        $this->load->model('ingress_model');
        $ingress_setex = new Ingress_model();

        $this->load->model('ingressmcr_model');
        $ingress_mcr = new Ingressmcr_model();

        $this->load->model('ingresstnl_model');
        $ingress_tnl = new Ingresstnl_model();
        

        foreach($shiftData as $index => $shift){
            
            $temp_sortie = [];

            $site = $shift->usr_site;
            $ingress = false;
            //Pointage
            if($site == SITE_SETEX){
                $ingress = $ingress_setex;
            }else if($site == SITE_MCR){
                $ingress = $ingress_mcr;
            }else if($site == SITE_TNL){
                $ingress = $ingress_tnl;
            }

            $in = $out = $am = $pm = $prod = $pause = '';

            $userIngressID = $shift->usr_ingress;
            $userHasIngress = ($userIngressID) ? true : false;

            if($ingress && $userHasIngress){

                $pointage = $ingress->getUserPointage($userIngressID, $shift->shift_day);
                if($pointage && is_array($pointage) && !empty($pointage)){
                    $pointage_in = $pointage[0]->att_in;
                    $pointage_out = $this->_getDayOut($pointage[0]);  
                    $pointage_break = $this->_getDayBreak($pointage[0]);
                    $pointage_break_reprise = $this->_getDayBreakReprise($pointage[0]);

                    $in = $pointage_in;
                    $out = $pointage_out;

                    if($pointage_in && $pointage_break){
                        $am = $this->_calculDateDiff($pointage_in, $pointage_break);
                    }
                    if($pointage_break_reprise && $pointage_out){
                        $pm = $this->_calculDateDiff($pointage_break_reprise, $pointage_out);
                    }
                    
                    $prod = $pointage[0]->workhour;  
                }
            }

          
            $shiftData[$index]->pointage_in = $in;
            $shiftData[$index]->pointage_in = $in;
            $shiftData[$index]->pointage_out = $out;
            $shiftData[$index]->pointage_temps_am = $am;
            $shiftData[$index]->pointage_temps_pm = $pm;
            $shiftData[$index]->pointage_prod = $prod;

            $temp_sortie = [
                $shift->shift_day,
                $shift->contrat,
                $shift->matricule,
                $shift->agent,
                $shift->listcampagne,
                $shift->listservice,
                $shift->pointage_in,
                $shift->pointage_out,
                $shift->pointage_temps_am,
                $shift->pointage_temps_pm,
                $shift->shift_begin,
                $shift->shift_end,
                $shift->shiftTotal,
                $shift->total_pause,
                $shift->shift_ajustbegin,
                $shift->shift_ajustend,
                $shift->ajustTotal,
                '',
                $shift->nb_pause,
            ];

            $sortie[] = $temp_sortie;
            
        }

        //var_dump($shiftData); die;
        
        return $sortie;
    }

    /**
     * SOus menu absences anormales pour admin-rh
     */
    public function absencesanormales()
    {
        $header = ['pageTitle' => 'Absences anormales - TimeTracking'];

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('dashboard/absencesanormales', []);
        $this->load->view('common/footer', []);
    }

    public function getAbsencesAnormales()
    {
        $this->load->model('presence_model');
        $list_absencesanormales = $this->presence_model->getAbsencesAnormales();
        echo json_encode(array('data' => $list_absencesanormales));
    }


    /**
     * Envoye du message
     */

     public function envoye() {

        $header = ['pageTitle' => 'Suivi présence - TimeTracking'];

        $this->load->model('role_model');
        $listRole = $this->role_model->getAllRole();
        $this->load->model('message_model');
 
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('message/envoye' , array('listRole' => $listRole ,  'top' => $this->_top));
        $this->load->view('common/footer', []);      
   
    }

    /**
     * Affichez les message
     */
    public function recue() 
    {
        
        $header = ['pageTitle' => 'Suivi présence - TimeTracking'];

        $this->load->model('message_model');
        $msg_user = $this->input->post('username_msg');
        $all_exepideur = $this->message_model->select_all_expediteur();
        $roleUser = $this->session->userdata('user')['role'];
        $results = $this->message_model->select_role_libelle($roleUser);
        $nb_msg = $this->message_model->Number_msg();
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('message/recue' ,array(
            'nb_msg'=>$nb_msg,
            'all_exepideur' =>$all_exepideur,
            'role_data' => $results,
        ));
        $this->load->view('common/footer', []);
        
    }

    /**
     * Affichez lites_sms
     */
    public function listes() 
    {
        
        $header = ['pageTitle' => 'Suivi présence - TimeTracking'];
 
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('message/listes_msg');
        $this->load->view('common/footer', []);
        
    }
    

}