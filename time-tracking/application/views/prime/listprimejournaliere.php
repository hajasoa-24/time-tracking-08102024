<?php 
    if($this->session->flashdata('msg') != null){
        //echo "<script>alert(".$this->session->flashdata('msg').")</script>";
    }
?>

<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Suivi de production</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form name="setFilter" method="post">
                <div class="form-group row">
                    <label for="service_site" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-2">
                        <select name="filtre_month" class="form-control">
                            <?php
                                $months = array("Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Dec");
                                foreach ($months as $index => $month) {
                                    echo '<option value="' . ($index+1) . '" '. ( ($index+1) == $filtre['month'] ? 'selected="selected"' : '' ) . '>' . $month . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="filtre_year" class="form-control">
                            <?php
                                $year = date('Y');
                                $endYear = $year - 5;
                                for($year; $year > $endYear; $year--){
                                    echo '<option value="' . $year . '" '. ( ($year) == $filtre['year'] ? 'selected="selected"' : '' ) . '>' . $year . '</option>';
                                }
                            ?>
                        </select>
                        
                    </div>
                    <div class="col-sm-2">
                        <button id="doFilterPlanning" class="btn btn-primary btn-sm">Afficher</button>
                    </div>
                    
                </div>
            </form>
        </div> 
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier profil/modalprofil 
             * */ ?>
            <!--<button id="add-profil" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addprofilModal"><i class="fa fa-plus-circle pr-2"></i>Ajout profil</button>-->
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-realisationjournaliere" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Agent</th>
                        <th>Campagne</th>
                        <th>Profil</th>
                        <th>Process</th>
                        <th>Objectif</th>
                        <th>Production</th>
                        <th>Taux d'atteinte</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
  
    $(document).ready(function(){

        //initialisation datatable
        $("#list-realisationjournaliere").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("primeproduction/getPrimeJournaliere") . '/' . $filtre['year'] . '-' . $filtre['month'] . '-1'; ?>",
            columns : [
                { data : "usr_prenom" },
                { data : "campagne_libelle" },
                { data : "primeprofil_libelle" },
                { data : "process_libelle" },
                { data : "objectifs"},
                { data : "nbProduction" },
                { 
                    data : "tauxatteinte",
                    render : function(data, type, row)
                    {
                        var taux = data * 100;
                        return taux + '%'
                    }
                },
            ]
        });

    })
</script>

