<div class="modal fade" id="modalsuppresionaxe" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalannulationtransport" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Suppression axe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <input type="hidden" id="id_axeheuretodelete">

                    <div class="mb-12 row">
                      <h6 class="">Etes-vous certain de vouloir supprimer  cet axe, cela affectera la suppression de toutes les quartiers assignés</h6>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" id="ConfirmDelete" class="btn btn-primary">Supprimer</button>
            </div>
            </div>
        </div>
</div>



<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Assignation des quartiers</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <button id="addaxetest" class="btn btn-primary mx-2" ><i class="fa fa-plus-circle pr-2"></i>Ajouter</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-axeTransport" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Axe</th>
                        <th>Quartiers</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div
  class="modal fade"
  id="editUtilisateurModal"
  tabindex="-1"
  role="dialog"
  aria-labelledby="exampleModalLabel"
  aria-hidden="true"
>
<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Ajouter des quartiers
        </h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>

      <form
        method="post"
        id="form_edit_utilisateur"
        action="<?= site_url('transport/saveassignquatieraxe') ?>"
      >
        <div class="modal-body">
          <div class="form-group row">
            <input type="hidden" id="id_axequartieraxe" name="test">
          <div id="service-list-edit"></div>

        
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            data-bs-dismiss="modal"
          >
            Annuler
          </button>
          <button
            type="submit"
            id="edit_utilisateur"
            value="edit_utilisateur"
            class="btn btn-primary"
          >
            Enregister
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function()
    {
        $(document).on('click', '#ConfirmDelete', function() {
          var table = $("#list-axeTransport").DataTable();
          var row = $(this).closest('tr');
          var rowData = table.row(row).data();
          var id = $("#id_axeheuretodelete").val();

          $.ajax({
              type: "POST",
              url: "<?= site_url('transport/deleteAxetransport'); ?>",
              data: {id_axetransport: id},
              success: function(response) {
            var jsonResponse = JSON.parse(response);
            $("#modalsuppresionaxe").modal("hide");

            console.log(jsonResponse);
            table.ajax.reload();

          
              },
            
          });
        });

        $(document).on("click"," .deleteaxe",function (e){
          var id = $(this).data('id');
          $("#id_axeheuretodelete").val(id);
          $("#modalsuppresionaxe").modal("show");

        });
        $(document).on("click","#addaxetest",function (e){
          $("#testadd").modal("show");

        });
        $("#service-list").html("");
        edit_service_elt(1);
        //initialisation datatable
        $("#list-axeTransport").DataTable({
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
            ajax : "<?= site_url("transport/getAllAxequartierlist"); ?>",
            columns : [

                {data:"heuretransport_heure"},
                { data : "axe_libelle" },
            
                { 
                    data : "quartier_libelle",
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
                    render: function ( data, type, row ) {
                        return '<button title="" data-id="'+data.heureaxe_id+'" class="edit-axetransport btn btn-secondary btn-sm mr-1"><i class="fa fa-thumb-tack"></i></button><button title="" data-id="'+data.heureaxe_id+'" class="deleteaxe btn btn-danger btn-sm mr-1"><i class="fa fa-trash"></i></button>';
                      }
                }
            ]
        });

        $(document).on("click", ".edit-axetransport", function () {
            let id_axeheure = $(this).data("id");
            $.ajax({
                url: "<?= site_url('transport/getinfoquartier') ?>",
                method: "POST",
                dataType: "json",
                data: { id_axeheure: id_axeheure },
                success: function (response) {
                console.log(response);
                if (!response.error) {
                    console.log(response.data)
                    
                    load_service_elts(response.data);
                    console.log(id_axeheure);
                    $("#id_axequartieraxe").val(id_axeheure);

                    $("#editUtilisateurModal").modal("show");
                } else {
                    //TODO
                    alert("Erreur survenu");
                }
                },
            });
        });


        function edit_service_elt(serv, init = 0) 
        {
            let nb_elt = $(".service-elt-edit").length;
            let curr_elt = nb_elt + 1;

      

            //On supprime les elements déjà selectionnés dans les autres select
            $.each(selected_elt, function (k, v) {
                $(
                ".service-elt-edit-" + curr_elt + " option[value=" + v + "]"
                ).remove();
            });
            //Prendre les elements selectionnés sur les autres select .service-list
            var selected_elt = $(".service-elt-edit select")
                .map(function () {
                return this.value;
                })
                .get();

            let elt_empty =
                '<div class="form-group row service-elt-edit-empty">' +
                '<div class="col-sm-7 offset-sm-2">' +
                "</div>" +
                '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-default add_service_to_edit_user"><i class="fa fa-plus-circle"></i></button></div>' +
                "</div>";

            let elt =
                '<div class="form-group row service-elt-edit">' +
                '<div class="col-sm-7 offset-sm-2">' +
                '<select id="list_service_edit" class="form-control service-elt-edit-' +
                curr_elt +
                '" name="user_service_edit[]">' +
                "<?php foreach ($listequartiers as $listequartier) { ?>" +
                '<option value=<?php echo $listequartier->quartier_id ?>><?php echo $listequartier->quartier_libelle ?></option>' +
                "<?php }" +
                "?>" +
                "</select>" +
                "</div>" +
                '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-default add_service_to_edit_user"><i class="fa fa-plus-circle"></i></button></div>' +
                '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger remove_service_to_edit_user mx-sm-0"><i class="fa fa-minus-circle"></i></button></div>' +
                "</div>";
            if (init == 1) {
                $("#service-list-edit").append(elt_empty);
            } else {
                $("#service-list-edit").append(elt);
                $(".service-elt-edit-empty").remove();
            }


            if (serv)
                $(
                ".service-elt-edit-" +
                    curr_elt +
                    " option[value=" +
                    serv.quartier_id +
                    "]"
                ).attr("selected", "selected");

           
        }


       
        function load_service_elts(list) 
        {
          if (list){
            $("#service-list-edit").html("");
            edit_service_elt("init", 1);
            for (const serv of list) {
              console.log(serv);
                edit_service_elt(serv);
            }

          }
          else{
            edit_service_elt("init", 1);

          }
          
        }

        function add_service_elt(init = 0) {
        let nb_elt = $(".service-elt").length;
        let curr_elt = nb_elt + 1;
        //prendre les elements selectionnés sur les autres select .service-list
        var selected_elt = $(".service-elt select")
          .map(function () {
            return this.value;
          })
          .get();

        let elt_empty =
          '<div class="form-group row service-elt-empty">' +
          '<div class="col-sm-7 offset-sm-2">' +
          "</div>" +
          '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-primary add_service_to_user"><i class="fa fa-plus-circle"></i></button></div>' +
          "</div>";

        let elt =
          '<div class="form-group row service-elt">' +
          '<div class="col-sm-7 offset-sm-2">' +
          '<select id="list_service" class="form-control service-elt-' +
          curr_elt +
          '" name="user_service_add[]">' +
          "<?php foreach ($listequartiers as $listequartier) { ?>" +
            "<option value=<?php echo $listequartier->quartier_id ?>><?php echo $listequartier->quartier_libelle ?></option>" +
          "<?php }" +
          "?>" +
          "</select>" +
          "</div>" +
          '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-primary add_service_to_user"><i class="fa fa-plus-circle"></i></button></div>' +
          '<div class="col-sm-1"><button type="button" class="btn btn-sm btn-danger remove_service_to_user mx-sm-0"><i class="fa fa-minus-circle"></i></button></div>' +
          "</div>";
        if (init == 1) {
          $("#service-list").append(elt_empty);
        } else {
          $("#service-list").append(elt);
          $(".service-elt-empty").remove();
        }

        //On supprime les elements déjà selectionnés dans les autres select
        $.each(selected_elt, function (k, v) {
          $(".service-elt-" + curr_elt + " option[value=" + v + "]").remove();
        });
      }

      $(document).on("click", ".add_service_to_edit_user", function (e) {
        edit_service_elt();
      });

      $(document).on("click", ".remove_service_to_edit_user", function (e) {
        let nb_elt = $(".service-elt").length;
        $(this).closest(".form-group").remove();
        if (nb_elt <= 1) {
          add_service_elt(1);
        }
      });

      

    })
</script>