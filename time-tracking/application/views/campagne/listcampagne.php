<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Gestion des campagnes</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier campagne/modalCampagne 
             * */ ?>
            <button id="add-campagne" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addCampagneModal"><i class="fa fa-plus-circle pr-2"></i>Ajouter</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-campagne" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libelle</th>
                        <th>Client</th>
                        <th>Pole</th>
                        <th>Site</th>
                        <th>Prorio</th>
                        <th>Actif</th>
                        <th>Date de création</t h>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //initialisation datatable
        $("#list-campagne").DataTable({

             dom: 'Blfrtip',
        buttons: [
            {
                extend : 'excelHtml5',
                exportOptions : {
                    orthogonal : 'export',
                    columns: ':not(.notexport)'
                }
            },
            
        ],
        
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("campagne/getListCampagne"); ?>",
            columns : [
                { data : "campagne_id" },
                { data : "campagne_libelle" },
                { data : "campagne_client" },
                { data : "pole_libelle" },
                { data : "site_libelle" },
                { data : "proprio_libelle" },
                { 
                    
                    data : null,
                    render : function(data, type, row){
                        return (data.campagne_actif) == "1" ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>';
                    } 
                },
                { data : "campagne_datecrea" },
                {
                    data: null,
                    render: function ( data, type, row ) {
                        return '<button title="modifier la campagne" data-campagne="'+data.campagne_id+'" class="edit-campagne btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>' + 
                                '<button title="affecter la campagne" data-campagne="'+data.campagne_id+'" class="affecter-campagne btn btn-info btn-sm mr-1"><i class="fa fa-puzzle-piece"></i></button>' + 
                                '<button title="supprimer la campagne" data-campagne="'+data.campagne_id+'" class="delete-campagne btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button>';
                    }
                }
            ]
        });
    })
</script>