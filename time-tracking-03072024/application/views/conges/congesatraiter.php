<style type="text/css">
    table#historique th, table#historique td{
        text-align: center;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Congés à traiter</h3>
        </div>
    </div>

    <div class="row mt-3">
        <div class="form-check form-switch">
            <div class="col-md-12 text-right">
                <input class="form-check-input" type="checkbox" <?= ($this->session->userdata('show_validated') == 'true') ? 'checked' : '' ?> id="showHideValidatedConge">
                <label class="form-check-label" for="showHideValidatedConge">Afficher les demandes traitées</label>
            </div>
        </div>
    </div>
   
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="congeatraiter" class="table-striped table-bordered" style="width:100%">
                <thead>

                    <tr>
                        <th>Date de la demande</th>
                        <th>Site</th>
                        <th>Demandeur</th>
                        <th>Du</th>
                        <th>Au</th>
                        <th>Date de retour</th>
                        <th>Type</th>
                        <th>Durée</th>
                        <th>Motif</th>
                        <th>Etat</th>
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
    var congedematernite = "<?= CONGE_DE_MATERNITE ?>";


    $(document).ready(function(){
        
        //initialisation datatable
        var table = $("#congeatraiter").DataTable({
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
            ajax : "<?= site_url("conges/getCongesATraiter"); ?>",
            columns : [
                { 
                    data : "conge_datecrea",
                    render : function(data, type, row){
                        var myDate = moment(data);
                        return myDate.isValid() ? myDate.format('DD/MM/YYYY') : ''
                    }
                },
                { data : 'site_contrat' },
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
                        console.log(row);
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
                    data : null,
                    //defaultContent : '<button class="treat-conge btn btn-sm btn-success mx-2"><i class="fa fa-check-square-o"></i> </button><button class="refus-conge btn btn-sm btn-danger"><i class="fa fa-window-close-o"></i> </button>'
                    render : function(data, row, type){
                        let isCongeTraite = ( (data.conge_etat == congeValide) 
                                                || (data.conge_etat == congedematernite)
                                                || (data.conge_etat == congeRefuse) 
                                                || (data.conge_etat == reposMaladie) 
                                                || (data.conge_etat == assistanceMaternelle)) ? true : false;
                                                
                        console.log('ROW ', data.conge_etat, isCongeTraite);

                        //let htmlButtons = '<button title="Suivi des états" class="suivi-monconge btn btn-sm btn-success mr-2"><i class="fa fa-calendar-check-o"></i></button>';
                        let htmlButtons = '';
                        if(data.conge_etat == congeATraiterRh){
                            htmlButtons += '<button title="Editer" class="edit-monconge btn btn-sm btn-primary mx-1"><i class="fa fa-edit"></i></button>';
                        }
                        if(!isCongeTraite){
                            htmlButtons += '<button class="treat-conge btn btn-sm btn-success mx-1"><i class="fa fa-check-square-o"></i></button><button class="refus-conge btn btn-sm btn-danger mx-1"><i class="fa fa-window-close-o"></i></button>';
                        }
                        
                        return htmlButtons
                    }
                }
            ],
            "createdRow": function( row, data, dataIndex){
                if(data.conge_etat == congeRefuse){
                    $(row).addClass('table-danger');
                }
            }
        });

    
        $('#congeatraiter tbody').on('click', '.edit-monconge', function(){
            var data = table.row($(this).parents('tr')).data();
            //console.log(data);
            modalControl.editModal(data);
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

                $('#conge_datedebut').val(datedebut.format('YYYY-MM-DD'));
                $('#conge_datefin').val(datefin.format('YYYY-MM-DD'));
                $('#conge_heuredatedebut').val(datedebut.format('HH:mm'));
                $('#conge_heuredatefin').val(datefin.format('HH:mm'));
                $('#permission_heuredatedebut').val(datedebut.format('HH:mm'));
                $('#permission_heuredatefin').val(datefin.format('HH:mm'));

                $('#conge_datefin').trigger('change');

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

        $('#showHideValidatedConge').on('change', function(){
            let showHideValidated = $(this).is(':checked');
            $.ajax({
                url : '<?= site_url('conges/showValidatedConges')?>',
                data : {'showHideValidated' : showHideValidated},
                dataType : 'json',
                type : 'POST',
                success : function(response){
                    if(!response.err){
                        table.ajax.reload();
                    }
                }
            })

        })
      
    })
</script>