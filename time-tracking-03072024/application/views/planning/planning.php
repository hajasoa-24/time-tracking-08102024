<style type="text/css">
    table#historique th, table#historique td{
        text-align: center;
    }
    td.planningDay {
        cursor: pointer;
    }
</style>
<div class="row">
    <div class="row">
        <div class="col-md-12  title-page">
            <h3>Suivi planning - <?= ($info && $info->libelle) ? $info->libelle : '' ?></h3>
        </div>
    </div>
    
    <?php if($role != ROLE_CLIENT): ?>
    <div class="row mt-3">
        <div class="col-md-12">
            <button id="addPlanning" class="btn btn-primary mx-2"><i class="fa fa-calendar-plus-o pr-2"></i>Planifier</button>
            <!--<button id="editPlanning" class="btn btn-info mx-2"><i class="fa fa-edit pr-2"></i>Modifier un planning existant</button>-->
            <button id="deletePlanning" class="btn btn-danger mx-2"><i class="fa fa-trash pr-2"></i>Supprimer un planning</button>
            <button id="Exporterplanning" class="btn btn-success mx-2"><i class="fa fa-file-excel-o pr-2"></i>Exporter le planning</button>
            <button id="ExporterplanningGlobal" class="btn btn-primary mx-2" style="<?php echo ($role == ROLE_SUP || $role == ROLE_CADRE || $role == ROLE_CADRE2 || $role == ROLE_CLIENT || $role == ROLE_REPORTING || $role == ROLE_ADMINRH)  ? 'display: inline-block;' : 'display: none;'; ?>"><i class="fa fa-file-excel-o pr-2"></i>Exporter le planning global <span class="spinner-border m-0" style="display:none"></span></button>

        </div>
    </div>
    <?php endif; ?>

    <div class="row mt-3">
        <div class="form-group row">

            <label for="service_site" class="col-sm-2 col-form-label">Date</label>
            <div class="col-sm-3">
                <input type="date" name="filtre_debut" class="form-control dateRef" id="filtre_debut" value="<?=$filtre['debut']?>">
            </div>
            <div class="col-sm-3">
                <input type="date" name="filtre_fin" class="form-control dateDest" id="filtre_fin" value="<?=$filtre['fin']?>">
            </div>
            <div class="col-sm-2">
                <button id="doFilterPlanning" class="btn btn-primary btn-sm">Afficher</button>
            </div>
                
        </div>
    </div>
    
    <div class="row mt-3">
        <table id="myTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Contrat</th>
                    <th>Matricule</th>
                    <th>Prénom</th>
                    <th>Pseudo</th>
                    <!-- liste Jours -->
                    <?php foreach($listJours as $jour) : ?> 
                        <?php 
                            $dayClass = '';
                            if( date('N', strtotime($jour)) <= '4'){
                                $dayClass = 'first';
                            }else{
                                $dayClass = 'last';
                            } 
                        ?>
                        <th class="show-<?= $dayClass ?>" style="white-space: nowrap;"><?= date('D d-m-y', strtotime($jour)) ?></th>
                        
                    <?php endforeach; ?>
                    <!-- End liste jours -->
                </tr>
            </thead>
            <tbody>
                <!-- boucler les données par agent -->
                <?php foreach($listPlanning as $planningUser) : ?>
                <tr>
                    <th><?= $planningUser['contrat'] ?></th>
                    <th><?= $planningUser['matricule'] ?></th>
                    <th><?= $planningUser['user'] ?></th>
                    <th><?= $planningUser['pseudo'] ?></th>
                    <?php foreach($planningUser['datas'] as $day) : ?>
                        <?php //var_dump($day);
                             
                            if($day->jourSemaine <= '4')
                                $dayClass = 'first';
                            else
                                $dayClass = 'last';
                        ?>
                        <?php if(isset($day->planning_id) && !empty($day->planning_id)) : ?>
                            <?php 
                                $style = "";
                                $bgColor = '';
                                if($day->planning_hs) $bgColor = "background-color:#fff500;";
                                $style .= $bgColor;
                            ?>
                            <td class='planningDay show-<?= $dayClass ?>' 
                                        data-user="<?= $day->planning_user ?>" 
                                        data-planning="<?= $day->planning_id ?>"
                                        data-prenom="<?= $day->usr_prenom ?>"
                                        data-entree="<?= $day->planning_entree ?>"
                                        data-sortie="<?= $day->planning_sortie ?>"
                                        data-jour="<?= $day->planning_date ?>"
                                        data-off="<?= $day->planning_off ?>"
                                        style="<?= $style ?>">
                                <?= ($day->planning_off != '1') ? date('H:i', strtotime($day->planning_entree)) . ' - ' . date('H:i', strtotime($day->planning_sortie)) : 'OFF' ?> 
                                
                            </td>
                        <?php else: ?>
                            <td class='planningDay show-<?= $dayClass ?>' 
                                        data-user="<?= $day->user ?>" 
                                        data-prenom="<?= $day->prenom ?>"
                                        data-jour="<?= $day->jour ?>"
                                        style="background-color:#DC143C"></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <!-- end boucle de données par agent -->
            </tbody>
        </table>
    </div>
