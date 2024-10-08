<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require(APPPATH.'libraries/RestController.php');
use chriskacerguis\RestServer\RestController;

class Apietp extends RestController {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    /**
     * API de récupération d'une activité en GET
     */
    public function activity_get($user=false, $status = false)
    {
        if(!$user){
            $this->response([
                'status' => false,
                'message' => 'user requis'
            ], 400);
        }

        if(!$status){
            $this->response([
                'status' => false,
                'message' => 'status requis'
            ], 400);
        }

        $this->load->model('etp_model');

        $this->etp_model->setUser($user);
        
        $listActivite = $this->etp_model->getUserActivityByStatus( MCP_STATUS_ENPAUSE);
        if(!$listActivite){

            $this->response( [
                'status' => false,
                'message' => 'No activity were found'
            ], 404 );
        }

        $successData = [
            'status' => true,
            'data' => $listActivite,
            'message' => 'Liste des activités récupérée'
        ];

        $this->response($successData, 200);
    }

    /**
     * API creation d'une activité en POST
     */
    public function activity_post()
    {
        $action = $this->input->post('action');
        if(!$action){
            $this->response([
                'status' => false,
                'message' => 'action requis'
            ], 400);
        }else{
            $allowedAction = ['debut'];
            if(!in_array($action, $allowedAction)){
                $this->response([
                    'status' => false,
                    'message' => 'Valeur de [action] non autorisé'
                ], 400);
            }
        }

        $campagne = $this->input->post('campagne');
        if(!$campagne){
            $this->response([
                'status' => false,
                'message' => 'Campagne requis'
            ], 400);
        }

        $profil = $this->input->post('profil');
        if(!$profil){
            $this->response([
                'status' => false,
                'message' => 'Profil requis'
            ], 400);
        }

        $mission = $this->input->post('mission');
        if(!$mission){
            $this->response([
                'status' => false,
                'message' => 'Mission requis'
            ], 400);
        }

        $process = $this->input->post('process');
        if(!$process){
            $this->response([
                'status' => false,
                'message' => 'Process requis'
            ], 400);
        }

        $agent = $this->input->post('agent');
        if(!$agent){
            $this->response([
                'status' => false,
                'message' => 'Agent requis'
            ], 400);
        }

        $etatRessource = $this->input->post('etatressource');
        if(!$etatRessource){
            $this->response([
                'status' => false,
                'message' => 'Etat de la ressource requis'
            ], 400);
        }

        $data = [
            'action' => $action,
            'campagne' => $campagne,
            'profil' => $profil,
            'mission' => $mission,
            'process' => $process,
            'agent' => $agent,
            'etatRessource' => $etatRessource,
            'date' => date('Y-m-d H:i:s') // On utilise la date actuelle du serveur
        ];

        $this->_activity($data);
    }

    /**
     * API de mise à jour d'une activité en PUT
     */
    public function activity_put()
    {
        $action = $this->put('action');
        if(!$action){
            $this->response([
                'status' => false,
                'message' => 'action requis'
            ], 400);
        }else{
            $allowedAction = ['fin', 'pause', 'reprise', 'validate'];
            if(!in_array($action, $allowedAction)){
                $this->response([
                    'status' => false,
                    'message' => 'Valeur de [action] non autorisé'
                ], 400);
            }
        }

        $activityId = $this->put('id');
        if(!$activityId){
            $this->response([
                'status' => false,
                'message' => 'ID requis'
            ], 400);
        }

        $agent = $this->put('agent');
        if(!$agent){
            $this->response([
                'status' => false,
                'message' => 'Agent requis'
            ], 400);
        }

        $etatRessource = $this->put('etatressource');

        $data = [
            'action' => $action,
            'id' => $activityId,
            'agent' => $agent,
            'etatRessource' => $etatRessource,
            'date' => date('Y-m-d H:i:s') // date actuelle du serveur
        ];

        $this->_activity($data);
    }

    public function campagne_get($user = false)
    {
        if(!$user){
            $this->response([
                'status' => false,
                'message' => 'user requis'
            ], 400);
        }

        $this->load->model('campagne_model');
        $listCampagne = $this->campagne_model->getUserCampagne($user);
        //echo $this->db->last_query(); die;
        if(!$listCampagne){
            $this->response([
                'status' => false,
                'message' => 'Erreur lors de la récupération de la liste de campagne'
            ], 400);
        }
        $successData = [
            'status' => true,
            'data' => $listCampagne,
            'message' => 'Liste des campagnes récupérée'
        ];

        $this->response($successData, 200);
    }

