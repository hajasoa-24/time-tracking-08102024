<div class="row">
	<div class="row">
		<div class="col-md-12 title-page">
			<h3>Axe</h3>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="card container">
				<form method="post" id="" action="<?= site_url('transport/addaxe') ?>">
					<div class="form-group" style="width: 70%">
						<label for="heuretransport">Heure</label><br />
                        <select name="heure" id="heure" class="form-control" required>
                                <option value=""></option>
                                <?php if(isset($top['heuretransport'])) : ?>
                                    <?php foreach($top['heuretransport'] as $heure) : ?>
                                        <option value="<?= $heure->heuretransport_id ?>"><?= $heure->heuretransport_heure ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                        </select>
						
					</div>

					<div class="form-group" style="width: 70%">
					<label for="axe">Axe</label><br />

						<select name="axe" id="axe" class="form-control" required>
									<option value=""></option>
									<?php if(isset($top['listAxe'])) : ?>
										<?php foreach($top['listAxe'] as $axe) : ?>
											<option value="<?= $axe->axe_id ?>"><?= $axe->axe_libelle ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
							</select>
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
				id="axedata"
				class="table-striped table-bordered"
				style="width: 100%"
			>
				<thead>
					<tr>						
						<th>Heure</th>
						<th>Axe</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<div
  class="modal fade"
  id="ModificationTransport"
  data-bs-backdrop="static"
  data-bs-keyboard="false"
  tabindex="-1"
  aria-labelledby="ModificationTransport"
  aria-hidden="true"
>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">
            Modification Axe     
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>

      <div class="modal-body">
        <div class="form-group row">
          <form
            id="confirm"
            name="confirme"
            action="<?= site_url('transport/transportupdate') ?>"
            method="post"
          >
                    
                    <label for="Axe">Axe : </label>
                    <select name="axetoupdate" id="axeupdate" class=" col-md-6 form-control" required>
                                <option value=""></option>     
                    </select><br>
                   

            <input
              type="hidden"
              name="id_transportuser"
              id="id_transportuser"
              value=""
            />
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Annuler
        </button>
        <button
          type="submit"
          id="confirmDesactivateUser"
          value="save_utilisateur"
          class="btn btn-primary"
        >
          Modifier
        </button>
        </form>

      </div>
    </div>
  </div>
</div>
    <script type="text/javascript">

$(document).ready(function(){
	$(document).on('click', '.deleteaxe', function() {
    var table = $("#axedata").DataTable();
    var row = $(this).closest('tr');
    var rowData = table.row(row).data();
    var id = $(this).data('id');

    $.ajax({
        type: "POST",
        url: "<?= site_url('transport/deleteAxetransport'); ?>",
        data: {id_axetransport: id},
        success: function(response) {
			var jsonResponse = JSON.parse(response);
			console.log(jsonResponse);
			table.row(row).remove().draw(false);

    
        },
       
    });
});
	$('#selectyear').on('change',function(){
		var year = this.value;
		console.log(year);
	});



    var table = $("#axedata").DataTable({
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
        ajax : "<?= site_url("transport/getAxe"); ?>",
        columns : [
			{
				data:"heuretransport_heure",			
            },
            {
                data :"axe_libelle",

            },
			{
                    data : null,
                    render: function ( data, type, row ) {
                        return '<button title="supprimer" data-id="'+data.axe_id+'" class="deleteaxe btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button><button title="modifer" data-id="'+data.axe_id+'" class="updatetransport btn btn-primary btn-sm mr-1"><i class="fa fa-edit"></i></button>';
                    }
                }
        ]

});
})

    //initialisation datatable
   


</script>
