<style type="text/css">
    table#historique th, table#historique td{
        text-align: center;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Historique des agents</h3>
        </div>
    </div>
    <div class="row mt-3">
        <div class="form-group row">

            <label for="service_site" class="col-sm-2 col-form-label">Date</label>
            <div class="col-sm-3">
                <input type="date" name="histo_debut" class="form-control dateRef" id="histo_debut" value="<?=$filtre['debut']?>">
            </div>
            <div class="col-sm-3">
                <input type="date" name="histo_fin" class="form-control dateDest" id="histo_fin" value="<?=$filtre['fin']?>">
            </div>
            <div class="col-sm-2">
                <button id="doFilterHistoriqueAgents" class="btn btn-primary btn-sm">Appliquer</button>
            </div>
                
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="historique" class="table-striped" style="width:100%">
                <thead>

                    <tr>
                        <th rowspan="2">DATE</th>
                        <th rowspan="2">CONTRAT</th>
                        <th rowspan="2">MATRICULE</th>
                        <th rowspan="2">AGENT</th>
                        <?php if($role == ROLE_CLIENT): ?>
                        <th rowspan="2">PSEUDO FRANCAIS</th>
                        <?php endif; ?>
                        <?php if($role != ROLE_CLIENT): ?>
                            <th rowspan="2">CAMPAGNE</th>
                            <th rowspan="2">SERVICE</th>
                        <?php endif; ?>
                        <th rowspan="2">PLANNING</th>
                        <th colspan="5">POINTAGE</th>
                        <th rowspan="2">RETARD</th>
                        <th colspan="6">SHIFT</th>
                        <th colspan="4">AJUSTEMENT SUP</th>
                        <th rowspan="2">H SUP</th>
                        <th rowspan="2">NB PAUSE</th>
                        <?php if($role != ROLE_CLIENT): ?>
                            <th rowspan="2" class="notexport">Actions</th>
                        <?php endif; ?>
                        
                    </tr>
                    <tr>


                        <th>In</th>
                        <th>Out</th>
                        <th>Am</th>
                        <th>Pm</th>
                        <th>Prod</th>

                        <th>In</th>
                        <th>Out</th>
                        <th>Am</th>
                        <th>Pm</th>
                        <th>Prod</th>
                        <th>Pause</th>

                        <th>Am</th>
                        <th>Pm</th>
                        <th>Prod</th>                 
                        <th>Pause</th>

                    </tr>
                </thead>

                <!-- <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th colspan="4"></th>
                        <th></th> 
                        <th colspan="4"></th>
                        <th></th>
                        <th></th> 
                        <th colspan="2"></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>

                        <th></th>
                    </tr>
                </tfoot> -->
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var total_details = '00:00';
    $(document).ready(function(){
        //initialisation datatable
        historique_table = $("#historique").DataTable({

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
            //ajax : "<?= site_url("histo/getHistoriqueAgentsSup"); ?>",
            ajax : "<?= site_url("histo/getUsersHistorique"); ?>",
            columns : [

                { data : 'shift_day'},
                { data : 'site_contrat'},
                { data : 'usr_matricule'},
                { data : 'usr_prenom'},
            <?php if($role == ROLE_CLIENT): ?>
                { data : 'usr_pseudo'},
            <?php endif; ?>
            <?php if($role != ROLE_CLIENT): ?>
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
                    data : 'entree_planifie',
                    render : function(data, type, row){
                        var myDate = moment(data, 'HH:mm:ss');
                        return myDate.isValid() ? myDate.format('HH:mm') : ''
                    }
                },
                {   
                    data : 'pointage_in',
                    render : function(data, type, row){
                        var myDate = moment(data, 'HH:mm:ss');
                        return myDate.isValid() ? myDate.format('HH:mm') : ''
                    }
                },
                {   
                    data : 'pointage_out',
                    render : function(data, type, row){
                        var myDate = moment(data, 'HH:mm:ss');;
                        return myDate.isValid() ? myDate.format('HH:mm') : ''
                    }
                },
                

                { data : "pointage_temps_am",
                    render : function(data, type, row){
                        return (data) ? data : '';
                    } 
                },
                { data : "pointage_temps_pm",
                    render : function(data, type, row){
                        return (data) ? data : '';
                    } 
                },
                {   
                    data : 'pointage_temps_total',
                    render : function(data, type, row){
                        return (data) ? data.replace('.',':') : '';
                    }
                },

                {   
                    data : 'retard',
                    render : function(data, type, row){
                        if(data == 'absent') return data

                        var myDate = moment(data, 'HH:mm:ss');
                        return myDate.isValid() ? myDate.format('HH:mm') : ''
                    }
                },
                
                {   
                    data : 'shift_begin',
                    render : function(data, type, row){
                        var myDate = moment(data);
                        return myDate.isValid() ? myDate.format('HH:mm') : ''
                    }
                },
                {   
                    data : 'shift_end',
                    render : function(data, type, row){
                        var myDate = moment(data);
                        return myDate.isValid() ? myDate.format('HH:mm') : ''
                    }
                },
                { data : 'shift_temps_am'},
                { data : 'shift_temps_pm' },
                { data : 'shift_prod'},
                { 
                    data : 'total_pause',
                    render : function(data, type, row){
                        return data
                    }
                },
                { 
                    data : 'ajust_temps_am',
                    render : function(data, type, row){
                        return data
                    }
                },
                { 
                    data : 'ajust_temps_pm',
                    render : function(data, type, row){
                        return data
                    }
                },
                { data : 'total_work_ajustement'},
                { 
                    data : 'total_pause_ajustement',
                    render : function(data, type, row){
                        return data
                    }
                },
                { data : null,
                    render : function(data, type, row){
                        return ''
                    } 
                }
                ,
                {   data : 'nb_pause',
                    render : function(data, type, row){
                        return data 
                    } 
                }
                ,
                <?php if($role != ROLE_CLIENT): ?>
                { data : null,
                    render : function(data, type, row){
                        return '<button title="Ajustement" data-shift="' + row.shift_id + '" class="show-ajustement-modal btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil-square-o"></i></button>'
                    } 
                }
                <?php endif; ?>
                
            ],
            "createdRow": function (row, data, index) {
                let rowBgColor = '';
                if(data.retard == 'absent'){
                    //rowBgColor = '#dc3545'
                }else if(data.retard != ''){
                    rowBgColor = '#ffc107'
                }
                //$('td:eq(9)', row).css("background-color", rowBgColor);
                $(row).css("background-color", rowBgColor);
            },
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                total_pointage = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {

                        if (b){ b = b.replace('.',':') }
                        return moment(a, 'HH:mm').add(b, 'HH:mm');
                    }, '00:00' );

                total_prod_details = api
                    .column( 13 )
                    .data()
                    .reduce( function (a, b) {
                        return moment(a, 'HH:mm').add(b, 'HH:mm');
                    }, '00:00' );

                total_ajustement = api
                    .column( 17 )
                    .data()
                    .reduce( function (a, b) {
                        return moment(a, 'HH:mm').add(b, 'HH:mm');
                    }, '00:00' );

                total_nbpause = api
                    .column( 20 )
                    .data()
                    .reduce( function (a, b) {
                        return a + b
                    }, 0 );


                // Update footer
                $( api.column( 8 ).footer() ).html(
                    moment(total_pointage).format('HH:mm')
                );

                $( api.column( 13 ).footer() ).html(
                    moment(total_prod_details).format('HH:mm')
                );

                $( api.column( 20 ).footer() ).html(
                    total_nbpause
                );

                $( api.column( 17 ).footer() ).html(
                    moment(total_ajustement).format('HH:mm')
                );

             

               
            }
        });

        $('#doFilterHistoriqueAgents').on('click', function(){
            let debut = $('#histo_debut').val();
            let fin = $('#histo_fin').val();
            $.ajax({
                url : "<?= site_url('histo/setFiltreHistoriqueAgents') ?>",
                dataType : 'json',
                method : 'post',
                data : {debut : debut, fin : fin},
                success : function(resp){
                    //Mise à jour de la table
                    if(!resp.err){
                        historique_table.ajax.reload();
                    }
                }
            })
        })
    })
</script>