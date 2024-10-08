<!-- MODAL SELECTION COULEUR -->
<div class="modal fade" id="editcalendar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Edition calendrier" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edition calendrier agence</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_edit_calendar" action="<?= site_url('agence/editCalendar') ?>">
            
              <select class="form-select" name="editcalendar_etat" id="editcalendar_etat" aria-label="Etat">
                <?php foreach($listEtat as $etat): ?>
                  <option value="<?= $etat->etatagence_id ?>"><?= $etat->etatagence_libelle ?> <span width="40%" style="background-color: <?= $etat->coulagence_hexa ?>;">&nbsp;&nbsp;</span></option>
                <?php endforeach; ?>
              </select>

              <input type="hidden" id="editcalendar_agence" name="editcalendar_agence" value="" />
              <input type="hidden" id="editcalendar_date" name="editcalendar_date" value="" />

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_editcalendar" name="save_editcalendar"  value="save_editcalendar" class="btn btn-primary">Modifier</button>
              </div>
             
          </form>


      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL AJOUT CAMPAGNE -->