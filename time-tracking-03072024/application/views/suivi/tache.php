<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3></h3>
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
                        <th >Tâche</th>
                        <th >Commentaire</th>
                        <th>Nom</th>
                        <th></th>

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
            ajax : "<?= site_url("tache/getmessuivis"); ?>",
           
            columns : [
                {
                    data :"tache_date"
                },

                {
                    data :"checking_libelle",  
                },
                {
                    data :"tache_commentaire",

                },
                {
                    data :"usr_prenom",

                },
                {
                    targets : -1,
                    "width": "10%",
                    data : null,
                    render : function(data, row, type)
                    {
                        console.log(data)
                        if(data.tache_status === '2'){
                            let htmlButtons = '<button title="valider" class="valider btn btn-sm btn-primary mr-1" data-id= '+data.tache_id+' ></i>valider</button>';
                            return htmlButtons
                        }
                        else {
                            let user_nom= '<p>valider par '+data.tache_usr_validation+'</p>';
                            return user_nom
                        }
                    }
                }
               
            ],
            

        });

       

    table.draw();

        
            $(document).on('click', '.valider', function(){
            var data = table.row($(this).parents('tr')).data();
            let id = $(this).data('id');
            $("#id_tache").val(id);
            $.ajax({
                type:"POST",
                url: "<?= site_url("tache/validationtache"); ?>",
                data: { id_tache : id},
                success: function(data)
                {
                    table.draw();

                }
            })

        })//ajout commentaire

        var modalControl = {
            'validation' : function(){
                $("#validationModal").modal('show');

                
            },
        }
    
       
    })
</script>