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
            ajax : "<?= site_url("primeproduction/getPrimeJournaliere"); ?>",
            columns : [
                { data : "usr_username" },
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

