<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    /**
     * Charger la page de profil utilisateur
     */
    public function profil()
    {
        $msg = $this->session->flashdata('msg');
        //chargement des données de l'Utilisateur
        $this->load->model('user_model');
        $infosUser = $this->user_model->getUserInfos($this->_userId);

        $header = ['pageTitle' => 'Update Profil - TimeTracking'];
        $this->load->view('common/header', $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('user/profil', array('user' => $infosUser, 'msg' => $msg));
        $this->load->view('common/footer');

    }

    /*
    * Changer les informations de l'utilisateur
    */

    public function updateProfil()
    {
        $retour = [];
        $this->load->model('user_model');
        if($this->input->post()){
            $nom = $this->input->post('usr_nom');
            $prenom = $this->input->post('usr_prenom');
            $email = $this->input->post('usr_email');
            $arrInfosUser = [
                'usr_nom' => $nom,
                'usr_prenom' => $prenom,
                'usr_email' => $email
            ];

            $arrCond = [
                'usr_id' => $this->_userId
            ];
            
            if($isSaved = $this->user_model->updateUserInfos($arrInfosUser, $arrCond)){
                $retour = [
                    'err' => false,
                    'message' => "Informations de l'utilisateur mis à jour"
                ];
            }else{
                $retour = [
                    'err' => true,
                    'message' => "Erreur survenu lors de la sauvegarde"
                ];
            }
            
        }
        $this->session->set_flashdata('msg', $retour);
        redirect('user/profil');
    }

    /*
    * Changer le mot de passe 
    */

    public function updateUserPassword()
    {
        if($this->input->post()){
            $old_pwd = $this->input->post('usr_oldpwd');
            $new_pwd = $this->input->post('usr_newpwd');
            $confirm_pwd = $this->input->post("usr_pwdconfirm");

            if($new_pwd == $confirm_pwd){
                $this->load->model('user_model');
                //Vérification ancien mot de passe
                if($this->user_model->checkPwd($old_pwd)){
                    //Insertion nouveau mot de passe
                    if($this->user_model->updatePwd($new_pwd, $this->_userId)){
                        $retour = [
                            'err' => false,
                            'message' => 'Mot de passe mis à jour'
                        ];
                    }else{
                        $retour = [
                            'err' => true,
                            'message' => "Erreur de mise à jour du mot de passe"
                        ];
                    }
                    
                }else{
                    $retour = ['err' => true, 'message' => 'Ancien mot de passe érroné'];
                }
                
            }else{
                $retour = ['err' => true, 'message' => "Les mots de passe ne correspondent pas"];
            }
            $this->session->set_flashdata('msg', $retour);
        }

        redirect('user/profil');
    }

    /*
    * affichage tableau liste des utilisateurs 
    */
    public function listUtilisateur()
    {
        $header = ['pageTitle' => 'Gestion des utilisateurs - TimeTracking'];

        $this->load->model('role_model');
        $listRole = $this->role_model->getAllRole();

        $this->load->model('site_model');
        $listSite = $this->site_model->getAllSite();

        $this->load->model('campagne_model');
        $listCampagne = $this->campagne_model->getAllCampagne();

        $this->load->model('service_model');
        $listService = $this->service_model->getAllService();

        $this->load->model('user_model');
        $listUtilisateur = $this->user_model->getAllUser();
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('user/listutilisateur', array(
            'listUtilisateur' => $listUtilisateur));
        $this->load->view('user/modalutilisateur', array(
                'listRole'=> $listRole,
                'listSite'=> $listSite,
                'listCampagne'=> $listCampagne,
                'listService'=> $listService));

        $this->load->view('common/footer', []);
    }

    /**
     * Afficher la liste des utilisateurs
     */

    public function getListUtilisateur()
    {
        $this->load->model('user_model');
        $listUtilisateur = $this->user_model->getAllUser(true);
        if(!$listUtilisateur) $listUtilisateur = [];
        
        echo json_encode(array('data' => $listUtilisateur));
    }
    /**
     * Enregister un new utilisateur
     */
    public function saveNewUtilisateur()
    {
        //$header_data = $this->get_header_info();
        if($this->input->post() && $this->input->post('send') && $this->input->post('send') == 'sent')

        {
            $info_user = $this->input->post();

            //On demarre une transaction 
            $this->db->trans_begin();
            
            //load model
            $this->load->model('user_model');
            $inserted_user = $this->user_model->inserUtilisateur($info_user);

            //inserer les campagnes de l'user
            if($inserted_user && $info_user['user_campagne_add']){
                $this->user_model->insertUserCampagne($inserted_user, $info_user['user_campagne_add']);

            }

            if($inserted_user && $info_user['user_service_add']){
                $this->user_model->insertUserservice($inserted_user, $info_user['user_service_add']);

            }

            //Si tt c'est bien passé, on commit la transaction sinon on fait un rollback
            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                redirect('user/listUtilisateur?msg=error');
            }else{
                $this->db->trans_commit();
                redirect('user/listUtilisateur?msg=success');
            }

        }

        
    }
    /**
    *Prendre les infos d'un utilisateur
    */

    public function getInfoUtilisateur(){

        $user_id = $this->input->post('usr_id');

        if($user_id){
            $this->load->model('user_model');
            $info_user = $this->user_model->getUser($user_id); 

            if($info_user){
                //Prendre la liste des campagnes de l'utilisateur
                $listCampagne = $this->user_model->getUserCampagne($user_id);
                //Prendre la liste des services de l'utilisateur
                $listService = $this->user_model->getUserService($user_id);

                echo json_encode(
                    array(
                        'error' => false, 
                        'info_user' => $info_user, 
                        'listCampagne' => $listCampagne,
                        'listService' => $listService
                    )
                );
            }else
                echo json_encode(array('error' => true));
            die();
        }   

        echo json_encode(array('error' => true));

    }

    /**
     * Enregister les informations modifiés d'un utilisateur
     */
    public function saveEditUtilisateur()
    {
	 if($this->input->post() && $this->input->post('send_edit') && $this->input->post('send_edit') == 'sent')
        {
            $edit_user_data = $this->input->post();

            $this->db->trans_begin();
            //load model
            $this->load->model('user_model');
            $updated_user = $this->user_model->updateUser($edit_user_data);

            if (empty($edit_user_data['user_campagne_edit'])){
                $this->user_model->deleteUserCampagne($edit_user_data['edit_user_id']);
            }

            else{

                $this->user_model->insertUserCampagne($edit_user_data['edit_user_id'], $edit_user_data['user_campagne_edit']);


            }

            if (empty($edit_user_data['user_service_edit'])){
                $this->user_model->deleteUserService($edit_user_data['edit_user_id']);

            }
            else {
                $this->user_model->insertUserservice($edit_user_data['edit_user_id'], $edit_user_data['user_service_edit']);

            }
           

           
           

            if ($this->db->trans_status() === FALSE){
                $this->db->trans_rollback();
                redirect('user/listUtilisateur?msg=error');
            }else{
                $this->db->trans_commit();
                redirect('user/listUtilisateur?msg=succes');
            }

        }

    }
    
    /**
     * importer un ou plusieurs utilisateurs 
     * Définir les usernames et pwd enregistrés 
     */

    public function doImportUtilisateur()
    {
        //$this->load->library('csvimport');
        $this->load->helper('file');

        $path = FCPATH . "uploads/";
 
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'csv';
        //$config['max_size'] = 1024000;
        $this->load->library('upload', $config);

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('user_file_import')) {
            $error = $this->upload->display_errors();

            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('user/listUtilisateur?msg=error');
            //echo $error['error'];
        } else {

            $file_data = $this->upload->data();
            $file_path = base_url() . "uploads/" . $file_data['file_name'];
            $row = 1;
            $insert_data = array();
            if(($handle = fopen($file_path, "r")) !== FALSE){
                while(($data = fgetcsv($handle, 1000, ";")) !== FALSE){
                    $list_campagne = array();
                    $list_service = array();
                    if($row > 1){
                        $identifiant = $data[3] . '-' . $data[2];
                        $pwd = DEFAULT_USER_PWD;
                        $campagne_id = NULL;
                        $service_id = NULL;
                        //Get campagne ID
                        $this->load->model('campagne_model');
                        if($data[6]){
                            $arr_campagne = explode('|',$data[6]);
                            foreach($arr_campagne as $cp){
                                $campagne_info = $this->campagne_model->getCampagneByLib($cp);
                                if($campagne_info) {
                                    $campagne_id = $campagne_info->campagne_id;
                                    $list_campagne[] = array('usercampagne_campagne' => $campagne_id);
                                }
                            }
                            
                        }
                        $this->load->model('service_model');
                        if($data[7]){

                            $arr_service = explode('|',$data[7]);
                            foreach($arr_service as $srv){
                                $service_info = $this->service_model->getServiceByLib($srv);
                                if($service_info) {
                                    $service_id = $service_info->service_id;
                                    $list_service[] = array('userservice_service' => $service_id);
                                }
                            }

                            $service_id = $service_info->service_id;
                        }

                        $insert_data[] = array(
                            'usr_nom' => $data[0],
                            'usr_prenom' => $data[1],
                            'usr_matricule' => $data[2],
                            'usr_initiale' => $data[3],
                            'usr_email' => $data[4],
                            'usr_ingress' => $data[5],
                            'campagnes' => $list_campagne,
                            'services' => $list_service,
                            'usr_dateembauche' => $data[8],
                            'usr_username' => $identifiant,
                            'usr_password' => md5($pwd),
                            'usr_role' => $data[9],
                            'usr_actif' => 1, //User par defaut actif
                            'usr_site' => $data[10],
                            'usr_contrat' => $data[11],
                            'usr_datecrea' => date('Y-m-d H:i:s'),
                            'usr_datemodif' => date('Y-m-d H:i:s')
                        );
                    }
                    $row++;
                }
                fclose($handle);
                //var_dump($insert_data); die;
                $this->load->model('user_model');
                if($this->user_model->importUsers($insert_data)){
                    redirect('user/listUtilisateur?msg=succes');
                }
            }else{
                redirect('user/listUtilisateur?msg=error');
            }
            
        }
    }
    /**
     * désactiver un utilisateur
     */

    public function desactivateUtilisateur()
    {

        $user_id = $this->input->post('userToDesactivate');
        
        if($user_id){
            $this->load->model('user_model');
            $is_desactivate = $this->user_model->desactivateUser($user_id);

            if($is_desactivate)
                redirect('user/listUtilisateur?msg=success');
            else
                redirect('user/listUtilisateur?msg=success');
        }
    }


}