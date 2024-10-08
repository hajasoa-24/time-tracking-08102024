<style type="text/css">
    table#historique th, table#historique td{
        text-align: center;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Mon historique</h3>
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
                <button id="doFilterHistorique" class="btn btn-primary btn-sm">Appliquer</button>
            </div>
                
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="historique" class="table-striped table-bordered" style="width:100%">
                <thead>

                    <tr>
                        <th rowspan="2">DATE</th>
                        <th rowspan="2">PLANNING</th>
                        <th rowspan="2">RETARD</th>
                        <th colspan="5">POINTAGE</th>
                        <th colspan="6">SHIFT</th>
                        <th colspan="4">AJUSTEMENT SUP</th>
                        <th rowspan="2">H SUPP</th>
                        <th rowspan="2">NB PAUSE</th>
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
                        <th colspan="5"></th>
                        <th colspan="5"></th> 
                        <th colspan="4"></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>  -->
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    var total_details = '00:00';

    $(document).ready(function(){
        //initialisation datatable
        
        var historiqueTable = $("#historique").DataTable({


            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            //ajax : "<?= site_url("histo/getHistorique"); ?>",
            ajax : "<?= site_url("histo/getUsersHistorique/") . $user; ?>",
            columns : [

                { data : "shift_day" },
                {
                    data : 'entree_planifie',
                    render : function(data, type, row){
                        var myDate = moment(data, 'HH:mm:ss');
                        return myDate.isValid() ? myDate.format('HH:mm') : ''
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
                        /*data= data.replace('.',':');*/
                        if(data != null && data !== undefined) data= data.replace('.', ':');
                        var myDate = moment(data, 'H:mm');
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
                { data : 'shift_temps_am',
                    render : function(data, type, row){
                        return (data) ? data : '';
                    } 
                },
                { data : 'shift_temps_pm',
                    render : function(data, type, row){
                        return (data) ? data : '';
                    } 
                },
                { data : 'shift_prod'},
                { 
                    data : 'total_pause',
                    render : function(data, type, row){
                        //let time = data.replace('mn','');
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
                {   data : null,
                    render : function(data, type, row){
                        return ''
                    } 
                },
                
                
                {   data : 'nb_pause',
                    render : function(data, type, row){
                        return data 
                    } 
                }
            ],
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
                console.log(api.columns().data())
                /*total_pointage = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        b = b.replace('.',':')
                        return moment(a, 'HH:mm').add(b, 'HH:mm');
                    }, '00:00' );*/

                total_prod_details = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        //b = b.replace('.',',')
                        //return moment(a, 'HH:mm').add(b, 'HH:mm');
                        return parseFloat(a) + parseFloat(b);
                    }, '0' );

                total_ajustement = api
                    .column( 16 )
                    .data()
                    .reduce( function (a, b) {
                        //return moment(a, 'HH:mm').add(b, 'HH:mm');
                        return parseFloat(a) + parseFloat(b);
                    }, '0' );

                total_pause_detail = api
                    .column( 13 )
                    .data()
                    .reduce( function (a, b) {
                       
                        return moment(a, 'HH:mm').add(b, 'HH:mm');
                    }, '00:00' );

                total_pause_ajusement = api
                    .column( 17 )
                    .data()
                    .reduce( function (a, b) {
                       
                        return moment(a, 'HH:mm').add(b, 'HH:mm');
                    }, '00:00' );

                total_nbpause = api
                    .column( 19 )
                    .data()
                    .reduce( function (a, b) {
                        return a + b
                    }, 0 );

                // Update footer
                /*$( api.column( 3 ).footer() ).html(
                    moment(total_pointage).format('HH:mm')
                );*/
               
               /* $( api.column( 7 ).footer() ).html(
                    parseFloat(total_prod_details).toFixed(2).replace('.',':')
                    //moment(total_prod_details).format('DD[j] HH:mm')
                );*/


                $( api.column( 13 ).footer() ).html(
                    moment(total_pause_detail).format('HH:mm')
                );

                /*$( api.column( 16 ).footer() ).html(
                    //moment(total_ajustement).format('HH:mm')
                    parseFloat(total_ajustement).toFixed(2).replace('.',':')
                );*/

                 $( api.column( 17 ).footer() ).html(
                    moment(total_pause_ajusement).format('HH:mm')
                );

                $( api.column( 19 ).footer() ).html(
                    total_nbpause
                );
            }
        });

        $('#doFilterHistorique').on('click', function(){
            let debut = $('#histo_debut').val();
            let fin = $('#histo_fin').val();
            $.ajax({
                url : "<?= site_url('histo/setFiltreHistorique') ?>",
                dataType : 'json',
                method : 'post',
                data : {debut : debut, fin : fin},
                success : function(resp){
                    //Mise à jour de la table
                    if(!resp.err){
                        historiqueTable.ajax.reload();
                    }
                }
            })
        })
    })
</script>