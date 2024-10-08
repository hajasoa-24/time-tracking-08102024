<?php 
    if($this->session->flashdata('msg') != null){
        echo "<script>alert(".$this->session->flashdata('msg').")</script>";
    }
?>
<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Liste agent</h2>
        </div>
    </div>

    <div class="row mt-3 ">
        <div class="col-md-12">
            <table id="list-presence" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Site</th>
                        <th>Agent</th>
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Campagne</th>
                        <th>Service</th> 
                        <th>Présence</th>
                        <th class="notexport">Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<script type="text/javascript">
    var total_pause = "0";
    $(document).ready(function(){
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
        ajax : "<?= site_url('presence/getListPresence'); ?>",
        columns : [
        /* { 
            data : "day",
            render : function(data, type, row){
                return moment(data, "YYYY-MM-DD").format('DD-MM-YYYY')
            }
        },*/
        { data : "site_libelle" },
        { data : "usr_prenom" },
        { data : "usr_matricule" },
        { data : "usr_initiale" },

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

        { 
            data : null,
            render : function(data, type, row){
                
                if(row.planning_off == '1') 
                    return '<span class="badge bg-secondary">OFF</span>';
                else if(row.conge_id != null)
                    return '<span class="badge bg-info">CONGE</span>';
                else
                    return (row.presence == 1) ? '<span class="badge bg-success">Présent</span>' : '<span class="badge bg-danger">Absent</span>';
            }
        },  
        { 
            data : null,
            render : function(data, type, row){
                return '<button title="Ajouter son photo de profil" data-motif="'+data.motifpresence_motif+'" data-incomplet="'+data.motifpresence_incomplet+'" data-day="'+data.day+'" data-presence="'+data.presence+'" data-shift="'+data.shift_id+'" data-agent="'+data.usr_id+'" class="edit-presence btn btn-secondary btn-sm mr-1"><i class="fa fa-upload"></i></button>';
            } 
        }
        ]
        });  
    });
</script>