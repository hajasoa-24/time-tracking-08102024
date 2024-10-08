<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Gestion des agences</h2>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-md-12">
            <button id="add-agence" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addAgenceModal"><i class="fa fa-plus-circle pr-2"></i>Ajout agence</button>
        </div>
    </div>

    <div class="row mt-3 ">
        <div class="col-md-12">
            <table id="list-agence" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Code</th> 
                        <th>Libelle</th>
                        <th>Actif</th>
                        <th>Date création</th>
                        <th>Date de modification</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
         
    });

    //initialisation datatable
    var agenceTable = $("#list-agence").DataTable({

        language : {
            url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
        },
        ajax : "<?= site_url("agence/getListAgence"); ?>",
        columns : [
            { data : "agence_id" },
            { data : "agence_libelle" },

            { 
                data : null,
                render : function(data, type, row){
                    
                    if(row.agence_actif == '0') 
                        return '<button id ="activeagence" data-agence="'+data.agence_id+'" class=" btn badge bg-danger" style="color:white">Inactive</button>';
                    else if(row.agence_actif == 1)
                    return '<button id ="desactiveagence" data-agence="'+data.agence_id+'" class=" btn badge bg-success" style="color:white">Active</button>';
                 
                }
            },            
            { data : "agence_datecrea" },
            { data : "agence_datemodif" },
            {
                data: null,
                render: function ( data, type, row ) {
                    return '<button title="modifier agence" data-agence="'+data.agence_id+'" class="edit-agence btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>'
                }
            }
        ]
    });

    
</script>

<script src="<?= base_url('assets/bootstrap/js/jquery.popconfirm.js')?>"></script>


