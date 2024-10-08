<?php 

defined('BASEPATH') OR exit('No direct script access allowed');
require_once  APPPATH.'../vendor/autoload.php';

class Badge extends MY_Controller {
    /**
     * to badge
     */

     public function badge(){
        $header = ['pageTitle' => 'Badge - TimeTracking'];



        $this->load->model('User_model');
        $listUtilisateur = $this->User_model->getAllUser();
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('badge/badge', array(
            'listUtilisateur' => $listUtilisateur));
        $this->load->view('badge/modaldownload', []);

        $this->load->view('common/footer', []);
    }

    /**
     * export badge
     */

    public function exportBadge($user_id=""){
        if($user_id == null){
            $user_id = $this->input->get('usr_id');
        }
        $this->load->model('User_model');
        $infosUser = $this->User_model->getUserInfos($user_id);
        $image_user = $this->User_model->getImage($user_id);


        if($image_user == null){
            $image_user = 'DEFAULT.png';
        }

        // Création de l'image vide
        $width = 636;
        $height = 966;
        $image = imagecreatetruecolor($width, $height);

        // Load des images
        
        if($infosUser->usr_site == 1 || $infosUser->usr_site == 4){
            $overlayImagePath = 'assets/img/setex.png';
        } else if($infosUser->usr_site == 2){
            $overlayImagePath = 'assets/img/monte.png';
        }
        else if($infosUser->usr_site == 3){
            $overlayImagePath = 'assets/img/tonelle.png';
        }
        $overlayImage = imagecreatefrompng($overlayImagePath);

        $pdpPath = 'IMGAG/'.$image_user;
        if(strpos($image_user, ".png") !== false){
            $pdp = imagecreatefrompng($pdpPath);
        } 
        if(strpos($image_user, ".jpeg") !== false || strpos($image_user, ".jpg") !== false ){
            $pdp = imagecreatefromjpeg($pdpPath);
        }

        $extWidth = imagesx($overlayImage);
        $extHeight = imagesy($overlayImage);
        $pdpWidth = imagesx($pdp);
        $pdpHeight = imagesy($pdp);

        $newWidth = $width;
        $newHeight = $height;

        $x_img = 0;
        $y_img = 0;

        $font = "assets/fonts/poppins/Poppins-Bold.ttf";

        imagecopyresampled($image, $overlayImage, $x_img, $y_img, 0, 0, $newWidth, $newHeight, $extWidth, $extHeight);
        imagecopyresampled($image, $pdp, ($width/2) - 300/2, 240, 0, 0, 300, 300, $pdpWidth, $pdpHeight);

        $textColor = imagecolorallocate($image, 255, 255, 255);

        // Text
        $textBoundingBox = imagettfbbox(30, 0, $font, $infosUser->usr_prenom);
        $textWidth = $textBoundingBox[2] - $textBoundingBox[0];

        imagettftext($image, 30, 0, ($width - $textWidth) / 2, 680, $textColor, $font,$infosUser->usr_prenom);

        $textBoundingBox = imagettfbbox(20, 0, $font, $infosUser->usr_initiale."-".$infosUser->usr_matricule);
        $textWidth = $textBoundingBox[2] - $textBoundingBox[0];

        imagettftext($image, 20, 0, ($width - $textWidth) / 2, 725, $textColor, $font,$infosUser->usr_initiale."-".$infosUser->usr_matricule);
        imagedestroy($overlayImage);
        imagedestroy($pdp); 
        // Download
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $infosUser->usr_matricule . '_' . $infosUser->usr_prenom . '.png"');
        imagepng($image);

