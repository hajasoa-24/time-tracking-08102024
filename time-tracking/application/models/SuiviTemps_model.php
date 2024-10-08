<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SuiviTemps_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insertData($table_name, $data)
	{
		return $this->db->insert($table_name, $data) ? 'success' : 'error';
	}

	public function insertAppelHomelandData($user, $date, $categorie, $nomclient, $adresse, $contact, $mesure, $commentaire)
	{
		$table_name = 't_appelhomeland';

		$data = array(
			'appelhomeland_user' => $user,
			'appelhomeland_date' => $date,
			'appelhomeland_categorie' => $categorie,
			'appelhomeland_nomclient' => $nomclient,
			'appelhomeland_adresse' => $adresse,
			'appelhomeland_contact' => $contact,
			'appelhomeland_mesure' => $mesure,
			'appelhomeland_commentaire' => $commentaire,
		);

		return $this->insertData($table_name, $data);
	}

	public function insertMailHomelandData($user, $date, $categorie, $nomclient, $adresse, $contact, $commentaire, $mesure)
	{
		$table_name = 't_mailhomeland';

		$data = array(
			'mailhomeland_user' => $user,
			'mailhomeland_date' => $date,
			'mailhomeland_categorie' => $categorie,
			'mailhomeland_nomclient' => $nomclient,
			'mailhomeland_adresse' => $adresse,
			'mailhomeland_contact' => $contact,
			'mailhomeland_mesure' => $mesure,
			'mailhomeland_commentaire' => $commentaire,
		);

		return $this->insertData($table_name, $data);
	}

	public function insertAffectationHomelandData($user, $date, $typeaffectation, $dossier) {
		$table_name = "t_affectationhomeland";
		$data = array(
			'affectationhomeland_user' => $user,
			'affectationhomeland_date' => $date,
			'affectationhomeland_typeaffectation' => $typeaffectation,
			'affectationhomeland_iddossier' => $dossier,
		);
		return $this->insertData($table_name, $data);
	}
	public function insertAutresTachesHomelandData($user, $date, $lien, $motif, $debut, $fin, $dureeMinutes, $nbtraitement) {
		$table_name = "t_autretacheshomeland";
		$data = array(
			'autretacheshomeland_user' => $user,
			'autretacheshomeland_date' => $date,
			'autretacheshomeland_lien' => $lien,
			'autretacheshomeland_motif' => $motif,
			'autretacheshomeland_debut' => $debut,
			'autretacheshomeland_fin' => $fin,
			'autretacheshomeland_duree' => $dureeMinutes,
			'autretacheshomeland_nbtraitement' => $nbtraitement
		);
		return $this->insertData($table_name, $data);
	}
	public function insertComptabiliteHomelandData($user, $date, $categorie, $debut, $fin, $dureeMinutes, $nbtraitement) {
		$table_name = "t_comptahomeland";
		$data = array(
			'comptahomeland_user' => $user,
			'comptahomeland_date' => $date,
			'comptahomeland_categorie' => $categorie,
			'comptahomeland_debut' => $debut,
			'comptahomeland_fin' => $fin,
			'comptahomeland_duree' => $dureeMinutes,
			'comptahomeland_nbtraitement' => $nbtraitement
		);
		return $this->insertData($table_name, $data);
	}
	public function insertJuridiqueHomelandData($user, $date, $categorie, $refdossier, $debut, $fin, $dureeMinutes,$nbtraitement) {
		$table_name = "t_juridiquehomeland";
		$data = array(
			'juridiquehomeland_user' => $user,
			'juridiquehomeland_date' => $date,
			'juridiquehomeland_categorie' => $categorie,
			'juridiquehomeland_refdossier' => $refdossier,
			'juridiquehomeland_debut' => $debut,
			'juridiquehomeland_fin' => $fin,
			'juridiquehomeland_duree' => $dureeMinutes,
			'juridiquehomeland_nbtraitement' => $nbtraitement


		);
		return $this->insertData($table_name, $data);
	}

	public function insertPededHomelandData($user, $date, $categorie, $nomvente, $commentaire, $debut, $fin, $dureeMinutes, $nbtraitement, $nbproduction) {
		$table_name = "t_pededhomeland";
		$data = array(
			'pededhomeland_user' => $user,
			'pededhomeland_date' => $date,
			'pededhomeland_categorie' => $categorie,
			'pededhomeland_nomvente' => $nomvente,
			'pededhomeland_commentaire' => $commentaire,
			'pededhomeland_debut' => $debut,
			'pededhomeland_fin' => $fin,
			'pededhomeland_duree' => $dureeMinutes,
			'pededhomeland_nbtraitement' => $nbtraitement,
			'pededhomeland_nbproduction' => $nbproduction,
		);
		return $this->insertData($table_name, $data);
	}

	public function insertImmaHomelandData($user, $date, $categorie, $debut, $fin, $dureeMinutes, $nbtraitement) {
		$table_name = "t_immahomeland";
		$data = array(
			'immahomeland_user' => $user,
			'immahomeland_date' => $date,
			'immahomeland_categorie' => $categorie,
			'immahomeland_debut' => $debut,
			'immahomeland_fin' => $fin,
			'immahomeland_duree' => $dureeMinutes,
			'immahomeland_nbtraitement' => $nbtraitement
		);
		return $this->insertData($table_name, $data);
	}


	public function insertSinistreHomelandData($user, $date, $debut, $fin, $dureeMinutes, $nbtraitement, $categorie, $numdossier) {
		$table_name = "t_sinistrehomeland";
		$data = array(
			'sinistrehomeland_user' => $user,
			'sinistrehomeland_date' => $date,
			'sinistrehomeland_debut' => $debut,
			'sinistrehomeland_fin' => $fin,
			'sinistrehomeland_duree' => $dureeMinutes,
			'sinistrehomeland_nbtraitement' => $nbtraitement,
			'sinistrehomeland_categorie' => $categorie,
			'sinistrehomeland_numdossier' => $numdossier,
		);
		return $this->insertData($table_name, $data);
	}



	public function insertTechniqueHomelandData($user, $date, $categorie, $adresse, $debut, $fin, $dureeMinutes, $nbtraitement) {
		$table_name = "t_techniquehomeland";
		$data = array(
			'techniquehomeland_user' => $user,
			'techniquehomeland_date' => $date,
			'techniquehomeland_categorie' => $categorie,
			'techniquehomeland_adresse' => $adresse,
			'techniquehomeland_debut' => $debut,
			'techniquehomeland_fin' => $fin,
			'techniquehomeland_duree' => $dureeMinutes,
			'techniquehomeland_nbtraitement' => $nbtraitement
		);
		return $this->insertData($table_name, $data);
	}


	public function insertMajhboHomelandData($user, $date, $type, $lien) {
		$table_name = "t_majhbohomeland";
		$data = array(
			'majhbohomeland_user' => $user,
			'majhbohomeland_date' => $date,
			'majhbohomeland_typemaj' => $type,
			'majhbohomeland_lien' => $lien
		);
		return $this->insertData($table_name, $data);
 	}
	public function insertDispatch($data)
	{
		$table_name = "t_dispatchhomeland";

		$this->db->insert($table_name, $data);
		return $this->db->insert_id();
	}
	public function getPoles()
	{
		return $this->db->get('tr_polehomeland')->result_array();
	}
	public function getAppel()
	{
		return $this->db->get('tr_categorieappel')->result_array();
	}
	public function getMail()
	{
		return $this->db->get('tr_categoriemail')->result_array();
	}
	public function getAffectation()
	{
		return $this->db->get('tr_affectation')->result_array();
	}
	public function getSinistre()
	{
		return $this->db->get('tr_categoriesinistre')->result_array();
	}
	public function getCompta()
	{
		return $this->db->get('tr_categoriecompta')->result_array();
	}
	public function getJuridique()
	{
		return $this->db->get('tr_categoriejuridique')->result_array();
	}
	public function getPeded()
	{
		return $this->db->get('tr_categoriepeded')->result_array();
	}
	public function getImma()
	{
		return $this->db->get('tr_categorieimma')->result_array();
	}
	public function getTechnique()
	{
		return $this->db->get('tr_categorietechnique')->result_array();
	}
	public function getMesure()
	{
		return $this->db->get('tr_mesureprise')->result_array();
	}




}
