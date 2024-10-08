<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends My_Controller{

    public function __construct()
    {
        parent::__construct();
        //$this->load->model('message_model');
        $this->load->library('session');
    }
 
    public function index()
    {
        $this->envoye();
    }

    public function envoye() {
        $header = ['pageTitle' => 'Suivi présence - TimeTracking'];
        $this->load->model('role_model');
        $listRole = $this->role_model->getAllRole();
        $nom = 'nom';
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('message/envoye' , array('listRole' => $listRole, 'nom' => $nom));
        $this->load->view('common/footer', []);
    }

   public function insertMessage() {
        $this->load->model('message_model');
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $expediteur = $this->input->post('userInfo');
            $expediteur_name = $this->input->post('expediteur_nom');
            $role = $this->input->post('role_msg');
            $user_destinataire = $this->input->post('select_users_specifique');
            $objet = $this->input->post('objet_msg');
            $message = $this->input->post('message_msg');
            $date = $this->input->post('date_msg');
            $status = false;
            // Configuration de l'upload
            $config['upload_path']   = './uploads/'; // Répertoire où le fichier sera enregistré
            $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|txt|sql|zip'; // Types de fichiers autorisés
            $config['max_size']      = 2048; // Taille maximale du fichier en Ko
            $this->load->library('upload', $config);
            // Vérifiez si un fichier a été téléchargé
            if ($this->upload->do_upload('file_msg')) {
                $upload_data = $this->upload->data(); // Récupère les informations du fichier
                $fichier = $upload_data['file_name']; // Nom du fichier téléchargé
            } else {
                $fichier = ''; // Pas de fichier ou une erreur est survenue
                // Vous pouvez gérer l'erreur ici si nécessaire
                log_message('error', 'Erreur lors de l\'upload : ' . $this->upload->display_errors());
            }
            $role_mapping = array(
                /*'1' => 'Admin',
                '2' => 'Agent',
                '3' => 'Superviseur',
                '4' => 'Cadre',
                '5' => 'Admin_rh',
                '6' => 'Cadre2',
                '7' => 'Direction',
                '8' => 'Costrat',
                '9' => 'Client',
                '10' => 'Reporting'*/
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10'
            );
            // Initialiser un tableau pour stocker les rôles associés
            $roles_associés = array();
            // Boucle pour associer les identifiants aux valeurs
            foreach ($role as $role_id) {
                if (isset($role_mapping[$role_id])) {
                    $roles_associés[] = $role_mapping[$role_id];
                }
            }
            if (!empty($message)) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Aucune donnée à insérer.'
                );
                if (is_array($roles_associés) && !empty($roles_associés) && is_array($user_destinataire) && !empty($user_destinataire)) {
                    $successfulInserts = 0;
                    $failedInserts = 0;  
                    foreach ($roles_associés as $role) {
                        foreach ($user_destinataire as $value) {
                            $data = array(
                                'message_expediteur_id' => $expediteur,
                                'message_expediteur_name' => $expediteur_name,
                                'message_role_id' => $role,
                                'message_objet' => $objet,
                                'message_message' => $message,
                                'message_fichier_name' => $fichier,
                                'message_user' => $value,
                                'message_date' => date('Y-m-d H:i:s'),
                                'message_status' => $status
                            );
                            // Insérer dans la base de données
                            if ($this->message_model->insertMessage($data)) {
                                $successfulInserts++;
                            } else {
                                // Gestion des erreurs d'insertion
                                $failedInserts++;
                                log_message('error', 'Erreur lors de l\'insertion de: ' . json_encode($data)); // Log d'erreur
                            }
                        }
                    }
                    // Mise à jour de la réponse en fonction des insertions réussies
                    if ($successfulInserts > 0) {
                        $response = array(
                            'status' => 'success',
                            'message' => "$successfulInserts données insérées avec succès."
                        );
                    }   
                    if ($failedInserts > 0) {
                        $response['status'] = 'partial_success';
                        $response['message'] .= " $failedInserts insertion(s) échouée(s).";
                    }
                } elseif (is_array($roles_associés) && empty($roles_associés)) {
                    foreach ($user_destinataire as $value) {
                        $data = array(
                            'message_expediteur_id' => $expediteur,
                            'message_expediteur_name' => $expediteur_name,
                            'message_role_id' => '',
                            'message_objet' => $objet,
                            'message_message' => $message,
                            'message_fichier_name' => $fichier,
                            'message_user' => $value,
                            'message_date' => date('Y-m-d H:i:s'),
                            'message_status' => $status
                        );   
                        // Insérez chaque message avec le rôle correspondant
                        $this->message_model->insertMessage($data);
                    }
                } elseif (empty($user_destinataire)) {
                    foreach ($roles_associés as $role) {
                        $data = array(
                            'message_expediteur_id' => $expediteur,
                            'message_expediteur_name' => $expediteur_name,
                            'message_role_id' => $role,
                            'message_objet' => $objet,
                            'message_message' => $message,
                            'message_fichier_name' => $fichier,
                            'message_user' => '',
                            'message_date' => date('Y-m-d H:i:s'),
                            'message_status' => $status
                        );
                        $this->message_model->insertMessage($data);
                    }
                }  
                $response = array(
                    'status' => 'success',
                    'message' => 'Roles associated successfully',
                    'data' => $roles_associés
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Le message ne peut pas être vide!',
                );
            }   
            // Retourner la réponse
            echo json_encode($response);
        }
    }
