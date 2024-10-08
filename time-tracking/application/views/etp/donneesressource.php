<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Mon suivi</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form class="row gx-3 gy-2 align-items-center" method="GET">
                <div class="col-sm-3">
                    <label class="visually-hidden" for="validation-ressource-du">Du</label>
                    <div class="input-group">
                        <div class="input-group-text">Du</div>
                        <input type="date" class="form-control" name="filtreValidationRessourceDu" id="validation-ressource-du" placeholder="Du" value="<?= $filtreValidationRessourceDu ?>">
                    </div>
                </div>
                <div class="col-sm-3">
                    <label class="visually-hidden" for="validation-ressource-au">Au</label>
                    <div class="input-group">
                        <div class="input-group-text">Au</div>
                        <input type="date" class="form-control" name="filtreValidationRessourceAu" id="validation-ressource-au" placeholder="Au" value="<?= $filtreValidationRessourceAu ?>">
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
            <table id="list-donneesressources" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Agent</th>
                        <th>Campagne</th>
                        <th>Mission</th>
                        <th>Process</th>
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Durée</th>
                        <th>Pause</th>
                        <th>Status</th>
                        <th>Quantité</th>
                        <th>Validation</th>
                        
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>


<!-- MODAL DETAIL-->
<div class="modal fade" id="id_detail_task" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Détails</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        
        <div class="modal-body">
            <div class= "col-md-12">
                <table id="details_process" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Commentaire</th>
                            <th>D1</th>
                            <th>D2</th>
                            <th>D3</th>
                            <th>D4</th>
                            <th>Début</th>
                            <th>Fin</th>
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



<script type="text/javascript">
    $(document).ready(function(){
        var detailprocess = new bootstrap.Modal(document.getElementById('id_detail_task'));

        

        //initialisation datatable
        var validationTable = $("#list-donneesressources").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("etp/getMesDatasMcp"); ?>",
            columns : [
                { data : "mcp_date" },
                { data : "usr_prenom" },
                { data : "campagne_libelle" },
                { data : "mission_libelle" },
                { data : "process_libelle" },
                { data : "mcp_datedebut"},
                { data : "mcp_datefin"},
                { data : "mcp_tempstravail"},               
                { data : "mcp_tempspause" },
                /*{ data : "mcp_status" },*/
                {
                    data: "mcp_status",
                    render: function (data, type, row) {
                        let className = '';
                        let statusText = '';

                        switch(parseInt(data)) {
                            case <?php echo MCP_STATUS_ENCOURS; ?>:
                                className = "bg-primary";
                                statusText = "En cours";
                                break;
                            case <?php echo MCP_STATUS_ENPAUSE; ?>:
                                className = "bg-warning";
                                statusText = "En pause";
                                break;
                            case <?php echo MCP_STATUS_TERMINE; ?>:
                                className = "bg-success";
                                statusText = "Terminé";
                                break;
                            default:
                                className = "bg-secondary";
                                statusText = "Indéfini";
                        }

                        return '<span style="font-size: 0.9em;" class="badge ' + className + '">' + statusText + '</span>';
                    }
                },
                { data : "mcp_quantite" },
                { 
                    data : "etatressource_libelle",
                    render: function (data, type, row) {
                        let className = '';
                        if(row.etatressource_facturation == "1") className = "bg-success" 
                        else if(row.etatressource_facturation == "0") className = "bg-secondary"
                        return '<span style="font-size: 0.9em;" class="badge '+className+'">'+data+'</span><button id="details_id" title="Afficher les details" data-mcp="'+row.mcp_id+'" class="details_id btn btn-default btn-sm mr-1"><i class="fa fa-info-circle"></i></button>'
                    }
                }
            ]

        });

        var tabledetailprocess = $("#details_process").DataTable(
            {
                language : {
                    url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
                    },
                    ajax : "<?= site_url("etp/getIdTaskDetail/1") ?>" ,
                    columns : [
                        { data : "mcpdetails_commentaire"},
                        { data : "mcpdetails_detail1"},
                        { data : "mcpdetails_detail2"},
                        { data : "mcpdetails_detail3"},
                        { data : "mcpdetails_detail4"},
                        {
                            data : 'mcpdetails_datedebut',
                            render : function(data, type, row){
                                var myDate = moment(data);
                                return myDate.isValid() ? myDate.format('HH:mm:ss') : ''
                            }
                        },
                        {
                            data : 'mcpdetails_datefin',
                            render : function(data, type, row){
                                var myDate = moment(data);
                                return myDate.isValid() ? myDate.format('HH:mm:ss') : ''
                            }
                        },
        
                    ]
            });


        $(document).on("click", "#details_id", function(){

            let id_mcp = $(this).data("mcp");
            console.log(id_mcp);
            tabledetailprocess.ajax.url('<?= site_url('etp/getIdTaskDetail/') ?>' + id_mcp).load();
            detailprocess.show();

        })

       
    })



</script>