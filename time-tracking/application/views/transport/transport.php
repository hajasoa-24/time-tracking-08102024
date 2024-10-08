<div class="row">
      <!-- <div class="row mt-3">
        <div class="form-group row">
          <label for="service_site" class="col-sm-2 col-form-label">Date</label>
          <div class="col-sm-3">
            <input
              type="date"
              name="debut"
              class="form-control dateRef"
              id="transport_debut"
              value="<?=$filtretranpsort['debut']?>"
            />
          </div>
          <div class="col-sm-3">
            <input
              type="date"
              name="fin"
              class="form-control dateDest"
              id="transport_fin"
              value="<?=$filtretranpsort['fin']?>"
            />
          </div>
          
          <div class="col-sm-2">
            <button id="dotransport" class="btn btn-primary btn-sm" type="submit">
              Appliquer
            </button>
          </div>
          
      </div> -->
    <!-- </div> -->
    <div class="row mt-3">
    <div class="col-sm-2">
            <button id="importexel" class="btn btn-primary btn-sm" >
              Export par axe
            </button>
          </div>
        <br><br><br>
      <div class="col-md-12">
        <table
          id="Transport"
          class="table-striped table-bordered"
          style="width: 100%"
        >
          <thead>
            <tr>
              <th>Date</th>
              <th>Nom</th>
              <th>HS planifiée </th>
              <th>Heure</th>
              <th>Axe</th>
              <th>Quartié</th>
              <th>Site</th>
              <th>Service/Campagne</th>
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
            Modification Transport      
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


    $(document).ready(function()
    {
      var axesquartier = [];


      $.ajax({
    url: '<?= site_url('transport/getAxequartier') ?>',
    method: "POST",
    dataType: "json",
    success: function (response) {
       axesquartier = response.data;

    }});

      $.ajax({
    url: '<?= site_url('transport/getAxe') ?>',
    method: "POST",
    dataType: "json",
    success: function (response) {
        var axes = response.data;
        
        // Initialisation du DataTable
        $("#Transport").DataTable({
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
            ajax : "<?= site_url("transport/getsuivitransport"); ?>",
            columns : [
                {data : "date"},
                {data : "usr_prenom"},
                {data : "planning_sortie"},
                {data : "heuretransport_heure"},
                {data : "axe_libelle"},
                {data : "transportuser_quartier"},
                {data : "site_libelle"},
                {data : "campagnes_et_services",
                  render : function(data, type, row){
                        let limit = 50;
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
                    render: function ( data, type, row ) {
                        return '<button title="modifer" data-id="'+data.transportuser_id+'" class="updatetransport btn btn-primary btn-sm mr-1"><i class="fa fa-edit"></i></button>';
                    }
                }
            ],
            drawCallback: function(settings) {
    var api = this.api();
    api.rows().every(function() {
        var data = this.data();
        var matchFound = false;

        // Comparer les données avec les données obtenues via la requête AJAX
        axes.forEach(function(axe) {
            if (data.axe_libelle === axe.axe_libelle && data.heuretransport_heure === axe.heuretransport_heure) {
                matchFound = true;
            }
        });

        // Si la correspondance est trouvée dans axes, marquer en rouge (prioritaire)
        if (matchFound) {
            // Vérifier si l'élément est également trouvé dans axesquartier
            var isInAxesQuartier = false;
            axesquartier.forEach(function(axesquartiers) {
              if (data.axe_libelle == axesquartiers.axe_libelle && data.transportuser_quartier == axesquartiers.quartier_libelle && data.heuretransport_heure == axesquartiers.heuretransport_heure) {
                    isInAxesQuartier = true;
                }
            });

            // Si l'élément n'est pas trouvé dans axesquartier, mettre en bleu
            if (!isInAxesQuartier) {
                $(this.node()).css('color', 'blue');
            }
        } 
        // Si aucune correspondance n'est trouvée dans axes, mettre en rouge toute la ligne
        else {
            $(this.node()).css('color', 'red');
        }
    });
}
        });
    },
    error: function (xhr, status, error) {
        console.error("Error:", error);
    }
});


    $(document).on("click", "#importexel", function () {
      window.location.assign("<?= site_url('transport/index') ?>");

    });
    

    $(document).on("click", ".updatetransport", function () {
      $('#axeupdate').empty();

      let transportusr_id = $(this).data("id");
      $("#id_transportuser").val(transportusr_id);

      console.log(transportusr_id);
      $.ajax({
                url : "<?= site_url('transport/getAxetransportuser') ?>",
                dataType : 'json',
                method : 'post',
                data : {id_transportuser:transportusr_id},
                success : function(response){
                  $.each(response.data, function(index, item) {
                    $('#axeupdate').append($('<option>', {
                        value: item.axe_id,
                        text: item.axe_libelle
                    }));
                });
                    
                }
            })
      $("#ModificationTransport").modal("show");
    });

    $.ajax({
    url: '<?= site_url('transport/getAxe') ?>',
    method: "POST",
    dataType: "json",
    success: function (response) {
        var axes = response.data;
        console.log(axes);
    },
    error: function (xhr, status, error) {
        console.error("Error:", error);
    }
});



        $('#dotransport').on('click', function(){
            let debut = $('#transport_debut').val();
            let fin = $('#transport_fin').val();
            $.ajax({
                url : "<?= site_url('tranport/setfilter') ?>",
                dataType : 'json',
                method : 'post',
                data : {debut : debut, fin : fin},
                success : function(resp){
                    //Mise à jour de la table
                    if(!resp.err){
                        table.ajax.reload();
                    }
                }
            })
        })
       
    })

</script>