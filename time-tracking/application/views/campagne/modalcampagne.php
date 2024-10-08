<!-- MODAL AJOUT CAMPAGNE -->
<div class="modal fade" id="addCampagneModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter une campagne" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajout d'une campagne</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_add_campagne" action="<?= site_url('campagne/saveNewCampagne') ?>">
                  
              <div class="form-group row">
                  <label for="campagne_libelle" class="col-sm-2 col-form-label">Libellé</label>
                  <div class="col-sm-10">
                    <input type="text" required="true" name="campagne_libelle" class="form-control" id="campagne_libelle" placeholder="Libellé">
                  </div>
              </div>
          

              <div class="form-group row">

                <label for="campagne_pole" class="col-sm-2 col-form-label">Pôle</label>
                <div class="col-sm-10">
                      <select id="campagne_pole" class="form-control" name="campagne_pole">
                        <option value="">-</option>
                        <?php foreach ($listPole as $pole) { ?>
                          <option value="<?php echo $pole->pole_id ?>"><?php echo $pole->pole_libelle ?></option>
                        <?php }
                         ?>
                       
                      </select>
                  </div>
              </div>

            
              <div class="form-group row">

                <label for="campagne_site" class="col-sm-2 col-form-label">Site</label>
                <div class="col-sm-10">
                      <select id="campagne_site" class="form-control" name="campagne_site">
                        <option value="">-</option>
                        <?php foreach ($listSite as $site) { ?>
                          <option value="<?php echo $site->site_id ?>"><?php echo $site->site_libelle ?></option>
                        <?php }
                         ?>
                       
                      </select>
                  </div>
              </div>

              <div class="form-group row">

                <label for="campagne_proprio" class="col-sm-2 col-form-label">Propriétaire</label>
                <div class="col-sm-10">
                      <select id="campagne_proprio" class="form-control" name="campagne_proprio">
                        <option value="">-</option>
                        <?php foreach ($listProprio as $proprio) { ?>
                          <option value="<?php echo $proprio->proprio_id ?>"><?php echo $proprio->proprio_libelle ?></option>
                        <?php }
                         ?>
                       
                      </select>
                  </div>
              </div>

               <div class="form-group row">
                  <label for="campagne_client" class="col-sm-2 col-form-label">Client</label>
                  <div class="col-sm-10">
                    <input type="text" required="true" name="campagne_client" class="form-control" id="campagne_client" placeholder="Client">
                  </div>
              </div>

              <div class="form-group row">
                  <label for="campagne_ipserveur" class="col-sm-2 col-form-label">Ip serveur</label>
                  <div class="col-sm-10">
                    <input type="text" name="campagne_ipserveur" class="form-control" id="campagne_ipserveur" placeholder="Ip serveur">
                  </div>
              </div>


              <input type="hidden" name="send" value="sent">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_campagne" value="save_campagne" class="btn btn-primary">Enregister</button>
              </div>
             
          </form>


      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL AJOUT CAMPAGNE -->

<!--  MODAL MODIF CAMPAGNE -->
<div class="modal fade" id="editCampagneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modification d'une campagne</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="form_edit_campagne" action="<?= site_url('campagne/saveEditCampagne') ?>">
              
          <div class="form-group row">
              <label for="edit_campagne_libelle" class="col-sm-2 col-form-label">Libellé</label>
              <div class="col-sm-10">
                <input type="text" required="true" name="edit_campagne_libelle" class="form-control" id="edit_campagne_libelle" placeholder="Libellé" value="">
              </div>
          </div>

        
          <div class="form-group row">
            <label for="edit_campagne_pole" class="col-sm-2 col-form-label">Pôle</label>
            <div class="col-sm-10">
                  <select id="edit_campagne_pole" class="form-control" name="edit_campagne_pole">
                    <option value="">-</option>
                    <?php foreach ($listPole as $pole) { ?>
                      <option value="<?php echo $pole->pole_id ?>"><?php echo $pole->pole_libelle ?></option>
                    <?php }
                     ?>
                   
                  </select>
              </div>
          </div>


          <div class="form-group row">
            <label for="edit_campagne_site" class="col-sm-2 col-form-label">Site</label>
            <div class="col-sm-10">
                  <select id="edit_campagne_site" class="form-control" name="edit_campagne_site">
                    <option value="">-</option>
                    <?php foreach ($listSite as $site) { ?>
                      <option value="<?php echo $site->site_id ?>"><?php echo $site->site_libelle ?></option>
                    <?php }
                     ?>
                   
                  </select>
              </div>
          </div>

          <div class="form-group row">
            <label for="edit_campagne_proprio" class="col-sm-2 col-form-label">Propriétaire</label>
            <div class="col-sm-10">
                  <select id="edit_campagne_proprio" class="form-control" name="edit_campagne_proprio">
                    <option value="">-</option>
                    <?php foreach ($listProprio as $proprio) { ?>
                      <option value="<?php echo $proprio->proprio_id ?>"><?php echo $proprio->proprio_libelle ?></option>
                    <?php }
                     ?>
                   
                  </select>
              </div>
          </div>

          <div class="form-group row">
              <label for="edit_campagne_client" class="col-sm-2 col-form-label">Client</label>
              <div class="col-sm-10">
                <input type="text" required="true" name="edit_campagne_client" class="form-control" id="edit_campagne_client" placeholder="Client" value="">
              </div>
          </div>


          <div class="form-group row">
              <label for="edit_campagne_ipserveur" class="col-sm-2 col-form-label">Ip serveur</label>
              <div class="col-sm-10">
                <input type="text" name="edit_campagne_ipserveur" class="form-control" id="edit_campagne_ipserveur" placeholder="Ip serveur" value="">
              </div>
          </div>

          
          <input type="hidden" id="edit_campagne_id" name="edit_campagne_id" />

          <input type="hidden" name="send_edit" value="sent">
         
        
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" id="edit_campagne" value="edit_campagne" class="btn btn-primary">Enregister</button>
          </div>

          </form>
      </div>
    </div>
