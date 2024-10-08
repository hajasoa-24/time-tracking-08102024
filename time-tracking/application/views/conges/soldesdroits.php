<style type="text/css">
    table#historique th, table#historique td{
        text-align: center;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Soldes de congés et droits de permission</h3>
        </div>
    </div>
    <!-- <div class="row mt-3">
        <div class="col-md-12">
            <button id="add-conge" class="btn btn-primary mx-2"><i class="fa fa-plus-circle pr-2"></i>Import</button>
        </div>
    </div> -->
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="soldesdroitsTable" class="table-striped table-bordered" style="width:100%">
                <thead>

                    <tr>
                        <th >Nom</th>
                        <th >Prénom</th>
                        <th >Matricule</th>
                        <th >Initiale</th>
                        <th >Contrat</th>
                        <th >Site</th>
                        <th >Soldes congés</th>
                        <th >Droits permission</th>
                        <th class="notexport">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    //GLOBAL
    var typeConge = "<?= TYPECONGE_CONGE ?>";
    var typePermission = "<?= TYPECONGE_PERMISSION ?>";
    var congeAValiderSup = "<?= A_VALIDER_SUP?>";
    var congeAValiderDir = "<?= A_VALIDER_DIR?>";
    var congeATraiterRh = "<?= A_TRAITER_RH?>";
    var congeValide = "<?= VALIDE?>";
    var congeRefuse = "<?= REFUSE?>";
    var reposMaladie = "<?= REPOS_MALADIE?>";
    var assistanceMaternelle = "<?= ASSISTANCE_MATERNELLE?>";
    var congeAutres = "<?= AUTRES?>";

    $(document).ready(function(){
        
        //initialisation datatable
        var table = $("#soldesdroitsTable").DataTable({
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
            ajax : "<?= site_url("conges/getAllSoldesDroits"); ?>",
            columns : [
                { data : 'usr_nom' },
                { data : 'usr_prenom' },
                { data : 'usr_matricule' },
                { data : 'usr_initiale' },
                { data : 'contrat_libelle' },
                { data : 'site_libelle' },
                { data : 'usr_soldeconge' },
                { data : 'usr_droitpermission' },
                {
                    targets : -1,
                    data : null,
                    defaultContent : '',
                    render : function(data, row, type){
                        htmlButtons = '<button title="Editer" class="edit-soldesdroits btn btn-sm btn-primary mx-1"><i class="fa fa-edit"></i></button>';
                        return htmlButtons
                    }
                }
            ]
        });

        $('#mesconges tbody').on('click', '.edit-monconge', function(){
            var data = table.row($(this).parents('tr')).data();
            //console.log(data);
            modalControl.editModal(data);
        })

        $(document).on('click', '#add-conge', function(){
            //console.log(data);
            modalControl.addModal();
        })

        $(document).on('click', '.delete-monconge', function(){
            var data = table.row($(this).parents('tr')).data();
            modalControl.deleteModal(data);
        })
        $(document).on('click', '.suivi-monconge', function(){
            var data = table.row($(this).parents('tr')).data();
            modalControl.suiviEtatModal(data);
        })

        $(document).on('click', '#confirmDeleteConge', function(){
            $('#confirmDeleteCongeForm').submit();
        })

        var modalControl = {
            'init' : function(){
                $('#addCongeModal input').val('');
                $('.form-conge, .form-permission, .form-date').hide();
                $('#permission_heuredatedebut, #permission_heuredatefin').val('00:00');
                $('#action-addCongeModal').val('add');
                $('#congeToDelete').val();
                $('#congeToDeleteEtat').val();
            },
            'addModal' : function(){
                this.init();
                $('#form-addconge-verif').val('sent');
                $("#addCongeModal").modal('show');
            },
            'editModal' : function(data){
                this.init();
                $('#action-addCongeModal').val('edit');
                $('#conge_type').val(data.conge_type);
                $('#edit-conge-id').val(data.conge_id);
                $('#edit-conge-user').val(data.conge_user);
                $('#conge_type').trigger('change');
                
                let datedebut = moment(data.conge_datedebut);
                let datefin = moment(data.conge_datefin);
                let dateretour = moment(data.conge_dateretour);

                $('#conge_datedebut').val(datedebut.format('YYYY-MM-DD'));
                $('#conge_datefin').val(datefin.format('YYYY-MM-DD'));
                $('#conge_heuredatedebut').val(datedebut.format('HH:mm'));
                $('#conge_heuredatefin').val(datefin.format('HH:mm'));
                $('#permission_heuredatedebut').val(datedebut.format('HH:mm'));
                $('#permission_heuredatefin').val(datefin.format('HH:mm'));

                $('#conge_datefin').trigger('change');

                $('#conge_heuredateretour').val(dateretour.format('HH:mm'));
                $('#conge_datefin').val(dateretour.format('YYYY-MM-DD'));
                

                //$('#conge_duree').val(data.conge_duree);
                $('#conge_motif').val(data.conge_motif);
                $('#addCongeModal .modal-title').html('Edition de demande');
                $('#form_add_conge').prop('action', '<?= site_url('conges/saveEditConge/') ?>' + data.conge_id);
                $('#form-addconge-verif').val('sent');
                

                $("#addCongeModal").modal('show');
            },
            'deleteModal' : function(data){
                this.init();
                $('#congeToDelete').val(data.conge_id);
                $('#congeToDeleteEtat').val(data.conge_etat);
                $("#deleteCongeModal").modal('show');
            },
            'suiviEtatModal' : function(data){
                this.init();
                //TODO Appel Ajax pour récupérer la liste des histoetatconge de la personne concernée
                $.ajax({
                    url : "<?= site_url('conges/getHistoEtatConge') ?>",
                    type : 'POST',
                    data : {'conge' : data.conge_id},
                    dataType : 'json',
                    success : function(response){
                        let tableBody = '';
                        if(!response.error){
                            //Formater le tbody
                            
                             response.data.forEach(elt => {
                                tableBody += '<tr><th>'+moment(elt.histoetatconge_date).format('DD/MM/YYYY HH:mm')+'</th><td>'+elt.etatconge_libelle+'</td><td>'+elt.validateur+'</td></tr>';
                            });
                            
                        }
                        $('#suiviCongeModal table tbody').html(tableBody);
                    }
                })
                $("#suiviCongeModal").modal('show');
            }
        };
    })
</script>