        // // Vider cache
        imagedestroy($image);
    }

    //avoir la liste des utilisateurs sous les charges du SUP

    public function ListUser(){
        $header = ['pageTitle' => 'Liste de Agent - TimeTracking'];

        $this->load->model('motif_model');
        
        $this->load->view('common/header',  $header);
        $this->load->view('common/sidebar', $this->_sidebar);
        $this->load->view('common/top', array('top' => $this->_top));
        $this->load->view('badge/agent', []);
        $this->load->view('badge/modalimage');
        $this->load->view('common/footer', []);
    }

    //upload image de l'utilisateur par le sup

    public function uploadImage(){
        if (isset($_POST["submit"])) {
            $this->load->model('User_model');
            $targetDir = "IMGAG/";
            $targetFile = $targetDir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
            if (isset($_FILES["fileToUpload"])) {
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    $this->session->set_flashdata('msg', "Votre fichier n'est pas une image!");
                    $uploadOk = 0;
                }
            }
        
            if (file_exists($targetFile)) {
                $this->session->set_flashdata('msg', "Fichier déja existant!");
                $uploadOk = 0;
            }
        
            if ($_FILES["image"]["size"] > 500000) {
                $this->session->set_flashdata('msg', "Fichier trop volumineux!");
                $uploadOk = 0;
            }
        
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $this->session->set_flashdata('msg', "Type de fichier non valide! (Fichier valide .PNG)");
                $uploadOk = 0;
            }

            $newTargerFile = $this->input->post('image_agent').'_IMAGE.'.$imageFileType;
        
            if ($uploadOk == 0) {
                redirect(site_url('badge/ListUser'));
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir.$newTargerFile)) {
                    $this->User_model->uploadImage($_POST['image_agent'], $newTargerFile);
                } else {
                    $this->session->set_flashdata('msg', "UPLOAD echouer");
                }
            }
        }
        redirect(site_url('badge/ListUser'));
    }

    //Exportation de tous les utilisateurs sur un même pdf
    public function exportPdf(){
        if(!empty($_POST['donnee'])){

            ob_clean();
            
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8');
            $pdf->AddPage();
            $allSelected = $this->input->post("donnee");
            $this->load->model('User_model');
            $pdf->SetMargins(0, 20, 0);

            $badgeWidth = 636 / 12; // Largeur du badge en unités TCPDF
            $badgeHeight = 966 / 12; // Hauteur du badge en unités TCPDF
            $spacingX = 5; // Espacement horizontal entre les badges
            $spacingY = 5; // Espacement vertical entre les badges
            $maxBadgesPerRow = 3;
            $maxBadgesPerLine = 3;

            $x = 20;
            $y = 20;

            $badgeCount = 0;
            $lineCount = 0;
            foreach($allSelected as $selected){
                $infosUser = $this->User_model->getUserInfos($selected);
                $image_user = $this->User_model->getImage($selected);
                if($image_user == null){
                    $image_user = 'DEFAULT.png';
                }
        
                // Création de l'image vide
                $width = 636;
                $height = 966;
                $image = imagecreatetruecolor($width, $height);
        
                // Load des images
                
                if($infosUser->usr_site == 1 || $infosUser->usr_site == 4){
                    $overlayImagePath = 'assets/img/setex.png';
                } else if($infosUser->usr_site == 2){
                    $overlayImagePath = 'assets/img/monte.png';
                }
                else if($infosUser->usr_site == 3){
                    $overlayImagePath = 'assets/img/tonelle.png';
                }
                $overlayImage = imagecreatefrompng($overlayImagePath);
        
                $pdpPath = 'IMGAG/'.$image_user;
                if(strpos($image_user, ".png") !== false){
                    $pdp = imagecreatefrompng($pdpPath);
                } 
                if(strpos($image_user, ".jpeg") !== false || strpos($image_user, ".jpg") !== false ){
                    $pdp = imagecreatefromjpeg($pdpPath);
                }
        
                $extWidth = imagesx($overlayImage);
                $extHeight = imagesy($overlayImage);
                $pdpWidth = imagesx($pdp);
                $pdpHeight = imagesy($pdp);
        
                $newWidth = $width;
                $newHeight = $height;
        
                $x_img = 0;
                $y_img = 0;
        
                $font = "assets/fonts/poppins/Poppins-Bold.ttf";
        
                imagecopyresampled($image, $overlayImage, $x_img, $y_img, 0, 0, $newWidth, $newHeight, $extWidth, $extHeight);
                imagecopyresampled($image, $pdp, ($width/2) - 300/2, 240, 0, 0, 300, 300, $pdpWidth, $pdpHeight);
        
                $textColor = imagecolorallocate($image, 255, 255, 255);
        
                // Text
                $textBoundingBox = imagettfbbox(30, 0, $font, $infosUser->usr_prenom);
                $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
        
                imagettftext($image, 30, 0, ($width - $textWidth) / 2, 680, $textColor, $font,$infosUser->usr_prenom);
        
                $textBoundingBox = imagettfbbox(20, 0, $font, $infosUser->usr_initiale."-".$infosUser->usr_matricule);
                $textWidth = $textBoundingBox[2] - $textBoundingBox[0];
        
                imagettftext($image, 20, 0, ($width - $textWidth) / 2, 725, $textColor, $font,$infosUser->usr_initiale."-".$infosUser->usr_matricule);
                imagedestroy($overlayImage);
                imagedestroy($pdp);      
                

                ob_start();
                imagepng($image);
                $imageData = ob_get_clean();

                $pdf->Image('@'.$imageData, $x, $y, $badgeWidth, $badgeHeight, '', '', '', false, 300, '', false, false, 0, false, false, false);
                $x += $badgeWidth + $spacingX;
                $badgeCount++;
                if ($badgeCount >= $maxBadgesPerRow) {
                    $x = 20;
                    $y += $badgeHeight + $spacingY;
                    $badgeCount = 0;
                    $lineCount++;
                    if($lineCount >= $maxBadgesPerLine){
                        $pdf->AddPage();
                        $lineCount = 0;
                        $y = 20;
                    }
                }
            }
            ob_clean();
            $pdf->Output('Liste des badges','I');
            imagedestroy($image);
        } else {
            redirect('Badge/badge');         
        }
    }
}