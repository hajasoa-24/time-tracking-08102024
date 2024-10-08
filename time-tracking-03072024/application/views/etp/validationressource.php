<style>
.custom-table {
  width: 100%;
  table-layout: fixed;
}

.custom-table th, .custom-table td {
  word-wrap: break-word;
  overflow-wrap: break-word;
  overflow: hidden;
}
</style>



<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Valider les ressources</h2>
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
            <table id="list-validationressource" class="table-striped table-hover" style="width:100%">
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
                        <th>CA</th>
                        <th>Validation</th>
                        <th class="notexport" >Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- MODAL VALIDATION RESSOURCE -->
<div class="modal fade" id="modalvalidateressource" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalvalidateressource" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Qualifier la ressource</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            <form id="formvalidateressource" name="formvalidateressource">
                    <div class="mb-3 row">
                        <label for="mcp_agent" class="col-sm-4 col-form-label">Agent </label>
                        <div class="col-sm-8">
                            <input type="text" readonly name="agent" class="form-control" value="" id="ressource_agent"/>
                        </div>
                    </div>

                    <div class="mb-3 row">

                        <label for="mcp_client" class="col-sm-4 col-form-label">Client</label>
                        <div class="col-sm-8">
                            <input type="text" readonly name="client" class="form-control" value="" id="ressource_client"/>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="mcp_mission" class="col-sm-4 col-form-label">Mission</label>
                        <div class="col-sm-8">
                            <input type="text" readonly name="mission" class="form-control" value="" id="ressource_mission"/>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="mcp_process" class="col-sm-4 col-form-label">Process</label>
                        <div class="col-sm-8">
                            <input type="text" readonly name="process" class="form-control" value="" id="ressource_process"/>
                        </div>
                    </div>
    
                    
                    <div class="mb-3 row">
                        <label for="mcp_etatressource" class="col-sm-4 col-form-label">Qualification</label>
                        <div class="col-sm-8">
                            <select name="etatressource" id="mcp_etatressource" class="form-control" required>
                                <option value=""></option>
                                <?php foreach($listEtatRessource as $etatRessource) : ?>
                                    <option class="<?= ($etatRessource->etatressource_facturation == "1") ? "etatfacture" : "etatnotfacture" ; ?>" value="<?= $etatRessource->etatressource_id ?>"><?= $etatRessource->etatressource_libelle ?></option>
                                <?php endforeach; ?>
                                
                            </select>
                        </div>
                    </div>
    
                    
                    <input type="hidden" name="action" id="ressource_action" />
                    <input type="hidden" name="id" id="ressource_id" />
                    <input type="hidden" name="agent" id="ressource_idagent" value="" />

                </form>    
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="validateEtatRessource" class="btn btn-primary">Valider</button>
            </div>
            </div>
        </div>
        </div>

    <!-- END MODAL VALIDATION RESSOURCE -->



    <!-- MODIFICATION QUANTITE RESSORCE -->
    <div class="modal fade" id="modaladdquantiteressource" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modaladdquantiteressource" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter quantité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="id_mcp">
                        <div class="col-sm-3">
                            <label for="Mcpquantite">Quantité</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="Mcpquantite" class="form-control">
                        </div>
                    </div>
    
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" id="addormodifyquantity" class="btn btn-primary">Enregister</button>
                </div>
            </div>
        </div>
    </div>

    <!-- END MODAL MODIFICATION QUANTITE RESSORCE -->

    <div class="modal fade" id="id_detail_task" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Détails</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">
            <!-- <div class = "row"> -->
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
                <!-- </div> -->

            </div>
        </div>
      
        <div class="modal-footer">
            <button type="button" class="btn btn-" data-bs-dismiss="modal">Fermer</button>
      
        </div>

    </div>
    </div>


