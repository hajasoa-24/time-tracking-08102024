<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Gestion des services</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier service/modalService
             * */ ?>
            <button id="add-service" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addServiceModal"><i class="fa fa-plus-circle pr-2"></i>Ajout service</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-service" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Libelle</th>
                        <th>Site</th>
                        <th>Actif</th>
                        <th>Date de création</th>
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
        $("#list-service").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("service/getListService"); ?>",
            columns : [
                { data : "service_libelle" },
                { data : "site_libelle" },
                { 
                    
                    data : null,
                    render : function(data, type, row){
                        return (data.service_actif) == "1" ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>';
                    } 
                },
                { data : "service_datecrea" },
                {
                    data: null,
                    render: function ( data, type, row ) {
                        return '<button title="modifier le service" data-service="'+data.service_id+'" class="edit-service btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>' + 
                                '<button title="supprimer le service" data-service="'+data.service_id+'" class="delete-service btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button>';
                    }
                }
            ]
        });
    })
</script>