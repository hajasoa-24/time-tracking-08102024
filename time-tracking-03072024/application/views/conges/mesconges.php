<style type="text/css">
    table#historique th, table#historique td{
        text-align: center;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Mes congés</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Mon solde de congés</h5>
                <p class="card-text">Vous avez <strong><?= (isset($monSolde) ? $monSolde : 0)?></strong> Jours de congés disponible</p>
            </div>
            </div>
        </div>
        <!--<div class="col-sm-6">
            <div class="card">
            <div class="card-body">
                <h5 class="card-title">Mes droits de permission</h5>
                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                <a href="#" class="btn btn-primary">Go somewhere</a>
            </div>
            </div>
        </div>-->
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <button id="add-conge" class="btn btn-primary mx-2"><i class="fa fa-plus-circle pr-2"></i>Nouvelle demande</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="mesconges" class="table-striped table-bordered" style="width:100%">
                <thead>

                    <tr>
                        <th >Date de la demande</th>
                        <th >Demandeur</th>
                        <th >Du</th>
                        <th >Au</th>
                        <th >Date de retour</th>
                        <th >Type</th>
                        <th >Durée</th>
                        <th >Motif</th>
                        <th >Etat</th>
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
    var congeAValiderCadre2 = "<?= A_VALIDER_CADRE2?>";
    var congeAValiderDir = "<?= A_VALIDER_DIR?>";
    var congeATraiterRh = "<?= A_TRAITER_RH?>";
    var congeValide = "<?= VALIDE?>";
    var congeRefuse = "<?= REFUSE?>";
    var reposMaladie = "<?= REPOS_MALADIE?>";
    var assistanceMaternelle = "<?= ASSISTANCE_MATERNELLE?>";
    var congeAutres = "<?= AUTRES?>";
    var congeTermine = "<?= CONGE_TERMINE?>";

    $(document).ready(function(){
        
        //initialisation datatable
        var table = $("#mesconges").DataTable({
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
            ajax : "<?= site_url("conges/getMesConges"); ?>",
            columns : [
                { 
                    data : "conge_datecrea",
                    render : function(data, type, row){
                        var myDate = moment(data);
                        return myDate.isValid() ? myDate.format('DD/MM/YYYY') : ''
                    }
                },
                { data : 'usr_prenom' },
                { 
                    data : 'conge_datedebut',
                    render : function(data, type, row){
                        var myDate = moment(data);
                        if(row.conge_type == typePermission)
                            return myDate.isValid() ? myDate.format('DD/MM/YYYY HH:mm') : ''
                        
                        return myDate.isValid() ? myDate.format('DD/MM/YYYY') : ''
                    }
                },
                { 
                    data : 'conge_datefin',
                    render : function(data, type, row){
                        var myDate = moment(data);
                        if(row.conge_type == typePermission)
                            return myDate.isValid() ? myDate.format('DD/MM/YYYY HH:mm') : ''
                        
                        return myDate.isValid() ? myDate.format('DD/MM/YYYY') : ''
                    } 
                },
                { 
                    data : 'conge_dateretour',
                    render : function(data, type, row){
                        var myDate = moment(data);
                        if(row.conge_type == typePermission)
                            return myDate.isValid() ? myDate.format('DD/MM/YYYY HH:mm') : ''
                        
                        return myDate.isValid() ? myDate.format('DD/MM/YYYY') : ''
                    } 
                },
                { data : 'typeconge_libelle' },
                { 
                    data : 'conge_duree',
                    render : function(data, type, row){
                        
                        if(row.conge_type == "<?= TYPECONGE_CONGE ?>"){
                            return (data) ? ((data > 1) ? data + ' jours' : data + ' jour') : ''
                        }else if(row.conge_type == "<?= TYPECONGE_PERMISSION ?>"){
                            let hourValue = data * 8;
                            return (hourValue) ? (hourValue > 1 ? hourValue + ' heures' : hourValue + ' heure') : ''
                        }else{
                            return '';
                        }
                        
                    } 
                },
                { data : 'conge_motif' },
                { data : 'etatconge_libelle' },
                {
                    targets : -1,
                    "width": "10%",
                    data : null,
                    render : function(data, row, type){
                        //console.log('ligne ', data, row);
                        let canDelete = ( (data.conge_etat == congeAValiderSup) 
                                                || (data.conge_etat == congeAValiderDir) 
                                                || (data.conge_etat == congeAValiderCadre2) 
                                                || (data.conge_etat == congeATraiterRh)
                                                ) ? true : false;

                        let htmlButtons = '<button title="Suivi des états" class="suivi-monconge btn btn-sm btn-success mr-1"><i class="fa fa-calendar-check-o"></i></button>';
                        if(data.conge_etat == congeAValiderSup){
                            htmlButtons += '<button title="Editer" class="edit-monconge btn btn-sm btn-primary mr-1"><i class="fa fa-edit"></i></button>';
                        }
                        if(canDelete){
                            htmlButtons += '<button title="Supprimer" class="delete-monconge btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>';
                        }
                        
                        return htmlButtons
                    }
                }
            ],
            "createdRow": function( row, data, dataIndex){
                //console.log('Created row ', data);
                if(data.conge_etat == congeRefuse){
                    $(row).addClass('table-danger');
                }else if(data.conge_etat == congeValide){
                    $(row).addClass('table-success');
                }else if(data.conge_etat == congeTermine){
                    $(row).addClass('table-secondary');
                }
            }
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
                                let highlightRow = (elt.histoetatconge_etat == congeRefuse) ? ' table-danger ' : '';
                                tableBody += '<tr class="' + highlightRow + '"><th>'+moment(elt.histoetatconge_date).format('DD/MM/YYYY HH:mm')+'</th><td>'+elt.etatconge_libelle+'</td><td>'+((elt.validateur) ? elt.validateur : '') +'</td></tr>';
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