<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Gestion des proprio</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier proprio/modalProprio 
             * */ ?>
            <button id="add-proprio" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addProprioModal"><i class="fa fa-plus-circle pr-2"></i>Ajout proprio</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-proprio" class="table-striped table-hover" style="width:100%">
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

<!-- MODAL AJOUT PROPRIO -->
<div class="modal fade" id="addProprioModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter un proprio" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajout d'un proprio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_add_proprio" action="<?= site_url('proprio/saveNewProprio') ?>">
                  
              <div class="form-group row">
                  <label for="proprio_libelle" class="col-sm-2 col-form-label">Libellé</label>
                  <div class="col-sm-10">
                    <input type="text" required="true" name="proprio_libelle" class="form-control" id="proprio_libelle" placeholder="Libellé">
                  </div>
              </div>

              <input type="hidden" name="send" value="sent">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_proprio" value="save_proprio" class="btn btn-primary">Enregister</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL AJOUT PROPRIO -->

<!--  MODAL MODIF PROPRIO -->
<div class="modal fade" id="editProprioModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modification d'un proprio</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="form_edit_proprio" action="<?= site_url('proprio/saveEditProprio') ?>">
              
                <div class="form-group row">
                    <label for="edit_proprio_libelle" class="col-sm-2 col-form-label">Libellé</label>
                    <div class="col-sm-10">
                        <input type="text" required="true" name="edit_proprio_libelle" class="form-control" id="edit_proprio_libelle" placeholder="Libellé" value="">
                    </div>
                </div>

                <input type="hidden" id="edit_proprio_id" name="edit_proprio_id" />
                <input type="hidden" name="send_edit" value="sent">
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="edit_proprio" value="edit_proprio" class="btn btn-primary">Enregister</button>
                </div>

            </form>
           </div>
        </div>
    </div>
</div>

<!-- END MODAL MODIF PROPRIO -->

<!-- MODAL SUPPRESSION PROPRIO -->
<div class="modal fade" id="deleteProprioModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Supprimer un proprio" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suppression d'un proprio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Etes-vous sur de bien vouloir désactiver ce proprio ? </p>
              <form id="confirmDesactivateProprioForm" name="confirmDesactivateProprioForm" action="<?= site_url('proprio/desactivateProprio') ?>" method="post">
                  <input type="hidden" name="sendDesactivate" value="sent">
                  <input type="hidden" name="proprioToDesactivate" id="proprioToDesactivate" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmDesactivateProprio" value="save_proprio" class="btn btn-primary">Confirmer la desactivation</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION PROPRIO -->

<script type="text/javascript">
    $(document).ready(function(){

        //initialisation datatable
        $("#list-proprio").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("proprio/getListProprio"); ?>",
            columns : [
                { data : "proprio_id" },
                { data : "proprio_libelle" },
                { 
                    data : null,
                    render : function(data, type, row){
                        return (data.proprio_actif) == "1" ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>';
                    } 
                },
                { data : "proprio_datecrea" },
                {
                    data: null,
                    render: function ( data, type, row ) {
                        return '<button title="modifier la proprio" data-proprio="'+data.proprio_id+'" class="edit-proprio btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>' + 
                                '<button title="supprimer la proprio" data-proprio="'+data.proprio_id+'" class="delete-proprio btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button>';
                    }
                }
            ]
        });

        $('#edit-proprio').on('click', function(){
        $('#form_edit_proprio').submit();
      })

      /**
      * Click sur les boutons de modification proprio pour chaque ligne
      */
      $(document).on('click', '.edit-proprio', function(){
        let proprio_id = $(this).data('proprio');

        $.ajax({
            url : "<?= site_url('proprio/getInfoProprio') ?>",
            method : "POST",
            dataType : "json",
            data : {proprio_id : proprio_id},
            success : function(response){
              if(!response.error){
                //Mise à jour des champs de la modal par rapport au json retourné
                $('#edit_proprio_id').val(response.info_proprio.proprio_id);
                $('#edit_proprio_libelle').val(response.info_proprio.proprio_libelle);
                
                $('#editProprioModal').modal('show');
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
        
      })

      $(document).on('click', '.delete-proprio', function(){
        let proprioId = $(this).data('proprio');
        $('#proprioToDesactivate').val(proprioId);
        $('#deleteProprioModal').modal('show');
      });

      $(document).on('click', '#confirmDesactivateProprio', function(){

        $('#confirmDesactivateProprioForm').submit();

      });

    })
</script>