    public function mission_get($campagne = false)
    {
        if(!$campagne){
            $this->response([
                'status' => false,
                'message' => 'campagne requis'
            ], 400);
        }

        $this->load->model('mission_model');
        $listMission = $this->mission_model->getListMissionForCampagne($campagne);
        //echo $this->db->last_query(); die;
        if(!$listMission){
            $this->response([
                'status' => false,
                'message' => 'Erreur lors de la récupération de la liste de missions'
            ], 400);
        }
        $successData = [
            'status' => true,
            'data' => $listMission,
            'message' => 'Liste des missions récupérée'
        ];

        $this->response($successData, 200);
    }

    public function missionprofil_get($campagne = false, $profil = false)
    {
        if(!$campagne){
            $this->response([
                'status' => false,
                'message' => 'campagne requis'
            ], 400);
        }

        if(!$profil){
            $this->response([
                'status' => false,
                'message' => 'profil requis'
            ], 400);
        }

        $this->load->model('mission_model');
        $listMission = $this->mission_model->getListMissionForCampagneAndProfil($campagne, $profil);
        //echo $this->db->last_query(); die;
        if(!$listMission){
            $this->response([
                'status' => false,
                'message' => 'Erreur lors de la récupération de la liste de missions'
            ], 400);
        }
        $successData = [
            'status' => true,
            'data' => $listMission,
            'message' => 'Liste des missions récupérée'
        ];

        $this->response($successData, 200);
    }

    public function process_get($campagne = false, $mission = false)
    {
        if(!$campagne){
            $this->response([
                'status' => false,
                'message' => 'campagne requis'
            ], 400);
        }
        if(!$mission){
            $this->response([
                'status' => false,
                'message' => 'mission requis'
            ], 400);
        }

        $this->load->model('process_model');
        $listProcess = $this->process_model->getListProcessCampagneMission($campagne, $mission);
        //echo $this->db->last_query(); die;
        if(!$listProcess){
            $this->response([
                'status' => false,
                'message' => 'Erreur lors de la récupération de la liste des process'
            ], 400);
        }
        $successData = [
            'status' => true,
            'data' => $listProcess,
            'message' => 'Liste des process récupérée'
        ];

        $this->response($successData, 200);
    }

    public function ressource_get($id = false)
    {
        /*if(!$id){
            $this->response([
                'status' => false,
                'message' => 'id requis'
            ], 400);
        }*/
        $campagne = $this->input->get('campagne');
        $mission = $this->input->get('mission');
        $process = $this->input->get('process');
        $du = $this->input->get('du');
        $au = $this->input->get('au');

        $filtre = [
            'id' => $id,
            'campagne' => $campagne,
            'mission' => $mission,
            'process' => $process,
            'du' => $du,
            'au' => $au
        ];

        $this->load->model('etp_model');
        $listRessource = $this->etp_model->getMcpInfosBy($filtre);
        //echo $this->db->last_query(); die;
        if(!$listRessource){
            $this->response([
                'status' => false,
                'message' => 'Erreur lors de la récupération de la liste des ressources'
            ], 400);
        }
        $successData = [
            'status' => true,
            'data' => $listRessource,
            'message' => 'Liste des ressources récupérée'
        ];

        $this->response($successData, 200);
    }

    public function ressource_delete($id)
    {
        if(!$id){
            $this->response([
                'status' => false,
                'message' => 'id ressource requis'
            ], 400);
        }

        $this->load->model('etp_model');
        $isDeleted = $this->etp_model->deleteRessourceById($id);

        if(!$isDeleted){
            $this->response([
                'status' => false,
                'message' => 'Erreur survenu lors de la suppression de la ressource'
            ], 400);
        }

        $successData = [
            'status' => true,
            'message' => 'Ressource libérée'
        ];

        $this->response($successData, 200);
    }


    public function livrepaie_post()
    {
        $annee = $this->input->post('annee');
        if(!$annee){
            $this->response([
                'status' => false,
                'message' => 'annee requis'
            ], 400);
        }

        $mois = $this->input->post('mois');
        if(!$mois){
            $this->response([
                'status' => false,
                'message' => 'mois requis'
            ], 400);
        }

        $valeur = $this->input->post('valeur');
        if(!$valeur){
            $this->response([
                'status' => false,
                'message' => 'valeur requis'
            ], 400);
        }

        $data = [
            'action' => 'post',
            'annee' => $annee,
            'mois' => $mois,
            'valeur' => $valeur
        ];

        $this->_livrepaie($data);
    }


