<!-- Page Content  -->
<div id="content" class="p-4 p-md-5">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">

            <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div class="col-md-6 text-left">
                        <div class="row">
                            <div class ="col-md-6">
                                <i class="fa fa-user-circle-o mr-2"></i><?=$top['username']?> : <span id="ctrl-statut" class="badge pill fs-6" style="<?= ($top['role'] !== ROLE_ADMIN && $top['role'] !== ROLE_ADMINRH && $top['role'] !== ROLE_DIRECTION && $top['role'] !== ROLE_CADRE2 && $top['role'] !== ROLE_COSTRAT && $top['role'] !== ROLE_CLIENT && $top['role'] !== ROLE_REPORTING) ? 'display:block' : 'display:none'?>"></span><br>
                            </div>

                            <div class="card col-md-8" id="card-activity" style="cursor: pointer;">
                                <div class="card-body" id="cardActivity">
                                    <h5 class="">Actuellement sur</h5>
                                <div style="inline"><span><b>Campagne : </b></span><span id="campagneActivity"></span><br><span><b>Mission : </b></span><span id="missionActivity"></span><br><span><b>Process : </b></span><span id="processActivity"></span></div>
                            </div>
                            
                        </div>
                        </div>
                </div>
               
                
               
                <div id="ctrl-buttons" class="col-md-6   text-right" style="<?php echo ($top['role'] !== ROLE_ADMIN && $top['role'] !== ROLE_ADMINRH && $top['role'] !== ROLE_DIRECTION && $top['role'] !== ROLE_CADRE2 && $top['role'] !== ROLE_COSTRAT && $top['role'] !== ROLE_CLIENT) ? 'display:block' : 'display: none'; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" id="btn-ctrl-debut" class="btn btn-sm btn-primary"><i class="fa fa-play pr-2"></i>Débuter</button>
                            <button type="button" id="btn-ctrl-pause" class="btn btn-sm btn-warning"><i class="fa fa-pause-circle pr-2"></i>Pause</button>
                            <button type="button" id="btn-ctrl-reprise" class="btn btn-sm btn-success"><i class="fa fa-play-circle pr-2"></i>Reprendre</button>
                            <button type="button" id="btn-ctrl-fin" class="btn btn-sm btn-danger"><i class="fa fa-stop-circle pr-2"></i>Fin de shift</button>
                            <div id="ctrl-buttons-transport"  style="<?php echo ($top['role'] !== ROLE_ADMIN && $top['role'] !== ROLE_ADMINRH && $top['role'] !== ROLE_DIRECTION && $top['role'] !== ROLE_COSTRAT && $top['role'] !== ROLE_CLIENT) ? 'display: inline-block;' : 'display: none;'; ?>">
                                <button type="button" id="btn-ctrl-transport" class="btn btn-sm btn-success" ><i class="fa fa-car pr-2"></i>Réserver votre transport</button>
                            </div>
                        </div>

                        <div class="col-md-12 mt-4">
                            <button type="button" id="btn-activity-debut" class="btn btn-sm btn-info"><i class="fa fa-play pr-2"></i>Demarrer une tâche</button>
                            <button type="button" id="btn-activity-pause" class="btn btn-sm btn-warning" data-user="<?= $top['userid'] ?>"><i class="fa fa-pause-circle pr-2"></i>Mettre en pause</button>
                            <button type="button" id="btn-activity-reprise" class="btn btn-sm btn-success"><i class="fa fa-play-circle pr-2"></i>Reprendre</button>
                            <button type="button" id="btn-activity-fin" class="btn btn-sm btn-secondary" data-user="<?= $top['userid'] ?>"><i class="fa fa-stop-circle pr-2"></i>Terminer la tâche</button>
                        </div>
                        <input type="hidden" name="currentActivityId" id="currentActivityId" />
                    </div>
                </div>

                

            </div>
        </div>
    </nav>

    <!-- MODAL PRISE DE PAUSE -->
    <div class="modal fade" id="modaltakepause" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modaltakepause" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prendre une pause</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('control/takePause') ?>" method="post" name="formTakingPause" id="formTakingPause">

                    <div class="mb-3 row">
                        <label for="pause_libelle" class="col-sm-4 col-form-label">Je vais faire quoi ?</label>
                        <div class="col-sm-8">
                            <select name="pause_libelle" id="pause_libelle" class="form-control" required>
                                <option value=""></option>
                                <?php if(isset($top['listTypePause'])) : ?>
                                    <?php foreach($top['listTypePause'] as $typePause) : ?>
                                        <option value="<?= $typePause->typepause_id ?>"><?= $typePause->typepause_libelle ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirmTakingPause" class="btn btn-primary">Confirmer ma pause</button>
            </div>
            </div>
        </div>
        </div>
    <!-- END MODAL PRISE DE PAUSE -->

    <!-- MODAL DE VALIDATION DE FIN DE SHIFT -->
    <div class="modal fade" id="modalconfirmfinshift" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalconfirmfinshift" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de fin de shift</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Etes-vous sur de bien vouloir terminer votre shift ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirmFinShift" class="btn btn-primary">Terminer le shift</button>
            </div>
            </div>
        </div>
        </div>
    <!-- END MODAL DE VALIDATION DE FIN DE SHIFT -->



    <!-- MODAL DEBUTER ACTIVITE -->
    <div class="modal fade" id="modaldebutactivity" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modaldebutactivity" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Débuter une activité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            <form id="formdebutactivity" name="formdebutactivity">
                    <div class="mb-3 row">
                        <label for="etpcampagne_libelle" class="col-sm-4 col-form-label">Campagne </label>
                        <div class="col-sm-8">
                            <select name="campagne" id="etpcampagne_libelle" class="form-control" required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="etpprofil_libelle" class="col-sm-4 col-form-label">Profil </label>
                        <div class="col-sm-8">
                            <select name="profil" id="etpprofil_libelle" class="form-control" required>
                                <option value=""></option>
                                
                            </select>
                        </div>
                    </div>
    
                    <div class="mb-3 row">
                        <label for="etpmission_libelle" class="col-sm-4 col-form-label">Mission</label>
                        <div class="col-sm-8">
                            <select name="mission" id="etpmission_libelle" class="form-control" required>
                                <option value=""></option>
                                
                            </select>
                        </div>
                    </div>
    
                    <div class="mb-3 row">
                        <label for="etpprocess_libelle" class="col-sm-4 col-form-label">Process</label>
                        <div class="col-sm-8">
                            <select name="process" id="etpprocess_libelle" class="form-control" required>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
    
                    <div class="mb-3 row">
                        <label for="prod_formation" class="col-sm-4 col-form-label">Prod/Formation</label>
                        <div class="col-sm-4">
                            <input type="radio" name="etatressource" value="<?= ETATRESSOURCE_PROD_DEFAULT ?>" class="form-check-input" checked> 
                            PROD
                        </div>
                        <div class="col-sm-4">
                            <input type="radio" name="etatressource" value="<?= ETATRESSOURCE_FORMATION_DEFAULT ?>" class="form-check-input" > 
                            FORMATION 
                        </div> 
                    </div>
                    <input type="hidden" name="action" id="etpaction" />
                    <input type="hidden" name="agent" id="etpagent" value="<?= $top['userid'] ?>" />

                </form>    
               

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="debutActivity" class="btn btn-primary">Démarrer une tâche</button>
            </div>
            </div>
        </div>
        </div>

    <!-- END MODAL DEBUTER ACTIVITE -->

    <!-- MODAL REPRISE ACTIVITE -->
    <div class="modal fade" id="modalRepriseActivity" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalRepriseActivity" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reprendre une activité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <form id="formrepriseactivity" name="formrepriseactivity">

                        <div class="mb-3 row">
                            <label for="etp_mcp" class="col-sm-4 col-form-label">Activité </label>
                            <div class="col-sm-8">
                                <select name="id" id="list_activite_reprise" class="form-control" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="action" id="etpreprise_action" />
                        <input type="hidden" name="agent" id="etpreprise_agent" value="<?= $top['userid'] ?>" />
                    </form>    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" id="repriseActivity" class="btn btn-primary">Reprendre l'activité</button>
                </div>
            </div>
        </div>
    </div>

    <!-- END MODAL REPRISE ACTIVITE -->

    <!-- MODAL DE VALIDATION DE FIN ACTIVITE -->
     <div class="modal fade" id="modalconfirmfinactivity" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalconfirmfinactivity" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de fin de l'activité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Etes-vous sur de bien vouloir terminer cette activité ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirmFinActivity" class="btn btn-primary">Terminer l'activité</button>
            </div>
            </div>
        </div>
        </div>
    <!-- END MODAL DE VALIDATION DE FIN ACTIVITE -->




    <!-- MODAL RESERVATION TRANSPORT -->

     <div class="modal " id="modaltransport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modaltransport" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Votre réservation </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="input_suggestion" class="labelcartier">Chercher <sup class="exposant">➀</sup>:</label>
                                </div>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" id="input_suggestion" placeholder="" required>
                                </div>
                            </div>
                        </div>
                        <div id="suggestions_quartier" class="col-sm-6">
                            <div class="row">
                                    <div class="col-sm-4">
                                        <label for="suggestions_select">Quartier <sup class="exposant">➁</sup>:</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select  class="form-control" id="suggestions_select" required></select>
                                    </div>
                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="heure" class ="labelheure">Heure <sup class="exposant">➂</sup>:</label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="heure" id="heure" class="form-control" required>
                                        <option value=""></option>
                                        <?php if(isset($top['heuretransport'])) : ?>
                                            <?php foreach($top['heuretransport'] as $heure) : ?>
                                                <option value="<?= $heure->heuretransport_id ?>"><?= $heure->heuretransport_heure ?></option>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="axe" class ="labelAxe">Axe <sup class="exposant">➃</sup>:</label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="axe" id="axe" class="form-control" required>
                                        <option value=""></option>
                                    </select><br>
                            </div>
                        </div>

                    </div>
                       
                </div>             
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary fermer" data-bs-dismiss="modal">Fermer</button>
                    <button class="btn btn-success addtransport">Enregister</button>

                </div>
            </div>
        </div>
    </div>

    <!--END  MODAL RESERVATION TRANSPORT -->

    <!-- MODAL ANNULATION TRANSPORT -->


    <div class="modal fade" id="modalannulationtransport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalannulationtransport" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Annulation transport</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                    <div class="mb-12 row">
                      <h6 class="">Etes-vous certain de vouloir annuler votre transport?</h6>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" id="ConfirmAnnulation" class="btn btn-primary">Valider</button>
            </div>
            </div>
        </div>
    </div>


                                        
    <div class="modal fade" id="testadd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalannulationtransport" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assignation Heure et Axe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <form method="post" id="" action="<?= site_url('transport/addaxe') ?>">
                            <div class="form-group" style="width: 70%">
                                <label for="heuretransport">Heure</label><br />
                                <select name="heure" id="heure" class="form-control" required>
                                        <option value=""></option>
                                        <?php if(isset($top['heuretransport'])) : ?>
                                            <?php foreach($top['heuretransport'] as $heure) : ?>
                                                <option value="<?= $heure->heuretransport_id ?>"><?= $heure->heuretransport_heure ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                </select>
                                
                            </div>

                            <div class="form-group" style="width: 70%">
                            <label for="axe">Axe</label><br />

                                <select name="axe" id="axe" class="form-control" required>
                                            <option value=""></option>
                                            <?php if(isset($top['listAxe'])) : ?>
                                                <?php foreach($top['listAxe'] as $axe) : ?>
                                                    <option value="<?= $axe->axe_id ?>"><?= $axe->axe_libelle ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                    </select>
                            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" id="" class="btn btn-primary">Enregister</button>
                </form>

            </div>
            </div>
        </div>
    </div>

   <!-- END MODAL  ANNULATION TRANSPORT -->


   <!-- BEGIN MODAL ADD DETAIL ETP -->
   <div class="modal fade" id="detailtask" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Details d'une tâche
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <button class="btn btn-primary" id="debut_onedetailtask">Débuter</button><br><br>
                    <div class="form-group row">
                            <input type="hidden" id="id_mcpdetailtask">
                            <input type="hidden" id="actitymcp_quantite">

                            <div class="row">
                                <div class="col-md-2">
                                    <label for="detail_task">Commentaire:</label>
                                </div>
                                <div class="col-md-8">
                                    <input rows="4" cols="50" class="form-control" id="detail_task" disabled="disabled"></input>
                                </div>
                            </div><br><br>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="detail_task">Détail 1:</label>
                                </div>
                                <div class="col-md-8">
                                    <input rows="4" cols="50" class="form-control" id="detail_task1" disabled="disabled"></input>
                                </div>
                            </div><br><br>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="detail_task">Détail 2:</label>
                                </div>
                                <div class="col-md-8">
                                    <input rows="4" cols="50" class="form-control" id="detail_task2" disabled="disabled"></input>
                                </div>
                            </div><br><br>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="detail_task">Détail 3:</label>
                                </div>
                                <div class="col-md-8">
                                    <input rows="4" cols="50" class="form-control" id="detail_task3" disabled="disabled"></input>
                                </div>
                            </div><br><br>
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="detail_task">Détail 4:</label>
                                </div>
                                <div class="col-md-8">
                                    <input rows="4" cols="50" class="form-control" id="detail_task4" disabled="disabled"></input>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" value="" id="isAsMontant">
                                    <label class="form-check-label" for="isAsMontant">
                                        Montant
                                    </label>
                                </div>
                            </div>

                        
                    </div> 
                </div>
                
                <div class="modal-footer">
                    <button type="" id ="fin_onedetailtask"class="btn btn-success">Terminer</button>

                </div>

            
            </div>
        </div>
    </div>

    <!-- END MODAL ADD DETAIL ETP -->

    <script type="text/javascript">

        /*==================================================================
        [ Control button ]*/
        $(document).ready(function()
        {


            var modalDebutActivity = new bootstrap.Modal(document.getElementById('modaldebutactivity'));
            var modalRepriseActivity = new bootstrap.Modal(document.getElementById('modalRepriseActivity'));
            var modalPause = new bootstrap.Modal(document.getElementById('modaltakepause'));
            var modalConfirmFinShift = new bootstrap.Modal(document.getElementById('modalconfirmfinshift'));
            var modalConfirmFinActivity = new bootstrap.Modal(document.getElementById('modalconfirmfinactivity'));
            var detailtaskActiviter = new bootstrap.Modal(document.getElementById('detailtask'));

            refreshControlButtons();
            var modaltransport = new bootstrap.Modal(document.getElementById("modaltransport"));
            var modalannulationtransport = new bootstrap.Modal(document.getElementById("modalannulationtransport"));


            $("#cardActivity").hide();
            
            function refreshControlButtons(){
                //Disable all activity buttons
                $("#btn-activity-debut").attr("disabled", "disabled");
                $("#btn-activity-pause").attr("disabled", "disabled");
                $("#btn-activity-reprise").attr("disabled", "disabled");
                $("#btn-activity-fin").attr("disabled", "disabled");

                $("#ctrl-buttons button").attr("disabled", "disabled");

                $.ajax({
                    'url' : '<?= site_url("control/updateControlButtons") ?>',
                    'type' : 'post', 
                    'dataType' : 'json',
                    'success' : function(resp){
                        //console.log(resp);
                        //On active tt les boutons par defaut
                        $("#ctrl-buttons button").removeAttr("disabled");
                        
                        if(resp && resp.buttons){
                            //Pour chaque bouton à false, on active l'attribut disabled
                            if(!resp.buttons.btnDebut) $("#btn-ctrl-debut").attr("disabled", "disabled");
                            if(!resp.buttons.btnPause) $("#btn-ctrl-pause").attr("disabled", "disabled");
                            if(!resp.buttons.btnReprise) $("#btn-ctrl-reprise").attr("disabled", "disabled");
                            if(!resp.buttons.btnFin) $("#btn-ctrl-fin").attr("disabled", "disabled");        
                        }

                        if(resp && resp.state) {
                            var $el = $('#ctrl-statut');
                            var classList = $el.attr('class').split(' ');
                            $.each(classList, function(id, item) {
                                if (item.indexOf('bg-') == 0) $el.removeClass(item);
                            });

                            $("#ctrl-statut").html(resp.state);
                            $("#ctrl-statut").addClass("bg-"+resp.bgButton);
                        }

                        if(resp && resp.buttonsActivity)
                        {
                            //Pour chaque bouton à false, on active l'attribut disabled
                            if(!resp.buttonsActivity.btnDebut) $("#btn-activity-debut").attr("disabled", "disabled");
                            if(!resp.buttonsActivity.btnPause) $("#btn-activity-pause").attr("disabled", "disabled");
                            if(!resp.buttonsActivity.btnReprise) $("#btn-activity-reprise").attr("disabled", "disabled");
                            if(!resp.buttonsActivity.btnFin) $("#btn-activity-fin").attr("disabled", "disabled");  
                            
                            if(resp && resp.currentActivityId){
                                $("#cardActivity").show();
                                $("#currentActivityId").val(resp.currentActivityId);
                                $("#campagneActivity").text((resp.userActivityCampagne).toLowerCase());
                                $("#missionActivity").text((resp.userActivityMission).toLowerCase());
                                $("#processActivity").text((resp.userActivityProcess).toLowerCase());
                                $("#campagneActivity").text((resp.userActivityCampagne).toLowerCase());
                                $("#actitymcp_quantite").val(resp.userQuantityEtp);
                                console.log(resp.userQuantityEtp);
                            }
                        }
                        
                        updateProgressBar();
                        updatePauseProgressTable();
                    }
                })
            }

            /**
             * Click sur le bouton début demarre la journée de l'agent
             * Renseigner la valeur de shift_begin
             */
            $(document).on('click', '#btn-ctrl-debut', function(){
                $.ajax({
                    url : "<?= site_url('control/beginMyProd') ?>",
                    datatype : 'json',
                    data : '',
                    type : 'post',
                    success : function(resp){
                        if(!resp.err){
                            //On propose le début d'une activité
                            //$('#btn-activity-debut').trigger('click');
                            refreshControlButtons();
                        }
                    }
                })
            });

            /**
             * Click sur le bouton Pause pour prendre une pause
             * Ouverture d'une modal pour choisir le type de pause à prendre
             */
            $(document).on('click', '#btn-ctrl-pause', function(){
                $('#pause_libelle').val("");
                $(this).prop('disabled', false);
                modalPause.show();

            });

            /**
             * Quand on clique sur confirmer ma pause au niveau du modal
             */
            $(document).on('click', '#confirmTakingPause', function(){
                let btn = $(this);
                btn.prop('disabled', true);
                let myPause = $('#pause_libelle').val();
                if(myPause == "") alert('La pause à prendre ne peut être vide');
                else {
                    var formData = $("#formTakingPause").serialize();
                    $.ajax({
                        url : '<?= site_url('control/takePause') ?>',
                        datatype : 'json',
                        data : formData,
                        type : 'post',
                        success : function(resp){
                            if(!resp.err){
                                //btn.prop('disabled', false);
                                modalPause.hide();
                                //On va arrêter aussi l'activité en cours
                                $('#btn-activity-pause').trigger('click');
                                refreshControlButtons();
                            }
                        }
                    });
                }
            });

            /**
             * Click sur le bouton reprendre, après une pause
             */
            $(document).on('click', '#btn-ctrl-reprise', function(){
                $.ajax({
                    url : '<?= site_url('control/endPause') ?>',
                    datatype : 'json',
                    data : '',
                    type : 'post',
                    success : function(resp){
                        if(!resp.err){
                            //On propose la reprise des activités
                            $('#btn-activity-reprise').trigger('click');
                            refreshControlButtons();
                        }
                    }
                })
            });

            /**
             * Click sur le bouton terminer shift, pour cloturer la journée 
             * Ouverture d'un modal de validation
             */
            $(document).on('click', '#btn-ctrl-fin', function(){
                modalConfirmFinShift.show();
            });

            /**
             * CLick sur le bouton de confirmation "Terminer le shift" 
             */
            $(document).on('click', '#confirmFinShift', function(){

                $.ajax({
                    url : '<?= site_url('control/endShift') ?>',
                    dataType : 'json',
                    data : '',
                    success : function(resp){
                        if(!resp.err){
                            refreshControlButtons();
                            modalConfirmFinShift.hide();
                            //On termine aussi l'activité en cours
                            $('#btn-activity-fin').trigger('click');
                        }
                    } 
                })
            });


            
            function updateProgressBar(){
                if(typeof loadProgressBar === 'function'){
                    console.log('loadProgressBar called');
                    loadProgressBar();
                }
            }

            function updatePauseProgressTable(){
                if(typeof pauseProgressTable === 'object'){
                    pauseProgressTable.ajax.reload();
                    console.log('Pause table progress reloaded ...');
                }
            }

            /**
             * Click sur le bouton débuter shift, pour débuter la journée 
             * Ouverture d'un modal pour le ETP
             * Chosir l'activité qu'on va travailler 
             * On choit la mission et le process qu'on va travailler 
             * click sur démarrer pour l'enregistrement de données et démarrer le shift
             */
            $(document).on('click', '#btn-activity-debut', function()
            {
                $('#etpcampagne_libelle').html('');
                $('#etpprofil_libelle').html('');
                $('#etpmission_libelle').html('');
                $('#etpprocess_libelle').html('');
                //On récupère la liste des campagnes via ajax
                $.ajax({
                    url : '<?= site_url('apietp/campagne/') . $top['userid'] ?>',
                    method : 'get',
                    dataType : 'json',
                    success : function(resp){
                        let options = '<option value=""></option>';
                        resp.data.forEach(function(cmp){
                            options += '<option value="'+cmp.campagne_id+'">'+cmp.campagne_libelle+'</option>';
                        })
                        $('#etpcampagne_libelle').html(options);
                        $('#etpaction').val('debut');
                        modalDebutActivity.show();
                    } 


                })
                
            });

            $(document).on("dblclick","#cardActivity", function(){
                checkOngoingDetailTask();
            });

            /**
             * Quand on selectionne une campagne, la liste des missions se charge
             */
            $(document).on('change', '#etpcampagne_libelle', function()
            {
                let campagne = $(this).val();
                $('#etpprofil_libelle').html('');
                $.ajax({
                    url : '<?= site_url('apiprimecritere/primecritereprofilbycampagne/') ?>' + campagne,
                    method : 'get',
                    dataType : 'json',
                    success : function(resp)
                    {
                        let optioncritereprofil = '<option value=""></option>';
                        resp.data.forEach(function(profil){
                            optioncritereprofil += '<option value="'+profil.primeprofil_id+'">'+profil.primeprofil_libelle+'</option>';
                        })
                        $('#etpprofil_libelle').html(optioncritereprofil);
                    }
                })
            })

            $(document).on('change', '#etpprofil_libelle', function(){
                $('#etpmission_libelle').html('');
                let campagne = $('#etpcampagne_libelle option:selected').val();
                let profil = $(this).val();
                //On récupère la liste des missions via ajax
                $.ajax({
                    url : '<?= site_url('apietp/missionprofil/') ?>' + campagne + '/' + profil ,
                    method : 'get',
                    dataType : 'json',
                    success : function(resp){
                        let options = '<option value=""></option>';
                        resp.data.forEach(function(mission){
                            options += '<option value="'+mission.mission_id+'">'+mission.mission_libelle+'</option>';
                        })
                        $('#etpmission_libelle').html(options);
                    } 
                })
            });

            /**
             * Quand on selectionne une mission, la liste des process se charge
             */
            $(document).on('change', '#etpmission_libelle', function(){
                $('#etpprocess_libelle').html('');
                let mission = $(this).val();
                let campagne = $('#etpcampagne_libelle option:selected').val();
                //On récupère la liste des missions via ajax
                $.ajax({
                    url : '<?= site_url('apietp/process/') ?>' + campagne + '/' + mission,
                    method : 'get',
                    dataType : 'json',
                    success : function(resp){
                        let options = '<option value=""></option>';
                        resp.data.forEach(function(process){
                            options += '<option value="'+process.process_id+'">'+process.process_libelle+'</option>';
                        })
                        $('#etpprocess_libelle').html(options);
                    } 
                })

                $('#')
            })

            /**
             * Enregistrement debut activité
             */
            $(document).on('click', '#debutActivity', function(){
                
                let formdata = $('#formdebutactivity').serializeArray();
                $.ajax({
                    url : '<?= site_url('apietp/activity/') ?>',
                    method : 'post',
                    dataType : 'json',
                    data : formdata,
                    success : function(resp){
                        refreshControlButtons();
                        modalDebutActivity.hide(); 
                        detailtaskActiviter.show();  
                        $("#fin_onedetailtask").hide();          

                        // location.reload();

                    } 
                })
            })

            $(document).on('click', '#btn-activity-pause', function(){

                let agent = $(this).data('user');
                let activity = $('#currentActivityId').val();
                let data = {
                    'action' : 'pause',
                    'id' : activity,
                    'agent' : agent 
                };

                $.ajax({
                    url : '<?= site_url('apietp/activity/') ?>',
                    method : 'put',
                    dataType : 'json',
                    data : data,
                    success : function(resp){
                        //alert(resp.message);
                        refreshControlButtons();
                        //location.reload();
                    } 
                })
            });

            $(document).on('click', '#btn-activity-reprise', function(){

                $('#list_activite_reprise').html('');

                $.ajax({
                    url : '<?= site_url('apietp/activity/') . $top['userid'] . '/' . MCP_STATUS_ENPAUSE ?>',
                    method : 'get',
                    dataType : 'json',
                    success : function(resp){
                        let options = '<option value=""></option>';
                        console.log(resp.data);
                        resp.data.forEach(function(mcp){
                            options += '<option value="'+mcp.mcp_id+'">'+mcp.mcp_libelle+'</option>';
                        })
                        $('#list_activite_reprise').html(options);
                        $('#etpreprise_action').val('reprise');
                        modalRepriseActivity.show();
                    } 
                })
            });

            $(document).on('click', '#repriseActivity', function(){

                let formdata = $('#formrepriseactivity').serializeArray();
                $.ajax({
                    url : '<?= site_url('apietp/activity/') ?>',
                    method : 'put',
                    dataType : 'json',
                    data : formdata,
                    success : function(resp){
                        //alert(resp.message);
                        refreshControlButtons();
                        modalRepriseActivity.hide();             
                    } 
                })
            });

            $(document).on('click', '#btn-activity-reprise', function(){

                $('#list_activite_reprise').html('');

                $.ajax({
                    url : '<?= site_url('apietp/activity/') . $top['userid'] . '/' . MCP_STATUS_ENPAUSE ?>',
                    method : 'get',
                    dataType : 'json',
                    success : function(resp){
                        let options = '<option value=""></option>';
                        resp.data.forEach(function(mcp){
                            options += '<option value="'+mcp.mcp_id+'">'+mcp.mcp_libelle+'</option>';
                        })
                        $('#list_activite_reprise').html(options);
                        $('#etpreprise_action').val('reprise');
                        modalRepriseActivity.show();
                        //location.reload();
                    } 
                })
            });

            $(document).on('click', '#btn-activity-fin', function(){
                modalConfirmFinActivity.show();
            });

            $(document).on('click', '#confirmFinActivity', function(){
                let agent = $('#btn-activity-fin').data('user');
                let activity = $('#currentActivityId').val();
                let data = {
                    'action' : 'fin',
                    'id' : activity,
                    'agent' : agent 
                };
                $.ajax({
                    url : '<?= site_url('apietp/activity/') ?>',
                    method : 'put',
                    dataType : 'json',
                    data : data,
                    success : function(resp){
                        modalConfirmFinActivity.hide();
                        refreshControlButtons();
                        location.reload();            
                    } 
                })
            });

            // Nouvelle fonctionnalité
            $(document).on("click", "#debut_onedetailtask", function(){
                var id_mcp = $("#currentActivityId").val();
                var quantity_mcp = $("#actitymcp_quantite").val();
                var debutBtn = this;
                $("#detail_task").removeAttr('disabled', true);
                $("#detail_task1").removeAttr('disabled', true);
                $("#detail_task2").removeAttr('disabled', true);
                $("#detail_task3").removeAttr('disabled', true);
                $("#detail_task4").removeAttr('disabled', true);
                $("#fin_onedetailtask").show(); 
                //refreshControlButtons();
                $.ajax({
                    url: '<?= site_url('Etp/addDetailsTask') ?>',
                    method:"POST",
                    data : {id_mcp:id_mcp,mcp_quantity:quantity_mcp},
                    dataType: "json",
                    success: function(resp){
                        $(debutBtn).prop('disabled', true);
                        var lastId = resp.data;
                        $('#fin_onedetailtask').attr('data-detailsid', lastId);                        
                        $('#id_mcpdetailtask').val(lastId);
                        refreshControlButtons();
                    }
                })
            })

            $(document).on("click", "#fin_onedetailtask", function(){

                var idDataidmcpdetailtask = $(this).data('detailsid');
                var inputmcdetailtask = $('#id_mcpdetailtask').val();
                if(inputmcdetailtask == ''){
                    var Iddetail = idDataidmcpdetailtask;
                }
                else{
                    var Iddetail = inputmcdetailtask;
                }
                var detailcommentaire = $("#detail_task").val();
                var detailcommentaire1 = $("#detail_task1").val();
                var detailcommentaire2 = $("#detail_task2").val();
                var detailcommentaire3 = $("#detail_task3").val();
                var detailcommentaire4 = $("#detail_task4").val();

                if($('#isAsMontant').is(':checked')){
                    detailcommentaire4 = '%montant%' + detailcommentaire4;
                }


                if (detailcommentaire.trim() === "") {
                    alert("Le champ commentaire est requis");
                    return;
                    
                }
                else 
                {
                    $.ajax(
                    {
                        url: '<?= site_url('Etp/finDetailTask') ?>',
                        method:"POST",
                        data : {   
                                    iddetail:Iddetail,
                                    detailcommentaire:detailcommentaire,
                                    detailcommentaire1:detailcommentaire1,
                                    detailcommentaire2:detailcommentaire2,
                                    detailcommentaire3:detailcommentaire3,
                                    detailcommentaire4:detailcommentaire4,


                                },
                        success: function()
                        {
                            $("#fin_onedetailtask").hide();
                            $('#debut_onedetailtask').prop('disabled', false);
                            $("#detail_task").val('');
                            $("#detail_task1").val('');
                            $("#detail_task2").val('');
                            $("#detail_task3").val('');
                            $("#detail_task4").val('');

                            $("#detail_task").prop('disabled', true);
                            $("#detail_task1").prop('disabled', true);
                            $("#detail_task2").prop('disabled', true);
                            $("#detail_task3").prop('disabled', true);
                            $("#detail_task4").prop('disabled', true);
                            refreshControlButtons();

                    
                        }
                    })

                }


               
                
            })



            // Gestion de Transport (axes et quartiers , réservation de transport pour les salariés )

            // var Liste_campagne = '<?php $listcampagne; ?>';


            // $(document).on("click", "#btn-ctrl-transport", function () {
            //     modaltransport.show();
            // });


            // $("#axe").append('<option value=""></option>');
            // var options = <?php echo json_encode($top['listAxe']); ?>;
            // var optionsHtml = options.map(function(axe) {
            //     return '<option value="' + axe.axe_id + '">' + axe.axe_libelle + '</option>';
            // }).join('');

            // $("#axe").append(optionsHtml);
            // $(".addtransport").prop("disabled", true);

            // function checkFields() 
            // {
            //     var input_suggestion = $("#input_suggestion").val();
            //     var suggestions_select = $("#suggestions_select").val();
            //     var heure = $("#heure").val();
            //     var axe = $("#axe").val();

            //     if ( input_suggestion !== "" && suggestions_select !== "" && heure !== "" && axe !== "") {
            //         $(".addtransport").prop("disabled", false);
            //     } else {
            //         $(".addtransport").prop("disabled", true);
            //     }
            // }

            // setInterval(checkFields, 100);
            // $(".addtransport").prop("disabled", !checkFields());
            // const contenuheure = $('#heure').html();
            // const contenuaxe = $('#axe').html();
            // const quartier = $('#input_suggestion').html();
            // setInterval(function(){
            //     var date = new Date();
            //     var currentHour = date.getHours(); 

            //     if (currentHour >= 20) {
            //         $('#btn-ctrl-transport').hide(); 
            //     } else {
            //         $('#btn-ctrl-transport').show();
            //     }
            // }, 1000);
            // var suggestions = [];
            // var listeaxe = [];
            // $.ajax({
            //     url: '<?= site_url('transport/getQuartierTransport') ?>',
            //     method: "POST",
            //     dataType: "json",
            //     success: function (response) {
            //         suggestions = response.data;
            //     },
            //     error: function (xhr, status, error) {
            //         console.error("Error:", error);
            //     },
            // });
            // $.ajax({
            //     url: '<?= site_url('transport/getaxebyquartier') ?>',
            //     method: "POST",
            //     dataType: "json",
            //     success: function (response) {
            //         listeaxe = response.data;
            //         console.log(listeaxe);
            //     },
            //     error: function (xhr, status, error) {
            //         console.error("Error:", error);
            //     },
            // });

            // function isJsonEmpty(jsonObj) {
            // return $.isEmptyObject(jsonObj);
            // }

            // function testload(){
            //     $.ajax({
            //     url : "<?= site_url('transport/getTransportuser') ?>",
            //     type: "post",
            //     data: "",
            //     dataType: "json",
            //     success: function (resp) {
            //         if (isJsonEmpty(resp.data)) {
            //         } else {
            //         var heuretransport_heure = resp.data[0].heuretransport_heure;
            //         var heuretransport_axe = resp.data[0].axe_libelle;
            //         var quartier_user = resp.data[0].transportuser_quartier;
            //         var usertransportstatus = resp.data[0].transportuser_status;
            //         var usertransport_id = resp.data[0].transportuser_id;

                
            //         $("#axe").replaceWith(
            //             '<input type="texte" class="form-control"  id="axe" value="' +
            //             heuretransport_axe +
            //             '" readonly>'
            //         );

            //         $("#heure").replaceWith(
            //             '<input type="text"  class="form-control" id="heure" value="' +
            //             heuretransport_heure +
            //             '" readonly>'
            //         );

            //         $(".labelcartier").replaceWith(
            //             '<label for="suggestions_select" class="labelcartier">Quartier:</label>'
            //         );
            //         $(".labelAxe").replaceWith(
            //             '<label for="axe" class ="labelAxe">Axe:</label>'
            //         );
            //         $(".labelheure").replaceWith(
            //             '<label for="heure" class ="labelheure">Heure:</label>'
            //         );



            //         $("#input_suggestion").replaceWith(
            //             '<input type="text"  class="form-control" id="input_suggestion" value="' +
            //             quartier_user +
            //             '" readonly>'
            //         );

            //         $(".addtransport").replaceWith(
            //             '<button type="button" id ="modiftransportuser" class="btn btn-primary" data-id = "'+ usertransport_id +'">Modifier</button>'
            //             );

            //         $(".fermer").replaceWith(
            //             '<button type="button" id ="annulertransport" class="btn btn-danger" data-id = "'+ usertransport_id +'">Annuler mon transport</button>'
            //         );

            //         $("#suggestions_quartier").hide();
            //         if (usertransportstatus == "0") {
            //             $("#btn-ctrl-transport").replaceWith(
            //             '<button type="button" id="btn-ctrl-transport" class="btn btn-sm btn-warning" ><i class="fa fa-car pr-2" ></i>Votre transport</button>'
            //             );
            //         } else {
            //         $("#axe").replaceWith(
            //             '<input type="texte" class="form-control"  id="axe" value="' +
            //             heuretransport_axe +
            //             '" readonly style="color:red">'
            //         );


            //         $("#modiftransportuser").replaceWith(
            //             '<button type="button" id ="modiftransportuser" class="btn btn-primary" disabled>Modifier</button>'
            //             );
            //             $("#btn-ctrl-transport").replaceWith(
            //             '<button type="button" id="btn-ctrl-transport" class="btn btn-sm btn-danger position-relative">' +
            //             '<i class="fa fa-car pr-2"></i>Modification apportée par RT ' +
            //             '<span class="notification-badge badge badge-pill badge-secondary position-absolute top-0 start-100 translate-middle">' +
            //             '<i class="fa fa-bell"></i></span>' +
            //             '</button>');

                        
            //             // $("#btn-ctrl-transport").replaceWith(
            //             // '<button type="button" id="btn-ctrl-transport" class="btn btn-sm btn-danger" ><i class="fa fa-car pr-2" ></i>Modification apportée par RT</button>'
            //             // );
            //         }
            //         }
            //     },
            //     });

            // }
            // testload();

            // $(document).on("click", '#annulertransport', function()
            // {
            //     var id_transportuser = $(this).data('id');
            //     $("#ConfirmAnnulation").replaceWith(
            //                 '<button type="button" id="ConfrirmAnnulation" class="btn btn-primary" data-id = "'+ id_transportuser +'">Valider</button>'
            //                 );
            //     modaltransport.hide();
            //     modalannulationtransport.show();
            // });

            // $(document).on("click",'#ConfrirmAnnulation', function(){
            //     var id_transportuser = $(this).data('id');
            //     $("#btn-ctrl-transport").replaceWith(
            //                 '<button type="button" id="btn-ctrl-transport" class="btn btn-sm btn-success" ><i class="fa fa-car pr-2" ></i>Réserver votre transport</button>'
            //                 );
            //     modalannulationtransport.hide();
            //     $.ajax({
            //         url: '<?= site_url("transport/annulationtransport") ?>',
            //         type: "POST",
            //         dataType: "json",
            //         data: { id_transportuser:id_transportuser }, 
            //         success: function (resp) {
            //         if (!resp.err) {
            //         }
                    

                    
            //         },
            //         error: function (xhr, status, error) {
            //         console.error(xhr.responseText);
            //         },
            //     });
            // });

            // $(document).on("click", '#modiftransportuser', function()
            // {
            //     $("#axe").replaceWith('<select  class="form-control" id="axe"></select>');
            //     $("#axe").append('<option value=""></option>');
            //     $(".labelcartier").replaceWith(
            //         '<label for="input_suggestion" class="labelcartier">Chercher <sup class="exposant">➀</sup>:</label>'
            //         );
            //     $(".labelAxe").replaceWith(
            //         '<label for="axe" class ="labelAxe">Axe <sup class="exposant">➃</sup>:</label>'
            //         );
            //     $(".labelheure").replaceWith(
            //         '<label for="heure" class ="labelheure">Heure <sup class="exposant">➂</sup>:</label>'
            //         );


            //     var options = <?php echo json_encode($top['listAxe']); ?>;
            //     var optionsHtml = options.map(function(axe) {
            //         return '<option value="' + axe.axe_id + '">' + axe.axe_libelle + '</option>';
            //     }).join('');

            //     $("#axe").append(optionsHtml);
            //     $(".addtransport").prop("disabled", true);

            //     var id_transportuser = $(this).data('id');

            //     $("#heure").replaceWith('<select  class="form-control" id="heure"> "'+ contenuheure +'"</select>');
            //     $("#input_suggestion").replaceWith('<input type="text"  class="form-control" id="input_suggestion">');
            //     $(this).replaceWith('<button type="button" id ="saveUpdatetransportUser" class="btn btn-success" data-id = "'+ id_transportuser +'">Modifier</button>');
            //     $("#annulertransport").replaceWith('<button type="button" class="btn btn-secondary fermer" data-bs-dismiss="modal">Fermer</button>');



                    
            //     $("#suggestions_quartier").show();
            //     $("#input_suggestion").on("input", function () 
            //     {

            //         var userInput = $(this).val().toLowerCase(); // Valeur de l'input en minuscules
            //         if (userInput == "") {
            //             $("#suggestions_select").empty();
            //         } else {
            //             console.log(userInput);
            //             $("#suggestions_select").empty(); // Vider le sélecteur avant d'ajouter de nouvelles suggestions
            //             // Filtrer les suggestions en fonction de l'input de l'utilisateur
            //             $.each(suggestions, function (index, suggestion) {
            //             if (suggestion.quartier_libelle.toLowerCase().includes(userInput)) {
            //                 $("#suggestions_select").append(
            //                 $("<option>", {
            //                     value: suggestion.quartier_id,
            //                     text: suggestion.quartier_libelle,
            //                 })
            //                 );
            //             }
            //             });
            //         }
            //     });

            // })

            // $(document).on("click", ".addtransport", function () 
            // {
            //     var quartie = $("#suggestions_select option:selected").text();


            //     var heuretransport = $("#heure").val();
            //     var axetransport = $("#axe").val();

            //     $("#heure").prop("disabled", true);
            //     $(".addtransport").replaceWith(
            //             '<button type="button" id ="modiftransportuser" class="btn btn-primary">Modifier</button>'
            //     );
            //     $("#input_suggestion").prop("disabled", true);
            //     $("#axe").prop("disabled", true);
            //     $(this).prop("disabled", true);
            //     $("#suggestions_quartier").hide();
            //     $("#btn-ctrl-transport").replaceWith(
            //     '<button type="button" id="btn-ctrl-transport" class="btn btn-sm btn-warning" ><i class="fa fa-car pr-2" ></i>Votre transport</button>'
            //     );

            //     $.ajax({
            //     url : "<?= site_url('transport/addusertranport') ?>",
            //     data: { heure: heuretransport, axe: axetransport, quartie: quartie },
            //     type: "post",
            //     success: function (resp) {
            //         if (!resp.err) {
            //             testload();
            //         }
            //     },
            //     });

            // });
            // $(document).on("click", "#saveUpdatetransportUser", function () {
            //     var quartie = $("#input_suggestion").val();
            //     var heuretransport = $("#heure").val();
            //     var axetransport = $("#axe").val();
            //     console.log(heuretransport);
            //     var id_transportuser = $(this).data('id');

            //     if (quartie.trim() === "") {
            //         alert("Le champ quartier est requis.");
            //         return;
            //     } else {
            //         var heuretransport = $("#heure").val();
            //         var axetransport = $("#axe").val();

            //         $("#heure").prop("disabled", true);
            //         $(".addtransport").replaceWith(
            //                 '<button type="button" id ="modiftransportuser" class="btn btn-primary">Modifier</button>'
            //         );
            //         $("#input_suggestion").prop("disabled", true);
            //         $("#axe").prop("disabled", true);
            //         $(this).prop("disabled", true);
            //         $("#suggestions_quartier").hide();
            //         $("#btn-ctrl-transport").replaceWith(
            //         '<button type="button" id="btn-ctrl-transport" class="btn btn-sm btn-warning" ><i class="fa fa-car pr-2" ></i>Votre transport</button>'
            //         );

            //         $.ajax({
            //         url : "<?= site_url('transport/updateusertransport') ?>",
            //         data: { heure: heuretransport, axe: axetransport, quartie: quartie, id_transportuser:id_transportuser },
            //         type: "post",
            //         success: function (resp) {
            //             if (!resp.err) {
            //                 testload();
            //             }
            //         },
            //         });
            //         }
            // });

            // $("#input_suggestion").on("input", function () 
            // {
            //     var userInput = $(this).val().toLowerCase(); // Valeur de l'input en minuscules
            //     if (userInput == "") {
            //         $("#suggestions_select").empty();
            //     } else {
            //         console.log(userInput);
            //         $("#suggestions_select").empty(); // Vider le sélecteur avant d'ajouter de nouvelles suggestions
            //         // Filtrer les suggestions en fonction de l'input de l'utilisateur
            //         $.each(suggestions, function (index, suggestion) {
            //         if (suggestion.quartier_libelle.toLowerCase().includes(userInput)) {
            //             $("#suggestions_select").append(
            //             $("<option>", {
            //                 value: suggestion.quartier_id,
            //                 text: suggestion.quartier_libelle,
            //             })
            //             );
            //         }
            //         });
            //     }
            // });

            // $("#suggestions_select").on("click", function () {
            //     // $("#heure").off("click"); // Désactiver l'événement de changement sur #heure

            //     // $("#axe").empty(); // Vider l'élément #axe

            //     var id_quartier = $(this).val();

            //     // Récupérer l'heure sélectionnée dans #heure
            //     var selected_heure = $("#heure").val();

            //     // Filtrer les axes en fonction de l'heure sélectionnée
            //     $.each(listeaxe, function (index, liste) {
            //         if (liste.axequartier_fokontany == id_quartier && liste.heuretransport_id == selected_heure) {
            //             $("#axe").append(
            //                 $("<option>", {
            //                     value: liste.axe_id,
            //                     text: liste.axe_libelle,
            //                 })
            //             );
            //         }
            //     });
            // });

            // $("#heure").on("click", function () 
            // {
                
            //             $("#axe").empty();
            //             $("#axe").append('<option value=""></option>');
            //             var options = <?php echo json_encode($top['listAxe']); ?>;

            //             var optionsHtml = options.map(function(axe) {
            //                 return '<option value="' + axe.axe_id + '">' + axe.axe_libelle + '</option>';
            //             }).join('');
            //             $("#axe").append(optionsHtml);

                    

            // })




            // Localstorage in


            /*$('#id_mcpdetailtask').on('input', function() {
                const detailtask = $('#id_mcpdetailtask').val();

                localStorage.setItem('id_mcpdetailtask', id_mcpdetailtask);
            });

            // Charger les données du localStorage quand la page se charge
            const id_mcpdetailtask = localStorage.getItem('id_mcpdetailtask');


            if (savedEmail) {
                $('#id_mcpdetailtask').val(id_mcpdetailtask);
            }*/

            function checkOngoingDetailTask()
            {
                var currentActivity = $('#currentActivityId').val();
                $('#ongoingtask').html('');
                $.ajax({
                    url : "<?= site_url('Etp/checkOngoingDetailTask') ?>",
                    dataType : 'json',
                    data : {'mcp': currentActivity},
                    type : 'post',
                    success : function(resp){
                        if(!resp.error){

                            if(resp.data){
                                $('#debut_onedetailtask').prop('disabled', true);
                                $("#fin_onedetailtask").show();
                                $('#id_mcpdetailtask').val(resp.data.mcpdetails_id);
                                $('#fin_onedetailtask').data('detailsid', resp.data.mcpdetails_id);
                                $("#detail_task").removeAttr('disabled', true);
                                $("#detail_task1").removeAttr('disabled', true);
                                $("#detail_task2").removeAttr('disabled', true);
                                $("#detail_task3").removeAttr('disabled', true);
                                $("#detail_task4").removeAttr('disabled', true);
                            }else{
                                $("#fin_onedetailtask").hide();
                                $('#debut_onedetailtask').prop('disabled', false);
                                $("#detail_task").attr('disabled', true);
                                $("#detail_task1").attr('disabled', true);
                                $("#detail_task2").attr('disabled', true);
                                $("#detail_task3").attr('disabled', true);
                                $("#detail_task4").attr('disabled', true);
                            }

                            
                            detailtaskActiviter.show();  
                            refreshControlButtons();

                        }
                    }
                })
            }


            // function setCookie(name, value, days) 
            // {
            //     var expires = "";
            //     if (days) {
            //         var date = new Date();
            //         date.setTime(date.getTime() + (days*24*60*60*1000));
            //         expires = "; expires=" + date.toUTCString();
            //     }
            //     document.cookie = name + "=" + (value || "")  + expires + "; path=/";
            // }

            // // Fonction pour récupérer un cookie
            // function getCookie(name) {
            //     var nameEQ = name + "=";
            //     var ca = document.cookie.split(';');
            //     for(var i=0;i < ca.length;i++) {
            //         var c = ca[i];
            //         while (c.charAt(0)==' ') c = c.substring(1,c.length);
            //         if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            //     }
            //     return null;
            // }

            //     // Sauvegarder les valeurs des champs d'entrée dans les cookies lors de la saisie
            // $('#id_mcpdetailtask, #actitymcp_quantite, #detail_task, #detail_task1, #detail_task2, #detail_task3, #detail_task4').on('input', function () {
            //     var id = $(this).attr('id');
            //     var value = $(this).val();
            //     setCookie(id, value, 365); // Les cookies expirent après 1 an
            // });

            // $('#isAsMontant').change(function () {
            //     var value = $(this).prop('checked');
            //     setCookie('isAsMontant', value, 365);
            // });

            // // Récupérer les valeurs des cookies et les définir dans les champs d'entrée lors du chargement de la page
            // $('#id_mcpdetailtask').val(getCookie('id_mcpdetailtask'));
            // $('#actitymcp_quantite').val(getCookie('actitymcp_quantite'));
            // $('#detail_task').val(getCookie('detail_task'));
            // $('#detail_task1').val(getCookie('detail_task1'));
            // $('#detail_task2').val(getCookie('detail_task2'));
            // $('#detail_task3').val(getCookie('detail_task3'));
            // $('#detail_task4').val(getCookie('detail_task4'));
            // $('#isAsMontant').prop('checked', getCookie('isAsMontant') === 'true');
            
        });
        

  </script>
