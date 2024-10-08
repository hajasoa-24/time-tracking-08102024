<!-- MODAL GESTION DES AJUSTEMENTS -->
<div class="modal fade" id="ajustementmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajustement de temps" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Ajustement de temps</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="post" id="form_ajustement" action="<?= site_url('histo/saveAjustement') ?>">
              
                <div class="form-group row">

                  <label for="service_site" class="col-sm-2 col-form-label">Début</label>
                  <div class="col-sm-4">
                    <input type="datetime-local" disabled="true" name="shiftBegin" class="form-control dateRef" id="shiftBegin" >
                  </div>
                  <div class="col-sm-1"><button type="button" class="btn btn-sm btn-secondary clone-date"><i class="fa fa-files-o" aria-hidden="true"></i></button></div>
                  <div class="col-sm-4">
                    <input type="datetime-local" step="any" name="ajustBegin" class="form-control dateDest" id="ajustBegin" >
                  </div>
                        
                </div>

                <div class="form-group row">

                  <label for="service_site" class="col-sm-2 col-form-label">Fin</label>
                  <div class="col-sm-4">
                    <input type="datetime-local" disabled="true" name="shiftEnd" class="form-control dateRef" id="shiftEnd" >
                  </div>
                  <div class="col-sm-1"><button type="button" class="btn btn-sm btn-secondary clone-date"><i class="fa fa-files-o" aria-hidden="true"></i></button></div>
                  <div class="col-sm-4">
                    <input type="datetime-local" step="any" name="ajustEnd" class="form-control dateDest" id="ajustEnd" >
                  </div>
                        
                </div>
                
                <!-- <h6 class="mt-5">Heures supplémentaires</h6>
                <div class="form-group row">

                  <label for="service_site" class="col-sm-2 col-form-label">HS</label>
                  <div class="col-sm-4">
                    <input type="datetime-local" name="hs_begin" class="form-control" id="hs_begin" >
                  </div>
                  <div class="col-sm-4 offset-sm-1">
                    <input type="datetime-local" name="hs_end" class="form-control" id="hs_end" >
                  </div>
                        
                </div> -->

                <h6 class="mt-5">Liste des pauses et ajustements</h6>

                  <div class="form-group row">
                      <div class="col-sm-2">
                          <select name="addPauseLib" id="addPauseLib" class="form-control">
                              <option value="">--Choisir une pause--</option>
                                  <?php if(isset($top['listTypePause'])) : ?>
                                      <?php foreach($top['listTypePause'] as $typePause) : ?>
                                        <option value="<?= $typePause->typepause_id ?>"><?= $typePause->typepause_libelle ?></option>
                                      <?php endforeach; ?>
                                  <?php endif; ?>
                          </select>
                      </div>
                      <div class="col-sm-4">
                          <input type="datetime-local" name="addPauseBegin" class="form-control dateDest" id="addPauseBegin" >
                      </div>
                      <div class="col-sm-4">
                          <input type="datetime-local" name="addPauseEnd" class="form-control dateDest" id="addPauseEnd" >
                      </div>
                      <div class="col-sm-2">
                          <button type="button" id="add_pause_agent" value="add_pause_agent" class="btn btn-primary">Ajouter</button>
                      </div>
                  </div>
                    
                <div class="list_pause"></div>

                <input type="hidden" name="send" value="sent">
                <input type="hidden" name="shiftID" id="shiftID" value="">

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" id="save_ajustement" value="save_ajustement" class="btn btn-primary">Enregister</button>
                </div>
               
            </form>

        </div>
      
    </div>
    
  </div>
</div>
<!-- END MODAL GESTION DES AJUSTEMENTS -->



