<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Suivi pointage des femme de menage etc</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 float-end">
            <form class="row gx-3 gy-2 align-items-center" method="GET">
                <div class="col-sm-3">
                    <label class="visually-hidden" for="dashboard-du">Du</label>
                    <div class="input-group">
                        <div class="input-group-text">Du</div>
                        <input type="date" class="form-control" name="filtrePointageDu" id="dashboard-du" placeholder="Du" value="<?= $filtrePointageDu ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="visually-hidden" for="dashboard-au">Au</label>
                    <div class="input-group">
                        <div class="input-group-text">Au</div>
                        <input type="date" class="form-control" name="filtrePointageAu" id="dashboard-au" placeholder="Au" value="<?= $filtrePointageAu ?>">
                    </div>
                </div>
                
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                </form>
        </div> 
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="pointage-autres" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Site</th>
                        <th>Prénom</th>
                        <th>Matricule</th>
                        <th>Service</th>
                        <th>Entrée</th>
                        <th>Sortie</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //initialisation datatable
        $("#pointage-autres").DataTable({
        dom: 'Blfrtip',
            buttons: [
                {
                    extend : 'excelHtml5',
                    exportOptions : {
                        orthogonal : 'export'
                    }
                },
                
            ],
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("pointage/getPointageAutres"); ?>",
            columns : [
                { data : 'date'},
                { data : 'site_libelle'},
                { data : 'usr_prenom'},
                { data : 'usr_matricule'},
                { data : 'service_libelle',
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
                { data : 'att_in'},
                { data : 'att_break'},
                
            ]
        });
    })
</script>