/**concroler test avec fichier */
   /* public function insertMessage() {
        $this->load->model('message_model');
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Récupération des données
            $expediteur = $this->input->post('userInfo');
            $expediteur_name = $this->input->post('expediteur_nom');
            $role = json_decode($this->input->post('role_msg'), true); // Décoder les rôles depuis JSON
            $user_destinataire = json_decode($this->input->post('select_users_specifique'), true); // Décoder les destinataires
            $objet = $this->input->post('objet_msg');
            $message = $this->input->post('message_msg');
            $date = $this->input->post('date_msg');
            $status = false;

            log_message('info', 'Données reçues: ' . json_encode($_POST));

            // Configuration de l'upload
            $config['upload_path']   = './uploads/'; // Répertoire où le fichier sera enregistré
            $config['allowed_types'] = 'gif|jpg|png|pdf|doc|docx|txt|sql|zip'; // Types de fichiers autorisés
            $config['max_size']      = 2048; // Taille maximale du fichier en Ko
            $this->load->library('upload', $config);
    
            // Vérifiez si un fichier a été téléchargé
            $fichier = '';
            if (isset($_FILES['fichiers']) && !empty($_FILES['fichiers']['name'][0])) {
                // Gestion de l'upload de fichiers multiples
                $files = $_FILES['fichiers'];
                $file_count = count($files['name']);
                for ($i = 0; $i < $file_count; $i++) {
                    $_FILES['file']['name'] = $files['name'][$i];
                    $_FILES['file']['type'] = $files['type'][$i];
                    $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                    $_FILES['file']['error'] = $files['error'][$i];
                    $_FILES['file']['size'] = $files['size'][$i];
    
                    if ($this->upload->do_upload('file')) {
                        $upload_data = $this->upload->data(); // Récupère les informations du fichier
                        $fichier .= $upload_data['file_name'] . ','; // Stocker les noms des fichiers
                    } else {
                        log_message('error', 'Erreur lors de l\'upload : ' . $this->upload->display_errors());
                    }
                }
                $fichier = rtrim($fichier, ','); // Enlever la virgule finale
            }
    
            $role_mapping = array(
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10'
            );
    
            // Initialiser un tableau pour stocker les rôles associés
            $roles_associés = array();
            foreach ($role as $role_id) {
                if (isset($role_mapping[$role_id])) {
                    $roles_associés[] = $role_mapping[$role_id];
                }
            }
    
            if (!empty($message)) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Aucune donnée à insérer.'
                );
    
                if (is_array($roles_associés) && !empty($roles_associés) && is_array($user_destinataire) && !empty($user_destinataire)) {
                    $successfulInserts = 0;
                    $failedInserts = 0;  
                    foreach ($roles_associés as $role) {
                        foreach ($user_destinataire as $value) {
                            $data = array(
                                'message_expediteur_id' => $expediteur,
                                'message_expediteur_name' => $expediteur_name,
                                'message_role_id' => $role,
                                'message_objet' => $objet,
                                'message_message' => $message,
                                'message_fichier_name' => $fichier,
                                'message_user' => $value,
                                'message_date' => date('Y-m-d H:i:s'),
                                'message_status' => $status
                            );
                            if ($this->message_model->insertMessage($data)) {
                                $successfulInserts++;
                            } else {
                                $failedInserts++;
                                log_message('error', 'Erreur lors de l\'insertion de: ' . json_encode($data));
                            }
                        }
                    }
    
                    // Mise à jour de la réponse en fonction des insertions réussies
                    if ($successfulInserts > 0) {
                        $response = array(
                            'status' => 'success',
                            'message' => "$successfulInserts données insérées avec succès."
                        );
                    }
                    if ($failedInserts > 0) {
                        $response['status'] = 'partial_success';
                        $response['message'] .= " $failedInserts insertion(s) échouée(s).";
                    }
                } else {
                    // Gestion des cas où les rôles ou destinataires sont vides
                    // Ajoutez votre logique ici si nécessaire
                }
    
                // Retourner la réponse
                echo json_encode($response);
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Le message ne peut pas être vide!'
                );
                echo json_encode($response);
            }
        }
    }*/
    /**Fin concroler test avec fichier */


