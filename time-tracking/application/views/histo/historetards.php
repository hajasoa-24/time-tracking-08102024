<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Historique des retards</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form class="row gx-3 gy-2 align-items-center" method="GET">
                <div class="col-sm-3">
                    <label class="visually-hidden" for="histo-retard-du">Du</label>
                    <div class="input-group">
                        <div class="input-group-text">Du</div>
                        <input type="date" class="form-control" name="filtreHistoRetardDu" id="histo-retard-du" placeholder="Du" value="<?= $filtreHistoRetardDu ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="visually-hidden" for="histo-retard-au">Au</label>
                    <div class="input-group">
                        <div class="input-group-text">Au</div>
                        <input type="date" class="form-control" name="filtreHistoRetardAu" id="histo-retard-au" placeholder="Au" value="<?= $filtreHistoRetardAu ?>">
                    </div>
                </div>
                
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div> 
    </div>

    <div class="row mt-3 ">
        <div class="col-md-12">
            <table id="list-retard" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Site</th> 
                        <th>Prénom</th>
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Campagne</th>
                        <th>Service</th>                        
                        <th>Nombre de retard</th>
                        <th>Durée de retard</th>
                        <th class="notexport">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- MODAL DETAILS RETARDS PAR AGENT -->
<div class="modal fade" id="modalDetailsRetards" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Détails des retards" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Détails des retards</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">
        <div class="col-md-12">
            <table id="details-retards" class="table table-striped table-hover" >
                <thead>
                    <tr>
                        <th>Date</th> 
                        <th>Prénom</th>
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Planning</th>
                        <th>Pointage</th>                        
                        <th>Shift</th>
                        <th>Durée de retard</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        
      </div>
      
        <div class="modal-footer">
            <button type="button" class="btn btn-" data-bs-dismiss="modal">Fermer</button>
      
        </div>

  </div>
</div>
<!-- END MODAL SUPPRESSION UTILISATEUR -->

<script type="text/javascript">

    $(document).ready(function(){
         
    });

    //initialisation datatable
    var retardTable = $("#list-retard").DataTable({

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
        ajax : "<?= site_url("retard/getUsersHistoriqueRetard"); ?>",
        columns : [
        
            { data : 'site_libelle' },
            { data : 'usr_prenom' },
            { data : 'usr_matricule' },
            { data : 'usr_initiale' },
            { data : 'listcampagne',
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
            { data : 'listservice',
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
            { data : 'nombre_retard' },
            { data : 'total_retard' },
            {
                data : null,
                render: function ( data, type, row ) {
                    return '<button title="Détails retards" data-user="'+data.usr_id+'" class="afficher-details btn btn-default btn-sm mr-1"><i class="fa fa-info-circle"></i></button>';
                }
            }
        ]
    });

    $(document).on('click', '.afficher-details', function(){
        //Chargement des données via ajax
        let user = $(this).data('user');
        $('#details-retards tbody').html('');
        $.ajax({
            url : "<?= site_url('retard/getHistoretardsDetails') ?>",
            method : 'post',
            dataType : 'json',
            data : {'user': user},
            success : function(response){
                let data = '';
                if(!response.err){
                    response.data.forEach(function(item){
                        //convert minutes to time with momentjs
                        //let duree = moment.duration( (item.duree_retard*60), 'secondes');
                        //console.log(duree)
                        //let formatted_duree = moment.utc(moment.duration((item.duree_retard*60), "seconds").asMilliseconds()).format("HH:mm:ss");
                        data += '<tr><td>'+item.jour+'</td><td>'+item.usr_prenom+'</td><td>'+item.usr_matricule+'</td><td>'+item.usr_initiale+'</td><td>'+item.planning_entree+'</td><td>'+item.pointage_in+'</td><td>'+item.shift_begin+'</td><td>'+item.duree_retard_formatted+'</td></tr>'
                    })
                    
                }else{
                    data = '<tr><td colspan="8">Aucun retard trouvé !</td></tr>'
                }

                $('#details-retards tbody').html(data);
            }
        })
        $('#modalDetailsRetards').modal('show');
    });


    
</script>