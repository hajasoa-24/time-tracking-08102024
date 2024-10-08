<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Primeproduction extends MY_Controller {

    public function production()
    {
        $header = ['pageTitle' => 'Suivi des primes'];
        if($this->input->post('filtre_month') && $this->input->post('filtre_year')){
            $this->session->set_userdata('filtre_suiviprime', [
                'month' => $this->input->post('filtre_month'),
                'year' => $this->input->post('filtre_year'),
            ]);
        }
        $filtre = $this->session->userdata('filtre_suiviprime');
        if(!$filtre){
            $filtre = [
                'month' => date('m'),
                'year' => date('Y')
            ];
        }
        $this->load->model('primecritere_model');
        $listQualite = $this->primecritere_model->getListTypeCritere();

        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('primeproduction/primeproduction', [
            'filtre' => $filtre,
            'userId' => $this->_userId,
            'userRole' => $this->_userRole,
            'ajusteurs' => PRIME_LISTAJUSTEUR,
            'validateurs' => PRIME_LISTVALIDATEURS,
            'listQualite' => $listQualite
        ]);
        $this->load->view('common/footer', []);

    }

    public function takeprod(){
        $filter_du = $this->input->post("filterprod_du");
        $filer_au = $this->input->post("filterprod_au");
        $campagne = $this->input->post("prod_campagne");
        $profil = $this->input->post("profil");
        $mission = $this->input->post("mission");
        $process = $this->input->post("primeprofilprocess");


        $this->load->model('Etp_model');
       $result =  $this->Etp_model->findtask($filter_du,$filer_au,$campagne,$campagne,$profil,$campagne,$process);
       

    }

    public function getPrimeJournaliere($date = false)
    {
        $user = $this->_userId;
        if(!$date) $date = date('Y-m-d');
        $month = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        $this->load->model('primeproduction_model');
        //$listPrimeJournaliere = $this->primeproduction_model->getAtteinteJournaliereByDateAndUser($date, $user);
        $listPrimeJournaliere = $this->primeproduction_model->getDataFromReachRateDaysMonth($month, $year);
        echo json_encode(['data' => $listPrimeJournaliere]);
    }
    
    public function getPrimeMonth($month, $year)
    {
        $superior = $this->_userId;
        $listObjCampagne = $this->session->userdata('user')['listcampagne'];
        $listCampagne = [];
        $listService = [];
        if(is_array($listObjCampagne)){
            foreach($listObjCampagne as $campagne){
                $listCampagne[] = $campagne->campagne_id;
            }
        }
        $listObjService = $this->session->userdata('user')['listservice'];
        if(is_array($listObjService)){
            foreach($listObjService as $service){
                $listService[] = $service->service_id;
            }
        }
        //List users
        $this->load->model('user_model');
        $listUsersFromCampagne = $this->user_model->getListAgentByCampagne($listCampagne);
        $listUserCampagne = [];
        if(is_array($listUsersFromCampagne)){
            foreach($listUsersFromCampagne as $userCampagne){
                $listUserCampagne[] = $userCampagne->usr_id;
            }
        }
        $listUsersFromService = $this->user_model->getListAgentByService($listService);
        $listUserService = [];
        if(is_array($listUsersFromService)){
            foreach($listUsersFromService as $userService){
                $listUserService[] = $userService->usr_id;
            }
        }
        $listUser = array_unique(array_merge($listUserCampagne, $listUserService), SORT_REGULAR);
        $listUser[] = 46; //POUR BESOIN DES TEST
        $this->load->model('primeproduction_model');
        $listPrime = $this->primeproduction_model->getPrimeMonthData($month, $year, $listUser);

        echo json_encode(['data' => $listPrime]);
    }

    public function addBonusMalus() : void
    {
        $input = $this->input->post();
        if($input)
        {
            //var_dump($input); die;
            $entry = [
                'primeajustement_prime' => $input['primeajustement_prime'],
                'primeajustement_commentaire' => $input['primeajustement_commentaire'],
                'primeajustement_bonus' => '',
                'primeajustement_malus' => '',
                'primeajustement_ajusteur' => $this->_userId,
                'primeajustement_dateajustement' => date('Y-m-d H:i:s'),
                'primeajustement_datecrea' => date('Y-m-d H:i:s'),
                'primeajustement_datemodif' => date('Y-m-d H:i:s')
            ];
            if($input['primeajustement_type'] == PRIME_BONUS){
                $entry['primeajustement_bonus'] = $input['primeajustement_ajustement'];
            }else if($input['primeajustement_type'] == PRIME_MALUS){
                $entry['primeajustement_malus'] = $input['primeajustement_ajustement'];
            }
            $this->load->model('primeproduction_model');
            if($this->primeproduction_model->savePrimeAjustement($entry)) redirect('primeproduction/production?msg=success');
            redirect('primeproduction/production?msg=error');
        }
    }

    public function getPrimeAjustementByPrime()
    {
        $returnData = ['err' => true];
        $input = $this->input->post();
        if($input){
            $htmlData = '';
            $prime = $input['prime'];
            $this->load->model('primeproduction_model');
            $listPrimeAjustement = $this->primeproduction_model->getPrimeAjustementByPrime($prime);
            foreach($listPrimeAjustement as $primeAjustement){
                $htmlData .=    '<tr>' . 
                                    '<td>' . $primeAjustement->primeajustement_bonus . '</td>'.
                                    '<td>' . $primeAjustement->primeajustement_malus . '</td>'.
                                    '<td>' . $primeAjustement->primeajustement_commentaire . '</td>'.
                                    '<td>' . $primeAjustement->ajusteur . '</td>'.
                                    '<td>' . $primeAjustement->primeajustement_dateajustement . '</td>'.
                                    '<td><button class="btn btn-sm btn-success send-validate-ajustement" data-ajustement="' . $primeAjustement->primeajustement_id . '"><i class="fa fa-check"></i></button></td>'.
                                '</tr>';
            }
            $returnData = ['err' => false, 'htmlData' => $htmlData];
        }
        echo json_encode($returnData);
    }

    public function validerAjustement()
    {
        $this->load->model('primeproduction_model');
        $input = $this->input->post();
        if($input && isset($input['sendValidation']) && $input['sendValidation'] == 'sent'){
            $id = $input['primeAjustementToValidate'];
            $validator = $this->_userId;
            $entry = [
                'primeajustement_validateur' => $validator,
                'primeajustement_datevalidation' => date('Y-m-d H:i:s'),
                'primeajustement_datemodif' => date('Y-m-d H:i:s')
            ];
            if($this->primeproduction_model->updatePrimeAjustement($id, $entry)) 
                redirect('primeproduction/production?msg=success');

            redirect('primeproduction/production?msg=error');
        }
    }

    public function doImportQualite()
    {
        
        $type = $this->input->post('importqualite_type');
        $month = $this->input->post('importqualite_month');
        $year = $this->input->post('importqualite_year');

        $this->load->helper('file');
        $path = FCPATH . "uploads/";
 
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'csv';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('importqualite_file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('primeproduction/production?msg=error');
            //echo $error['error'];
        } else {
            $file_data = $this->upload->data();
            $file_path = base_url() . "uploads/" . $file_data['file_name'];
            $row = 1;
            $import_data = array();
            if(($handle = fopen($file_path, "r")) !== FALSE){
                while(($data = fgetcsv($handle, 1000, ";")) !== FALSE){
                    
                    if($row > 1){
                        if(isset($data[0]) && isset($data[1]) && isset($data[2]) && isset($data[3]))
                        {
                            $usr_nom = $data[0];
                            $usr_matricule = $data[1];
                            $campagne_libelle = $data[2];
                            $note = $data[3];
                            $commentaire = (isset($data[4])) ? $data[4] : '';
                            $campagne_id = false;
                            
                            $this->load->model('user_model');
                            $user = $this->user_model->getUserByNameAndMatricule($usr_nom, $usr_matricule);
    
                            if($user){
                                $this->load->model('campagne_model');
                                if($campagne_libelle){
                                    $campagne_info = $this->campagne_model->getCampagneByLib($campagne_libelle);
                                    if($campagne_info) {
                                        $campagne_id = $campagne_info->campagne_id;
                                        $import_data[] = array(
                                            'mois' => $month,
                                            'annee' => $year,
                                            'usr' => $user->usr_id,
                                            'note' => $note,
                                            'commentaire' => $commentaire,
                                            'campagne' => $campagne_id,
                                            'type' => $type
    
                                        );
                                    }  
                                }
                            }
                        }
                    }
                    $row++;
                }
                fclose($handle);
                var_dump($import_data);
                $this->load->model('primeproduction_model');
                if($this->primeproduction_model->importQualite($import_data)){
                    redirect('primeproduction/production?msg=succes');
                }
            }else{
                redirect('primeproduction/production?msg=error');
            }
            
        }
    }

}

?>