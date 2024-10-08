<!-- MODAL AJOUT CONGE -->
<div class="modal fade" id="addCongeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Nouvelle demande de congés" aria-hidden="true">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Nouvelle demande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div id="errmsg-addcongeModal" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none">
            <p></p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <form method="post" id="form_add_conge" name="form_add_conge" action="<?= site_url('conges/saveNewConge') ?>">

               <div class="form-group row">
                    <label for="conge_type" class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <select required="true" id="conge_type" class="form-control" name="conge_type">
                            <option value="">-</option>
                            <?php foreach ($listTypeConge as $typeConge) { ?>
                                <option value="<?php echo $typeConge->typeconge_id ?>"><?php echo $typeConge->typeconge_libelle ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
              </div>

              <div class="form-group row form-date">
                  <label for="conge_datedebut" class="col-sm-2 col-form-label">Du</label>
                  <div class="col-sm-7">
                    <input type="date" required="true" name="conge_datedebut" class="form-control" id="conge_datedebut" placeholder="Du">
                  </div>
                  <div class="col-sm-3 form-conge">
                    <select name="conge_heuredatedebut" id="conge_heuredatedebut" class="form-control">
                      <option value="09:00">Matin</option>
                      <option value="14:00">Après-midi</option>
                    </select>
                  </div>
                  <div class="col-sm-3 form-permission">
                    <input type="time" step="1800" name="permission_heuredatedebut" id="permission_heuredatedebut" pattern="[0-9]{2}:[0-9]{2}" placeholder="" />
                  </div>
              </div>

              <div class="form-group row form-date">
                  <label for="conge_datefin" class="col-sm-2 col-form-label">Au</label>
                  <div class="col-sm-7">
                    <input type="date" required="true" name="conge_datefin" class="form-control" id="conge_datefin" placeholder="Au">
                  </div>
                  <div class="col-sm-3 form-conge">
                    <select name="conge_heuredatefin" id="conge_heuredatefin" class="form-control">
                      <option value="13:00">Après-midi</option>
                      <option value="18:00">Soir</option>
                    </select>
                  </div>
                  <div class="col-sm-3 form-permission">
                    <input type="time" step="1800" name="permission_heuredatefin" id="permission_heuredatefin" pattern="[0-9]{2}:[0-9]{2}" placeholder="" />
                  </div>
              </div>

              <div class="form-group row form-date">
                  <label for="conge_dateretour" class="col-sm-2 col-form-label">Retour</label>
                  <div class="col-sm-7">
                    <input type="date" required="true" name="conge_dateretour" class="form-control" id="conge_dateretour" placeholder="Retour">
                  </div>
                  <div class="col-sm-3 form-conge">
                    <select name="conge_heuredateretour" id="conge_heuredateretour" class="form-control">
                      <option value="08:00">Matin</option>
                      <option value="14:00">Après-midi</option>
                      <!--<option value="19:00">Soir</option>-->
                    </select>
                  </div>
                  <div class="col-sm-3 form-permission">
                    <input type="time" step="1800" name="permission_heuredateretour" id="permission_heuredateretour" pattern="[0-9]{2}:[0-9]{2}" placeholder="" />
                  </div>
              </div>

              <div class="form-group row form-date">
                  <label for="conge_duree" class="col-sm-2 col-form-label">Durée</label>
                  <div class="col-sm-4 input-group">
                    <input type="text" readonly="true" required="true" name="conge_duree" class="form-control" id="conge_duree" placeholder="">
                    <span class="input-group-text" id="duree-suffix">jour(s)</span>
                  </div>
              </div>

                <div class="form-group row form-motif">
                    <label for="conge_motif" class="col-sm-2 col-form-label">Motif</label>
                    <div class="col-sm-10">
                        <textarea required="true" name="conge_motif" class="form-control" id="conge_motif"></textarea>
                    </div>
                </div>

              <input type="hidden" id="edit-conge-id" value="" name="edit-conge-id">
              <input type="hidden" id="edit-conge-user" value="" name="edit-conge-user">
              <input type="hidden" id="form-addconge-verif" name="send" value="sent">
              <input type="hidden" id="action-addCongeModal" name="action-addCongeModal" value="">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_conge" value="save_conge" class="btn btn-primary">Envoyer</button>
              </div>
             
          </form>


      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL AJOUT CONGE -->


<!-- MODAL SUPPRESSION CONGE -->
<div class="modal fade" id="deleteCongeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Supprimer une demande de congés" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suppression d'un congé</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p>Etes-vous sur de vouloir annuler cette demande de congé ? </p>
              <form id="confirmDeleteCongeForm" name="confirmDeleteCongeForm" action="<?= site_url('conges/deleteConge') ?>" method="post">
                  <input type="hidden" name="sendDelete" value="sent">
                  <input type="hidden" name="congeToDelete" id="congeToDelete" value="">
                  <input type="hidden" name="congeToDeleteEtat" id="congeToDeleteEtat" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmDeleteConge" value="delete-conge" class="btn btn-primary">Confirmer l'annulation</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION CONGE -->

<!-- MODAL VALIDATION CONGE -->
<div class="modal fade" id="validateCongeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Valider une demande" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Valider une demande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              <p id="message-validate-conge"></p>
              <form id="confirmvalidateCongeForm" name="confirmvalidateCongeForm" action="<?= site_url('conges/validateConge') ?>" method="post">
                  <input type="hidden" id="validateConge" name="validate" value="">
                  <input type="hidden" name="congeToValidate" id="congeToValidate" value="">
                  <input type="hidden" name="congeEtat" id="congeEtat" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmValidateConge" value="validate-conge" class="btn btn-primary">Valider</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL VALIDATION CONGE -->

<!-- MODAL REFUS CONGE -->
<div class="modal fade" id="refusCongeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Refuser une demande" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Refuser une demande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              
              <form id="confirmRefusCongeForm" name="confirmRefusCongeForm" action="<?= site_url('conges/refuserConge') ?>" method="post">
                <p id="message-refus-conge"></p>
                <input type="hidden" id="refuserConge" name="refuser" value="">
                  <input type="hidden" name="congeToRefuse" id="congeToRefuse" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmRefusConge" value="refuser-conge" class="btn btn-primary">Refuser</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL REFUS CONGE -->

<!-- MODAL COMMENTER CONGE -->
<div class="modal fade" id="commentCongeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Refuser une demande" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Commentaire sur congés</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="form-group row">
              
              <form id="confirmCommentCongeForm" name="confirmCommentCongeForm" action="<?= site_url('conges/commentConge') ?>" method="post">
                <p id="message-comment-conge"></p>

                <div class="form-group row">
                    <label for="conge_motif" class="col-sm-4 col-form-label">Commentaire</label>
                    <div class="col-sm-8">
                        <textarea required="true" name="conge_commentaire" class="form-control" id="conge_commentaire"></textarea>
                    </div>
                </div>
                <input type="hidden" id="commentConge" name="comment" value="">
                  <input type="hidden" name="congeToComment" id="congeToComment" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmCommentConge" value="comment-conge" class="btn btn-primary">Ajouter le commentaire</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL COMMENTER CONGE -->

<!-- MODAL SUIVI CONGE -->
<div class="modal fade" id="suiviCongeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Suivi demande" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Suivi de demande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
      <div class="modal-body">

          <div class="suivi-details">
            <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Etat</th>
                      <th>Validé par</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
            </table>
            
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
          
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL SUIVI CONGE -->


<!-- MODAL TRAITEMENT RH CONGE -->
<div class="modal fade" id="treatementCongeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Traiter une demande" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Traiter une demande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

          <div class="form-group row">
              <p id="message-treat-conge"></p>
              <form id="confirmtreatementCongeForm" name="confirmtreatementCongeForm" action="<?= site_url('conges/treatConge') ?>" method="post">
                  <input type="hidden" id="treatConge" name="treatConge" value="">
                  <input type="hidden" name="congeToTreat" id="congeToTreat" value="">
                  <input type="hidden" name="congeEtat" id="congeEtat" value="">
                  <div id="action_traiter"></div>
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmTreatementConge" value="treatement-conge" class="btn btn-primary">Enregister</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL TRAITEMENT RH CONGE  -->

<!-- MODAL EDITION SOLDES CONGES ET DROITS PERMISSION RH -->
<div class="modal fade" id="editSoldesDroitsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Edition Soldes et droits" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edition Soldes et droits</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

          <div class="form-group row">
              <p id="message-treat-conge"></p>
              <form id="confirmEditSoldesDroitsForm" name="confirmEditSoldesDroitsForm" action="<?= site_url('conges/saveEditSoldesDroits') ?>" method="post">
                  
                  <div class="form-group row">
                    <label for="usr_soldeconge" class="col-sm-4 col-form-label">Soldes congés</label>
                    <div class="col-sm-8">
                      <input type="text" name="usr_soldeconge" class="form-control" id="usr_soldeconge" placeholder="Soldes">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="usr_droitpermission" class="col-sm-4 col-form-label">Droit permissions</label>
                    <div class="col-sm-8">
                      <input type="text" name="usr_droitpermission" class="form-control" id="usr_droitpermission" placeholder="Droits">
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="edit_soldesdroitscommentaire" class="col-sm-4 col-form-label">Commentaire</label>
                    <div class="col-sm-8">
                        <textarea required="true" name="edit_soldesdroitscommentaire" class="form-control" id="edit_soldesdroitscommentaire"></textarea>
                    </div>
                </div>

                  <input type="hidden" name="edit-soldesdroits-user" id="edit-soldesdroits-user" value="">
              </form>
          </div>
        
      </div>
      
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="button" id="confirmEditSoldesDroitsBtn" value="edit-soldesdroits" class="btn btn-primary">Enregister</button>
      </div>
      
    </div>
  </div>
</div>
<!-- END MODAL EDITION SOLDES CONGES ET DROITS PERMISSION RH -->


<script type="text/javascript">
  
  //GLOBAL
  var typeConge = "<?= TYPECONGE_CONGE ?>";
  var typePermission = "<?= TYPECONGE_PERMISSION ?>";

  $(document).ready(function() {
    
    $('#action-addCongeModal').val('add');

    $('.form-conge, .form-permission, .form-date, .form-motif').hide();

      $(document).on('change', '#conge_type', function(){
        
        $('.form-conge, .form-permission, .form-date').hide();
        $('#conge_duree').val('');
        $('#permission_heuredatedebut, #permission_heuredatefin').val('00:00');

        
        let selectedType = $(this).val();
        let labelDuree = 'jour(s)';
        if(selectedType == typeConge){
          $('.form-date, .form-conge').show();
        }else if(selectedType == typePermission){
          $('.form-date, .form-permission').show();
          labelDuree = 'heure(s)';
        }
        $('#duree-suffix').html(labelDuree);
      })

      $(document).on('change', '#conge_datedebut', function(){
        let dateDebut = $(this).val();
        $('#conge_datefin').attr('min', dateDebut);
        $('#conge_dateretour').attr('min', dateDebut);
      })

      $(document).on('click', '.delete-conge', function(){
        let congeId = $(this).data('conge');
        $('#congeToDelete').val(congeId);
        $('#deleteCongeModal').modal('show');
      });

      $(document).on('click', '#confirmValidateConge', function(){
        $('#validateConge').val('sent');
        
        $('#confirmvalidateCongeForm').submit();
      });

      $(document).on('click', '.validate-conge', function(){

        let data = $('#congesavalider').DataTable().row($(this).parents('tr')).data();
        
        let messageModal = '<table class="table table-striped"><tr><th>Demandeur : </th><td>'+data.usr_prenom+'</td></tr>';
        messageModal += '<tr><th>Solde de congé</th><td>'+data.usr_soldeconge+'</td></tr>';
        messageModal += '<tr><th>Droit de permission</th><td>'+data.usr_droitpermission+'</td></tr>';
        messageModal += '<tr><th>Type</th><td>'+data.typeconge_libelle+'</td></tr>';
        if(data.conge_type == typeConge){

          messageModal += '<tr><th>Du</th><td>'+moment(data.conge_datedebut).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Au</th><td>'+moment(data.conge_datefin).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Retour</th><td>'+moment(data.conge_dateretour).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Durée</th><td>'+data.conge_duree+' jours</td></tr>';

          messageModal += '<tr><th>Motif</th><td>'+data.conge_motif+'</td></tr>';

          
        }else if(data.conge_type == typePermission){
          
          messageModal += '<tr><th>Du</th><td>'+moment(data.conge_datedebut).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Au</th><td>'+moment(data.conge_datefin).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Retour</th><td>'+moment(data.conge_dateretour).format('DD MMM YYYY HH:mm')+'</td></tr>';

          messageModal += '<tr><th>Durée</th><td>'+(data.conge_duree * 8)+' heures</td></tr>';
          messageModal += '<tr><th>Retour</th><td>'+moment(data.conge_dateretour).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Motif</th><td>'+data.conge_motif+'</td></tr>';

        }
        messageModal += '</table>';
        let congeId = data.conge_id;
        $('#congeEtat').val(data.conge_etat);
        $('#congeToValidate').val(congeId);
        $('#message-validate-conge').html(messageModal);
        $('#validateCongeModal').modal('show');
      });

      $(document).on('click', '.refus-conge', function(){

        let currentTable = $(this).closest('table.dataTable');
        let data = currentTable.DataTable().row($(this).parents('tr')).data();

        let messageModal = '<table class="table table-striped"><tr><th>Demandeur : </th><td>'+data.usr_prenom+'</td></tr>';
        messageModal += '<tr><th>Solde de congé</th><td>'+data.usr_soldeconge+'</td></tr>';
        messageModal += '<tr><th>Droit de permission</th><td>'+data.usr_droitpermission+'</td></tr>';
        messageModal += '<tr><th>Type</th><td>'+data.typeconge_libelle+'</td></tr>';
        if(data.conge_type == typeConge){

          messageModal += '<tr><th>Du</th><td>'+moment(data.conge_datedebut).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Au</th><td>'+moment(data.conge_datefin).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Durée</th><td>'+data.conge_duree+' jours</td></tr>';
          
        }else if(data.conge_type == typePermission){
          
          messageModal += '<tr><th>Du</th><td>'+moment(data.conge_datedebut).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Au</th><td>'+moment(data.conge_datefin).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Durée</th><td>'+(data.conge_duree * 8)+' heures</td></tr>';
        }
        messageModal += '<tr><th>Motif</th><td><textarea class="form-control" id="motif_refus_conge" name="motif_refus_conge"></textarea></td></tr>';
        messageModal += '</table>';
        let congeId = data.conge_id;
        //$('#congeEtat').val(data.conge_etat);
        $('#congeToRefuse').val(congeId);
        $('#message-refus-conge').html(messageModal);
        $('#refusCongeModal').modal('show');
      });

      $(document).on('click', '.comment-conge', function(){

        let data = $('#congesavalider').DataTable().row($(this).parents('tr')).data();

        let messageModal = '<table class="table table-striped"><tr><th>Demandeur : </th><td>'+data.usr_prenom+'</td></tr>';
        messageModal += '<tr><th>Solde de congé</th><td>'+data.usr_soldeconge+'</td></tr>';
        messageModal += '<tr><th>Droit de permission</th><td>'+data.usr_droitpermission+'</td></tr>';
        messageModal += '<tr><th>Type</th><td>'+data.typeconge_libelle+'</td></tr>';
        if(data.conge_type == typeConge){

          messageModal += '<tr><th>Du</th><td>'+moment(data.conge_datedebut).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Au</th><td>'+moment(data.conge_datefin).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Durée</th><td>'+data.conge_duree+' jours</td></tr>';
          
        }else if(data.conge_type == typePermission){
          
          messageModal += '<tr><th>Du</th><td>'+moment(data.conge_datedebut).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Au</th><td>'+moment(data.conge_datefin).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Durée</th><td>'+(data.conge_duree * 8)+' heures</td></tr>';
        }
        //messageModal += '<tr><th>Motif</th><td><textarea class="form-control" id="motif_refus_conge" name="motif_refus_conge"></textarea></td></tr>';
        messageModal += '</table>';
        let congeId = data.conge_id;
        
        $('#congeToComment').val(congeId);
        $('#message-comment-conge').html(messageModal);
        $('#commentCongeModal').modal('show');
      });


      $(document).on('click', '.edit-soldesdroits', function(){

        let data = $('#soldesdroitsTable').DataTable().row($(this).parents('tr')).data();
        console.log(data);
        let user = data.usr_id;
        
        $('#edit-soldesdroits-user').val(user);
        $('#usr_soldeconge').val(data.usr_soldeconge);
        $('#usr_droitpermission').val(data.usr_droitpermission);
        $('#editSoldesDroitsModal').modal('show');
      });


      $(document).on('click', '.treat-conge', function(){

        let data = $('#congeatraiter').DataTable().row($(this).parents('tr')).data();
        let actionModal = '';
        let messageModal = '<h6>Demande initiale</h6><table class="table table-striped"><tr><th>Demandeur : </th><td>'+data.usr_prenom+'</td></tr>';
        messageModal += '<tr><th>Solde de congé</th><td>'+data.usr_soldeconge+'</td></tr>';
        messageModal += '<tr><th>Droit de permission</th><td>'+data.usr_droitpermission+'</td></tr>';
        messageModal += '<tr><th>Type</th><td>'+data.typeconge_libelle+'</td></tr>';
        if(data.conge_type == typeConge){

          messageModal += '<tr><th>Du</th><td>'+moment(data.conge_datedebut).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Au</th><td>'+moment(data.conge_datefin).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Retour</th><td>'+moment(data.conge_dateretour).format('DD MMM YYYY a')+'</td></tr>';
          messageModal += '<tr><th>Durée</th><td>'+data.conge_duree+' jours</td></tr>';
          messageModal += '<tr><th>Motif</th><td>'+data.conge_motif+'</td></tr>';

          
        }else if(data.conge_type == typePermission){
          
          messageModal += '<tr><th>Du</th><td>'+moment(data.conge_datedebut).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Au</th><td>'+moment(data.conge_datefin).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Retour</th><td>'+moment(data.conge_dateretour).format('DD MMM YYYY HH:mm')+'</td></tr>';
          messageModal += '<tr><th>Durée</th><td>'+(data.conge_duree * 8)+' heures</td></tr>';
          messageModal += '<tr><th>Motif</th><td>'+data.conge_motif+'</td></tr>';

        }
        messageModal += '</table>';
        
        actionModal += '<h6>Action à faire :</h6>'+
                        '<div class="col-sm-10 offset-sm-1" >'+
                          '<div class="form-check">'+
                            '<input class="form-check-input" value="avaloirsurconge" type="radio" name="actionTraitement" id="aValoirSurConge"><label class="form-check-label" for="aValoirSurConge">A Valoir sur Congés</label>'+
                          '</div>'+
                          '<div class="form-check">'+
                            '<input class="form-check-input" value="avaloirsurdroitspermission" type="radio" name="actionTraitement" id="aValoirSurDroitsPermission"><label class="form-check-label" for="aValoirSurDroitsPermission">A Valoir sur les 80h de droits de permission</label>'+
                          '</div>'+
                          '<div class="form-check">'+
                            '<input class="form-check-input" value="reposmaladie" type="radio" name="actionTraitement" id="reposMaladie"><label class="form-check-label" for="reposMaladie">Repos maladie</label>'+
                          '</div>'+
                          '<div class="form-check">'+
                            '<input class="form-check-input" value="assistancematernelle" type="radio" name="actionTraitement" id="assistanceMaternelle"><label class="form-check-label" for="assistanceMaternelle">Assistance maternelle</label>'+
                          '</div>'+
                          '<div class="form-check">'+
                            '<input class="form-check-input" value="congedematernite" type="radio" name="actionTraitement" id="congedematernite"><label class="form-check-label" for="congedematernite">Congé de maternité</label>'+
                          '</div>'+
                          '<div class="form-check">'+
                            '<input class="form-check-input" value="autres" type="radio" name="actionTraitement" id="autresActionTraitement"><label class="form-check-label" for="autresActionTraitement">Autres</label>'+
                          '</div>'+
                        '</div>'; 
        let congeId = data.conge_id;
        $('#congeEtat').val(data.conge_etat);
        $('#congeToTreat').val(congeId);
        $('#message-treat-conge').html(messageModal);
        $('#action_traiter').empty().append(actionModal);
        $('#treatementCongeModal').modal('show');
      });

      $(document).on('click', '#confirmTreatementConge', function(){
        $('#treatConge').val('sent');
        
        $('#confirmtreatementCongeForm').submit();
      });

      $(document).on('click', '#confirmRefusConge', function(){
        $('#refuserConge').val('sent');
        $('#confirmRefusCongeForm').submit();
      }); 

      $(document).on('click', '#confirmCommentConge', function(){
        $('#commentConge').val('sent');
        $('#confirmCommentCongeForm').submit();
      }); 

      $(document).on('click', '#confirmEditSoldesDroitsBtn', function(){
        $('#confirmEditSoldesDroitsForm').submit();
      }); 

      $(document).on('click', '#confirmDeleteConge', function(){
        $('#confirmDeleteCongeForm').submit();
      });

      $(document).on('change', '#conge_datedebut, #conge_datefin, #conge_dateretour, #conge_heuredatedebut, #conge_heuredatefin, #conge_heuredateretour, #permission_heuredatedebut, #permission_heuredatefin, #permission_heuredateretour', function(){
        
        let eltID = $(this).attr('id');
        let du = $('#conge_datedebut').val();
        let au = $("#conge_datefin").val();
        let retour = $("#conge_dateretour").val();
        let dateDu, dateAu, dateRetour = '';
        let type = $('#conge_type').val();
        let heureDu, heureAu, heureRetour = '00:00';
        let action = $('#action-addCongeModal').val();
        let id = $('#edit-conge-id').val();
        let user = $('#edit-conge-user').val();

        if(type == typeConge){
          heureDu = $('#conge_heuredatedebut').val();
          heureAu = $('#conge_heuredatefin').val();
          heureRetour = $('#conge_heuredateretour').val();
        }else if(type == typePermission){
          heureDu = $('#permission_heuredatedebut').val();
          heureAu = $('#permission_heuredatefin').val();
          heureRetour = $('#permission_heuredateretour').val();
        }

        dateDu = du + ' ' + heureDu;
        dateAu = au + ' ' + heureAu;
        dateRetour = retour + ' ' + heureRetour;

        //Action specifique selon l'element
        if(eltID == 'conge_datefin' || eltID == 'conge_heuredatefin'){
          if(heureAu != '18:00'){
            $('#conge_dateretour').val(au);
            $('#conge_heuredateretour').val('14:00');
          }else{
            let nextDay = moment(au).add(1, 'd').format('YYYY-MM-DD');
            console.log(nextDay);
            $('#conge_dateretour').val(nextDay);
            $('#conge_heuredateretour').val('08:00');
          }
          
        }
        //Recuperation du nombre de jours de congés
        $.ajax({
          url : '<?= site_url('conges/calculateDureeConge') ?>',
          type : 'post',
          dataType : 'json',
          data : {
            'du' : dateDu, 
            'au' : dateAu, 
            'retour' : dateRetour,
            'type' : type, 
            'action' : action, 
            'id' : id, 
            'user' : user
          },
          success : function(response){
            if(!response.error){
              $('#errmsg-addcongeModal p').html('');
              $('#errmsg-addcongeModal').hide();
              $('#save_conge').removeAttr('disabled');
              $('#conge_duree').val(response.data);
              $('.form-motif').show();
            }else{
              $('#conge_duree').val('');
              $('.form-motif').hide();
              if(response.message){
                $('#errmsg-addcongeModal p').html(response.message);
                $('#errmsg-addcongeModal').show();
                $('#save_conge').attr('disabled', true);
              }
            }
          }
        })

      })

  } );
</script>