</div>

<!-- END MODAL MODIF CAMPAGNE -->

<!-- MODAL SUPPRESSION CAMPAGNE -->
<div class="modal fade" id="deleteCampagneModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Supprimer une campagne" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suppression d'une campagne</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Etes-vous sur de bien vouloir désactiver cette campagne ? </p>
              <form id="confirmDesactivateCampagneForm" name="confirmDesactivateCampagneForm" action="<?= site_url('campagne/desactivateCampagne') ?>" method="post">
                  <input type="hidden" name="sendDesactivate" value="sent">
                  <input type="hidden" name="campagneToDesactivate" id="campagneToDesactivate" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmDesactivateCampagne" value="save_campagne" class="btn btn-primary">Confirmer la desactivation</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION CAMPAGNE -->

<!--  MODAL AFFECTATION CAMPAGNE -->
<div class="modal fade" id="affectationCampagneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Affectation d'une campagne</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            
              
          <div class="form-group row">
              <label for="affectation_campagne_libelle" class="col-sm-2 col-form-label">Libellé</label>
              <div class="col-sm-10">
                <input type="text" required="true" name="affectation_campagne_libelle" class="form-control" id="affectation_campagne_libelle" placeholder="Libellé" value="">
              </div>
          </div>

        
          <div class="form-group row form-group-mission">
            <label for="edit_campagne_pole" class="col-sm-2 col-form-label">Missions</label>
            <div class="col-sm-10">
                  <?php foreach($listMission as $mission): ?>
                    <div class="form-check form-check-mission alert-primary px-4">
                        <input type="checkbox" disabled="disabled" class="form-check-input" value="<?= $mission->mission_id ?>" id="mission_<?= $mission->mission_id ?>">
                        <a href="#" class="edit-process"> <?= $mission->mission_libelle ?></a>
                    </div>    
                  <?php endforeach; ?>
              </div>
          </div>


          <div class="form-group row form-group-process">
            <label for="edit_campagne_site" class="col-sm-2 col-form-label">Process</label>
            <div class="col-sm-10">
              <?php foreach($listProcess as $process): ?>
                  <div class="form-check form-check-process">
                      <input type="checkbox" class="form-check-input form-check-input-process" value="<?= $process->process_id ?>" id="process_<?= $process->process_id ?>">
                      <label for="" class="form-check-label"><?= $process->process_libelle ?></label>
                  </div>
              <?php endforeach; ?>
            </div>
          </div>
          
          <input type="hidden" id="affectation_mission_id" name="affectation_mission_id" />
          <input type="hidden" id="affectation_campagne_id" name="affectation_campagne_id" />

          <input type="hidden" name="send_affectation" value="sent">
         
        
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          </div>

      </div>
    </div>
</div>

<!-- END MODAL AFFECTATION CAMPAGNE -->