<script type="text/javascript">
    $(document).ready(function(){
        var validateRessourceModal = new bootstrap.Modal(document.getElementById('modalvalidateressource'));
        var addquantiteressourcemodal = new bootstrap.Modal(document.getElementById('modaladdquantiteressource'));
        var detailprocess = new bootstrap.Modal(document.getElementById('id_detail_task'));


        //initialisation datatable
        var validationTable = $("#list-validationressource").DataTable({
        
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
            ajax : "<?= site_url("etp/getMcpDatas"); ?>",
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
                {   data : null,
                    render: function(data,type,row){

                        if (data.mcp_quantite == null){
                            return '<button title="Ajouter une quantité" data-mcpquantity="'+data.mcp_id+'" class="quantite-ressource btn btn-primary btn-sm mx-1" value="0"><i class="fa fa-edit"></i></button>'
                        }

                        else{
                            return data.mcp_quantite;
                        }
                    }

                },
                { data : "mcp_ca" },
                { 
                    data : "etatressource_libelle",
                    render: function (data, type, row) {
                        let className = '';
                        if(row.etatressource_facturation == "1") className = "bg-success" 
                        else if(row.etatressource_facturation == "0") className = "bg-secondary"
                        return '<span style="font-size: 0.9em;" class="badge '+className+'">'+data+'</span>'
                    }
                },
                {
                    data: null,
                    render: function ( data, type, row ) {
                        return '<button title="Qualifier la ressource" data-mcp="'+data.mcp_id+'" class="validate-ressource btn btn-success btn-sm mx-1" value="1"><i class="fa fa-check"></i></button><button title="Ne pas facturer la ressource" data-mcp="'+data.mcp_id+'" class="cancel-ressource btn btn-default btn-sm mx-1" value="0"><i class="fa fa-times"></i></button><button id="details_id" title="Afficher les details" data-mcp="'+data.mcp_id+'" class="details_id btn btn-default btn-sm mr-1"><i class="fa fa-info-circle"></i></button>' ;
                    }
                }
            ]

        });

        var tabledetailprocess = $("#details_process").DataTable({
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
            tabledetailprocess.ajax.url('<?= site_url('etp/getIdTaskDetail/') ?>' + id_mcp).load();


            detailprocess.show();

        })


       
        $(document).on('click', '.validate-ressource, .cancel-ressource', function(){
            let mcpId = $(this).data('mcp');
            let btnValue = this.value;
            $('#etpaction').val('');
            let data = {};
            $.ajax({
                url : "<?= site_url('apietp/ressource/') ?>" + mcpId,
                datatype : 'json',
                data : data,
                type : 'get',
                success : function(resp){
                    if(!resp.err){
                        $('#ressource_id').val(resp.data.mcp_id);
                        $('#ressource_idagent').val(resp.data.mcp_agent);
                        $('#ressource_agent').val(resp.data.usr_prenom);
                        $('#ressource_client').val(resp.data.campagne_libelle);
                        $('#ressource_mission').val(resp.data.mission_libelle);
                        $('#ressource_process').val(resp.data.process_libelle);
                        $('#ressource_action').val('validate');

                        $('option.etatfacture, option.etatnotfacture').hide();
                        if(btnValue == '1'){
                            $('option.etatfacture').show();
                        }else if(btnValue == '0'){
                            $('option.etatnotfacture').show();
                        }
                        validateRessourceModal.show();
                    }
                }
            })
        });

        $(document).on('click', '#validateEtatRessource', function(){
            let formdata = $('#formvalidateressource').serializeArray();

            $.ajax({
                url : '<?= site_url('apietp/activity/') ?>',
                method : 'put',
                dataType : 'json',
                data : formdata,
                success : function(resp)
                {
                    //alert(resp.message);
                    validateRessourceModal.hide();
                    validationTable.ajax.reload();
                } 
            })
        });


        $(document).on('click', '.quantite-ressource', function(){
            let mcpId = $(this).data("mcpquantity");
            $("#id_mcp").val(mcpId);

            addquantiteressourcemodal.show();

        })


        $(document).on("click","#addormodifyquantity", function()
        {
            let mcp_id = $("#id_mcp").val();
            let mcpquantity = $("#Mcpquantite").val();

            $.ajax({
                    type:"POST",
                    url: "<?= site_url("Etp/addQuantityProduction"); ?>",
                    data: { mcp_id : mcp_id, mcpquantity:mcpquantity},
                    success: function(data)
                    {
                        validationTable.ajax.reload();
                        addquantiteressourcemodal.hide();
                        
                    }
            })
        })
    })


</script>