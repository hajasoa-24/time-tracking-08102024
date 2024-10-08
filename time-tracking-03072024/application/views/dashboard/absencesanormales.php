<div class="row container">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Absences anormales</h2>
        </div>
    </div>

    <div class="row mt-3 ">
        <div class="col-md-12">
            <table id="list-absencesanormales" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Prénom</th>
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Site</th> 
                        <th>Motif absence</th>
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
    var absencesanormalesTable = $("#list-absencesanormales").DataTable({

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
        ajax : "<?= site_url("dashboard/getAbsencesAnormales"); ?>",
        columns : [
            { data : 'presence_date' },
            { data : 'usr_prenom' },
            { data : 'usr_matricule' },
            { data : 'usr_initiale' },
            { data : 'site_libelle' },
            { data : 'motif_absence' },
            { data : 'modificateur' }
        ]
    });

    
</script>