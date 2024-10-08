<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Batch extends CI_Controller {

    public function importCongeSetex(){

        $this->load->helper('file');
        $pathImportSetex =  FCPATH . PATH_IMPORT_CONGE . '/SETEX/';
        $outFileSetex = FCPATH . '/script/sqlImportCongeSetex.sql';

        //var_dump($pathImportSetex);
        
        $csvFiles = get_filenames($pathImportSetex);
        $sqlQuery = '';

        foreach($csvFiles as $csvFile){

            $filePath = $pathImportSetex . $csvFile;
            $fileHandle = fopen($filePath, 'r');
            if ($fileHandle !== false) {
                $posConge = false;
                
                while (($csvLine = fgets($fileHandle)) !== false) {
                    // Process the CSV line
                    $csvData = str_getcsv($csvLine, ';');
                    //var_dump($csvData);
                    //Traitement Data
                    if($posConge && !empty($csvData[0])){
                        $matricule = $csvData[0];
                        $nomPrenoms = $csvData[1];
                        $initiale = $csvData[2];
                        $reste = $csvData[$posConge];
                        $droit = $csvData[8];
                        
                        $sqlQuery .= "UPDATE tr_user SET usr_soldeconge = '" . floatval(str_replace(',', '.', $reste)) . "', usr_droitpermission = '" . floatval(str_replace(',', '.', $droit)) . "' WHERE usr_matricule = '". $matricule ."' AND usr_initiale = '" . $initiale . "';";
                    }

                    //Set En-tete
                    if($csvData[0] == 'MLE'){
                        $posConge = array_search('RESTE', $csvData);
                    }
                    
                }
                fclose($fileHandle);
            }
        }
        if($sqlQuery){
            $result = file_put_contents($outFileSetex, $sqlQuery, LOCK_EX);
        }
        if($result){
            echo "Requete SQL enregistré dans " . $outFileSetex;
        }else{
            echo "Erreur d'enregistrement de la requete SQL";
        }

    }

    public function importCongeMcr(){

        $this->load->helper('file');
        $pathImportMcr =  FCPATH . PATH_IMPORT_CONGE . '/MCR/';
        $outFileMcr = FCPATH . '/script/sqlImportCongeMcr.sql';

        //var_dump($pathImportSetex);
        
        $csvFiles = get_filenames($pathImportMcr);
        $sqlQuery = '';

        foreach($csvFiles as $csvFile){

            $filePath = $pathImportMcr . $csvFile;
            $fileHandle = fopen($filePath, 'r');
            if ($fileHandle !== false) {
                $posConge = false;
                
                while (($csvLine = fgets($fileHandle)) !== false) {
                    // Process the CSV line
                    $csvData = str_getcsv($csvLine, ';');
                    //var_dump($csvData);
                    //Traitement Data
                    if($posConge && !empty($csvData[0])){
                        $matricule = $csvData[1];
                        $nomPrenoms = $csvData[2];
                        $initiale = $csvData[0];
                        $reste = $csvData[$posConge];
                        $droit = $csvData[7];
                        
                        $sqlQuery .= "UPDATE tr_user SET usr_soldeconge = '" . floatval(str_replace(',', '.', $reste)) . "', usr_droitpermission = '" . floatval(str_replace(',', '.', $droit)) . "' WHERE usr_matricule = '". $matricule ."' AND usr_initiale = '" . $initiale . "';";
                    }

                    //Set En-tete
                    if($csvData[0] == 'INITIALES'){
                        $posConge = array_search('RESTE', $csvData);
                    }
                    
                }
                fclose($fileHandle);
            }
        }
        if($sqlQuery){
            $result = file_put_contents($outFileMcr, $sqlQuery, LOCK_EX);
        }
        if($result){
            echo "Requete SQL enregistré dans " . $outFileMcr;
        }else{
            echo "Erreur d'enregistrement de la requete SQL";
        }

    }

    public function importCongeTnl(){

        $this->load->helper('file');
        $pathImportTnl =  FCPATH . PATH_IMPORT_CONGE . '/TNL/';
        $outFileTnl = FCPATH . '/script/sqlImportCongeTnl.sql';

        //var_dump($pathImportSetex);
        
        $csvFiles = get_filenames($pathImportTnl);
        $sqlQuery = '';

        foreach($csvFiles as $csvFile){

            $filePath = $pathImportTnl . $csvFile;
            $fileHandle = fopen($filePath, 'r');
            if ($fileHandle !== false) {
                $posConge = false;
                $posDroit = false;
                
                while (($csvLine = fgets($fileHandle)) !== false) {
                    // Process the CSV line
                    $csvData = str_getcsv($csvLine, ';');
                    //var_dump($csvData);
                    //Traitement Data
                    if($posConge && $posDroit && !empty($csvData[0])){
                        $matricule = $csvData[0];
                        $nomPrenoms = $csvData[2] . ' ' . $csvData[3];
                        $initiale = $csvData[4];
                        $reste = $csvData[$posConge];
                        $droit = $csvData[$posDroit];
                        
                        $sqlQuery .= "UPDATE tr_user SET usr_soldeconge = '" . floatval(str_replace(',', '.', $reste)) . "', usr_droitpermission = '" . floatval(str_replace(',', '.', $droit)) . "' WHERE usr_matricule = '". $matricule ."' AND usr_initiale = '" . $initiale . "';";
                    }

                    //Set En-tete
                    if($csvData[2] == 'Nom'){
                        $posConge = 5;
                        $posDroit = 6;
                    }
                    
                }
                fclose($fileHandle);
            }
        }
        if($sqlQuery){
            $result = file_put_contents($outFileTnl, $sqlQuery, LOCK_EX);
        }
        if($result){
            echo "Requete SQL enregistré dans " . $outFileTnl;
        }else{
            echo "Erreur d'enregistrement de la requete SQL";
        }

    }

}
