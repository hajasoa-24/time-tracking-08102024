<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Gestion des utilisateurs</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier utilisateur/modalUtilisateur 
             * */ ?>
            <button id="add-utilisateur" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addUtilisateurModal"><i class="fa fa-plus-circle pr-2"></i>Ajouter</button>
            <button id="import-utilisateur" class="btn btn-success mx-2" data-bs-toggle="modal" data-bs-target=""><i class="fa fa-upload pr-2"></i>Importer</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-utilisateur" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                       <!--  <th>ID</th> -->
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Actif</th>
                        <th>Role</th>
                        <th>Id ingress</th>
                        <th>Campagne</th>
                        <th>Service</th>
                        <th class="notexport">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //initialisation datatable
        $("#list-utilisateur").DataTable({
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
            ajax : "<?= site_url("user/getListUtilisateur"); ?>",
            columns : [
                /*{ data : "usr_id" },*/
                { data : "usr_nom" },
                { data : "usr_prenom" },
                { data : "usr_matricule" },
                { data : "usr_initiale" },
                { 
                    data : null,
                    render : function(data, type, row){
                        return (data.usr_actif) == "1" ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>';
                    }
                },
                { data : "role_libelle" },
                { data : "usr_ingress" },
                { 
                    data : "campagnes",
                    render : function(data, type, row){
                        let limit = 30;
                        if(data){
                            if(data.length <= limit){
                                return data
                            }else{
                                let text = data.slice(0, 10) + ' ...';
                                return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' + data + '">' + text + '</span>' 
                            } 
                        }else{
                            return ''
                        }
                    }
                },
                { 
                    data : "services",
                    render : function(data, type, row){
                        let limit = 30;
                        if(data){
                            if(data.length <= limit){
                                return data
                            }else{
                                let text = data.slice(0, 10) + ' ...';
                                return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' + data + '">' + text + '</span>' 
                            } 
                        }else{
                            return ''
                        }
                    }
                },
                {
                    data : null,
                    render: function ( data, type, row ) {
                        return '<button title="modifier utilisateur" data-user="'+data.usr_id+'" class="edit-utilisateur btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>' + 
                                '<button title="supprimer utilisateur" data-user="'+data.usr_id+'" class="delete-utilisateur btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button>';
                    }
                }
            ]
        });
    })
</script>