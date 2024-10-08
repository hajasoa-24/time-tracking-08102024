	

		
<!-- MODAL AJOUT CAMPAGNE -->
<div class="modal fade" id="addAgenceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter une agence" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajout d'une agence</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_add_agence" action="<?= site_url('agence/saveNewAgence') ?>">
            
              <div class="form-group row">
                  <label for="agence_id" class="col-sm-2 col-form-label">Code</label>
                  <div class="col-sm-10">
                    <input type="text" required="true" name="agence_id" class="form-control" id="agence_id" placeholder="code">
                  </div>
              </div>

              <div class="form-group row">
                  <label for="agence_libelle" class="col-sm-2 col-form-label">Libellé</label>
                  <div class="col-sm-10">
                    <input type="text" required="true" name="agence_libelle" class="form-control" id="agence_libelle" placeholder="Libellé">
                  </div>
              </div>

              <input type="hidden" name="send" value="sent">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_agence" value="save_agence" class="btn btn-primary">Enregister</button>
              </div>
             
          </form>


      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL AJOUT CAMPAGNE -->

<!--  MODAL MODIF CAMPAGNE -->
<div class="modal fade" id="editAgenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modification d'une agence</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="form_edit_agence" action="<?= site_url('agence/saveEditAgence') ?>">
                <div class="form-group row">
                      <label for="edit_agence_id" class="col-sm-2 col-form-label">Code</label>
                      <div class="col-sm-10">
                        <input type="text" required="true" name="edit_agence_code" class="form-control" id="edit_agence_code" placeholder="code">
                      </div>
                  </div>

                  <div class="form-group row">
                      <label for="agence_libelle" class="col-sm-2 col-form-label">Libellé</label>
                      <div class="col-sm-10">
                        <input type="text" required="true" name="edit_agence_libelle" class="form-control" id="edit_agence_libelle" placeholder="Libellé">
                      </div>
                  </div>
          
                  <input type="hidden" id="edit_agence_id" name="edit_agence_id" />

                  <input type="hidden" name="send_edit" value="sent">
                
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="edit_agence" value="edit_agence" class="btn btn-primary">Enregister</button>
                  </div>

          </form>
      </div>
    </div>
</div>

<!-- END MODAL MODIF CAMPAGNE -->

<!-- MODAL SUPPRESSION CAMPAGNE -->
<div class="modal fade" id="deleteAgenceModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Supprimer une agence" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suppression d'une agence</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Etes-vous sur de bien vouloir désactiver cette agence ? </p>
              <form id="confirmDesactivateAgenceForm" name="confirmDesactivateAgenceForm" action="<?= site_url('agence/desactivateAgence') ?>" method="post">
                  <input type="hidden" name="sendDesactivate" value="sent">
                  <input type="hidden" name="agenceToDesactivate" id="agenceToDesactivate" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmDesactivateAgence" value="save_agence" class="btn btn-primary">Confirmer la desactivation</button>
      </div>
      
    </div>
  </div>
</div>

<!-- END MODAL SUPPRESSION CAMPAGNE -->



<div class="modal fade bd-example-modal" id="modalactivateagence" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Activation d'une agence</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div><br>
      <h6 class="text-center">Voulez vous activer cette agence?</h6>
      <input type="hidden" id="idagenceToactivate" value=""><br>

      <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Non</button>
            <button type="button" id="confirmactivation" value="" class="btn btn-primary btn-sm">Oui</button>
        </div>
    </div>
  </div>
</div>



<div class="modal fade bd-example-modal" id="modaldesactivateagence" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Désactivation d'une agence</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div><br>
      <h6 class="text-center">Voulez vous désactiver cette agence?</h6><br>
      <input type="hidden" id="idagenceTodesactivate" value="">

      <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Non</button>
            <button type="button" id="confirmdesactivation" value="" class="btn btn-primary btn-sm">Oui</button>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  
  $(document).ready(function() {

      $(document).on("click","#activeagence", function(){
        let agence_id = $(this).data('agence');
        $('#modalactivateagence').modal("show");
        $("#idagenceToactivate").val(agence_id);


      });
      $(document).on("click","#desactiveagence", function(){
        let agence_id = $(this).data('agence');
        $('#modaldesactivateagence').modal("show");
        $("#idagenceTodesactivate").val(agence_id);



      });

      $(document).on("click","#confirmactivation", function(){
        var agence_id =  $("#idagenceToactivate").val();
        $.ajax({
            url : "<?= site_url('agence/activateagence') ?>",
            method : "POST",
            dataType : "json",
            data : {agence_id : agence_id},
            success : function(response){
              if(response.data == "true"){
                agenceTable.ajax.reload();
                $('#modalactivateagence').modal("hide");


              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
      });
      $(document).on("click","#confirmdesactivation", function(){
        var agence_id =  $("#idagenceTodesactivate").val();
        $.ajax({
            url : "<?= site_url('agence/descactivateagence') ?>",
            method : "POST",
            dataType : "json",
            data : {agence_id : agence_id},
            success : function(response){
              if(response.data == "true"){
                agenceTable.ajax.reload();
                $('#modaldesactivateagence').modal("hide");

              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
      });


      $(function () 
      {
        $('.example-popover').popover({
          container: 'body'
        })
      })
            
      $('#edit-agence').on('click', function(){
        $('#form_edit_agence').submit();
      })

      /**
      * Click sur les boutons de modification campagne pour chaque ligne
      */
      $(document).on('click', '.edit-agence', function(){
        let agence_id = $(this).data('agence');

        $.ajax({
            url : "<?= site_url('agence/getInfoAgence') ?>",
            method : "POST",
            dataType : "json",
            data : {agence_id : agence_id},
            success : function(response){

              if(!response.error){
                //Mise à jour des champs de la modal par rapport au json retourné
                $('#edit_agence_id').val(response.info_agence.agence_id);
                $('#edit_agence_code').val(response.info_agence.agence_id);

                $('#edit_agence_libelle').val(response.info_agence.agence_libelle);
                
                $('#editAgenceModal').modal('show');
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
        
      })

      $(document).on('click', '.delete-agence', function(){
        let campagneId = $(this).data('agence');
        $('#agenceToDesactivate').val(campagneId);
        $('#deleteAgenceModal').modal('show');
      });

      $(document).on('click', '#confirmDesactivateAgence', function(){

        $('#confirmDesactivateAgenceForm').submit();

      });

   
    
    

});

</script>
