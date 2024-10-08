<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Historique des présences</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form class="row gx-3 gy-2 align-items-center" method="GET">
                <div class="col-sm-3">
                    <label class="visually-hidden" for="histo-presence-du">Du</label>
                    <div class="input-group">
                        <div class="input-group-text">Du</div>
                        <input type="date" class="form-control" name="filtreHistoPresenceDu" id="histo-presence-du" placeholder="Du" value="<?= $filtreHistoPresenceDu ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="visually-hidden" for="histo-presence-au">Au</label>
                    <div class="input-group">
                        <div class="input-group-text">Au</div>
                        <input type="date" class="form-control" name="filtreHistoPresenceAu" id="histo-presence-au" placeholder="Au" value="<?= $filtreHistoPresenceAu ?>">
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
            <table id="list-presence" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th> 
                        <th>Site</th>
                        <th>Agent</th>
                        <?php if($role == ROLE_CLIENT): ?>
                        <th>Pseudo français</th>
                        <?php endif; ?>
                        <?php if($role != ROLE_CLIENT): ?>
                        <th>Campgane</th>
                        <th>Service</th>
                        <?php endif; ?>
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Présence</th>
                        <th>Incomplet</th>
                        <th>Motif</th>
                        <th>Modificateur</th>
                        <?php if($role != ROLE_CLIENT): ?>
                        <th class="notexport">Action</th>
                        <?php endif; ?>
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
        ajax : "<?= site_url("presence/getListhistoPresence"); ?>",
        columns : [
        
            { data : "presence_date" },
            { 
                data : "site_libelle"
            },
            { 
                data : "usr_prenom"
               
            },
            <?php if($role == ROLE_CLIENT): ?>
            { 
                data : "usr_pseudo"
               
            },
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
                data : "usr_matricule"
            },
            { 
                data : "usr_initiale"
            },

           
            { 
                data : null,
                render : function(data, type, row){
                    
                    if(row.planning_off == '1') 
                        return '<span class="badge bg-secondary">OFF</span>';
                    else if(row.conge_id != null)
                        return '<span class="badge bg-info">CONGE</span>';
                    else
                        return (row.presence_present == 1) ? '<span class="badge bg-success">PRESENT</span>' : '<span class="badge bg-danger">ABSENT</span>';
                }
            },

            {
                data : "motifpresence_incomplet",
                render : function(data, type, row){
                    if(type === 'export'){
                        return (data == '1') ? 'OUI' : ''; 
                    }else{
                        return (data == '1') ? '<i class="fa fa-check btn-warning" ></i>' : ''; 
                    }
                }
            },
           
            { 
                data : 'motif_libelle',
                render : function(data, type, row){
                    if(row.presence !== '1'){
                        if(row.motif_id !== null){
                            return data;
                        }else{
                             return (row.typeconge_libelle ) ? (row.typeconge_libelle) : row.motifpresence_motif;
                        }
                       
                    }
                    return '';
                }
            },
            { 
               data : "modificateur"
            },
            <?php if($role != ROLE_CLIENT): ?>
            { 
                data : null,
                render : function(data, type, row){
                    console.log(data);
                    return '<button title="Gérer le motif" data-redirect="true" data-motif="'+data.motifpresence_motif+'" data-incomplet="'+data.motifpresence_incomplet+'" data-day="'+data.presence_date+'" data-presence="'+data.presence_id+'" data-shift="'+data.presence_shift+'" data-agent="'+data.usr_id+'" class="edit-presence btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>';
                } 
            }
            <?php endif; ?>
        ]
    });

    
</script>