<?php 
    if($this->session->flashdata('msg') != null){
        //echo "<script>alert(".$this->session->flashdata('msg').")</script>";
    }
?>

<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h2>Gestion des profils</h2>
        </div>
    </div>
    
    <div class="row mt-3">
        <div class="col-md-12">
            <?php /**
             * Si liaison avec un modal, les modals se trouvent dans le fichier profil/modalprofil 
             * */ ?>
            <button id="add-profil" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#addprofilModal"><i class="fa fa-plus-circle pr-2"></i>Ajout profil</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table id="list-profil" class="table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libelle</th>
                        <th>Actif</th>
                        <th>Campagne</th>
                        <th>Liste des Process</th>
                        <th>Liste des agents</th>
                        <th>Date de création</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- MODAL AJOUT PROFIL -->
<div class="modal fade" id="addprofilModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter un profil" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ajout d'un profil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_add_profil" action="<?= site_url('primeprofil/saveNewPrimeProfil') ?>">

          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-general-primeprofil-tab" data-bs-toggle="tab" data-bs-target="#nav-general" type="button" role="tab" aria-controls="nav-general" aria-selected="true">General</button>
              <button class="nav-link" id="nav-affectation-primeprofil-tab" data-bs-toggle="tab" data-bs-target="#nav-affectation" type="button" role="tab" aria-controls="nav-affectation" aria-selected="false">Affectation des agents</button>
              <button class="nav-link" id="nav-process-primeprofil-tab" data-bs-toggle="tab" data-bs-target="#nav-process" type="button" role="tab" aria-controls="nav-process" aria-selected="false">Affectation des process</button>
              
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-general" role="tabpanel" aria-labelledby="nav-general-primeprofil-tab">
              <div class="col-sm-12 mt-2">

                  <div class="form-group row">
                      <label for="primeprofil_libelle" class="col-sm-2 col-form-label">Libellé</label>
                      <div class="col-sm-10">
                        <input type="text" required="true" name="primeprofil_libelle" class="form-control" id="primeprofil_libelle" placeholder="Libellé">
                      </div>
                  </div>

                  <div class="form-group row">

                    <label for="primeprofil_campagne" class="col-sm-2 col-form-label">Campagne</label>
                    <div class="col-sm-10">
                          <select id="primeprofil_campagne" required="required" class="form-control" name="primeprofil_campagne">
                            <option value="">-</option>
                            <?php foreach ($listCampagneUser as $campagne) { ?>
                              <option value="<?php echo $campagne->campagne_id ?>"><?php echo $campagne->campagne_libelle ?></option>
                            <?php }
                            ?>
                          
                          </select>
                      </div>
                  </div>

              </div>

            </div>
            <div class="tab-pane fade" id="nav-affectation" role="tabpanel" aria-labelledby="nav-affectation-primeprofil-tab">
                <div class="col-sm-12" id="affectation-error">
                  
                </div>
                <table  id="table-affectation-primeprofil-agent" class="table table-hover">
                  <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Matricule</th>
                        <th>Initiale</th>
                        <th>Site</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
                
            </div>

            <div class="tab-pane fade" id="nav-process" role="tabpanel" aria-labelledby="nav-process-primeprofil-tab">
                <div class="col-sm-12" id="process-error">
                  
                </div>
                <table  id="table-process-profil" class="table table-hover">
                  <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Mission</th>
                        <th>Process</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
                
            </div>


          </div>
                  
              
              <input type="hidden" name="send" value="sent">
              <input type="hidden" id="primeprofil_user" name="primeprofil_user" value="<?= $user ?>">

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" name="save_primeprofil" id="save_primeprofil" value="sent" class="btn btn-primary">Enregister</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL AJOUT PROFIL -->

