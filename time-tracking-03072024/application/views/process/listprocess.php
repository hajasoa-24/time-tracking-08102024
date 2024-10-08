<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Gestion des process</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier process/modalProcess 
             * */ ?>
            <button id="add-process" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addProcessModal"><i class="fa fa-plus-circle pr-2"></i>Ajouter</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-process" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libelle</th>
                        <th>Actif</th>
                        <th>Date de création</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- MODAL AJOUT CAMPAGNE -->
<div class="modal fade" id="addProcessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter un process" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajout d'un process</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_add_process" action="<?= site_url('process/saveNewProcess') ?>">
                  
              <div class="form-group row">
                  <label for="process_libelle" class="col-sm-2 col-form-label">Libellé</label>
                  <div class="col-sm-10">
                    <input type="text" required="true" name="process_libelle" class="form-control" id="process_libelle" placeholder="Libellé">
                  </div>
              </div>

              <input type="hidden" name="send" value="sent">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_process" value="save_process" class="btn btn-primary">Enregister</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL AJOUT CAMPAGNE -->

<!--  MODAL MODIF CAMPAGNE -->
<div class="modal fade" id="editProcessModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modification d'un process</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="form_edit_process" action="<?= site_url('process/saveEditProcess') ?>">
              
                <div class="form-group row">
                    <label for="edit_process_libelle" class="col-sm-2 col-form-label">Libellé</label>
                    <div class="col-sm-10">
                        <input type="text" required="true" name="edit_process_libelle" class="form-control" id="edit_process_libelle" placeholder="Libellé" value="">
                    </div>
                </div>

                <input type="hidden" id="edit_process_id" name="edit_process_id" />
                <input type="hidden" name="send_edit" value="sent">
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="edit_process" value="edit_process" class="btn btn-primary">Enregister</button>
                </div>

            </form>
           </div>
        </div>
    </div>
</div>

<!-- END MODAL MODIF CAMPAGNE -->

<!-- MODAL SUPPRESSION CAMPAGNE -->
<div class="modal fade" id="deleteProcessModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Supprimer un process" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suppression d'un process</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Etes-vous sur de bien vouloir désactiver ce process ? </p>
              <form id="confirmDesactivateProcessForm" name="confirmDesactivateProcessForm" action="<?= site_url('process/desactivateProcess') ?>" method="post">
                  <input type="hidden" name="sendDesactivate" value="sent">
                  <input type="hidden" name="processToDesactivate" id="processToDesactivate" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmDesactivateProcess" value="save_process" class="btn btn-primary">Confirmer la desactivation</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION CAMPAGNE -->

<script type="text/javascript">
    $(document).ready(function(){

        //initialisation datatable
        $("#list-process").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("process/getListProcess"); ?>",
            columns : [
                { data : "process_id" },
                { data : "process_libelle" },
                { 
                    data : null,
                    render : function(data, type, row){
                        return (data.process_actif) == "1" ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>';
                    } 
                },
                { data : "process_datecrea" },
                {
                    data: null,
                    render: function ( data, type, row ) {
                        return '<button title="modifier la process" data-process="'+data.process_id+'" class="edit-process btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>' + 
                                '<button title="supprimer la process" data-process="'+data.process_id+'" class="delete-process btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button>';
                    }
                }
            ]
        });

        $('#edit-process').on('click', function(){
        $('#form_edit_process').submit();
      })

      /**
      * Click sur les boutons de modification process pour chaque ligne
      */
      $(document).on('click', '.edit-process', function(){
        let process_id = $(this).data('process');

        $.ajax({
            url : "<?= site_url('process/getInfoProcess') ?>",
            method : "POST",
            dataType : "json",
            data : {process_id : process_id},
            success : function(response){
              if(!response.error){
                //Mise à jour des champs de la modal par rapport au json retourné
                $('#edit_process_id').val(response.info_process.process_id);
                $('#edit_process_libelle').val(response.info_process.process_libelle);
                
                $('#editProcessModal').modal('show');
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
        
      })

      $(document).on('click', '.delete-process', function(){
        let processId = $(this).data('process');
        $('#processToDesactivate').val(processId);
        $('#deleteProcessModal').modal('show');
      });

      $(document).on('click', '#confirmDesactivateProcess', function(){

        $('#confirmDesactivateProcessForm').submit();

      });

    })
</script>

