<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homeland extends MY_Controller {
	private $userId;
	private $userRole;

	public function __construct()
	{
		parent::__construct();
		$this->userId = $this->session->userdata('user')['id'];
		$this->userRole = $this->session->userdata('user')['role'];
		$this->load->model('Prodhomeland_model');
	}

	/**
     * =================
     * PUBLIC FUNCTIONS
     * =================
     */
    
    public function reportHomeland()
    {
       //filtre
       $filtreDu = ($this->input->get('filtreProdHomelandDu')) ? $this->input->get('filtreProdHomelandDu') : ((isset($this->session->userdata('filtreProdHomeland')['Du'])) ? $this->session->userdata('filtreProdHomeland')['Du'] : date('Y-m-d'));
       $filtreAu = ($this->input->get('filtreProdHomelandAu')) ? $this->input->get('filtreProdHomelandAu') : ((isset($this->session->userdata('filtreProdHomeland')['Au'])) ? $this->session->userdata('filtreProdHomeland')['Au'] : date('Y-m-d'));
       $filtreProdHomeland = ['Du' => $filtreDu, 'Au' => $filtreAu];
       $this->session->set_userdata('filtreProdHomeland', $filtreProdHomeland);

        $header = ['pageTitle' => 'Report Homeland - TimeTracking'];
        //var_dump($this->_sidebar); die;
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('homeland/reporthomeland', ['filtre' => $filtreProdHomeland]);

        $this->load->view('common/footer', []);
    }


    public function suiviTemps()
    {
        $header = ['pageTitle' => 'Suivi Temps - Homeland'];

        $data['poles'] = $this->Prodhomeland_model->getPoles();
        $data['appels'] = $this->Prodhomeland_model->getAppel();
        $data['mails'] = $this->Prodhomeland_model->getMail();
        $data['affectations'] = $this->Prodhomeland_model->getAffectation();
        $data['sinistres'] = $this->Prodhomeland_model->getSinistre();
        $data['comptas'] = $this->Prodhomeland_model->getCompta();
        $data['juridiques'] = $this->Prodhomeland_model->getJuridique();
        $data['pededs'] = $this->Prodhomeland_model->getPeded();
        $data['immas'] = $this->Prodhomeland_model->getImma();
        $data['techniques'] = $this->Prodhomeland_model->getTechnique();
        $data['mesures'] = $this->Prodhomeland_model->getMesure();
        $data['parametrages'] = $this->Prodhomeland_model->getParametrage();

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('homeland/compterendu', $data);
        $this->load->view('common/footer', []);
    }


    /**
     * 
     */


    public function getReportHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getReportHomeland($du, $au, $userId, $userRole);

        //Partie de traitement du datas selon le besoin final

        $formattedDatas = $this->_formatReportHomeland($datas);
		header('Content-Type: application/json');
        echo json_encode(array('data' => $formattedDatas));
    }


	public function getReportAppelHomeland()
	{
		$du = $this->session->userdata('filtreProdHomeland')['Du'];
		$au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

		$this->load->model('prodhomeland_model');
		$datas = $this->prodhomeland_model->getAppelHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
		echo json_encode(array('data' => $datas));
	}



	public function getReportMailHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getMailHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }


    public function getReportAffectationHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getAffectationHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }

    public function getReportAutretachesHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getAutretachesHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }

    public function getReportComptaHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getComptaHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }

    public function getReportJuridiqueHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getJuridiqueHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }

    public function getReportPededHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getPededHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }


    public function getReportImmaHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getImmaHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }


    public function getReportSinistreHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getSinistreHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }


    public function getReportTechniqueHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getTechniqueHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }


    public function getReportMajhboHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getMajhboHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }


    public function getReportDispatchHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getDispatchHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }


    public function getReportParametrageHomeland()
    {
        //Récupération filtre
        $du = $this->session->userdata('filtreProdHomeland')['Du'];
        $au = $this->session->userdata('filtreProdHomeland')['Au'];

		$userId = $this->userId;
		$userRole = $this->userRole;

        $this->load->model('prodhomeland_model');
        $datas = $this->prodhomeland_model->getParametrageHomeland($du, $au, $userId, $userRole);

		header('Content-Type: application/json');
        echo json_encode(array('data' => $datas));
    }



    /**
     * ===================
     * PRIVATE FUNCTIONS
     * ===================
     */
    private function _formatReportHomeland($datas)
    {
        $formattedDatas = [];
        $tempDataPole = [];
        if(!empty($datas)){
            foreach($datas as $pole => $dataPole){
                //var_dump($dataPole);
                if(!empty($dataPole)){
                    
                    foreach($dataPole as $data){
                        if(!isset($tempDataPole[$data->usr_id])){
                            $tempDataPole[$data->usr_id] = (array)[
                                'agent' => $data->usr_prenom,
								'username' => $data->usr_username,
                                'appel' => 0,
                                'mail' => 0,
                                'affectation' => 0,
                                'autrestaches' => 0,
                                'comptabilite' => 0,
                                'juridique' => 0,
                                'peded' => 0,
                                'immatriculation' => 0,
                                'sinistre' => 0,
                                'technique' => 0,
                                'majhbo' => 0,
                                'dispatch' => 0,
                                'parametrage' => 0,

                            ];
                        }
                        
                        $tempDataPole[$data->usr_id][$pole] += 1;
                    }
                }
            }
        }
        foreach($tempDataPole as $tempData){
            $formattedDatas[] = $tempData;
        }

        return $formattedDatas;
    }
	public function insertAppelHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$categorie = $this->input->post('categorieId');
		$nomclient = $this->input->post('nomClient');
		$adresse = $this->input->post('adresse');
		$contact = $this->input->post('contact');
		$commentaire = $this->input->post('commentaire');
		$mesure = $this->input->post('mesurePrise');

		return $this->Prodhomeland_model->insertAppelHomelandData($user, $date, $categorie, $nomclient, $adresse, $contact, $mesure, $commentaire);
	}

	public function insertMailHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$categorie = $this->input->post('categorieId');
		$nomclient = $this->input->post('nomClient');
		$adresse = $this->input->post('adresse');
		$contact = $this->input->post('contact');
		$commentaire = $this->input->post('commentaire');
		$mesure = $this->input->post('mesurePrise');

		return $this->Prodhomeland_model->insertMailHomelandData($user, $date, $categorie, $nomclient, $adresse, $commentaire, $contact, $mesure);
	}


	public function insertImmaHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$debut = $this->input->post('debut');
		$fin = date('Y-m-d H:i:s');
		$dureeMinutes = $this->calculerDuree($debut, $fin);
		$categorie = $this->input->post('categorieId');
		$nbtraitement = $this->calculerNombreTraitements($dureeMinutes);

		return $this->Prodhomeland_model->insertImmaHomelandData($user, $date, $categorie, $debut, $fin, $dureeMinutes, $nbtraitement);
	}




	public function insertAffectationHomeland()
    {
        $user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d H:i:s');
        $typeaffectation = $this->input->post('categorieId');
        $dossier = $this->input->post('dossier');

        return $this->Prodhomeland_model->insertAffectationHomelandData($user, $date, $typeaffectation, $dossier);
    }

	public function insertAutresTachesHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$lien = $this->input->post('lien');
		$motif = $this->input->post('motif');
		$debut = $this->input->post('debut');
		$fin = date('Y-m-d H:i:s');
		$dureeMinutes = $this->calculerDuree($debut, $fin);
		$nbtraitement = $this->calculerNombreTraitements($dureeMinutes);

		return $this->Prodhomeland_model->insertAutresTachesHomelandData($user, $date, $lien, $motif, $debut, $fin, $dureeMinutes, $nbtraitement);
	}

	public function insertComptabiliteHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$categorie = $this->input->post('categorieId');
		$debut = $this->input->post('debut');
		$fin = date('Y-m-d H:i:s');
		$dureeMinutes = $this->calculerDuree($debut, $fin);
		$nbtraitement = $this->calculerNombreTraitements($dureeMinutes);

		return $this->Prodhomeland_model->insertComptabiliteHomelandData($user, $date, $categorie, $debut, $fin, $dureeMinutes, $nbtraitement);
	}


	public function insertJuridiqueHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$categorie = $this->input->post('categorieId');
		$refdossier = $this->input->post('refdossier');
		$debut = $this->input->post('debut');
		$fin = date('Y-m-d H:i:s');
		$dureeMinutes = $this->calculerDuree($debut, $fin);
		$nbtraitement = $this->calculerNombreTraitements($dureeMinutes);

		return $this->Prodhomeland_model->insertJuridiqueHomelandData($user, $date, $categorie, $refdossier, $debut, $fin, $dureeMinutes, $nbtraitement);
	}

	public function insertPededHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$categorie = $this->input->post('categorieId');
		$debut = $this->input->post('debut');
		$fin = date('Y-m-d H:i:s');

		$resultats = $this->calculerNombreProductions($categorie);
		$nbtraitement = $resultats['nbtraitement'];
		$nbproduction = $resultats['nbproduction'];

		$dureeFormatee = $this->calculerDuree($debut, $fin);
		$nomvente = $this->input->post('nomvente');
		$commentaire = $this->input->post('commentaire');

		return $this->Prodhomeland_model->insertPededHomelandData($user, $date, $categorie, $nomvente, $commentaire, $debut, $fin, $dureeFormatee, $nbtraitement, $nbproduction);
	}

	public function insertSinistreHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$categorie = $this->input->post('categorieId');
		$numdossier = $this->input->post('numdossier');
		$debut = $this->input->post('debut');
		$fin = date('Y-m-d H:i:s');
		$dureeMinutes = $this->calculerDuree($debut, $fin);
		$nbtraitement = $this->calculerNombreTraitements($dureeMinutes);

		return $this->Prodhomeland_model->insertSinistreHomelandData($user, $date, $debut, $fin, $dureeMinutes, $nbtraitement, $categorie, $numdossier);
	}



	public function insertTechniqueHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$categorie = $this->input->post('categorieId');
		$adresse = $this->input->post('adresse');
		$debut = $this->input->post('debut');
		$fin = date('Y-m-d H:i:s');
		$dureeFormatee = $this->calculerDuree($debut, $fin);
		$nbtraitement = $this->calculerNombreTraitements($dureeFormatee);

		return $this->Prodhomeland_model->insertTechniqueHomelandData($user, $date, $categorie, $adresse, $debut, $fin, $dureeFormatee, $nbtraitement);
	}



	public function insertMajhboHomeland()
    {
        $user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d H:i:s');
        $type = $this->input->post('typemaj');
        $lien = $this->input->post('lien');

        return $this->Prodhomeland_model->insertMajhboHomelandData($user, $date, $type, $lien);

    }

    public function insertDispatchHomeland()
    {
        $user = $this->session->userdata('user')['id'];
		$date = new DateTime();
        $formatted_date = $date->format("Y-m-d H:i:s");

        $lien = $this->input->post('lien_dispatch');
        $debut = $this->input->post('debut');
        // $dispatchCount = $this->input->post('nbtraitement');
        // $fin = date('Y-m-d H:i:s');
        // $dureeMinutes = $this->calculerDuree($debut, $fin);

        $data = array(
            'dispatchhomeland_user' => $user,
            'dispatchhomeland_date' => $formatted_date,
            'dispatchhomeland_debut' => NULL,
            'dispatchhomeland_lien' => $lien,
            'dispatchhomeland_fin' => NULL,
            'dispatchhomeland_nbtraitement' => NULL,
            'dispatchhomeland_duree' => NULL
        );

        return $this->Prodhomeland_model->insertDispatch($data);
    }

    public function insertParametrageHomeland()
	{
		$user = $this->session->userdata('user')['id'];
		date_default_timezone_set('Europe/Paris');
		$date = date('Y-m-d H:i:s');
		$categorie = $this->input->post('categorieId');
		$lien = $this->input->post('lien');
		$debut = $this->input->post('debut');
		$fin = date('Y-m-d H:i:s');
		$dureeFormatee = $this->calculerDuree($debut, $fin);
		$nbtraitement = $this->calculerNombreTraitements($dureeFormatee);
        	$adresse = $this->input->post('adresse');

		return $this->Prodhomeland_model->insertParametrageHomelandData($user, $date, $categorie, $lien, $debut, $fin, $dureeFormatee, $nbtraitement, $adresse);
	}

	function calculerDuree($debut, $fin) {
		if (empty($debut) || empty($fin)) {
			return '00:00:00';
		}

		$debutDateTime = new DateTime($debut);
		$finDateTime = new DateTime($fin);
		$interval = $debutDateTime->diff($finDateTime);
		$dureeFormatee = $interval->format('%H:%I:%S');

		return $dureeFormatee;
	}


	public function calculerNombreTraitements($duree)
	{
		list($heures, $minutes, $secondes) = explode(':', $duree);
		$dureeEnMinutes = $heures * 60 + $minutes + $secondes / 60;
		$dureeReferenceMinutes = 15;
		$nombreTraitements = ceil($dureeEnMinutes / $dureeReferenceMinutes * 50);
		$nombreTraitements = intval($nombreTraitements);

		return $nombreTraitements;
	}




	function calculerNombreProductions($categorie)
    {
        $nbtraitement = 1;
        $nbproduction = 1;

        if ($categorie == ED_PED_CERT) {
            $nbtraitement = 1;
            $nbproduction = 50;
        } elseif ($categorie == CARNET_ENTRETIENT) {
            $nbtraitement = 1;
            $nbproduction = 25;
        } else {
        	$nbtraitement = 1;
        }
        return array('nbtraitement' => $nbtraitement, 'nbproduction' => $nbproduction);
    }

    
	function getServerTimeEuropeParis() {
		date_default_timezone_set('Europe/Paris');
		$serverTimeEuropeParis = date('Y-m-d H:i:s');
		echo json_encode($serverTimeEuropeParis);
		return $serverTimeEuropeParis;
	}

}