<!--  MODAL MODIF PROFIL -->
<div class="modal fade" id="editprofilModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modification d'un profil</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" id="form_edit_profil" action="<?= site_url('primeprofil/saveEditPrimeProfil') ?>">

              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <button class="nav-link active" id="nav-general-primeprofil-tab-edit" data-bs-toggle="tab" data-bs-target="#nav-general-edit" type="button" role="tab" aria-controls="nav-general-edit" aria-selected="true">General</button>
                  <button class="nav-link" id="nav-affectation-primeprofil-tab-edit" data-bs-toggle="tab" data-bs-target="#nav-affectation-edit" type="button" role="tab" aria-controls="nav-affectation-edit" aria-selected="false">Affectation des agents</button>
                  <button class="nav-link" id="nav-process-primeprofil-tab-edit" data-bs-toggle="tab" data-bs-target="#nav-process-edit" type="button" role="tab" aria-controls="nav-process-edit" aria-selected="false">Affectation des process</button>
                  
                </div>
              </nav>
              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-general-edit" role="tabpanel" aria-labelledby="nav-general-primeprofil-tab-edit">
                  <div class="col-sm-12 mt-2">

                    <div class="form-group row">
                        <label for="edit_profil_libelle" class="col-sm-2 col-form-label">Libellé</label>
                        <div class="col-sm-10">
                            <input type="text" required="true" name="edit_primeprofil_libelle" class="form-control" id="edit_primeprofil_libelle" placeholder="Libellé" value="">
                        </div>
                    </div>

                    <div class="form-group row">

                      <label for="edit_primeprofil_campagne" class="col-sm-2 col-form-label">Campagne</label>
                      <div class="col-sm-10">
                            <select id="edit_primeprofil_campagne" required="required" class="form-control" name="edit_primeprofil_campagne">
                              <option value="">-</option>
                              <?php foreach ($listCampagneUser as $campagne) { ?>
                                <option value="<?php echo $campagne->campagne_id ?>"><?php echo $campagne->campagne_libelle ?></option>
                              <?php }
                              ?>
                            
                            </select>
                        </div>
                    </div>

                  </div>

                </div>
                <div class="tab-pane fade" id="nav-affectation-edit" role="tabpanel" aria-labelledby="nav-affectation-primeprofil-tab-edit">
                    <div class="col-sm-12" id="affectation-error-edit">
                      
                    </div>
                    <table  id="table-affectation-primeprofil-agent-edit" class="table table-hover">
                      <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Matricule</th>
                            <th>Initiale</th>
                            <th>Site</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                    
                </div>

                <div class="tab-pane fade" id="nav-process-edit" role="tabpanel" aria-labelledby="nav-process-primeprofil-tab-edit">
                    <div class="col-sm-12" id="process-error-edit">
                      
                    </div>
                    <table  id="table-process-profil-edit" class="table table-hover">
                      <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Mission</th>
                            <th>Process</th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                    
                </div>


              </div>
                    
                
              <input type="hidden" id="edit_primeprofil_id" name="edit_primeprofil_id" />
              <input type="hidden" name="send_edit" value="sent">
              <input type="hidden" id="edit_primeprofil_user" name="edit_primeprofil_user" value="<?= $user ?>">
              
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" id="save_editprimeprofil" name="save_editprimeprofil" value="sent" class="btn btn-primary">Enregister</button>
              </div>
            </form>
      </div>
    </div>
</div>

<!-- END MODAL MODIF PROFIL -->

<!-- MODAL SUPPRESSION PROFIL -->
<div class="modal fade" id="deleteprofilModal" tabindex="-1" role="dialog" aria-labelledby="deleteprofilModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Suppression d'un profil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
     
        <div class="modal-body">

            <div class="form-group row">
                <p>Etes-vous sur de bien vouloir désactiver ce profil ? </p>
                <form id="confirmDesactivateprofilForm" name="confirmDesactivateprofilForm" action="<?= site_url('primeprofil/desactivateProfil') ?>" method="post">
                    <input type="hidden" name="sendDesactivate" value="sent">
                    <input type="hidden" name="profilToDesactivate" id="profilToDesactivate" value="">
                </form>
            </div>
          
        </div>
      
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" id="confirmDesactivateprofil" name="confirmDesactivateprofil" value="sent" class="btn btn-primary">Confirmer la desactivation</button>
        </div>

      </div>
  </div>
</div>
<!-- END MODAL SUPPRESSION PROFIL -->