<script>
    //console.log('<?=  $this->session->userdata('user')['id'];?>');
    //console.log('<?=  $this->session->userdata('user')['role'];?>');
    var user_id = <?=  $this->session->userdata('user')['id'];?>;
    var user_role = <?=  $this->session->userdata('user')['role'];?>;

    let hasNewMessage = false;
    let isFocused = false; // Variable pour suivre si le focus a été fait


function checkForNewMessages() { 
    $.ajax({ 
    url: '<?= site_url('message/check_new_messages') ?>',
    method: 'GET',
    data: {
        userId: user_id,
        userRole: user_role, 
    },
    success: function(data) {
        const result = JSON.parse(data);
        
        const newMessages = result.newMessages; // Nouveaux messages non lus
        const unreadMessages = result.unreadMessages; // Messages non lus

        // Vérifier si des nouveaux messages existent
        if (newMessages.length > 0) {
            hasNewMessage = true;
            updateBadge(); // Mettre à jour le badge
        }

        // Vous pouvez aussi traiter les messages non lus ici si nécessaire
        if (unreadMessages.length > 0) {
            // Traiter les messages non lus
            hasNewMessage = true;
            updateBadge();
            console.log('Messages non lus:', unreadMessages);
        }
    },
    error: function(xhr, status, error) {
        console.error('Erreur lors de la vérification des nouveaux messages :', error);
    }
});

}

function updateBadge() {
    const badge = $('#badges');
    if (hasNewMessage) {
        badge.show(); // Affiche le badge
    }
}

$('.messageButton').click(function() {
    console.log(hasNewMessage);
    hasNewMessage = false; // Réinitialise l'état
    updateBadge(); // Met à jour le badge
    // Afficher les messages ici (ex: ouvrir un modal, rediriger, etc.)
});

// Vérifie les nouveaux messages toutes les 5 secondes
setInterval(checkForNewMessages, 60000);



</script>
        