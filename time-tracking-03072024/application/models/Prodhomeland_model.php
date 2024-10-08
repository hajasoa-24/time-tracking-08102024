<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prodhomeland_model extends CI_Model {



	//PRIVATE FUNCTIONS

	//PUBLIC FUNCTIONS

	public function __construct()
    {
        parent::__construct();
    }

	public function getReportHomeland($du, $au, $userId, $userRole)
	{
		$datas = [];
		$datas['appel'] = $this->getAppelHomeland($du, $au, $userId, $userRole);
		$datas['mail'] = $this->getMailHomeland($du, $au, $userId, $userRole);
		$datas['affectation'] = $this->getAffectationHomeland($du, $au, $userId, $userRole);
		$datas['autrestaches'] = $this->getAutretachesHomeland($du, $au, $userId, $userRole);
		$datas['comptabilite'] = $this->getComptaHomeland($du, $au, $userId, $userRole);
		$datas['juridique'] = $this->getJuridiqueHomeland($du, $au, $userId, $userRole);
		$datas['peded'] = $this->getPededHomeland($du, $au, $userId, $userRole);
		$datas['immatriculation'] = $this->getImmaHomeland($du, $au, $userId, $userRole);
		$datas['sinistre'] = $this->getSinistreHomeland($du, $au, $userId, $userRole);
		$datas['technique'] = $this->getTechniqueHomeland($du, $au, $userId, $userRole);
		$datas['majhbo'] = $this->getMajhboHomeland($du, $au, $userId, $userRole);
		$datas['dispatch'] = $this->getDispatchHomeland($du, $au, $userId, $userRole);
		$datas['parametrage'] = $this->getParametrageHomeland($du, $au, $userId, $userRole);

		return $datas;
	}


	public function getAppelHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_appelhomeland.appelhomeland_id, t_appelhomeland.appelhomeland_user, t_appelhomeland.appelhomeland_date, t_appelhomeland.appelhomeland_categorie, t_appelhomeland.appelhomeland_nomclient, t_appelhomeland.appelhomeland_adresse, t_appelhomeland.appelhomeland_contact,t_appelhomeland.appelhomeland_commentaire, t_appelhomeland.appelhomeland_mesure, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categorieappel.categorieappel_libelle, tr_mesureprise.mesureprise_libelle, tr_user.usr_username');
		$this->db->from('t_appelhomeland');
		$this->db->join('tr_user', 't_appelhomeland.appelhomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_mesureprise', 't_appelhomeland.appelhomeland_mesure = tr_mesureprise.mesureprise_id', 'left');
		$this->db->join('tr_categorieappel', 't_appelhomeland.appelhomeland_categorie = tr_categorieappel.categorieappel_id', 'left');
		$this->db->where("DATE_FORMAT(t_appelhomeland.appelhomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");
		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_appelhomeland.appelhomeland_user', $userId);
		} else {
			$this->db->where('t_appelhomeland.appelhomeland_user IS NOT NULL');
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			$result = $query->result();

			return $result;
		}
		return false;
	}

	public function getMailHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_mailhomeland.mailhomeland_id, t_mailhomeland.mailhomeland_user, t_mailhomeland.mailhomeland_date, t_mailhomeland.mailhomeland_categorie, t_mailhomeland.mailhomeland_nomclient, t_mailhomeland.mailhomeland_adresse, t_mailhomeland.mailhomeland_contact, t_mailhomeland.mailhomeland_commentaire, t_mailhomeland.mailhomeland_mesure, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categoriemail.categoriemail_libelle, tr_mesureprise.mesureprise_libelle, tr_user.usr_username');
		$this->db->from('t_mailhomeland');
		$this->db->join('tr_user', 't_mailhomeland.mailhomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_mesureprise', 't_mailhomeland.mailhomeland_mesure = tr_mesureprise.mesureprise_id', 'left');
		$this->db->join('tr_categoriemail', 't_mailhomeland.mailhomeland_categorie = tr_categoriemail.categoriemail_id', 'left');
		$this->db->where("DATE_FORMAT(t_mailhomeland.mailhomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");
		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_mailhomeland.mailhomeland_user', $userId);
		} else {
			$this->db->where('t_mailhomeland.mailhomeland_user IS NOT NULL');
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getAffectationHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_affectationhomeland.affectationhomeland_id, t_affectationhomeland.affectationhomeland_user, t_affectationhomeland.affectationhomeland_date, t_affectationhomeland.affectationhomeland_typeaffectation, t_affectationhomeland.affectationhomeland_iddossier, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_affectation.affectation_id, tr_affectation.affectation_libelle, tr_affectation.affectation_actif, tr_user.usr_username');
		$this->db->from('t_affectationhomeland');
		$this->db->join('tr_user', 't_affectationhomeland.affectationhomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_affectation', 't_affectationhomeland.affectationhomeland_typeaffectation = tr_affectation.affectation_id', 'left');
		$this->db->where("DATE_FORMAT(t_affectationhomeland.affectationhomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");
		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_affectationhomeland.affectationhomeland_user', $userId);
		} else {
			$this->db->where('t_affectationhomeland.affectationhomeland_user IS NOT NULL');
		}
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}


	public function getAutretachesHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_autretacheshomeland.autretacheshomeland_id, t_autretacheshomeland.autretacheshomeland_user, t_autretacheshomeland.autretacheshomeland_date, t_autretacheshomeland.autretacheshomeland_lien, t_autretacheshomeland.autretacheshomeland_motif, t_autretacheshomeland.autretacheshomeland_duree, t_autretacheshomeland.autretacheshomeland_nbtraitement, t_autretacheshomeland.autretacheshomeland_debut, t_autretacheshomeland.autretacheshomeland_fin, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_user.usr_username');
		$this->db->from('t_autretacheshomeland');
		$this->db->join('tr_user', 't_autretacheshomeland.autretacheshomeland_user = tr_user.usr_id', 'left');
		$this->db->where("DATE_FORMAT(t_autretacheshomeland.autretacheshomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_autretacheshomeland.autretacheshomeland_user', $userId);
		} else {
			$this->db->where('t_autretacheshomeland.autretacheshomeland_user IS NOT NULL');
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getComptaHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_comptahomeland.comptahomeland_id, t_comptahomeland.comptahomeland_debut, t_comptahomeland.comptahomeland_fin, t_comptahomeland.comptahomeland_user, t_comptahomeland.comptahomeland_date, t_comptahomeland.comptahomeland_categorie, t_comptahomeland.comptahomeland_nbtraitement, t_comptahomeland.comptahomeland_duree, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categoriecompta.categoriecompta_id, tr_categoriecompta.categoriecompta_libelle, tr_categoriecompta.categoriecompta_actif, tr_user.usr_username');
		$this->db->from('t_comptahomeland');
		$this->db->join('tr_user', 't_comptahomeland.comptahomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_categoriecompta', 't_comptahomeland.comptahomeland_categorie = tr_categoriecompta.categoriecompta_id', 'left');
		$this->db->where("DATE_FORMAT(t_comptahomeland.comptahomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_comptahomeland.comptahomeland_user', $userId);
		} else {
			$this->db->where('t_comptahomeland.comptahomeland_user IS NOT NULL');
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}



	public function getJuridiqueHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_juridiquehomeland.juridiquehomeland_id, t_juridiquehomeland.juridiquehomeland_nbtraitement,t_juridiquehomeland.juridiquehomeland_user,t_juridiquehomeland.juridiquehomeland_debut, t_juridiquehomeland.juridiquehomeland_duree, t_juridiquehomeland.juridiquehomeland_fin, t_juridiquehomeland.juridiquehomeland_date, t_juridiquehomeland.juridiquehomeland_categorie, t_juridiquehomeland.juridiquehomeland_refdossier, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categoriejuridique.categoriejuridique_id, tr_categoriejuridique.categoriejuridique_libelle, tr_categoriejuridique.categoriejuridique_actif, tr_user.usr_username');
		$this->db->from('t_juridiquehomeland');
		$this->db->join('tr_user', 't_juridiquehomeland.juridiquehomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_categoriejuridique', 't_juridiquehomeland.juridiquehomeland_categorie = tr_categoriejuridique.categoriejuridique_id', 'left');
		$this->db->where("DATE_FORMAT(t_juridiquehomeland.juridiquehomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_juridiquehomeland.juridiquehomeland_user', $userId);
		} else {
			$this->db->where('t_juridiquehomeland.juridiquehomeland_user IS NOT NULL');
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getPededHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_pededhomeland.pededhomeland_id, t_pededhomeland.pededhomeland_nbtraitement, t_pededhomeland.pededhomeland_nbproduction,t_pededhomeland.pededhomeland_debut, t_pededhomeland.pededhomeland_fin, t_pededhomeland.pededhomeland_duree,t_pededhomeland.pededhomeland_user, t_pededhomeland.pededhomeland_date, t_pededhomeland.pededhomeland_categorie, t_pededhomeland.pededhomeland_nomvente, t_pededhomeland.pededhomeland_commentaire, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categoriepeded.categoriepeded_id, tr_categoriepeded.categoriepeded_libelle, tr_categoriepeded.categoriepeded_actif, tr_user.usr_username');
		$this->db->from('t_pededhomeland');
		$this->db->join('tr_user', 't_pededhomeland.pededhomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_categoriepeded', 't_pededhomeland.pededhomeland_categorie = tr_categoriepeded.categoriepeded_id', 'left');
		$this->db->where("DATE_FORMAT(t_pededhomeland.pededhomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_pededhomeland.pededhomeland_user', $userId);
		} else {
			$this->db->where('t_pededhomeland.pededhomeland_user IS NOT NULL');
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getImmaHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_immahomeland.immahomeland_id, t_immahomeland.immahomeland_debut, t_immahomeland.immahomeland_fin,t_immahomeland.immahomeland_user, t_immahomeland.immahomeland_date, t_immahomeland.immahomeland_categorie, t_immahomeland.immahomeland_duree, t_immahomeland.immahomeland_nbtraitement, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categorieimma.categorieimma_id, tr_categorieimma.categorieimma_libelle, tr_categorieimma.categorieimma_actif, tr_user.usr_username');
		$this->db->from('t_immahomeland');
		$this->db->join('tr_user', 't_immahomeland.immahomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_categorieimma', 't_immahomeland.immahomeland_categorie = tr_categorieimma.categorieimma_id', 'left');
		$this->db->where("DATE_FORMAT(t_immahomeland.immahomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_immahomeland.immahomeland_user', $userId);
		} else {
			$this->db->where('t_immahomeland.immahomeland_user IS NOT NULL');
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getSinistreHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_sinistrehomeland.sinistrehomeland_id, t_sinistrehomeland.sinistrehomeland_debut, t_sinistrehomeland.sinistrehomeland_fin, t_sinistrehomeland.sinistrehomeland_duree, t_sinistrehomeland.sinistrehomeland_nbtraitement,t_sinistrehomeland.sinistrehomeland_user, t_sinistrehomeland.sinistrehomeland_date, t_sinistrehomeland.sinistrehomeland_categorie, t_sinistrehomeland.sinistrehomeland_numdossier, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categoriesinistre.categoriesinistre_id, tr_categoriesinistre.categoriesinistre_libelle, tr_categoriesinistre.categoriesinistre_actif, tr_user.usr_username');
		$this->db->from('t_sinistrehomeland');
		$this->db->join('tr_user', 't_sinistrehomeland.sinistrehomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_categoriesinistre', 't_sinistrehomeland.sinistrehomeland_categorie = tr_categoriesinistre.categoriesinistre_id', 'left');
		$this->db->where("DATE_FORMAT(t_sinistrehomeland.sinistrehomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_sinistrehomeland.sinistrehomeland_user', $userId);
		} else {
			$this->db->where('t_sinistrehomeland.sinistrehomeland_user IS NOT NULL');
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getTechniqueHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_techniquehomeland.techniquehomeland_id, t_techniquehomeland.techniquehomeland_debut, t_techniquehomeland.techniquehomeland_fin, t_techniquehomeland.techniquehomeland_duree, t_techniquehomeland.techniquehomeland_nbtraitement,t_techniquehomeland.techniquehomeland_user, t_techniquehomeland.techniquehomeland_date, t_techniquehomeland.techniquehomeland_categorie, t_techniquehomeland.techniquehomeland_adresse, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categorietechnique.categorietechnique_id, tr_categorietechnique.categorietechnique_libelle, tr_categorietechnique.categorietechnique_actif, tr_user.usr_username');
		$this->db->from('t_techniquehomeland');
		$this->db->join('tr_user', 't_techniquehomeland.techniquehomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_categorietechnique', 't_techniquehomeland.techniquehomeland_categorie = tr_categorietechnique.categorietechnique_id', 'left');
		$this->db->where("DATE_FORMAT(t_techniquehomeland.techniquehomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_techniquehomeland.techniquehomeland_user', $userId);
		} else {
			$this->db->where('t_techniquehomeland.techniquehomeland_user IS NOT NULL');
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getMajhboHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_majhbohomeland.majhbohomeland_id, t_majhbohomeland.majhbohomeland_lien,t_majhbohomeland.majhbohomeland_user, t_majhbohomeland.majhbohomeland_date, t_majhbohomeland.majhbohomeland_typemaj, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_user.usr_username');
		$this->db->from('t_majhbohomeland');
		$this->db->join('tr_user', 't_majhbohomeland.majhbohomeland_user = tr_user.usr_id', 'left');
		$this->db->where("DATE_FORMAT(t_majhbohomeland.majhbohomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_majhbohomeland.majhbohomeland_user', $userId);
		} else {
			$this->db->where('t_majhbohomeland.majhbohomeland_user IS NOT NULL');
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getDispatchHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_dispatchhomeland.dispatchhomeland_id, t_dispatchhomeland.dispatchhomeland_user,t_dispatchhomeland.dispatchhomeland_lien,t_dispatchhomeland.dispatchhomeland_date, t_dispatchhomeland.dispatchhomeland_debut, t_dispatchhomeland.dispatchhomeland_fin, t_dispatchhomeland.dispatchhomeland_duree, t_dispatchhomeland.dispatchhomeland_nbtraitement, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_user.usr_username');
		$this->db->from('t_dispatchhomeland');
		$this->db->join('tr_user', 't_dispatchhomeland.dispatchhomeland_user = tr_user.usr_id', 'left');
		$this->db->where("DATE_FORMAT(t_dispatchhomeland.dispatchhomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_dispatchhomeland.dispatchhomeland_user', $userId);
		} else {
			$this->db->where('t_dispatchhomeland.dispatchhomeland_user IS NOT NULL');
		}
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function getParametrageHomeland($du, $au, $userId, $userRole)
	{
		$this->db->select('t_parametragehomeland.parametragehomeland_id, t_parametragehomeland.parametragehomeland_debut, t_parametragehomeland.parametragehomeland_fin, t_parametragehomeland.parametragehomeland_duree, t_parametragehomeland.parametragehomeland_nbtraitement,t_parametragehomeland.parametragehomeland_user, t_parametragehomeland.parametragehomeland_date, t_parametragehomeland.parametragehomeland_categorie, t_parametragehomeland.parametragehomeland_lien, tr_user.usr_id, tr_user.usr_nom, tr_user.usr_prenom, tr_categorieparametrage.categorieparametrage_id, tr_categorieparametrage.categorieparametrage_libelle, tr_categorieparametrage.categorieparametrage_actif, tr_user.usr_username, t_parametragehomeland.parametragehomeland_adresse');
		$this->db->from('t_parametragehomeland');
		$this->db->join('tr_user', 't_parametragehomeland.parametragehomeland_user = tr_user.usr_id', 'left');
		$this->db->join('tr_categorieparametrage', 't_parametragehomeland.parametragehomeland_categorie = tr_categorieparametrage.categorieparametrage_id', 'left');
		$this->db->where("DATE_FORMAT(t_parametragehomeland.parametragehomeland_date, '%Y-%m-%d') BETWEEN '$du' AND '$au'");

		if ($userRole == ROLE_AGENT) {
			$this->db->where('t_parametragehomeland.parametragehomeland_user', $userId);
		} else {
			$this->db->where('t_parametragehomeland.parametragehomeland_user IS NOT NULL');
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
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

	public function insertParametrageHomelandData($user, $date, $categorie, $lien, $debut, $fin, $dureeMinutes, $nbtraitement,$adresse) {
		$table_name = "t_parametragehomeland";
		$data = array(
			'parametragehomeland_user' => $user,
			'parametragehomeland_date' => $date,
			'parametragehomeland_categorie' => $categorie,
			'parametragehomeland_lien' => $lien,
			'parametragehomeland_debut' => $debut,
			'parametragehomeland_fin' => $fin,
			'parametragehomeland_duree' => $dureeMinutes,
			'parametragehomeland_nbtraitement' => $nbtraitement,
			'parametragehomeland_adresse' => $adresse
		);
		return $this->insertData($table_name, $data);
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
	public function getParametrage()
	{
		return $this->db->get('tr_categorieparametrage')->result_array();
	}



}

