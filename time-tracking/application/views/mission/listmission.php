<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Gestion des missions</h2>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier mission/modalMission 
             * */ ?>
            <button id="add-mission" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addMissionModal"><i class="fa fa-plus-circle pr-2"></i>Ajouter</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-mission" class="table-striped table-hover" style="width:100%">
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
<div class="modal fade" id="addMissionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter une mission" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajout d'une mission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_add_mission" action="<?= site_url('mission/saveNewMission') ?>">
                  
              <div class="form-group row">
                  <label for="mission_libelle" class="col-sm-2 col-form-label">Libellé</label>
                  <div class="col-sm-10">
                    <input type="text" required="true" name="mission_libelle" class="form-control" id="mission_libelle" placeholder="Libellé">
                  </div>
              </div>

              <input type="hidden" name="send" value="sent">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_mission" value="save_mission" class="btn btn-primary">Enregister</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL AJOUT CAMPAGNE -->

<!--  MODAL MODIF CAMPAGNE -->
<div class="modal fade" id="editMissionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modification d'une mission</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="form_edit_mission" action="<?= site_url('mission/saveEditMission') ?>">
              
                <div class="form-group row">
                    <label for="edit_mission_libelle" class="col-sm-2 col-form-label">Libellé</label>
                    <div class="col-sm-10">
                        <input type="text" required="true" name="edit_mission_libelle" class="form-control" id="edit_mission_libelle" placeholder="Libellé" value="">
                    </div>
                </div>

                <input type="hidden" id="edit_mission_id" name="edit_mission_id" />
                <input type="hidden" name="send_edit" value="sent">
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="edit_mission" value="edit_mission" class="btn btn-primary">Enregister</button>
                </div>

            </form>
           </div>
        </div>
    </div>
</div>

<!-- END MODAL MODIF CAMPAGNE -->

<!-- MODAL SUPPRESSION CAMPAGNE -->
<div class="modal fade" id="deleteMissionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Supprimer une mission" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suppression d'une mission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Etes-vous sur de bien vouloir désactiver cette mission ? </p>
              <form id="confirmDesactivateMissionForm" name="confirmDesactivateMissionForm" action="<?= site_url('mission/desactivateMission') ?>" method="post">
                  <input type="hidden" name="sendDesactivate" value="sent">
                  <input type="hidden" name="missionToDesactivate" id="missionToDesactivate" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmDesactivateMission" value="save_mission" class="btn btn-primary">Confirmer la desactivation</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION CAMPAGNE -->

<script type="text/javascript">
    $(document).ready(function(){

        //initialisation datatable
        $("#list-mission").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("mission/getListMission"); ?>",
            columns : [
                { data : "mission_id" },
                { data : "mission_libelle" },
                { 
                    data : null,
                    render : function(data, type, row){
                        return (data.mission_actif) == "1" ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>';
                    } 
                },
                { data : "mission_datecrea" },
                {
                    data: null,
                    render: function ( data, type, row ) {
                        return '<button title="modifier la mission" data-mission="'+data.mission_id+'" class="edit-mission btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>' + 
                                '<button title="supprimer la mission" data-mission="'+data.mission_id+'" class="delete-mission btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button>';
                    }
                }
            ]
        });

        $('#edit-mission').on('click', function(){
        $('#form_edit_mission').submit();
      })

      /**
      * Click sur les boutons de modification mission pour chaque ligne
      */
      $(document).on('click', '.edit-mission', function(){
        let mission_id = $(this).data('mission');

        $.ajax({
            url : "<?= site_url('mission/getInfoMission') ?>",
            method : "POST",
            dataType : "json",
            data : {mission_id : mission_id},
            success : function(response){
              if(!response.error){
                //Mise à jour des champs de la modal par rapport au json retourné
                $('#edit_mission_id').val(response.info_mission.mission_id);
                $('#edit_mission_libelle').val(response.info_mission.mission_libelle);
                
                $('#editMissionModal').modal('show');
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
        
      })

      $(document).on('click', '.delete-mission', function(){
        let missionId = $(this).data('mission');
        $('#missionToDesactivate').val(missionId);
        $('#deleteMissionModal').modal('show');
      });

      $(document).on('click', '#confirmDesactivateMission', function(){

        $('#confirmDesactivateMissionForm').submit();

      });

    })
</script>

