<div class="row">
	<div class="row">
		<div class="col-md-12 title-page">
			<h3>Jours fériés</h3>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="card container">
				<form method="post" id="" action="<?= site_url('jourferie/addJourferie') ?>">
					<div class="form-group">
						<label for="description_ferie">Description:</label><br />
						<input
						required="required"
							class="form-control"
							type="text"
							id="description_ferie"
							name="description_ferie"
							style="width: 70%"
						/>
					</div>

					<div class="form-group">
						<label for="date_ferie">Date:</label>
						<input
                            class="form-control"
							type="date"
							id="date_ferie"
							name="date_ferie"
							style="width: 70%"
						/>
					</div>

					<button
						type="submit"
						id="save_import"
						class="btn btn-primary"
                        style="float:right"
					>
						Ajouter
					</button><br />
				</form>
			</div>
		</div>

		<div class="col-md-8">
			
			<table
				id="holidays"
				class="table-striped table-bordered"
				style="width: 100%"
			>
				<thead>
					<tr>						
						<th>Date</th>
						<th>Description</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
    <script type="text/javascript">

$(document).ready(function(){
	$(document).on('click', '.deleteferie', function() {
    var table = $("#holidays").DataTable();
    var row = $(this).closest('tr');
    var rowData = table.row(row).data();
    var id = $(this).data('id');

    $.ajax({
        type: "POST",
        url: "<?= site_url('jourferie/delete'); ?>",
        data: {id_ferie: id},
        success: function(response) {
			var jsonResponse = JSON.parse(response);
			if (jsonResponse.success) {
                table.row(row).remove().draw(false);
            } else {
                // Gérer les erreurs ou afficher un message d'erreur
            }    
        },
        error: function(xhr, status, error) {
            // Gérer les erreurs ou afficher un message d'erreur
        }
    });
});
	$('#selectyear').on('change',function(){
		var year = this.value;
		console.log(year);
	});
    var table = $("#holidays").DataTable({
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
        ajax : "<?= site_url("jourferie/getferie"); ?>",
        columns : [
			{
				data:"holidays_date",			
            },
            {
                data :"holidays_libelle",

            },
			{
                    data : null,
                    render: function ( data, type, row ) {
                        return '<button title="supprimer" data-id="'+data.holidays_id+'" class="deleteferie btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button>';
                    }
                }
        ]

});
})

    //initialisation datatable
   


</script>
