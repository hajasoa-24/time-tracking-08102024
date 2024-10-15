<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model{

    private $_table = "tr_message";
    private $_tableUser = "tr_user";
    private $_tabletest = "test";
    private $_role = "tr_role";
    private $_tUser = "tr_user";

    public function __construct() 
    {
        parent::__construct();
    }


/**Debut du model pour l'insertion du message */
public function insertMessage($data)
{
    return $this->db->insert($this->_table, $data);
    if ($this->db->affected_rows() > 0) {
        return true; // Insertion réussie
    } else {
        error_log('Erreur d\'insertion : ' . $this->db->error()['message'], 3, '/path/to/your/logfile.log');
        return false; // Échec de l'insertion
    }
}

/*public function insert_User($data) 
{
    return $this->db->insert($this->_role, $data); 
}/*
/**Fin du model pour l'insertion du message */
   

/**Debut du model pour le message recue */
public function select_message2($msg_user)
{
    $this->db->select("message_message");
    $this->db->where('message_expediteur_id',$msg_user);     
    $query = $this->db->get($this->_table);
    return $query->result();
}

public function select_message($userId,$usrRole)
{
        //Selectionne message par usr_id
    /*$this->db->select("message_id,message_expediteur_id,message_expediteur_name, message_objet, message_message, message_date,message_status,message_lus");
    $this->db->order_by('message_id', 'DESC');
    $this->db->group_by('message_expediteur_id');
    $this->db->where('message_user', $userId);*/
    $this->db->select("m.message_id, m.message_expediteur_id, m.message_expediteur_name, m.message_objet, m.message_message, m.message_date, m.message_status, m.message_lus");
    $this->db->from("{$this->_table} m");

    // Sous-requête pour obtenir le dernier message par expéditeur
    $this->db->join("(SELECT message_expediteur_id, MAX(message_date) AS last_message_date 
                      FROM {$this->_table} 
                      WHERE message_user = $userId 
                      GROUP BY message_expediteur_id) AS last_messages", 
                     "m.message_expediteur_id = last_messages.message_expediteur_id 
                      AND m.message_date = last_messages.last_message_date");
    $this->db->where('m.message_user', $userId);
    $this->db->order_by('m.message_date', ' DESC');
    $this->db->group_by('m.message_expediteur_id'); 
    $msg_query = $this->db->get($this->_table);
    $messages_id = $msg_query->result();

        //Selectionne message par role
    /*$this->db->select("message_id,message_expediteur_id, message_expediteur_name, message_objet, message_message, message_date, message_status,message_lus");
    $this->db->group_by('message_expediteur_id');
    $this->db->where('message_role_id', $usrRole);
    $this->db->order_by('message_date', 'DESC');*/
    $this->db->select("m.message_id, m.message_expediteur_id, m.message_expediteur_name, m.message_objet, m.message_message, m.message_date, m.message_status, m.message_lus");
    $this->db->from("{$this->_table} m");
    
    // Sous-requête pour obtenir le dernier message par expéditeur pour un rôle donné
    $this->db->join("(SELECT message_expediteur_id, MAX(message_date) AS last_message_date 
                      FROM {$this->_table} 
                      WHERE message_role_id = $usrRole 
                      GROUP BY message_expediteur_id) AS last_messages", 
                     "m.message_expediteur_id = last_messages.message_expediteur_id 
                      AND m.message_date = last_messages.last_message_date");
    
    // Filtrer par rôle
    $this->db->where('m.message_role_id', $usrRole);
    $this->db->order_by('m.message_date', ' DESC');
    $this->db->group_by('m.message_expediteur_id');
    
    $role_query = $this->db->get($this->_table);
    $messages_role = $role_query->result();

    /*$this->db->select("message_expediteur_id,message_expediteur_name, message_objet, message_message, message_date,message_status");
    $this->db->where('message_role_id', $usrRole);
    $this->db->group_by('message_expediteur_id');
    $this->db->order_by('message_date', 'DESC');
    $nb_query = $this->db->get($this->_table);
    $nb_messages = $nb_query->result();*/

        // Retourner les deux résultats
    return [
        'messages_id' => $messages_id,
        //'nb_messages' => $nb_messages,
        'messages_role' => $messages_role,
    ];

}

public function verification_message_status($message_id ,$dest_id, $dest_role, $exped_id, $date_msg) 
{
   
    $this->db->select("message_expediteur_id, message_expediteur_name, message_objet, message_message, message_date, message_status, message_lus,message_fichier_path");
    $this->db->where('message_role_id', $dest_role); // Vérifier par rôle
    $this->db->where('message_expediteur_id', $exped_id);
    $this->db->order_by('message_date', 'DESC');

    $query = $this->db->get($this->_table);
    $messages = $query->result();

    // Mettre à jour le statut de lecture
    foreach ($messages as $message) {
        // Récupérer l'ID actuel de message_lus
        $current_lus_ids = !empty($message->message_lus) ? explode(',', $message->message_lus) : [];
        
        // Ajouter le nouvel ID si ce n'est pas déjà présent
        if (!in_array($dest_id, $current_lus_ids)) {
            $current_lus_ids[] = $dest_id; // Ajouter le nouvel ID
        }

        // Convertir le tableau en une chaîne pour la base de données
        $updated_lus_ids = implode(',', $current_lus_ids);
        
        // Mettre à jour la colonne message_lus
        $this->db->where('message_expediteur_id', $exped_id); 
        $this->db->where('message_date', $message->message_date); 
        $this->db->update($this->_table, ['message_lus' => $updated_lus_ids]);

             // Compter le nombre total d'utilisateurs avec le rôle
             $this->db->where('usr_role', $dest_role); 
             $total_users_with_role = $this->db->count_all_results($this->_tUser); 
     
             // Compter le nombre d'utilisateurs ayant lu le message
             $count_users_lus = count($current_lus_ids);
     
             // Vérifier si les deux nombres correspondent
             if ($count_users_lus === $total_users_with_role) {
                 // Mettre à jour le message_status
                 $this->db->where('message_expediteur_id', $exped_id);
                 $this->db->where('message_date', $message->message_date);
                 $this->db->update($this->_table, ['message_status' => 1]);
             }
    }

    return [
        'messages' => $messages,
    ];
}

public function verification_message_status_userId($message_id, $dest_id, $dest_role, $exped_id, $date_msg) 
{
    // Sélection des messages spécifiques
    $this->db->select("message_expediteur_id, message_expediteur_name, message_objet, message_message, message_date, message_status, message_lus,message_fichier_path");
    $this->db->where('message_user', $dest_id);
    $this->db->where('message_expediteur_id', $exped_id);
    $this->db->order_by('message_date', 'DESC');

    $query = $this->db->get($this->_table);
    $messages_specifique = $query->result();

    // Mettre à jour message_lus pour les messages spécifiques
    foreach ($messages_specifique as $message) {
        // Récupérer l'ID actuel de message_lus
        $current_lus_ids = !empty($message->message_lus) ? explode(',', $message->message_lus) : [];
        
        // Ajouter le nouvel ID si ce n'est pas déjà présent
        if (!in_array($dest_id, $current_lus_ids)) {
            $current_lus_ids[] = $dest_id; // Ajouter le nouvel ID
        }

        // Convertir le tableau en une chaîne pour la base de données
        $updated_lus_ids = implode(',', $current_lus_ids);
        
        // Mettre à jour la colonne message_lus
        $this->db->where('message_expediteur_id', $exped_id); 
        $this->db->where('message_id', $message_id);
        $this->db->where('message_date', $message->message_date); 
        $this->db->update($this->_table, ['message_lus' => $updated_lus_ids]);
    }

    // Retourner les messages spécifiques
    return $messages_specifique; // Pas besoin d'appeler result() ici
}

public function select_role_libelle($roleUser)
{
    $this->db->select("role_libelle");
    $this->db->where('role_id', $roleUser);
    $query = $this->db->get($this->_role);
    return $query->result();
}

/**Fin du model pour le message recue */


/**Debut model pour le listse_msg */
function select_msg_user($usr_id) 
{
    // Sélectionner les colonnes des messages
    $this->db->select('message_id,	message_expediteur_id, message_role_id, message_message, message_objet, message_user, message_date');
    
    // Sélectionner les colonnes de la table tr_user
    $this->db->select('usr_nom, usr_prenom');
    
    // Joindre la table tr_user
    $this->db->from($this->_table); // Spécifiez la table de base pour la requête
    $this->db->join($this->_tUser, 'tr_user.usr_id = '.$this->_table.'.message_user', 'left'); // Remplacez 'message_expediteur_id' par la clé correspondante

    // Conditions de filtrage
    $this->db->where('message_expediteur_id', $usr_id);
    
    // Tri des résultats
    $this->db->order_by('message_date', 'DESC');
    
    // Exécution de la requête
    $query = $this->db->get();
    
    // Retourner les résultats
    return $query->result();
}

public function select_allmsg_send($message_destinatair, $expediteur_id, $message_id, $message_role)
{
    // Sélectionner les messages et les détails des utilisateurs
    $this->db->select('
        m.message_id,
        m.message_expediteur_id,
        m.message_role_id,
        m.message_message,
        m.message_objet,
        m.message_user,
        m.message_date,
        m.message_fichier_path,
        u.usr_nom,
        u.usr_prenom
    ');

    // Joindre la table tr_user pour récupérer les noms et prénoms
    $this->db->from($this->_table . ' AS m');
    $this->db->join($this->_tUser . ' AS u', 'u.usr_id = m.message_user', 'left');

    // Conditions de filtrage
    $this->db->where('m.message_user', $message_destinatair);
    $this->db->where('m.message_expediteur_id', $expediteur_id);
    $this->db->where('m.message_id', $message_id);
    
    // Tri des résultats
    $this->db->order_by('m.message_date', 'DESC');

    // Exécution de la requête
    $query = $this->db->get();
    $list_msg = $query->result();

    // Récupérer le message et les utilisateurs qui ont lu le message
    $this->db->select("message_id, message_lus");
    $this->db->from($this->_table);
    $this->db->where("message_id", $message_id);
    
    // Exécution de la requête pour obtenir les lecteurs
    $read_query = $this->db->get();
    $read_users = $read_query->result_array();

    // Extraire les IDs lus
    $read_user_ids = [];
    if (!empty($read_users)) {
        $read_user_ids = explode(',', $read_users[0]['message_lus']);
        $read_user_ids = array_map('trim', $read_user_ids); // Nettoyer les espaces
    }

    // Récupérer les détails des utilisateurs qui ont lu le message
    $user_details = [];
    if (!empty($read_user_ids)) {
        $this->db->select("usr_id, usr_nom, usr_prenom, usr_matricule");
        $this->db->from($this->_tUser);
        $this->db->where_in("usr_id", $read_user_ids);
        $user_query = $this->db->get();
        $user_details['read'] = $user_query->result_array();
    } else {
        $user_details['read'] = []; // Aucune ID à rechercher
    }

    // Récupérer tous les utilisateurs selon le rôle
    $this->db->select("usr_id, usr_nom, usr_prenom, usr_matricule");
    $this->db->where("usr_role", $message_role);
    $this->db->from($this->_tUser);
    $all_users_query = $this->db->get();
    $all_users = $all_users_query->result_array();

    // Extraire les IDs des utilisateurs
    $all_user_ids = array_column($all_users, 'usr_id');

    // Filtrer les utilisateurs qui n'ont pas lu le message
    $unread_user_ids = array_diff($all_user_ids, $read_user_ids);

    // Récupérer les détails des utilisateurs qui n'ont pas lu le message
    $unread_user_details = [];
    if (!empty($unread_user_ids)) {
        $this->db->select("usr_id, usr_nom, usr_prenom, usr_matricule");
        $this->db->from($this->_tUser);
        $this->db->where_in("usr_id", $unread_user_ids);
        $unread_query = $this->db->get();
        $unread_user_details = $unread_query->result_array();
    }

    return [
        'list_msg' => $list_msg,
        'read_users' => $user_details['read'], // Liste des utilisateurs qui ont lu le message
        'unread_users' => $unread_user_details, // Détails des utilisateurs qui n'ont pas lu le message
    ];
}

public function deleteMessage($message_id)
{
    $this->db->where('message_id',$message_id);
    return $this->db->delete($this->_table);
}
/**Fin model pour le listse_msg */

    /**selectionne les nouveaux messages */
    public function get_new_messages($userID, $userROLE) 
    {
        // Sélectionner les messages qui correspondent au rôle
        $this->db->select("message_expediteur_id, message_expediteur_name, message_objet, message_user, message_role_id, message_message, message_date, message_lus");
        $this->db->where('message_role_id', $userROLE); // Filtrer par rôle
    
        // Exécuter la requête pour récupérer les messages
        $query = $this->db->get($this->_table);
        $messages = $query->result();
    
        // Vérifier les nouveaux messages qui n'ont pas été lus
        $newMessages = [];
        foreach ($messages as $message) {
            $current_lus_ids = !empty($message->message_lus) ? explode(',', $message->message_lus) : [];
    
            // Vérifier si l'ID de l'utilisateur est dans les IDs lus
            if (!in_array($userID, $current_lus_ids)) {
                $newMessages[] = $message; // Ajouter le message à la liste des nouveaux messages
            }
        }
    
        // Vérifier si l'utilisateur n'a pas lu certains messages
        $this->db->select("message_expediteur_id, message_expediteur_name, message_objet, message_user, message_role_id, message_message, message_date");
        $this->db->where("message_user", $userID);
        //$this->db->where("message_expediteur_id", $expediteurID);
        $this->db->where("FIND_IN_SET('$userID', message_lus) =", 0); // Vérifier si userID n'est pas dans message_lus
        $query_unread = $this->db->get($this->_table);
        $unreadMessages = $query_unread->result();
    
        // Retourner les résultats
        return [
            'newMessages' => $newMessages, // Nouveaux messages non lus role
            'unreadMessages' => $unreadMessages // Messages non lus selon l'ID
        ];
    }

    public function select_file() {
        $this->db->select("file_name,file_path");
        $query = $this->db->get($this->_tabletest);   
        if ($query->num_rows() > 0) {
            return $query->row()->file_path; // Renvoie seulement le chemin du fichier
        }  
        return null; // Ou gérer le cas où aucun fichier n'est trouvé
    }



    public function updateMessage($message_id, $objet, $message) 
    {
        // Spécifier la condition de mise à jour
        $this->db->where('message_id', $message_id);
        // Essayer de mettre à jour le message
        if ($this->db->update($this->_table, ['message_objet' => $objet, 'message_message' => $message, 'message_status' => 0])) {
            return true; // Retourne vrai si la mise à jour a réussi
        } else {
            return false; // Retourne faux en cas d'échec
        }
    }
    
 

    public function findUser()
    {
        $this->db->select("usr_id, usr_nom , usr_prenom , usr_matricule, usr_initiale, usr_role");
        $query = $this->db->get($this->_tableUser); 
        return $query->result();
    }
        //fonction pour testé l'insertion du fichier
    public function insert_file($data)
    {    
        return $this->db->insert($this->_tabletest, $data);
    }

   

    /*public function select_message_user($userId, $usrRole) {
        // Sélectionne les messages spécifiques à l'utilisateur
        $this->db->select("m.message_id, m.message_expediteur_id, m.message_expediteur_name, m.message_objet, m.message_message, m.message_date, m.message_status, m.message_lus, 'user' AS source");
        $this->db->from("{$this->_table} m");
        $this->db->join("(SELECT message_expediteur_id, MAX(message_date) AS last_message_date 
                          FROM {$this->_table} 
                          WHERE message_user = $userId 
                          GROUP BY message_expediteur_id) AS last_messages", 
                         "m.message_expediteur_id = last_messages.message_expediteur_id 
                          AND m.message_date = last_messages.last_message_date", 
                         'left'); // Utiliser 'left' pour éviter de perdre des résultats
    
        $this->db->where('m.message_user', $userId);
        $this->db->group_by('m.message_expediteur_id');
        $this->db->order_by('m.message_date', 'ESC');
    
        // Exécute la première requête et obtient les résultats
        $query_user = $this->db->get();
        $messages_user = $query_user->result();
    
        // Récupère les messages par rôle
        $this->db->select("m.message_id, m.message_expediteur_id, m.message_expediteur_name, m.message_objet, m.message_message, m.message_date, m.message_status, m.message_lus, 'role' AS source");
        $this->db->from("{$this->_table} m");
        $this->db->join("(SELECT message_expediteur_id, MAX(message_date) AS last_message_date 
                          FROM {$this->_table} 
                          WHERE message_role_id = $usrRole 
                          GROUP BY message_expediteur_id) AS last_messages", 
                         "m.message_expediteur_id = last_messages.message_expediteur_id 
                          AND m.message_date = last_messages.last_message_date", 
                         'left'); // Utiliser 'left' pour éviter de perdre des résultats
    
        $this->db->where('m.message_role_id', $usrRole);
        $this->db->group_by('m.message_expediteur_id');
        $this->db->order_by('m.message_date', 'ESC');
    
        // Exécute la deuxième requête et obtient les résultats
        $query_role = $this->db->get();
        $messages_role = $query_role->result();
    
        // Combiner les deux résultats
        $messages = array_merge($messages_user, $messages_role);
    
        // Retourner les résultats combinés
        return $messages;
        log_message('info', $this->db->last_query());
    }
    
    
    

    /**---------------------------------------------- */
/**Verification si l'utilisateur a lus le message */





/*public function verification_message_status_combined($message_id, $dest_id, $dest_role, $exped_id, $date_msg) {
    // Sélectionner les messages basés sur l'expéditeur et le rôle ou l'utilisateur
    $this->db->select("message_expediteur_id, message_expediteur_name, message_objet, message_message, message_date, message_status, message_lus");
    $this->db->where('message_role_id', $dest_role);
    $this->db->where('message_expediteur_id', $exped_id);
    $this->db->order_by('message_date', 'DESC');

    $query = $this->db->get($this->_table);
    $messages = $query->result();

    // Mettre à jour le statut de lecture pour les messages trouvés
    foreach ($messages as $message) {
        // Récupérer l'ID actuel de message_lus
        $current_lus_ids = !empty($message->message_lus) ? explode(',', $message->message_lus) : [];

        // Ajouter le nouvel ID si ce n'est pas déjà présent
        if (!in_array($dest_id, $current_lus_ids)) {
            $current_lus_ids[] = $dest_id; // Ajouter le nouvel ID
        }

        // Convertir le tableau en une chaîne pour la base de données
        $updated_lus_ids = implode(',', $current_lus_ids);

        // Mettre à jour la colonne message_lus
        $this->db->where('message_expediteur_id', $exped_id); 
        $this->db->where('message_date', $message->message_date); 
        $this->db->update($this->_table, ['message_lus' => $updated_lus_ids]);

        // Compter le nombre total d'utilisateurs avec le rôle
        $this->db->where('usr_role', $dest_role); 
        $total_users_with_role = $this->db->count_all_results($this->_tUser); 

        // Compter le nombre d'utilisateurs ayant lu le message
        $count_users_lus = count($current_lus_ids);

        // Vérifier si les deux nombres correspondent
        if ($count_users_lus === $total_users_with_role) {
            // Mettre à jour le message_status
            $this->db->where('message_expediteur_id', $exped_id);
            $this->db->where('message_date', $message->message_date);
            $this->db->update($this->_table, ['message_status' => 1]);
        }
    }

    // Retourner les messages
    return $messages;
}


    public function get_readers_ids($message_id,$message_user) {
        // Récupérer le message correspondant
        $this->db->select('message_lus');
        $this->db->where('message_id', $message_id);
        $this->db->or_where('message_user', $message_id);
        $query = $this->db->get($this->_table);

        if ($query->num_rows() > 0) {
            $row = $query->row();
            // Vérifier si message_lus n'est pas vide
            if (!empty($row->message_lus)) {
                // Convertir la chaîne d'IDs en tableau
                return explode(',', $row->message_lus);
            }
        }

        return []; // Retourne un tableau vide si aucun ID trouvé
    }

    

/** --------------------------------------------- */


    public function select_messages_with_count($dest_id,$dest_role,$exped_id,$date_msg) 
    {
        // Récupérer tous les messages d'un utilisateur
        $this->db->select("message_expediteur_id,message_expediteur_name, message_objet, message_message,message_date");
        $this->db->where('message_user', $dest_id);
        $this->db->or_where('message_role_id', $dest_role);
        $this->db->where('message_expediteur_id', $exped_id);  
        $this->db->where('message_status', 0);
        $this->db->update($this->_table, ['message_status' => 1]);

            // Récupérer les messages mis à jour
        $this->db->select("message_expediteur_id,message_expediteur_name, message_objet, message_message, message_date");
        $this->db->where('message_user', $dest_id);
        $this->db->where('message_date', $date_msg);
        $this->db->or_where('message_role_id', $dest_role);
        $this->db->where('message_status', 1); // Récupérer les messages avec status mis à jour
        $this->db->where('message_expediteur_id', $exped_id);
        $this->db->order_by('message_date', 'DESC');

        $query = $this->db->get($this->_table);
        return $query->result();
        
    }
 
       
    public function message_envoye($user)
    {
            // Récupérer les messages lus
        $this->db->select("message_expediteur_id,message_expediteur_name, message_objet, message_user, message_role_id, message_message, message_date");
        $this->db->where('message_expediteur_id', $user);
        $this->db->where('message_status', 1); // Messages lus
        $lu_query = $this->db->get($this->_table);
        $messages_lu = $lu_query->result();

            // Récupérer les messages non lus
        $this->db->select("message_expediteur_id,message_expediteur_name, message_objet, message_user, message_role_id, message_message, message_date");
        $this->db->where('message_expediteur_id', $user);
        $this->db->where('message_status', 0); // Messages lus
        $non_lu_query = $this->db->get($this->_table);
        $messages_non_lu = $non_lu_query->result();

        return [
            'messages_lu' => $messages_lu,
            'messages_non_lu' => $messages_non_lu,
        ];
        
    }

   
    
    /*public function select_allmsg_send($message_destinatair,$expediteur_id,$message_id,$message_role) {
        $this->db->select('message_id,	message_expediteur_id, message_role_id, message_message, message_objet, message_user, message_date');
        
        // Sélectionner les colonnes de la table tr_user
        $this->db->select('usr_nom, usr_prenom');
        
        // Joindre la table tr_user
        $this->db->from($this->_table);
        $this->db->join($this->_tUser, 'tr_user.usr_id = '.$this->_table.'.message_user', 'left'); 
    
        // Conditions de filtrage
        $this->db->where('message_user', $message_destinatair);
        $this->db->where('message_expediteur_id', $expediteur_id);
        $this->db->where('message_id', $message_id);
        // Tri des résultats
        $this->db->order_by('message_date', 'DESC');
        
        // Exécution de la requête
        $query = $this->db->get();
        $list_msg = $query->result();
        
            // Récupérer les utilisateurs qui ont lu le message
        $this->db->select("message_id,message_lus");
        $this->db->select("usr_nom, usr_prenom, usr_matricule");
        $this->db->from($this->_table);
        $this->db->join($this->_tUser, 'tr_user.usr_id = '.$this->_table.'.message_user', 'left');
        $this->db->where("message_id", $message_id);
            
            // Message_lus est une colonne avec des IDs séparés par des virgules
        $read_query = $this->db->get();
        $read_users = $read_query->result_array();

        // Extraire les IDs lus
        $read_user_ids = [];
        if (!empty($read_users)) {
            $read_user_ids = explode(',', $read_users[0]['message_lus']);
        }
        
            // Récupérer tous les utilisateurs
        $this->db->select("usr_id, usr_nom, usr_prenom, usr_matricule");
        $this->db->where("usr_role", $message_role);
        $this->db->from($this->_tUser); // Table des utilisateurs
        $all_users_query = $this->db->get();
        $all_users = $all_users_query->result_array();

            // Extraire les IDs des utilisateurs
        $all_user_ids = array_column($all_users, 'usr_id');
        
            // Filtrer les utilisateurs qui n'ont pas lu le message
        $unread_users = array_diff($all_user_ids, $read_user_ids);
        
        return [
            'list_msg' => $list_msg,
            'read_users' => $read_users,
            'unread_users' => $unread_users,
        ];
    }*/
   

    public function get_read_unread_users($messageId,$userId) 
    {
        // Récupérer les utilisateurs qui ont lu le message
        $this->db->select("message_lus");
        $this->db->from($this->_table);
        $this->db->where("message_id", $messageId);
        
        // On suppose que message_lus est une colonne avec des IDs séparés par des virgules
        $this->db->like("message_lus", $userId);
        $read_query = $this->db->get();
        $read_users = $read_query->result_array();
    
        // Récupérer tous les utilisateurs
        $this->db->select("usr_id");
        $this->db->from($this->$_tUser); // Table des utilisateurs
        $all_users_query = $this->db->get();
        $all_users = $all_users_query->result_array();
    
        // Filtrer les utilisateurs qui n'ont pas lu le message
        $unread_users = array_diff(array_column($all_users, 'usr_id'), array_column($read_users, 'usr_id'));
    
        return [
            'read_users' => $read_users,
            'unread_users' => $unread_users,
        ];
    }

    public function select_nb_msg($userNom)
    {
        $this->db->select('COUNT(message_message) as count');
        $this->db->where('message_expediteur_id',$userNom);
        $this->db->where('message_status', 0);    
        $query = $this->db->get($this->_table);
        return $query->result();
    }



    public function select_user($user)
    {
        $this->db->select('usr_id,usr_nom,usr_prenom,usr_role');
        $this->db->where('usr_username',$user);   
        $query = $this->db->get($this->_tUser);
        return $query->result();
    }

    public function Number_msg()
    {
        $this->db->select('COUNT(message_message) as count');    
        $query = $this->db->get($this->_table);
        return $query->result();
    }

    public function find_Exped($exped)
    {
        $this->db->select("message_message");
        $this->db->where('message_expediteur_id', $exped);   
        $query = $this->db->get($this->_table);
        return $query->result();
    }

    public function select_expediteur($username_msg)
    {
        $this->db->select('usr_id,usr_nom,usr_prenom');
        $this->db->where('usr_username',$username_msg);
        $query = $this->db->get($this->_tUser);
        return $query->result();
    }

    public function select_all_expediteur()
    {
        $this->db->select('message_expediteur_id');
        $query = $this->db->get($this->_table);
        return $query->result();
    }
    
    public function get_new_messages_count($user_id) {
        $this->db->where('message_id', $user_id);
        $this->db->where('is_read', 0); // Par exemple, pour les messages non lus
        return $this->db->count_all_results('messages');
    }

   

   
}