<script type="text/javascript">
  
  $(document).ready(function() {
      
      $('#edit-campagne').on('click', function(){
        $('#form_edit_campagne').submit();
      })

      /**
      * Click sur les boutons de modification campagne pour chaque ligne
      */
      $(document).on('click', '.edit-campagne', function(){
        let campagne_id = $(this).data('campagne');

        $.ajax({
            url : "<?= site_url('campagne/getInfocampagne') ?>",
            method : "POST",
            dataType : "json",
            data : {campagne_id : campagne_id},
            success : function(response){
              console.log(response);
              if(!response.error){
                //Mise à jour des champs de la modal par rapport au json retourné
                $('#edit_campagne_id').val(response.info_campagne.campagne_id);
                $('#edit_campagne_libelle').val(response.info_campagne.campagne_libelle);
                $('#edit_campagne_pole').val(response.info_campagne.campagne_pole);
                $('#edit_campagne_site').val(response.info_campagne.campagne_site);
                $('#edit_campagne_proprio').val(response.info_campagne.campagne_proprio);
                $('#edit_campagne_ipserveur').val(response.info_campagne.campagne_ipserveur);
                
                $('#editCampagneModal').modal('show');
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
        
      })

      $(document).on('click', '.delete-campagne', function(){
        let campagneId = $(this).data('campagne');
        $('#campagneToDesactivate').val(campagneId);
        $('#deleteCampagneModal').modal('show');
      });

      $(document).on('click', '#confirmDesactivateCampagne', function(){

        $('#confirmDesactivateCampagneForm').submit();

      });


      $(document).on('click', '.affecter-campagne', function(){
        let campagne_id = $(this).data('campagne');
        $('#affectation_campagne_id').val(campagne_id);
        $('.form-group-process').hide();

        $.ajax({
            url : "<?= site_url('campagne/getAffectationcampagne') ?>",
            method : "POST",
            dataType : "json",
            data : {campagne_id : campagne_id},
            success : function(response){
              console.log(response);
              if(!response.error){
                let libCampagne = response.info_campagne.campagne_libelle;
                $('#affectation_campagne_libelle').val(libCampagne);
                let nbMission = response.affected_mission.length;
                if(nbMission > 0){
                  response.affected_mission.forEach((mission) => {
                    $('#mission_' + mission.affectationmcp_mission).prop('checked', true);
                  })
                }
                $('#affectationCampagneModal').modal('show');
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
        })
        
      })

      $(document).on('change', '.form-check-input-process', function(){
        let campagne_id = $('#affectation_campagne_id').val();
        let mission_id = $('#affectation_mission_id').val();
        let process_id = $(this).val();
        let action = '';
        if($(this).is(':checked')){
          action = 'set';
        }else{
          action = 'unset';
        }
        let data = {
          'campagne_id' : campagne_id,
          'mission_id' : mission_id,
          'process_id' : process_id,
          'action' : action,
        };

        $.ajax({
            url : "<?= site_url('campagne/setAffectationcampagne') ?>",
            method : "POST",
            dataType : "json",
            data : data,
            success : function(response){
              console.log(response);
              if(!response.error){
                //Mise à jour du check sur mission
                let nbProcess = response.affected_process.length;
                if(nbProcess > 0){
                  //On check la mission 
                  $('#mission_' + mission_id).prop('checked', true);
                }else{
                  //On deselectionne la mission
                  $('#mission_' + mission_id).prop('checked', false);
                }
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
        })
        
      })

      $(document).on('click', '.form-check-mission', function()
      {
        let campagne_id = $('#affectation_campagne_id').val();
        let mission_id = $(this).find('.form-check-input').val();
        $('.form-check-mission').removeClass('alert-success');
        $(this).addClass('alert-success');
        let currentMission = $(this);
        showProcess(campagne_id, mission_id, currentMission);
        
      });

      function showProcess(campagne_id, mission_id, currentMission)
      {
        $('#affectation_mission_id').val(mission_id);
        let data = {
          'campagne_id' : campagne_id,
          'mission_id' : mission_id
        };
        $.ajax({
            url : "<?= site_url('campagne/getAffectationCampagneMission') ?>",
            method : "POST",
            dataType : "json",
            data : data,
            success : function(response){
              if(!response.error){
                let nbProcess = response.affected_process.length;
                console.log(currentMission);
                $('.form-check-input-process').prop('checked', false);
                if(nbProcess > 0){
                  $(currentMission).find('input').prop('checked', true);
                  response.affected_process.forEach((process) => {
                    $('#process_' + process.affectationmcp_process).prop('checked', true);
                  })
                }else{
                  $(currentMission).find('input').prop('checked', false);
                }
                
                $('.form-group-process').show();
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
        })

      }

      

  } );
</script>
