<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Control extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
    }

    public function updateControlButtons(){
        $visibleBtnDebut = true;
        $visibleBtnPause = true;
        $visibleBtnReprise = true;
        $visibleBtnFin = true;
        $statut = $this->_userState;
        $bgColor = "secondary";
        if($statut == READYTOWORK){
            $visibleBtnDebut = true;
            $visibleBtnPause = false;
            $visibleBtnReprise = false;
            $visibleBtnFin = false;
            $bgColor = "danger";
        }else if($statut == WORKING){
            $visibleBtnDebut = false;
            $visibleBtnPause = true;
            $visibleBtnReprise = false;
            $visibleBtnFin = true;
            $bgColor = "primary";
        }else if($statut == PAUSED){
            $visibleBtnDebut = false;
            $visibleBtnPause = false;
            $visibleBtnReprise = true;
            $visibleBtnFin = true;
            $bgColor = "warning";
        }else if($statut == DONEWORKING){
            $visibleBtnDebut = false;
            $visibleBtnPause = false;
            $visibleBtnReprise = false;
            $visibleBtnFin = false;
            $bgColor = "success";
        }
        //Traitement boutons activités
        $statusActivity = $this->_userActivity;

        $isActiveBtnDebutActivity = true;
        $isActiveBtnPauseActivity = true;
        $isActiveBtnRepriseActivity = true;
        $isActiveBtnFinActivity = true;

        if($statut == READYTOWORK || $statut == DONEWORKING || $statut == PAUSED){
            $isActiveBtnDebutActivity = false;
            $isActiveBtnPauseActivity = false;
            $isActiveBtnRepriseActivity = false;
            $isActiveBtnFinActivity = false;
        }
        else if($statusActivity == MCP_STATUS_TERMINE)
        {
            $isActiveBtnDebutActivity = true;
            $isActiveBtnPauseActivity = false;
            $isActiveBtnRepriseActivity = true;
            $isActiveBtnFinActivity = false;
        }
        else if($statusActivity == MCP_STATUS_ENCOURS)
        {
            $isActiveBtnDebutActivity = false;
            $isActiveBtnPauseActivity = true;
            $isActiveBtnRepriseActivity = false;
            $isActiveBtnFinActivity = true;
        }
        else if($statusActivity == MCP_STATUS_ENPAUSE)
        {
            $isActiveBtnDebutActivity = true;
            $isActiveBtnPauseActivity = false;
            $isActiveBtnRepriseActivity = true;
            $isActiveBtnFinActivity = false;
        }


        // =========================
        $buttons = [
            'btnDebut' => $visibleBtnDebut, 
            'btnPause' => $visibleBtnPause, 
            'btnReprise' => $visibleBtnReprise, 
            'btnFin' => $visibleBtnFin
        ];

        $buttonsActivity = [
            'btnDebut' => $isActiveBtnDebutActivity,
            'btnPause' => $isActiveBtnPauseActivity,
            'btnReprise' => $isActiveBtnRepriseActivity,
            'btnFin' => $isActiveBtnFinActivity,
        ];
        
        echo json_encode(array(
            'state' => $this->_statusLib, 
            'buttons' => $buttons, 
            'bgButton' => $bgColor, 
            'buttonsActivity' => $buttonsActivity,
            'currentActivityId' => $this->session->userdata('userActivityId'),
            'userActivityCampagne' => $this->session->userdata('userActivityCampagne'),
            'userActivityMission' => $this->session->userdata('userActivityMission'),
            'userActivityProcess' => $this->session->userdata('userActivityProcess'),
            'userQuantityEtp' => $this->session->userdata('userActivityQuantity')
        ));
    }

    /**
     * Debut la production d'un agent donné
     * Mettre la valeur de shift_begin à l'heure actuelle
     */
    public function beginMyProd()
    {
        $shiftData = $this->session->userdata('shift');

        $userId = $shiftData['user'];
        $day = $shiftData['day'];

        $this->load->model('shift_model');
        if($this->shift_model->beginProd($userId, $day)){
            //Maj Status
            $this->session->set_userdata('userState', WORKING);
            //Maj shift data en session
            $shiftData = $this->shift_model->getCurrentShift();
            if($shiftData){
                $shiftSession = $this->session->userdata('shift');
                $shiftSession['beginprod'] = $shiftData->shift_begin;
                $this->session->set_userdata('shift', $shiftSession);
            }
            echo json_encode(['err' => false]);
        }else{
            echo json_encode(['err' => true]);
        }
    }

    /**
     * Prendre une pause
     */
    public function takePause()
    {
        $typePause = $this->input->post('pause_libelle');
        $shiftId = $this->session->userdata('shift')['id'];
        
        if($shiftId && $typePause != ""){
            $currDate = date('Y-m-d H:i:s');
            $data = [
                'pause_type' => $typePause,
                'pause_shift' => $shiftId,
                'pause_begin' => $currDate,
                'pause_etat' => 0,
                'pause_datecrea' => $currDate,
                'pause_datemodif' => $currDate
            ];
            $this->load->model('pause_model');
            if($pauseId = $this->pause_model->setPause($data)){
                //Enregistrement de l'ID de pause en cours en session et mise à jour du status
                $this->load->model('shift_model');
                
                if($this->shift_model->updateStatus(PAUSED, $shiftId)){

                    $this->session->set_userdata('userState', PAUSED);
                    echo json_encode(['err' => false]);
                }else{
                    echo json_encode(['err' => true]);
                }
            }else{
                echo json_encode(['err' => true]);
            }

        }
    }
    /**
     * Terminer une pause donnée
     * Mise à jour de pause_end avec la date courante
     * Mise à jour de pause_etat à 1
     * Mise à jour variable session userState à WORKING
     */
    public function endPause()
    {
        $this->load->model('pause_model');
        $shift = $this->session->userdata('shift')['id'];
        if(!$shift) {
            echo json_encode(['err' => true]);
            exit();
        }
        if(!$this->pause_model->endPause($shift)){
            echo json_encode(['err' => true]);
            exit();            
        }
        $this->load->model('shift_model');
        if(!$this->shift_model->updateStatus(WORKING, $shift)){
            echo json_encode(['err' => true]);
            exit();
        }
        $this->session->set_userdata('userState', WORKING);
        echo json_encode(['err' => false]);
        exit();
    }

    /**
     * Terminer le shift d'un utilisateur donné
     * Mise à jour de t_shift (renseigner shift_end et shift_status à DONEWORKING)
     * S'il y a des pauses en cours, les terminer
     * Mise à jour de la variable session userState à DONEWORKING
     */
    public function endShift()
    {
        $shiftId = $this->session->userdata('shift')['id'];
        if(!$shiftId){
            echo json_encode(['err' => true]);
            exit();
        }
        //Ouverture transaction
        $this->db->trans_start();
        //Terminer toutes les pauses en cours du shift
        $this->load->model('pause_model');
        $this->pause_model->endPause($shiftId);
        //Mettre à jour le shift
        $this->load->model('shift_model');
        if(!$this->shift_model->endProd($shiftId)){
            echo json_encode(['err' => true]);
            $this->db->trans_rollback();
            exit();
        }
        $shift = $this->shift_model->getShiftById($shiftId);
        if($shift){     
            //On termine toutes les activités du jour de l'user
            $this->load->model('etp_model');
            $this->_setFinUserDayActivities($this->_userId, $shift->shift_day);
        }

        $this->session->set_userdata('userState', DONEWORKING);
        //Fermeture transaction
        $this->db->trans_commit(); 
        //Suppression du shift terminé en session
        //$this->session->unset_userdata('shift');
        
        echo json_encode(['err' => false]);
        exit();
    }

    /** 
     * Fonction calquée sur Apietp->_setFinActivity à faire évoluer ensemble  
     * */
    private function _setFinUserDayActivities($user, $day)
    {
        $this->load->model('etp_model');
        //Recuperer les activités
        $listActivite = $this->etp_model->getUserActivityByStatus([MCP_STATUS_ENCOURS, MCP_STATUS_ENPAUSE]);
        if($listActivite){
            foreach($listActivite as $infoActivity){

                $dateDebut = $infoActivity->mcp_datedebut;
                $dateFin = date('Y-m-d H:i:s');
                $diff_seconds = strtotime($dateFin) - strtotime($dateDebut) - strtotime("00:00:00");
                
                $pause = strtotime($infoActivity->mcp_tempspause)-strtotime("00:00:00");
                $tempsTravail = gmdate("H:i:s", ($diff_seconds-$pause)) ;
                //var_dump($pause, $tempsTravail); die;
                $entry = [
                    'mcp_status' => MCP_STATUS_TERMINE,
                    'mcp_datefin' => $dateFin,
                    'mcp_tempstravail' => $tempsTravail,
                    'mcp_datecrea' => date('Y-m-d H:i:s'),
                    'mcp_datemodif' => date('Y-m-d H:i:s'),
                ];

                $where = [
                    'mcp_id' => $infoActivity->mcp_id,
                    'mcp_agent' => $user, // Pour assurer que ce sera le même agent qui effectue la mise à jour
                    'mcp_date' => $day
                ];

                $where_in = [
                    'mcp_status' => [MCP_STATUS_ENCOURS, MCP_STATUS_ENPAUSE]
                ];
 
                $this->etp_model->updateActivity($entry, $where, $where_in);
            }
        }
    }


    /**
     * Terminer le shift d'un utilisateur choisi
     * Mise à jour de t_shift (renseigner shift_end et shift_status à DONEWORKING)
     * S'il y a des pauses en cours, les terminer
     * Mise à jour de la variable session userState à DONEWORKING
     * By superviseur ou cadre
     */
    public function endShiftAgent()
    {
        $retour = array();
        
        if($this->input->post('shiftToEndID') !== FALSE){
            
            $shiftId = $this->input->post('shiftToEndID');
            $endedBy = $this->_userId;
        
            //Ouverture transaction
            $this->db->trans_start();
            //Terminer toutes les pauses en cours du shift
            $this->load->model('pause_model');
            $this->pause_model->endPause($shiftId);
            //Mettre à jour le shift
            $this->load->model('shift_model');
            if(!$this->shift_model->endProd($shiftId, $endedBy )){
                
                //$retour = ['err' => true, 'msg' => 'erreur survenu'];
                redirect('dashboard/tempsreel?msg=error');
                $this->db->trans_rollback();
                //return $retour;
            }else{
                //Fermeture transaction
            $this->db->trans_commit(); 
            //Suppression du shift terminé en session
            //$this->session->unset_userdata('shift');
            
            //return ['err' => false, 'msg' => 'Shift terminé avec succès'];
            redirect('dashboard/tempsreel?msg=success');
            }
            
        }
        
        //redirect('dashboard/tempsreel');
    }


}