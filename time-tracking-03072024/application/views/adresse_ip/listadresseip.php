<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Liste des adresses ip</h2>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-ip" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Site</th>
                        <th>Adresse Ip</th>
                        <!-- <th>Nom</th> -->
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Username</th>
                        <th>Campagne</th>
                        <th>Service</th>
                        <th>Date de Log</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        //initialisation datatable
        $("#list-ip").DataTable({
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
            ajax : "<?= site_url("ip/getListIp"); ?>",
            columns : [
                { data : "site_libelle" },
                { data : "ip_adresse" },
                /*{ data : "usr_nom" },*/
                { data : "usr_nom" },

                { data : "usr_prenom" },
                { data : "usr_username" },
                { 
                    data : "campagnes",
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
                    data : "services",
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
                { data : "ip_datelog" }
            ]
        });
    })
</script>