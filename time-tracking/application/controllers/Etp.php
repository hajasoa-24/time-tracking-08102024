<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Etp extends MY_Controller {

    public function __construct() 
    {
        parent::__construct();
    }

    public function index() 
    {
        $this->ressource();
    }


    public function ressource()
    {

        $header = ['pageTitle' => 'ETP - Ressources'];
        $month = $this->input->get('m');
        $year = $this->input->get('y');
        if(!$month){ $month = date('m'); }
        if(!$year){ $year = date('Y'); }

        $debutMonth = date('Y-m-01', strtotime("$year-$month-01"));
        $lastMonth = date('Y-m-t', strtotime("$year-$month-01"));

        $this->load->model('etp_model');
        $ressources = $this->etp_model->getEtpRessource($debutMonth, $lastMonth);
        //var_dump($ressources); die;
        //$this->load->view('calendar', []);
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('etp/ressource', ['ressources' => $ressources]);
        $this->load->view('common/footer', []);

    }

    public function addRessource()
    {
        $campagne = $this->input->post('ressource_campagne');
        $mission = $this->input->post('ressource_mission');
        $array_nb_ressource = $this->input->post('ressource_day_nombre');
        $array_day_ressource = $this->input->post('ressource_day');
        $data = [];
        $days = [];
        if(is_array($array_day_ressource) && !empty($array_day_ressource)
            && $campagne && $mission
        ){
            foreach($array_day_ressource as $index => $day){
                $ressource = $array_nb_ressource[$index];
                if($ressource){
                    $tempData = [
                        'etpressource_campagne' => $campagne,
                        'etpressource_mission' => $mission,
                        'etpressource_date' => $day,
                        'etpressource_nombre' => $ressource,
                        'etpressource_datecrea' => date('Y-m-d H:i:s'),
                        'etpressource_datemodif' => date('Y-m-d H:i:s')
                    ];
                    $days[] = $day;
                    $data[] = $tempData;
                }
            }
            if(!empty($data)){
                //On sauvegarde en mode transactionnel
                $this->load->model('etp_model');
                $this->db->trans_begin();
                $this->etp_model->deleteRessource($days, $campagne, $mission);
                $this->etp_model->setBatchRessource($data);
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    redirect('etp/ressource?msg=error');
                }else{
                    $this->db->trans_commit();
                    redirect('etp/ressource?msg=success');
                }
            }
        }
    }


    public function validationRessource()
    {

        //Init des filtres à la date du jour
        $filtreValidationRessourceDu = (isset($this->session->userdata('filtreValidationRessource')['Du'])) ? $this->session->userdata('filtreValidationRessource')['Du'] : date('Y-m-d', strtotime('0 days'));
        $filtreValidationRessourceAu = (isset($this->session->userdata('filtreValidationRessource')['Au'])) ? $this->session->userdata('filtreValidationRessource')['Au'] : date('Y-m-d', strtotime('0 days'));

        $currentValidationRessourceDu = $this->input->get('filtreValidationRessourceDu');
        $currentValidationRessourceAu = $this->input->get('filtreValidationRessourceAu');

        if($currentValidationRessourceDu){
            $filtreValidationRessourceDu = $currentValidationRessourceDu;
            //Enregistrement en session
            
        }
        if($currentValidationRessourceAu){
            $filtreValidationRessourceAu = $currentValidationRessourceAu;
        }
        
        $sessionfiltreValidationRessource = array('Du' => $filtreValidationRessourceDu, 'Au' => $filtreValidationRessourceAu);
        $this->session->set_userdata('filtreValidationRessource', $sessionfiltreValidationRessource);


        $this->load->model('Etatressource_model');
        $listEtatRessource = $this->Etatressource_model->getAllEtatRessource($filtreValidationRessourceDu, $filtreValidationRessourceAu);
        //var_dump($this->input->get());

        $header = ['pageTitle' => 'ETP - Validation Ressources'];

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('etp/validationressource', array(
            'listEtatRessource'=> $listEtatRessource,
            'filtreValidationRessourceDu' => $filtreValidationRessourceDu,
            'filtreValidationRessourceAu' => $filtreValidationRessourceAu,
         ));
        $this->load->view('common/footer', []);

    }

    public function suivietp()
    {
        setlocale(LC_TIME,'fr_FR','french','French_France.1252','fr_FR.ISO8859-1','fra');
        $annee = date('Y');
        /*$mois = date('n');
        $annee = date('Y');
        $sMois = $this->input->get('mois');
        $sAnnee = $this->input->get('annee');
        if($sMois) $mois = $sMois;
        if($sAnnee) $annee = $sAnnee;*/

        $listJoursFeries = [];
        $cUser = $this->_userId; 
        $this->load->model('etp_model');
        $dataETP = $this->etp_model->getSuiviEtp( $cUser); 

        $listETP = $this->_prepareETP($dataETP);

        $dataETPService = $this->etp_model->getDataService($cUser); 
        $listETPService = $this->_prepareETPService($dataETPService);

        $mois = array();
        // Boucle pour générer les noms de mois
        for ($i = 1; $i <= 12; $i++) {
            //$mois[$i] = date("M", mktime(0, 0, 0, $i, 1));
            $mois[$i] = utf8_encode(strftime('%b', mktime(0, 0, 0, $i, 1)));
        }
        $this->load->model('livrepaie_model');
        $livrePaie = $this->livrepaie_model->getLivrepaieByYear($annee); 

        //var_dump($listETPService); die;
        $header = ['pageTitle' => 'ETP - suivi'];
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('etp/suivietp', array(
            'listMois' => $mois,
            'listETP' => $listETP,
            'year' => $annee,
            'livrePaie' => $livrePaie,
            'role' => $this->_userRole,
            'listETPService' => $listETPService,
         ));
        $this->load->view('common/footer', []);

    }

    public function getMcpDatas()
    {
        //die('ok'); 

        //Chargement des filtres
        $filtre = $this->session->userdata('filtreValidationRessource');
        if(is_array($filtre) && !empty($filtre)){
            $filtreDu = (isset($filtre['Du']) ? $filtre['Du'] : date('Y-m-d'));
            $filtreAu = (isset($filtre['Au']) ? $filtre['Au'] : date('Y-m-d'));
        }


        $this->load->model('etp_model');
        $cUser = $this->_userId; //NEW
        $datas = $this->etp_model->getInfosMcp($cUser, $filtreDu, $filtreAu); 
        //var_dump($datas); die;
        if(!$datas) $datas = [];
        
        echo json_encode(array('data' => $datas));
    }


    private function _prepareETP($datas)
    {
        //var_dump($datas);
        $list = [];
        $etpDatas = [];
        if($datas){
            foreach($datas as $data){
            $campagne = $data->mcp_campagne;
            $mission = $data->mcp_mission;
            $index = $campagne . '_' . $mission;

            //$dYear = date('Y', strtotime($data->mcp_date));
            //$dMonth = date('n', strtotime($data->mcp_date));
            //$dDay = date('j', strtotime($data->mcp_date));

            $dMonth = $data->mois;
            $dYear = $data->annee;

            if(!array_key_exists($index, $list)){
                $list[$index] = (Object) [
                    'site' => $data->site,
                    'proprio' => $data->proprio_libelle,
                    'client' => $data->client,
                    'mission' => $data->mission_libelle,
                    'etpdatas' => []
                ];
            }
            $etpDatas = $list[$index]->etpdatas;

            if(!array_key_exists($dMonth, $etpDatas)){
                $etpDatas[$dMonth] = [];
            }
            //Calcul des jours ouvrés du mois
            $listJoursFeries = $this->getJoursFeries($dMonth, $dYear);
            $joursFeries = [];
            foreach($listJoursFeries as $ferie){
                $joursFeries[] = $ferie->holidays_date;
            }
            $joursOuvres = $this->getJoursOuvres($dMonth, $dYear, $joursFeries);
            $etpDatas[$dMonth][$data->etatressource_id][] = (Object)[
                'etatressource' => $data->etatressource_id,
                'isfacture' => $data->etatressource_facturation,
                'jourstravaille' => $data->joursTravaille,
                'lib' => $data->etatressource_libelle,
                'joursouvres' => $joursOuvres,
                'role' => $data->role_id
            ];


            $list[$index]->etpdatas = $etpDatas;
          }
        }
        

        //var_dump($list); die;

        return $list;

    }
   
    private function _prepareETPService($datas)
    {
        $list = [
            'CODIR / COSTRAT' => []
        ];
        if($datas !== FALSE){

            foreach($datas as $data){

                $dMonth = $data->mois;
                $dYear = $data->annee;

                $listJoursFeries = $this->getJoursFeries($dMonth, $dYear);
                $joursFeries = [];
                foreach($listJoursFeries as $ferie){
                    $joursFeries[] = $ferie->holidays_date;
                }
                $joursOuvres = $this->getJoursOuvres($dMonth, $dYear, $joursFeries);
                
                //On regarde les roles pour isoler les costrat et codir
                if($data-> role_id == ROLE_DIRECTION || $data->role_id == ROLE_COSTRAT){
                    if(!isset($list['CODIR / COSTRAT'][$data->mois])) 
                        $list['CODIR / COSTRAT'][$data->mois] = (Object)[
                            'joursouvres' => $joursOuvres,
                            'libelle' => 'CODIR / COSTRAT',
                            'jourstravaille' => 0
                        ];
                    $list['CODIR / COSTRAT'][$data->mois]->jourstravaille += $data->duree_shift;
                }else{
                    if(!isset($list[$data->service_libelle][$data->mois])) 
                        $list[$data->service_libelle][$data->mois] = (Object)[
                            'joursouvres' => $joursOuvres,
                            'libelle' => $data->service_libelle,
                            'jourstravaille' => 0
                        ];
                    $list[$data->service_libelle][$data->mois]->jourstravaille += $data->duree_shift;
                }
            }
        }
        return $list;
    }


    public function donneesRessource()
    {

        //Init des filtres à la date du jour
        $filtreValidationRessourceDu = (isset($this->session->userdata('filtreValidationRessource')['Du'])) ? $this->session->userdata('filtreValidationRessource')['Du'] : date('Y-m-d');
        $filtreValidationRessourceAu = (isset($this->session->userdata('filtreValidationRessource')['Au'])) ? $this->session->userdata('filtreValidationRessource')['Au'] : date('Y-m-d');


        $currentValidationRessourceDu = $this->input->get('filtreValidationRessourceDu');
        $currentValidationRessourceAu = $this->input->get('filtreValidationRessourceAu');

        if($currentValidationRessourceDu){
            $filtreValidationRessourceDu = $currentValidationRessourceDu;
            //Enregistrement en session
            
        }
        
        if($currentValidationRessourceAu){
            $filtreValidationRessourceAu = $currentValidationRessourceAu;
        }
        
        $sessionfiltreValidationRessource = array('Du' => $filtreValidationRessourceDu, 'Au' => $filtreValidationRessourceAu);
        $this->session->set_userdata('filtreValidationRessource', $sessionfiltreValidationRessource);


        $this->load->model('Etatressource_model');
        $listEtatRessource = $this->Etatressource_model->getAllEtatRessource($filtreValidationRessourceDu, $filtreValidationRessourceAu);
        //var_dump($this->input->get());

        $header = ['pageTitle' => 'ETP - Validation Ressources'];

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('etp/donneesressource', array(
            'listEtatRessource'=> $listEtatRessource,
            'filtreValidationRessourceDu' => $filtreValidationRessourceDu,
            'filtreValidationRessourceAu' => $filtreValidationRessourceAu,
         ));
        $this->load->view('common/footer', []);

    }

    public function getMesDatasMcp()
    {
        //die('ok'); 

        //Chargement des filtres
        $filtre = $this->session->userdata('filtreValidationRessource');
        if(is_array($filtre) && !empty($filtre)){
            $filtreDu = (isset($filtre['Du']) ? $filtre['Du'] : date('Y-m-d'));
            $filtreAu = (isset($filtre['Au']) ? $filtre['Au'] : date('Y-m-d'));
        }

        $this->load->model('etp_model');
        $current_user = $this->session->userdata('user')['id'];
        $datas = $this->etp_model->getInfosMcpByUser($current_user);
        //var_dump($datas); die;
        if(!$datas) $datas = [];
        
        echo json_encode(array('data' => $datas));
    }

    public function addQuantityProduction()
    {

        $id_mcp = $this->input->post('mcp_id');
        $quantity_mcp = $this->input->post('mcpquantity');

        $demande = array();

        $demande['mcp_quantite'] = $quantity_mcp;
        $this->load->model('etp_model');
        $update = $this->etp_model->addQuantityMcpProd($demande,$id_mcp);

    }


    public function addDetailsTask()
    {
        $id_mcp = $this->input->post('id_mcp');
        $quantity_mcp = $this->input->post('mcp_quantity');

        if($quantity_mcp == ''){
            $quantity_mcp = 1 ;
        }

        else {
            $quantity_mcp ++;
        }

        $this->load->model('etp_model');
        $demande = array();
        $demande['mcp_quantite'] = $quantity_mcp;

        $this->load->model('etp_model');
        $update = $this->etp_model->addQuantityMcpProd($demande,$id_mcp);

        $donneMcp = array();
        $donneMcp["mcpdetails_mcp"] = $id_mcp;
        $donneMcp["mcpdetails_commentaire"] = "";
        $donneMcp["mcpdetails_datecrea"] = date('Y-m-d H:i:s');
        $donneMcp["mcpdetails_datedebut"] = date('Y-m-d H:i:s');
        $donneMcp["mcpdetails_datemodif"] = date('Y-m-d H:i:s');

        $lastId = $this->etp_model->insertMcpDetails($donneMcp);

        echo json_encode(array('data' => $lastId));
            
    }


    public function finDetailTask()
    {

        $detail = $this->input->post('detailcommentaire');
        $detail1 = $this->input->post('detailcommentaire1');
        $detail2 = $this->input->post('detailcommentaire2');
        $detail3 = $this->input->post('detailcommentaire3');
        $detail4 = $this->input->post('detailcommentaire4');


        $iddetails = $this->input->post('iddetail');

        $demande = array();

        $demande['mcpdetails_commentaire'] = $detail;
        $demande['mcpdetails_detail1'] = $detail1;
        $demande['mcpdetails_detail2'] = $detail2;
        $demande['mcpdetails_detail3'] = $detail3;
        $demande['mcpdetails_detail4'] = $detail4;

        //vérification si presence de CA à additionner 
        if(strpos($detail4, TECHNODEV_CA_PATERN) !== FALSE){
            
            $this->load->model('etp_model');
            $mcp = $this->etp_model->getDetailsTaskById($iddetails);

            if($mcp){
                //récupérer le CA actuel
                $mcp_ca = $mcp->mcp_ca;
                $mcpToAdd = floatval(str_replace(TECHNODEV_CA_PATERN, '', $detail4));
                $newMcpCA = floatval($mcp_ca) + $mcpToAdd;

                //On met à jour mcp_ca
                $this->etp_model->updateCAMcpProd($newMcpCA, $mcp->mcp_id);
            }
        }


        $demande['mcpdetails_datefin'] = date('Y-m-d H:i:s');


        $this->load->model('etp_model');
        $update = $this->etp_model->addDetailsMcp($demande,$iddetails);
        

    }

        
    public function getIdTaskDetail($id)
    {
        $this->load->model('etp_model');

        $list_detailmcp = $this->etp_model->getDetailsTask($id);

        echo json_encode(array('data' => $list_detailmcp));


    }
    
    
    public function checkOngoingDetailTask()
    {
        $currentMcp = $this->input->post('mcp');
        // $returnData = array('data' => 'Erreur de traitement', 'error' => true);
          
        if($currentMcp){

            // $user = $this->_userId;
    
            $this->load->model('etp_model');
            $detailTask = $this->etp_model->getOngoingUserDetailTask($currentMcp);

            if($detailTask) 
            {
                $returnData = array('error' => false, 'data' => $detailTask);
            }
            else {
                $returnData = array('error' => false, 'data' => false);
            }
            
        }
        
        echo json_encode($returnData);
    }

} 