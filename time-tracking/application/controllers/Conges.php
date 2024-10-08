<?php

use phpDocumentor\Reflection\DocBlock\Tags\Var_;

defined('BASEPATH') OR exit('No direct script access allowed');

class conges extends MY_Controller {

    
    public function index()
    {
        $this->mesConges();
    }

    /**
     * Afficher et gérer mes congés 
     */
    public function mesConges()
    {
        
        $header = ['pageTitle' => 'Mes Congés - TimeTracking'];
        $this->load->model('conges_model');
        $this->load->model('user_model');
        $listTypeConge = $this->conges_model->getAllTypeConge();
        $current_user = $this->session->userdata('user')['id'];
        $monSolde = $this->user_model->getSoldeCongeUser($current_user);

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('conges/mesconges', ['monSolde' => $monSolde]);
        $this->load->view('conges/modalconges', ['listTypeConge' => $listTypeConge]);
        $this->load->view('common/footer', []);
    }

    public function getMesConges(){

        $this->load->model('conges_model');
        $current_user = $this->session->userdata('user')['id'];
        $datas = $this->conges_model->getAllConges($current_user);

        echo json_encode(array('data' => $datas));
    }

    public function saveNewConge(){
        
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')
        {
            $this->load->model('conges_model');

            $demande = $this->input->post();

            /*$jourFinConge = date("N", strtotime($demande['conge_datefin']));
            if($jourFinConge == 5){
                $demande['conge_dateretour'] = date('Y-m-d H:i:s', strtotime($demande['conge_datefin'] . ' +3 day'));
            }else{
                $demande['conge_dateretour'] = date('Y-m-d H:i:s', strtotime($demande['conge_datefin'] . ' +1 day'));
            }*/
            if($demande['conge_type'] == TYPECONGE_PERMISSION){
                $demande['conge_duree'] /= 8;
                $dateFin = $demande['conge_datefin'];
                $demande['conge_datedebut'] = $demande['conge_datedebut'] . ' ' . $demande['permission_heuredatedebut'];
                $demande['conge_datefin'] = $demande['conge_datefin'] . ' ' . $demande['permission_heuredatefin'];
                $demande['conge_dateretour'] = $demande['conge_dateretour'] . ' ' . $demande['permission_heuredateretour'];

                /*$heureFin = date('H', strtotime($demande['permission_heuredatefin']));
                if($heureFin < HEURE_NON_RETOUR){
                    $demande['conge_dateretour'] = $demande['conge_datefin'];
                }else{
                    if($jourFinConge == 5){
                        $demande['conge_dateretour'] = date('Y-m-d H:i:s', strtotime($dateFin . ' 09:00 +3 day'));
                    }else{
                        $demande['conge_dateretour'] = date('Y-m-d H:i:s', strtotime($dateFin . ' 09:00 +1 day')); 
                    }
                }*/
            }else if($demande['conge_type'] == TYPECONGE_CONGE){
                $demande['conge_datedebut'] = $demande['conge_datedebut'] . ' ' . $demande['conge_heuredatedebut'];
                $demande['conge_datefin'] = $demande['conge_datefin'] . ' ' . $demande['conge_heuredatefin'];
                $demande['conge_dateretour'] = $demande['conge_dateretour'] . ' ' . $demande['conge_heuredateretour'];
            }

            $toEtat = A_VALIDER_SUP;

            if($this->_userRole == ROLE_CADRE){
                $toEtat = A_VALIDER_CADRE2;
            }else if($this->_userRole == ROLE_CADRE2){
                $toEtat = A_VALIDER_DIR;
            }else if($this->_userRole == ROLE_DIRECTION){
                $toEtat = A_VALIDER_COSTRAT;
            }else if($this->_userRole == ROLE_COSTRAT){
                $toEtat = A_TRAITER_RH;
            }

            $demande['conge_etat'] = $toEtat;
            $demande['conge_user'] = $this->session->userdata('user')['id'];

            //load model
            
            $inserted_conge = $this->conges_model->insertConge($demande);
            if($inserted_conge){
                $dataHisto = [
                    'histoetatconge_conge' => $inserted_conge,
                    'histoetatconge_etat' => $toEtat,
                    'histoetatconge_date' => date('Y-m-d H:i:s')
                ];
                $this->load->model('histoetatconge_model');
                $inserted_conge = $this->histoetatconge_model->insertHistorique($dataHisto);
            }

            if ($inserted_conge === FALSE){
                redirect('conges/mesconges?msg=error');
            }else{
                redirect('conges/mesconges?msg=success');
            }

        }
    }

