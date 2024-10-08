<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prime extends MY_Controller {
    /*
    * Afficher la liste des primes
    */
    public function listPrime()
    {
        $header = ['pageTitle' => 'Gestion des prime - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('prime/listprime', []);

        $this->load->view('common/footer', []);
    }

    public function primejournaliere()
    {
        $header = ['pageTitle' => 'Gestion des prime - TimeTracking'];
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('prime/listprimeJournaliere', []);

        $this->load->view('common/footer', []);
    }

    public function savePrime()
    {
        $data = [
            'user' => 46,
            'basemensuelle' => '',

        ];
        
    }   

}