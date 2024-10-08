<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planning extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
    }

    public function index() 
    {
        //$this->suiviPlanning();
    }

   public function suiviPlanning($type, $id)
    {
        $header = ['pageTitle' => 'Suivi planning - TimeTracking'];
        
        $filtre = $this->session->userdata('filtre_planning');
        $showSemaine = 'show-first';
        $today = new DateTime('tomorrow');
        $numdayToday = $today->format('N');
        if($numdayToday <= 4){
            //Lundi eu jeudi
            $showSemaine = 'show-last';
        }
        if(!$filtre){
            if($numdayToday <= 4){
                $filtre = [
                    'debut' => date('Y-m-d', strtotime("monday this week")),
                    'fin' => date('Y-m-d', strtotime('next Sunday'))
                ];
            }else{
                $filtre = [
                    'debut' => date('Y-m-d', strtotime('next Monday')),
                    'fin' => date('Y-m-d', strtotime('next Sunday', strtotime('next Monday')))
                ];
            }
            
            $this->session->set_userdata('filtre_planning', $filtre);
        }

        
        //Preparation des données pour $id $type
        $info = (object)[];
        if($type == 'campagne'){
            $this->load->model('campagne_model');
            $campagne = $this->campagne_model->getCampagne($id);
            $info->id = $campagne->campagne_id;
            $info->libelle = $campagne->campagne_libelle;
        }else if($type == 'service'){
            $this->load->model('service_model');
            $service = $this->service_model->getService($id);
            $info->id = $service->service_id;
            $info->libelle = $service->service_libelle;
        }
        $duPlanning = $filtre['debut'];
        $auPlanning = $filtre['fin'];
        $this->load->model('planning_model');
        $poids = $this->session->userdata('user')['poids'];
        $dataPlanning = $this->planning_model->getPlanningByTypeAndDates($type, $id, $duPlanning, $auPlanning, $poids);
        $listPlanning = [];
        $joursPlanning = [];
        $finIncl = new DateTime($auPlanning);
        $finIncl->modify('+1 day');
        $period = new DatePeriod(new DateTime($duPlanning), new DateInterval('P1D'), $finIncl);
        /*$period = new DatePeriod(new DateTime($duPlanning), new DateInterval('P1D'), new DateTime($auPlanning));*/

        if(is_array($dataPlanning)){

            foreach($dataPlanning as $planning){
                
                $user = $planning->usr_id;
                $listPlanning[$user]['user'] = $planning->usr_prenom;
                $listPlanning[$user]['pseudo'] = $planning->usr_pseudo;
                $listPlanning[$user]['matricule'] = $planning->usr_matricule;
                $listPlanning[$user]['contrat'] = $planning->site_libelle;

                foreach($period as $objDay){
                    $jourRef = $objDay->format("Y-m-d");
                    $numJourRef = $objDay->format('d');

                    if(!isset($listPlanning[$user]['datas'][$numJourRef])) 
                        $listPlanning[$user]['datas'][$numJourRef] = (object)[
                            'jour' => $jourRef, 
                            'jourSemaine' => $objDay->format("N"),
                            'user' => $user,
                            'prenom' => $planning->usr_prenom
                            
                        ];

                    $joursPlanning[$numJourRef] = $jourRef;
                    if($planning->planning_date && ($planning->planning_date == $jourRef)){
                        $planning->jour = $jourRef;
                        $planning->jourSemaine = $objDay->format("N");
                        $jour = date('d', strtotime($planning->planning_date));
                        $listPlanning[$user]['datas'][$jour] = $planning;
                       
                    }
                }
            }
        }else{
            $listPlanning = [];
        }
        
        // END
        //var_dump($listPlanning); die;
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('planning/planning', [
            'type' => $type, 
            'info' => $info, 
            'listPlanning' => $listPlanning,
            'listJours' => $joursPlanning,
            'filtre' => $filtre,
            'showSemaine' => $showSemaine,
            'role' => $this->_userRole
        ]);
        $this->load->view('common/footer', []);
    }


    public function getAllAgentPlanning()
    {
        $type = $this->input->post('type');
        $typeId = $this->input->post('typeId');
        $poids = $this->session->userdata('user')['poids'];
        $list = false;
        if($type == 'campagne'){
            $this->load->model('campagne_model');
            $list = $this->campagne_model->getAllAgentCampagne($typeId, $poids);
        }else if($type == 'service'){
            $this->load->model('service_model');
            $list = $this->service_model->getAllAgentService($typeId, $poids);
        }
        if($list !== false){
            echo json_encode(['error' => false, 'data' => $list]);
        }else{
            echo json_encode(['error' => true]);
        }

    }


    public function traiterPlanning(){
       
        $input = $this->input->post();

        if($input && isset($input['action']) && !empty($input['action'])){

            $action = $input['action'];

            if(!isset($input['planning_datedebut'])) return false;
            $debut = $input['planning_datedebut'];

            if(!isset($input['planning_heureentree'])) return false;
            $entree = $input['planning_heureentree'];

            if(!isset($input['planning_datefin'])) return false;
            $fin = $input['planning_datefin'];

            if(!isset($input['planning_heuresortie'])) return false;
            $sortie = $input['planning_heuresortie'];

            if(!isset($input['liste_agent'])) return false;
            $listAgent = $input['liste_agent'];

            if(!isset($input['planningJour'])) return false;
            $planningJour = $input['planningJour'];

            if(!isset($input['type_id'])) return false;
            $typeId = $input['type_id'];

            if(!isset($input['type'])) return false;
            $type = $input['type'];

            $this->load->model('planning_model');
            $finIncl = new DateTime($fin);
            $finIncl->modify('+1 day');
            $period = new DatePeriod(new DateTime($debut), new DateInterval('P1D'), $finIncl);
            
            $result = false;
            $entry = [];
            //Delete planning already set 
            $this->planning_model->deletePlanningByDateAndAgents($debut, $fin, $listAgent);

            //Insert new planning
            foreach($period as $objDay){
                $jour = $objDay->format("Y-m-d");
                foreach($listAgent as $agent){
                    $jourSemaine = date('N', strtotime($jour));
                    $isOff = true;
                    if(in_array($jourSemaine, $planningJour)){
                        $isOff = false;
                    }
                    $entry[] = [
                        'planning_date' => $jour,
                        'planning_user' => $agent,
                        'planning_entree' => $entree,
                        'planning_sortie' => $sortie,
                        'planning_off' => $isOff,
                        'planning_datecrea' => date('Y-m-d H:i:s'),
                        'planning_datemodif' => date('Y-m-d H:i:s')
                    ];
                }
            }
            if(!empty($entry)){
                $result = $this->planning_model->insertBatchPlanning($entry);
            }
            
            if ($result === FALSE){
                redirect('planning/suiviPlanning/' . $type . '/' . $typeId . '?msg=error');
            }else{
                redirect('planning/suiviPlanning/' . $type . '/' . $typeId . '?msg=success');
            }
        }
    }

    public function traiterPlanningUser(){
       
        $input = $this->input->post();
        
        if($input && isset($input['planningId']) && !empty($input['planningId'])){


            $id = $input['planningId'];

            if(!isset($input['planninguser_heureentree'])) return false;
            $entree = $input['planninguser_heureentree'];

            if(!isset($input['planninguser_heuresortie'])) return false;
            $sortie = $input['planninguser_heuresortie'];

            if(!isset($input['planninguser_off'])) return false;
            $isOff = ($input['planninguser_off']) ? true : false;

            //if(!isset($input['planninguser_hs'])) return false;
            $isHs = ((isset(input['planninguser_hs']) && $input['planninguser_hs'])) ? true : false;

            if(!isset($input['type_id'])) return false;
            $typeId = $input['type_id'];

            if(!isset($input['type'])) return false;
            $type = $input['type'];

            $this->load->model('planning_model');

            $result = false;
            $planning = $this->planning_model->getPlanning($id);
            //var_dump($this->db->last_query(), $planning); die;
            if($planning){
                $planning->planning_off = $isOff;
                $planning->planning_hs = $isHs;
                $planning->planning_entree = $entree;
                $planning->planning_sortie = $sortie;

                $dataPlanning = (array)$planning;
                $result = $this->planning_model->updatePlanning($id, $dataPlanning);
                //var_dump($this->db->last_query(), $result); die;
            }
            
            if ($result === FALSE){
                redirect('planning/suiviPlanning/' . $type . '/' . $typeId . '?msg=error');
            }else{
                redirect('planning/suiviPlanning/' . $type . '/' . $typeId . '?msg=success');
            }

        }else if($input && isset($input['planningId']) && empty($input['planningId'])){
            //echo 'here';
            //ajout de planning
            if(!isset($input['planninguser_date'])) return false;
            $pdate = $input['planninguser_date'];

            if(!isset($input['planninguser_user'])) return false;
            $user = $input['planninguser_user'];

            if(!isset($input['planninguser_heureentree'])) return false;
            $entree = $input['planninguser_heureentree'];

            if(!isset($input['planninguser_heuresortie'])) return false;
            $sortie = $input['planninguser_heuresortie'];

            if(!isset($input['planninguser_off'])) return false;
            $isOff = ($input['planninguser_off']) ? true : false;

            if(!isset($input['planninguser_hs'])) return false;
            $isHs = ($input['planninguser_hs']) ? true : false;

            if(!isset($input['type_id'])) return false;
            $typeId = $input['type_id'];

            if(!isset($input['type'])) return false;
            $type = $input['type'];

            $this->load->model('planning_model');
            $datas = [
                'planning_date' => $pdate,
                'planning_user' => $user,
                'planning_entree' => $entree,
                'planning_sortie' => $sortie,
                'planning_off' => $isOff,
                'planning_hs' => $isHs,
                'planning_datecrea' => date('Y-m-d'),
                'planning_datemodif' => date('Y-m-d')
            ];
            $result = $this->planning_model->insertPlanning($datas);
            if ($result === FALSE){
                redirect('planning/suiviPlanning/' . $type . '/' . $typeId . '?msg=error');
            }else{
                redirect('planning/suiviPlanning/' . $type . '/' . $typeId . '?msg=success');
            }
        }
        redirect('dashboard/index/?msg=error');
    }

    public function setFiltrePlanning(){
        $filtre_debut = $this->input->post('debut');
        $filtre_fin = $this->input->post('fin');
        //Mise à jour session
        $filtrePlanning = [
            'debut' => $filtre_debut,
            'fin' => $filtre_fin
        ];
        $this->session->set_userdata('filtre_planning', $filtrePlanning);
        echo json_encode(['err' => false]);
    }   

    public function deletePlanning(){
        $input = $this->input->post();

        if($input && isset($input['actiondel']) && !empty($input['actiondel'])){

            $action = $input['action'];

            if(!isset($input['planningdel_datedebut'])) return false;
            $debut = $input['planningdel_datedebut'];

            if(!isset($input['planningdel_datefin'])) return false;
            $fin = $input['planningdel_datefin'];

            if(!isset($input['liste_agent_del'])) return false;
            $listAgent = $input['liste_agent_del'];

            if(!isset($input['deltype_id'])) return false;
            $typeId = $input['deltype_id'];

            if(!isset($input['deltype'])) return false;
            $type = $input['deltype'];

            $this->load->model('planning_model');
            $finIncl = new DateTime($fin);
            $finIncl->modify('+1 day');
            $period = new DatePeriod(new DateTime($debut), new DateInterval('P1D'), $finIncl);
            
            $result = false;
            $entry = [];
            //Delete planning already set 
            $result = $this->planning_model->deletePlanningByDateAndAgents($debut, $fin, $listAgent);
            
            if ($result === FALSE){
                redirect('planning/suiviPlanning/' . $type . '/' . $typeId . '?msg=error');
            }else{
                redirect('planning/suiviPlanning/' . $type . '/' . $typeId . '?msg=success');
            }
        }
    }
    
}