    /**
     * PRIVATE FUNCTION _activity()
     * Params : data
     * Out : json response 
     */
    private function _activity($data)
    {
        switch($data['action']){
            case 'debut' : 
                $activityId = $this->_setDebutActivity($data);
                if(!$activityId){
                    $this->response([
                        'status' => false,
                        'message' => 'Erreur survenu : création activité en erreur'
                    ], 400);
                }
                $successData = [
                    'ID' => $activityId,
                    'status' => true,
                    'message' => 'Activité démarrée'
                ];
                $this->session->set_userdata('userActivity', MCP_STATUS_ENCOURS);
                break;
            
            case 'fin':
                $updated = $this->_setFinActivity($data);
                if(!$updated){
                    $this->response([
                        'status' => false,
                        'message' => 'Erreur survenu : Fin activité en erreur'
                    ], 400);
                }
                $successData = [
                    'ID' => $data['id'],
                    'status' => true,
                    'message' => 'Activité terminée'
                ];
                $this->session->set_userdata('userActivity', MCP_STATUS_TERMINE);
                break;

            case 'pause':
                $updated = $this->_setEnPauseActivity($data);
                if(!$updated){
                    $this->response([
                        'status' => false,
                        'message' => 'Erreur survenu : Mise en pause activité en erreur'
                    ], 400);
                }
                $successData = [
                    'ID' => $data['id'],
                    'status' => true,
                    'message' => 'Activité en pause'
                ];
                $this->session->set_userdata('userActivity', MCP_STATUS_ENPAUSE);
                break;

            case 'reprise':
                $updated = $this->_setRepriseActivity($data);
                if(!$updated){
                    $this->response([
                        'status' => false,
                        'message' => 'Erreur survenu : Reprise activité en erreur'
                    ], 400);
                }
                $successData = [
                    'ID' => $data['id'],
                    'status' => true,
                    'message' => 'Activité reprise'
                ];
                $this->session->set_userdata('userActivity', MCP_STATUS_ENCOURS);
                break;

            case 'validate':
                $updated = $this->_setValidateRessource($data);
                if(!$updated){
                    $this->response([
                        'status' => false,
                        'message' => 'Erreur survenu : Validation ressource en erreur'
                    ], 400);
                }
                $successData = [
                    'ID' => $data['id'],
                    'status' => true,
                    'message' => 'Activité validée'
                ];
                break;
        }

        $this->response($successData, 200);
        
    }

    
    /**
     * PRIVATE FUNCTION _setDebutActivity()
     * Params : data
     * Out : boolean
     */
    private function _setDebutActivity($data)
    {

        $entry = [
            'mcp_campagne' => $data['campagne'],
            'mcp_primeprofil' => ($data['profil']) ? $data['profil'] : DEFAULT_PRIMEPROFIL,
            'mcp_mission' => $data['mission'],
            'mcp_process' => $data['process'],
            'mcp_agent' => $data['agent'],
            'mcp_status' => MCP_STATUS_ENCOURS,
            'mcp_etatressource' => $data['etatRessource'],
            'mcp_date' => date('Y-m-d', strtotime($data['date'])),
            'mcp_datedebut' => date('Y-m-d H:i:s', strtotime($data['date'])),
            'mcp_datecrea' => date('Y-m-d H:i:s'),
            'mcp_datemodif' => date('Y-m-d H:i:s'),
        ];
        
        $this->load->model('etp_model');
        return $this->etp_model->setDebutActivity($entry);
    }

    private function _setFinActivity($data)
    {
        $this->load->model('etp_model');
        $infoActivity = $this->etp_model->getInfoActivity($data['id']);

        $dateDebut = $infoActivity->mcp_datedebut;
        $dateFin = date('Y-m-d H:i:s');
        $dateDebut_datetime = new DateTime($dateDebut);
        $dateFin_datetime = new DateTime($dateFin);
        $diff_withoutpause = $dateDebut_datetime->diff($dateFin_datetime);
        $dateWorkIncludePause = new DateTime($diff_withoutpause->format('%H:%I:%S'));
        $pause = new DateTime("00:00:00");

        if($infoActivity->mcp_tempspause){
            $pause = new DateTime($infoActivity->mcp_tempspause);
        }
        
        $dateWorkWithoutPause = $dateWorkIncludePause->diff($pause);
        $tempsTravail =  $dateWorkWithoutPause->format('%H:%I:%S');
        
        $entry = [
            'mcp_status' => MCP_STATUS_TERMINE,
            'mcp_datefin' => $dateFin,
            'mcp_tempstravail' => $tempsTravail,
            'mcp_datecrea' => date('Y-m-d H:i:s'),
            'mcp_datemodif' => date('Y-m-d H:i:s'),
        ];

        $where = [
            'mcp_status' => MCP_STATUS_ENCOURS,
            'mcp_id' => $data['id'],
            'mcp_agent' => $data['agent'] // Pour assurer que ce sera le même agent qui effectue la mise à jour
        ];
        
        $this->load->model('etp_model');
        return $this->etp_model->updateActivity($entry, $where);
    }

    