</div>

<!-- MODALS -->
    <div class="modal fade" id="planningmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Gérer planning" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Gérer planning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formPlanning" action="<?= site_url('planning/traiterPlanning') ?>">
                    
                        <div class="form-group row">

                            <label for="service_site" class="col-sm-2 col-form-label">Entrée</label>
                            <div class="col-sm-4">
                                <input type="time" step="any" name="planning_heureentree" class="form-control dateDest" id="planning_heureentree" >
                            </div>
                            <label for="service_site" class="col-sm-2 col-form-label">Sortie</label>
                            <div class="col-sm-4">
                                <input type="time" step="any" name="planning_heuresortie" class="form-control dateDest" id="planning_heuresortie" >  
                            </div>
                                
                        </div>

                        <div class="form-group row">

                            <label for="service_site" class="col-sm-2 col-form-label">Du</label>
                            <div class="col-sm-4">
                                <input type="date" name="planning_datedebut" min="" class="form-control" id="planning_datedebut" >
                            </div>
                            <label for="service_site" class="col-sm-2 col-form-label">Au</label>
                            <div class="col-sm-4">
                                <input type="date" name="planning_datefin" class="form-control" id="planning_datefin" >
                            </div>
                                
                        </div>
                        

                        <h6 class="mt-5">Liste des agents concernées</h6>
                        <div class="form-group row">
                            <div class=" col-sm-8 offset-sm-2">
                                <select name="liste_agent[]" multiple="true" id="liste_agent" class="form-control">
                                    <option value="">-- Choisir les agents à affecter --</option>
                                    
                                </select>
                            </div>
                        </div>

                        <h6 class="mt-5">Jours travaillés</h6>
                        <div class="form-check form-check-inline offset-sm-2">
                            <input class="form-check-input" type="checkbox" id="planningJour1" name="planningJour[]" value="1">
                            <label class="form-check-label" for="planningJour1">L</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="planningJour2" name="planningJour[]" value="2">
                            <label class="form-check-label" for="planningJour2">M</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="planningJour3" name="planningJour[]" value="3">
                            <label class="form-check-label" for="planningJour3">M</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="planningJour4" name="planningJour[]" value="4">
                            <label class="form-check-label" for="planningJour4">J</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="planningJour5" name="planningJour[]" value="5">
                            <label class="form-check-label" for="planningJour5">V</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="planningJour6" name="planningJour[]" value="6">
                            <label class="form-check-label" for="planningJour6">S</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="planningJour7" name="planningJour[]" value="7">
                            <label class="form-check-label" for="inlineCheckbox3">D</label>
                        </div>


                        <input type="hidden" name="type" id="planningType" value="<?= $type ?>">
                        <input type="hidden" name="type_id" id="planningTypeId" value="<?= $info->id ?>">
                        <input type="hidden" name="action" id="planningAction" value="">

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" id="traiterPlanning" value="valider" class="btn btn-primary">Valider</button>
                        </div>
                    
                    </form>

                </div>
            
            </div>
            
        </div>
    </div>

    <div class="modal fade" id="planningdeletemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Gérer planning" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Gérer planning</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formDeletePlanning" action="<?= site_url('planning/deletePlanning') ?>">

                        <div class="form-group row">
                            <label for="service_site" class="col-sm-2 col-form-label">Du</label>
                            <div class="col-sm-4">
                                <input type="date" name="planningdel_datedebut" min="" class="form-control" id="planningdel_datedebut" >
                            </div>
                            <label for="service_site" class="col-sm-2 col-form-label">Au</label>
                            <div class="col-sm-4">
                                <input type="date" name="planningdel_datefin" class="form-control" id="planningdel_datefin" >
                            </div>
                        </div>
                        

                        <h6 class="mt-5">Liste des agents concernées</h6>
                        <div class="form-group row">
                            <div class=" col-sm-8 offset-sm-2">
                                <select name="liste_agent_del[]" multiple="true" id="liste_agent_del" class="form-control">
                                    <option value="">-- Choisir les agents à affecter --</option>
                                    
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="deltype" id="planningDelType" value="<?= $type ?>">
                        <input type="hidden" name="deltype_id" id="planningDelTypeId" value="<?= $info->id ?>">
                        <input type="hidden" name="actiondel" id="planningDelAction" value="">

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" id="delPlanning" value="valider" class="btn btn-primary">Supprimer planning</button>
                        </div>
                    
                    </form>

                </div>
            
            </div>
            
        </div>
    </div>

    <div class="modal fade" id="planningmodalUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="Planning utilisateur" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Planning utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" id="formPlanning" action="<?= site_url('planning/traiterPlanningUser') ?>">
                        
                        <div class="form-group row">

                            <label for="service_site" class="col-sm-2 col-form-label">Utilisateur concerné</label>
                            <div class="col-sm-4">
                                <input type="text" disabled name="planninguser_prenom" min="" class="form-control" id="planninguser_prenom" >
                            </div>
                            
                        </div>
                        <div class="form-group row">

                            <label for="service_site" class="col-sm-2 col-form-label">Date</label>
                            <div class="col-sm-4">
                                <input type="date" readonly="true" name="planninguser_date" min="" class="form-control" id="planninguser_date" >
                            </div>
                            
                        </div>

                        <div class="form-group row">

                            <label for="service_site" class="col-sm-2 col-form-label">Entrée</label>
                            <div class="col-sm-4">
                                <input type="time" step="any" name="planninguser_heureentree" class="form-control " id="planninguser_heureentree" >
                            </div>
                            <label for="service_site" class="col-sm-2 col-form-label">Sortie</label>
                            <div class="col-sm-4">
                                <input type="time" step="any" name="planninguser_heuresortie" class="form-control " id="planninguser_heuresortie" >  
                            </div>
                                
                        </div>

                        <div class="form-group row">

                            <label for="service_site" class="col-sm-2 col-form-label">HS</label>
                            <div class="col-sm-2">
                                <input type="checkbox" name="planninguser_hs" value="1" class="form-check-input" id="planninguser_hs"> 
                            </div>
                            
                            
                        </div>
                        <hr>
                        <div class="form-group row">

                            <label for="service_site" class="col-sm-2 col-form-label">OFF</label>
                            <div class="col-sm-2">
                                <input type="radio" name="planninguser_off" value="1" class="form-check-input" id="planninguser_off_oui" checked> 
                                OUI
                            </div>
                            <div class="col-sm-2">
                                <input type="radio" name="planninguser_off" value="0" class="form-check-input" id="planninguser_off_non" > 
                                NON 
                            </div>
                            
                        </div>


                        <input type="hidden" name="type" id="planningType" value="<?= $type ?>">
                        <input type="hidden" name="type_id" id="planningTypeId" value="<?= $info->id ?>">
                        <input type="hidden" name="planningId" id="planningId" value="">
                        <input type="hidden" name="actionuser" id="planningUserAction" value="">
                        <input type="hidden" name="planninguser_user" id="planninguser_user" value="">

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" id="traiterPlanningUser" value="valider" class="btn btn-primary">Valider</button>
                        </div>
                    
                    </form>

                </div>
            
            </div>
            
        </div>
    </div>

    <input type="hidden" name="type" id="typeRef" value="<?= $type ?>">
    <input type="hidden" name="type_id" id="typeIdRef" value="<?= $info->id ?>">

