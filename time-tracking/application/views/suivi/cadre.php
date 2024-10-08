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
                        <th >Nom</th>
                        <th >Tâche</th>
                        <th>Commentaire</th>
                        <th></th>
                        

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function()
    {
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
            ajax : "<?= site_url("tache/suivicadre");?>",
           
            columns : [
                {
                    data :"tache_date", 
                },
                {
                    data :"usr_prenom",
                
                },

                {
                    data :'checking_libelle',
                    render: function (data, type, row)
                    { 
                        const dateActuelle = new Date();
                        const date = new Date(row.tache_date);
                        var jour1 = date.getDate();
                        var mois1 = date.getMonth();
                        var annee1 = date.getFullYear();

                        var jour2 = dateActuelle.getDate();
                        var mois2 = dateActuelle.getMonth();
                        var annee2 = dateActuelle.getFullYear();

                        if (jour1 === jour2 && mois1 === mois2 && annee1 === annee2)
                        {
                            if(row.tache_status === '1')
                            {

                            
                                switch(row.tache_frequence)
                                {
                                    case '1':
                                        var button = '<span style="color:blue;">(Tâche journalière)<span>';
                                        return data + '' + button;
                                        break;
                                    case '2':
                                        var button = '<span style="color:blue;">(Tâche hebdomadaire)<span>';
                                        return data + '' + button;
                                        break;
                                    case '3':
                                        var button = '<span style="color:blue;">(Tâche mensuelle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '4':
                                        var button = '<span style="color:blue;">(Tâche trimestrielle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '5':
                                        var button = '<span style="color:blue;">(Tâche chaque 6 mois)<span>';
                                        return data + '' + button;
                                        break;
                                    case '6':
                                        var button = '<span style="color:blue;">(Tâche J+1 férié)<span>';
                                        return data + '' + button;
                                        break;

                                }
                            }

                        

                            else if (row.tache_status === '2')
                            {
                                switch(row.tache_frequence)
                                {
                                    case '1':
                                        var button = '<span style="color:blue;">(Tâche journalière)<span>';
                                        return data + '' + button;
                                        break;
                                    case '2':
                                        var button = '<span style="color:blue;">(Tâche hebdomadaire)<span>';
                                        return data + '' + button;
                                        break;
                                    case '3':
                                        var button = '<span style="color:blue;">(Tâche mensuelle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '4':
                                        var button = '<span style="color:blue;">(Tâche trimestrielle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '5':
                                        var button = '<span style="color:blue;">(Tâche chaque 6 mois)<span>';
                                        return data + '' + button;
                                        break;
                                    case '6':
                                        var button = '<span style="color:blue;">(Tâche J+1 férié)<span>';
                                        return data + '' + button;
                                        break;

                                }
                            }
                            else
                            {
                                switch(row.tache_frequence)
                                {
                                    case '1':
                                        var button = '<span style="color:blue;">(Tâche journalière)<span>';
                                        return data + '' + button;
                                        break;
                                    case '2':
                                        var button = '<span style="color:blue;">(Tâche hebdomadaire)<span>';
                                        return data + '' + button;
                                        break;
                                    case '3':
                                        var button = '<span style="color:blue;">(Tâche mensuelle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '4':
                                        var button = '<span style="color:blue;">(Tâche trimestrielle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '5':
                                        var button = '<span style="color:blue;">(Tâche chaque 6 mois)<span>';
                                        return data + '' + button;
                                        break;
                                    case '6':
                                        var button = '<span style="color:blue;">(Tâche J+1 férié)<span>';
                                        return data + '' + button;
                                        break;

                                }

                            }

                        }
                        else if((dateActuelle.getDay()-1) === date.getDay())
                        {
                            if(row.tache_status === '1')
                            {

                            
                                switch(row.tache_frequence)
                                {
                                    case '1':
                                        var button = '<span style="color:blue;">(Tâche journalière)<span>';
                                        return data + '' + button;
                                        break;
                                    case '2':
                                        var button = '<span style="color:blue;">(Tâche hebdomadaire)<span>';
                                        return data + '' + button;
                                        break;
                                    case '3':
                                        var button = '<span style="color:blue;">(Tâche mensuelle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '4':
                                        var button = '<span style="color:blue;">(Tâche trimestrielle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '5':
                                        var button = '<span style="color:blue;">(Tâche chaque 6 mois)<span>';
                                        return data + '' + button;
                                        break;
                                    case '6':
                                        var button = '<span style="color:blue;">(Tâche J+1 férié)<span>';
                                        return data + '' + button;
                                        break;

                                }
                            }

                        

                            else if (row.tache_status === '2')
                            {
                                switch(row.tache_frequence)
                                {
                                    case '1':
                                        var button = '<span style="color:blue;">(Tâche journalière)<span>';
                                        return data + '' + button;
                                        break;
                                    case '2':
                                        var button = '<span style="color:blue;">(Tâche hebdomadaire)<span>';
                                        return data + '' + button;
                                        break;
                                    case '3':
                                        var button = '<span style="color:blue;">(Tâche mensuelle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '4':
                                        var button = '<span style="color:blue;">(Tâche trimestrielle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '5':
                                        var button = '<span style="color:blue;">(Tâche chaque 6 mois)<span>';
                                        return data + '' + button;
                                        break;
                                    case '6':
                                        var button = '<span style="color:blue;">(Tâche J+1 férié)<span>';
                                        return data + '' + button;
                                        break;

                                }
                            }
                            else
                            {
                                switch(row.tache_frequence)
                                {
                                    case '1':
                                        var button = '<span style="color:blue;">(Tâche journalière)<span>';
                                        return data + '' + button;
                                        break;
                                    case '2':
                                        var button = '<span style="color:blue;">(Tâche hebdomadaire)<span>';
                                        return data + '' + button;
                                        break;
                                    case '3':
                                        var button = '<span style="color:blue;">(Tâche mensuelle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '4':
                                        var button = '<span style="color:blue;">(Tâche trimestrielle)<span>';
                                        return data + '' + button;
                                        break;
                                    case '5':
                                        var button = '<span style="color:blue;">(Tâche chaque 6 mois)<span>';
                                        return data + '' + button;
                                        break;
                                    case '6':
                                        var button = '<span style="color:blue;">(Tâche J+1 férié)<span>';
                                        return data + '' + button;
                                        break;

                                }

                            }

                        }

                        else
                        {
                            return null;
                        }
                        
                    }
                      
                },
                {
                    data :"tache_commentaire",
              
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
                        
                        if(data.tache_status === '1')
                        {
                            if (jour1 === jour2 && mois1 === mois2 && annee1 === annee2) 
                            {
                                let htmlButtons = '<button title="A débuter" class="valider btn btn-sm btn-warning mr-1" data-id= '+data.tache_id+' disabled></i>A débuter</button>';
                                return htmlButtons
                            }
                            else if ((dateActuelle.getDay()-1) === date.getDay())
                            {
                                let htmlButtons = '<button title="valider" class="btn btn-sm btn-danger mr-1" data-id= '+data.tache_id+' disabled>expirée</button>';
                                return htmlButtons;

                            }
                            else
                            {
                                return null;
                            }
                        }
                        else if(row.tache_status === '1.5')
                        {
                            if (jour1 === jour2 && mois1 === mois2 && annee1 === annee2) 
                            {
                                let htmlButtons = '<button title="En cours ..." class="btn btn-sm btn-info mr-1" data-id= '+data.tache_id+'></i>En cours ...</button>';
                                return htmlButtons
                                
                            }
                            else if ((dateActuelle.getDay()-1) === date.getDay())
                            {
                                let htmlButtons = '<button title="En cours ..." class="btn btn-sm btn-info mr-1" data-id= '+data.tache_id+'></i>En cours ...</button>';
                                return htmlButtons

                            }
                            else
                            {
                                return null;
                            }
                           
                        }
                        else if(data.tache_status === '2'){
                            let htmlButtons = '<button title="valider" class="valider btn btn-sm btn-primary mr-1" data-id= '+data.tache_id+'></i>valider</button>';
                            return htmlButtons
                        }
                        else 
                        {
                            let user_nom= '<button class="btn btn-sm btn-success mr-1" disabled>validée par ' +data.tache_usr_validation+'</button>';
                            return user_nom
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
                            
                            else if ((dateActuelle.getDay()-1) === date.getDay() && mois1 === mois2 && annee1 === annee2)
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

        $(document).on('click', '.valider', function()
        {
            var data = table.row($(this).parents('tr')).data();
            let id = $(this).data('id');
            $(this).html('Tâche validée');
                $("#id_tache").val(id);
                $.ajax({
                    type:"POST",
                    url: "<?= site_url("tache/validationtache"); ?>",
                    data: {id_tache : id},
                    success: function(data){

                    }
                })
        })

        var modalControl = 
        {
            'validation' : function()
            {
                $("#validationModal").modal('show');
            },
        }
    
       
    })

</script>