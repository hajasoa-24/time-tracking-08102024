<style type="text/css">
    table#historique th, table#historique td{
        text-align: center;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Historique des modifications des soldes et des droits </h3>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="soldesdroitsTable" class="table-striped table-bordered" style="width:100%">
                <thead>

                    <tr>
                        <th>Date de la modification</th>
                        <th>Salarié concerné</th>
                        <th>Ancien solde</th>
                        <th>Nouveau Solde</th>
                        <th>Ancien Droit</th>
                        <th>Nouveau Droit</th>
                        <th>Commentaire</th>
                        <th>Modificateur</th>
                        <th>Ip du modificateur</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        
        //initialisation datatable
        var table = $("#soldesdroitsTable").DataTable({
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
            ajax : "<?= site_url("conges/getAllHistoSoldesDroits"); ?>",
            columns : [
                { data : 'histosoldes_datemodif' },
                { data : 'agent_concerne' },
                { data : 'histosoldes_anciensolde' },
                { data : 'histosoldes_nouveausolde' },
                { data : 'histosoldes_anciendroitpermission' },
                { data : 'histosoldes_nouveaudroitpermission' },
                { data : 'histosoldes_commentaire' },
                { data : 'modificateur' },
                { data : 'histosoldes_ipmodificateur'}
            ]
        });

    })
</script>