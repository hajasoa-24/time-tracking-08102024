<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Suivi des retards</h2>
        </div>
    </div>

    <div class="row mt-3 ">
        <div class="col-md-12">
            <table id="list-presence" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <!-- <th>Date</th> -->
                        <th>Site</th>
                        <th>Agent</th>
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Campagne</th>
                        <th>Service</th> 
                        <th>Planning</th> 
                        <th>Pointage</th> 
                        <th>Shift</th> 
                        <th>Durée retard</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    var total_pause = "0";
    $(document).ready(function(){
         
    });

    //initialisation datatable
    var presenceTable = $("#list-presence").DataTable({

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
        ajax : "<?= site_url("presence/getListRetard"); ?>",
        columns : [
           /* { 
                data : "day",
                render : function(data, type, row){
                    return moment(data, "YYYY-MM-DD").format('DD-MM-YYYY')
                }
            },*/
            { data : "site" },
            { data : "agent" },
            { data : "matricule" },
            { data : "initiale" },

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

            { 
                data : "planning",
                render : function(data, type, row){
                    var myDate = moment(data, 'HH:mm:ss');
                    return myDate.isValid() ? myDate.format('HH:mm') : ''
                }
            },
            {
                data : "pointage",
                render : function(data, type, row){
                    var myDate = moment(data, 'HH:mm:ss');
                    return myDate.isValid() ? myDate.format('HH:mm') : ''
                }
            },

            {
                data : 'shift',
                render : function(data, type, row){
                    var myDate = moment(data, 'HH:mm:ss');
                    return myDate.isValid() ? myDate.format('HH:mm') : ''
                }
            },
        
            
            { 
                data : 'retard',
                render : function(data, type, row){
                    var myDate = moment(data, 'HH:mm:ss');
                    return myDate.isValid() ? myDate.format('HH:mm') : ''
                }
            }
            
        ]
    });

    
</script>