/**test function */

    public function test(){

        $this->load->model('message_model');
        
        $name = $this->input->post('name');
        $lastname = $this->input->post('lastname');

            
        $data = array(
            'nom' => $name,
            'prenom' => $lastname
        );

        if ($this->message_model->insert_data($data)) {
            $this->session->set_flashdata('success', 'Produit ajouté avec succès.');

            $data = array('status' => 'success', 'message' => 'Click handled');
            echo json_encode($data); 
        } else {
            $this->session->set_flashdata('error', 'Échec de l\'ajout du produit.');

            $data = array('status' => 'error', 'messageerror' => 'Click handled');
            echo json_encode($data); 
        }
        redirect('message/envoye');
    }

    public function test2() {
        $this->load->model('message_model');
    
        $user_destinataire = $this->input->post('select_users_specifique');
        
        // Réponse par défaut
        $response = array(
            'status' => 'error',
            'message' => 'Aucune donnée à insérer.'
        );
    
        if (is_array($user_destinataire) && !empty($user_destinataire)) {
            $successfulInserts = 0;
            $failedInserts = 0;
    
            foreach ($user_destinataire as $value) {
                $data = array(
                    'message_user' => $value // Remplacez par le nom de votre colonne
                );
    
                // Insérer dans la base de données
                if ($this->message_model->insert_User($data)) {
                    $successfulInserts++;
                } else {
                    // Gestion des erreurs d'insertion
                    $failedInserts++;
                    log_message('error', 'Erreur lors de l\'insertion de: ' . json_encode($data)); // Log d'erreur
                }
            }
    
            // Mise à jour de la réponse en fonction des insertions réussies
            if ($successfulInserts > 0) {
                $response = array(
                    'status' => 'success',
                    'message' => "$successfulInserts données insérées avec succès."
                );
            }
            
            if ($failedInserts > 0) {
                $response['status'] = 'partial_success';
                $response['message'] .= " $failedInserts insertion(s) échouée(s).";
            }
        }
    
        // Envoyer la réponse JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function handleClick() {

        $this->load->model('message_model');

        /*$name = $this->input->post('test');

        $lastname = $this->input->post('test1');*/
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

        $role = $this->input->post('role_msg');
      
        $role_mapping = array(
            '1' => 'Admin',
            '2' => 'Agent',
            '3' => 'Superviseur',
            '4' => 'Cadre',
            '5' => 'Admin_rh',
            '6' => 'Cadre2',
            '7' => 'Direction',
            '8' => 'Costrat',
            '9' => 'Client',
            '10' => 'Reporting'
        );

            // Initialiser un tableau pour stocker les rôles associés
            $roles_associés = array();

            // Boucle pour associer les identifiants aux valeurs
            foreach ($role as $role_id) {
                if (isset($role_mapping[$role_id])) {
                    $roles_associés[] = $role_mapping[$role_id];
                }
            }

            $response = array(
                'status' => 'success',
                'message' => 'Roles associated successfully',
                'data' => $roles_associés
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }else{
            show_error('Invalid request method');
        }
            
      $this->message_model->insertUser($roles_associés);

       
    }

    /**fonction test pour upload files */
    public function upload_files() {
        $files = $_FILES['fichiers'];
    
        if (isset($files) && count($files['name']) > 0) {
            for ($i = 0; $i < count($files['name']); $i++) {
                // Configuration de chaque fichier
                $_FILES['file']['name'] = $files['name'][$i];
                $_FILES['file']['type'] = $files['type'][$i];
                $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
                $_FILES['file']['error'] = $files['error'][$i];
                $_FILES['file']['size'] = $files['size'][$i];
    
                // Charger la bibliothèque d'upload
                $this->load->library('upload');
    
                // Configuration de l'upload
                $config['upload_path'] = './uploads/'; 
                $config['allowed_types'] = 'txt|sql|zip|bin'; 
                $config['max_size'] = 82048; 
    
                $this->upload->initialize($config);
    
                echo "Traitement du fichier: " . $_FILES['file']['name'] . "<br>";
                
                if ($this->upload->do_upload('file')) {
                    echo "Upload réussi: " . $_FILES['file']['name'] . "<br>";
                    $file_data = $this->upload->data();
                    $file_path = $file_data['full_path'];
    
                    // Insérer le chemin dans la base de données
                    $insert_data = array(
                        'file_name' => $file_data['file_name'],
                        'file_path' => $file_path,
                    );
                    $this->db->insert('test', $insert_data);
    
                    // Lire le contenu du fichier selon son extension
                    if ($file_data['file_ext'] == '.txt') {
                        $content = file_get_contents($file_path);
                        // Gérer le contenu si nécessaire
                    } elseif ($file_data['file_ext'] == '.sql') {
                        $sql = file_get_contents($file_path);
                        $queries = explode(';', $sql);
                        foreach ($queries as $query) {
                            $query = trim($query);
                            if (!empty($query)) {
                                $this->db->query($query);
                            }
                        }
                    }
                } else {
                    echo "Erreur lors de l'upload: " . $this->upload->display_errors() . "<br>";
                }
            }
            echo "Importation terminée.";
        } else {
            echo "Aucun fichier sélectionné.";
        }
    }
    
    

