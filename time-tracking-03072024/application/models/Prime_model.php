<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prime_model extends CI_Model {

    private $_id,
            $_user,
            $_campagne,
            $_mois,
            $_annee,
            $_basemensuelle,
            $_datecrea,
            $_datemodif,
            $_data = array(),
            $_table = 't_prime',
            $_prefix = 'prime_'
            ;

    public function __construct($datas = false) 
    {
        parent::__construct();

        if(is_array($datas)){
            if(isset($datas['id']))
                $primeDatas = $this->getPrimeById($datas['id']);
            else 
                $primeDatas = $datas;
            
            if($primeDatas !== false) $this->setPrimeData($primeDatas);
        }else if(!empty($datas)){
            $primeDatas = $this->getPrimeById($datas);
            if($primeDatas !== false) $this->setPrimeData($primeDatas);
        }
    }

    public function setPrimeData($datas)
    {
        
        if(is_array($datas) && !empty($datas)){
            foreach($datas as $key => $value){
                $varName = '_' . $key;
                $this->$varName = $value;;
            }
        }
    }

    public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getUser()
    {
        return $this->_user;
    }

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getCampagne()
    {
        return $this->_campagne;
    }

    public function setCampagne($campagne)
    {
        $this->_campagne = $campagne;
    }

    public function getBaseMensuelle()
    {
        return $this->_basemensuelle;
    }

    public function setBaseMensuelle($baseMensuelle)
    {
        $this->_basemensuelle = $baseMensuelle;
    }

    public function getMois()
    {
        return $this->_mois;
    }

    public function setMois($mois)
    {
        $this->_mois = $mois;
    }

    public function getAnnee()
    {
        return $this->_annee;
    }

    public function setAnnee($annee)
    {
        $this->_annee = $annee;
    }


    public function save()
    {
        $retour = false;
        //First check if prime data exists
        if($this->_exists($this->_id) || $this->_existsData()){
            //Update
            $retour = $this->_update();
        }else{
            //Insert
            $retour = $this->_insert();
        }   

        return $retour;
    }

    public function getPrimeById()
    {
        if(!$this->_id) return false;

        $this->db->select('prime_id, prime_user, prime_mois, prime_annee, prime, actif, prime_datecrea, prime_datemodif')
                ->where('prime_id', $this->_id)
                ;
        $query = $this->db->get($this->_table);

        if($query->num_rows() == 1){
            return $query->row();
        }
        return false;
    }
    
    public function saveBatch($data)
    {
        return $this->db->insert_batch($this->_table, $data);
    }

    private function _exists()
    {
        if($this->getPrimeById($this->_id) !== FALSE) return true;
        return false;
    }

    private function _existsData()
    {
        $this->db->select('prime_id')
                ->where('prime_mois', $this->_mois)
                ->where('prime_annee', $this->_annee)
                ->where('prime_user', $this->_user)
                ->where('prime_campagne', $this->_campagne)
                ;
        $query = $this->db->get($this->_table);
        if($query->num_rows() > 0){
            $this->setId($query->row()->prime_id);
            return true;
        }
        return false;
    }

    private function _insert()
    {
        $data = [
            'prime_user' => $this->_user,
            'prime_campagne' => $this->_campagne,
            'prime_mois' => $this->_mois,
            'prime_annee' => $this->_annee,
            'prime_basemensuelle' => $this->_basemensuelle,
            'prime_datecrea' => date('Y-m-d H:i:s'),
            'prime_datemodif' => date('Y-m-d H:i:s')
        ];
        if($this->db->insert($this->_table, $data)) return $this->db->insert_id();
        return false;
    }
    
    private function _update()
    {
        $data = [
            'prime_user' => $this->_user,
            'prime_basemensuelle' => $this->_basemensuelle,
            'prime_datemodif' => date('Y-m-d H:i:s')
        ];
        $this->db->where('prime_id', $this->_id)
                ->update($this->_table, $data);

        if($this->db->affected_rows() > 0) return true;
        return false;
    }


}

    