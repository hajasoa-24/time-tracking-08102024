<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $_isAuthenticated = false,
            $_userRole,
            $_userId,
            $_userState,
            $_userActivity,
            $_statusLib,
            $_top,
            $_sidebar;

    private $_userInfos;

    protected function _calculDateDiff($end, $begin)
    {
        $dateEnd = new Datetime($end);
        $dateBegin = new Datetime($begin);
        $interval = $dateBegin->diff($dateEnd);
        return $interval->format('%H:%I');
    }

    protected function _getDayBreak($day)
    {
        if(!$day->att_done)
            return $day->att_break;
        
            return $day->att_out;
    }
    protected function _getDayOut($day)
    {
        if(!$day->att_out)
            return $day->att_break;
            
        if(!$day->att_done) 
            return $day->att_out;

        return $day->att_done;
    }

    protected function _getDayBreakReprise($day)
    {
        if(!$day->att_done) 
            return $day->att_resume; 

        return $day->att_ot;
    }

    public function __construct()
    {

        parent::__construct();
        $this->_userInfos = $this->session->userdata('user');
        if(!$this->_userInfos){
            
            redirect('auth/login');
        }
        $this->_isAuthenticated = true;
        $this->_userRole = $this->session->userdata('user')['role'];
        $this->_userId = $this->_userInfos['id'];
        //Verification du shift en cours
        $this->syncShift();
        $this->syncActivity();

        //Tranfert userState d'une variable session vers _userState
        if(!$this->session->userdata('userState')){
            $this->session->set_userdata('userState', READYTOWORK);
        }
        $this->_userState = $this->session->userdata('userState');

        if($this->session->userdata('userActivity')=== FALSE){
            $this->session->set_userdata('userActivity', MCP_STATUS_TERMINE);
        }
        $this->_userActivity = $this->session->userdata('userActivity');

        $this->load->model('shiftstatus_model');
        $status = $this->shiftstatus_model->getStatusById($this->_userState);
        $this->_statusLib = '';
        if($status) $this->_statusLib = $status->ss_libelle;
        

        $this->getTopMenuInfos();
        $this->setActiveMenu();
    }

    /**
     * FOnction permettant de créer un nouveau shift si la précédente est terminée ainsi que le délai d'attente entre 2 shift respecté
     * Delai d'attente défini par MAXSHIFTHOUR
     */
    public function syncShift()
    {
        $userState = READYTOWORK;
        //Pour les non admin, on gère le shift
        if($this->_userRole !== ROLE_ADMIN && $this->_userRole !== ROLE_ADMINRH && $this->_userRole !== ROLE_CADRE2 && $this->_userRole !== ROLE_COSTRAT && $this->_userRole !== ROLE_CLIENT && $this->_userRole !== ROLE_REPORTING){
            $insert = true; 
            $this->load->model('shift_model');
            $this->shift_model->setUserId($this->_userId);
            //On récupère le numéro de shift en cours si c'est toujours valide
            $currShift = $this->shift_model->getCurrentShift();
            
            //Si pas de shift, on va créé un nouveau shift
            if(!$currShift){
                
                $shiftData = [
                    'shift_userid' => $this->_userId,
                    'shift_day' => date('Y-m-d'),
                    'shift_begin' => null,
                    'shift_end' => null,
                    'shift_ajustbegin' => null,
                    'shift_ajustend' => null,
                    //Readytowork par defaut
                    'shift_status' => $userState,
                    'shift_loggedin' => date('Y-m-d H:i:s'),
                    'shift_datecrea' => date('Y-m-d H:i:s'),
                    'shift_datemodif' => date('Y-m-d H:i:s')
                ];
                if($insert = $this->shift_model->setCurrentShift($shiftData)) {
                    $shiftData['shift_id'] = $insert;
                    $currShift = (Object)$shiftData;
                }
            }

            if($insert){
                $userState = $currShift->shift_status;
                //On insère les infos du shift en session
                $this->session->set_userdata('shift', [
                    'user' => $this->_userId, 
                    'id' => $currShift->shift_id,
                    'day' => $currShift->shift_day,
                    'loggedin' => $currShift->shift_loggedin,
                    'beginprod' => $currShift->shift_begin,
                    'endprod' => $currShift->shift_end,
                    'ajustbegin' => $currShift->shift_ajustbegin,
                    'ajustend' => $currShift->shift_ajustend
                ]);
            }
        }
        $this->session->set_userdata('userState', $userState);
    }

    public function syncActivity()
    {
        $userActivity = MCP_STATUS_TERMINE;
        $this->session->set_userdata('userActivityId', NULL);
        //Pour les non admin, on gère le shift
        if($this->_userRole !== ROLE_ADMIN && $this->_userRole !== ROLE_ADMINRH && $this->_userRole !== ROLE_CADRE2 && $this->_userRole !== ROLE_COSTRAT && $this->_userRole !== ROLE_CLIENT && $this->_userRole !== ROLE_REPORTING){
            $insert = true; 
            $this->load->model('etp_model');
            $this->etp_model->setUser($this->_userId);
            
            $currActivity = $this->etp_model->getUserActivityByStatus(MCP_STATUS_ENCOURS);

            if($currActivity !== false){
                foreach($currActivity as $activity){
                    $userActivity = $activity->mcp_status;
                    $this->session->set_userdata('userActivityId', $activity->mcp_id);
                    $this->session->set_userdata('userActivityCampagne', $activity->campagne_libelle);
                    $this->session->set_userdata('userActivityMission', $activity->mission_libelle);
                    $this->session->set_userdata('userActivityProcess', $activity->process_libelle);
                    $this->session->set_userdata('userActivityQuantity', $activity->mcp_quantite);
                }
            }  
        }
        $this->session->set_userdata('userActivity', $userActivity);
        
    }

    public function getTopMenuInfos()
    {
        $this->load->model('typepause_model');
        $listTypePause = $this->typepause_model->getAllTypePause();

        $this->load->model('pause_model');
        $listPause = $this->pause_model->getAllPause();

        /* Recuperation des données pour l'activite */
        $this->load->model('campagne_model');
        $listCampagne = $this->campagne_model->getUserCampagne($this->_userId);

        /* Recuperation des données pour transport */
        $this->load->model('Transport_model');
        $listHeure = $this->Transport_model->getHeure();
        $listeAxe = $this->Transport_model->getAxe();
        $listAllAxe = $this->Transport_model->getAllAxe();
        
        $topInfos = [
            'userid' => $this->_userId,
            'username' => $this->_userInfos['username'],
            'userState' => $this->_statusLib,
            'listTypePause' => $listTypePause,
            'listPause' => $listPause,
            'role' => $this->_userRole,
            'listCampagne' => $listCampagne,
            'heuretransport' => $listHeure,
            'axetransport' => $listeAxe,
            'listAxe' => $listAllAxe
        ];

        $this->_top = $topInfos;
    }

    public function setActiveMenu()
    {
        $menu = "";
        //$isAuthorized = true;
        $isAuthorized = $this->isAuthorized(CAMPAGNE_HOMELAND, 'campagne'); 
        $isRC = $this->isAuthorized(CAMPAGNE_RELAISCOLIS, 'campagne'); 
        $currentURI = $this->uri->uri_string();
        if($currentURI == 'dashboard/progression' || $currentURI == 'dashboard/index'){
            $menu = 'progression';
        }else if($currentURI == 'dashboard/tempsreel'){
            $menu = "tempsreel";
        }else if($currentURI == 'user/profil'){
            $menu = "profil";
        }else if($currentURI == 'user/listUtilisateur'){
            $menu = "utilisateur";
        }else if($currentURI == 'campagne/listCampagne'){
            $menu = "campagne";
        }else if($currentURI == 'service/listService'){
            $menu = "service";
        }else if($currentURI == 'dashboard/historique'){
            $menu = "historique";
        }else if($currentURI == 'dashboard/historiqueAgents'){
            $menu = "historiqueagents";
        }else if($currentURI == 'presence/index' || $currentURI == 'presence/suiviPresence'){
            $menu = "presence";
        }else if($currentURI == 'presence/suiviRetard'){
            $menu = "retard";
        }else if($currentURI == 'dasboard/tdb'){
            $menu = "dasboard";
        }else if($currentURI == 'ip/listIp'){
            $menu = "ip";
        }else if($currentURI == 'presence/histoPresence'){
            $menu = "histopresence";
        }else if($currentURI == 'dashboard/tdb'){
            $menu = "dashboard";
        }else if($currentURI == 'pointage/pointageSecurite'){
            $menu = "pointage-securite";
        }else if($currentURI == 'pointage/pointageTransport'){
            $menu = "pointage-transport";
        }else if($currentURI == 'pointage/pointageMedical'){
            $menu = "pointage-medical";
        }else if($currentURI == 'pointage/pointageAutres'){
            $menu = "pointage-autres";
        }else if($currentURI == 'presence/suiviRhPresence'){
            $menu = "rh-presence";
        }else if($currentURI == 'dashboard/suiviRhProduction'){
            $menu = "rh-production";
        }elseif($currentURI == 'conges/mesConges'){
            $menu = 'mesconges';
        }elseif($currentURI == 'conges/congesAValider'){
            $menu = 'conges-a-valider';
        }elseif($currentURI == 'conges/congesATraiter'){
            $menu = 'conges-a-traiter';
        }elseif($currentURI == 'conges/gestionSoldesDroits'){
            $menu = 'soldes-et-droits';
        }elseif($currentURI == 'conges/histoSoldesDroits'){
            $menu = 'histo-soldes-et-droits';
        }elseif($currentURI == 'retard/histoRetards'){
            $menu = 'historetards';
        }elseif($currentURI == 'tache/taches'){
            $menu = 'taches';
        }elseif($currentURI == 'homeland/reportHomeland'){
            $menu = 'reportHomeland';
            
        }elseif($currentURI == 'homeland/suiviTemps'){
            $menu = 'compte-rendu';
           
        }elseif($currentURI == 'dashboard/absencesanormales'){
            $menu = 'absencesanormales';  
        }elseif($currentURI == 'agence/gestionagence'){
            $menu = 'gestion-agence';  
        }elseif($currentURI == 'agence/tableauagence'){
            $menu = 'tableau-agence';  
        }elseif($currentURI == 'mission/listmission'){
            $menu = 'mission';  
        }elseif($currentURI == 'process/listprocess'){
            $menu = 'process';  
        }elseif($currentURI == 'proprio/listproprio'){
            $menu = 'proprio';  
        }elseif($currentURI == 'etp/ressource'){
            $menu = 'etpressource';  
        }elseif($currentURI == 'etp/validationRessource'){
            $menu = 'validationressource';  
        }elseif($currentURI == 'etp/suivietp'){
            $menu = 'suivietp';  
        }elseif($currentURI == 'etp/dooneesressource'){
            $menu = 'dooneesressource';  
        }elseif($currentURI == 'transport/suiviTransport'){
            $menu = 'suivitransport';  
        }elseif($currentURI == 'transport/assignation'){
            $menu = 'assignationaxe';  
        }elseif($currentURI == 'primeprofil/list'){
            $menu = 'gestionprofil';  
        }elseif($currentURI == 'primecritere/listCritere'){
            $menu = 'gestioncritere';  
        }elseif($currentURI == 'prime/primejournaliere'){
            $menu = 'primejournaliere';  
        }elseif($currentURI == 'primeproduction/production'){
            $menu = 'suiviprime';  
        }
        elseif($currentURI == 'dashboard/recue'){
            $menu = 'recue';  
        }
        elseif($currentURI == 'dashboard/envoye'){
            $menu = 'envoye';  
        }
        elseif($currentURI == 'dashboard/listes'){
            $menu = 'listes';  
        }

        $this->_sidebar = [
            'currentMenu' => $menu, 
            'role' => $this->_userRole,
            'isAuthorized' => $isAuthorized,
            'isRC' => $isRC,
            'listcampagne' => (isset($this->session->userdata('user')['listcampagne'])) ? $this->session->userdata('user')['listcampagne'] : false,
            'listservice' => (isset($this->session->userdata('user')['listservice'])) ? $this->session->userdata('user')['listservice'] : false
        ];
        
    }

    /**
     * Fonction permettant de dynamiser les données du progressbar
     */
    public function getProgressData($day = false, $format="json", $userId = false)
    {
        $shiftData = $this->session->userdata('shift');
        if(!$userId)
            $userId = $this->session->userdata('user')['id'];
        if($day){
            $this->load->model('shift_model');
            $day = date('Y-m-d', strtotime($day));
            $result = $this->shift_model->getUserShiftByDate($userId, $day);
            $shiftData = [
                'user' => $userId, 
                'id' => $result->shift_id,
                'day' => $result->shift_day,
                'loggedin' => $result->shift_loggedin,
                'beginprod' => $result->shift_begin,
                'endprod' => $result->shift_end,
                'ajustbegin' => $result->shift_ajustbegin,
                'ajustend' => $result->shift_ajustend
            ];
        }
        
        $this->load->model('pause_model');
        $listPause = $this->pause_model->getUserPauseByShift($shiftData['id'], $day);
        $listPauseAjust = $this->pause_model->getUserPauseAjustByShift($shiftData['id'], $day);
        //var_dump($listPause);
        list($data, $totalWork, $totalPause, $pauseDejeuner, $repriseDejeuner, $nbPause) = $this->_formatProgressBar($listPause, $shiftData);
        list($dataAjust, $totalWorkAjust, $totalPauseAjust, $pauseDejeunerAjust, $repriseDejeunerAjust, $nbPauseAjust) = $this->_formatProgressBar($listPauseAjust, $shiftData);
        //var_dump($userId, $data, $totalWork, $totalPause, $pauseDejeuner, $repriseDejeuner, $nbPause, $shiftData['beginprod']);
        //var_dump($dataAjust, $totalWorkAjust, $totalPauseAjust, $pauseDejeunerAjust, $repriseDejeunerAjust, $nbPauseAjust, $shiftData['ajustbegin']);die;
        $loggedinHour = str_pad(date("H", strtotime($shiftData['loggedin'])), 2, 0, STR_PAD_LEFT);
        $loggedinMinute = str_pad(date("i", strtotime($shiftData['loggedin'])), 2, 0, STR_PAD_LEFT);
        $debutProdHour = ($shiftData['beginprod']) ? str_pad(date("H", strtotime($shiftData['beginprod'])), 2, 0, STR_PAD_LEFT) : '';
        $debutProdMinute = ($shiftData['beginprod']) ? str_pad(date("i", strtotime($shiftData['beginprod'])), 2, 0, STR_PAD_LEFT) : '';

        $endProdHour = ($shiftData['endprod']) ? str_pad(date("H", strtotime($shiftData['endprod'])), 2, 0, STR_PAD_LEFT) : '';
        
        //$endDayHour = fmod(($loggedinHour + 9), 24);
        $endDay = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>';
        if($debutProdHour && $endProdHour){
            $endProdMinute = str_pad(date("i", strtotime($shiftData['endprod'])), 2, 0, STR_PAD_LEFT);
            $endDay = $endProdHour . ':' . $endProdMinute ;
        }
        //var_dump($shiftData['endprod'], $repriseDejeuner); die;
        $debutProd = $debutProdHour . ':' . $debutProdMinute; 
        //Calcul des données AM et PM
        //var_dump($pauseDejeuner, $shiftData['beginprod']);die;
        $prodAm = $this->_getDateDiffInHour($pauseDejeuner, $shiftData['beginprod'], 'hm');
        $prodPm = $this->_getDateDiffInHour($shiftData['endprod'], $repriseDejeuner, 'hm');

        $ajustementAm = $this->_getDateDiffInHour($pauseDejeunerAjust, $shiftData['ajustbegin'], 'hm');
        $ajustementPm = $this->_getDateDiffInHour($shiftData['ajustend'], $repriseDejeunerAjust, 'hm');
        
        //$loggedin = $loggedinHour . 'h' . $loggedinMinute . 'mn';
        
        if($format == 'return'){
            return array(
                        'dayTimeline' => $data, 
                        'loggedin' => ($shiftData['loggedin']) ? strftime('%H:%M', strtotime($shiftData['loggedin'])) : '', 
                        'endDay' => $endDay, 
                        'debutProd' => $debutProd, 
                        'endProd' => ($shiftData['endprod']) ? strftime('%H:%M', strtotime($shiftData['endprod'])) : '',
                        'prod_am' => $prodAm,
                        'prod_pm' => $prodPm,
                        'totalWork' => $totalWork, 
                        'totalPause' => $totalPause,
                        'nbPause' => $nbPause,
                        'debutAjustProd' => ($shiftData['ajustbegin']) ? strftime('%H:%M', strtotime($shiftData['ajustbegin'])) : '',
                        'endAjustProd' => ($shiftData['ajustend']) ? strftime('%H:%M', strtotime($shiftData['ajustend'])) : '',
                        'ajustement_am' => $ajustementAm,
                        'ajustement_pm' => $ajustementPm,
                        'totalWorkAjustement' => $totalWorkAjust,
                        'totalPauseAjustement' => $totalPauseAjust
            );
        }else{

            echo json_encode(
                array(
                    'dayTimeline' => $data, 
                    'loggedin' => ($shiftData['loggedin']) ? strftime('%H:%M', strtotime($shiftData['loggedin'])) : '', 
                    'endDay' => $endDay, 
                    'debutProd' => $debutProd, 
                    'endProd' => ($shiftData['endprod']) ? strftime('%H:%M', strtotime($shiftData['endprod'])) : '',
                    'prod_am' => $prodAm,
                    'prod_pm' => $prodPm,
                    'totalWork' => $totalWork, 
                    'totalPause' => $totalPause,
                    'nbPause' => $nbPause,
                    'debutAjustProd' => ($shiftData['ajustbegin']) ? strftime('%H:%M', strtotime($shiftData['ajustbegin'])) : '',
                    'endAjustProd' => ($shiftData['ajustend']) ? strftime('%H:%M', strtotime($shiftData['ajustend'])) : '',
                    'ajustement_am' => $ajustementAm,
                    'ajustement_pm' => $ajustementPm,
                    'totalWorkAjustement' => $totalWorkAjust,
                    'totalPauseAjustement' => $totalPauseAjust 
                )
            );
        }
    }

    private function _formatProgressBar($listPause, $shiftData)
    {
        $dateDebut = false;
        //$debutProd = false;
        //$endProd = false;
        $pauseDejeuner = false;
        $repriseDejeuner = false;
        $timeline = [];
        $nbPause = 0;
        if(!empty($listPause)){
            $nbPause = count($listPause);
            //Si il y a eu des pauses
            foreach ($listPause as $k => $pause){

                if($pause->pause_begin){

                    $debutJournee = ($pause->shift_begin) ? $pause->shift_begin : $pause->shift_loggedin;
                    $finJournee = ($pause->shift_end) ? $pause->shift_end : false;
                    
                    if(!$dateDebut){
                        //On insère le 1er bout working time au debut
                        $dateDebut = $debutJournee;
                        //$debutProd = $dateDebut;
                        $pauseDejeuner = $pause->pause_begin;
                        $repriseDejeuner = $pause->pause_end;

                        $dateEnd = $pause->pause_begin;
                        $timeline = $this->_setTimelineData($timeline, $dateDebut, $dateEnd, WORKING, 'done');
                    }else{
                        //A chaque boucle, on insère le working time avant la pause avec dateDebut déjà défini par l'itération précédente              
                        $dateEnd = $pause->pause_begin;
                        $timeline = $this->_setTimelineData($timeline, $dateDebut, $dateEnd, WORKING, 'done');
                    }
                    $dateDebut = $pause->pause_begin;
                    $dateEnd = $pause->pause_end;
                    $status = 'done';
                    if(!$dateEnd){
                        $dateEnd = date('Y-m-d H:i:s');
                        $status = 'inprogress';
                    }
                    $timeline = $this->_setTimelineData($timeline, $dateDebut, $dateEnd, PAUSED, $status);
                    //var_dump($pauseDejeuner, $dateDebut, $this->_getDateDiffInHour($pauseDejeuner, $dateDebut));die;
                    $diffDateDebutToPause = $this->_getDateDiffInHour($pauseDejeuner, $debutJournee);
                    if( $diffDateDebutToPause >= 0 && $diffDateDebutToPause < 4){
                        $pauseDejeuner = $dateDebut;
                        $repriseDejeuner = $dateEnd;
                    }
                    //Pour le prochain Working time
                    $dateDebut = $dateEnd;
                }
                //var_dump('=====>', $pauseDejeuner, $debutJournee, $diffDateDebutToPause);
                //Ajouter un dernier Working time si plus de pause à venir
                if(!isset($listPause[$k+1])){
                    $status = 'inprogress';
                    $dateEnd = date('Y-m-d H:i:s');
                    if($finJournee){
                        $status = 'done';
                        $dateEnd = $finJournee;
                    }
                    $timeline = $this->_setTimelineData($timeline, $dateDebut, $dateEnd, WORKING, $status);
                    $diffDateDebutToPause = $this->_getDateDiffInHour($pauseDejeuner, $debutJournee);
                    //var_dump('=>=>=>', $pauseDejeuner, $dateEnd, $repriseDejeuner, $diffDateDebutToPause);
                    if($finJournee != $dateEnd){
                        $pauseDejeuner = $dateEnd;
                        $repriseDejeuner = null;
                    }
                }
            }
            //die;
        }else{
            //$type = $this->_userState;
            //Modif 07/07/2022
            if($shiftData['endprod']){
                $type = DONEWORKING;
                $status = 'done';
            }else if($shiftData['beginprod']){
                $type = WORKING;
                $status = 'inprogress';
            }else{
                $type = READYTOWORK;
                $status = 'done';
            }

            $dateEnd = date('Y-m-d H:i:s');
            if($type == READYTOWORK){
                //Si pas de pause prises et pas encore demarré
                $dateDebut = $shiftData['loggedin'];
            }else if($type == WORKING ){
                //Si pas de pause prises mais prod demarré
                $dateDebut = $shiftData['beginprod'];
                $debutJournee = $dateDebut;
            }else if($type == DONEWORKING){
                $dateDebut = $shiftData['beginprod'];
                $dateEnd = $shiftData['endprod'];
            }
            
            $timeline = $this->_setTimelineData($timeline, $dateDebut, $dateEnd, $type, $status);
        }
        $totalWork = 0;
        $totalPause = 0;
        foreach($timeline as $timePart){
            if($timePart['type'] == '2' || $timePart['type'] == '4'){
                $totalWork += $timePart['duree'];
            }else if($timePart['type'] == '3'){
                $totalPause += $timePart['duree'];
            }
            
        }
        return array($timeline, $this->_formatTime($totalWork), $this->_formatTimeNormal($totalPause), $pauseDejeuner, $repriseDejeuner, $nbPause);
    }

    private function _setTimelineData($timeline, $debut, $fin, $type = WORKING, $status = 'done')
    {
        $duree = 0;
        if($debut && $fin)
            $duree = strtotime($fin) - strtotime($debut);

        $data = [
            'debut' => $debut,
            'fin' => $fin,
            'duree' => $duree,
            'dureeText' => $this->_formatTime($duree),
            'percent' => $this->_setDureeToPercent($duree),
            'type' => $type,
            'status' => $status
        ];
        $timeline[] = $data;

        return $timeline;
    }

    private function _setDureeToPercent($duree)
    {
        $_100percent = 60 * 60 * 8;
        return round(($duree * 100) / $_100percent, 2);
    }

    private function _formatTime($duree)
    {
        return sprintf('%02dh%02dmn', ($duree/ 3600),($duree/ 60 % 60));;
    }

    private function _formatTimeNormal($duree)
    {
        return sprintf('%02d:%02d', ($duree/ 3600),($duree/ 60 % 60));
    }

    protected function _getDateDiffInHour($date1, $date2, $format = 'h')
    {
        $debut = new DateTime($date1);
        $fin = new DateTime($date2);
        $diff = $fin->diff($debut);
        if($format == 'hm'){
            return $diff->format('%H:%I');
        }
        if($format == 'hms'){
            return $diff->format('%H:%I:%S');
        }

        
        return $diff->h;
    }

    public function isAuthorized($id, $type)
    {
        $isAutorized = false;
        if($type == 'campagne'){
            $listCampagne = $this->session->userdata('user')['listcampagne'];
            if(!empty($listCampagne)){
                foreach($listCampagne as $campagne){
                    if($campagne->campagne_id == $id){
                        return true;
                    }
                }
            }
        }else if($type == 'service'){
            $listService = $this->session->userdata('user')['listcampagne'];
            if(!empty($listService)){
                foreach($listService as $service){
                    if($service->service_id == $id){
                        return true;
                    }
                }
            }
        }

        return $isAutorized;
    }

    public function getJoursFeries($mois, $annee)
    {
        $this->load->model('conges_model');
        $joursFeries = $this->conges_model->getMonthHolydays($mois, $annee);
        //echo $this->db->last_query(); die;
        if(!$joursFeries) $joursFeries = [];
        return $joursFeries;
    }

    public function getJoursOuvres($mois, $annee, $joursFeries = array()) {
        $nbJours = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
        $nbJoursOuvres = 0;
        for ($jour = 1; $jour <= $nbJours; $jour++) {
            $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
            // Vérifier si le jour est un jour ouvré (lundi à vendredi) et n'est pas un jour férié
            if (date('N', $timestamp) < 6 && !in_array(date('Y-m-d', $timestamp), $joursFeries)) {
                $nbJoursOuvres++;
            }
        }
        return $nbJoursOuvres;
    }

}