<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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
	

	public function index()
	{
		$this->login();
	}

	public function login($error = [])
	{

		$this->load->view('auth/login-page', array('error' => $error));
	}

	public function verify()
	{
		
		$username = $this->input->post('tt_username');
		$pwd = $this->input->post('tt_pwd');
		
		if(empty($username) || empty($pwd))
		{
			$error = [
				'msg' => 'Login/mot de passe incorrecte'
			];
			redirect('auth/login');
		}
		
		$this->load->model('user_model');
		$this->load->model('campagne_model');
		$this->load->model('service_model');
		$this->user_model->setLoginCredentials($username, $pwd);
		$infosUser = $this->user_model->verifyLogin();
		
		if(!$infosUser)
		{
			$error = [
				'msg' => 'Login/mot de passe incorrecte'
			];
			redirect('auth/login');
		}
		//Récupération des campagnes et services de l'utilisateur
		$infosUser->list_campagne = $this->campagne_model->getUserCampagne($infosUser->usr_id);

		$infosUser->list_service = $this->service_model->getUserService($infosUser->usr_id);
				
		$this->load->model('shift_model');
		//Enregistrement en session des données de l'utilisateur
		$this->setToSessionUserInfos($infosUser);

		$this->_saveUserIP($infosUser);

		//var_dump($this->session->userdata('user')); die;
		if($this->session->userdata('user')){
			//Go to authenticated pages (dashboard)
			redirect('dashboard/index');
		}
		
	}

	/**
	 * Deconnexion de l'utilisateur courant
	 * Suppression session 
	 * Redirection to auth/login
	 */
	public function doLogout()
	{
		session_destroy();
		//S'assurer que la session a bien été détruite par redirection vers une page sécurisé
		redirect('dashboard/index');
	}

	public function externalAuth(){
		//var_dump($this->input->post());die;
		$post = $this->input->post();
		if($post){
			$username = isset($post['username']) ? $post['username'] : false;
			$pwd = isset($post['pwd']) ? $post['pwd'] : false;
			if(!$username || !$pwd){
				$error = [
					'err' => true,
					'msg' => 'Login/Mot de passe vide'
				];
				echo json_encode($error);
				die();
			}
			$this->load->model('user_model');
			$this->user_model->setLoginCredentials($username, $pwd);
			$infosUser = $this->user_model->verifyLogin();
			if(!$infosUser){

				$error = [
					'err' => true,
					'msg' => 'Login/mot de passe incorrecte'
				];
				echo json_encode($error);
			}
			$success = [
				'err' => false,
				'msg' => 'connexion reussi',
				'datas' => $infosUser
			];
			echo json_encode($success); die();
		}
	}

	//PRIVATE FUNCTIONS

	private function setToSessionUserInfos($datas)
	{
		$user = [
			'id' => $datas->usr_id,
			'nom' => $datas->usr_nom,
			'prenom' => $datas->usr_prenom,
			'username' => $datas->usr_username,
			'matricule' => $datas->usr_matricule,
			'initiale' => $datas->usr_initiale,
			'email' => $datas->usr_email,
			'role' => $datas->usr_role,
			'poids' => $datas->role_poids,
			'isactif' => $datas->usr_actif,
			'istech' => $datas->usersuppl_istech,
			'istech' => $datas->usersuppl_istech,
			'iscadreIT' => $datas->usersuppl_iscadreIT,
			'issecurite' => $datas->usersuppl_issecurite,
			'istransport' => $datas->usersuppl_istransport,
			'isadmin' => $datas->usersuppl_isadmin,
			'usersuppl_issupportadmin' => $datas->usersuppl_issupportadmin,
			'usersuppl_isRespTransport' => $datas->usersuppl_isRespTransport,
			'listcampagne' => $datas->list_campagne,
			'listservice' => $datas->list_service
		];

		$this->session->set_userdata('user', $user);
	}

	private function _saveUserIP($userInfos)
	{
		$userIP = $this->input->ip_address();
		$userID = $userInfos->usr_id;
		$dateLog = date('Y-m-d');
		$data = [
			'ip' => $userIP,
			'user' => $userID,
			'dateLog' => $dateLog

		];

		if(!$this->input->valid_ip($userIP)) {
			//IP non valide ,  Qu'est ce qu'il faut faire ???
		}else{
			// IP Valide , on enregistre les informations dans la table d'IPs
			$this->load->model('ip_model');
			$this->ip_model->insertIp($data);

		}
	}



	
} 
