<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $_username,
            $_password,
            $_nom,
            $_prenom,
            $_matricule,
            $_initiale,
            $_email,
            $_dateembauche,
            $_role,
            $_actif,
            $ingress,
            $_datecrea,
            $_datemodif,
            $_table = 'tr_user';
    

    //PUBLIC FUNCTIONS

    public function __construct() 
    {
        parent::__construct();
    }

    public function setLoginCredentials($username, $password)
    {
        $this->_setUsername($username);
        $this->_setPassword($password);
    }

    /**
     * Verifier la coherence de l'username et mot de passe au niveau de la base de données
     * Si OK => on récupère les infos de l'utilisateur 
     * Sinon, on renvoie FALSE
     */
    public function verifyLogin()
    {
        $query = $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_email, usr_role, usr_site, usr_contrat, site_libelle, usr_actif, usr_ingress, usr_contrat, role_poids')
                ->select('usersuppl_istech, usersuppl_isGestionAchat, usersuppl_issecurite, usersuppl_istransport, usersuppl_isadmin, usersuppl_issupportadmin,usersuppl_iscadreIT,usersuppl_isRespTransport')
                ->where('usr_username', $this->_username)
                ->where('usr_password', md5($this->_password))
                ->where('usr_actif', '1')
                ->join('t_usersuppl', 'usr_id = usersuppl_user', 'left')
                ->join('tr_site', 'site_id = usr_site', 'inner')
                ->join('tr_role', 'role_id = usr_role', 'inner')
                ->get($this->_table);
      
        if($query->num_rows() == 1)
        {
            return $query->row();
        }

        return false;
    }

    /**
     * Charger les informations de l'utilisateur par usr_id
     */
    public function getUserInfos($id)
    {
        $query = $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_email, usr_role, usr_actif, usr_ingress, usr_contrat, usr_dateembauche,usr_site')
                            ->where('usr_id', $id)
                            ->get($this->_table);
        if($query->num_rows() == 1)
        {
            return $query->row();
        }
        return false;
    }

    /**
     * Mise à jour des informations basique d'un utilisateur
     */
    public function updateUserInfos($dataToUpdate, $cond)
    {
        $this->db->update($this->_table, $dataToUpdate, $cond);
        if($this->db->affected_rows() > 0)
            return true;
        else
            return false;
    }

    /**
     * Vérification d'un mot de passe saisi en BD
     */
    public function checkPwd($pwd)
    {
        $data = [
            'usr_password' => MD5($pwd)
        ];
        $query = $this->db->get_where($this->_table, $data);
        if($query->num_rows() > 0){
            return true;
        }
        return false;
    }

    /**
     * Mise à jour d'un mot de passe
     */
    public function updatePwd($password, $userId)
    {
        $query = $this->db->update($this->_table, array('usr_password' => MD5($password)), array('usr_id' => $userId));
        if($this->db->affected_rows() > 0) return true;

        return false;
    }


    //PRIVATE FUNCTIONS

    private function _setUsername($username)
    {
        $this->_username = $username;
    }

    private function _setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * get all users 
     */
    public function getAllUser($onlyActive = false)
    {
        $this->db->select('usr_id, usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_role, usr_ingress, usr_actif, usr_dateembauche')
                ->select('role_libelle, site.site_libelle, site.site_id as usr_site')  
                ->select('(SELECT GROUP_CONCAT(campagne_libelle)  FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS campagnes')
                ->select('(SELECT GROUP_CONCAT(service_libelle)  FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS services')
                ->select('contrat.site_libelle as site_contrat')
                ->join('tr_role', 'usr_role = role_id', 'inner')  
                ->join('tr_site as site', 'usr_site = site.site_id', 'inner')
                ->join('tr_site as contrat', 'usr_contrat = contrat.site_id', 'left');
                
        if($onlyActive){
            $this->db->where('usr_actif', 1);
        }

        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->result();
        }

        return false;
    }


    /*
    * enregistrer un nouvel utilisateur 
    */

    public function inserUtilisateur($data)
    {
    
        $entry = array(
        'usr_nom' => (isset($data['user_nom'])) ? $data['user_nom'] : '',
        'usr_prenom' => (isset($data['user_prenom'])) ? $data['user_prenom'] : '',
        'usr_matricule' => (isset($data['user_matricule'])) ? $data['user_matricule'] : '',
        'usr_initiale' => (isset($data['user_initiale'])) ? $data['user_initiale'] : '',
        'usr_email' => (isset($data['user_email'])) ? $data['user_email'] : '',
        'usr_dateembauche' => (isset($data['user_dateembauche'])) ? $data['user_dateembauche'] : '',
        
        'usr_username' => (isset($data['user_identifiant'])) ? $data['user_identifiant'] : '',
        'usr_password' => (isset($data['user_password'])) ? md5($data['user_password']) : '',
        'usr_ingress' => (isset($data['user_ingress'])) ? $data['user_ingress'] : '',
        'usr_droitpermission' => DROIT_PERMISSION,
        'usr_datecrea' => date('Y-m-d H:i:s'),
        'usr_datemodif' => date('Y-m-d H:i:s')

      );
     

      if(isset($data['user_role']) && $data['user_role'] != ""){
        $entry['usr_role'] = $data['user_role'];

      }

      if(isset($data['user_site']) && $data['user_site'] != ""){
        $entry['usr_site'] = $data['user_site'];

      }

      if(isset($data['user_contrat']) && $data['user_contrat'] != ""){
        $entry['usr_contrat'] = $data['user_contrat'];

      }

      if($this->db->insert($this->_table, $entry)) return $this->db->insert_id();

        return false;
    }

    /*
    * enregistrer la campagne affecté à l'utilisateur
    */
    public function insertUserCampagne($id, $data)
    {
        //delete all campagne for user_role
          $this->db
                ->where('usercampagne_user', $id)
                ->delete('t_usercampagne');

          //Insert datas
          if(!empty($data)){
            foreach($data as $k => $campagne_id){
              $entry[] = array(
                'usercampagne_user' => $id,
                'usercampagne_campagne' => $campagne_id,
                'usercampagne_datecrea' => date('Y-m-d H:i:s'),
                'usercampagne_datemodif' => date('Y-m-d H:i:s')
              );
            }
            
            $query = $this->db
                            ->insert_batch('t_usercampagne', $entry);

            return $this->db->insert_id();
          }
          
          return false;
    }

 	public function deleteUserCampagne($id)
    {
      $this->db
      ->where('usercampagne_user', $id)
      ->delete('t_usercampagne');

      return true;

    }
 

    /*
    * enregistrer le service affecté à l'utilisateur
    */

    public function insertUserservice($id, $data){
      //delete all service for user_role
      $this->db
            ->where('userservice_user', $id)
            ->delete('t_userservice');

      //Insert datas
      if(!empty($data)){
        foreach($data as $k => $campagne_id){
          $entry[] = array(
            'userservice_user' => $id,
            'userservice_service' => $campagne_id,
            'userservice_datecrea' => date('Y-m-d H:i:s'),
            'userservice_datemodif' => date('Y-m-d H:i:s')
          );
        }
        
        $query = $this->db
                        ->insert_batch('t_userservice', $entry);

        return $this->db->insert_id();
      }
      
      return false;
    }  

	public function deleteUserService($id){
      $this->db
      ->where('userservice_user', $id)
      ->delete('t_userservice');


      return true;

    } 

    /*
    * get user 
    */
    public function getUser($id){
      $this->db->join('tr_role', 'role_id = usr_role', 'inner');
      return $this->db->get_where('tr_user', array('usr_id' => $id))->row();

    }

    /**
     * prendre la campagne affecté à un utilisateur 
     */
    public function getUserCampagne($user_id){
        $query = $this->db->where('usercampagne_user', $user_id)
                      ->select('campagne_libelle, usr_id, campagne_id')
                      ->join('tr_campagne', 'campagne_id = usercampagne_campagne','inner')
                      ->join('tr_user', 'usercampagne_user = usr_id', 'inner')
                      ->from('t_usercampagne')
                      ->get();
        return $query->result();
    }
     /**
     * prendre le service affecté à un utilisateur 
     */
    public function getUserService($user_id){
        $query = $this->db->where('userservice_user', $user_id)
                      ->select('service_libelle, usr_id, service_id')
                      ->join('tr_service', 'service_id = userservice_service','inner')
                      ->join('tr_user', 'userservice_user = usr_id', 'inner')
                      ->from('t_userservice')
                      ->get();
        return $query->result();
    }

    public function getUsersByService($serviceID){
      $query = $this->db->where('userservice_service', $serviceID)
                          ->join('tr_service', 'service_id = userservice_service','inner')
                          ->join('tr_user', 'userservice_user = usr_id', 'inner')
                          ->join('tr_site', 'site_id = usr_site', 'inner')
                          ->from('t_userservice')
                          ->where('usr_actif', 1)
                          ->where_not_in('usr_role ', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_CADRE2, ROLE_DIRECTION, ROLE_COSTRAT, ROLE_CADRE, ROLE_SUP, ROLE_REPORTING, ROLE_CLIENT])
                          ->get();
      return $query->result();
    }

    /**
     * Modififier un utilisateur
     */

    public function updateUser($data){
        $entry = array(
              
              'usr_nom' => (isset($data['edit_user_nom'])) ? $data['edit_user_nom'] : '',
              'usr_prenom' => (isset($data['edit_user_prenom'])) ? $data['edit_user_prenom'] : '',
              'usr_matricule' => (isset($data['edit_user_matricule'])) ? $data['edit_user_matricule'] : '',
              'usr_initiale' => (isset($data['edit_user_initiale'])) ? $data['edit_user_initiale'] : '',
              'usr_email' => (isset($data['edit_user_mail'])) ? $data['edit_user_mail'] : '',
              'usr_dateembauche' => (isset($data['edit_user_dateembauche'])) ? $data['edit_user_dateembauche'] : '',
              'usr_ingress' => (isset($data['edit_user_ingress'])) ? $data['edit_user_ingress'] : '',
              'usr_datemodif' => date('Y-m-d H:i:s'),
              'usr_password' => (empty($data['edit_user_password'])) ?  ($data['edit_user_password4']) : md5($data['edit_user_password']),


        );


        if(isset($data['edit_user_role']) && $data['edit_user_role'] != ""){
          $entry['usr_role'] = $data['edit_user_role'];

        }

        if(isset($data['edit_user_site']) && $data['edit_user_site'] != ""){
          $entry['usr_site'] = $data['edit_user_site'];

        }

         if(isset($data['edit_user_contrat']) && $data['edit_user_contrat'] != ""){
          $entry['usr_contrat'] = $data['edit_user_contrat'];

        }

        $this->db->where('usr_id', $data['edit_user_id'])
                  ->update($this->_table, $entry);

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }


    /**
     * importer les utilisateurs dans la table tr_user
     */
    public function importUsers($insert_datas){

    foreach ($insert_datas as $key => $insert){

     $campagnes = $insert['campagnes'];
     $services = $insert['services'];

      $data_to_insert = array(
        'usr_username' => $insert['usr_username'],
        'usr_nom' => $insert['usr_nom'],
        'usr_prenom' => $insert['usr_prenom'],
        'usr_password' => $insert['usr_password'],
        'usr_matricule' => $insert['usr_matricule'],
        'usr_initiale' => $insert['usr_initiale'],
        'usr_email' => $insert['usr_email'],
        'usr_ingress' => $insert['usr_ingress'],
        'usr_dateembauche' => $insert['usr_dateembauche'],
        'usr_role' => $insert['usr_role'],
        'usr_actif' => $insert['usr_actif'],
        'usr_site' => $insert['usr_site'],
        'usr_contrat' => $insert['usr_contrat'],
        'usr_datecrea' => $insert['usr_datecrea'],
        'usr_datemodif' => $insert['usr_datemodif']
      );
      $this->db->insert($this->_table,$data_to_insert);

      if($id = $this->db->insert_id()){
        //Insertion dans la table t_usercampagne et t_userservice
        if(!empty($campagnes)){
          foreach($campagnes as $cp){
            $data = array(
              'usercampagne_user' => $id,
              'usercampagne_campagne' => $cp['usercampagne_campagne'],
              'usercampagne_datecrea' => date('Y-m-d H:i:s'),
              'usercampagne_datemodif' => date('Y-m-d H:i:s')
            );
            $this->db->insert('t_usercampagne', $data);
          }
        }

        if(!empty($services)){
          foreach($services as $srv){
            $data = array(
              'userservice_user' => $id,
              'userservice_service' => $srv['userservice_service'],
              'userservice_datecrea' => date('Y-m-d H:i:s'),
              'userservice_datemodif' => date('Y-m-d H:i:s')
            );
            $this->db->insert('t_userservice', $data);
          }
        }
      }

    }

     return true;
  }

    /**
     * Désactiver un utilisateur
     * Modifier usr_actif à 0
     */
    public function desactivateUser($user_id){

        $this->db->where('usr_id', $user_id)
                  ->update($this->_table, array('usr_actif' => 0));

        if($this->db->affected_rows() > 0){
          return true;
        }

        return false;
    }

    public function getListAgentByCampagne($listCampagne)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('usercampagne_campagne', $listCampagne)
                ->where('usr_role', ROLE_AGENT)
                ->join('tr_user', 'usercampagne_user = usr_id', 'inner');

        return $this->db->get('t_usercampagne')->result();

    }

    public function getListAgentByService($listService)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('userservice_service', $listService)
                ->where('usr_role', ROLE_AGENT)
                ->join('tr_user', 'userservice_user = usr_id', 'inner');

        return $this->db->get('t_userservice')->result();

    }

    public function getListUserByCampagne($listCampagne)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('usercampagne_campagne', $listCampagne)
                //->where('usr_role', ROLE_AGENT)
                ->join('tr_user', 'usercampagne_user = usr_id', 'inner');

        return $this->db->get('t_usercampagne')->result();

    }

    public function getListUserByService($listService)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('userservice_service', $listService)
                //->where('usr_role', ROLE_AGENT)
                ->join('tr_user', 'userservice_user = usr_id', 'inner');

        return $this->db->get('t_userservice')->result();

    }

    public function getPresenceUser($filtre)
    {
      $day = (isset($filtre['day']) && !empty($filtre['day'])) ? $filtre['day'] : false;
      $user = (isset($filtre['user']) && !empty($filtre['user'])) ? $filtre['user'] : false;
      $userPoids = (isset($filtre['user_poids']) && !empty($filtre['user_poids'])) ? $filtre['user_poids'] : false;
      $listCampagne = (isset($filtre['list_campagne']) && !empty($filtre['list_campagne'])) ? $filtre['list_campagne'] : false;
      $listService = (isset($filtre['list_service']) && !empty($filtre['list_service'])) ? $filtre['list_service'] : false;
      $whereSubQuery1 = $whereSubQuery2 = false;

      if($listCampagne !== FALSE){

        $this->db->select('usercampagne_user')
                      ->from('t_usercampagne')
                      ->join('tr_user', 'usr_id = usercampagne_user')
                      ->join('tr_role', 'role_id = usr_role')
                      ->where_in('usercampagne_campagne', $listCampagne);
          if($userPoids) $this->db->where('role_poids <', $userPoids);
          $whereSubQuery1 = $this->db->get_compiled_select();
      }

      if($listService !== FALSE){
        $this->db->select('userservice_user')
                    ->from('t_userservice')
                    ->join('tr_user', 'usr_id = userservice_user')
                    ->join('tr_role', 'role_id = usr_role')
                    ->where_in('userservice_service', $listService);
        if($userPoids) $this->db->where('role_poids <', $userPoids);
        $whereSubQuery2 = $this->db->get_compiled_select();
      }

      $this->db
          ->select('site_libelle, usr_prenom, usr_matricule, usr_initiale')
          ->select("(SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS list_campagne", FALSE)
          ->select("(SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS list_service", FALSE)    
          ->select("CASE WHEN shift_begin IS NOT NULL THEN 1 ELSE CASE WHEN pointage_in IS NOT NULL THEN 1 ELSE 0 END END AS presence", FALSE)  
          ->select('typeconge_libelle, motifpresence_incomplet, motifpresence_motif')
          ->from('tr_user')
          ->join('tr_site','site_id = usr_site', 'inner')
          ->join('t_shift', "shift_userid = usr_id AND shift_day = '$day'", 'left')
          ->join('t_pointage', "pointage_user = usr_id AND pointage_date = '$day'", 'left')
          ->join('t_conge', "conge_user = usr_id AND DATE(conge_datedebut) <= '$day' AND DATE(conge_datefin) >= '$day'", 'left')
          ->join('t_motifpresence', "motifpresence_day = '$day'", 'left')
          ->join('tr_typeconge', 'typeconge_id = conge_type', 'left')
          ->where('usr_actif', 1)
          ;

      $whereIn = '';
      if($whereSubQuery1 !== false){
        $whereIn = "usr_id IN ($whereSubQuery1)";
      }
      if($whereSubQuery2 !== false){
        if($whereIn) $whereIn .= ' OR ';
        $whereIn .= "usr_id IN ($whereSubQuery2)";
      }
      if($whereIn){
        $this->db->where("($whereIn)", NULL, FALSE);
      }

      $query = $this->db->get();
      //echo $this->db->last_query(); die;
      if($query->num_rows() > 0){
          return $query->result();
      }

      return false;

    }

    /**
     * OLD
     */
   /* public function getPresenceAgents($day, $listCampagne, $listService)
    {
      $sql_query = '';

      if($listCampagne){
          $sql_query = "( SELECT usr_id, usr_nom, usr_prenom, usr_username, usr_matricule, usr_pseudo,
            usr_initiale, usr_site, usr_contrat, usr_actif, site_libelle,'" . $day . "' as day,  role_id, role_libelle, shift_id, shift_day, shift_begin, shift_end, shift_loggedin, 
          motifpresence_incomplet,
          motifpresence_motif,
           motif_id,
            motif_libelle,
            CASE
              WHEN shift_begin IS NOT NULL THEN
                1 
              ELSE
                CASE 
                  WHEN pointage_in IS NOT NULL THEN
                    1
                  ELSE
                    0
                  END
            END AS presence,
            TIMEDIFF( shift_end, shift_begin ) AS total_time,
            (
            SELECT
              SEC_TO_TIME(
              SUM( CASE WHEN pause_begin IS NULL OR pause_end IS NULL THEN NULL ELSE TIME_TO_SEC( TIMEDIFF( pause_end, pause_begin )) END )) AS pause 
            FROM
              t_pause 
            WHERE
              pause_shift = shift_id 
            ) AS total_pause,
            TIMEDIFF(
              TIMEDIFF( shift_end, shift_begin ),
              (
              SELECT
                SEC_TO_TIME(
                SUM( CASE WHEN pause_begin IS NULL OR pause_end IS NULL THEN NULL ELSE TIME_TO_SEC( TIMEDIFF( pause_end, pause_begin )) END )) AS pause 
              FROM
                t_pause 
              WHERE
                pause_shift = shift_id 
              )) AS total_work,
              (SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS list_campagne,
              (SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS list_service,
              typeconge_libelle,
              planning_off,
              conge_id
          FROM
            t_usercampagne
            INNER JOIN tr_user ON usr_id = usercampagne_user
            INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne
            INNER JOIN tr_role ON role_id = usr_role
            INNER JOIN tr_site ON site_id = usr_site
            LEFT JOIN t_shift ON shift_userid = usr_id AND shift_day = '" . $day . "'
            LEFT JOIN t_pointage ON pointage_user = usr_id AND pointage_date = '" . $day . "'  
            LEFT JOIN t_motifpresence ON motifpresence_day = '" . $day . "' AND motifpresence_agent = usr_id
            LEFT JOIN tr_motif ON motif_id = motifpresence_motif
            LEFT JOIN t_conge ON conge_user = usr_id AND DATE(conge_datedebut) <= '" . $day . "' AND DATE(conge_datefin) >= '" . $day . "' 
            LEFT JOIN tr_typeconge ON typeconge_id = conge_type
            LEFT JOIN t_planning ON planning_user = usr_id AND planning_date = '" . $day . "'
          WHERE
            campagne_id IN ( " . $listCampagne . " ) 
            AND usr_actif = 1
            AND role_id != 1
            AND role_id != 5
            AND role_id != 6
            AND role_id != 7
            AND role_id != 8
            AND role_id != 9
            GROUP BY usr_id
          )"; 
      }

      if($listService){

        if($sql_query != "") $sql_query .= " UNION ";
        
        $sql_query .= "(
          SELECT
            usr_id,
            usr_nom,
            usr_prenom,
            usr_username,
            usr_pseudo,
            usr_matricule,
            usr_initiale,
            usr_site,
            usr_contrat,
            usr_actif,
            site_libelle,
            '" . $day . "' as day,
            role_id,
            role_libelle,
            shift_id,
            shift_day,
            shift_begin,
            shift_end,
            shift_loggedin,
            motifpresence_incomplet,
            motifpresence_motif,
            motif_id,
            motif_libelle,
            CASE
              WHEN shift_begin IS NOT NULL THEN
                1 
              ELSE
                CASE 
                  WHEN pointage_in IS NOT NULL THEN
                    1
                  ELSE
                    0
                  END
            END AS presence,
            TIMEDIFF( shift_end, shift_begin ) AS total_time,
            (
            SELECT
              SEC_TO_TIME(
              SUM( CASE WHEN pause_begin IS NULL OR pause_end IS NULL THEN NULL ELSE TIME_TO_SEC( TIMEDIFF( pause_end, pause_begin )) END )) AS pause 
            FROM
              t_pause 
            WHERE
              pause_shift = shift_id 
            ) AS total_pause,
            TIMEDIFF(
              TIMEDIFF( shift_end, shift_begin ),
              (
              SELECT
                SEC_TO_TIME(
                SUM( CASE WHEN pause_begin IS NULL OR pause_end IS NULL THEN NULL ELSE TIME_TO_SEC( TIMEDIFF( pause_end, pause_begin )) END )) AS pause 
              FROM
                t_pause 
              WHERE
                pause_shift = shift_id 
              )) AS total_work,
              (SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS list_campagne,
              (SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS list_service,
              typeconge_libelle,
              planning_off,
              conge_id
          FROM
            t_userservice
            INNER JOIN tr_user ON usr_id = userservice_user
            INNER JOIN tr_service ON service_id = userservice_service
            INNER JOIN tr_role ON role_id = usr_role
            INNER JOIN tr_site ON site_id = usr_site 
            LEFT JOIN t_shift ON shift_userid = usr_id AND shift_day = '" . $day . "' 
            LEFT JOIN t_pointage ON pointage_user = usr_id AND pointage_date = '" . $day . "' 
            LEFT JOIN t_motifpresence ON motifpresence_day = '" . $day . "' AND motifpresence_agent = usr_id
            LEFT JOIN tr_motif ON motif_id = motifpresence_motif
            LEFT JOIN t_conge ON conge_user = usr_id AND DATE(conge_datedebut) <= '" . $day . "' AND DATE(conge_datefin) >= '" . $day . "' 
            LEFT JOIN tr_typeconge ON typeconge_id = conge_type
            LEFT JOIN t_planning ON planning_user = usr_id AND planning_date = '" . $day . "'
          WHERE
            service_id IN ( " . $listService . " ) 
            AND usr_actif = 1
            AND role_id != 5
            AND role_id != 6
            AND role_id != 7
            AND role_id != 8
            AND role_id != 9
         GROUP BY usr_id
          )";

      }

      $query = $this->db->query($sql_query);
      //echo($sql_query); die;
      return $query->result();
    }*/


    public function getPresenceAgents($day, $listCampagne, $listService, $userRole = false)
    {
      $sql_query = '';

      //condition pour role
      $where_role = " AND role_id != 1 AND role_id != 5 AND role_id != 6 AND role_id != 7 AND role_id != 8 ";
      if($userRole !== false){
        if($userRole == ROLE_CLIENT)
          $where_role = " AND (role_id = 2 OR role_id = 3) ";
      }

      if($listCampagne){
          $sql_query = "( SELECT usr_id, usr_nom, usr_prenom, usr_username, usr_matricule, usr_pseudo,
            usr_initiale, usr_site, usr_contrat, usr_actif, site_libelle,'" . $day . "' as day,  role_id, role_libelle, shift_id, shift_day, shift_begin, shift_end, shift_loggedin, 
          motifpresence_incomplet,
          motifpresence_motif,
           motif_id,
            motif_libelle,
            CASE
              WHEN shift_begin IS NOT NULL THEN
                1 
              ELSE
                CASE 
                  WHEN pointage_in IS NOT NULL THEN
                    1
                  ELSE
                    0
                  END
            END AS presence,
            TIMEDIFF( shift_end, shift_begin ) AS total_time,
            (
            SELECT
              SEC_TO_TIME(
              SUM( CASE WHEN pause_begin IS NULL OR pause_end IS NULL THEN NULL ELSE TIME_TO_SEC( TIMEDIFF( pause_end, pause_begin )) END )) AS pause 
            FROM
              t_pause 
            WHERE
              pause_shift = shift_id 
            ) AS total_pause,
            TIMEDIFF(
              TIMEDIFF( shift_end, shift_begin ),
              (
              SELECT
                SEC_TO_TIME(
                SUM( CASE WHEN pause_begin IS NULL OR pause_end IS NULL THEN NULL ELSE TIME_TO_SEC( TIMEDIFF( pause_end, pause_begin )) END )) AS pause 
              FROM
                t_pause 
              WHERE
                pause_shift = shift_id 
              )) AS total_work,
              (SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS list_campagne,
              (SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS list_service,
              typeconge_libelle,
              planning_off,
              conge_id
          FROM
            t_usercampagne
            INNER JOIN tr_user ON usr_id = usercampagne_user
            INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne
            INNER JOIN tr_role ON role_id = usr_role
            INNER JOIN tr_site ON site_id = usr_site
            LEFT JOIN t_shift ON shift_userid = usr_id AND shift_day = '" . $day . "'
            LEFT JOIN t_pointage ON pointage_user = usr_id AND pointage_date = '" . $day . "'  
            LEFT JOIN t_motifpresence ON motifpresence_day = '" . $day . "' AND motifpresence_agent = usr_id
            LEFT JOIN tr_motif ON motif_id = motifpresence_motif
            LEFT JOIN t_conge ON conge_user = usr_id AND DATE(conge_datedebut) <= '" . $day . "' AND DATE(conge_datefin) >= '" . $day . "' 
            LEFT JOIN tr_typeconge ON typeconge_id = conge_type
            LEFT JOIN t_planning ON planning_user = usr_id AND planning_date = '" . $day . "'
          WHERE
            campagne_id IN ( " . $listCampagne . " ) 
            AND usr_actif = 1 " . $where_role . "
            GROUP BY usr_id
          )"; 
      }

      if($listService){

        if($sql_query != "") $sql_query .= " UNION ";
        
        $sql_query .= "(
          SELECT
            usr_id,
            usr_nom,
            usr_prenom,
            usr_username,
            usr_pseudo,
            usr_matricule,
            usr_initiale,
            usr_site,
            usr_contrat,
            usr_actif,
            site_libelle,
            '" . $day . "' as day,
            role_id,
            role_libelle,
            shift_id,
            shift_day,
            shift_begin,
            shift_end,
            shift_loggedin,
            motifpresence_incomplet,
            motifpresence_motif,
            motif_id,
            motif_libelle,
            CASE
              WHEN shift_begin IS NOT NULL THEN
                1 
              ELSE
                CASE 
                  WHEN pointage_in IS NOT NULL THEN
                    1
                  ELSE
                    0
                  END
            END AS presence,
            TIMEDIFF( shift_end, shift_begin ) AS total_time,
            (
            SELECT
              SEC_TO_TIME(
              SUM( CASE WHEN pause_begin IS NULL OR pause_end IS NULL THEN NULL ELSE TIME_TO_SEC( TIMEDIFF( pause_end, pause_begin )) END )) AS pause 
            FROM
              t_pause 
            WHERE
              pause_shift = shift_id 
            ) AS total_pause,
            TIMEDIFF(
              TIMEDIFF( shift_end, shift_begin ),
              (
              SELECT
                SEC_TO_TIME(
                SUM( CASE WHEN pause_begin IS NULL OR pause_end IS NULL THEN NULL ELSE TIME_TO_SEC( TIMEDIFF( pause_end, pause_begin )) END )) AS pause 
              FROM
                t_pause 
              WHERE
                pause_shift = shift_id 
              )) AS total_work,
              (SELECT GROUP_CONCAT(campagne_libelle) FROM t_usercampagne INNER JOIN tr_campagne ON campagne_id = usercampagne_campagne WHERE usercampagne_user = usr_id) AS list_campagne,
              (SELECT GROUP_CONCAT(service_libelle) FROM t_userservice INNER JOIN tr_service ON service_id = userservice_service WHERE userservice_user = usr_id) AS list_service,
              typeconge_libelle,
              planning_off,
              conge_id
          FROM
            t_userservice
            INNER JOIN tr_user ON usr_id = userservice_user
            INNER JOIN tr_service ON service_id = userservice_service
            INNER JOIN tr_role ON role_id = usr_role
            INNER JOIN tr_site ON site_id = usr_site 
            LEFT JOIN t_shift ON shift_userid = usr_id AND shift_day = '" . $day . "' 
            LEFT JOIN t_pointage ON pointage_user = usr_id AND pointage_date = '" . $day . "' 
            LEFT JOIN t_motifpresence ON motifpresence_day = '" . $day . "' AND motifpresence_agent = usr_id
            LEFT JOIN tr_motif ON motif_id = motifpresence_motif
            LEFT JOIN t_conge ON conge_user = usr_id AND DATE(conge_datedebut) <= '" . $day . "' AND DATE(conge_datefin) >= '" . $day . "' 
            LEFT JOIN tr_typeconge ON typeconge_id = conge_type
            LEFT JOIN t_planning ON planning_user = usr_id AND planning_date = '" . $day . "'
          WHERE
            service_id IN ( " . $listService . " ) 
            AND usr_actif = 1 AND usr_actif = 1 " . $where_role . "
            GROUP BY usr_id
          )";

      }

      $query = $this->db->query($sql_query);
      //echo($sql_query); die;
      return $query->result();
    }


    public function getListSupByCampagne($listCampagne)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('usercampagne_campagne', $listCampagne)
                ->where('usr_role', ROLE_SUP)
                ->join('tr_user', 'usercampagne_user = usr_id', 'inner');

        return $this->db->get('t_usercampagne')->result();

    }

    public function getListCadreByCampagne($listCampagne)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('usercampagne_campagne', $listCampagne)
                ->where('usr_role', ROLE_CADRE)
                ->join('tr_user', 'usercampagne_user = usr_id', 'inner');

        return $this->db->get('t_usercampagne')->result();

    }


    public function getListCadre2ByCampagne($listCampagne)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('usercampagne_campagne', $listCampagne)
                ->where('usr_role', ROLE_CADRE2)
                ->join('tr_user', 'usercampagne_user = usr_id', 'inner');

        return $this->db->get('t_usercampagne')->result();

    }


    public function getListDirectionByCampagne($listCampagne)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('usercampagne_campagne', $listCampagne)
                ->where('usr_role', ROLE_DIRECTION)
                ->join('tr_user', 'usercampagne_user = usr_id', 'inner');

        return $this->db->get('t_usercampagne')->result();

    }

    public function getListCostratByCampagne($listCampagne)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('usercampagne_campagne', $listCampagne)
                ->where('usr_role', ROLE_COSTRAT)
                ->join('tr_user', 'usercampagne_user = usr_id', 'inner');

        return $this->db->get('t_usercampagne')->result();

    }


    public function getListSupByService($listService)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('userservice_service', $listService)
                ->where('usr_role', ROLE_SUP)
                ->join('tr_user', 'userservice_user = usr_id', 'inner');

        return $this->db->get('t_userservice')->result();

    }

    public function getListCadreByService($listService)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('userservice_service', $listService)
                ->where('usr_role', ROLE_CADRE)
                ->join('tr_user', 'userservice_user = usr_id', 'inner');

        return $this->db->get('t_userservice')->result();

    }


    public function getListCadre2ByService($listService)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('userservice_service', $listService)
                ->where('usr_role', ROLE_CADRE2)
                ->join('tr_user', 'userservice_user = usr_id', 'inner');

        return $this->db->get('t_userservice')->result();

    }


    public function getListDirectionByService($listService)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('userservice_service', $listService)
                ->where('usr_role', ROLE_DIRECTION)
                ->join('tr_user', 'userservice_user = usr_id', 'inner');

        return $this->db->get('t_userservice')->result();

    }

    public function getListCostratByService($listService)
    {
        $this->db->select('usr_id, usr_username, usr_nom, usr_prenom, usr_role, usr_actif, usr_site, usr_contrat, usr_matricule')
                ->where_in('userservice_service', $listService)
                ->where('usr_role', ROLE_COSTRAT)
                ->join('tr_user', 'userservice_user = usr_id', 'inner');

        return $this->db->get('t_userservice')->result();

    }


    public function getAllSoldesDroits($active= true)
    {
        $this->db->select('usr_id, usr_nom, usr_prenom, usr_matricule, usr_initiale, usr_email, usr_dateembauche, usr_contrat, usr_site, usr_soldeconge, usr_droitpermission')
              ->select('contrat.site_libelle AS contrat_libelle, site.site_libelle AS site_libelle')
              ->join('tr_site AS contrat', 'usr_contrat = contrat.site_id')
              ->join('tr_site AS site', 'usr_site = site.site_id')
              ->where_not_in('usr_role', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_REPORTING, ROLE_CLIENT])
              ->order_by('usr_nom ASC');
                    
        if($active){
            $this->db->where('usr_actif', 1);
        }
        $query = $this->db->get('tr_user');

        if($query->num_rows() > 0){
            return $query->result();
        }
        return false;
    }

    public function getSoldesDroitsUser($user)
    {
        $this->db->select('usr_soldeconge, usr_droitpermission')
                ->where('usr_id', $user);
                    
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->row();
        }
        return false;
    }

    public function updateSoldesDroits($user, $soldes, $droits)
    {
        $entry = [];
        if($soldes) $entry['usr_soldeconge'] = $soldes;
        if($droits) $entry['usr_droitpermission'] = $droits;

        $this->db->update($this->_table, $entry, array('usr_id' => $user));
        if($this->db->affected_rows() > 0) return true;

        return false;
    }

    public function getSoldeCongeUser($user, $active = true)
    {
        $this->db->select('usr_soldeconge')
                ->where('usr_id', $user);
                    
        if($active){
            $this->db->where('usr_actif', 1);
        }
        $query = $this->db->get($this->_table);

        if($query->num_rows() > 0){
            return $query->row()->usr_soldeconge;
        }
        return false;
    }

    public function addValueToSoldeConge($user, $soldeToAdd){
      
      $this->db->set('usr_soldeconge', 'IF(usr_soldeconge IS NULL, '.floatval($soldeToAdd).', usr_soldeconge+'.floatval($soldeToAdd).')', FALSE)
                ->where('usr_id', $user)
                ->where_not_in('usr_role', [ROLE_ADMIN, ROLE_ADMINRH, ROLE_REPORTING, ROLE_CLIENT])
                ->update($this->_table);
      //echo $this->db->last_query();
      if($this->db->affected_rows() > 0) return true;

      return false;                
    }
    public function verifIfExist($id_user){
      $this->db->where("usr_id", $id_user);
      $data = $this->db->get('t_userimage');
      if($data->num_rows()>0){
        return true;
      }
      return false;
    }

    //Upload ou update l'image de l'utilisateur
    public function uploadImage($id_user, $fileName){
      if(!$this->verifIfExist($id_user)){
        $this->db->insert("t_userimage", ['usr_id'=>$id_user, 'usr_filenameimg'=>$fileName]);
        return 0;
      }
      else {
        try{
          $this->db->where('usr_id', $id_user);
          $this->db->update('t_userimage', ['usr_filenameimg'=>$fileName]);
          return 0;
        } catch(Exception $e1){
          return 1;
        }

      }
      
    }

    //Avoir l'image de l'utilisateur
    public function getImage($id_user){
      $this->db->where('usr_id', $id_user);
      $data = $this->db->get("t_userimage");
      if($data->num_rows() > 0){
        $row = $data->row()->usr_filenameimg;
        $data->free_result();
        return $row;
      }
      return null;
    }

    
}