<script type="text/javascript">
  
  $(document).ready(function() {
      
       $('#save_service').on('click', function(){
        $('#form_add_service').submit();
      })

      $('#edit_service').on('click', function(){
        $('#form_edit_service').submit();
      })

      /**
      * Click sur le bouton prénom .show-ajustement-modal de l'agent pour ouvrir le modal d'ajustement
      */
      $(document).on('click', '.show-ajustement-modal', function(){
        let shift_id = $(this).data('shift');
        emptyModalAjustement();
        loadModalDatas(shift_id);
       })

       $('#add_pause_agent').on('click', function(){
         
         let pause_lib = $('#addPauseLib option:selected').val();
         let pause_begin = $('#addPauseBegin').val();
         let pause_end = $('#addPauseEnd').val();
         let shift_id = $('#shiftID').val();
         console.log(pause_lib, pause_begin, pause_end, shift_id)

         if(pause_lib && pause_begin && pause_end){
           $.ajax({
             url : "<?= site_url('histo/AddPauseAjustement') ?>",
             method : 'post',
             dataType : 'json',
             data : {lib : pause_lib, begin : pause_begin, end : pause_end, shift : shift_id},
             success : function(resp){
              if(!resp.err){
                loadModalDatas(shift_id);
              }else{
                alert("Erreur survenu lors de l'ajout de la pause");
              }
             }
           })
         }
       })

       function loadModalDatas(shift_id)
       {
          $.ajax({
            url : "<?= site_url('histo/getModalHistoriqueData') ?>",
            method : "POST",
            dataType : "json",
            data : {shift_id : shift_id},
            success : function(response){
              //console.log(response);
              let list_pause_html = '';
              if(!response.error){
                //Charger les données
                $('#shiftID').val(response.data.id);
                $('#shiftBegin').val(response.data.debut);
                $('#shiftEnd').val(response.data.fin);
                //Afficher les ajustements si déjà présents
                $('#ajustBegin').val(response.data.ajustdebut)
                $('#ajustEnd').val(response.data.ajustfin)
                list_pause_html = prepareListPause(response.data.listPause);
                $('.list_pause').html(list_pause_html);
                //afficher la modal
                $('#ajustementmodal').modal('show');
              }else{
                //TODO
                alert('Erreur survenu')
              }
            }
          })
       }

       /**
        * Dupliquer la valeur du champ de date de gauche vers celui de droite
        */
       $('.clone-date').on('click', function(){
            let ref = $(this).closest('.form-group').find('.dateRef').val();
            $(this).closest('.form-group').find('.dateDest').val(ref);
       })

       $('#save_ajustement').on('click', function(){
           $('#form_ajustement').submit();
       })

       $(document).on('click', '.clone-pause', function(){
        let ref = $(this).data('ref');
        let debut = $('#'+ref+'_begin').val();
        let fin = $('#'+ref+'_end').val();
        $('#ajust_'+ref+'_begin').val(debut);
        $('#ajust_'+ref+'_end').val(fin);
       })
  } );

  function emptyModalAjustement()
  {
      $('#ajustementModal input').val('');
  }

  /**
   * Preparer la liste des pauses
   */
  function prepareListPause(list)
  {
    let html_data = '';
    list.forEach(element => {
      console.log(element)
      let libPause = 'pause_'+element.pause_id;
      html_data += '<div class="form-group row">' +
                        '<label for="'+libPause+'" class="col-sm-2 col-form-label">'+element.pause_libelle+'</label>'+
                        '<div class="col-sm-4">'+
                          '<input type="datetime-local" disabled="true" name="'+libPause+'_begin" class="form-control dateRef" id="'+libPause+'_begin" value="'+element.pause_begin+'">'+
                        '</div>'+
                        '<div class="col-sm-4">'+
                          '<input type="datetime-local" disabled="true" name="'+libPause+'_end" class="form-control dateRef" id="'+libPause+'_end" value="'+element.pause_end+'">'+
                        '</div>'+
                        '<div class="col-sm-1"><button type="button" data-ref="'+libPause+'" class="btn btn-sm btn-primary clone-pause"><i class="fa fa-files-o" aria-hidden="true"></i></button></div>'+
                        '<div class="col-sm-4 offset-sm-2">'+
                          '<input type="datetime-local" name="ajustpause_begin[]" class="form-control dateRef" id="ajust_'+libPause+'_begin" value="'+element.pause_ajustbegin+'">'+
                        '</div>'+
                        '<div class="col-sm-4">'+
                          '<input type="datetime-local" name="ajustpause_end[]" class="form-control dateRef" id="ajust_'+libPause+'_end" value="'+element.pause_ajustend+'">'+
                        '</div>'+
                        '<input type="hidden" name="ajustpause_pause[]" value="'+element.pause_id+'"/>'+
                    '</div>';
    });
    
    return html_data;
  }
</script>