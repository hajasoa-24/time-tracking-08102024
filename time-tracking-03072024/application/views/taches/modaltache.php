<div class="modal fade" id="validationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Commentaire</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Ajouter un commentaire</p>
              <form id="" name="" action="<?= site_url('tache/commentaire') ?>" method="post">
                  <input type="hidden" name="id_tache" id="id_tache">
                  <textarea id="w3review" name="commentaire" rows="4" cols="50" class="form-control"></textarea>
             
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" id="" value="" class="btn btn-primary">Valider</button>
          </form>
      </div>

      
    </div>
  </div>
</div>