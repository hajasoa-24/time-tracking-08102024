<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Mes tâches</h3>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="mestaches" class="table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th >Date</th>
                        <th >Tâches</th>
                        <th>Action</th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){

        //initialisation datatable
        var table = $("#mestaches").DataTable({
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
            ajax : "<?= site_url("tache/getmestaches"); ?>",
           


            columns : [
                {
                    data :"tache_date",

                },
      
                {
                    data :"checking_libelle",
                    
                },
                {
                    targets : -1,
                    "width": "10%",
                    data : null,
                    render : function(data,type,row)
                    {

                        const dateActuelle = new Date();
                        const date = new Date(data.tache_date);
                        var jour1 = date.getDate();
                        var mois1 = date.getMonth();
                        var annee1 = date.getFullYear();

                        var jour2 = dateActuelle.getDate();
                        var mois2 = dateActuelle.getMonth();
                        var annee2 = dateActuelle.getFullYear();

                        if (jour1 === jour2 && mois1 === mois2 && annee1 === annee2) 
                        {
                            console.log(data.tache_status);
                            if(data.tache_status === '1.5'){
                                let htmlButtons = '<span class="d-flex"><span title="En cours ..." class="spinner-border btn btn-sm btn-success mr-1"></span>'+'<button title="valider" class="valider btn btn-sm btn-primary mr-1" data-id= '+data.tache_id+'>Valider</button></span>';
                                return htmlButtons;
                            }
                            else{
                                let htmlButtons = '<span><button title="accomplir" class="accomplir btn btn-sm btn-info mr-1" data-id= '+data.tache_id+'>accomplir</button>'+'<span class="encours" style="display:none"><span title="En cours ..." class="spinner-border btn btn-sm btn-success mr-1"></span>'+'<button title="valider" class="valider btn btn-sm btn-primary mr-1" data-id= '+data.tache_id+'>Valider</button></span></span>';
                                return htmlButtons;
                            }
                          

                        }
                        else if ((dateActuelle.getDay()-1) === date.getDay())
                        {
                            let htmlButtons = '<button title="valider" class="btn btn-sm btn-danger mr-1" data-id= '+data.tache_id+' disabled>expirée</button>';
                            return htmlButtons;

                        }
                        else{
                            return null;

                        }
                    }
                        
                }
              
            ],
            "createdRow": function( row, data, dataIndex){
                const dateActuelle = new Date();
                        const date = new Date(data.tache_date);
                        var jour1 = date.getDate();
                        var mois1 = date.getMonth();
                        var annee1 = date.getFullYear();

                        var jour2 = dateActuelle.getDate();
                        var mois2 = dateActuelle.getMonth();
                        var annee2 = dateActuelle.getFullYear();
                        
                       
                            if (jour1 === jour2 && mois1 === mois2 && annee1 === annee2) 
                            {
                                console.log("");
                                
                            }
                            
                            else if ((dateActuelle.getDay()-1) === date.getDay())
                            {
                                console.log("");
                            }
                            else
                            {
                                var table = this.DataTable();
                                table.row(dataIndex).remove().draw(false);
                            }        

            }
     
        });


    table.draw();

    

        $(document).on('click', '.valider', function(){
            var data = table.row($(this).parents('tr')).data();
            let id = $(this).data('id');
            $("#id_tache").val(id);

            modalControl.validation(data);
        })

        $(document).on('click', '.accomplir', function(){

            let id = $(this).data('id');
            var parents = $(this).parents('span');
            parents.find(".encours").css('display','flex');
            parents.find(".accomplir").css('display','none');

            $("#id_tache").val(id);
            modalControl.accomplir(id);
        })

        var modalControl = {
            'validation' : function(){
                $("#validationModal").modal('show');

                
            },

            'accomplir' : function(id){
                $.ajax({
                    type:"POST",
                    url: "<?= site_url("tache/encours"); ?>",
                    data: { id_tache : id},
                    success: function(data)
                    {
                    }
                })

                

            }
        }
    
       
    })
</script>