/**End test function */
  
    public function seacrch()
    {
        $this->load->model('message_model');

        $results = $this->message_model->findUser();
        echo json_encode(["data" => $results]);
    }

    public function select_subject_exped(){

        $results = $this->message_model->select_subject_and_exped();
        echo json_encode(["data" => $results]);
    }

    public function find_Exped(){
        $this->load->library('form_validation');
        $this->load->model('message_model');       
        $username_msg = $this->input->post('username_msg');
        $results = $this->message_model->select_expediteur($username_msg);
        echo json_encode(["data" => $results]);
        $response = array(
            'status' => 'success',
            'message' => 'Roles associated successfully',
            'data' => $results
        );
    }

    public function find_message(){
        $this->load->model('message_model');
        $expediteur = $this->input->post('expediteur');
        $results = $this->message_model->select_message2($expediteur);
        echo json_encode(["data" => $results]);
        $response = array(
            'status' => 'success',
            'message' => 'Roles associated successfully',
            'data' => $results
        );
    }

    public function get_message(){
        $this->load->model('message_model');
        $userId = $this->session->userdata('user')['id'];
        $usrRole = $this->session->userdata('user')['role'];  
        
        // Appel à la méthode pour récupérer les messages et le compte
        $new_messages = $this->message_model->select_message($userId,$usrRole);  
        
            // Vérifier si des messages ont été récupérés
       /* if (!empty($new_messages['messages_id'])) {
            // Stocker les messages dans la session
            $messages = $this->session->set_userdata('user_messages', $new_messages['messages_id']);
        } else {
            // Optionnel : Nettoyer la session si aucun message n'est trouvé
            $this->session->unset_userdata('user_messages');
        }*/

        $response = [
            'messages' => $new_messages['messages_id'],
            //'message_counts' => $new_messages['nb_messages'],
            'messages_role' => $new_messages['messages_role'], 
        ];
        // Retourner la réponse au format JSON
        echo json_encode($response);
       
    }

    public function nb_message(){
        $this->load->model('message_model');      
        $dest_id = $this->input->post('id_destinatair');
        $dest_role = $this->input->post('role_destinatair');
        $exped_id = $this->input->post('id_expediteur');
        $this->session->set_userdata('ExpediteurId', $exped_id);
        $date_msg = $this->input->post('date_msg');
        $results = $this->message_model->select_messages_with_count($dest_id,$dest_role,$exped_id,$date_msg);
        echo json_encode(["data" => $results]);
        $response = array(
            'status' => 'success',
            'message' => 'Roles associated successfully',
            'data' => $results
        );
    } 

    public function verifierStatutMessage() {      
        $this->load->model('message_model');       
        //$message_id = $this->input->post('message_id');
        $dest_id = $this->input->post('id_destinatair');
        $dest_role = $this->input->post('role_destinatair');
        $exped_id = $this->input->post('id_expediteur');
        $date_msg = $this->input->post('date_msg');
        $new_messages = $this->message_model->verification_message_status($dest_id, $dest_role, $exped_id, $date_msg);
       
        $response = [
            'messages' => $new_messages['messages_specifique'],
            'messages_role' => $new_messages['messages'], 
        ];
        echo json_encode($response);
    }
    
    public function select_message_user(){
        $this->load->model('message_model');      
        $usr_id = $this->input->post('user_id');   
        $results = $this->message_model->select_msg_user($usr_id);
        echo json_encode(["data" => $results]);
        $response = array(
            'status' => 'success',
            'message' => 'Roles associated successfully',
            'data' => $results
        );
    }
    
    public function select_message_send(){
        $this->load->model('message_model');
        $message_id = $this->input->post('messageID');
        $expediteur_id = $this->input->post('expediteurID'); 
        $message_destinatair = $this->input->post('messageDESTINATAIR');   
        $results = $this->message_model->select_allmsg_send($message_destinatair,$expediteur_id,$message_id);
        echo json_encode(["data" => $results]);
        $response = array(
            'status' => 'success',
            'message' => 'Roles associated successfully',
            'data' => $results
        );
    } 

    public function find_user(){
        $this->load->model('message_model');
        $user = $this->input->post('username_msg');
        $results = $this->message_model->select_user($user);
        echo json_encode(["data" => $results]);
        $response = array(
            'status' => 'success',
            'message' => 'Roles associated successfully',
            'data' => $results
        );
    }

    public function get_message_envoye() {
        $this->load->model('message_model');
        $user = $this->input->post('username_expediteur');
        $results = $this->message_model->message_envoye($user);    
        $messages = $this->message_model->message_envoye($user);  
        // Vérifie si $messages est un tableau et contient les clés attendues
        $response = [
            'messages_lu' => isset($messages['messages_lu']) ? $messages['messages_lu'] : [],
            'messages_non_lu' => isset($messages['messages_non_lu']) ? $messages['messages_non_lu'] : [],
        ];
    
        echo json_encode($response);   
    }

    public function suppression(){
        $this->load->model('message_model');
        $message_id = $this->input->post('id_message');
        $results = $this->message_model->deleteMessage($message_id);
        echo json_encode(["data" => $results]);
        $response = array(
            'status' => 'success',
            'message' => 'Message supprimer!',
            'data' => $results
        );
    }

    public function modification_msg(){
        $this->load->model('message_model');
        $message_id = $this->input->post('id_message');
        $objet = $this->input->post('objet');
        $message = $this->input->post('message');
        $result = $this->message_model->updateMessage($message_id, $objet, $message);
        if ($result) {
            $response = ['status' => 'success', 'message' => 'Message mis à jour avec succès.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Échec de la mise à jour du message.'];
        }   
        echo json_encode($response); // Retourner la réponse en JSON
    }    

    public function check_new_messages() {
        $this->load->model('message_model');
        $userID = $this->input->get('userId');
        $userROLE = $this->input->get('userRole');
        //$expediteurID = $this->input->get('usr_expediteur_id');
       
        $new_messages = $this->message_model->get_new_messages($userID,$userROLE);      

        $response = [
            'newMessages' => isset($new_messages['newMessages']) ? $new_messages['newMessages'] : [],
            'unreadMessages' => isset($new_messages['unreadMessages']) ? $new_messages['unreadMessages'] : [],
        ];
    
        echo json_encode($new_messages); 
    }

    public function mark_as_read() {
        $this->load->model('message_model');
        $user_id = $this->session->userdata('message_id');
        $this->Message_model->mark_as_read($user_id);
        echo json_encode(['success' => true]);
    }

    public function mark() {
        $this->load->model('message_model');
        $file_path = $this->message_model->select_file(); // Récupérer le chemin du fichier
        if (file_exists($file_path)) {
            echo json_encode(['success' => true, 'content' => $file_path]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Fichier non trouvé.']);
        }
    }

        
}