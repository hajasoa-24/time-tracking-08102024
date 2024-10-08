<!-- MODAL AJOUT SERVICE -->
<div class="modal fade" id="addServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter un service" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Ajout d'un service</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="form_add_service" action="<?= site_url('service/saveNewService') ?>">
                    
                <div class="form-group row">
                    <label for="service_libelle" class="col-sm-2 col-form-label">Libellé</label>
                    <div class="col-sm-10">
                      <input type="text" required="true" name="service_libelle" class="form-control" id="service_libelle" placeholder="Libellé">
                    </div>
                </div>
            
              
                <div class="form-group row">

                  <label for="service_site" class="col-sm-2 col-form-label">Site</label>
                  <div class="col-sm-10">
                        <select id="service_site" class="form-control" name="service_site">
                          <option value="">-</option>
                          <?php foreach ($listSite as $site) { ?>
                            <option value="<?php echo $site->site_id ?>"><?php echo $site->site_libelle ?></option>
                          <?php }
                           ?>
                         
                        </select>
                    </div>
                </div>

                <input type="hidden" name="send" value="sent">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="save_service" value="save_service" class="btn btn-primary">Enregister</button>
                </div>
               
            </form>

        </div>
      
    </div>
    
  </div>
</div>
<!-- END MODAL AJOUT SERVICE -->

<!--  MODAL MODIF SERVICE -->
<div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modification d'un service</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="form_edit_service" action="<?= site_url('service/saveEditService') ?>">
              
          <div class="form-group row">
              <label for="edit_service_libelle" class="col-sm-2 col-form-label">Libellé</label>
              <div class="col-sm-10">
                <input type="text" required="true" name="edit_service_libelle" class="form-control" id="edit_service_libelle" placeholder="Libellé" value="">
              </div>
          </div>

          <div class="form-group row">
            <label for="edit_service_site" class="col-sm-2 col-form-label">Site</label>
            <div class="col-sm-10">
                  <select id="edit_service_site" class="form-control" name="edit_service_site">
                    <option value="">-</option>
                    <?php foreach ($listSite as $site) { ?>
                      <option value="<?php echo $site->site_id ?>"><?php echo $site->site_libelle ?></option>
                    <?php }
                     ?>
                   
                  </select>
              </div>
          </div>
          
          <input type="hidden" id="edit_service_id" name="edit_service_id" />

          <input type="hidden" name="send_edit" value="sent">
         
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" id="edit_service" value="edit_service" class="btn btn-primary">Enregister</button>
          </div>

          </form>
      </div>
    </div>
</div>
<!-- END MODAL MODIF SERVICE -->

<!-- MODAL SUPPRESSION SERVICE -->
<div class="modal fade" id="deleteServiceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Supprimer un service" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suppression d'un service</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Etes-vous sur de bien vouloir désactiver ce service ? </p>
              <form id="confirmDesactivateServiceForm" name="confirmDesactivateServiceForm" action="<?= site_url('service/desactivateService') ?>" method="post">
                  <input type="hidden" name="sendDesactivate" value="sent">
                  <input type="hidden" name="serviceToDesactivate" id="serviceToDesactivate" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmDesactivateService" value="save_service" class="btn btn-primary">Confirmer la desactivation</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION SERVICE -->

<script type="text/javascript">
  
  $(document).ready(function() {
      
       $('#save_service').on('click', function(){
        $('#form_add_service').submit();
      })

      $('#edit_service').on('click', function(){
        $('#form_edit_service').submit();
      })

      /**
      * Click sur les boutons de modification service pour chaque ligne
      */
      $(document).on('click', '.edit-service', function(){
        let service_id = $(this).data('service');

        $.ajax({
            url : "<?= site_url('service/getInfoService') ?>",
            method : "POST",
            dataType : "json",
            data : {service_id : service_id},
            success : function(response){
              console.log(response);
              if(!response.error){
                //Mise à jour des champs de la modal par rapport au json retourné
                $('#edit_service_id').val(response.info_service.service_id);
                $('#edit_service_libelle').val(response.info_service.service_libelle);
                $('#edit_service_site').val(response.info_service.service_site);
                
                
                $('#editServiceModal').modal('show');
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
        
      })

      $(document).on('click', '.delete-service', function(){
        let serviceId = $(this).data('service');
        $('#serviceToDesactivate').val(serviceId);
        $('#deleteServiceModal').modal('show');
      });

      $(document).on('click', '#confirmDesactivateService', function(){

        $('#confirmDesactivateServiceForm').submit();

      });

  } );
</script>