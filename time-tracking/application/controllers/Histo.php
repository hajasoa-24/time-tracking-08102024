<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Histo extends MY_Controller {

    
    public function index()
    {
        $this->historique();
    }

    /**
     * Afficher mes historiques 
     */
    public function historique()
    {

        $header = ['pageTitle' => 'Mon historique - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('histo/historique', []);
        $this->load->view('common/footer', []);
    }


    /**
     * Prendre un shift d'un utilisateur
     */
    public function getHistorique($idUser = false, $return = false, $filtre = [])
    {
        if(!$idUser) $idUser = $this->session->userdata('user')['id'];
        
        $this->load->model('shift_model');
        $listShift = $this->shift_model->getAllShift($idUser, $filtre);

        if(!$listShift) $listShift = [];
        /* On va boucler par shift pour récupérer les données ingress */
        $datas = [];
        
        
        foreach($listShift as $shift){

            $site = $shift->usr_site;
            $ingress = false;
           if($site == SITE_SETEX){
                $this->load->model('ingress_model');
                $ingress = new Ingress_model();
            }else if($site == SITE_MCR){
                $this->load->model('ingressmcr_model');
                $ingress = new Ingressmcr_model();
            }else if($site == SITE_TNL){
                $this->load->model('ingresstnl_model');
                $ingress = new Ingresstnl_model();
            }


            
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
                'usr_pseudo' => $historique->usr_pseudo,
                'usr_matricule' => $shift->usr_matricule,
                'usr_contrat' => $shift->usr_contrat,
                'site_contrat' => $shift->site_contrat,
                'list_campagne' => $shift->list_campagne,      
                'list_service' => $shift->list_service      
            ];

            $userIngressId = $shift->usr_ingress;
            $jour = $shift->shift_day;
            
            if($ingress && $userIngressId){
                $pointage = $ingress->getUserPointage($userIngressId, $jour);
                 //var_dump($pointage, $userIngressId, $jour);
                if($pointage && is_array($pointage) && !empty($pointage)){
                    $pointage_in = $pointage[0]->att_in;
                    $pointage_out = $this->_getDayOut($pointage[0]);  
                    $pointage_break = $this->_getDayBreak($pointage[0]);
                    $pointage_break_reprise = $this->_getDayBreakReprise($pointage[0]);

                    $data['pointage_in'] = $pointage_in;
                    $data['pointage_out'] = $pointage_out;

                    if($pointage_in && $pointage_break){
                        $data['pointage_temps_am'] = $this->_calculDateDiff($pointage_in, $pointage_break);
                    }
                    if($pointage_break_reprise && $pointage_out){
                        $data['pointage_temps_pm'] = $this->_calculDateDiff($pointage_break_reprise, $pointage_out);
                    }
                    $data['pointage_temps_total'] = $pointage[0]->workhour;
                    
                }
            }

            $details = $this->getProgressData($jour, 'return', $idUser);
            if($details){
                $total_prod = str_replace('h',':',$details['totalWork']);
                $total_prod = str_replace('mn','', $total_prod);
                $total_prod_ajustement = str_replace('h',':',$details['totalWorkAjustement']);
                $total_prod_ajustement = str_replace('mn','', $total_prod_ajustement);
                //var_dump($total_prod / 3600);die;
                $data['shift_temps_am'] = $details['prod_am'];
                $data['shift_temps_pm'] = $details['prod_pm'];
                $data['shift_prod'] = $total_prod;
                $data['nb_pause'] = $details['nbPause'];
                $data['total_pause'] = $details['totalPause'];
                $data['ajust_begin'] = $details['debutAjustProd'];
                $data['ajust_end'] = $details['debutAjustProd'];
                $data['ajust_temps_am'] = $details['ajustement_am'];
                $data['ajust_temps_pm'] = $details['ajustement_pm'];
                $data['total_pause_ajustement'] = $details['totalPauseAjustement'];
                $data['total_work_ajustement'] = $total_prod_ajustement;
                
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
     * Récupérer les historiques des agents associés à un superviseur
     */
    public function getHistoriqueAgentsSup($return = false)
    {
        $this->load->model('user_model');
        $this->load->model('campagne_model');
        $this->load->model('service_model');

        //Recuperation des filtres 
        $filtre = $this->session->userdata('filtre_historiqueagents');
       
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

        //On va appeler la fonction getHistorique en haut par utilisateur avec une boucle selon $lisData
        foreach($listAgent as $agentId){
            $datasAgent = $this->getHistorique($agentId, true, $filtre);
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

    public function getModalHistoriqueData()
    {
        $shift = $this->input->post('shift_id');
        $user = $this->input->post('user');
        //recuperation des données
        $this->load->model('shift_model');
        $dataShift = $this->shift_model->getShiftById($shift);
        $data = [
            'jour' => '',
            'debut' => '00:00',
            'fin' => '00:00'
        ];
        if($dataShift){
            //Liste des pauses
            $this->load->model('pause_model');
            $listPauseBrut = $this->pause_model->getUserPauseByShift($dataShift->shift_id, $dataShift->shift_day);
            $listPause = [];
            foreach($listPauseBrut as $pause){
                $listPause[] = [
                    'pause_id' => $pause->pause_id,
                    'pause_begin' => ($pause->pause_begin) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($pause->pause_begin)) : null,
                    'pause_end' => ($pause->pause_end) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($pause->pause_end)) : null,
                    'pause_libelle' => $pause->typepause_libelle,
                    'pause_ajustbegin' => ($pause->pause_ajustbegin) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($pause->pause_ajustbegin)) : null,
                    'pause_ajustend' => ($pause->pause_ajustend) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($pause->pause_ajustend)) : null,
                ];
            }
            //var_dump($listPause);die;
            $data = [
                'id' => $dataShift->shift_id, 
                'jour' => $dataShift->shift_day,
                'debut' => ($dataShift->shift_begin) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($dataShift->shift_begin)) : null,
                'fin' => ($dataShift->shift_end) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($dataShift->shift_end)) : null,
                'ajustdebut' => ($dataShift->shift_ajustbegin) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($dataShift->shift_ajustbegin)) : null,
                'ajustfin' => ($dataShift->shift_ajustend) ? strftime('%Y-%m-%dT%H:%M:%S', strtotime($dataShift->shift_ajustend)) : null,
                'listPause' => $listPause
            ];

        }
        echo json_encode([
            'error' => false,
            'data' => $data
        ]);
    }

    /**
     * Enregistrement des ajustements effectués par un responsable
     */
    public function saveAjustement()
    {
        $postedData = $this->input->post();
        //var_dump($postedData); die;
        if($postedData && isset($postedData['send']) && $postedData['send'] == 'sent' && $postedData['shiftID']){
            $dataAjustement = [
                'shift_ajustBegin' => $postedData['ajustBegin'],
                'shift_ajustEnd' => $postedData['ajustEnd'],
                'shift_ajustmodifier' => $this->_userId
            ];
            $whereAjustement = [
                'shift_id' => $postedData['shiftID']
            ];
            $this->load->model("shift_model");
            $update = $this->shift_model->setAjustementSup($dataAjustement, $whereAjustement);

            //Enregistrement des pauses
            $this->load->model('pause_model');
            foreach($postedData['ajustpause_pause'] as $k => $pause){
                $pause_data = [
                    'pause_ajustbegin' => $postedData['ajustpause_begin'][$k],
                    'pause_ajustend' => $postedData['ajustpause_end'][$k]
                ];
                $where_pause = [
                    'pause_id' => $postedData['ajustpause_pause'][$k]
                ];
                $this->pause_model->setAjustementPauseSup($pause_data, $where_pause);
            }

            //Enregistrement des Heures supplémentaires
            $hsData = [
                "hs_responsable" => $this->_userId,
                "hs_begin" => $postedData['hs_begin'],
                "hs_end" => $postedData['hs_end'],
                "hs_datecrea" => date("Y-m-d H:i:s"),
                "hs_datemodif" => date("Y-m-d H:i:s")
            ];
            $this->load->model('heuresup_model');
            $this->heuresup_model->insertHS($hsData);

            if($update){
                redirect('dashboard/historiqueAgents');
            }
        }
    }

    public function AddPauseAjustement()
    {
        $type_pause = $this->input->post('lib');
        $pause_begin = $this->input->post('begin');
        $pause_end = $this->input->post('end');
        $shift_id = $this->input->post('shift');

        if($type_pause && $pause_begin && $pause_end){

            $data = [
                'pause_type' => $type_pause,
                'pause_shift' => $shift_id,
                'pause_begin' => null,
                'pause_ajustbegin' => $pause_begin,
                'pause_end' => null,
                'pause_ajustend' => $pause_end,
                'pause_etat' => 1,
                'pause_datecrea' => date('Y-m-d H:i:s'),
                'pause_datemodif' => date('Y-m-d H:i:s')
            ];
            $this->load->model('pause_model');
            $pause_id = $this->pause_model->setPause($data);

            if($pause_id){
                echo json_encode(array('err' => false, 'pause_id' => $pause_id));
            }else{
                echo json_encode(array('err' => true));
            }
        }
    }

    public function setFiltreHistoriqueAgents()
    {
        $filtre_debut = $this->input->post('debut');
        $filtre_fin = $this->input->post('fin');
        //Mise à jour session
        $filtreHistoriqueAgents = [
            'debut' => $filtre_debut,
            'fin' => $filtre_fin
        ];
        $this->session->set_userdata('filtre_historiqueagents', $filtreHistoriqueAgents);
        echo json_encode(['err' => false]);
    }

    public function setFiltreHistorique()
    {
        $filtre_debut = $this->input->post('debut');
        $filtre_fin = $this->input->post('fin');
        //Mise à jour session
        $filtreMonHistorique = [
            'debut' => $filtre_debut,
            'fin' => $filtre_fin
        ];
        $this->session->set_userdata('filtre_monhistorique', $filtreMonHistorique);
        echo json_encode(['err' => false]);
    }
    

    /**
     * Récupérer les historiques de prod pour tout le monde
     */
    public function getSuiviProd($return = false)
    {
        $this->load->model('user_model');
        $this->load->model('campagne_model');
        $this->load->model('service_model');

        //Recuperation des filtres 
        $filtre = $this->session->userdata('filtre_historiqueagents');
       
        $currentSup = $this->session->userdata('user')['id'];
        $listAgent = [];

        $datas = [];
        

        $allUser = $this->user_model->getAllUser(true);
        foreach($allUser as $usr){
            $listAgent[] = $usr->usr_id;
        }

        //On va appeler la fonction getHistorique en haut par utilisateur avec une boucle selon $lisData
        foreach($listAgent as $agentId){
            $datasAgent = $this->getHistorique($agentId, true, $filtre);
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

    public function getUsersHistorique($user = false){

        $filtre = $this->session->userdata('filtre_historiqueagents');
        
        $this->load->model('campagne_model');
        $this->load->model('service_model');
        
        if($user !== false){
            $filtre = $this->session->userdata('filtre_monhistorique');
            $filtre['user'] = $user;
        }else{
            //On active le système de poids quand ce n'est pas pour un user specifique
            $userPoids = $this->session->userdata('user')['poids'];
            $filtre['user_poids'] = $userPoids;
        } 

        //On définit une valeur par défaut des filtres de date si non définit
        if(!isset($filtre['debut']) || (isset($filtre['debut']) && empty($filtre['debut']))){
            $filtre['debut'] = date('Y-m-d', strtotime(' -7 days'));
        }
        if(!isset($filtre['fin']) || (isset($filtre['fin']) && empty($filtre['fin']))){
            $filtre['fin'] = date('Y-m-d');
        }

        $filtre['list_campagne'] = $filtre['list_service'] = [];
        $currentSup = $this->session->userdata('user')['id'];
        

        $listCampagneSup = $this->campagne_model->getUserCampagne($currentSup);
        if($listCampagneSup){
            foreach($listCampagneSup as $campagne){
                $filtre['list_campagne'][] = $campagne->campagne_id;
            }
        }
        $listServiceSup = $this->service_model->getUserService($currentSup);
        if($listServiceSup){
            foreach($listServiceSup as $service){
                $filtre['list_service'][] = $service->service_id;
            }
        }

        $this->load->model('shift_model');
        $listHistorique = $this->shift_model->getUsersHistorique($filtre);
        //Si données récupérés, on boucle pour former le tableau
        $datas = [];
        if($listHistorique){
            $shiftAM = $shiftPM = false;
            $nbPause = 0;
            $totalPause = $totalPauseAjust = '00:00:00';
            $ajustAM = $ajustPM = false;
            $totalProd = $ajustProd = false;
            $pauseDej = $repriseDej = false;
            $newShift = true;
            foreach($listHistorique as $index => $historique){
                if($historique->shift_id == '82138') {
                    //var_dump($historique);
                    //die;
                }
                $currentShift = $historique->shift_id;
                
                if($historique->pointage_in){}
                //bloc retard
                $retard = '';
                if($historique->retard){
                    $isRetard = strtotime($historique->retard) - strtotime('00:00:00');
                    if($isRetard > 0) $retard =  $historique->retard;
                    else $retard = '';
                    
                }
                //End bloc retard
                $data = [
                    'shift_id' => $historique->shift_id,
                    'shift_day' => $historique->shift_day,
                    'site_contrat' => $historique->site_contrat,
                    'usr_matricule' => $historique->usr_matricule,
                    'usr_prenom' => $historique->usr_prenom,
                    'usr_pseudo' => $historique->usr_pseudo,
                    'list_campagne' => $historique->list_campagne,
                    'list_service' => $historique->list_service,
                    'entree_planifie' => $historique->planning_entree,
                    'pointage_in' => $historique->pointage_in,
                    'pointage_out' => $historique->pointage_done,
                    //'retard' => ($historique->pointage_in) ? $retard : '',
                    'retard' => $retard,
                    'pointage_temps_am' => '',
                    'pointage_temps_pm' => '',
                    'pointage_temps_total' => '',
                    'shift_begin' => $historique->shift_loggedin,
                    'shift_end' => $historique->shift_end,
                    'shift_temps_am' => '',
                    'shift_temps_pm' => '',
                    'shift_prod' => '',
                    'total_pause' => '',
                    'ajust_temps_am' => '',
                    'ajust_temps_pm' => '',
                    'total_work_ajustement' => '',
                    'total_pause_ajustement' => '',
                    'nb_pause' => ''
                ];
                
                //Bloc pointage
                $pointage = (object)[
                    'att_in' => $historique->pointage_in,
                    'att_break' => $historique->pointage_break,
                    'att_resume' => $historique->pointage_resume,
                    'att_out' => $historique->pointage_out,
                    'att_ot' => $historique->pointage_ot,
                    'att_done' => $historique->pointage_done
                ];
                $pointage_in = $pointage->att_in;
                $pointage_out = $this->_getDayOut($pointage);  
                $pointage_break = $this->_getDayBreak($pointage);
                $pointage_break_reprise = $this->_getDayBreakReprise($pointage);

                $data['pointage_in'] = $pointage_in;
                $data['pointage_out'] = $pointage_out;

                if($pointage_in && $pointage_break){
                    $data['pointage_temps_am'] = $this->_calculDateDiff($pointage_in, $pointage_break);
                }
                if($pointage_break_reprise && $pointage_out){
                    $data['pointage_temps_pm'] = $this->_calculDateDiff($pointage_break_reprise, $pointage_out);
                }
                $data['pointage_temps_total'] = $historique->pointage_workhour;
                //END BLoc pointage

                //Bloc de calcul des temps AM et PM
                $debutJournee = ($historique->shift_begin) ? $historique->shift_begin : $historique->shift_loggedin;
                $miJournee = date('Y-m-d H:i:s', strtotime($debutJournee . ' + 4 hours'));
                $finJournee = ($historique->shift_end) ? $historique->shift_end : false;
                $isPause = ($historique->pause_begin) ? true : false;

                $ajustBegin = ($historique->shift_ajustbegin && $historique->shift_ajustbegin != "0000-00-00 00:00:00") ? $historique->shift_ajustbegin : $debutJournee;
                $ajustEnd = ($historique->shift_ajustend && $historique->shift_ajustend != "0000-00-00 00:00:00") ? $historique->shift_ajustend : $finJournee;

               
                if($newShift){
                    $shiftAM = $shiftPM = '00:00:00';
                    $nbPause = 0;
                    $totalPause = $totalPauseAjust = '00:00:00';
                    $ajustAM = $ajustPM = false;
                    $totalProd = $ajustProd = '00:00:00';
                    $pauseDej = $repriseDej = false;
                    
                    if($isPause){
                        $pauseDej = $historique->pause_begin;
                        $repriseDej = $historique->pause_end;

                        $ajustPauseDej = ($historique->pause_ajustbegin && $historique->pause_ajustbegin != "0000-00-00 00:00:00") ? $historique->pause_ajustbegin : $historique->pause_begin;
                        $ajustRepriseDej = ($historique->pause_ajustend && $historique->pause_ajustend != "0000-00-00 00:00:00") ? $historique->pause_ajustend : $historique->pause_end;
                    }else{
                        $pauseDej = $finJournee;
                        $repriseDej = $finJournee;

                        $ajustPauseDej = $ajustEnd;
                        $ajustRepriseDej = $ajustEnd;
                    }

                    $newShift = false;
                    
                }else{
                    if($historique->pause_begin <= $miJournee && $historique->pause_type != '2'){
                        $pauseDej = $historique->pause_begin;
                        $repriseDej = $historique->pause_end;



                        $ajustPauseDej = ($historique->pause_ajustbegin && $historique->pause_ajustbegin != "0000-00-00 00:00:00") ? $historique->pause_ajustbegin : $historique->pause_begin;
                        $ajustRepriseDej = ($historique->pause_ajustend && $historique->pause_ajustend != "0000-00-00 00:00:00") ? $historique->pause_ajustend : $historique->pause_end;
                    }
                    //$shiftAM = strtotime($lastEndPause)
                    
                }
                //Si type pause est déjeuner
                if($historique->pause_type == '2'){
                    $pauseDej = $historique->pause_begin;
                    $repriseDej = $historique->pause_end;

                    $ajustPauseDej = ($historique->pause_ajustbegin && $historique->pause_ajustbegin != "0000-00-00 00:00:00") ? $historique->pause_ajustbegin : $pauseDej;
                    $ajustRepriseDej = ($historique->pause_ajustend && $historique->pause_ajustend != "0000-00-00 00:00:00") ? $historique->pause_ajustend : $repriseDej;
                }
                //S'il y a eu pause 
                if($isPause){

                    if($historique->pause_end){
                        //Si la pause est terminée, alors on compte te temps de la pause
                        $timePause = ($totalPause) ? date('H:i:s', strtotime($totalPause)) : '00:00:00';
                        $totalPause = $this->_calculateTime($timePause, $historique->pause_duration);
                        if($historique->shift_id == '94574') {
                            //var_dump($historique->pause_duration_ajust);
                            //die;
                        }
                        $timePauseAjust = ($totalPauseAjust) ? date('H:i:s', strtotime($totalPauseAjust)) : '00:00:00';
                        $totalPauseAjust = $this->_calculateTime($timePauseAjust, ($historique->pause_duration_ajust && $historique->pause_duration_ajust != "00:00:00") ? $historique->pause_duration_ajust : $historique->pause_duration);

                    }
                    //On ajoute toutes les pauses dans le comptage du nb de pause
                    $nbPause += 1;
                }

                $nextShift = (isset($listHistorique[$index+1])) ? $listHistorique[$index+1]->shift_id : false;

                if($currentShift != $nextShift){
                    $shiftAM = $this->_getDateDiffInHour($pauseDej, $debutJournee, 'hm');
                    $shiftPM = $this->_getDateDiffInHour($finJournee, $repriseDej, 'hm');
                    if($historique->shift_id == '82383') {
                        //var_dump($totalPause);
                        //die;
                    }

                    $ajustAM = $this->_getDateDiffInHour($ajustPauseDej, $ajustBegin, 'hm');
                    $ajustPM = $this->_getDateDiffInHour($ajustEnd, $ajustRepriseDej, 'hm');

                    $totalTime = $this->_getDateDiffInHour($finJournee, $debutJournee, 'hm');
                    $totalTimeAjust = $this->_getDateDiffInHour($ajustEnd, $ajustBegin, 'hm');

                    $totalProd = $this->_getDateDiffInHour($totalTime, date('H:i', strtotime($totalPause)), 'hm');

                    $ajustProd = $this->_getDateDiffInHour($totalTimeAjust, date('H:i', strtotime($totalPauseAjust)), 'hm');

                    $data['shift_temps_am'] = $shiftAM;
                    $data['shift_temps_pm'] = $shiftPM;
                    $data['ajust_temps_am'] = $ajustAM;
                    $data['ajust_temps_pm'] = $ajustPM;
                    $data['nb_pause'] = $nbPause;
                    $data['total_pause'] = date('H:i', strtotime($totalPause));
                    $data['shift_prod'] = $totalProd;
                    $data['total_work_ajustement'] = $ajustProd;
                    $data['total_pause_ajustement'] = date('H:i', strtotime($totalPauseAjust));


                    $datas[] = $data;

                    $newShift = true;
                    $lastEndPause = false;
                    $nbPause = 0;
                    $totalPause = $totalProd = $totalPauseAjust = false;
                    $shiftAM = $shiftPM  = $ajustAM = $ajustPM = false;
                }
            }
        }
        echo json_encode(['data' => $datas]);

    }


    private function _calculateTime($time1, $time2) {

        //var_dump($time1, $time2);
        $time1 = date('H:i:s',strtotime($time1));
        $time2 = date('H:i:s',strtotime($time2));
        $times = array($time1, $time2);
        $seconds = 0;
        foreach ($times as $time)
        {
            list($hour,$minute,$second) = explode(':', $time);
            $seconds += $hour*3600;
            $seconds += $minute*60;
            $seconds += $second;
        }
        $hours = floor($seconds/3600);
        $seconds -= $hours*3600;
        $minutes  = floor($seconds/60);
        $seconds -= $minutes*60;
        if($seconds < 9)
        {
        $seconds = "0".$seconds;
        }
        if($minutes < 9)
        {
        $minutes = "0".$minutes;
        }
            if($hours < 9)
        {
        $hours = "0".$hours;
        }
        return $hours . ":" . $minutes . ":" . $seconds;
          
    }


   

}