    private function _setEnPauseActivity($data)
    {
        $this->load->model('etp_model');
        $infoActivity = $this->etp_model->getInfoActivity($data['id']);

        $pause = ($infoActivity->mcp_tempspause) ? DateTime::createFromFormat('H:i:s', $infoActivity->mcp_tempspause) : DateTime::createFromFormat('H:i:s', '00:00:00');

        $entry = [
            'mcp_status' => MCP_STATUS_ENPAUSE,
            'mcp_lastpause' => date('Y-m-d H:i:s', strtotime($data['date'])),
            'mcp_tempspause' => $pause->format('H:i:s'),
            'mcp_datemodif' => date('Y-m-d H:i:s'),
        ];
        $where = [
            'mcp_status' => MCP_STATUS_ENCOURS,
            'mcp_id' => $data['id'],
            'mcp_agent' => $data['agent'] // Pour assurer que ce sera le même agent qui effectue la mise à jour
        ];
        
        $this->load->model('etp_model');
        return $this->etp_model->updateActivity($entry, $where);
    }

    private function _setRepriseActivity($data)
    {
        $this->load->model('etp_model');
        $infoActivity = $this->etp_model->getInfoActivity($data['id']);
        //var_dump($infoActivity);die;
        $debutPause = new DateTime($infoActivity->mcp_lastpause);
        $finPause = new DateTime($data['date']);
        $diff = $debutPause->diff($finPause);
        
        $currentPause = strtotime($infoActivity->mcp_tempspause);
        $addedPause = strtotime($diff->format('%H:%I:%S'));

        $currentPause = $infoActivity->mcp_tempspause;
        $addedPause = $diff->format('%H:%I:%S');

        $secs = strtotime($addedPause)-strtotime("00:00:00");
        $newPause = date("H:i:s",strtotime($currentPause)+$secs);

        $entry = [
            'mcp_status' => MCP_STATUS_ENCOURS,
            'mcp_lastpause' => null,
            'mcp_tempspause' => $newPause,
            'mcp_datemodif' => date('Y-m-d H:i:s'),
        ];
        $where = [
            'mcp_status' => MCP_STATUS_ENPAUSE,
            'mcp_id' => $data['id'],
            'mcp_agent' => $data['agent'] // Pour assurer que ce sera le même agent qui effectue la mise à jour
        ];
        
        return $this->etp_model->updateActivity($entry, $where);
    }

    private function _setValidateRessource($data)
    {
        $this->load->model('etp_model');
        $infoActivity = $this->etp_model->getInfoActivity($data['id']);

        $entry = [
            'mcp_etatressource' => $data['etatRessource'],
            'mcp_datemodif' => date('Y-m-d H:i:s'),
        ];
        $where = [
            'mcp_status' => MCP_STATUS_TERMINE,
            'mcp_id' => $data['id'],
            'mcp_agent' => $data['agent'] // Pour assurer que ce sera le même agent qui effectue la mise à jour
        ];
        //$this->etp_model->updateActivity($entry, $where);
        //echo $this->db->last_query(); die;
        return $this->etp_model->updateActivity($entry, $where);
    }

    private function _livrepaie($data)
    {
        if($data['action'] == 'post')
        {
            $this->load->model('livrepaie_model');
            $mois = $data['mois'];
            $annee = $data['annee'];
            $livrePaie = $this->livrepaie_model->getLivrepaieByMonthYear($mois, $annee);
            //var_dump($mois, $annee, $livrePaie);
            if($livrePaie){
                $data['id'] = $livrePaie->livrepaie_id;
                $inserted = $this->livrepaie_model->updateLivrepaie($data);
            }else{
            $inserted = $this->livrepaie_model->insertLivrepaie($data);
            }

            if(!$inserted)
            {
                $this->response([
                    'status' => false,
                    'message' => 'Erreur survenu : Mise en pause activité en erreur'
                ], 400);
            }
            $successData = [
                'ID' => $inserted,
                'status' => true,
                'message' => 'Livre Paie ajoutée'
            ];

            $this->response($successData, 200);
        }

        $this->response([
            'status' => false,
            'message' => 'Methode non autorisée'
        ], 400);
    }


}