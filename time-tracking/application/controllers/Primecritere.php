<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Primecritere extends MY_Controller {


    public function listCritere()
    {
        
        $header = ['pageTitle' => 'Critere prime'];
                                                                
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('critere/modalcritere', ['day' => date('Y-m-d')]);

        $this->load->view('common/footer', []);
    }
    public function dircritere()
    {
        $header = ['pageTitle' => 'Critere prime'];
                                                                
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('critere/criteredirection');
        $this->load->view('common/footer', []);
    }

    public function getprimeallcritere()
    {
        $this->load->model("Primecritere_model");
        $listDonnne = $this->Primecritere_model->getallprimecritere();
        echo json_encode(array('data' => $listDonnne));

    }


    public function getprimecritere($idcampagne)
    {
        $this->load->model("Primecritere_model");
        $listDonnne = $this->Primecritere_model->getallprimecriterebycampagne($idcampagne);
        echo json_encode(array('data' => $listDonnne));

    }


    
    public function getprimedirection($idcampagne)
    {
        $this->load->model("Primecritere_model");
        $listDonnne = $this->Primecritere_model->getprimecriteredirectionbycampagne($idcampagne);
        echo json_encode(array('data' => $listDonnne));

    }

    public function insertcritere(){
        $critere = array();
        $critereaffectationtc = array();
       
        //var_dump($this->input->post()); die;
        $critere['primecritere_libelle'] = $this->input->post('critere_libelle');
        $critere['primecritere_profil'] = ($this->input->post('profil_critere') != '') ? $this->input->post('profil_critere') : null;
        $critere['primecritere_campagne'] = $this->input->post('campagne_critere');
        $critere['primecritere_process'] = ($this->input->post('prime_process') != '') ? $this->input->post('prime_process') : null;
        $critere['primecritere_typecritere'] = $this->input->post('critere_type');
        
        $critere['primecritere_modecalcul'] = $this->input->post('critere_modecalcul');
        $critere['primecritere_objectif'] = $this->input->post('critere_objectif');
        $critere['primecritere_montant'] = $this->input->post('critere_base');
        $critere['primecritere_etat'] = '1';
        $critere['primecritere_actif'] = '1';
        $critere['primecritere_datecrea'] = date('Y-m-d H:i:s');
        $critere['primecritere_datemodif'] = date('Y-m-d H:i:s');
        

    
        $this->load->model("Primecritere_model");
        $this->Primecritere_model->addcritere($critere);

        /*$dernier_id_critere = $this->db->insert_id();

        $critereaffectationtc['primeaffectationtc_type'] = $this->input->post('critere_type');
        $critereaffectationtc['primeaffectationtc_critere'] = $dernier_id_critere;

        $critereaffectationtc['primeaffectationtc_profil'] = $this->input->post('profil_critere');
        $critereaffectationtc['primeaffectationtc_process'] = $this->input->post('prime_process') ;

        $critereaffectationtc['primeaffectationtc_campagne'] = $this->input->post('campagne_critere');

        $critereaffectationtc['primeaffectationtc_datecrea'] = date('Y-m-d H:i:s');
        $critereaffectationtc['primeaffectationtc_datemodif'] = date('Y-m-d H:i:s');


        $this->Primecritere_model->addprofilcritere($critereaffectationtc);*/
        redirect('primecritere/listCritere');

     
    }
    
    public function modificationcritere($id)
    {
        $critere = array();
        $critereaffectationtc = array();

        if (!empty($this->input->post('frequence_critere'))) {
            $critere['primecritere_frequence'] = $this->input->post('frequence_critere');
        }
        
        $critere['primecritere_format'] = $this->input->post('Critere_format');
        $critere['primecritere_libelle'] = $this->input->post('critere_libelle');
        $critere['primecritere_critere'] = $this->input->post('');
        $critere['primecritere_etatcritere'] = '1';
        $critere['primecritere_actif'] = '1';
        $critere['primecritere_datecrea'] = date('Y-m-d H:i:s');
        $critere['primecritere_datemodif'] = date('Y-m-d H:i:s');
        
        $this->load->model("Primecritere_model");
        $this->Primecritere_model->addcritere($critere);
        $dernier_id_critere = $this->db->insert_id();
        $critereaffectationtc['primeaffectationtc_critere'] = $dernier_id_critere;


        

        $critereaffectationtc['primeaffectationtc_type'] = $this->input->post('profil_critere') ;

        $critereaffectationtc['primeaffectationtc_profil'] = $this->input->post('profil_critere');
        $critereaffectationtc['primeaffectationtc_process'] = $this->input->post('prime_process') ;

        $critereaffectationtc['primeaffectationtc_campagne'] = $this->input->post('campagne_critere');


        $critereaffectationtc['primeaffectationtc_datecrea'] = date('Y-m-d H:i:s');
        $critereaffectationtc['primeaffectationtc_datemodif'] = date('Y-m-d H:i:s');


        $this->Primecritere_model->addprofilcritere($critereaffectationtc);


    }

    public function applicationcriteredirection()
    {
        $id = $this->input->post("critere_id");
        $demande = array();
        $demande['primecritere_etatcritere'] = 2;
        $this->load->model('Primecritere_model');
        $updated = $this->Primecritere_model->applicationtachedirection($demande, $id);

    }

    public function desactivecriteredirection(){
        $id = $this->input->post("critere_id");
        $demande = array();

        $demande['primecritere_actif'] = 0;


        $this->load->model('Primecritere_model');
        $updated = $this->Primecritere_model->desactivationcriteredirection($demande, $id);

    }


    public function desactivecriterecadre(){
        $id = $this->input->post("critere_id");
        $demande = array();
        $demande['primecritere_etatcritere'] = 4;
        $this->load->model('Primecritere_model');
        $updated = $this->Primecritere_model->applicationtachedirection($demande, $id);

    }
    public function importcritere()
    {
        //$this->load->library('csvimport');
        $this->load->helper('file');

        $path = FCPATH . "uploads/";
 
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'csv';
        //$config['max_size'] = 1024000;
        $this->load->library('upload', $config);

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('export_file_import')) {
            $error = $this->upload->display_errors();

            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('primecritere/?msg=error');
            //echo $error['error'];
        } else {

            $file_data = $this->upload->data();
            $file_path = base_url() . "uploads/" . $file_data['file_name'];
            $row = 1;
            $insert_data = array();
            if(($handle = fopen($file_path, "r")) !== FALSE){
                while(($data = fgetcsv($handle, 1000, ";")) !== FALSE){
                    if($row > 1){
                      

                        $insert_data[] = array(
                            'primecritere_libelle' => $data[0],
                            'primecritere_format' => $data[1],
                            'primecritere_frequence' => $data[2],
                            'primecritere_objectif' => $data[3],
                            'primecritere_montant' => $data[4],
                            'primeaffectationtc_type' => $data[5],
                            'primeaffectationtc_profil' => $data[6],
                            'primeaffectationtc_proccess' => $data[7],
                            'primeaffecationtc_campagne' => $data[8],
                            'primeaffectationtc_datecrea' => date('Y-m-d H:i:s'),
                            'primeaffectationtc_datemodif' => date('Y-m-d H:i:s')
                        );
                    }
                    $row++;
                }
                fclose($handle);
                //var_dump($insert_data); die;
                $this->load->model('Primecritere_model');
                if($this->Primecritere_model->importcritere($insert_data)){
                    redirect('primecritere/listecritere?msg=succes');
                }
            }else{
                redirect('user/listecritere?msg=error');
            }
            
        }
    }


     public function nombreDeJoursOuvrables($annee, $mois, $joursFeries = []) {
        $nombreJoursOuvrables = 0;
        
        $nombreJoursDansLeMois = cal_days_in_month(CAL_GREGORIAN, $mois, $annee);
        
        for ($jour = 1; $jour <= $nombreJoursDansLeMois; $jour++) {
            $timestamp = mktime(0, 0, 0, $mois, $jour, $annee);
            
            $jourDeLaSemaine = date('N', $timestamp);
            
            if ($jourDeLaSemaine < 6) {
                $dateDuJour = date('Y-m-d', $timestamp);
                if (!in_array($dateDuJour, $joursFeries)) {
                    $nombreJoursOuvrables++;
                }
            }
        }
        
        return $nombreJoursOuvrables;
    }

    public function insertobjectifjournalier()
    {
        
        $critere = $this->input->post('objectifjournalier_critere');
        $objectifJournalier = $this->input->post('objectifjournalier_objectif');
        $data = [
            'primeobjectifjour_critere' => $critere,
            'primeobjectifjour_valeur' => $objectifJournalier,
            'primeobjectifjour_day' => date('Y-m-d'),
            'primeobjectifjour_datecrea' => date('Y-m-d H:i:s'),
            'primeobjectifjour_datemodif' => date('Y-m-d H:i:s')
        ];
        $this->load->model('primecritere_model');
        if($this->primecritere_model->addObjectifJournalier($data)) redirect('primecritere/listCritere?msg=succes');

        redirect('primecritere/listCritere?msg=error');
    }
}

?>