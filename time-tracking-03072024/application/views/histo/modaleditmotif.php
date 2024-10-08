<!-- MODAL MOTIF -->
<div class="modal fade" id="addMotifModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter un motif" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajout d'un  motif</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_add_motif" action="<?= site_url('presence/saveNewMotif') ?>">
              <div class="form-group row">
                  <label for="motif_day_readonly" class="col-sm-4 col-form-label">Jour</label>
                  <div class="col-sm-8">
                    <input type="text" name="motif_day_readonly" disabled="true" class="form-control" id="motif_day_readonly">
                    
                  </div>
              </div> 
              <div class="form-group row">
                  <label for="motif_incomplet" class="col-sm-4 col-form-label">Incomplet ?</label>
                  <div class="col-sm-8">
                    <input class="form-check-input" type="checkbox" value="" name="motif_incomplet" id="motif_incomplet">
                      
                  </div>
              </div>    


              <div class="form-group row">
                <label for="listMotif" class="col-sm-2 col-form-label">Motif</label>
                <div class="col-sm-10">
                      <select id="motif_libelle" class="form-control" name="motif_libelle">
                        <option value="">-</option>
                        <?php foreach ($listMotif as $motif) { ?>
                          <option value="<?php echo $motif->motif_id ?>"><?php echo $motif->motif_libelle ?></option>
                        <?php }
                         ?>
                       
                      </select>

                </div>
              </div> 

              <input type="hidden" name="motif_agent" id="motif_agent" value="">
              <input type="hidden" name="motif_shift" id="motif_shift" value="">
              <input type="hidden" name="motif_day" id="motif_day" value="">
              <input type="hidden" name="motif_redirect_back" id="motif_redirect_back" value="" >
              <input type="hidden" name="send" value="sent">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_motif" value="save_motif" class="btn btn-primary">Enregistrer</button>
              </div>
          </form>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL MOTIF -->


<script type="text/javascript">
  
  $(document).ready(function() {

     $('#save_motif').on('click', function(){
        $('#form_add_motif').submit();
      })

     /**
      * Click sur les boutons gerer motif pour chaque ligne
      */
      $(document).on('click', '.edit-presence', function(){
        $('#motif_incomplet').prop('checked', false);
        //Load agent_id on modal before showing it
        let agent_id = $(this).data('agent');
        let shift = $(this).data('shift');
        let day = $(this).data('day');
        let isPresent = $(this).data('presence');
        let incomplet = $(this).data('incomplet');
        let motif = $(this).data('motif');
        let redirect = $(this).data('redirect');
        if(moment(day).isValid()){
          day = moment(day, 'YYYY-MM-DD').format('DD-MM-YYYY');
        }
        $('#motif_agent').val(agent_id);
        $('#motif_shift').val(shift);
        $('#motif_day_readonly').val(day);
        $('#motif_day').val(day);
        $('#motif_libelle').val(motif);
        $('#motif_redirect_back').val(redirect);
        
        if(!isPresent){
          $('#motif_incomplet').prop('disabled', true);
        }

        if(incomplet){
          $('#motif_incomplet').prop('checked', true);
        }


        $('#addMotifModal').modal('show');
      });
     

  } );
</script>