    public function saveEditConge($conge_id){
        //var_dump($_POST); die;
        if($conge_id && $this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')
        {
            $demande = $this->input->post();
            $jourFinConge = date("N", strtotime($demande['conge_datefin']));
            $conge_user = $demande['edit-conge-user'];
            
            if($jourFinConge == 5){
                $demande['conge_dateretour'] = date('Y-m-d H:i:s', strtotime($demande['conge_datefin'] . ' +3 day'));
            }else{
                $demande['conge_dateretour'] = date('Y-m-d H:i:s', strtotime($demande['conge_datefin'] . ' +1 day'));
            }
            if($demande['conge_type'] == TYPECONGE_PERMISSION){
                $demande['conge_duree'] /= 8;
                $demande['conge_datedebut'] = $demande['conge_datedebut'] . ' ' . $demande['permission_heuredatedebut'];
                $demande['conge_datefin'] = $demande['conge_datefin'] . ' ' . $demande['permission_heuredatefin'];
                $heureFin = date('H:i', strtotime($demande['permission_heuredatefin']));
            
                if($jourFinConge == 5 && date('H', $heureFin) > 17){
                    $demande['conge_dateretour'] = date('Y-m-d H:i:s', strtotime($demande['conge_datefin'] . ' +3 day'));
                }else if(date('H', $heureFin) > 17){
                    $demande['conge_dateretour'] = date('Y-m-d H:i:s', strtotime($demande['conge_datefin'] . ' +1 day')); 
                }else{
                    $demande['conge_dateretour'] = $demande['conge_datefin'];
                }
            }else if($demande['conge_type'] == TYPECONGE_CONGE){
                $demande['conge_datedebut'] = $demande['conge_datedebut'] . ' ' . $demande['conge_heuredatedebut'];
                $demande['conge_datefin'] = $demande['conge_datefin'] . ' ' . $demande['conge_heuredatefin'];
            }

            //$demande['conge_etat'] = A_VALIDER_SUP;
            $demande['conge_user'] = $conge_user;

            //load model
            $this->load->model('conges_model');
            $updated = $this->conges_model->updatetConge($demande, $conge_id);

            $page = 'conges/mesconges';
            if($this->_userRole == ROLE_ADMINRH){
                $page = 'conges/congesATraiter';
            }
            if ($updated === FALSE){   
                redirect($page . '?msg=error');
            }else{
                redirect($page . '?msg=success');
            }

        }
    }

    public function deleteConge(){

        if($this->input->post() && $this->input->post('sendDelete') && $this->input->post('sendDelete') == 'sent'){

            $data = $this->input->post();
             
            //On autorise la suppression seulement si ca n'a pas encore été valider
            if($data['congeToDelete'] 
                && ( 
                    $data['congeToDeleteEtat'] == A_VALIDER_SUP ||
                    ( ($this->_userRole == ROLE_CADRE || $this->_userRole) && ($data['congeToDeleteEtat'] == A_VALIDER_DIR || $data['congeToDeleteEtat'] == A_VALIDER_CADRE2) ) ||
                    ( $this->_userRole == ROLE_DIRECTION && $data['congeToDeleteEtat'] == A_VALIDER_COSTRAT ) || 
                    ( $this->_userRole == ROLE_COSTRAT && $data['congeToDeleteEtat'] == A_TRAITER_RH )
                ) 
            ){

                    $this->load->model('conges_model');
                    if($this->conges_model->deleteConge($data['congeToDelete']))
                        redirect('conges/mesconges?msg=success');
            }
            

        }
        redirect('conges/mesconges?msg=error');
    }

    public function calculateDureeConge(){
        
        $datas = [
            'error' => true,
            'data' => []
        ];
        
        $start_date = $this->input->post('du');
        $end_date = $this->input->post('au');
        $retour_date = $this->input->post('retour');
        $type = $this->input->post('type');
        $action = $this->input->post('action');
        $conge_id = ($action == 'edit') ? $this->input->post('id') : false;
        $conge_user = ($action == 'edit') ? $this->input->post('user') : $this->session->userdata('user')['id'];

        if($this->input->post() 
            && !empty($start_date)
            && !empty($end_date)
            && !empty($retour_date)
            && !empty($type)){
            
            $this->load->model('conges_model');
            //Verification disponibilité date
            //if($action == 'add'){
                
                $isAvailableDate = $this->conges_model->isAvailableDate($start_date, $end_date, $conge_user, $conge_id);
                if(!$isAvailableDate){
                    $datas = [
                        'error' => true,
                        'message' => 'Vous avez déjà des congés/permissions en cours sur cette période' 
                    ];
                    echo json_encode($datas); die();
                }
            //}
            //$holidays = $this->conges_model->countHolidays($start_date, $end_date);

            $startDateNoHours = date('Y-m-d', strtotime($start_date));
            $endDateNoHours = date('Y-m-d', strtotime($end_date));
            $holidays = $this->conges_model->countHolidays($startDateNoHours, $endDateNoHours);
            
            $debut = new DateTime($start_date);
            $retour = new DateTime($end_date);
            $difference = $debut->diff($retour);

            $days = $difference->days;
            $hours = $debut->diff($retour)->h;
            $minutes = $debut->diff($retour)->i;


            if($type == TYPECONGE_CONGE){

                $end_timestamp = strtotime($end_date);

                if($hours > 0 && $hours <= 4 ){
                    $days += 0.5;
                }else if($hours > 4){
                    $days += 1;
                }

                $leave_duration = $days - $holidays;

                if($leave_duration)
                {
                    $datas = [
                        'error' => false,
                        'data' => $leave_duration
                    ];
                }


            }else if($type == TYPECONGE_PERMISSION){

                $days += ($hours/8);
                $leave_duration = $days - $holidays;
                $leave_duration *= 8;

                if($minutes) $leave_duration += ($minutes/60);


                //$soldePermission = $this->conges_model->getSoldePermission($conge_user);
                if ($leave_duration) {
                    $datas = [
                        'error' => false,
                        'data' => $leave_duration
                    ];
                }
            }
             
        }
        echo json_encode($datas);
    }




    /**
     * Afficher et valider les congés 
     */

    public function congesAValider()
    {
        if($this->session->userdata('user')['role'] !== ROLE_SUP 
            && $this->session->userdata('user')['role'] !== ROLE_CADRE
            && $this->session->userdata('user')['role'] !== ROLE_CADRE2
            && $this->session->userdata('user')['role'] !== ROLE_DIRECTION
            && $this->session->userdata('user')['role'] !== ROLE_COSTRAT
            && $this->session->userdata('user')['role'] !== ROLE_COSTRAT
            && $this->session->userdata('user')['role'] !== ROLE_CLIENT
            ){
            redirect('dashboard/index');
        }

        $header = ['pageTitle' => 'Les congés à valider - TimeTracking'];
        $this->load->model('conges_model');
        $listTypeConge = $this->conges_model->getAllTypeConge();
        $listEtatConge = [ A_VALIDER_SUP, A_VALIDER_CADRE2, A_VALIDER_DIR];
        if($this->session->userdata('user')['role'] == ROLE_COSTRAT){
            $listEtatConge[] = A_VALIDER_COSTRAT;
        }
        $listEtatCongeAValider = $this->conges_model->getInfosListEtatConge($listEtatConge);
        $userRole = $this->_userRole;

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('conges/modalconges', ['listTypeConge' => $listTypeConge]);
        $this->load->view('conges/congesavalider', ['userRole' => $userRole, 'listEtatConge' => $listEtatCongeAValider]);
        $this->load->view('common/footer', []);
    }

    public function getCongesAValider(){

        $this->load->model('user_model');
        $this->load->model('campagne_model');
        $this->load->model('service_model');

        $currentSup = $this->session->userdata('user')['id'];
        $etat = false;
        
        if($this->_userRole == ROLE_SUP){
            $etat = [A_VALIDER_SUP];
        }else if($this->_userRole == ROLE_CADRE || $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_CLIENT){
            $etat = [A_VALIDER_SUP, A_VALIDER_CADRE2, A_VALIDER_DIR];
        }else if($this->_userRole == ROLE_COSTRAT){
            $etat = [A_VALIDER_COSTRAT];
        }

        //var_dump($this->session->userdata('show_validated')); die;
         //var_dump($this->session->userdata('show_validated'), $this->session->userdata('filtreetatvalidateconge')); die;
        if($this->session->userdata('show_validated') == 'true'){
            array_push($etat,A_TRAITER_RH, VALIDE, REFUSE, REPOS_MALADIE, ASSISTANCE_MATERNELLE, AUTRES, CONGE_ENCOURS, CONGE_TERMINE);
        }
        if($this->session->userdata('filtreetatvalidateconge') && !empty($this->session->userdata('filtreetatvalidateconge'))){
            unset($etat);
            $etat[] = $this->session->userdata('filtreetatvalidateconge');
        }


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
            if($this->_userRole == ROLE_CADRE || $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_CLIENT){
                $listSupCampagne = $this->user_model->getListSupByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

            //Vérifier que le profil est direction ou CADRE 2 si OUI on ajoute les cadres
            if( $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_DIRECTION ){
                $listSupCampagne = $this->user_model->getListCadreByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }


            //Vérifier que le profil est direction si OUI on ajoute les cadres 2
            if($this->_userRole == ROLE_DIRECTION){
                $listSupCampagne = $this->user_model->getListCadre2ByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }


            //Vérifier que le profil est costrat si OUI on ajoute les directions
            if($this->_userRole == ROLE_COSTRAT){
                $listSupCampagne = $this->user_model->getListDirectionByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
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
            if($this->_userRole == ROLE_CADRE || $this->_userRole == ROLE_CADRE2 || $this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_CLIENT){
                $listSupService = $this->user_model->getListSupByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

            //Vérifier que le profil est direction  si OUI on ajoute les SUPs
            if($this->_userRole == ROLE_CADRE2  || $this->_userRole == ROLE_DIRECTION ){
                $listSupService = $this->user_model->getListCadreByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

            //Vérifier que le profil est direction  si OUI on ajoute les SUPs
            if($this->_userRole == ROLE_DIRECTION){
                $listSupService = $this->user_model->getListCadre2ByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

            //Vérifier que le profil est direction  si OUI on ajoute les SUPs
            if($this->_userRole == ROLE_COSTRAT){
                $listSupService = $this->user_model->getListDirectionByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }
        }


        $this->load->model('conges_model');
        $datas = $this->conges_model->getAllCongesToValidate($listAgent, $etat);

        echo json_encode(array('data' => $datas));
    }

  /*  public function validateConge(){

        //var_dump($this->input->post()); die;
        if($this->input->post() && $this->input->post('validate') && $this->input->post('validate') == 'sent'){

            $data = $this->input->post();
            $toEtat = false;
            if($data['congeEtat'] == A_VALIDER_SUP ){

                if($this->_userRole == ROLE_CADRE){
                    $toEtat = A_VALIDER_DIR;
                }else if($this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_COSTRAT || $this->_userRole == ROLE_CADRE2){
                     $toEtat = A_TRAITER_RH;
                }

            }else if($data['congeEtat'] == A_VALIDER_CADRE2){
                $toEtat = A_VALIDER_DIR;
            }else if($data['congeEtat'] == A_VALIDER_DIR){
                $toEtat = A_TRAITER_RH;
            }

            //Ceci va écraser les autres conditions
            if($this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_COSTRAT || $this->_userRole == ROLE_CADRE2){
                 $toEtat = A_TRAITER_RH;
            }



            
            if($data['congeToValidate'] && $toEtat !== false){
                
                $this->load->model('conges_model');
                if($updated_conge = $this->conges_model->validateConge($data['congeToValidate'], $toEtat)){
                    
                    $dataHisto = [
                        'histoetatconge_conge' => $data['congeToValidate'],
                        'histoetatconge_etat' => $toEtat,
                        'histoetatconge_validateur' => $this->session->userdata('user')['id'],
                        'histoetatconge_date' => date('Y-m-d H:i:s')
                    ];

                    $this->load->model('histoetatconge_model');
                    $updated_conge = $this->histoetatconge_model->insertHistorique($dataHisto);
                }
                
                if($updated_conge)
                    redirect('conges/congesavalider?msg=success');
            }
        }
        redirect('conges/congesavalider?msg=error');
    }*/


    public function validateConge(){

        //var_dump($this->input->post()); die;
        if($this->input->post() && $this->input->post('validate') && $this->input->post('validate') == 'sent'){

            $data = $this->input->post();
            $toEtat = false;
            if($data['congeEtat'] == A_VALIDER_SUP ){
                if($this->_userRole == ROLE_CADRE ){
                    $toEtat = A_VALIDER_DIR;
                }else if($this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_COSTRAT || $this->_userRole == ROLE_CADRE2){
                     $toEtat = A_TRAITER_RH;
                }

            }else if($data['congeEtat'] == A_VALIDER_CADRE2 || $data['congeEtat'] == A_VALIDER_DIR){
                $toEtat = A_TRAITER_RH;
            }

            //Ceci va écraser les autres conditions
            if($this->_userRole == ROLE_DIRECTION || $this->_userRole == ROLE_COSTRAT){
                 $toEtat = A_TRAITER_RH;
            }
            
            if($data['congeToValidate'] && $toEtat !== false){
                
                $this->load->model('conges_model');
                if($updated_conge = $this->conges_model->validateConge($data['congeToValidate'], $toEtat)){
                    
                    $dataHisto = [
                        'histoetatconge_conge' => $data['congeToValidate'],
                        'histoetatconge_etat' => $toEtat,
                        'histoetatconge_validateur' => $this->session->userdata('user')['id'],
                        'histoetatconge_date' => date('Y-m-d H:i:s')
                    ];

                    $this->load->model('histoetatconge_model');
                    $updated_conge = $this->histoetatconge_model->insertHistorique($dataHisto);
                }
                
                if($updated_conge)
                    redirect('conges/congesavalider?msg=success');
            }
        }
        redirect('conges/congesavalider?msg=error');
    }

    public function refuserConge(){

        if($this->input->post() && $this->input->post('refuser') && $this->input->post('refuser') == 'sent'){

            $data = $this->input->post();
            
            if($data['congeToRefuse']){
                
                $this->load->model('conges_model');
                if($updated_conge = $this->conges_model->refuserConge($data['congeToRefuse'])){
                    
                    $dataHisto = [
                        'histoetatconge_conge' => $data['congeToRefuse'],
                        'histoetatconge_etat' => REFUSE,
                        'histoetatconge_validateur' => $this->session->userdata('user')['id'],
                        'histoetatconge_motifrefus' => $data['motif_refus_conge'],
                        'histoetatconge_date' => date('Y-m-d H:i:s')
                    ];
                    $this->load->model('histoetatconge_model');
                    $updated_conge = $this->histoetatconge_model->insertHistorique($dataHisto);
                }

                $page = 'conges/congesavalider';
                if($this->_userRole == ROLE_ADMINRH){
                    $page = 'conges/congesATraiter';
                }
                if ($updated_conge === FALSE){   
                    redirect($page . '?msg=error');
                }else{
                    redirect($page . '?msg=success');
                }
            }
        }
        redirect($page . '?msg=error');
    }

    public function commentConge(){

        if($this->input->post() && $this->input->post('comment') && $this->input->post('comment') == 'sent'){

            $data = $this->input->post();
            
            if($data['congeToComment']){
                
                $this->load->model('conges_model');
                $prenomCommentAutor = $this->session->userdata('user')['prenom'];
                $comment = $prenomCommentAutor . ': ' . $data['conge_commentaire'];
                $updated_conge = $this->conges_model->commentConge($data['congeToComment'], $comment);
                
                if($updated_conge)
                    redirect('conges/congesavalider?msg=success');
            }
        }
        redirect('conges/congesavalider?msg=error');
    }


    /**
     * Afficher les historiques d'état d'une demande de congé
     */

    public function getHistoEtatConge(){
        
        $error = true;
        $datas = false;

        if($this->input->post() && $this->input->post('conge')){

            $this->load->model('histoetatconge_model');
            //$current_user = $this->session->userdata('user')['id'];
            $conge_id = $this->input->post('conge');
            $datas = $this->histoetatconge_model->getAllHistoEtat($conge_id);
            
            if($datas) 
                $error = false;
        }

        echo json_encode(array('error' => $error, 'data' => $datas));
    }


    /**
     * Afficher et traiter les congés 
     */
    public function congesATraiter()
    {
        $header = ['pageTitle' => 'Les congés à traiter - TimeTracking'];
        $this->load->model('conges_model');
        $listTypeConge = $this->conges_model->getAllTypeConge();

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('conges/modalconges', ['listTypeConge' => $listTypeConge]);
        $this->load->view('conges/congesatraiter', []);
        $this->load->view('common/footer', []);
    }


    /*
    * Récupérer les congés validés par la direction 
    */
    public function getCongesATraiter(){


        $this->load->model('user_model');
        $this->load->model('campagne_model');
        $this->load->model('service_model');

        $currentSup = $this->session->userdata('user')['id'];
        $etat = [A_TRAITER_RH];

        if($this->session->userdata('show_validated') == 'true'){
            array_push($etat, VALIDE, REFUSE, REPOS_MALADIE, ASSISTANCE_MATERNELLE, AUTRES, CONGE_ENCOURS, CONGE_TERMINE,CONGE_DE_MATERNITE);
        }
        
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

            
            if($this->_userRole == ROLE_ADMINRH){
                $listSupCampagne = $this->user_model->getListSupByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

            
            if($this->_userRole == ROLE_ADMINRH){
                $listSupCampagne = $this->user_model->getListCadreByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }


           
            if($this->_userRole == ROLE_ADMINRH){
                $listSupCampagne = $this->user_model->getListCadre2ByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }


            
            if($this->_userRole == ROLE_ADMINRH){
                $listSupCampagne = $this->user_model->getListDirectionByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }


            if($this->_userRole == ROLE_ADMINRH){
                $listSupCampagne = $this->user_model->getListCostratByCampagne($listCampagne);
                foreach($listSupCampagne as $agent){
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

            
            if($this->_userRole == ROLE_ADMINRH){
                $listSupService = $this->user_model->getListSupByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

           
            if($this->_userRole == ROLE_ADMINRH){
                $listSupService = $this->user_model->getListCadreByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                    }
            }

            
            if($this->_userRole == ROLE_ADMINRH){
                $listSupService = $this->user_model->getListCadre2ByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                    }
            }

             
            if($this->_userRole == ROLE_ADMINRH){
                $listSupService = $this->user_model->getListDirectionByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }


            if($this->_userRole == ROLE_ADMINRH){
                $listSupService = $this->user_model->getListCostratByService($listService);
                foreach($listSupService as $agent){
                    if(!in_array($agent->usr_id, $listAgent)){
                        $listAgent[] = $agent->usr_id;
                    }
                }
            }

        }


        $this->load->model('conges_model');
        $datas = $this->conges_model->getAllCongesToTreate($listAgent, $etat);

        echo json_encode(array('data' => $datas));
    }

    /**
     * 04/04/2023 
     * -- Ne pas traiter directement les décomptes lors du traitement mais attendre le lancement du cron le jour du début du congé ou permission
     */
    public function treatConge(){
        //Vérifier le profil qui peut effectuer l'action
        if($this->session->userdata('user')['role'] !== ROLE_ADMINRH){
            redirect('dashboard/index');
        }
        $result = false;
        if($this->input->post() && $this->input->post('treatConge') && $this->input->post('treatConge') == 'sent'){
            //Execution des actions à faire selon le choix 
            $choixAction = $this->input->post('actionTraitement');
            $congeId = $this->input->post('congeToTreat');
            $congeEtat = $this->input->post('congeEtat');

            $etat = false;

            if($choixAction == 'avaloirsurconge'){
                $etat = VALIDE;
                $result = $this->_traiterAValoirSurConge($congeId, $etat);
            }else if($choixAction == 'avaloirsurdroitspermission'){
                $etat = VALIDE;
                $result = $this->_traiterAValoirSurDroitsPermission($congeId, $etat);
            }else if($choixAction == 'reposmaladie'){
                $etat = REPOS_MALADIE;
                $result = $this->_traiterReposMaladie($congeId, $etat);
            }else if($choixAction == 'assistancematernelle'){
                $etat = ASSISTANCE_MATERNELLE;
                $result = $this->_traiterAssistanceMaternelle($congeId, $etat);
            }else if($choixAction == 'autres'){
                $etat = AUTRES;
                $result = $this->_traiterAutres($congeId);
            }
            else if($choixAction == 'congedematernite'){
                $etat = CONGE_DE_MATERNITE;
                $result = $this->_traiterCongedeMaternite();
            }
            $this->load->model('conges_model');
            $result = $this->conges_model->validateConge($congeId, $etat);

            if($result){
                $dataHisto = [
                    'histoetatconge_conge' => $congeId,
                    'histoetatconge_etat' => $etat,
                    'histoetatconge_validateur' => $this->session->userdata('user')['id'],
                    'histoetatconge_date' => date('Y-m-d H:i:s')
                ];
                $this->load->model('histoetatconge_model');
                $this->histoetatconge_model->insertHistorique($dataHisto);

                redirect('conges/congesATraiter?msg=success');
            }
        }
        redirect('conges/congesATraiter?msg=error');
    }

    /**
     * A decompter du solde de congés de l'employé
     */
    private function _traiterAValoirSurConge($congeId, $etat){
        $this->load->model('conges_model');
        $this->load->model('user_model');
        //Récupérer l'info du congé 
        $objConge = $this->conges_model->getConge($congeId);
        if($objConge){
            $user = $objConge->conge_user;
            $duree = $objConge->conge_duree;
            $currentSoldeConge = $this->conges_model->getSoldeConge($user);
            $solde = $currentSoldeConge - $duree;
            $res = $this->conges_model->setSoldeConge($user, $solde);

            if($res){
                //Changement d'etat
                if($this->conges_model->validateConge($congeId, $etat))
                    return true;
            }
        }
        return false;
    }

    /**
     * A valoir sur 80h droit de permission 
     */
    private function _traiterAValoirSurDroitsPermission($congeId, $etat){
        $this->load->model('conges_model');
        $this->load->model('user_model');
        $this->load->model('histopermission_model');
        //Récupérer l'info du congé 
        $objConge = $this->conges_model->getConge($congeId);
        if($objConge){
            $res = false;
            $user = $objConge->conge_user;
            $dureeHeure = $objConge->conge_duree * 8;   
            $cPermission = $this->histopermission_model->getTotalPermissionUser($user);
            //On autorise le decompte en permission si inférieur au droit de permission accordé 
            if($cPermission < DROIT_PERMISSION){
                $data = array(
                    'histopermission_conge' => $congeId,
                    'histopermission_user' => $user,
                    'histopermission_duree' => $dureeHeure
                );
                $res = $this->histopermission_model->insertHistopermission($data);
                //Changement de type si besoin
                if($res)
                    $res = $this->conges_model->updateType($congeId, TYPECONGE_PERMISSION);
                if($res)
                    $res = $this->conges_model->validateConge($congeId, $etat);

            }else{
                redirect('conges/congesATraiter?msg=error&details=Droits de permission dépassé');
            }

            if($res){
                return true;
            }
        }
        return false;
    }

    /**
     * Traitement pour Rm
     */
    private function _traiterReposMaladie($congeId, $etat){
        $this->load->model('conges_model');
        $res = $this->conges_model->validateConge($congeId, $etat);
        return $res;
    }

    /**
     * Traitement pour AM
     */
    private function _traiterAssistanceMaternelle($congeId, $etat){
        $this->load->model('conges_model');
        $res = $this->conges_model->validateConge($congeId, $etat);
        return $res;
    }

    /**
     * Pour les autres cas 
     */
    private function _traiterAutres(){
        $this->load->model('conges_model');
        $res = $this->conges_model->validateConge($congeId, $etat);
        return $res;
    }

       /**
     * Pour le cas de cong? de maternit? 
     */
    private function _traiterCongedeMaternite(){
        $this->load->model('conges_model');
        $res = $this->conges_model->validateConge($congeId, $etat);
        return $res;
    }





    public function gestionSoldesDroits()
    {
        if( $this->_userRole !== ROLE_ADMINRH ) redirect('auth/login');
        
        $header = ['pageTitle' => 'Soldes congés et droits permission - TimeTracking'];
        $this->load->model('conges_model');
        $listTypeConge = $this->conges_model->getAllTypeConge();

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('conges/soldesdroits', []);
        $this->load->view('conges/modalconges', ['listTypeConge' => $listTypeConge]);
        $this->load->view('common/footer', []);
    }

    public function getAllSoldesDroits(){

        if( $this->_userRole !== ROLE_ADMINRH ) echo json_encode(array('error' => true, 'message' => 'Not authorized'));

        $this->load->model('user_model');
        $datas = $this->user_model->getAllSoldesDroits();

        echo json_encode(array('data' => $datas));
    }

    public function saveEditSoldesDroits(){

        if( $this->_userRole !== ROLE_ADMINRH ) redirect('auth/login');

        if($this->input->post() && $this->input->post('edit-soldesdroits-user')){

            $data = $this->input->post();
            
            $user = $data['edit-soldesdroits-user'];

            $this->load->model('user_model');
            $oldData = $this->user_model->getSoldesDroitsUser($user);  

            $aSolde = ($oldData && $oldData->usr_soldeconge) ?  $oldData->usr_soldeconge : 0;
            $aDroit =  ($oldData && $oldData->usr_droitpermission) ?  $oldData->usr_droitpermission : 0;

            $updated_soldesdroits = $this->user_model->updateSoldesDroits($user, $data['usr_soldeconge'], $data['usr_droitpermission']);
            $ip = $this->input->ip_address();

            if($updated_soldesdroits){
                $this->load->model('histosoldes_model');
                //ajout dans t_histosoldes
                $dataHisto = [
                    'histosoldes_user' => $user,
                    'histosoldes_datemodif' => date('Y-m-d H:i:s'),
                    'histosoldes_anciensolde' => $aSolde,
                    'histosoldes_nouveausolde' => $data['usr_soldeconge'],
                    'histosoldes_anciendroitpermission' => $aDroit,
                    'histosoldes_nouveaudroitpermission' => $data['usr_droitpermission'],
                    'histosoldes_modificateur' => $this->session->userdata('user')['id'],
                    'histosoldes_commentaire' => $data['edit_soldesdroitscommentaire'],
                    'histosoldes_ipmodificateur' => $ip
                ];
                $this->histosoldes_model->insertHistoSoldes($dataHisto);
                redirect('conges/gestionSoldesDroits?msg=success');
            }
            
        }
        redirect('conges/gestionSoldesDroits?msg=error');
    }

    public function showValidatedConges(){

        if($this->input->post() && $this->input->post('showHideValidated')){

            $this->session->set_userdata('show_validated', $this->input->post('showHideValidated'));
            echo json_encode(array('err' => false)); exit();
        }
        echo json_encode(array('err' => true)); exit();
    }
 
    public function setValidateCongeFilter(){

        if($this->input->post()){
            $filtre = ($this->input->post('etatconge')) ? $this->input->post('etatconge') : '';
            $this->session->set_userdata('filtreetatvalidateconge', $filtre);
           
            echo json_encode(array('err' => false)); exit();
        }
        echo json_encode(array('err' => true)); exit();
    }

    /**
     * Afficher les historiques des modifications des soldes et des droits 
     * Afficher le prénom, l'ip et le commentaire du modificateur 
     **/
     public function histoSoldesDroits()
    {
        if( $this->_userRole !== ROLE_ADMINRH ) redirect('auth/login');
        
        $header = ['pageTitle' => 'Historique des modifications des congés et des droits - TimeTracking'];

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('conges/histosoldesdroits', []);
        
        $this->load->view('common/footer', []);
    }

    public function getAllHistoSoldesDroits(){

        if( $this->_userRole !== ROLE_ADMINRH ) echo json_encode(array('error' => true, 'message' => 'Not authorized'));

        $this->load->model('histosoldes_model');
        $datas = $this->histosoldes_model->getAllHistory();

        echo json_encode(array('data' => $datas));
    }

}
