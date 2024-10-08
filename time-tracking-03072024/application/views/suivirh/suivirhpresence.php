<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Historiques des présences</h2>
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
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Présence</th>
                        <th>Incomplet</th>
                        <th>Motif</th>
                        <th>Modificateur</th>
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
        ajax : "<?= site_url("presence/getAllListPresence"); ?>",
        columns : [
        
            { data : "presence_date" },
            { 
                data : "site_libelle"
            },
            { 
                data : "usr_prenom"
               
            },
            { 
                data : "usr_matricule"
            },
            { 
                data : "usr_initiale"
            },

           
            { 
                data : "presence_present",
                render : function(data, type, row){
                    return (data == 1) ? '<span class="badge bg-success">Présent</span>' : '<span class="badge bg-danger">Absent</span>';
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
               data : "motifpresence_motif"
            },
            { 
               data : "modificateur"
            }

           
        ]
    });

    
</script>