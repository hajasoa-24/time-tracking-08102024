<style type="text/css">
    table#historique th, table#historique td{
        text-align: center;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Congés à valider</h3>
        </div>
    </div>
   
    <div class="row mt-3" style="">
        <div class="form-group row">
            <div class="col-md-6 text-left">
                <label for="filterTypeConge" class="col-sm-3 col-form-label">Filtrer :</label>
                <div class="col-sm-8">
                    <select name="filterTypeConge" id="filterTypeConge" class="form-select form-select-sm">
                        <option value=""> -- </option>
                        <?php 
                            if(is_array($listEtatConge)) : 
                                foreach($listEtatConge as $etatConge) : ?>
                                    <option value="<?= $etatConge->etatconge_id ?>" <?php if($etatConge->etatconge_id == $this->session->userdata('filtreetatvalidateconge')) echo 'selected="selected"' ?>><?= $etatConge->etatconge_libelle ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-check form-switch">
            <div class="col-md-12 text-right">
                <input class="form-check-input" type="checkbox" <?= ($this->session->userdata('show_validated') == 'true') ? 'checked' : '' ?> id="showHideValidatedConge">
                <label class="form-check-label" for="showHideValidatedConge">Afficher les demandes validées</label>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-md-12">
            <table id="congesavalider" class="table-striped table-bordered" style="width:100%">
                <thead>

                    <tr>
                        <th>Date de la demande</th>
                        <th>Demandeur</th>
                        <?php if($userRole == ROLE_CLIENT): ?>
                        <th>Pseudo Français</th>
                        <?php endif; ?>
                        <?php if($userRole != ROLE_CLIENT): ?>
                        <th>Campagne</th>
                        <th>Service</th>
                        <?php endif; ?>
                        <th>Du</th>
                        <th>Au</th>
                        <th>Date de retour</th>
                        <th>Type</th>
                        <th>Durée</th>
                        <th>Motif</th>
                        <th>Etat</th>
                        <th>Commentaire</th>
                        <?php if($userRole != ROLE_CLIENT): ?>
                        <th class="notexport">Actions</th>
                        <?php endif; ?>
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
    var congeAValiderCadre2 = "<?= A_VALIDER_CADRE2?>";
    var congeAValiderCostrat = "<?= A_VALIDER_COSTRAT?>";
    var congeATraiterRh = "<?= A_TRAITER_RH?>";
    var congeValide = "<?= VALIDE?>";
    var congeRefuse = "<?= REFUSE?>";
    var reposMaladie = "<?= REPOS_MALADIE?>";
    var assistanceMaternelle = "<?= ASSISTANCE_MATERNELLE?>";
    var congeAutres = "<?= AUTRES?>";
    var congeAutres = "<?= AUTRES?>";
    var congeTermine = "<?= CONGE_TERMINE?>";
    var congeEncours= "<?= CONGE_ENCOURS?>";
    var userRole = "<?= $userRole ?>";

    $(document).ready(function(){
        
        //initialisation datatable
        var table = $("#congesavalider").DataTable({
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
            ajax : "<?= site_url("conges/getCongesAValider"); ?>",
            columns : [
                { 
                    data : "conge_datecrea",
                    render : function(data, type, row){
                        var myDate = moment(data);
                        return myDate.isValid() ? myDate.format('DD/MM/YYYY') : ''
                    }
                },
                { data : 'usr_prenom' },
                <?php if($userRole == ROLE_CLIENT): ?>
                { data : 'usr_pseudo' },
                <?php endif; ?>
                <?php if($userRole != ROLE_CLIENT): ?>
                { data : 'list_campagne',
                    visible : true,
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
                { data : 'list_service',
                    visible : true,
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
                <?php endif; ?>
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
                { data : 'conge_commentaire' },
                <?php if($userRole != ROLE_CLIENT): ?>
                {
                    targets : -1,
                    data : null,
                    defaultContent : '',
                    render : function(data, row, type){

                        let canEdit = ( (data.conge_etat == congeAValiderSup) 
                                                || (data.conge_etat == congeAValiderDir) 
                                                || (data.conge_etat == congeAValiderCadre2)
                                                || (data.conge_etat == congeAValiderCostrat) 
                                                || (data.conge_etat == congeATraiterRh)) ? true : false;

                        let validated = ( (data.conge_etat != congeAValiderSup) 
                                                && (data.conge_etat != congeAValiderDir) 
                                                && (data.conge_etat != congeAValiderCostrat)
                                                && (data.conge_etat != congeAValiderCadre2) ) ? true : false;
                        
                        
                        let htmlButtons = '';
                        //console.log('DUMP ',userRole, data.conge_etat);
                        if(!validated){
                            
                           

                            if(userRole != "<?= ROLE_SUP ?>"){
                                if( (data.conge_etat == congeAValiderCadre2 || data.conge_etat == congeAValiderDir) &&  (userRole == "<?= ROLE_DIRECTION ?>" || userRole == "<?= ROLE_CADRE2 ?>") 
                                    || (data.conge_etat == congeAValiderCostrat && userRole == "<?= ROLE_COSTRAT ?>")
                                    || ( (data.conge_etat == congeAValiderCadre2 || data.conge_etat == congeAValiderSup) && (userRole == "<?= ROLE_CADRE ?>" || userRole == "<?= ROLE_CADRE2 ?>")) 
                                ){
                                    htmlButtons +=  '<button class="validate-conge btn btn-sm btn-success mx-2"><i class="fa fa-check-square-o"></i></button>'+
                                                '<button class="refus-conge btn btn-sm btn-danger"><i class="fa fa-window-close-o"></i></button>';
                                }
                                
                                
    
                                /*htmlButtons += '<button class="validate-conge btn btn-sm btn-success mx-2"><i class="fa fa-check-square-o"></i></button>'+
                                                '<button class="refus-conge btn btn-sm btn-danger"><i class="fa fa-window-close-o"></i></button>';*/                        
                            }else{
                                if(canEdit) htmlButtons += '<button class="comment-conge btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>';
                            }
                            
                        }
                        return htmlButtons
                    }
                }
                <?php endif; ?>
            ],
            "createdRow": function( row, data, dataIndex){
                if(data.conge_etat == congeRefuse){
                    $(row).addClass('table-danger');
                }else if(data.conge_etat == congeValide){
                    $(row).addClass('table-success');
                }else if(data.conge_etat == congeTermine){
                    $(row).addClass('table-secondary');
                }else if(data.conge_etat == congeEncours){
                    $(row).addClass('table-info');
                }
            }
        });

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
    
        $('#filterTypeConge').on('change', function(){
            let selected = $(this).val();
            $.ajax({
                url : '<?= site_url('conges/setValidateCongeFilter')?>',
                data : {'etatconge' : selected},
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