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
        if($this->input->post('filtre_month') && $this->input->post('filtre_year')){
            $this->session->set_userdata('filtre_suiviproduction', [
                'month' => $this->input->post('filtre_month'),
                'year' => $this->input->post('filtre_year'),
            ]);
        }
        $filtre = $this->session->userdata('filtre_suiviproduction');
        if(!$filtre){
            $filtre = [
                'month' => date('m'),
                'year' => date('Y')
            ];
        }

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('prime/listprimeJournaliere', [
            'filtre' => $filtre
        ]);

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