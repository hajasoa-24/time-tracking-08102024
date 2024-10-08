<?php
defined("BASEPATH") or exit("No direct script access allowed");
require "vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Transport extends MY_Controller
{
    public function suiviTransport()
    {
        $filtretranpsort = $this->session->userdata("filtretranpsort");

        $filtretranpsort = [
            "debut" => date("Y-m-d", strtotime("0 days")),
            "fin" => date("Y-m-d", strtotime("0 days")),
        ];
        $this->session->set_userdata("filtretranpsort", $filtretranpsort);

        $header = ["pageTitle" => "Transport - TimeTracking"];
        $this->load->model("user_model");
        $this->load->model("Transport_model");
        $current_user = $this->session->userdata("user")["id"];

        $this->load->view("common/header", $header);
        $this->load->view("common/sidebar", $this->_sidebar);
        $this->load->view("common/top", ["top" => $this->_top]);
        $this->load->view("transport/transport", [
            "filtretranpsort" => $filtretranpsort,
        ]);
        $this->load->view("common/footer", []);
    }

    public function getAxequartier()
    {
        $this->load->model("Transport_model");
        $data = $this->Transport_model->getAllAxequartier();

        echo json_encode(["data" => $data]);
    }

    public function getAllAxequartierlist(){
        $this->load->model("Transport_model");
        $data = $this->Transport_model->getAllAxequartierlist();

        echo json_encode(["data" => $data]);
    }

    public function annulationtransport()
    {
        $transportuser_id = $this->input->post("id_transportuser");
        
        $this->load->model("Transport_model");
        $datas = $this->Transport_model->transportuserannulation(
            $transportuser_id
        );

    }

    public function getaxebyquartier()
    {
        $this->load->model("Transport_model");
        $data = $this->Transport_model->getaxebyquartiertest();

        echo json_encode(["data" => $data]);
    }

    public function getHeure()
    {
        $this->load->model("Transport_model");
        $datas = $this->Transport_model->getHeure();
        echo json_encode(["data" => $datas]);
    }

    public function getAxe()
    {
        $this->load->model("Transport_model");
        $datas = $this->Transport_model->getaxe();
        echo json_encode(["data" => $datas]);
    }

    public function addusertranport()
    {
        $current_user = $this->session->userdata("user")["id"];

        $usertransport = [];
        $date = new DateTime();
        $formatted_date = $date->format("Y-m-d H:i");

        $usertransport["transportuser_heure"] = $this->input->post("heure");
        $usertransport["transportuser_axe"] = $this->input->post("axe");
        $usertransport["transportuser_quartier"] = $this->input->post(
            "quartie"
        );
        $usertransport["transportuser_date"] = $formatted_date;
        $usertransport["transportuser_user"] = $current_user;

        $this->load->model("Transport_model");

        $this->Transport_model->add($usertransport);
    }

    public function setfilter()
    {
        $filtretranpsort = $this->session->userdata("filtretranpsort");

        $start = $this->input->post("debut");
        $end = $this->input->post("fin");

        $filtretranpsort = [
            "debut" => $start,
            "fin" => $end,
        ];
        $this->session->set_userdata("filtretranpsort", $filtretranpsort);
        echo json_encode(["err" => false]);
    }

    public function getsuivitransport()
    {
        $filtretranpsort = $this->session->userdata("filtretranpsort");

        if (
            !isset($filtretranpsort["debut"]) ||
            (isset($filtretranpsort["debut"]) &&
                empty($filtretranpsort["debut"]))
        ) {
            $filtretranpsort["debut"] = date("Y-m-d", strtotime(" -1 days"));
        }
        if (
            !isset($filtretranpsort["fin"]) ||
            (isset($filtretranpsort["fin"]) && empty($filtretranpsort["fin"]))
        ) {
            $filtretranpsort["fin"] = date("Y-m-d");
        }

        $this->load->model("Transport_model");
        $datas = $this->Transport_model->suiviTransport($filtretranpsort);

        echo json_encode(["data" => $datas]);
    }

    public function getAxtransportbytime()
    {
        $this->load->model("Transport_model");
        $id = $this->input->post("selectedValue");

        $datas = $this->Transport_model->getAxebytime($id);
        echo json_encode(["data" => $datas]);
    }

    public function addtransport()
    {
        $this->load->model("Transport_model");
        $datas = $this->Transport_model->getAllAxe();

        $header = ["pageTitle" => "Ajout Axe"];

        $this->load->view("common/header", $header);
        $this->load->view("common/sidebar", $this->_sidebar);
        $this->load->view("common/top", ["top" => $this->_top]);
        $this->load->view("transport/addtransport");

        $this->load->view("common/footer", []);
    }

    public function addAxe()
    {
        $axe = [];

        $axe["heureaxe_axe"] = $this->input->post("axe");
        $axe["heureaxe_heure"] = $this->input->post("heure");
        $this->load->model("Transport_model");
        $add = $this->Transport_model->addAxetransport($axe);

        $page = "transport/assignation";

        if ($add) {
            redirect($page);
        }
    }

    public function deleteAxetransport()
    {
        $id = $this->input->post("id_axetransport");
        $this->load->model("Transport_model");
        $requete = $this->Transport_model->deleteAxe($id);

        if ($requete) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false]);
        }
    }
    public function getQuartierTransport()
    {
        $this->load->model("Transport_model");
        $datas = $this->Transport_model->getquartier();

        echo json_encode(["data" => $datas]);
    }
    public function getAxetransportuser()
    {
        $transportuser_id = $this->input->post("id_transportuser");
        $this->load->model("Transport_model");
        $heureusertransport = $this->Transport_model->getaxeuser(
            $transportuser_id
        );
        $heure = $heureusertransport[0]->transportuser_heure;

        $datas = $this->Transport_model->getAxebytime($heure);
        echo json_encode(["data" => $datas]);
    }

    public function assignation()
    {
        $header = ["pageTitle" => "Assignation des quartiers"];

        $this->load->model("Transport_model");
        $listequartiers = $this->Transport_model->getquartier();

        $this->load->view("common/header", $header);
        $this->load->view("common/sidebar", $this->_sidebar);

        $this->load->view("common/top", ["top" => $this->_top]);
        $this->load->view("transport/quartiers", [
            "listequartiers" => $listequartiers,
        ]);
    }

    public function transportupdate()
    {
        $axe = [];
        $transportuser_id = $this->input->post("id_transportuser");
        $axe["transportuser_axe"] = $this->input->post("axetoupdate");
        $axe["transportuser_status"] = 1;

        $this->load->model("Transport_model");
        $datas = $this->Transport_model->transportuserupdate(
            $transportuser_id,
            $axe
        );
        redirect("transport/suiviTransport");
    }

    public function updateusertransport()
    {
        $axe = [];
        $transportuser_id = $this->input->post("id_transportuser");
        $axe["transportuser_heure"] = $this->input->post("heure");
        $axe["transportuser_axe"] = $this->input->post("axe");
        $axe["transportuser_quartier"] = $this->input->post("quartie");

        $this->load->model("Transport_model");
        $datas = $this->Transport_model->transportuserupdatebyuser(
            $transportuser_id,
            $axe
        );
    }

    public function getTransportuser()
    {
        $current_user = $this->session->userdata("user")["id"];
        $this->load->model("Transport_model");

        $datas = $this->Transport_model->axetransportday($current_user);
        echo json_encode(["data" => $datas]);
    }

    public function testexport()
    {
        $query1 = $this->db->get("tr_heuretransport");
        $heuretransport = $query1->result();

        $nombre_lignes = $query1->num_rows();
        $i = 0;

        for ($i = 0; $i < $nombre_lignes; $i++) {
            $po = $heuretransport[$i]->heuretransport_id;
            $query2 = $this->db
                ->where("axetransport_heure", $po)
                ->get("t_axetransport");
            $axetransport = $query2->result();
            $nombre_ligne = $query2->num_rows();

            for ($j = 0; $j < $nombre_ligne; $j++) {
                echo "(" .
                    $heuretransport[$i]->heuretransport_heure .
                    "," .
                    $axetransport[$j]->axetransport_libelle .
                    ")" .
                    " ";
            }
        }
    }

    public function indextest()
    {
        $filtretranpsort = $this->session->userdata("filtretranpsort");

        if (
            !isset($filtretranpsort["debut"]) ||
            (isset($filtretranpsort["debut"]) &&
                empty($filtretranpsort["debut"]))
        ) {
            $filtretranpsort["debut"] = date("Y-m-d", strtotime(" -1 days"));
        }
        if (
            !isset($filtretranpsort["fin"]) ||
            (isset($filtretranpsort["fin"]) && empty($filtretranpsort["fin"]))
        ) {
            $filtretranpsort["fin"] = date("Y-m-d");
        }
        $spreadsheet = new Spreadsheet();

        $query1 = $this->db->get("tr_heuretransport");
        $heuretransport = $query1->result();
        $nombre_lignes = $query1->num_rows();

        for ($i = 0; $i < $nombre_lignes; $i++) {
            $po = $heuretransport[$i]->heuretransport_id;
            $query2 = $this->db
                ->where("axetransport_heure", $po)
                ->get("t_axetransport");
            $axetransport = $query2->result();
            $nombre_ligne = $query2->num_rows();

            for ($j = 0; $j < $nombre_ligne; $j++) {
                // Créer une nouvelle feuille dans le classeur
                $newSheet = $spreadsheet->createSheet();

                $styleFont = [
                    "font" => [
                        "bold" => false,
                        "size" => 10,
                        "name" => "Arial",
                    ],
                ];

                // Appliquer le style de police aux en-têtes de colonnes
                $newSheet->getStyle("A1:K1")->applyFromArray($styleFont);

                // Définir les en-têtes des colonnes
                $newSheet->setCellValue("A1", "Service");
                $newSheet->setCellValue("B1", "Prénom");
                $newSheet->setCellValue("C1", "Quartier");
                $newSheet->setCellValue("D1", "Site");
                $newSheet->setCellValue("E1", "Heure de sortie");
                $newSheet->setCellValue("F1", "Heure de départ");
                $newSheet->setCellValue("G1", "Heure d'arrivée");
                $newSheet->setCellValue(
                    "H1",
                    "Signature pas besoin d'accompagnateur"
                );
                $newSheet->setCellValue("I1", "Durée accompagnement");
                $newSheet->setCellValue("J1", "Commentaire");
                $newSheet->setCellValue("K1", "Signature");

                // Adapter la largeur des cellules de l'en-tête en fonction de la longueur du texte
                foreach (range("A", "K") as $column) {
                    $newSheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Nettoyer le titre de la feuille
                $heuretransport_heure_cleaned = str_replace(
                    ":",
                    "-",
                    $heuretransport[$i]->heuretransport_heure
                );
                $newSheet->setTitle(
                    "$heuretransport_heure_cleaned, {$axetransport[$j]->axe_libelle}"
                );

                // Charger le modèle et récupérer les données
                $this->load->model("Transport_model");
                $data = $this->Transport_model->export(
                    $filtretranpsort,
                    $heuretransport[$i]->heuretransport_id,
                    $axetransport[$j]->axetransport_id
                );

                $startRow = 2;

                $newSheet->fromArray($data, null, "A" . $startRow);
            }
        }

        // Créer un écrivain Excel
        $writer = new Xlsx($spreadsheet);

        header(
            "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        );
        header('Content-Disposition: attachment;filename="test.xlsx"');
        header("Cache-Control: max-age=0");

        // Envoyer le fichier Excel au navigateur
        $writer->save("php://output");
    }

    public function index()
    {
        $filtretranpsort = $this->session->userdata("filtretranpsort");

        if (
            !isset($filtretranpsort["debut"]) ||
            (isset($filtretranpsort["debut"]) &&
                empty($filtretranpsort["debut"]))
        ) {
            $filtretranpsort["debut"] = date("Y-m-d", strtotime(" -1 days"));
        }
        if (
            !isset($filtretranpsort["fin"]) ||
            (isset($filtretranpsort["fin"]) && empty($filtretranpsort["fin"]))
        ) {
            $filtretranpsort["fin"] = date("Y-m-d");
        }
        $spreadsheet = new Spreadsheet();

        $query1 = $this->db->get("tr_heuretransport");
        $heuretransport = $query1->result();
        $nombre_lignes = $query1->num_rows();

        for ($i = 0; $i < $nombre_lignes; $i++) {
            $po = $heuretransport[$i]->heuretransport_id;
            $query2 = $this->db
                ->select("axe_libelle,axe_id")
                ->join("tr_axe", "axe_id = heureaxe_axe ", "inner")
                ->where("heureaxe_heure", $po)
                ->get("t_axeheure");
            $axetransport = $query2->result();
            $nombre_ligne = $query2->num_rows();

            for ($j = 0; $j < $nombre_ligne; $j++) {
                // Créer une nouvelle feuille dans le classeur
                $newSheet = $spreadsheet->createSheet();

                $styleFont = [
                    "font" => [
                        "bold" => false,
                        "size" => 10,
                        "name" => "Arial",
                    ],
                ];

                // Appliquer le style de police aux en-têtes de colonnes
                $newSheet->getStyle("A1:K1")->applyFromArray($styleFont);

                // Définir les en-têtes des colonnes
                $newSheet->setCellValue("A1", "Service");
                $newSheet->setCellValue("B1", "Prénom");
                $newSheet->setCellValue("C1", "Quartier");
                $newSheet->setCellValue("D1", "Site");
                $newSheet->setCellValue("E1", "Heure de sortie");
                $newSheet->setCellValue("F1", "Heure de départ");
                $newSheet->setCellValue("G1", "Heure d'arrivée");
                $newSheet->setCellValue(
                    "H1",
                    "Signature pas besoin d'accompagnateur"
                );
                $newSheet->setCellValue("I1", "Durée accompagnement");
                $newSheet->setCellValue("J1", "Commentaire");
                $newSheet->setCellValue("K1", "Signature");

                // Adapter la largeur des cellules de l'en-tête en fonction de la longueur du texte
                foreach (range("A", "K") as $column) {
                    $newSheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Nettoyer le titre de la feuille
                $heuretransport_heure_cleaned = str_replace(
                    ":",
                    "-",
                    $heuretransport[$i]->heuretransport_heure
                );
                $newSheet->setTitle(
                    "$heuretransport_heure_cleaned, {$axetransport[$j]->axe_libelle}"
                );

                // Charger le modèle et récupérer les données
                $this->load->model("Transport_model");
                $data = $this->Transport_model->export(
                    $filtretranpsort,
                    $heuretransport[$i]->heuretransport_id,
                    $axetransport[$j]->axe_id
                );

                $startRow = 2;

                $newSheet->fromArray($data, null, "A" . $startRow);
            }
        }

        // Créer un écrivain Excel
        $writer = new Xlsx($spreadsheet);
        $date_actuelle = date("Y-m-d");
        $NomduFichier = "Transport du " . $date_actuelle . ".xlsx";



        header(
            "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
        );
        header('Content-Disposition: attachment;filename="'.$NomduFichier.'"');
        header("Cache-Control: max-age=0");

        // Envoyer le fichier Excel au navigateur
        $writer->save("php://output");
    }


    public function getinfoquartier(){
        $id_axeheure = $this->input->post('id_axeheure');
        $this->load->model("Transport_model");
        $listequartiers = $this->Transport_model->getInfoquartier($id_axeheure);

        echo json_encode(["data" => $listequartiers]);


    }


    public function saveassignquatieraxe()
    {
        $edit_user_data = $this->input->post();

        $this->load->model("Transport_model");


        if (empty($edit_user_data['user_service_edit'])){
            $this->Transport_model->deleteassignquatieraxe($edit_user_data['edit_user_id']);

        }
        else {
            $this->Transport_model->insertassignquatieraxe($edit_user_data['test'], $edit_user_data['user_service_edit']);

        }

        $page = "transport/assignation";
        redirect($page);



    }
}