<!-- FIN MODALS -->

<script type="text/javascript">
    var total_pause = "0";
    var showSemaine = "<?= $showSemaine ?>";
    console.log(showSemaine);
    $(document).ready(function(){
        
        //$('.'+showSemaine).hide();

        $('#addPlanning').on('click', function(){

            var type = $('#planningType').val();
            var typeId = $('#planningTypeId').val();

            $('#planningmodal input:not([type=hidden],[type=checkbox])').val('');
                        
            $('#planningmodal input[type=checkbox]').prop('checked', false);

            $('#planningAction').val('add');
            //Récupérer la liste des agents et afficher dans le select
            $.ajax({
                url : "<?= site_url('planning/getAllAgentPlanning') ?>",
                method : 'post',
                dataType : 'json',
                data : {'type' : type, 'typeId' : typeId},
                success : function(response){
                    var listAgent = '<option value="">-- Choisir les agents à affecter --</option>' 
                    if(response && response.error === false){
                        list = response.data;
                        if(Array.isArray(list)){
                            list.forEach(function(agent){
                                list += '<option value="' + agent.usr_id + '">' + agent.usr_prenom + '</option>';
                            })
                        }
                    }

                    $('#liste_agent').html(list);
                    $("#planningmodal").modal('show');
                },
                error : function(err){
                    alert("Erreur survenu! Si l'erreur persiste, contacter l'administrateur! ")
                }
            })
            
         })

        $('#deletePlanning').on('click', function(){

            var type = $('#planningDelType').val();
            var typeId = $('#planningDelTypeId').val();

            $('#planningdeletemodal input:not([type=hidden],[type=checkbox],[type=radio])').val('');
                        
            $('#planningdeletemodal input[type=checkbox]').prop('checked', false);

            $('#planningDelAction').val('del');
            //Récupérer la liste des agents et afficher dans le select
            $.ajax({
                url : "<?= site_url('planning/getAllAgentPlanning') ?>",
                method : 'post',
                dataType : 'json',
                data : {'type' : type, 'typeId' : typeId},
                success : function(response){
                    var listAgent = '<option value="">-- Choisir les agents à enlever --</option>' 
                    if(response && response.error === false){
                        list = response.data;
                        if(Array.isArray(list)){
                            list.forEach(function(agent){
                                list += '<option value="' + agent.usr_id + '">' + agent.usr_prenom + '</option>';
                            })
                        }
                    }

                    $('#liste_agent_del').html(list);
                    $("#planningdeletemodal").modal('show');
                },
                error : function(err){
                    alert("Erreur survenu! Si l'erreur persiste, contacter l'administrateur! ")
                }
            })
            
         })

        $('.planningDay').on('dblclick', function(){

            $('#planningmodalUser input:not([type=hidden],[type=radio],[type=checkbox])').val('');
            $('#planninguser_user').val('');
            $('#planningId').val('');
                        
            $('#planningmodalUser input[type=radio]').prop('checked', false);

            let prenom = $(this).data('prenom');
            let jour = $(this).data('jour');
            let entree = $(this).data('entree');
            let sortie = $(this).data('sortie');
            let planning = $(this).data('planning');
            let user = $(this).data('user');
            let isOff = $(this).data('off');

            $('#planninguser_prenom').val(prenom);
            $('#planninguser_heureentree').val(entree);
            $('#planninguser_heuresortie').val(sortie);
            $('#planninguser_date').val(jour);
            $('#planninguser_user').val(user);
            $('#planningId').val(planning);
            if(isOff == '1'){
                $('#planninguser_off_oui').prop('checked', true);
            }else{
                $('#planninguser_off_non').prop('checked', true);
            }

            $('#planningmodalUser').modal('show');
        })
        $('#doFilterPlanning').on('click', function(){
            let debut = $('#filtre_debut').val();
            let fin = $('#filtre_fin').val();
            $.ajax({
                url : "<?= site_url('planning/setFiltrePlanning') ?>",
                dataType : 'json',
                method : 'post',
                data : {debut : debut, fin : fin},
                success : function(resp){
                    
                    if(!resp.err){
                        location.reload();
                    }
                }
            })
        })

        var Campagne = '<?php echo json_encode($listcampagne);  ?>';
        var CampagneJson = JSON.parse(Campagne);
        console.log(CampagneJson);
        var Service = '<?php echo json_encode($listservice);  ?>';
        var ServiceJson = JSON.parse(Service);
        console.log(ServiceJson);


        const aujourdhui = new Date();
        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
        const dateEnString = aujourdhui.toLocaleDateString('fr-FR', options);
        console.log(dateEnString);


        function disablePage() 
        {
            $(".spinner-border").css("display", "inline-block")        
        }

        function enablePage() {
            $(".spinner-border").css("display", "none")        
        }

        $('#Exporterplanning').click(function () 
        {
            var nomService = "<?= ($info && $info->libelle) ? $info->libelle : '' ?>"

            var workbook = XLSX.utils.book_new();
                // Ajouter une feuille de calcul au classeur avec les données du tableau
            var ws = XLSX.utils.table_to_sheet($('#myTable')[0]);

                // Récupérer la plage de cellules
            var range = XLSX.utils.decode_range(ws['!ref']);

                // Appliquer l'alignement horizontal centré à chaque cellule
            for (var R = range.s.r; R <= range.e.r; ++R) {
                for (var C = range.s.c; C <= range.e.c; ++C) {
                    var cellAddress = { r: R, c: C };
                    var cellRef = XLSX.utils.encode_cell(cellAddress);
                    if (!ws[cellRef]) continue;
                    if (!ws[cellRef].s) ws[cellRef].s = {};
                    ws[cellRef].s.alignment = { horizontal: 'center' };
                }
            }
                    // Ajouter la feuille de calcul au classeur
            XLSX.utils.book_append_sheet(workbook, ws, 'Sheet1');

            // Convertir le classeur en blob
            var wbout = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
            var blob = new Blob([wbout], { type: 'application/octet-stream' });

            // Appeler la fonction saveAs pour télécharger le fichier Excel avec le nom défini
            saveAs(blob, 'Planning ' +nomService + '.xlsx');


        })

        var wb = XLSX.utils.book_new(); // Créer le Workbook en dehors de la fonction exportDivs
        var sheetCounter = 1; // Variable pour suivre le nombre de feuilles ajoutées

        function tableToExcel(table, sheetName) {
            // Convertir la table en feuille Excel
            var ws = XLSX.utils.table_to_sheet(table);
            XLSX.utils.book_append_sheet(wb, ws, sheetName);
        }

        function sanitizeSheetName(sheetName) 
        {
            // Liste des caractères interdits
            const invalidChars = /[\\\/\:\*\?\[\]\']/g;
            
            // Remplacement des caractères interdits par un tiret bas (_)
            let sanitizedSheetName = sheetName.replace(invalidChars, '_');
            
            // Tronquer le nom à 31 caractères
            if (sanitizedSheetName.length > 31) {
                sanitizedSheetName = sanitizedSheetName.substring(0, 31);
            }
            
            return sanitizedSheetName;
        }

        function exportDivs(Campagne, Service) 
        {
            disablePage()

            var promises = [];

            if (Campagne && Campagne.length > 0) {
                Campagne.map(function(campagne) {
                    if (campagne.campagne_id) {
                        var url = '<?= site_url('planning/suiviPlanning/campagne/') ?>' + campagne.campagne_id;
                        var sheetName = campagne.campagne_libelle;
                        fetchDataAndExport(url, sanitizeSheetName(sheetName));
                    }
                });
            }

            if (Service && Service.length > 0) {
                Service.map(function(service) {
                    if (service.service_id) {
                        var url = '<?= site_url('planning/suiviPlanning/service/') ?>' + service.service_id;
                        var sheetName = service.service_libelle;
                        fetchDataAndExport(url, sanitizeSheetName(sheetName));
                    }
                });
            }

            function fetchDataAndExport(url, sheetName) {
                var promise = new Promise(function(resolve, reject) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'html',
                        success: function(data) {
                            var tempContainer = $('<div>').html(data);
                            var myTableDiv = tempContainer.find('#myTable')[0];
                            tableToExcel(myTableDiv, sheetName);
                            console.log(sheetName);
                            resolve();
                        },
                        error: function(error) {
                            reject(error); 
                        }
                    });
                });

                promises.push(promise);
            }

            // Attendre que toutes les promesses soient résolues
            Promise.all(promises).then(function() {
                enablePage();

                // Écrire le workbook dans le fichier Excel après que toutes les promesses sont résolues
                setTimeout(function() {
                    XLSX.writeFile(wb, 'Planning ' +dateEnString+ '.xlsx');
                }, 500); // Attendre 100 millisecondes pour s'assurer que toutes les opérations asynchrones sont terminées
            }).catch(function(error) {
                console.error(error);
                enablePage();
            });
        }


        $('#ExporterplanningGlobal').click(function () {
            exportDivs(CampagneJson, ServiceJson);
        })

    });
    function saveAs(blob, fileName) 
    {
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = fileName;
        link.click();
    }

    

    
</script>
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>


