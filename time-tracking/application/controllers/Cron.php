<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once( APPPATH.'libraries/Dayprime_lib.php' );
require_once( APPPATH.'libraries/Prime.php' );

class Cron extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function purgeIp()
	{
		$this->load->model('ip_model');
		$deleteIp = $this->ip_model->deleteIp();
	}

	/**
	 * Fonction permettant d'initialiser la table t_presence pour tout les users disponibles et actifs
	 */

	public function initPresence()
	{
		$isSetDayPresence = $this->_isSetPresenceDay();	
		if(!$isSetDayPresence){
			$this->load->model('presence_model');
			$this->presence_model->initPresenceDay();
		}
	 }

	private function _isSetPresenceDay()
	{
		$this->load->model('presence_model');
		$isDayPresenceSet = $this->presence_model->checkPresenceDay();
		return $isDayPresenceSet;
	}

	public function updatePresenceDatas($date = false)
	{
		if(!$date){
			$date = date('Y-m-d');
		}
		$this->load->model('presence_model');
		$update = $this->presence_model->updatePresence($date);
		$updatemotif = $this->presence_model->updatePresenceMotif($date);
	}


	public function addSoldeCongeMensuel(){

		$debutCron = date('Y-m-d H:i:s');
		echo "CRON attribution conge - BEGINS <br/> " . 
			" Date de début de traitement : " . $debutCron ;

		$this->load->model('user_model');
		$this->load->model('conges_model');
		$errorNum = 0;
		$traite = 0;
		$dejaTraite = 0;
		//Recuperation des salariés actifs
		$employees = $this->user_model->getAllUser();
		foreach($employees as $employee){
			$traite +=1;
			$dateEmbauche = $employee->usr_dateembauche;
			$user = $employee->usr_id;
			$currentMonth = date('n');
			$currentYear = date('Y');
			$isSetSolde = $this->conges_model->isSetSolde($user, $currentMonth, $currentYear);
			if(!$isSetSolde){
				$result = $this->_addSoldeEmployee($user, $dateEmbauche, $currentMonth, $currentYear);
				if(!$result){
					//TODO Loguer les erreurs quelques part, pour le moment l'envoyer dans le log
					log_message('error', 'CRON -- ATTRIBUTION CONGE ERROR ==> ' . 'Mois: ' . $currentMonth . ' Annee:' . $currentYear . ' UserID: ' . $user );
					$errorNum += 1;
				}
			}else{
				$dejaTraite += 1;
			}
		}
		$finCron = date('Y-m-d H:i:s');
		echo "CRON attribution conge - END <br/> " . 
				 " Date de fin de traitement: " . $finCron . "<br/>" .
				"Traite: " . $traite . " <br/> En erreur: " . $errorNum . '<br/>' . 'Déja traité : ' . $dejaTraite;
		
	}

	private function _addSoldeEmployee($user, $dateEmbauche, $month, $year){

		$this->load->model('user_model');
		$this->load->model('conges_model');

		//On va ouvrir une trasaction pour chaque traitement pour un employee donné
		$this->conges_model->startTransaction();

		$daysToAdd = JOUR_CONGE_MENSUEL;
		$startDay = date('d', strtotime($dateEmbauche));
		$startMonth = date('n', strtotime($dateEmbauche));
		$startYear = date('Y', strtotime($dateEmbauche));
		//var_dump($startMonth , $month, $startYear , $year); die;
		if($startMonth == $month && $startYear == $year){
			$currentDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month,$year);
			$daysToAdd = ceil(( ($currentDaysInMonth - ($startDay - 1)) * JOUR_CONGE_MENSUEL) / $currentDaysInMonth);
		}
		$result = $this->user_model->addValueToSoldeConge($user, $daysToAdd);
		//var_dump($result);

		if($result) {
			$dataAtributionConge = [
				'attribconge_user' => $user,
				'attribconge_month' => $month,
				'attribconge_year' => $year
			];
			$result = $this->conges_model->insertAttributionConge($dataAtributionConge);
		}

		$result = $this->conges_model->endTransaction();

		return $result;
		
	}

	public function traiterDecompteCongePermission(){

		$debutCron = date('Y-m-d H:i:s');
		echo "CRON decompte conge - BEGINS <br/> " . 
			" Date de début de traitement : " . $debutCron ;

		$currentDate = date('Y-m-d');
		$this->load->model('conges_model');
		$etatATraiter = [VALIDE];
		$congesATraiter = $this->conges_model->getCongesByEtat($etatATraiter);
		//On boucle sur les conges a traiter
		if(is_array($congesATraiter)){

			foreach($congesATraiter as $conge){
				$congeDebut = date('Y-m-d', strtotime($conge->conge_datedebut));
				$user = $conge->conge_user;
				$congeDuree = $conge->conge_duree;
				$congeID = $conge->conge_id;
				$toEtat = CONGE_ENCOURS;
				$type = $conge->conge_type;
				if($congeDebut == $currentDate){
					//Decompte du congé et mise à jour Etat VALIDE => EN COURS
					$this->conges_model->startTransaction();
					if($type == TYPECONGE_CONGE){
						$solde = $this->conges_model->getSoldeConge($user);
						$newSolde = $solde - $congeDuree;
						$result = $this->conges_model->setSoldeConge($user, $newSolde);
					}else if($type == TYPECONGE_PERMISSION){
						$droitPermission = $this->conges_model->getDroitPermission($user);
						$newDroitPermission = $droitPermission - $congeDuree;
						$result = $this->conges_model->setDroitPermission($user, $newDroitPermission);
					}
					
					if($result){
						$this->conges_model->validateConge($congeID, $toEtat);
					}
					if($result){
						$dataHisto = [
							'histoetatconge_conge' => $congeID,
							'histoetatconge_etat' => $toEtat,
							'histoetatconge_date' => date('Y-m-d H:i:s')
						];
						$this->load->model('histoetatconge_model');
						$this->histoetatconge_model->insertHistorique($dataHisto);
					}
					$this->conges_model->endTransaction();
				}
			}
		}
		$finCron = date('Y-m-d H:i:s');
		echo "CRON decompte conge - END <br/> " . 
				 " Date de fin de traitement: " . $finCron . "<br/>";

	}

	public function traiterFinCongePermission(){

		$debutCron = date('Y-m-d H:i:s');
		echo "CRON FIN conge - BEGINS <br/> " . 
			" Date de début de traitement : " . $debutCron ;

		$currentDate = date('Y-m-d');
		$this->load->model('conges_model');
		$etatATraiter = [CONGE_ENCOURS];
		$congesATraiter = $this->conges_model->getCongesByEtat($etatATraiter);
		//On boucle sur les conges a traiter
		if(is_array($congesATraiter)){

			foreach($congesATraiter as $conge){
				//$congeDebut = date('Y-m-d', strtotime($conge->conge_datedebut));
				$congeRetour = date('Y-m-d', strtotime($conge->conge_dateretour));
				//$user = $conge->conge_user;
				//$congeDuree = $conge->conge_duree;
				$congeID = $conge->conge_id;
				$toEtat = CONGE_TERMINE;
				//$type = $conge->conge_type;
				if($congeRetour <= $currentDate){
					//mise à jour Etat EN COURS => TERMINE
					$this->conges_model->startTransaction();
					$result = $this->conges_model->validateConge($congeID, $toEtat);
					
					if($result){
						$dataHisto = [
							'histoetatconge_conge' => $congeID,
							'histoetatconge_etat' => $toEtat,
							'histoetatconge_date' => date('Y-m-d H:i:s')
						];
						$this->load->model('histoetatconge_model');
						$this->histoetatconge_model->insertHistorique($dataHisto);
					}
					$this->conges_model->endTransaction();
				}
			}
		}
		$finCron = date('Y-m-d H:i:s');
		echo "CRON FIN conge - END <br/> " . 
				 " Date de fin de traitement: " . $finCron . "<br/>";


	}


	public function traiterDebutCongePermission(){

		$debutCron = date('Y-m-d H:i:s');
		echo "CRON decompte conge - BEGINS <br/> " . 
			" Date de début de traitement : " . $debutCron ;

		$currentDate = date('Y-m-d');
		$this->load->model('conges_model');
		$etatATraiter = [VALIDE];
		$congesATraiter = $this->conges_model->getCongesByEtat($etatATraiter);
		//On boucle sur les conges a traiter
		if(is_array($congesATraiter)){

			foreach($congesATraiter as $conge){
				$congeDebut = date('Y-m-d', strtotime($conge->conge_datedebut));

				$congeID = $conge->conge_id;
				$toEtat = CONGE_ENCOURS;
				if($congeDebut <= $currentDate){
					//Decompte du congé et mise à jour Etat VALIDE => EN COURS
					$this->conges_model->startTransaction();
					$result = $this->conges_model->validateConge($congeID, $toEtat);
					
					if($result){
						$dataHisto = [
							'histoetatconge_conge' => $congeID,
							'histoetatconge_etat' => $toEtat,
							'histoetatconge_date' => date('Y-m-d H:i:s')
						];
						$this->load->model('histoetatconge_model');
						$this->histoetatconge_model->insertHistorique($dataHisto);
					}
					$this->conges_model->endTransaction();
				}
			}
		}
		$finCron = date('Y-m-d H:i:s');
		echo "CRON decompte conge - END <br/> " . 
				 " Date de fin de traitement: " . $finCron . "<br/>";

	}


	public function updatePointageVeille($cDate = false, $site = SITE_SETEX)
	{
		$cDate = date('Y-m-d', strtotime('-1 days'));
		$this->updatePointage($cDate, $site);
	}


	/**
	 * Boucler sur les utilisateurs actifs.
	 * Pour chaque utilisateur, récupérer les données ingress et créer une nouvelle ligne pour la date du jour (selectionnée) 
	 * ou mettre à jour la ligne déjà présente
	 * $site par defaut à SITE_SETEX
	 */
	public function updatePointage($cDate = false, $site = SITE_SETEX){
		
		$debutCron = date('Y-m-d H:i:s');
		echo "CRON Mise à jour pointage - BEGINS <br/> " . 
			" Date de début de traitement : " . $debutCron ;

		if(!in_array($site, [SITE_SETEX, SITE_MCR, SITE_TNL])){
			echo "Veuillez specifier un site valide! <br/>" .
					"Arrêt du CRON";
			exit();
		}

		if(!$cDate)
			$cDate = date('Y-m-d');

		$this->load->model('user_model');
		$this->load->model('pointage_model');
		$listOk = 0;
		$listNotOk = 0;
		//On boucle sur les utilisateurs actifs
		$listUser = $this->user_model->getAllUser(true);

		if($listUser !== false ){
			$this->load->model('ingress_model');
			$this->load->model('ingressmcr_model');
			$this->load->model('ingresstnl_model');

			foreach($listUser as $user){
				$userId = $user->usr_id;
				$userIngress = $user->usr_ingress;
				$userSite = $user->usr_site;
	
				$ingressModel = false;
				if($userSite == SITE_SETEX){
					$ingressModel = new Ingress_model();
				}else if($userSite == SITE_MCR){
					$ingressModel = new Ingressmcr_model();
				}else if($userSite == SITE_TNL){
					$ingressModel = new Ingresstnl_model();
				}
	
				if($ingressModel !== false){

					$pointage = $ingressModel->getUserPointage($userIngress, $cDate);
					//echo $this->db->last_query(); die;
					$res = false;
					if($userId == '629'){
						//var_dump($user, $pointage);
					}
					if(is_array($pointage) && isset($pointage[0])){
						$pointage = $pointage[0];
						$data = [
							'pointage_user' => $userId,
							'pointage_date' => $cDate,
							'pointage_site' => $userSite,
							'pointage_ingressid' => $userIngress,
							'pointage_in' => $pointage->att_in,
							'pointage_break' =>$pointage->att_break,
							'pointage_resume' => $pointage->att_resume,
							'pointage_out' => $pointage->att_out,
							'pointage_ot' => $pointage->att_ot,
							'pointage_done' => $pointage->att_done,
							'pointage_workhour' => floatval($pointage->workhour)
						];
						//Récupérer l'enregistrement si disponible
						$cPointage = $this->pointage_model->getUserPointageByDate($userId, $cDate, $userSite);
						if($cPointage !== false){
		
							$data['pointage_datemodif'] = date('Y-m-d H:i:s');
							$res = $this->pointage_model->updatePointage($cPointage->pointage_id, $data);
			
						}else{
							
							$data['pointage_datecrea'] = date('Y-m-d H:i:s');
							$data['pointage_datemodif'] = date('Y-m-d H:i:s');
							$res = $this->pointage_model->insertPointage($data);
						}
						if($res){
							$listOk += 1;
						}else{
							$listNotOk += 1;
						}
					}
				}else{
					$listNotOk += 1;
				}
			}
		}
		
		$finCron = date('Y-m-d H:i:s');
		echo "CRON mise à jour pointage - END <br/> " . 
				 " Date de fin de traitement: " . $finCron . "<br/>" .
				 "Traité et Ok : " . $listOk . "<br/>" .
				 "En Erreur : " . $listNotOk . "<br/>";
	}

	public function assign()
	{
		$this->load->model('Tache_model');

		$this->Tache_model->assignTasksToUsers();
		echo 'Tâches attribuées avec succès aux utilisateurs.';
	}

	public function assigndailytask()
	{
		$this->load->model('Tache_model');

		$this->Tache_model->assignDailyTask();
		echo 'Tâches attribuées avec succès aux utilisateurs.';
	}



	public function updateRetardDatas($day = false)
	{
		echo 'Début de mise à jour des retards <br/>';
		$this->output->enable_profiler(TRUE);

		$inserted = 0;
		$updated = 0;

		$this->load->model('retard_model');
		if(!$day) $day = date('Y-m-d');

		$datas = $this->retard_model->getRetardDatas($day);
		if(!$datas){
			echo 'Aucun retard trouvé pour  ' . $day . '<br/>';
			return false;
		} 
		$total = count($datas);
		foreach($datas as $data){
			//Voir si déjà présent en BD si Oui on update sinon on insert
			$retardData = $this->retard_model->getUserRetardByDay($day, $data->usr_id);

			$entry = [
				'retard_user' => $data->usr_id,
				'retard_day' => $data->jour,
				'retard_planningentree' => $data->planning_entree,
				'retard_pointagein' => $data->pointage_in,
				'retard_shiftloggedin' => $data->shift_loggedin,
				'retard_shiftbegin' => $data->shift_begin,
				'retard_duree' => $data->duree,
				'retard_datecrea' => date('Y-m-d H:i:s'),
				'retard_datemodif' => date('Y-m-d H:i:s')
			];
			
			if($retardData === false){
				//Pas de données, on insert
				if($this->retard_model->insertUserRetardDayData($entry)){
					$inserted++;
				}

			}else{
				//On update
				if($this->retard_model->updateUserRetardDayData($entry, $retardData->retard_id)){
					$updated++;
				}
			}
		}
		echo 'Récap : <br/> -Total : ' . $total . '<br/> -Insert : ' . $inserted . '<br/> -Updated : ' . $updated . '<br/>';
	}
	
	public function calculatePrimeDay($campagneLib = false)
	{
		$currentDay = new DateTime('yesterday');

		$this->load->model('campagne_model');
		if($campagneLib !== false){
			$listCampagne = $this->campagne_model->getCampagneByLib($campagneLib); 
		}else{
			$listCampagne = $this->campagne_model->getAllCampagne();
		}
		
		$this->_calculateDayPrimeCampagne($listCampagne, $currentDay);
		
		
		
	}

	private function _calculateDayPrimeCampagne(Array $listCampagne, DateTime $day): Void
	{
		
		$params = ['day' => $day];
		$dayPrime = new Dayprime_lib($params);
		foreach($listCampagne as $campagne){

			//$this->load->library('dayprime_lib', $params);
			$dayPrime->calculatePrimeBaseCampagne($campagne);
		}
		
		$prime = $dayPrime->getDayPrime();

		echo '<pre>';
		print_r($prime);
		echo '</pre>';
	}

	/**
	 * Cumul des taux d'atteinte journalières
	 */
	public function calculateAndSavePrimeReachDay(int $dayPrime = 0, int $monthPrime = 0, int $yearPrime = 0) : void
	{
		if($dayPrime === 0) $dayPrime = date('d');
		if($monthPrime === 0) $monthPrime = date('n');
		if($yearPrime === 0) $yearPrime = date('Y');

		$paramsPrime = ['day' => $dayPrime, 'month' => $monthPrime, 'year' => $yearPrime];
		//var_dump($paramsPrime);
		$prime = new Prime($paramsPrime);
		$prime->calculatePrimeReachDay();
	}


	/**
	 * Calcul de la prime mensuelle
	 */
	public function calculateAndSavePrimeMonth(int $dayPrime = 0, int $monthPrime = 0, int $yearPrime = 0, string $campagneLib = '') : void
	{
		if($dayPrime === 0) $dayPrime = date('d');
		if($monthPrime === 0) $monthPrime = date('n');
		if($yearPrime === 0) $yearPrime = date('Y');
		$paramsPrime = ['day' => $dayPrime, 'month' => $monthPrime, 'year' => $yearPrime];

		$prime = new Prime($paramsPrime);
		$prime->calculatePrimeMonth($monthPrime, $yearPrime);
		$prime->savePrime();

		$error = $prime->calculateQualite();
		echo $error . ' Lignes qualité en erreur<br>';

		$prime->calculateBonusMalus();

		$prime->updatePrimeMontantPercu();
		
		echo '<pre>';
		print_r($prime->getPrime());
		//echo $this->db->last_query();
		echo '</pre>';
		
	}

	public function calculateBonusMalus(int $dayPrime = 0, int $monthPrime = 0, int $yearPrime = 0)
	{
		if($dayPrime === 0) $dayPrime = date('d');
		if($monthPrime === 0) $monthPrime = date('n');
		if($yearPrime === 0) $yearPrime = date('Y');
		$paramsPrime = ['day' => $dayPrime, 'month' => $monthPrime, 'year' => $yearPrime];
		echo 'BEGIN calculateBonusMalus : ' . $dayPrime . '/' . $monthPrime . '/' . $yearPrime . '<br>';
		$prime = new Prime($paramsPrime);
		$prime->calculateBonusMalus();
		echo 'END calculateBonusMalus';
	}

	public function calculateQualite(int $dayPrime = 0, int $monthPrime = 0, int $yearPrime = 0)
	{
		if($dayPrime === 0) $dayPrime = date('d');
		if($monthPrime === 0) $monthPrime = date('n');
		if($yearPrime === 0) $yearPrime = date('Y');
		$paramsPrime = ['day' => $dayPrime, 'month' => $monthPrime, 'year' => $yearPrime];

		$prime = new Prime($paramsPrime);
		$error = $prime->calculateQualite();
		echo $error . ' Lignes en erreur<br>';
	}

	

}