<!-- MODAL AFFECTATION PROFIL CAMPAGNE -->
<div class="modal fade" id="setProfilCampagneModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Ajouter un profil" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Affectation profil campagne</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <form method="post" id="form_add_profil" action="<?= site_url('primeprofil/saveAddProfilCampagne') ?>">
                  
              <div class="form-group row">
                  <label for="primepc_profil" class="col-sm-2 col-form-label">Profils</label>
                  <div class="col-sm-10">
                  <select id="primepc_profil" name="primepc_profil" class="form-select" multiple="multiple">
                      
                  </select>
                  </div>
              </div>


              <div class="form-group row">
                  <label for="primepc_campagne" class="col-sm-2 col-form-label">Campagnes</label>
                  <div class="col-sm-10">
                  <select id="primepc_campagne" name="primepc_campagne" class="form-select" multiple="multiple">
                      
                  </select>
                  </div>
              </div>

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" name="save_primeprofil" id="save_primeprofil" value="sent" class="btn btn-primary">Enregister</button>
              </div>
          </form>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL AFFECTATION PROFIL CAMPAGNE -->

<script type="text/javascript">
  
    $(document).ready(function(){

        //s$('#example-getting-started').multiselect();

        //initialisation datatable
        $("#list-profil").DataTable({
            language : {
                url : "<?= base_url("assets/datatables/fr-FR.json"); ?>"
            },
            ajax : "<?= site_url("primeprofil/getListprofil"); ?>",
            columns : [
                { data : "primeprofil_id" },
                { data : "primeprofil_libelle" },
                { 
                    data : null,
                    render : function(data, type, row){
                        return (data.primeprofil_actif) == "1" ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-danger">Non</span>';
                    } 
                },
                { data : "campagne_libelle" },
                { data : "list_process" },
                { data : "list_agent" },
                { data : "primeprofil_datecrea" },
                {
                    data: null,
                    render: function ( data, type, row ) {
                        return '<button title="modifier la profil" data-profil="'+data.primeprofil_id+'" class="edit-profil btn btn-secondary btn-sm mr-1"><i class="fa fa-pencil"></i></button>' + 
                                '<button title="supprimer la profil" data-profil="'+data.primeprofil_id+'" class="delete-profil btn btn-danger btn-sm mr-1"><i class="fa fa-trash-o"></i></button>';
                    }
                }
            ]
        });

        $('#edit-profil').on('click', function(){
          $('#form_edit_profil').submit();
        });

      /**
      * Click sur les boutons de modification profil pour chaque ligne
      */
      $(document).on('click', '.edit-profil', function(){
        let profil_id = $(this).data('profil');

        $.ajax({
            url : "<?= site_url('primeprofil/getInfoprofil') ?>",
            method : "GET",
            dataType : "json",
            data : {id : profil_id},
            success : function(response){
              if(!response.error){
                //Mise à jour des champs de la modal par rapport au json retourné
                $('#edit_primeprofil_id').val(response.data.primeprofil_id);
                $('#edit_primeprofil_libelle').val(response.data.primeprofil_libelle);
                $('#edit_primeprofil_campagne option[value='+response.data.primeprofil_campagne+']').prop('selected', true);
                
                $('#editprofilModal').modal('show');
                // ----------------
                let campagne = $('#edit_primeprofil_campagne').find(":selected").val();
                let profil = $('#edit_primeprofil_id').val();

                $('#table-process-profil-edit tbody').html('');
                let user = $('#edit_primeprofil_user').val();
                if(campagne == ''){
                  $('#table-process-profil-edit').hide();
                  $('#process-error-edit').html('<div class="row"><span class="alert alert-danger text-center">Campagne requise !</span></div>');
                }else{
                  $('#process-error-edit').html('');
                  $('#table-process-profil-edit').show();
                  getEditListProcessCampagne(campagne, profil);
                }

                // -----------------
                $('#table-affectation-primeprofil-agent-edit tbody').html('');
                if(campagne == ''){
                  $('#table-affectation-primeprofil-agent-edit').hide();
                  $('#affectation-error-edit').html('<div class="row"><span class="alert alert-danger text-center">Campagne requise !</span></div>');
                }else{
                  $('#affectation-error-edit').html('');
                  $('#table-affectation-primeprofil-agent-edit').show();
                  getEditListUserCampagne(user, campagne, true, profil);
                }
                
              }else{
                //TODO
                alert('Erreur survenu')
              }

            }
          })
        
      })

      $(document).on('click', '.delete-profil', function(){
      
        let profilId = $(this).data('profil');
        $('#profilToDesactivate').val(profilId);
        $('#deleteprofilModal').modal('show');
      });

      $(document).on('click', '#confirmDesactivateprofil', function(){

        $('#confirmDesactivateprofilForm').submit();

      });



      $(document).on('click', '#nav-affectation-primeprofil-tab', function(){
        let campagne = $('#primeprofil_campagne').find(":selected").val();

        $('#table-affectation-primeprofil-agent tbody').html('');
        let user = $('#primeprofil_user').val();
        if(campagne == ''){
          $('#table-affectation-primeprofil-agent').hide();
          $('#affectation-error').html('<div class="row"><span class="alert alert-danger text-center">Campagne requise !</span></div>');
        }else{
          $('#affectation-error').html('');
          $('#table-affectation-primeprofil-agent').show();
          getListUserCampagne(user, campagne, true);
        }

      })

      /*$(document).on('click', '#nav-affectation-primeprofil-tab-edit', function(){
        let campagne = $('#edit_primeprofil_campagne').find(":selected").val();
        let profil = $('#edit_primeprofil_id').val();

        $('#table-affectation-primeprofil-agent-edit tbody').html('');
        let user = $('#edit_primeprofil_user').val();
        if(campagne == ''){
          $('#table-affectation-primeprofil-agent-edit').hide();
          $('#affectation-error-edit').html('<div class="row"><span class="alert alert-danger text-center">Campagne requise !</span></div>');
        }else{
          $('#affectation-error-edit').html('');
          $('#table-affectation-primeprofil-agent-edit').show();
          getEditListUserCampagne(user, campagne, true, profil);
        }

      })*/

      $(document).on('click', '#nav-process-primeprofil-tab', function(){
        let campagne = $('#primeprofil_campagne').find(":selected").val();

        $('#table-process-profil tbody').html('');
        let user = $('#primeprofil_user').val();
        if(campagne == ''){
          $('#table-process-profil').hide();
          $('#process-error').html('<div class="row"><span class="alert alert-danger text-center">Campagne requise !</span></div>');
        }else{
          $('#process-error').html('');
          $('#table-process-profil').show();
          getListProcessCampagne(campagne);
        }

      })

      /*$(document).on('click', '#nav-process-primeprofil-tab-edit', function(){
        let campagne = $('#edit_primeprofil_campagne').find(":selected").val();
        let profil = $('#edit_primeprofil_id').val();

        $('#table-process-profil-edit tbody').html('');
        let user = $('#edit_primeprofil_user').val();
        if(campagne == ''){
          $('#table-process-profil-edit').hide();
          $('#process-error-edit').html('<div class="row"><span class="alert alert-danger text-center">Campagne requise !</span></div>');
        }else{
          $('#process-error-edit').html('');
          $('#table-process-profil-edit').show();
          getEditListProcessCampagne(campagne, profil);
        }
        //Recupération de la liste des agents selon la campagne selectionnée

      })*/

      function getListUserCampagne(user, campagne, is_poids){
        
        let data = { 'user' : user, 'campagne' : campagne, 'is_poids' : is_poids };
        $.ajax({
            url : "<?= site_url('campagne/getListUserCampagne') ?>",
            method : "GET",
            dataType : "json",
            data : data,
            success : function(response){
              if(!response.error){
                let list = response.data;
                let tbodyData = '';
                list.forEach( (agent) => {
                  tbodyData += '<tr>'+
                                  '<td><input type="checkbox" name="primeaffectationupc_user[]" class="primeaffectationupc_user" value="'+agent.usr_id+'"></td>'+
                                  '<td>'+agent.usr_id+'</td>'+
                                  '<td>'+agent.usr_nom+'</td>'+
                                  '<td>'+agent.usr_prenom+'</td>'+
                                  '<td>'+agent.usr_matricule+'</td>'+
                                  '<td>'+agent.usr_initiale+'</td>'+
                                  '<td>'+agent.site_libelle+'</td>'+
                                '</tr>';
                });
                $('#table-affectation-primeprofil-agent tbody').html(tbodyData);
              }else{
                //TODO
                
              }

            }
        })

      }

      function getEditListUserCampagne(user, campagne, is_poids, profil){
        
        let data = { 'user' : user, 'campagne' : campagne, 'is_poids' : is_poids, 'profil' : profil };
        $.ajax({
            url : "<?= site_url('campagne/getListUserCampagne') ?>",
            method : "GET",
            dataType : "json",
            data : data,
            success : function(response){
              if(!response.error){
                let list = response.data;
                let affectedAgent = response.affected;
                let tbodyData = '';
                list.forEach( (agent) => {

                  tbodyData += '<tr>'+
                                  '<td><input type="checkbox" id="edit_primeaffectationupc_user_'+agent.usr_id+'" name="edit_primeaffectationupc_user[]" class="edit_primeaffectationupc_user" value="'+agent.usr_id+'"></td>'+
                                  '<td>'+agent.usr_id+'</td>'+
                                  '<td>'+agent.usr_nom+'</td>'+
                                  '<td>'+agent.usr_prenom+'</td>'+
                                  '<td>'+agent.usr_matricule+'</td>'+
                                  '<td>'+agent.usr_initiale+'</td>'+
                                  '<td>'+agent.site_libelle+'</td>'+
                                '</tr>';
                });
                $('#table-affectation-primeprofil-agent-edit tbody').html(tbodyData);

                affectedAgent.forEach( (agent) => {
                  $('#edit_primeaffectationupc_user_' + agent.primeaffectationupc_user).prop('checked', true);
                });

              }else{
                //TODO
                
              }

            }
        })

      }

      function getListProcessCampagne(campagne){
        
        $.ajax({
            url : "<?= site_url('process/getListProcessCampagne/') ?>" + campagne ,
            method : "GET",
            dataType : "json",
            success : function(response){
              if(!response.error){
                let list = response.data;
                let tbodyData = '';
                list.forEach( (process) => {
                  tbodyData += '<tr>'+
                                  '<td><input type="checkbox" name="primeprofilprocess_process[]" class="primeprofilprocess_process" value="'+process.affectationmcp_id+'"></td>'+
                                  '<td>'+process.affectationmcp_id+'</td>'+
                                  '<td>'+process.mission_libelle+'</td>'+
                                  '<td>'+process.process_libelle+'</td>'+
                                '</tr>';
                });
                $('#table-process-profil tbody').html(tbodyData);

              }else{
                //TODO
                
              }

            }
        })

      }

      function getEditListProcessCampagne(campagne, profil){
        
        $.ajax({
            url : "<?= site_url('process/getListProcessCampagne/') ?>" + campagne + "/" + profil,
            method : "GET",
            dataType : "json",
            success : function(response){
              if(!response.error){
                let list = response.data;
                let affectedProcess = response.affected;
                let tbodyData = '';
                list.forEach( (process) => {
                  tbodyData += '<tr>'+
                                  '<td><input type="checkbox" id="edit_primeprofilprocess_process_'+process.affectationmcp_id+'" name="edit_primeprofilprocess_process[]" class="edit_primeprofilprocess_process" value="'+process.affectationmcp_id+'"></td>'+
                                  '<td>'+process.affectationmcp_id+'</td>'+
                                  '<td>'+process.mission_libelle+'</td>'+
                                  '<td>'+process.process_libelle+'</td>'+
                                '</tr>';
                });
                $('#table-process-profil-edit tbody').html(tbodyData);

                affectedProcess.forEach( (process) => {
                  $('#edit_primeprofilprocess_process_' + process.primeprofilprocess_process).prop('checked', true);
                });

              }else{
                //TODO
                
              }

            }
        })

      }

    })
</script>

